<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\LoginView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';

class LoginViewTest extends PHPUnit_Framework_TestCase {
    
    private static $goodInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
    );
    
    private static $badInput = array(
        "userName" => 'armando$n',
        "password1" => "pass",
        "password2" => "pass"
    );
    
    private static $wrongInput = array(
        "userName" => 'armandon',
        "password1" => "password123",
        "password2" => "password123"
    );
    
    public function testShow_NoSession() {
        ob_start();
        self::removeSession();
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the login view when no session exists');
    }
    
    public function testShow_NoData() {
        ob_start();
        self::checkSession();
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the login view when no data is provided');
    }
    
    public function testShow_InvalidData_LoginFailed() {
        ob_start();
        self::checkSession();
        $_SESSION['user'] = new User(self::$badInput);
        $_SESSION['loginFailed'] = true;
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the login view when invalid data is provided');
        $this->assertTrue(stristr($output, 'name="userName" value="' . self::$badInput['userName'] . '"') !== false,
            'It should call show and fill in the user name of the login view when invalid data is provided. Output is: ' . $output);
        $this->assertTrue(stristr($output, 'User name or password invalid') !== false,
            'It should call show and display an error message when invalid data is provided and "loginFailed" session variable is set');
    }
    
    public function testShow_ValidData_UserNameNotFound() {
        ob_start();
        self::checkSession();
        $_SESSION['user'] = new User(self::$wrongInput);
        $_SESSION['loginFailed'] = true;
        LoginView::show();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the login view when valid data is provided');
        $this->assertTrue(stristr($output, 'name="userName" value="' . self::$wrongInput['userName'] . '"') !== false,
            'It should call show and fill in the user name of the login view when valid data is provided');
        $this->assertTrue(stristr($output, 'User name or password invalid') !== false,
            'It should call show and display an error message when the wrong user name is provided and "loginFailed" session variable is set');
    }
    
    public function testShow_ValidData() {
        ob_start();
        self::checkSession();
        $_SESSION['user'] = new User(self::$goodInput);
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the login view when valid data is provided');
        $this->assertTrue(stristr($output, 'name="userName" value="' . self::$goodInput['userName'] . '"') !== false,
            'It should call show and fill in the user name of the login view when valid data is provided');
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