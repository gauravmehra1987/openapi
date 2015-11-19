<?php
class ModelCheckoutMagOrder extends Model {
	public function updateOrderPaymentAddress($order_id, $data) {

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 

		payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', 
		payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', 
		payment_company = '" . $this->db->escape($data['payment_company']) . "', 
		payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', 
		payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', 
		payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', 
		payment_city = '" . $this->db->escape($data['payment_city']) . "', 
		payment_zone_id = '" . (int)$data['payment_zone_id'] . "', 
		payment_zone = '" . $this->db->escape($data['payment_zone']) . "', 
		payment_country = '" . $this->db->escape($data['payment_country']) . "', 
		payment_country_id = '" . (int)$data['payment_country_id'] . "', 
		payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', 
		
		date_modified = NOW() 
		
		WHERE order_id = '" . (int)$order_id . "'");

	}

}