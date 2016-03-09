<?php
/**
 * @file
 * Contains \PiTher\Controller\HeatingController.
 */

namespace PiTher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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
