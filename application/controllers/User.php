<?php

if(!defined('BASEPATH')) exit('No direct script access is allowed');

require_once(APPPATH.'handlers/User_handler.php');
class User extends CI_Controller{
    
        public function __construct() {
            parent::__construct();
        }
        
    public function signup($userRole) {       
    	$uid = $this->customsession->getUserId();
    	if($uid>0){
    		header('Location:'.commonHelperGetPageUrl('home'));
    	}
        $userHandler=new User_handler();
        $inputArray = $this->input->post('submit');
        if($inputArray){
            $inputArray=$_POST;
            $inputArray['userrole']=$userRole;
            $signupResponse =$userHandler->signup($inputArray);
            if($signupResponse['status'] && $signupResponse['response']['userId']>0 ){               
               $signupSuccess=TRUE;
            }
        }
        if(isset($signupSuccess)){
            $data['content'] = 'signupSuccess_view';
        }else{
             $data['content'] = 'signup_view'; 
        }
        $data['pageName'] = 'Signup';
        $data['pageTitle'] = 'Signup | Chessship.com';
        $data['jsArray'] = array($this->config->item('js_public_path') . '/signup');
        $this->load->view('templates/basic_template', $data);
    }
}