<?php
$pathDir = dirname(__FILE__);
$paths = array("views", "models", "controllers", "resources", "exceptions");
foreach ($paths as $path) {
    set_include_path(get_include_path()
            . PATH_SEPARATOR
            . $pathDir
            . DIRECTORY_SEPARATOR
            . $path);
}

spl_autoload_register('myClassLoader');

function myClassLoader($className) {
	$paths = explode (PATH_SEPARATOR, get_include_path ());
	foreach ($paths as $path) {
		$file = $path . DIRECTORY_SEPARATOR . $className . '.class.php';
		if (file_exists($file)) {
			include_once $file;
			break;
		}
	}
}
?>