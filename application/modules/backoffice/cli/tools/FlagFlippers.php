<?php
/**
 * Gets all the methods in a controller and 
 * grabs all the flag and flippers information
 *
 * @category backoffice
 * @package backoffice_cli
 * @subpackage backoffice_cli_tools
 * @copyright casting.net
 */

require_once dirname(__FILE__) . '/../cli.php';

if (!Zend_Registry::get('IS_DEVELOPMENT')) {
    echo 'Please use this tool only in the development environment.';
    die();
}

try {
    $opts = new Zend_Console_Getopt(array('module|m=s' => 'Name of the desired module. For example: backoffice',));
    $opts->parse();
} catch(Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit(); 
}

$module = $opts->getOption('m');
if (!$module) {
    echo $opts->getUsageMessage();
    exit();
}

$path = APPLICATION_PATH . '/modules/' . $module . '/controllers';

if (!is_readable($path)) {
    echo 'Error! Path ' . $path . ' is not readable.';
    exit();
}

$files = array();
if (is_file($path)) {
    $files  []= basename($path);
    $path = dirname($path);
} else {
    if (($dir = opendir($path)) !== false) {
        while (($file = readdir($dir)) !== false) {
            if (fnmatch('*.php', $file) && $file !== 'ErrorController.php') {
                $files[]= $file;
            }
        }
        closedir($dir);
    }
}

$resources = array();

foreach ($files as $file) {
    $filepath = $path . DIRECTORY_SEPARATOR . $file;
    require_once $filepath;
    
    $reflectionFile = new Zend_Reflection_File($filepath);
    foreach($reflectionFile->getClasses() as $class) {
        $classInfo = array(
            'description' => $class->getDocblock()->getShortDescription(),
            'name' => strtolower($module) . '-' . App_Inflector::convertControllerName($class->getName()),
            'methods' => array(),
        );
        
        foreach ($class->getMethods() as $method) {
            if (substr($method->getName(), -6) == 'Action') {
                $classInfo['methods'][] = array(
                    'description' => $method->getDocblock()->getShortDescription(),
                    'name' => App_Inflector::convertActionName($method->getName()),
                );
            }
        }
        
        $resources[] = $classInfo;
    }
}

$flagFlippers = App_Cli_FlagFlippers::getInstance();
$inserts = $flagFlippers->generateInserts($resources);
if (empty($inserts)) {
    echo 'No new flags / privileges found.';
    exit();
}

$date = new Zend_Date();
echo '-- Flag And Flipper data' . PHP_EOL;
echo '-- Report generated at ' . $date . PHP_EOL;

foreach ($inserts as $insert) {
    echo $insert . PHP_EOL;
}