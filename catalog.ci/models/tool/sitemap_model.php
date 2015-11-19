<?php
class Sitemap_model extends CI_Model{
    public function getCategorySitemap($category_id){
        $query = $this->db->select('cd.slug, cd.name')
                ->from('category_description cd')
                ->where('cd.parent_id',$category_id)
                ->get();
        return $query->result();
    }
    
    public function getCategory(){
        $query = $this->db->select('cd.slug, cd.name')
                ->from('category_description cd')
                ->join('category c','c.category_id=cd.category_id')
                ->where('c.parent_id',81)
                ->group_by('cd.slug')
                ->get();
        return $query->result();
    }
    
    public function getProducts($args=false){
        $this->db->cache_off();
        $query = $this->db->select('pd.slug')
                ->from('product_description pd')
//                ->limit(100,0)
                ->get();
        return $query->result();
    }
    
    public function getProductsByCategory($cat=false){
        $this->db->cache_off();
        $cat = $this->findCategoryBySlug($cat);
        if($cat){
            $query = $this->db->select('pd.slug')
                    ->from('product_description pd')
                    ->join('product_to_category p2c','pd.product_id=p2c.product_id')
                    ->where('p2c.category_id',$cat)
//                    ->limit(100,0)
                    ->get();
            return $query->result();
        }
    }
    
    public function findCategoryBySlug($slug){
        $query = $this->db->select('category_id')->from('category_description')->where('slug',$slug)->get();
        if($query->num_rows())
        return $query->row()->category_id;
    }
    
    public function getCompare(){
        $this->db->cache_off();
        $query = $this->db->select('slugs as slug')->from('compare')->get();
        return $query->result();
    }
}
?>
