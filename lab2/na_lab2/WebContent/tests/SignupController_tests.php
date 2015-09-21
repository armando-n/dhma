<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for Signup Controller</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>
<h1>Signup controller tests</h1>

<?php
include_once("../controllers/SignupController.class.php");
include_once("../models/User.class.php");
include_once("../models/UserData.class.php");
include_once("../views/SignupView.class.php");
include_once("../views/HomeView.class.php");
include_once("../views/HeaderView.class.php");
include_once("../views/FooterView.class.php");
?>

<h2>It should call the run method for invalid input and display the Signup view</h2>
<?php 
$_SERVER ["REQUEST_METHOD"] = "POST";
$_POST = array(
        "userName" => "Some-Really-Long-User-Name",
        "password1" => "password123",
        "password2" => "password",
        "fname" => "Some-Really-Really-Long-First-Name",
        "lname" => "Some-Really-Really-Long-Last-Name",
        "email" => "invalid-email-address",
        "gender" => "femail",
        "phone" => "28112562346134",
        "facebook" => "http://google.com",
        "dob" => "19831102",
        "country" => "United States of America",
        "theme" => "medium",
        "color" => "00008b",
        "picture" => "someimage",
        "public-profile" => "on",
        "showpic" => "on",
        "reminders" => "on",
        "keep-logged-in" => "on"
);
SignupController::run();
?>
</body>
</html>
