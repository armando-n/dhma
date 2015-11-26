<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';

class HeaderViewTest extends PHPUnit_Framework_TestCase {
    
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
    
    public function testShow_NoSession() {
        ob_start();
        self::removeSession();
        HeaderView::show();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<a href="login_show">Login</a>') !== false,
            'It should call show and display the header view with a Sign Up link when no session exists');
        $this->assertTrue(stristr($output, '<a href="signup_show">Sign Up</a>') !== false,
            'It should call show and display the header view with a Log In link when no session exists');
        $this->assertTrue(stristr($output, '<a href="measurements_show">Past Measurements</a>') === false,
            'It should call show and display the header view without a Past Measurements link when no session exists');
        $this->assertTrue(stristr($output, '<a href="login_logout">Logout</a>') === false,
            'It should call show and display the header view without a Profile link when no session exists');
    }
    
    public function testShow_NoProfile() {
        ob_start();
        self::checkSession();
        unset($_SESSION['profile']);
        HeaderView::show();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<a href="login_show">Login</a>') !== false,
            'It should call show and display the header view with a Sign Up link when no profile is provided');
        $this->assertTrue(stristr($output, '<a href="signup_show">Sign Up</a>') !== false,
            'It should call show and display the header view with a Log In link when no profile is provided');
        $this->assertTrue(stristr($output, '<a href="measurements_show">Past Measurements</a>') === false,
            'It should call show and display the header view without a Past Measurements link when no profile is provided');
        $this->assertTrue(stristr($output, '<a href="login_logout">Logout</a>') === false,
            'It should call show and display the header view without a Profile link when no profile is provided');
    }
    
    public function testShow_Profile() {
        ob_start();
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        HeaderView::show();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<a href="measurements_show">Past Measurements</a>') !== false,
            'It should call show and display the header view without a Past Measurements link when a profile is provided');
        $this->assertTrue(stristr($output, '<a href="login_logout">Logout</a>') !== false,
            'It should call show and display the header view without a Profile link when a profile is provided');
        $this->assertTrue(stristr($output, '<a href="login_show">Login</a>') === false,
            'It should call show and display the header view with a Sign Up link when a profile is provided');
        $this->assertTrue(stristr($output, '<a href="signup_show">Sign Up</a>') === false,
            'It should call show and display the header view with a Log In link when a profile is provided');
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
        if (isset($_SESSION))
            foreach ($_SESSION as $key => $value)
                unset($_SESSION[$key]);
        unset ( $_SESSION );

        if (isset($_POST))
            foreach ($_POST as $key => $value)
                unset($_POST[$key]);
    }
    
}

?>