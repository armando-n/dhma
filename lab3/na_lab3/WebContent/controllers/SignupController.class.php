<?php
class SignupController {
    
    public static function run() {
        
        if (!isset($_SESSION) || !isset($_SESSION['base'])) {
            ?><p>Error: session data not found.</p><?php
            return;
        }
            
        if (!isset($_SESSION['action']))
            SignupController::redirect('home', 'Error: Unrecognized command');
        
        else if ($_SESSION['action'] === 'show')
            SignupView::show();
        
        else if ($_SESSION['action'] === 'post')
            self::post();
        
        else
            SignupController::redirect('home', 'Error: Unrecognized action requested');
    }
    
    private static function post() {
        $user = new User($_POST);
        $profile = new UserProfile($_POST);
        
        // post data has errors. show sign up page with error message
        if ($user->getErrorCount() > 0 || $profile->getErrorCount() > 0) {
            $_SESSION['userSignup'] = $user;
            $_SESSION['profileSignup'] = $profile;
            $_SESSION['flash'] = 'Sign up failed. Correct any errors and try again.';
            SignupView::show();
            return;
        }
        
        // user name already taken. show sign up page with error message 
        $existingUser = UsersDB::getUserBy('userName', $user->getUserName());
        if (!is_null($existingUser)) {
            $user->setError('userName', 'USER_NAME_EXISTS');
            $profile->setError('userName', 'USER_NAME_EXISTS');
            $_SESSION['userSignup'] = $user;
            $_SESSION['profileSignup'] = $profile;
            $_SESSION['flash'] = 'Sign up failed. Correct any errors and try again.';
            SignupView::show();
        }
        
        // user name available
        else {
            $userID = UsersDB::addUser($user);
    
            // add user to database failed. re-show signup view with error message
            if ($user->getErrorCount() > 0 || $userID == -1) {
                $_SESSION['flash'] = "Error: Failed to add member. Try again later.";
                SignupView::show();
                return;
            }
    
            // add profile to database failed. delete user and re-show sign up view with error message
            UserProfilesDB::addUserProfile($profile, $userID);
            if ($profile->getErrorCount() > 0) {
                // TODO once implemented, call UsersDB::deleteUser($user->getUserName())
                $_SESSION['flash'] = "Error: Failed to add profile. Try again later.";
                SignupView::show();
                return;
            }
    
            // user and profile successfully added to database. show profile
            unset($_SESSION['userSignup']);
            unset($_SESSION['profileSignup']);
            $_SESSION['profile'] = $profile;
            self::redirect('profile_show', 'Welcome to DHMS! You can review your profile below.');
        }
    }
    
    private static function redirect($control = '', $message = null) {
        if (!is_null($message))
            $_SESSION['flash'] = $message;
        if (!empty($control))
            $control = '/' . $control;
        
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
}
?>