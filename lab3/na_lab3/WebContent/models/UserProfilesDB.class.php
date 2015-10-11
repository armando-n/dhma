<?php
class UserProfilesDB {
    
    // adds the specified UserProfile object to the database, associating it with the specified userID
    public static function addUserProfile($uProfile, $userID, $dbName = null, $configFile = null) {
        $returnProfileID = -1;
        
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare(
                "insert into UserProfiles (firstName, lastName, email, phone,
                gender, dob, country, picture, facebook, theme, accentColor,
                isProfilePublic, isPicturePublic, sendReminders, stayLoggedIn,
                userID)
                values (:firstName, :lastName, :email, :phone, :gender, :dob,
                    :country, :picture, :facebook, :theme, :accentColor,
                    :isProfilePublic, :isPicturePublic, :sendReminders,
                    :stayLoggedIn, :userID)"
            );
            $stmt->execute(array(
                ":firstName" => $uProfile->getFirstName(),
                ":lastName" => $uProfile->getLastName(),
                ":email" => $uProfile->getEmail(),
                ":phone" => $uProfile->getPhoneNumber(),
                ":gender" => $uProfile->getGender(),
                ":dob" => $uProfile->getDOB(),
                ":country" => $uProfile->getCountry(),
                ":picture" => $uProfile->getPicture(),
                ":facebook" => $uProfile->getFacebook(),
                ":theme" => $uProfile->getTheme(),
                ":accentColor" => $uProfile->getAccentColor(),
                ":isProfilePublic" => $uProfile->isProfilePublic(),
                ":isPicturePublic" => $uProfile->isPicturePublic(),
                ":sendReminders" => $uProfile->isSendRemindersSet(),
                ":stayLoggedIn" => $uProfile->isStayLoggedInSet(),
                ":userID" => $userID
            ));
            $returnProfileID = $db->lastInsertId("profileID");
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $returnProfileID;
    }
    
    // returns an array of UserProfile objects for all user profiles in the database
    public static function getAllUserProfiles($dbName = null, $configFile = null) {
        $allUsers = array();
        
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare(
                "select Users.userID, userName, dateCreated, profileID, firstName, lastName, email,
                phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,
                isPicturePublic, sendReminders, stayLoggedIn from Users, UserProfiles
                where Users.userID = UserProfiles.userID"
            );
            $stmt->execute();
        
            foreach ($stmt as $row) {
                $uProfile = new UserProfile($row);
                if (!is_object($uProfile) || !empty($uProfile->getErrors()))
                    throw new PDOException("Failed to create valid user profile");
                
                $allUsers[] = $uProfile;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $allUsers;
    }
    
    // returns a UserProfile object whose $type field has value $value
    public static function getUserProfileBy($type, $value, $dbName = null, $configFile = null) {
        $allowed = ['profileID', 'userID', 'email', 'phone'];
        $uProfile = null;
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for UserProfile");
            
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare("select * from UserProfiles where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $uProfile = new UserProfile($row);
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $uProfile;
    }
    
    public static function getAllUserProfilesTest() {
        return UserProfilesDB::getAllUserProfiles('dhma_testDB', 'myConfig.ini');
    }
    
    public static function addUserProfileTest($uProfile, $userID) {
        return UserProfilesDB::addUserProfile($uProfile, $userID, 'dhma_testDB', 'myConfig.ini');
    }
    
    public static function getUserProfileByTest($type, $value) {
        return UserProfilesDB::getUserProfileBy($type, $value, 'dhma_testDB', 'myConfig.ini');
    }
    
}
?>