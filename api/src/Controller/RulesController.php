<?php
/**
 * @file
 * Contains \PiTher\Controller\RulesController.
 */

namespace PiTher\Controller;

use PiTher\Model\Rule;
use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RulesController
 * @package PiTher\Controller
 */
class RulesController extends Controller {

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
      $rule = Rule::load($id);
      if ($rule !== NULL) {
        $rd->setData($rule);
      }
      else {
        $rd->addError("Rule does not exist.");
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
      $rules = [];
      foreach (Rule::loadAll() as $rule) {
        $rules[] = $rule->get();
      }
      $rd->setData($rules);
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function delete(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_rules'])) {
      $rd->addPermissionError('manage rules');
    }
    else {
      $id = $request->get('id');
      $rule = Rule::load($id);
      if ($rule !== FALSE) {
        if (!$rule->delete()) {
          $rd->addError('Failed to delete rule.');
        }
      }
      else {
        $rd->addError("Rule does not exist.");
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAll(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_rules'])) {
      $rd->addPermissionError('manage rules');
    }
    else {
      if (!Rule::deleteAll()) {
        $rd->addError("Failed to delete rules.");
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function update(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_rules', 'manage_rules'])) {
      $rd->addPermissionError('manage rules');
    }
    else {
      $input = $this->getInput($request, ['id'], ['day', 'start', 'end', 'temp']);
      $rule = Rule::load($input->id);
      if ($rule === NULL) {
        $rd->addError('Rule not found.');
      }
      else {
        if (isset($input->day) && !$rule->setDay($input->day)) {
          $rd->addError('Invalid day of week.');
        }
        if (isset($input->start) && !$rule->setStart($input->start)) {
          $rd->addError('Invalid start time.');
        }
        if (isset($input->end) && !$rule->setEnd($input->end)) {
          $rd->addError('Invalid end time.');
        }
        if (isset($input->temp) && !$rule->setTemp($input->temp)) {
          $rd->addError('Invalid temperature.');
        }

        if (!$rd->hasErrors() && !$rule->save()) {
          $rd->addError('Failed to update rule.');
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

    if (!$this->checkPermissions(['access_rules', 'manage_rules'])) {
      $rd->addPermissionError('manage rules');
    }
    else {
      $input = $this->getInput($request, ['day', 'start', 'end', 'temp']);
      if (!$input) {
        $rd->addError('Required fields were left empty.');
      }
      else {
        $rule = new Rule(-1, FALSE, FALSE, FALSE, FALSE);

        if (!$rule->setDay($input->day)) {
          $rd->addError('Invalid day of week.');
        }
        if (!$rule->setStart($input->start)) {
          $rd->addError('Invalid start time.');
        }
        if (!$rule->setEnd($input->end)) {
          $rd->addError('Invalid end time.');
        }
        if (!$rule->setTemp($input->temp)) {
          $rd->addError('Invalid temperature.');
        }

        if (!$rd->hasErrors()) {
          if ($rule->save()) {
            $rd->setData($rule->getId());
          }
          else {
            $rd->addError('Failed to update rule.');
          }
        }
      }
    }

    return $rd->toResponse();
  }

}
