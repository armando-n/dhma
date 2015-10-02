<?php
class Database {
    
    private static $db;
    
    public static function getDB($dbName = 'dhma', $configFile = 'myConfig.ini') {
        if (!isset(Database::$db)) {
            try {
                // read database config info from file
                $configArray = parse_ini_file('../../../' . $configFile);
                $userName = $configArray["username"];
                $pass = $configArray["password"];
                
                // open the connection
                Database::$db = new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8", $userName, $pass);
                Database::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e) {
                echo "Failed to connect to database $dbName: " . $e->getMessage();
            }
        }
        
        return Database::$db;
    }
}
?>