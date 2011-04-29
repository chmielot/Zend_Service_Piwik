<?php

class Zend_Service_Piwik_MethodProxy
{
    /**
     * @var Zend_Service_Piwik
     */
    protected $_piwikInstance = null;

    /**
     * @var string
     */
    protected $_module = null;

    /**
     * Constructor
     *
     * @param string             $module
     * @param Zend_Service_Piwik $piwik
     */
    public function __construct($module, Zend_Service_Piwik $piwik)
    {
        $this->_module        = $module;
        $this->_piwikInstance = $piwik;
    }

    /**
     * Set the used module
     *
     * @param string $module
     */
    public function setModule($module)
    {
        $this->_module = $module;
    }

    /**
     * Magic method to call $method with $params
     *
     * @param string $method
     * @param array  $params
     *
     * @method mixed getCustomVariables
     *
     * @return string
     */
    public function __call($method, $params)
    {
        $name = $this->_module . '.' . $method;

        return $this->_piwikInstance->queryApi($name, $params);
    }
}