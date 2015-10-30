<?php
require_once dirname(__FILE__).'\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\SleepMeasurement.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\SleepMeasurementsDB.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\Messages.class.php';

class SleepMeasurementsDBTest extends PHPUnit_Framework_TestCase {
    
    /** @expectedException Exception
     * @expectedExceptionMessage Undefined variable
     */
    public function testAddMeasurement_NoParameters() {
        self::checkSession();
        SleepMeasurementsDB::addMeasurement();
    }
    
    public function testAddMeasurement_ValidParameters() {
        self::checkSession();
        $input = array(
            "userName" => "armando-n",
            "date" => "2015-10-20",
            "time" => "21:44",
            "duration" => "125"
        );
        $measurement = new SleepMeasurement($input);
        if (is_null($measurement) || $measurement->getErrorCount() > 0)
            throw new Exception('Failed to create measurement object: check measurement object\'s class: ' . array_shift($measurement->getErrors()));
    
            $userID = $this->dbSelect("select * from Users where userName = 'armando-n'")[0]['userID'];
            $rowsBeforeAdd = $this->dbSelect("select * from Users join SleepMeasurements using (userID) where userName = 'armando-n'");
            $measurementID = SleepMeasurementsDB::addMeasurement($measurement, $userID);
            $rowsAfterAdd = $this->dbSelect("select * from Users join SleepMeasurements using (userID) where userName = 'armando-n'");
            $this->dbQuery("delete from SleepMeasurements where sleepID = $measurementID");
    
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
            "duration" => "110"
        );
        $newInput = array(
            "userName" => "armando-n",
            "date" => "2015-10-21",
            "time" => "11:45",
            "duration" => "115"
        );
        $oldMeasurement = new SleepMeasurement($input);
        if (is_null($oldMeasurement) || $oldMeasurement->getErrorCount() > 0)
            throw new Exception('Failed to create measurement object: check measurement object\'s class');
            $userID = $this->dbSelect("select * from Users where userName = 'armando-n'")[0]['userID'];
            $measurementID = SleepMeasurementsDB::addMeasurement($oldMeasurement, $userID);
    
            $newMeasurement = new SleepMeasurement($newInput);
            SleepMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
    
            $row = $this->dbSelect("select * from Users join SleepMeasurements using (userID) where sleepID = $measurementID");
            $this->dbQuery("delete from SleepMeasurements where sleepID = $measurementID");
            $this->assertEquals('115', $row[0]['duration'],
                'It should modify the measurement in the database');
    }
    
    public function testGetAllMeasurements() {
        self::checkSession();
        $allMeasurements = SleepMeasurementsDB::getAllMeasurements();
        
        $this->assertNotNull($allMeasurements,
            'It should return an array');
        $this->assertNotEmpty($allMeasurements,
            'It should return a non-empty array');
        foreach ($allMeasurements as $measurement) {
            $this->assertInstanceOf('SleepMeasurement', $measurement,
                'It should return an array of only SleepMeasurement objects');
            $this->assertCount(0, $measurement->getErrors(),
                'It should not have errors in any returned SleepMeasurement objects');
        }
    }
    
    public function testGetMeasurementsBy_UserName() {
        self::checkSession();
        $measurementsArray = SleepMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        
        $this->assertNotNull($measurementsArray,
            'It should return an array');
        $this->assertNotEmpty($measurementsArray,
            'It should return a non-empty array');
        foreach ($measurementsArray as $measurement) {
            if (!isset($previousDate))
                $previousDate = $measurement->getDateTime();
            
            $this->assertInstanceOf('SleepMeasurement', $measurement,
                'It should return an array of only SleepMeasurement objects');
            $this->assertCount(0, $measurement->getErrors(),
                'It should not have errors in any returned SleepMeasurement objects');
            $this->assertEquals('armando-n', $measurement->getUserName(),
                'It should only return measurements belonging to the user name provided');
            $this->assertLessThanOrEqual($previousDate, $measurement->getDateTime(),
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