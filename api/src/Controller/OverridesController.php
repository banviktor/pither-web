<?php
/**
 * @file
 * Contains \PiTher\Controller\OverridesController.
 */

namespace PiTher\Controller;

use PiTher\Model\Override;
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
    $this->checkPermissions(['access_overrides']);
    $id = $request->get('id');
    $override = Override::load($id);
    return $app->json($override);
  }

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    $this->checkPermissions(['access_overrides']);
    $overrides = [];
    foreach (Override::loadAll() as $override) {
      $overrides[] = $override->get();
    }
    return $app->json($overrides);
  }

  /**
   * {@inheritdoc}
   */
  public function delete(Request $request, Application $app) {
    $this->checkPermissions(['access_overrides', 'manage_overrides']);
    $id = $request->get('id');
    $override = Override::load($id);
    if (!$override) {
      return $app->json(FALSE);
    }
    return $app->json($override->delete());
  }

  /**
   * {@inheritdoc}
   */
  public function deleteAll(Request $request, Application $app) {
    $this->checkPermissions(['access_overrides', 'manage_overrides']);
    return $app->json(Override::deleteAll());
  }

  /**
   * {@inheritdoc}
   */
  public function modify(Request $request, Application $app) {
    $this->checkPermissions(['access_overrides', 'manage_overrides']);
    $id = $request->get('id');
    $override = Override::load($id);

    if ($start = $request->get('start')) {
      $override->setStart($start);
    }
    if ($end = $request->get('end')) {
      $override->setEnd($end);
    }
    if ($temp = $request->get('temp')) {
      $override->setTemp($temp);
    }

    return $app->json($override->save());
  }
  
}
