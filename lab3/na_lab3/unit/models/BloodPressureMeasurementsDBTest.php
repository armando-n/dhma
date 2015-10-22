<?php
require_once dirname(__FILE__).'\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\BloodPressureMeasurement.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\BloodPressureMeasurementsDB.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\Messages.class.php';

class BloodPressureMeasurementsDBTest extends PHPUnit_Framework_TestCase {
    
    public function testGetAllMeasurements() {
        self::checkSession();
        $measurements = BloodPressureMeasurementsDB::getAllMeasurements();
        
        $this->assertNotNull($measurements,
            'It should return an array');
        $this->assertNotEmpty($measurements,
            'It should return a non-empty array');
        foreach ($measurements as $bp) {
            $this->assertInstanceOf('BloodPressureMeasurement', $bp,
                'It should return an array of only BloodPressureMeasurement objects');
            $this->assertCount(0, $bp->getErrors(),
                'It should not have errors in any returned BloodPressureMeasurement objects');
        }
    }
    
    public function testGetMeasurementsBy_UserName() {
        self::checkSession();
        $measurements = BloodPressureMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        
        $this->assertNotNull($measurements,
            'It should return an array');
        $this->assertNotEmpty($measurements,
            'It should return a non-empty array');
        foreach ($measurements as $bp) {
            if (!isset($previousDate))
                $previousDate = $bp->getDateTime();
            
            $this->assertInstanceOf('BloodPressureMeasurement', $bp,
                'It should return an array of only BloodPressureMeasurement objects');
            $this->assertCount(0, $bp->getErrors(),
                'It should not have errors in any returned BloodPressureMeasurement objects');
            $this->assertEquals('armando-n', $bp->getUserName(),
                'It should only return measurements belonging to the user name provided');
            $this->assertLessThanOrEqual($previousDate, $bp->getDateTime(),
                'It should return an array of measurements in descending order when no order is provided');
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