<?php

require_once('App/DI/Container.php');

/**
 * This class just provides a method to be able to access the objects stored
 * on the static instance array
 *
 * @category   App
 * @package    App_DI
 * @copyright  company
 */
class ContainerWrapper extends App_DI_Container{

    /**
     * Method to access the protected static property that holds the
     * objects
     *
     * @access public
     * @return mixed
     **/
    public static function getStaticVariableInstances(){
        return self::$_instances;
    }
}