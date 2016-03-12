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

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function log(Request $request, Application $app) {
    $start = (int) $request->get('start');
    $end = (int) $request->get('end');

    $events = [];
    $last = FALSE;
    for ($t = $start; $t <= $end; $t += rand(30, 600)) {
      $events[$t] = $last = !$last;
    }
    return $app->json($events);
  }

}
