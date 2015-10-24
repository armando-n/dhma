<?php
require_once dirname(__FILE__).'\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\BloodPressureMeasurement.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\BloodPressureMeasurementsDB.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\Messages.class.php';

class BloodPressureMeasurementsDBTest extends PHPUnit_Framework_TestCase {
    
    /** @expectedException Exception
     * @expectedExceptionMessage Undefined variable
     */
    public function testAddMeasurement_NoParameters() {
        self::checkSession();
        BloodPressureMeasurementsDB::addMeasurement();
    }
    
    public function testAddMeasurement_ValidParameters() {
        self::checkSession();
        $input = array(
            "userName" => "armando-n",
            "date" => "2015-10-20",
            "time" => "21:44",
            "systolicPressure" => "125",
            "diastolicPressure" => "78"
        );
        $measurement = new BloodPressureMeasurement($input);
        if (is_null($measurement) || $measurement->getErrorCount() > 0)
            throw new Exception('Failed to create measurement object: check measurement object\'s class');
        
        $userID = $this->dbSelect("select * from Users where userName = 'armando-n'")[0]['userID'];
        $rowsBeforeAdd = $this->dbSelect("select * from Users join BloodPressureMeasurements using (userID) where userName = 'armando-n'");
        $measurementID = BloodPressureMeasurementsDB::addMeasurement($measurement, $userID);
        $rowsAfterAdd = $this->dbSelect("select * from Users join BloodPressureMeasurements using (userID) where userName = 'armando-n'");
        $this->dbQuery("delete from BloodPressureMeasurements where bpID = $measurementID");
        
        $this->assertEquals(1, count($rowsAfterAdd) - count($rowsBeforeAdd),
            'It should have one more row after the add than it did before the add');
        $this->assertNotEquals(-1, $measurementID,
            'It should return the measurement ID of the added measurement when a valid measurement and userID are provided');
    }
    
    public function testEditMeasurement_ValidParameters() {
        self::checkSession();
        $input = array(
            "userName" => "armando-n",
            "date" => "2015-10-21",
            "time" => "11:45",
            "systolicPressure" => "110",
            "diastolicPressure" => "65"
        );
        $newInput = array(
            "userName" => "armando-n",
            "date" => "2015-10-21",
            "time" => "11:45",
            "systolicPressure" => "115",
            "diastolicPressure" => "82"
        );
        $oldMeasurement = new BloodPressureMeasurement($input);
        if (is_null($oldMeasurement) || $oldMeasurement->getErrorCount() > 0)
            throw new Exception('Failed to create measurement object: check measurement object\'s class');
        $userID = $this->dbSelect("select * from Users where userName = 'armando-n'")[0]['userID'];
        $measurementID = BloodPressureMeasurementsDB::addMeasurement($oldMeasurement, $userID);
        
        $newMeasurement = new BloodPressureMeasurement($newInput);
        BloodPressureMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
        
        $row = $this->dbSelect("select * from Users join BloodPressureMeasurements using (userID) where bpID = $measurementID");
        $this->dbQuery("delete from BloodPressureMeasurements where bpID = $measurementID");
        $this->assertEquals('115', $row[0]['systolicPressure'],
            'It should modify the measurement in the database');
        $this->assertEquals('82', $row[0]['diastolicPressure'],
            'It should modify the measurement in the database');
    }
    
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
    
    private function dbSelect($query) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $rows;
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