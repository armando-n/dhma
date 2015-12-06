<?php
class MeasurementsController {
    
    public static function run() {
        
        if (!isset($_SESSION)) {
            self::error('Error: session data not found');
            return;
        }
        
        if (!isset($_SESSION['profile'])) {
            self::redirect('login_show', 'warning', 'You must log in before you can see your measurements. For professor/grader: Use &quot;armando-n&quot; for user name, and &quot;pass123&quot; for password.');
            return;
        }
        
        if (!isset($_SESSION['action'])) {
            self::error('Error: unrecognized command');
            return;
        }
        
        switch ($_SESSION['action']) {
            case 'show': self::show(); break;
            case 'add': $result = self::add(); break;
            case 'edit': self::edit(); break;
            case 'post': self::post(); break;
            case 'delete': $result = self::delete(); break;
            case 'get': self::get(); break;
            default:
                self::error('Error: unrecognized command');
        }
        
        if (isset($_POST) && isset($_POST['json'])) {
            if ($result)
                echo '{"result":true}';
            else
                echo '{"result":false, "error":"' .$_SESSION['error']. '"}';
        }
    }
    
    // example call: /measurements_get_all                         (all time)
    // example call: /measurements_get_bloodPressure_all           (all time)
    // example call: /measurements_get_bloodPressure_individual    (for last 30 days)
    // example call: /measurements_get_bloodPressure_day           (for last 30 days)
    // example call: /measurements_get_bloodPressure_week          (for last year)
    // example call: /measurements_get_bloodPressure_month         (for last year)
    // example call: /measurements_get_bloodPressure_year          (for last 5 years)
    // example call: /measurements_get_exercise_dailyavg_week
    // example call: /measurements_get_sleep_dailyavg_month
    // example call: /measurements_get_calories_dailyavg_year
    private static function get() {        
        
        // partial input validation
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: 2 arguments expected');
            return;
        }
        if (strpos($_SESSION['arguments'], '_') === false && $_SESSION['arguments'] !== 'all') {
            self::error('Error: invalid argument(s)');
            return;
        }
        
        // all measurements of all types requested
        if (strpos($_SESSION['arguments'], '_') === false && $_SESSION['arguments'] === 'all') {
            $allMeasurements = new stdClass();
            $allMeasurements->bloodPressure = BloodPressureMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
            $allMeasurements->calories = CalorieMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
            $allMeasurements->exercise = ExerciseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
            $allMeasurements->glucose = GlucoseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
            $allMeasurements->sleep = SleepMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
            $allMeasurements->weight = WeightMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
            echo json_encode($allMeasurements, JSON_PRETTY_PRINT);
        }
        
        // measurements of a specific type requested, either as individual measurements or as daily/weekly/monthly/yearly averages
        else {
            
            // validate arguments
            $args = explode('_', $_SESSION['arguments']);
            if (count($args) == 3)
                list($firstArg, $secondArg, $thirdArg) = $args;
            else
                list($firstArg, $secondArg) = $args; 
            $allowedFirst = array('bloodPressure', 'calorie', 'exercise', 'glucose', 'sleep', 'weight');
            $allowedSecond = array('dailyavg', 'all', 'individual', 'day', 'week', 'month', 'year');
            $allowedThird = array('week', 'month', 'year');
            if (!in_array($firstArg, $allowedFirst) || !in_array($secondArg, $allowedSecond)) {
                echo '{"error":"One or both arguments invalid. firstarg: ' .$firstArg. '; secondarg: ' .$secondArg. '"}';
                return; 
            }
            if (isset($thirdArg) && !in_array($thirdArg, $allowedThird)) {
                echo '{"error":"One or more arguments invalid"}';
                return;
            }
            
            /* class/function to call depends on measurement type and what the data requested.
             * The data requested can be individual measurements, or daily/weekly/monthly/yearly averages. */
            $dbClassName = ucfirst($firstArg) . 'MeasurementsDB';
            if ($secondArg === 'all')
                $queryCommand = 'return ' .$dbClassName. '::getMeasurementsBy("userName", "' .$_SESSION['profile']->getUserName(). '");';
            else if ($secondArg === 'individual')
                $queryCommand = 'return ' .$dbClassName. '::getMeasurementsBounded("userName", "' .$_SESSION['profile']->getUserName(). '");';
            else if ($secondArg === 'dailyavg') // TODO finish this
                $queryCommand = 'return ' .$dbClassName. '::getAverageMeasurements("' .$_SESSION["profile"]->getUserName(). '", "' .$thirdArg. '", true);';
            else
                $queryCommand = 'return ' .$dbClassName. '::getAverageMeasurements("' .$_SESSION["profile"]->getUserName(). '", "' .$secondArg. '");';
            
            // get measurement data and check for error message
            $measurements = eval($queryCommand);
            if (array_key_exists('error', $measurements)) {
                echo '{"error":"' .$measurements['error']. '"}';
                return;
            }
            
            // output measurement data in json
            if (isset($_GET['debug']))
                echo '<pre>' .json_encode($measurements, JSON_PRETTY_PRINT). '</pre>';
            else
                echo json_encode($measurements, JSON_PRETTY_PRINT);
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
                MeasurementsView::show();
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
            $_SESSION['error'] = 'arguments expected';
            return false;
        }
        if (!isset($_POST) || empty($_POST)) {
            self::error('Error: post data missing');
            $_SESSION['error'] = 'post data missing';
            return false;
        }
        
        $measurementID = -1;
        switch ($_SESSION['arguments']) {
            case 'bloodPressure': $measurement = new BloodPressureMeasurement($_POST); break;
            case 'calories': case 'calorie': $measurement = new CalorieMeasurement($_POST); break;
            case 'exercise': $measurement = new ExerciseMeasurement($_POST); break;
            case 'glucose': $measurement = new GlucoseMeasurement($_POST); break;
            case 'sleep': $measurement = new SleepMeasurement($_POST); break;
            case 'weight': $measurement = new WeightMeasurement($_POST); break;
            default:
                $_SESSION['error'] = 'unrecognized measurement type: ' .$_SESSION['arguments'];
                return false;
        }
        
        if ($measurement->getErrorCount() > 0) {
            self::setVars('danger', 'Add failed. Correct any errors and try again.', 'show', 'all', 'show');
            $_SESSION['error'] = 'Add failed. Correct any errors and try again.';
            return false;
        }
        
        switch ($_SESSION['arguments']) {
            case 'bloodPressure':
                $measurementID = BloodPressureMeasurementsDB::addMeasurement($measurement);
                break;
            case 'calories': case 'calorie':
                $measurementID = CalorieMeasurementsDB::addMeasurement($measurement);
                break;
            case 'exercise':
                $measurementID = ExerciseMeasurementsDB::addMeasurement($measurement);
                break;
            case 'glucose':
                $measurementID = GlucoseMeasurementsDB::addMeasurement($measurement);
                break;
            case 'sleep':
                $measurementID = SleepMeasurementsDB::addMeasurement($measurement);
                break;
            case 'weight':
                $measurementID = WeightMeasurementsDB::addMeasurement($measurement);
                break;
            default:
                $_SESSION['error'] = 'unrecognized measurement type: ' .$_SESSION['arguments'];
                return false;
        }
        
        if ($measurementID < 0) {
            if (isset($_POST['json'])) {
                $_SESSION['error'] = 'Add failed. Internal error. Try again.';
                return false;
            } else
                self::setVars('danger', 'Add failed. Internal error. Try again.', 'show', 'all', 'show');
        }
        else {
            if (isset($_POST['json']))
                return true;
            else
                self::setVars('success', 'Measurement added', 'show', 'all', 'show');
        }
        
    }
    
    private static function edit() {
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: arguments expected');
            $_SESSION['error'] = 'arguments expected';
            return;
        }
        
        if (strpos($_SESSION['arguments'], '_') === false) {
            self::error('Error: multiple arguments expected');
            $_SESSION['error'] = 'multiple arguments expected';
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
        if (!isset($_POST['oldDateTime']) || empty($_POST['oldDateTime'])) {
            self::error('Error: original measurement date and time not found');
            return;
        }
        if (!isset($_POST) || empty($_POST)) {
            self::error('Error: post data missing');
            return;
        }
        
        switch ($args[1]) {
            case 'bloodPressure': $oldMeasurement = BloodPressureMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'calories': $oldMeasurement = CalorieMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'exercise': $oldMeasurement = ExerciseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'glucose': $oldMeasurement = GlucoseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'sleep': $oldMeasurement = SleepMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'weight': $oldMeasurement = WeightMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            default:
                echo "Unrecognized measurement type argument: $args[1]";
        }
        
        if (is_null($oldMeasurement)) {
            echo "Failed to fetch old measurement. Profile: " . $_SESSION['profile']->getUserName() . "; oldDateTime: " . $_POST['oldDateTime'];
            return;
        }
        
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
            self::setVars('danger', 'Edit failed. Correct any errors and try again.', 'show', 'all', 'show');
        else
            self::setVars('success', 'Measurement edited', 'show', 'all', 'show');
        
        unset($_SESSION['measurement']);
    }
    
    private static function delete() {
        if (!isset($_SESSION['arguments'])) {
            self::error('Error: arguments expected');
            return false;
        }
    
        if (strpos($_SESSION['arguments'], '_') === false) {
            self::error('Error: multiple arguments expected');
            return false;
        }
    
        $args = explode('_', $_SESSION['arguments']);
    
        $dateAndTime = str_replace('%20', ' ', $args[1]);
        $dateAndTime = str_replace('H-i', 'H:i', $dateAndTime);

        switch ($args[0]) {
            case 'bloodPressure':
                BloodPressureMeasurementsDB::deleteMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                break;
            case 'calories': case 'calorie':
                CalorieMeasurementsDB::deleteMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                break;
            case 'exercise':
                ExerciseMeasurementsDB::deleteMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                break;
            case 'glucose':
                GlucoseMeasurementsDB::deleteMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                break;
            case 'sleep':
                SleepMeasurementsDB::deleteMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                break;
            case 'weight':
                WeightMeasurementsDB::deleteMeasurement($_SESSION['profile']->getUserName(), $dateAndTime);
                break;
        }
        
        if (isset($_POST['json']))
            return true;
        
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
        self::setVars('info', 'Measurement deleted', 'show', 'all', 'show');
    }
    
    private static function setVars($alertType = 'info', $flash = null, $action = null, $arguments = null, $method = null) {
        if (!is_null($flash)) {
            $_SESSION['alertType'] = $alertType;
            $_SESSION['flash'] = $flash;
        }
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
    
    private static function redirect($control = '', $alertType = 'info', $message = null) {
        if (!is_null($message)) {
            $_SESSION['alertType'] = $alertType;
            $_SESSION['flash'] = $message;
        }
        if (!empty($control))
            $control = '/' . $control;

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
}
?>