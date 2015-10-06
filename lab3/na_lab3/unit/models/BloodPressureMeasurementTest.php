<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\BloodPressureMeasurement.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';
class BloodPressureMeasurementTest extends PHPUnit_Framework_TestCase {
    
    private static $validInput1 = array(
            "userName" => "armando-n",
            "date" => "2015-09-27",
            "time" => "17:22",
            "systolicPressure" => "120",
            "diastolicPressure" => "80"
    );
    
    private static $validInput2 = array(
            "userName" => "armando-n",
            "dateAndTime" => "2015-09-27 17:22",
            "systolicPressure" => "120",
            "diastolicPressure" => "80"
    );
    
    public function testCreateValidMeasurement_SeparateDateAndTime() {
        $validBP = new BloodPressureMeasurement(self::$validInput1);
        
        $this->assertInstanceOf('BloodPressureMeasurement', $validBP,
            'It should create a BloodPressureMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
            'It should not have errors when valid input is provided: ' . array_shift($validBP->getErrors()));
    }
    
    public function testCreateValidMeasurement_CombinedDateAndTime() {
        $validBP = new BloodPressureMeasurement(self::$validInput2);
    
        $this->assertInstanceOf('BloodPressureMeasurement', $validBP,
                'It should create a BloodPressureMeasurement object when valid input is provided');
        $this->assertCount(0, $validBP->getErrors(),
                'It should not have errors when valid input is provided');
    }
    
    public function testParameterExtraction() {
        $bp = new BloodPressureMeasurement(self::$validInput2);
        $params = $bp->getParameters();
        
        $this->assertArrayHasKey('userName', $params,
            'It should return an array with the key "userName" when valid input is provided');
        $this->assertArrayHasKey('dateAndTime', $params,
                'It should return an array with the key "dateAndTime" when valid input is provided');
        $this->assertArrayHasKey('systolicPressure', $params,
                'It should return an array with the key "systolicPressure" when valid input is provided');
        $this->assertArrayHasKey('diastolicPressure', $params,
                'It should return an array with the key "diastolicPressure" when valid input is provided');
        
        $this->assertEquals(self::$validInput2['userName'], $params['userName'],
            'It should return an array with a value for key "userName" that matches the provided input');
        $this->assertEquals(self::$validInput2['dateAndTime'], $params['dateAndTime']->format("Y-m-d H:i"),
            'It should return an array with a value for key "dateAndTime" that matches the provided input');
        $this->assertEquals(self::$validInput2['systolicPressure'], $params['systolicPressure']);
        $this->assertEquals(self::$validInput2['diastolicPressure'], $params['diastolicPressure']);
    }
    
    public function testAccessorMethods() {
        $bp = new BloodPressureMeasurement(self::$validInput2);
        
        $this->assertEquals(self::$validInput2['userName'], $bp->getUserName());
        $this->assertEquals(self::$validInput2['systolicPressure'], $bp->getSystolicPressure());
        $this->assertEquals(self::$validInput2['diastolicPressure'], $bp->getDiastolicPressure());
        $this->assertEquals(self::$validInput2['systolicPressure'] . ' / ' . self::$validInput2['diastolicPressure'], $bp->getMeasurement());
        $this->assertEquals(self::$validInput2['systolicPressure'], $bp->getMeasurementParts()[0]);
        $this->assertEquals(self::$validInput2['diastolicPressure'], $bp->getMeasurementParts()[1]);
        $this->assertEquals('2015-09-27', $bp->getDate());
        $this->assertEquals('05:22 pm', $bp->getTime());
        $this->assertEquals(self::$validInput2['dateAndTime'], $bp->getDateTime()->format("Y-m-d H:i"));
    }
    
    public function testNullInput() {
        $invalidMeasurement = new BloodPressureMeasurement(null);
    }
}
?>