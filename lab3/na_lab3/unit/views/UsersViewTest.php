<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\UsersView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';

class UsersViewTest extends PHPUnit_Framework_TestCase {
    
    public function testShowWithNoParameters() {
        ob_start();
        $return = UsersView::show();
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when show is called with no parameters');
        $this->assertTrue(stristr($output, 'Error') !== false,
            'It should show an error when show is called with no parameters');
        $this->assertFalse($return,
            'It should return false to indicate an error when show is called with no parameters');
    }
    
    public function testShowWithNullParameters() {
        ob_start();
        $return = UsersView::show(null, null);
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when show is called with null parameters');
        $this->assertTrue(stristr($output, 'Error') !== false,
            'It should show an error when show is called with null parameters');
        $this->assertFalse($return,
            'It should return false to indicate an error when show is called with null parameters');
    }
    
    public function testShowWithParameters() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfiles();
        
        ob_start();
        $return = UsersView::show($profiles, true);
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when show is called with parameters');
        $this->assertTrue(stristr($output, 'Error') === false,
            'It should not show errors when show is called with parameters');
        $this->assertTrue($return,
            'It should return true to indicate success when show is called with parameters');
    }
    
    public function testShowWithProfilesParameter() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfiles();
        
        ob_start();
        $return = UsersView::show($profiles);
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when show is called with the profiles parameter');
        $this->assertTrue(stristr($output, 'Error') === false,
            'It should not show errors when show is called with the profiles parameter');
        $this->assertTrue($return,
            'It should return true to indicate success when show is called with the profiles parameter');
    }
    
    public function testShowWithNullLoggedInParameter() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfiles();
    
        ob_start();
        $return = UsersView::show($profiles, null);
        $output = ob_get_clean();
    
        $this->assertNotEmpty($output,
            'It should have output when show is called with the profiles parameter');
        $this->assertTrue(stristr($output, 'Error') === false,
            'It should not show errors when show is called with the profiles parameter');
        $this->assertTrue($return,
            'It should return true to indicate success when show is called with the profiles parameter');
    }
    
    public function testShowWithNullProfileParameter() {
        ob_start();
        $return = UsersView::show(null, true);
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when show is called with null profile parameter');
        $this->assertTrue(stristr($output, 'Error') !== false,
            'It should show an error when show is called with null profile parameter');
        $this->assertFalse($return,
            'It should return false to indicate an error when show is called with null profile parameter');
    }
    
    /** @expectedException Exception
     */
    public function testShowBodyWithNoParameters() {
        ob_start();
        $return = UsersView::showBody();
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when showBody is called with no parameters');
        $this->assertTrue(stristr($output, 'Error') !== false,
            'It should show an error when showBody is called with no parameters');
        $this->assertFalse($return,
            'It should return false to indicate an error when showBody is called with no parameters');
    }
    
    public function testShowBodyWithNullParameter() {
        ob_start();
        $return = UsersView::showBody(null);
        $output = ob_get_clean();
    
        $this->assertNotEmpty($output,
            'It should have output when showBody is called with a null parameter');
        $this->assertTrue(stristr($output, 'Error') !== false,
            'It should show an error when showBody is called with a null parameter');
        $this->assertFalse($return,
            'It should return false to indicate an error when showBody is called with a null parameter');
    }
    
    public function testShowBodyWithProfilesParameter() {
        self::checkSession();
        $profiles = UserProfilesDB::getAllUserProfiles();
        
        ob_start();
        $return = UsersView::showBody($profiles);
        $output = ob_get_clean();
    
        $this->assertNotEmpty($output,
            'It should have output when showBody is called with a profiles parameter');
        $this->assertTrue(stristr($output, 'Error') === false,
            'It should not show errors when showBody is called with a profiles parameter');
        $this->assertTrue($return,
            'It should return true to indicate success when showBody is called with a profiles parameter');
    }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION))
            $_SESSION = array();
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
    }
    
}
?>