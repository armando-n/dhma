<?php

class MeasurementsOptionsDB {
    const DATE_FORMAT = "Y-m-d";
    const FOREVER_AGO = '1970-01-01';
    
    /* Adds the specified MeasurementsOptions object to the appropriate user in the database.
     * Returns a stdClass object containing the assigned optionsID on success,
     * or throws an exception on failure. */
    public static function addOptions($options) {
        $todaysDate = date(self::DATE_FORMAT);
        $resultData = new stdClass();
        $resultData->optionsID = -1;
    
        if (!($options instanceof MeasurementsOptions))
            throw new InvalidArgumentException('Expected MeasurementsOptions. Got ' . get_class($options));
        
        $userID = self::findUserID($options->getUserName());
            
        // create and run database query
        $stmt = Database::getDB()->prepare(
            "insert into MeasurementsOptions (userID, optionsName, isActive, activeMeasurement,
                bloodPressureUnits, calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                timeFormat, durationFormat, showTooltips, showSecondaryCols, showDateCol, showTimeCol,
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
            values (:userID, :optionsName, :isActive, :activeMeasurement, :bloodPressureUnits,
                :calorieUnits, :exerciseUnits, :glucoseUnits, :sleepUnits, :weightUnits,
                :timeFormat, :durationFormat, :showTooltips, :showSecondaryCols, :showDateCol, :showTimeCol,
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
            ':isActive' => $options->isActive(),
            ':activeMeasurement' => $options->getActiveMeasurement(),
            ':bloodPressureUnits' => $options->getBloodPressureUnits(),
            ':calorieUnits' => $options->getCalorieUnits(),
            ':exerciseUnits' => $options->getExerciseUnits(),
            ':glucoseUnits' => $options->getGlucoseUnits(),
            ':sleepUnits' => $options->getSleepUnits(),
            ':weightUnits' => $options->getWeightUnits(),
            ':timeFormat' => $options->getTimeFormat(),
            ':durationFormat' => $options->getDurationFormat(),
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
            ':individualBloodPressureChartEnd' => $options->getIndividualBloodPressureChartEnd() === $todaysDate ? null : $options->getIndividualBloodPressureChartEnd(),
            ':dailyBloodPressureChartStart' => $options->getDailyBloodPressureChartStart(),
            ':dailyBloodPressureChartEnd' => $options->getDailyBloodPressureChartEnd() === $todaysDate ? null : $options->getDailyBloodPressureChartEnd(),
            ':weeklyBloodPressureChartStart' => $options->getWeeklyBloodPressureChartStart(),
            ':weeklyBloodPressureChartEnd' => $options->getWeeklyBloodPressureChartEnd() === $todaysDate ? null : $options->getWeeklyBloodPressureChartEnd(),
            ':monthlyBloodPressureChartStart' => $options->getMonthlyBloodPressureChartStart(),
            ':monthlyBloodPressureChartEnd' => $options->getMonthlyBloodPressureChartEnd() === $todaysDate ? null : $options->getMonthlyBloodPressureChartEnd(),
            ':yearlyBloodPressureChartStart' => $options->getYearlyBloodPressureChartStart(),
            ':yearlyBloodPressureChartEnd' => $options->getYearlyBloodPressureChartEnd() === $todaysDate ? null : $options->getYearlyBloodPressureChartEnd(),
            ':individualCaloriesChartStart' => $options->getIndividualCaloriesChartStart(),
            ':individualCaloriesChartEnd' => $options->getIndividualCaloriesChartEnd() === $todaysDate ? null : $options->getIndividualCaloriesChartEnd(),
            ':dailyCaloriesChartStart' => $options->getDailyCaloriesChartStart(),
            ':dailyCaloriesChartEnd' => $options->getDailyCaloriesChartEnd() === $todaysDate ? null : $options->getDailyCaloriesChartEnd(),
            ':weeklyCaloriesChartStart' => $options->getWeeklyCaloriesChartStart(),
            ':weeklyCaloriesChartEnd' => $options->getWeeklyCaloriesChartEnd() === $todaysDate ? null : $options->getWeeklyCaloriesChartEnd(),
            ':monthlyCaloriesChartStart' => $options->getMonthlyCaloriesChartStart(),
            ':monthlyCaloriesChartEnd' => $options->getMonthlyCaloriesChartEnd() === $todaysDate ? null : $options->getMonthlyCaloriesChartEnd(),
            ':yearlyCaloriesChartStart' => $options->getYearlyCaloriesChartStart(),
            ':yearlyCaloriesChartEnd' => $options->getYearlyCaloriesChartEnd() === $todaysDate ? null : $options->getYearlyCaloriesChartEnd(),
            ':individualExerciseChartStart' => $options->getIndividualExerciseChartStart(),
            ':individualExerciseChartEnd' => $options->getIndividualExerciseChartEnd() === $todaysDate ? null : $options->getIndividualExerciseChartEnd(),
            ':dailyExerciseChartStart' => $options->getDailyExerciseChartStart(),
            ':dailyExerciseChartEnd' => $options->getDailyExerciseChartEnd() === $todaysDate ? null : $options->getDailyExerciseChartEnd(),
            ':weeklyExerciseChartStart' => $options->getWeeklyExerciseChartStart(),
            ':weeklyExerciseChartEnd' => $options->getWeeklyExerciseChartEnd() === $todaysDate ? null : $options->getWeeklyExerciseChartEnd(),
            ':monthlyExerciseChartStart' => $options->getMonthlyExerciseChartStart(),
            ':monthlyExerciseChartEnd' => $options->getMonthlyExerciseChartEnd() === $todaysDate ? null : $options->getMonthlyExerciseChartEnd(),
            ':yearlyExerciseChartStart' => $options->getYearlyExerciseChartStart(),
            ':yearlyExerciseChartEnd' => $options->getYearlyExerciseChartEnd() === $todaysDate ? null : $options->getYearlyExerciseChartEnd(),
            ':individualGlucoseChartStart' => $options->getIndividualGlucoseChartStart(),
            ':individualGlucoseChartEnd' => $options->getIndividualGlucoseChartEnd() === $todaysDate ? null : $options->getIndividualGlucoseChartEnd(),
            ':dailyGlucoseChartStart' => $options->getDailyGlucoseChartStart(),
            ':dailyGlucoseChartEnd' => $options->getDailyGlucoseChartEnd() === $todaysDate ? null : $options->getDailyGlucoseChartEnd(),
            ':weeklyGlucoseChartStart' => $options->getWeeklyGlucoseChartStart(),
            ':weeklyGlucoseChartEnd' => $options->getWeeklyGlucoseChartEnd() === $todaysDate ? null : $options->getWeeklyGlucoseChartEnd(),
            ':monthlyGlucoseChartStart' => $options->getMonthlyGlucoseChartStart(),
            ':monthlyGlucoseChartEnd' => $options->getMonthlyGlucoseChartEnd() === $todaysDate ? null : $options->getMonthlyGlucoseChartEnd(),
            ':yearlyGlucoseChartStart' => $options->getYearlyGlucoseChartStart(),
            ':yearlyGlucoseChartEnd' => $options->getYearlyGlucoseChartEnd() === $todaysDate ? null : $options->getYearlyGlucoseChartEnd(),
            ':individualSleepChartStart' => $options->getIndividualSleepChartStart(),
            ':individualSleepChartEnd' => $options->getIndividualSleepChartEnd() === $todaysDate ? null : $options->getIndividualSleepChartEnd(),
            ':dailySleepChartStart' => $options->getDailySleepChartStart(),
            ':dailySleepChartEnd' => $options->getDailySleepChartEnd() === $todaysDate ? null : $options->getDailySleepChartEnd(),
            ':weeklySleepChartStart' => $options->getWeeklySleepChartStart(),
            ':weeklySleepChartEnd' => $options->getWeeklySleepChartEnd() === $todaysDate ? null : $options->getWeeklySleepChartEnd(),
            ':monthlySleepChartStart' => $options->getMonthlySleepChartStart(),
            ':monthlySleepChartEnd' => $options->getMonthlySleepChartEnd() === $todaysDate ? null : $options->getMonthlySleepChartEnd(),
            ':yearlySleepChartStart' => $options->getYearlySleepChartStart(),
            ':yearlySleepChartEnd' => $options->getYearlySleepChartEnd() === $todaysDate ? null : $options->getYearlySleepChartEnd(),
            ':individualWeightChartStart' => $options->getIndividualWeightChartStart(),
            ':individualWeightChartEnd' => $options->getIndividualWeightChartEnd() === $todaysDate ? null : $options->getIndividualWeightChartEnd(),
            ':dailyWeightChartStart' => $options->getDailyWeightChartStart(),
            ':dailyWeightChartEnd' => $options->getDailyWeightChartEnd() === $todaysDate ? null : $options->getDailyWeightChartEnd(),
            ':weeklyWeightChartStart' => $options->getWeeklyWeightChartStart(),
            ':weeklyWeightChartEnd' => $options->getWeeklyWeightChartEnd() === $todaysDate ? null : $options->getWeeklyWeightChartEnd(),
            ':monthlyWeightChartStart' => $options->getMonthlyWeightChartStart(),
            ':monthlyWeightChartEnd' => $options->getMonthlyWeightChartEnd() === $todaysDate ? null : $options->getMonthlyWeightChartEnd(),
            ':yearlyWeightChartStart' => $options->getYearlyWeightChartStart(),
            ':yearlyWeightChartEnd' => $options->getYearlyWeightChartEnd() === $todaysDate ? null : $options->getYearlyWeightChartEnd()
        ));
        $resultData->optionsID = $db->lastInsertId('optionsID');
    
        return $resultData;
    }
    
    /* Edits the old options with the information in the new options.
     * Returns a stdClass object containing the number of rows affected on success,
     * or throws an exception on failure. */
    public static function editOptions($oldOptions, $newOptions) {
        $todaysDate = date(self::DATE_FORMAT);
        $returnData = new stdClass();
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
                isActive = :isActive,
                activeMeasurement = :activeMeasurement,
                bloodPressureUnits = :bloodPressureUnits,
                calorieUnits = :calorieUnits,
                exerciseUnits = :exerciseUnits,
                glucoseUnits = :glucoseUnits,
                sleepUnits = :sleepUnits,
                weightUnits = :weightUnits,
                timeFormat = :timeFormat,
                durationFormat = :durationFormat,
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
            ':isActive' => $newOptions->isActive(),
            ':activeMeasurement' => $newOptions->getActiveMeasurement(),
            ':bloodPressureUnits' => $newOptions->getBloodPressureUnits(),
            ':calorieUnits' => $newOptions->getCalorieUnits(),
            ':exerciseUnits' => $newOptions->getExerciseUnits(),
            ':glucoseUnits' => $newOptions->getGlucoseUnits(),
            ':sleepUnits' => $newOptions->getSleepUnits(),
            ':weightUnits' => $newOptions->getWeightUnits(),
            ':timeFormat' => $newOptions->getTimeFormat(),
            ':durationFormat' => $newOptions->getDurationFormat(),
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
            ':individualBloodPressureChartEnd' => $newOptions->getIndividualBloodPressureChartEnd() === $todaysDate ? null : $newOptions->getIndividualBloodPressureChartEnd(),
            ':dailyBloodPressureChartStart' => $newOptions->getDailyBloodPressureChartStart(),
            ':dailyBloodPressureChartEnd' => $newOptions->getDailyBloodPressureChartEnd() === $todaysDate ? null : $newOptions->getDailyBloodPressureChartEnd(),
            ':weeklyBloodPressureChartStart' => $newOptions->getWeeklyBloodPressureChartStart(),
            ':weeklyBloodPressureChartEnd' => $newOptions->getWeeklyBloodPressureChartEnd() === $todaysDate ? null : $newOptions->getWeeklyBloodPressureChartEnd(),
            ':monthlyBloodPressureChartStart' => $newOptions->getMonthlyBloodPressureChartStart(),
            ':monthlyBloodPressureChartEnd' => $newOptions->getMonthlyBloodPressureChartEnd() === $todaysDate ? null : $newOptions->getMonthlyBloodPressureChartEnd(),
            ':yearlyBloodPressureChartStart' => $newOptions->getYearlyBloodPressureChartStart(),
            ':yearlyBloodPressureChartEnd' => $newOptions->getYearlyBloodPressureChartEnd() === $todaysDate ? null : $newOptions->getYearlyBloodPressureChartEnd(),
            ':individualCaloriesChartStart' => $newOptions->getIndividualCaloriesChartStart(),
            ':individualCaloriesChartEnd' => $newOptions->getIndividualCaloriesChartEnd() === $todaysDate ? null : $newOptions->getIndividualCaloriesChartEnd(),
            ':dailyCaloriesChartStart' => $newOptions->getDailyCaloriesChartStart(),
            ':dailyCaloriesChartEnd' => $newOptions->getDailyCaloriesChartEnd() === $todaysDate ? null : $newOptions->getDailyCaloriesChartEnd(),
            ':weeklyCaloriesChartStart' => $newOptions->getWeeklyCaloriesChartStart(),
            ':weeklyCaloriesChartEnd' => $newOptions->getWeeklyCaloriesChartEnd() === $todaysDate ? null : $newOptions->getWeeklyCaloriesChartEnd(),
            ':monthlyCaloriesChartStart' => $newOptions->getMonthlyCaloriesChartStart(),
            ':monthlyCaloriesChartEnd' => $newOptions->getMonthlyCaloriesChartEnd() === $todaysDate ? null : $newOptions->getMonthlyCaloriesChartEnd(),
            ':yearlyCaloriesChartStart' => $newOptions->getYearlyCaloriesChartStart(),
            ':yearlyCaloriesChartEnd' => $newOptions->getYearlyCaloriesChartEnd() === $todaysDate ? null : $newOptions->getYearlyCaloriesChartEnd(),
            ':individualExerciseChartStart' => $newOptions->getIndividualExerciseChartStart(),
            ':individualExerciseChartEnd' => $newOptions->getIndividualExerciseChartEnd() === $todaysDate ? null : $newOptions->getIndividualExerciseChartEnd(),
            ':dailyExerciseChartStart' => $newOptions->getDailyExerciseChartStart(),
            ':dailyExerciseChartEnd' => $newOptions->getDailyExerciseChartEnd() === $todaysDate ? null : $newOptions->getDailyExerciseChartEnd(),
            ':weeklyExerciseChartStart' => $newOptions->getWeeklyExerciseChartStart(),
            ':weeklyExerciseChartEnd' => $newOptions->getWeeklyExerciseChartEnd() === $todaysDate ? null : $newOptions->getWeeklyExerciseChartEnd(),
            ':monthlyExerciseChartStart' => $newOptions->getMonthlyExerciseChartStart(),
            ':monthlyExerciseChartEnd' => $newOptions->getMonthlyExerciseChartEnd() === $todaysDate ? null : $newOptions->getMonthlyExerciseChartEnd(),
            ':yearlyExerciseChartStart' => $newOptions->getYearlyExerciseChartStart(),
            ':yearlyExerciseChartEnd' => $newOptions->getYearlyExerciseChartEnd() === $todaysDate ? null : $newOptions->getYearlyExerciseChartEnd(),
            ':individualGlucoseChartStart' => $newOptions->getIndividualGlucoseChartStart(),
            ':individualGlucoseChartEnd' => $newOptions->getIndividualGlucoseChartEnd() === $todaysDate ? null : $newOptions->getIndividualGlucoseChartEnd(),
            ':dailyGlucoseChartStart' => $newOptions->getDailyGlucoseChartStart(),
            ':dailyGlucoseChartEnd' => $newOptions->getDailyGlucoseChartEnd() === $todaysDate ? null : $newOptions->getDailyGlucoseChartEnd(),
            ':weeklyGlucoseChartStart' => $newOptions->getWeeklyGlucoseChartStart(),
            ':weeklyGlucoseChartEnd' => $newOptions->getWeeklyGlucoseChartEnd() === $todaysDate ? null : $newOptions->getWeeklyGlucoseChartEnd(),
            ':monthlyGlucoseChartStart' => $newOptions->getMonthlyGlucoseChartStart(),
            ':monthlyGlucoseChartEnd' => $newOptions->getMonthlyGlucoseChartEnd() === $todaysDate ? null : $newOptions->getMonthlyGlucoseChartEnd(),
            ':yearlyGlucoseChartStart' => $newOptions->getYearlyGlucoseChartStart(),
            ':yearlyGlucoseChartEnd' => $newOptions->getYearlyGlucoseChartEnd() === $todaysDate ? null : $newOptions->getYearlyGlucoseChartEnd(),
            ':individualSleepChartStart' => $newOptions->getIndividualSleepChartStart(),
            ':individualSleepChartEnd' => $newOptions->getIndividualSleepChartEnd() === $todaysDate ? null : $newOptions->getIndividualSleepChartEnd(),
            ':dailySleepChartStart' => $newOptions->getDailySleepChartStart(),
            ':dailySleepChartEnd' => $newOptions->getDailySleepChartEnd() === $todaysDate ? null : $newOptions->getDailySleepChartEnd(),
            ':weeklySleepChartStart' => $newOptions->getWeeklySleepChartStart(),
            ':weeklySleepChartEnd' => $newOptions->getWeeklySleepChartEnd() === $todaysDate ? null : $newOptions->getWeeklySleepChartEnd(),
            ':monthlySleepChartStart' => $newOptions->getMonthlySleepChartStart(),
            ':monthlySleepChartEnd' => $newOptions->getMonthlySleepChartEnd() === $todaysDate ? null : $newOptions->getMonthlySleepChartEnd(),
            ':yearlySleepChartStart' => $newOptions->getYearlySleepChartStart(),
            ':yearlySleepChartEnd' => $newOptions->getYearlySleepChartEnd() === $todaysDate ? null : $newOptions->getYearlySleepChartEnd(),
            ':individualWeightChartStart' => $newOptions->getIndividualWeightChartStart(),
            ':individualWeightChartEnd' => $newOptions->getIndividualWeightChartEnd() === $todaysDate ? null : $newOptions->getIndividualWeightChartEnd(),
            ':dailyWeightChartStart' => $newOptions->getDailyWeightChartStart(),
            ':dailyWeightChartEnd' => $newOptions->getDailyWeightChartEnd() === $todaysDate ? null : $newOptions->getDailyWeightChartEnd(),
            ':weeklyWeightChartStart' => $newOptions->getWeeklyWeightChartStart(),
            ':weeklyWeightChartEnd' => $newOptions->getWeeklyWeightChartEnd() === $todaysDate ? null : $newOptions->getWeeklyWeightChartEnd(),
            ':monthlyWeightChartStart' => $newOptions->getMonthlyWeightChartStart(),
            ':monthlyWeightChartEnd' => $newOptions->getMonthlyWeightChartEnd() === $todaysDate ? null : $newOptions->getMonthlyWeightChartEnd(),
            ':yearlyWeightChartStart' => $newOptions->getYearlyWeightChartStart(),
            ':yearlyWeightChartEnd' => $newOptions->getYearlyWeightChartEnd() === $todaysDate ? null : $newOptions->getYearlyWeightChartEnd(),
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
        $returnData = new stdClass();
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
            "select userName, optionsName, isActive, activeMeasurement, bloodPressureUnits,
                calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                timeFormat, durationFormat, showTooltips, showSecondaryCols, showDateCol, showTimeCol,
                showNotesCol, numRows, showTable, tableSize, chartPlacement, showFirstChart,
                showSecondChart, firstChartType, secondChartType, chartLastYear, chartGroupDays,
                individualBloodPressureChartStart,
                case when individualBloodPressureChartEnd is null then date(now()) else individualBloodPressureChartEnd end as individualBloodPressureChartEnd,
                dailyBloodPressureChartStart,
                case when dailyBloodPressureChartEnd is null then date(now()) else dailyBloodPressureChartEnd end as dailyBloodPressureChartEnd,
                weeklyBloodPressureChartStart,
                case when weeklyBloodPressureChartEnd is null then date(now()) else weeklyBloodPressureChartEnd end as weeklyBloodPressureChartEnd,
                monthlyBloodPressureChartStart,
                case when monthlyBloodPressureChartEnd is null then date(now()) else monthlyBloodPressureChartEnd end as monthlyBloodPressureChartEnd,
                yearlyBloodPressureChartStart,
                case when yearlyBloodPressureChartEnd is null then date(now()) else yearlyBloodPressureChartEnd end as yearlyBloodPressureChartEnd,
                individualCaloriesChartStart,
                case when individualCaloriesChartEnd is null then date(now()) else individualCaloriesChartEnd end as individualCaloriesChartEnd,
                dailyCaloriesChartStart,
                case when dailyCaloriesChartEnd is null then date(now()) else dailyCaloriesChartEnd end as dailyCaloriesChartEnd,
                weeklyCaloriesChartStart,
                case when weeklyCaloriesChartEnd is null then date(now()) else weeklyCaloriesChartEnd end as weeklyCaloriesChartEnd,
                monthlyCaloriesChartStart,
                case when monthlyCaloriesChartEnd is null then date(now()) else monthlyCaloriesChartEnd end as monthlyCaloriesChartEnd,
                yearlyCaloriesChartStart,
                case when yearlyCaloriesChartEnd is null then date(now()) else yearlyCaloriesChartEnd end as yearlyCaloriesChartEnd,
                individualExerciseChartStart,
                case when individualExerciseChartEnd is null then date(now()) else individualExerciseChartEnd end as individualExerciseChartEnd,
                dailyExerciseChartStart,
                case when dailyExerciseChartEnd is null then date(now()) else dailyExerciseChartEnd end as dailyExerciseChartEnd,
                weeklyExerciseChartStart,
                case when weeklyExerciseChartEnd is null then date(now()) else weeklyExerciseChartEnd end as weeklyExerciseChartEnd,
                monthlyExerciseChartStart,
                case when monthlyExerciseChartEnd is null then date(now()) else monthlyExerciseChartEnd end as monthlyExerciseChartEnd,
                yearlyExerciseChartStart,
                case when yearlyExerciseChartEnd is null then date(now()) else yearlyExerciseChartEnd end as yearlyExerciseChartEnd,
                individualGlucoseChartStart,
                case when individualGlucoseChartEnd is null then date(now()) else individualGlucoseChartEnd end as individualGlucoseChartEnd,
                dailyGlucoseChartStart,
                case when dailyGlucoseChartEnd is null then date(now()) else dailyGlucoseChartEnd end as dailyGlucoseChartEnd,
                weeklyGlucoseChartStart,
                case when weeklyGlucoseChartEnd is null then date(now()) else weeklyGlucoseChartEnd end as weeklyGlucoseChartEnd,
                monthlyGlucoseChartStart,
                case when monthlyGlucoseChartEnd is null then date(now()) else monthlyGlucoseChartEnd end as monthlyGlucoseChartEnd,
                yearlyGlucoseChartStart,
                case when yearlyGlucoseChartEnd is null then date(now()) else yearlyGlucoseChartEnd end as yearlyGlucoseChartEnd,
                individualSleepChartStart,
                case when individualSleepChartEnd is null then date(now()) else individualSleepChartEnd end as individualSleepChartEnd,
                dailySleepChartStart,
                case when dailySleepChartEnd is null then date(now()) else dailySleepChartEnd end as dailySleepChartEnd,
                weeklySleepChartStart,
                case when weeklySleepChartEnd is null then date(now()) else weeklySleepChartEnd end as weeklySleepChartEnd,
                monthlySleepChartStart,
                case when monthlySleepChartEnd is null then date(now()) else monthlySleepChartEnd end as monthlySleepChartEnd,
                yearlySleepChartStart,
                case when yearlySleepChartEnd is null then date(now()) else yearlySleepChartEnd end as yearlySleepChartEnd,
                individualWeightChartStart,
                case when individualWeightChartEnd is null then date(now()) else individualWeightChartEnd end as individualWeightChartEnd,
                dailyWeightChartStart,
                case when dailyWeightChartEnd is null then date(now()) else dailyWeightChartEnd end as dailyWeightChartEnd,
                weeklyWeightChartStart,
                case when weeklyWeightChartEnd is null then date(now()) else weeklyWeightChartEnd end as weeklyWeightChartEnd,
                monthlyWeightChartStart,
                case when monthlyWeightChartEnd is null then date(now()) else monthlyWeightChartEnd end as monthlyWeightChartEnd,
                yearlyWeightChartStart,
                case when yearlyWeightChartEnd is null then date(now()) else yearlyWeightChartEnd end as yearlyWeightChartEnd 
            from Users join MeasurementsOptions using (userID)
            where userName = :userName"
        );
        $stmt->execute(array(":userName" => $userName));

        // collect returned options data in an array of MeasurementsOptions objects
        foreach ($stmt as $row) {
            $options = new MeasurementsOptions($row);
            if (!is_object($options) || $options->getErrorCount() > 0)
                throw new RuntimeException('Failed to create valid measurements options: ' . array_shift($options->getErrors()));

            $allOptions[] = $options;
        }
        
        return $allOptions;
    }
    
    /* On success, returns a MeasurementsOptions object for the options with the specified name of the specified user.
     * Throws a NotFoundException on failure, returns an error associative array. */
    public static function getOptions($userName, $optionsName) {
        // create and run database query
        $stmt = Database::getDB()->prepare(
            "select userName, optionsName, isActive, activeMeasurement, bloodPressureUnits,
                calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                timeFormat, durationFormat, showTooltips, showSecondaryCols, showDateCol, showTimeCol,
                showNotesCol, numRows, showTable, tableSize, chartPlacement, showFirstChart,
                showSecondChart, firstChartType, secondChartType, chartLastYear, chartGroupDays,
                individualBloodPressureChartStart,
                case when individualBloodPressureChartEnd is null then date(now()) else individualBloodPressureChartEnd end as individualBloodPressureChartEnd,
                dailyBloodPressureChartStart,
                case when dailyBloodPressureChartEnd is null then date(now()) else dailyBloodPressureChartEnd end as dailyBloodPressureChartEnd,
                weeklyBloodPressureChartStart,
                case when weeklyBloodPressureChartEnd is null then date(now()) else weeklyBloodPressureChartEnd end as weeklyBloodPressureChartEnd,
                monthlyBloodPressureChartStart,
                case when monthlyBloodPressureChartEnd is null then date(now()) else monthlyBloodPressureChartEnd end as monthlyBloodPressureChartEnd,
                yearlyBloodPressureChartStart,
                case when yearlyBloodPressureChartEnd is null then date(now()) else yearlyBloodPressureChartEnd end as yearlyBloodPressureChartEnd,
                individualCaloriesChartStart,
                case when individualCaloriesChartEnd is null then date(now()) else individualCaloriesChartEnd end as individualCaloriesChartEnd,
                dailyCaloriesChartStart,
                case when dailyCaloriesChartEnd is null then date(now()) else dailyCaloriesChartEnd end as dailyCaloriesChartEnd,
                weeklyCaloriesChartStart,
                case when weeklyCaloriesChartEnd is null then date(now()) else weeklyCaloriesChartEnd end as weeklyCaloriesChartEnd,
                monthlyCaloriesChartStart,
                case when monthlyCaloriesChartEnd is null then date(now()) else monthlyCaloriesChartEnd end as monthlyCaloriesChartEnd,
                yearlyCaloriesChartStart,
                case when yearlyCaloriesChartEnd is null then date(now()) else yearlyCaloriesChartEnd end as yearlyCaloriesChartEnd,
                individualExerciseChartStart,
                case when individualExerciseChartEnd is null then date(now()) else individualExerciseChartEnd end as individualExerciseChartEnd,
                dailyExerciseChartStart,
                case when dailyExerciseChartEnd is null then date(now()) else dailyExerciseChartEnd end as dailyExerciseChartEnd,
                weeklyExerciseChartStart,
                case when weeklyExerciseChartEnd is null then date(now()) else weeklyExerciseChartEnd end as weeklyExerciseChartEnd,
                monthlyExerciseChartStart,
                case when monthlyExerciseChartEnd is null then date(now()) else monthlyExerciseChartEnd end as monthlyExerciseChartEnd,
                yearlyExerciseChartStart,
                case when yearlyExerciseChartEnd is null then date(now()) else yearlyExerciseChartEnd end as yearlyExerciseChartEnd,
                individualGlucoseChartStart,
                case when individualGlucoseChartEnd is null then date(now()) else individualGlucoseChartEnd end as individualGlucoseChartEnd,
                dailyGlucoseChartStart,
                case when dailyGlucoseChartEnd is null then date(now()) else dailyGlucoseChartEnd end as dailyGlucoseChartEnd,
                weeklyGlucoseChartStart,
                case when weeklyGlucoseChartEnd is null then date(now()) else weeklyGlucoseChartEnd end as weeklyGlucoseChartEnd,
                monthlyGlucoseChartStart,
                case when monthlyGlucoseChartEnd is null then date(now()) else monthlyGlucoseChartEnd end as monthlyGlucoseChartEnd,
                yearlyGlucoseChartStart,
                case when yearlyGlucoseChartEnd is null then date(now()) else yearlyGlucoseChartEnd end as yearlyGlucoseChartEnd,
                individualSleepChartStart,
                case when individualSleepChartEnd is null then date(now()) else individualSleepChartEnd end as individualSleepChartEnd,
                dailySleepChartStart,
                case when dailySleepChartEnd is null then date(now()) else dailySleepChartEnd end as dailySleepChartEnd,
                weeklySleepChartStart,
                case when weeklySleepChartEnd is null then date(now()) else weeklySleepChartEnd end as weeklySleepChartEnd,
                monthlySleepChartStart,
                case when monthlySleepChartEnd is null then date(now()) else monthlySleepChartEnd end as monthlySleepChartEnd,
                yearlySleepChartStart,
                case when yearlySleepChartEnd is null then date(now()) else yearlySleepChartEnd end as yearlySleepChartEnd,
                individualWeightChartStart,
                case when individualWeightChartEnd is null then date(now()) else individualWeightChartEnd end as individualWeightChartEnd,
                dailyWeightChartStart,
                case when dailyWeightChartEnd is null then date(now()) else dailyWeightChartEnd end as dailyWeightChartEnd,
                weeklyWeightChartStart,
                case when weeklyWeightChartEnd is null then date(now()) else weeklyWeightChartEnd end as weeklyWeightChartEnd,
                monthlyWeightChartStart,
                case when monthlyWeightChartEnd is null then date(now()) else monthlyWeightChartEnd end as monthlyWeightChartEnd,
                yearlyWeightChartStart,
                case when yearlyWeightChartEnd is null then date(now()) else yearlyWeightChartEnd end as yearlyWeightChartEnd 
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
            throw new RuntimeException('Failed to create valid measurements options: ' . array_shift($options->getErrors()));

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