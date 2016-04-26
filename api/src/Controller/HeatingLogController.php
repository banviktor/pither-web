<?php
/**
 * @file
 * Contains \PiTher\Controller\HeatingLogController.
 */

namespace PiTher\Controller;

use PiTher\Model\HeatingLog;
use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HeatingLogController
 * @package PiTher\Controller
 */
class HeatingLogController extends LogController {

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return 'heating';
  }

  /**
   * {@inheritdoc}
   */
  public function now(Request $request, Application $app) {
    $rd = new ResponseData();
    $rd->setData(HeatingLog::getCurrent());
    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function log(Request $request, Application $app) {
    $rd = new ResponseData();

    $input = $this->getInput($request, ['start', 'end']);
    if (is_numeric($input->start) && is_numeric($input->end) && $input->end >= $input->start) {
      $rd->setData(HeatingLog::getInterval((int) $input->start, (int) $input->end));
    }
    else {
      $rd->addError('Invalid request.');
    }

    return $rd->toResponse();
  }

}
