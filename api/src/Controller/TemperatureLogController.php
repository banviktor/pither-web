<?php
/**
 * @file
 * Contains \PiTher\Controller\TemperatureLogController.
 */

namespace PiTher\Controller;

use PiTher\Model\TemperatureLog;
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
  /**
   * {@inheritdoc}
   */
  public function now(Request $request, Application $app) {
    $rd = new ResponseData();
    $rd->setData(TemperatureLog::getCurrent());
    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function log(Request $request, Application $app) {
    $rd = new ResponseData();

    $input = $this->getInput($request, ['start', 'end']);
    if (is_numeric($input->start) && is_numeric($input->end) && $input->end >= $input->start) {
      $rd->setData(TemperatureLog::getInterval((int) $input->start, (int) $input->end));
    }
    else {
      $rd->addError('Invalid request.');
    }

    return $rd->toResponse();
  }

}
