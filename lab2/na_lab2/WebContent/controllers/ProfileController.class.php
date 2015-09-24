<?php
class ProfileController {
    
    public static function run($user, $uData) {
        
        // user is editing profile
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = new User($_POST);
            $uData = new UserData($_POST);
        
            // edit successful; show profile
            if ($user->getErrorCount() == 0 && $uData->getErrorCount == 0)
                ProfileView::show($user, $uData, true);
        
            // edit failed; load view w/old values
            else
                ProfileView::show($user, $uData);
        
        }
        
        // user is viewing profile
        else
            ProfileView::show($user, $uData);
    }
    
}
?>