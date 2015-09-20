<!DOCTYPE html>
<html>
<head>
    <title>Basic tests for HeaderView</title>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>HeaderView test</h1>

<h2>It should call show and display the view as if a user were logged in</h2>
<?php
include_once("../views/HeaderView.class.php");
HeaderView::show("Page Title", false);
?>

<h2>It should call show and display the view as if a user were logged out</h2>
<?php
HeaderView::show("Page Title", true);
?>
    
</body>
</html>