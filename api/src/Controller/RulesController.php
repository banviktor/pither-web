<?php
/**
 * @file
 * Contains \PiTher\Controller\RulesController.
 */

namespace PiTher\Controller;

use PiTher\Model\Rule;
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
    $this->checkPermissions(['access_rules']);
    $id = $request->get('id');
    $rule = Rule::load($id);
    return $app->json($rule);
  }

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    $this->checkPermissions(['access_rules']);
    $rules = [];
    foreach (Rule::loadAll() as $rule) {
      $rules[] = $rule->get();
    }
    return $app->json($rules);
  }

  /**
   * {@inheritdoc}
   */
  public function delete(Request $request, Application $app) {
    $this->checkPermissions(['access_rules', 'manage_rules']);
    $id = $request->get('id');
    $rule = Rule::load($id);
    if (!$rule) {
      return $app->json(FALSE);
    }
    return $app->json($rule->delete());
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAll(Request $request, Application $app) {
    $this->checkPermissions(['access_rules', 'manage_rules']);
    return $app->json(Rule::deleteAll());
  }

  /**
   * {@inheritdoc}
   */
  public function modify(Request $request, Application $app) {
    $this->checkPermissions(['access_rules', 'manage_rules']);
    $id = $request->get('id');
    $rule = Rule::load($id);

    if ($day = $request->get('day')) {
      $rule->setDay($day);
    }
    if ($start = $request->get('start')) {
      $rule->setStart($start);
    }
    if ($end = $request->get('end')) {
      $rule->setEnd($end);
    }
    if ($temp = $request->get('temp')) {
      $rule->setTemp($temp);
    }

    return $app->json($rule->save());
  }

  /**
   * {@inheritdoc}
   */
  public function create(Request $request, Application $app) {
    $this->checkPermissions(['access_rules', 'manage_rules']);
    $required = ['day', 'start', 'end', 'temp'];
    foreach ($required as $field) {
      if (!$request->request->has($field)) {
        return $app->json(FALSE);
      }
    }

    $rule = new Rule(-1, FALSE, FALSE, FALSE, FALSE);
    if ($day = $request->get('day')) {
      $rule->setDay($day);
    }
    if ($start = $request->get('start')) {
      $rule->setStart($start);
    }
    if ($end = $request->get('end')) {
      $rule->setEnd($end);
    }
    if ($temp = $request->get('temp')) {
      $rule->setTemp($temp);
    }

    return $app->json($rule->save());
  }

}
