<?php
/**
 * @file
 * Contains \PiTher\Controller\SensorController.
 */

namespace PiTher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    // Random temperature between 17.0 and 22.0 degrees celsius.
    $temp = rand(2902, 2952);
    return $app->json($temp / 10);
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function log(Request $request, Application $app) {
    $start = (int) $request->get('start');
    $end = (int) $request->get('end');

    $temps = [];
    for ($t = $start; $t <= $end; $t += 60) {
      $temps[$t] = rand(2902, 2952);
    }
    return $app->json($temps);
  }

}
