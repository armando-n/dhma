<?php
if (!isset($_SESSION))
    session_start();

class ProfileController {
    
    public static function run($dbName = null, $configFile = null) {
        
        if (!isset($_SESSION['action'])) {
            ProfileController::redirect('home', 'Unrecognized command');
            return;
        }
        
        // action: view -> arguments: none or userName of existing user
        if ($_SESSION['action'] === 'view')
            ProfileController::view($dbName, $configFile);
        
        // action: edit -> arguments: view or post
        else if ($_SESSION['action'] === 'edit')
            ProfileController::edit();
    }
    
    private static function view($dbName, $configFile) {
        
        // no arguments: requesting own profile; show it
        if (!isset($_SESSION['arguments'])) {
            
            // send user to login view if not logged in;
            if (!isset($_SESSION['profile'])) {
                ProfileController::redirect('login_view', 'You must be logged in to edit your profile');
                return;
            }
        
            // user logged in; show profile
            ProfileView::showProfile($_SESSION['profile']);
            
        }
        
        // argument exists: requesting someone else's profile
        else {
            $profile = UserProfilesDB::getUserProfileBy('userName', $_SESSION['arguments'], $dbName, $configFile);
        
            // go to users view if requested profile not found
            if ($profile === null) {
                ProfileController::redirect('members_view', 'Unable to find theese user "' . htmlspecialchars($_SESSION['arguments']) . '"');
                return;
            }
        
            // profile found; show it
            ProfileView::showProfile($profile);
        }
    }
    
    private static function edit() {
        
        // not logged in: send user to login view
        if (!isset($_SESSION['profile']))
            ProfileController::redirect('login_view', 'You must be logged in to edit your profile');
        
        // no arguments: arguments must be set for edit action; redirect if not
        else if (!isset($_SESSION['arguments']))
            ProfileController::redirect('home', 'Unrecognized command');
        
        // argument 'view': requesting edit form
        else if ($_SESSION['arguments'] === 'view')
            ProfileView::showEditForm();
        
        // argument 'post' posting profile edits
        else if ($_SESSION['arguments'] === 'post') {
            $profile = new UserProfile($_POST);
            // TODO fix this
            // submission had errors; re-show edit form
            if ($profile->getErrorCount() > 0) {
                $_SESSION['profileEdit'] = $profile;
                $_SESSION['flash'] = 'Edit failed. Correct any errors before submitting changes.';
                ProfileView::showEditForm();
                unset($_SESSION['profileEdit']);
            }
            
            // edit succeeded
            else {
                UserProfilesDB::editUserProfile($_SESSION['profile'], $profile);
                $_SESSION['profile'] = UserProfilesDB::getUserProfileBy('userName', $profile->getUserName());
                ProfileView::showProfile($_SESSION['profile']);
            }
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