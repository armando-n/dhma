<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for FooterView</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>FooterView test</h1>

<h2>It should call show and display the footer as if a user were logged in</h2>
<?php
include_once("../views/FooterView.class.php");
FooterView::show(true);
?>
    
<h2>It should call show and display the footer as if a user were logged out</h2>
<?php
FooterView::show(false);
?>
    
</body>
</html>