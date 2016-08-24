<?php

require_once 'Common_model.php';

class User_model extends Common_model { 
    var $id;
    var $email;
    var $password;
    var $firstname;
    var $lastname;
    var $username;
    var $signupdate;
    var $activated;
    var $mobile ;
    var $status; 
    var $deleted;
    
    function __construct() {
        
        parent::__construct();
        $this->select[] = $this->id;
        //setting the table name
        $this->setTableName("user");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
     //Set the field values
    private function _setFieldNames() {
        $this->id = "id";
        $this->email = "email";
        $this->password = "password";
        $this->firstname = "firstname";
        $this->lastname = "lastname";
        $this->username = "username";
        $this->signupdate = "signupdate";
        $this->mobile = "mobile";
        $this->status = "status";
        $this->activated = "activated";
        $this->deleted = "deleted";
    }
}

