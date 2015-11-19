<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Success extends CI_Controller{
    
    var $data;
    
    public function index(){
        $this->template->header(
                        array(
                            'title'=>'Contact Us | Price oye',
                            'meta_description'=>'',
                            'meta_keyword'=>'',
                            'meta_extra'=>''
                        )
                );
        $this->load->view('common/success',$this->data);
        $this->template->footer();
    }
}
?>
