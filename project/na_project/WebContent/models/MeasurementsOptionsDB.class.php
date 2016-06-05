<?php

class MeasurementsOptionsDB {
    
    /* adds the specified MeasurementsOptions object to the appropriate user in the database.
     * returns success associative array w/ optionsID in data object,
     * or an associative array w/ key 'error' w/ an error message as value on failure */
    public static function addOptions($options) {
        $resultData = new stdObject();
        $resultData->returnOptionsID = -1;
    
        try {
            if (!($options instanceof MeasurementsOptions))
                throw new InvalidArgumentException('Expected MeasurementsOptions. Got ' . get_class($options));
            
            $userID = self::findUserID($options->getUserName());
                
            // add the options and associate with the correct userID
            $stmt = Database::getDB()->prepare(
                "insert into MeasurementsOptions (userID, optionsName, bloodPressureUnits,
                    calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                    timeFormat, showTooltips, showExerciseTypeCol, showDateCol, showTimeCol,
                    showNotesCol, numRows, showFirstChart, showSecondChart, firstChartType,
                    secondChartType, firstChartStart, secondChartStart, firstChartEnd,
                    secondChartEnd, chartLastYear, chartDailyAverages)
                values (:userID, :optionsName, :bloodPressureUnits,
                    :calorieUnits, :exerciseUnits, :glucoseUnits, :sleepUnits, :weightUnits,
                    :timeFormat, :showTooltips, :showExerciseTypeCol, :showDateCol, :showTimeCol,
                    :showNotesCol, :numRows, :showFirstChart, :showSecondChart, :firstChartType,
                    :secondChartType, :firstChartStart, :secondChartStart, :firstChartEnd,
                    :secondChartEnd, :chartLastYear, :chartDailyAverages)"
            );
            $stmt->execute(array(
                ":userID" => $userID,
                ":optionsName" => $options->getOptionsName(),
                ":bloodPressureUnits" => $options->getBloodPressureUnits(),
                ":calorieUnits" => $options->getCalorieUnits(),
                ":exerciseUnits" => $options->getExerciseUnits(),
                ":glucoseUnits" => $options->getGlucoseUnits(),
                ":sleepUnits" => $options->getSleepUnits(),
                ":weightUnits" => $options->getWeightUnits(),
                ":timeFormat" => $options->getTimeFormat(),
                ":showTooltips" => $options->getShowTooltips(),
                ":showExerciseTypeCol" => $options->getShowExerciseTypeCol(),
                ":showDateCol" => $options->getShowDateCol(),
                ":showTimeCol" => $options->getShowTimeCol(),
                ":showNotesCol" => $options->getShowNotesCol(),
                ":numRows" => $options->getNumRows(),
                ":showFirstChart" => $options->getShowFirstChart(),
                ":showSecondChart" => $options->getShowSecondChart(),
                ":firstChartType" => $options->getFirstChartType(),
                ":secondChartType" => $options->getSecondChartType(),
                ":firstChartStart" => $options->getFirstChartStart(),
                ":secondChartStart" => $options->getSecondChartStart(),
                ":firstChartEnd" => $options->getFirstChartEnd(),
                ":secondChartEnd" => $options->getSecondChartEnd(),
                ":chartLastYear" => $options->getChartLastYear(),
                ":chartDailyAverages" => $options->getChartDailyAverages()
            ));
            $resultData->returnOptionsID = $db->lastInsertId("optionsID");
    
        } catch (PDOException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('success' => false, 'error' => 'Database config file not found');
        } catch (InvalidArgumentException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (UserNotFoundException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    
        return array('success' => true, 'data' => $resultData);
    }
    
    /* edits the old options with the information in the new options
     * returns a success associative array on success w/ number of rows affected in data field,
     * or returns an error associative array on failure */
    public static function editOptions($oldOptions, $newOptions) {
        $returnData = new stdObject();
        $returnData->rowsAffected = 0;
        
        try {
            if (!($oldOptions instanceof MeasurementsOptions))
                throw new InvalidArgumentException('Expected MeasurementsOptions for old options. Got ' . get_class($oldOptions));
            if (!($newOptions instanceof MeasurementsOptions))
                throw new InvalidArgumentException('Expected MeasurementsOptions for new options. Got ' . get_class($newOptions));
            
            $userID = self::findUserID($oldOptions->getUserName());
            
            // edit the options
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
                    showExerciseTypeCol = :showExerciseTypeCol,
                    showDateCol = :showDateCol,
                    showTimeCol = :showTimeCol,
                    showNotesCol = :showNotesCol,
                    numRows = :numRows,
                    showFirstChart = :showFirstChart,
                    showSecondChart = :showSecondChart,
                    firstChartType = :firstChartType,
                    secondChartType = :secondChartType,
                    firstChartStart = :firstChartStart,
                    secondChartStart = :secondChartStart,
                    firstChartEnd = :firstChartEnd,
                    secondChartEnd = :secondChartEnd,
                    chartLastYear = :chartLastYear,
                    chartDailyAverages = :chartDailyAverages
                where userID = :userID
                    and optionsName = :oldOptionsName"
            );
            $stmt->execute(array(
                ":userID" => $userID,
                ":newOptionsName" => $newOptions->getOptionsName(),
                ":bloodPressureUnits" => $newOptions->getBloodPressureUnits(),
                ":calorieUnits" => $newOptions->getCalorieUnits(),
                ":exerciseUnits" => $newOptions->getExerciseUnits(),
                ":glucoseUnits" => $newOptions->getGlucoseUnits(),
                ":sleepUnits" => $newOptions->getSleepUnits(),
                ":weightUnits" => $newOptions->getWeightUnits(),
                ":timeFormat" => $newOptions->getTimeFormat(),
                ":showTooltips" => $newOptions->getShowTooltips(),
                ":showExerciseTypeCol" => $newOptions->getShowExerciseTypeCol(),
                ":showDateCol" => $newOptions->getShowDateCol(),
                ":showTimeCol" => $newOptions->getShowTimeCol(),
                ":showNotesCol" => $newOptions->getShowNotesCol(),
                ":numRows" => $newOptions->getNumRows(),
                ":showFirstChart" => $newOptions->getShowFirstChart(),
                ":showSecondChart" => $newOptions->getShowSecondChart(),
                ":firstChartType" => $newOptions->getFirstChartType(),
                ":secondChartType" => $newOptions->getSecondChartType(),
                ":firstChartStart" => $newOptions->getFirstChartStart(),
                ":secondChartStart" => $newOptions->getSecondChartStart(),
                ":firstChartEnd" => $newOptions->getFirstChartEnd(),
                ":secondChartEnd" => $newOptions->getSecondChartEnd(),
                ":chartLastYear" => $newOptions->getChartLastYear(),
                ":chartDailyAverages" => $newOptions->getChartDailyAverages(),
                ":oldOptionsName" => $oldOptions->getOptionsName()
            ));
            $returnData->rowsAffected = $stmt->rowCount();
    
        } catch (PDOException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (InvalidArgumentException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (UserNotFoundException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
        
        return array('success' => true, 'data' => $returnData);
    }
    
    public static function deleteOptions($userName, $optionsName) {
        $returnData = new stdObject();
        $returnData->rowsAffected = 0;
        
        try {
            $userID = self::findUserID($userName);

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

        } catch (PDOException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (UserNotFoundException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }

        return array('success' => true, 'data' => $returnData);;
    }
    
    /* on success, returns a success associative array w/ the data field containing
     * an array of MeasurementsOptions objects for the user with the specified userName.
     * returns an error associative array on failure */
    public static function getOptionsFor($userName) {
        $allOptions = array();
    
        try {
            $stmt = Database::getDB()->prepare(                // query database
                "select userName, optionsName, bloodPressureUnits,
                    calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                    timeFormat, showTooltips, showExerciseTypeCol, showDateCol, showTimeCol,
                    showNotesCol, numRows, showFirstChart, showSecondChart, firstChartType,
                    secondChartType, firstChartStart, secondChartStart, firstChartEnd,
                    secondChartEnd, chartLastYear, chartDailyAverages
                from Users join MeasurementsOptions using (userID)
                where userName = :userName"
            );
            $stmt->execute(array(":userName" => $userName));
    
            foreach ($stmt as $row) {    // create objects for each returned row
                $options = new MeasurementsOptions($row);
                if (!is_object($options) || $options->getErrorCount() > 0)
                    throw new RuntimeException("Failed to create valid user profile");
    
                $allOptions[] = $options;
            }
        } catch (PDOException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }
    
        return array('success' => true, 'data' => $allOptions);
    }
    
    /* on success, returns a MeasurementsOptions object for the specified options of the specified user
     * on failure, returns an error associative array */
    public static function getOptions($userName, $optionsName) {
        try {
            // query database
            $stmt = Database::getDB()->prepare(
                "select userName, optionsName, bloodPressureUnits,
                    calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                    timeFormat, showTooltips, showExerciseTypeCol, showDateCol, showTimeCol,
                    showNotesCol, numRows, showFirstChart, showSecondChart, firstChartType,
                    secondChartType, firstChartStart, secondChartStart, firstChartEnd,
                    secondChartEnd, chartLastYear, chartDailyAverages
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
                throw new NotFoundException('User name "' . htmlspecialchars($userName). '" not found');

            $options = new MeasurementsOptions($row);
            if (!is_object($options) || $options->getErrorCount() > 0)
                throw new RuntimeException("Failed to create valid user profile");

        } catch (PDOException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        } catch (NotFoundException $e) {
            return array('success' => false, 'error' => $e->getMessage());
        }

        return array('success' => true, 'data' => $options);
    
    // find userID from given userName
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