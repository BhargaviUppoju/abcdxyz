<?php

require_once 'Common_model.php';

/**
 * userrole model class
 */
class Userrole_model extends Common_model {
    
    var $id;
    var $userid;
    var $role;
    var $status; 
    var $deleted;
     
    function __construct() {
        
        parent::__construct();
        $this->select[] = $this->id;
        //setting the table name
        $this->setTableName("userrole");
        //Giving alias names to table field names
        $this->_setFieldNames();
    }
    
    //Set the field values
    private function _setFieldNames() {
        $this->id = "id";
        $this->userid = "userid";
        $this->role = "role";
        $this->status = "status";
        $this->deleted = "deleted";
    }
}

?>

