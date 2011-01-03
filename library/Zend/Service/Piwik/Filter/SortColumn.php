<?php

class Zend_Service_Piwik_Filter_SortColumn extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $_filter = 'filter_sort_column';

	/**
     * @var integer
	 */
	protected $_filterValue = null;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Abstract::setFilterValue()
	 */
	public function setFilterValue($value = 'asc')
	{
		$this->_filterValue = $value;
	}
}