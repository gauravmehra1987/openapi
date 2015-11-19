<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Product extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('catalog/product_model','model');
        $this->load->model('catalog/category_model','category');
        $this->load->model('catalog/attribute_model','attribute');
        $this->load->model('tool/image_model','img');
    }
    public function view($slug=''){
//         $this->output->cache(1200);
//        echo $slug; exit;
        
        $this->data['is_user'] = $this->acl->is_user();
        if($this->acl->is_user())
            $this->data['user'] = $this->acl->user;
        $product = $this->model->getBySlug($slug);
        $this->data['cat'] = $this->model->getCategory($product->product_id);
        
//        print_r($this->data['cat']); exit;
        if(empty($product->auto_meta))
            $product->auto_meta = $this->generateMetaDesc($product);
       
        $product->largethumb = $pMain = $this->img->resize($product->image,300,350);
        $pthumb = $this->img->resize($product->image,80,80);
        $plarge = HTTP_SERVER.'image/'.$product->image;
        $product_attributes = $this->model->getProductAttributes($product->product_id);
        
        $product_images = $this->model->getProductImages($product->product_id);
        $product->attributes = $product_attributes;
        $images[] = array('image'=>$plarge,'thumb'=>$pthumb,'lthumb'=>$pMain);
        if($product_images)
        foreach($product_images as $key=>$image){
            $large = HTTP_SERVER.'image/'.$image->image;
            $lthumb = $this->img->resize($image->image,300,350);
            $thumb = $this->img->resize($image->image,80,80);
            $images[]=array('image'=>$large,'thumb'=>$thumb,'lthumb'=>$lthumb);
        }
//       print_r($images); exit;
        $product->images = $images;
        $this->data['rating'] = $this->model->averageStarRating($product->product_id);
        $this->template->header(
                        array(
                            'title'=>($product->meta_title)?$product->meta_title:$product->name . ' Features, Specifications, Prices & Reviews | priceoye',
                            'meta_description'=>$product->name . ' prices, features, reviews, specifications, images, specs & all the details of '.$product->name,
                            'meta_keyword'=>$product->meta_keyword,
                            'meta_extra'=>$product->meta_extra,
                            'ogtitle' => $product->name,
                            'ogurl' => site_url($product->slug),
                            'ogimage'=>$pMain
                        )
                );
        $default_ratings = array('1 star'=>0,'2 star'=>0,'3 star'=>0,'4 star'=>0,'5 star'=>0);
        $ratings = $this->model->getStarRating($product->product_id);
        $this->data['total_reviews'] = $countRate = $this->model->getReviewCount($product->product_id);
        
        $reviews = array();
        foreach($ratings as $rating){
            $reviews["$rating->star star"] = array('star'=>$rating->star,'count'=>$rating->count,'average'=>$rating->count/$countRate*100);
        }

        $merged_reviews = parse_args($reviews,$default_ratings);
        
        $this->data['review_stat'] = $merged_reviews;
        $this->data['product']=$product;
        
        $this->data['similarScreen'] = $this->model->similarFeatures($product->product_id,$this->attribute->getFlatAttribute($product->product_id,"87"));
        if($this->data['similarScreen']){
            foreach($this->data['similarScreen'] as $key=>$item){
                if($item->image)
                    $this->data['similarScreen'][$key]->image = $this->img->resize($item->image,150,150);
                else 
                    $this->data['similarScreen'][$key]->image = $this->img->resize('no_image.jpg',150,150);
            }
        }
        
        $this->data['similarOs'] = $this->model->similarFeatures($product->product_id,$this->attribute->getFlatAttribute($product->product_id,"13"));
        if($this->data['similarOs']){
            foreach($this->data['similarOs'] as $key=>$item){
                if($item->image)
                    $this->data['similarOs'][$key]->image = $this->img->resize($item->image,150,150);
                else 
                    $this->data['similarOs'][$key]->image = $this->img->resize('no_image.jpg',150,150);
            }
        }
        
        
        $latest = $this->db->select('pd.name,p.image,pd.slug')->from('product p')->join('product_description pd','p.product_id=pd.product_id')->order_by('p.product_id desc')->limit(6)->get();
        $lproducts=array();
        foreach($latest->result() as $product){
            if($product->image)
                $product->image = $this->img->resize($product->image,150,150);
            else {
                $product->image = $this->img->resize('no_image.jpg',150,150);
            }
            $lproducts[]=$product;
        }
        $this->data['latest'] = $lproducts;
        $this->load->view('product/view',$this->data);
        $this->template->footer();
        
    }
    
    
    
    public function search($term=''){
        if($q = $this->input->post('q')){
            redirect('search/'.str_replace(' ','-', $q));
        }
        if($term){
            $this->data['term'] = $term;
        }
        $this->template->header(
                        array(
                            'title'=>'Search result for '.str_replace('-', ' ', $term),
                            'meta_description'=>'Product Search',
                        )
                );
        $this->load->view('product/search',$this->data);
        
        $this->template->footer();
    }

    public function ajax(){
        $json=array();
        switch($this->input->post('action')){
            case 'write_review':
                if($this->acl->is_user()){
                $data = array(
                    'product_id'=>$this->input->post('id'),
                    'customer_id'=>$this->acl->uid(),
                    'title'=>        $this->input->post('title'),
                    'author'=>        $this->acl->fullname(),
                    'text'=>        $this->input->post('text'),
                    'rating'        =>$this->input->post('rate'),
                    'status'        =>0,
                    'date_added'    =>date('Y-m-d H:i:s'),
                    'date_modified'    =>date('Y-m-d H:i:s')
                );
                if($this->model->saveReview($data)){
                    $json['staus']=true;
                    $json['msg']='Review Submitted successfully';
                }
                }
            break;
            
            case 'fetch_reviews':
                $start=1;
                $limit=1;
                $this->db->cache_off();
                list($page,$limit) = explode(':', $this->input->post('pagi'));
                $count = $this->model->countReview($this->input->post('id'));
                if( $count > 0 ) { $pages = ceil($count/$limit); } else { $pages = 0; }
                if ($page > $pages) $page = $pages; 
                if($count)
                    $start = $limit * $page - $limit;
                else
                    $start=0;
                
                $reviews = $this->model->fetchReview($this->input->post('id'),$limit,$start);
                $this->load->library('parser');
                $json['status'] = true;
                $json['html'] = $this->parser->parse('product/ajax/reviews',array('reviews'=>$reviews));
                $json['pagi'] = array('count'=>$count,'pages'=> $pages);
                break;
                
                case '_search':
                $sidx = ($this->input->post('sidx'))?$this->input->post('sidx'):'p.product_id';
                $sord = ($this->input->post('sord'))?$this->input->post('sord'):'ASC'; 
                $start = ($this->input->post('start'))?$this->input->post('start'):0;
                $page = ($this->input->post('page'))?$this->input->post('page'):1;
                $limit = ($this->input->post('limit'))?$this->input->post('limit'):30;
                $cat = ($this->input->post('_c'))?$this->input->post('_c'):0;
                $s = ($this->input->post('_s'))?$this->input->post('_s'):0;
                $select = "p.product_id, p.image, p.price, pd.name, pd.slug ";
                $from = 'product p LEFT JOIN product_description pd ON p.product_id = pd.product_id LEFT JOIN product_to_category ptc ON ptc.product_id=p.product_id ';
                if($this->input->post('_a')){
                    $from .= 'LEFT JOIN product_attribute pa ON (pa.product_id=p.product_id) ';
                }
                $where = "where 1 ";
                
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
                
                
                if($cat){
                    $where .= "AND ptc.category_id = " . $cat. ' ';
                }
                
                $s=str_replace('-',' ', $s);
                if (!empty($s)) {
                    $where .= 'AND (';
                            $implode = array();
                            $words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $s)));
                            foreach ($words as $word) {
                                    $implode[] = "pd.name LIKE " . $this->db->escape('%'.$word.'%') . "";
                            }
                            if ($implode) {
                                    $where .= " " . implode(" AND ", $implode) . "";
                            }
                            $where .= ' ) ';
                }
              
                $count = $this->category->count($from,$where);
                if( $count > 0 ) { $pages = ceil($count/$limit); } else { $pages = 0; }
                if($page > $pages) $page = $pages; 
                if($count)
                    $start = $limit * $page - $limit;
                else
                    $start=0;
                $products=array();
                $products = $this->category->ls($select,$from,$where,$sidx,$sord,$start,$limit);
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
                
                case '_auto':
                        $s = ($this->input->post('_s'))?$this->input->post('_s'):0;
                        $select = "p.product_id, p.name, p.slug ";
                        $from = 'product_description p ';
                       
                        if (!empty($s)) {
                            $where = "where " ."p.name LIKE " . $this->db->escape($s.'%') ;
                            $where2 = "where p.name LIKE " . $this->db->escape('%'.$s.'%') ;
                        }
                        
                        $products=$products1=$products2=array();
                        $products1 = $this->category->ls($select,$from,$where,'p.name','asc',0,7);
                        $products2 = $this->category->ls($select,$from,$where2,'p.name','asc',0,10);
                        if($products1){
                            $products = array_merge($products1, $products2);
                            $products=array_unique($products, SORT_REGULAR);
                        }
                        if($products){
                           foreach($products as $ky=>$item){
                               $products[$ky]->name = $item->name; 
                           }
                        }
                        die(json_encode(array('p'=>$products)));
                        break;
                        case '_srate':
                            if($this->input->post('star')){
                            $avg = $this->model->saveRatings(array(
                                    'product_id'=>$this->input->post('id'),
                                    'rating'    =>$this->input->post('star'),
                                    'ip'        =>$this->input->ip_address()
                                 ));
                            }
                            die(json_encode(array('status'=>true,'r'=>$avg)));
                            break;
        }
        die(json_encode($json));
    }
    
    public function generateMetaDesc($product){
        $map = array(
           'display_size'   => 87,
           'os'             => 13,
           'device_type'    => 12,
           'stand_by'       => 43,
           'talk_time'      => 42,
           'Resolution'     => 88
        );
        
        $this->db->cache_off();
        $attrs = $this->attribute->getAttributeSet($product->product_id,"13,12,43,42,88,87");
        
        $attrs = explode(',', $attrs);
        $type = array();
        
       
        foreach($attrs as $attr)
        $type[] =  explode('-', $attr);
        
        $search = $replace = array();
        foreach($type as $k=>$v){
            $search[] = '['.$v[0].']';
            $replace[] = (isset($v[1]))?$v[1]:'';
        }
        $conn = $this->attribute->getGroupSet($product->product_id,6);
        $mutimedia = $this->attribute->getGroupSet($product->product_id,12);
        $other = $this->attribute->getGroupSet($product->product_id,14);
        $str = $product->name . " comes with [87] display & resolution of [88]. The ".$product->name . " is a [12] by ".substr($product->name,0, strpos($product->name,' ')). " that is powered by [13] with talk time of [42] & in stand by mode it can be used [43]. ".$product->name . " can be connected with other devices via $conn. Multimedia functionalities of ".$product->name . " includes $mutimedia & other features like $other";
            $meta_description =  str_replace($search, $replace, $str);
            $this->db->where('product_id',$product->product_id);
            $this->db->update('product_description',array('auto_meta'=>$meta_description));
            $this->db->cache_on();
        return $meta_description;
    }
}
