<?php
if (!isset($_SESSION))
    session_start();

class ProfileController {
    
    private static $imgDir = 'images/profile/';
    
    public static function run() {
        
        //self::$imgDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dhma_images' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR;
        
        if (!isset($_SESSION) || !isset($_SESSION['base'])) {
            ?><p>Error: session data not found.</p><?php
            return;
        }
        
        if (!isset($_SESSION['action']))
            ProfileController::redirect('home', 'danger', 'Error: Unrecognized command');
        
        // action: show -> arguments: none or userName of existing user
        else if ($_SESSION['action'] === 'show')
            ProfileController::show();
        
        // action: edit -> arguments: view or post
        else if ($_SESSION['action'] === 'edit')
            ProfileController::edit();
        else
            ProfileController::redirect('home', 'danger', 'Error: Unrecognized action requested');
    }
    
    private static function show() {
        
        // no arguments: requesting own profile; show it
        if (!isset($_SESSION['arguments'])) {

            // send user to login view if not logged in;
            if (!isset($_SESSION['profile'])) {
                ProfileController::redirect('login_view', 'warning', 'You must be logged in to edit your profile');
                return;
            }
        
            // user logged in; show profile
            ProfileView::showProfile($_SESSION['profile']);
            
        }
        
        // argument exists: requesting someone else's profile
        else {
            $profile = UserProfilesDB::getUserProfileBy('userName', $_SESSION['arguments']);

            // go to users view if requested profile not found
            if ($profile === null) {
                ProfileController::redirect('members_show', 'danger', 'Unable to find user "' . htmlspecialchars($_SESSION['arguments']) . '"');
                return;
            }
        
            // profile found; show it
            ProfileView::showProfile($profile);
        }
    }
    
    private static function edit() {
        
        // not logged in: send user to login view
        if (!isset($_SESSION['profile']))
            ProfileController::redirect('login_view', 'warning', 'You must be logged in to edit your profile');
        
        // no arguments: arguments must be set for edit action; redirect if not
        else if (!isset($_SESSION['arguments']))
            ProfileController::redirect('home', 'danger', 'Unrecognized command');
        
        // argument 'show': requesting edit form
        else if (explode('_', $_SESSION['arguments'])[0] === 'show') {
            $args = explode('_', $_SESSION['arguments']);
            if (array_key_exists(1, $args))
                $_SESSION['profileOld'] = UserProfilesDB::getUserProfileBy('userName', $args[1]);
            ProfileView::showEditForm();
        }
        
        // argument 'post' posting profile edits
        else if ($_SESSION['arguments'] === 'post') {
            // process uploaded image
            if (isset($_FILES['picture']) && !empty($_FILES['picture']['name']))
                self::processImage();
            else
                $_POST['picture'] = $_POST['oldPicture'];
            
            if (!isset($_POST))
                throw new Exception("Cannot process profile edit: post data not found");
            $profile = new UserProfile($_POST);

            // submission had errors; re-show edit form
            if ($profile->getErrorCount() > 0) {
                $_SESSION['profileEdit'] = $profile;
                $_SESSION['alertType'] = 'danger';
                $_SESSION['flash'] = 'Edit failed. Correct any errors before submitting changes.';
                ProfileView::showEditForm();
                unset($_SESSION['profileEdit']);
            }
            
            // edit succeeded
            else {
                $oldProfile = isset($_SESSION['profileOld']) ? $_SESSION['profileOld'] : $_SESSION['profile'];
                UserProfilesDB::editUserProfile($oldProfile, $profile);
                unset($_SESSION['profileOld']);
                $_SESSION['profile'] = UserProfilesDB::getUserProfileBy('userName', $_SESSION['profile']->getUserName());
                $_SESSION['alertType'] = 'success';
                $_SESSION['flash'] = 'Profile edited';
                ProfileView::showProfile($profile);
            }
        }
    }
    
    private static function processImage() {
        $filename = $_FILES['picture']['name'];
        $filetype = $_FILES['picture']['type'];
        $tmp_name = $_FILES['picture']['tmp_name'];
        $extension = strtolower(pathinfo($filename)['extension']);
    
        // make sure file is an image
        if (strncmp($filetype, 'image', strlen('image')) !== 0) {
            self::alertMessage('danger', 'Signup failed: ' . htmlspecialchars($filename) . 'is not an image file. It has type: ' . $filetype . '; ');
            SignupView::show();
            return;
        }
    
        // move the uploaded file to permanent location
        if (is_uploaded_file($tmp_name))
            move_uploaded_file($tmp_name, self::$imgDir . $_POST['userName'] . '.' . $extension);
        else {
            self::alertMessage('danger', 'Signup failed: ' . htmlspecialchars($tmp_name) . 'was not found or is not an uploaded file.');
            SignupView::show();
            return;
        }

        // add image file name to post data
        $_POST['picture'] = $_POST['userName'] . '.' . $extension;
    }
    
    private static function redirect($control = '', $alertType = 'info', $message = null) {
        if (!is_null($message)) {
            $_SESSION['alertType'] = $alertType;
            $_SESSION['flash'] = $message;
        }
        if (!empty($control))
            $control = '/' . $control;
        
        header('Location: https://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
    
}
?>