<?php
if (!isset($_COOKIE['PHPSESSID']))
    session_start();

class LoginController {
    public static function run() {
        
        // user is logging out
        if (isset($_SESSION['action']) && $_SESSION['action'] === 'logout') {
            $base = $_SESSION['base'];
            session_destroy();
            session_regenerate_id(true);
            
            session_start();
            $_SESSION['flash'] = 'You have been successfully logged out';
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $base);
//                 header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base']);
        }
        
        // user is logging in
        else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userName']) && isset($_POST['password'])) {
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
                $_SESSION['flash'] = 'Welcome back, ' . $profile->getFirstName() . '!';
                $_SESSION['profile'] = $profile;
                HomeView::show();
//                 header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base']);
            }
            
            // log in failed; load view w/old values
            else {
                $_SESSION['user'] = $user;
                $_SESSION['loginFailed'] = true;
                LoginView::show();
            }
        }
        
        // user requesting login page
        else
            LoginView::show();
    }
}
?>