<?php

class Zend_Service_Piwik_Filter_Pattern extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $_filter = 'filter_pattern';

	/**
     * @var integer
	 */
	protected $_filterValue = null;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Abstract::setFilterValue()
	 */
	public function setFilterValue($value = '')
	{
		$this->_filterValue = $value;
	}
}