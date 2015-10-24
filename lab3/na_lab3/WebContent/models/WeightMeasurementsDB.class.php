<?php
class WeightMeasurementsDB {
    
    // takes a WeightMeasurement object as its first argument. No other argument is required. A second argument
    // can be the userID, which reduces the amount of work necessary by 1 query
    public static function addMeasurement() {
        $measurementID = -1;
        $userID = -1;
    
        try {
            $db = Database::getDB();
            
            if (func_num_args() < 1)
                throw new PDOException('WeightMeasurementsDB.addMeasurement: arguments expected');
            
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
                "insert into WeightMeasurements (weight, dateAndTime, notes, userID)
                values (:weight, :dateAndTime, :notes, :userID)"
            );
            $stmt->execute(array(
                ":weight" => $measurement->getMeasurement(),
                ":dateAndTime" => $measurement->getDateTime()->format("Y-m-d H:i"),
                ":notes" => $measurement->getNotes(),
                ":userID" => $userID
            ));
            $measurementID = $db->lastInsertId("weightID");
    
        } catch (PDOException $e) {
            $measurement->setError('weightMeasurementsDB', 'ADD_MEASUREMENT_FAILED');
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
                            "update WeightMeasurements set $key = :value
                            where userID in
                                (select userID from Users where userName = :userName)
                            and dateAndTime = :dateAndTime");
                        $stmt->execute(array(
                            ":value" => $value,
                            "userName" => $newParams['userName'],
                            ":dateAndTime" => $dateAndTime
                        ));
                    }
                    
                    if ($key === 'dateAndTime')
                        $dateAndTime = $value;
            }
    
        } catch (PDOException $e) {
            $newMeasurement->setError('weightMeasurementsDB', 'EDIT_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $newMeasurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
        
        return $newMeasurement;
    }
    
    // returns an array of all WeightMeasurement objects found in the database
    public static function getAllMeasurements() {
        $allMeasurements = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, weightID, weight, dateAndTime, notes, userID
                from Users join WeightMeasurements using (userID)");
            $stmt->execute();
            $str = '';
            foreach ($stmt as $row) {
                $measurement = new WeightMeasurement($row);
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
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, weightID, weight, dateAndTime, notes, userID
                from Users join WeightMeasurements using (userID)
                where userName = :userName and dateAndTime = :dateAndTime"
            );
            $stmt->execute(array(":userName" => $userName, ":dateAndTime" => $dateAndTime));
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $measurement = new WeightMeasurement($row);
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $measurement;
    }
    
    // returns an array of WeightMeasurement objects, sorted by date
    public static function getMeasurementsBy($type, $value, $order = 'desc') {
        $allowedOrders = array('asc', 'desc');
        $allowedTypes = array('userName', 'userID');
        $measurementsArray = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new Exception("$order is not an allowed order");
            if (!in_array($type, $allowedTypes))
                throw new PDOException("$type not allowed search criterion for weight measurement");
            
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, weightID, weight, dateAndTime, notes, userID
                from Users join WeightMeasurements using (userID)
                where ($type = :$type)
                order by dateAndTime $order");
            $stmt->execute(array(":$type" => $value));
            
            $str = '';
            foreach ($stmt as $row) {
                $measurement = new WeightMeasurement($row);
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
}
?>