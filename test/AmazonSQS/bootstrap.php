<?php 

error_reporting(E_ALL);

require_once __DIR__.'/../../vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->useIncludePath(true);

$loader->registerNamespaces(array(
    'AmazonSQS' => __DIR__.'/../../src',
    'Symfony'   => __DIR__.'/../../vendor/',
    'apiTalk'   => __DIR__.'/../../vendor/apiTalk/src',
));

$loader->register();