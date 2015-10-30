<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php'; 
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class UserTest extends PHPUnit_Framework_TestCase {
    
    private static $validInput = array(
        "userName" => "armando-n",
        "password" => "password123",
        "password1" => "password123",
        "password2" => "password123"
    );
    
    public function testCreateValidUser() {
        $validUser = new User(UserTest::$validInput);
        
        $this->assertInstanceOf('User', $validUser,
            'It should create a User object when valid input is provided');
        $this->assertCount(0, $validUser->getErrors(),
            'It should not have errors when valid input is provided');
    }
    
    public function testParameterExtraction() {
        $validUser = new User(UserTest::$validInput);
        $params = $validUser->getParameters();
        
        $this->assertArrayHasKey('userName', $params,
            'It should return a parameter array with "userName" as a key');
        $this->assertArrayHasKey('password', $params,
            'It should return a parameter array with "password" as a key');
        $this->assertEquals(UserTest::$validInput['userName'], $params['userName'],
            'It should return a parameter array with a value for key "userName" that matches the provided input');
        $this->assertEquals(UserTest::$validInput['password'], $params['password'],
            'It should return a parameter array with a value for key "password" that matches the provided input');
    }
    
    public function testAccessorMethods() {
        $validUser = new User(UserTest::$validInput);
        
        $this->assertEquals(UserTest::$validInput['userName'], $validUser->getUserName(),
            'It should return the same value that was provided during object creation');
        $this->assertEquals(UserTest::$validInput['password'], $validUser->getPassword(),
            'It should return the same value that was provided during object creation');
    }
    
    public function testMissingUserName() {
        $invalidInput = array("someKey" => "someValue");
        $invalidUser = new User($invalidInput);
        
        $this->assertGreaterThan(0, $invalidUser->getErrorCount(),
            'It should have errors when user name is missing');
    }
    
    public function testMissingInput() {
        $invalidUser = new User();
        
        $this->assertInstanceOf('User', $invalidUser,
            'It should create an invalid User object when input is missing');
        $this->assertCount(2, $invalidUser->getParameters(),
            'It should have attributes when input is missing');
        $this->assertEmpty($invalidUser->getUserName(),
            'It should have empty values for all fields of the User object');
        $this->assertEmpty($invalidUser->getPassword(),
            'It should have empty values for all fields of the User object');
    }
    
    public function testNullInput() {
        $invalidUser = new User(null);
    
        $this->assertInstanceOf('User', $invalidUser,
            'It should create an invalid User object when null input is provided');
        $this->assertCount(2, $invalidUser->getParameters(),
            'It should have attributes when null input is provided');
        $this->assertEmpty($invalidUser->getUserName(),
            'It should have empty values for all fields of the User object');
        $this->assertEmpty($invalidUser->getPassword(),
            'It should have empty values for all fields of the User object');
    }
    
    public function testUserNameTooLong() {
        $invalidInput = array("userName" => "SomeReallyReallyReallyLongUserName");
        $invalidUser = new User($invalidInput);
        
        $this->assertGreaterThan(0, $invalidUser->getErrorCount(),
            'It should have errors when a long user name is provided');
    }

    public function testUserNameEmpty() {
        $user = new User(array('userName' => ''));
        $this->assertCount(1, $user->getErrors(),
            'It should have 1 error when an empty user name is provided');
        $this->assertArrayHasKey('userName', $user->getErrors(),
            'It should have an error named "userName" when an empty user name is provided');
    }
    
    public function testUserNameHasInvalidCharacters() {
        $invalidInput = array("userName" => "armando-n$");
        $invalidUser = new User($invalidInput);
    
        $this->assertGreaterThan(0, $invalidUser->getErrorCount(),
            'It should have errors when invalid characters are provided');
    }
}
?>