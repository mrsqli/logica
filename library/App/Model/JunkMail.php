<?php

/**
 * Model that manages the flags (controller names) for defining
 * the Flags in the application
 *
 * @package backoffice_models
 * @copyright company
 */
class JunkMail extends App_Model {

    protected $_primary = 'id_mail';
    protected $_name = 'junk_mail';

    public function listJunkMail() {
        
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from($this->_name,'email');


        return $this->fetchall($select)->toArray();
    }

}