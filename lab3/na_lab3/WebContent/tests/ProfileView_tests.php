<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for ProfileView</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>ProfileView test</h1>

<h2>It should call showProfile and show without crashing</h2>
<?php
include_once("../views/ProfileView.class.php");
include_once("../models/UserData.class.php");
include_once("../models/User.class.php");
include_once("../models/Messages.class.php");
include_once("../resources/Utilities.class.php");

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
$userData = new UserData($validUserDataInput);
ProfileView::showProfile($user, $userData);
?>
    
<h2>It should call showEditForm and show without crashing</h2>
<?php 
ProfileView::showEditForm($user,$userData, true);
?>

<h2>It should call showEditForm and show error messages next to some fields</h2>
<?php 
$invalidUserDataInput = array(
        "fname" => "Some-Really-Really-Super-Duper-Long-Name",
        "lname" => '$InvaldName',
        "email" => "fdf786",
        "gender" => "orange",
        "phone" => "281-555-218x",
        "facebook" => "http://face.com/someguy210",
        "dob" => "1983-11-0x",
        "country" => "United States of America",
        "theme" => "middle",
        "color" => "#00008s",
        "picture" => "someimage",
        "public-profile" => "on",
        "showpic" => "on",
        "reminders" => "on",
        "keep-logged-in" => "on"
);
$userData2 = new UserData($invalidUserDataInput);
ProfileView::showEditForm($user, $userData2, true);
?>

</body>
</html>
