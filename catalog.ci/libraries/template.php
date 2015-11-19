<?php
class Template{
  
    var $header = array(
                        'title' => 'Price',
                        'meta_title' => '',
                        'meta_description' => '',
                        'meta_keyword' => '',
                        'username'=>'',
                        'user'=>array('status'=>false,'firstname'=>'Guest'),
                        'title' => 'Price Oye - Compare Easy',
                        'username'=>'Guest',
                        'ogtitle'=>'priceoye',
                        'ogurl'=>'http://priceoye.com/',
                        'ogimage'=>'http://priceoye.com/static/image/fbprofile.png'
                        );    
    var $footer = array();
    var $leftbar = array();
    var $foot = array();
    var $block = array();
    var $fbconnect;
    public function __construct(){
       $this->CI =&get_instance();
       $this->CI->load->database();
       $this->CI->load->library('acl');
       $this->CI->load->library('session');
    }
    
    function parse_args( $args, $defaults = '' ) {
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;
        else
		wp_parse_str( $args, $r );

	if ( is_array( $defaults ) )
            	return array_merge( $defaults, $r );
	return $r;
    }
    
    public function head($args=false){
      if($args) $this->head = $this->parse_args($args,$this->head);
        $this->CI->load->view('common/head',$this->head);
    }
    
    public function header($args=false){
        
          if($this->CI->acl->is_user()){
              $this->CI->load->model('tool/image_model','image');
             
              if($this->CI->acl->user->image){
                  $user = $this->CI->acl->user;
              }
              $this->header['user']=array(
                  'status'=>true,
                  'firstname'=>
                  $this->CI->acl->user->firstname,
                  'image'=>$user->image
              );
          }
        
          $this->CI->load->model('catalog/category_model','category');
          
          if($args) $this->header = $this->parse_args($args,$this->header);
          $this->header['menu'] = $this->CI->category->getCategoryMenu();
         
          $this->CI->load->view('common/header',$this->header);
    }
    
    
    public function block($block,$data){
      $this->block = $data;
      $this->CI->load->view($block,$data);
    }
    
    public function footer($args=false){
      if($args) $this->footer = $this->parse_args($args,$this->footer);
      
      if(!$this->CI->acl->is_user()){
            if(!isset($this->fbconnect)){
                $this->CI->load->library('facebook',array(
                                            'appId'  =>'1417770258459485',
                                            'secret' =>'7744cf6a9b09982afadf147ac98bf6b8',
                                    ),'fbconnect');

            }

            $this->footer['fbconnect_url'] = $this->CI->fbconnect->getLoginUrl(
                    array(
                            'scope' => 'email,user_birthday,user_location,user_hometown',
                            'redirect_uri'  => site_url('auth/fbconnect', '', 'SSL'),
                    )
            );
            
//            $this->load->library('user_agent','agent');
            $this->CI->session->set_userdata(array('forward'=>uri_string()));
                         
            
        }
        
        $this->CI->load->view('common/footer',$this->footer);
    }
    
    
} 


?>