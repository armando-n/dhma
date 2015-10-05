<?php
include_once("../models/UsersDB.class.php");
include_once("../models/User.class.php");
include_once("../models/Database.class.php");
include_once("../models/Messages.class.php");
include_once("../resources/Utilities.class.php");
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Tests for UsersDB object</title>
</head>
<body>
<h1>UsersDB tests</h1>

<h2>It should call getAllUsers and return a valid array of User objects</h2>
<?php 
$users = UsersDB::getAllUsersTest();
foreach ($users as $profile) {
    ?><pre><?=$profile?></pre><?php
}
?>

<h2>It should call addUser and return the userID of the new User</h2>
<?php
$db = Database::getDB('dhma_testDB');
$stmt = $db->prepare("delete from Users where userName = 'lizzy426'");
$stmt->execute();
$stmt->closeCursor();
$input = array(
    "userName" => "lizzy426",
    "password" => "pass101112"
);
$user = new User($input);
$userID = UsersDB::addUserTest($user);
?>
The new userID is: <?=$userID?>

<h2>It should call getUserBy and return a User object for the specified user ID</h2>
<?php
$user = UsersDB::getUserByTest('userID', 3);
if (is_object($user)) {
    ?>The retrieved User object with user ID of 3 is:<br />
    <pre><?=$user?></pre><?php
} else {
    ?>Failed: invalid object returned<?php
}
?>

<h2>It should call getUserBy and return a User object for the specified userName</h2>
<?php
$user = UsersDB::getUserByTest('userName', 'sarahk');
if (is_object($user)) {
    ?>The retrieved User object with userName of sarahk is:<br />
    <pre><?=$user?></pre><?php
} else {
    ?>Failed: invalid object returned<?php
}
?>

</body>
</html>
