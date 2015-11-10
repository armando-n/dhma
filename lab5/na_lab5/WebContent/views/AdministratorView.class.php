<?php
class AdministratorView {
    
    private static $imgDir = 'images/profile/';
    
    // takes an array of UserProfile objects
    public static function show($uProfiles = null) {
        if (!isset($_SESSION['styles']))
            $_SESSION['styles'] = array();
        $_SESSION['styles'][] = 'AdministratorStyles.css';
        if (!isset($_SESSION['scripts']))
            $_SESSION['scripts'] = array();
        $_SESSION['scripts'][] = 'AdministratorScripts.js';
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
    
        <div class="myscrollable">
        
            <table id="membertable" class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Facebook</th>
                    </tr>
                </thead>
                
                <tbody><?php
        foreach ($uProfiles as $profile): ?>
                    <tr>
                        <td>
                            <?php if ($isLoggedIn): ?><a href="profile_show_<?=$profile->getUserName()?>"><?php endif; ?>
                                <img src="<?= 'http://' . $host_base . '/' . self::$imgDir . $profile->getPicture()?>" class="img-circle" alt="<?=$profile->getUserName()?>'s profile picture" width="45" height="45" />
                            <?php if ($isLoggedIn): ?></a><?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isLoggedIn): ?><a href="profile_show_<?=$profile->getUserName()?>"><?php endif; ?>
                                <?= $profile->getFirstName() ?> <?=$profile->getLastName() ?>
                            <?php if ($isLoggedIn): ?></a><?php endif; ?>
                        </td>
                        <td><?=$profile->getGender()?></td>
                        <td><?php
            if (!empty($profile->getFacebook())): ?>
                            <a href="<?= $profile->getFacebook()?>"><img src="<?= 'http://' . $host_base . '/images/icon_facebook.png' ?>" class="img-responsive" alt="<?=$profile->getUserName()?>'s Facebook page" /></a>
                        </td><?php
            endif; ?>
                    </tr><?php
        endforeach; ?>
                </tbody>
                
            </table>
            
        </div>
    </div>
</section>


<?php
        return true;
    }
}
?>