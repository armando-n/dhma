<?php
class SleepMeasurement extends GenericModelObject {
    
    private $formInput;
    private $userName;
    private $datetime;
    private $duration;
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
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
        return $this->duration;
    }
    
    public function getParameters() {
        $params = array(
                "userName" => $this->userName,
                "datetime" => $this->datetime,
                "duration" => $this->duration
        );
    
        return $params;
    }
    
    public function __toString() {
        $dtVal = is_object($this->datetime) ? $this->datetime->format("Y-m-d h:i:s a") : '';
        $str =
            "User Name: [" .$this->userName . "]\n" .
            "Date and Time: [" . $dtVal . "]\n" .
            "Sleep Duration: [" . $this->duration . "]";
        
        return $str;
    }
    
    protected function initialize() {
        $this->errors = array();
        $this->errorCount = 0;
        
        if (is_null($this->formInput)) {
            $this->userName = '';
            $this->datetime = '';
            $this->duration = '';
        } else {
            $this->validateUserName();
            $this->validateDateAndTime();
            $this->validateMeasurement();
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
    
    private function validateDateAndTime() {
        $date = $this->extractForm($this->formInput, "date");
        $time = $this->extractForm($this->formInput, "time");
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
    
    private function validateMeasurement() {
        $this->duration = $this->extractForm($this->formInput, "duration");
        
        if (empty($this->duration)) {
            $this->setError("duration", "DURATION_EMPTY");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^\d+$/"));
        if (!filter_var($this->duration, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("duration", "DURATION_HAS_INVALID_CHARS");
            return;
        }
        
        $this->duration = (int)$this->duration;
    }

}
?>