<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for Profile Controller</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>
<h1>Profile controller tests</h1>

<?php
include_once("../controllers/ProfileController.class.php");
include_once("../models/User.class.php");
include_once("../models/UserData.class.php");
include_once("../models/Messages.class.php");
include_once("../views/ProfileView.class.php");
include_once("../views/HomeView.class.php");
include_once("../views/HeaderView.class.php");
include_once("../views/FooterView.class.php");
include_once("../resources/Utilities.class.php");
?>

<h2>It should call the run method for valid input and display the Profile view</h2>
<?php
// dummy data, so profile page can be viewed correctly (no session support yet)
$validUserInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
);
$validUserDataInput = array(
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
$user = new User($validUserInput);
$uData = new UserData($validUserDataInput);
ProfileController::run($user, $uData, false);
?>

<h2>It should call the run method for valid input and show the Edit Profile form</h2>
<?php
ProfileController::run($user, $uData, true);
?>

<h2>It should call the run method for valid post input and display the Profile view</h2>
<?php
$_SERVER ["REQUEST_METHOD"] = "POST";
$_POST = array (
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
        "keep-logged-in" => "on",
);
ProfileController::run(null, null, true);
?>

<h2>It should call the run method for invalid post input and display the Edit Profile form with error messages</h2>
<?php
$_SERVER ["REQUEST_METHOD"] = "POST";
$_POST = array(
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
ProfileController::run(null, null, true);
?>

<h2>It should call the run method for empty input and only show an error message</h2>
<?php 
ProfileController::run();
?>

</body>
</html>
