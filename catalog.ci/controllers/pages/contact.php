<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Contact extends CI_Controller{
    
    var $data;
    
    public function index(){
        if($this->input->post('email') && $this->input->post('first_name') && $this->input->post('message')){
            $to = 'gauravmehra1987@gmail.com,ashish150755@gmail.com,ashish@priceoye.com';
           // $to = 'gauravmehra1987@gmail.com';
            $subject = 'PriceOye | ' . $this->input->post('first_name') . ' just enquired!' ;
            $headers = "From:PriceOye<info@priceoye.com>\n";
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $html = 'A new enquiry has been recieved'."<br>";

            $html .= "<b>First Name : </b> ".$this->input->post('first_name')."<br>";
            $html .= "<b>Last Name : </b> ".$this->input->post('last_name')."<br>";
            $html .= "<b>Email : </b>  ".$this->input->post('email')."<br>";
            $html .= "<b>Phone : </b>  ".$this->input->post('phone')."<br>";
            $html .= "<b>Message : </b>  ".nl2br($this->input->post('message'))."<br>";
            mail($to, $subject, $html, $headers);
            redirect('success?contact');
        }
        
        $this->template->header(
                        array(
                            'title'=>'Contact Us | Price oye',
                            'meta_description'=>'',
                            'meta_keyword'=>'',
                            'meta_extra'=>''
                        )
                );
        $this->load->view('pages/contact',$this->data);
        $this->template->footer();
    }
}
?>
