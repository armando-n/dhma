<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\ProfileView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class ProfileViewTest extends PHPUnit_Framework_TestCase {
    
    private static $validInput = array(
            "firstName" => "Armando",
            "lastName" => "Navarro",
            "email" => "fdf786@my.utsa.edu",
            "gender" => "male",
            "phone" => "281-555-2180",
            "facebook" => "http://facebook.com/someguy210",
            "dob" => "1983-11-02",
            "country" => "United States of America",
            "theme" => "dark",
            "accentColor" => "#00008b",
            "picture" => "someimage",
            "isProfilePublic" => "on",
            "isPicturePublic" => "on",
            "sendReminders" => "on",
            "stayLoggedIn" => "on",
            "userName" => "armando-n"
    );
    
    private static $invalidInput = array(
            "firstName" => "Some-Really-Really-Super-Duper-Long-Name",
            "lastName" => '$InvaldName',
            "email" => "fdf786",
            "gender" => "orange",
            "phone" => "281-555-218x",
            "facebook" => "http://face.com/someguy210",
            "dob" => "1983-11-0x",
            "country" => "United States of America",
            "theme" => "middle",
            "accentColor" => "#00008s",
            "picture" => "someimage",
            "isProfilePublic" => "on",
            "isPicturePublic" => "on",
            "showReminders" => "on",
            "stayLoggedIn" => "on"
    );
    
    /** @expectedException Exception
     * @expectedExceptionMessage Missing argument
     */
    public function testShowProfile_NoParameters() {
        ob_start();
        ProfileView::showProfile();
        $output = ob_get_clean();
    }
    
    public function testShowProfile_NullParameter(){
        ob_start();
        ProfileView::showProfile(null);
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, 'Error: profile has errors') !== false,
            'It should call showProfile and output an error message when null input is provided');
        $this->assertTrue(strstr($output, 'First Name:') === false,
            'It should call showProfile and it should not output any profile information when null input is provided');
    }
    
    public function testShowProfile_ValidProfile() {
        self::checkSession();
        $profile = UserProfilesDB::getUserProfileBy('userName', 'armando-n');
        if ($profile === false)
            throw new Exception("Test Error: null returned from call to UserProfilesDB::getUserProfilesByTest");
        
        ob_start();
        ProfileView::showProfile($profile);
        $output= ob_get_clean();
        
        $this->assertTrue(strstr($output, '<h2>armando-n\'s Profile</h2>') !== false,
            'It should call showProfile and include in the output a reference to the user\'s name when a valid profile is provided');
        $this->assertTrue(strstr($output, 'First Name: Armando') !== false,
            'It should call showProfile and include in the output a reference to the user\'s first name when a valid profile is provided');
    }
    
    public function testShowProfile_InvalidProfile() {
        $badProfile = new UserProfile(self::$invalidInput);
        
        if ($badProfile == null || $badProfile->getErrorCount() == 0)
            throw new Exception("Test Error: UserProfile constructor returned unexpected data. Check UserProfile model and tests.");
        
        ob_start();
        ProfileView::showProfile($badProfile);
        $output = ob_get_clean();
        
        $this->assertTrue(strstr($output, 'Error: profile has errors') !== false,
            'It should call showProfile and output an error message when an invalid user profile is provided');
        $this->assertTrue(strstr($output, 'First Name:') === false,
            'It should call showProfile and it should not output any profile information when an invalid user profile is provided');
    }
    
    /** @expectedException Exception
     * @expectedExceptionMessage profile not found
     */
    public function testShowEditForm_NullParameter_NoSessionData() {
        ProfileView::showEditForm();
    }
    
    /** @expectedException Exception
     * @expectedExceptionMessage profile not found
     */
    public function testShowEditForm_NoProfile_NoProfileEdit() {
        ProfileView::showEditForm();
    }
    
    public function testShowEditForm_InvalidProfile_NoProfileEdit() {
        self::checkSession();
        unset($_SESSION['profileEdit']);
        $_SESSION['profile'] = new UserProfile(self::$invalidInput);
        
        ob_start();
        ProfileView::showEditForm();
        $output = ob_get_clean();
        
        unset($_SESSION['profile']);
        
        $this->assertTrue(strstr($output, 'First Name: ') !== false,
            'It should call showEditForm and display the user profile when an invalid profile is provided in a "profile" session variable');
        $this->assertTrue(strstr($output, 'Your first name cannot contain more than') !== false,
            'It should call showEditForm and display error messages along with the profile when an invalid profile is provided in a "profile" session variable');
    }
    
    public function testShowEditForm_NoProfile_InvalidProfileEdit() {
        self::checkSession();
        unset($_SESSION['profile']);
        $_SESSION['profileEdit'] = new UserProfile(self::$invalidInput);
        
        ob_start();
        ProfileView::showEditForm();
        $output = ob_get_clean();

        unset($_SESSION['profileEdit']);
        
        $this->assertTrue(strstr($output, 'First Name: ') !== false,
            'It should call showEditForm and display the user profile when an invalid profile is provided in a "profileEdit" session variable');
        $this->assertTrue(strstr($output, 'Your first name cannot contain more than') !== false,
            'It should call showEditForm and display error messages along with the profile when an invalid profile is provided in a "profileEdit" session variable');
    }
    
    public function testShowEditForm_ValidProfile_InvalidProfileEdit() {
        self::checkSession();
        $_SESSION['profile'] = new UserProfile(self::$validInput);
        $_SESSION['profileEdit'] = new UserProfile(self::$invalidInput);
        
        ob_start();
        ProfileView::showEditForm();
        $output = ob_get_clean();
        
        unset($_SESSION['profile']);
        unset($_SESSION['profileEdit']);
        
        $this->assertTrue(strstr($output, 'First Name: ') !== false,
            'It should call showEditForm and display the user profile when valid "profile" and invalid "profileEdit" session variables are provided');
        $this->assertTrue(strstr($output, 'Your first name cannot contain more than') !== false,
            'It should call showEditForm and display error messages along with the profile when valid "profile" and invalid "profileEdit" session variables are provided');
    }
    
    public function testShowEditForm_ValidProfile_NoProfileEdit() {
        self::checkSession();
        unset($_SESSION['profileEdit']);
        $_SESSION['profile'] = new UserProfile(self::$validInput);
        
        ob_start();
        ProfileView::showEditForm();
        $output = ob_get_clean();
        
        unset($_SESSION['profile']);
                
        $this->assertTrue(strstr($output, '<h2>armando-n\'s Profile</h2>') !== false,
            'It should call showEditForm and include in the output a reference to the user\'s name when a valid profile is provided in a "profile" session variable');
        $this->assertTrue(strstr($output, 'First Name: <input type="text" name="firstName" value="Armando"') !== false,
            'It should call showEditForm and include in the output a reference to the user\'s first name in a text input element when a valid profile is provided in a "profile" session variable');
    }
    
    public function testShowEditForm_NoProfile_ValidProfileEdit() {
        self::checkSession();
        unset($_SESSION['profile']);
        $_SESSION['profileEdit'] = new UserProfile(self::$validInput);
        
        ob_start();
        ProfileView::showEditForm();
        $output = ob_get_clean();
        
        unset($_SESSION['profileEdit']);
        
        $this->assertTrue(strstr($output, '<h2>armando-n\'s Profile</h2>') !== false,
                'It should call showEditForm and include in the output a reference to the user\'s name when a valid profile is provided in a "profileEdit" session variable');
        $this->assertTrue(strstr($output, 'First Name: <input type="text" name="firstName" value="Armando"') !== false,
                'It should call showEditForm and include in the output a reference to the user\'s first name in a text input element when a valid profile is provided in a "profileEdit" session variable');
    }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
    }
    
}
?>