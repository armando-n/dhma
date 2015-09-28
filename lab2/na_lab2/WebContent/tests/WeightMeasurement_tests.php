<?php
include_once("../models/WeightMeasurement.class.php");
include_once("../models/Messages.class.php");
include_once("../resources/Utilities.class.php");

$validInput = array(
        "userName" => "armando-n",
        "date" => "2015-09-27",
        "time" => "17:22",
        "units" => "lb",
        "weight" => "140.5"
);

$emptyInputValues = array(
        "userName" => "",
        "date" => "",
        "time" => "",
        "units" => "",
        "weight" => ""
);

$invalidInputValues = array(
        "userName" => "Invalid#User",
        "date" => "201-12-56",
        "time" => "42:21",
        "units" => "metric",
        "weight" => "180kg"
);

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Basic tests for WeightMeasurement class</title>
</head>
<body>

<h1>WeightMeasurement class tests</h1>

<!-- output: WeightMeasurement object creation test -->
<h2>It should create a valid WeightMeasurement object when all input is provided</h2>
<?php
$validMeasurement = new WeightMeasurement($validInput);
$test1 = is_object($validMeasurement) ? '' : 'Failed: It should create a valid object when valid input is provided';
$test2 = (empty($validMeasurement->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';
?>
<?= $test1 ?> <?php if (!empty($test1)) {?><br /><?php ; }?>
<?= $test2 ?> <?php if (!empty($test2)) {?><br /><?php ; }?>
The object is: <pre><?= $validMeasurement?></pre>

<!-- output: parameter extraction test -->
<h2>It should extract the parameters that went in</h2>
<pre><?php print_r($validMeasurement->getParameters()); ?></pre>

<!-- output: get methods -->
<h2>It should call the get methods of a valid WeightMeasurement object and display its attribute values</h2>
Errors: <?php print_r($validMeasurement->getErrors()); ?><br />
Error Count: <?=$validMeasurement->getErrorCount()?><br />
User Name: <?=$validMeasurement->getUserName()?><br />
DateTime: <pre><?php print_r($validMeasurement->getDateTime()); ?></pre><br />
Date: <?=$validMeasurement->getDate()?><br />
Time: <?=$validMeasurement->getTime()?><br />
Units: <?=$validMeasurement->getUnits()?><br />
Weight: <?=$validMeasurement->getMeasurement()?>

<!-- output: null input test -->
<h2>It should create an empty WeightMeasurement object when null is passed to the constructor</h2>
<?php
$invalidMeasurement1 = new WeightMeasurement(null);
$test3 = (is_object($invalidMeasurement1)) ? '' : 'Failed: It should create an empty object when null input is provided';
$test4 = (empty($invalidMeasurement1->getErrors())) ? '' : 'Failed: It should not have errors when null input is provided';
?>
<?= $test3 ?> <?php if (!empty($test3)) {?><br /><?php ; }?>
<?= $test4 ?> <?php if (!empty($test4)) {?><br /><?php ; }?>
The object is:
<pre><?= $invalidMeasurement1 ?></pre>

<!-- output: empty input values test -->
<h2>It should have errors when the WeightMeasurement constructor is passed empty property values</h2>
<?php
$invalidMeasurement2 = new WeightMeasurement($emptyInputValues);
$test5 = (is_object($invalidMeasurement2)) ? '' : 'Failed: it should have created an empty object when empty input values are provided';
$test6 = (empty($invalidMeasurement2->getErrors())) ? 'Failed: There should have been errors when empty input values are provided' : '';
?>
<?= $test5 ?> <?php if (!empty($test5)) {?><br /><?php ; }?>
<?= $test6 ?> <?php if (!empty($test6)) {?><br /><?php ; }?>
The errors are: <pre><?php print_r($invalidMeasurement2->getErrors()); ?></pre>
The object is:
<pre><?= $invalidMeasurement2 ?></pre>

<!--  output: invalid input values test -->
<h2>It should have errors and create an invalid WeightMeasurement object when invalid input is provided</h2>
<?php
$invalidMeasurement3 = new WeightMeasurement($invalidInputValues);
$test7 = (is_object($invalidMeasurement3)) ? '' : 'Failed: it should have created an invalid object when invalid input values are provided';
$test8 = (empty($invalidMeasurement3)) ? 'Failed: There should have been errors when invalid input values are provided' : '';
?>
<?= $test7 ?> <?php if (!empty($test7)) {?><br /><?php ; }?>
<?= $test8 ?> <?php if (!empty($test8)) {?><br /><?php ; }?>
The errors are: <pre><?php print_r($invalidMeasurement3->getErrors()); ?></pre>
The object is:
<pre><?= $invalidMeasurement3 ?></pre>

</body>
</html>
