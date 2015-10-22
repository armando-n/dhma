<?php
class BloodPressureMeasurementsDB {
    
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