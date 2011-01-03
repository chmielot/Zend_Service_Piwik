<?php

class Zend_Service_Piwik_Filter_Language extends Zend_Service_Piwik_Filter_Abstract
{
	/**
	 * @var string
	 */
	const DEFAULT_LANGUAGE = 'en';

	/**
	 * @var string
	 */
	protected $_filter = 'language';

	/**
     * @var integer
	 */
	protected $_filterValue = self::DEFAULT_LANGUAGE;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Service_Piwik_Filter_Abstract::setFilterValue()
	 */
	public function setFilterValue($value = 'en')
	{
		if (strlen($value) != 2)
		{
			$value = self::DEFAULT_LANGUAGE;
		}

		// check for a valid language code

		$this->_filterValue = $value;
	}
}