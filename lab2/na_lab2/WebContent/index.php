<?php
include("includer.php");

$loggedIn = isset($_GET["loggedin"]);

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

// determine which page was requested, then load it
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$urlPieces = preg_split("/\//", $url, null, PREG_SPLIT_NO_EMPTY);
$control = (count($urlPieces) < 2) ? "none" : $urlPieces[1];
switch ($control) {
    case "login" : LoginController::run(); break;
    case "logout" : LoginController::run(true); break; 
    case "profile" : ProfileController::run($user, $uData, false); break;
    case "edit-profile" : ProfileController::run($user, $uData, true); break; 
    case "signup" : SignupController::run(); break;
    case "simpleEcho" :
        include("simpleEcho.php"); break;
    default: HomeView::show();
}

?>