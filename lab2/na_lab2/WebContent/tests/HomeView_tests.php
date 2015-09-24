<!DOCTYPE html>
<html>
<head>
	<title>Basic tests for HomeView</title>
	<meta charset="utf-8" />
	<meta name="author" content="Armando Navarro" />
</head>
<body>

<h1>HomeView test</h1>
<h2>It should call show and show without crashing</h2>

<?php
include_once("../views/HomeView.class.php");
HomeView::showBody(null);
?>
    
</body>
</html>