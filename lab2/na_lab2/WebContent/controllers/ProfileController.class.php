<?php
class ProfileController {
    
    public static function run($user = null, $uData = null, $edit = false) {
        
        // user is editing profile
        if ($edit === true) {
            
            // data passed in post vars, instead of as parameters
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $user = new User($_POST);
                $uData = new UserData($_POST);
                
                // edit successful; show profile
                if ($user->getErrorCount() == 0 && $uData->getErrorCount == 0)
                    ProfileView::show($user, $uData, false);
                
                // edit failed; load view w/old values
                else
                    ProfileView::show($user, $uData, true);
                
            } else if (!is_null($user) && !is_null($uData)) {
                
                // edit successful; show profile
                if ($user->getErrorCount() == 0 && $uData->getErrorCount == 0)
                    ProfileView::show($user, $uData, true);
                
                // edit failed; load view w/old values
                else
                    ProfileView::show($user, $uData, true);
                
            }
        
            
        }
        
        // user is viewing profile
        else
            ProfileView::show($user, $uData, false);
    }
    
}
?>