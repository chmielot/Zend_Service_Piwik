<?php
/**
 * @throws Zend_Service_Piwik_Exception
 *
 * @method mixed Api
 * @method mixed Actions
 * @method mixed CustomVariables
 * @method mixed Goals
 * @method mixed LanguagesManager
 * @method mixed Live
 * @method mixed PDFReports
 * @method mixed Provider
 * @method mixed Referers
 * @method mixed SEO
 * @method mixed SitesManager
 * @method mixed UserCountry
 * @method mixed UserSettings
 * @method mixed UsersManager
 * @method mixed VisitFrequency
 * @method mixed VisitTime
 * @method mixed VisitorInterest
 * @method mixed VistsSummary
 */
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
     * @var boolean
     */
    protected $_prettyDisplay = false;

    /**
     * @var integer
     */
    protected $_serialize = true;

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
    protected $_date = 'today';

    /**
     * @var array
     */
    protected $_filters = array();

    /**
     * @var string
     */
    protected $_jsoncallback = null;

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
     * Magic method
     * Call a API method, e.g.:
     *
     * <pre>
     * $piwik->API->getDefaultMetrics();
     * </pre>
     *
     * @param string $module
     * @param string $params
     *
     * @return Zend_Service_Piwik_MethodProxy
     */
    public function __call($module, $params)
    {
        return new Zend_Service_Piwik_MethodProxy($module, $this);
    }

    /**
     * Set configuration options
     *
     * @param Zend_Config $config
     *
     * @return Zend_Service_Piwik
     */
    public function setConfig(Zend_Config $config)
    {
        $config = $config->toArray();

        foreach ($config as $key => $value) {
            $option = str_replace('_', ' ', strtolower($key));
            $option = str_replace(' ', '', ucwords($option));
            $method = 'set' . $option;

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Query the Piwik API
     *
     * @param string $method
     * @param array  $params
     *
     * @throws Zend_Service_Piwik_Exception
     *
     * @return string
     */
    public function queryApi($method = null, $params = array())
    {
        $client = self::getHttpClient();

        // do we have the mandatory parameters?
        if (null === $this->_tokenAuth
            || null === $this->_idSite
            || null === $this->_host
            || null === $method)
        {
            $msg = 'One mandatory setting (tokenAuth, idSite, Host or Method) is empty.';
            throw new Zend_Service_Piwik_Exception($msg);
        }

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
        $client->setParameterGet('method', $method);

        // append jsoncallback
        if (null !== $this->_jsoncallback)
        {
            $client->setParameterGet('jsoncallback', $this->_jsoncallback);
        }

        // special options when format = PHP
        if ($this->_format = self::FORMAT_PHP)
        {
            if (false !== $this->_prettyDisplay)
            {
                $client->setParameterGet('prettyDisplay', $this->_prettyDisplay);
            }

            if (true !== $this->_serialize)
            {
                $client->setParameterGet('serialize', $this->_serialize);
            }
        }

        try
        {
            return $client->request()->getBody();
        }
        catch (Zend_Http_Client_Exception $e)
        {
            $msg = 'An error occured: ' . $e->getMessage();
            throw new Zend_Service_Piwik_Exception($msg);
        }
    }

    /**
     * Return auth token
     *
     * @return string
     */
    public function getTokenAuth()
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
    public function setTokenAuth($token)
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
        else if (in_array($date, array('today', 'yesterday')))
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

        return $this;
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

    /**
     * Return JSON callback
     *
     * @return string
     */
    public function getJsonCallback()
    {
        return $this->_jsoncallback;
    }

    /**
     * Set json callback
     *
     * @param string $callback
     *
     * @return Zend_Service_Piwik
     */
    public function setJsonCallback($callback = '')
    {
        $this->_jsoncallback = $callback;

        return $this;
    }

    /**
     * Get the current setting for pretty display of PHP
     * content
     *
     * @return boolean
     */
    public function getPrettyDisplay()
    {
        return (boolean)$this->_prettyDisplay;
    }

    /**
     * Set the state of prettyDisplay for PHP
     *
     * @param boolean $prettyDisplay
     *
     * @return Zend_Service_Piwik
     */
    public function setPrettyDisplay($prettyDisplay = false)
    {
        $this->_prettyDisplay = (boolean)$prettyDisplay;

        return $this;
    }

    /**
     * Get the setting if the output should be serialized or not
     * when using format PHP
     *
     * @return boolean
     */
    public function getSerialize()
    {
        return (boolean)$this->_serialize;
    }

    /**
     * Change the setting if the output should be serialized or not
     * when using format PHP
     *
     * @param boolean $serialize
     *
     * @return Zend_Service_Piwik
     */
    public function setSerialize($serialize = false)
    {
        $this->_serialize = (boolean)$serialize;

        return $this;
    }
}