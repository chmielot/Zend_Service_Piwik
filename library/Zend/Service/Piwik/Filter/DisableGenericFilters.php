<?php

class Zend_Service_Piwik_Filter_IdSubtable extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $_filter = 'idSubtable';

	/**
     * @var integer
	 */
	protected $_filterValue = 0;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Abstract::setFilterValue()
	 */
	public function setFilterValue($value = 0)
	{
		$this->_filterValue = (integer)$value;
	}
}