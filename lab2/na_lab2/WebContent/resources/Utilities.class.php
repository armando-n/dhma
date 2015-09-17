<?php
class Utilities {
    
    public static function extractForm($formInput, $valueName) {
        $value = "";
        if (isset($formInput[$valueName])) {
            $value = trim($formInput[$valueName]);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
        }
        return $value;
    }
    
}


?>