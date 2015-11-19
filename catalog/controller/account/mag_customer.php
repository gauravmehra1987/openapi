<?php
class ControllerAccountMagCustomer extends Controller {

	public function signin() {
		
		$error_zippass = "ZIP code has to be between 4 and 10 digits";
		$error_invalid_zip = "Welcome back! The ZIP code you entered is not the same ZIP code you had previously used. Please check and try again.";
		
		$json = array();
		
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}
		if ( (utf8_strlen($this->request->post['zippass']) > 10) || (utf8_strlen($this->request->post['zippass']) < 4) ) {
			$json['error']['zippass'] = $error_zippass;
		}
		
		if (!$json) { //meaning there is no errors
			
			$this->load->model('account/customer');
			
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
			if ($customer_info) { //customer exists
				
				$this->load->model('account/mag_customer');
				
				$mag_customer_info = $this->model_account_mag_customer->getMagCustomer($customer_info['customer_id']);
				
				if ( $mag_customer_info && ( utf8_strtolower($mag_customer_info['zippass']) == utf8_strtolower($this->request->post['zippass']) ) ) {
					
					//ZIP code validated successfully
					
					$this->customer->login($customer_info['email'], '', true);
					
					$json['customer'] = $customer_info;
				
				} else { //ZIP code not correct
					$json['error']['zippass'] = $error_invalid_zip;
				}
			} else { //customer does not exist, creating
				$this->load->model('account/mag_customer');
				$this->model_account_mag_customer->createCustomerFromEmail($this->request->post['email'],$this->request->post['zippass']);
				$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
				$this->customer->login($customer_info['email'], '', true);
				$json['customer'] = $customer_info;
			}
			
			// Totals /////////////////////////////////////// 
			//(code from catalog/controller/checkout/cart.php
			$this->load->language('checkout/cart');
			$this->load->model('extension/extension');

			$total_data = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();

			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('total');

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

				$sort_order = array();

				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);
			}

			$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			
			//end totals ///////////////////////////////
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getAddresses(){
		
		$this->load->model('account/address');
		
		$addresses = $this->model_account_address->getAddresses();
		$default = '';
		if($this->customer->getAddressId()){
			$default = $this->customer->getAddressId();
		}else if($addresses){
			$default_add = end($addresses);
			$default  = $default_add['address_id'];
		}
		$json = array(
                    'addresses' => $addresses,
                    'default_address' => $default
		);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}