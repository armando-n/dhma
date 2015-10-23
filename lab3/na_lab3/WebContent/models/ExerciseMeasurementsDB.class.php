<?php
class ExerciseMeasurementsDB {
    
    public static function addMeasurement($measurement, $userID) {
        $measurementID = -1;
    
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "insert into ExerciseMeasurements (duration,
                    type, dateAndTime, notes, userID)
                values (:duration, :type, :dateAndTime,
                    :notes, :userID)"
            );
            $stmt->execute(array(
                ":duration" => $measurement->getDuration(),
                ":type" => $measurement->getType(),
                ":dateAndTime" => $measurement->getDateTime()->format("Y-m-d H:i"),
                ":notes" => $measurement->getNotes(),
                ":userID" => $userID
            ));
            $measurementID = $db->lastInsertId("exerciseID");
    
        } catch (PDOException $e) {
            $measurement->setError('exerciseMeasurementsDB', 'ADD_MEASUREMENT_FAILED');
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
    
            foreach ($newParams as $key => $value) {
    
                if (!array_key_exists($key, $oldParams))
                    throw new PDOException('Key ' . htmlspecialchars($key) . ' is invalid');
    
                    if ($oldParams[$key] !== $newParams[$key]) {
                        $stmt = $db->prepare(
                            "update ExerciseMeasurements set $key = :value
                            where userID in
                                (select userID from Users where userName = :userName)");
                        $stmt->execute(array(
                            ":value" => $value,
                            "userName" => $newParams['userName']
                        ));
                    }
            }
    
        } catch (PDOException $e) {
            $measurement->setError('exerciseMeasurementsDB', 'EDIT_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $measurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
    }
    
    // returns an array of all ExerciseMeasurement objects found in the database
    public static function getAllMeasurements() {
        $allMeasurements = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, exerciseID, duration, type, dateAndTime, notes, userID
                from Users join ExerciseMeasurements using (userID)");
            $stmt->execute();

            foreach ($stmt as $row) {
                $measurement = new ExerciseMeasurement($row);
                if (!is_object($measurement) || $measurement->getErrorCount() > 0)
                    throw new PDOException('Failed to create valid exercise measurement:\n' . array_shift($measurement->getErrors()));
                
                $allMeasurements[] = $measurement;
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $allMeasurements;
    }
    
    // returns an array of ExerciseMeasurement objects, sorted by date
    public static function getMeasurementsBy($type, $value, $order = 'desc') {
        $allowedOrders = array('asc', 'desc');
        $allowedTypes = array('userName', 'userID');
        $measurementsArray = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new Exception("$order is not an allowed order");
            if (!in_array($type, $allowedTypes))
                throw new PDOException("$type not allowed search criterion for exercise measurement");
            
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, exerciseID, duration, type, dateAndTime, notes, userID
                from Users join ExerciseMeasurements using (userID)
                where ($type = :$type)
                order by dateAndTime $order");
            $stmt->execute(array(":$type" => $value));
            
            foreach ($stmt as $row) {
                $measurement = new ExerciseMeasurement($row);
                if (!is_object($measurement) || $measurement->getErrorCount() > 0)
                    throw new PDOException('Failed to create valid exercise measurement:\n' . array_shift($measurement->getErrors()));
    
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