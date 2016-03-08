<?php
/**
 * @file
 * Entry point for PiTher Web.
 */

require_once __DIR__.'/../vendor/autoload.php';
$app = new PiTher\Application();
$app->run();
