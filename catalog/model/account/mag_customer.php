<?php
class ModelAccountMagCustomer extends Model {
    
	public function getMagCustomer($customer_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mag_customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}
	
	public function getMagCustomerZippass($email) {
		$email = $this->db->escape(utf8_strtolower($email));
		
		$query = $this->db->query("
			SELECT mc.zippass FROM " . DB_PREFIX . "customer c
			LEFT JOIN " . DB_PREFIX . "mag_customer mc
			ON (c.customer_id=mc.customer_id)
			WHERE LOWER(c.email) = '" . $this->db->escape(utf8_strtolower($email)) . "'"
		);
		
		if ( $query->row ) {
			return $query->row['zippass'];
		} else return false;
	}
	
	public function createCustomerFromEmail($email,$zipcode){
		
		$customer_group_id = $this->config->get('config_customer_group_id');
		
		$this->load->model('account/customer_group');
		
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		
		$first_name = explode('@',$email);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($first_name[0]) . "', email = '" . $this->db->escape($email) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "',  ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
		
		$customer_id = $this->db->getLastId();
		
		$this->saveCustomerMeta(array('email'=>$email,'zippass'=>$zipcode), $customer_id);
	
	}
	
	public function saveCustomerMeta($data,$customer_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "mag_customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "mag_customer SET customer_id = '" . (int)$customer_id . "', zippass = '".$data['zippass']."'");
	}
	
}
?>