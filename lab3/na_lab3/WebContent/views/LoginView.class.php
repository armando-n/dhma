<?php
class LoginView {
    
    public static function show() {
        HeaderView::show("Member Log In");
        LoginView::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        $uNameValue = !isset($_SESSION['user']) ? '' : $_SESSION['user']->getUserName();
        $uNameError = !isset($_SESSION['user']) ? '' : $_SESSION['user']->getError("userName");
        ?>
<section>
    <h2>Log In</h2>
    <form action="login_login" method="post">
        <fieldset>
            <legend>Log In</legend>
            <!-- Pattern attribute and specific error reporting absent to avoid hints that weaken security -->
<?php
            if (isset($_SESSION['loginFailed'])): ?>
            <div class="error">User name or password invalid</div>
<?php
            endif; ?>
            User Name <input type="text" name="userName" value="<?=$uNameValue?>" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" />
            <span class="error"><?=$uNameError?></span><br />
            Password <input type="password" name="password" size="15" required="required" maxlength="30" tabindex="2" />
        </fieldset>
        <div>
            <input type="submit" tabindex="3" />
        </div>
    </form>
</section>
<?php
    }
}

?>