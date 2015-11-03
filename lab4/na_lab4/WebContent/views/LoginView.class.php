<?php
class LoginView {
    
    public static function show() {
        HeaderView::show("Member Log In");
        LoginView::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        $userSet =          isset($_SESSION) && isset($_SESSION['user']);
        $loginFailedSet =   isset($_SESSION) && isset($_SESSION['loginFailed']);
        
        $uNameValue =   ($userSet)          ? $_SESSION['user']                 : ''; 
        $loginError =   ($loginFailedSet)   ? 'User name or password invalid'   : ''; 
        ?>
<section>
    <h2>Log In</h2>
    
    <form action="login_login" method="post">
        <fieldset>
            <legend>Log In</legend>
            <!-- Pattern attribute and specific error reporting absent to avoid hints that weaken security -->
            <div class="error"><?=$loginError?></div>
            User Name <input type="text" name="userName" value="<?=$uNameValue?>" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" /><br />
            Password <input type="password" name="password" size="15" required="required" maxlength="30" tabindex="2" />
        </fieldset>
        <div>
            <label for="submitLogin" class="btn btn-primary">
                <span class="glyphicon glyphicon-ok"></span>
                &nbsp;Submit
            </label>
            <input type="submit" id="submitLogin" class="hidden" value="Submit" tabindex="3" />
            <a href="home" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-remove"></span>
                &nbsp;Cancel
            </a>
        </div>
    </form>
</section>
<?php
        if (isset($_SESSION)) {
            unset($_SESSION['loginFailed']);
            unset($_SESSION['userNameNotFound']);
            unset($_SESSION['user']);
        }
    }
}

?>