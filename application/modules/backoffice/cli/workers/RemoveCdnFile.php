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
echo sprintf("%s: remove_cdn_file worker ready and waiting for jobs...\n", date('r'));

$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction('remove_cdn_file', 'remove_cdn_file');

while($worker->work()) {
    switch($worker->returnCode()){
        case GEARMAN_SUCCESS:
            break;
        default:
            echo sprintf("%s: Error ocurred: %s\n", date('r'), $worker->returnCode());
            exit;
    }
}

echo sprintf("%s: remove_cdn_file worker finished\n");

/**
 * Functions registered with the worker
 *
 * @param object $job 
 * @return void
 */
function remove_cdn_file($job){
    //Get the info of the job
    list($remoteFilename) = unserialize($job->workload());
    
    //Do some checks
    if(empty($remoteFilename)){
        logError(sprintf("%s: The workload do not contain the required info to remove a file from the CDN\n\n", date('r')));
        $job->sendFail();
        
        return FALSE;
    }
    
    echo sprintf("%s: Received job to remove %s from the CDN\n", date('r'), $remoteFilename);
    
    //Get the Amazon S3 client
    $config = getConfig();
    
    $amazonS3Client = Zend_Cloud_StorageService_Factory::getAdapter(
        array(
            Zend_Cloud_StorageService_Factory::STORAGE_ADAPTER_KEY => 'Zend_Cloud_StorageService_Adapter_S3',
            Zend_Cloud_StorageService_Adapter_S3::AWS_ACCESS_KEY => $config->amazon->aws_access_key,
            Zend_Cloud_StorageService_Adapter_S3::AWS_SECRET_KEY => $config->amazon->aws_private_key,
        )
    );
    
    //Get the Amazon Cloud Front client
    $cloudFrontConfig = array(
        'accessKey' => $config->amazon->aws_access_key,
        'privateKey' => $config->amazon->aws_private_key,
        'distributionId' => $config->amazon->cloudfront->distribution_id,
    );
    
    $cloudFront = new App_Amazon_CloudFront($cloudFrontConfig);
    
    //Remove from S3
    echo sprintf("%s: Removing file from S3\n", date('r'));
    try {
        $amazonS3Client->deleteItem(
            $remoteFilename, 
            array(
                Zend_Cloud_StorageService_Adapter_S3::BUCKET_NAME => $config->amazon->s3->assets_bucket
            )
        );
    } catch (Zend_Cloud_StorageService_Exception $e) {
        logError(sprintf("%s: Error removing the file %s from S3\n\n", date('r'), $remoteFilename));
        $job->sendFail();
        
        return FALSE;
    }
    echo sprintf("%s: File %s removed from S3\n", date('r'), $remoteFilename);
    
    //Invalidate the image in the CDN
    echo sprintf("%s: Invalidating file %s from the CDN\n", date('r'), $remoteFilename);
    if(!$cloudFront->invalidate($remoteFilename)){
        logError(sprintf("%s: Error invalidating the file %s in the CDN\n\n", date('r'), $remoteFilename));
        $job->sendFail();
        
        return FALSE;
    }
    echo sprintf("%s: File %s invalidated from the CDN\n", date('r'), $remoteFilename);
    
    $job->sendComplete(TRUE);
    echo sprintf("%s: Job finished successfully\n\n", date('r'));
    
    return TRUE;
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