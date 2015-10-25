<?php
class MeasurementsController {
    
    public static function run() {
        
        if (!isset($_SESSION)) {
            self::error('Error: session data not found');
            return;
        }
        
        if (!isset($_SESSION['profile'])) {
            self::redirect('login_show', 'You must log in before you can see your measurements. For professor/grader: Use &quot;armando-n&quot; for user name, and &quot;pass123&quot; for password.');
            return;
        }
        
        if (!isset($_SESSION['action'])) {
            self::error('Error: unrecognized command');
            return;
        }
        
        switch ($_SESSION['action']) {
            case 'show': self::show(); break;
            case 'add': self::add(); break;
            case 'edit': self::edit(); break;
            case 'post': self::post(); break;
            default:
                self::error('Error: unrecognized command');
        }
        
    }
    
    private static function show() {
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: arguments expected');
            return;
        }
        
        switch ($_SESSION['arguments']) {
            case 'bloodPressure':
                $_SESSION['measurements']['bloodPressure'] = BloodPressureMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                BloodPressureMeasurementsView::show();
                break;
            case 'calories':
                $_SESSION['measurements']['calories'] = CalorieMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                CalorieMeasurementsView::show();
                break;
            case 'exercise':
                $_SESSION['measurements']['exercise'] = ExerciseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                ExerciseMeasurementsView::show();
                break;
            case 'glucose':
                $_SESSION['measurements']['glucose'] = GlucoseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                GlucoseMeasurementsView::show();
                break;
            case 'sleep':
                $_SESSION['measurements']['sleep'] = SleepMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                SleepMeasurementsView::show();
                break;
            case 'weight':
                $_SESSION['measurements']['weight'] = WeightMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
                WeightMeasurementsView::show();
                break;
            case 'all':
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
        }
    }
    
    private static function add() {
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: arguments expected');
            return;
        }
        if (!isset($_POST) || empty($_POST)) {
            self::error('Error: post data missing');
            return;
        }
        
        $measurementID = -1;
        switch ($_SESSION['arguments']) {
            case 'bloodPressure': $measurement = new BloodPressureMeasurement($_POST); break;
            case 'calories': $measurement = new CalorieMeasurement($_POST); break;
            case 'exercise': $measurement = new ExerciseMeasurement($_POST); break;
            case 'glucose': $measurement = new GlucoseMeasurement($_POST); break;
            case 'sleep': $measurement = new SleepMeasurement($_POST); break;
            case 'weight': $measurement = new WeightMeasurement($_POST); break;
            default:
                MeasurementsView::add();
        }
        
        if ($measurement->getErrorCount() > 0) {
            self::setVars('Add failed. Correct any errors and try again.', 'show', $_SESSION['arguments'], 'show');
            return;
        }
        
        switch ($_SESSION['arguments']) {
            case 'bloodPressure': $measurementID = BloodPressureMeasurementsDB::addMeasurement($measurement); break;
            case 'calories': $measurementID = CalorieMeasurementsDB::addMeasurement($measurement); break;
            case 'exercise': $measurementID = ExerciseMeasurementsDB::addMeasurement($measurement); break;
            case 'glucose': $measurementID = GlucoseMeasurementsDB::addMeasurement($measurement); break;
            case 'sleep': $measurementID = SleepMeasurementsDB::addMeasurement($measurement); break;
            case 'weight': $measurementID = WeightMeasurementsDB::addMeasurement($measurement); break;
            default:
                MeasurementsView::add();
        }
            
        if ($measurementID < 0)
            self::setVars('Add failed. Internal error. Try again.', 'show', $_SESSION['arguments'], 'show');
        else
            self::setVars(null, 'show', $_SESSION['arguments'], 'show');
    }
    
    private static function edit() {
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: arguments expected');
            return;
        }
        
        if (strpos($_SESSION['arguments'], '_') === false) {
            self::error('Error: multiple arguments expected');
            return;
        }
        
        $args = explode('_', $_SESSION['arguments']);
        
        switch ($args[0]) {
            case 'show':
                $dateAndTime = str_replace('%20', ' ', $args[2]);
                $dateAndTime = str_replace('H-i', 'H:i', $dateAndTime);
                
                switch ($args[1]) {
                    case 'bloodPressure':
                        $_SESSION['measurement'] = BloodPressureMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                        BloodPressureMeasurementsView::edit();
                        break;
                    case 'calories':
                        $_SESSION['measurement'] = CalorieMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                        CalorieMeasurementsView::edit();
                        break;
                    case 'exercise':
                        $_SESSION['measurement'] = ExerciseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                        ExerciseMeasurementsView::edit();
                        break;
                    case 'glucose':
                        $_SESSION['measurement'] = GlucoseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                        GlucoseMeasurementsView::edit();
                        break;
                    case 'sleep':
                        $_SESSION['measurement'] = SleepMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                        SleepMeasurementsView::edit();
                        break;
                    case 'weight':
                        $_SESSION['measurement'] = WeightMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                        WeightMeasurementsView::edit();
                        break;
                }
                break;
            case 'post':
                self::post($args);
                break;
        }
    }
    
    private static function post($args) {
        if (!isset($_SESSION['measurement'])) {
            self::error('Error: measurement not found');
            return;
        }
        if (!isset($_POST) || empty($_POST)) {
            self::error('Error: post data missing');
            return;
        }
        
        $oldMeasurement = $_SESSION['measurement'];
        switch ($args[1]) {
            case 'bloodPressure':
                $newMeasurement = new BloodPressureMeasurement($_POST);
                $newMeasurement = BloodPressureMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
            case 'calories':
                $newMeasurement = new CalorieMeasurement($_POST);
                $newMeasurement = CalorieMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
            case 'exercise':
                $newMeasurement = new ExerciseMeasurement($_POST);
                $newMeasurement = ExerciseMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
            case 'glucose':
                $newMeasurement = new GlucoseMeasurement($_POST);
                $newMeasurement = GlucoseMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
            case 'sleep':
                $newMeasurement = new SleepMeasurement($_POST);
                $newMeasurement = SleepMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
            case 'weight':
                $newMeasurement = new WeightMeasurement($_POST);
                $newMeasurement = WeightMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
        }
        
        if ($newMeasurement->getErrorCount() > 0)
            self::setVars('Edit failed. Correct any errors and try again.', null, 'show_' . $args[1] . '_' . $oldMeasurement->getDateTime()->format('Y-m-d H-i'), 'edit');
        else
            self::setVars(null, null, $args[1], 'show');
        
        unset($_SESSION['measurement']);
    }
    
    private static function setVars($flash = null, $action = null, $arguments = null, $method = null) {
        if (!is_null($flash))
            $_SESSION['flash'] = $flash;
        if (!is_null($action))
            $_SESSION['action'] = $action;
        if (!is_null($arguments))
            $_SESSION['arguments'] = $arguments;
        if (!is_null($method)) {
            switch ($method) {
                case 'show': self::show(); break;
                case 'edit': self::edit(); break;
                case 'add': self::add(); break;
                case 'post': self::post(); break;
                default:
                    throw new Exception("setVars invalid arguments");
            }
        } else
            throw new Exception("setVars invalid arguments");
    }
    
    private static function error($message = '') {
        ?><p><?=$message?></p><?php
    }
    
    private static function redirect($control = '', $message = null) {
        if (!is_null($message))
            $_SESSION['flash'] = $message;
        if (!empty($control))
            $control = '/' . $control;

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
}
?>