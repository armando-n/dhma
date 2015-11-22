<?php
class UsersController {
    
    public static function run() {
        
        if (!isset($_SESSION) || !isset($_SESSION['action'])) {
            $profiles = UserProfilesDB::getAllUserProfiles();
            UsersView::show($profiles);
            return;
        }
        
        switch (strtolower($_SESSION['action'])) {
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