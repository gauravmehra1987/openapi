<?php
class ControllerAccountSubscriptions extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/subscriptions', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/subscriptions');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/subscriptions', $url, 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_note'] = $this->language->get('text_note');

		$data['column_subscription_number'] = $this->language->get('column_subscription_number');
		$data['column_magazine'] = $this->language->get('column_magazine');
		$data['column_start_issue'] = $this->language->get('column_start_issue');
		$data['column_exp_date'] = $this->language->get('column_exp_date');
		
		$data['btn_change_address'] = $this->language->get('btn_change_address');
		$data['btn_renew'] = $this->language->get('btn_renew');
		$data['btn_give_gift'] = $this->language->get('btn_give_gift');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['order_products'] = array();

		$this->load->model('account/subscriptions');

		$order_total = $this->model_account_subscriptions->getTotalOrdersProducts();

		$results = $this->model_account_subscriptions->getOrdersProducts(($page - 1) * 10, 10);

		$this->load->model('tool/upload');
		$this->load->model('catalog/mag_product');
//file_put_contents(DIR_LOGS.'svlog_results_subscriptions.txt', print_r($results,1));

		foreach ($results as $result) {
			$option_data = array();
			$pos = '';
			$product_issue_end = '';
			$start_issue = '';
			$product_exp_date = '';

			$options = $this->model_account_subscriptions->getOrderOptions($result['order_id'], $result['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
					if (strpos($option['type'], 'price_') !== false) {
						$product_exp_date = substr($option['type'], -1);
					}
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

//				$product_info = $this->model_catalog_product->getProduct($product['product_id']);
			$mag_product_info = $this->model_catalog_mag_product->getMagProduct($result['product_id']);

//				if ($product_info) {
//					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
//				} else {
//					$reorder = '';
//				}

			$pos = strpos($mag_product_info['product_delivery'], ' ');
			$product_issue_end = substr($mag_product_info['product_delivery'], 0, $pos);
			$start_issue = strtotime($result['date_added'] . ' +' . $product_issue_end . '  week');

			$data['order_products'][] = array(
				'order_id'          => $result['order_id'],
				'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'product_id'        => $result['product_id'],
				'name'              => $result['name'],
				'model'             => $result['model'],
				'option'            => $option_data,
				'quantity'          => $result['quantity'],
				'product_delivery'  => $mag_product_info['product_delivery'],
				'start_issue'       => date($this->language->get('date_format_short'), $start_issue),
				'exp_date'          => date($this->language->get('date_format_short'), strtotime(date("Y-m-d H:i:s", $start_issue) . ' +' . $product_exp_date . '  year')),
				'product_exp_date'  => $product_exp_date,
				'product_issue_end' => $product_issue_end,
//					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
//					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
//					'reorder'  => $reorder,
				'gift_link'         => 'http://gag.sr.opencartcart.com/index.php?route=product/product&product_id=' . $result['product_id'],
				'renew_link'        => $this->url->link('product/product', 'product_id=' . $result['product_id'], 'SSL')
			);

		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/subscriptions', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/subscriptions_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/subscriptions_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/subscriptions_list.tpl', $data));
		}
	}
}