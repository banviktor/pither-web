<?php
/**
 * @file
 * Contains \PiTher\Controller\TargetLogController.
 */

namespace PiTher\Controller;

use PiTher\Model\TargetLog;
use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TargetLogController
 * @package PiTher\Controller
 */
class TargetLogController extends LogController {

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return 'target';
  }

  /**
   * {@inheritdoc}
   */
  public function now(Request $request, Application $app) {
    $rd = new ResponseData();
    $rd->setData(TargetLog::getCurrent());
    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function log(Request $request, Application $app) {
    $rd = new ResponseData();

    $input = $this->getInput($request, ['start', 'end']);
    if (is_numeric($input->start) && is_numeric($input->end) && $input->end >= $input->start) {
      $rd->setData(TargetLog::getInterval((int) $input->start, (int) $input->end));
    }
    else {
      $rd->addError('Invalid request.');
    }

    return $rd->toResponse();
  }

}
