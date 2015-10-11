<?php
class UsersController {
    
    public static function run($loggedIn = false, $dbName = null, $configFile = null) {
        
        $profiles = UserProfilesDB::getAllUserProfiles($dbName, $configFile);
        
        UsersView::show($profiles, $loggedIn);
    }
    
    public static function runTest($loggedIn = false) {
        UsersController::run($loggedIn, 'dhma_testDB', 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini');
    }
}
?>