<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Basic tests for Login Controller</title>
</head>
<body>
<h1>Login controller tests</h1>

<?php
include_once("../controllers/LoginController.class.php");
include_once("../models/User.class.php");
include_once("../views/LoginView.class.php");
include_once("../views/HomeView.class.php");
include_once("../views/HeaderView.class.php");
include_once("../views/FooterView.class.php");
?>

<h2>It should call the run method for invalid input and display the Login view</h2>
<?php 
$_SERVER ["REQUEST_METHOD"] = "POST";
$_POST = array(
        "userName" => "Some-Really-Long-User-Name",
        "password1" => "password123",
        "password2" => "password123"
);
LoginController::run();
?>
</body>
</html>
