<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Attribute_Model extends CI_Model{
    
    public function getByCategoryId($category_id) {
		$sql = "SELECT DISTINCT pa.attribute_id as aid, pa.text, a.`attribute_id`, ad.`name`, ag.attribute_group_id, agd.name as attribute_group_name FROM `" . DB_PREFIX . "product_attribute` pa" .
			   " LEFT JOIN " . DB_PREFIX . "attribute a ON(pa.attribute_id=a.`attribute_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_description ad ON(a.attribute_id=ad.`attribute_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_group ag ON(ag.attribute_group_id=a.`attribute_group_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON(agd.attribute_group_id=ag.`attribute_group_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "product p ON(p.product_id=pa.`product_id`) " .
			   " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(p.product_id=p2c.product_id) " .
			   " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON(p.product_id=p2s.product_id) " .
			   " WHERE  p.status = '1' AND a.filter=1 ";
		if($category_id) {
			$sql .= " AND p2c.category_id = '" . (int)$category_id . "'";
		}

		$sql .= " ORDER BY ag.sort_order, agd.name, a.sort_order, ad.name, pa.text";

		$query = $this->db->query($sql);
                
		$attributes = array();
		foreach($query->result_array() as $row) {
			if(!isset($attributes[$row['attribute_group_id']])) {
				$attributes[$row['attribute_group_id']] = array(
					'name' => $row['attribute_group_name'],
					'attribute_values' => array()
				);
			}

			if(!isset($attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']])) {
				$attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']] = array('name' => $row['name'], 'values' => array());
			}

				if (!in_array($row['text'], $attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']]['values'])){
					$attributes[$row['attribute_group_id']]['attribute_values'][$row['attribute_id']]['values'][] = $row['text'];
				}

		}
		return $attributes;
	}
        
        public function getAttributeSet($id,$atrributes){
            $sql = "SELECT GROUP_CONCAT( CONCAT( `attribute_id`,'-',`text` ) 
                    SEPARATOR  ',' ) AS text
                    FROM  `product_attribute` 
                    WHERE product_id =$id
                    AND  `attribute_id` 
                    IN ( $atrributes ) 
                    ";
            $query = $this->db->query($sql);
            return $query->row()->text;
        }
        
        public function getFlatAttribute($id,$atrributes){
            $sql = "SELECT GROUP_CONCAT( CONCAT(`text` ) 
                    SEPARATOR  ',' ) AS text
                    FROM  `product_attribute` 
                    WHERE product_id =$id
                    AND  `attribute_id` 
                    IN ( $atrributes ) 
                    ";
            $query = $this->db->query($sql);
            return $query->row()->text;
        }
        
        public function getGroupSet($product_id,$group){
            $sql = "SELECT GROUP_CONCAT( CONCAT(`text` ) 
                    SEPARATOR  ',' ) AS text
                    FROM  `product_attribute` pa, attribute a
                    WHERE product_id =$product_id AND a.attribute_id = pa.attribute_id
                    AND a.attribute_group_id='$group'
                    ";
            $query = $this->db->query($sql);
            return $query->row()->text;
        }
    
}
?>
