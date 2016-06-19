<?php
class MeasurementsController {
    
    public static function run() {
        
        if (!isset($_SESSION)) {
            self::error('Error: session data not found');
            return;
        }
        
        if (!isset($_SESSION['profile'])) {
            self::redirect('login_show', 'warning', 'You must log in before you can see your measurements. You can use &quot;member&quot; for user name with &quot;pass123&quot; for password or &quot;admin&quot; for user name with &quot;admin&quot; for password.');
            return;
        }
        
        if (!isset($_SESSION['action'])) {
            self::error('Error: unrecognized command');
            return;
        }
        
        switch ($_SESSION['action']) {
            case 'show': self::show(); break;
            case 'add': $result = self::add(); break;
            case 'edit': $result = self::edit(); break;
            case 'post': $result = self::post(); break;
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
    
    /* labels used for various parts of a call: /control_action_arg[0]_arg[1]_..._arg[n-1]
     * example call: /measurements_get_all                          (all time)         (gets all known data for all measurement types at once)
     * example call: /measurements_get_bloodPressure_individual     (for last 30 days) (for both cumulative and non-cumulative measurements)
     * example call: /measurements_get_bloodPressure_day            (for last 30 days) (for both cumulative and non-cumulative measurements)
     * example call: /measurements_get_bloodPressure_week           (for last year)    (for both cumulative and non-cumulative measurements)
     * example call: /measurements_get_bloodPressure_month          (for last year)    (for both cumulative and non-cumulative measurements)
     * example call: /measurements_get_bloodPressure_year           (for last 5 years) (for both cumulative and non-cumulative measurements)
     * example call: /measurements_get_bloodPressure_all            (all time)         (for both cumulative and non-cumulative measurements)
     * example call: /measurements_get_exercise_dailyavg_week       (for last year)    (for cumulative measurements) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_exercise_dailyavg_month      (for last year)    (for cumulative measurements) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_exercise_dailyavg_year       (for last 5 years) (for cumulative measurements) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_exercise_dailyavg_all        (all time)         (for cumulative measurements) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_sleep_dailyavg_week                             (just a reminder that sleep is cumulative)      TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_calories_dailyavg_month                         (just a reminder that calories are cumulative)  TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_bloodPressure_individual_2015-01-25_2016-02-15  (from Jan 25 2015 to Feb 15 2016) (non-cumulative/cumulative)
     * example call: /measurements_get_bloodPressure_day_2015-01-25_2016-02-15         (from Jan 25 2015 to Feb 15 2016) (non-cumulative/cumulative)
     * example call: /measurements_get_bloodPressure_week_2015-01-25_2016-02-15        (from Jan 25 2015 to Feb 15 2016) (non-cumulative/cumulative)
     * example call: /measurements_get_bloodPressure_month_2015-01-25_2016-02-15       (from Jan 25 2015 to Feb 15 2016) (non-cumulative/cumulative)
     * example call: /measurements_get_bloodPressure_year_2015-01-25_2016-02-15        (from Jan 25 2015 to Feb 15 2016) (non-cumulative/cumulative)
     * example call: /measurements_get_bloodPressure_all_2015-01-25_2016-02-15         (from Jan 25 2015 to Feb 15 2016) (non-cumulative/cumulative)
     * example call: /measurements_get_exercise_dailyavg_week_2015-01-25_2016-02-15        (from Jan 25 2015 to Feb 15 2016) (cumulative only) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_exercise_dailyavg_month_2015-01-25_2016-02-15       (from Jan 25 2015 to Feb 15 2016) (cumulative only) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_exercise_dailyavg_year_2015-01-25_2016-02-15        (from Jan 25 2015 to Feb 15 2016) (cumulative only) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_exercise_dailyavg_all_2015-01-25_2016-02-15         (from Jan 25 2015 to Feb 15 2016) (cumulative only) TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_sleep_dailyavg_year_2015-01-25_2016-02-15           (just a reminder that sleep is cumulative)          TODO change 'dailyavg' to 'groupdays'
     * example call: /measurements_get_calories_dailyavg_year_2015-01-25_2016-02-15        (just a reminder that calories are cumulative)      TODO change 'dailyavg' to 'groupdays'
     */
    private static function get() {        
        
        if (!isset($_SESSION['arguments'])) { // arguments don't exist (i.e. there is nothing past 'get' in the call)
            self::error('Error: arguments expected');
            return;
        }
        
        // were all measurements of all types requested?
        if ($_SESSION['arguments'] === 'all') {
            self::getAll();
            return;
        }
        
        // measurements of a specific type requested, either as individual measurements or as daily/weekly/monthly/yearly averages
        $allowedMeasTypes = array('bloodPressure', 'calorie', 'exercise', 'glucose', 'sleep', 'weight');
        $allowedPeriods = array('individual', 'day', 'week', 'month', 'year', 'all');
        $allowedAvgPeriods = array('week', 'month', 'year', 'all');
        $dateRegExpOptions = array("options" => array("regexp" => "/^((\d{4}[\/-]\d\d[\/-]\d\d)|(\d\d[\/-]\d\d[\/-]\d{4}))$/")); // YYYY-MM-DD or MM-DD-YYYY
        $groupEachDay = false;
        
        // break up arguments, taking into account a possible extra argument for daily average requests
        $args = explode('_', $_SESSION['arguments']);
        $measType = array_shift($args);
        if ( ($timePeriod = array_shift($args)) === 'dailyavg') {
            $groupEachDay = true;
            $timePeriod = array_shift($args);
        }
        if (count($args) === 2) {
            $minDate = array_shift($args);
            $maxDate = array_shift($args);
        }
        
        // validate arguments
        if (!in_array($measType, $allowedMeasTypes)) {
            echo '{"error":"Requested measurement type ' .$measType. ' invalid."}';
            return;
        }
        if ($groupEachDay && !in_array($timePeriod, $allowedAvgPeriods)) {
            echo '{"error":"Requested period ' .$timePeriod. ' invalid for daily average data."}';
            return;
        } else if (!$groupEachDay && !in_array($timePeriod, $allowedPeriods)) {
            echo '{"error":"Requested period ' .$timePeriod. ' invalid."}';
            return;
        }
        if (isset($minDate) && isset($maxDate)) {
            if (!filter_var($minDate, FILTER_VALIDATE_REGEXP, $dateRegExpOptions)) {
                echo '{"error":"Requested start date ' .$minDate. ' invalid."}';
                return;
            }
            if (!filter_var($maxDate, FILTER_VALIDATE_REGEXP, $dateRegExpOptions)) {
                echo '{"error":"Requested end date ' .$maxDate. ' invalid."}';
                return;
            }
        } else {
            $minDate = null;
            $maxDate = null;
        }
            
        /* class/function to call depends on measurement type and what data was requested.
         * The data requested can be individual measurements, daily/weekly/monthly/yearly averages,
         * with or without the date range specified. If no date range is specified, a default will be used.
         * The function call is constructed manually as a string and then executed.*/
        $dbModelClass = ucfirst($measType) . 'MeasurementsDB'; // determine which measurement DB model should be called
        if ($timePeriod === 'all')
            $measurements = call_user_func("$dbModelClass::getMeasurementsBy", 'userName', $_SESSION['profile']->getUserName());
        else if ($timePeriod === 'individual')
            $measurements = call_user_func("$dbModelClass::getMeasurementsBounded", 'userName', $_SESSION['profile']->getUserName(), $minDate, $maxDate);
        else
            $measurements = call_user_func("$dbModelClass::getTimePeriodMeasurements", $_SESSION['profile']->getUserName(), $timePeriod, $groupEachDay, $minDate, $maxDate);
        
        // check for error message and output error if one is found
        if (array_key_exists('error', $measurements)) {
            echo '{"error":"' .$measurements['error']. '"}';
            return;
        }
        
        // no errors found; output measurement data in json
        if (isset($_GET['debug']))
            echo '<pre>' .json_encode($measurements, JSON_PRETTY_PRINT). '</pre>';
        else
            echo json_encode($measurements, JSON_PRETTY_PRINT);
        
    }
    
    private static function getAll() {
        $allMeasurements = new stdClass();
        $allMeasurements->bloodPressure = BloodPressureMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
        $allMeasurements->calories = CalorieMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
        $allMeasurements->exercise = ExerciseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
        $allMeasurements->glucose = GlucoseMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
        $allMeasurements->sleep = SleepMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
        $allMeasurements->weight = WeightMeasurementsDB::getMeasurementsBy('userName', $_SESSION['profile']->getUserName());
        echo json_encode($allMeasurements, JSON_PRETTY_PRINT);
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
                // retrieve all measurements options for the user
                $allOptions = MeasurementsOptionsDB::getOptionsFor($_SESSION['profile']->getUserName());
                if (empty($allOptions))
                    $_SESSION['flash'] = 'Error: failed retrieviing your measurements options';
                $_SESSION['allMeasurementsOptions'] = $allOptions;
                
                // find and store the MeasurementsOptions object for the active measurements options
                $activeOptions = null;
                foreach ($_SESSION['allMeasurementsOptions'] as $currentOptions) {
                    if ($currentOptions->isActive())
                        $activeOptions = $currentOptions;
                }
                if ($activeOptions === null)
                    $_SESSION['flash'] = 'Error: failed to find your active measurements options';
                $_SESSION['activeMeasurementsOptions'] = $activeOptions;
                
                // show the measurements page
                MeasurementsView::show();
                break;
        }
    }
    
    private static function add() {
        if (!isset($_SESSION['arguments'])) {
            $_SESSION['error'] = 'arguments expected';
            return false;
        }
        if (!isset($_POST) || empty($_POST)) {
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
            $_SESSION['error'] = 'Add failed: '.array_shift($measurement->getErrors());
            return false;
        }
        
        // make sure a measurement with the specified date and time does not already exist
        $dateAndTime = $_POST['date'].' '.$_POST['time'];
        switch ($_SESSION['arguments']) {
            case 'bloodPressure': $existingMeasurement = BloodPressureMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime); break;
            case 'calories': case 'calorie': $existingMeasurement = CalorieMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime); break;
            case 'exercise': $existingMeasurement = ExerciseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime); break;
            case 'glucose': $existingMeasurement = GlucoseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime); break;
            case 'sleep': $existingMeasurement = SleepMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime); break;
            case 'weight': $existingMeasurement = WeightMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $dateAndTime); break;
            default:
                $_SESSION['error'] = "Unrecognized measurement type argument: $args[1]";
                return false;
        }
        
        if (! is_null($existingMeasurement)) {
            $_SESSION['error'] = 'A measurement with the specified date and time already exists. Update the date and time to a unique value, then try again.';
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
        
        if ($measurementID < 0 || !is_numeric($measurementID)) {
            if (isset($_POST['json'])) {
                $_SESSION['error'] = 'Add failed. Internal error. Try again: ' .$measurementID;
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
            $_SESSION['error'] = 'arguments expected';
            return false;
        }
        
        if (strpos($_SESSION['arguments'], '_') === false) {
            $_SESSION['error'] = 'multiple arguments expected';
            return false;
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
                    case 'calories': case 'calorie':
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
                return self::post($args);
        }
    }
    
    private static function post($args) {
        if (!isset($_POST['oldDateTime']) || empty($_POST['oldDateTime'])) {
            $_SESSION['error'] = 'original measurement date and time not found';
            return false;
        }
        if (!isset($_POST) || empty($_POST)) {
            $_SESSION['error'] = 'post data missing';
            return false;
        }
        
//         $oldDateAndTime = (new DateTime($_POST['oldDateTime'])).format();
        
        switch ($args[1]) {
            case 'bloodPressure': $oldMeasurement = BloodPressureMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'calories': case 'calorie': $oldMeasurement = CalorieMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'exercise': $oldMeasurement = ExerciseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'glucose': $oldMeasurement = GlucoseMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'sleep': $oldMeasurement = SleepMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            case 'weight': $oldMeasurement = WeightMeasurementsDB::getMeasurement($_SESSION['profile']->getUserName(), $_POST['oldDateTime']); break;
            default:
                $_SESSION['error'] = "Unrecognized measurement type argument: $args[1]";
                return false;
        }
        
        if (is_null($oldMeasurement)) {
            $_SESSION['error'] = "Failed to fetch old measurement. Profile: " . $_SESSION['profile']->getUserName() . "; oldDateTime: " . $_POST['oldDateTime'];
            return false;
        }
        
        switch ($args[1]) {
            case 'bloodPressure':       
                $newMeasurement = new BloodPressureMeasurement($_POST);
                $newMeasurement = BloodPressureMeasurementsDB::editMeasurement($oldMeasurement, $newMeasurement);
                break;
            case 'calories': case 'calorie':
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
        
        if ($newMeasurement->getErrorCount() > 0) {
            if (isset($_POST['json'])) {
                $_SESSION['error'] = 'Edit failed. Correct any errors and try again.';
                return false;
            }
            else
                self::setVars('danger', 'Edit failed. Correct any errors and try again.', 'show', 'all', 'show');
        }
        else {
            if (isset($_POST['json']))
                return true;
            else
                self::setVars('success', 'Measurement edited', 'show', 'all', 'show');
        }
        
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

        header('Location: https://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['base'] . $control);
    }
}
?>