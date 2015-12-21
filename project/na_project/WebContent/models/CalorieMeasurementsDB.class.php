<?php
class CalorieMeasurementsDB {
    
    // takes a CalorieMeasurement object as its first argument. No other argument is required. A second argument
    // can be the userID, which reduces the amount of work necessary by 1 query
    public static function addMeasurement() {
        $measurementID = -1;
        $userID = -1;
    
        try {
            $db = Database::getDB();
            
            if (func_num_args() < 1)
                throw new PDOException('CalorieMeasurementsDB.addMeasurement: arguments expected');
            
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
                "insert into CalorieMeasurements (calories, dateAndTime, notes, units, userID)
                values (:calories, :dateAndTime, :notes, :units, :userID)"
            );
            $stmt->execute(array(
                ":calories" => $measurement->getMeasurement(),
                ":dateAndTime" => $measurement->getDateTime()->format("Y-m-d H:i"),
                ":notes" => $measurement->getNotes(),
                ":units" => $measurement->getUnits(),
                ":userID" => $userID
            ));
            $measurementID = $db->lastInsertId("calorieID");
    
        } catch (PDOException $e) {
            $measurement->setError('calorieMeasurementsDB', 'ADD_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $measurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
    
        return $measurementID;
    }
    
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
                            "update CalorieMeasurements set $key = :value
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
            $newMeasurement->setError('calorieMeasurementsDB', 'EDIT_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $newMeasurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
        
        return $newMeasurement;
    }
    
    // returns an array of all CalorieMeasurement objects found in the database
    public static function getAllMeasurements() {
        $allMeasurements = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare("select * from Users join CalorieMeasurements using (userID)");
            $stmt->execute();
            $str = '';
            foreach ($stmt as $row) {
                $measurement = new CalorieMeasurement($row);
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
    
    public static function getMeasurement($userName, $dateAndTime) {
        $measurement = null;
        if ( ($dashPos = strrpos($dateAndTime, '-')) > 8)
            $dateAndTime[$dashPos] = ':';
        $dateTime = new DateTime($dateAndTime);
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select *
                from Users join CalorieMeasurements using (userID)
                where userName = :userName and dateAndTime = :dateAndTime"
            );
            $stmt->execute(array(":userName" => $userName, ":dateAndTime" => $dateTime->format('Y-m-d H:i')));
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $measurement = new CalorieMeasurement($row);
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $measurement;
    }
    
    // returns an array of CalorieMeasurement objects, sorted by date
    public static function getMeasurementsBy($type, $value, $order = 'desc') {
        $allowedOrders = array('asc', 'desc');
        $allowedTypes = array('userName', 'userID');
        $measurementsArray = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new Exception("$order is not an allowed order");
            if (!in_array($type, $allowedTypes))
                throw new PDOException("$type not allowed search criterion for calorie measurement");
            
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select *
                from Users join CalorieMeasurements using (userID)
                where ($type = :$type)
                order by dateAndTime $order");
            $stmt->execute(array(":$type" => $value));
            
            $str = '';
            foreach ($stmt as $row) {
                $measurement = new CalorieMeasurement($row);
                if (is_object($measurement) && $measurement->getErrorCount() == 0)
                    $measurementsArray[] = $measurement;
            }
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $measurementsArray;
    }
    
    public static function getMeasurementsBounded($type, $value, $minDate = 'date_sub(now(), interval 30 day)', $maxDate = 'now()', $order = 'asc') {
        $allowedOrders = array('asc', 'desc');
        $allowedTypes = array('userName', 'userID');
        $measurements = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new PDOException("$order is not an allowed order");
            if (!in_array($type, $allowedTypes))
                throw new PDOException("$type not allowed search criterion for measurement");

            $db = Database::getDB();
            $stmt = $db->prepare(
                "select *
                from Users join CalorieMeasurements using (userID)
                where ($type = :$type)
                    and date(dateAndTime) > $minDate
                    and date(dateAndTime) < $maxDate
                order by dateAndTime $order"
            );
            $stmt->execute(array(":$type" => $value));

            foreach ($stmt as $row) {
                $msmt = new CalorieMeasurement($row);
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
    
    /* returns an array of stdClass objects representing the average measurement over the specified time period, sorted by date.
     * if $dailyAvgWanted is true, the objects returned instead represent the average daily measurement over the time period. */
    public static function getAverageMeasurements($userName, $timePeriod, $dailyAvgWanted = false, $order = 'asc') {
        $allowedOrders = array('asc', 'desc');
        $measurements = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new PDOException("$order is not an allowed order");
            
            switch ($timePeriod) {
                case 'day':
                    $interval = '30 day';
                    $periodCol = "date(dateAndTime)";
                    break;
                case 'week':
                    $interval = '1 year';
                    $periodCol = "concat(year(dateAndTime),'-', lpad(week(dateAndTime), 2, '0'))";
                    break;
                case 'month':
                    $interval = '1 year';
                    $periodCol = "concat(year(dateAndTime),'-', lpad(month(dateAndTime), 2, '0'))";
                    break;
                case 'year':
                    $interval = '5 year';
                    $periodCol = "year(dateAndTime)";
                    break;
                default:
                    throw new PDOException("$timePeriod not allowed search criterion for measurement");
            }

            $db = Database::getDB();
            
            if ($dailyAvgWanted) {
                if ($timePeriod !== 'week' && $timePeriod !== 'month' && $timePeriod !== 'year')
                    throw new PDOException('CalorieMeasurementsDB: invalid time period for daily average request');
                
                $sql =
                    "select userName, $timePeriod, units, replace(format(avg(calories), 2), ',', '') calories
                    from
                        (select userName, date(dateAndTime) day, $periodCol $timePeriod, units,
                            replace(format(sum(calories), 2), ',', '') calories
                        from Users join CalorieMeasurements using (userID)
                        where userName = :userName
                            and dateAndTime > date_sub(now(), interval $interval)
                        group by day
                        order by dateAndTime $order) as custom
                    group by $timePeriod;";
            }
            
            else {
                $sql =
                    "select userName, $periodCol $timePeriod, units,
                        replace(format(sum(calories), 2), ',', '') calories
                    from Users join CalorieMeasurements using (userID)
                    where userName = :userName
                        and dateAndTime > date_sub(now(), interval $interval)
                    group by $timePeriod
                    order by dateAndTime $order";
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":userName" => $userName));

            foreach ($stmt as $row) {
                $msmt = new stdClass();
                $msmt->$timePeriod = $row[$timePeriod];
                $msmt->calories = $row['calories'];
                $msmt->units = $row['units'];
                $msmt->userName = $row['userName'];
                $measurements[] = $msmt;
            }
    
        } catch (PDOException $e) {
            return array('error' => ('PDOException: ' .$e->getMessage()) );
        } catch (RuntimeException $e) {
            return array('error' => ('RuntimeException: ' .$e->getMessage()) );
        }
    
        return $measurements;
    }
    
    public static function deleteMeasurement($userName, $dateAndTime) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "delete from CalorieMeasurements
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