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
<div class="alert alert-<?=$_SESSION['alertType']?> fade in">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?= $_SESSION['flash'] ?>
</div><?php
        endif;
        ?>

<h1 class="hidden"><?= $title ?></h1>

<header>
    <nav id="main-nav" class="navbar navbar-default navbar-fixed-top"><!-- class="navbar navbar-inverse navbar-fixed-top">-->
        <div class="container-fluid">
            <h2 class="hidden">Site Navigation</h2>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNav">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="images/logo.png" class="img-responsive" alt="DHMA Logo" width="99" height="58" />
            </div>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="nav navbar-nav">
                    <li><a href="home">Home</a></li><?php
                    if (isset($_SESSION['profile'])): ?>
                    <li><a href="measurements_show_all">Measurements</a></li><?php
                    endif; ?>
                </ul>
                <ul class="nav navbar-nav navbar-right"><?php
                    if (isset($_SESSION['profile'])): ?>
                    <li><a href="login_logout">Logout</a></li><?php
                    else: ?> 
                    <li><a href="signup_show">Sign Up</a></li>
                    <li><a href="login_show">Login</a></li><?php
                    endif; ?> 
                </ul>
            </div>
        </div>
    </nav>
</header>

<?php
        if (isset($_SESSION['flash']))
            unset($_SESSION['flash']);
        if (isset($_SESSION['alertType']))
            unset($_SESSION['alertType']);
    }
}
?>