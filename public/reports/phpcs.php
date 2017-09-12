<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

// Load the XML source
$xml = new DOMDocument;
$xml->load('phpcs.xml');

$xsl = new DOMDocument;
$xsl->load('phpcs.xslt');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attach the xsl rules

echo $proc->transformToXML($xml);