<?php
class User extends GenericModelObject {
    
    private $formInput;
    private $userName;
    private $password;
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
    }
    
    public function getUserName() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function getParameters() {
        $paramArray = array(
                "userName" => $this->userName,
                "password" => $this->password
        );
        return $paramArray;
    }
    
    public function __toString() {
        $str =
            "User name: [" . $this->userName . "]\n" .
            "Password: [" . $this->password . "]";
        return $str;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->formInput)) {
            $this->userName = "";
            $this->password = "";
        }
        else {
            $this->validateUserName();
            $this->validatePassword();
        }
    }
    
    private function validateUserName() {
        $this->userName = $this->extractForm($this->formInput, "userName");
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
    
    private function validatePassword() {
        $pass = $this->extractForm($this->formInput, "password");
        $pass1 = $this->extractForm($this->formInput, "password1");
        $pass2 = $this->extractForm($this->formInput, "password2");
        if (!empty($pass1))
            $this->password = $pass1;
        else
            $this->password = $pass;
    
//         if ($pass1 !== $pass2) {
//             $this->setError("password", "PASSWORDS_DO_NOT_MATCH");
//             return;
//         }
    
//         if (empty($this->password)) {
//             $this->setError("password", "PASSWORD_EMPTY");
//             return;
//         }
    
//         if (strlen($this->password) < 6) {
//             $this->setError("password", "PASSWORD_TOO_SHORT");
//             return;
//         }
    
//         if (strlen($this->password) > 20) {
//             $this->setError("password", "PASSWORD_TOO_LONG");
//             return;
//         }
    }
    
}

?>