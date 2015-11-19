<?php
class Category_Model extends CI_Model {
	public function getCategory($category_id) {
		$query = $this->db->select('*')
                        ->from('category c')
                        ->join('category_description cd','c.category_id=cd.category_id')
                        ->where('c.category_id',$category_id)
                        ->get();
		return $query->row();
	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
                return $query->result();
	}
        
        public function getCategoryMenu(){
            $cats = array();
					
		$categories = $this->getCategories(0);
		
		
		foreach ($categories as $category) {
			if ($category->top) {
				$children_data = array();
				$children = $this->getCategories($category->category_id);
                                foreach ($children as $kid) {
                                    $children_data[] = array(
                                        'name'=>$kid->name,
                                        'href'=> site_url($category->slug.'/'.$kid->slug),
                                    );
                                }
				$cats[] = array(
					'name'     => $category->name,
					'href'     => site_url($category->slug),
                                        'children' => $children_data
                    		);
			}
		}
                return $cats;
        }
        
        public function count($from,$where){
            $query = $this->db->query("select p.product_id from $from $where GROUP BY p.product_id");
                return $query->num_rows();
        }
        
        public function ls($select,$from,$where,$sidx,$sord,$start,$limit){
//            echo "select $select from $from $where ORDER BY $sidx $sord LIMIT $start , $limit"; exit;
            $query = $this->db->query("select $select from $from $where GROUP BY p.product_id ORDER BY $sidx $sord LIMIT $start , $limit");
            if ($query->num_rows()){
                return $query->result();
            }
            
        }
        
        public function toID($value){
            $query = $this->db->select('query')->from('url_alias')->where('keyword',$value)->get();
            if($query->num_rows()){
                return end(explode('=', $query->row()->query));
            }
        }
        
        public function getPriceLimits($data) {
            
            $query = $this->db->select_max('p.price')->from('product p')->join('product_to_category pc','p.product_id=pc.product_id')->get();
               if($query->num_rows()){

               }
        }
        
       
}
?>