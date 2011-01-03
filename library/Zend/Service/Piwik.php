<?php

class Zend_Service_Piwik extends Zend_Service_Abstract
{
	/**
	 * @var string
	 */
	protected $_tokenAuth = null;

	/**
     * @var integer
	 */
	protected $_idSite = null;

	/**
     * @var string
	 */
	protected $_host = null;

	/**
     * @var boolean
	 */
	protected $_useSsl = false;

	/**
	 * Output format XML
	 *
	 * @var string
	 */
	const FORMAT_XML = 'xml';

	/**
	 * Output format JSON
	 *
	 * @var string
	 */
	const FORMAT_JSON = 'json';

	/**
	 * Output format CSV
	 *
	 * @var string
	 */
	const FORMAT_CSV = 'csv';

	/**
	 * Output format TSV
	 *
	 * @var string
	 */
	const FORMAT_TSV = 'tsv';

	/**
	 * Output format HTML
	 *
	 * @var string
	 */
	const FORMAT_HTML = 'html';

	/**
	 * Output format serialized PHP
	 *
	 * @var string
	 */
	const FORMAT_PHP = 'php';

	/**
	 * Output format RSS
	 *
	 * @var string
	 */
	const FORMAT_RSS = 'rss';

	/**
	 * Output format original PHP data
	 *
	 * @var string
	 */
	const FORMAT_ORIGINAL = 'original';

	/**
	 * @var string
	 */
	protected $_format = self::FORMAT_ORIGINAL;

	/**
	 * @var array
	 */
	protected $_formats = array(
		self::FORMAT_CSV,
		self::FORMAT_HTML,
		self::FORMAT_JSON,
		self::FORMAT_ORIGINAL,
		self::FORMAT_PHP,
		self::FORMAT_RSS,
		self::FORMAT_TSV,
		self::FORMAT_XML
	);

	/**
	 * @var string
	 */
	const PERIOD_DAY = 'day';

	/**
	 * @var string
	 */
	const PERIOD_WEEK = 'week';

	/**
	 * @var string
	 */
	const PERIOD_MONTH = 'month';

	/**
	 * @var string
	 */
	const PERIOD_YEAR = 'year';

	/**
	 * @var string
	 */
	protected $_period = self::PERIOD_DAY;

	/**
	 * @var array
	 */
	protected $_periods = array(
		self::PERIOD_DAY,
		self::PERIOD_MONTH,
		self::PERIOD_WEEK,
		self::PERIOD_YEAR
	);

	/**
     * @var string
	 */
	protected $_date = null;

	/**
	 * @var array
	 */
	protected $_filters = array();

	/**
	 * Constructor
	 *
	 * @param Zend_Config $config
	 */
	public function __construct(Zend_Config $config = null)
	{
		if (null !== $config)
		{
			$this->setConfig($config);
		}
	}

	/**
	 * Set configuration options
	 *
	 * @param Zend_Config $config
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setConfig($config)
	{
		if ($config instanceof Zend_Config)
		{
			$config = $config->toArray();
		}
		else if (!is_array($config))
		{
			$config = (array)$config;
		}

		return $this;
	}

	/**
	 * Return auth token
	 *
	 * @return string
	 */
	public function getAuthToken()
	{
		return $this->_tokenAuth;
	}

	/**
	 * Set auth token
     *
	 * @param string $token
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setAuthToken($token)
	{
		$this->_tokenAuth = $token;

		return $this;
	}

	/**
	 * Return host
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->_host;
	}

	/**
	 * Set host
	 *
	 * @param string $host
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setHost($host)
	{
		$this->_host = $host;

		return $this;
	}

	/**
	 * Is this an SSL request?
	 *
	 * @return boolean
	 */
	public function isSsl()
	{
		return $this->_useSsl;
	}

	/**
	 * Switch usage of SSL
	 *
	 * @param boolean $useSsl
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setUseSsl($useSsl = false)
	{
		$this->_useSsl = $useSsl;

		return $this;
	}

	/**
	 * Return site id
	 *
	 * @return integer
	 */
	public function getIdSite()
	{
		return $this->_idSite;
	}

	/**
	 * Set site id
	 *
	 * @param integer $siteId
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setIdSite($idSite)
	{
		$this->_idSite = (integer)$idSite;

		return $this;
	}

	/**
	 * Return current set format of the response
	 *
	 * @return string
	 */
	public function getFormat()
	{
		return $this->_format;
	}

	/**
	 * Set the format of the response
	 *
	 * @param string $format
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setFormat($format = self::FORMAT_ORIGINAL)
	{
		if (in_array($format, $this->_formats))
		{
			$this->_format = $format;
		}

		return $this;
	}

	/**
	 * Return period
	 *
	 * @return string
	 */
	public function getPeriod()
	{
		return $this->_period;
	}

	/**
	 * Set period
	 *
	 * @param string $period
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setPeriod($period = self::PERIOD_DAY)
	{
		if (in_array($period, $this->_periods))
		{
			$this->_period = $period;
		}

		return $this;
	}

	/**
	 * Return set date
	 *
	 * @return string
	 */
	public function getDate()
	{
		return $this->_date;
	}

	/**
	 * Set the date in various flavors
	 *
	 * default:       YYYY-MM-DD
	 * magic formats: today, yesterday (these are relative to the webservers timezone)
	 * ranges:        lastX (where X is the number of days incl. today)
	 *                previousX (where X is the number of days without today)
	 *                YYYY-MM-DD,YYYY-MM-DD (in between the 2 dates)
	 *
	 * @param string $date
	 *
	 * @return Zend_Service_Piwik
	 */
	public function setDate($date = '')
	{
		if (Zend_Date::isDate($date, 'YYYY-MM-DD'))
		{
			$this->_date = $date;
		}
		else if (preg_match('#(last|previous)[0-9]+#si', $date))
		{
			$this->_date = $date;
		}
		else if (substr($date, 10, 1) == ',')
		{
			$dates = explode(',', $date);

			foreach ($dates as $d)
			{
				if (Zend_Date::isDate($d, 'YYYY-MM-DD'))
				{
					continue;
				}
			}

			$date = implode(',', $dates);
		}
		else
		{
			// Whoops, no date is valid, so lets take the current date
			$this->_date = date('YYYY-mm-dd');
		}

		return $this;
	}

	public function requestApi(Zend_Service_Piwik_Method_Interface $method = null)
	{
		$client = self::getHttpClient();

		$client->setUri($this->_host)
		       ->setMethod(Zend_Http_Client::GET)
		       ->setParameterGet('module', 'API')
		       ->setParameterGet('token_auth', $this->_tokenAuth)
		       ->setParameterGet('idSite', $this->_idSite)
		       ->setParameterGet('format', $this->_format)
		       ->setParameterGet('period', $this->_period)
		       ->setParameterGet('date', $this->_date);

		// append filters
		foreach ($this->_filters as $filter)
		{
			$filterValue = $filter->getFilterValue();
			if (null !== $filterValue)
			{
				$client->setParameterGet($filter->getFilter(), $filterValue);
			}
		}

		// append method

		$result = $client->request()->getBody();
		header('Content-Type: application/json');
		echo $result;
	}

	/**
	 * Add filter to the service
	 *
	 * @param Zend_Service_Piwik_Filter_Interface $filter
	 */
	public function addFilter(Zend_Service_Piwik_Filter_Interface $filter)
	{
		$this->_filters[$filter->getFilter()] = $filter;
	}

	/**
	 * Remove filter from filters by filtername
	 *
	 * @param string $filter
	 */
	public function removeFilter($filter)
	{
		unset($this->_filters[$filter]);
	}
}