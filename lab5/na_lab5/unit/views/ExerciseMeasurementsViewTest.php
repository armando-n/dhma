<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\ExerciseMeasurementsView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\ExerciseMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\ExerciseMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class ExerciseMeasurementsViewTest extends PHPUnit_Framework_TestCase {
    
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
    
    public function testShow_ValidData() {
        ob_start();
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $_SESSION['measurements']['exercise'] = ExerciseMeasurementsDB::getAllMeasurements();
        ExerciseMeasurementsView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<th>Type: Duration</th>') !== false,
            'It should call show and display a table of exercise measurements when valid data is provided');
    }
    
    public function testEdit_ValidData() {
        ob_start();
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $this->dbQuery(
            "insert into ExerciseMeasurements (duration, type, dateAndTime, userID)
                values (120, 'running', '2015-10-15 10:00', 1)");
        $_SESSION['measurement'] = ExerciseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), '2015-10-15 10:00');
        ExerciseMeasurementsView::edit();
        $this->dbQuery("delete from ExerciseMeasurements where userName = '" . $_SESSION['profile']->getUserName() . "' and dateAndTime = '2015-10-15 10:00'");
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2><a id="exercise">Edit Exercise Measurement</a></h2>') !== false,
            'It should call show and display the exercise measurement edit form when valid data is provided');
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
    
}
?>