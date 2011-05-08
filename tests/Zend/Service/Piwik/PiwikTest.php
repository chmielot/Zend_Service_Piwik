<?php
class Zend_Service_PiwikTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstanceWithConfigFile()
    {
        $configFile = dirname(__FILE__) . '/_fixtures/piwik.ini';
        $config = new Zend_Config_Ini($configFile, 'piwik');

        $piwik = new Zend_Service_Piwik($config);

        $this->assertEquals('http://demo.piwik.org', $piwik->getHost());
        $this->assertEquals('7', $piwik->getIdSite());
        $this->assertEquals('json', $piwik->getFormat());
        $this->assertEquals('anonymous', $piwik->getTokenAuth());
        $this->assertEquals('previous7', $piwik->getDate());
    }
}
