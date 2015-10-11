<?php
require_once dirname(__FILE__) . '\..\..\WebContent\views\UsersView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\HeaderView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\views\FooterView.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\GenericModelObject.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfile.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\UserProfilesDB.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\controllers\UsersController.class.php';
class UsersControllerTest extends PHPUnit_Framework_TestCase {
    
    public function testRunWithNoParameters() {
        ob_start();
        UsersController::runTest();
        $output = ob_get_clean();
        
        $this->assertNotEmpty($output,
            'It should have output when run is called with no parameters');
        $this->assertTrue(stristr($output, 'Error') === false,
            'It should not show errors when run is called with no parameters');
    }
    
    public function testRunWithLoggedInParameter() {
        ob_start();
        UsersController::runTest(true);
        $output = ob_get_clean();
    
        $this->assertNotEmpty($output,
            'It should have output when run is called with the loggedIn parameter');
        $this->assertTrue(stristr($output, 'Error') === false,
            'It should not show errors when run is called with the loggedIn parameter');
    }
    
}
?>