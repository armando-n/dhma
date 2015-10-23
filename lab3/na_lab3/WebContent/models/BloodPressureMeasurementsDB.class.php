<?php
class BloodPressureMeasurementsDB {
    
    public static function addMeasurement($measurement, $userID) {
        $measurementID = -1;
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "insert into BloodPressureMeasurements (systolicPressure,
                    diastolicPressure, dateAndTime, notes, userID)
                values (:systolicPressure, :diastolicPressure, :dateAndTime,
                    :notes, :userID)"
            );
            $stmt->execute(array(
                ":systolicPressure" => $measurement->getSystolicPressure(),
                ":diastolicPressure" => $measurement->getDiastolicPressure(),
                ":dateAndTime" => $measurement->getDateTime()->format("Y-m-d H:i"),
                ":notes" => $measurement->getNotes(),
                ":userID" => $userID
            ));
            $measurementID = $db->lastInsertId("bpID");
            
        } catch (PDOException $e) {
            $measurement->setError('bloodPressureMeasurementsDB', 'ADD_MEASUREMENT_FAILED');
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
                                "update BloodPressureMeasurements set $key = :value
                                    where userID in
                                (select userID from Users where userName = :userName)");
                        $stmt->execute(array(
                            ":value" => $value,
                            "userName" => $newParams['userName']
                        ));
                    }
            }
        
        } catch (PDOException $e) {
            $measurement->setError('bloodPressureMeasurementsDB', 'EDIT_MEASUREMENT_FAILED');
        } catch (RuntimeException $e) {
            $measurement->setError('database', 'DB_CONFIG_NOT_FOUND');
        }
    }
    
    // returns an array of all BloodPressureMeasurement objects found in the database
    public static function getAllMeasurements() {
        $allMeasurements = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, bpID, systolicPressure, diastolicPressure,
                    dateAndTime, notes, userID
                from Users join BloodPressureMeasurements using (userID)");
            $stmt->execute();
            $str = '';
            foreach ($stmt as $row) {
                $bp = new BloodPressureMeasurement($row);
                if (!is_object($bp) || $bp->getErrorCount() > 0)
                    throw new PDOException('Failed to create valid blood pressure measurement:\n' . $str);
                
                $allMeasurements[] = $bp;
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $allMeasurements;
    }
    
    // returns an array of BloodPressureMeasurement objects, sorted by date
    public static function getMeasurementsBy($type, $value, $order = 'desc') {
        $allowedOrders = array('asc', 'desc');
        $allowedTypes = array('userName', 'userID');
        $measurements = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new Exception("$order is not an allowed order");
            if (!in_array($type, $allowedTypes))
                throw new PDOException("$type not allowed search criterion for blood pressure measurement");
            
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userName, bpID, systolicPressure, diastolicPressure,
                    dateAndTime, notes, userID
                from Users join BloodPressureMeasurements using (userID)
                where ($type = :$type)
                order by dateAndTime $order");
            $stmt->execute(array(":$type" => $value));
            
            $str = '';
            foreach ($stmt as $row) {
                $bp = new BloodPressureMeasurement($row);
                if (!is_object($bp) || $bp->getErrorCount() > 0)
                    throw new PDOException('Failed to create valid blood pressure measurement:\n' . $str);
    
                $measurements[] = $bp;
            }
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $measurements;
    }
}
?>