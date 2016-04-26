<?php
/**
 * @file
 * Contains \PiTher\Controller\LogController.
 */

namespace PiTher\Controller;

use PiTher\Model\Log;
use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LogController
 * @package PiTher\Controller
 */
class LogController extends Controller {

  /**
   * {@inheritdoc}
   */
  public static function routes() {
    $routes = [];
    $routes['get']['/log/{type}/{view}'] = 'get';
    $routes['get']['/log/fill'] = 'fill';
    $routes['get']['/log/clean'] = 'clean';
    return $routes;
  }

  /**
   * {@inheritdoc}
   */
  public function clean(Request $request, Application $app) {
    $rd = new ResponseData();
    Log::cleanOld();
    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function fill(Request $request, Application $app) {
    $now = ceil(time() / 5 / 60) * 5 * 60 + 24 * 60 * 60;
    $curr = $now - 35 * 24 * 60 * 60;
    $temp = 17;
    $target = 20;
    while ($curr <= $now) {
      if (rand(0, 100) <= 5) {
        $target = $target == 20 ? 17 : 20;
      }
      $temp += ($target - $temp) * 0.04;
      $app['db']->insert('log_target', [
        'date' => date('Y-m-d H:i:s', $curr),
        'temp' => $target,
      ]);
      $app['db']->insert('log_temp', [
        'date' => date('Y-m-d H:i:s', $curr),
        'temp' => $temp,
      ]);
      $curr += 5 * 60;
    }

    return $app->json(null);
  }

  /**
   * {@inheritdoc}
   */
  public function get(Request $request, Application $app) {
    $rd = new ResponseData();
    $input = $this->getInput($request, ['type', 'view']);

    $interval = $length = 0;
    switch ($input->view) {
      case '24h':
        $interval = 30 * 60;
        $length = 24 * 3600;
        break;

      case 'week':
        $interval = 7 * 30 * 60;
        $length = 7 * 24 * 3600;
        break;

      case 'month':
        $interval = 4 * 7 * 30 * 60;
        $length = 28 * 24 * 3600;
        break;

      default:
        $rd->addError('Invalid request.');
        break;
    }
    if (!$rd->hasErrors()) {
      $end = floor(time() / $interval) * $interval + 7200;
      $start = $end - $length;
      $log = Log::getValues($input->type, $start, $end, $interval);
      $rd->setData($log);
    }

    return $rd->toResponse();
  }

}
