<?php
class ModelFeedMagReport extends Model {
	public function getReportValues($start_date,$end_date) {
		$query = $this->db->query("
			SELECT 
				mp.product_sku_sa, 
				mp.product_sku_p1, 
				mp.product_sku_sa_digital, 
				mp.product_sku_p1_digital, 
				mp.remit_1, 
				mp.remit_2, 
				mp.remit_3, 
				op.product_id, 
				op.order_product_id, 
				op.name, op.price, 
				o.firstname, 
				o.lastname, 
				o.payment_company, 
				o.comment, 
				o.payment_address_1, 
				o.payment_city, 
				o.payment_postcode, 
				o.payment_zone_id, 
				o.order_id, 
				o.email 
			FROM " . DB_PREFIX . "order_product op 
			LEFT JOIN " . DB_PREFIX . "order o ON (op.order_id = o.order_id) 
			LEFT JOIN " . DB_PREFIX . "mag_product mp ON (op.product_id = mp.product_id) 
			WHERE o.store_id = '" . (int)$this->config->get('config_store_id') . "' 
				AND o.date_added >= '" . $start_date . "' 
				AND o.date_added <= '" . $end_date . "' 
				AND o.order_status_id != 0
			ORDER BY o.order_id DESC, op.order_product_id ASC
		");

		return $query->rows;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function getZoneCodeById($zone_id) {
		$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row;
	}
}