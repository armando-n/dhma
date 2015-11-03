<?php
class SignupView {
    
    public static function show() {
        if (!isset($_SESSION)) {
            ?><p>Error: session data not found.</p><?php
            return;
        }
        
        HeaderView::show("Member Sign Up");
        SignupView::showBody();
        FooterView::show();
    }
    
    public static function showBody() {
        if (isset($_SESSION['userSignup'])) {
            $user = $_SESSION['userSignup'];
            $uNameVal = $user->getUserName(); $uNameErr = $user->getError('userName');
            $passErr = $user->getError('password');
        } else {
            $uNameVal = ''; $uNameErr = '';
            $emailVal = ''; $emailErr = '';
            $passErr = '';
        }
        
        if (isset($_SESSION['profileSignup'])) {
            $profile = $_SESSION['profileSignup'];
            $fNameVal = $profile->getFirstName(); $fNameErr = $profile->getError('firstName');
            $lNameVal = $profile->getLastName(); $lNameErr = $profile->getError('lastName');
            $emailVal = $profile->getEmail(); $emailErr = $profile->getError('email');
            $phoneVal = $profile->getPhoneNumber(); $phoneErr = $profile->getError('phone');
            $faceVal = $profile->getFacebook(); $faceErr = $profile->getError('facebook');
            $genderMaleVal = ($profile->getGender() === 'male') ? ' checked="checked"' : '';
            $genderFemaleVal = ($profile->getGender() === 'female') ? ' checked="checked"' : '';
            $genderErr = $profile->getError('gender');
            $dobVal = $profile->getDOB(); $dobErr = $profile->getError('dob');
            $countryVal = $profile->getCountry();
            $picVal = $profile->getPicture();
            $themeDarkVal = ($profile->getTheme() === 'dark') ? ' selected="selected"' : '';
            $themeLightVal = ($profile->getTheme() === 'light') ? ' selected="selected"' : '';
            $themeErr = $profile->getError('theme');
            $colorVal = $profile->getAccentColor(); $colorErr = $profile->getError('accentColor');
            $pubProfileVal = ($profile->isProfilePublic()) ? ' checked="checked"' : '';
            $pubPicVal = ($profile->isPicturePublic()) ? ' checked="checked"' : '';
            $remindVal = ($profile->isSendRemindersSet()) ? ' checked="checked"' : '';
            $stayLoggedVal = ($profile->isStayLoggedInSet()) ? ' checked="checked"' : '';
        } else  {
            $fNameVal = ''; $fNameErr = '';
            $lNameVal = ''; $lNameErr = '';
            $phoneVal = ''; $phoneErr = '';
            $faceVal = ''; $faceErr = '';
            $genderMaleVal = ''; $genderFemaleVal = ''; $genderErr = '';
            $dobVal = ''; $dobErr = '';
            $countryVal = '';
            $picVal = '';
            $themeDarkVal = '';
            $themeLightVal = '';
            $themeErr = '';
            $colorVal = ''; $colorErr = '';
            $pubProfileVal = '';
            $pubPicVal = '';
            $remindVal = '';
            $stayLoggedVal = '';
        }
        
        ?>
<section>
    <h2>Sign Up Form</h2>
    
    <form action="signup_post" enctype="multipart/form-data" method="post">
        <fieldset>
            <legend>Required Account Information</legend>
            E-mail <input type="email" name="email" value="<?=$emailVal?>" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" />
                <span class="error"><?=$emailErr?></span><br />
            User Name <input type="text" name="userName" value="<?=$uNameVal?>" size="15" required="required" maxlength="20" tabindex="2" pattern="^[a-zA-Z0-9_-]+$" title="User name can only contain letters, numbers, dashes (-), and underscores (_)" />
                <span class="error"><?=$uNameErr?></span><br />
            Password <input type="password" name="password1" size="15" required="required" maxlength="20" tabindex="3" pattern=".{6,}" title="Password must be 6-20 characters long" />
                <span class="error"><?=$passErr?></span><br />
            Retype Password <input type="password" name="password2" size="15" required="required" maxlength="20" tabindex="4" pattern=".{6,}" title="Password must be 6-20 characters long" />
        </fieldset>
        <fieldset>
            <legend>Profile Information</legend>
            First Name <input type="text" name="fname" value="<?=$fNameVal?>" size="15" maxlength="30" tabindex="5" pattern="(^$)|(^([^\-!#\$%\^\x26\(\)\*,\./:;\?@\[\\\]_\{\|\}¨ˇ“”€\+<=>§°\d\s¤®™©]| )+$)" title="Remove invalid characters" />
                <span class="error"><?=$fNameErr?></span><br />
            Last Name <input type="text" name="lname" value="<?=$lNameVal?>" size="15" maxlength="30" tabindex="6" pattern="(^$)|(^([^\-!#\$%\^\x26\(\)\*,\./:;\?@\[\\\]_\{\|\}¨ˇ“”€\+<=>§°\d\s¤®™©]| )+$)" title="Remove invalid characters" />
                <span class="error"><?=$lNameErr?></span><br />
            Phone # <input type="tel" name="phone" value="<?=$phoneVal?>" size="15" maxlength="15"  tabindex="7" placeholder="xxx-xxx-xxxx" pattern="^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$" title="xxx-xxx-xxx or x-xxx-xxx-xxxx"/>
                <span class="error"><?=$phoneErr?></span><br />
            Facebook URL <input type="url" name="facebook" value="<?=$faceVal?>" size="30" tabindex="8" pattern="((http|https):\/\/)?(www\.)?facebook\.com\/.+" title="Must be a valid Facebook URL" />
                <span class="error"><?=$faceErr?></span><br />
            Gender
                <input type="radio" id="male" name="gender" value="male"<?=$genderMaleVal?> tabindex="9" /> <label for="male">Male</label>
                <input type="radio" id="female" name="gender" value="female"<?=$genderFemaleVal?> tabindex="10" /> <label for="female">Female</label>
                <span class="error"><?=$genderErr?></span><br />
            Date of Birth <input type="date" name="dob" value="<?=$dobVal?>" tabindex="11" title="mm/dd/yyyy or mm-dd-yyyy" />
                <span class="error"><?=$dobErr?></span><br />
            Country
                <input type="text" name="country" list="country" value="<?=$countryVal?>" maxlength="30" tabindex="12" pattern="^[a-zA-Z& \{\}\(\)]{2,}$" title="Allowed characters: letters, spaces, &amp;, {}, ()" /><br />
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
            Profile Picture <input type="file" class="btn btn-info btn-sm" name="pic" accept="image/*" tabindex="13" /><br />
        </fieldset>
        <fieldset>
            <legend>Site Preferences</legend>
            Theme
                <select name="theme" tabindex="14">
                    <option<?=$themeDarkVal?>>dark</option>
                    <option<?=$themeLightVal?>>light</option>
                </select>
                <span class="error"><?=$themeErr?></span><br />
            Theme Accent Color <input type="color" class="btn btn-default btn-sm" name="color" value="<?=$colorVal?>" tabindex="15" />
                <span class="error"><?=$colorErr?></span><br />
            <input type="checkbox" id="public-profile" name="public-profile"<?=$pubProfileVal?> tabindex="16" /> <label for="public-profile">Make profile public</label><br />
            <input type="checkbox" id="showpic" name="showpic"<?=$pubPicVal?> tabindex="17" /> <label for="showpic">Show picture in profile</label><br />
            <input type="checkbox" id="reminders" name="reminders"<?=$remindVal?> tabindex="18" /> <label for="reminders">E-mail reminders after prolonged inactivity</label><br />
            <input type="checkbox" id="keep-logged-in" name="keep-logged-in"<?=$stayLoggedVal?> tabindex="19" /> <label for="keep-logged-in">Keep me logged in</label>
        </fieldset>
        <div>
            <input type="submit" class="btn btn-primary" value="Submit" size="15" tabindex="20" />
        </div>
    </form>
</section>
<?php
    }
}
?>