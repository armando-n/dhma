<?php
class AdministratorView {
    
    private static $imgDir = 'images/profile/';
    
    // takes an array of UserProfile objects
    public static function show($uProfiles = null) {
        if (!isset($_SESSION['styles']))
            $_SESSION['styles'] = array();
        $_SESSION['styles'][] = 'AdministratorStyles.css';
        $_SESSION['styles'][] = '../lib/datatables/datatables.css';
        if (!isset($_SESSION['scripts']))
            $_SESSION['scripts'] = array();
        $_SESSION['scripts'][] = 'AdministratorScripts.js';
        if (!isset($_SESSION['libraries']))
            $_SESSION['libraries'] = array();
        $_SESSION['libraries'][] = 'datatables/datatables.js';
        HeaderView::show("Administator Page");
        $return = AdministratorView::showBody($uProfiles);
        FooterView::show();
        return $return;
    }
    
    public static function showBody($uProfiles) {
        $host_base = $_SERVER['HTTP_HOST'].'/'.$_SESSION['base'];
        $isLoggedIn = isset($_SESSION) && isset($_SESSION['profile']);
        
        if (is_null($uProfiles)):
            ?><p>Error: user profiles not found</p><?php
            return false;
        elseif (empty($uProfiles)):
            ?><p>No users exist yet</p><?php
            return true;
        endif;
        ?>
<section class="row">
    <div class="col-sm-12">
            
        <table id="membertable" class="table table-striped table-condensed">
        </table>

    </div>
</section>


<?php
        return true;
    }
}
?>