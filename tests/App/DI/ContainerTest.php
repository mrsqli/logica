<?php

require_once 'Zend/Config/Ini.php';
require_once 'App/DI/Definition/ConfigObject.php';
require_once dirname(__FILE__) . '/_files/ContainerWrapper.php';
/**
 * Test the dependency injector
 * 
 * @category   App
 * @package    App_DI
 * @copyright  company
 */
class App_DI_ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test the initial state of the dependency injection container
     *
     * @access public
     * @return void
     **/
    public function testInitialState(){
        $internalInstanceProperty = ContainerWrapper::getStaticVariableInstances();
        
        $this->assertTrue(empty($internalInstanceProperty));
    }
    
    /**
     * Test the get function and check if the container stores it on the
     * instances array by asking for the ConfigObject
     *
     * @access public
     * @return void
     **/
    public function testGetMethod(){
        $objectFromContainer = ContainerWrapper::get('ConfigObject');
        $internalInstanceProperty = ContainerWrapper::getStaticVariableInstances();
        
        $this->assertTrue(array_key_exists('configobject', $internalInstanceProperty));
        $this->assertEquals($objectFromContainer, $internalInstanceProperty['configobject']);
    }
}
