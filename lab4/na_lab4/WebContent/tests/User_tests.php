<?php
include_once("../models/GenericModelObject.class.php");
include_once("../models/User.class.php");
include_once("../models/Messages.class.php");

// test User object creation
$validInput = array(
        "userName" => "armando-n",
        "password1" => "password123",
        "password2" => "password123"
);
$validUser = new User($validInput);
$test1 = (is_object($validUser)) ? '' : 'Failed: It should create a valid object when valid input is provided';
$test2 = (empty($validUser->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

// test parameter extraction
$params = $validUser->getParameters();

// test invalid characters in user name input
$invalidInput = array("userName" => "armando-n$");
$invalidUser = new User($invalidInput);
$test3 = (!empty($invalidUser->getErrors())) ? '' : 'Failed: It should have errors when invalid input is provided';

// test missing user name
$invalidInput2 = array("someKey" => "someValue");
$invalidUser2 = new User($invalidInput2);
$test4 = (!empty($invalidUser2->getErrors())) ? '' : 'Failed: It should have errors when user name is missing';

// test missing form input
$invalidUser3 = new User();
$test5 = (empty($invalidUser3->getErrors())) ? '' : 'Failed: It should not have errors when form input is missing';

// test user name too long
$invalidInput3 = array("userName" => "SomeLongUserName");
$invalidUser4 = new User($invalidInput3);
$test6 = (!empty($invalidUser4->getErrors())) ? '' : 'Failed: It should have errors when user name is too long';

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Basic tests for User class</title>
</head>
<body>

<h1>User Tests</h1>

<h2>It should create a valid User object when all input is provided</h2>

<!-- output: User object creation test -->
<?= $test1 ?><br />
<?= $test2 ?><br />
The object is: <?= $validUser ?><br />

<!-- output: parameter extraction test -->
<h2>It should extract the parameters that went in</h2>
<pre><?php print_r($params); ?></pre>

<h2>It should call the get methods and retrieve the parameters</h2>
User name: <?=$validUser->getUserName()?><br />
Password: <?=$validUser->getPassword()?><br />
Error count: <?=$validUser->getErrorCount();?><br />
Errors: <?php print_r($validUser->getErrors()); ?><br />
Parameters: <pre><?php print_r($validUser->getParameters()); ?></pre>

<!-- output: invalid input test -->
<h2>It should have an error when the user name contains invalid characters</h2>
<?= $test3 ?><br />
The error for userName is: <?= $invalidUser->getError('userName') ?><br />
The object is: <?= $invalidUser ?>

<!-- output: missing user name test -->
<h2>It should have an error when the user name is missing</h2>
<?= $test4 ?><br />
The error for userName is: <?= $invalidUser2->getError('userName') ?><br />
The object is: <?= $invalidUser2 ?>

<!-- output: missing form input test -->
<h2>It should not have an error when the form input is missing</h2>
<?= $test5 ?><br />
The object is: <?= $invalidUser3 ?>

<!-- output: user name too long test -->
<h2>It should have errors when the user name is too long</h2>
<?= $test6 ?><br />
The error for userName is: <?= $invalidUser4->getError('userName') ?><br />
The object is: <?= $invalidUser4 ?>

</body>
</html>
