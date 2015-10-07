<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\ExerciseMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class ExerciseMeasurementTest extends PHPUnit_Framework_TestCase {
    
    private static $validInputSeparate = array(
        "userName" => "armando-n",
        "date" => "2015-09-27",
        "time" => "17:22",
        "notes" => "some notes",
        "duration" => "60",
        "type" => "running"
    );
    
    private static $validInputCombined = array(
        "userName" => "armando-n",
        "dateAndTime" => "2015-09-27 17:22",
        "notes" => "some notes",
        "duration" => 60,
        "type" => "running"
    );
    
    public function testCreateValidMeasurement_SeparateDateAndTime() {
        $validBP = new ExerciseMeasurement(self::$validInputSeparate);
        
        $this->assertInstanceOf('ExerciseMeasurement', $validBP,
            'It should create an ExerciseMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided');
        $this->assertEquals(0, $validBP->getErrorCount(),
            'It should have an error count of 0 when valid input is provided');
    }
    
    public function testCreateValidMeasurement_CombinedDateAndTime() {
        $validBP = new ExerciseMeasurement(self::$validInputCombined);
    
        $this->assertInstanceOf('ExerciseMeasurement', $validBP,
            'It should create an ExerciseMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided');
        $this->assertEquals(0, $validBP->getErrorCount(),
            'It should have an error count of 0 when valid input is provided');
    }
    
    public function testParameterExtraction() {
        $bp = new ExerciseMeasurement(self::$validInputCombined);
        $params = $bp->getParameters();
        
        $this->assertCount(5, $params,
            'It should return an array with 5 key-value pairs when valid input is provided');
        $this->assertArrayHasKey('userName', $params,
            'It should return an array with the key "userName" when valid input is provided');
        $this->assertArrayHasKey('dateAndTime', $params,
            'It should return an array with the key "dateAndTime" when valid input is provided');
        $this->assertArrayHasKey('notes', $params,
            'It should return an array with the key "notes" when valid input is provided');
        $this->assertArrayHasKey('duration', $params,
            'It should return an array with the key "duration" when valid input is provided');
        $this->assertArrayHasKey('type', $params,
            'It should return an array with the key "type" when valid input is provided');
        
        $this->assertEquals(self::$validInputCombined['userName'], $params['userName'],
            'It should return an array with a value for key "userName" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['dateAndTime'], $params['dateAndTime']->format("Y-m-d H:i"),
            'It should return an array with a value for key "dateAndTime" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['notes'], $params['notes'],
            'It should return an array with a value for key "notes" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['duration'], $params['duration'],
            'It should return an array with a value for key "duration" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['type'], $params['type'],
            'It should return an array with a value for key "type" that matches the provided input');
    }
    
    public function testAccessorMethods() {
        $bp = new ExerciseMeasurement(self::$validInputCombined);
        
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
        $this->assertEquals(self::$validInputCombined['duration'], $bp->getDuration(),
            'It should return a value for field "duration" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['type'], $bp->getType(),
            'It should return a value for field "type" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['type'] . ': ' . self::$validInputCombined['duration'] . ' minutes', $bp->getMeasurement(),
            'It should return a value for method "getMeasurement" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['duration'], $bp->getMeasurementParts()[0],
            'It should return a value for method "getMeasurementParts" that is an array with its first item matching the provided duration');
        $this->assertEquals(self::$validInputCombined['type'], $bp->getMeasurementParts()[1],
            'It should return a value for method "getMeasurementParts" that is an array with its second item matching the provided type');
    }
    
    public function testNullInput() {
        $measurement = new ExerciseMeasurement(null);
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when null input is provided');
        $this->assertCount(5, $measurement->getParameters(),
            'It should have 5 attributes when null input is provided');
        $this->assertCount(0, $measurement->getErrors(),
            'It should not have errors when null input is provided');
        $this->assertEquals(0, $measurement->getErrorCount(),
            'It should have an error count of 0 when null input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getDate(),
            'It should return an empty value for the getDate method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getTime(),
            'It should return an empty value for the getTime method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getDateTime(),
            'It should return an empty value for the getDateTime method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getDuration(),
            'It should return an empty value for the getDuration method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getType(),
            'It should return an empty value for the getType method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the ExerciseMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[0],
            'It should return a value for the getMeasurementParts method of the ExerciseMeasurement object that is an array with an empty value for its frst item when null input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[1],
            'It should return a value for the getMeasurementParts method of the ExerciseMeasurement object that is an array with an empty value for its second item when null input is provided');
    }
    
    public function testNoInput() {
        $measurement = new ExerciseMeasurement();
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when no input is provided');
        $this->assertCount(5, $measurement->getParameters(),
            'It should have 5 attributes when no input is provided');
        $this->assertCount(0, $measurement->getErrors(),
            'It should not have errors when no input is provided');
        $this->assertEquals(0, $measurement->getErrorCount(),
            'It should have an error count of 0 when null input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getDate(),
            'It should return an empty value for the getDate method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getTime(),
            'It should return an empty value for the getTime method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getDateTime(),
            'It should return an empty value for the getDateTime method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getDuration(),
            'It should return an empty value for the getDuration method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getType(),
            'It should return an empty value for the getType method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the ExerciseMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[0],
            'It should return a value for the getMeasurementParts method of the ExerciseMeasurement object that is an array with an empty value for its frst item when no input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[1],
            'It should return a value for the getMeasurementParts method of the ExerciseMeasurement object that is an array with an empty value for its second item when no input is provided');
    }
    
    public function testEmptyInput() {
        $emptyInput = array(
            "userName" => "",
            "date" => "",
            "time" => "",
            "notes" => "",
            "duration" => "",
            "type" => ""
        );
        $measurement = new ExerciseMeasurement($emptyInput);
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when empty input is provided');
        $this->assertCount(5, $measurement->getParameters(),
            'It should create an ExerciseMeasurement object with 5 attributes when empty input is provided');
        $this->assertCount(4, $measurement->getErrors(),
            'It should create an ExerciseMeasurement with 4 errors when empty input is provided (error for userName, dateAndTime, duration, type)');
        $this->assertEquals(4, $measurement->getErrorCount(),
            'It should have an error count of 4 when empty input is provided');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should have an error for "userName" when empty input is provided');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should have an error for "dateAndTime" when empty input is provided');
        $this->assertArrayHasKey('duration', $measurement->getErrors(),
            'It should have an error for "duration" when empty input is provided');
        $this->assertArrayHasKey('type', $measurement->getErrors(),
            'It should have an error for "type" when empty input is provided');
        $this->assertArrayNotHasKey('notes', $measurement->getErrors(),
            'It should not have an error for "notes" when empty input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getDate(),
            'It should return an empty value for the getDate method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getTime(),
            'It should return an empty value for the getTime method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getDateTime(),
            'It should return an empty value for the getDateTime method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getDuration(),
            'It should return an empty value for the getDuration method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getType(),
            'It should return an empty value for the getType method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the ExerciseMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[0],
            'It should return a value for the getMeasurementParts method of the ExerciseMeasurement object that is an array with an empty value for its frst item when empty input is provided');
        $this->assertEmpty($measurement->getMeasurementParts()[1],
            'It should return a value for the getMeasurementParts method of the ExerciseMeasurement object that is an array with an empty value for its second item when empty input is provided');
    }
    
    public function testErrorUserNameHasInvalidChars() {
        $input = array(
            'userName' => 'inv&al^d%Nam$',
            'dateAndTime' => '2015-10-06 13:40:00',
            'notes' => 'some notes',
            'duration' => 60,
            'type' => "running"
        );
        $measurement = new ExerciseMeasurement($input);
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when invalid characters are in the provided user name');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when invalid characters are in the provided user name');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when invalid characters are in the provided user name');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should have an error for "userName" when invalid characters are in the provided user name');
    }
    
    public function testErrorUserNameTooLong() {
        $input = array(
            'userName' => 'TwentyOneCharactrName',
            'dateAndTime' => '2015-10-06 13:40:00',
            'notes' => 'some notes',
            'duration' => 60,
            'type' => "running"
        );
        $measurement = new ExerciseMeasurement($input);
            
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided user name is too long');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided user name is too long');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided user name is too long');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "userName" error when the provided user name is too long');
    }
    
    public function testErrorDateAndTimeHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'dateAndTime' => '2015-10+06 13:40:00',
            'notes' => 'some notes',
            'duration' => 60,
            'type' => "running"
        );
        $measurement = new ExerciseMeasurement($input);
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided date and time has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided date and time has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided date and time has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "dateAndTime" error when the provided the date and time has invalid characters');
    }
    
    public function testErrorDateHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-10+06',
            'time' => '13-40:00',
            'notes' => 'some notes',
            'duration' => 60,
            'type' => "running"
        );
        $measurement = new ExerciseMeasurement($input);
    
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided date has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided date has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided date has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "dateAndTime" error when the provided date has invalid characters');
    }
    
    public function testErrorTimeHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-10-06',
            'time' => '13$40:00',
            'notes' => 'some notes',
            'duration' => 60,
            'type' => "running"
        );
        $measurement = new ExerciseMeasurement($input);
    
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided time has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided time has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided time has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "dateAndTime" error when the provided time has invalid characters');
    }
    
    public function testErrorDateAndTimeInvalid() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-40-06',
            'time' => '13:40:00',
            'notes' => 'some notes',
            'duration' => 60,
            'type' => "running"
        );
        $measurement = new ExerciseMeasurement($input);
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided date is in an invalid format');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided date is in an invalid format: ' . $measurement->getDate());
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided date is in an invalid format');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "dateAndTime" error when the provided date is in an invalid format');
    }
    
    public function testErrorDurationHasInvalidCharacters() {
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => "some notes",
            "duration" => "60 mins",
            "type" => "running"
        );
        $measurement = new ExerciseMeasurement($input);
        
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided duration has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided duration has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided duration has invalid characters');
        $this->assertArrayHasKey('duration', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "duration" error when the provided duration has invalid characters');
    }
    
    public function testErrorTypeHasInvalidCharacters() {
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => "some notes",
            "duration" => "60",
            "type" => "running+"
        );
        $measurement = new ExerciseMeasurement($input);
    
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided type has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided type has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided type has invalid characters');
        $this->assertArrayHasKey('type', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "type" error when the provided type has invalid characters');
    }
    
    public function testErrorNotesTooLong() {
        $notes256 = '';
        for ($i = 0; $i < 16; $i++)
            $notes256 = $notes256 . '0123456789ABCDEF';
    
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => $notes256,
            "duration" => "60",
            "type" => "running"
        );
        $measurement = new ExerciseMeasurement($input);
    
        $this->assertInstanceOf('ExerciseMeasurement', $measurement,
            'It should create an ExerciseMeasurement object when the provided notes are too long');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with an error when the provided notes are too long');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create an ExerciseMeasurement object with an error count of 1 when the provided notes are too long');
        $this->assertArrayHasKey('notes', $measurement->getErrors(),
            'It should create an ExerciseMeasurement object with a "notes" error when the provided notes are too long');
    }
}
?>