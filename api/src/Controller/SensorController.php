<?php
/**
 * @file
 * Contains \PiTher\Controller\SensorController.
 */

namespace PiTher\Controller;

/**
 * Class SensorController
 * @package PiTher\Controller
 */
class SensorController extends Controller {

  /**
   * {@inheritdoc}
   */
  public static function routes() {
    $routes = parent::routes();
    $routes['get']['/sensor/log/{start}/{end}'] = 'log';
    return $routes;
  }

}
