<?php
require_once(APPPATH . 'handlers/Handler.php');

class User_handler extends Handler {
    
    var $ci;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('user_model');
        $this->ci->load->model('userrole_model');
    }
    
    public function signup($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        //checking validation using Group Validation (signup)  
        if ($this->ci->form_validation->run('signup') == FALSE) { 
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        } elseif($inputArray['inputPassword']!=$inputArray['inputConfirmPassword']){
            $output = parent::createResponse(FALSE, PASSWORDS_ERROR, STATUS_BAD_REQUEST);
            return $output;
        }else {
            $data['firstname'] = $inputArray['inputFirstName'];
            $data['lastname'] = $inputArray['inputLastName'];
            $data['email'] = $inputArray['inputEmail'];
            $data['username'] = $inputArray['inputUserName'];
            $data['mobile'] = $inputArray['inputMobileNumber'];
            $data['password'] = encryptPassword($inputArray['inputPassword']);
            $data['signupdate'] = allTimeFormats('', 11);
            $data['ipaddress'] = commonHelperGetClientIp();
            if($inputArray['userrole']==USER_TRAINEE){
                 $data['activated'] = 1; // By default USER_TRAINEE is activated
            }else if($inputArray['userrole']==USER_TRAINER){
                $data['activated'] = 0;
            }
            $data['status'] = 1;
            $data['email'] = $inputArray['inputEmail'];
            $inputUserName['username']=$inputArray['inputUserName'];
            //User name check
            $userNameResponse=$this->getUserData($inputUserName);
            if($userNameResponse['status'] && $userNameResponse['response']['total']>0){
                $output = parent::createResponse(FALSE, REGISTERED_USERNAME, STATUS_CONFLICT);
                return $output; 
            }elseif(!$userNameResponse['status']){
                return $userNameResponse;
            }
            //Email check
            if (empty($data['email']) || !$this->emailExist($data['email'])) {
                $this->ci->user_model->setInsertUpdateData($data);
                $response = $this->ci->user_model->insert_data();
                if ($response) {
                    $roleInput['userid']=$response;
                    $roleInput['userrole']=$inputArray['userrole'];
                    $roleOutput=$this->createUserRole($roleInput);
                    if($roleOutput['status']){
                        $output = parent::createResponse(TRUE, SUCCESS_SIGNUP, STATUS_OK, 0, 'userId', $response);
                        return $output;
                    }else{
                         $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                         return $output;
                    }

                } else {
                    $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                    return $output;
                }
            } else {
                $output = parent::createResponse(FALSE, REGISTERED_EMAIL, STATUS_CONFLICT);
                return $output;
            }
        }
    }
    
    public function login($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('username', 'username', 'required_strict');
        $this->ci->form_validation->set_rules('password', 'password', 'required_strict');
        
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //set default values
        $username = $password = '';
        
        $username = $inputArray['username'];
        $password = $inputArray['password'];
        //get md5 encrypted password 
        $password = encryptPassword($inputArray['password']);
        
        if (!empty($username) && !empty($password)) {
            $input['username'] = $username;
            $input['password'] = $password;
        }
        
        $userData = $this->getUserData($input);
        if($userData['status']){
            if($userData['response']['total']>0){
                $input['userid'] = $userData['response']['userData']['id'];
                //getting user role
                $userRole = $this->getUserRole($input);
                if($userRole['status']){
//                    $role = '';
                    if($userRole['response']['total'] > 0){
//                    $role = $userRole['response']['userRoleData']['role'];
                    $userData['response']['userData']['role'] =  $userRole['response']['userRoleData']['role'];
                    $sessionData = $userData['response']['userData'];
                      //set userrole in the session  
                        $this->setSession($sessionData);
                        $output = parent::createResponse(TRUE, SUCCESS_LOGIN, STATUS_OK);
                        return $output;
                    }
                }
            }else{ 
                         $output = parent::createResponse(FALSE, ERROR_INVALID_USER, STATUS_INVALID_USER);
                         return $output;
                    }
                 
            }else {
                    $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_SERVER_ERROR);
                    return $output;
                }
    }
    
    public function logout() {
        $status = false;

        $returnStatus = $this->ci->customsession->destroy();
        if ($returnStatus) {
            $status = true;
        } else {
            $output = parent::createResponse(FALSE, ERROR_NO_SESSION, 507);
            return $output;
        }
        $output = parent::createResponse(TRUE, SUCCESS_LOGOUT, STATUS_OK,0,'loggedout',$status);
        return $output;
    }
    
    public function getUserData($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('status', 'status', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('username', 'User Name', 'min_length[6]|max_length[50]');
        
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $response['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        
        $status = '';
        if (isset($inputArray['status'])) {
            $status = $inputArray['status'];
        }
        $password = '';
        if (isset($inputArray['password'])) {
            $password = $inputArray['password'];
        }
        
        $this->ci->user_model->resetVariable();
        $selectInput['id'] = $this->ci->user_model->id;
        $selectInput['email'] = $this->ci->user_model->email;
        $selectInput['password'] = $this->ci->user_model->password;
        $selectInput['firstname'] = $this->ci->user_model->firstname;
        $selectInput['lastname'] = $this->ci->user_model->lastname;
        $selectInput['mobile'] = $this->ci->user_model->mobile;
        $selectInput['status'] = $this->ci->user_model->status;
        $selectInput['activated'] = $this->ci->user_model->activated;
        $selectInput['username'] = $this->ci->user_model->username;
        
        $this->ci->user_model->setSelect($selectInput);
        
        $where[$this->ci->user_model->deleted] = 0;
        
        if (!empty($password)) {
            $where[$this->ci->user_model->password] = $password;
        }
        if (isset($inputArray['username']) && !empty($inputArray['username'])) {
            $where[$this->ci->user_model->username] = $inputArray['username'];
        }

        $this->ci->user_model->setWhere($where);
        $this->ci->user_model->setRecords(1);
        $userData = $this->ci->user_model->get();
        
        if (count($userData) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_USER, STATUS_INVALID_USER);
            return $output;
        }
        $output = parent::createResponse(TRUE, DATA_SUCCESS, STATUS_OK,1,'userData',$userData[0]);
        return $output;
        
    }
    
    public function getUserRole($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userid', 'userid', 'is_natural_no_zero');
        
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $response['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        
        $this->ci->userrole_model->resetVariable();
        $selectInput['id'] = $this->ci->userrole_model->id;
        $selectInput['role'] = $this->ci->userrole_model->role;
        $selectInput['status'] = $this->ci->userrole_model->status;
        
        $this->ci->userrole_model->setSelect($selectInput);
        
        $where[$this->ci->userrole_model->deleted] = 0;
        $where[$this->ci->userrole_model->status] = 1;
        $where[$this->ci->userrole_model->userid] = $inputArray['userid'];
        $this->ci->userrole_model->setWhere($where);
        $this->ci->userrole_model->setRecords(1);
        $userRoleData = $this->ci->userrole_model->get();
        
        if (count($userRoleData) == 0) {
            $output = parent::createResponse(TRUE, ERROR_NO_USER, STATUS_INVALID_USER);
            return $output;
        }
        $output = parent::createResponse(TRUE, DATA_SUCCESS, STATUS_OK,1,'userRoleData',$userRoleData[0]);
        return $output;
    }
    
    function setSession($userData) {
        $this->ci->customsession->destroy();
        //  print_r($userData);
        $this->ci->customsession->setData(USER_ID, $userData['id']);
        $this->ci->customsession->setData(USERNAME, $userData['username']);
        $this->ci->customsession->setData(USER_EMAIL, $userData['email']);
        if($userData['role'] == USER_TRAINER){
            $this->ci->customsession->setData(USER_ROLE, USER_TRAINER);
        }else{
            $this->ci->customsession->setData(USER_ROLE, USER_TRAINEE);
        }
    }
    
    //Function to check if email already exists in user table
    function emailExist($email) {
        $selectInput = array();
        $selectInput['id'] = $this->ci->user_model->id;
        $this->ci->user_model->setSelect($selectInput);
        $where[$this->ci->user_model->email] = $email;
        $where[$this->ci->user_model->deleted] = 0;
        $this->ci->user_model->setWhere($where);
        $emailExists = $this->ci->user_model->get();
        if (count($emailExists) > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    //Creating the userrole for the given userid
    public function createUserRole($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userid', 'userid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('userrole', 'User Role', 'alpha|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $errorMsg = $this->ci->form_validation->get_errors();
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        }
        
        $this->ci->userrole_model->resetVariable();
        $selectInput['id'] = $this->ci->userrole_model->id;
        
        $this->ci->userrole_model->setSelect($selectInput);
        
        $insertUpdateData[$this->ci->userrole_model->deleted] = 0;
        $insertUpdateData[$this->ci->userrole_model->status] = 1;
        $insertUpdateData[$this->ci->userrole_model->userid] = $inputArray['userid'];
        $insertUpdateData[$this->ci->userrole_model->role] = $inputArray['userrole'];
        $this->ci->userrole_model->setInsertUpdateData($insertUpdateData);
        $userRoleData = $this->ci->userrole_model->insert_data();
        
        if (count($userRoleData) > 0) {
            $output = parent::createResponse(TRUE, SUCCESS_USERROLE, STATUS_CREATED);
            return $output;
        }
        $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_BAD_REQUEST);
        return $output;
    }
    
    public function getTrainers() {
        $userId = $this->ci->customsession->getData(USER_ID);
        $isLogin = ($userId > 0) ? 1 : 0;
        if($isLogin){
            
            $selectInput['id'] = $this->ci->userrole_model->id;
            $selectInput['userid'] = $this->ci->userrole_model->userid;
        
            $this->ci->userrole_model->setSelect($selectInput);
            
            $where[$this->ci->userrole_model->role] = USER_TRAINER;
            $where[$this->ci->userrole_model->deleted] = 0;
            $where[$this->ci->userrole_model->status] = 1;
        
            $this->ci->userrole_model->setWhere($where);
            $userRoleData = $this->ci->userrole_model->get();
            
            $userIdsArray = commonHelperGetIdArray($userRoleData, 'id');
            
            $selectUserInput['id'] = $this->ci->user_model->id;
            $selectUserInput['email'] = $this->ci->user_model->email;
            $selectUserInput['firstname'] = $this->ci->user_model->firstname;
            $selectUserInput['lastname'] = $this->ci->user_model->lastname;
            $selectUserInput['mobile'] = $this->ci->user_model->mobile;
            $selectUserInput['username'] = $this->ci->user_model->username;
            
            $this->ci->user_model->setSelect($selectUserInput);
            
            $userwhere[$this->ci->user_model->deleted] = 0;
            $userwhere[$this->ci->user_model->activated] = 1;
            $userwhere[$this->ci->user_model->status]=1;
            
            $this->ci->user_model->setWhere($userwhere);
            
            $whereInArray = array();
            $whereInArray[$this->ci->user_model->id] = array_keys($userIdsArray);
            $this->ci->user_model->setWhereIns($whereInArray);
//            echo $this->ci->user_model->get();
            $trainersData = $this->ci->user_model->get();
            
            if (count($trainersData) == 0) {
                $output = parent::createResponse(TRUE, ERROR_NO_DATA, STATUS_OK);
                return $output;
            }
            $output = parent::createResponse(TRUE, DATA_SUCCESS, STATUS_OK,count($trainersData),'trainersData',$trainersData);
            return $output;
        }else{
            $output = parent::createResponse(FALSE, ERROR_SOMETHING_WENT_WRONG, STATUS_BAD_REQUEST);
            return $output;
        }
    }

}

?>

