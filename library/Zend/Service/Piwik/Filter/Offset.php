<?php

class Zend_Service_Piwik_Filter_Offset extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $_filter = 'filter_offset';

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
		$value = (integer)$value;

		if ($value < 0)
		{
			$value = 0;
		}

		$this->_filterValue = $value;
	}
}