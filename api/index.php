<?php
/**
 * @file
 * Entry point for PiTher Web.
 */

session_start();
require_once __DIR__.'/../vendor/autoload.php';
$app = new PiTher\Application();
$app->run();
session_write_close();
