<?php 
class ControllerAccountCoupons extends Controller { 
	public function index() {
		$data = array();
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/coupons', '', 'SSL');
	  
	  		$this->response->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
	
		$this->language->load('account/account');
		$this->language->load('module/eccustomercoupon');

		$this->document->setTitle($this->language->get('heading_title'));

      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 
      	$data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
      	$data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_coupons'),
			'href'      => $this->url->link('account/coupon', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		$data['button_back'] = $this->language->get('button_back');
		$data['back'] = $this->url->link('account/account', '', 'SSL');
		$this->load->model("eccustomercoupon/coupon");
		$this->model_eccustomercoupon_coupon->installECModule();
		
		$data['coupons'] = $this->model_eccustomercoupon_coupon->getCouponsByCustomer($this->customer, true);
		if (isset($this->session->data['success'])) {
    		$data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['error_warning'] = '';
    	$data['heading_title'] = $this->language->get('heading_title');
    	$data['text_customer_coupons'] = $this->language->get('text_customer_coupons');
    	$data['text_coupon_code'] = $this->language->get('text_coupon_code');
    	$data['text_coupon_name'] = $this->language->get('text_coupon_name');
    	$data['text_date_end'] = $this->language->get('text_date_end');
    	$data['text_date_start'] = $this->language->get('text_date_start');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/coupons.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/coupons.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/coupons.tpl', $data));
		}
  	}
}
?>