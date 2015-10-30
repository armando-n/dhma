<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Basic tests for Login Controller</title>
</head>
<body>
<h1>Measurements controller tests</h1>

<?php
include_once("../controllers/MeasurementsController.class.php");
include_once("../models/GenericModelObject.class.php");
include_once("../models/User.class.php");
include_once("../models/UserProfile.class.php");
include_once("../models/GlucoseMeasurement.class.php");
include_once("../models/BloodPressureMeasurement.class.php");
include_once("../models/CalorieMeasurement.class.php");
include_once("../models/ExerciseMeasurement.class.php");
include_once("../models/SleepMeasurement.class.php");
include_once("../models/WeightMeasurement.class.php");
include_once("../models/Messages.class.php");
include_once("../views/MeasurementsView.class.php");
include_once("../views/HeaderView.class.php");
include_once("../views/FooterView.class.php");
?>

<h2>It should call the run method and display the Past Measurements view without crashing</h2>
<?php
MeasurementsController::run();
?>



</body>
</html>
