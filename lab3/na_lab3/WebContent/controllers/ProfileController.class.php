<?php
class ProfileController {
    
    public static function run($user = null, $uData = null, $edit = false) {
        
        // user is editing profile
        if ($edit === true) {
            
            // data passed in post vars, instead of in parameters
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $validUserInput = array(
                        "userName" => "armando-n",
                        "password1" => "password123",
                        "password2" => "password123"
                );
                $user = new User($validUserInput);
                $uData = new UserProfile($_POST);
                
                // edit successful; show profile
                if ($user->getErrorCount() == 0 && $uData->getErrorCount() == 0)
                    ProfileView::show($user, $uData, false);
                
                // edit failed; load view w/old values
                else
                    ProfileView::show($user, $uData, true);
                
            }
            
            // data passed in parameters
            else if (!is_null($user) && !is_null($uData))
                ProfileView::show($user, $uData, true);
            
        }
        
        // user is viewing profile
        else
            ProfileView::show($user, $uData, false);
    }
    
}
?>