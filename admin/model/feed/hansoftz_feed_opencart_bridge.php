<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelFeedHansoftzFeedOpencartBridge extends Model{
    
    public function getCategoryPath($category_id, $language_id = 0) {
                
        if (!$language_id) {
            $language_id = $this->config->get('config_language_id');
        }

        $query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int) $category_id . "' AND cd.language_id = '" . (int) $language_id . "' ORDER BY c.sort_order, cd.name ASC");

        if (empty($query->row)) {
            return '';
        }

        if ($query->row['parent_id']) {
            return $this->getCategoryPath($query->row['parent_id'], $language_id) . ' > ' . $query->row['name'];
        } else {
            return $query->row['name'];
        }
    }
    
    public function getCategoryPaths() {
        $language_id = 1;
        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' > ') AS name FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int) $language_id . "' AND cd2.language_id = '" . (int) $language_id . "'";
        $sql .= " GROUP BY cp.category_id ORDER BY name";
        $query = $this->db->query($sql);
        $category_path = array();
        if ($query->num_rows){
           foreach($query->rows as $cat){
               $category_path[html_entity_decode($cat['name'])] = $cat['category_id'];
           }
        }
        return $category_path;
    }
    
    public function saveCategory($category_chain) {

        if (empty($category_chain)) {
            return false;
        }

        $category_chain = $this->strip($category_chain, '>');
        $category_names = explode('>', $category_chain);
//		print_r($category_names); exit;
        $categories = array();

        $parent_id = 0;
        $category_id = 0;
        $i = 1;
        $levels = count($category_names);

        foreach ($category_names as $ck => $cv) {

            $cv = trim($cv);

            $new_category = false;
            $queries[] = $sql = "SELECT c.category_id FROM " . DB_PREFIX . "category_description cd
				INNER JOIN " . DB_PREFIX . "category c ON cd.category_id=c.category_id
				WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' AND name='" . $this->db->escape($cv) . "' AND parent_id = '$parent_id'";
            $sel = $this->db->query($sql);
            if (!$sel->num_rows) {
                $queries[] = $sql = "INSERT INTO " . DB_PREFIX . "category SET 
					parent_id = '$parent_id',
					status ='1',
					image = '',
					date_modified = NOW(), date_added = NOW()
				";
                $this->db->query($sql);
                $category_id = $this->db->getLastId();
                $is_new = true;

                if($cv){
                    
                    $queries[] = $sql = 'INSERT INTO ' . DB_PREFIX . 'category_description SET category_id="' . $category_id . '", language_id = 1, name="' . $this->db->escape($cv) . '"';
                    $this->db->query($sql);
                    
                    $queries[] = $sql = "INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . 0 . "'";
                    $this->db->query($sql);
                }else{
                    return false;
                }
                
            } else {
                $category_id = $sel->row['category_id'];
            }
            $parent_id = $category_id;

            $i++;
        }
        return array('id'=>$category_id,'path'=>$category_chain);
    }
    
    public function strip($str, $chars) {
        $str = trim($str);
        if (empty($chars)) {
            return $str;
        }
        if (!is_array($chars)) {
            $chars = array($chars);
        }
        $pat = array();
        $rep = array();
        foreach ($chars as $char) {
            $pat[] = "/(" . preg_quote($char, '/') . ")*$/";
            $rep[] = '';
            $pat[] = "/^(" . preg_quote($char, '/') . ")*/";
            $rep[] = '';
        }
        $res = preg_replace($pat, $rep, $str);
        return $res;
    }
    
    public function saveManufacurer($name) {
        if(empty($name)) return false;
        $sel = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer AS m WHERE name='" . $this->db->escape($name) . "'");
        if (empty($sel->row['manufacturer_id'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer set name = '" . $this->db->escape($name) . "'");
            $manufacturer_id = $this->db->getLastId();
            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store set manufacturer_id = '" . $manufacturer_id . "', store_id = '0'");
        } else {
            $manufacturer_id = $sel->row['manufacturer_id'];
        }
        return $manufacturer_id;
    }
    
    public function saveOption($option_name,$option_value, $product_id){
        
        $option = new stdClass();
        $option->name = $option_name;
        $option->type = "select";
        $option->option_value = $option_value;
        $option->required = 0;
      
        if(is_array($option))
            $option = (object)$option;
        
        $language_id = $this->config->get('config_language_id');
    
        
        if (!$product_id && !is_numeric($product_id))
            return false;

        $extended_types = array('select', 'radio', 'checkbox', 'image');
        $option_types = array('select', 'radio', 'checkbox', 'image', 'text', 'textarea', 'file', 'date', 'time', 'datetime');

//        $option->required = ($option->required == 'Yes' || $option->required == 'yes' || $option->required == 'Y' || $option->required == 1) ? 1 : 0;
        $is_new = false;
        $data = array();

        // validate parameters
        //
		if (!in_array($option->type, $option_types)) {
                    
                    return false;
                }

        // STAGE 1: find the option in the store
        //
        $qry = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX . "option` o INNER JOIN " . DB_PREFIX . "option_description od ON o.option_id = od.option_id WHERE language_id = '" . $language_id . "' AND  od.name='$option->name'");

        if (empty($qry->row)) {
            // if the option is NOT found
            //
			
            $this->db->query("insert into `" . DB_PREFIX . "option` set type='" . $option->type . "'");
            $option_id = $this->db->getLastId();

            $is_new = true;

            $this->db->query("insert into " . DB_PREFIX . "option_description set option_id='$option_id', language_id='" . $language_id . "', name='" . $this->db->escape($option->name) . "'");
            // repeat option request
            //
	    $qry = $this->db->query("SELECT o.option_id FROM `" . DB_PREFIX . "option` o INNER JOIN " . DB_PREFIX . "option_description od ON o.option_id = od.option_id WHERE language_id = '" . $language_id . "' AND o.type='$option->type' AND od.name='$option->name'");
        }

        //
        // STAGE 2: option found/created and we are going to assing it to a product
        //		
        $option_id = $option->option_id = $qry->row['option_id'];

        /*
          There are two option types in Opencart:
          simple   - user enters a custom value manually
          extended - options with predefined values
         */
        $extended = false;
        if (in_array($option->type, $extended_types)) {
            $extended = true;
        }

        // find product option id or insert a new one
        //
	$qry = $this->db->query("SELECT product_option_id FROM " . DB_PREFIX . "product_option WHERE product_id='$product_id' AND option_id='$option->option_id'");

        if (empty($qry->row['product_option_id'])) {
            $this->db->query("insert into " . DB_PREFIX . "product_option set product_id='" . $product_id . "',option_id='" . $option->option_id . "', required='" . $option->required . "'");
            $product_option_id = $this->db->getLastId();
        } else {
            $product_option_id = $qry->row['product_option_id'];
            $this->db->query("update " . DB_PREFIX . "product_option set required='" . $option->required . "' where product_option_id = '$product_option_id'");
        }

        if ($extended) {

            // find option value or insert a new one
            //
			$qry = $this->db->query("SELECT option_value_id FROM " . DB_PREFIX . "option_value_description WHERE 
				option_id = '" . $option_id . "'
				AND language_id = '" . $language_id . "'
				AND name='" . $this->db->escape($option->option_value) . "'");



            if (empty($qry->row['option_value_id'])) {

                $this->db->query("insert into " . DB_PREFIX . "option_value set option_id='" . $option->option_id . "'");
                $option_value_id = $this->db->getLastId();

                $this->db->query("insert into " . DB_PREFIX . "option_value_description set option_id='" . $option->option_id . "', option_value_id='" . $option_value_id . "', language_id='" . $language_id . "', name='" . $this->db->escape($option->option_value) . "'");
            } else {
                $option_value_id = $qry->row['option_value_id'];
            }

            // assign option value for product
            //
     		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_id='$product_option_id' AND option_value_id='$option_value_id'");

            $rec = array(
                'product_option_id' => $product_option_id,
                'product_id' => $product_id,
                'option_id' => $option_id,
                'option_value_id' => $option_value_id,
                'quantity' => 999,
                'subtract' => 0,
                'price' => 0,
                'price_prefix' => '',
                'points' => '',
                'points_prefix' => '',
                'weight' => '',
                'weight_prefix' => '',
            );

            $sql = "insert into " . DB_PREFIX . "product_option_value set";

            foreach ($rec as $key => $val) {
                $sql .= " " . $key . "='" . $val . "',";
            }
            $sql = trim($sql, ',');
            $this->db->query($sql);
            $product_option_value_id = $this->db->getLastId();
        } else {
            $this->db->query("update " . DB_PREFIX . "product_option set required='" . $option->required . "', value='" . $option->option_value . "' where product_option_id='$product_option_id'");
        }
        return true;
    }
    
    
    public function addProduct($data) {
//        print_r($data); exit;
        $product = $data['product'];
        if(empty($product['model']) && !isset($data['product_description'][1]))
            return false;
        
        if (isset($data['product'])) {
            $sql = 'INSERT INTO `' . DB_PREFIX . 'product` SET ';
            foreach ($data['product'] as $field => $value) { //if(!isset($this->pf[$field])) continue;
                $sql .= ' ' . $field . '="' . $this->db->escape($value) . '",';
            }
            $sql = trim($sql, ',');
             
            $this->db->query($sql);
        }
        
        $product_id = $this->db->getLastId();

        if (isset($product['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($product['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int) $product_id . "'");
        }

        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape(htmlentities($value['name'], ENT_QUOTES, "UTF-8")) . "',  meta_description = '" . $this->db->escape(htmlentities($value['meta_description'], ENT_QUOTES, "UTF-8")) . "',meta_title = '" . $this->db->escape(htmlentities($value['meta_title'], ENT_QUOTES, "UTF-8")) . "', description = '" . $this->db->escape(htmlentities($value['description'], ENT_QUOTES, "UTF-8")) . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->slugify($this->db->escape(htmlentities($value['name'], ENT_QUOTES, "UTF-8"))) . "'");
        }

        if (isset($data['product_store'])) {
            foreach ($data['product_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        
        if(isset($data['product_option'])){
            foreach($data['product_option'] as $option){
                $this->saveOption($option['name'],$option['value'],$product_id);
            }
        }

        
        if (isset($data['product_image'])) {
            foreach ($data['product_image'] as $product_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int) $product_image['sort_order'] . "'");
            }
        }

        
        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
            }
        }

        
        if (isset($data['product_profiles'])) {
            foreach ($data['product_profiles'] as $profile) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_profile` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $profile['customer_group_id'] . ", `profile_id` = " . (int) $profile['profile_id']);
            }
        }
        if(isset($data['discount'])){
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '1', priority = '1', price = '" . (float)$data['discount'] . "'");
        }
        
        $this->cache->delete('product');

        return $product_id;
    }

    public function editProduct($product_id,$data) { //print_r($data); exit;
        $queries = array();
        if (isset($data['product'])) {
            $sql = 'UPDATE `' . DB_PREFIX . 'product` SET ';
            foreach ($data['product'] as $field => $value) { //if(!isset($this->pf[$field])) continue;
                $sql .= ' ' . $field . '="' . $this->db->escape($value) . '",';
            }
            $sql = trim($sql, ',');
            $sql .= ' where product_id="' . $product_id . '"';           
            $this->db->query($sql);
        }
        if (isset($data['product_description'])){
            foreach($data['product_description'] as $language_id => $row) {
                
                $query = $this->db->query('select product_id from `' . DB_PREFIX . 'product_description` where product_id="' . $product_id . '" AND language_id="' . $language_id . '"');
                if($query->num_rows){
                    $sql = 'UPDATE `' . DB_PREFIX . 'product_description` SET ';
                    foreach ($row as $field => $value) { $sql .= ' ' . $field . '="' . $this->db->escape(htmlentities($value, ENT_QUOTES, "UTF-8")) . '",'; }
                    $sql = trim($sql, ',');
                    $sql .= ' where product_id="' . $product_id . '" AND language_id="' . $language_id . '"';
                }else{
                    $sql = 'INSERT INTO `' . DB_PREFIX . 'product_description` SET product_id="' . $product_id . '", language_id="' . $language_id . '", ';
                    foreach($row as $field => $value) { $sql .= ' ' . $field . '="' . $this->db->escape(htmlentities($value, ENT_QUOTES, "UTF-8")) . '",'; }
                    $sql = trim($sql, ',');
                }
                $queries[] = $sql;
                $this->db->query($sql);
            }
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->slugify($this->db->escape(htmlentities($data['product_description'][1]['name'], ENT_QUOTES, "UTF-8"))) . "'");
        }

        if (isset($data['product_image'])) {
            $queries[] = $sql = "DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'";
            $this->db->query($sql);
            if($data['product_image']){
                foreach ($data['product_image'] as $product_image) {
                    if($product_image['image']){
                        $queries[] = $sql = "INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int) $product_image['sort_order'] . "'";
                        $this->db->query($sql);
                    }
                }
            }
        }
        

        if (isset($data['product_category'])){
            $queries[] = $sql = "DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'";
            $this->db->query($sql);
            foreach ($data['product_category'] as $category_id) {
                $queries[] = $sql = "INSERT IGNORE INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'";
                $this->db->query($sql);
            }
        }
 
        
        if (!empty($data['product_attribute'])) {
            foreach ($data['product_attribute'] as $product_attribute) {
                if ($product_attribute['attribute_id']) {
                    foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
                        $queries[] = $sql = "DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "' AND language_id = '" . (int) $language_id . "'";
                        $this->db->query($sql);
                        if (!empty($product_attribute_description['text'])){
                            $queries[] = $sql = "INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int) $product_id . "', attribute_id = '" . (int) $product_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($product_attribute_description['text']) . "'";
                            $this->db->query($sql);
                        }
                    }
                }
            }
        }
        
        if(isset($data['product_store'])) {
             $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
	          foreach ($data['product_store'] as $store_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		  }
        }
        
        if(isset($data['product_option'])){
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
            
            foreach($data['product_option'] as $option){
                $this->saveOption($option['name'],$option['value'],$product_id);
            }
        }
    }
    
    public function product_exists($model){
        $query = $this->db->query("select product_id from " . DB_PREFIX . "product where model = '".$model."'");
        if($query->num_rows){
            return $query->row['product_id'];
        }
        return false;
    }
    
    public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		$this->cache->delete('product');

		
	}
        
        public function slugify($text){ 
            
            $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

            $text = trim($text, '-');

            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            $text = strtolower($text);
            $text = preg_replace('~[^-\w]+~', '', $text);
            if (empty($text)){
              return 'n-a';
            }
            return $text;
        }
}