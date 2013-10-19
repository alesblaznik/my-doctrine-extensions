<?php
/*
 * This file bootstraps the test environment.
 */
namespace My\Doctrine\Tests;

error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../../../vendor/autoload.php';

$classLoader = new \Doctrine\Common\ClassLoader('My\Doctrine\Tests', __DIR__ . '/../../../');
$classLoader->register();

$myDoctrineLoader = new \Doctrine\Common\ClassLoader('My\Doctrine', __DIR__ . '/../../../../lib');
$myDoctrineLoader->register();