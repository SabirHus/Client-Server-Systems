<?php

/*
 * Class User Data
 *
 * Represents data for each user
 */
class UserData {

    // private fields
    protected $_id, $_userName, $_password, $_userType; // class for each user

    // constructor
    public function __construct($dbRow) { // connect the variables to the corresponding rows
        $this->_id = $dbRow['id'];
        $this->_userName = $dbRow['username'];
        $this->_password = $dbRow['password'];
        $this->_userType = $dbRow['userType'];
    }

    // accessors
    public function getID() { // id accessor
        return $this->_id;
    }

    public function getUserName() { // username accessor
        return $this->_userName;
    }

    public function getPassword() { // password accessor
        return $this->_password;
    }

    public function getUserType() { // user type accessor
        return $this->_userType;
    }

}


