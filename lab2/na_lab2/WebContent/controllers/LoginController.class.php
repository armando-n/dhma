<?php
class LoginController {
    public static function run() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") { // user logging in
            $user = new User($_POST);
            if ($user->getErrorCount() == 0) {
                HeaderView::show("Diabetic Health Management Assistant", true);
                HomeView::show(); // log in successful; go back honme
                FooterView::show(true);
            }
            else {
                HeaderView::show("Member Log In", false);
                LoginView::show($user); // log in failed; load view w/old values
                FooterView::show(false);
            }
        } else {
            HeaderView::show("Member Log In", false);
            LoginView::show(null); // user requesting login page
            FooterView::show(false);
        }
    }
}
?>