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
            $uNameVal = $user->getUserName(); $uNameErr = $user->getError('userName'); $uNameStatus = empty($uNameErr) ? '' : ' has-error';
            $passErr = $user->getError('password'); $passStatus = empty($passErr) ? '' : ' has-error';
        } else {
            $uNameVal = ''; $uNameErr = ''; $uNameStatus = '';
            $emailVal = ''; $emailErr = ''; $emailStatus = '';
            $passErr = ''; $passStatus = '';
        }
        
        if (isset($_SESSION['profileSignup'])) {
            $profile = $_SESSION['profileSignup'];
            $fNameVal = $profile->getFirstName(); $fNameErr = $profile->getError('firstName'); $fNameStatus = empty($fNameErr) ? '' : ' has-error';
            $lNameVal = $profile->getLastName(); $lNameErr = $profile->getError('lastName'); $lNameStatus = empty($lNameErr) ? '' : ' has-error';
            $emailVal = $profile->getEmail(); $emailErr = $profile->getError('email'); $emailStatus = empty($emailErr) ? '' : ' has-error';
            $phoneVal = $profile->getPhoneNumber(); $phoneErr = $profile->getError('phone'); $phoneStatus = empty($phoneErr) ? '' : ' has-error';
            $faceVal = $profile->getFacebook(); $faceErr = $profile->getError('facebook'); $faceStatus = empty($faceErr) ? '' : ' has-error';
            $genderMaleVal = ($profile->getGender() === 'male') ? ' checked="checked"' : '';
            $genderFemaleVal = ($profile->getGender() === 'female') ? ' checked="checked"' : '';
            $genderErr = $profile->getError('gender'); $genderStatus = empty($genderErr) ? '' : ' has-error';
            $dobVal = $profile->getDOB(); $dobErr = $profile->getError('dob'); $dobStatus = empty($dobErr) ? '' : ' has-error';
            $countryVal = $profile->getCountry();
            $picVal = $profile->getPicture();
            $themeDarkVal = ($profile->getTheme() === 'dark') ? ' selected="selected"' : '';
            $themeLightVal = ($profile->getTheme() === 'light') ? ' selected="selected"' : '';
            $themeErr = $profile->getError('theme'); $themeStatus = empty($themeErr) ? '' : ' has-error';
            $colorVal = $profile->getAccentColor(); $colorErr = $profile->getError('accentColor'); $colorStatus = empty($colorErr) ? '' : ' has-error';
            $pubProfileVal = ($profile->isProfilePublic()) ? ' checked="checked"' : '';
            $pubPicVal = ($profile->isPicturePublic()) ? ' checked="checked"' : '';
            $remindVal = ($profile->isSendRemindersSet()) ? ' checked="checked"' : '';
            $stayLoggedVal = ($profile->isStayLoggedInSet()) ? ' checked="checked"' : '';
        } else  {
            $fNameVal = ''; $fNameErr = ''; $fNameStatus = '';
            $lNameVal = ''; $lNameErr = ''; $lNameStatus = '';
            $emailStatus = '';
            $phoneVal = ''; $phoneErr = ''; $phoneStatus = '';
            $faceVal = ''; $faceErr = ''; $faceStatus = '';
            $genderMaleVal = ''; $genderFemaleVal = ''; $genderErr = ''; $genderStatus = '';
            $dobVal = ''; $dobErr = ''; $dobStatus = '';
            $countryVal = '';
            $picVal = '';
            $themeDarkVal = '';
            $themeLightVal = '';
            $themeErr = ''; $themeStatus = '';
            $colorVal = ''; $colorErr = ''; $colorStatus = '';
            $pubProfileVal = '';
            $pubPicVal = '';
            $remindVal = '';
            $stayLoggedVal = '';
        }
        
        ?>
</div>
<div class="container-fluid">
<section class="row">
    <div class="col-sm-12">
        <h2 class="hidden">Sign Up Form</h2>
        <form action="signup_post" enctype="multipart/form-data" method="post" role="form" class="form-horizontal"> 
        
            <div class="row">
                <fieldset class="col-xs-12 col-sm-6 col-lg-5">
                    <legend>Account Information (Required)</legend>
                    <div class="form-group<?=$emailStatus?>">
                        <label for="email" class="control-label col-md-3">E-mail</label>
                        <div class="col-md-9">
                            <input type="email" id="email" name="email" value="<?=$emailVal?>" class="form-control" aria-describedby="emailHelp" size="15" autofocus="autofocus" required="required" maxlength="30" tabindex="1" />
                            <span id="emailHelp" class="help-block"><?=$emailErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$uNameStatus?>">
                        <label for="userName" class="control-label col-md-3">User Name</label>
                        <div class="col-md-9">
                            <input type="text" id="userName" name="userName" value="<?=$uNameVal?>" class="form-control" aria-describedby="uNameHelp" size="15" required="required" maxlength="20" tabindex="2" pattern="^[a-zA-Z0-9_-]+$" title="User name can only contain letters, numbers, dashes (-), and underscores (_)" />
                            <span id="uNameHelp" class="help-block"><?=$uNameErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$passStatus?>">
                        <label for="password" class="control-label col-md-3">Password</label>
                        <div class="col-md-9">
                            <input type="password" id="password" name="password1" class="form-control" aria-describedby="passHelp" size="15" required="required" maxlength="20" tabindex="3" pattern=".{6,}" title="Password must be 6-20 characters long" />
                            <span id="passHelp" class="help-block"><?=$passErr?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="retypePassword" class="control-label col-md-3">Retype Password</label>
                        <div class="col-md-9">
                            <input type="password" id="retypePassword" name="password2" class="form-control" size="15" required="required" maxlength="20" tabindex="4" pattern=".{6,}" title="Password must be 6-20 characters long" />
                        </div>
                    </div>
                </fieldset>
                
                <fieldset class="col-xs-12 col-sm-6 col-lg-5">
                    <legend>Profile Information</legend>
                    <div class="form-group">
                        <label class="control-label col-md-3">Picture</label>
                        <div class="col-md-9">
                            <label for="choosePicture" class="btn btn-default btn-sm btn-block">
                                <span class="glyphicon glyphicon-user"></span>
                                &nbsp;Upload Profile Picture
                            </label>
                            <input type="file" id="choosePicture" class="hidden" name="picture" accept="image/*" tabindex="5" />
                        </div>
                    </div>
                    <div class="form-group<?=$fNameStatus?>">
                        <label for="firstName" class="control-label col-md-3">First Name</label>
                        <div class="col-md-9">
                            <input type="text" id="firstName" name="firstName" value="<?=$fNameVal?>" class="form-control" aria-describedby="fNameHelp" size="15" maxlength="30" tabindex="6" pattern="^[a-zA-Z ']+$" title="Name can only contain letters, spaces, and apostrophes" />
                            <span id="fNameHelp" class="help-block"><?=$fNameErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$lNameStatus?>">
                        <label for="lastName" class="control-label col-md-3">Last Name</label>
                        <div class="col-md-9">
                            <input type="text" id="lastName" name="lastName" value="<?=$lNameVal?>" class="form-control" aria-describedby="lNameHelp" size="15" maxlength="30" tabindex="7" pattern="^[a-zA-Z ']+$" title="Name can only contain letters, spaces, and apostrophes" />
                            <span id="lNameHelp" class="help-block"><?=$lNameErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$phoneStatus?>">
                        <label for="phone" class="control-label col-md-3">Phone #</label>
                        <div class="col-md-9">
                            <input type="tel" id="phone" name="phone" value="<?=$phoneVal?>" class="form-control" aria-describedby="phoneHelp" size="15" maxlength="15"  tabindex="8" placeholder="xxx-xxx-xxxx" pattern="^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$" title="xxx-xxx-xxx or x-xxx-xxx-xxxx"/>
                            <span id="phoneHelp" class="help-block"><?=$phoneErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$faceStatus?>">
                        <label for="facebook" class="control-label col-md-3">Facebook URL</label>
                        <div class="col-md-9">
                            <input type="url" id="facebook" name="facebook" value="<?=$faceVal?>" class="form-control" aria-describedby="faceHelp" size="30" tabindex="9" pattern="((http|https):\/\/)?(www\.)?facebook\.com\/.+" title="Must be a valid Facebook URL" />
                            <span id="faceHelp" class="help-block"><?=$faceErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$genderStatus?>">
                        <label class="control-label col-xs-12 col-md-3">Gender</label>
                        <div class="radio col-xs-12 col-md-9" aria-describedby="genderHelp">
                            <label>
                                <input type="radio" id="male" name="gender" value="male"<?=$genderMaleVal?> tabindex="10" />Male
                            </label>
                            <label>
                                <input type="radio" id="female" name="gender" value="female"<?=$genderFemaleVal?> tabindex="11" />Female
                            </label>
                            <span id="genderHelp" class="help-block"><?=$genderErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$dobStatus?>">
                        <label for="dob" class="control-label col-md-3">Date of Birth</label>
                        <div class="col-md-9">
                            <input type="date" id="dob" name="dob" value="<?=$dobVal?>" class="form-control" aria-describedby="dobHelp" tabindex="12" title="mm/dd/yyyy or mm-dd-yyyy" />
                            <span id="dobHelp" class="help-block"><?=$dobErr?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="countryDL" class="control-label col-md-3">Country</label>
                        <div class="col-md-9">
                            <input type="text" id="countryDL" name="country" list="country" value="<?=$countryVal?>" class="form-control" maxlength="30" tabindex="13" pattern="^[a-zA-Z& \{\}\(\)]{2,}$" title="Allowed characters: letters, spaces, &amp;, {}, ()" />
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
                
                <fieldset class="col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-3 col-lg-2 col-lg-offset-0">
                    <legend>Site Preferences</legend>
                    <div class="form-group<?=$themeStatus?>">
                        <label for="theme" class="control-label col-xs-12 col-md-3">Theme</label>
                        <div class="col-xs-12 col-md-9">
                            <select id="theme" name="theme" class="form-control" aria-describedby="themHelp" tabindex="14">
                                <option<?=$themeDarkVal?>>dark</option>
                                <option<?=$themeLightVal?>>light</option>
                            </select>
                            <span id="themeHelp" class="help-block"><?=$themeErr?></span>
                        </div>
                    </div>
                    <div class="form-group<?=$colorStatus?>">
                        <label for="accentColor" class="control-label col-xs-12 col-md-3">Theme Accent Color</label>
                        <div class="col-xs-12 col-md-9">
                            <input type="color" id="accentColor" name="accentColor" class="btn btn-default btn-sm" value="<?=$colorVal?>" aria-describedby="colorHelp" tabindex="15" />
                            <span id="colorHelp" class="help-block"><?=$colorErr?></span>
                        </div>
                    </div>
                    <div class="from-group">
                        <div class="col-xs-12">
                            <label class="checkbox">
                                <input type="checkbox" id="isProfilePublic" name="isProfilePublic"<?=$pubProfileVal?> tabindex="16" />Make profile public
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" id="isPicturePublic" name="isPicturePublic"<?=$pubPicVal?> tabindex="17" />Show picture in profile
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" id="showReminders" name="showReminders"<?=$remindVal?> tabindex="18" />E-mail reminders after prolonged inactivity
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" id="stayLoggedIn" name="stayLoggedIn"<?=$stayLoggedVal?> tabindex="19" />Keep me logged in
                            </label>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="row">
                <div class="col-xs-12 col-xs-0 col-md-8 col-md-offset-2 col-lg-10 col-lg-offset-1">
                    <hr />
                    <div class="btn-group btn-group-justified" role="group">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary" tabindex="20">
                                <span class="glyphicon glyphicon-ok"></span>
                                &nbsp;Submit
                            </button>
                        </div>
                        
                        <div class="btn-group" role="group">
                            <a href="home" class="btn btn-default">
                                <span class="glyphicon glyphicon-remove"></span>
                                &nbsp;Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php
    }
}
?>