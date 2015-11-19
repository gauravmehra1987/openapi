<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class acl{

var $user;

public function __construct($params = array()){
   $this->CI =& get_instance();
   
   $this->CI->load->database();
   
   $config = array();
   
   $this->CI->load->library('session', $config);
   if($this->is_user()){
        $uq = $this->CI->db->get_where('customer',array('customer_id'=>$this->uid()));
        $this->user=$uq->row();
   }
   
}
    public function login($email, $password){
        $sql = "SELECT customer_id as id, firstname, lastname, email FROM customer WHERE LOWER(email) = " . $this->CI->db->escape($email) . " AND password = " . $this->CI->db->escape(md5($password)) . " AND status = '1' AND approved = '1'";

       $result = $this->CI->db->query($sql);
       if(!$result){
                return false;
          }else{
             if ($result->num_rows() > 0){
               $user = $result->row();
               $perms = array();
//               $perms = $this->load_user_permissions($user->user_group);
//               $this->CI->session->set_userdata(array('xcvcs' => $perms));
               $this->CI->session->set_userdata(array('user' => serialize($user)));
               return true;
           }else{
              return false;
           }
       }
    }
    
    function load_user_permissions($group){ 
    $q = "select permission from user_group where id = $group"; 
        $result = $this->CI->db->query($q);
        return $result->row()->permission; 
    }
    
    
    
    public function is_user(){
        if($this->CI->session->userdata('user')){
            return 1;
        }else{
          return 0;
        }
    }
    
    
    public function get_user(){
        return unserialize($this->CI->session->userdata('user'));
    }
    
    public function uid(){
        return unserialize($this->CI->session->userdata('user'))->id;
    }
    
    public function firstname(){
        return unserialize($this->CI->session->userdata('user'))->firstname;
    }
    
    public function fullname(){
       $user = unserialize($this->CI->session->userdata('user'));
       return $user->firstname . ' ' . $user->lastname;
    }
    
    public function get_user_by_key($key){
        return unserialize($this->CI->session->userdata('user'))->$key;
    }
    
    public function get_permissions(){
        if($this->CI->session->userdata('user') && $this->CI->session->userdata('xcvcs')){
            
            if($this->CI->session->userdata('xcvcs')){
              $perms = $this->CI->session->userdata('xcvcs');
              return unserialize($perms);
            }
            
        }else{
            return false;
        }
    }
    
    public function has_permission($resourse,$action){ 
       $perms = $this->get_permissions();
      return true;
        if($this->check_defaults($perms,$resourse))
          return true;
        if($this->auth_ajax_actions($resourse))
          return true;
        
 
        if($action == 'modify' || $action == 'delete') $resourse = substr ($resourse,0,strrpos($resourse,"/"));
        if(array_key_exists($action, $perms)){ 
            if(in_array($resourse, $perms[$action])){
                return true;
            }
        }
        return false;
    }
    
    public function form_access($resourse){ 
       $perms = $this->get_permissions();
       
       if(array_key_exists('form', $perms)){ 
            if(in_array($resourse, $perms['form'])){ 
                return true;
            }
        }
        return false;
    }
    
    public function check_defaults($perms,$resourse){
      if(!$perms)  $perms=array();
        $default = array('','dashboard','account/signout','user/user/logoff','user/accessdenied','account/login','user/group');
        if(in_array($resourse,$default)){
            return true;
          }
    }
    public function auth_ajax_actions($resourse){
      if(preg_match('/grid/', $resourse)) return true;  
      if(preg_match('/view/', $resourse)) return true;
    }
    
    
    public function has_permissions($access,$to){
      if(!empty($access)){
          $default = array('dashboard','user/user/logoff','user/accessdenied','user/user/login','user/group');
          $path = explode("/", $access);
          if(array_key_exists(1, $path)){
              $route = $path[0] . "/" . $path[1];
          }else{
              $route = $access;
          }
          
          if(in_array($route,$default)){
            return true; exit;
          }
          $perms = $this->get_permissions();
              if(is_array($perms[$to])){
                    if(in_array($route, $perms[$to]) || in_array($access,$default)){
                      return true;
                    }else{
                      redirect('user/accessdenied');
                    }
              }
      }else{
        return true;
      }
    }    
    
}