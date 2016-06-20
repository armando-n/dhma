<?php
class LoginController {
    public static function run() {
        
        if (!isset($_SESSION) || !isset($_SESSION['action'])) {
            LoginView::show();
            return;
        }
        
        switch ($_SESSION['action']) {
            case 'logout': self::logout(); break;
            case 'login': self::login(); break;
            case 'show': LoginView::show(); break;
            default:
                self::alertMessage('danger', 'Unrecognized command');
                LoginView::show();
                return;
        }
    }
    
    private static function login() {
        
        if (!isset($_POST) || !isset($_POST['userName']) || !isset($_POST['password'])) {
            self::alertMessage('danger', 'Error: Login data not found. Try again.');
            LoginView::show();
            return;
        }
        
        $user = UsersDB::getUserBy('userName', $_POST['userName']);
        $profile = UserProfilesDB::getUserProfileBy('userName', $_POST['userName']);
        
        // user name not found or wrong password
        if (is_null($user) || is_null($profile) || !$user->verifyPassword($_POST['password'])) {
            self::alertMessage('danger', 'Login failed. User name or password incorrect.');
            $_SESSION['user'] = $_POST['userName'];
            LoginView::show();
            return;
        }
        
        // login successful; clean up, store profile & admin flag, then show measurements page
        
        foreach ($_POST as $key => $value)
            unset($_POST[$key]);
        
        $_SESSION['profile'] = $profile;
        if ($user->isAdministrator())
            $_SESSION['admin'] = true; 
        
        self::redirect('measurements_show', 'success', 'Welcome back, ' . $profile->getFirstName() . '!');
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
        self::redirect('home', 'success', 'You have been successfully logged out');
    }
    
    private static function redirect($command = '', $alertType = 'info', $message = '') {
        if (strlen($message) > 0)
            self::alertMessage($alertType, $message);
        if (!empty($command))
            $command = '/' . $command;
    
        header('Location: https://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $command);
    }
    
    private static function alertMessage($alertType, $alertMessage) {
        $_SESSION['alertType'] = $alertType;
        $_SESSION['flash'] = $alertMessage;
    }
}
?>