<?php
include_once("../models/GenericModelObject.class.php");
include_once("../views/UsersView.class.php");
include_once("../models/UserProfile.class.php");
include_once("../models/UserProfilesDB.class.php");
include_once("../models/Database.class.php");
include_once("../models/Messages.class.php");
?><!DOCTYPE html>
<html>
<head>
    <title>Tests for UsersView</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>UsersView test</h1>

<h2>It should call show and display the users view</h2>
<?php
$uProfiles = UserProfilesDB::getAllUserProfilesTest();
UsersView::showBody($uProfiles);
?>

<h2>It should call show and display an error message when null input is provided</h2>
<?php UsersView::showBody(null) ?>

<h2>It should call show and display a message that there are no users to show when empty input is provided</h2>
<?php
$emptyArray = array();
UsersView::showBody($emptyArray);
?>

</body>
</html>
