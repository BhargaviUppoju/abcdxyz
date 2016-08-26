<?php
require_once(APPPATH.'handlers/User_handler.php');
class Dashboard extends CI_Controller{
    var $userHandler;
    public function __construct() { 
        parent::__construct();
    }
    
    public function index() {
        $this->userHandler = new User_handler();
        $trainersList = $this->userHandler->getTrainers();
         if($trainersList['status'] && $trainersList['response']['total'] > 0){
             $data['trainersList'] = $trainersList['response']['trainersData'];
         }
        $data['pageName'] = 'Dashboard';
        $data['pageTitle'] = 'Dashboard | Chessship.com'; 
        $data['content'] = 'dashboard_view';
        $this->load->view('templates/user_template', $data);
    }
}

