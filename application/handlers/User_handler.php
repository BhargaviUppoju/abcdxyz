<?php
require_once(APPPATH . 'handlers/handler.php');

class User_handler extends Handler {
    
    var $ci;
    public function __construct() {
        parent::__construct();
        $this->ci = parent::$CI;
        $this->ci->load->model('User_model');
        $this->ci->load->model('Userrole_model');
    }
    
    public function signup($inputArray) {
        $this->ci->form_validation->pass_array($inputArray);
        //checking validation using Group Validation (signup)        
        if ($this->ci->form_validation->run('signup') == FALSE) { 
            $errorMsg = $this->ci->form_validation->get_errors();
            //creating response output
            $output = parent::createResponse(FALSE, $errorMsg['message'], STATUS_BAD_REQUEST);
            return $output;
        } elseif($inputArray['inputPassword']!=$inputArray['inputConfirmPassword']){
            $output = parent::createResponse(FALSE, PASSWORDS_ERROR, STATUS_BAD_REQUEST);
            return $output;
        }else {

            //Setting Data for inserting
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
            $userNameResponse=$this->getUserData($inputUserName);
            if($userNameResponse['status'] && $userNameResponse['response']['total']>0){
                $output = parent::createResponse(FALSE, REGISTERED_USERNAME, STATUS_CONFLICT);
                return $output; 
            }elseif(!$userNameResponse['status']){
                return $userNameResponse;
            }
            if (empty($data['email']) || !$this->emailExist($data['email'])) {
                //setting data for inserting
                $this->ci->User_model->setInsertUpdateData($data);
                $response = $this->ci->User_model->insert_data();
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
        $this->ci->form_validation->set_rules('email', 'email', 'required_strict');
        $this->ci->form_validation->set_rules('password', 'password', 'required_strict');
        
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        //set default values
        $email = $password = '';
        
        $email = $inputArray['email'];
        $password = $inputArray['password'];
        //get md5 encrypted password 
        $password = encryptPassword($inputArray['password']);
        
        if (!empty($email) && !empty($password)) {
            $input['email'] = $email;
            $input['password'] = $password;
        }
        
        $userData = $this->getUserData($input);
                
        if ($userData['status']) {
            if ($userData['response']['total'] > 0) {
                $input['userid'] = $userData['response']['userData']['id'];
                //getting user role
                $userRole = $this->getUserRole($input);
                
                if($userRole['status']){
                    $role = '';
                    if ($userRole['response']['total'] > 0) {
                       //print_r($userRole['response']['userRoleData']);
                       $role = $userRole['response']['userRoleData']['role'];
                      //set userrole in the session  
                        $this->setSession($userRole['response']['userRoleData'])  ;
                        
                        $output['status'] = TRUE;
                        $output['response']['userData'] = $userData['response']['userData'];
                        $output['response']['messages'] = array();
                        $output['response']['total'] = ($userData['response']['total']);
                        $output['statusCode'] = STATUS_OK;
                    }else {
                        $output['status'] = FALSE;
                        $output['response']['messages'][] = ERROR_INVALID_USER;
                        $output['response']['total'] = 0;
                        $output['statusCode'] = STATUS_INVALID_USER;
                        return $output;
                    }
                }else{
                    return $userRole;
                }
                
            }else {
                $output['status'] = FALSE;
                $output['response']['messages'][] = ERROR_INVALID_USER;
                $output['response']['total'] = 0;
                $output['statusCode'] = STATUS_INVALID_USER;
                return $output;
            }
        }else {
            return $userData;
        }
    }
    
    public function logout() {
        $status = false;

        $returnStatus = $this->ci->customsession->destroy();
        if ($returnStatus) {
            $status = true;
        } else {
            $output['status'] = FALSE;
            $output['response']['messages'][] = ERROR_NO_SESSION;
            $output['statusCode'] = 507;
            return $output;
        }
        $output['status'] = TRUE;
        $output['response']['loggedout'] = $status;
        $output['response']['messages'] = array();
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    
    public function getUserData($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('email', 'email', 'valid_email');
        $this->ci->form_validation->set_rules('status', 'status', 'is_natural_no_zero');
        $this->ci->form_validation->set_rules('username', 'User Name', 'min_length[6]|max_length[50]');
        
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
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
        
        $this->ci->User_model->resetVariable();
        $selectInput['id'] = $this->ci->User_model->id;
        $selectInput['email'] = $this->ci->User_model->email;
        $selectInput['password'] = $this->ci->User_model->password;
        $selectInput['firstname'] = $this->ci->User_model->firstname;
        $selectInput['lastname'] = $this->ci->User_model->lastname;
        $selectInput['mobile'] = $this->ci->User_model->mobile;
        $selectInput['status'] = $this->ci->User_model->status;
        $selectInput['activated'] = $this->ci->User_model->activated;
        
        $this->ci->User_model->setSelect($selectInput);
        
        $where[$this->ci->User_model->deleted] = 0;
        
        if (isset($inputArray['email']) && $inputArray['email'] != '') {
            $where[$this->ci->User_model->email] = $inputArray['email'];
        }
        if (!empty($password)) {
            $where[$this->ci->User_model->password] = $password;
        }
        if (isset($inputArray['username']) && !empty($inputArray['username'])) {
            $where[$this->ci->User_model->username] = $inputArray['username'];
        }

        $this->ci->User_model->setWhere($where);
        $this->ci->User_model->setRecords(1);
        $userData = $this->ci->User_model->get();
        
        if (count($userData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        
        $output['status'] = TRUE;
        $output['response']['userData'] = $userData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
        
    }
    
    public function getUserRole($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userid', 'userid', 'is_natural_no_zero');
        
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        $this->ci->Userrole_model->resetVariable();
        $selectInput['id'] = $this->ci->Userrole_model->id;
        $selectInput['role'] = $this->ci->Userrole_model->role;
        $selectInput['status'] = $this->ci->Userrole_model->status;
        
        $this->ci->Userrole_model->setSelect($selectInput);
        
        $where[$this->ci->Userrole_model->deleted] = 0;
        $where[$this->ci->Userrole_model->status] = 1;
        $where[$this->ci->Userrole_model->userid] = $inputArray['userid'];
        $this->ci->Userrole_model->setWhere($where);
        $this->ci->Userrole_model->setRecords(1);
        $userRoleData = $this->ci->Userrole_model->get();
        
        if (count($userRoleData) == 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = ERROR_NO_USER;
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_INVALID_USER;
            return $output;
        }
        
        $output['status'] = TRUE;
        $output['response']['userRoleData'] = $userRoleData[0];
        $output['response']['messages'] = array();
        $output['response']['total'] = 1;
        $output['statusCode'] = STATUS_OK;
        return $output;
    }
    
    function setSession($userRoleData) {
        $this->ci->customsession->destroy();
        //  print_r($userData);
        $this->ci->customsession->setData(USER_ID, $userRoleData['userid']);
        if($userRoleData['role'] == USER_TRAINER){
            $this->ci->customsession->setData(USER_ROLE, USER_TRAINER);
        }else{
            $this->ci->customsession->setData(USER_ROLE, USER_TRAINEE);
        }
    }
    
    //Function to check if email already exists in user table
    function emailExist($email) {
        $selectInput = array();
        $selectInput['id'] = $this->ci->User_model->id;
        $this->ci->User_model->setSelect($selectInput);
        $where[$this->ci->User_model->email] = $email;
        $where[$this->ci->User_model->deleted] = 0;
        $this->ci->User_model->setWhere($where);
        $emailExists = $this->ci->User_model->get();
        if (count($emailExists) > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    public function createUserRole($inputArray) {
        
        $this->ci->form_validation->reset_form_rules();
        $this->ci->form_validation->pass_array($inputArray);
        $this->ci->form_validation->set_rules('userid', 'userid', 'is_natural_no_zero|required_strict');
        $this->ci->form_validation->set_rules('userrole', 'User Role', '|required_strict');
        if ($this->ci->form_validation->run() == FALSE) {
            $response = $this->ci->form_validation->get_errors();
            $output['status'] = FALSE;
            $output['response']['messages'] = $response['message'];
            $output['response']['total'] = 0;
            $output['statusCode'] = STATUS_BAD_REQUEST;
            return $output;
        }
        
        $this->ci->Userrole_model->resetVariable();
        $selectInput['id'] = $this->ci->Userrole_model->id;
        
        $this->ci->Userrole_model->setSelect($selectInput);
        
        $insertUpdateData[$this->ci->Userrole_model->deleted] = 0;
        $insertUpdateData[$this->ci->Userrole_model->status] = 1;
        $insertUpdateData[$this->ci->Userrole_model->userid] = $inputArray['userid'];
        $insertUpdateData[$this->ci->Userrole_model->role] = $inputArray['userrole'];
        $this->ci->Userrole_model->setInsertUpdateData($insertUpdateData);
        $userRoleData = $this->ci->Userrole_model->insert_data();
        
        if (count($userRoleData) > 0) {
            $output['status'] = TRUE;
            $output['response']['messages'][] = SUCCESS_USERROLE;
            $output['statusCode'] = STATUS_CREATED;
            return $output;
        }
        
        $output['status'] = FALSE;
        $output['response']['messages'] = ERROR_SOMETHING_WENT_WRONG;
        $output['statusCode'] = STATUS_BAD_REQUEST;
        return $output;
    }

}

?>

