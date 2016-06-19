<?php
class HeaderView {
    
    public static function show($title = null) {
        $host_base = $_SERVER['HTTP_HOST'].'/'.$_SESSION['base'];
        if (isset($_SESSION['profile']) && $_SESSION['profile']->getTheme() == 'dark') {
            $bootstrap_css = '/css/bootstrap.dark.min.css';
            $logo = '/images/logo_dark.png';
        } else {
            $bootstrap_css = '/css/bootstrap.min.css';
            $logo = '/images/logo.png';
        }
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="//<?= $host_base . $bootstrap_css ?>" type="text/css" />
    <link rel="stylesheet" href="//<?= $host_base . '/css/myStyles.css'?>" type="text/css" /><?php
        if (isset($_SESSION['styles'])):
            foreach ($_SESSION['styles'] as $style): ?>
    <link rel="stylesheet" href="//<?= $host_base . '/css/' . $style ?>" type="text/css" /><?php
            endforeach;
            unset($_SESSION['styles']);
        endif; ?>
    <script src="//<?= $host_base . '/js/jquery-1.11.3.js' ?>"></script>
    <script src="//<?= $host_base . '/js/bootstrap.min.js' ?>"></script><?php
        if (isset($_SESSION['scripts'])):
            foreach ($_SESSION['scripts'] as $script): ?>
    <script src="//<?= $host_base . '/js/' . $script ?>"></script><?php
            endforeach;
            unset($_SESSION['scripts']);
        endif; 
        if (isset($_SESSION['libraries'])):
            foreach ($_SESSION['libraries'] as $library): ?>
    <script src="//<?= $host_base . '/lib/' . $library ?>"></script><?php
            endforeach;
            unset($_SESSION['libraries']);
        endif; ?>
    <title>DHMA | <?= $title ?></title>
</head>
<body>

<!-- <h1 class="hidden"><?php// $title ?></h1> -->

<nav id="main-nav" class="navbar navbar-default"><!-- class="navbar navbar-inverse navbar-fixed-top">-->
    <div class="container-fluid">
    
        <h2 class="hidden">Site Navigation</h2>
        
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNav">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="//<?= $host_base . $logo ?>" class="img-responsive" alt="DHMA Logo" width="99" height="58" />
        </div>
        
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="nav navbar-nav">
                <li><a href="home">Home</a></li><?php
                if (isset($_SESSION['profile'])): ?>
                <li><a href="measurements_show_all">Measurements</a></li><?php
                    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <li><a href="admin_show">Admin</a></li><?php
                    else: ?>
                <li><a href="profile_show">Profile</a></li><?php
                    endif;
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
<?php
                if (isset($_SESSION['flash'])): ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="alert alert-<?=$_SESSION['alertType']?> fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $_SESSION['flash'] ?>
            </div>
        </div>
    </div>
</div><?php
                endif;
                if (!is_null($title)): ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1 id="pagetitle" class="page-header"><?=$title?></h1>
        </div>
    </div>
</div><?php
                endif; ?>

<div class="container">

<?php
        if (isset($_SESSION['flash']))
            unset($_SESSION['flash']);
        if (isset($_SESSION['alertType']))
            unset($_SESSION['alertType']);
    }
}
?>