<?php

class MeasurementsOptionsDB {
    
    /* Adds the specified MeasurementsOptions object to the appropriate user in the database.
     * Returns a stdObject containing the assigned optionsID on success,
     * or throws an exception on failure. */
    public static function addOptions($options) {
        $resultData = new stdObject();
        $resultData->optionsID = -1;
    
        if (!($options instanceof MeasurementsOptions))
            throw new InvalidArgumentException('Expected MeasurementsOptions. Got ' . get_class($options));
        
        $userID = self::findUserID($options->getUserName());
            
        // create and run database query
        $stmt = Database::getDB()->prepare(
            "insert into MeasurementsOptions (userID, optionsName, bloodPressureUnits,
                calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                timeFormat, showTooltips, showSecondaryCols, showDateCol, showTimeCol,
                showNotesCol, numRows, showTable, tableSize, chartPlacement, showFirstChart,
                showSecondChart, firstChartType, secondChartType, chartLastYear, chartGroupDays,
                individualBloodPressureChartStart, individualBloodPressureChartEnd,
                dailyBloodPressureChartStart, dailyBloodPressureChartEnd,
                weeklyBloodPressureChartStart, weeklyBloodPressureChartEnd,
                monthlyBloodPressureChartStart, monthlyBloodPressureChartEnd,
                yearlyBloodPressureChartStart, yearlyBloodPressureChartEnd,
                individualCaloriesChartStart, individualCaloriesChartEnd,
                dailyCaloriesChartStart, dailyCaloriesChartEnd,
                weeklyCaloriesChartStart, weeklyCaloriesChartEnd,
                monthlyCaloriesChartStart, monthlyCaloriesChartEnd,
                yearlyCaloriesChartStart, yearlyCaloriesChartEnd,
                individualExerciseChartStart, individualExerciseChartEnd,
                dailyExerciseChartStart, dailyExerciseChartEnd,
                weeklyExerciseChartStart, weeklyExerciseChartEnd,
                monthlyExerciseChartStart, monthlyExerciseChartEnd,
                yearlyExerciseChartStart, yearlyExerciseChartEnd,
                individualGlucoseChartStart, individualGlucoseChartEnd,
                dailyGlucoseChartStart, dailyGlucoseChartEnd,
                weeklyGlucoseChartStart, weeklyGlucoseChartEnd,
                monthlyGlucoseChartStart, monthlyGlucoseChartEnd,
                yearlyGlucoseChartStart, yearlyGlucoseChartEnd,
                individualSleepChartStart, individualSleepChartEnd,
                dailySleepChartStart, dailySleepChartEnd,
                weeklySleepChartStart, weeklySleepChartEnd,
                monthlySleepChartStart, monthlySleepChartEnd,
                yearlySleepChartStart, yearlySleepChartEnd,
                individualWeightChartStart, individualWeightChartEnd,
                dailyWeightChartStart, dailyWeightChartEnd,
                weeklyWeightChartStart, weeklyWeightChartEnd,
                monthlyWeightChartStart, monthlyWeightChartEnd,
                yearlyWeightChartStart, yearlyWeightChartEnd)
            values (:userID, :optionsName, :bloodPressureUnits,
                :calorieUnits, :exerciseUnits, :glucoseUnits, :sleepUnits, :weightUnits,
                :timeFormat, :showTooltips, :showSecondaryCols, :showDateCol, :showTimeCol,
                :showNotesCol, :numRows, :showTable, :tableSize, :chartPlacement,
                :showFirstChart, :showSecondChart, :firstChartType, :secondChartType,
                :chartLastYear, :chartGroupDays,
                :individualBloodPressureChartStart, :individualBloodPressureChartEnd,
                :dailyBloodPressureChartStart, :dailyBloodPressureChartEnd,
                :weeklyBloodPressureChartStart, :weeklyBloodPressureChartEnd,
                :monthlyBloodPressureChartStart, :monthlyBloodPressureChartEnd,
                :yearlyBloodPressureChartStart, :yearlyBloodPressureChartEnd,
                :individualCaloriesChartStart, :individualCaloriesChartEnd,
                :dailyCaloriesChartStart, :dailyCaloriesChartEnd,
                :weeklyCaloriesChartStart, :weeklyCaloriesChartEnd,
                :monthlyCaloriesChartStart, :monthlyCaloriesChartEnd,
                :yearlyCaloriesChartStart, :yearlyCaloriesChartEnd,
                :individualExerciseChartStart, :individualExerciseChartEnd,
                :dailyExerciseChartStart, :dailyExerciseChartEnd,
                :weeklyExerciseChartStart, :weeklyExerciseChartEnd,
                :monthlyExerciseChartStart, :monthlyExerciseChartEnd,
                :yearlyExerciseChartStart, :yearlyExerciseChartEnd,
                :individualGlucoseChartStart, :individualGlucoseChartEnd,
                :dailyGlucoseChartStart, :dailyGlucoseChartEnd,
                :weeklyGlucoseChartStart, :weeklyGlucoseChartEnd,
                :monthlyGlucoseChartStart, :monthlyGlucoseChartEnd,
                :yearlyGlucoseChartStart, :yearlyGlucoseChartEnd,
                :individualSleepChartStart, :individualSleepChartEnd,
                :dailySleepChartStart, :dailySleepChartEnd,
                :weeklySleepChartStart, :weeklySleepChartEnd,
                :monthlySleepChartStart, :monthlySleepChartEnd,
                :yearlySleepChartStart, :yearlySleepChartEnd,
                :individualWeightChartStart, :individualWeightChartEnd,
                :dailyWeightChartStart, :dailyWeightChartEnd,
                :weeklyWeightChartStart, :weeklyWeightChartEnd,
                :monthlyWeightChartStart, :monthlyWeightChartEnd,
                :yearlyWeightChartStart, :yearlyWeightChartEnd)"
        );
        $stmt->execute(array(
            ':userID' => $userID,
            ':optionsName' => $options->getOptionsName(),
            ':bloodPressureUnits' => $options->getBloodPressureUnits(),
            ':calorieUnits' => $options->getCalorieUnits(),
            ':exerciseUnits' => $options->getExerciseUnits(),
            ':glucoseUnits' => $options->getGlucoseUnits(),
            ':sleepUnits' => $options->getSleepUnits(),
            ':weightUnits' => $options->getWeightUnits(),
            ':timeFormat' => $options->getTimeFormat(),
            ':showTooltips' => $options->getShowTooltips(),
            ':showSecondaryCols' => $options->getShowSecondaryCols(),
            ':showDateCol' => $options->getShowDateCol(),
            ':showTimeCol' => $options->getShowTimeCol(),
            ':showNotesCol' => $options->getShowNotesCol(),
            ':numRows' => $options->getNumRows(),
            ':showTable' => $options->getShowTable(),
            ':tableSize' => $options->getTableSize(),
            ':chartPlacement' => $options->getChartPlacement(),
            ':showFirstChart' => $options->getShowFirstChart(),
            ':showSecondChart' => $options->getShowSecondChart(),
            ':firstChartType' => $options->getFirstChartType(),
            ':secondChartType' => $options->getSecondChartType(),
            ':chartLastYear' => $options->getChartLastYear(),
            ':chartGroupDays' => $options->getChartGroupDays(),
            ':individualBloodPressureChartStart' => $options->getIndividualBloodPressureChartStart(),
            ':individualBloodPressureChartEnd' => $options->getIndividualBloodPressureChartEnd(),
            ':dailyBloodPressureChartStart' => $options->getDailyBloodPressureChartStart(),
            ':dailyBloodPressureChartEnd' => $options->getDailyBloodPressureChartEnd(),
            ':weeklyBloodPressureChartStart' => $options->getWeeklyBloodPressureChartStart(),
            ':weeklyBloodPressureChartEnd' => $options->getWeeklyBloodPressureChartEnd(),
            ':monthlyBloodPressureChartStart' => $options->getMonthlyBloodPressureChartStart(),
            ':monthlyBloodPressureChartEnd' => $options->getMonthlyBloodPressureChartEnd(),
            ':yearlyBloodPressureChartStart' => $options->getYearlyBloodPressureChartStart(),
            ':yearlyBloodPressureChartEnd' => $options->getYearlyBloodPressureChartEnd(),
            ':individualCaloriesChartStart' => $options->getIndividualCaloriesChartStart(),
            ':individualCaloriesChartEnd' => $options->getIndividualCaloriesChartEnd(),
            ':dailyCaloriesChartStart' => $options->getDailyCaloriesChartStart(),
            ':dailyCaloriesChartEnd' => $options->getDailyCaloriesChartEnd(),
            ':weeklyCaloriesChartStart' => $options->getWeeklyCaloriesChartStart(),
            ':weeklyCaloriesChartEnd' => $options->getWeeklyCaloriesChartEnd(),
            ':monthlyCaloriesChartStart' => $options->getMonthlyCaloriesChartStart(),
            ':monthlyCaloriesChartEnd' => $options->getMonthlyCaloriesChartEnd(),
            ':yearlyCaloriesChartStart' => $options->getYearlyCaloriesChartStart(),
            ':yearlyCaloriesChartEnd' => $options->getYearlyCaloriesChartEnd(),
            ':individualExerciseChartStart' => $options->getIndividualExerciseChartStart(),
            ':individualExerciseChartEnd' => $options->getIndividualExerciseChartEnd(),
            ':dailyExerciseChartStart' => $options->getDailyExerciseChartStart(),
            ':dailyExerciseChartEnd' => $options->getDailyExerciseChartEnd(),
            ':weeklyExerciseChartStart' => $options->getWeeklyExerciseChartStart(),
            ':weeklyExerciseChartEnd' => $options->getWeeklyExerciseChartEnd(),
            ':monthlyExerciseChartStart' => $options->getMonthlyExerciseChartStart(),
            ':monthlyExerciseChartEnd' => $options->getMonthlyExerciseChartEnd(),
            ':yearlyExerciseChartStart' => $options->getYearlyExerciseChartStart(),
            ':yearlyExerciseChartEnd' => $options->getYearlyExerciseChartEnd(),
            ':individualGlucoseChartStart' => $options->getIndividualGlucoseChartStart(),
            ':individualGlucoseChartEnd' => $options->getIndividualGlucoseChartEnd(),
            ':dailyGlucoseChartStart' => $options->getDailyGlucoseChartStart(),
            ':dailyGlucoseChartEnd' => $options->getDailyGlucoseChartEnd(),
            ':weeklyGlucoseChartStart' => $options->getWeeklyGlucoseChartStart(),
            ':weeklyGlucoseChartEnd' => $options->getWeeklyGlucoseChartEnd(),
            ':monthlyGlucoseChartStart' => $options->getMonthlyGlucoseChartStart(),
            ':monthlyGlucoseChartEnd' => $options->getMonthlyGlucoseChartEnd(),
            ':yearlyGlucoseChartStart' => $options->getYearlyGlucoseChartStart(),
            ':yearlyGlucoseChartEnd' => $options->getYearlyGlucoseChartEnd(),
            ':individualSleepChartStart' => $options->getIndividualSleepChartStart(),
            ':individualSleepChartEnd' => $options->getIndividualSleepChartEnd(),
            ':dailySleepChartStart' => $options->getDailySleepChartStart(),
            ':dailySleepChartEnd' => $options->getDailySleepChartEnd(),
            ':weeklySleepChartStart' => $options->getWeeklySleepChartStart(),
            ':weeklySleepChartEnd' => $options->getWeeklySleepChartEnd(),
            ':monthlySleepChartStart' => $options->getMonthlySleepChartStart(),
            ':monthlySleepChartEnd' => $options->getMonthlySleepChartEnd(),
            ':yearlySleepChartStart' => $options->getYearlySleepChartStart(),
            ':yearlySleepChartEnd' => $options->getYearlySleepChartEnd(),
            ':individualWeightChartStart' => $options->getIndividualWeightChartStart(),
            ':individualWeightChartEnd' => $options->getIndividualWeightChartEnd(),
            ':dailyWeightChartStart' => $options->getDailyWeightChartStart(),
            ':dailyWeightChartEnd' => $options->getDailyWeightChartEnd(),
            ':weeklyWeightChartStart' => $options->getWeeklyWeightChartStart(),
            ':weeklyWeightChartEnd' => $options->getWeeklyWeightChartEnd(),
            ':monthlyWeightChartStart' => $options->getMonthlyWeightChartStart(),
            ':monthlyWeightChartEnd' => $options->getMonthlyWeightChartEnd(),
            ':yearlyWeightChartStart' => $options->getYearlyWeightChartStart(),
            ':yearlyWeightChartEnd' => $options->getYearlyWeightChartEnd()
        ));
        $resultData->optionsID = $db->lastInsertId('optionsID');
    
        return $resultData;
    }
    
    /* Edits the old options with the information in the new options.
     * Returns a stdObject containing the number of rows affected on success,
     * or throws an exception on failure. */
    public static function editOptions($oldOptions, $newOptions) {
        $returnData = new stdObject();
        $returnData->rowsAffected = 0;
        
        if (!($oldOptions instanceof MeasurementsOptions))
            throw new InvalidArgumentException('Expected MeasurementsOptions for old options. Got ' . get_class($oldOptions));
        if (!($newOptions instanceof MeasurementsOptions))
            throw new InvalidArgumentException('Expected MeasurementsOptions for new options. Got ' . get_class($newOptions));
        
        $userID = self::findUserID($oldOptions->getUserName());
        
        // create and run database query
        $stmt = Database::getDB()->prepare(
            "update MeasurementsOptions
            set optionsName = :newOptionsName,
                bloodPressureUnits = :bloodPressureUnits,
                calorieUnits = :calorieUnits,
                exerciseUnits = :exerciseUnits,
                glucoseUnits = :glucoseUnits,
                sleepUnits = :sleepUnits,
                weightUnits = :weightUnits,
                timeFormat = :timeFormat,
                showTooltips = :showTooltips,
                showSecondaryCols = :showSecondaryCols,
                showDateCol = :showDateCol,
                showTimeCol = :showTimeCol,
                showNotesCol = :showNotesCol,
                numRows = :numRows,
                showTable = :showTable,
                tableSize = :tableSize,
                chartPlacement = :chartPlacement,
                showFirstChart = :showFirstChart,
                showSecondChart = :showSecondChart,
                firstChartType = :firstChartType,
                secondChartType = :secondChartType,
                chartLastYear = :chartLastYear,
                chartGroupDays = :chartGroupDays,
                individualBloodPressureChartStart = :individualBloodPressureChartStart,
                individualBloodPressureChartEnd = :individualBloodPressureChartEnd,
                dailyBloodPressureChartStart = :dailyBloodPressureChartStart,
                dailyBloodPressureChartEnd = :dailyBloodPressureChartEnd,
                weeklyBloodPressureChartStart = :weeklyBloodPressureChartStart,
                weeklyBloodPressureChartEnd = :weeklyBloodPressureChartEnd,
                monthlyBloodPressureChartStart = :monthlyBloodPressureChartStart,
                monthlyBloodPressureChartEnd = :monthlyBloodPressureChartEnd,
                yearlyBloodPressureChartStart = :yearlyBloodPressureChartStart,
                yearlyBloodPressureChartEnd = :yearlyBloodPressureChartEnd,
                individualCaloriesChartStart = :individualCaloriesChartStart,
                individualCaloriesChartEnd = :individualCaloriesChartEnd,
                dailyCaloriesChartStart = :dailyCaloriesChartStart,
                dailyCaloriesChartEnd = :dailyCaloriesChartEnd,
                weeklyCaloriesChartStart = :weeklyCaloriesChartStart,
                weeklyCaloriesChartEnd = :weeklyCaloriesChartEnd,
                monthlyCaloriesChartStart = :monthlyCaloriesChartStart,
                monthlyCaloriesChartEnd = :monthlyCaloriesChartEnd,
                yearlyCaloriesChartStart = :yearlyCaloriesChartStart,
                yearlyCaloriesChartEnd = :yearlyCaloriesChartEnd,
                individualExerciseChartStart = :individualExerciseChartStart,
                individualExerciseChartEnd = :individualExerciseChartEnd,
                dailyExerciseChartStart = :dailyExerciseChartStart,
                dailyExerciseChartEnd = :dailyExerciseChartEnd,
                weeklyExerciseChartStart = :weeklyExerciseChartStart,
                weeklyExerciseChartEnd = :weeklyExerciseChartEnd,
                monthlyExerciseChartStart = :monthlyExerciseChartStart,
                monthlyExerciseChartEnd = :monthlyExerciseChartEnd,
                yearlyExerciseChartStart = :yearlyExerciseChartStart,
                yearlyExerciseChartEnd = :yearlyExerciseChartEnd,
                individualGlucoseChartStart = :individualGlucoseChartStart,
                individualGlucoseChartEnd = :individualGlucoseChartEnd,
                dailyGlucoseChartStart = :dailyGlucoseChartStart,
                dailyGlucoseChartEnd = :dailyGlucoseChartEnd,
                weeklyGlucoseChartStart = :weeklyGlucoseChartStart,
                weeklyGlucoseChartEnd = :weeklyGlucoseChartEnd,
                monthlyGlucoseChartStart = :monthlyGlucoseChartStart,
                monthlyGlucoseChartEnd = :monthlyGlucoseChartEnd,
                yearlyGlucoseChartStart = :yearlyGlucoseChartStart,
                yearlyGlucoseChartEnd = :yearlyGlucoseChartEnd,
                individualSleepChartStart = :individualSleepChartStart,
                individualSleepChartEnd = :individualSleepChartEnd,
                dailySleepChartStart = :dailySleepChartStart,
                dailySleepChartEnd = :dailySleepChartEnd,
                weeklySleepChartStart = :weeklySleepChartStart,
                weeklySleepChartEnd = :weeklySleepChartEnd,
                monthlySleepChartStart = :monthlySleepChartStart,
                monthlySleepChartEnd = :monthlySleepChartEnd,
                yearlySleepChartStart = :yearlySleepChartStart,
                yearlySleepChartEnd = :yearlySleepChartEnd,
                individualWeightChartStart = :individualWeightChartStart,
                individualWeightChartEnd = :individualWeightChartEnd,
                dailyWeightChartStart = :dailyWeightChartStart,
                dailyWeightChartEnd = :dailyWeightChartEnd,
                weeklyWeightChartStart = :weeklyWeightChartStart,
                weeklyWeightChartEnd = :weeklyWeightChartEnd,
                monthlyWeightChartStart = :monthlyWeightChartStart,
                monthlyWeightChartEnd = :monthlyWeightChartEnd,
                yearlyWeightChartStart = :yearlyWeightChartStart,
                yearlyWeightChartEnd = :yearlyWeightChartEnd
            where userID = :userID
                and optionsName = :oldOptionsName"
        );
        $stmt->execute(array(
            ':newOptionsName' => $newOptions->getOptionsName(),
            ':bloodPressureUnits' => $newOptions->getBloodPressureUnits(),
            ':calorieUnits' => $newOptions->getCalorieUnits(),
            ':exerciseUnits' => $newOptions->getExerciseUnits(),
            ':glucoseUnits' => $newOptions->getGlucoseUnits(),
            ':sleepUnits' => $newOptions->getSleepUnits(),
            ':weightUnits' => $newOptions->getWeightUnits(),
            ':timeFormat' => $newOptions->getTimeFormat(),
            ':showTooltips' => $newOptions->getShowTooltips(),
            ':showSecondaryCols' => $newOptions->getShowSecondaryCols(),
            ':showDateCol' => $newOptions->getShowDateCol(),
            ':showTimeCol' => $newOptions->getShowTimeCol(),
            ':showNotesCol' => $newOptions->getShowNotesCol(),
            ':numRows' => $newOptions->getNumRows(),
            ':showTable' => $newOptions->getShowTable(),
            ':tableSize' => $newOptions->getTableSize(),
            ':chartPlacement' => $newOptions->getChartPlacement(),
            ':showFirstChart' => $newOptions->getShowFirstChart(),
            ':showSecondChart' => $newOptions->getShowSecondChart(),
            ':firstChartType' => $newOptions->getFirstChartType(),
            ':secondChartType' => $newOptions->getSecondChartType(),
            ':chartLastYear' => $newOptions->getChartLastYear(),
            ':chartGroupDays' => $newOptions->getChartGroupDays(),
            ':individualBloodPressureChartStart' => $newOptions->getIndividualBloodPressureChartStart(),
            ':individualBloodPressureChartEnd' => $newOptions->getIndividualBloodPressureChartEnd(),
            ':dailyBloodPressureChartStart' => $newOptions->getDailyBloodPressureChartStart(),
            ':dailyBloodPressureChartEnd' => $newOptions->getDailyBloodPressureChartEnd(),
            ':weeklyBloodPressureChartStart' => $newOptions->getWeeklyBloodPressureChartStart(),
            ':weeklyBloodPressureChartEnd' => $newOptions->getWeeklyBloodPressureChartEnd(),
            ':monthlyBloodPressureChartStart' => $newOptions->getMonthlyBloodPressureChartStart(),
            ':monthlyBloodPressureChartEnd' => $newOptions->getMonthlyBloodPressureChartEnd(),
            ':yearlyBloodPressureChartStart' => $newOptions->getYearlyBloodPressureChartStart(),
            ':yearlyBloodPressureChartEnd' => $newOptions->getYearlyBloodPressureChartEnd(),
            ':individualCaloriesChartStart' => $newOptions->getIndividualCaloriesChartStart(),
            ':individualCaloriesChartEnd' => $newOptions->getIndividualCaloriesChartEnd(),
            ':dailyCaloriesChartStart' => $newOptions->getDailyCaloriesChartStart(),
            ':dailyCaloriesChartEnd' => $newOptions->getDailyCaloriesChartEnd(),
            ':weeklyCaloriesChartStart' => $newOptions->getWeeklyCaloriesChartStart(),
            ':weeklyCaloriesChartEnd' => $newOptions->getWeeklyCaloriesChartEnd(),
            ':monthlyCaloriesChartStart' => $newOptions->getMonthlyCaloriesChartStart(),
            ':monthlyCaloriesChartEnd' => $newOptions->getMonthlyCaloriesChartEnd(),
            ':yearlyCaloriesChartStart' => $newOptions->getYearlyCaloriesChartStart(),
            ':yearlyCaloriesChartEnd' => $newOptions->getYearlyCaloriesChartEnd(),
            ':individualExerciseChartStart' => $newOptions->getIndividualExerciseChartStart(),
            ':individualExerciseChartEnd' => $newOptions->getIndividualExerciseChartEnd(),
            ':dailyExerciseChartStart' => $newOptions->getDailyExerciseChartStart(),
            ':dailyExerciseChartEnd' => $newOptions->getDailyExerciseChartEnd(),
            ':weeklyExerciseChartStart' => $newOptions->getWeeklyExerciseChartStart(),
            ':weeklyExerciseChartEnd' => $newOptions->getWeeklyExerciseChartEnd(),
            ':monthlyExerciseChartStart' => $newOptions->getMonthlyExerciseChartStart(),
            ':monthlyExerciseChartEnd' => $newOptions->getMonthlyExerciseChartEnd(),
            ':yearlyExerciseChartStart' => $newOptions->getYearlyExerciseChartStart(),
            ':yearlyExerciseChartEnd' => $newOptions->getYearlyExerciseChartEnd(),
            ':individualGlucoseChartStart' => $newOptions->getIndividualGlucoseChartStart(),
            ':individualGlucoseChartEnd' => $newOptions->getIndividualGlucoseChartEnd(),
            ':dailyGlucoseChartStart' => $newOptions->getDailyGlucoseChartStart(),
            ':dailyGlucoseChartEnd' => $newOptions->getDailyGlucoseChartEnd(),
            ':weeklyGlucoseChartStart' => $newOptions->getWeeklyGlucoseChartStart(),
            ':weeklyGlucoseChartEnd' => $newOptions->getWeeklyGlucoseChartEnd(),
            ':monthlyGlucoseChartStart' => $newOptions->getMonthlyGlucoseChartStart(),
            ':monthlyGlucoseChartEnd' => $newOptions->getMonthlyGlucoseChartEnd(),
            ':yearlyGlucoseChartStart' => $newOptions->getYearlyGlucoseChartStart(),
            ':yearlyGlucoseChartEnd' => $newOptions->getYearlyGlucoseChartEnd(),
            ':individualSleepChartStart' => $newOptions->getIndividualSleepChartStart(),
            ':individualSleepChartEnd' => $newOptions->getIndividualSleepChartEnd(),
            ':dailySleepChartStart' => $newOptions->getDailySleepChartStart(),
            ':dailySleepChartEnd' => $newOptions->getDailySleepChartEnd(),
            ':weeklySleepChartStart' => $newOptions->getWeeklySleepChartStart(),
            ':weeklySleepChartEnd' => $newOptions->getWeeklySleepChartEnd(),
            ':monthlySleepChartStart' => $newOptions->getMonthlySleepChartStart(),
            ':monthlySleepChartEnd' => $newOptions->getMonthlySleepChartEnd(),
            ':yearlySleepChartStart' => $newOptions->getYearlySleepChartStart(),
            ':yearlySleepChartEnd' => $newOptions->getYearlySleepChartEnd(),
            ':individualWeightChartStart' => $newOptions->getIndividualWeightChartStart(),
            ':individualWeightChartEnd' => $newOptions->getIndividualWeightChartEnd(),
            ':dailyWeightChartStart' => $newOptions->getDailyWeightChartStart(),
            ':dailyWeightChartEnd' => $newOptions->getDailyWeightChartEnd(),
            ':weeklyWeightChartStart' => $newOptions->getWeeklyWeightChartStart(),
            ':weeklyWeightChartEnd' => $newOptions->getWeeklyWeightChartEnd(),
            ':monthlyWeightChartStart' => $newOptions->getMonthlyWeightChartStart(),
            ':monthlyWeightChartEnd' => $newOptions->getMonthlyWeightChartEnd(),
            ':yearlyWeightChartStart' => $newOptions->getYearlyWeightChartStart(),
            ':yearlyWeightChartEnd' => $newOptions->getYearlyWeightChartEnd(),
            ':userID' => $userID,
            ':oldOptionsName' => $oldOptions->getOptionsName()
        ));
        $returnData->rowsAffected = $stmt->rowCount();
        
        return $returnData;
    }
    
    /* Deletes the measurements options with the specified name for the specified user.
     * Returns the number of rows affected on a successful query. If nothing was deleted,
     * the number of rows affected will be 0. Otherwise, it should be 1.
     * If the specified user is not found, or if the query itself fails, an exception is thrown. */
    public static function deleteOptions($userName, $optionsName) {
        $returnData = new stdObject();
        $returnData->rowsAffected = 0;
        
        $userID = self::findUserID($userName);

        // create and run database query
        $stmt = Database::getDB()->prepare(
            "delete from MeasurementsOptions
            where userID = :userID
                and optionsName = :optionsName"
        );
        $stmt->execute(array(
            ":userID" => $userID,
            ":optionsName" => $optionsName
        ));
        $returnData->rowsAffected = $stmt->rowCount();

        return $returnData;
    }
    
    /* On success, returns an array of MeasurementsOptions objects for the user
     * with the specified $userName. Throws an exception on failure. */
    public static function getOptionsFor($userName) {
        $allOptions = array();
    
        // create and run database query
        $stmt = Database::getDB()->prepare(
            "select userName, optionsName, bloodPressureUnits,
                calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                timeFormat, showTooltips, showSecondaryCols, showDateCol, showTimeCol,
                showNotesCol, numRows, showTable, tableSize, chartPlacement, showFirstChart,
                showSecondChart, firstChartType, secondChartType, chartLastYear, chartGroupDays,
                individualBloodPressureChartStart, individualBloodPressureChartEnd,
                dailyBloodPressureChartStart, dailyBloodPressureChartEnd,
                weeklyBloodPressureChartStart, weeklyBloodPressureChartEnd,
                monthlyBloodPressureChartStart, monthlyBloodPressureChartEnd,
                yearlyBloodPressureChartStart, yearlyBloodPressureChartEnd,
                individualCaloriesChartStart, individualCaloriesChartEnd,
                dailyCaloriesChartStart, dailyCaloriesChartEnd,
                weeklyCaloriesChartStart, weeklyCaloriesChartEnd,
                monthlyCaloriesChartStart, monthlyCaloriesChartEnd,
                yearlyCaloriesChartStart, yearlyCaloriesChartEnd,
                individualExerciseChartStart, individualExerciseChartEnd,
                dailyExerciseChartStart, dailyExerciseChartEnd,
                weeklyExerciseChartStart, weeklyExerciseChartEnd,
                monthlyExerciseChartStart, monthlyExerciseChartEnd,
                yearlyExerciseChartStart, yearlyExerciseChartEnd,
                individualGlucoseChartStart, individualGlucoseChartEnd,
                dailyGlucoseChartStart, dailyGlucoseChartEnd,
                weeklyGlucoseChartStart, weeklyGlucoseChartEnd,
                monthlyGlucoseChartStart, monthlyGlucoseChartEnd,
                yearlyGlucoseChartStart, yearlyGlucoseChartEnd,
                individualSleepChartStart, individualSleepChartEnd,
                dailySleepChartStart, dailySleepChartEnd,
                weeklySleepChartStart, weeklySleepChartEnd,
                monthlySleepChartStart, monthlySleepChartEnd,
                yearlySleepChartStart, yearlySleepChartEnd,
                individualWeightChartStart, individualWeightChartEnd,
                dailyWeightChartStart, dailyWeightChartEnd,
                weeklyWeightChartStart, weeklyWeightChartEnd,
                monthlyWeightChartStart, monthlyWeightChartEnd,
                yearlyWeightChartStart, yearlyWeightChartEnd
            from Users join MeasurementsOptions using (userID)
            where userName = :userName"
        );
        $stmt->execute(array(":userName" => $userName));

        // collect returned options data in an array of MeasurementsOptions objects
        foreach ($stmt as $row) {
            $options = new MeasurementsOptions($row);
            if (!is_object($options) || $options->getErrorCount() > 0)
                throw new RuntimeException("Failed to create valid measurements options");

            $allOptions[] = $options;
        }
        
        return $allOptions;
    }
    
    /* On success, returns a MeasurementsOptions object for the options with the specified name of the specified user.
     * Throws a NotFoundException on failure, returns an error associative array. */
    public static function getOptions($userName, $optionsName) {
        // create and run database query
        $stmt = Database::getDB()->prepare(
            "select userName, optionsName, bloodPressureUnits,
                calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                timeFormat, showTooltips, showSecondaryCols, showDateCol, showTimeCol,
                showNotesCol, numRows, showTable, tableSize, chartPlacement, showFirstChart,
                showSecondChart, firstChartType, secondChartType, chartLastYear, chartGroupDays,
                individualBloodPressureChartStart, individualBloodPressureChartEnd,
                dailyBloodPressureChartStart, dailyBloodPressureChartEnd,
                weeklyBloodPressureChartStart, weeklyBloodPressureChartEnd,
                monthlyBloodPressureChartStart, monthlyBloodPressureChartEnd,
                yearlyBloodPressureChartStart, yearlyBloodPressureChartEnd,
                individualCaloriesChartStart, individualCaloriesChartEnd,
                dailyCaloriesChartStart, dailyCaloriesChartEnd,
                weeklyCaloriesChartStart, weeklyCaloriesChartEnd,
                monthlyCaloriesChartStart, monthlyCaloriesChartEnd,
                yearlyCaloriesChartStart, yearlyCaloriesChartEnd,
                individualExerciseChartStart, individualExerciseChartEnd,
                dailyExerciseChartStart, dailyExerciseChartEnd,
                weeklyExerciseChartStart, weeklyExerciseChartEnd,
                monthlyExerciseChartStart, monthlyExerciseChartEnd,
                yearlyExerciseChartStart, yearlyExerciseChartEnd,
                individualGlucoseChartStart, individualGlucoseChartEnd,
                dailyGlucoseChartStart, dailyGlucoseChartEnd,
                weeklyGlucoseChartStart, weeklyGlucoseChartEnd,
                monthlyGlucoseChartStart, monthlyGlucoseChartEnd,
                yearlyGlucoseChartStart, yearlyGlucoseChartEnd,
                individualSleepChartStart, individualSleepChartEnd,
                dailySleepChartStart, dailySleepChartEnd,
                weeklySleepChartStart, weeklySleepChartEnd,
                monthlySleepChartStart, monthlySleepChartEnd,
                yearlySleepChartStart, yearlySleepChartEnd,
                individualWeightChartStart, individualWeightChartEnd,
                dailyWeightChartStart, dailyWeightChartEnd,
                weeklyWeightChartStart, weeklyWeightChartEnd,
                monthlyWeightChartStart, monthlyWeightChartEnd,
                yearlyWeightChartStart, yearlyWeightChartEnd
            from Users join MeasurementsOptions using (userID)
            where userName = :userName
                and optionsName = :optionsName"
        );
        $stmt->execute(array(
            ":userName" => $userName,
            ":optionsName" => $optionsName
        ));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false)
            throw new NotFoundException('Measurements options not found');

        $options = new MeasurementsOptions($row);
        if (!is_object($options) || $options->getErrorCount() > 0)
            throw new RuntimeException("Failed to create valid measurements options");

        return $options;
    }
    
    /* Finds userID from given $userName, or throws an exception if the userID is not found. */
    private static function findUserID($userName) {
        $stmt = Database::getDB()->prepare('select userID from Users where userName = :userName');
        $stmt->execute(array(":userName" => $userName));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false)
            throw new UserNotFoundException('User name "' . htmlspecialchars($userName). '" not found');
        
        return $row['userID'];
    }
    
}

?>