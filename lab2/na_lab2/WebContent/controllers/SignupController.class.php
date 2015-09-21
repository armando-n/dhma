<?php
class SignupController {
    public static function run() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") { // user is signing up
            $user = new User($_POST);
            $uData = new UserData($_POST);
            if ($user->getErrorCount() == 0 && $uData->getErrorCount == 0) {
                // sign up successful; show profile
                HeaderView::show("Member Sign Up", true);
                ProfileView::show($user, $uData);
                FooterView::show(true);
            }
            else { // sign up failed; load view w/old values
                HeaderView::show("Member Sign Up", false);
                SignupView::show($user, $uData);
                FooterView::show(false);
            }
        } else { // user is requesting signup page
            HeaderView::show("Member Sign Up", false);
            SignupView::show(null);
            FooterView::show(false);
        }
    }
}
?>