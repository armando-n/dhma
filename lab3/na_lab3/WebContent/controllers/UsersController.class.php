<?php
class UsersController {
    
    public static function run($loggedIn = false) {
        
        $profiles = UserProfilesDB::getAllUserProfiles();
        
        UsersView::show($profiles, $loggedIn);
    }
}
?>