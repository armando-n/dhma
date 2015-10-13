<?php 
class ProfileView {
    
    public static function show($user = null, $uData = null, $edit = false) {
        if (is_null($user) || is_null($uData)):
            ?>
            <p>Error: unable to show profile. User data is missing.</p>
            <?php
            return;
        endif;
        $title = ($edit) ? "Edit Profile" : "Your Profile";
        
        HeaderView::show($title);
        
        if ($edit)
            ProfileView::showEditForm($user, $uData);
        else
            ProfileView::showProfile($user, $uData);
        
        FooterView::show();
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
    
    public static function showEditForm($user, $uData, $testing = false) {
        $genderMaleVal = ($uData->getGender() === 'male') ? ' checked="checked"' : '';
        $genderFemaleVal = ($uData->getGender() === 'female') ? ' checked="checked"' : '';
        $themeDarkVal = ($uData->getTheme() === 'dark') ? ' selected="selected"' : '';
        $themeLightVal = ($uData->getTheme() === 'light') ? ' selected="selected"' : '';
        $pubProfileVal = ($uData->isProfilePublic()) ? ' checked="checked"' : '';
        $pubPicVal = ($uData->isPicturePublic()) ? ' checked="checked"' : '';
        $remindVal = ($uData->isSendRemindersSet()) ? ' checked="checked"' : '';
        $stayLoggedVal = ($uData->isStayLoggedInSet()) ? ' checked="checked"' : '';
        ?>
<section id="profile-info">
    <h2><?= $user->getUserName() ?>'s Profile</h2>
    
    <img src="images/profile/<?= $user->getUserName() ?>.png" alt="<?= $user->getUserName() ?>'s profile picture" /><br />
    Change Picture: <input type="file" name="pic" accept="image/*" tabindex="13" />
    <form action="edit-profile" method="post">
    <ul>
        <li>First Name: <input type="text" name="fname" value="<?=$uData->getFirstName()?>" size="15" maxlength="30" tabindex="5" pattern="^[a-zA-Z_-]+$" title="Remove invalid characters" />
                <span class="error"><?=$uData->getError("firstName")?></span></li>
        <li>Last Name: <input type="text" name="lname" value="<?=$uData->getLastName()?>" size="15" maxlength="30" tabindex="6" pattern="(^$)|(^([^\-!#\$%\^\x26\(\)\*,\./:;\?@\[\\\]_\{\|\}¨ˇ“”€\+<=>§°\d\s¤®™©]| )+$)" title="Remove invalid characters" />
                <span class="error"><?=$uData->getError("lastName")?></span></li>
        <li>E-mail: <input type="email" name="email" value="<?=$uData->getEmail()?>" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" />
                <span class="error"><?=$uData->getError("email")?></span></li>
        <li>Phone #: <input type="tel" name="phone" value="<?=$uData->getPhoneNumber()?>" size="15" maxlength="15"  tabindex="7" placeholder="xxx-xxx-xxxx" pattern="^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$" title="xxx-xxx-xxx or x-xxx-xxx-xxxx"/>
                <span class="error"><?=$uData->getError("phone")?></span></li>
        <li>Gender: <input type="radio" id="male" name="gender" value="male"<?=$genderMaleVal?> tabindex="9" /> <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female"<?=$genderFemaleVal?> tabindex="10" /> <label for="female">Female</label>
                <span class="error"><?=$uData->getError("gender")?></span></li>
        <li>Facebook: <input type="url" name="facebook" value="<?=$uData->getFacebook()?>" size="30" tabindex="8" pattern="((http|https):\/\/)?(www\.)?facebook\.com\/.+" title="Must be a valid Facebook URL" />
                <span class="error"><?=$uData->getError("facebook")?></span></li>
        <li>Date of Birth: <input type="date" name="dob" value="<?=$uData->getDOB()?>" tabindex="11" title="mm/dd/yyyy or mm-dd-yyyy" />
                <span class="error"><?=$uData->getError("dob")?></span></li>
        <li>Country: <input type="text" name="country" list="country" value="<?=$uData->getCountry()?>" maxlength="30" tabindex="12" pattern="^[a-zA-Z& \{\}\(\)]{2,}$" title="Allowed characters: letters, spaces, &amp;, {}, ()" />
                <datalist id="country">
                    <option>Afghanistan</option>
                    <option>Albania</option>
                    <option>Algeria</option>
                    <option>Andorra</option>
                    <option>Angola</option>
                    <option>Antigua &amp; Deps</option>
                    <option>Argentina</option>
                    <option>Armenia</option>
                    <option>Australia</option>
                    <option>Austria</option>
                    <option>Azerbaijan</option>
                    <option>Bahamas</option>
                    <option>Bahrain</option>
                    <option>Bangladesh</option>
                    <option>Barbados</option>
                    <option>Belarus</option>
                    <option>Belgium</option>
                    <option>Belize</option>
                    <option>Benin</option>
                    <option>Bhutan</option>
                    <option>Bolivia</option>
                    <option>Bosnia Herzegovina</option>
                    <option>Botswana</option>
                    <option>Brazil</option>
                    <option>Brunei</option>
                    <option>Bulgaria</option>
                    <option>Burkina</option>
                    <option>Burundi</option>
                    <option>Cambodia</option>
                    <option>Cameroon</option>
                    <option>Canada</option>
                    <option>Cape Verde</option>
                    <option>Central African Rep</option>
                    <option>Chad</option>
                    <option>Chile</option>
                    <option>China</option>
                    <option>Colombia</option>
                    <option>Comoros</option>
                    <option>Congo</option>
                    <option>Congo {Democratic Rep}</option>
                    <option>Costa Rica</option>
                    <option>Croatia</option>
                    <option>Cuba</option>
                    <option>Cyprus</option>
                    <option>Czech Republic</option>
                    <option>Denmark</option>
                    <option>Djibouti</option>
                    <option>Dominica</option>
                    <option>Dominican Republic</option>
                    <option>East Timor</option>
                    <option>Ecuador</option>
                    <option>Egypt</option>
                    <option>El Salvador</option>
                    <option>Equatorial Guinea</option>
                    <option>Eritrea</option>
                    <option>Estonia</option>
                    <option>Ethiopia</option>
                    <option>Fiji</option>
                    <option>Finland</option>
                    <option>France</option>
                    <option>Gabon</option>
                    <option>Gambia</option>
                    <option>Georgia</option>
                    <option>Germany</option>
                    <option>Ghana</option>
                    <option>Greece</option>
                    <option>Grenada</option>
                    <option>Guatemala</option>
                    <option>Guinea</option>
                    <option>Guinea-Bissau</option>
                    <option>Guyana</option>
                    <option>Haiti</option>
                    <option>Honduras</option>
                    <option>Hungary</option>
                    <option>Iceland</option>
                    <option>India</option>
                    <option>Indonesia</option>
                    <option>Iran</option>
                    <option>Iraq</option>
                    <option>Ireland {Republic}</option>
                    <option>Israel</option>
                    <option>Italy</option>
                    <option>Ivory Coast</option>
                    <option>Jamaica</option>
                    <option>Japan</option>
                    <option>Jordan</option>
                    <option>Kazakhstan</option>
                    <option>Kenya</option>
                    <option>Kiribati</option>
                    <option>Korea North</option>
                    <option>Korea South</option>
                    <option>Kosovo</option>
                    <option>Kuwait</option>
                    <option>Kyrgyzstan</option>
                    <option>Laos</option>
                    <option>Latvia</option>
                    <option>Lebanon</option>
                    <option>Lesotho</option>
                    <option>Liberia</option>
                    <option>Libya</option>
                    <option>Liechtenstein</option>
                    <option>Lithuania</option>
                    <option>Luxembourg</option>
                    <option>Macedonia</option>
                    <option>Madagascar</option>
                    <option>Malawi</option>
                    <option>Malaysia</option>
                    <option>Maldives</option>
                    <option>Mali</option>
                    <option>Malta</option>
                    <option>Marshall Islands</option>
                    <option>Mauritania</option>
                    <option>Mauritius</option>
                    <option>Mexico</option>
                    <option>Micronesia</option>
                    <option>Moldova</option>
                    <option>Monaco</option>
                    <option>Mongolia</option>
                    <option>Montenegro</option>
                    <option>Morocco</option>
                    <option>Mozambique</option>
                    <option>Myanmar, {Burma}</option>
                    <option>Namibia</option>
                    <option>Nauru</option>
                    <option>Nepal</option>
                    <option>Netherlands</option>
                    <option>New Zealand</option>
                    <option>Nicaragua</option>
                    <option>Niger</option>
                    <option>Nigeria</option>
                    <option>Norway</option>
                    <option>Oman</option>
                    <option>Pakistan</option>
                    <option>Palau</option>
                    <option>Panama</option>
                    <option>Papua New Guinea</option>
                    <option>Paraguay</option>
                    <option>Peru</option>
                    <option>Philippines</option>
                    <option>Poland</option>
                    <option>Portugal</option>
                    <option>Qatar</option>
                    <option>Romania</option>
                    <option>Russian Federation</option>
                    <option>Rwanda</option>
                    <option>St Kitts &amp; Nevis</option>
                    <option>St Lucia</option>
                    <option>Saint Vincent &amp; the Grenadines</option>
                    <option>Samoa</option>
                    <option>San Marino</option>
                    <option>Sao Tome &amp; Principe</option>
                    <option>Saudi Arabia</option>
                    <option>Senegal</option>
                    <option>Serbia</option>
                    <option>Seychelles</option>
                    <option>Sierra Leone</option>
                    <option>Singapore</option>
                    <option>Slovakia</option>
                    <option>Slovenia</option>
                    <option>Solomon Islands</option>
                    <option>Somalia</option>
                    <option>South Africa</option>
                    <option>South Sudan</option>
                    <option>Spain</option>
                    <option>Sri Lanka</option>
                    <option>Sudan</option>
                    <option>Suriname</option>
                    <option>Swaziland</option>
                    <option>Sweden</option>
                    <option>Switzerland</option>
                    <option>Syria</option>
                    <option>Taiwan</option>
                    <option>Tajikistan</option>
                    <option>Tanzania</option>
                    <option>Thailand</option>
                    <option>Togo</option>
                    <option>Tonga</option>
                    <option>Trinidad &amp; Tobago</option>
                    <option>Tunisia</option>
                    <option>Turkey</option>
                    <option>Turkmenistan</option>
                    <option>Tuvalu</option>
                    <option>Uganda</option>
                    <option>Ukraine</option>
                    <option>United Arab Emirates</option>
                    <option>United Kingdom</option>
                    <option>United States</option>
                    <option>Uruguay</option>
                    <option>Uzbekistan</option>
                    <option>Vanuatu</option>
                    <option>Vatican City</option>
                    <option>Venezuela</option>
                    <option>Vietnam</option>
                    <option>Yemen</option>
                    <option>Zambia</option>
                    <option>Zimbabwe</option>
                </datalist></li>
        <li>Theme: <select name="theme" tabindex="14">
                    <option<?=$themeDarkVal?>>dark</option>
                    <option<?=$themeLightVal?>>light</option>
                </select>
                <span class="error"><?=$uData->getError("theme")?></span></li>
        <li>Theme Accent Color: <input type="color" name="color" value="<?=$uData->getAccentColor()?>" tabindex="15" />
                <span class="error"><?=$uData->getError("accentColor")?></span></li>
        <li><label for="public-profile">Profile Public:</label> <input type="checkbox" id="public-profile" name="public-profile"<?=$pubProfileVal?> tabindex="16" /></li>
        <li><label for="showpic">Picture Public:</label> <input type="checkbox" id="showpic" name="showpic"<?=$pubPicVal?> tabindex="17" /></li>
        <li><label for="reminders">E-mail Reminders:</label> <input type="checkbox" id="reminders" name="reminders"<?=$remindVal?> tabindex="18" /></li>
        <li><label for="keep-logged-in">Stay Logged In:</label> <input type="checkbox" id="keep-logged-in" name="keep-logged-in"<?=$stayLoggedVal?> tabindex="19" /></li>
    </ul>
    <div>
            <input type="submit" size="15" tabindex="20" />
            <a href="profile" tabindex="21">Cancel</a>
    </div>
    </form>
</section>
        <?php
    }
}
?>