<?php
class UsersController {
    
    public static function run() {
        
        $profiles = UserProfilesDB::getAllUserProfiles();
        UsersView::show($profiles);
    }
    
    public static function runTest() {
        UsersController::run('dhma_testDB', 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini');
    }
}
?>