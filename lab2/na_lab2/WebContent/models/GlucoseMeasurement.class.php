<?php
class GlucoseMeasurement {
    
    private $formInput;
    private $errors;
    private $errorCount;
    private $userName;
    private $datetime;
    private $glucose;
    private $units; // either mmol/L (millimoles per liter) or mg/dL
    
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
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getErrorCount() {
        return $this->errorCount;
    }
    
    public function getUserName() {
        return $this->userName;
    }
    
    public function getDateTime() {
        return $this->datetime;
    }
    
    public function getDate() {
        return is_object($this->datetime) ? $this->datetime->format("Y-m-d") : '';
    }
    
    public function getTime() {
        return is_object($this->datetime) ? $this->datetime->format("h:i:s a") : '';
    }
    
    public function getMeasurement() {
        return $this->glucose;
    }
    
    public function getUnits() {
        return $this->units;
    }
    
    public function getParameters() {
        $params = array(
                "userName" => $this->userName,
                "datetime" => $this->datetime,
                "units" => $this->units,
                "glucose" => $this->glucose
        );
    
        return $params;
    }
    
    public function __toString() {
        $dtVal = is_object($this->datetime) ? $this->datetime->format("Y-m-d h:i:s a") : '';
        $str =
            "User Name: [" .$this->userName . "]\n" .
            "Date and Time: [" . $dtVal . "]\n" .
            "Units: [" . $this->units . "]\n" .
            "Glucose Levels: [" . $this->glucose . "]";
        
        return $str;
    }
    
    private function initialize() {
        $this->errors = array();
        $this->errorCount = 0;
        
        if (is_null($this->formInput)) {
            $this->userName = '';
            $this->datetime = '';
            $this->units = '';
            $this->glucose = '';
        } else {
            $this->validateUserName();
            $this->validateDateAndTime();
            $this->validateUnits();
            $this->validateMeasurement();
        }
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
    
    private function validateDateAndTime() {
        $date = Utilities::extractForm($this->formInput, "date");
        $time = Utilities::extractForm($this->formInput, "time");
        $this->datetime = '';
    
        if (empty($date)) {
            $this->setError("datetime", "DATE_EMPTY");
            return;
        }
    
        if (empty($time)) {
            $this->setError("datetime", "TIME_EMPTY");
            return;
        }
    
        $options = array("options" => array("regexp" => "/^((\d{4}[\/-]\d\d[\/-]\d\d)|(\d\d[\/-]\d\d[\/-]\d{4}))$/"));
        if (!filter_var($date, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("datetime", "DATE_HAS_INVALID_CHARS");
            return;
        }
    
        $options = array("options" => array("regexp" => "/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/"));
        if (!filter_var($time, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("datetime", "TIME_HAS_INVALID_CHARS");
            return;
        }
    
        try { $dt = new DateTime($date . ' ' . $time); }
        catch (Exception $e) {
            $this->setError("datetime", "DATE_AND_TIME_INVALID");
            return;
        }
    
        $this->datetime = $dt;
    }
    
    private function validateUnits() {
        $this->units = Utilities::extractForm($this->formInput, "units");
        
        if (empty($this->units)) {
            $this->setError("units", "UNITS_EMPTY");
            return;
        }
        
        if (strcasecmp($this->units, 'mmol/L') != 0 && strcasecmp($this->units, 'mg/dL') != 0) {
            $this->setError("units", "UNITS_INVALID");
            return;
        }
    }
    
    private function validateMeasurement() {
        $this->glucose = Utilities::extractForm($this->formInput, "glucose");
        
        if (empty($this->glucose)) {
            $this->setError("glucose", "GLUCOSE_EMPTY");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^((\d+)|(\d*\.\d))$/"));
        if (!filter_var($this->glucose, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("glucose", "GLUCOSE_HAS_INVALID_CHARS");
            return;
        }
        
        if (strcasecmp($this->units, 'mmol/L') == 0)
            $this->glucose = floatval($this->glucose);
        else if (strcasecmp($this->units, 'mg/dL')== 0)
            $this->glucose = (int)$this->glucose;
    }

}
?>