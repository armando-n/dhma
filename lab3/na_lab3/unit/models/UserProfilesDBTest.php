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
        UserProfilesDB::addUserProfileTest();
    }
    
    public function testAddUserProfileWithValidParameters() {
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
        $userID = UsersDB::addUserTest($user);
        $profileID = UserProfilesDB::addUserProfileTest($profile, $userID);
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
        $profileID = UserProfilesDB::addUserProfileTest($profile, 1000);
        $output = ob_get_clean();
        $this->assertTrue(stristr($output, 'a foreign key constraint fails') !== false,
            'It should output an error message when an invalid user ID is provided');
    }
    
    public function testGetAllUserProfiles() {
        $profiles = UserProfilesDB::getAllUserProfilesTest();
        
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
        $profiles = UserProfilesDB::getAllUserProfilesTest(null);
        
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