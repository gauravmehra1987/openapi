<?php

class ModelJournal2Checkout extends Model {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->load->model('checkout/order');
    }

    public function getTotal() {
        $total_data = array();
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

                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        return $total;
    }

    public function getPaymentMethods($country_id, $zone_id) {
        $address = array(
            'country_id'    => $country_id,
            'zone_id'       => $zone_id
        );

        $total = $this->getTotal();

        $method_data = array();

        if (Front::$IS_OC2) {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('payment');
        } else {
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('payment');
        }

        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($address, $total);

                if ($method) {
                    if ($recurring) {
                        if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                            $method_data[$result['code']] = $method;
                        }
                    } else {
                        $method_data[$result['code']] = $method;
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

//        var_dump($method_data); die();

        return $method_data;
    }

    public function getShippingMethods($country_id, $zone_id) {
        $address = array(
            'country_id'    => $country_id,
            'zone_id'       => $zone_id
        );

        $method_data = array();

        if (Front::$IS_OC2) {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('shipping');
        } else {
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('shipping');
        }

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('shipping/' . $result['code']);

                $quote = $this->{'model_shipping_' . $result['code']}->getQuote($address);

                if ($quote) {
                    $method_data[$result['code']] = array(
                        'title'      => $quote['title'],
                        'quote'      => $quote['quote'],
                        'sort_order' => $quote['sort_order'],
                        'error'      => $quote['error']
                    );
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        return $method_data;
    }

    public function createOrder() {
        if (!Journal2Utils::getProperty($this->session->data, 'order_id')) {
            $order_data = array(
                'invoice_prefix'            => $this->config->get('config_invoice_prefix'),
                'store_id'                  => $this->config->get('config_store_id'),
                'store_name'                => $this->config->get('config_name'),
                'store_url'                 => $this->config->get('config_store_id') ? $this->config->get('config_url') : HTTP_SERVER,

                'language_id'               => $this->config->get('config_language_id'),
                'currency_id'               => $this->currency->getId(),
                'currency_code'             => $this->currency->getCode(),
                'currency_value'            => $this->currency->getValue($this->currency->getCode()),
                'ip'                        => $this->request->server['REMOTE_ADDR'],
                'forwarded_ip'              => Journal2Utils::getProperty($this->request->server, 'HTTP_X_FORWARDED_FOR', Journal2Utils::getProperty($this->request->server, 'HTTP_CLIENT_IP')),
                'user_agent'                => Journal2Utils::getProperty($this->request->server, 'HTTP_USER_AGENT'),
                'accept_language'           => Journal2Utils::getProperty($this->request->server, 'HTTP_ACCEPT_LANGUAGE'),

                'customer_id'               => '',
                'customer_group_id'         => '',
                'firstname'                 => '',
                'lastname'                  => '',
                'email'                     => '',
                'telephone'                 => '',
                'fax'                       => '',

                'payment_firstname'         => '',
                'payment_lastname'          => '',
                'payment_company'           => '',
                'payment_company_id'        => '',
                'payment_address_1'         => '',
                'payment_address_2'         => '',
                'payment_city'              => '',
                'payment_postcode'          => '',
                'payment_country'           => '',
                'payment_country_id'        => '',
                'payment_tax_id'            => '',
                'payment_zone'              => '',
                'payment_zone_id'           => '',
                'payment_address_format'    => '',
                'payment_method'            => '',
                'payment_code'              => '',

                'shipping_firstname'        => '',
                'shipping_lastname'         => '',
                'shipping_company'          => '',
                'shipping_address_1'        => '',
                'shipping_address_2'        => '',
                'shipping_city'             => '',
                'shipping_postcode'         => '',
                'shipping_country'          => '',
                'shipping_country_id'       => '',
                'shipping_zone'             => '',
                'shipping_zone_id'          => '',
                'shipping_address_format'   => '',
                'shipping_method'           => '',
                'shipping_code'             => '',

                'comment'                   => '',
                'total'                     => '',

                'affiliate_id'              => '',
                'commission'                => '',
                'marketing_id'              => '',
                'tracking'                  => ''
            );

            if (!Front::$IS_OC2) {
                $order_data['products'] = array();
                $order_data['vouchers'] = array();
                $order_data['totals']   = array();
            }

            $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
        }
    }

    public function updateOrder($order_id, $data) {
        $data['customer_group_id'] = Journal2Utils::getProperty($data, 'customer_group_id');
        if (Front::$IS_OC2) {
            $this->model_checkout_order->editOrder($order_id, $data);
        } else {
            $this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

            $this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");


            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward'] . "'");

                $order_product_id = $this->db->getLastId();

                foreach ($product['option'] as $option) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
                }

                foreach ($product['download'] as $download) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
                }
            }

            foreach ($data['vouchers'] as $voucher) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");
            }

            foreach ($data['totals'] as $total) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
            }
        }
    }

    public function getOrder($order_id) {
        return $this->model_checkout_order->getOrder($order_id);
    }

}