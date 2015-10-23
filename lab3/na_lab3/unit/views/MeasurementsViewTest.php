<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\MeasurementsView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
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

class MeasurementsViewTest extends PHPUnit_Framework_TestCase {
    
    public function testShow_NoSession() {
        ob_start();
        self::removeSession();
        MeasurementsView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, 'unable to show measurements') !== false,
            'It should call show and output an error message when no session exists');
    }
    
    public function testShow_NoData() {
        ob_start();
        self::checkSession();
        unset($_SESSION['measurements']);
        MeasurementsView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, 'unable to show measurements') !== false,
            'It should call show and output an error message when no measurement data is provided ("measurements" not set)');
    }
    
    public function testShow_ValidData() {
        ob_start();
        self::checkSession();
        $bpMeasurements = BloodPressureMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $calorieMeasurements = CalorieMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $exerciseMeasurements = ExerciseMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $glucoseMeasurements = GlucoseMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $sleepMeasurements = SleepMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $weightMeasurements = WeightMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $_SESSION['measurements'] = array(
            'bloodPressure' => $bpMeasurements,
            'calories' => $calorieMeasurements,
            'exercise' => $exerciseMeasurements,
            'glucose' => $glucoseMeasurements,
            'sleep' => $sleepMeasurements,
            'weight' => $weightMeasurements
        );
        MeasurementsView::show();
        $output = ob_get_clean();
        
        $this->assertTrue(stristr($output, '<h2>Jump to a measurement</h2>') !== false,
            'It should call show and display the past measurements view when valid measurement data is provided');
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