<?php
class HeaderView {
    
    public static function show($title = null) {
        
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <title>DHMA | <?= $title ?></title>
</head>
<body>
<?php
        if (isset($_SESSION['flash'])): ?>
<div><?= $_SESSION['flash'] ?></div><?php
        endif;
        ?>

<h1><?= $title ?></h1>

<header>
    <img src="images/logo.png" alt="DHMA Logo" width="99" height="58" />
    <nav id="main-nav" class="navbar navbar-inverse navbar-fixed-top">
        <h2>Site Navigation</h2>
        <ul>
            <li><a href="home">Home</a></li><?php
            if (isset($_SESSION['profile'])): ?>
            <li><a href="measurements_show_all">Past Measurements</a></li>
            <li><a href="login_logout">Logout</a></li><?php
            else: ?> 
            <li><a href="login_show">Login</a></li>
            <li><a href="signup_show">Sign Up</a></li><?php
            endif; ?> 
        </ul>
    </nav>
</header>

<?php
        if (isset($_SESSION['flash']))
            unset($_SESSION['flash']);
    }
}
?>