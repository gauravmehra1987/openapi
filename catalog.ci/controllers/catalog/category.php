<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Category extends CI_Controller{
    
    var $data;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('catalog/category_model','model');
        $this->load->model('catalog/attribute_model','attribute');
        $this->load->model('tool/image_model','img');
//        exit;
        
    }
    public function index($category=''){
        echo $category; exit;
    }
    
    public function view($parent='',$category=''){
        $category = $this->model->getCategory($this->model->toID($category));
        $sidx = ($this->input->post('sidx'))?$this->input->post('sidx'):'p.sort_order';
        $sord = ($this->input->post('sord'))?$this->input->post('sord'):'ASC'; 
        $start = ($this->input->post('start'))?$this->input->post('start'):0;
        $page = ($this->input->post('page'))?$this->input->post('page'):1;
        $limit = ($this->input->post('limit'))?$this->input->post('limit'):30;
                
        $products = array();
        $this->template->header(
                        array(
                            'title'=>($category->meta_title)?$category->meta_title:$category->name . ' Mobile Phone Prices India | '.$category->name.' Mobiles List',
                            'meta_description'=>$category->name." mobiles features, specification & prices in India. Compare ".$category->name." mobile phone prices features, specs and check reviews.",
                            'meta_keyword'=>$category->meta_keyword,
                            'meta_extra'=>$category->meta_extra,
                            'ogtitle' => $category->name . ' Mobile Phones',
                            'ogurl' => site_url('mobile/'.$category->slug)
                        )
                );
        
                $select = "p.product_id, p.image, p.price, pd.name, pd.slug ";
                $from = 'product p LEFT JOIN product_description pd ON p.product_id = pd.product_id LEFT JOIN product_to_category ptc ON ptc.product_id=p.product_id ';
                if($this->input->post('_a')){
                    $from .= 'LEFT JOIN product_attribute pa ON (pa.product_id=p.product_id) ';
                }
                $where = "where 1 ";
                $where .= "AND ptc.category_id = " . $category->category_id. ' ';
                $count = $this->model->count($from,$where);
                if( $count > 0 ) { $pages = ceil($count/$limit); } else { $pages = 0; }
                if ($page > $pages) $page = $pages; 
                if($count)
                    $start = $limit * $page - $limit;
                else
                    $start=0;
        
                $products=array();
                $products = $this->model->ls($select,$from,$where,$sidx,$sord,$start,$limit);
                if($products){
                    foreach($products as $key=>$product){
                        if($product->image)
                            $products[$key]->thumb = $this->img->resize($product->image,150,150);
                        else
                            $products[$key]->thumb = $this->img->resize('no_image.jpg',150,150);
                    }
                }
       
                $this->data['products'] = $products;
                $this->data['pagi'] = array();
                $this->data['category'] = $category;
                $this->load->view('category/list',$this->data);

                $this->template->footer();
    }
    
    public function detail($category){
        echo 'at category detail page ' . $category; exit;
    }
    
    public function ajax(){
        switch ($this->input->post('action')){
            case '_ls':
                $sidx = ($this->input->post('sidx'))?$this->input->post('sidx'):'p.sort_order';
                $sord = ($this->input->post('sord'))?$this->input->post('sord'):'ASC'; 
                $start = ($this->input->post('start'))?$this->input->post('start'):0;
                $page = ($this->input->post('page'))?$this->input->post('page'):1;
                $limit = ($this->input->post('limit'))?$this->input->post('limit'):30;
                $cat = ($this->input->post('_c'))?$this->input->post('_c'):0;
                $select = "p.product_id, p.image, p.price, pd.name, pd.slug ";
                $from = 'product p LEFT JOIN product_description pd ON p.product_id = pd.product_id LEFT JOIN product_to_category ptc ON ptc.product_id=p.product_id ';
                if($this->input->post('_a')){
                    $from .= 'LEFT JOIN product_attribute pa ON (pa.product_id=p.product_id) ';
                }
                $where = "where 1 ";
                
                if($cat){
                    $where .= "AND ptc.category_id = " . $cat. ' ';
                }
                
                if($this->input->post('_a')){
                    $attributes=array();
                    $attrs = $this->input->post('_a');
                    foreach($attrs as $a){
                        foreach(explode('+', $a) as $it){
                            $attributes[] = $it;
                        }
                    }
                    $cond = implode('%") OR (pa.text like "%', $attributes);
                    
                    $where .= "AND ( ";
                        $where .= ' (pa.text like "%'.$cond.'%" ';
                    $where .= ")) ";
                }
                
//                echo $where;
                $count = $this->model->count($from,$where);
//                echo $count; 
                if( $count > 0 ) { $pages = ceil($count/$limit); } else { $pages = 0; }
                if ($page > $pages) $page = $pages; 
                if($count)
                    $start = $limit * $page - $limit;
                else
                    $start=0;
        $products=array();
                $products = $this->model->ls($select,$from,$where,$sidx,$sord,$start,$limit);
                if($products){
                    foreach($products as $key=>$product){
                        if($product->image)
                            $products[$key]->thumb = $this->img->resize($product->image,150,150);
                        else
                            $products[$key]->thumb = $this->img->resize('no_image.jpg',150,150);
                    }
                }
                die(json_encode(array('p'=>$products,'pg'=>array('page'=>$page,'pages'=>$pages,'limit'=>$limit,'start'=>$start))));
                break;
        }
    }
}
?>
