<?php
include_once("../models/UserData.class.php");

// test valid UserData object creation
$validInput = array(
        "fname" => "Armando",
        "lname" => "Navarro",
        "email" => "fdf786@my.utsa.edu",
        "gender" => "male",
        "phone" => "281-555-2180",
        "facebook" => "http://facebook.com/someguy210",
        "dob" => "1983-11-02",
        "country" => "United States of America",
        "theme" => "dark",
        "color" => "#00008b",
        "picture" => "someimage",
        "public-profile" => "on",
        "showpic" => "on",
        "reminders" => "on",
        "keep-logged-in" => "on"
);
$validUserData = new UserData($validInput);
$test1 = is_object($validUserData) ? '' : 'Failed: It should create a valid object when valid input is provided';
$test2 = (empty($validUserData->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

// test parameter extraction
$params = $validUserData->getParameters();

// test null input
$emptyUserData1 = new UserData(null);
$test3 = is_object($emptyUserData1) ? '' : 'Failed: It should create a valid object with empty property values when valid input is provided';
$test4 = (empty($emptyUserData1->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

// test empty input values
$emptyInputValues = array(
        "fname" => "",
        "lname" => "",
        "email" => "",
        "gender" => "",
        "phone" => "",
        "facebook" => "",
        "dob" => "",
        "country" => "",
        "theme" => "",
        "color" => "",
        "picture" => "",
        "public-profile" => "",
        "showpic" => "",
        "reminders" => "",
        "keep-logged-in" => ""
);
$emptyUserData2 = new UserData($emptyInputValues);
$test5 = is_object($emptyUserData2) ? '' : 'Failed: It should create a valid object with empty property values when valid input is provided';
$test6 = (empty($emptyUserData2->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Basic tests for UserData class</title>
</head>
<body>

<h1>UserData class tests</h1>

<!-- output: user object creation test -->
<h2>It should create a valid UserData object when all input is provided</h2>
<?= $test1 ?> <?php if (!empty($test1)) {?><br /><?php ; }?>
<?= $test2 ?> <?php if (!empty($test2)) {?><br /><?php ; }?>
The object is: <pre><?= $validUserData ?></pre>

<!-- output: parameter extraction test -->
<h2>It should extract the parameters that went in</h2>
<pre><?php print_r($params); ?></pre>

<!-- output: null input test -->
<h2>It should create a valid UserData object with empty property values; Theme, accent color, and boolean properties will be set to default values</h2>
<?= $test3 ?> <?php if (!empty($test3)) {?><br /><?php ; }?>
<?= $test4 ?> <?php if (!empty($test4)) {?><br /><?php ; }?>
The object is:
<pre><?= $emptyUserData1 ?></pre>

<!-- output: empty input values test -->
<h2>It should create a valid UserData object with mostly empty property values; Theme, accent color, and boolean properties will be set to default values</h2>
<?= $test5 ?> <?php if (!empty($test5)) {?><br /><?php ; }?>
<?= $test6 ?> <?php if (!empty($test6)) {?><br /><?php ; }?>
The object is:
<pre><?= $emptyUserData2 ?></pre>