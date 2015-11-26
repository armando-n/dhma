<?php

$numOfInserts = 6;

$str = '';
for ($i = 1; $i <= 10; $i++)
    $str = $str . generateMeasurements($i);

$outfile = fopen("measurement_inserts.sql", "w");
fwrite($outfile, $str);
fclose($outfile);

function generateMeasurements($userID) {
    $str = generateGlucose($userID) . " ";
    $str = $str . generateBloodPressure($userID) . " ";
    $str = $str . generateCalories($userID) . " ";
    $str = $str . generateExercise($userID) . " ";
    $str = $str . generateSleep($userID) . " ";
    $str = $str . generateWeight($userID) . "\n";
    
    return $str;
}

function generateGlucose($userID) {
    global $numOfInserts;
    $day = new DateTime();
    $day->modify('-1 day');
    
    $str = "insert into GlucoseMeasurements (glucose, dateAndTime, notes, userID) values ";
    for ($i = 0; $i < $numOfInserts; $i++, $day->modify('-1 day')) {
        if ($i !== 0)
            $str = $str . ", ";
        $str = $str .
            "(" .rand(95, 115).
            ', "' .$day->format('Y'). '-' .$day->format('m'). '-' .$day->format('d'). ' ' .generateTime().
            '", null, ' .$userID. ')';
    }
    $str = $str . ';';
    
    return $str;
}

function generateBloodPressure($userID) {
    global $numOfInserts;
    $day = new DateTime();
    $day->modify('-1 day');

    $str = "insert into BloodPressureMeasurements (systolicPressure, diastolicPressure, dateAndTime, notes, userID) values ";
    for ($i = 0; $i < $numOfInserts; $i++, $day->modify('-1 day')) {
        if ($i !== 0)
            $str = $str . ", ";
        $str = $str .
        "(" .rand(105, 125). ', ' .rand(80, 95).
        ', "' .$day->format('Y'). '-' .$day->format('m'). '-' .$day->format('d'). ' ' .generateTime().
        '", null, ' .$userID. ')';
    }
    $str = $str . ';';

    return $str;
}

function generateCalories($userID) {
    global $numOfInserts;
    $day = new DateTime();
    $day->modify('-1 day');

    $str = "insert into CalorieMeasurements (calories, dateAndTime, notes, userID) values ";
    for ($i = 0; $i < $numOfInserts; $i++, $day->modify('-1 day')) {
        if ($i !== 0)
            $str = $str . ", ";
        $str = $str .
        "(" .rand(1100, 1700).
        ', "' .$day->format('Y'). '-' .$day->format('m'). '-' .$day->format('d'). ' ' .generateTime().
        '", null, ' .$userID. ')';
    }
    $str = $str . ';';

    return $str;
}

function generateExercise($userID) {
    global $numOfInserts;
    $day = new DateTime();
    $day->modify('-1 day');
    $types = array('running', 'running', 'weights');
    
    $str = "insert into ExerciseMeasurements (duration, type, dateAndTime, notes, userID) values ";
    for ($i = 0; $i < $numOfInserts; $i++, $day->modify('-1 day')) {
        if ($i !== 0)
            $str = $str . ", ";
        $str = $str .
        "(" .rand(15, 60). ', "' .$types[rand(0,2)]. '"' .
        ', "' .$day->format('Y'). '-' .$day->format('m'). '-' .$day->format('d'). ' ' .generateTime().
        '", null, ' .$userID. ')';
    }
    $str = $str . ';';
    
    return $str;
}

function generateSleep($userID) {
    global $numOfInserts;
    $day = new DateTime();
    $day->modify('-1 day');

    $str = "insert into SleepMeasurements (duration, dateAndTime, notes, userID) values ";
    for ($i = 0; $i < $numOfInserts; $i++, $day->modify('-1 day')) {
        if ($i !== 0)
            $str = $str . ", ";
            $str = $str .
            "(" .rand(360, 540).
            ', "' .$day->format('Y'). '-' .$day->format('m'). '-' .$day->format('d'). ' ' .generateTime().
            '", null, ' .$userID. ')';
    }
    $str = $str . ';';

    return $str;
}

function generateWeight($userID) {
    global $numOfInserts;
    $day = new DateTime();
    $day->modify('-1 day');

    $str = "insert into WeightMeasurements (weight, dateAndTime, notes, userID) values ";
    for ($i = 0; $i < $numOfInserts; $i++, $day->modify('-1 day')) {
        if ($i !== 0)
            $str = $str . ", ";
            $str = $str .
            "(" .rand(140, 148).
            ', "' .$day->format('Y'). '-' .$day->format('m'). '-' .$day->format('d'). ' ' .generateTime().
            '", null, ' .$userID. ')';
    }
    $str = $str . ';';

    return $str;
}

function generateTime() {
    $hour = rand(0,23);
    $min = rand(0,59);
    if ($hour < 10)
        $hour = '0' . $hour;
    if($min < 10)
        $min = '0' . $min;
    return '' .$hour. ':' .$min;
}

?>