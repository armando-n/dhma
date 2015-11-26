<?php
class SignupController {
    
    private static $imgDir = 'images/profile/';
    private static $defaultPicture = 'profile_default.png';
    
    public static function run() {
        
        //self::$imgDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dhma_images' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR;
        
        if (!isset($_SESSION) || !isset($_SESSION['base'])) {
            ?><p>Error: session data not found.</p><?php
            return;
        }
            
        if (!isset($_SESSION['action']))
            SignupController::redirect('home', 'danger', 'Error: Unrecognized command');
        
        else if ($_SESSION['action'] === 'show')
            SignupView::show();
        
        else if ($_SESSION['action'] === 'post')
            self::post();
        
        else if ($_SESSION['action'] === 'loadimage')
            self::loadImage();
        
        else
            SignupController::redirect('home', 'danger', 'Error: Unrecognized action requested');
    }
    
    private static function post() {
        // handle image file upload
        if (isset($_FILES['picture']) && !empty($_FILES['picture']['name']))
            self::processImage();
        else
            $_POST['picture'] = self::$defaultPicture;
        
        // create user and profile objects with submitted data
        $user = new User($_POST);
        $profile = new UserProfile($_POST);
        
        // post data has errors. show sign up page with error message
        if ($user->getErrorCount() > 0 || $profile->getErrorCount() > 0) {
            $_SESSION['userSignup'] = $user;
            $_SESSION['profileSignup'] = $profile;
            self::alertMessage('danger', 'Sign up failed. Correct any errors and try again.');
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
            self::alertMessage('danger', 'Sign up failed. Correct any errors and try again.');
            SignupView::show();
        }
        
        // user name available
        else {
            $userID = UsersDB::addUser($user);
    
            // add user to database failed. re-show signup view with error message
            if ($user->getErrorCount() > 0 || $userID == -1) {
                self::alertMessage('danger', 'Error: Failed to add member. Try again later.');
                SignupView::show();
                return;
            }
    
            // add profile to database failed. delete user and re-show sign up view with error message
            UserProfilesDB::addUserProfile($profile, $userID);
            if ($profile->getErrorCount() > 0) {
                self::alertMessage('danger', 'Error: Failed to add profile. Try again later.');
                UsersDB::deleteUser($profile->getUserName());
                SignupView::show();
                return;
            }
    
            // user and profile successfully added to database. show profile
            unset($_SESSION['userSignup']);
            unset($_SESSION['profileSignup']);
            $_SESSION['profile'] = $profile;
            self::redirect('profile_show', 'success', 'Welcome to DHMS! You can review your profile below.');
        }
    }
    
//     private static function loadImage() {
//         if (!isset($_FILES['picture']) || empty($_FILES['picture']['name'])) {
//             echo '{"error":"Image not found"}';
//             return;
//         }
        
//         $filename = $_FILES['picture']['name'];
//         $filetype = $_FILES['picture']['type'];
//         $tmp_name = $_FILES['picture']['tmp_name'];
//         $extension = strtolower(pathinfo($filename)['extension']);
        
//         // make sure file is an image
//         if (strncmp($filetype, 'image', strlen('image')) !== 0) {
//             echo '{"error":"' .htmlspecialchars($filename). ' is not an image file. It has type: ' .$filetype. '"}';
//             return;
//         }
        
//         // move the uploaded file to permanent location
//         $permlocation = self::$imgDir . 'temp/' . $_POST['userName'] . '.' . $extension;
//         if (is_uploaded_file($tmp_name))
//             move_uploaded_file($tmp_name, $permlocation);
//         else {
//             echo '{"error":"' .htmlspecialchars($tmp_name). ' was not found or is not an uploaded file"}';
//             return;
//         }
        
//         echo '{"imgsrc":"' .$permlocation. '"}';
//     }
    
    private static function processImage() {
        $filename = $_FILES['picture']['name'];
        $filetype = $_FILES['picture']['type'];
        $tmp_name = $_FILES['picture']['tmp_name'];
        $extension = strtolower(pathinfo($filename)['extension']);
    
        // make sure file is an image
        if (strncmp($filetype, 'image', strlen('image')) !== 0) {
            self::alertMessage('danger', 'Signup failed: ' . htmlspecialchars($filename) . ' is not an image file. It has type: ' . $filetype . '; ');
            SignupView::show();
            return;
        }
    
        // move the uploaded file to permanent location
        if (is_uploaded_file($tmp_name))
            move_uploaded_file($tmp_name, self::$imgDir . $_POST['userName'] . '.' . $extension);
        else {
            self::alertMessage('danger', 'Signup failed: ' . htmlspecialchars($tmp_name) . ' was not found or is not an uploaded file.');
            SignupView::show();
            return;
        }

        // add image file name to post data
        $_POST['picture'] = $_POST['userName'] . '.' . $extension;
    }
    
    private static function alertMessage($alertType, $alertMessage) {
        $_SESSION['alertType'] = $alertType;
        $_SESSION['flash'] = $alertMessage;
    }
    
    private static function redirect($control = '', $alertType = 'info', $message = null) {
        if (!is_null($message)) {
            $_SESSION['alertType'] = $alertType;
            $_SESSION['flash'] = $message;
        }
        if (!empty($control))
            $control = '/' . $control;
        
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
}
?>