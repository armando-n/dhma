<?php
class MeasurementsOptions extends GenericModelObject implements JsonSerializable {
    
    const DATE_FORMAT = "Y-m-d";
    const DEFAULT_BLOODPRESSURE_UNITS = 'mm Hg';
    const DEFAULT_CALORIE_UNITS = 'calories';
    const DEFAULT_EXERCISE_UNITS = 'minutes';
    const DEFAULT_GLUCOSE_UNITS = 'mg/dL';
    const DEFAULT_SLEEP_UNITS = 'minutes';
    const DEFAULT_WEIGHT_UNITS = 'lbs';
    const DEFAULT_TIME_FORMAT = '12 hour';
    const DEFAULT_DURATION_FORMAT = 'hours:minutes';
    const DEFAULT_SHOW_TOOLTIPS = true;
    const DEFAULT_SHOW_SECONDARY_COLS = true;
    const DEFAULT_SHOW_DATE_COL = true;
    const DEFAULT_SHOW_TIME_COL = true;
    const DEFAULT_SHOW_NOTES_COL = true;
    const DEFAULT_NUM_ROWS = 25;
    const DEFAULT_SHOW_TABLE = true;
    const DEFAULT_TABLE_SIZE = 35;
    const DEFAULT_CHART_PLACEMENT = 'bottom';
    const DEFAULT_SHOW_FIRST_CHART = true;
    const DEFAULT_SHOW_SECOND_CHART = true;
    const DEFAULT_FIRST_CHART_TYPE = 'individual';
    const DEFAULT_SECOND_CHART_TYPE = 'monthly';
    const DEFAULT_CHART_LAST_YEAR = false;
    const DEFAULT_CHART_GROUP_DAYS = false;
    private static $DEFAULT_INDIVIDUAL_BLOODPRESSURE_CHART_START;
    private static $DEFAULT_INDIVIDUAL_BLOODPRESSURE_CHART_END;
    private static $DEFAULT_DAILY_BLOODPRESSURE_CHART_START;
    private static $DEFAULT_DAILY_BLOODPRESSURE_CHART_END;
    private static $DEFAULT_WEEKLY_BLOODPRESSURE_CHART_START;
    private static $DEFAULT_WEEKLY_BLOODPRESSURE_CHART_END;
    private static $DEFAULT_MONTHLY_BLOODPRESSURE_CHART_START;
    private static $DEFAULT_MONTHLY_BLOODPRESSURE_CHART_END;
    private static $DEFAULT_YEARLY_BLOODPRESSURE_CHART_START;
    private static $DEFAULT_YEARLY_BLOODPRESSURE_CHART_END;
    private static $DEFAULT_INDIVIDUAL_CALORIES_CHART_START;
    private static $DEFAULT_INDIVIDUAL_CALORIES_CHART_END;
    private static $DEFAULT_DAILY_CALORIES_CHART_START;
    private static $DEFAULT_DAILY_CALORIES_CHART_END;
    private static $DEFAULT_WEEKLY_CALORIES_CHART_START;
    private static $DEFAULT_WEEKLY_CALORIES_CHART_END;
    private static $DEFAULT_MONTHLY_CALORIES_CHART_START;
    private static $DEFAULT_MONTHLY_CALORIES_CHART_END;
    private static $DEFAULT_YEARLY_CALORIES_CHART_START;
    private static $DEFAULT_YEARLY_CALORIES_CHART_END;
    private static $DEFAULT_INDIVIDUAL_EXERCISE_CHART_START;
    private static $DEFAULT_INDIVIDUAL_EXERCISE_CHART_END;
    private static $DEFAULT_DAILY_EXERCISE_CHART_START;
    private static $DEFAULT_DAILY_EXERCISE_CHART_END;
    private static $DEFAULT_WEEKLY_EXERCISE_CHART_START;
    private static $DEFAULT_WEEKLY_EXERCISE_CHART_END;
    private static $DEFAULT_MONTHLY_EXERCISE_CHART_START;
    private static $DEFAULT_MONTHLY_EXERCISE_CHART_END;
    private static $DEFAULT_YEARLY_EXERCISE_CHART_START;
    private static $DEFAULT_YEARLY_EXERCISE_CHART_END;
    private static $DEFAULT_INDIVIDUAL_GLUCOSE_CHART_START;
    private static $DEFAULT_INDIVIDUAL_GLUCOSE_CHART_END;
    private static $DEFAULT_DAILY_GLUCOSE_CHART_START;
    private static $DEFAULT_DAILY_GLUCOSE_CHART_END;
    private static $DEFAULT_WEEKLY_GLUCOSE_CHART_START;
    private static $DEFAULT_WEEKLY_GLUCOSE_CHART_END;
    private static $DEFAULT_MONTHLY_GLUCOSE_CHART_START;
    private static $DEFAULT_MONTHLY_GLUCOSE_CHART_END;
    private static $DEFAULT_YEARLY_GLUCOSE_CHART_START;
    private static $DEFAULT_YEARLY_GLUCOSE_CHART_END;
    private static $DEFAULT_INDIVIDUAL_SLEEP_CHART_START;
    private static $DEFAULT_INDIVIDUAL_SLEEP_CHART_END;
    private static $DEFAULT_DAILY_SLEEP_CHART_START;
    private static $DEFAULT_DAILY_SLEEP_CHART_END;
    private static $DEFAULT_WEEKLY_SLEEP_CHART_START;
    private static $DEFAULT_WEEKLY_SLEEP_CHART_END;
    private static $DEFAULT_MONTHLY_SLEEP_CHART_START;
    private static $DEFAULT_MONTHLY_SLEEP_CHART_END;
    private static $DEFAULT_YEARLY_SLEEP_CHART_START;
    private static $DEFAULT_YEARLY_SLEEP_CHART_END;
    private static $DEFAULT_INDIVIDUAL_WEIGHT_CHART_START;
    private static $DEFAULT_INDIVIDUAL_WEIGHT_CHART_END;
    private static $DEFAULT_DAILY_WEIGHT_CHART_START;
    private static $DEFAULT_DAILY_WEIGHT_CHART_END;
    private static $DEFAULT_WEEKLY_WEIGHT_CHART_START;
    private static $DEFAULT_WEEKLY_WEIGHT_CHART_END;
    private static $DEFAULT_MONTHLY_WEIGHT_CHART_START;
    private static $DEFAULT_MONTHLY_WEIGHT_CHART_END;
    private static $DEFAULT_YEARLY_WEIGHT_CHART_START;
    private static $DEFAULT_YEARLY_WEIGHT_CHART_END;
    
    private $formInput;
    private $optionsName;
    private $userName;
    private $bloodPressureUnits;
    private $calorieUnits;
    private $exerciseUnits;
    private $glucoseUnits;
    private $sleepUnits;
    private $weightUnits;
    private $timeFormat;
    private $durationFormat;
    private $showTooltips;
    private $showSecondaryCols;
    private $showDateCol;
    private $showTimeCol;
    private $showNotesCol;
    private $numRows;
    private $showTable;
    private $tableSize;
    private $chartPlacement;
    private $showFirstChart;
    private $showSecondChart;
    private $firstChartType;
    private $secondChartType;
    private $individualBloodPressureChartStart;
    private $indidivualBloodPressureChartEnd;
    private $dailyBloodPressureChartStart;
    private $dailyBloodPressureChartEnd;
    private $weeklyBloodPressureChartStart;
    private $weeklyBloodPressureChartEnd;
    private $monthlyBloodPressureChartStart;
    private $monthlyBloodPressureChartEnd;
    private $yearlyBloodPressureChartStart;
    private $yearlyBloodPressureChartEnd;
    private $individualCaloriesChartStart;
    private $indidivualCaloriesChartEnd;
    private $dailyCaloriesChartStart;
    private $dailyCaloriesChartEnd;
    private $weeklyCaloriesChartStart;
    private $weeklyCaloriesChartEnd;
    private $monthlyCaloriesChartStart;
    private $monthlyCaloriesChartEnd;
    private $yearlyCaloriesChartStart;
    private $yearlyCaloriesChartEnd;
    private $individualExerciseChartStart;
    private $indidivualExerciseChartEnd;
    private $dailyExerciseChartStart;
    private $dailyExerciseChartEnd;
    private $weeklyExerciseChartStart;
    private $weeklyExerciseChartEnd;
    private $monthlyExerciseChartStart;
    private $monthlyExerciseChartEnd;
    private $yearlyExerciseChartStart;
    private $yearlyExerciseChartEnd;
    private $individualGlucoseChartStart;
    private $indidivualGlucoseChartEnd;
    private $dailyGlucoseChartStart;
    private $dailyGlucoseChartEnd;
    private $weeklyGlucoseChartStart;
    private $weeklyGlucoseChartEnd;
    private $monthlyGlucoseChartStart;
    private $monthlyGlucoseChartEnd;
    private $yearlyGlucoseChartStart;
    private $yearlyGlucoseChartEnd;
    private $individualSleepChartStart;
    private $indidivualSleepChartEnd;
    private $dailySleepChartStart;
    private $dailySleepChartEnd;
    private $weeklySleepChartStart;
    private $weeklySleepChartEnd;
    private $monthlySleepChartStart;
    private $monthlySleepChartEnd;
    private $yearlySleepChartStart;
    private $yearlySleepChartEnd;
    private $individualWeightChartStart;
    private $indidivualWeightChartEnd;
    private $dailyWeightChartStart;
    private $dailyWeightChartEnd;
    private $weeklyWeightChartStart;
    private $weeklyWeightChartEnd;
    private $monthlyWeightChartStart;
    private $monthlyWeightChartEnd;
    private $yearlyWeightChartStart;
    private $yearlyWeightChartEnd;
    private $chartLastYear;
    private $chartGroupDays;
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
    }
    
    public function getOptionsName() {
        return $this->optionsName;
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
    
    public function getDurationFormat() {
        return $this->durationFormat;
    }
    
    public function getShowTooltips() {
        return $this->showTooltips;
    }
    
    public function getShowSecondaryCols() {
        return $this->showSecondaryCols;
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
    
    public function getShowTable() {
        return $this->showTable;
    }
    
    public function getTableSize() {
        return $this->tableSize;
    }
    
    public function getChartPlacement() {
        return $this->chartPlacement;
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
    
    public function getChartLastYear() {
        return $this->chartLastYear;
    }
    
    public function getChartGroupDays() {
        return $this->chartGroupDays;
    }
    
    public function getIndividualBloodPressureChartStart() {
        return $this->individualBloodPressureChartStart;
    }
    
    public function getIndividualBloodPressureChartEnd() {
        return $this->individualBloodPressureChartEnd;
    }
    
    public function getDailyBloodPressureChartStart() {
        return $this->dailyBloodPressureChartStart;
    }
    
    public function getDailyBloodPressureChartEnd() {
        return $this->dailyBloodPressureChartEnd;
    }
    
    public function getWeeklyBloodPressureChartStart() {
        return $this->weeklyBloodPressureChartStart;
    }
    
    public function getWeeklyBloodPressureChartEnd() {
        return $this->weeklyBloodPressureChartEnd;
    }
    
    public function getMonthlyBloodPressureChartStart() {
        return $this->monthlyBloodPressureChartStart;
    }
    
    public function getMonthlyBloodPressureChartEnd() {
        return $this->monthlyBloodPressureChartEnd;
    }
    
    public function getYearlyBloodPressureChartStart() {
        return $this->yearlyBloodPressureChartStart;
    }
    
    public function getYearlyBloodPressureChartEnd() {
        return $this->yearlyBloodPressureChartEnd;
    }
    
    public function getIndividualCaloriesChartStart() {
        return $this->individualCaloriesChartStart;
    }
    
    public function getIndividualCaloriesChartEnd() {
        return $this->individualCaloriesChartEnd;
    }
    
    public function getDailyCaloriesChartStart() {
        return $this->dailyCaloriesChartStart;
    }
    
    public function getDailyCaloriesChartEnd() {
        return $this->dailyCaloriesChartEnd;
    }
    
    public function getWeeklyCaloriesChartStart() {
        return $this->weeklyCaloriesChartStart;
    }
    
    public function getWeeklyCaloriesChartEnd() {
        return $this->weeklyCaloriesChartEnd;
    }
    
    public function getMonthlyCaloriesChartStart() {
        return $this->monthlyCaloriesChartStart;
    }
    
    public function getMonthlyCaloriesChartEnd() {
        return $this->monthlyCaloriesChartEnd;
    }
    
    public function getYearlyCaloriesChartStart() {
        return $this->yearlyCaloriesChartStart;
    }
    
    public function getYearlyCaloriesChartEnd() {
        return $this->yearlyCaloriesChartEnd;
    }
    
    public function getIndividualExerciseChartStart() {
        return $this->individualExerciseChartStart;
    }
    
    public function getIndividualExerciseChartEnd() {
        return $this->individualExerciseChartEnd;
    }
    
    public function getDailyExerciseChartStart() {
        return $this->dailyExerciseChartStart;
    }
    
    public function getDailyExerciseChartEnd() {
        return $this->dailyExerciseChartEnd;
    }
    
    public function getWeeklyExerciseChartStart() {
        return $this->weeklyExerciseChartStart;
    }
    
    public function getWeeklyExerciseChartEnd() {
        return $this->weeklyExerciseChartEnd;
    }
    
    public function getMonthlyExerciseChartStart() {
        return $this->monthlyExerciseChartStart;
    }
    
    public function getMonthlyExerciseChartEnd() {
        return $this->monthlyExerciseChartEnd;
    }
    
    public function getYearlyExerciseChartStart() {
        return $this->yearlyExerciseChartStart;
    }
    
    public function getYearlyExerciseChartEnd() {
        return $this->yearlyExerciseChartEnd;
    }
    
    public function getIndividualGlucoseChartStart() {
        return $this->individualGlucoseChartStart;
    }
    
    public function getIndividualGlucoseChartEnd() {
        return $this->individualGlucoseChartEnd;
    }
    
    public function getDailyGlucoseChartStart() {
        return $this->dailyGlucoseChartStart;
    }
    
    public function getDailyGlucoseChartEnd() {
        return $this->dailyGlucoseChartEnd;
    }
    
    public function getWeeklyGlucoseChartStart() {
        return $this->weeklyGlucoseChartStart;
    }
    
    public function getWeeklyGlucoseChartEnd() {
        return $this->weeklyGlucoseChartEnd;
    }
    
    public function getMonthlyGlucoseChartStart() {
        return $this->monthlyGlucoseChartStart;
    }
    
    public function getMonthlyGlucoseChartEnd() {
        return $this->monthlyGlucoseChartEnd;
    }
    
    public function getYearlyGlucoseChartStart() {
        return $this->yearlyGlucoseChartStart;
    }
    
    public function getYearlyGlucoseChartEnd() {
        return $this->yearlyGlucoseChartEnd;
    }
    
    public function getIndividualSleepChartStart() {
        return $this->individualSleepChartStart;
    }
    
    public function getIndividualSleepChartEnd() {
        return $this->individualSleepChartEnd;
    }
    
    public function getDailySleepChartStart() {
        return $this->dailySleepChartStart;
    }
    
    public function getDailySleepChartEnd() {
        return $this->dailySleepChartEnd;
    }
    
    public function getWeeklySleepChartStart() {
        return $this->weeklySleepChartStart;
    }
    
    public function getWeeklySleepChartEnd() {
        return $this->weeklySleepChartEnd;
    }
    
    public function getMonthlySleepChartStart() {
        return $this->monthlySleepChartStart;
    }
    
    public function getMonthlySleepChartEnd() {
        return $this->monthlySleepChartEnd;
    }
    
    public function getYearlySleepChartStart() {
        return $this->yearlySleepChartStart;
    }
    
    public function getYearlySleepChartEnd() {
        return $this->yearlySleepChartEnd;
    }
    
    public function getIndividualWeightChartStart() {
        return $this->individualWeightChartStart;
    }
    
    public function getIndividualWeightChartEnd() {
        return $this->individualWeightChartEnd;
    }
    
    public function getDailyWeightChartStart() {
        return $this->dailyWeightChartStart;
    }
    
    public function getDailyWeightChartEnd() {
        return $this->dailyWeightChartEnd;
    }
    
    public function getWeeklyWeightChartStart() {
        return $this->weeklyWeightChartStart;
    }
    
    public function getWeeklyWeightChartEnd() {
        return $this->weeklyWeightChartEnd;
    }
    
    public function getMonthlyWeightChartStart() {
        return $this->monthlyWeightChartStart;
    }
    
    public function getMonthlyWeightChartEnd() {
        return $this->monthlyWeightChartEnd;
    }
    
    public function getYearlyWeightChartStart() {
        return $this->yearlyWeightChartStart;
    }
    
    public function getYearlyWeightChartEnd() {
        return $this->yearlyWeightChartEnd;
    }
    
    // Returns data fields as an associative array
    public function getParameters() {
        $paramArray = array(
            'optionsName' => $this->optionsName,
            'userName' => $this->userName,
            'bloodPressureUnits' => $this->bloodPressureUnits,
            'calorieUnits' => $this->calorieUnits,
            'exerciseUnits' => $this->exerciseUnits,
            'glucoseUnits' => $this->glucoseUnits,
            'sleepUnits' => $this->sleepUnits,
            'weightUnits' => $this->weightUnits,
            'timeFormat' => $this->timeFormat,
            'durationFormat' => $this->durationFormat,
            'showTooltips' => $this->showTooltips,
            'showSecondaryCols' => $this->showSecondaryCols,
            'showDateCol' => $this->showDateCol,
            'showTimeCol' => $this->showTimeCol,
            'showNotesCol' => $this->showNotesCol,
            'numRows' => $this->numRows,
            'showTable' => $this->showTable,
            'tableSize' => $this->tableSize,
            'chartPlacement' => $this->chartPlacement,
            'showFirstChart' => $this->showFirstChart,
            'showSecondChart' => $this->showSecondChart,
            'firstChartType' => $this->firstChartType,
            'secondChartType' => $this->secondChartType,
            'chartLastYear' => $this->chartLastYear,
            'chartGroupDays' => $this->chartGroupDays,
            'individualBloodPressureChartStart' => $this->individualBloodPressureChartStart->format(self::DATE_FORMAT),
            'individualBloodPressureChartEnd' => $this->individualBloodPressureChartEnd->format(self::DATE_FORMAT),
            'dailyBloodPressureChartStart' => $this->dailyBloodPressureChartStart->format(self::DATE_FORMAT),
            'dailyBloodPressureChartEnd' => $this->dailyBloodPressureChartEnd->format(self::DATE_FORMAT),
            'weeklyBloodPressureChartStart' => $this->weeklyBloodPressureChartStart->format(self::DATE_FORMAT),
            'weeklyBloodPressureChartEnd' => $this->weeklyBloodPressureChartEnd->format(self::DATE_FORMAT),
            'monthlyBloodPressureChartStart' => $this->monthlyBloodPressureChartStart->format(self::DATE_FORMAT),
            'monthlyBloodPressureChartEnd' => $this->monthlyBloodPressureChartEnd->format(self::DATE_FORMAT),
            'yearlyBloodPressureChartStart' => $this->yearlyBloodPressureChartStart->format(self::DATE_FORMAT),
            'yearlyBloodPressureChartEnd' => $this->yearlyBloodPressureChartEnd->format(self::DATE_FORMAT),
            'individualCaloriesChartStart' => $this->individualCaloriesChartStart->format(self::DATE_FORMAT),
            'individualCaloriesChartEnd' => $this->individualCaloriesChartEnd->format(self::DATE_FORMAT),
            'dailyCaloriesChartStart' => $this->dailyCaloriesChartStart->format(self::DATE_FORMAT),
            'dailyCaloriesChartEnd' => $this->dailyCaloriesChartEnd->format(self::DATE_FORMAT),
            'weeklyCaloriesChartStart' => $this->weeklyCaloriesChartStart->format(self::DATE_FORMAT),
            'weeklyCaloriesChartEnd' => $this->weeklyCaloriesChartEnd->format(self::DATE_FORMAT),
            'monthlyCaloriesChartStart' => $this->monthlyCaloriesChartStart->format(self::DATE_FORMAT),
            'monthlyCaloriesChartEnd' => $this->monthlyCaloriesChartEnd->format(self::DATE_FORMAT),
            'yearlyCaloriesChartStart' => $this->yearlyCaloriesChartStart->format(self::DATE_FORMAT),
            'yearlyCaloriesChartEnd' => $this->yearlyCaloriesChartEnd->format(self::DATE_FORMAT),
            'individualExerciseChartStart' => $this->individualExerciseChartStart->format(self::DATE_FORMAT),
            'individualExerciseChartEnd' => $this->individualExerciseChartEnd->format(self::DATE_FORMAT),
            'dailyExerciseChartStart' => $this->dailyExerciseChartStart->format(self::DATE_FORMAT),
            'dailyExerciseChartEnd' => $this->dailyExerciseChartEnd->format(self::DATE_FORMAT),
            'weeklyExerciseChartStart' => $this->weeklyExerciseChartStart->format(self::DATE_FORMAT),
            'weeklyExerciseChartEnd' => $this->weeklyExerciseChartEnd->format(self::DATE_FORMAT),
            'monthlyExerciseChartStart' => $this->monthlyExerciseChartStart->format(self::DATE_FORMAT),
            'monthlyExerciseChartEnd' => $this->monthlyExerciseChartEnd->format(self::DATE_FORMAT),
            'yearlyExerciseChartStart' => $this->yearlyExerciseChartStart->format(self::DATE_FORMAT),
            'yearlyExerciseChartEnd' => $this->yearlyExerciseChartEnd->format(self::DATE_FORMAT),
            'individualGlucoseChartStart' => $this->individualGlucoseChartStart->format(self::DATE_FORMAT),
            'individualGlucoseChartEnd' => $this->individualGlucoseChartEnd->format(self::DATE_FORMAT),
            'dailyGlucoseChartStart' => $this->dailyGlucoseChartStart->format(self::DATE_FORMAT),
            'dailyGlucoseChartEnd' => $this->dailyGlucoseChartEnd->format(self::DATE_FORMAT),
            'weeklyGlucoseChartStart' => $this->weeklyGlucoseChartStart->format(self::DATE_FORMAT),
            'weeklyGlucoseChartEnd' => $this->weeklyGlucoseChartEnd->format(self::DATE_FORMAT),
            'monthlyGlucoseChartStart' => $this->monthlyGlucoseChartStart->format(self::DATE_FORMAT),
            'monthlyGlucoseChartEnd' => $this->monthlyGlucoseChartEnd->format(self::DATE_FORMAT),
            'yearlyGlucoseChartStart' => $this->yearlyGlucoseChartStart->format(self::DATE_FORMAT),
            'yearlyGlucoseChartEnd' => $this->yearlyGlucoseChartEnd->format(self::DATE_FORMAT),
            'individualSleepChartStart' => $this->individualSleepChartStart->format(self::DATE_FORMAT),
            'individualSleepChartEnd' => $this->individualSleepChartEnd->format(self::DATE_FORMAT),
            'dailySleepChartStart' => $this->dailySleepChartStart->format(self::DATE_FORMAT),
            'dailySleepChartEnd' => $this->dailySleepChartEnd->format(self::DATE_FORMAT),
            'weeklySleepChartStart' => $this->weeklySleepChartStart->format(self::DATE_FORMAT),
            'weeklySleepChartEnd' => $this->weeklySleepChartEnd->format(self::DATE_FORMAT),
            'monthlySleepChartStart' => $this->monthlySleepChartStart->format(self::DATE_FORMAT),
            'monthlySleepChartEnd' => $this->monthlySleepChartEnd->format(self::DATE_FORMAT),
            'yearlySleepChartStart' => $this->yearlySleepChartStart->format(self::DATE_FORMAT),
            'yearlySleepChartEnd' => $this->yearlySleepChartEnd->format(self::DATE_FORMAT),
            'individualWeightChartStart' => $this->individualWeightChartStart->format(self::DATE_FORMAT),
            'individualWeightChartEnd' => $this->individualWeightChartEnd->format(self::DATE_FORMAT),
            'dailyWeightChartStart' => $this->dailyWeightChartStart->format(self::DATE_FORMAT),
            'dailyWeightChartEnd' => $this->dailyWeightChartEnd->format(self::DATE_FORMAT),
            'weeklyWeightChartStart' => $this->weeklyWeightChartStart->format(self::DATE_FORMAT),
            'weeklyWeightChartEnd' => $this->weeklyWeightChartEnd->format(self::DATE_FORMAT),
            'monthlyWeightChartStart' => $this->monthlyWeightChartStart->format(self::DATE_FORMAT),
            'monthlyWeightChartEnd' => $this->monthlyWeightChartEnd->format(self::DATE_FORMAT),
            'yearlyWeightChartStart' => $this->yearlyWeightChartStart->format(self::DATE_FORMAT),
            'yearlyWeightChartEnd' => $this->yearlyWeightChartEnd->format(self::DATE_FORMAT)
        );
        
        return $paramArray;
    }
    
    public function __toString() {
        $str =
            "Options Name: [$this->optionsName]\n" .
            "User Name: [$this->userName]\n" .
            "Blood Pressure Units: [$this->bloodPressureUnits]\n" .
            "Calorie Units: [$this->calorieUnits]\n" .
            "Exercise Units: [$this->exerciseUnits]\n" .
            "Glucose Units: [$this->glucoseUnits]\n" .
            "Sleep Units: [$this->sleepUnits]\n" .
            "Weight Units: [$this->weightUnits]\n" .
            "Time Format: [$this->timeFormat]\n" .
            "Duration Format: [$this->durationFormat]\n" .
            "Show Tooltips: [" .(($this->showTooltips === true) ? "true" : "false"). "]\n" .
            "Show Secondary Columns: [" .(($this->showSecondaryCols === true) ? "true" : "false"). "]\n" .
            "Show Date Column: [" .(($this->showDateCol === true) ? "true" : "false"). "]\n" .
            "Show Time Column: [" .(($this->showTimeCol === true) ? "true" : "false"). "]\n" .
            "Show Notes Column: [" .(($this->showNotesCol === true) ? "true" : "false"). "]\n" .
            "Number of Rows per Page: [$this->numRows]\n" .
            "Show Table: [" .(($this->showTable === true) ? "true" : "false"). "]\n" .
            "Table Size: [$this->tableSize]\n" .
            "Chart Placement: [$this->chartPlacement]\n" .
            "Show First Chart: [" .(($this->showFirstChart === true) ? "true" : "false"). "]\n" .
            "Show Second Chart: [" .(($this->showSecondChart === true) ? "true" : "false"). "]\n" .
            "First Chart Type: [$this->firstChartType]\n" .
            "Second Chart Type: [ $this->secondChartType]\n" .
            "Chart Last Year: [" .(($this->chartLastYear === true) ? "true" : "false"). "]\n" .
            "Chart Group Days: [" .(($this->chartGroupDays === true) ? "true" : "false"). "]\n" .
            "Individual Blood Pressure Chart Start Date: [" .$this->individualBloodPressureChartStart->format(self::DATE_FORMAT). "]\n" .
            "Individual Blood Pressure Chart End Date: [" .$this->individualBloodPressureChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Daily Blood Pressure Chart Start Date: [" .$this->dailyBloodPressureChartStart->format(self::DATE_FORMAT). "]\n" .
            "Daily Blood Pressure Chart End Date: [" .$this->dailyBloodPressureChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Weekly Blood Pressure Chart Start Date: [" .$this->weeklyBloodPressureChartStart->format(self::DATE_FORMAT). "]\n" .
            "Weekly Blood Pressure Chart End Date: [" .$this->weeklyBloodPressureChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Monthly Blood Pressure Chart Start Date: [" .$this->monthlyBloodPressureChartStart->format(self::DATE_FORMAT). "]\n" .
            "Monthly Blood Pressure Chart End Date: [" .$this->monthlyBloodPressureChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Yearly Blood Pressure Chart Start Date: [" .$this->yearlyBloodPressureChartStart->format(self::DATE_FORMAT). "]\n" .
            "Yearly Blood Pressure Chart End Date: [" .$this->yearlyBloodPressureChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Individual Calories Chart Start Date: [" .$this->individualCaloriesChartStart->format(self::DATE_FORMAT). "]\n" .
            "Individual Calories Chart End Date: [" .$this->individualCaloriesChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Daily Calories Chart Start Date: [" .$this->dailyCaloriesChartStart->format(self::DATE_FORMAT). "]\n" .
            "Daily Calories Chart End Date: [" .$this->dailyCaloriesChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Weekly Calories Chart Start Date: [" .$this->weeklyCaloriesChartStart->format(self::DATE_FORMAT). "]\n" .
            "Weekly Calories Chart End Date: [" .$this->weeklyCaloriesChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Monthly Calories Chart Start Date: [" .$this->monthlyCaloriesChartStart->format(self::DATE_FORMAT). "]\n" .
            "Monthly Calories Chart End Date: [" .$this->monthlyCaloriesChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Yearly Calories Chart Start Date: [" .$this->yearlyCaloriesChartStart->format(self::DATE_FORMAT). "]\n" .
            "Yearly Calories Chart End Date: [" .$this->yearlyCaloriesChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Individual Exercise Chart Start Date: [" .$this->individualExerciseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Individual Exercise Chart End Date: [" .$this->individualExerciseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Daily Exercise Chart Start Date: [" .$this->dailyExerciseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Daily Exercise Chart End Date: [" .$this->dailyExerciseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Weekly Exercise Chart Start Date: [" .$this->weeklyExerciseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Weekly Exercise Chart End Date: [" .$this->weeklyExerciseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Monthly Exercise Chart Start Date: [" .$this->monthlyExerciseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Monthly Exercise Chart End Date: [" .$this->monthlyExerciseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Yearly Exercise Chart Start Date: [" .$this->yearlyExerciseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Yearly Exercise Chart End Date: [" .$this->yearlyExerciseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Individual Glucose Chart Start Date: [" .$this->individualGlucoseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Individual Glucose Chart End Date: [" .$this->individualGlucoseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Daily Glucose Chart Start Date: [" .$this->dailyGlucoseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Daily Glucose Chart End Date: [" .$this->dailyGlucoseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Weekly Glucose Chart Start Date: [" .$this->weeklyGlucoseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Weekly Glucose Chart End Date: [" .$this->weeklyGlucoseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Monthly Glucose Chart Start Date: [" .$this->monthlyGlucoseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Monthly Glucose Chart End Date: [" .$this->monthlyGlucoseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Yearly Glucose Chart Start Date: [" .$this->yearlyGlucoseChartStart->format(self::DATE_FORMAT). "]\n" .
            "Yearly Glucose Chart End Date: [" .$this->yearlyGlucoseChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Individual Sleep Chart Start Date: [" .$this->individualSleepChartStart->format(self::DATE_FORMAT). "]\n" .
            "Individual Sleep Chart End Date: [" .$this->individualSleepChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Daily Sleep Chart Start Date: [" .$this->dailySleepChartStart->format(self::DATE_FORMAT). "]\n" .
            "Daily Sleep Chart End Date: [" .$this->dailySleepChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Weekly Sleep Chart Start Date: [" .$this->weeklySleepChartStart->format(self::DATE_FORMAT). "]\n" .
            "Weekly Sleep Chart End Date: [" .$this->weeklySleepChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Monthly Sleep Chart Start Date: [" .$this->monthlySleepChartStart->format(self::DATE_FORMAT). "]\n" .
            "Monthly Sleep Chart End Date: [" .$this->monthlySleepChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Yearly Sleep Chart Start Date: [" .$this->yearlySleepChartStart->format(self::DATE_FORMAT). "]\n" .
            "Yearly Sleep Chart End Date: [" .$this->yearlySleepChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Individual Weight Chart Start Date: [" .$this->individualWeightChartStart->format(self::DATE_FORMAT). "]\n" .
            "Individual Weight Chart End Date: [" .$this->individualWeightChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Daily Weight Chart Start Date: [" .$this->dailyWeightChartStart->format(self::DATE_FORMAT). "]\n" .
            "Daily Weight Chart End Date: [" .$this->dailyWeightChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Weekly Weight Chart Start Date: [" .$this->weeklyWeightChartStart->format(self::DATE_FORMAT). "]\n" .
            "Weekly Weight Chart End Date: [" .$this->weeklyWeightChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Monthly Weight Chart Start Date: [" .$this->monthlyWeightChartStart->format(self::DATE_FORMAT). "]\n" .
            "Monthly Weight Chart End Date: [" .$this->monthlyWeightChartEnd->format(self::DATE_FORMAT). "]\n" .
            "Yearly Weight Chart Start Date: [" .$this->yearlyWeightChartStart->format(self::DATE_FORMAT). "]\n" .
            "Yearly Weight Chart End Date: [" .$this->yearlyWeightChartEnd->format(self::DATE_FORMAT). "]";
        
        return $str;
    }
    
    public function jsonSerialize() {
        $object = new stdClass();
        $object->optionsName = $this->optionsName;
        $object->userName = $this->userName;
        $object->bloodPressureUnits = $this->bloodPressureUnits;
        $object->calorieUnits = $this->calorieUnits;
        $object->exerciseUnits = $this->exerciseUnits;
        $object->glucoseUnits = $this->glucoseUnits;
        $object->sleepUnits = $this->sleepUnits;
        $object->weightUnits = $this->weightUnits;
        $object->timeFormat = $this->timeFormat;
        $object->durationFormat = $this->durationFormat;
        $object->showTooltips = $this->showTooltips;
        $object->showSecondaryCols = $this->showSecondaryCols;
        $object->showDateCol = $this->showDateCol;
        $object->showTimeCol = $this->showTimeCol;
        $object->showNotesCol = $this->showNotesCol;
        $object->numRows = $this->numRows;
        $object->showTable = $this->showTable;
        $object->tableSize = $this->tableSize;
        $object->chartPlacement = $this->chartPlacement;
        $object->showFirstChart = $this->showFirstChart;
        $object->showSecondChart = $this->showSecondChart;
        $object->firstChartType = $this->firstChartType;
        $object->secondChartType = $this->secondChartType;
        $object->chartLastYear = $this->chartLastYear;
        $object->chartGroupDays = $this->chartGroupDays;
        $object->individualBloodPressureChartStart = $this->individualBloodPressureChartStart;
        $object->individualBloodPressureChartEnd = $this->individualBloodPressureChartEnd;
        $object->dailyBloodPressureChartStart = $this->dailyBloodPressureChartStart;
        $object->dailyBloodPressureChartEnd = $this->dailyBloodPressureChartEnd;
        $object->weeklyBloodPressureChartStart = $this->weeklyBloodPressureChartStart;
        $object->weeklyBloodPressureChartEnd = $this->weeklyBloodPressureChartEnd;
        $object->monthlyBloodPressureChartStart = $this->monthlyBloodPressureChartStart;
        $object->monthlyBloodPressureChartEnd = $this->monthlyBloodPressureChartEnd;
        $object->yearlyBloodPressureChartStart = $this->yearlyBloodPressureChartStart;
        $object->yearlyBloodPressureChartEnd = $this->yearlyBloodPressureChartEnd;
        $object->individualCaloriesChartStart = $this->individualCaloriesChartStart;
        $object->individualCaloriesChartEnd = $this->individualCaloriesChartEnd;
        $object->dailyCaloriesChartStart = $this->dailyCaloriesChartStart;
        $object->dailyCaloriesChartEnd = $this->dailyCaloriesChartEnd;
        $object->weeklyCaloriesChartStart = $this->weeklyCaloriesChartStart;
        $object->weeklyCaloriesChartEnd = $this->weeklyCaloriesChartEnd;
        $object->monthlyCaloriesChartStart = $this->monthlyCaloriesChartStart;
        $object->monthlyCaloriesChartEnd = $this->monthlyCaloriesChartEnd;
        $object->yearlyCaloriesChartStart = $this->yearlyCaloriesChartStart;
        $object->yearlyCaloriesChartEnd = $this->yearlyCaloriesChartEnd;
        $object->individualExerciseChartStart = $this->individualExerciseChartStart;
        $object->individualExerciseChartEnd = $this->individualExerciseChartEnd;
        $object->dailyExerciseChartStart = $this->dailyExerciseChartStart;
        $object->dailyExerciseChartEnd = $this->dailyExerciseChartEnd;
        $object->weeklyExerciseChartStart = $this->weeklyExerciseChartStart;
        $object->weeklyExerciseChartEnd = $this->weeklyExerciseChartEnd;
        $object->monthlyExerciseChartStart = $this->monthlyExerciseChartStart;
        $object->monthlyExerciseChartEnd = $this->monthlyExerciseChartEnd;
        $object->yearlyExerciseChartStart = $this->yearlyExerciseChartStart;
        $object->yearlyExerciseChartEnd = $this->yearlyExerciseChartEnd;
        $object->individualGlucoseChartStart = $this->individualGlucoseChartStart;
        $object->individualGlucoseChartEnd = $this->individualGlucoseChartEnd;
        $object->dailyGlucoseChartStart = $this->dailyGlucoseChartStart;
        $object->dailyGlucoseChartEnd = $this->dailyGlucoseChartEnd;
        $object->weeklyGlucoseChartStart = $this->weeklyGlucoseChartStart;
        $object->weeklyGlucoseChartEnd = $this->weeklyGlucoseChartEnd;
        $object->monthlyGlucoseChartStart = $this->monthlyGlucoseChartStart;
        $object->monthlyGlucoseChartEnd = $this->monthlyGlucoseChartEnd;
        $object->yearlyGlucoseChartStart = $this->yearlyGlucoseChartStart;
        $object->yearlyGlucoseChartEnd = $this->yearlyGlucoseChartEnd;
        $object->individualSleepChartStart = $this->individualSleepChartStart;
        $object->individualSleepChartEnd = $this->individualSleepChartEnd;
        $object->dailySleepChartStart = $this->dailySleepChartStart;
        $object->dailySleepChartEnd = $this->dailySleepChartEnd;
        $object->weeklySleepChartStart = $this->weeklySleepChartStart;
        $object->weeklySleepChartEnd = $this->weeklySleepChartEnd;
        $object->monthlySleepChartStart = $this->monthlySleepChartStart;
        $object->monthlySleepChartEnd = $this->monthlySleepChartEnd;
        $object->yearlySleepChartStart = $this->yearlySleepChartStart;
        $object->yearlySleepChartEnd = $this->yearlySleepChartEnd;
        $object->individualWeightChartStart = $this->individualWeightChartStart;
        $object->individualWeightChartEnd = $this->individualWeightChartEnd;
        $object->dailyWeightChartStart = $this->dailyWeightChartStart;
        $object->dailyWeightChartEnd = $this->dailyWeightChartEnd;
        $object->weeklyWeightChartStart = $this->weeklyWeightChartStart;
        $object->weeklyWeightChartEnd = $this->weeklyWeightChartEnd;
        $object->monthlyWeightChartStart = $this->monthlyWeightChartStart;
        $object->monthlyWeightChartEnd = $this->monthlyWeightChartEnd;
        $object->yearlyWeightChartStart = $this->yearlyWeightChartStart;
        $object->yearlyWeightChartEnd = $this->yearlyWeightChartEnd;
    
        return $object;
    }
    
    protected function initialize() {
        $this->errorCount = 0;
        $this->errors = array();
        
        if (is_null($this->formInput)) {
            $this->setError('measurementsOptions', 'NULL_INPUT');
            return;
        }
        
        $now = new DateTime();
        $oneMonthAgo = (new DateTime())->sub(new DateInterval('P1M'));
        $oneYearAgo = (new DateTime())->sub(new DateInterval('P1Y'));
        $fiveYearsAgo = (new DateTime())->sub(new DateInterval('P5Y'));
        self::$DEFAULT_INDIVIDUAL_BLOODPRESSURE_CHART_START = $oneMonthAgo;
        self::$DEFAULT_INDIVIDUAL_BLOODPRESSURE_CHART_END = $now;
        self::$DEFAULT_DAILY_BLOODPRESSURE_CHART_START = $oneMonthAgo;
        self::$DEFAULT_DAILY_BLOODPRESSURE_CHART_END = $now;
        self::$DEFAULT_WEEKLY_BLOODPRESSURE_CHART_START = $oneYearAgo;
        self::$DEFAULT_WEEKLY_BLOODPRESSURE_CHART_END = $now;
        self::$DEFAULT_MONTHLY_BLOODPRESSURE_CHART_START = $oneYearAgo;
        self::$DEFAULT_MONTHLY_BLOODPRESSURE_CHART_END = $now;
        self::$DEFAULT_YEARLY_BLOODPRESSURE_CHART_START = $fiveYearsAgo;
        self::$DEFAULT_YEARLY_BLOODPRESSURE_CHART_END = $now;
        self::$DEFAULT_INDIVIDUAL_CALORIES_CHART_START = $oneMonthAgo;
        self::$DEFAULT_INDIVIDUAL_CALORIES_CHART_END = $now;
        self::$DEFAULT_DAILY_CALORIES_CHART_START = $oneMonthAgo;
        self::$DEFAULT_DAILY_CALORIES_CHART_END = $now;
        self::$DEFAULT_WEEKLY_CALORIES_CHART_START = $oneYearAgo;
        self::$DEFAULT_WEEKLY_CALORIES_CHART_END = $now;
        self::$DEFAULT_MONTHLY_CALORIES_CHART_START = $oneYearAgo;
        self::$DEFAULT_MONTHLY_CALORIES_CHART_END = $now;
        self::$DEFAULT_YEARLY_CALORIES_CHART_START = $fiveYearsAgo;
        self::$DEFAULT_YEARLY_CALORIES_CHART_END = $now;
        self::$DEFAULT_INDIVIDUAL_EXERCISE_CHART_START = $oneMonthAgo;
        self::$DEFAULT_INDIVIDUAL_EXERCISE_CHART_END = $now;
        self::$DEFAULT_DAILY_EXERCISE_CHART_START = $oneMonthAgo;
        self::$DEFAULT_DAILY_EXERCISE_CHART_END = $now;
        self::$DEFAULT_WEEKLY_EXERCISE_CHART_START = $oneYearAgo;
        self::$DEFAULT_WEEKLY_EXERCISE_CHART_END = $now;
        self::$DEFAULT_MONTHLY_EXERCISE_CHART_START = $oneYearAgo;
        self::$DEFAULT_MONTHLY_EXERCISE_CHART_END = $now;
        self::$DEFAULT_YEARLY_EXERCISE_CHART_START = $fiveYearsAgo;
        self::$DEFAULT_YEARLY_EXERCISE_CHART_END = $now;
        self::$DEFAULT_INDIVIDUAL_GLUCOSE_CHART_START = $oneMonthAgo;
        self::$DEFAULT_INDIVIDUAL_GLUCOSE_CHART_END = $now;
        self::$DEFAULT_DAILY_GLUCOSE_CHART_START = $oneMonthAgo;
        self::$DEFAULT_DAILY_GLUCOSE_CHART_END = $now;
        self::$DEFAULT_WEEKLY_GLUCOSE_CHART_START = $oneYearAgo;
        self::$DEFAULT_WEEKLY_GLUCOSE_CHART_END = $now;
        self::$DEFAULT_MONTHLY_GLUCOSE_CHART_START = $oneYearAgo;
        self::$DEFAULT_MONTHLY_GLUCOSE_CHART_END = $now;
        self::$DEFAULT_YEARLY_GLUCOSE_CHART_START = $fiveYearsAgo;
        self::$DEFAULT_YEARLY_GLUCOSE_CHART_END = $now;
        self::$DEFAULT_INDIVIDUAL_SLEEP_CHART_START = $oneMonthAgo;
        self::$DEFAULT_INDIVIDUAL_SLEEP_CHART_END = $now;
        self::$DEFAULT_DAILY_SLEEP_CHART_START = $oneMonthAgo;
        self::$DEFAULT_DAILY_SLEEP_CHART_END = $now;
        self::$DEFAULT_WEEKLY_SLEEP_CHART_START = $oneYearAgo;
        self::$DEFAULT_WEEKLY_SLEEP_CHART_END = $now;
        self::$DEFAULT_MONTHLY_SLEEP_CHART_START = $oneYearAgo;
        self::$DEFAULT_MONTHLY_SLEEP_CHART_END = $now;
        self::$DEFAULT_YEARLY_SLEEP_CHART_START = $fiveYearsAgo;
        self::$DEFAULT_YEARLY_SLEEP_CHART_END = $now;
        self::$DEFAULT_INDIVIDUAL_WEIGHT_CHART_START = $oneMonthAgo;
        self::$DEFAULT_INDIVIDUAL_WEIGHT_CHART_END = $now;
        self::$DEFAULT_DAILY_WEIGHT_CHART_START = $oneMonthAgo;
        self::$DEFAULT_DAILY_WEIGHT_CHART_END = $now;
        self::$DEFAULT_WEEKLY_WEIGHT_CHART_START = $oneYearAgo;
        self::$DEFAULT_WEEKLY_WEIGHT_CHART_END = $now;
        self::$DEFAULT_MONTHLY_WEIGHT_CHART_START = $oneYearAgo;
        self::$DEFAULT_MONTHLY_WEIGHT_CHART_END = $now;
        self::$DEFAULT_YEARLY_WEIGHT_CHART_START = $fiveYearsAgo;
        self::$DEFAULT_YEARLY_WEIGHT_CHART_END = $now;
        $this->validateOptionsName();
        $this->validateUserName();
        $this->validateBloodPressureUnits();
        $this->validateCalorieUnits();
        $this->validateExerciseUnits();
        $this->validateGlucoseUnits();
        $this->validateSleepUnits();
        $this->validateWeightUnits();
        $this->validateTimeFormat();
        $this->validateDurationFormat();
        $this->validateShowTooltips();
        $this->validateShowSecondaryCols();
        $this->validateShowDateCol();
        $this->validateShowTimeCol();
        $this->validateShowNotesCol();
        $this->validateNumRows();
        $this->validateShowTable();
        $this->validateTableSize();
        $this->validateChartPlacement();
        $this->validateShowFirstChart();
        $this->validateShowSecondChart();
        $this->validateFirstChartType();
        $this->validateSecondChartType();
        $this->validateChartsDates();
        $this->validateChartLastYear();
        $this->validateChartGroupDays();
    }
    
    private function validateOptionsName() {
        $this->optionsName = $this->extractForm($this->formInput, "optionsName");
        if (empty($this->optionsName)) {
            $this->optionsName = null;
            $this->setError("optionsName", "OPTIONS_NAME_EMPTY");
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
    
    private function validateDurationFormat() {
        $this->durationFormat = $this->extractForm($this->formInput, 'durationFormat');
        if (empty($this->durationFormat)) {
            $this->durationFormat = self::DEFAULT_DURATION_FORMAT;
            return;
        }
        
        $allowed = array('minutes', 'hours', 'hours:minutes');
        if (!in_array($this->durationFormat, $allowed)) {
            $this->setError('durationFormat', 'DURATION_FORMAT_INVALID');
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
    
    private function validateShowSecondaryCols() {
        $value = $this->extractForm($this->formInput, "showSecondaryCols");
        if (empty($value)) {
            $this->showSecondaryCols= self::DEFAULT_SHOW_EXERCISETYPE_COL;
            return;
        }
    
        $this->showSecondaryCols = ($value) ? true : false;
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
        if (!filter_var($this->numRows, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("numRows", "NUM_ROWS_INVALID");
            return;
        }
    }
    
    private function validateShowTable() {
        $value = $this->extractForm($this->formInput, "showTable");
        if (empty($value)) {
            $this->showTable = self::DEFAULT_SHOW_TABLE;
            return;
        }
        
        $this->showTable = ($value) ? true : false;
    }
    
    private function validateTableSize() {
        $this->tableSize = $this->extractForm($this->formInput, "tableSize");
        if (empty($this->tableSize)) {
            $this->tableSize = self::DEFAULT_NUM_ROWS;
            return;
        }
        
        $options = array("options" => array("regexp" => "/^[0-9]{1,5}$/"));
        if (!filter_var($this->tableSize, FILTER_VALIDATE_REGEXP, $options)) {
            $this->setError("tableSize", "TABLE_SIZE_INVALID");
            return;
        }
    }
    
    private function validateChartPlacement() {
        $allowedPlacements = array('left', 'right', 'top', 'bottom');
        
        $value = $this->extractForm($this->formInput, 'chartPlacement');
        if (empty($value)) {
            $this->chartPlacement = self::DEFAULT_CHART_PLACEMENT;
            return;
        }
        
        if (!in_array($value, $allowedPlacements)) {
            $this->setError('chartPlacement', 'CHART_PLACEMENT_INVALID');
            return;
        }
        
        $this->chartPlacement = $value;
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
    
    private function validateChartLastYear() {
        $value = $this->extractForm($this->formInput, "chartLastYear");
        if (empty($value)) {
            $this->chartLastYear = self::DEFAULT_CHART_LAST_YEAR;
            return;
        }
    
        $this->chartLastYear = ($value) ? true : false;
    }
    
    private function validateChartGroupDays() {
        $value = $this->extractForm($this->formInput, "chartGroupDays");
        if (empty($value)) {
            $this->chartGroupDays = self::DEFAULT_CHART_GROUP_DAYS;
            return;
        }
    
        $this->chartGroupDays = ($value) ? true : false;
    }
    
    private function validateChartsDates() {
        $measurementTypes = array('BloodPressure', 'Calories', 'Exercise', 'Glucose', 'Sleep', 'Weight');
        $granularities = array('individual', 'daily', 'weekly', 'monthly', 'yearly');
        $startAndEnd = array('Start', 'End');
        
        foreach ($measurementTypes as $measType) {
            foreach ($granularities as $granularity) {
                foreach ($startAndEnd as $startOrEnd) {
                 
                    $chartName = "{$granularity}{$measType}Chart{$startOrEnd}";
                    $staticDefaultName = 'DEFAULT_' .strtoupper($granularity). '_' .strtoupper($measType). '_CHART_' .strtoupper($startOrEnd);
                    
                    $dateString = $this->extractForm($this->formInput, $chartName);
                    if (empty($dateString)) {
                        $staticDefault = new ReflectionProperty('MeasurementsOptions', $staticDefaultName);
                        $this->$chartName =  $staticDefault->getValue();
                        return;
                    }
                    
                    try { $dt = new DateTime($dateString); }
                    catch (Exception $e) {
                        $this->setError($chartName, "DATE_INVALID");
                        return;
                    }
                    
                    $this->$chartName = $dt;
                }
            }
        }
    }
    
}
?>