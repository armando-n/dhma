<?php
class CalorieMeasurement extends GenericModelObject {
    
    private $formInput;
    private $userName;
    private $datetime;
    private $notes;
    private $calories;
    
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
        return is_object($this->datetime) ? $this->datetime->format("h:i a") : '';
    }
    
    public function getNotes() {
        return $this->notes;
    }
    
    public function getMeasurement() {
        return $this->calories;
    }
    
    public function getParameters() {
        $params = array(
                "userName" => $this->userName,
                "dateAndTime" => $this->datetime->format("Y-m-d H:i"),
                "notes" => $this->notes,
                "calories" => $this->calories
        );
    
        return $params;
    }
    
    public function __toString() {
        $dtVal = is_object($this->datetime) ? $this->datetime->format("Y-m-d h:i:s a") : '';
        $str =
            "User Name: [" . $this->userName . "]\n" .
            "Date and Time: [" . $dtVal . "]\n" .
            "Calories Consumed: [" . $this->calories . "]\n" .
            "Notes: [" . $this->notes . "]";
        
        return $str;
    }
    
    protected function initialize() {
        $this->errors = array();
        $this->errorCount = 0;
        
        if (is_null($this->formInput)) {
            $this->userName = '';
            $this->datetime = new DateTime();
            $this->notes = '';
            $this->calories = '';
        } else {
            $this->validateUserName();
            $this->validateDateAndTime();
            $this->validateNotes();
            $this->validateMeasurement();
        }
    }
    
    private function validateUserName() {
        $this->userName = $this->extractForm($this->formInput, "userName");
        if (empty($this->userName)) {
            $this->setError("userName", "USER_NAME_EMPTY");
            return;
        }
    
        $options = array("options" => array("regexp" => "/^[a-zA-Z0-9_-]+$/"));
        if (!filter_var($this->userName, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("userName", "USER_NAME_HAS_INVALID_CHARS");
            return;
        }
        
        if (strlen($this->userName) > 20) {
            $this->setError("userName", "USER_NAME_TOO_LONG");
            return;
        }
    }
    
    private function validateDateAndTime() {
        // the date and time may be present as a single value or as separate values
        if (array_key_exists('dateAndTime', $this->formInput)) {
            $datetime = $this->extractForm($this->formInput, "dateAndTime");
            list($date, $time) = preg_split("/ /", $datetime);
        } else {
            $date = $this->extractForm($this->formInput, "date");
            $time = $this->extractForm($this->formInput, "time");
        }
        $this->datetime = new DateTime();
    
        if (empty($date)) {
            $this->setError("dateAndTime", "DATE_EMPTY");
            return;
        }
    
        if (empty($time)) {
            $this->setError("dateAndTime", "TIME_EMPTY");
            return;
        }
    
        $options = array("options" => array("regexp" => "/^((\d{4}[\/-]\d\d[\/-]\d\d)|(\d\d[\/-]\d\d[\/-]\d{4}))$/"));
        if (!filter_var($date, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("dateAndTime", "DATE_HAS_INVALID_CHARS");
            return;
        }
    
        $options = array("options" => array("regexp" => "/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/"));
        if (!filter_var($time, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("dateAndTime", "TIME_HAS_INVALID_CHARS");
            return;
        }
    
        try { $dt = new DateTime($date . ' ' . $time); }
        catch (Exception $e) {
            $this->setError("dateAndTime", "DATE_AND_TIME_INVALID");
            return;
        }
    
        $this->datetime = $dt;
    }
    
    private function validateNotes() {
        $this->notes = $this->extractForm($this->formInput, "notes");
        
        if (empty($this->notes))
            return;
        
        if (strlen($this->notes) > 255) {
            $this->setError("notes", "NOTES_ARE_TOO_LONG");
            return;
        }
    }
    
    private function validateMeasurement() {
        $this->calories = $this->extractForm($this->formInput, "calories");
        
        if (empty($this->calories)) {
            $this->setError("calories", "CALORIES_EMPTY");
            return;
        }
        
        $options = array("options" => array("regexp" => "/^\d+$/"));
        if (!filter_var($this->calories, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("calories", "CALORIES_HAS_INVALID_CHARS");
            return;
        }
        
        $this->calories = (int)$this->calories;
    }

}
?>