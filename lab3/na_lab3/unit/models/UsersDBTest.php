<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class UsersDBTest extends PHPUnit_Framework_TestCase {
    
    /** @expectedException Exception
     */
    public function testAddUserWithNoParameters() {
        UsersDB::addUserTest();
    }
    
    public function testAddUserWithUserParameter() {
        $input = array(
            "userName" => "nathan-m",
            "password" => "password123",
            "password1" => "password123",
            "password2" => "password123"
        );
        $user = new User($input);
        $this->dbQuery("delete from Users where userName = 'nathan-m'");
        
        $rowsBeforeAdd = $this->dbSelect("select * from Users where userName = 'nathan-m'");
        $userID = UsersDB::addUserTest($user);
        $rowsAfterAdd = $this->dbSelect("select * from Users where userName = 'nathan-m'");
        
        $this->assertEmpty($rowsBeforeAdd,
            'It should not have the user "nathan-m" before the user has been added');
        $this->assertCount(1, $rowsAfterAdd,
            'It should have a new row in the Users table of the database when the user parameter is provided');
        $this->assertArrayHasKey('userName', $rowsAfterAdd[0],
            'It should have a new row in the Users table of the database when the user parameter is provided');
        $this->assertEquals("nathan-m", $rowsAfterAdd[0]["userName"],
            'It should have a new row in the Users table of the database when the user parameter is provided');
        $this->assertTrue(is_numeric($userID),
            'It should return the user ID of the added user when the user parameter is provided');
        
    }
    
    /** @expectedException InvalidArgumentException
     */
    public function testAddUserWithInvalidUserParameter() {
        $profile = new UserProfile();
        UsersDB::addUserTest($profile);
    }
    
    public function testGetAllUsers() {
        $users = UsersDB::getAllUsersTest();
        
        $this->assertGreaterThan(0, count($users),
            'It should call getAllUsers and return an array with at least 1 element');
        
        foreach ($users as $user) {
            $this->assertInstanceOf('User', $user,
                'It should call getAllUsers and return an array of User objects');
            $this->assertEmpty($user->getErrors(),
                'It should call getAllUsers and not have errors in the returned User objects');
            $this->assertEquals(0, $user->getErrorCount(),
                'It should call getAllUsers and have an error count of 0 in the returned User objects');
        }
    }
    
    public function testGetAllUsersWithNullParameter() {
        $users = UsersDB::getAllUsersTest(null);
        
        $this->assertGreaterThan(0, count($users),
            'It should call getAllUsers with a null parameter and return an array with at least 1 element');
        
        foreach ($users as $user) {
            $this->assertInstanceOf('User', $user,
                'It should call getAllUsers with a null parameter and return an array of User objects');
            $this->assertEmpty($user->getErrors(),
                'It should call getAllUsers with a null parameter and not have errors in the returned User objects');
            $this->assertEquals(0, $user->getErrorCount(),
                'It should call getAllUsers with a null parameter and have an error count of 0 in the returned User objects');
        }
    }
    
    /** @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Missing argument
     */
    public function testGetUserByWithNoParameters() {
        $user = UsersDB::getUserByTest();
    }
    
    /** @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Missing argument
     */
    public function testGetUserByWithNoValueParameter() {
        $user = UsersDB::getUserByTest('userName');
    }
    
    public function testGetUserByWithValidParameters() {
        $user = UsersDB::getUserByTest('userName', 'armando-n');
        
        $this->assertInstanceOf('User', $user,
            'It should return a User object when valid parameters are provided');
        $this->assertEquals('armando-n', $user->getUserName(),
            'It should return a User object with the provided user name when valid parameters are provided');
        $this->assertCount(0, $user->getErrors(),
            'It should return a User object without errors when valid input is provided:' . "\n" . array_shift($user->getErrors()));
        $this->assertEquals(0, $user->getErrorCount(),
            'It should return a User object with an error count of 0 when valid input is provided');
    }
    
    public function testGetUserByWithUnknownUser() {
        $user = UsersDB::getUserByTest('userName', 'unkown-user');
    
        $this->assertNull($user,
            'It should return NULL when an unknown user name is provided');
    }
    
    public function testGetUsersCreatedSinceWithValidDateAndResults() {
        $users = UsersDB::getUsersCreatedSinceTest('2015-10-11');
        
        $this->assertNotNull($users,
            'It should call getUsersCreatedSince and return an array when a valid date is provided');
        $this->assertGreaterThan(0, count($users),
            'It should call getUsersCreatedSince and return a non-empty array when a valid date is provided');
        
        foreach ($users as $user) {
            $this->assertInstanceOf('User', $user,
                'It should call getUsersCreatedSince and return an array of User objects when a valid date is provided');
            $this->assertEmpty($user->getErrors(),
                'It should call getUsersCreatedSince and not have errors in the returned User objects when a valid date is provided');
            $this->assertEquals(0, $user->getErrorCount(),
                'It should call getUsersCreatedSince and have an error count of 0 in the returned User objects when a valid date is provided');
        }
    }
    
    public function testGetUsersCreatedSinceWithInvalidDateAndNoResults() {
        $users = UsersDB::getUsersCreatedSinceTest('2050-10-11');
    
        $this->assertNotNull($users,
            'It should call getUsersCreatedSince and return an array when an invalid date is provided');
        $this->assertCount(0, $users,
            'It should call getUsersCreatedSince and return an empty array when an invalid date is provided');
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage Invalid date
     */
    public function testGetUsersCreatedSinceWithInvalidDateString() {
        $users = UsersDB::getUsersCreatedSinceTest('invalid date string');
    }
    
    public function testGetUsersCreatedByWithValidDateAndResults() {
        $users = UsersDB::getUsersCreatedByTest('2015-10-05');
    
        $this->assertNotNull($users,
            'It should call getUsersCreatedBy and return an array when a valid date is provided');
        $this->assertGreaterThan(0, count($users),
            'It should call getUsersCreatedBy and return a non-empty array when a valid date is provided');
    
        foreach ($users as $user) {
            $this->assertInstanceOf('User', $user,
                'It should call getUsersCreatedBy and return an array of User objects when a valid date is provided');
            $this->assertEmpty($user->getErrors(),
                'It should call getUsersCreatedBy and not have errors in the returned User objects when a valid date is provided');
            $this->assertEquals(0, $user->getErrorCount(),
                'It should call getUsersCreatedBy and have an error count of 0 in the returned User objects when a valid date is provided');
        }
    }
    
    public function testGetUsersCreatedByWithInvalidDateAndNoResults() {
        $users = UsersDB::getUsersCreatedByTest('1950-10-05');
    
        $this->assertNotNull($users,
            'It should call getUsersCreatedBy and return an array when an invalid date is provided');
        $this->assertCount(0, $users,
            'It should call getUsersCreatedBy and return an empty array when an invalid date is provided');
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage Invalid date
     */
    public function testGetUsersCreatedByWithInvalidDateString() {
        $users = UsersDB::getUsersCreatedByTest('invalid date string');
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage Missing argument
     */
    public function testGetAllUsersSortedByDateCreatedWithNoParameters() {
        UsersDB::getAllUsersSortedByDateCreatedTest();
    }
    
    public function testGetAllUsersSortedByDateCreatedAscending() {
        $users = UsersDB::getAllUsersSortedByDateCreatedTest('asc');
        
        $this->assertGreaterThan(0, count($users),
            'It should call getAllUsersSortedByDateCreated and return an array with at least 1 element when a valid order is provided');
        $previousDate = new DateTime('1950-01-01');
        
        foreach ($users as $user) {
            $this->assertInstanceOf('User', $user,
                'It should call getAllUsersSortedByDateCreated and return an array of User objects when a valid order is provided');
            $this->assertEmpty($user->getErrors(),
                'It should call getAllUsersSortedByDateCreated and not have errors in the returned User objects when a valid order is provided');
            $this->assertEquals(0, $user->getErrorCount(),
                'It should call getAllUsersSortedByDateCreated and have an error count of 0 in the returned User objects when a valid order is provided');
//             $this->assertGreaterThan($previousDate, $user->getDateTime(),
//                 'It should call getAllUsersSortedByDateCreated and return array of User objects in ascending date order');
        }
    }
    
    public function testGetAllUsersSortedByDateCreatedDescending() {
        $users = UsersDB::getAllUsersSortedByDateCreatedTest('desc');
    
        $this->assertGreaterThan(0, count($users),
            'It should call getAllUsersSortedByDateCreated and return an array with at least 1 element when a valid order is provided');
        $previousDate = new DateTime('1950-01-01');
    
        foreach ($users as $user) {
            $this->assertInstanceOf('User', $user,
                'It should call getAllUsersSortedByDateCreated and return an array of User objects when a valid order is provided');
            $this->assertEmpty($user->getErrors(),
                'It should call getAllUsersSortedByDateCreated and not have errors in the returned User objects when a valid order is provided');
            $this->assertEquals(0, $user->getErrorCount(),
                'It should call getAllUsersSortedByDateCreated and have an error count of 0 in the returned User objects when a valid order is provided');
//             $this->assertGreaterThan($previousDate, $user->getDateTime(),
//                 'It should call getAllUsersSortedByDateCreated and return array of User objects in ascending date order');
        }
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage not an allowed order
     */
    public function testGetAllUsersSortedByDateCreatedWithInvalidOrder() {
        $users = UsersDB::getAllUsersSortedByDateCreatedTest('invalid order');
    }
    
    private function dbQuery($query, $dbName = null, $configFile = null) {
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare($query);
            $stmt->execute();
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
    
    private function dbSelect($query, $dbName = null, $configFile = null) {
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $rows;
    }
    
}
?>