<?php
class PastMeasurementsController {
    
    private static $measurementData;
    
    public static function run() {
        
        PastMeasurementsController::initData();
        PastMeasurementsView::show(PastMeasurementsController::$measurementData);
    }
    
    public static function initData() {
        $glucoseMeasurements = array();
        $bloodPressureMeasurements = array();
        $calorieMeasurements = array();
        $exerciseMeasurements = array();
        $sleepMeasurements = array();
        $weightMeasurements = array();
    
        $userInput = array(
                "userName" => "armando-n",
                "password1" => "password123",
                "password2" => "password123"
        );
        $user = new User($userInput);
        $uData = new UserProfile(null);
    
        $glucoseInput1 = array(
                "userName" => "armando-n",
                "date" => "2015-09-27",
                "time" => "8:15",
                "units" => "mg/dL",
                "glucose" => "95"
        );
        $glucoseInput2 = array(
                "userName" => "armando-n",
                "date" => "2015-09-26",
                "time" => "8:22",
                "units" => "mg/dL",
                "glucose" => "120"
        );
        $glucoseInput3 = array(
                "userName" => "armando-n",
                "date" => "2015-09-25",
                "time" => "8:15",
                "units" => "mg/dL",
                "glucose" => "110"
        );
        $glucoseInput4 = array(
                "userName" => "armando-n",
                "date" => "2015-09-24",
                "time" => "8:12",
                "units" => "mg/dL",
                "glucose" => "112"
        );
        $glucoseMeasurements[] = new GlucoseMeasurement($glucoseInput1);
        $glucoseMeasurements[] = new GlucoseMeasurement($glucoseInput2);
        $glucoseMeasurements[] = new GlucoseMeasurement($glucoseInput3);
        $glucoseMeasurements[] = new GlucoseMeasurement($glucoseInput4);
    
        $bloodPressureInput1 = array(
                "userName" => "armando-n",
                "date" => "2015-09-27",
                "time" => "14:00",
                "systolic" => "120",
                "diastolic" => "80"
        );
        $bloodPressureInput2 = array(
                "userName" => "armando-n",
                "date" => "2015-09-26",
                "time" => "14:05",
                "systolic" => "110",
                "diastolic" => "90"
        );
        $bloodPressureInput3 = array(
                "userName" => "armando-n",
                "date" => "2015-09-25",
                "time" => "14:02",
                "systolic" => "115",
                "diastolic" => "95"
        );
        $bloodPressureInput4 = array(
                "userName" => "armando-n",
                "date" => "2015-09-24",
                "time" => "14:00",
                "systolic" => "125",
                "diastolic" => "78"
        );
        $bloodPressureMeasurements[] = new BloodPressureMeasurement($bloodPressureInput1);
        $bloodPressureMeasurements[] = new BloodPressureMeasurement($bloodPressureInput2);
        $bloodPressureMeasurements[] = new BloodPressureMeasurement($bloodPressureInput3);
        $bloodPressureMeasurements[] = new BloodPressureMeasurement($bloodPressureInput4);
    
        $calorieInput1 = array(
                "userName" => "armando-n",
                "date" => "2015-09-27",
                "time" => "21:00",
                "calories" => "1800"
        );
        $calorieInput2 = array(
                "userName" => "armando-n",
                "date" => "2015-09-26",
                "time" => "21:03",
                "calories" => "1540"
        );
        $calorieInput3 = array(
                "userName" => "armando-n",
                "date" => "2015-09-25",
                "time" => "21:01",
                "calories" => "1620"
        );
        $calorieInput4 = array(
                "userName" => "armando-n",
                "date" => "2015-09-24",
                "time" => "21:00",
                "calories" => "1460"
        );
        $calorieMeasurements[] = new CalorieMeasurement($calorieInput1);
        $calorieMeasurements[] = new CalorieMeasurement($calorieInput2);
        $calorieMeasurements[] = new CalorieMeasurement($calorieInput3);
        $calorieMeasurements[] = new CalorieMeasurement($calorieInput4);
    
        $exerciseInput1 = array(
                "userName" => "armando-n",
                "date" => "2015-09-27",
                "time" => "20:00",
                "type" => "running",
                "duration" => "60"
        );
        $exerciseInput2 = array(
                "userName" => "armando-n",
                "date" => "2015-09-26",
                "time" => "20:02",
                "type" => "running",
                "duration" => "56"
        );
        $exerciseInput3 = array(
                "userName" => "armando-n",
                "date" => "2015-09-25",
                "time" => "20:05",
                "type" => "running",
                "duration" => "40"
        );
        $exerciseInput4 = array(
                "userName" => "armando-n",
                "date" => "2015-09-24",
                "time" => "20:0",
                "type" => "running",
                "duration" => "58"
        );
        $exerciseMeasurements[] = new ExerciseMeasurement($exerciseInput1);
        $exerciseMeasurements[] = new ExerciseMeasurement($exerciseInput2);
        $exerciseMeasurements[] = new ExerciseMeasurement($exerciseInput3);
        $exerciseMeasurements[] = new ExerciseMeasurement($exerciseInput4);
    
        $sleepInput1 = array(
                "userName" => "armando-n",
                "date" => "2015-09-27",
                "time" => "22:00",
                "duration" => "480"
        );
        $sleepInput2 = array(
                "userName" => "armando-n",
                "date" => "2015-09-26",
                "time" => "22:12",
                "duration" => "460"
        );
        $sleepInput3 = array(
                "userName" => "armando-n",
                "date" => "2015-09-25",
                "time" => "22:35",
                "duration" => "464"
        );
        $sleepInput4 = array(
                "userName" => "armando-n",
                "date" => "2015-09-24",
                "time" => "22:02",
                "duration" => "472"
        );
        $sleepMeasurements[] = new SleepMeasurement($sleepInput1);
        $sleepMeasurements[] = new SleepMeasurement($sleepInput2);
        $sleepMeasurements[] = new SleepMeasurement($sleepInput3);
        $sleepMeasurements[] = new SleepMeasurement($sleepInput4);
    
        $weightInput1 = array(
                "userName" => "armando-n",
                "date" => "2015-09-27",
                "time" => "20:45",
                "units" => "lb",
                "weight" => "140.5"
        );
        $weightInput2 = array(
                "userName" => "armando-n",
                "date" => "2015-09-26",
                "time" => "20:50",
                "units" => "lb",
                "weight" => "139.5"
        );
        $weightInput3 = array(
                "userName" => "armando-n",
                "date" => "2015-09-25",
                "time" => "20:28",
                "units" => "lb",
                "weight" => "140"
        );
        $weightInput4 = array(
                "userName" => "armando-n",
                "date" => "2015-09-24",
                "time" => "20:46",
                "units" => "lb",
                "weight" => "141"
        );
        $weightMeasurements[] = new WeightMeasurement($weightInput1);
        $weightMeasurements[] = new WeightMeasurement($weightInput2);
        $weightMeasurements[] = new WeightMeasurement($weightInput3);
        $weightMeasurements[] = new WeightMeasurement($weightInput4);
        
        PastMeasurementsController::$measurementData["glucose"] = $glucoseMeasurements;
        PastMeasurementsController::$measurementData["bloodPressure"] = $bloodPressureMeasurements;
        PastMeasurementsController::$measurementData["calories"] = $calorieMeasurements;
        PastMeasurementsController::$measurementData["exercise"] = $exerciseMeasurements;
        PastMeasurementsController::$measurementData["sleep"] = $sleepMeasurements;
        PastMeasurementsController::$measurementData["weight"] = $weightMeasurements;
    }
}
?>