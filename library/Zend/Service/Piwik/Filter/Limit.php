<?php

class Zend_Service_Piwik_Filter_Limit extends Zend_Service_Piwik_Filter_Abstract
{
	/**
     * @var string
	 */
	protected $_filter = 'filter_limit';

	/**
     * @var integer
	 */
	protected $_filterValue = 100;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Abstract::setFilterValue()
	 */
	public function setFilterValue($value = 100)
	{
		$value = (integer)$value;

		if ($value < -1)
		{
			$value = -1;
		}

		$this->_filterValue = $value;
	}
}