<?php

/**
 * @property Journal2 $journal2
 * @property ModelJournal2Checkout model_journal2_checkout
 */

class ControllerJournal2Checkout extends Controller {

    protected $data = array();

    private function renderView($template) {
        if (Front::$IS_OC2) {
            return $this->load->view($template, $this->data);
        }
        $this->template = $template;
        return parent::render();
    }

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->language('checkout/cart');
        $this->load->language('checkout/checkout');

        if (Front::$IS_OC2) {
            $this->load->language('checkout/coupon');
            $this->load->language('checkout/voucher');

            $this->load->model('account/activity');
            $this->load->model('account/custom_field');
            $this->load->model('tool/upload');
        }
        $this->load->model('account/address');
        $this->load->model('account/customer');
        $this->load->model('account/customer_group');
        $this->load->model('journal2/checkout');
        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');
    }

    public function index() {
        if (!$this->checkCart()) {
            $this->response->redirect($this->url->link('checkout/cart'));
            exit;
        }

        $this->model_journal2_checkout->createOrder();

        $this->load->language('checkout/checkout');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', 'SSL')
        );

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->document->setTitle($this->language->get('heading_title'));

        if (Front::$IS_OC2) {
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

            // Required by klarna
            if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
                $this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
            }
        } else {
            $this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
        }

        // journal checkout
        if ($this->isLoggedIn()) {
            $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            $this->data['is_logged_in'] = true;
            $this->data['payment_address'] = $this->renderAddressForm('payment');
            $this->data['shipping_address'] = $this->renderAddressForm('shipping');
        } else {
            $this->data['is_logged_in'] = false;
            $this->data['allow_guest_checkout'] = $this->allowGuestCheckout();
            $this->data['register_form'] = $this->renderRegisterForm();
        }

        // shipping
        if ($this->isShippingRequired()) {
            $this->data['is_shipping_required'] = true;
            $this->data['shipping_methods'] = $this->shipping(true);
        } else {
            $this->data['is_shipping_required'] = false;
        }

        // payment
        $this->data['payment_methods'] = $this->payment(true);

        // coupon + voucher
        $this->data['coupon_voucher'] = $this->renderCouponVoucher();

        // cart
        $this->data['cart'] = $this->cart(true);

        // checkboxes
        if (!$this->isLoggedIn() && $this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info) {
                $this->data['text_privacy'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $this->data['text_privacy'] = '';
            }
        } else {
            $this->data['text_privacy'] = '';
        }

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info) {
                $this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL'), $information_info['title'], $information_info['title']);
            } else {
                $this->data['text_agree'] = '';
            }
        } else {
            $this->data['text_agree'] = '';
        }

        $this->data['text_comments'] = $this->language->get('text_comments');
        if ($this->isLoggedIn()) {
            $this->data['entry_newsletter'] = false;
        } else {
            $this->data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
        }

        $this->data['comment'] = '';

        if (Front::$IS_OC2) {
            $this->data['column_left'] = $this->load->controller('common/column_left');
            $this->data['column_right'] = $this->load->controller('common/column_right');
            $this->data['content_top'] = $this->load->controller('common/content_top');
            $this->data['content_bottom'] = $this->load->controller('common/content_bottom');
            $this->data['footer'] = $this->load->controller('common/footer');
            $this->data['header'] = $this->load->controller('common/header');
        } else {
            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header'
            );
        }

        $this->response->setOutput($this->renderView($this->config->get('config_template') . '/template/journal2/checkout/checkout.tpl'));

    }

    public function save() {
        if ($value = Journal2Utils::getProperty($this->request->post, 'shipping_address_id')) {
            $this->session->data['shipping_address'] = $this->model_account_address->getAddress($value);
        }

        if ($value = Journal2Utils::getProperty($this->request->post, 'shipping_country_id')) {
            $this->session->data['shipping_address'] = array(
                'country_id'    => $value,
                'zone_id'       => Journal2Utils::getProperty($this->request->post, 'shipping_zone_id')
            );
        }

        if ($value = Journal2Utils::getProperty($this->request->post, 'shipping_method')) {
            $shipping = explode('.', $value);
            $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
        }

        if ($value = Journal2Utils::getProperty($this->request->post, 'payment_address_id')) {
            $this->session->data['payment_address'] = $this->model_account_address->getAddress($value);
        }

        if ($value = Journal2Utils::getProperty($this->request->post, 'payment_country_id')) {
            $this->session->data['payment_address'] = array(
                'country_id'    => $value,
                'zone_id'       => Journal2Utils::getProperty($this->request->post, 'payment_zone_id')
            );
        }

        if ($value = Journal2Utils::getProperty($this->request->post, 'payment_method')) {
            $this->session->data['payment_method'] = $this->session->data['payment_methods'][$value];
        }
    }

    public function confirm() {
        $order_id = $this->session->data['order_id'];
        $order_data = $this->model_journal2_checkout->getOrder($order_id);

        $new_payment_address = null;
        $new_shipping_address = null;

        $register_account = Journal2Utils::getProperty($this->request->post, 'account') === 'register';

        $errors = array();

        if ($this->isLoggedIn()) {
            // payment data
            if (Journal2Utils::getProperty($this->request->post, 'payment_address') === 'existing') {
                $address_info = $this->model_account_address->getAddress(Journal2Utils::getProperty($this->request->post, 'payment_address_id'));
                $order_data = array_replace($order_data, $this->getAddressData($address_info, '', 'payment_'));
            } else {
                $new_payment_address = $this->getAddressData($this->request->post, 'payment_', 'payment_');
                $order_data = array_replace($order_data, $new_payment_address);
                $errors = array_merge($errors, $this->validateAddressData($new_payment_address, 'payment_'));
            }

            // shipping data
            if ($this->isShippingRequired()) {
                if (Journal2Utils::getProperty($this->request->post, 'shipping_address') === 'existing') {
                    $address_info = $this->model_account_address->getAddress(Journal2Utils::getProperty($this->request->post, 'shipping_address_id'));
                    $order_data = array_replace($order_data, $this->getAddressData($address_info, '', 'shipping_'));
                } else {
                    $new_shipping_address = $this->getAddressData($this->request->post, 'shipping_', 'shipping_');
                    $order_data = array_replace($order_data, $new_shipping_address);
                    $errors = array_merge($errors, $this->validateAddressData($new_shipping_address, 'shipping_'));
                }
            }

            // customer data
            if (!$errors) {
                $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

                $order_data['customer_id'] = $this->customer->getId();
                $order_data['customer_group_id'] = $customer_info['customer_group_id'];
                $order_data['firstname'] = $customer_info['firstname'];
                $order_data['lastname'] = $customer_info['lastname'];
                $order_data['email'] = $customer_info['email'];
                $order_data['telephone'] = $customer_info['telephone'];
                $order_data['fax'] = $customer_info['fax'];
                if (Front::$IS_OC2) {
                    $order_data['custom_field'] = unserialize($customer_info['custom_field']);
                }
            }
        } else {
            // check firstname, lastname
            $errors = array_merge($errors, $this->validateUserData($this->request->post, $register_account));

            // check customer group id
            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $order_data['customer_group_id'] = $this->request->post['customer_group_id'];
            } else {
                $order_data['customer_group_id'] = $this->config->get('config_customer_group_id');
            }

            // check passwords if register
            if ($register_account) {
                $errors = array_merge($errors, $this->validatePassword($this->request->post));
            }

            // check payment address
            $new_payment_address = $this->getAddressData($this->request->post, 'payment_', 'payment_');
            $order_data = array_replace($order_data, $new_payment_address);
            $errors = array_merge($errors, $this->validateAddressData($new_payment_address, 'payment_', false));

            // add payment firstname and lastname
            $order_data['firstname'] = $this->request->post['firstname'];
            $order_data['lastname'] = $this->request->post['lastname'];
            $order_data['email'] = $this->request->post['email'];
            $order_data['telephone'] = $this->request->post['telephone'];
            $order_data['fax'] = $this->request->post['fax'];
            $order_data['custom_field'] = Journal2Utils::getProperty($this->request->post, 'custom_field', array());
            $order_data['payment_firstname'] = $order_data['firstname'];
            $order_data['payment_lastname'] = $order_data['lastname'];

            // check delivery address
            if ($this->isShippingRequired()) {
                if (!Journal2Utils::getProperty($this->request->post, 'shipping_address')) {
                    $new_shipping_address = $this->getAddressData($this->request->post, 'shipping_', 'shipping_');
                    $order_data = array_replace($order_data, $new_shipping_address);
                    $errors = array_merge($errors, $this->validateAddressData($new_shipping_address, 'shipping_'));
                } else {
                    $order_data = array_replace($order_data, $this->getAddressData($order_data, 'payment_', 'shipping_'));
                }
            }
        }

        // payment method
        if ($payment_method = Journal2Utils::getProperty($this->session->data, 'payment_methods.' . Journal2Utils::getProperty($this->request->post, 'payment_method') . '.title')) {
            $order_data['payment_method'] = $payment_method;
            $order_data['payment_code'] = Journal2Utils::getProperty($this->request->post, 'payment_method');
        } else {
            $errors['payment_method'] = 'no payment method';
        }

        // shipping method
        if ($this->isShippingRequired()) {
            $shipping = explode('.', Journal2Utils::getProperty($this->request->post, 'shipping_method'));
            $shipping_method = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
            if ($shipping_method) {
                $order_data['shipping_method'] = $shipping_method['title'];
                $order_data['shipping_code'] = Journal2Utils::getProperty($this->request->post, 'shipping_method');
            } else {
                $order_data['shipping_method'] = 'no shipping method';
            }
        }

        // order totals
        $order_data['totals'] = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        if (Front::$IS_OC2) {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('total');
        } else {
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('total');
        }

        $sort_order = array();

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($order_data['totals'] as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $order_data['totals']);

        $order_data['total'] = $total;

        // order products
        $order_data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id'       => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id'               => $option['option_id'],
                    'option_value_id'         => $option['option_value_id'],
                    'name'                    => $option['name'],
                    'value'                   => $option['value'],
                    'type'                    => $option['type']
                );
            }

            $order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'total'      => $product['total'],
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward']
            );
        }

        // Gift Voucher
        $order_data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $order_data['vouchers'][] = array(
                    'description'      => $voucher['description'],
                    'code'             => substr(md5(mt_rand()), 0, 10),
                    'to_name'          => $voucher['to_name'],
                    'to_email'         => $voucher['to_email'],
                    'from_name'        => $voucher['from_name'],
                    'from_email'       => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message'          => $voucher['message'],
                    'amount'           => $voucher['amount']
                );
            }
        }

        // comment + checkboxes
        $order_data['comment'] = Journal2Utils::getProperty($this->request->post, 'comment');

        if (!$this->isLoggedIn() && $this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info && !isset($this->request->post['privacy'])) {
                $errors['privacy'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }

        if ($this->config->get('config_checkout_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

            if ($information_info && !isset($this->request->post['agree'])) {
                $errors['agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }

        // update order
        $this->model_journal2_checkout->updateOrder($order_id, $order_data);

        if (!$errors) {
            if ($this->isLoggedIn()) {
                // save new payment address
                if ($new_payment_address) {
                    $this->model_account_address->addAddress($this->getAddressData($new_payment_address, 'payment_'));
                }

                // save new shipping address
                if ($new_shipping_address && $new_shipping_address !== $new_payment_address) {
                    $this->model_account_address->addAddress($this->getAddressData($new_shipping_address, 'shipping_'));
                }
            } else if ($register_account) {
                $this->registerAccount();
            } else {
                $this->session->data['guest'] = $this->getAddressData($order_data, 'payment_');
            }
        }

        // send response
        echo json_encode(array(
            'errors'    => $errors ? $errors : null,
            'order_data'=> $order_data
        ));
        exit;
    }

    public function shipping($return = false) {
        $this->data['text_shipping_method'] = $this->language->get('text_shipping_method');

        $shipping_methods = $this->model_journal2_checkout->getShippingMethods(
            Journal2Utils::getProperty($this->session->data, 'shipping_address.country_id'),
            Journal2Utils::getProperty($this->session->data, 'shipping_address.zone_id')
        );

        if (!$shipping_methods) {
            $this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        } else {
            $this->data['error_warning'] = '';
        }

        $this->session->data['shipping_methods'] = $shipping_methods;
        $this->data['shipping_methods'] = $shipping_methods;
        $this->data['code'] = '';

        $shipping_method = explode('.', Journal2Utils::getProperty($this->session->data, 'shipping_method.code'));

        if (count($shipping_method) > 1 && ($value = Journal2Utils::getProperty($shipping_methods, $shipping_method[0] . '.quote.' . $shipping_method[1]))) {
            $this->session->data['shipping_method'] = $value;
            $this->data['code'] = implode('.', $shipping_method);
        } else {
            $shipping_key = array_keys($shipping_methods);
            $shipping_key = reset($shipping_key);

            if ($shipping_key) {
                $key = $this->getFirstKey($shipping_methods[$shipping_key]['quote']);
                $this->session->data['shipping_method'] = $shipping_methods[$shipping_key]['quote'][$key];
                $this->data['code'] = $shipping_key . '.' . $key;
            } else {
                unset($this->session->data['shipping_method']);
            }
        }

        if ($return) {
            return $this->renderView($this->config->get('config_template') . '/template/journal2/checkout/shipping_methods.tpl');
        } else {
            $this->response->setOutput($this->renderView($this->config->get('config_template') . '/template/journal2/checkout/shipping_methods.tpl'));
        }
    }

    public function payment($return = false) {
        $this->data['text_payment_method'] = $this->language->get('text_payment_method');

        $payment_methods = $this->model_journal2_checkout->getPaymentMethods(
            Journal2Utils::getProperty($this->session->data, 'payment_address.country_id'),
            Journal2Utils::getProperty($this->session->data, 'payment_address.zone_id')
        );

        if (!$payment_methods) {
            $this->data['error_warning'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        } else {
            $this->data['error_warning'] = '';
        }

        $this->session->data['payment_methods'] = $payment_methods;
        $this->data['payment_methods'] = $payment_methods;
        $this->data['code'] = '';

        $payment_method = Journal2Utils::getProperty($this->session->data, 'payment_method.code');

        if ($payment_method && isset($payment_methods[$payment_method])) {
            $this->session->data['payment_method'] = $payment_methods[$payment_method];
            $this->data['code'] = $payment_method;
        } else {
            $payment_key = $this->getFirstKey($payment_methods);

            if ($payment_key) {
                $this->session->data['payment_method'] = $payment_methods[$payment_key];
                $this->data['code'] = $payment_key;
            } else {
                unset($this->session->data['payment_method']);
            }
        }

        if ($return) {
            return $this->renderView($this->config->get('config_template') . '/template/journal2/checkout/payment_methods.tpl');
        } else {
            $this->response->setOutput($this->renderView($this->config->get('config_template') . '/template/journal2/checkout/payment_methods.tpl'));
        }
    }

    public function cart($return = false) {
        $data['text_recurring_item'] = $this->language->get('text_recurring_item');
        $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');

        $this->data['button_update'] = $this->language->get('button_update');
        $this->data['button_remove'] = $this->language->get('button_remove');

        $this->data['column_image'] = $this->language->get('column_image');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_total'] = $this->language->get('column_total');


        $this->data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            if ($product['image']) {
                $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
            } else {
                $image = '';
            }

            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            $recurring = '';

            if ($product['recurring']) {
                $frequencies = array(
                    'day'        => $this->language->get('text_day'),
                    'week'       => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month'      => $this->language->get('text_month'),
                    'year'       => $this->language->get('text_year'),
                );

                if ($product['recurring']['trial']) {
                    $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                }

                if ($product['recurring']['duration']) {
                    $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                } else {
                    $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                }
            }

            $this->data['products'][] = array(
                'key'        => $product['key'],
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'thumb'      => $image,
                'model'      => $product['model'],
                'option'     => $option_data,
                'recurring'  => $recurring,
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
                'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']),
                'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
            );
        }

        // Gift Voucher
        $this->data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $this->data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'])
                );
            }
        }

        $this->data['totals'] = array();

        $order_data['totals'] = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        if (Front::$IS_OC2) {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('total');
        } else {
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('total');
        }

        $sort_order = array();

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($order_data['totals'] as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $order_data['totals']);

        foreach ($order_data['totals'] as $total) {
            $this->data['totals'][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value']),
            );
        }

        if ($value = Journal2Utils::getProperty($this->session->data, 'payment_method.code')) {
            if (Front::$IS_OC2) {
                $this->data['payment'] = $this->load->controller('payment/' . $value);
            } else {
                $this->data['payment'] = $this->getChild('payment/' . $this->session->data['payment_method']['code']);
            }
        } else {
            $this->data['payment'] = '';
        }

        if ($return) {
            return $this->renderView($this->config->get('config_template') . '/template/journal2/checkout/cart.tpl');
        } else {
            $this->response->setOutput($this->renderView($this->config->get('config_template') . '/template/journal2/checkout/cart.tpl'));
        }
    }

    public function cart_update() {
        $key = Journal2Utils::getProperty($this->request->post, 'key');
        $qty = Journal2Utils::getProperty($this->request->post, 'quantity');
        $this->cart->update($key, $qty);

        $json = array();

        if (!$this->checkCart()) {
            $json['redirect'] = $this->url->link('checkout/cart');
        } else {
            $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($this->model_journal2_checkout->getTotal()));
        }

        echo json_encode($json);
        exit;
    }

    public function cart_delete() {
        $this->cart->remove(Journal2Utils::getProperty($this->request->post, 'key'));

        $json = array();

        if (!$this->checkCart()) {
            $json['redirect'] = $this->url->link('checkout/cart');
        } else {
            $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($this->model_journal2_checkout->getTotal()));
        }

        echo json_encode($json);
        exit;
    }

    public function coupon() {
        $json = array();

        $this->load->model('checkout/coupon');

        if (isset($this->request->post['coupon'])) {
            $coupon = $this->request->post['coupon'];
        } else {
            $coupon = '';
        }

        $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);

        if (empty($this->request->post['coupon'])) {
            $json['error'] = $this->language->get('error_coupon');
        } elseif ($coupon_info) {
            $this->session->data['coupon'] = $this->request->post['coupon'];

            $this->session->data['success'] = $this->language->get('text_success');

            $json['redirect'] = $this->url->link('checkout/cart');
        } else {
            $json['error'] = $this->language->get('error_coupon');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function voucher() {
        $json = array();

        $this->load->model('checkout/voucher');

        if (isset($this->request->post['voucher'])) {
            $voucher = $this->request->post['voucher'];
        } else {
            $voucher = '';
        }

        $voucher_info = $this->model_checkout_voucher->getVoucher($voucher);

        if (empty($this->request->post['voucher'])) {
            $json['error'] = $this->language->get('error_voucher');
        } elseif ($voucher_info) {
            $this->session->data['voucher'] = $this->request->post['voucher'];

            $this->session->data['success'] = $this->language->get('text_success');

            $json['redirect'] = $this->url->link('checkout/cart');
        } else {
            $json['error'] = $this->language->get('error_voucher');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function checkCart() {
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            return false;
        }

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                return false;
            }
        }

        return true;
    }

    private function isShippingRequired() {
        return $this->cart->hasShipping();
    }

    private function isLoggedIn() {
        return $this->customer->isLogged();
    }

    private function allowGuestCheckout() {
        return $this->config->get(Front::$IS_OC2 ? 'config_checkout_guest' : 'config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload();
    }

    private function getFirstKey($array) {
        $key = array_keys($array);
        $key = reset($key);
        return $key;
    }

    private function renderAddressForm($type, $name = true) {
        $this->data['type'] = $type;
        $this->data['name'] = $name;

        $this->data['text_address_existing'] = $this->language->get('text_address_existing');
        $this->data['text_address_new'] = $this->language->get('text_address_new');
        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['text_none'] = $this->language->get('text_none');

        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_company'] = $this->language->get('entry_company');
        $this->data['entry_address_1'] = $this->language->get('entry_address_1');
        $this->data['entry_address_2'] = $this->language->get('entry_address_2');
        $this->data['entry_postcode'] = $this->language->get('entry_postcode');
        $this->data['entry_city'] = $this->language->get('entry_city');
        $this->data['entry_country'] = $this->language->get('entry_country');
        $this->data['entry_zone'] = $this->language->get('entry_zone');

        if (Front::$IS_OC2) {
            $this->data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
        } else {
            $this->data['custom_fields'] = array();
        }
        $this->data['addresses'] = $this->model_account_address->getAddresses();
        $this->data['countries'] = $this->model_localisation_country->getCountries();

        $this->data['address_id'] = Journal2Utils::getProperty($this->session->data, $type . '_address.address_id');
        $this->data['country_id'] = Journal2Utils::getProperty($this->session->data, $type . '_address.country_id', $this->config->get('config_country_id'));
        $this->data['zone_id'] = Journal2Utils::getProperty($this->session->data, $type . '_address.zone_id', $this->config->get('config_zone_id'));

        return $this->renderView($this->config->get('config_template') . '/template/journal2/checkout/address_form.tpl');
    }

    private function renderRegisterForm() {
        $this->data['text_register'] = $this->language->get('text_register');
        $this->data['text_guest'] = $this->language->get('text_guest');
        $this->data['entry_email'] = $this->language->get('entry_email');
        $this->data['entry_password'] = $this->language->get('entry_password');
        $this->data['text_forgotten'] = $this->language->get('text_forgotten');
        $this->data['text_loading'] = $this->language->get('text_loading');
        $this->data['button_login'] = $this->language->get('button_login');
        $this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $this->data['text_returning_customer'] = $this->language->get('text_returning_customer');

        $this->data['text_your_details'] = $this->language->get('text_your_details');
        $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $this->data['entry_firstname'] = $this->language->get('entry_firstname');
        $this->data['entry_lastname'] = $this->language->get('entry_lastname');
        $this->data['entry_telephone'] = $this->language->get('entry_telephone');
        $this->data['entry_fax'] = $this->language->get('entry_fax');
        $this->data['text_your_password'] = $this->language->get('text_your_password');
        $this->data['entry_confirm'] = $this->language->get('entry_confirm');
        $this->data['text_your_address'] = $this->language->get('text_your_address');
        $this->data['entry_shipping'] = $this->language->get('entry_shipping');

        $this->data['customer_groups'] = array();
        $this->data['custom_fields'] = array();

        $this->data['payment_address'] = $this->renderAddressForm('payment', false);
        $this->data['shipping_address'] = $this->renderAddressForm('shipping');
        $this->data['is_shipping_required'] = $this->isShippingRequired();

        $this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

        return $this->renderView($this->config->get('config_template') . '/template/journal2/checkout/register.tpl');
    }

    private function renderCouponVoucher() {
        $this->data['text_loading'] = $this->language->get('text_loading');

        $this->data['coupon_status'] = $this->config->get('coupon_status');
        $this->data['entry_coupon'] = $this->language->get('entry_coupon');
        $this->data['button_coupon'] = $this->language->get('button_coupon');
        $this->data['coupon'] = Journal2Utils::getProperty($this->session->data, 'coupon');

        $this->data['voucher_status'] = $this->config->get('voucher_status');
        $this->data['entry_voucher'] = $this->language->get('entry_voucher');
        $this->data['button_voucher'] = $this->language->get('button_voucher');
        $this->data['voucher'] = Journal2Utils::getProperty($this->session->data, 'voucher');
        return $this->renderView($this->config->get('config_template') . '/template/journal2/checkout/coupon_voucher.tpl');
    }

    private function getAddressData($array, $key = '', $prefix = '') {
        $keys = array(
            'address_1',
            'address_2',
            'address_id',
            'address_format',
            'city',
            'company',
            'company_id',
            'country',
            'country_id',
            'firstname',
            'lastname',
            'method',
            'postcode',
            'tax_id',
            'zone',
            'zone_id'
        );

        $result = array();

        foreach ($keys as $k) {
            $result[$prefix . $k] = Journal2Utils::getProperty($array, $key . $k, '');
        }

        if (!$result[$prefix . 'country'] && $result[$prefix . 'country_id']) {
            $country_info = $this->model_localisation_country->getCountry($result[$prefix . 'country_id']);
            if ($country_info) {
                $result[$prefix . 'country'] = $country_info['name'];
            }
        }

        if (!$result[$prefix . 'zone'] && $result[$prefix . 'zone_id']) {
            $zone_info = $this->model_localisation_zone->getZone($result[$prefix . 'zone_id']);
            if ($zone_info) {
                $result[$prefix . 'zone'] = $zone_info['name'];
            }
        }

        return $result;
    }

    private function validateUserData($data, $register) {
        $errors = array();

        // firstname
        if ((utf8_strlen(trim($data['firstname'])) < 1) || (utf8_strlen(trim($data['firstname'])) > 32)) {
            $errors['firstname'] = $this->language->get('error_firstname');
        }

        // lastname
        if ((utf8_strlen(trim($data['lastname'])) < 1) || (utf8_strlen(trim($data['lastname'])) > 32)) {
            $errors['lastname'] = $this->language->get('error_lastname');
        }

        // email
        if ((utf8_strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $data['email'])) {
            $errors['email'] = $this->language->get('error_email');
        } else if ($register && $this->model_account_customer->getTotalCustomersByEmail($data['email'])) {
            $errors['email'] = $this->language->get('error_exists');
        }

        // telephone
        if ((utf8_strlen($data['telephone']) < 3) || (utf8_strlen($data['telephone']) > 32)) {
            $errors['telephone'] = $this->language->get('error_telephone');
        }

        // Custom field validation
        if (Front::$IS_OC2) {
            $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

            foreach ($custom_fields as $custom_field) {
                if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($data['custom_field'][$custom_field['custom_field_id']])) {
                    $errors['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }
        }

        return $errors;
    }

    private function validatePassword($data) {
        $errors = array();

        if ((utf8_strlen($data['password']) < 4) || (utf8_strlen($data['password']) > 20)) {
            $errors['password'] = $this->language->get('error_password');
        }

        if ($data['confirm'] != $data['password']) {
            $errors['confirm'] = $this->language->get('error_confirm');
        }

        return $errors;
    }

    private function validateAddressData($data, $key, $name = true) {
        $errors = array();

        if ($name) {
            // firstname
            if ((utf8_strlen(trim($data[$key . 'firstname'])) < 1) || (utf8_strlen(trim($data[$key . 'firstname'])) > 32)) {
                $errors[$key . 'firstname'] = $this->language->get('error_firstname');
            }

            // lastname
            if ((utf8_strlen(trim($data[$key . 'lastname'])) < 1) || (utf8_strlen(trim($data[$key . 'lastname'])) > 32)) {
                $errors[$key . 'lastname'] = $this->language->get('error_lastname');
            }

            // Custom field validation
            if (Front::$IS_OC2) {
                $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
                foreach ($custom_fields as $custom_field) {
                    if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($data[$key . 'custom_field'][$custom_field['custom_field_id']])) {
                        $errors[$key . 'custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                    }
                }
            }

        }

        if ((utf8_strlen(trim($data[$key . 'address_1'])) < 3) || (utf8_strlen(trim($data[$key . 'address_1'])) > 128)) {
            $errors[$key . 'address_1'] = $this->language->get('error_address_1');
        }

        if ((utf8_strlen($data[$key . 'city']) < 2) || (utf8_strlen($data[$key . 'city']) > 32)) {
            $errors[$key . 'city'] = $this->language->get('error_city');
        }

        $country_info = $this->model_localisation_country->getCountry($data[$key . 'country_id']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($data[$key . 'postcode'])) < 2 || utf8_strlen(trim($data[$key . 'postcode'])) > 10)) {
            $errors[$key . 'postcode'] = $this->language->get('error_postcode');
        }

        if ($data[$key . 'country_id'] == '') {
            $errors[$key . 'country'] = $this->language->get('error_country');
        }

        if (!isset($data[$key . 'zone_id']) || $data[$key . 'zone_id'] == '') {
            $errors[$key . 'zone'] = $this->language->get('error_zone');
        }

        return $errors;
    }

    private function registerAccount() {
        $data = $this->getAddressData($this->request->post, 'payment_');

        $data = array_merge($data, array(
            'firstname'     => Journal2Utils::getProperty($this->request->post, 'firstname'),
            'lastname'      => Journal2Utils::getProperty($this->request->post, 'lastname'),
            'customer_group_id'=> Journal2Utils::getProperty($this->request->post, 'customer_group_id', $this->config->get('config_customer_group_id')),
            'email'         => Journal2Utils::getProperty($this->request->post, 'email'),
            'telephone'     => Journal2Utils::getProperty($this->request->post, 'telephone'),
            'fax'           => Journal2Utils::getProperty($this->request->post, 'fax'),
            'password'      => Journal2Utils::getProperty($this->request->post, 'password')
        ));

        $customer_id = $this->model_account_customer->addCustomer($data);

        // Clear any previous login attempts for unregistered accounts.
        if (Front::$IS_OC2) {
            $this->model_account_customer->deleteLoginAttempts($data['email']);
        }

        $this->customer->login($data['email'], $data['password']);

        if (Journal2Utils::getProperty($this->request->post, 'shipping_address') != '1') {
            $this->model_account_address->addAddress($this->getAddressData($this->request->post, 'shipping_'));
        }

        // Add to activity log
        $activity_data = array(
            'customer_id' => $customer_id,
            'name'        => $data['firstname'] . ' ' . $data['lastname']
        );

        if (Front::$IS_OC2) {
            $this->model_account_activity->addActivity('register', $activity_data);
        }
    }

}