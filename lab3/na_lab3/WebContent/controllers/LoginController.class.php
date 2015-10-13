<?php
if (!isset($_SESSION))
    session_start();

class LoginController {
    public static function run() {
        
        if (!isset($_SESSION['action'])) {
            LoginController::redirect('home', 'Unrecognized command');
            return;
        }
        
        switch ($_SESSION['action']) {
            case 'logout': LoginController::logout(); break;
            case 'login': LoginController::login(); break;
            case 'view': LoginView::show(); break;
            default:
                self::redirect('home', 'Unrecognized command');
                return;
        }
    }
    
    private static function login() {
        
        if (!isset($_POST['userName']) || !isset($_POST['password'])) {
            self::redirect('login_view', 'An error occured. Try again.');
            return;
        }
        
        $user = UsersDB::getUserBy('userName', $_POST['userName']);
        $profile = UserProfilesDB::getUserProfileBy('userName', $_POST['userName']);
        
        // log in successful; go back home
        if (!is_null($user) && $user->getErrorCount() == 0
                && !is_null($profile) && $profile->getErrorCount() == 0
                && $user->getPassword() === $_POST['password']) {
                    unset($_POST['userName']);
                    unset($_POST['password']);
                    unset($_POST['user']);
                    unset($_POST['loginFailed']);
                    $_SESSION['profile'] = $profile;
                    self::redirect('home', 'Welcome back, ' . $profile->getFirstName() . '!');
        }
        
        // log in failed; load view w/old values
        else {
            $_SESSION['user'] = $user;
            $_SESSION['loginFailed'] = true;
            LoginView::show();
        }
    }
    
    private static function logout() {
        // end session (but keep base)
        $base = $_SESSION['base'];
        session_destroy();
        session_regenerate_id(true);
        
        // start new session
        session_start();
        $_SESSION['base'] = $base;
        self::redirect('home', 'You have been successfully logged out');
    }
    
    private static function redirect($control = '', $message = '') {
        if (strlen($message) > 0)
            $_SESSION['flash'] = $message;
        if (!empty($control))
            $control = '/' . $control;
    
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
}
?>