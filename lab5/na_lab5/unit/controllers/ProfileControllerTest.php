<?php
require_once dirname(__FILE__) . '\..\..\WebContent\controllers\ProfileController.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\ProfileView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HomeView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class ProfileControllerTest extends PHPUnit_Framework_TestCase {
    
    private static $profileInput = array(
            "firstName" => "Armando",
            "lastName" => "Navarro",
            "email" => "fdf786@my.utsa.edu",
            "gender" => "male",
            "phone" => "281-555-2180",
            "facebook" => "http://facebook.com/someguy210",
            "dob" => "1983-11-02",
            "country" => "United States of America",
            "theme" => "dark",
            "accentColor" => "#00008b",
            "picture" => "someimage",
            "isProfilePublic" => "on",
            "isPicturePublic" => "on",
            "sendReminders" => "on",
            "stayLoggedIn" => "on",
            "userName" => "armando-n"
    );
    
    private static $badInput = array(
            "firstName" => "Some-Really-Really-Super-Duper-Long-Name",
            "lastName" => '$InvaldName',
            "email" => "fdf786",
            "gender" => "orange",
            "phone" => "281-555-218x",
            "facebook" => "http://face.com/someguy210",
            "dob" => "1983-11-0x",
            "country" => "United States of America",
            "theme" => "middle",
            "accentColor" => "#00008s",
            "picture" => "someimage",
            "isProfilePublic" => "on",
            "isPicturePublic" => "on",
            "showReminders" => "on",
            "stayLoggedIn" => "on"
    );
    
    public function testRun_NoSessionVariables() {
        ob_start();
        self::removeSession();
        ProfileController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, 'session data not found') !== false,
            'It should call run and display an error message when no session data is provided');
    }
    
//    /** @runInSeparateProcess
//     */
//     public function testRun_NoAction() {
//         ob_start();
//         self::checkSession();
//         unset($_SESSION['action']);
//         ProfileController::run();
//         $output = ob_get_clean();
        
//         $this->assertTrue(strstr($output, '<section id="site-info">') !== false,
//             'It should call run and redirect to the home page when no session data is provided.');
//         $this->assertTrue(strstr($output, 'Unrecognized command') !== false,
//             'It should call run and display an error message when no session data is provided.');
//     }
    
//    /** @runInSeparateProcess
//     */
//     public function testRun_InvalidAction() {
//         ob_start();
//         self::checkSession();
//         $_SESSION['action'] = 'invalidAction';
//         ProfileController::run();
//         $output = ob_get_clean();
        
//         $this->assertTrue(strstr($output, '<section id="site-info">') !== false,
//             'It should call run and redirect to the home page when an invalid action is provided.');
//         $this->assertTrue(strstr($output, 'Unrecognized action') !== false,
//             'It should call run and display an error message when an invalid action is provided.');
//     }
    
//     /** @runInSeparateProcess
//      */
//     public function testRun_Show_NoArgs_LoggedOut() {
//         ob_start();
//         self::checkSession();
//         unset($_SESSION['arguments']);
//         unset($_SESSION['profile']);
//         $_SESSION['action'] = 'show';
//         ProfileController::run();
//         $output = ob_get_clean();
        
//         $this->assertTrue(strstr($output, '<h2>Log In</h2>') !== false,
//             'It should call run and redirect to the login page when the action is "show" with no arguments and the user is logged out (i.e. no "profile" session variable).');
//         $this->assertTrue(strstr($output, 'You must be logged in') !== false,
//             'It should call run and display an error message when the action is "show" with no arguments and the user is logged out (i.e. no "profile" session variable).');
//     }
    
    public function testRun_Show_NoArgs_LoggedIn() {
        ob_start();
        self::checkSession();
        unset($_SESSION['arguments']);
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $_SESSION['action'] = 'show';
        ProfileController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>armando-n\'s Profile</h2>') !== false,
            'It should call run and include in the output a reference to the user\'s name when the action is "show" with no arguments and the user is logged in (i.e. "profile" session variable is set).');
        $this->assertTrue(strstr($output, 'First Name: Armando') !== false,
            'It should call run and include in the output a reference to the user\'s first name when the action is "show" with no arguments and the user is logged in (i.e. "profile" session variable is set).');
    }
    
//     /** @runInSeparateProcess
//      */
//     public function testRun_Show_InvalidArg() {
//         self::checkSession();
//         $_SESSION['action'] = 'show';
//         $_SESSION['arguments'] = 'invalidUserName';
//         ob_start();
//         ProfileController::run();
//         $output = ob_get_clean();
        
//         $this->assertTrue(strstr($output, '<th>E-mail</th>') !== false,
//             'It should call run and redirect to the members list page when the action is "show" with an invalid user name argument. Output was: ' . $output);
//         $this->assertTrue(strstr($output, 'Unable to find user') !== false,
//             'It should call run and show an error message when the action is "show" with an invalid user name argument');
//     }
    
    public function testRun_Show_ValidArg() {
        self::checkSession();
        $_SESSION['action'] = 'show';
        $_SESSION['arguments'] = 'armando-n';
        ob_start();
        ProfileController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>armando-n\'s Profile</h2>') !== false,
            'It should call run and show the requested profile when the action is "show" with a valid user name argument.');
        $this->assertTrue(strstr($output, 'First Name: Armando') !== false,
            'It should call run and include in the output a reference to the user\'s first name when the action is "show" with a valid user name argument');
    }
    
//     /** @runInSeparateProcess
//      */
//     public function testRun_Edit_LoggedOut() {
//         self::checkSession();
//         unset($_SESSION['profile']);
//         $_SESSION['action'] = 'edit';
//         $_SESSION['arguments'] = 'show';
//         ob_start();
//         ProfileController::run();
//         $output = ob_get_clean();
        
//         $this->assertTrue(strstr($output, '<section id="site-info">') !== false,
//             'It should call run and redirect to the home page when the action is "edit" and the user is logged out (i.e. no "profile" session variable is set).');
//         $this->assertTrue(strstr($output, 'Unrecognized action') !== false,
//             'It should call run and display an error message when the action is "edit" and the user is logged out (i.e. no "profile" session variable is set).');
//     }

    public function testRun_Edit_Show_LoggedIn() {
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $_SESSION['action'] = 'edit';
        $_SESSION['arguments'] = 'show';
        ob_start();
        ProfileController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<input type="text" name="firstName" value="Armando"') !== false,
            'It should call run and show the edit profile form when the action is "edit", the argument is "show", and the user is logged in (i.e. a valid "profile" is set)');
    }
    
    public function testRun_Edit_PostNoErrors_LoggedIn() {
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $_POST = self::$profileInput;
        $_SESSION['action'] = 'edit';
        $_SESSION['arguments'] = 'post';
        ob_start();
        ProfileController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>armando-n\'s Profile</h2>') !== false,
            'It should call run and show the user profile when the action is "edit", the argument is "post", the user is logged in, and the edits in $_POST are valid');
    }
    
    public function testRun_Edit_PostWithErrors_LoggedIn() {
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $_SESSION['action'] = 'edit';
        $_SESSION['arguments'] = 'post';
        $_POST = self::$profileInput;
        $_POST['firstName'] = 'SomeReallyReallyReallyLongUserName';
        ob_start();
        ProfileController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<input type="text" name="firstName" value="') !== false,
            'It should call run and show the edit profile form when the action is "edit", the argument is "post", the user is logged in, and the edits in $_POST are invalid.');
        $this->assertTrue(strstr($output, 'Your first name cannot contain more than') !== false,
            'It should call run and show an error message when the action is "edit", the argument is "post", the user is logged in, and the edits in $_POST are invalid. Output: ' . $output);
    }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION))
            $_SESSION = array();
        if (!isset($_SERVER['HTTP_HOST']))
            $_SERVER['HTTP_HOST'] = 'localhost';
        if (!isset($_SESSION['base']))
            $_SESSION['base'] = 'na_lab3';
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
        if (!isset($_SESSION['testing']))
            $_SESSION['testing'] = true;
    }
    
    private function removeSession() {
        if (!isset($_SESSION))
            return;
        unset($_SESSION['base']);
        unset($_SESSION['control']);
        unset($_SESSION['action']);
        unset($_SESSION['arguments']);
        unset($_SESSION['dbName']);
        unset($_SESSION['configFile']);
        unset($_SESSION['testing']);
        unset($_SESSION['user']);
        unset($_SESSION['profile']);
        unset($_SESSION['profileEdit']);
        unset($_SESSION);
    }
    
}

?>