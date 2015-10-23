<?php
require_once dirname(__FILE__).'\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\CalorieMeasurement.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\CalorieMeasurementsDB.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\Messages.class.php';

class CalorieMeasurementsDBTest extends PHPUnit_Framework_TestCase {
    
    /** @expectedException Exception
     * @expectedExceptionMessage Missing argument
     */
    public function testAddMeasurement_NoParameters() {
        self::checkSession();
        CalorieMeasurementsDB::addMeasurement();
    }
    
    public function testAddMeasurement_ValidParameters() {
        self::checkSession();
        $input = array(
            "userName" => "armando-n",
            "date" => "2015-10-20",
            "time" => "21:44",
            "calories" => "125"
        );
        $measurement = new CalorieMeasurement($input);
        if (is_null($measurement) || $measurement->getErrorCount() > 0)
            throw new Exception('Failed to create measurement object: check measurement object\'s class');
    
            $userID = $this->dbSelect("select * from Users where userName = 'armando-n'")[0]['userID'];
            $rowsBeforeAdd = $this->dbSelect("select * from Users join CalorieMeasurements using (userID) where userName = 'armando-n'");
            $measurementID = CalorieMeasurementsDB::addMeasurement($measurement, $userID);
            $rowsAfterAdd = $this->dbSelect("select * from Users join CalorieMeasurements using (userID) where userName = 'armando-n'");
            $this->dbQuery("delete from CalorieMeasurements where calorieID = $measurementID");
    
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
            "calories" => "110"
        );
        $newInput = array(
            "userName" => "armando-n",
            "date" => "2015-10-21",
            "time" => "11:45",
            "calories" => "115"
        );
        $oldMeasurement = new CalorieMeasurement($input);
        if (is_null($oldMeasurement) || $oldMeasurement->getErrorCount() > 0)
            throw new Exception('Failed to create measurement object: check measurement object\'s class');
            $userID = $this->dbSelect("select * from Users where userName = 'armando-n'")[0]['userID'];
            $measurementID = CalorieMeasurementsDB::addMeasurement($oldMeasurement, $userID);
    
            $newMeasurement = new CalorieMeasurement($newInput);
            CalorieMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
    
            $row = $this->dbSelect("select * from Users join CalorieMeasurements using (userID) where calorieID = $measurementID");
            $this->dbQuery("delete from CalorieMeasurements where calorieID = $measurementID");
            $this->assertEquals('115', $row[0]['calories'],
                'It should modify the measurement in the database');
    }
    
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