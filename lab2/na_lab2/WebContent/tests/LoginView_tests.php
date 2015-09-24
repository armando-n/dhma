<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for LoginView</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>LoginView test</h1>

<h2>It should call show and display the view without crashing</h2>
<?php
include_once("../views/LoginView.class.php");
LoginView::showBody();
?>

<h2>It should call show and display the view with user name filled in and an error message</h2>
<?php
include_once("../models/User.class.php");
$invalidUserInput = array(
        "userName" => "A-Really-Long-User-Name",
        "password1" => "password123",
        "password2" => "password123"
);
$user = new User($invalidUserInput);
LoginView::showBody($user);
?>
    
</body>
</html>