<?php
include_once("Messages.class.php");
include_once("../resources/Utilities.class.php");
class UserData {
    
    private $formInput;
    private $errors;
    private $errorCount;
    private $firstName;
    private $lastName;
    private $email;
    private $passHash;
    private $phone;
    private $gender;
    private $dob;
    private $country;
    private $pic;
    private $isProfilePublic;
    private $isPicturePublic;
    private $sendReminders;
    private $stayLoggedIn;
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
        if (!isset($formInput["userName"]) || !isset($formInput["email"]) ||
                !isset($formInput["password"]))
                    alert("Error: user name, email address, and password must be set.");
        
                $this->userName = $userName;
                $this->email = $email;
                $this->passHash = $password; // change this eventually
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
    
    public function getFirstName() {
        return $this->firstName;
    }
    
    public function getLastName() {
        return $this->firstName;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function getPasswordHash() {
        return $this->passHash;
    }
    
    public function getPhoneNumber() {
        return $this->phone;
    }
    
    public function getGender() {
        return $this->gender;
    }
    
    public function getDOB() {
        return $this->dob;
    }
    
    public function getCountry() {
        return $this->country;
    }
    
    public function getPicture() {
        return $this->pic;
    }
    
    public function isProfilePublic() {
        return $this->isProfilePublic;
    }
    
    public function isPicturePublic() {
        return $this->isPicturePublic;
    }
    
    public function isSendRemindersSet() {
        return $this->sendReminders;
    }
    
    public function isStayLoggedInSet() {
        return $this->stayLoggedIn;
    }
    
    // Returns data fields as an associative array
    public function getParameters() {
        $paramArray = array(
                "firstName" => $this->firstName,
                "lastName" => $this->lastName,
                "email" => $this->email,
                "passwordHash" => $this->passHash,
                "phoneNumber" => $this->phone,
                "gender" => $this->gender,
                "dob" => $this->dob,
                "country" => $this->country,
                "picture" => $this->pic,
                "profilePublic" => $this->isProfilePublic,
                "picturePublic" => $this->isPicturePublic,
                "sendReminders" => $this->sendReminders,
                "stayLoggedIn" => $this->stayLoggedIn
        );
        
        return $paramArray;
    }
    
    public function __toString() {
        $str =
                "First name: [" . $this->firstName . "] " .
                "Last name: [" . $this->lastName . "] " .
                "E-mail address: [" . $this->email . "] " .
                "Password hash: [" . $this->passHash . "] " .
                "Phone number: [" . $this->phone . "] " .
                "Gender: [" . $this->gender . "]" .
                "Date of birth: [" . $this->dob . "] " .
                "Country: [" . $this->country . "] " .
                "Picture: [" . $this->pic . "] " .
                "Profile public: [" . $this->isProfilePublic . "] " .
                "Picture public: [" . $this->isPicturePublic . "] " .
                "Send reminders: [" . $this->sendReminders . "] " .
                "Stay logged in: [" . $this->stayLoggedIn . "] ";
        
        return $str;
    }
    
    private function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->formInput)) {
            $this->firstName = "";
            $this->lastName = "";
            $this->email = "";
            $this->passHash = "";
            $this->phone = "";
            $this->gender = "";
            $this->dob = "";
            $this->country = "";
            $this->pic = "";
            $this->isProfilePublic = "";
            $this->isPicturePublic = "";
            $this->sendReminders = "";
            $this->stayLoggedIn = "";
        }
        else {
            $this->validateFirstName();
            $this->validateLastName();
            $this->validateEmail();
            $this->validatePass();
            $this->validatePhone();
            $this->validateGender();
            $this->validateDOB();
            $this->validateCountry();
            $this->validatePicture();
            $this->validateIsProfilePublic();
            $this->validateIsPicturePublic();
            $this->validateSendReminders();
            $this->validateStayLoggedIn();
        }
    }
    
    private function validateFirstName() {
        
    }
    
    private function validateLastName() {
    
    }
    
    private function validateEmail() {
    
    }
    
    private function validatePass() {
    
    }
    
    private function validatePhone() {
    
    }
    
    private function validateGender() {
    
    }
    
    private function validateDOB() {
    
    }
    
    private function validateCountry() {
    
    }
    
    private function validatePicture() {
    
    }
    
    private function validateIsProfilePublic() {
    
    }
    
    private function validateIsPicturePublic() {
    
    }
    
    private function validateSendReminders() {
    
    }
    
    private function validateStayLoggedIn() {
    
    }
}
?>