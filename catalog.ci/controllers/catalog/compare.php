<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Compare extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('catalog/product_model','model');
        $this->load->model('catalog/category_model','category');
        $this->load->model('catalog/attribute_model','attribute');
        $this->load->model('tool/image_model','img');
        if(!$this->session->userdata('_ck')){
            $this->session->set_userdata(array('_ck' =>array()));
        }
    }
    
    public function index($products=''){
//        echo $products; exit;
        $products = substr($products,0,  strrpos($products,'-'));
        $this->data['product_attributes']  = array();
        $products_slugs =  explode('-vs-', $products);
//        print_r($products_slugs); exit;
        $sp=array();
        foreach ($products_slugs as $slug){
            $p = $this->getProduct("$slug-prices-reviews-features-mobile");
            if($p){
                $this->data['products'][$p->product_id]=$p;
                $sp[$p->product_id] = $p->product_id;
                $this->session->set_userdata(array('_ck'=>$sp));
            }
        }
       
        $this->template->header(
                        array(
                            'title'=> ucwords(str_replace('-',' ', $products)),
                            'meta_description'=>"Product compare: $products",
                            'meta_keyword'=>$products,
                            'meta_extra'=>'',
                        )
                );
        
        $this->load->view('product/compare',$this->data);
        
        $this->template->footer();
        
    }
    
    public function getProduct($slug,$id=false){ //echo $slug;
        
        if($id){
            $product = $this->model->getByID($id);
        }else{
            $product = $this->model->getBySlug($slug);
        }
        if($product){
        $product->largethumb = $pMain = $this->img->resize($product->image,150,150);
        $pthumb = $this->img->resize($product->image,40,40);
        $plarge = HTTP_SERVER.'image/'.$product->image;
        $product_attributes = $this->model->getProductAttributes($product->product_id);
        $attrgs=$attrs =array();
        foreach($product_attributes as $attribute_group){
            $this->data['product_attributes'][$attribute_group['attribute_group_id']]['name']=$attribute_group['name'];
//            $attrgs[$attribute_group['attribute_group_id']] = $attribute_group['attribute'];
            
            foreach ($attribute_group['attribute'] as $attribute) {
                 $attrgs[$attribute_group['attribute_group_id']][$attribute['attribute_id']] = $attribute['text'];
                 $this->data['product_attributes'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
            }
            
            
        }
        $product_images = $this->model->getProductImages($product->product_id);
        $product->attributes = $attrgs;
        
        $images[] = array('image'=>$plarge,'thumb'=>$pthumb,'lthumb'=>$pMain);
        if($product_images)
        foreach($product_images as $key=>$image){
            $large = HTTP_SERVER.'image/'.$image->image;
            $lthumb = $this->img->resize($image->image,300,350);
            $thumb = $this->img->resize($image->image,40,40);
            $images[]=array('image'=>$large,'thumb'=>$thumb,'lthumb'=>$lthumb);
        }
//       print_r($images); exit;
        $product->images = $images;
        }
        return $product;
    }
    
    public function products($compare=''){
        $url = site_url($compare);
        $products = $this->db->select('products')->from('compare')->where('slugs',$url)->get();
        $items = $products->row();
        
        $items = unserialize($items->products);
        
        $sp=array();
        foreach($items as $product){ if(!$product) continue;
            $p = $this->getProduct(null,$product);
            if($p){
                $this->data['products'][$p->product_id]=$p;
                $sp[$p->product_id] = $p->product_id;
                $this->session->set_userdata(array('_ck'=>$sp));
            }
        }
       
        $this->template->header(
                        array(
                            'title'=> ucwords(str_replace('-',' ', $compare)),
                            'meta_description'=>"Product compare: $compare",
                            'meta_keyword'=>$products,
                            'meta_extra'=>'',
                        )
                );
        
        $this->load->view('product/compare',$this->data);
        
        $this->template->footer();
        
    }
    
    public function ajax(){
        switch($this->input->post('action')){
            case '_atc':
                $products = $this->session->userdata('_ck');
                if(count($products)<4){
                    $products[$this->input->post('id')] = $this->input->post('id');
                    $this->session->set_userdata(array('_ck'=>$products));
                    $json['products'] = $this->model->getCompareProducts();
                    $json['status'] = true;
                }else{
                    $json['error'] = true;
                    $json['status'] = false;
                    $json['msg'] = 'Only four products allowed to compare!';
                }
                break;
            case '_dtc':
                $products = $this->session->userdata('_ck');
                if(array_key_exists($this->input->post('id'), $products))
                    unset($products[$this->input->post('id')]);
                $this->session->set_userdata(array('_ck'=>$products));
                $json['products'] = $this->model->getCompareProducts();
                $json['status']=true;
                break;
            case '_ftc':
                $json['products'] = $this->model->getCompareProducts();
                break;
        }
        die(json_encode($json));
    }
    
   
}
?>
