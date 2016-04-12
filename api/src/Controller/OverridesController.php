<?php
/**
 * @file
 * Contains \PiTher\Controller\OverridesController.
 */

namespace PiTher\Controller;

use PiTher\Model\Override;
use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OverridesController
 * @package PiTher\Controller
 */
class OverridesController extends Controller {

  /**
   * {@inheritdoc}
   */
  public function get(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules'])) {
      $rd->addPermissionError('access rules');
    }
    else {
      $id = $request->get('id');
      $override = Override::load($id);
      if ($override !== NULL) {
        $rd->setData($override);
      }
      else {
        $rd->addError("Override does not exist.");
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules'])) {
      $rd->addPermissionError('access rules');
    }
    else {
      $overrides = [];
      foreach (Override::loadAll() as $override) {
        $overrides[] = $override->get();
      }
      $rd->setData($overrides);
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function delete(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_overrides'])) {
      $rd->addPermissionError('manage overrides');
    }
    else {
      $id = $request->get('id');
      $override = Override::load($id);
      if ($override !== FALSE) {
        if (!$override->delete()) {
          $rd->addError('Failed to delete override.');
        }
      }
      else {
        $rd->addError("Override does not exist.");
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAll(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_overrides'])) {
      $rd->addPermissionError('manage overrides');
    }
    else {
      if (!Override::deleteAll()) {
        $rd->addError("Failed to delete overrides.");
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function update(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_overrides'])) {
      $rd->addPermissionError('manage overrides');
    }
    else {
      $input = $this->getInput($request, ['id'], ['start', 'end', 'temp']);
      $override = Override::load($input->id);
      if ($override === NULL) {
        $rd->addError('Override not found.');
      }
      else {
        if (isset($input->start) && !$override->setStart($input->start)) {
          $rd->addError('Invalid start time.');
        }
        if (isset($input->end) && !$override->setEnd($input->end)) {
          $rd->addError('Invalid end time.');
        }
        if (isset($input->temp) && !$override->setTemp($input->temp)) {
          $rd->addError('Invalid temperature.');
        }

        if (!$rd->hasErrors() && !$override->save()) {
          $rd->addError('Failed to update override.');
        }
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function create(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_overrides'])) {
      $rd->addPermissionError('manage overrides');
    }
    else {
      $input = $this->getInput($request, ['start', 'end', 'temp']);
      if (!$input) {
        $rd->addError('Required fields were left empty.');
      }
      else {
        $override = new Override(-1, FALSE, FALSE, FALSE);

        if (!$override->setStart($input->start)) {
          $rd->addError('Invalid start time.');
        }
        if (!$override->setEnd($input->end)) {
          $rd->addError('Invalid end time.');
        }
        if (!$override->setTemp($input->temp)) {
          $rd->addError('Invalid temperature.');
        }

        if (!$rd->hasErrors()) {
          if ($override->save()) {
            $rd->setData($override->getId());
          }
          else {
            $rd->addError('Failed to insert override.');
          }
        }
      }
    }

    return $rd->toResponse();
  }

}
