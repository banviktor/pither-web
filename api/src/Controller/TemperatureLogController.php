<?php
/**
 * @file
 * Contains \PiTher\Controller\TemperatureLogController.
 */

namespace PiTher\Controller;

use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TemperatureLogController
 * @package PiTher\Controller
 */
class TemperatureLogController extends LogController {

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return 'temp';
  }

  /**
   * {@inheritdoc}
   */
  public function now(Request $request, Application $app) {
    $rd = new ResponseData();

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function log(Request $request, Application $app) {
    $rd = new ResponseData();
    $input = $this->getInput($request, ['start', 'end']);

    return $rd->toResponse();
  }

}
