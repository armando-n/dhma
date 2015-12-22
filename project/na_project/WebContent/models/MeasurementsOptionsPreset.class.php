<?php
class MeasurementsOptionsPreset extends GenericModelObject implements JsonSerializable {
    
    const DEFAULT_BLOODPRESSURE_UNITS = 'mm Hg';
    const DEFAULT_CALORIE_UNITS = 'calories';
    const DEFAULT_EXERCISE_UNITS = 'minutes';
    const DEFAULT_GLUCOSE_UNITS = 'mg/dL';
    const DEFAULT_SLEEP_UNITS = 'minutes';
    const DEFAULT_WEIGHT_UNITS = 'lbs';
    const DEFAULT_TIME_FORMAT = '12 hour';
    const DEFAULT_SHOW_TOOLTIPS = true;
    const DEFAULT_SHOW_EXERCISETYPE_COL = false;
    const DEFAULT_SHOW_DATE_COL = true;
    const DEFAULT_SHOW_TIME_COL = true;
    const DEFAULT_SHOW_NOTES_COL = true;
    const DEFAULT_NUM_ROWS = 10;
    const DEFAULT_SHOW_FIRST_CHART = true;
    const DEFAULT_SHOW_SECOND_CHART = true;
    const DEFAULT_FIRST_CHART_TYPE = 'individual';
    const DEFAULT_SECOND_CHART_TYPE = 'monthly';
    private static final $DEFAULT_FIRST_CHART_START;
    private static final $DEFAULT_SECOND_CHART_START;
    private static final $DEFAULT_FIRST_CHART_END;
    private static final $DEFAULT_SECOND_CHART_END;
    const DEFAULT_CHART_LAST_YEAR = false;
    const DEFAULT_CHART_DAILY_AVERAGES = false;
    
    private $formInput;
    private $presetName;
    private $userName;
    private $bloodPressureUnits;
    private $calorieUnits;
    private $exerciseUnits;
    private $glucoseUnits;
    private $sleepUnits;
    private $weightUnits;
    private $timeFormat;
    private $showTooltips;
    private $showExerciseTypeCol;
    private $showDateCol;
    private $showTimeCol;
    private $showNotesCol;
    private $numRows;           // new
    private $showFirstChart;
    private $showSecondChart;
    private $firstChartType;
    private $secondChartType;
    private $firstChartStart;  // new from here on down
    private $secondChartStart;
    private $firstChartEnd;
    private $secondChartEnd;
    private $chartLastYear;
    private $chartDailyAverages;
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
    }
    
    public function getPresetName() {
        return $this->presetName;
    }
    
    public function getUserName() {
        return $this->userName;
    }
    
    public function getBloodPressureUnits() {
        return $this->bloodPressureUnits;
    }
    
    public function getCalorieUnits() {
        return $this->calorieUnits;
    }

    public function getExerciseUnits() {
        return $this->exerciseUnits;
    }
    
    public function getGlucoseUnits() {
        return $this->glucoseUnits;
    }
    
    public function getSleepUnits() {
        return $this->sleepUnits;
    }
    
    public function getWeightUnits() {
        return $this->weightUnits;
    }
    
    public function getTimeFormat() {
        return $this->timeFormat;
    }
    
    public function getShowTooltips() {
        return $this->showTooltips;
    }
    
    public function getShowExerciseTypeCol() {
        return $this->showExerciseTypeCol;
    }
    
    public function getShowDateCol() {
        return $this->showDateCol;
    }
    
    public function getShowTimeCol() {
        return $this->showTimeCol;
    }
    
    public function getShowNotesCol() {
        return $this->showNotesCol;
    }
    
    public function getNumRows() {
        return $this->numRows;
    }
    
    public function getShowFirstChart() {
        return $this->showFirstChart;
    }
    
    public function getShowSecondChart() {
        return $this->showSecondChart;
    }
    
    public function getFirstChartType() {
        return $this->firstChartType;
    }
    
    public function getSecondChartType() {
        return $this->secondChartType;
    }
    
    public function getFirstChartStart() {
        return $this->firstChartStart;
    }
    
    public function getSecondChartStart() {
        return $this->secondChartStart;
    }
    
    public function getFirstChartEnd() {
        return $this->firstChartEnd;
    }
    
    public function getSecondChartEnd() {
        return $this->secondChartEnd;
    }
    
    public function getChartLastYear() {
        return $this->chartLastYear;
    }
    
    public function getChartDailyAverages() {
        return $this->dailyAverages;
    }
    
    // Returns data fields as an associative array
    public function getParameters() {
        $paramArray = array(
            "presetName" => $this->presetName,
            "userName" => $this->userName,
            "bloodPressureUnits" => $this->bloodPressureUnits,
            "calorieUnits" => $this->calorieUnits,
            "exerciseUnits" => $this->exerciseUnits,
            "glucoseUnits" => $this->glucoseUnits,
            "sleepUnits" => $this->sleepUnits,
            "weightUnits" => $this->weightUnits,
            "timeFormat" => $this->timeFormat,
            "showTooltips" => $this->showTooltips,
            "showExerciseTypeCol" => $this->showExerciseTypeCol,
            "showDateCol" => $this->showDateCol,
            "showTimeCol" => $this->showTimeCol,
            "showNotesCol" => $this->showNotesCol,
            "numRows" => $this->numRows,
            "showFirstChart" => $this->showFirstChart,
            "showSecondChart" => $this->showSecondChart,
            "firstChartType" => $this->firstChartType,
            "secondChartType" => $this->secondChartType,
            "firstChartStart" => $this->firstChartStart,
            "secondChartStart" => $this->secondChartStart,
            "firstChartEnd" => $this->firstChartEnd,
            "secondChartEnd" => $this->secondChartEnd,
            "chartLastYear" => $this->chartLastYear,
            "chartDailyAverages" => $this->chartDailyAverages
        );
        
        return $paramArray;
    }
    
    public function __toString() {
        $str =
            "Preset Name: [" . $this->presetName . "]\n" .
            "User Name: [" . $this->userName . "]\n" .
            "Blood Pressure Units: [" . $this->bloodPressureUnits . "]\n" .
            "Calorie Units: [" . $this->calorieUnits . "]\n" .
            "Exercise Units: [" . $this->exerciseUnits . "]\n" .
            "Glucose Units: [" . $this->glucoseUnits . "]\n" .
            "Sleep Units: [" . $this->sleepUnits . "]\n" .
            "Weight Units: [" . $this->weightUnits . "]\n" .
            "Time Format: [" . $this->timeFormat . "]\n" .
            "Show Tooltips: [" . (($this->showTooltips === true) ? "true" : "false") . "]\n" .
            "Show Exercise Type Column: [" . (($this->showExerciseTypeCol === true) ? "true" : "false") . "]\n" .
            "Show Date Column: [" . (($this->showDateCol === true) ? "true" : "false") . "]\n" .
            "Show Time Column: [" . (($this->showTimeCol === true) ? "true" : "false") . "]\n" .
            "Show Notes Column: [" . (($this->showNotesCol === true) ? "true" : "false") . "]\n" .
            "Number of Rows per Page: [" . $this->numRows . "]\n" .
            "Show First Chart: [" . (($this->showFirstChart === true) ? "true" : "false") . "]\n" .
            "Show Second Chart: [" . (($this->showSecondChart === true) ? "true" : "false") . "]\n" .
            "First Chart Type: [" . $this->firstChartType . "]\n" .
            "Second Chart Type: [" . $this->secondChartType . "]\n" .
            "First Chart Start Date: [" . $this->firstChartStart . "]\n" .
            "Second Chart Start Date: [" . $this->secondChartStart . "]\n" .
            "First Chart End Date: [" . $this->firstChartEnd . "]\n" .
            "Second Chart End Date: [" . $this->secondChartEnd . "]\n" .
            "Chart Last Year: [" . (($this->chartLastYear === true) ? "true" : "false") . "]\n" .
            "Chart Daily Averages: [" . (($this->chartDailyAverages === true) ? "true" : "false") . "]";
        
        return $str;
    }
    
    public function jsonSerialize() {
        $object = new stdClass();
        $object->presetName = $this->presetName;
        $object->userName = $this->userName;
        $object->bloodPressureUnits = $this->bloodPressureUnits;
        $object->calorieUnits = $this->calorieUnits;
        $object->exerciseUnits = $this->exerciseUnits;
        $object->glucoseUnits = $this->glucoseUnits;
        $object->sleepUnits = $this->sleepUnits;
        $object->weightUnits = $this->weightUnits;
        $object->timeFormat = $this->timeFormat;
        $object->showTooltips = $this->showTooltips;
        $object->showExerciseTypeCol = $this->showExerciseTypeCol;
        $object->showDateCol = $this->showDateCol;
        $object->showTimeCol = $this->showTimeCol;
        $object->showNotesCol = $this->showNotesCol;
        $object->numRows = $this->numRows;
        $object->showFirstChart = $this->showFirstChart;
        $object->showSecondChart = $this->showSecondChart;
        $object->firstChartType = $this->firstChartType;
        $object->secondChartType = $this->secondChartType;
        $object->firstChartStart = $this->firstChartStart;
        $object->secondChartStart = $this->secondChartStart;
        $object->firstChartEnd = $this->firstChartEnd;
        $object->secondChartEnd = $this->secondChartEnd;
        $object->chartLastYear = $this->chartLastYear;
        $object->chartDailyAverages = $this->chartDailyAverages;
    
        return $object;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->formInput)) {
            $this->setError('measurementsOptionsPreset', 'NULL_INPUT');
        }
        else {
            self::$DEFAULT_FIRST_CHART_START = (new DateTime())->sub(new DateInterval('P1M'));
            self::$DEFAULT_SECOND_CHART_START = (new DateTime())->sub(new DateInterval('P1Y'));
            self::$DEFAULT_FIRST_CHART_END = new DateTime();
            self::$DEFAULT_SECOND_CHART_END = new DateTime();
            $this->validatePresetName();
            $this->validateUserName();
            $this->validateBloodPressureUnits();
            $this->validateCalorieUnits();
            $this->validateExerciseUnits();
            $this->validateGlucoseUnits();
            $this->validateSleepUnits();
            $this->validateWeightUnits();
            $this->validateTimeFormat();
            $this->validateShowTooltips();
            $this->validateShowExerciseTypeCol();
            $this->validateShowDateCol();
            $this->validateShowTimeCol();
            $this->validateShowNotesCol();
            $this->validateNumRows();
            $this->validateShowFirstChart();
            $this->validateShowSecondChart();
            $this->validateFirstChartType();
            $this->validateSecondChartType();
            $this->validateFirstChartStart();
            $this->validateSecondChartStart();
            $this->validateFirstChartEnd();
            $this->validateSecondChartEnd();
            $this->validateChartLastYear();
            $this->validateChartDailyAverages();
        }
    }
    
    private function validatePresetName() {
        $this->presetName = $this->extractForm($this->formInput, "presetName");
        if (empty($this->presetName)) {
            $this->presetName = null;
            $this->setError("presetName", "PRESET_NAME_EMPTY");
            return;
        }
    }
    
    private function validateUserName() {
        $this->userName = $this->extractForm($this->formInput, "userName");
        if (empty($this->userName)) {
            $this->userName = null;
            $this->setError("userName", "USER_NAME_EMPTY");
            return;
        }
    
        if (strlen($this->userName) > 20) {
            $this->setError("userName", "USER_NAME_TOO_LONG");
            return;
        }
    
        $options = array("options" => array("regexp" => "/^[a-zA-Z0-9-]+$/"));
        if (!filter_var($this->userName, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("userName", "USER_NAME_HAS_INVALID_CHARS");
            return;
        }
    }
    
    private function validateBloodPressureUnits() {
        $this->bloodPressureUnits = $this->extractForm($this->formInput, "bloodPressureUnits");
        if (empty($this->bloodPressureUnits)) {
            $this->bloodPressureUnits = self::DEFAULT_BLOODPRESSURE_UNITS;
            return;
        }
        
        $allowed = array('mm Hg');
        if (!in_array($this->bloodPressureUnits, $allowed)) {
            $this->setError('bloodPressureUnits', 'UNITS_INVALID');
            return;
        }
    }
    
    private function validateCalorieUnits() {
        $this->calorieUnits = $this->extractForm($this->formInput, "calorieUnits");
        if (empty($this->calorieUnits)) {
            $this->calorieUnits = self::DEFAULT_CALORIE_UNITS;
            return;
        }
        
        $allowed = array('calories');
        if (!in_array($this->calorieUnits, $allowed)) {
            $this->setError("calorieUnits", "UNITS_INVALID");
            return;
        }
    }
    
    private function validateExerciseUnits() {
        $this->exerciseUnits = $this->extractForm($this->formInput, "exerciseUnits");
        if (empty($this->exerciseUnits)) {
            $this->exerciseUnits = self::DEFAULT_EXERCISE_UNITS;
            return;
        }
        
        $allowed = array('minutes');
        if (!in_array($this->exerciseUnits, $allowed)) {
            $this->setError("exerciseUnits", "UNITS_INVALID");
            return;
        }
    }
    
    private function validateGlucoseUnits() {
        $this->glucoseUnits = $this->extractForm($this->formInput, "glucoseUnits");
        if (empty($this->glucoseUnits)) {
            $this->glucoseUnits = self::DEFAULT_GLUCOSE_UNITS;
            return;
        }
        
        $allowed = array('mg/dL');
        if (!in_array($this->glucoseUnits, $allowed)) {
            $this->setError("glucoseUnits", "UNITS_INVALID");
            return;
        }
    }
    
    private function validateSleepUnits() {
        $this->sleepUnits = $this->extractForm($this->formInput, "sleepUnits");
        if (empty($this->sleepUnits)) {
            $this->sleepUnits = self::DEFAULT_SLEEP_UNITS;
            return;
        }
        
        $allowed = array('minutes');
        if (!in_array($this->sleepUnits, $allowed)) {
            $this->setError("sleepUnits", "UNITS_INVALID");
            return;
        }
    }
    
    private function validateWeightUnits() {
        $this->weightUnits = $this->extractForm($this->formInput, "weightUnits");
        if (empty($this->weightUnits)) {
            $this->weightUnits = self::DEFAULT_WEIGHT_UNITS;
            return;
        }
        
        $allowed = array('lbs');
        if (!in_array($this->weightUnits, $allowed)) {
            $this->setError("weightUnits", "UNITS_INVALID");
            return;
        }
    }
    
    private function validateTimeFormat() {
        $this->timeFormat = $this->extractForm($this->formInput, "timeFormat");
        if (empty($this->timeFormat)) {
            $this->timeFormat = self::DEFAULT_TIME_FORMAT;
            return;
        }
        
        $allowed = array('12 hour', '24 hour');
        if (!in_array($this->timeFormat, $allowed)) {
            $this->setError("timeFormat", "TIME_FORMAT_INVALID");
            return;
        }
    }
    
    private function validateShowTooltips() {
        $value = $this->extractForm($this->formInput, "showTooltips");
        if (empty($value)) {
            $this->showTooltips = self::DEFAULT_SHOW_TOOLTIPS;
            return;
        }

        $this->showTooltips = ($value) ? true : false;
    }
    
    private function validateShowExerciseTypeCol() {
        $value = $this->extractForm($this->formInput, "showExerciseTypeCol");
        if (empty($value)) {
            $this->showExerciseTypeCol= self::DEFAULT_SHOW_EXERCISETYPE_COL;
            return;
        }
    
        $this->showExerciseTypeCol = ($value) ? true : false;
    }
    
    private function validateShowDateCol() {
        $value = $this->extractForm($this->formInput, "showDateCol");
        if (empty($value)) {
            $this->showDateCol = self::DEFAULT_SHOW_DATE_COL;
            return;
        }
    
        $this->showDateCol = ($value) ? true : false;
    }
    
    private function validateShowTimeCol() {
        $value = $this->extractForm($this->formInput, "showTimeCol");
        if (empty($value)) {
            $this->showTimeCol = self::DEFAULT_SHOW_TIME_COL;
            return;
        }
    
        $this->showTimeCol = ($value) ? true : false;
    }
    
    private function validateShowNotesCol() {
        $value = $this->extractForm($this->formInput, "showNotesCol");
        if (empty($value)) {
            $this->showNotesCol = self::DEFAULT_SHOW_NOTES_COL;
            return;
        }
    
        $this->showNotesCol = ($value) ? true : false;
    }
    
    private function validateNumRows() {
        $this->numRows = $this->extractForm($this->formInput, "numRows");
        if (empty($this->numRows)) {
            $this->numRows = self::DEFAULT_NUM_ROWS;
            return;
        }
        
        $options = array("options" => array("regexp" => "/^[0-9]{1,5}$/"));
        if (!filter_var($this->firstName, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("numRows", "NUM_ROWS_INVALID");
            return;
        }
    }
    
    private function validateShowFirstChart() {
        $value = $this->extractForm($this->formInput, "showFirstChart");
        if (empty($value)) {
            $this->showFirstChart = self::DEFAULT_SHOW_FIRST_CHART;
            return;
        }
        
        $this->showFirstChart = ($value) ? true : false;
    }
    
    private function validateShowSecondChart() {
        $value = $this->extractForm($this->formInput, "showSecondChart");
        if (empty($value)) {
            $this->showSecondChart = self::DEFAULT_SHOW_SECOND_CHART;
            return;
        }
        
        $this->showSecondChart = ($value) ? true : false;
    }
    
    private function validateFirstChartType() {
        $this->firstChartType = $this->extractForm($this->formInput, "firstChartType");
        if (empty($this->firstChartType)) {
            $this->firstChartType = self::DEFAULT_FIRST_CHART_TYPE;
            return;
        }
        
        $allowed = array('individual', 'daily', 'weekly', 'monthly', 'yearly');
        if (!in_array($this->firstChartType, $allowed)) {
            $this->setError('firstChartType', 'CHART_TYPE_INVALID');
            return;
        }
    }
    
    private function validateSecondChartType() {
        $this->secondChartType = $this->extractForm($this->formInput, "secondChartType");
        if (empty($this->secondChartType)) {
            $this->secondChartType = self::DEFAULT_SECOND_CHART_TYPE;
            return;
        }
    
        $allowed = array('individual', 'daily', 'weekly', 'monthly', 'yearly');
        if (!in_array($this->secondChartType, $allowed)) {
            $this->setError('secondChartType', 'CHART_TYPE_INVALID');
            return;
        }
    }
    
    private function validateFirstChartStart() {
        $this->firstChartStart = $this->extractForm($this->formInput, "firstChartStart");
        if (empty($this->firstChartStart)) {
            $this->firstChartStart = self::$DEFAULT_FIRST_CHART_START;
            return;
        }
        
        try { $dt = new DateTime($this->firstChartStart); }
        catch (Exception $e) {
            $this->setError("firstChartStart", "DATE_INVALID");
            return;
        }
        
        $this->firstChartStart = $dt;
    }
    
    private function validateSecondChartStart() {
        $this->secondChartStart = $this->extractForm($this->formInput, "secondChartStart");
        if (empty($this->secondChartStart)) {
            $this->secondChartStart = self::$DEFAULT_SECOND_CHART_START;
            return;
        }
    
        try { $dt = new DateTime($this->secondChartStart); }
        catch (Exception $e) {
            $this->setError("secondChartStart", "DATE_INVALID");
            return;
        }
    
        $this->secondChartStart = $dt;
    }
    
    private function validateFirstChartEnd() {
        $this->firstChartEnd = $this->extractForm($this->formInput, "firstChartEnd");
        if (empty($this->firstChartEnd)) {
            $this->firstChartEnd = self::$DEFAULT_FIRST_CHART_END;
            return;
        }
    
        try { $dt = new DateTime($this->firstChartEnd); }
        catch (Exception $e) {
            $this->setError("firstChartEnd", "DATE_INVALID");
            return;
        }
    
        $this->firstChartEnd = $dt;
    }
    
    private function validateSecondChartEnd() {
        $this->secondChartEnd = $this->extractForm($this->formInput, "secondChartEnd");
        if (empty($this->secondChartEnd)) {
            $this->secondChartEnd = self::$DEFAULT_SECOND_CHART_END;
            return;
        }
    
        try { $dt = new DateTime($this->secondChartEnd); }
        catch (Exception $e) {
            $this->setError("secondChartEnd", "DATE_INVALID");
            return;
        }
    
        $this->secondChartEnd = $dt;
    }
    
    private function validateChartLastYear() {
        $value = $this->extractForm($this->formInput, "chartLastYear");
        if (empty($value)) {
            $this->chartLastYear = self::DEFAULT_CHART_LAST_YEAR;
            return;
        }
        
        $this->chartLastYear = ($value) ? true : false;
    }
    
    private function validateChartDailyAverages() {
        $value = $this->extractForm($this->formInput, "chartDailyAverages");
        if (empty($value)) {
            $this->chartDailyAverages = self::DEFAULT_CHART_DAILY_AVERAGES;
            return;
        }
        
        $this->chartDailyAverages = ($value) ? true : false;
    }
    
}
?>