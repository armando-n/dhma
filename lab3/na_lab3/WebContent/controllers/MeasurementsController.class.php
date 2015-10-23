<?php
class MeasurementsController {
    
    public static function run() {
        
        if (!isset($_SESSION)) {
            self::error('Error: session data not found');
            return;
        }
        
        if (!isset($_SESSION['profile'])) {
            self::error('Error: profile not found');
            return;
        }
        
        if (!isset($_SESSION['action'])) {
            self::error('Error: unrecognized command');
            return;
        }
        
        switch ($_SESSION['action']) {
            case 'show':
                $bpMeasurements = BloodPressureMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                $calorieMeasurements = CalorieMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                $exerciseMeasurements = ExerciseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                $glucoseMeasurements = GlucoseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                $sleepMeasurements = SleepMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                $weightMeasurements = WeightMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                $_SESSION['measurements'] = array(
                    'bloodPressure' => $bpMeasurements,
                    'calories' => $calorieMeasurements,
                    'exercise' => $exerciseMeasurements,
                    'glucose' => $glucoseMeasurements,
                    'sleep' => $sleepMeasurements,
                    'weight' => $weightMeasurements
                );
                MeasurementsView::show();
                break;
            case 'add':
                self::add();
                break;
            default:
                self::error('Error: unrecognized command');
        }
        
    }
    
    private static function add() {
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: arguments expected');
            return;
        }
        
        switch ($_SESSION['arguments']) {
            default:
                MeasurementsView::add();
                break;
        }
    }
    
    private static function error($message = '') {
        ?><p><?=$message?></p><?php
    }
}
?>