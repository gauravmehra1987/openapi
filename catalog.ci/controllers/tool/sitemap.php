<?php
class Sitemap extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('tool/sitemap_model','model');
    }
    public function index($type = ''){
        
        $keys = explode('-',$type);
        
            if(count($keys) == 2){
                
                if($keys[1]=='mobiles.xml'){
                    $this->data['urls']=$this->model->getProducts();
                    $this->load->view('tool/sitemap',$this->data);
                }
            }elseif(count($keys) == 3){
                
//                $category = preg_replace('/.xml/','', substr($type,  strrpos($type,'-',1)+1,strlen($type)));
//                $this->data['urls']=$this->model->getProductsByCategory($category.'-mobile-phones-price-list');
//                $this->load->view('tool/sitemap',$this->data);
                
               if($keys[2]=='lp.xml'){
                   
                    $this->data['urls']=$this->model->getCategory();
//                    print_r($this->data['urls']); exit;
                    foreach ($this->data['urls'] as $key=>$item)
                        $this->data['urls'][$key]->slug = 'mobile/'.$item->slug;
                    $this->load->view('tool/sitemap',$this->data);
               }
               
               if($keys[2]=='vs.xml'){
                 
                    $this->data['urls']=$this->model->getCompare();
                    $this->load->view('tool/sitemap',$this->data);
               }
               
            }
        
    }
    
}
?>
