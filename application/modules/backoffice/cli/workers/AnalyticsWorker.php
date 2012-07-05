<?php

//Constants
if (!defined('STDIN')) {
    die('You must launch the worker from the command line');
}

if (!defined('ROOT_PATH')){
    define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../../../../'));
}

//Include path
$paths = array(
    ROOT_PATH . '/library',
    get_include_path(),
);

set_include_path(implode(PATH_SEPARATOR, $paths));

//Autoloader
require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('App_');
$loader->registerNamespace('Zend_');
$loader->setFallbackAutoloader(TRUE);

//Require the environment file
require ROOT_PATH . '/application/configs/environment.php';

//Start the worker
echo sprintf("%s: Analytics worker ready and waiting for tasks...\n", date('r'));

$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction('analytics', 'analytics');

while($worker->work()) {
    switch($worker->returnCode()){
        case GEARMAN_SUCCESS:
            break;
        default:
            echo sprintf("%s: Error ocurred: %s\n", date('r'), $worker->returnCode());
            exit;
    }
}

echo sprintf("%s: Analytics worker finished\n");

/**
 * Functions registered with the worker
 *
 * @param object $job 
 * @return void
 */
function analytics($job){
    //Get the info of the job
    list($domain, $id, $data) = unserialize($job->workload());
    
    //Ensure the minimum info
    if(empty($id) || empty($data) || empty($domain)){
        echo sprintf("%s: To register an event we need the data and the id\n", date('r'));
        $job->sendFail();
        
        return FALSE;
    }
    
    echo sprintf("%s: Received a task to store an analytics event\n", date('r'));
    echo sprintf("%s: Sending the event #%s to %s domain\n", date('r'), $id, $domain);
    $document = new Zend_Cloud_DocumentService_Document($data, $id);
    
    try{
        $amazonSDB = getAmazonSDB();
        
        try{
            $amazonSDB->insertDocument($domain, $document);
        }catch(Zend_Cloud_DocumentService_Exception $e){
            echo sprintf("%s: Connectivity issues, sleeping 0.5s\n", date('r'));
            usleep(500000);
            $amazonSDB->insertDocument($domain, $document);
        }
        
        echo sprintf("%s: Event #%s stored\n\n", date('r'), $id);
        
        $job->sendComplete(TRUE);
        
        return TRUE;
    }catch(Zend_Cloud_DocumentService_Exception $e){
        logError(sprintf("%s: Error while storing the event #%s - %s\n\n",date('r'), $id, $e->getMessage()));
        
        $job->sendFail();
        
        return FALSE;
    }
}

/**
 * Log an error and send a SNS notification
 *
 * @param string $string 
 * @return void
 */
function logError($msg){
    $config = getConfig();
    
    $snsConfig = array(
        'accessKey' => $config->amazon->aws_access_key,
        'privateKey' => $config->amazon->aws_private_key,
        'host' => $config->amazon->sns->host,
    );
    
    $snsConfig['topicArn'] = $config->amazon->sns->topics->frontend_errors->arn;
    $sns = new App_Amazon_SNS_Topic($snsConfig);
    
    echo $msg;
    $sns->publish('Critical Error', $msg);
}

/**
 * Return the content of the config file
 *
 * @return object
 */
function getConfig(){
    return new Zend_Config_Ini(ROOT_PATH . '/application/configs/application.ini', APPLICATION_ENV);
}

/**
 * Create a Amazon Simple DB client
 *
 * @return Zend_Cloud_DocumentService_Adapter_SimpleDb
 */
function getAmazonSDB(){
    $config = getConfig();
    
    $adapterClass = 'Zend_Cloud_DocumentService_Adapter_SimpleDb';
    $amazonSDB = Zend_Cloud_DocumentService_Factory::getAdapter(array(
        Zend_Cloud_DocumentService_Factory::DOCUMENT_ADAPTER_KEY    => $adapterClass,
        Zend_Cloud_DocumentService_Adapter_SimpleDb::AWS_ACCESS_KEY => $config->amazon->aws_access_key,
        Zend_Cloud_DocumentService_Adapter_SimpleDb::AWS_SECRET_KEY => $config->amazon->aws_private_key
    ));
    
    //Check if we have to create the domain
    $domains = $amazonSDB->listCollections();
    
    foreach($config->analytics as $key => $item){
        if(!in_array($item->domain, $domains)){
            $amazonSDB->createCollection($item->domain);
        }
    }
    
    return $amazonSDB;
}