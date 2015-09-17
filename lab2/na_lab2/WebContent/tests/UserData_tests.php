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

// test invalid input
// more tests here

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
<?= $test1 ?><br />
<?= $test2 ?><br />
The object is: <pre><?= $validUserData ?></pre>

<!-- output: parameter extraction test -->
<h2>It should extract the parameters that went in</h2>
<pre><?php print_r($params); ?></pre>

<!-- output: invalid input test -->
