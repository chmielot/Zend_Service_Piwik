<?php

class Zend_Service_Piwik_Filter_SortOrder extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	protected $_filter = 'filter_sort_order';

	/**
     * @var integer
	 */
	protected $_filterValue = 'asc';

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Abstract::setFilterValue()
	 */
	public function setFilterValue($value = 'asc')
	{
		$this->_filterValue = $value == 'asc' ? 'asc' : 'desc';
	}
}