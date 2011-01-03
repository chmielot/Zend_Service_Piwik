<?php

class Zend_Service_Piwik_Filter_DisableGenericFilters extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $_filter = 'disable_generic_filters';

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

		$this->_filterValue = $value != 0 ? 1 : 0;
	}
}