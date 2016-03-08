<?php
/**
 * @file
 * Contains \PiTher\Controller\HeatingController.
 */

namespace PiTher\Controller;

/**
 * Class HeatingController
 * @package PiTher\Controller
 */
class HeatingController extends Controller {

  /**
   * {@inheritdoc}
   */
  public static function routes() {
    $routes = parent::routes();
    $routes['get']['/heating/log/{start}/{end}'] = 'log';
    return $routes;
  }

}
