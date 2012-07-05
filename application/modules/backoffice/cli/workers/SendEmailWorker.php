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
echo sprintf("%s: Send_email worker ready and waiting for tasks...\n", date('r'));

$worker = new GearmanWorker();
$worker->addServer();
$worker->addFunction('send_email', 'send_email');

while($worker->work()) {
    switch($worker->returnCode()){
        case GEARMAN_SUCCESS:
            break;
        default:
            echo sprintf("%s: Error ocurred: %s\n", date('r'), $worker->returnCode());
            exit;
    }
}

echo sprintf("%s: Send_email worker finished\n");

/**
 * Functions registered with the worker
 *
 * @param GearmanJob $job
 * @return boolean
 */
function send_email($job){
    //Get the info of the job
    $workload = unserialize($job->workload());
    
    //Ensure the minimum info
    if(!array_key_exists('text', $workload) && !array_key_exists('html', $workload)){
        echo sprintf("%s: To send an email we need at least the text or html\n", date('r'));
        $job->sendFail();
        
        return FALSE;
    }
    
    if(!array_key_exists('to', $workload) || (array_key_exists('to', $workload) && empty($workload['to']))){
        echo sprintf("%s: To send an email we need the recipient address\n", date('r'));
        $job->sendFail();
        
        return FALSE;
    }
    
    if(!array_key_exists('subject', $workload)){
        echo sprintf("%s: To send an email we need the subject of the email\n", date('r'));
        
        $job->sendFail();
        return FALSE;
    }
    
    echo sprintf("%s: Received a task to send email to %s\n", date('r'), implode(', ', (is_array($workload['to'])? $workload['to'] : array($workload['to']))));
    
    $config = getConfig();
    
    $mail = new Zend_Mail('utf-8');

    if($config->system->email_system->send_by_amazon_ses){
        $transport = new App_Mail_Transport_AmazonSES(array(
            'accessKey' => $config->amazon->aws_access_key,
            'privateKey' => $config->amazon->aws_private_key
        ));   
    }
    
    if(array_key_exists('text', $workload)){
        $mail->setBodyText($workload['text']);
    }
    
    if(array_key_exists('html', $workload)){
        $mail->setBodyHtml($workload['html']);
    }
    
    if(array_key_exists('reply', $workload) && !empty($workload['reply'])){
        $mail->setReplyTo($workload['reply']);
    }

    $mail->setFrom($config->amazon->ses->from_address, $config->amazon->ses->from_name);
    $mail->addTo($workload['to']);
    $mail->setSubject($workload['subject']);

    //Prepare gearman client
    $config = getConfig();
    $gearmanClient = new GearmanClient();
    
    if (!empty($config->gearman->servers)) {
        $gearmanClient->addServers($config->gearman->servers->toArray());
    } else {
        $gearmanClient->addServer();
    }
    
    //Add the callbacks
    $gearmanClient->setCompleteCallback('taskCompleted');
    $gearmanClient->setFailCallback('taskFailed');
    
    try{
        if(isset($transport) && $transport instanceOf App_Mail_Transport_AmazonSES){
            $mail->send($transport);
        }else{
            $mail->send();
        }
        
        //Some status info
        echo sprintf("%s: Email (%s) sent to %s\n", date('r'), $workload['subject'], implode(', ', (is_array($workload['to'])? $workload['to'] : array($workload['to']))));
        echo sprintf("%s: Task finished successfully\n\n", date('r'));
        $job->sendComplete(TRUE);
        
        return TRUE;
    }catch(Exception $e){
        logError(sprintf("Error while sending an email to %s.\n\nError: %s\n", $workload['to'], $e->getMessage()));
        $job->sendFail();
        
        return FALSE;
    }
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
 * Return the content of the config file
 *
 * @return object
 */
function getConfig(){
    return new Zend_Config_Ini(ROOT_PATH . '/application/configs/application.ini', APPLICATION_ENV);
}