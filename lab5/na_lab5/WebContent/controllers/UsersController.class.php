<?php
class UsersController {
    
    public static function run() {
        
        if (!isset($_SESSION) || !isset($_SESSION['action'])) {
            $profiles = UserProfilesDB::getAllUserProfiles();
            UsersView::show($profiles);
            return;
        }
        
        switch (strtolower($_SESSION['action'])) {
            case 'get': self::getUser(); break;
            case 'getall': self::getAllUsers(); break;
            case 'delete': self::deleteUser(); break;
            default:
                $profiles = UserProfilesDB::getAllUserProfiles();
                UsersView::show($profiles);
        }
    }
    
    public static function runTest() {
        UsersController::run('dhma_testDB', 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini');
    }
    
    public static function getAllUsers() {
        $profiles = UserProfilesDB::getAllUserProfiles();
        echo json_encode($profiles, JSON_PRETTY_PRINT);
    }
    
    public static function getUser() {
        if (!isset($_SESSION['arguments'])) {
            echo '{"error":"Missing user name argument"}';
            return;
        }
        
        $user = UsersDB::getUserBy('userName', $_SESSION['arguments']);
        if (is_null($user)) {
            echo '{"error":"Either user doesn\'t exist or an error occured"}';
            return;
        }
        
        echo json_encode($user);
    }
    
    public function deleteUser() {
        if (isset($_SESSION['arguments'])) {
            if (strpos($_SESSION['arguments'], '_') !== false) {
                $userNames = explode('_', $_SESSION['arguments']);
                foreach ($userNames as $uName)
                    UsersDB::deleteUser($uName);
            }
            else
                UsersDB::deleteUser($_SESSION['arguments']);
        }
    }

}
?>