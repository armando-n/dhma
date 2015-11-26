<?php
require_once dirname(__FILE__) . '\..\..\WebContent\models\Database.class.php';
require_once dirname(__FILE__) . '\..\..\WebContent\models\Messages.class.php';

class DatabaseTest extends PHPUnit_Framework_TestCase {

    public function testShow() {
        ob_start();
        self::checkSession();
        $db = Database::getDB();
        $output = ob_get_clean();

        $this->assertNotNull($db,
            'It should call getDB and return a non-null value');
        $this->assertInstanceOf('PDO', $db,
            'It should call getDB and return a PDO object');
        $this->assertTrue(stristr($output, 'Failed to connect to database') === false,
            'It should call getDB and not report any errors');
        
        $db = null;
    }
    
    private function checkSession() {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION))
            $_SESSION = array();
        if (!isset($_SERVER['HTTP_HOST']))
            $_SERVER['HTTP_HOST'] = 'localhost';
        if (!isset($_SESSION['base']))
            $_SESSION['base'] = 'na_lab3';
        if (!isset($_SESSION['dbName']) || $_SESSION['dbName'] !== 'dhma_testDB')
            $_SESSION['dbName'] = 'dhma_testDB';
        if (!isset($_SESSION['configFile']) || $_SESSION['configFile'] !== 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini')
            $_SESSION['configFile'] = 'na_lab3' . DIRECTORY_SEPARATOR . 'myConfig.ini';
        if (!isset($_SESSION['testing']))
            $_SESSION['testing'] = true;
    }

}
?>