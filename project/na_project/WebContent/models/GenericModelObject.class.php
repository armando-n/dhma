<?php
abstract class GenericModelObject {
    protected $errors;
    protected $errorCount;
    
    protected function extractForm($formInput, $valueName) {
        $value = "";
        if (isset($formInput[$valueName])) {
            $value = trim($formInput[$valueName]);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
        }
        return $value;
    }
    
    public function getError($errorName) {
        if (isset($this->errors[$errorName]))
            return $this->errors[$errorName];
    
        return "";
    }
    
    public function setError($errorName, $errorValue) {
        $this->errors[$errorName] = trim(Messages::getError($errorValue));
        $this->errorCount++;
    }
    
    public function getErrorCount() {
        return $this->errorCount;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    abstract public function getParameters();
    
    abstract public function __toString();
    
    abstract protected function initialize();
}
?>