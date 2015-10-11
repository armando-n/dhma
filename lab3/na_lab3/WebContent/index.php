<?php
include("includer.php");

$loggedIn = isset($_GET["loggedin"]);

// dummy data, so profile page can be viewed correctly (no session support yet)
$validUserInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
);
$validUserProfileInput = array(
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

// dummy user info; necessary because sessions are not yet implemented
$user = new User($validUserInput);
$uData = new UserProfile($validUserProfileInput);

// parse the request URL
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$urlPieces = preg_split("/\//", $url, null, PREG_SPLIT_NO_EMPTY);
$numPieces = count($urlPieces);
$testUrlPrefix = '';
if ($numPieces == 2)
    $control = $urlPieces[1];
else if ($numPieces >= 3 && $urlPieces[1] == 'tests') {
    while (isset($urlPieces[1]) && $urlPieces[1] == 'tests')
        array_splice($urlPieces, 1, 1);
    $control = $urlPieces[1];
}
else
    $control = "none";

// run the requested controller
switch ($control) {
    case "login" : LoginController::run(); break;
    case "logout" : LoginController::run(true); break; 
    case "profile" : ProfileController::run($user, $uData, false); break;
    case "edit-profile" : ProfileController::run($user, $uData, true); break; 
    case "signup" : SignupController::run(); break;
    case "past-measurements" : PastMeasurementsController::run(); break;
    case "members" : UsersController::run(); break;
    default: HomeView::show();
}

?>