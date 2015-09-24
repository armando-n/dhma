<?php
class LoginController {
    public static function run() {
        
        // user logging in
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $user = new User($_POST);
            
            // log in successful; go back honme
            if ($user->getErrorCount() == 0)
                HomeView::show($user);
            
            // log in failed; load view w/old values
            else
                LoginView::show($user);
        }
        
        // user requesting login page
        else
            LoginView::show(null);
    }
}
?>