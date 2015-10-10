<?php
class BloodPressureMeasurementsDB {
    
    // returns an array of all BloodPressureMeasurement objects found in the database
    public static function getAllMeasurements($dbName = null, $configFile = null) {
        $allMeasurements = array();
        
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare("select * from BloodPressureMeasurementsView");
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
        }
        
        return $allMeasurements;
    }
}
?>