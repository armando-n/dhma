<?php
class UsersDB {
    
    // adds the specified User object to the database
    public static function addUser($user, $dbName = null, $configFile = null) {
        $returnUserID = -1;
        
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare(
                "insert into Users (userName, password)
                    values (:userName, :password)");
            $stmt->execute(array(
                ":userName" => $user->getUserName(),
                ":password" => $user->getPassword()
            ));
            $returnUserID = $db->lastInsertId("userID");
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $returnUserID;
    }
    
    // returns an array of User objects for all users in the database
    public static function getAllUsers($dbName = null, $configFile = null) {
        $allUsers = array();
        
        try {
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare("select * from Users");
            $stmt->execute();
            
            foreach ($stmt as $row) {
                $user = new User($row);
                if (!is_object($user) || !empty($user->getErrors()))
                    throw new PDOException("Failed to create valid user");
                
                $allUsers[] = $user;
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $allUsers;
    }
    
    // returns a User object whose $type field has value $value
    public static function getUserBy($type, $value, $dbName = null, $configFile = null) {
        $allowed = array('userID', 'userName');
        $user = null;
        
        try {
            if (!in_array($type, $allowed))
                throw new PDOException("$type not allowed search criterion for User");
            
            $db = Database::getDB($dbName, $configFile);
            $stmt = $db->prepare("select * from Users where ($type = :$type)");
            $stmt->execute(array(":$type" => $value));
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false)
                $user = new User($row);
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
        
        return $user;
    }
    
    public static function addUserTest($user) {
        return UsersDB::addUser($user, 'dhma_testDB', 'na_lab3/myConfig.ini');
    }
    
    public static function getAllUsersTest() {
        return UsersDB::getAllUsers('dhma_testDB', 'na_lab3/myConfig.ini');
    }
    
    public static function getUserByTest($type, $value) {
        return UsersDB::getUserBy($type, $value, 'dhma_testDB', 'na_lab3/myConfig.ini');
    }
}
?>