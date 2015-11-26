<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class GenericModelObjectTest extends PHPUnit_Framework_TestCase {
    
    public function testSetError() {
        $formInput = array(
            'userName' => 'armando-n',
            'password' => 'pass133'
        );
        $user = new User($formInput);
        $user->setError('userName', 'USER_NAME_EXISTS');
        
        $this->assertTrue(stristr($user->getError('userName'), 'User name already exists') !== false,
            'It should call setError and correctly retreive and store the specified error');
    }
    
    public function testGetError() {
        $formInput = array(
            'userName' => 'armando-n',
            'password' => 'pass133'
        );
        $user = new User($formInput);
        $user->setError('userName', 'USER_NAME_EXISTS');
        
        $this->assertTrue(stristr($user->getError('userName'), 'User name already exists') !== false,
            'It should call getError and return the specified error');
    }
    
    public function testGetErrorCount() {
        $formInput = array(
            'userName' => 'armando-n',
            'password' => 'pass133'
        );
        $user = new User($formInput);
        
        $this->assertEquals(0, $user->getErrorCount(),
            'It should call getErrorCount and return 0 when no errors exist');
        $user->setError('userName', 'USER_NAME_EXISTS');
        $this->assertEquals(1, $user->getErrorCount(),
            'It should call getErrorCount and return 1 when 1 error exists');
    }
    
    public function testGetErrors() {
        $formInput = array(
            'userName' => 'armando-n',
            'password' => 'pass133'
        );
        $user = new User($formInput);
        
        $this->assertEmpty($user->getErrors(),
            'It should call getErrors and return an empty array when no errors exist');
        $user->setError('userName', 'USER_NAME_EXISTS');
        $this->assertCount(1, $user->getErrors(),
            'It should call getErrors and return an array with 1 element when 1 error exists');
    }
    
}
?>