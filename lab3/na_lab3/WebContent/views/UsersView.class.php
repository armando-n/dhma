<?php
class UsersView {
    
    // takes an array of UserProfile objects
    public static function show($users, $loggedIn = false) {
        HeaderView::show("User List", $loggedIn);
        UsersView::show($users);
        FooterView::show($loggedIn);
    }
    
    public static function showBody($users) {
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
        foreach ($user as $users) {
            ?>
        <tr>
            <td><?=$user->getFirstName()?> <?=$user->getLastName()?></td>
            <td><?=$user->getEmail()?></td>
            <td><?=$user->getGender()?></td>
            <td><?=$user->getDOB()?></td>
            <td><?=$user->getCountry()?></td>
            <td><?=$user->getPicture()?></td>
            <td><?=$user->getFacebook()?></td>
            <td><?=$user->getTheme()?></td>
            <td><?=$user->getAccentColor()?></td>
            <td><?=$user->isProfilePublic()?></td>
            <td><?=$user->isPicturePublic()?></td>
            <td><?=$user->isSendRemdersSet()?></td>
            <td><?=$user->isStayLoggedInSet()?></td>
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