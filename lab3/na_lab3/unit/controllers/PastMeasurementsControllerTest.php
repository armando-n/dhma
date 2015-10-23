<?php
require_once dirname(__FILE__) . '\..\..\WebContent\controllers\PastMeasurementsController.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\PastMeasurementsView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HomeView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\BloodPressureMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\BloodPressureMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\CalorieMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\CalorieMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\ExerciseMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\ExerciseMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GlucoseMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GlucoseMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\SleepMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\SleepMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\WeightMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\WeightMeasurementsDB.class.php';

class PastMeasurementsControllerTest extends PHPUnit_Framework_TestCase {
    
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
    
    public function testRun_NoSession() {
        ob_start();
        self::removeSession();
        PastMeasurementsController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, 'session data not found') !== false,
            'It should call run and output an error message when no session exists');
    }
    
    public function testRun_NoProfile() {
        ob_start();
        self::checkSession();
        unset($_SESSION['profile']);
        PastMeasurementsController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, 'profile not found') !== false,
            'It should call run and output an error message when no profile is provided');
    }
    
    public function testRun_ValidProfile() {
        ob_start();
        self::checkSession();
        $_SESSION['action'] = 'show';
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        PastMeasurementsController::run();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Jump to a measurement</h2>') !== false,
            'It should call run and display the past measurements view when a valid profile is provided');
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