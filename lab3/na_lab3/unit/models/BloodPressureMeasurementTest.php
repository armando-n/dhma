<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\BloodPressureMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class BloodPressureMeasurementTest extends PHPUnit_Framework_TestCase {
    
    private static $validInputSeparate = array(
        "userName" => "armando-n",
        "date" => "2015-09-27",
        "time" => "17:22",
        "notes" => "some notes",
        "systolicPressure" => "120",
        "diastolicPressure" => "80"
    );
    
    private static $validInputCombined = array(
        "userName" => "armando-n",
        "dateAndTime" => "2015-09-27 17:22",
        "notes" => "some notes",
        "systolicPressure" => "120",
        "diastolicPressure" => "80"
    );
    
    public function testCreateValidMeasurement_SeparateDateAndTime() {
        $validBP = new BloodPressureMeasurement(self::$validInputSeparate);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $validBP,
            'It should create a BloodPressureMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided');
        $this->assertEquals(0, $validBP->getErrorCount(),
            'It should have an error count of 0 when valid input is provided');
    }
    
    public function testCreateValidMeasurement_CombinedDateAndTime() {
        $validBP = new BloodPressureMeasurement(self::$validInputCombined);
    
        $this->assertInstanceOf('BloodPressureMeasurement', $validBP,
            'It should create a BloodPressureMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided');
        $this->assertEquals(0, $validBP->getErrorCount(),
            'It should have an error count of 0 when valid input is provided');
    }
    
    public function testParameterExtraction() {
        $bp = new BloodPressureMeasurement(self::$validInputCombined);
        $params = $bp->getParameters();
        
        $this->assertCount(5, $params,
            'It should return an array with 5 key-value pairs when valid input is provided');
        $this->assertArrayHasKey('userName', $params,
            'It should return an array with the key "userName" when valid input is provided');
        $this->assertArrayHasKey('dateAndTime', $params,
            'It should return an array with the key "dateAndTime" when valid input is provided');
        $this->assertArrayHasKey('notes', $params,
            'It should return an array with the key "notes" when valid input is provided');
        $this->assertArrayHasKey('systolicPressure', $params,
            'It should return an array with the key "systolicPressure" when valid input is provided');
        $this->assertArrayHasKey('diastolicPressure', $params,
            'It should return an array with the key "diastolicPressure" when valid input is provided');
        
        $this->assertEquals(self::$validInputCombined['userName'], $params['userName'],
            'It should return an array with a value for key "userName" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['dateAndTime'], $params['dateAndTime']->format("Y-m-d H:i"),
            'It should return an array with a value for key "dateAndTime" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['notes'], $params['notes'],
            'It should return an array with a value for key "notes" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['systolicPressure'], $params['systolicPressure'],
            'It should return an array with a value for key "systolicPressure" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['diastolicPressure'], $params['diastolicPressure'],
            'It should return an array with a value for key "diastolicPressure" that matches the provided input');
    }
    
    public function testAccessorMethods() {
        $bp = new BloodPressureMeasurement(self::$validInputCombined);
        
        $this->assertEquals(self::$validInputCombined['userName'], $bp->getUserName(),
            'It should return a value for field "userName" that matches the provided input');
        $this->assertEquals('2015-09-27', $bp->getDate(),
            'It should return a value for method "getDate" that matches the provided date input');
        $this->assertEquals('05:22 pm', $bp->getTime(),
            'It should return a value for method "getTime" that matches the provided time input');
        $this->assertEquals(self::$validInputCombined['dateAndTime'], $bp->getDateTime()->format("Y-m-d H:i"),
            'It should return a value for method getDateTime that is a DateTime object with a working format method');
        $this->assertEquals(self::$validInputCombined['notes'], $bp->getNotes(),
            'It should return a value for field "notes" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['systolicPressure'], $bp->getSystolicPressure(),
            'It should return a value for field "systolicPressure" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['diastolicPressure'], $bp->getDiastolicPressure(),
            'It should return a value for field "diastolicPressure" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['systolicPressure'] . ' / ' . self::$validInputCombined['diastolicPressure'], $bp->getMeasurement(),
            'It should return a value for method "getMeasurement" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['systolicPressure'], $bp->getMeasurementParts()[0],
            'It should return a value for method "getMeasurementParts" that is an array with its first item matching the provided systolic pressure');
        $this->assertEquals(self::$validInputCombined['diastolicPressure'], $bp->getMeasurementParts()[1],
            'It should return a value for method "getMeasurementParts" that is an array with its second item matching the provided diastolic pressure');
    }
    
    public function testNullInput() {
        $measurement = new BloodPressureMeasurement(null);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when null input is provided');
        $this->assertCount(5, $measurement->getParameters(),
            'It should have 5 attributes when null input is provided');
        $this->assertCount(0, $measurement->getErrors(),
            'It should not have errors when null input is provided');
        $this->assertEquals(0, $measurement->getErrorCount(),
            'It should have an error count of 0 when null input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getDate(),
            'It should return an empty value for the getDate method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getTime(),
            'It should return an empty value for the getTime method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getDateTime(),
            'It should return an empty value for the getDateTime method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getSystolicPressure(),
            'It should return an empty value for the getSystolicPressure method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getDiastolicPressure(),
            'It should return an empty value for the getDiastolicPressure method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the BloodPressureMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[0],
            'It should return a value for the getMeasurementParts method of the BloodPressureMeasurement object that is an array with an empty value for its frst item when null input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[1],
            'It should return a value for the getMeasurementParts method of the BloodPressureMeasurement object that is an array with an empty value for its second item when null input is provided');
    }
    
    public function testNoInput() {
        $measurement = new BloodPressureMeasurement();
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when no input is provided');
        $this->assertCount(5, $measurement->getParameters(),
            'It should have 5 attributes when no input is provided');
        $this->assertCount(0, $measurement->getErrors(),
            'It should not have errors when no input is provided');
        $this->assertEquals(0, $measurement->getErrorCount(),
            'It should have an error count of 0 when null input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getDate(),
            'It should return an empty value for the getDate method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getTime(),
            'It should return an empty value for the getTime method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getDateTime(),
            'It should return an empty value for the getDateTime method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getSystolicPressure(),
            'It should return an empty value for the getSystolicPressure method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getDiastolicPressure(),
            'It should return an empty value for the getDiastolicPressure method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the BloodPressureMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[0],
            'It should return a value for the getMeasurementParts method of the BloodPressureMeasurement object that is an array with an empty value for its frst item when no input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[1],
            'It should return a value for the getMeasurementParts method of the BloodPressureMeasurement object that is an array with an empty value for its second item when no input is provided');
    }
    
    public function testEmptyInput() {
        $emptyInput = array(
            "userName" => "",
            "date" => "",
            "time" => "",
            "notes" => "",
            "systolicPressure" => "",
            "diastolicPressure" => ""
        );
        $measurement = new BloodPressureMeasurement($emptyInput);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when empty input is provided');
        $this->assertCount(5, $measurement->getParameters(),
            'It should create a BloodPressureMeasurement object with 5 attributes when empty input is provided');
        $this->assertCount(4, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement with 4 errors when empty input is provided (error for userName, dateAndTime, systolicPressure, diastolicPressure)');
        $this->assertEquals(4, $measurement->getErrorCount(),
            'It should have an error count of 4 when empty input is provided');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should have an error for "userName" when empty input is provided');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should have an error for "dateAndTime" when empty input is provided');
        $this->assertArrayHasKey('systolicPressure', $measurement->getErrors(),
            'It should have an error for "systolicPressure" when empty input is provided');
        $this->assertArrayHasKey('diastolicPressure', $measurement->getErrors(),
            'It should have an error for "diastolicPressure" when empty input is provided');
        $this->assertArrayNotHasKey('notes', $measurement->getErrors(),
            'It should not have an error for "notes" when empty input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getDate(),
            'It should return an empty value for the getDate method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getTime(),
            'It should return an empty value for the getTime method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getDateTime(),
            'It should return an empty value for the getDateTime method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getSystolicPressure(),
            'It should return an empty value for the getSystolicPressure method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getDiastolicPressure(),
            'It should return an empty value for the getDiastolicPressure method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the BloodPressureMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[0],
            'It should return a value for the getMeasurementParts method of the BloodPressureMeasurement object that is an array with an empty value for its frst item when empty input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[1],
            'It should return a value for the getMeasurementParts method of the BloodPressureMeasurement object that is an array with an empty value for its second item when empty input is provided');
    }
    
    public function testErrorUserNameHasInvalidChars() {
        $input = array(
            'userName' => 'inv&al^d%Nam$',
            'dateAndTime' => '2015-10-06 13:40:00',
            'notes' => 'some notes',
            'systolicPressure' => 120,
            'diastolicPressure' => 80
        );
        $measurement = new BloodPressureMeasurement($input);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when invalid characters are in the provided user name');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when invalid characters are in the provided user name');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when invalid characters are in the provided user name');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should have an error for "userName" when invalid characters are in the provided user name');
    }
    
    public function testErrorUserNameTooLong() {
        $input = array(
            'userName' => 'TwentyOneCharactrName',
            'dateAndTime' => '2015-10-06 13:40:00',
            'notes' => 'some notes',
            'systolicPressure' => 120,
            'diastolicPressure' => 80
        );
        $measurement = new BloodPressureMeasurement($input);
            
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided user name is too long');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided user name is too long');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided user name is too long');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "userName" error when the provided user name is too long');
    }
    
    public function testErrorDateAndTimeHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'dateAndTime' => '2015-10+06 13:40:00',
            'notes' => 'some notes',
            'systolicPressure' => 120,
            'diastolicPressure' => 80
        );
        $measurement = new BloodPressureMeasurement($input);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided date and time has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided date and time has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided date and time has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "dateAndTime" error when the provided the date and time has invalid characters');
    }
    
    public function testErrorDateHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-10+06',
            'time' => '13-40:00',
            'notes' => 'some notes',
            'systolicPressure' => 120,
            'diastolicPressure' => 80
        );
        $measurement = new BloodPressureMeasurement($input);
    
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided date has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided date has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided date has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "dateAndTime" error when the provided date has invalid characters');
    }
    
    public function testErrorTimeHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-10-06',
            'time' => '13$40:00',
            'notes' => 'some notes',
            'systolicPressure' => 120,
            'diastolicPressure' => 80
        );
        $measurement = new BloodPressureMeasurement($input);
    
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided time has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided time has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided time has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "dateAndTime" error when the provided time has invalid characters');
    }
    
    public function testErrorDateAndTimeInvalid() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-40-06',
            'time' => '13:40:00',
            'notes' => 'some notes',
            'systolicPressure' => 120,
            'diastolicPressure' => 80
        );
        $measurement = new BloodPressureMeasurement($input);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided date is in an invalid format');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided date is in an invalid format: ' . $measurement->getDate());
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided date is in an invalid format');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "dateAndTime" error when the provided date is in an invalid format');
    }
    
    public function testErrorSystolicPressureHasInvalidCharacters() {
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => "some notes",
            "systolicPressure" => "120 mg/dL",
            "diastolicPressure" => "80"
        );
        $measurement = new BloodPressureMeasurement($input);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided systolicPressure has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided systolicPressure has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided systolicPressure has invalid characters');
        $this->assertArrayHasKey('systolicPressure', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "systolicPressure" error when the provided systolicPressure has invalid characters');
    }
    
    public function testErrorDiastolicPressureHasInvalidCharacters() {
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => "some notes",
            "systolicPressure" => "120",
            "diastolicPressure" => "80 mg/dL"
        );
        $measurement = new BloodPressureMeasurement($input);
    
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided diastolicPressure has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided diastolicPressure has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided diastolicPressure has invalid characters');
        $this->assertArrayHasKey('diastolicPressure', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "diastolicPressure" error when the provided diastolicPressure has invalid characters');
    }
    
    public function testErrorNotesTooLong() {
        $notes256 = '';
        for ($i = 0; $i < 16; $i++)
            $notes256 = $notes256 . '0123456789ABCDEF';
    
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => $notes256,
            "systolicPressure" => "120",
            "diastolicPressure" => "80"
        );
        $measurement = new BloodPressureMeasurement($input);
    
        $this->assertInstanceOf('BloodPressureMeasurement', $measurement,
            'It should create a BloodPressureMeasurement object when the provided notes are too long');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with an error when the provided notes are too long');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a BloodPressureMeasurement object with an error count of 1 when the provided notes are too long');
        $this->assertArrayHasKey('notes', $measurement->getErrors(),
            'It should create a BloodPressureMeasurement object with a "notes" error when the provided notes are too long');
    }
}
?>