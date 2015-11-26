<?php
require_once dirname(__FILE__) . '\..\..\WebContent\controllers\SignupController.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\SignupView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HomeView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class SignupControllerTest extends PHPUnit_Framework_TestCase {
    
    private static $goodInput = array(
        "firstName" => "Armando",
        "lastName" => "Navarro",
        "email" => "fdf787@my.utsa.edu",
        "gender" => "male",
        "phone" => "281-555-2181",
        "facebook" => "http://facebook.com/someguy211",
        "dob" => "1983-11-02",
        "country" => "United States of America",
        "theme" => "dark",
        "accentColor" => "#00008b",
        "picture" => "someimage",
        "isProfilePublic" => "on",
        "isPicturePublic" => "on",
        "sendReminders" => "on",
        "stayLoggedIn" => "on",
        "userName" => "armando-n2",
        "password1" => "validPassword",
        "password2" => "validPassword"
    );
    
    private static $dupInput = array(
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
        "userName" => "armando-n",
        "password1" => "validPassword",
        "password2" => "validPassword"
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
        "stayLoggedIn" => "on",
        "userName" => "armando#",
        "password1" => "some",
        "password2" => "thing"
    );
    
    /*
     * @runInSeparateProcess
     */
    public function testRun_NoSessionData() {
        ob_start();
        self::removeSession();
        SignupController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, 'session data not found') !== false,
            'It should call run and show an error message when no session data is provided');
    }
    
    public function testRun_Show_NoArgs() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'show';
        SignupController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call run and display the sign up view when the action is "show"');
        $this->assertTrue(strstr($output, 'E-mail <input type="email" name="email" value=""') !== false,
            'It should call run and display the sign up view when the action is "show"');
    }
    
    public function testRun_Post_NoData() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'post';
        SignupController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call run and display the sign up view when the action is "post" with no arguments');
        $this->assertTrue(strstr($output, 'E-mail <input type="email" name="email" value=""') !== false,
            'It should call run and output an error when the action is "post" with no arguments');
    }
    
    public function testRun_Post_InvalidData() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'post';
        $_POST = self::$badInput;
        SignupController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call run and display the sign up view when the action is "post" with invalid post data');
        $this->assertTrue(strstr($output, 'Your first name cannot contain more than') !== false,
            'It should call run and output an error when the action is "post" with invalid post data');
    }
    
    public function testRun_Post_DuplicateData() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'post';
        $_POST = self::$dupInput;
        SignupController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call run and display the sign up view when the action is "post" and an existing user name is provided');
        $this->assertTrue(strstr($output, 'User name already exists') !== false,
            'It should call run and output an error when the action is "post" and an existing user name is provided');
    }
    
//     /** @runInSeparateProcess
//      */
//     public function testRun_Post_ValidData() {
//         ob_start();
//         self::checkSession();
//         $_SESSION['action'] = 'post';
//         $_POST = self::$goodInput;
//         $this->dbQuery("delete from Users where userName = 'armando-n2'");
//         SignupController::run();
        
//         $output = ob_get_clean();
        
//         $this->assertTrue(strstr($output, self::$goodInput['userName'] . '\'s Profile') !== false,
//             'It should call run and redirect to the profile page when the action is "post" with valid, non-duplicate post data. Output: ' . $output);
//     }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
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
        if (isset($_SESSION))
            foreach ($_SESSION as $key => $value)
                unset($_SESSION[$key]);
        unset($_SESSION);
        
        if (isset($_POST))
            foreach ($_POST as $key => $value)
                unset($_POST[$key]);
    }
    
    private function dbQuery($query) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare($query);
            $stmt->execute();
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
}

?>