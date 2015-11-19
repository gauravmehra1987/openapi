<?php
class Customer extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
    }
    
    public function login(){
        
        if(!$this->acl->is_user()){
            if(!isset($this->fbconnect)){
                $this->load->library('facebook',array(
                                            'appId'  =>'1417770258459485',
                                            'secret' =>'7744cf6a9b09982afadf147ac98bf6b8',
                                    ),'fbconnect');

            }

            $this->data['fbconnect_url'] = $this->fbconnect->getLoginUrl(
                    array(
                            'scope' => 'email,user_birthday,user_location,user_hometown',
                            'redirect_uri'  => site_url('auth/fbconnect', '', 'SSL')
                    )
            );
            
            $this->load->library('user_agent','agent');
            $this->session->data['forward'] = $this->agent->referrer();                
            $this->load->view('account/login',$this->data);
        }else{
            redirect('/');
        }
    }
    
    public function logout(){
        $this->session->unset_userdata('user');
        redirect('/');
    }
    
    public function fbconnect(){
        
                if(!isset($this->fbconnect)){
                    $this->load->library('facebook',array(
                                                'appId'  =>'1417770258459485',
                                                'secret' =>'7744cf6a9b09982afadf147ac98bf6b8',
                                        ),'fbconnect');

                }

		$_SERVER_CLEANED = $_SERVER;
		$_SERVER = $this->clean_decode($_SERVER);

		$fbuser = $this->fbconnect->getUser();
                
		$fbuser_profile = null;
		if ($fbuser){
			try {
				$fbuser_profile = $this->fbconnect->api("/$fbuser");
                                $fbuser_pic = $this->fbconnect->api("/$fbuser?fields=picture.width(500)");
			} catch (FacebookApiException $e) {
				error_log($e);
				$fbuser = null;
			}
		}
                
                $this->db->cache_off();
                
                $this->load->model('tool/image_model','image');
                $image = $this->image->saveUserImage($fbuser_pic['picture']['data']['url'],$fbuser_profile['email']);
		$_SERVER = $_SERVER_CLEANED;
	
//                print_r($fbuser_profile); exit;
		if($fbuser_profile['id'] && $fbuser_profile['email'] && $fbuser_profile['verified']){
			$this->load->model('account/customer_model','customer');
                        $redirect = $this->session->userdata('forward');
                       
			$email = $fbuser_profile['email'];
			$password = $this->get_password($fbuser_profile['id']);
                        $this->load->library('acl');
			if($this->acl->login($email, $password)){
                            redirect($redirect); 
			}
                        
			$email_query = $this->db->query("SELECT `email` FROM customer WHERE LOWER(email) = " . $this->db->escape(strtolower($email)) . "");
                        
			if($email_query->num_rows()){
				$this->customer->updateFbUser($password,$image,$email);
				if($this->acl->login($email, $password)){
					redirect($redirect); 
				}
			}else{
				
				$add_data=array();
				$add_data['email'] = $fbuser_profile['email'];
                                $add_data['image'] = $image;
				$add_data['password'] = $password;
				$add_data['firstname'] = isset($fbuser_profile['first_name']) ? $fbuser_profile['first_name'] : '';
				$add_data['lastname'] = isset($fbuser_profile['last_name']) ? $fbuser_profile['last_name'] : '';
				$add_data['fax'] = '';
                                $add_data['status'] = 1;
//                                print_r($add_data);
				$this->customer->addCustomer($add_data);
				

				if($this->acl->login($email, $password)){
					redirect($redirect);
				}else{
                                    redirect($redirect);
                                }
			}
                        $this->db->cache_on();
            }
    }
    
        private function get_password($str) {
		$password = 'xhansoftz';
		$password.=substr('7744cf6a9b09982afadf147ac98bf6b8',0,3).substr($str,0,3).substr('7744cf6a9b09982afadf147ac98bf6b8',-3).substr($str,-3);
		return strtolower($password);
	}

	private function clean_decode($data) {
    		if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);
				$data[$this->clean_decode($key)] = $this->clean_decode($value);
	  		}
		} else { 
	  		$data = htmlspecialchars_decode($data, ENT_COMPAT);
		}

		return $data;
	}	 
}