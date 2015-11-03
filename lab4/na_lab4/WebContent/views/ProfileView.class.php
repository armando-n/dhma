<?php 
class ProfileView {
    
    public static function showProfile($profile) {
        if (is_null($profile) || !($profile instanceof UserProfile) || $profile->getErrorCount() > 0) {
            ?><p>Error: profile has errors or is invalid and cannot be shown</p><?php
            return;
        }
        HeaderView::show($profile->getUserName() . '\'s Profile');
        ?>
<section id="profile-info">
    <h2><?= $profile->getUserName() ?>'s Profile</h2>
    <img src="images/profile/<?= $profile->getUserName() ?>.png" alt="<?= $profile->getUserName() ?>'s profile picture" />
    <ul>
        <li>First Name: <?= $profile->getFirstName() ?></li>
        <li>Last Name: <?= $profile->getLastName() ?></li>
        <li>E-mail: <?= $profile->getEmail() ?></li>
        <li>Phone #: <?= $profile->getPhoneNumber() ?></li>
        <li>Gender: <?= $profile->getGender() ?></li>
        <li>Facebook: <?= $profile->getFacebook() ?></li>
        <li>Date of Birth: <?= $profile->getDOB() ?></li>
        <li>Country: <?= $profile->getCountry() ?></li>
        <li>Theme: <?= $profile->getTheme() ?></li>
        <li>Theme Accent Color: <?= $profile->getAccentColor() ?></li>
        <li>Profile Public: <?= $profile->isProfilePublic() ? "yes" : "no" ?></li>
        <li>Picture Public: <?= $profile->isPicturePublic() ? "yes" : "no" ?></li>
        <li>E-mail Reminders: <?= $profile->isSendRemindersSet() ? "yes" : "no" ?></li>
        <li>Stay Logged In: <?= $profile->isStayLoggedInSet() ? "yes" : "no" ?></li>
    </ul><?php
    if (isset($_SESSION) && isset($_SESSION['profile']) && $_SESSION['profile']->getUserName() == $profile->getUserName()): ?>
    <a href="profile_edit_show" class="btn btn-info btn-sm">
        <span class="glyphicon glyphicon-pencil"></span>
        &nbsp;Edit Profile
    </a><?php
    endif; ?>
</section>
<?php
        FooterView::show();
    }
    
    public static function showEditForm() {
        if (isset($_SESSION['profileEdit']))
            $profile = $_SESSION['profileEdit'];
        else if (isset($_SESSION['profile']))
            $profile = $_SESSION['profile'];
        else
            throw new Exception('Error: profile not found. Unable to edit profile.');
        
        HeaderView::show($profile->getUserName() . '\'s Profile');
        
        $genderMaleVal = ($profile->getGender() === 'male') ? ' checked="checked"' : '';
        $genderFemaleVal = ($profile->getGender() === 'female') ? ' checked="checked"' : '';
        $themeDarkVal = ($profile->getTheme() === 'dark') ? ' selected="selected"' : '';
        $themeLightVal = ($profile->getTheme() === 'light') ? ' selected="selected"' : '';
        $pubProfileVal = ($profile->isProfilePublic()) ? ' checked="checked"' : '';
        $pubPicVal = ($profile->isPicturePublic()) ? ' checked="checked"' : '';
        $remindVal = ($profile->isSendRemindersSet()) ? ' checked="checked"' : '';
        $stayLoggedVal = ($profile->isStayLoggedInSet()) ? ' checked="checked"' : '';
        ?>
<section id="profile-info">
    <h2><?= $profile->getUserName() ?>'s Profile</h2>
    
    <img src="images/profile/<?=$profile->getUserName()?>.png" alt="<?=$profile->getUserName()?>'s profile picture" /><br />
    <form action="profile_edit_post" enctype="multipart/form-data" method="post">
    <label for="choosePicture" class="btn btn-info btn-sm">
        <span class="glyphicon glyphicon-user"></span>
        &nbsp;Change Profile Picture
    </label>
    <input type="file" id="choosePicture" class="hidden" name="pic" accept="image/*" tabindex="13" />
    <ul>
        <li>First Name: <input type="text" name="firstName" value="<?=$profile->getFirstName()?>" size="15" maxlength="30" tabindex="5" pattern="^[a-zA-Z_-]+$" title="Remove invalid characters" />
                <span class="error"><?=$profile->getError("firstName")?></span></li>
        <li>Last Name: <input type="text" name="lastName" value="<?=$profile->getLastName()?>" size="15" maxlength="30" tabindex="6" pattern="(^$)|(^([^\-!#\$%\^\x26\(\)\*,\./:;\?@\[\\\]_\{\|\}¨ˇ“”€\+<=>§°\d\s¤®™©]| )+$)" title="Remove invalid characters" />
                <span class="error"><?=$profile->getError("lastName")?></span></li>
        <li>E-mail: <input type="email" name="email" value="<?=$profile->getEmail()?>" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" />
                <span class="error"><?=$profile->getError("email")?></span></li>
        <li>Phone #: <input type="tel" name="phone" value="<?=$profile->getPhoneNumber()?>" size="15" maxlength="15"  tabindex="7" placeholder="xxx-xxx-xxxx" pattern="^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$" title="xxx-xxx-xxx or x-xxx-xxx-xxxx"/>
                <span class="error"><?=$profile->getError("phone")?></span></li>
        <li>Gender: <input type="radio" id="male" name="gender" value="male"<?=$genderMaleVal?> tabindex="9" /> <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female"<?=$genderFemaleVal?> tabindex="10" /> <label for="female">Female</label>
                <span class="error"><?=$profile->getError("gender")?></span></li>
        <li>Facebook: <input type="url" name="facebook" value="<?=$profile->getFacebook()?>" size="30" tabindex="8" pattern="((http|https):\/\/)?(www\.)?facebook\.com\/.+" title="Must be a valid Facebook URL" />
                <span class="error"><?=$profile->getError("facebook")?></span></li>
        <li>Date of Birth: <input type="date" name="dob" value="<?=$profile->getDOB()?>" tabindex="11" title="mm/dd/yyyy or mm-dd-yyyy" />
                <span class="error"><?=$profile->getError("dob")?></span></li>
        <li>Country: <input type="text" name="country" list="country" value="<?=$profile->getCountry()?>" maxlength="30" tabindex="12" pattern="^[a-zA-Z& \{\}\(\)]{2,}$" title="Allowed characters: letters, spaces, &amp;, {}, ()" />
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
                <span class="error"><?=$profile->getError("theme")?></span></li>
        <li>Theme Accent Color: <input type="color" class="btn btn-default btn-sm" name="accentColor" value="<?=$profile->getAccentColor()?>" tabindex="15" />
                <span class="error"><?=$profile->getError("accentColor")?></span></li>
        <li><label for="isProfilePublic">Profile Public:</label> <input type="checkbox" id="isProfilePublic" name="isProfilePublic"<?=$pubProfileVal?> tabindex="16" /></li>
        <li><label for="isPicturePublic">Picture Public:</label> <input type="checkbox" id="isPicturePublic" name="isPicturePublic"<?=$pubPicVal?> tabindex="17" /></li>
        <li><label for="sendReminders">E-mail Reminders:</label> <input type="checkbox" id="sendReminders" name="sendReminders"<?=$remindVal?> tabindex="18" /></li>
        <li><label for="stayLoggedIn">Stay Logged In:</label> <input type="checkbox" id="stayLoggedIn" name="stayLoggedIn"<?=$stayLoggedVal?> tabindex="19" /></li>
    </ul>
    <div>
        <input type="hidden" name="userName" value="<?=$profile->getUserName()?>" />
        <label for="submitSave" class="btn btn-primary">
            <span class="glyphicon glyphicon-ok"></span>
            &nbsp;Save Changes
        </label>
        <input type="submit" id="submitSave" class="hidden" value="Save Changes" size="15" tabindex="20" />
        <a href="profile_show" class="btn btn-default btn-sm" tabindex="21">
            <span class="glyphicon glyphicon-remove"></span>
            &nbsp;Cancel
        </a>
    </div>
    </form>
</section>
        <?php
        FooterView::show();
    }
}
?>