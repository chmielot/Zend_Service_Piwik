<?php

class Zend_Service_Piwik_Filter_Abstract implements Zend_Service_Piwik_Filter_Interface
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Interface::getFilter()
	 */
	public function getFilter()
	{
		return $this->_filter;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Interface::getFilterValue()
	 */
	public function getFilterValue()
	{
		return $this->_filterValue;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Interface::setFilterValue()
	 */
	public function setFilterValue($value)
	{
		$this->_filterValue = $value;
	}
}