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
    <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
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
                <div class="btn-group btn-group-justified" role="group">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary" tabindex="3">
                            <span class="glyphicon glyphicon-ok"></span>
                            &nbsp;Submit
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <a href="home" class="btn btn-default">
                            <span class="glyphicon glyphicon-remove"></span>
                            &nbsp;Cancel
                        </a>
                    </div>
                </div>
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