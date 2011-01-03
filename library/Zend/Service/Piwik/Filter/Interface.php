<?php

interface Zend_Service_Piwik_Filter_Interface
{
	/**
	 * Return filter
	 *
	 * @return string
	 */
	public function getFilter();

	/**
	 * Get the value of the filter
	 *
	 * @return mixed
	 */
	public function getFilterValue();

	/**
	 * Set the value of the filter
	 *
	 * @param mixed $value
	 */
	public function setFilterValue($value);
}