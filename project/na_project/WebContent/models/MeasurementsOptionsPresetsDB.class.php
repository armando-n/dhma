<?php

class MeasurementsOptionsPresetsDB {
    
    // adds the specified MeasurementsOptionsPreset object to the database, associating it with the user with the specified userName
    public static function addPreset($preset, $userName) {
        $returnPresetID = -1;
    
        try {
            if (!($preset instanceof MeasurementsOptionsPreset))
                throw new InvalidArgumentException('Expected MeasurementsOptionsPreset. Got ' . get_class($preset));
            
            $userID = self::findUserID($userName);
                
            // add the preset and associate with the correct userID
            $stmt = Database::getDB()->prepare(
                "insert into MeasurementsOptionsPresets (userID, presetName, bloodPressureUnits,
                    calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                    timeFormat, showTooltips, showExerciseTypeCol, showDateCol, showTimeCol,
                    showNotesCol, numRows, showFirstChart, showSecondChart, firstChartType,
                    secondChartType, firstChartStart, secondChartStart, firstChartEnd,
                    secondChartEnd, chartLastYear, chartDailyAverages)
                values (:userID, :presetName, :bloodPressureUnits,
                    :calorieUnits, :exerciseUnits, :glucoseUnits, :sleepUnits, :weightUnits,
                    :timeFormat, :showTooltips, :showExerciseTypeCol, :showDateCol, :showTimeCol,
                    :showNotesCol, :numRows, :showFirstChart, :showSecondChart, :firstChartType,
                    :secondChartType, :firstChartStart, :secondChartStart, :firstChartEnd,
                    :secondChartEnd, :chartLastYear, :chartDailyAverages)"
            );
            $stmt->execute(array(
                ":userID" => $userID,
                ":presetName" => $preset->getPresetName(),
                ":bloodPressureUnits" => $preset->getBloodPressureUnits(),
                ":calorieUnits" => $preset->getCalorieUnits(),
                ":exerciseUnits" => $preset->getExerciseUnits(),
                ":glucoseUnits" => $preset->getGlucoseUnits(),
                ":sleepUnits" => $preset->getSleepUnits(),
                ":weightUnits" => $preset->getWeightUnits(),
                ":timeFormat" => $preset->getTimeFormat(),
                ":showTooltips" => $preset->getShowTooltips(),
                ":showExerciseTypeCol" => $preset->getShowExerciseTypeCol(),
                ":showDateCol" => $preset->getShowDateCol(),
                ":showTimeCol" => $preset->getShowTimeCol(),
                ":showNotesCol" => $preset->getShowNotesCol(),
                ":numRows" => $preset->getNumRows(),
                ":showFirstChart" => $preset->getShowFirstChart(),
                ":showSecondChart" => $preset->getShowSecondChart(),
                ":firstChartType" => $preset->getFirstChartType(),
                ":secondChartType" => $preset->getSecondChartType(),
                ":firstChartStart" => $preset->getFirstChartStart(),
                ":secondChartStart" => $preset->getSecondChartStart(),
                ":firstChartEnd" => $preset->getFirstChartEnd(),
                ":secondChartEnd" => $preset->getSecondChartEnd(),
                ":chartLastYear" => $preset->getChartLastYear(),
                ":chartDailyAverages" => $preset->getChartDailyAverages()
            ));
            $returnPresetID = $db->lastInsertId("presetID");
    
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('error' => 'Database config file not found');
        } catch (InvalidArgumentException $e) {
            return array('error' => $e->getMessage());
        } catch (UserNotFoundException $e) {
            return array('error' => $e->getMessage());
        }
    
        return $returnPresetID;
    }
    
    public static function editPreset($oldPreset, $newPreset) {
        try {
            if (!($oldPreset instanceof MeasurementsOptionsPreset))
                throw new InvalidArgumentException('Expected MeasurementsOptionsPreset for old preset. Got ' . get_class($oldPreset));
            if (!($newPreset instanceof MeasurementsOptionsPreset))
                throw new InvalidArgumentException('Expected MeasurementsOptionsPreset for new preset. Got ' . get_class($newPreset));
            
            $userID = self::findUserID($oldPreset->getUserName());
            
            // edit the preset
            $stmt = Database::getDB()->prepare(
                "update MeasurementsOptionsPresets
                set presetName = :newPresetName,
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
                    and presetName = :oldPresetName"
            );
            $stmt->execute(array(
                ":userID" => $userID,
                ":newPresetName" => $newPreset->getPresetName(),
                ":bloodPressureUnits" => $newPreset->getBloodPressureUnits(),
                ":calorieUnits" => $newPreset->getCalorieUnits(),
                ":exerciseUnits" => $newPreset->getExerciseUnits(),
                ":glucoseUnits" => $newPreset->getGlucoseUnits(),
                ":sleepUnits" => $newPreset->getSleepUnits(),
                ":weightUnits" => $newPreset->getWeightUnits(),
                ":timeFormat" => $newPreset->getTimeFormat(),
                ":showTooltips" => $newPreset->getShowTooltips(),
                ":showExerciseTypeCol" => $newPreset->getShowExerciseTypeCol(),
                ":showDateCol" => $newPreset->getShowDateCol(),
                ":showTimeCol" => $newPreset->getShowTimeCol(),
                ":showNotesCol" => $newPreset->getShowNotesCol(),
                ":numRows" => $newPreset->getNumRows(),
                ":showFirstChart" => $newPreset->getShowFirstChart(),
                ":showSecondChart" => $newPreset->getShowSecondChart(),
                ":firstChartType" => $newPreset->getFirstChartType(),
                ":secondChartType" => $newPreset->getSecondChartType(),
                ":firstChartStart" => $newPreset->getFirstChartStart(),
                ":secondChartStart" => $newPreset->getSecondChartStart(),
                ":firstChartEnd" => $newPreset->getFirstChartEnd(),
                ":secondChartEnd" => $newPreset->getSecondChartEnd(),
                ":chartLastYear" => $newPreset->getChartLastYear(),
                ":chartDailyAverages" => $newPreset->getChartDailyAverages(),
                ":oldPresetName" => $oldPreset->getPresetName()
            ));
    
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('error' => $e->getMessage());
        } catch (InvalidArgumentException $e) {
            return array('error' => $e->getMessage());
        } catch (UserNotFoundException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    // returns an array of MeasurementsOptionsPreset objects for the user with the specified userName
    public static function getPresetsFor($userName) {
        $allPresets = array();
    
        try {
            // query database
            $stmt = Database::getDB()->prepare(
                "select userName, presetName, bloodPressureUnits,
                    calorieUnits, exerciseUnits, glucoseUnits, sleepUnits, weightUnits,
                    timeFormat, showTooltips, showExerciseTypeCol, showDateCol, showTimeCol,
                    showNotesCol, numRows, showFirstChart, showSecondChart, firstChartType,
                    secondChartType, firstChartStart, secondChartStart, firstChartEnd,
                    secondChartEnd, chartLastYear, chartDailyAverages
                from Users join MeasurementsOptionsPresets using (userID)
                where userName = :userName"
            );
            $stmt->execute(array("userName" => $userName));
    
            // create objects for each returned row
            foreach ($stmt as $row) {
                $preset = new MeasurementsOptionsPreset($row);
                if (!is_object($preset) || $preset->getErrorCount() > 0)
                    throw new RuntimeException("Failed to create valid user profile");

                $allPresets[] = $preset;
            }
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('error' => $e->getMessage());
        }
    
        return $allPresets;
    }
    
    public static function deletePreset($userName, $presetName) {
        try {
            $userID = self::findUserID($userName);

            $stmt = Database::getDB()->prepare(
                "delete from MeasurementsOptionsPresets
                where userID = :userID
                    and presetName = :presetName"
            );
            $stmt->execute(array(
                ":userID" => $userID,
                ":presetName" => $presetName
            ));
    
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('error' => $e->getMessage());
        }
    
        return true;
    }
    
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