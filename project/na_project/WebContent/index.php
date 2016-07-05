<?php
try {
if (ob_get_contents() === false)
    ob_start();
include_once("includer.php");
if (!isset($_SESSION)) {
    session_start();
    $_SESSION['styles'] = array();
    $_SESSION['scripts'] = array();
    $_SESSION['libraries'] = array();
    $_SESSION['dbName'] = 'dhmadb';
    $_SESSION['configFile'] = 'myConfig.ini';
}

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
    case 'admin' : AdministratorController::run(); break;
    case "login" : LoginController::run(); break;
    case "logout" : LoginController::run(); break; 
    case "profile" : ProfileController::run(); break;
    case "signup" : SignupController::run(); break;
    case "measurements" : MeasurementsController::run(); break;
    case 'measurementsOptions' : MeasurementsOptionsController::run(); break;
    case "members_show" :
    case "users" :
    case "members" : UsersController::run(); break;
    case "faq" : FaqView::show(); break;
    case "demo" : DemoController::run(); break;
    case "home":
    default:
        $_SESSION['styles'] = array('HomeStyles.css');
        HomeView::show();
}

ob_end_flush();
} catch (Exception $e) {
    $_SESSION['flash'] = 'Unable to complete request: An unexpected error occured.';
    HomeView::show();
}
?>