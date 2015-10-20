<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\SignupView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\ProfileView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class SignupViewTest extends PHPUnit_Framework_TestCase {
    
    private static $validUserInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
    );
    
    private static $invalidUserInput = array(
        "userName" => 'armando$n',
        "password1" => "pass",
        "password2" => "pass"
    );
    
    private static $validProfileInput = array(
        "firstName" => "Armando",
        "lastName" => "Navarro",
        "email" => 'fdf786@my.utsa.edu',
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
    
    private static $invalidProfileInput = array(
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
    
    /*
     * @runInSeparateProcess
     */
    public function testShow_NoSession() {
        ob_start();
        self::removeSession();
        SignupView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, 'session data not found') !== false,
            'It should call show and output an error message when no session is available');
    }
    
    public function testShow_WithSession_NoData() {
        ob_start();
        self::checkSession();
        unset($_SESSION['user']);
        unset($_SESSION['profile']);
        SignupView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call show and display the sign up view when a session is available and no data is provided');
        $this->assertTrue(strstr($output, 'E-mail <input type="email" name="email" value=""') !== false,
            'It should call show and display a blank sign up form when a session is available and no data is provided');
    }
    
    public function testShow_InvalidData() {
        ob_start();
        self::checkSession();
        $_SESSION['user'] = new User(self::$invalidUserInput);
        $_SESSION['profile'] = new UserProfile(self::$invalidProfileInput);
        SignupView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call show and display the sign up view when a session is available and invalid data is provided');
        $this->assertTrue(strstr($output, 'E-mail <input type="email" name="email" value="fdf786"') !== false,
            'It should call show and display a filled-in sign up form when a session is available and invalid data is provided. Output was: ' . $output);
        $this->assertTrue(strstr($output, 'Your user name can only contain') !== false,
            'It should call show and include an error message for invalid user in the output when a session is available and invalid data is provided');
        $this->assertTrue(strstr($output, 'Your first name cannot contain more') !== false,
            'It should call show and include an error message for invalid first name in the output when a session is available and invalid data is provided');
    }
    
    public function testShow_ValidData() {
        ob_start();
        self::checkSession();
        $_SESSION['user'] = new User(self::$validUserInput);
        $_SESSION['profile'] = new UserProfile(self::$validProfileInput);
        SignupView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>Sign Up Form</h2>') !== false,
            'It should call show and display the sign up view when a session is available and valid data is provided');
        $this->assertTrue(strstr($output, 'E-mail <input type="email" name="email" value="fdf786@my.utsa.edu"') !== false,
            'It should call show and display a filled-in sign up form when a session is available and valid data is provided');
    }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION))
            $_SESSION = array();
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
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