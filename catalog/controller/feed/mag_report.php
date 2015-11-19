<?php 
class ControllerFeedMagReport extends Controller {
	private $error = array(); 
        public $_log = array();
        protected $data = array();
        
        public function feeds(){
            if(!isset($this->request->get['action'])){
                die(json_encode(array('error'=>'Error 901: Sorry, Unable to serve request!')));
            }
			
            $action = $this->request->get['action'];
			
			if (isset($this->request->get['start_date']) && isset($this->request->get['end_date'])) {
				$start_date = $this->request->get['start_date'];
				$end_date = $this->request->get['end_date'];
			} else if (isset($this->request->get['start_date']) && !isset($this->request->get['end_date'])) {
				$start_date = $this->request->get['start_date'];
				$end_date = date("Y-m-d H:i:s" , strtotime($start_date . ' +1  week'));
			} else if (!isset($this->request->get['start_date']) && isset($this->request->get['end_date'])) {
				$end_date = $this->request->get['end_date'];
				$start_date = date("Y-m-d H:i:s" , strtotime($end_date . ' -1  week'));
			} else {
				$end_date = date("Y-m-d H:i:s");
				$start_date = date("Y-m-d H:i:s" , strtotime($end_date . ' -1  week'));
			}
			
            $this->load->model('feed/mag_report');
			
            switch($action){
                case 'report':
				
					//some constants; they should be in line with the same variables in one of the cart functions
					$years = array(
						'price_1' => 1,
						'price_2' => 2,
						'price_3' => 3,
						'price_can_1' => 1,
						'price_can_2' => 2,
						'price_can_3' => 3,
						'price_digital_1' => 1,
						'price_digital_2' => 2,
						'price_digital_3' => 3
					);
					$destination = array(
						'price_1' => 'USA',
						'price_2' => 'USA',
						'price_3' => 'USA',
						'price_can_1' => 'CANADA',
						'price_can_2' => 'CANADA',
						'price_can_3' => 'CANADA',
						'price_digital_1' => 'DIGITAL',
						'price_digital_2' => 'DIGITAL',
						'price_digital_3' => 'DIGITAL'
					);

					
                    $mag_report_products = $this->model_feed_mag_report->getReportValues($start_date,$end_date);
					
					$mag_report = array();
					foreach ($mag_report_products as $mag_report_product) {
					
	                    $mag_report_product_options = $this->model_feed_mag_report->getOrderOptions($mag_report_product['order_id'], $mag_report_product['order_product_id']);

						$order_status = '';
						$product_exp_date = '';
						$product_sku = '';
						$order_destination = '';
						$order_years = 0;
						$price_type = '';
//						
						foreach ($mag_report_product_options as $mag_report_product_option) {
							if ($mag_report_product_option['product_option_id'] == '10002') {
								$order_status = $mag_report_product_option['value'];
							} else if ( $mag_report_product_option['product_option_id'] == '300000' ) {
								$price_type =  $mag_report_product_option['type'];
								if ( !isset($destination[$price_type]) || !isset($years[$price_type]) ) {
									die(json_encode(array('error'=>'Unknown price type in table order_option for order_option_id: '.$mag_report_product_option['order_option_id'])));
									return;
								}
								$order_destination = $destination[$price_type];
								$order_years = $years[$price_type];
							}

						}

						$mag_report_product_zone = $this->model_feed_mag_report->getZoneCodeById($mag_report_product['payment_zone_id']);
						
						//every destination (USA, CANADA, DIGITAL) has it's own SKU, but only the one that is ordered should be displayed
						if ($order_destination == 'USA') {
							$mag_report_product['product_sku_sa_digital'] = $mag_report_product['product_sku_p1_digital'] = '';
						} elseif ($order_destination == 'DIGITAL') {
							$mag_report_product['product_sku_sa'] = $mag_report_product['product_sku_p1'] = '';
						}
						$remit = ($order_years) ? $mag_report_product['remit_'.$order_years] : 0;
						$remit_1y = round($remit/$order_years,2);
						
						$mag_report[] = array(
							'product_sku_sa'			=> $mag_report_product['product_sku_sa'],
							'product_sku_p1'			=> $mag_report_product['product_sku_p1'],
							'product_sku_sa_digital'	=> $mag_report_product['product_sku_sa_digital'],
							'product_sku_p1_digital'	=> $mag_report_product['product_sku_p1_digital'],
							'product_id'				=> $mag_report_product['product_id'],
							'order_product_id'        	=> $mag_report_product['order_product_id'],
							'name'                    	=> $mag_report_product['name'],
							'price'                   	=> $mag_report_product['price'],
							'firstname'               	=> $mag_report_product['firstname'],
							'lastname'                	=> $mag_report_product['lastname'],
							'payment_company'         	=> $mag_report_product['payment_company'],
							'comment'                 	=> $mag_report_product['comment'],
							'payment_address_1'       	=> $mag_report_product['payment_address_1'],
							'payment_city'            	=> $mag_report_product['payment_city'],
							'state'                   	=> (isset($mag_report_product_zone['code'])) ? $mag_report_product_zone['code'] : '',
							'payment_postcode'        	=> $mag_report_product['payment_postcode'],
							'yrs'                     	=> $order_years,
							'new_renew'               	=> $order_status,
							'remit'                   	=> $remit,
							'remit_1y'					=> $remit_1y,
							'order_id'                	=> $mag_report_product['order_id'],
							'email'                   	=> $mag_report_product['email']
						);
					}
						
					die(json_encode(array('mag_report'=>$mag_report)));
					break;
				default:
					die(json_encode(array('error'=>'Invalid Request')));
					break;
            }
        }
}
?>