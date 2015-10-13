<?php
if (!isset($_SESSION))
    session_start();

class UsersController {
    
    public static function run($dbName = null, $configFile = null) {
        
        $profiles = UserProfilesDB::getAllUserProfiles($dbName, $configFile);
        UsersView::show($profiles);
    }
    
    public static function runTest() {
        UsersController::run('dhma_testDB', 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini');
    }
}
?>