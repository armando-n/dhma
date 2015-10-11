<?php
require_once dirname(__FILE__).'\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\BloodPressureMeasurement.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\BloodPressureMeasurementsDB.class.php';
require_once dirname(__FILE__).'\..\..\WebContent\models\Messages.class.php';

class BloodPressureMeasurementsDBTest extends PHPUnit_Framework_TestCase {
    
    public function testGetAllMeasurements() {
        $measurements = BloodPressureMeasurementsDB::getAllMeasurements('dhma_testDB', 'na_lab3'.DIRECTORY_SEPARATOR.'myConfig.ini');
        
        $this->assertNotNull($measurements,
            'It should return an array');
        $this->assertGreaterThan(0, count($measurements));
        $this->assertNotEmpty($measurements);
        foreach ($measurements as $bp) {
            $this->assertInstanceOf('BloodPressureMeasurement', $bp);
            $this->assertCount(0, $bp->getErrors(),
                'It should not have errors in any returned BloodPressureMeasurement objects');
        }
    }
}
?>