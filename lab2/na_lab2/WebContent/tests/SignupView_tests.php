<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for SignupView</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>SignupView tests</h1>

<h2>It should call show and display the view without crashing</h2>
<?php
include_once("../views/SignupView.class.php");
SignupView::showBody();
?>

<h2>It should call show and display the view with most fields filled in and some error messages</h2>
<?php
include_once("../models/User.class.php");
include_once("../models/UserData.class.php");
$invalidUserInput = array(
        "userName" => "A-Really-Long-User-Name",
        "password1" => "password123",
        "password2" => "password"
);
$invalidDataInput = array(
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
$user = new User($invalidUserInput);
$uData= new UserData($invalidDataInput);
SignupView::showBody($user, $uData);
?>
    
</body>
</html>