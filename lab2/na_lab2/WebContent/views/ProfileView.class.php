<?php 
class ProfileView {
    
    public static function show($user = null, $uData = null, $edit = null) {
        if (is_null($edit)) {
            HeaderView::show("Your Profile", true);
            ProfileView::showProfile($user, $uData);
            FooterView::show(true);
        } else {
            HeaderView::show("Edit Profile", true);
            ProfileView::showEditForm($user, $uData);
            FooterView::show(true);
        }
    }
    
    public static function showProfile($user, $uData) {
        ?>
<section id="profile-info">
    <h2><?= $user->getUserName() ?>'s Profile</h2>
    <img src="images/profile/<?= $user->getUserName() ?>.png" alt="<?= $user->getUserName() ?>'s profile picture" />
    <ul>
        <li>First Name: <?= $uData->getFirstName() ?></li>
        <li>Last Name: <?= $uData->getLastName() ?></li>
        <li>E-mail: <?= $uData->getEmail() ?></li>
        <li>Phone #: <?= $uData->getPhoneNumber() ?></li>
        <li>Gender: <?= $uData->getGender() ?></li>
        <li>Facebook: <?= $uData->getFacebook() ?></li>
        <li>Date of Birth: <?= $uData->getDOB() ?></li>
        <li>Country: <?= $uData->getCountry() ?></li>
        <li>Theme: <?= $uData->getTheme() ?></li>
        <li>Theme Accent Color: <?= $uData->getAccentColor() ?></li>
        <li>Profile Public: <?= $uData->isProfilePublic() ? "yes" : "no" ?></li>
        <li>Picture Public: <?= $uData->isPicturePublic() ? "yes" : "no" ?></li>
        <li>E-mail Reminders: <?= $uData->isSendRemindersSet() ? "yes" : "no" ?></li>
        <li>Stay Logged In: <?= $uData->isStayLoggedInSet() ? "yes" : "no" ?></li>
    </ul>
    <a href="edit-profile">Edit Profile</a>
</section>
<?php 
    }
    
    public static function showEditForm($user, $uData) {
        
    }
}
?>