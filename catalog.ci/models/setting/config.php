<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Config extends CI_Model{
    
    public function getConfig($group,$store_id=0){
        $data = array(); 
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = " . (int)$store_id . " AND `code` = " . $this->db->escape($group) . "");
		
		foreach ($query->result() as $result) {
			if (!$result->serialized) {
				$data[$result->key] = $result->value;
			} else {
				$data[$result->key] = unserialize($result->value);
			}
		}

		return $data;
    }
    
    public function getSetting($group,$key,$store_id=0){
        $data = array(); 
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = " . $this->db->escape($group) . " AND `key` = " . $this->db->escape($key) . "");
		
		foreach ($query->result() as $result) {
			if (!$result->serialized) {
				$data[$result->key] = $result->value;
			} else {
				$data[$result->key] = unserialize($result->value);
			}
		}

		return $data;
    }
}
?>
