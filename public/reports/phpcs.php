<?php
// Load the XML source
$xml = new DOMDocument;
$xml->load('phpcs.xml');

$xsl = new DOMDocument;
$xsl->load('phpcs.xsl');

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attach the xsl rules

echo $proc->transformToXML($xml);