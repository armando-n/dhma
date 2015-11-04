<?php
class LoginView {
    
    public static function show() {
        HeaderView::show("Member Log In");
        LoginView::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        $userSet = isset($_SESSION) && isset($_SESSION['user']);
        $uNameValue = ($userSet) ? $_SESSION['user'] : '';  
        ?>
<section class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <h2 class="hidden">Log In Form</h2>
        
        <!-- Pattern attribute and specific error reporting absent to avoid hints that weaken security -->
        <form action="login_login" method="post">
            <div class="form-group">
                <label for="userName">User Name</label>
                <input type="text" id="userName" name="userName" value="<?=$uNameValue?>" class="form-control" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" /><br />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" size="15" required="required" maxlength="30" tabindex="2" />
            </div>
            <div class="form-group">
                <label for="submitLogin" class="btn btn-primary btn-sm">
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
    </div>
</section>
<?php
        if (isset($_SESSION)) {
            unset($_SESSION['userNameNotFound']);
            unset($_SESSION['user']);
        }
    }
}

?>