<?php
class DemoController {
    private static $defaultUserName = 'member';
    private static $defaultPassword = 'pass123';
    
    public static function run() {
        
        if (isset($_SESSION) && isset($_SESSION['profile']))
            self::logout();
        
        self::useExisting();
        
    }
    
    private static function useExisting() {
        $_POST['userName'] = self::$defaultUserName;
        $_POST['password'] = self::$defaultPassword;
        
        $user = UsersDB::getUserBy('userName', $_POST['userName']);
        $profile = UserProfilesDB::getUserProfileBy('userName', $_POST['userName']);
        
        // user name not found or wrong password
        if (is_null($user) || is_null($profile) || !$user->verifyPassword($_POST['password'])) {
            self::alertMessage('danger', 'Login failed. User name or password incorrect.');
            $_SESSION['user'] = $_POST['userName'];
            LoginView::show();
            return;
        }
        
        // login success
        else {
            foreach ($_POST as $key => $value)
                unset($_POST[$key]);
        
            if ($user->isAdministrator())
                $_SESSION['admin'] = true;
    
            $_SESSION['profile'] = $profile;
            $_SESSION['action'] = 'show';
            $_SESSION['arguments'] = 'all';
            MeasurementsController::run();
        }
    }
    
    private static function logout() {
        // end session (but keep base)
        $base = $_SESSION['base'];
        $dbName = $_SESSION['dbName'];
        $configFile = $_SESSION['configFile'];
        session_destroy();
        session_regenerate_id(true);
        
        // start new session
        session_start();
        $_SESSION['base'] = $base;
        $_SESSION['dbName'] = $dbName;
        $_SESSION['configFile'] = $configFile;
        $_SESSION['styles'] = array();
        $_SESSION['scripts'] = array();
        $_SESSION['libraries'] = array();
    }
    
    private static function alertMessage($alertType, $alertMessage) {
        $_SESSION['alertType'] = $alertType;
        $_SESSION['flash'] = $alertMessage;
    }
}
?>