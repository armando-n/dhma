<!DOCTYPE html>
<html>
<head>
	<title>Basic tests for HomeView</title>
	<meta charset="utf-8" />
	<meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>HomeView test</h1>
<h2>It should call show and show without crashing</h2>

<?php
include_once("../views/HomeView.class.php");
HomeView::showBody(null);
?>

<h2>It should call show and show with a welcome back message</h2>

<?php
include_once("../models/GenericModelObject.class.php");
include_once("../models/User.class.php");
include_once("../models/Messages.class.php");
$validInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
);
$user = new User($validInput);
HomeView::showBody($user);
?>
    
</body>
</html>