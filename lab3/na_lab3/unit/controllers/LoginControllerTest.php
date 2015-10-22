<?php
require_once dirname(__FILE__) . '\..\..\WebContent\controllers\LoginController.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\LoginView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HomeView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class LoginControllerTest {
    
    public function testRun_NoSession() {
        ob_start();
        LoginController::run();
        $output = ob_get_clean();
    }
    
    public function testRun_NoAction() {
        ob_start();
        self::checkSession();
        unset($_SESSION['action']);
        LoginController::run();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call run and display the login view when no action is provided');
    }
    
    public function testRun_InvalidAction() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'invalidAction';
        LoginController::run();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call run and display the login view when no action is provided');
        $this->assertTrue(stristr($output, 'Unrecognized command') !== false,
            'It should call run and display an error message when an invalid action is provided');
    }
    
    public function testRun_Show() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'show';
        LoginController::run();
        $output = ob_get_clean();
    
        $this->assertTrue(stristr($output, '<h2>Log In</h2>') !== false,
            'It should call run and display the login view when the action is "show"');
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
        unset($_SESSION);
        
        if (isset($_POST))
            foreach ($_POST as $key => $value)
                unset($_POST[$key]);
    }
    
}
?>