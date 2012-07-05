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
echo sprintf("%s: internal_upload worker ready and waiting for tasks...\n", date('r'));

$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction('internal_upload', 'internal_upload');

while($worker->work()) {
    switch($worker->returnCode()){
        case GEARMAN_SUCCESS:
            break;
        default:
            echo sprintf("%s: Error ocurred: %s\n", date('r'), $worker->returnCode());
            exit;
    }
}

echo sprintf("%s: internal_upload worker finished\n");

/**
 * Functions registered with the worker
 *
 * @param object $job 
 * @return void
 */
function internal_upload($job){
    //Get the info of the job
    list($localFilename, $remoteFilename, $removeFile) = unserialize($job->workload());
    
    //Default value if empty
    if(is_null($removeFile)){
        $removeFile = FALSE;
    }
    
    //Do some checks
    if(empty($localFilename) || empty($remoteFilename)){
        logError(sprintf("%s: The workload do not contain the required info to do the upload\n\n", date('r')));
        $job->sendFail();
        
        return FALSE;
    }
    
    echo sprintf("%s: Received job to internal upload %s\n", date('r'), $localFilename);
    
    //Do the upload
    $uploaded = upload($localFilename, $remoteFilename);
    if($uploaded !== TRUE){
        logError(sprintf("%s: Error while uploading the file to Amazon S3 - %s\n\n", date('r'), $uploaded->getMessage()));
        $job->sendFail();
        
        return FALSE;
    }
    
    //Check if we have to remove the file
    if($removeFile){
        //Remove the original file
        if(!unlink(ROOT_PATH . '/public/frontend/tmp/' . $localFilename)){
            logError(sprintf("%s: Error removing the file\n\n", date('r')));
            $job->sendFail();
            
            return FALSE;
        }
    }
    
    $job->sendComplete(TRUE);
    echo sprintf("%s: Job finished successfully\n\n", date('r'));
    
    return TRUE;
}

/**
 * Process the completed tasks
 *
 * @param object $task 
 * @return void
 */
function taskCompleted($task){
    echo sprintf("%s: Completed task %s\n", date('r'), $task->jobHandle());
}

/**
 * Process the failed tasks
 *
 * @param object $task 
 * @return void
 */
function taskFailed($task){
    echo sprintf("%s: Task %s failed\n", date('r'), $task->jobHandle());
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
 * Upload the file to Amazon
 *
 * @param string $localFilename 
 * @param string $remoteFilename
 * @return boolean
 */
function upload($localFilename, $remoteFilename){
    //Create the Amazon S3 client
    $config = getConfig();
    
    $amazonS3Client = Zend_Cloud_StorageService_Factory::getAdapter(
        array(
            Zend_Cloud_StorageService_Factory::STORAGE_ADAPTER_KEY => 'Zend_Cloud_StorageService_Adapter_S3',
            Zend_Cloud_StorageService_Adapter_S3::AWS_ACCESS_KEY => $config->amazon->aws_access_key,
            Zend_Cloud_StorageService_Adapter_S3::AWS_SECRET_KEY => $config->amazon->aws_private_key,
        )
    );
    
    //Get the contents of the file
    $fileContent = file_get_contents(ROOT_PATH . '/public/frontend/tmp/' . $localFilename);
    $fileInfo = new SplFileInfo(ROOT_PATH . '/public/frontend/tmp/' . $localFilename);
    
    //Upload the file to Amazon
    echo sprintf("%s: Uploading file to Amazon S3\n", date('r'));
    try {
        $amazonS3Client->storeItem($remoteFilename, $fileContent, array(
            Zend_Cloud_StorageService_Adapter_S3::BUCKET_NAME => $config->amazon->s3->assets_bucket,
            Zend_Cloud_StorageService_Adapter_S3::METADATA => array(
                Zend_Service_Amazon_S3::S3_ACL_HEADER => Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ,
            )
        ));
        
        echo sprintf("%s: File uploaded to Amazon S3 (%s bytes)\n", date('r'), $fileInfo->getSize());
        
        return TRUE;
    } catch (Zend_Cloud_StorageService_Exception $e){
        return $e;
    }
}

/**
 * Return the content of the config file
 *
 * @return object
 */
function getConfig(){
    return new Zend_Config_Ini(ROOT_PATH . '/application/configs/application.ini', APPLICATION_ENV);
}