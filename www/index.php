<?php
header('Content-Type: text/html; charset=UTF-8');

chdir(__DIR__ . '/../');

function __autoload($class_name) { //automatic loading of the needed classes
    $theDir = strstr($class_name, '_', true); //So you need to respect the standards...
    $DirFile = $theDir . '/' . $class_name . '.class.php';
    $theFile = $class_name . '.class.php';

    if (file_exists($DirFile)) {
        include $DirFile;
    } else {
        $error = new controllers_Error();
        $error->start('<br/>' . $theFile . ' <strong><font color="red"> not found</font></strong>'); //Display error and stop the script
    }
}

$GLOBALS['Application'] = new engine_Application();
$GLOBALS['Application']->start();
?>