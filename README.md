Zend_Service_Piwik
==================

Synopsis:
---------
Use the Zend_Service_Piwik-component to query your Piwik-Installation via the API.

Basic usage:
------------
``` php
<?php
$piwik = new Zend_Service_Piwik();
$piwik->setHost('http://demo.piwik.org')
      ->setAuthToken('anonymous')
      ->setIdSite(7)
      ->setFormat(Zend_Service_Piwik::FORMAT_JSON)
      ->setDate('previous7');

$pageTitles = $piwik->Actions()->getPageTitles();

echo $pageTitles;
?>
```

`$pageTitles` contains the relevant information and can be processed further if needed.