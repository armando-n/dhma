<?php
class PastMeasurementsController {
    
    public static function run() {
        
        if (!isset($_SESSION)) {
            self::error('Error: session data not found');
            return;
        }
        
        if (!isset($_SESSION['profile'])) {
            self::error('Error: profile not found');
            return;
        }
        
        $bpMeasurements = BloodPressureMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $calorieMeasurements = CalorieMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $exerciseMeasurements = ExerciseMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $glucoseMeasurements = GlucoseMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $sleepMeasurements = SleepMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $weightMeasurements = WeightMeasurementsDB::getMeasurementsBy('userName', 'armando-n');
        $_SESSION['measurements'] = array(
            'bloodPressure' => $bpMeasurements,
            'calories' => $calorieMeasurements,
            'exercise' => $exerciseMeasurements,
            'glucose' => $glucoseMeasurements,
            'sleep' => $sleepMeasurements,
            'weight' => $weightMeasurements
        );
        
        PastMeasurementsView::show();
    }
    
    private static function error($message = '') {
        ?><p><?=$message?></p><?php
    }
}
?>