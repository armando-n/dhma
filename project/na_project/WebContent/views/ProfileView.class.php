<?php 
class ProfileView {
    
    private static $imgDir = 'images/profile/';
    
    public static function showProfile($profile) {
        //self::$imgDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dhma_images' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR;
        
        if (is_null($profile) || !($profile instanceof UserProfile) || $profile->getErrorCount() > 0) {
            ?><p>Error: profile has errors or is invalid and cannot be shown</p><?php
            return;
        }
        
        $host_base = $_SERVER['HTTP_HOST'].'/'.$_SESSION['base'];
        $_SESSION['styles'][] = 'ProfileStyles.css';
        HeaderView::show($profile->getUserName() . '\'s Profile');
        ?>
<section id="profile-info" class="row">
    <div class="col-sm-12 col-md-3 col-lg-4">
        <img id="profile-pic" src="<?= 'http://' . $host_base . '/' . self::$imgDir . $profile->getPicture() ?>" class="img-responsive img-rounded" alt="<?= $profile->getUserName() ?>'s profile picture" />
    </div>
    <div class="col-sm-6 col-md-5 col-lg-4">
    
        <ul class="list-group">
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    First Name:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getFirstName() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    Last Name:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getLastName() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    E-mail:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getEmail() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    Phone #:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getPhoneNumber() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    Gender:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getGender() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    Facebook:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getFacebook() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    Date of Birth:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getDOB() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-4">
                    Country:
                </div>
                <div class="attrValue col-sm-8">
                    <?= $profile->getCountry() ?>
                </div>
            </li>
        </ul>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-4">
        <ul class="list-group">
            <li class="list-group-item row">
                <div class="attrLabel col-sm-6 col-lg-5">
                    Theme:
                </div>
                <div class="attrValue col-sm-6 col-lg-7">
                    <?= $profile->getTheme() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-6 col-lg-5">
                    Theme Accent Color:
                </div>
                <div class="attrValue col-sm-6 col-lg-7">
                    <?= $profile->getAccentColor() ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-6 col-lg-5">
                    Profile Public:
                </div>
                <div class="attrValue col-sm-6 col-lg-7">
                    <?= $profile->isProfilePublic() ? "yes" : "no" ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-6 col-lg-5">
                    Picture Public:
                </div>
                <div class="attrValue col-sm-6 col-lg-7">
                    <?= $profile->isPicturePublic() ? "yes" : "no" ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-6 col-lg-5">
                    E-mail Reminders:
                </div>
                <div class="attrValue col-sm-6 col-lg-7">
                    <?= $profile->isSendRemindersSet() ? "yes" : "no" ?>
                </div>
            </li>
            <li class="list-group-item row">
                <div class="attrLabel col-sm-6 col-lg-5">
                    Stay Logged In:
                </div>
                <div class="attrValue col-sm-6 col-lg-7">
                    <?= $profile->isStayLoggedInSet() ? "yes" : "no" ?>
                </div>
            </li>
        </ul>
    </div>
</section><?php
        $user = UsersDB::getUserBy('userName', $_SESSION['profile']->getUserName());
        if (isset($_SESSION) && isset($_SESSION['profile'])
                && ($_SESSION['profile']->getUserName() == $profile->getUserName() || $user->isAdministrator())): ?>
<section class="row">
    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
        <a href="profile_edit_show_<?=$profile->getUserName()?>" class="btn btn-info btn-block">
            <span class="glyphicon glyphicon-pencil"></span>
            &nbsp;Edit Profile
        </a>
    </div>
</section><?php
        endif;
        
        FooterView::show();
    }
    
    public static function showEditForm() {
        $host_base = $_SERVER['HTTP_HOST'].'/'.$_SESSION['base'];
        
        if (isset($_SESSION['profileOld']))
            $profile = $_SESSION['profileOld'];
        else if (isset($_SESSION['profileEdit']))
            $profile = $_SESSION['profileEdit'];
        else if (isset($_SESSION['profile']))
            $profile = $_SESSION['profile'];
        else
            throw new Exception('Error: profile not found. Unable to edit profile.');
        
        $_SESSION['scripts'][] = 'moment-with-locales.js';
        $_SESSION['scripts'][] = 'bootstrap-datetimepicker.min.js';
        $_SESSION['scripts'][] = 'ProfileScripts.js';
        $_SESSION['styles'][] = 'bootstrap-datetimepicker.min.css';
        $_SESSION['styles'][] = 'ProfileStyles.css';
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
<section id="profile-info" class="row">
<div class="col-sm-12">
<form action="profile_edit_post" enctype="multipart/form-data" method="post" class="form-horizontal">
<div class="row">
    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <div class="col-sm-12">
                <div id="picture-wrapper">
                    <img src="<?= 'http://' . $host_base . '/' . self::$imgDir . $profile->getPicture() ?>" class="profilePic img-responsive img-rounded" alt="<?=$profile->getUserName()?>'s profile picture" /><br />
                </div>

                <label for="picture" id="picture-btn" class="btn btn-info btn-block">
                    <span class="glyphicon glyphicon-user"></span>
                    &nbsp;Change Profile Picture
                </label>
                <input type="file" id="picture" class="hidden" name="picture" accept="image/*" tabindex="13" />
                <input type="hidden" id="oldPicture" name="oldPicture" value="<?=$profile->getPicture()?>" />
            </div>
        </div>
    </div>
    <fieldset class="col-sm-6 col-md-5">
        <legend>Profile</legend>
        <div class="form-group">
            <label for="firstName" class="control-label col-md-4 col-lg-3">First Name:</label>
            <div class="col-md-8 col-lg-9">
                <input type="text" id="firstName" name="firstName" value="<?=$profile->getFirstName()?>" class="form-control" aria-describedby="fNameHelp" size="15" maxlength="30" tabindex="5" pattern="^[a-zA-Z_-]+$" title="Remove invalid characters" />
                <span id="fNameHelp" class="help-block"><?=$profile->getError("firstName")?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="lastName" class="control-label col-md-4 col-lg-3">Last Name:</label>
            <div class="col-md-8 col-lg-9">
                <input type="text" id="lastName" name="lastName" value="<?=$profile->getLastName()?>" class="form-control" aria-describedby="lNameHelp" size="15" maxlength="30" tabindex="6" pattern="(^$)|(^([^\-!#\$%\^\x26\(\)\*,\./:;\?@\[\\\]_\{\|\}Â¨Ë‡â€œâ€�â‚¬\+<=>Â§Â°\d\sÂ¤Â®â„¢Â©]| )+$)" title="Remove invalid characters" />
                <span id="lNameHelp" class="help-block"><?=$profile->getError("lastName")?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="control-label col-md-4 col-lg-3">E-mail:</label>
            <div class="col-md-8 col-lg-9">
                <input type="email" id="email" name="email" value="<?=$profile->getEmail()?>" class="form-control" aria-describedby="emailHelp" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" />
                <span id="emailHelp" class="help-block"><?=$profile->getError("email")?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="control-label col-md-4 col-lg-3">Phone #:</label>
            <div class="col-md-8 col-lg-9">
                <input type="tel" id="phone" name="phone" value="<?=$profile->getPhoneNumber()?>" class="form-control" aria-describedby="phoneHelp" size="15" maxlength="15"  tabindex="7" placeholder="xxx-xxx-xxxx" pattern="^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$" title="xxx-xxx-xxx or x-xxx-xxx-xxxx"/>
                <span id="phoneHelp" class="help-block"><?=$profile->getError("phone")?></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4 col-lg-3">Gender:</label>
            <div class="radio col-md-8 col-lg-9" aria-describedby="genderHelp">
                <div class="row">
                    <div class="col-xs-4 col-xs-offset-2">
                        <label>
                            <input type="radio" id="male" name="gender" value="male"<?=$genderMaleVal?> tabindex="9" />Male
                        </label>
                    </div>
                    <div class="col-xs-4 col-xs-offset-1">
                        <label>
                            <input type="radio" id="female" name="gender" value="female"<?=$genderFemaleVal?> tabindex="10" />Female
                        </label>
                        <span id="genderHelp" class="help-block"><?=$profile->getError("gender")?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="facebook" class="control-label col-md-4 col-lg-3">Facebook:</label>
            <div class="col-md-8 col-lg-9">
                <input type="url" id="facebook" name="facebook" value="<?=$profile->getFacebook()?>" class="form-control" aria-describedby="faceHelp" size="30" tabindex="8" pattern="((http|https):\/\/)?(www\.)?facebook\.com\/.+" title="Must be a valid Facebook URL" />
                <span id="faceHelp" class="help-block"><?=$profile->getError("facebook")?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="dob" class="control-label col-md-4 col-lg-3">Date of Birth:</label>
            <div class="col-md-8 col-lg-9">
                <div class="input-group date date-picker">
                    <input type="text" id="dob" name="dob" value="<?=$profile->getDOB()?>" class="form-control" aria-describedby="dobHelp" tabindex="11" title="mm/dd/yyyy or mm-dd-yyyy" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <span id="dobHelp" class="help-block"><?=$profile->getError("dob")?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="countryInput" class="control-label col-md-4 col-lg-3">Country:</label>
            <div class="col-md-8 col-lg-9">
                <input type="text" id="countryInput" name="country" list="country" value="<?=$profile->getCountry()?>" class="form-control" maxlength="30" tabindex="12" pattern="^[a-zA-Z& \{\}\(\)]{2,}$" title="Allowed characters: letters, spaces, &amp;, {}, ()" />
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
                </datalist>
            </div>
        </div>
    </fieldset>
    <fieldset class="col-sm-6 col-md-4">
        <legend>Preferences</legend>
        <div class="form-group">
            <label for="theme" class="control-label col-md-4 col-lg-3">Theme:</label>
            <div class="col-md-8 col-lg-9">
                <select id="theme" name="theme" class="form-control" aria-describedby="themeHelp" tabindex="14">
                    <option<?=$themeDarkVal?>>dark</option>
                    <option<?=$themeLightVal?>>light</option>
                </select>
                <span id="themeHelp" class="help-block"><?=$profile->getError("theme")?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="accentColor" class="control-label col-md-4 col-lg-3">Theme Accent Color:</label>
            <div class="col-md-8 col-lg-9">
                <input type="color" id="accentColor" name="accentColor" value="<?=$profile->getAccentColor()?>" class="form-control" aria-describedby="aColorHelp" tabindex="15" />
                <span id="aColorHelp" class="help-block"><?=$profile->getError("accentColor")?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-10 col-xs-offset-2">
                <label class="checkbox">
                    <input type="checkbox" id="isProfilePublic" name="isProfilePublic"<?=$pubProfileVal?> tabindex="16" />
                    Profile Public
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="isPicturePublic" name="isPicturePublic"<?=$pubPicVal?> tabindex="17" />
                    Picture Public
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="sendReminders" name="sendReminders"<?=$remindVal?> tabindex="18" />
                    E-mail Reminders
                </label>
                <label class="checkbox">
                    <input type="checkbox" id="stayLoggedIn" name="stayLoggedIn"<?=$stayLoggedVal?> tabindex="19" />
                    Stay Logged In
                </label>
            </div>
        </div>
    </fieldset>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <hr />
        
        <div class="form-group">
            <div class="col-xs-12">
                <input type="hidden" name="userName" value="<?=$profile->getUserName()?>" />
                
                <div class="btn-group btn-group-justified" role="group">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary" tabindex="20">
                            <span class="glyphicon glyphicon-ok"></span>
                            &nbsp;Save Changes
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <a href="profile_show_<?=$profile->getUserName()?>" class="btn btn-default" tabindex="21">
                            <span class="glyphicon glyphicon-remove"></span>
                            &nbsp;Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</form>
</div>
</section>
        <?php
        FooterView::show();
    }
}
?>