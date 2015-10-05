<?php
include_once("../models/GenericModelObject.class.php");
include_once("../models/UserProfile.class.php");
include_once("../models/Messages.class.php");

// test valid UserProfile object creation
$validInput = array(
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
        "stayLoggedIn" => "on",
        "userName" => "armando-n"
);
$validUserProfile = new UserProfile($validInput);
$test1 = is_object($validUserProfile) ? '' : 'Failed: It should create a valid object when valid input is provided';
$test2 = (empty($validUserProfile->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

// test parameter extraction
$params = $validUserProfile->getParameters();

// test null input
$emptyUserProfile1 = new UserProfile(null);
$test3 = is_object($emptyUserProfile1) ? '' : 'Failed: It should create a valid object with empty property values when valid input is provided';
$test4 = (empty($emptyUserProfile1->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

// test empty input values
$emptyInputValues = array(
        "firstName" => "",
        "lastName" => "",
        "email" => "",
        "gender" => "",
        "phone" => "",
        "facebook" => "",
        "dob" => "",
        "country" => "",
        "theme" => "",
        "accentColor" => "",
        "picture" => "",
        "isProfilePublic" => "",
        "isPicturePublic" => "",
        "sendReminders" => "",
        "stayLoggedIn" => "",
        "userName" => ""
);
$emptyUserProfile2 = new UserProfile($emptyInputValues);
$test5 = is_object($emptyUserProfile2) ? '' : 'Failed: It should create a valid object with empty property values when valid input is provided';
$test6 = (empty($emptyUserProfile2->getErrors())) ? '' : 'Failed: It should not have errors when valid input is provided';

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="author" content="Armando Navarro" />
    <title>Basic tests for UserProfile class</title>
</head>
<body>

<h1>UserProfile class tests</h1>

<!-- output: user object creation test -->
<h2>It should create a valid UserProfile object when all input is provided</h2>
<?= $test1 ?> <?php if (!empty($test1)) {?><br /><?php ; }?>
<?= $test2 ?> <?php if (!empty($test2)) {?><br /><?php ; }?>
The object is: <pre><?= $validUserProfile ?></pre>

<!-- output: parameter extraction test -->
<h2>It should extract the parameters that went in</h2>
<pre><?php print_r($params); ?></pre>

<!-- output: get method -->
<h2>It should call the get methods of a valid UserProfile object and display its property values</h2>
Errors: <?php print_r($validUserProfile->getErrors()); ?><br />
Error Count: <?=$validUserProfile->getErrorCount()?><br />
First Name: <?=$validUserProfile->getFirstName()?><br />
Last Name: <?=$validUserProfile->getLastName()?><br />
E-mail: <?=$validUserProfile->getEmail()?><br />
Phone Number: <?=$validUserProfile->getPhoneNumber()?><br />
Gender: <?=$validUserProfile->getGender()?><br />
Date of Birth: <?=$validUserProfile->getDOB()?><br />
Country: <?=$validUserProfile->getCountry()?><br />
Facebook : <?=$validUserProfile->getFacebook()?><br />
Theme: <?=$validUserProfile->getTheme()?><br />
Accent Color: <?=$validUserProfile->getAccentColor()?><br />
is profile public: <?=$validUserProfile->isProfilePublic()?><br />
is picture public: <?=$validUserProfile->isPicturePublic()?><br />
is send reminders set: <?=$validUserProfile->isSendRemindersSet()?><br />
is stay logged in set: <?=$validUserProfile->isStayLoggedInSet()?><br />
Parameters: <pre><?php print_r($validUserProfile->getParameters()); ?></pre>

<!-- output: null input test -->
<h2>It should create a valid UserProfile object with mostly empty property values; Theme, accent color, and boolean properties will be set to default values</h2>
<?= $test3 ?> <?php if (!empty($test3)) {?><br /><?php ; }?>
<?= $test4 ?> <?php if (!empty($test4)) {?><br /><?php ; }?>
The object is:
<pre><?= $emptyUserProfile1 ?></pre>

<!-- output: empty input values test -->
<h2>It should create a valid UserProfile object with mostly empty property values; Theme, accent color, and boolean properties will be set to default values</h2>
<?= $test5 ?> <?php if (!empty($test5)) {?><br /><?php ; }?>
<?= $test6 ?> <?php if (!empty($test6)) {?><br /><?php ; }?>
The object is:
<pre><?= $emptyUserProfile2 ?></pre>

</body>
</html>
