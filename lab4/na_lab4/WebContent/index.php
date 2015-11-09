<?php
if (ob_get_contents() === false)
    ob_start();
include_once("includer.php");
if (!isset($_SESSION))
    session_start();

$_SESSION['dbName'] = 'na_lab4db';
$_SESSION['configFile'] = 'myConfig.ini';
if (!isset($_SESSION['scripts']))
    $_SESSION['scripts'] = array();

// dummy data, so profile page can be viewed correctly (no session support yet)
$validUserInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
);
$validUserProfileInput = array(
        "firstName" => "Armando",
        "lastName" => "Navarro",
        "email" => "fdf786@my.utsa.edu",
        "gender" => "male",
        "phone" => "281-555-2180",
        "facebook" => "http://facebook.com/someguy210",
        "dob" => "1983-11-02",
        "country" => "United States of America",
        "theme" => "dark",
        "accentColor" => "#00008b",
        "picture" => "someimage",
        "isProfilePublic" => "on",
        "isPicturePublic" => "on",
        "sendReminders" => "on",
        "stayLoggedIn" => "on"
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
if ( ($hashPos = strrpos($control, '#')) !== false)
    $control = substr($control, 0, $hashPos);

$_SESSION['base'] = $urlPieces[0];    
$controlParts = preg_split("/_/", $control, null, PREG_SPLIT_NO_EMPTY);
$numParts = count($controlParts);
$_SESSION['arguments'] = '';
if ($numParts > 0) {
    $_SESSION['control'] = $controlParts[0];
    $control = $controlParts[0];
} else
    unset($_SESSION['control']);
if ($numParts > 1)
    $_SESSION['action'] = $controlParts[1];
else
    unset($_SESSION['action']);
if ($numParts > 2) {
    $_SESSION['arguments'] = $controlParts[2];
    for ($i = 3; $i < $numParts; $i++)
        $_SESSION['arguments'] = $_SESSION['arguments'] . '_' . $controlParts[$i];
}
else
    unset($_SESSION['arguments']);

// run the requested controller
switch ($control) {
    case "login" : LoginController::run(); break;
    case "logout" : LoginController::run(true); break; 
    case "profile" : ProfileController::run($user, $uData, false); break;
    case "signup" : SignupController::run(); break;
    case "measurements" : MeasurementsController::run(); break;
    case "members_show" :
    case "members" : UsersController::run(); break;
    case "home":
    default:
        $_SESSION['styles'] = array('HomeStyles.css');
        HomeView::show();
}

ob_end_flush();
?>