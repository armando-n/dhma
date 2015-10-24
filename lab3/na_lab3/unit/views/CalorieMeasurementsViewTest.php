<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\CalorieMeasurementsView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\CalorieMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\CalorieMeasurementsDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class CalorieMeasurementsViewTest extends PHPUnit_Framework_TestCase {
    
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
        $_SESSION['measurements']['calories'] = CalorieMeasurementsDB::getAllMeasurements();
        CalorieMeasurementsView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<th>Calories</th>') !== false,
            'It should call show and display a table of calorie measurements when valid data is provided');
    }
    
    public function testEdit_ValidData() {
        ob_start();
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$profileInput);
        $this->dbQuery(
            "insert into CalorieMeasurements (calories, dateAndTime, userID)
                values (400, '2015-10-15 10:00', 1)");
        $_SESSION['measurement'] = CalorieMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), '2015-10-15 10:00');
        CalorieMeasurementsView::edit();
        $this->dbQuery("delete from CalorieMeasurements where userName = '" . $_SESSION['profile']->getUserName() . "' and dateAndTime = '2015-10-15 10:00'");
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2><a id="calories">Edit Calorie Measurement</a></h2>') !== false,
            'It should call show and display the calorie measurement edit form when valid data is provided');
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