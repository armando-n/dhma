<?php
class UsersView {
    
    // takes an array of UserProfile objects
    public static function show($uProfiles = null) {
        HeaderView::show("Members");
        $return = UsersView::showBody($uProfiles);
        FooterView::show();
        return $return;
    }
    
    public static function showBody($uProfiles) {
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
        
        <table class="table table-striped table-condensed table-responsive">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Country</th>
                    <th>Picture</th>
                    <th>Facebook</th>
                    <th>Theme</th>
                    <th>Theme Accent Color</th>
                    <th>Profile Public</th>
                    <th>Picture Public</th>
                    <th>Send Reminders</th>
                    <th>Stay Logged In</th>
                </tr>
            </thead>
            
            <tbody>
<?php
        foreach ($uProfiles as $profile):
            ?>
                <tr><?php
            if (isset($_SESSION) && isset($_SESSION['profile'])): ?>
                    <td><a href="profile_show_<?=$profile->getUserName()?>"><?=$profile->getFirstName()?> <?=$profile->getLastName()?></a></td><?php
            else: ?>
                    <td><?=$profile->getFirstName()?> <?=$profile->getLastName()?></td><?php
            endif; ?>
                    <td><?=$profile->getEmail()?></td>
                    <td><?=$profile->getGender()?></td>
                    <td><?=$profile->getDOB()?></td>
                    <td><?=$profile->getCountry()?></td>
                    <td><?=$profile->getPicture()?></td>
                    <td><?=$profile->getFacebook()?></td>
                    <td><?=$profile->getTheme()?></td>
                    <td><?=$profile->getAccentColor()?></td>
                    <td><?php
            if ($profile->isProfilePublic()): ?>
                        <span class="glyphicon glyphicon-ok text-center"></span><?php
            endif; ?>
                    </td>
                    <td><?php
            if ($profile->isPicturePublic()): ?>
                        <span class="glyphicon glyphicon-ok text-center"></span><?php
            endif; ?>
                    </td>
                    <td><?php
            if ($profile->isSendRemindersSet()): ?>
                        <span class="glyphicon glyphicon-ok text-center"></span><?php
            endif; ?>
                    </td>
                    <td><?php
            if ($profile->isStayLoggedInSet()): ?>
                        <span class="glyphicon glyphicon-ok text-center"></span><?php
            endif; ?>
                    </td>
                </tr>
<?php
        endforeach;
        ?>
            </tbody>
            
        </table>
    </div>
</section>


<?php
        return true;
    }
}
?>