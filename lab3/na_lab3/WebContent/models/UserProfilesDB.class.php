<?php
class UserProfilesDB {
    
    // adds the specified UserProfile object to the database, associating it with the specified userID
    public static function addUserProfile($uProfile, $userID) {
        $returnProfileID = -1;
        
        try {
            $db = Database::getDB();
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
    
    public static function editUserProfile($oldProfile, $newProfile) {
        try {
            $db = Database::getDB();
            $oldParams = $oldProfile->getParameters();
            $newParams = $newProfile->getParameters();
            $numParams = count($oldProfile);
            
            foreach ($newParams as $key => $value) {
                
                if (!array_key_exists($key, $oldParams))
                    throw new PDOException('Key ' . htmlspecialchars($key) . ' is invalid');
                
                if ($oldParams[$key] !== $newParams[$key]) {
                    $stmt = $db->prepare(
                        "update UserProfiles set $key = :value
                         where userID in
                            (select userID from Users where userName = :userName)");
                    $stmt->execute(array(
                        ":value" => $value,
                        "userName" => $newParams['userName']
                    ));
                }
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }
    
    // returns an array of UserProfile objects for all user profiles in the database
    public static function getAllUserProfiles() {
        $allUsers = array();
        
        try {
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userID, userName, dateCreated, profileID, firstName, lastName, email,
                phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,
                isPicturePublic, sendReminders, stayLoggedIn from Users join UserProfiles using (userID)"
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
    public static function getUserProfileBy($type, $value) {
        $allowed = ['profileID', 'userID', 'email', 'phone', 'userName', 'dateCreated'];
        $uProfile = null;
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for UserProfile");
            
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userID, userName, dateCreated, profileID, firstName, lastName, email,
                    phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,
                    isPicturePublic, sendReminders, stayLoggedIn from Users join UserProfiles using (userID)
                where ($type = :$type)");
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
    
    public static function getAllUserProfilesSortedByDateCreated($order) {
        $allowedOrders = array('asc', 'desc');
        $allProfiles = array();
    
        try {
            if (!in_array($order, $allowedOrders))
                throw new Exception("$order is not an allowed order");
    
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userID, userName, dateCreated, profileID, firstName, lastName, email,
                    phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,
                    isPicturePublic, sendReminders, stayLoggedIn from Users join UserProfiles using (userID)
                order by dateCreated $order");
            $stmt->execute();
    
            foreach ($stmt as $row) {
                $profile = new UserProfile($row);
                if (!is_object($profile) || !empty($profile->getErrors()))
                    throw new PDOException("Failed to create valid user profile");
    
                $allProfiles[] = $profile;
            }
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    
        return $allProfiles;
    }
    
    // returns an array of UserProfile objects created since the specified date string
    public static function getUserProfilesCreatedSince($dateString) {
        return UserProfilesDB::getUserProfilesByDate($dateString, 'after');
    }
    
    // returns an array of UserProfile objects created by the specified date string
    public static function getUserProfilesCreatedBy($dateString) {
        return UserProfilesDB::getUserProfilesByDate($dateString, 'before');
    }
    
    private static function getUserProfilesByDate($dateString, $direction) {
        $allowedDirections = array('before', 'after');
        $profiles = array();
    
        try {
            if (!in_array($direction, $allowedDirections))
                throw new PDOException("$direction is not an allowed direction");
            $operator = ($direction === 'before') ? '<=' : '>=';
    
            $datetime = new DateTime($dateString);
            $db = Database::getDB();
            $stmt = $db->prepare(
                "select userID, userName, dateCreated, profileID, firstName, lastName, email,
                    phone, gender, dob, country, picture, facebook, theme, accentColor, isProfilePublic,
                    isPicturePublic, sendReminders, stayLoggedIn from Users join UserProfiles using (userID)
                where dateCreated $operator :date");
            $stmt->execute(array(":date" => $datetime->format('Y-m-d')));
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rows !== false)
                foreach ($rows as $row)
                    $profiles[] = new UserProfile($row);
    
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        } catch (Exception $e) {
            if (stristr($e->getMessage(), 'Failed to parse') !== false)
                throw new Exception("Invalid date: $dateString");
            else
                throw $e;
        }
    
        return $profiles;
    }
    
}
?>