<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\CalorieMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class CalorieMeasurementTest extends PHPUnit_Framework_TestCase {
    
    private static $validInputSeparate = array(
        "userName" => "armando-n",
        "date" => "2015-09-27",
        "time" => "17:22",
        "notes" => "some notes",
        "calories" => 550
    );
    
    private static $validInputCombined = array(
        "userName" => "armando-n",
        "dateAndTime" => "2015-09-27 17:22",
        "notes" => "some notes",
        "calories" => "550"
    );
    
    public function testCreateValidMeasurement_SeparateDateAndTime() {
        $validBP = new CalorieMeasurement(self::$validInputSeparate);
        
        $this->assertInstanceOf('CalorieMeasurement', $validBP,
            'It should create a CalorieMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided');
        $this->assertEquals(0, $validBP->getErrorCount(),
            'It should have an error count of 0 when valid input is provided');
    }
    
    public function testCreateValidMeasurement_CombinedDateAndTime() {
        $validBP = new CalorieMeasurement(self::$validInputCombined);
    
        $this->assertInstanceOf('CalorieMeasurement', $validBP,
            'It should create a CalorieMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided');
        $this->assertEquals(0, $validBP->getErrorCount(),
            'It should have an error count of 0 when valid input is provided');
    }
    
    public function testParameterExtraction() {
        $bp = new CalorieMeasurement(self::$validInputCombined);
        $params = $bp->getParameters();
        
        $this->assertCount(4, $params,
            'It should return an array with 4 key-value pairs when valid input is provided');
        $this->assertArrayHasKey('userName', $params,
            'It should return an array with the key "userName" when valid input is provided');
        $this->assertArrayHasKey('dateAndTime', $params,
            'It should return an array with the key "dateAndTime" when valid input is provided');
        $this->assertArrayHasKey('notes', $params,
            'It should return an array with the key "notes" when valid input is provided');
        $this->assertArrayHasKey('calories', $params,
            'It should return an array with the key "calories" when valid input is provided');
        
        $this->assertEquals(self::$validInputCombined['userName'], $params['userName'],
            'It should return an array with a value for key "userName" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['dateAndTime'], $params['dateAndTime'],
            'It should return an array with a value for key "dateAndTime" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['notes'], $params['notes'],
            'It should return an array with a value for key "notes" that matches the provided input');
        $this->assertEquals(self::$validInputCombined['calories'], $params['calories'],
            'It should return an array with a value for key "calories" that matches the provided input');
    }
    
    public function testAccessorMethods() {
        $bp = new CalorieMeasurement(self::$validInputCombined);
        
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
        $this->assertEquals(self::$validInputCombined['calories'], $bp->getMeasurement(),
            'It should return a value for field "calories" that matches the provided input');
    }
    
    public function testNullInput() {
        $measurement = new CalorieMeasurement(null);
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when null input is provided');
        $this->assertCount(4, $measurement->getParameters(),
            'It should have 4 attributes when null input is provided');
        $this->assertCount(0, $measurement->getErrors(),
            'It should not have errors when null input is provided');
        $this->assertEquals(0, $measurement->getErrorCount(),
            'It should have an error count of 0 when null input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the CalorieMeasurement object when null input is provided');
//         $this->assertEmpty($measurement->getDate(),
//             'It should return an empty value for the getDate method of the CalorieMeasurement object when null input is provided');
//         $this->assertEmpty($measurement->getTime(),
//             'It should return an empty value for the getTime method of the CalorieMeasurement object when null input is provided');
//         $this->assertEmpty($measurement->getDateTime(),
//             'It should return an empty value for the getDateTime method of the CalorieMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the CalorieMeasurement object when null input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the CalorieMeasurement object when null input is provided');
    }
    
    public function testNoInput() {
        $measurement = new CalorieMeasurement();
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when no input is provided');
        $this->assertCount(4, $measurement->getParameters(),
            'It should have 4 attributes when no input is provided');
        $this->assertCount(0, $measurement->getErrors(),
            'It should not have errors when no input is provided');
        $this->assertEquals(0, $measurement->getErrorCount(),
            'It should have an error count of 0 when null input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the CalorieMeasurement object when no input is provided');
//         $this->assertEmpty($measurement->getDate(),
//             'It should return an empty value for the getDate method of the CalorieMeasurement object when no input is provided');
//         $this->assertEmpty($measurement->getTime(),
//             'It should return an empty value for the getTime method of the CalorieMeasurement object when no input is provided');
//         $this->assertEmpty($measurement->getDateTime(),
//             'It should return an empty value for the getDateTime method of the CalorieMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the CalorieMeasurement object when no input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the CalorieMeasurement object when no input is provided');
    }
    
    public function testEmptyInput() {
        $emptyInput = array(
            "userName" => "",
            "date" => "",
            "time" => "",
            "notes" => "",
            "calories" => ""
        );
        $measurement = new CalorieMeasurement($emptyInput);
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when empty input is provided');
        $this->assertCount(4, $measurement->getParameters(),
            'It should create a CalorieMeasurement object with 4 attributes when empty input is provided');
        $this->assertCount(3, $measurement->getErrors(),
            'It should create a CalorieMeasurement with 3 errors when empty input is provided (error for userName, dateAndTime, calories)');
        $this->assertEquals(3, $measurement->getErrorCount(),
            'It should have an error count of 3 when empty input is provided');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should have an error for "userName" when empty input is provided');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should have an error for "dateAndTime" when empty input is provided');
        $this->assertArrayHasKey('calories', $measurement->getErrors(),
            'It should have an error for "calories" when empty input is provided');
        $this->assertArrayNotHasKey('notes', $measurement->getErrors(),
            'It should not have an error for "notes" when empty input is provided');
        
        $this->assertEmpty($measurement->getUserName(),
            'It should return an empty value for the getUserName method of the CalorieMeasurement object when empty input is provided');
//         $this->assertEmpty($measurement->getDate(),
//             'It should return an empty value for the getDate method of the CalorieMeasurement object when empty input is provided');
//         $this->assertEmpty($measurement->getTime(),
//             'It should return an empty value for the getTime method of the CalorieMeasurement object when empty input is provided');
//         $this->assertEmpty($measurement->getDateTime(),
//             'It should return an empty value for the getDateTime method of the CalorieMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getNotes(),
            'It should return an empty value for the getNotes method of the CalorieMeasurement object when empty input is provided');
        $this->assertEmpty($measurement->getMeasurement(),
            'It should return an empty value for the getMeasurement method of the CalorieMeasurement object when empty input is provided');
    }
    
    public function testErrorUserNameHasInvalidChars() {
        $input = array(
            'userName' => 'inv&al^d%Nam$',
            'dateAndTime' => '2015-10-06 13:40:00',
            'notes' => 'some notes',
            'calories' => 550
        );
        $measurement = new CalorieMeasurement($input);
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when invalid characters are in the provided user name');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when invalid characters are in the provided user name');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when invalid characters are in the provided user name');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should have an error for "userName" when invalid characters are in the provided user name');
    }
    
    public function testErrorUserNameTooLong() {
        $input = array(
            'userName' => 'TwentyOneCharactrName',
            'dateAndTime' => '2015-10-06 13:40:00',
            'notes' => 'some notes',
            'calories' => 550
        );
        $measurement = new CalorieMeasurement($input);
            
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided user name is too long');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided user name is too long');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided user name is too long');
        $this->assertArrayHasKey('userName', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "userName" error when the provided user name is too long');
    }
    
    public function testErrorDateAndTimeHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'dateAndTime' => '2015-10+06 13:40:00',
            'notes' => 'some notes',
            'calories' => 550
        );
        $measurement = new CalorieMeasurement($input);
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided date and time has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided date and time has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided date and time has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "dateAndTime" error when the provided the date and time has invalid characters');
    }
    
    public function testErrorDateHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-10+06',
            'time' => '13-40:00',
            'notes' => 'some notes',
            'calories' => 550
        );
        $measurement = new CalorieMeasurement($input);
    
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided date has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided date has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided date has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "dateAndTime" error when the provided date has invalid characters');
    }
    
    public function testErrorTimeHasInvalidCharacters() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-10-06',
            'time' => '13$40:00',
            'notes' => 'some notes',
            'calories' => 550
        );
        $measurement = new CalorieMeasurement($input);
    
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided time has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided time has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided time has invalid characters');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "dateAndTime" error when the provided time has invalid characters');
    }
    
    public function testErrorDateAndTimeInvalid() {
        $input = array(
            'userName' => 'armando-n',
            'date' => '2015-40-06',
            'time' => '13:40:00',
            'notes' => 'some notes',
            'calories' => 550
        );
        $measurement = new CalorieMeasurement($input);
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided date is in an invalid format');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided date is in an invalid format: ' . $measurement->getDate());
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided date is in an invalid format');
        $this->assertArrayHasKey('dateAndTime', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "dateAndTime" error when the provided date is in an invalid format');
    }
    
    public function testErrorCaloriesHasInvalidCharacters() {
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => "some notes",
            "calories" => "550s"
        );
        $measurement = new CalorieMeasurement($input);
    
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided calories has invalid characters');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided calories has invalid characters');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided calories has invalid characters');
        $this->assertArrayHasKey('calories', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "calories" error when the provided calories has invalid characters');
    }
    
    public function testErrorNotesTooLong() {
        $notes256 = '';
        for ($i = 0; $i < 16; $i++)
            $notes256 = $notes256 . '0123456789ABCDEF';
        
        $input = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "notes" => $notes256,
            "calories" => 550
        );
        $measurement = new CalorieMeasurement($input);
        
        $this->assertInstanceOf('CalorieMeasurement', $measurement,
            'It should create a CalorieMeasurement object when the provided notes are too long');
        $this->assertCount(1, $measurement->getErrors(),
            'It should create a CalorieMeasurement object with an error when the provided notes are too long');
        $this->assertEquals(1, $measurement->getErrorCount(),
            'It should create a CalorieMeasurement object with an error count of 1 when the provided notes are too long');
        $this->assertArrayHasKey('notes', $measurement->getErrors(),
            'It should create a CalorieMeasurement object with a "notes" error when the provided notes are too long');
    }
}
?>