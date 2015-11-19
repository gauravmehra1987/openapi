<?php
// This controller requires extension 'custom_option_type'
class ControllerProductMagproduct extends Controller {
	public function index($data = array()) {
			
		if($this->request->post){
			$data = $this->request->post;
		}
        $store_id = $data['store_id'] = $this->config->get('config_store_id'); 
        
        if ($store_id == 1) {
			$data['button_subscribe'] 		= 'GIVE A Gift';
			$data['button_renew'] 			= 'RENEW Gift';
        } else {
			$data['button_subscribe'] 		= 'Subscribe';
			$data['button_renew'] 			= 'Renew';
        }
        
		$data['entry_email'] 		= 'Your email';
		$data['entry_zippass'] 		= 'Your ZIP code';
		$data['entry_firstname'] 	= 'First name';
		$data['entry_lastname'] 	= 'Last name';
		$data['entry_telephone'] 	= 'Phone';
		$data['entry_company'] 		= 'Company';
		$data['entry_address_1'] 	= 'Address';
		$data['entry_address_2'] 	= 'Address 2';
		$data['entry_city'] 		= 'City';
		$data['entry_postcode'] 	= 'ZIP code/ Post code';
		$data['entry_country'] 		= 'Country';
		$data['entry_zone'] 		= 'Region/ State';
		$data['zone_id']                = '';
		
		$data['entry_select_address'] = 'Select your address or create new';

		$this->load->model('localisation/country');
		$this->load->model('catalog/mag_product');

		$data['countries'] = $this->model_catalog_mag_product->getShippingCountries();
		
		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		$data['text_signin'] 			= 'Please tell us who you are';
		$data['text_signing_in'] 		= 'Signing In...';
		$data['text_delivery_address'] 	= 'Delivery Address';
		$data['text_forgotten'] 		= 'I have Forgotten my ZIP code';
		$data['text_none'] 				= 'None';
		$data['text_select']                = '--- Please select ---';
		$data['button_signin'] = 'Continue';
		
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

		if($this->customer->isLogged()){
			$data['is_user'] = true;
			$this->load->model('account/address');
			$data['addresses'] = $this->model_account_address->getAddresses();
		}else{
			$data['addresses'] = array();
			$data['is_user'] = false;
		}
		
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$data['custom_options'] = $this->getOptions($product_id);
		
		if (isset($data['custom_options']['postcard_image'])) {
			$this->load->model('tool/image');
			foreach ($data['custom_options']['postcard_image']['product_option_value'] as &$option_value) {
				$option_value['image'] = $this->model_tool_image->resize($option_value['image'], 180, 180);
			}
		}
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/mag_custom.css');
		
		if($this->request->post){
			//this is for popup in cart page - Change Address
			$this->response->setOutput($this->load->view('default/template/product/mag_product_edit.tpl', $data));
		} else {
			return $this->load->view('default/template/product/mag_product.tpl', $data);
		}
	}
	
	public function validateAddress() {
		
		$this->load->language('account/register');
		
		$json = array();
		
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$json['error']['address_1'] = $this->language->get('error_address_1');
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$json['error']['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			$json['error']['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			$json['error']['zone'] = $this->language->get('error_zone');
		}
		
		if ( !isset($json['error'] )) { //create address for this customer
			
			$this->load->model('account/address');
			$json['address_id'] = $this->model_account_address->addAddress($this->request->post);
			$json['address_data'] = $this->model_account_address->getAddress($json['address_id']);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function changeAddress(){
	
		if($this->request->post){
			$data = $this->request->post;
			$key = trim($data['key']);
			$parts = explode("|", $data['address']);
			
			$address_id = $parts[0];
			$formatted_address = $parts[1];
			
			
			//format address 
			//$formatted_address = $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['address_2'] . ', ' . $address['city'] . ', ' . $address['country'];
			
			//replace the product in cart
			
			$quantity = $this->session->data['cart'][$key];
			
			$product = unserialize(base64_decode($key));
			
			$product_id = $product['product_id'];

			$stock = true;

			// Options
			if (!empty($product['option'])) {
				$options = $product['option'];
			} else {
				$options = array();
			}
			
			$options[10001] = $address_id . "|" . $formatted_address;
			$product['option'] = $options;
			
			$new_key = base64_encode(serialize($product));
			
			$new_cart = array();
			
			foreach ( $this->session->data['cart'] as $old_key => $old_quantity ) {
				if ($old_key == $key) {
					$new_cart[$new_key] = $quantity;
				} else {
					$new_cart[$old_key] = $old_quantity;
				}
			}
			$this->session->data['cart'] = $new_cart;
		} 
		return;
	}
	
	public function getOptions($product_id) {
		
		$this->load->model('catalog/mag_product');
		$options = $this->model_catalog_mag_product->getMagOptions($product_id);
		return $options;
		
	}
}