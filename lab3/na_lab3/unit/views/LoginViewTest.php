<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\LoginView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class LoginViewTest extends PHPUnit_Framework_TestCase {
    
    public function testShow_NoSession() {
        ob_start();
        self::removeSession();
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the loogin view when no session exists');
    }
    
    public function testShow_NoData() {
        ob_start();
        self::checkSession();
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the loogin view when no data is provided');
    }
    
    public function testShow_InvalidData() {
        ob_start();
        self::checkSession();
        $_SESSION['user'] = self::$badInput;
        LoginView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call show and display the login view when invalid data is provided');
        $this->assertTrue(stristr($output, 'User name or password invalid') !== false,
            'It should call show and display an error message when invalid data is provided');
        $this->assertTrue(stristr($output, ''))
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