<?php
class ModelEccustomercouponCoupon extends Model {
	public function getCouponsByCustomer($customer = null, $view_expire = false) {
		$customer = !empty($customer)?$customer:$this->customer;
		$customer_group_id = $customer->getGroupId();
		$customer_id = $customer->getId();
		$coupon_list = array();

		if(!empty($customer_id)){
			$customer_query = $this->db->query("SELECT coupon_id FROM `" . DB_PREFIX . "coupon_customer` WHERE customer_id=".(int)$customer_id);
			if($customer_query->num_rows > 0){
				foreach($customer_query->rows as $item){
					$coupon_list[] = $item["coupon_id"];
				}
			}
		}
		if(!empty($customer_group_id)){
			$customer_group_query = $this->db->query("SELECT coupon_id FROM `" . DB_PREFIX . "coupon_customer_group` WHERE customer_group_id=".(int)$customer_group_id);
			if($customer_group_query->num_rows > 0){
				foreach($customer_group_query->rows as $item){
					if(!in_array($item["coupon_id"], $coupon_list)){
						$coupon_list[] = $item["coupon_id"];
					}
					
				}
			}
		}
		if(!empty($coupon_list)){
			if($view_expire) {
				$coupon_query = $this->db->query("SELECT c.* FROM `" . DB_PREFIX . "coupon` AS c
				 WHERE c.status = '1' AND c.coupon_id IN (".implode(",",$coupon_list).")");
			} else {
				$coupon_query = $this->db->query("SELECT c.* FROM `" . DB_PREFIX . "coupon` AS c
				 WHERE ((c.date_start = '0000-00-00' OR c.date_start < NOW()) AND (c.date_end = '0000-00-00' OR c.date_end > NOW())) AND c.status = '1' AND c.coupon_id IN (".implode(",",$coupon_list).")");
			}
			
			if ($coupon_query->num_rows > 0) {
				return $coupon_query->rows;
			}
		}
		return false;
	}
	public function installECModule() {
		$sql = " SHOW TABLES LIKE '".DB_PREFIX."coupon_customer'";
		$query = $this->db->query( $sql );
		if( count($query->rows) <=0 )
			$this->createECTables();
	}
	protected function createECTables(){
		$sql = array();

		$sql[] = "
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."coupon_customer` (
				  `coupon_id` int(11) NOT NULL DEFAULT '0',
				  `customer_id` int(11) NOT NULL DEFAULT '0',
				  `no_of_product` int(11) NOT NULL DEFAULT '0',
				  `year_of_subscription` int(11) NOT NULL DEFAULT '0',
				  `margin` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`coupon_id`,`customer_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
		";
		$sql[] = "
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."coupon_customer_group` (
				  `coupon_id` int(11) NOT NULL DEFAULT '0',
				  `customer_group_id` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`coupon_id`,`customer_group_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

		";

		$sql[] = "
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mag_coupon` (
				  `coupon_id` int(11) NOT NULL DEFAULT '0',
				  `no_of_product` int(11) NOT NULL DEFAULT '0',
				  `year_of_subscription` int(11) NOT NULL DEFAULT '0',
				  `margin` int(11) NOT NULL DEFAULT '0',				  
				  PRIMARY KEY (`coupon_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
		";
				
		foreach( $sql as $q ){
			$query = $this->db->query( $q );
		}
		
	}
}