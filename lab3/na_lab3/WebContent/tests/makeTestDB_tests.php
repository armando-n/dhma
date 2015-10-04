<?php
include_once("../models/Database.class.php");
include_once("./makeTestDB.php");
?><!DOCTYPE html>
<html>
<head>
    <title>Tests for makeTestDB function</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>makeTestDB test</h1>

<h2>It should create a test database for a particular name</h2>
<?php $testDB = makeTestDB('dhma_testDB'); ?>
    
</body>
</html>