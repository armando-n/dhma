<?php
$pathDir = dirname(__FILE__);
$paths = array("views", "models", "controllers", "resources");
foreach ($paths as $path) {
    set_include_path(get_include_path()
            . PATH_SEPARATOR
            . $pathDir
            . DIRECTORY_SEPARATOR
            . $path);
}
spl_autoload_extensions(".class.php");
spl_autoload_register();
?>