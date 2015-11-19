<?php
// This controller requires extension 'custom_option_type'
class ModelCatalogMagproduct extends Model {
	public function add($product_id, $qty = 1, $option = array(), $recurring_id = 0,$address_data){
		$product['product_id'] = (int)$product_id;

		if ($option) $product['option'] = $option;
		

		if ($recurring_id) $product['recurring_id'] = (int)$recurring_id;
		

		$key = base64_encode(serialize($product));
		$address_data['product_id'] = $product_id;
		if($key){
			if(!isset($this->session->data['product_address']))
				$this->session->data['product_address'] = array();

			$this->session->data['product_address'][$key] = $address_data;
			return $key;
		}
	}
	
	public function findProductAddress($product_id){
		if(isset($this->session->data['product_address'])){
			foreach($this->session->data['product_address'] as $item){
				if(isset($item['product_id']) && $item['product_id'] == $product_id)
					return $item;
			}
		}
		return false;
	}
	
	public function getShippingCountries() {
		$country_data = $this->cache->get('shippingcountry.status');
		
		if (!$country_data) {
			
			$shippingCountries = "223";
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND country_id IN (" . $shippingCountries . ") ORDER BY name ASC");

			$country_data = $query->rows;

			$this->cache->set('shippingcountry.status', $country_data);
		}

		return $country_data;
	}
	
	public function getMagOptions($product_id) {
		//some hardcoded values:
		$postcard_image_option_id = 14;
		
		$product_option_data = array();
		
		// Select Term option
		$mag_product = $this->cart->getMagProduct($product_id);
		
		if ( !$mag_product ) return array();
		
		
		$fields = array(
			'price_1',
			'price_2',
			'price_3',
			'price_can_1',
			'price_can_2',
			'price_can_3',
			'price_digital_1',
			'price_digital_2',
			'price_digital_3'
		);
		
		$product_option_value_data = array();
		
		foreach ($fields as $index => $field) {
		
			if ( $mag_product[$field] && $mag_product[$field] > 0 ) {
				$option_name = $this->cart->getSubscriptionOptionName($field, $mag_product['product_issues'], $mag_product[$field]);
				
				$product_option_value_data[] = array(
					'product_option_value_id' => $field,
					'option_value_id'         => 200000,
					'name'                    => $option_name,
					'image'                   => '',
					'subtract'                => '0',
					'price'                   => $mag_product[$field] - $mag_product['price_1'],
					'price_prefix'            => '+',
					'weight'                  => '',
					'weight_prefix'           => ''
				);
			}
		}
		
		$product_option_data['select_term'] = array(
			'product_option_id'    => 300000,
			'product_option_value' => $product_option_value_data,
			'option_id'            => 'select_term',
			'name'                 => 'Select Term',
			'type'                 => 'radio',
			'value'                => '',
			'required'             => 1
		);
		// End Select Term option
		
		//select postcard image option
		if ($this->config->get('config_store_id') == 1 ) {
			$postcard_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option o 
				LEFT JOIN " . DB_PREFIX . "option_value ov ON (o.option_id = ov.option_id) 
				LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
				WHERE o.option_id = '" . (int)$postcard_image_option_id . "' 
					AND ov.sort_order != 0 
					AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				ORDER BY ov.sort_order");
				
			$product_option_value_data = array();
			
			foreach ( $postcard_option_query->rows as $option_value) {
			
				$product_option_value_data[] = array(
					'product_option_value_id' => $option_value['name'],
					'option_value_id'         => $option_value['option_value_id'],
					'name'                    => $option_value['name'],
					'image'                   => $option_value['image'],
					'subtract'                => '0',
					'price'                   => 0,
					'price_prefix'            => '+',
					'weight'                  => '',
					'weight_prefix'           => ''
				);
			}
			
			$product_option_data['postcard_image'] = array(
				'product_option_id'    => 300001,
				'product_option_value' => $product_option_value_data,
				'option_id'            => 'postcard_image',
				'name'                 => 'Select Postcard Design',
				'type'                 => 'image',
				'value'                => '',
				'required'             => 0
			);
		}
		//End select postcard image option
		
		return $product_option_data;
	}
	
	public function getMagProduct($product_id) {
		
		$query = $this->db->query("SELECT mp.*, p.price FROM " . DB_PREFIX . "mag_product mp LEFT JOIN " . DB_PREFIX . "product p ON (mp.product_id = p.product_id) WHERE mp.product_id = " . $product_id);
		
		if ( $query->rows ) return $query->row; else return false;
		
	}

}