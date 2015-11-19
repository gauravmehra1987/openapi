<?php
class Home extends CI_Controller{
    
    public function index(){
        $this->template->header(
                    array('title'=>"Priceoye - Mobile Product & Price Comparison",
                      'meta_description'=>"priceoye lets you compare mobiles & mobile prices in India. Compare mobile phones to check which phone you should buy & compare its prices from various online retailers to get best mobile prices in India.")
                );
        
        $this->load->model('setting/config','setting');
        $this->load->model('tool/image_model','image');
        echo $this->router->fetch_class() . '/' . $this->router->fetch_method(); exit;
        $data = $this->setting->getSetting('featured');
        print_r($data); exit;
        $in = explode(',', $data['featured_product']);
        
        $query = $this->db->select('pd.name,p.image,pd.slug')->from('product p')->join('product_description pd','p.product_id=pd.product_id')->where_in('p.product_id',$in)->limit(10)->get();
//        print_r($query->result()); exit;
        $fproducts=array();
        foreach($query->result() as $product){
            if($product->image)
                $product->image = $this->image->resize($product->image,100,125);
            else {
                $product->image = $this->image->resize('no_image.jpg',100,125);
            }
            $fproducts[]=$product;
        }
        
        $latest = $this->db->select('pd.name,p.image,pd.slug')->from('product p')->join('product_description pd','p.product_id=pd.product_id')->order_by('p.product_id desc')->limit(10)->get();
        
        $lproducts=array();
        foreach($latest->result() as $product){
            if($product->image)
                $product->image = $this->image->resize($product->image,100,125);
            else {
                $product->image = $this->image->resize('no_image.jpg',100,125);
            }
            $lproducts[]=$product;
        }
        
        $this->data['featured_products'] = $fproducts;
        $this->data['latest_products'] = $lproducts;
        
        $data = array();
        $this->load->view('common/home',$this->data);
        $this->template->footer();
    }
    
}
?>