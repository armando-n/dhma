<?php
class SignupController {
    
    public static function run() {
        
        // user is signing up
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = new User($_POST);
            $uData = new UserProfile($_POST);
            
            // sign up successful; show profile
            if ($user->getErrorCount() == 0 && $uData->getErrorCount() == 0)
                ProfileView::show($user, $uData);
            
            // sign up failed; load view w/old values
            else
                SignupView::show($user, $uData);
            
        }
        
        // user is requesting signup page
        else
            SignupView::show(null, null);
    }
}
?>