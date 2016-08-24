<?php

if(!defined('BASEPATH')) 
    exit('No direct script access allowed');
/* 
 * login controller
 * 
 */

require_once(APPPATH . 'handlers/user_handler.php');

class Login extends CI_Controller {
    
    var $userHandler;

      public function __construct() {
        parent::__construct();
        $this->userHandler = new User_handler();
        $this->load->helper('url');
       }
       
        public function index() {
           $data = array();
           $data['content'] = 'login_view';
           $data['pageName'] = 'login';
           $data['js'] = $this->config->item('js_path') . 'login.js';
           $inputArray = $this->input->post();
           $userresponse =$this->userHandler->login($inputArray);
           if($userresponse['status'] && $userresponse['response']['total']>0 ){
               $data['userDetails'] = $userresponse ['response'] ['userData'];
               $data['userRole'] = $userresponse ['response'] ['userRoleData']['role'];
           }
           $this->load->view('templates/basic_template', $data);
           //$this->load->view('welcome_message');
        }
       
        public function logout() {
            $response = $this->userHandler->logout();
            header('Location:' . site_url());
            exit;
        }
}

?>