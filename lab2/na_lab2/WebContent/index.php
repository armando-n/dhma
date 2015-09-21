<?php
include("includer.php");

$loggedIn = isset($_GET["loggedin"]);

// determine which page was requested, then load it
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$urlPieces = preg_split("/\//", $url, null, PREG_SPLIT_NO_EMPTY);
$control = (count($urlPieces) < 2) ? "none" : $urlPieces[1];
switch ($control) {
    case "login": LoginController::run() ; break;
    case "profile": ; ProfileController::run(); break;
    case "signup": SignUpController::run(); break;
    default:
        HeaderView::show("Diabetic Health Management Assistant", $loggedIn);
        HomeView::show();
        FooterView::show($loggedIn);
}

?>