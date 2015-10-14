<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\User.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UsersDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class UserProfilesDBTest extends PHPUnit_Framework_TestCase {
    
    /** @expectedException Exception
     * @expectedExceptionMessage Missing argument
     */
    public function testAddUserProfileWithNoParameters() {
        self::checkSession();
        UserProfilesDB::addUserProfile();
    }
    
    public function testAddUserProfileWithValidParameters() {
        self::checkSession();
        $userInput = array(
                "userName" => "nathan-m",
                "password" => "password123",
                "password1" => "password123",
                "password2" => "password123"
        );
        $input = array(
            "firstName" => "Nathan",
            "lastName" => "Martin",
            "email" => "namar@email.com",
            "gender" => "male",
            "phone" => "210-555-4917",
            "facebook" => "http://facebook.com/namar",
            "dob" => "1983-11-26",
            "country" => "United States of America",
            "theme" => "dark",
            "accentColor" => "#09ae6f",
            "picture" => "natimg",
            "isProfilePublic" => "on",
            "isPicturePublic" => "on",
            "userName" => "nathan-m"
        );
        $user = new User($userInput);
        $profile = new UserProfile($input);
        $this->dbQuery("delete from UserProfiles where email = 'namar@email.com'");
        $this->dbQuery("delete from Users where userName = 'nathan-m'");
        
        $rowsBeforeAdd = $this->dbSelect("select * from UserProfiles where email = 'namar@email.com'");
        $userID = UsersDB::addUser($user);
        $profileID = UserProfilesDB::addUserProfile($profile, $userID);
        $rowsAfterAdd = $this->dbSelect("select * from UserProfiles where email = 'namar@email.com'");
        
        $this->assertEmpty($rowsBeforeAdd,
            'It should not have the provided user profile until the profile has been added');
        $this->assertCount(1, $rowsAfterAdd,
            'It should have a new row in the UserProfiles table of the database when the profile parameter is provided');
        $this->assertArrayHasKey('firstName', $rowsAfterAdd[0],
            'It should have a new row in the UserProfiles table of the database when the profile parameter is provided');
        $this->assertEquals("namar@email.com", $rowsAfterAdd[0]["email"],
            'It should have a new row in the UserProfiles table of the database when the profile parameter is provided');
        $this->assertTrue(is_numeric($profileID),
            'It should return the profile ID of the added user profile when the profile parameter is provided');
        $this->assertNotEquals(-1, $profileID,
            'It should return the profile ID of the added user profile when the profile parameter is provided');
    }
    
    public function testAddUserProfileWithInvalidUserID() {
        self::checkSession();
        $input = array(
                "firstName" => "Nathan",
                "lastName" => "Martin",
                "email" => "namar@email.com",
                "gender" => "male",
                "phone" => "210-555-4917",
                "facebook" => "http://facebook.com/namar",
                "dob" => "1983-11-26",
                "country" => "United States of America",
                "theme" => "dark",
                "accentColor" => "#09ae6f",
                "picture" => "natimg",
                "isProfilePublic" => "on",
                "isPicturePublic" => "on",
                "userName" => "nathan-m"
        );
        $profile = new UserProfile($input);
        $this->dbQuery("delete from UserProfiles where email = 'namar@email.com'");
        $this->dbQuery("delete from Users where userID = 1000");
        
        ob_start();
        $profileID = UserProfilesDB::addUserProfile($profile, 1000);
        $output = ob_get_clean();
        $this->assertTrue(stristr($output, 'a foreign key constraint fails') !== false,
            'It should output an error message when an invalid user ID is provided');
    }
    
    public function testGetAllUserProfiles() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfiles();
        
        $this->assertNotEmpty($profiles,
            'It should return a non-empty array');
        
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('UserProfile', $profile,
                'It should return an array of UserProfile objects');
            $this->assertCount(0, $profile->getErrors(),
                'It should return an array of UserProfile objects without errors');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should return an array of UserProfile objects with an error count of 0');
        }
    }
    
    public function testGetAllUserProfilesWithNullParameter() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfiles(null);
        
        $this->assertNotEmpty($profiles,
            'It should return a non-empty array when null input is provided');
        
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('UserProfile', $profile,
                'It should return an array of UserProfile objects when null input is provided');
            $this->assertCount(0, $profile->getErrors(),
                'It should return an array of UserProfile objects without errors when null input is provided');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should return an array of UserProfile objects with an error count of 0 when null input is provided');
        }
    }
    
    /** @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Missing argument
     */
    public function testGetUserProfileByWithNoParameters() {
        self::checkSession();
        $profile = UserProfilesDB::getUserProfileBy();
    }
    
    /** @expectedException PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Missing argument
     */
    public function testGetUserProfileByWithNoValueParameter() {
        self::checkSession();
        $user = UserProfilesDB::getUserProfileBy('phone');
    }
    
    public function testGetUserProfileByWithValidParameters() {
        self::checkSession();
        $profile = UserProfilesDB::getUserProfileBy('phone', '210-555-2170');
        
        $this->assertInstanceOf('UserProfile', $profile,
            'It should return a UserProfile object when valid parameters are provided');
        $this->assertEquals('210-555-2170', $profile->getPhoneNumber(),
            'It should return a UserProfile object whose phone number field matches the provided input when valid input is provided');
        $this->assertCount(0, $profile->getErrors(),
            'It should return a UserProfile object without errors when valid input is provided:' . "\n" . array_shift($profile->getErrors()));
        $this->assertEquals(0, $profile->getErrorCount(),
            'It should return a UserProfile object with an error count of 0 when valid input is provided');
    }
    
    public function testGetUserProfileByWithNoResults() {
        self::checkSession();
        $profile = UserProfilesDB::getUserProfileBy('phone', '555-555-5515');
        
        $this->assertNull($profile,
            'It should return NULL when an unknown attribute-value pair is provided');
    }

    public function testGetUserProfilesCreatedSinceWithValidDateAndResults() {
        self::checkSession();
        $profiles = UserProfilesDB::getUserProfilesCreatedSince('2015-10-11');
    
        $this->assertNotNull($profiles,
            'It should call getUserProfilesCreatedSince and return an array when a valid date is provided');
        $this->assertGreaterThan(0, count($profiles),
            'It should call getUserProfilesCreatedSince and return a non-empty array when a valid date is provided');
    
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('UserProfile', $profile,
                'It should call getUserProfilesCreatedSince and return an array of UserProfile objects when a valid date is provided');
            $this->assertEmpty($profile->getErrors(),
                'It should call getUserProfilesCreatedSince and not have errors in the returned UserProfile objects when a valid date is provided');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should call getUserProfilesCreatedSince and have an error count of 0 in the returned UserProfile objects when a valid date is provided');
        }
    }
    
    public function testGetUserProfilesCreatedSinceWithInvalidDateAndNoResults() {
        self::checkSession();
        $profiles = UserProfilesDB::getUserProfilesCreatedSince('2050-10-11');
    
        $this->assertNotNull($profiles,
            'It should call getUserProfilesCreatedSince and return an array when an invalid date is provided');
        $this->assertCount(0, $profiles,
            'It should call getUserProfilesCreatedSince and return an empty array when an invalid date is provided');
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage Invalid date
     */
    public function testGetUserProfilesCreatedSinceWithInvalidDateString() {
        self::checkSession();
        $profiles = UserProfilesDB::getUserProfilesCreatedSince('invalid date string');
    }
    
    public function testGetUserProfilesCreatedByWithValidDateAndResults() {
        self::checkSession();
        $profiles = UserProfilesDB::getUserProfilesCreatedBy('2015-10-05');
    
        $this->assertNotNull($profiles,
            'It should call getUserProfilesCreatedBy and return an array when a valid date is provided');
        $this->assertGreaterThan(0, count($profiles),
            'It should call getUserProfilesCreatedBy and return a non-empty array when a valid date is provided');
    
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('UserProfile', $profile,
                'It should call getUserProfilesCreatedBy and return an array of UserProfile objects when a valid date is provided');
            $this->assertEmpty($profile->getErrors(),
                'It should call getUserProfilesCreatedBy and not have errors in the returned UserProfile objects when a valid date is provided');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should call getUserProfilesCreatedBy and have an error count of 0 in the returned UserProfile objects when a valid date is provided');
        }
    }
    
    public function testGetUserProfilesCreatedByWithInvalidDateAndNoResults() {
        self::checkSession();
        $profiles = UserProfilesDB::getUserProfilesCreatedBy('1950-10-05');
    
        $this->assertNotNull($profiles,
            'It should call getUserProfilesCreatedBy and return an array when an invalid date is provided');
        $this->assertCount(0, $profiles,
            'It should call getUserProfilesCreatedBy and return an empty array when an invalid date is provided');
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage Invalid date
     */
    public function testGetUserProfilesCreatedByWithInvalidDateString() {
        self::checkSession();
        $profiles = UserProfilesDB::getUserProfilesCreatedBy('invalid date string');
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage Missing argument
     */
    public function testGetAllUserProfilesSortedByDateCreatedWithNoParameters() {
        self::checkSession();
        UserProfilesDB::getAllUserProfilesSortedByDateCreated();
    }
    
    public function testGetAllUserProfilesSortedByDateCreatedAscending() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfilesSortedByDateCreated('asc');
    
        $this->assertGreaterThan(0, count($profiles),
            'It should call getAllUserProfilesSortedByDateCreated and return an array with at least 1 element when a valid order is provided');
        $previousDate = new DateTime('1950-01-01');
    
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('UserProfile', $profile,
                'It should call getAllUserProfilesSortedByDateCreated and return an array of UserProfile objects when a valid order is provided');
            $this->assertEmpty($profile->getErrors(),
                'It should call getAllUserProfilesSortedByDateCreated and not have errors in the returned UserProfile objects when a valid order is provided');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should call getAllUserProfilesSortedByDateCreated and have an error count of 0 in the returned UserProfile objects when a valid order is provided');
//             $this->assertGreaterThan($previousDate, $profile->getDateTime(),
//                 'It should call getAllUserProfilesSortedByDateCreated and return array of UserProfile objects in ascending date order');
        }
    }
    
    public function testGetAllUserProfilesSortedByDateCreatedDescending() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfilesSortedByDateCreated('desc');
    
        $this->assertGreaterThan(0, count($profiles),
            'It should call getAllUserProfilesSortedByDateCreated and return an array with at least 1 element when a valid order is provided');
        $previousDate = new DateTime('1950-01-01');
    
        foreach ($profiles as $profile) {
            $this->assertInstanceOf('UserProfile', $profile,
                'It should call getAllUserProfilesSortedByDateCreated and return an array of UserProfile objects when a valid order is provided');
            $this->assertEmpty($profile->getErrors(),
                'It should call getAllUserProfilesSortedByDateCreated and not have errors in the returned UserProfile objects when a valid order is provided');
            $this->assertEquals(0, $profile->getErrorCount(),
                'It should call getAllUserProfilesSortedByDateCreated and have an error count of 0 in the returned UserProfile objects when a valid order is provided');
//             $this->assertGreaterThan($previousDate, $profile->getDateTime(),
//                 'It should call getAllUserProfilesSortedByDateCreated and return array of UserProfile objects in ascending date order');
        }
    }
    
    /** @expectedException Exception
     *  @expectedExceptionMessage not an allowed order
     */
    public function testGetAllUserProfilesSortedByDateCreatedWithInvalidOrder() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfilesSortedByDateCreated('invalid order');
    }
    
    private function dbQuery($query) {
        try {
            $db = Database::getDB();
            $stmt = $db->prepare($query);
            $stmt->execute();
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
    
    private function dbSelect($query) {
        try {
            $db = Database::getDB();
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
    
    private function checkSession() {
        if (!isset($_SESSION))
            session_start();
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
    }
}
?>