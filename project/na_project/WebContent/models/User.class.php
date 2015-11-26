<?php
class User extends GenericModelObject implements JsonSerializable {
    
    private $formInput;
    private $userName;
    private $password; // this is actually the hash of the password
    private $isAdministrator;
    
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
    
    public function isAdministrator() {
        return $this->isAdministrator;
    }
    
    public function verifyPassword($pass) {
        return password_verify($pass, $this->password);
    }
    
    public function getParameters() {
        $paramArray = array(
                "userName" => $this->userName,
                "password" => $this->password,
                "isAdministrator" => $this->isAdministrator
        );
        return $paramArray;
    }
    
    public function __toString() {
        $str =
            "User name: [" . $this->userName . "]\n" .
            "Password: [" . $this->password . "]\n" .
            "Is administrator?: [" . $this->isAdministrator . "]";
        return $str;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->formInput)) {
            $this->userName = "";
            $this->password = "";
            $this->isAdministrator = "";
        }
        else {
            $this->validateUserName();
            $this->validatePassword();
            $this->validateIsAdministrator();
        }
    }
    
    private function validateUserName() {
        $this->userName = $this->extractForm($this->formInput, "userName");
        if (empty($this->userName)) {
            $this->setError("userName", "USER_NAME_EMPTY");
            return;
        }
        
        if (strlen($this->userName) > 20) {
            $this->setError("userName", "USER_NAME_TOO_LONG");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^[a-zA-Z0-9-]+$/"));
        if (!filter_var($this->userName, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("userName", "USER_NAME_HAS_INVALID_CHARS");
            return;
        }
    }
    
    private function validatePassword() {
        $pass = $this->extractForm($this->formInput, "password");
        $pass1 = $this->extractForm($this->formInput, "password1");
        $pass2 = $this->extractForm($this->formInput, "password2");
        if (!empty($pass1) || !empty($pass2))
            self::validatePassword_Signup($pass1, $pass2);
        else
            $this->password = $pass;
    }
    
    private function validatePassword_Signup($pass1, $pass2) {
        if ($pass1 !== $pass2) {
            $this->setError('password', 'PASSWORDS_DO_NOT_MATCH');
            return;
        }
        
        if (strlen($pass1) == 0) {
            $this->setError('password', 'PASSWORD_EMPTY');
            return;
        }
        
        if (strlen($pass1) < 6) {
            $this->setError('password', 'PASSWORD_TOO_SHORT');
            return;
        }
        
        if (strlen($pass1) > 20) {
            $this->setError("password", "PASSWORD_TOO_LONG");
            return;
        }
        
        $this->password = password_hash($pass1, PASSWORD_DEFAULT);
    }
    
    private function validateIsAdministrator() {
        $this->isAdministrator = $this->extractForm($this->formInput, "isAdministrator");
        $this->isAdministrator = empty($this->isAdministrator) ? false : true;
    }
    
    public function jsonSerialize() {
        $object = new stdClass();
        $object->userName = $this->userName;
        $object->isAdministrator = $this->isAdministrator;
        
        return $object;
    }
    
}

?>