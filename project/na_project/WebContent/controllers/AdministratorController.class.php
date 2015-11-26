<?php

class AdministratorController {
    
    public static function run() {
        if (!isset($_SESSION) || !isset($_SESSION['base'])) {
            ?><p>Error: session data not found. Make sure cookies are enabled. <a href="home">Go Home</a></p><?php
            return;
        }
        
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true)
            self::redirect('home', 'danger', 'Permission denied. You do not have permission to access that page.');
        
        else if (!isset($_SESSION['action']))
            self::redirect('home', 'danger', 'Error: action not found.');
        
        else if ($_SESSION['action'] === 'show') {
//             $profiles = UserProfilesDB::getAllUserProfiles();
            $profiles = json_encode(UserProfilesDB::getAllUserProfiles(), JSON_PRETTY_PRINT);
            AdministratorView::show($profiles);
        }
    
//         else if ($_SESSION['action'] === 'post')
//             self::post();

        else
            self::redirect('home', 'danger', 'Error: Unrecognized action requested');
    }
    
    private static function alertMessage($alertType, $alertMessage) {
        $_SESSION['alertType'] = $alertType;
        $_SESSION['flash'] = $alertMessage;
    }
    
    private static function redirect($control = '', $alertType = 'info', $message = null) {
        if (!is_null($message))
            self::alertMessage($alertType, $message);
        if (!empty($control))
            $control = '/' . $control;
    
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
    
}

?>