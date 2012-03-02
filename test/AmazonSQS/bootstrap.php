<?php 

/**
 * This file is part of the AmazonSQS package.
 *
 * (c) Christian Eikermann <christian@chrisdev.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

date_default_timezone_set('UTC'); 

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