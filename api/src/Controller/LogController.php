<?php
/**
 * @file
 * Contains \PiTher\Controller\LogController.
 */

namespace PiTher\Controller;

use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LogController
 * @package PiTher\Controller
 */
abstract class LogController extends Controller {

  /**
   * {@inheritdoc}
   */
  public static function routes() {
    $routes = parent::routes();
    $routes['get']['/log/' . static::getType() . '/now'] = 'now';
    $routes['get']['/log/' . static::getType() . '/{start}/{end}'] = 'log';
    return $routes;
  }

  /**
   * @return string
   */
  public static function getType() {
    return '';
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function now(Request $request, Application $app) {
    return new ResponseData();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function log(Request $request, Application $app) {
    return new ResponseData();
  }

}
