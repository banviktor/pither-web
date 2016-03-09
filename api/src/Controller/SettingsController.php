<?php
/**
 * @file
 * Contains \PiTher\Controller\SettingsController.
 */

namespace PiTher\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SettingsController
 * @package PiTher\Controller
 */
class SettingsController extends Controller {

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    $res = $this->db()->fetchAll("SELECT * FROM settings");
    $settings = [];
    foreach ($res as $row) {
      $settings[$row['key']] = $row['value'];
    }
    return $app->json($settings);
  }

  /**
   * {@inheritdoc}
   */
  public function get(Request $request, Application $app) {
    $id = $request->get('id');

    $setting = $this->db()->fetchAssoc("SELECT * FROM settings WHERE id = ?", [$id]);
    return $app->json($setting['value']);
  }

  /**
   * {@inheritdoc}
   */
  public function modify(Request $request, Application $app) {
    $id = $request->get('id');
    $value = $request->getContent();

    $res = $this->db()->update('settings', ['value' => $value], ['id' => $id]);
    return $app->json($res > 0);
  }

}
