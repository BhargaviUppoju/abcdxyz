<?php

if(!defined('BASEPATH')) exit('No direct script access is allowed');

require_once(APPPATH.'handlers/User_handler.php');
class User extends CI_Controller{
    var $userHandler;
    
        public function __construct() { 
            parent::__construct();
            $this->userHandler = new User_handler();
        }
    public function login() { 
        $uid = $this->customsession->getUserId();
    	if($uid>0){
    		header('Location:'.commonHelperGetPageUrl('dashboard'));
    	}
        $data = array();
        $inputArray = $this->input->post();
        $data['content'] = 'login_view';
        $data['pageName'] = 'Login';
        $data['pageTitle'] = 'Login | Chessship.com';
        $data['jsArray'] = array(
            $this->config->item('js_public_path'). 'login'
        );
        if ($this->input->post('LoginSubmit')) {
            $userresponse = $this->userHandler->login($inputArray);
            if ($userresponse['status']) { 
                header('Location:' .commonHelperGetPageUrl('dashboard'));
            } else {
                $data['errors'] = $userresponse['response']['messages']['0'];
            }
        }
        $this->load->view('templates/user_template', $data);
    } 
        
    public function signup($userRole) {       
    	$uid = $this->customsession->getUserId();
    	if($uid>0){
    		header('Location:'.commonHelperGetPageUrl('home'));
    	}
        $inputArray = $this->input->post('submit');
        if($inputArray){
            $inputArray=$_POST;
            $inputArray['userrole']=$userRole;
            $signupResponse =$this->userHandler->signup($inputArray);
            if($signupResponse['status'] && $signupResponse['response']['userId']>0 ){               
               $signupSuccess=TRUE;
            }else{
                $data['errorMessage']=  is_array($signupResponse['response']['messages'])?$signupResponse['response']['messages'][0]:$signupResponse['response']['messages'];
            }
        }
        if(isset($signupSuccess)){
            $data['content'] = 'signupSuccess_view';
        }else{
             $data['content'] = 'signup_view'; 
        }
        $data['pageName'] = 'Signup';
        $data['pageTitle'] = 'Signup | Chessship.com';
        $data['jsArray'] = array($this->config->item('js_public_path') . 'signup');
        $this->load->view('templates/user_template', $data);
    }
    
     public function logout() {
        $response = $this->userHandler->logout();
        header('Location:' .commonHelperGetPageUrl('home'));
        exit;
    }
}