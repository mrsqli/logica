<?php

class LoginAttempt extends Zend_Db_Table_Abstract {      //App_Model {

    protected $_name = 'login_attempt';
    protected $_primary = 'login_Attempt_Id';
    var $_iniConfig = null;

    public function init() {
        $this->_iniConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        parent::init();
    }

    /**
     * set a new entry in the table in case of a failed login
     * 
     * @author kamal zairig - kamal@fornetmaroc.com
     */
    public function addFailedLogin() {
        // $item = $this->create();
        $item->ip = $_SERVER['REMOTE_ADDR'];
        $item->time_create = time();
        $this->insert((array) $item);
        //item->save();
    }

    /**
     * Checks if User has attempted max allowed failed login threshhold or not
     * 
     * @return <bool> true:can, false cannot
     */
    public function canAttemptToLogin() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $max = $this->_iniConfig->security->bruteforce->max;
        $timeout = $this->_iniConfig->security->bruteforce->timeout;
        $interval = $this->_iniConfig->security->bruteforce->interval;

        $select = $this->select();
        $select->order(' time_create DESC ')
                ->where('ip = "' . $ip . '"');
        $attempts = $this->fetchAll($select);


        if ($attempts->count() < $max) {
            return true;
        }

        $i = 1;
        $lastTime = 0;
        foreach ($attempts as $attempt) {

            if ($i == 1) {
                $delta = time() - $attempt->time_create;
                if ($delta > $timeout) {
                    return true;
                }
                $lastTime = $attempt->time_create;
            }

            if ($i == $max) {
                return (($lastTime - $attempt->time_create) <= $interval) ? FALSE : TRUE;
            }
            $i++;
        }
        return true;
    }

}
