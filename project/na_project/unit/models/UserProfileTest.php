<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php'; 
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class UserProfileTest extends PHPUnit_Framework_TestCase {
    
    private static $validInput = array(
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
    
    public function testCreateValidUserProfile() {
        $validProfile = new UserProfile(UserProfileTest::$validInput);
        
        $this->assertInstanceOf('UserProfile', $validProfile,
            'It should create a UserProfile object when valid input is provided');
        $this->assertCount(0, $validProfile->getErrors(),
            'It should not have errors when valid input is provided');
    }
    
    public function testParameterExtraction() {
        $validProfile = new UserProfile(UserProfileTest::$validInput);
        $params = $validProfile->getParameters();
        
        $this->assertArrayHasKey('firstName', $params,
            'It should return a parameter array with "firstName" as a key');
        $this->assertArrayHasKey('lastName', $params,
            'It should return a parameter array with "lastName" as a key');
        $this->assertArrayHasKey('email', $params,
            'It should return a parameter array with "email" as a key');
        $this->assertArrayHasKey('gender', $params,
            'It should return a parameter array with "gender" as a key');
        $this->assertArrayHasKey('phone', $params,
            'It should return a parameter array with "phone" as a key');
        $this->assertArrayHasKey('facebook', $params,
            'It should return a parameter array with "facebook" as a key');
        $this->assertArrayHasKey('dob', $params,
            'It should return a parameter array with "dob" as a key');
        $this->assertArrayHasKey('country', $params,
            'It should return a parameter array with "country" as a key');
        $this->assertArrayHasKey('theme', $params,
            'It should return a parameter array with "lastthemeName" as a key');
        $this->assertArrayHasKey('accentColor', $params,
            'It should return a parameter array with "accentColor" as a key');
        $this->assertArrayHasKey('picture', $params,
            'It should return a parameter array with "picture" as a key');
        $this->assertArrayHasKey('isProfilePublic', $params,
            'It should return a parameter array with "isProfilePublic" as a key');
        $this->assertArrayHasKey('isPicturePublic', $params,
            'It should return a parameter array with "isPicturePublic" as a key');
        $this->assertArrayHasKey('sendReminders', $params,
            'It should return a parameter array with "sendReminders" as a key');
        $this->assertArrayHasKey('stayLoggedIn', $params,
            'It should return a parameter array with "stayLoggedIn" as a key');
        $this->assertArrayHasKey('userName', $params,
            'It should return a parameter array with "userName" as a key');
        
        $this->assertEquals(UserProfileTest::$validInput['firstName'], $params['firstName'],
            'It should return a parameter array with a value for key "firstName" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['lastName'], $params['lastName'],
            'It should return a parameter array with a value for key "lastName" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['email'], $params['email'],
            'It should return a parameter array with a value for key "email" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['gender'], $params['gender'],
            'It should return a parameter array with a value for key "gender" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['phone'], $params['phone'],
            'It should return a parameter array with a value for key "phone" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['facebook'], $params['facebook'],
            'It should return a parameter array with a value for key "facebook" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['dob'], $params['dob'],
            'It should return a parameter array with a value for key "dob" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['country'], $params['country'],
            'It should return a parameter array with a value for key "country" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['theme'], $params['theme'],
            'It should return a parameter array with a value for key "theme" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['accentColor'], $params['accentColor'],
            'It should return a parameter array with a value for key "accentColor" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['picture'], $params['picture'],
            'It should return a parameter array with a value for key "picture" that matches the provided input');
        $this->assertEquals(array_key_exists('isProfilePublic', UserProfileTest::$validInput) ? true : false, $params['isProfilePublic'],
            'It should return a parameter array with a value for key "isProfilePublic" that corresponds to the provided input');
        $this->assertEquals(array_key_exists('isPicturePublic', UserProfileTest::$validInput) ? true : false, $params['isPicturePublic'],
            'It should return a parameter array with a value for key "isPicturePublic" that corresponds to the provided input');
        $this->assertEquals(array_key_exists('sendReminders', UserProfileTest::$validInput) ? true : false, $params['sendReminders'],
            'It should return a parameter array with a value for key "sendReminders" that corresponds to the provided input');
        $this->assertEquals(array_key_exists('stayLoggedIn', UserProfileTest::$validInput) ? true : false, $params['stayLoggedIn'],
            'It should return a parameter array with a value for key "stayLoggedIn" that corresponds to the provided input');
        $this->assertEquals(UserProfileTest::$validInput['userName'], $params['userName'],
            'It should return a parameter array with a value for key "userName" that matches the provided input');
    }
    
    public function testAccessorMethods() {
        $profile = new UserProfile(UserProfileTest::$validInput);
        
        $this->assertEquals(UserProfileTest::$validInput['firstName'], $profile->getFirstName(),
            'It should return a value for field "firstName" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['lastName'], $profile->getLastName(),
            'It should return a value for field "lastName" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['email'], $profile->getEmail(),
            'It should return a value for field "email" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['gender'], $profile->getGender(),
            'It should return a value for field "gender" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['phone'], $profile->getPhoneNumber(),
            'It should return a value for field "phone" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['facebook'], $profile->getFacebook(),
            'It should return a value for field "facebook" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['dob'], $profile->getDOB(),
            'It should return a value for field "dob" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['country'], $profile->getCountry(),
            'It should return a value for field "country" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['theme'], $profile->getTheme(),
            'It should return a value for field "theme" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['accentColor'], $profile->getAccentColor(),
            'It should return a value for field "accentColor" that matches the provided input');
        $this->assertEquals(UserProfileTest::$validInput['picture'], $profile->getPicture(),
            'It should return a value for field "picture" that matches the provided input');
        $this->assertEquals(array_key_exists('isProfilePublic', UserProfileTest::$validInput) ? true : false, $profile->isProfilePublic(),
            'It should return a value for field "isProfilePublic" that corresponds to the provided input');
        $this->assertEquals(array_key_exists('isPicturePublic', UserProfileTest::$validInput) ? true : false, $profile->isPicturePublic(),
            'It should return a value for field "isPicturePublic" that corresponds to the provided input');
        $this->assertEquals(array_key_exists('sendReminders', UserProfileTest::$validInput) ? true : false, $profile->isSendRemindersSet(),
            'It should return a value for field "sendReminders" that corresponds to the provided input');
        $this->assertEquals(array_key_exists('stayLoggedIn', UserProfileTest::$validInput) ? true : false, $profile->isStayLoggedInSet(),
            'It should return a value for field "stayLoggedIn" that corresponds to the provided input');
        $this->assertEquals(UserProfileTest::$validInput['userName'], $profile->getUserName(),
            'It should return a value for field "userName" that matches the provided input');
    }
    
    public function testNullInput() {
        $profile = new UserProfile(null);
    
        $this->assertInstanceOf('UserProfile', $profile,
            'It should create a UserProfile object when null input is provided');
        $this->assertCount(16, $profile->getParameters(),
            'It should have attributes when null input is provided');
        $this->assertCount(0, $profile->getErrors(),
            'It should not have errors when null input is provided');
        
        $this->assertEmpty($profile->getFirstName(),
            'It should have an empty value for the first name field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getLastName(),
            'It should have an empty value for the last name field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getEmail(),
            'It should have an empty value for the email field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getPhoneNumber(),
            'It should have an empty value for the phone number field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getGender(),
            'It should have an empty value for the gender field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getDOB(),
            'It should have an empty value for the DOB field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getCountry(),
            'It should have an empty value for the country field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getPicture(),
            'It should have an empty value for the picture field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getFacebook(),
            'It should have an empty value for the facebook field of the UserProfile object when null input is provided');
        $this->assertEquals(UserProfile::DEFAULT_THEME, $profile->getTheme(),
            'It should have a default value of "'. UserProfile::DEFAULT_THEME . '" for the theme field of the UserProfile object when null input is provided');
        $this->assertEquals(UserProfile::DEFAULT_COLOR, $profile->getAccentColor(),
            'It should have a default value of "'. UserProfile::DEFAULT_COLOR . '" for the accent color field of the UserProfile object when null input is provided');
        $this->assertFalse($profile->isProfilePublic(),
            'It should have a default value of false for the isProfilePublic field of the UserProfile object when null input is provided');
        $this->assertFalse($profile->isPicturePublic(),
            'It should have a default value of false for the isPicturePublic field of the UserProfile object when null input is provided');
        $this->assertFalse($profile->isSendRemindersSet(),
            'It should have a default value of false for the sendReminders field of the UserProfile object when null input is provided');
        $this->assertFalse($profile->isStayLoggedInSet(),
            'It should have a default value of false for the stayLoggedIn field of the UserProfile object when null input is provided');
        $this->assertEmpty($profile->getUserName(),
            'It should have an empty value for the user name field of the UserProfile object when null input is provided');
    }
    
    public function testNoInput() {
        $profile = new UserProfile();
        
        $this->assertInstanceOf('UserProfile', $profile,
            'It should create a UserProfile object when no input is provided');
        $this->assertCount(16, $profile->getParameters(),
            'It should have attributes when no input is provided');
        $this->assertCount(0, $profile->getErrors(),
            'It should not have errors when no input is provided');
        
        $this->assertEmpty($profile->getFirstName(),
            'It should have an empty value for the first name field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getLastName(),
            'It should have an empty value for the last name field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getEmail(),
            'It should have an empty value for the email field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getPhoneNumber(),
            'It should have an empty value for the phone number field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getGender(),
            'It should have an empty value for the gender field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getDOB(),
            'It should have an empty value for the DOB field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getCountry(),
            'It should have an empty value for the country field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getPicture(),
            'It should have an empty value for the picture field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getFacebook(),
            'It should have an empty value for the facebook field of the UserProfile object when no input is provided');
        $this->assertEquals(UserProfile::DEFAULT_THEME, $profile->getTheme(),
            'It should have a default value of "'. UserProfile::DEFAULT_THEME . '" for the theme field of the UserProfile object when no input is provided');
        $this->assertEquals(UserProfile::DEFAULT_COLOR, $profile->getAccentColor(),
            'It should have a default value of "'. UserProfile::DEFAULT_COLOR . '" for the accent color field of the UserProfile object when no input is provided');
        $this->assertFalse($profile->isProfilePublic(),
            'It should have a default value of false for the isProfilePublic field of the UserProfile object when no input is provided');
        $this->assertFalse($profile->isPicturePublic(),
            'It should have a default value of false for the isPicturePublic field of the UserProfile object when no input is provided');
        $this->assertFalse($profile->isSendRemindersSet(),
            'It should have a default value of false for the sendReminders field of the UserProfile object when no input is provided');
        $this->assertFalse($profile->isStayLoggedInSet(),
            'It should have a default value of false for the stayLoggedIn field of the UserProfile object when no input is provided');
        $this->assertEmpty($profile->getUserName(),
            'It should have an empty value for the user name field of the UserProfile object when no input is provided');
    }
    
    public function testEmptyInput() {
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
        $profile = new UserProfile($emptyInputValues);
        
        $this->assertInstanceOf('UserProfile', $profile,
            'It should create a UserProfile object when empty input is provided');
        $this->assertCount(16, $profile->getParameters(),
            'It should have attributes when empty input is provided');
        $this->assertCount(1, $profile->getErrors(),
            'It should have 1 error when empty input is provided (for missing userName)');
        $this->assertArrayHasKey('userName', $profile->getErrors(),
            'It should have an error for "userName" when empty input is provided');
        
        $this->assertEmpty($profile->getFirstName(),
            'It should have an empty value for the first name field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getLastName(),
            'It should have an empty value for the last name field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getEmail(),
            'It should have an empty value for the email field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getPhoneNumber(),
            'It should have an empty value for the phone number field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getGender(),
            'It should have an empty value for the gender field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getDOB(),
            'It should have an empty value for the DOB field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getCountry(),
            'It should have an empty value for the country field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getPicture(),
            'It should have an empty value for the picture field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getFacebook(),
            'It should have an empty value for the facebook field of the UserProfile object when empty input is provided');
        $this->assertEquals(UserProfile::DEFAULT_THEME, $profile->getTheme(),
            'It should have a default value of "'. UserProfile::DEFAULT_THEME . '" for the theme field of the UserProfile object when empty input is provided');
        $this->assertEquals(UserProfile::DEFAULT_COLOR, $profile->getAccentColor(),
            'It should have a default value of "'. UserProfile::DEFAULT_COLOR . '" for the accent color field of the UserProfile object when empty input is provided');
        $this->assertFalse($profile->isProfilePublic(),
            'It should have a default value of false for the isProfilePublic field of the UserProfile object when empty input is provided');
        $this->assertFalse($profile->isPicturePublic(),
            'It should have a default value of false for the isPicturePublic field of the UserProfile object when empty input is provided');
        $this->assertFalse($profile->isSendRemindersSet(),
            'It should have a default value of false for the sendReminders field of the UserProfile object when empty input is provided');
        $this->assertFalse($profile->isStayLoggedInSet(),
            'It should have a default value of false for the stayLoggedIn field of the UserProfile object when empty input is provided');
        $this->assertEmpty($profile->getUserName(),
            'It should have an empty value for the user name field of the UserProfile object when empty input is provided');
    }

    
}
?>