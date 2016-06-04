<?php
class GlucoseMeasurementsDB {
    
    /* Adds the GlucoseMeasurement object that must be passed as the first argument.
     * Returns the automatically assigned measurement ID.
     * Only the first argument is required.
     * A second argument can be the userID, which reduces the amount of work necessary by 1 query.
     */
    public static function addMeasurement() {
        $measurementID = -1;
        $userID = -1;
    
        try {
            $db = Database::getDB();
            
            if (func_num_args() < 1)
                throw new PDOException('GlucoseMeasurementsDB.addMeasurement: arguments expected');
            
            $measurement = func_get_arg(0);
        
            if (func_num_args() < 2) {
                $stmt = $db->prepare('select userID from Users where userName = :userName');
                $stmt->execute(array(":userName" => $measurement->getUserName()));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row !== false)
                    $userID = $row['userID'];
                else
                    throw new PDOException('User name "' . $measurement->getUserName() . '" not found');
            } else
                $userID = func_get_arg(1);
            
            $stmt = $db->prepare(
                "insert into GlucoseMeasurements (glucose,
                    dateAndTime, notes, units, userID)
                values (:glucose, :dateAndTime,
                    :notes, :units, :userID)"
            );
            $stmt->execute(array(
                ":glucose" => $measurement->getMeasurement(),
                ":dateAndTime" => $measurement->getDateTime()->format("Y-m-d H:i"),
                ":notes" => $measurement->getNotes(),
                ":units" => $measurement->getUnits(),
                ":userID" => $userID
            ));
            $measurementID = $db->lastInsertId("glucoseID");
    
        } catch (PDOException $e) {
            $measurement->setError('glucoseMeasurementsDB', 'ADD_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $measurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
    
        return $measurementID;
    }
    
    /* Updates a measurement's attributes to the attribute values in $newMeasurement.
     * $oldMeasurement should contain the measurement's old attribute values.
     */
    public static function editMeasurement($oldMeasurement, $newMeasurement) {
        try {
            $db = Database::getDB();
            $oldParams = $oldMeasurement->getParameters();
            $newParams = $newMeasurement->getParameters();
            $numParams = count($oldMeasurement);
            $dateAndTime = $oldParams['dateAndTime'];
    
            foreach ($newParams as $key => $value) {
    
                if (!array_key_exists($key, $oldParams))
                    throw new PDOException('Key ' . htmlspecialchars($key) . ' is invalid');
    
                    if ($oldParams[$key] !== $newParams[$key]) {
                        $stmt = $db->prepare(
                            "update GlucoseMeasurements set $key = :value
                            where userID in
                                (select userID from Users where userName = :userName)
                            and dateAndTime = :dateAndTime");
                        $stmt->execute(array(
                            ":value" => $value,
                            ":userName" => $newParams['userName'],
                            ":dateAndTime" => $dateAndTime
                        ));
                    }
                    
                    if ($key === 'dateAndTime')
                        $dateAndTime = $value;
            }
    
        } catch (PDOException $e) {
            $newMeasurement->setError('glucoseMeasurementsDB', 'EDIT_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $newMeasurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
        
        return $newMeasurement;
    }
    
    /* Returns an array of all GlucoseMeasurement objects found in the database for all users.
     * TODO This method serves no purpose that I can see. It was probably required for the original assignment. Delete this?
     */ 
    public static function getAllMeasurements() {
        $allMeasurements = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, dateAndTime, glucose, units, notes
                from Users join GlucoseMeasurements using (userID)"
            );
            $stmt->execute();

            foreach ($stmt as $row) {
                $measurement = new GlucoseMeasurement($row);
                if (is_object($measurement) && $measurement->getErrorCount() == 0)
                    $allMeasurements[] = $measurement;
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $allMeasurements;
    }
    
    // Returns a GlucoseMeasurement object for the specified user with the specified date and time.
    public static function getMeasurement($userName, $dateAndTime) {
        $measurement = null;
        if ( ($dashPos = strrpos($dateAndTime, '-')) > 8)
            $dateAndTime[$dashPos] = ':';
        $dateTime = new DateTime($dateAndTime);
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select *
                from Users join GlucoseMeasurements using (userID)
                where userName = :userName and dateAndTime = :dateAndTime"
            );
            $stmt->execute(array(":userName" => $userName, ":dateAndTime" => $dateTime->format('Y-m-d H:i')));
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $measurement = new GlucoseMeasurement($row);
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $measurement;
    }
    
    // Returns an array of all GlucoseMeasurement objects for the specified user, sorted by date
    public static function getMeasurementsBy($byAttrName, $byAttrValue, $order = 'desc') {
        $allowedOrders = array('asc', 'desc');
        $allowedByAttrs = array('userName', 'userID');
        $measurements = array();
    
        try {
            // validate arguments
            if (!in_array($order, $allowedOrders))
                throw new Exception("$order is not an allowed order");
            if (!in_array($byAttrName, $allowedByAttrs))
                throw new PDOException("$byAttrName not allowed search criterion for glucose measurement");
            
            // create and run database query
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select *
                from Users join GlucoseMeasurements using (userID)
                where ($byAttrName = :$byAttrName)
                order by dateAndTime $order");
            $stmt->execute(array(":$byAttrName" => $byAttrValue));
            
            // collect returned data in an array of GlucoseMeasurement objects
            foreach ($stmt as $row) {
                $msmt = new GlucoseMeasurement($row);
                if (is_object($msmt) && $msmt->getErrorCount() == 0)
                    $measurements[] = $msmt;
            }
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $measurements;
    }
    
    /* Returns an array of GlucoseMeasurement objects for the specified user, sorted by date.
     * The date range must be specified using $minDate and $maxDate.
     * IMPORTANT!: minDate and maxDate are expected to have been verified already (to avoid SQL injection)
     */
    public static function getMeasurementsBounded($byAttrName, $byAttrValue, $minDate, $maxDate, $order = 'asc') {
        $allowedOrders = array('asc', 'desc');
        $allowedByAttrs = array('userName', 'userID');
        $measurements = array();
    
        try {
            // validate arguments, setting defaults for date range if not set
            if (!in_array($order, $allowedOrders))
                throw new PDOException("Invalid order specified");
            if (!in_array($byAttrName, $allowedByAttrs))
                throw new PDOException("Invalid search criterion for measurement");
            if (is_null($minDate) || is_null($maxDate))
                throw new PDOException("Both minimum and maximum dates must be specified in time period measurement requests");

            // create and run database query
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select *
                from Users join GlucoseMeasurements using (userID)
                where ($byAttrName = :$byAttrName)
                    and date(dateAndTime) >= '$minDate'
                    and date(dateAndTime) <= '$maxDate'
                order by dateAndTime $order"
            );
            $stmt->execute(array(":$byAttrName" => $byAttrValue));

            // collect returned data in an array of GlucoseMeasurement objects
            foreach ($stmt as $row) {
                $msmt = new GlucoseMeasurement($row);
                if (is_object($msmt) && $msmt->getErrorCount() == 0)
                    $measurements[] = $msmt;
            }
    
        } catch (PDOException $e) {
            return array('error' => ('PDOException: ' .$e->getMessage()) );
        } catch (RuntimeException $e) {
            return array('error' => ('RuntimeException: ' .$e->getMessage()) );
        }
    
        return $measurements;
    }
    
    /* Note that this function's implementation is for a non-cumulative measurement type, which behaves differently than cumulative measurement types.
     * $timePeriod can be "day", "week", "month", or "year". It specifies whether you want weekly averages, monthly averages, etc.
     * The $timePeriod-ly averages are given for each $timePeriod that falls within the date range specified by $minDate and $maxDate.
     * If $groupEachDay is false, this returns an array of stdClass objects representing the average INDIVIDUAL measurement over each $timePeriod.
     * In other words, it would return the average INDIVIDUAL measurement for each month, if $timePeriod was "month".
     * If $groupEachDay is true, the stdClass objects returned instead represent the average of each DAY'S AVERAGED measurements over each $timePeriod.
     * In other words, it would return the average DAILY AVERAGE measurement for each month, if $timePeriod was "month".
     * $timePeriod set to "day" will behave the same regardless of the value of $groupEachDay, for obvious reasons. (It's a bit rendundant).
     * IMPORTANT!: $minDate and $maxDate are expected to have been verified already (to avoid SQL injection)
     */
    public static function getTimePeriodMeasurements($userName, $timePeriod, $groupEachDay, $minDate, $maxDate, $order = 'asc') {
        $allowedOrders = array('asc', 'desc');
        $measurements = array();
    
        try {
            // validate arguments
            if ($groupEachDay)
                throw new PDOException("Grouping each day is not applicable to glucose measurements");
            if (!in_array($order, $allowedOrders))
                throw new PDOException("$order is not an allowed order");
            if (is_null($minDate) || is_null($maxDate))
                throw new PDOException("Both minimum and maximum dates must be specified in time period measurement requests");
            
            // validate $timePeriod; also determine date column format and default date interval
            switch ($timePeriod) {
                case 'day': $periodColumnName = "date(dateAndTime)"; break;
                case 'week': $periodColumnName = "concat(year(dateAndTime),'-', week(dateAndTime))"; break;
                case 'month': $periodColumnName = "concat(year(dateAndTime),'-', month(dateAndTime))"; break;
                case 'year': $periodColumnName = "year(dateAndTime)"; break;
                default:
                    throw new PDOException("Invalid time period specified");
            }

            // create and run database query
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, $periodColumnName $timePeriod, units,
                    replace(format(avg(glucose), 2), ',', '') glucose
                from Users join GlucoseMeasurements using (userID)
                where userName = :userName
                    and date(dateAndTime) >= '$minDate'
                    and date(dateAndTime) <= '$maxDate'
                group by $timePeriod
                order by dateAndTime $order"
            );
            $stmt->execute(array(":userName" => $userName));

            // collect returned data in an array of stdClass objects
            foreach ($stmt as $row) {
                $msmt = new stdClass();
                $msmt->$timePeriod = $row[$timePeriod];
                $msmt->glucose = $row['glucose'];
                $msmt->units = $row['units'];
                $msmt->userName = $row['userName'];
                $measurements[] = $msmt;
            }
    
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        } catch (RuntimeException $e) {
            return array('error' => $e->getMessage());
        }
    
        return $measurements;
    }
    
    // Deletes the measurement with the specified date, time, and user name
    public static function deleteMeasurement($userName, $dateAndTime) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "delete from GlucoseMeasurements
                where userID in
                    (select userID from Users
                    where userName = :userName)
                and dateAndTime = :dateAndTime"
            );
            $stmt->execute(array(":userName" => $userName, ":dateAndTime" => $dateAndTime));
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
}
?>