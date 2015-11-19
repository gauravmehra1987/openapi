<?php
class ControllerModuleEccustomercoupon extends Controller {
	public function index($setting) {
		static $module = 0;
		$this->language->load('module/eccustomercoupon');
		$data = array();
		if (!$this->customer->isLogged()) {
	  		return "";
    	} 
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
         	$data['base'] = $this->config->get('config_ssl');
	    } else {
	        $data['base'] = $this->config->get('config_url');
	    }
	    $lang_id = $this->config->get('config_language_id');
	   
	    $data['show_only_link'] = isset($setting['show_only_link'])?$setting['show_only_link']:0;
	    $data['list_coupons_link'] = $this->url->link('account/coupons', '', 'SSL');
	    $data['text_coupons'] = $this->language->get("text_coupons");
	    $data['text_view_list_coupons'] = $this->language->get("text_view_list_coupons");
	    $data['text_view_list_coupons'] = $this->language->get("text_view_list_coupons");
		$data['module'] = $module++;
		$template = "";
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/eccustomercoupon.tpl')) {
			$template = $this->config->get('config_template') . '/template/module/eccustomercoupon.tpl';
		} else {
			$template = 'default/template/module/eccustomercoupon.tpl';
		}
		return $this->load->view($template, $data);
	}
	public function listCoupons(){
		if (!$this->customer->isLogged()) {
	  		return "";
    	} 
    	$this->language->load('module/eccustomercoupon');
    	$this->load->model("eccustomercoupon/coupon");
    	$coupons = $this->model_eccustomercoupon_coupon->getCouponsByCustomer($this->customer);

    	$html = "";
    	if(!empty($coupons)){
    		$html .= '<label class="col-sm-2 control-label" for="user_coupon">'.$this->language->get("text_choose_coupon").'</label>';
    		$html .= '<div class="input-group">';
    		$html .= '<select name="user_coupon" class="form-control" id="user_coupon" onChange="loadCoupon(this)">';
    		$html .= '<option value="">'.$this->language->get("text_choose_your_coupon").'</option>';
    		foreach($coupons as $coupon){
    			$html .= '<option value="'.$coupon['code'].'">'.$coupon['name'].' ('.$coupon['code'].')</option>';
    		}
    		$html .='</select></div><br/>';
    		$html .='<script type="text/javascript">
    		function loadCoupon(obj){
    			var val = $(obj).val();
    			$("input[name=\'coupon\']").val(val);
    		}

    		</script>';
    		echo $html;
    	}
    	return $html;
	}
}
?>
