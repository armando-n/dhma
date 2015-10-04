<?php
include_once("../models/UserProfilesDB.class.php");
include_once("../models/UserProfile.class.php");
include_once("../models/Database.class.php");
include_once("../models/Messages.class.php");
include_once("../resources/Utilities.class.php");
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Tests for UserProfileDB object</title>
</head>
<body>
<h1>UserProfileDB tests</h1>

<h2>It should call getAllUserProfiles and return a valid array of UserProfile objects</h2>
<?php 
$userProfiles = UserProfilesDB::getAllUserProfilesTest();
foreach ($userProfiles as $profile) {
    ?><pre><?=$profile?></pre><?php
}
?>

<h2>It should call addUserProfile and return the ID of the new UserProfile</h2>
<?php
$db = Database::getDB('dhma_testDB');
$stmt = $db->prepare("delete from UserProfiles where firstName = 'Elizabeth' and lastName = 'Jones'");
$stmt->execute();
$stmt->closeCursor();
$input = array(
    "firstName" => "Elizabeth",
    "lastName" => "Jones",
    "email" => "liz@email.com",
    "gender" => "female",
    "phone" => "210-555-5384",
    "facebook" => "http://facebook.com/liz",
    "dob" => "1982-06-17",
    "country" => "United States of America",
    "theme" => "dark",
    "accentColor" => "#04f08b",
    "picture" => "lizimage",
    "isProfilePublic" => "on",
    "isPicturePublic" => "on",
    "userName" => "lizzy426"
);
$uProfile = new UserProfile($input);
$profileID = UserProfilesDB::addUserProfileTest($uProfile, 6);
?>
The new ID is: <?=$profileID?>

<h2>It should call getUserProfileBy and return a UserProfile object for the specified profile ID</h2>
<?php
$uProfile = UserProfilesDB::getUserProfileByTest('profileID', 4);
if (is_object($uProfile)) {
    ?>The retrieved UserProfile object with profile ID of 4 is:<br />
    <pre><?=$uProfile?></pre><?php
} else {
    ?>Failed: invalid object returned<?php
}
?>

<h2>It should call getUserProfileBy and return a UserProfile object for the specified e-mail</h2>
<?php
$uProfile = UserProfilesDB::getUserProfileByTest('email', 'robin@email.com');
if (is_object($uProfile)) {
    ?>The retrieved UserProfile object with e-mail address robin@email.com is:<br />
    <pre><?=$uProfile?></pre><?php
} else {
    ?>Failed: invalid object returned<?php
}
?>

<h2>It should call getUserProfileBy and return a UserProfile object for the specified user ID</h2>
<?php
$uProfile = UserProfilesDB::getUserProfileByTest('userID', 3);
if (is_object($uProfile)) {
    ?>The retrieved UserProfile object with user ID of 3 is:<br />
    <pre><?=$uProfile?></pre><?php
} else {
    ?>Failed: invalid object returned<?php
}
?>

</body>
</html>
