<?php
class UsersView {
    
    // takes an array of UserProfile objects
    public static function show($uProfiles = null, $loggedIn = false) {
        HeaderView::show("User List", $loggedIn);
        UsersView::showBody($uProfiles);
        FooterView::show($loggedIn);
    }
    
    public static function showBody($uProfiles) {
        if (is_null($uProfiles)) {
            ?><p>Error: user profiles not found</p><?php
            return;
        } else if (empty($uProfiles)) {
            ?><p>No users exist yet</p><?php
            return;
        }
        ?>
<section>
    <h2>Users</h2>
    
    <table>
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
<?php
        foreach ($uProfiles as $profile) {
            ?>
        <tr>
            <td><?=$profile->getFirstName()?> <?=$profile->getLastName()?></td>
            <td><?=$profile->getEmail()?></td>
            <td><?=$profile->getGender()?></td>
            <td><?=$profile->getDOB()?></td>
            <td><?=$profile->getCountry()?></td>
            <td><?=$profile->getPicture()?></td>
            <td><?=$profile->getFacebook()?></td>
            <td><?=$profile->getTheme()?></td>
            <td><?=$profile->getAccentColor()?></td>
            <td><?=$profile->isProfilePublic() ? 'yes' : 'no'?></td>
            <td><?=$profile->isPicturePublic() ? 'yes' : 'no'?></td>
            <td><?=$profile->isSendRemindersSet() ? 'yes' : 'no'?></td>
            <td><?=$profile->isStayLoggedInSet() ? 'yes' : 'no'?></td>
        </tr>
<?php
        }
        ?>
    </table>
</section>


<?php
    }
}
?>