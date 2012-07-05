<?php

/**
 * Mailer action helper
 * 
 * @uses Zend_Controller_Action_Helper_Abstract
 * @category Core
 * @package Core_Controller_Action_Helper
 */
class App_Controller_Action_Helper_Mailer extends Zend_Controller_Action_Helper_Abstract {

    /**
     * Send an email
     * @param String $emailTo
     * @param String $emailFrom
     * @param String $emailSubject
     * @param String $emailData
     * @param String $tpl
     * @return Zend_Session_Namespace
     * @author Reda Menhouch, reda@fornetmaroc.com
     */
    public function sendMail($toMail, $toName, $mailSubject, $emailData, $tpl, $fromMail = false, $fromName = false, $sujetMail = "", $h1Mail = "") {

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);

        $mailConfig = array('auth' => $config->mail->transport->auth,
            'username' => $config->mail->transport->username,
            'password' => $config->mail->transport->password,
            'ssl' => $config->mail->transport->ssl,
            'port' => $config->mail->transport->port);

        //var_dump($mailConfig); die;

        $transport = new Zend_Mail_Transport_Smtp($config->mail->transport->host, $mailConfig);

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/modules/frontend/views/emails/');

        foreach ($emailData as $key => $value) {
            $html->assign($key, $value);
        }
        $html->assign('sujetMail', $sujetMail);
        $html->assign('h1Mail', $h1Mail);
        $mailSubjectTail = "";
        //$mailSubjectTail=" - [" . $toMail . "]";

        $html->assign('site', $config->site->url);
        $bodyText = $html->render($tpl . '.phtml');

        $fromMail = ($fromMail === false) ? $config->mail->defaultFrom->email : $fromMail;
        $fromName = ($fromName === false) ? $config->mail->defaultFrom->name : $fromName;

        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($bodyText, 'utf-8');
        $mail->setFrom($fromMail, $fromName);

        $mail->addTo($toMail, $toName);

        $mail->setSubject($config->mail->defaultSubject->prefix . $mailSubject . $mailSubjectTail);
        $mail->send($transport);
    }

}
