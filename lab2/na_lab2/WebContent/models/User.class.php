<?php
include_once("Messages.class.php");
include_once("../resources/Utilities.class.php");
class User {
    
    private $formInput;
    private $errors;
    private $errorCount;
    private $userName;
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
    }
    
    public function getError($errorName) {
        if (isset($this->errors[$errorName]))
            return $this->errors[$errorName];

        return "";
    }
    
    public function setError($errorName, $errorValue) {
        $this->errors[$errorName] =  Messages::getError($errorValue);
        $this->errorCount++;
    }
    
    public function getErrorCount() {
        return $this->errorCount;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getUserName() {
        return $this->userName;
    }
    
    public function getParameters() {
        return array("userName" => $this->userName);;
    }
    
    public function __toString() {
        $str = "User name: " . $this->userName;
        return $str;
    }
    
    private function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->formInput))
            $this->userName = "";
        else
            $this->validateUserName();
    }
    
    private function validateUserName() {
        $this->userName = Utilities::extractForm($this->formInput, "userName");
        if (empty($this->userName)) {
            $this->setError("userName", "USER_NAME_EMPTY");
            return;
        }
        
        if (strlen($this->userName) > 15) {
            $this->setError("userName", "USER_NAME_TOO_LONG");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^[a-zA-Z0-9_-]+$/"));
        if (!filter_var($this->userName, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("userName", "USER_NAME_HAS_INVALID_CHARS");
            return;
        }
    }
    
}

?>