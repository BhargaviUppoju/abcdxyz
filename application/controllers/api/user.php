<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once (APPPATH . 'libraries/REST_Controller.php');
require_once (APPPATH . 'handlers/user_handler.php');
class User extends REST_Controller {
    public $userHandler;
    public function __construct() {
        parent::__construct();
        $this->userHandler = new User_handler();
    }
    public function login_post() {
        $inputArray = $this->post();
        $loginResponse = $this->userHandler->login($inputArray);
            $resultArray = array('response' => $loginResponse['response']);
            $this->response($resultArray, $loginResponse['statusCode']);
    }
    
    public function logout_post() {
		
	$this->loginCheck();
        $inputArray = $this->post();
        $loginResponse = $this->userHandler->logout($inputArray);
        $resultArray = array('response' => $loginResponse['response']);
        $this->response($resultArray, $loginResponse['statusCode']);
    }
    
    /** Function to check for logged in user
    *
    * @access	public
    * @return	json response with status and message
    */
    public function loginCheck() {
	$loginCheck = $this->customsession->loginCheck();
	if($loginCheck != 1 && !$loginCheck['status']) {
		$output['status'] = FALSE;
		$output['response']['messages'][] = $loginCheck['response']['messages'][0];
		$output['statusCode'] = STATUS_INVALID_SESSION;
		
		$resultArray = array('response' => $output['response']);
		$this->response($resultArray, $output['statusCode']);
	}
    }
    
        public function signup_post(){
        
        $inputArray=$this->post();
        $signupResponse=$this->userHandler->signup($inputArray);
        $resultArray = array('response' => $signupResponse['response']);
        $this->response($resultArray,$signupResponse['statusCode']);
    }
    
}

?>

