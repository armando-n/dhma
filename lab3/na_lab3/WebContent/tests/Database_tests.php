<?php
include_once("../models/Database.class.php");
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Basic tests for CalorieMeasurement class</title>
</head>
<body>

<h1>Tests for Database class</h1>

<h2>It should show an error when the wrong database name is provided</h2>
<?php $db = Database::getDB('wrongname'); ?>

<h2>It should show an error when a config file is provided with the wrong username in the config</h2>
<?php $db = Database::getDB('dhma', 'wrongNameConfig.ini'); ?>

<h2>It should show an error when config file is provided with the wrong password in the config</h2>
<?php $db = Database::getDB('dhma', 'wrongPassConfig.ini'); ?>

<h2>It should create a database object with no errors the first time called</h2>
<?php
$db = Database::getDB();
if (!isset($db) || is_null($db)) echo "Failed: database connection was not opened";
else echo "Success: a database object was created";
?>

<h2>It should not open another connection if called again</h2>
<?php
$db2 = Database::getDB();
if ($db != $db2) echo "Failed: a new connection was opened";
else echo "Success: the original connection was returned";
?>

<h2>It should not open another connection if called another time</h2>
<?php $db3 = Database::getDB();
if ($db3 != $db2 || $db3 != $db) echo "Failed: a new connection was opened";
else echo "Success: the original connection was returned";
?>

</body>
</html>
