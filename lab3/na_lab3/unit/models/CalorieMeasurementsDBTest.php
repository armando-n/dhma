<?php
require_once dirname(__FILE__).'\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\CalorieMeasurement.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\CalorieMeasurementsDB.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\Messages.class.php';

class CalorieMeasurementsDBTest extends PHPUnit_Framework_TestCase {
    
    public function testGetAllMeasurements() {
        self::checkSession();
        $allMeasurements = CalorieMeasurementsDB::getAllMeasurements();
        
        $this->assertNotNull($allMeasurements,
            'It should return an array');
        $this->assertNotEmpty($allMeasurements,
            'It should return a non-empty array');
        foreach ($allMeasurements as $measurement) {
            $this->assertInstanceOf('CalorieMeasurement', $measurement,
                'It should return an array of only CalorieMeasurement objects');
            $this->assertCount(0, $measurement->getErrors(),
                'It should not have errors in any returned CalorieMeasurement objects');
        }
    }
    
    public function testGetMeasurementsBy_UserName() {
        self::checkSession();
        $measurementsArray = CalorieMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        
        $this->assertNotNull($measurementsArray,
            'It should return an array');
        $this->assertNotEmpty($measurementsArray,
            'It should return a non-empty array');
        foreach ($measurementsArray as $measurement) {
            if (!isset($previousDate))
                $previousDate = $measurement->getDateTime();
            
            $this->assertInstanceOf('CalorieMeasurement', $measurement,
                'It should return an array of only CalorieMeasurement objects');
            $this->assertCount(0, $measurement->getErrors(),
                'It should not have errors in any returned CalorieMeasurement objects');
            $this->assertEquals('armando-n', $measurement->getUserName(),
                'It should only return measurements belonging to the user name provided');
            $this->assertLessThanOrEqual($previousDate, $measurement->getDateTime(),
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