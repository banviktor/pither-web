<?php
/**
 * @file
 * Contains \PiTher\Controller\SettingsController.
 */

namespace PiTher\Controller;

use PiTher\Model\Setting;
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
    $this->checkPermissions(['access_settings']);
    $settings = [];
    foreach (Setting::loadAll() as $setting) {
      $settings[] = $setting->get();
    }
    return $app->json($settings);
  }

  /**
   * {@inheritdoc}
   */
  public function get(Request $request, Application $app) {
    $this->checkPermissions(['access_settings']);
    $id = $request->get('id');

    $setting = Setting::load($id);
    if (empty($setting)) {
      return $app->json(FALSE);
    }

    return $app->json($setting->getValue());
  }

  /**
   * {@inheritdoc}
   */
  public function update(Request $request, Application $app) {
    $this->checkPermissions(['access_settings', 'manage_settings']);
    $id = $request->get('id');
    $value = $request->getContent();

    $setting = Setting::load($id);
    if (empty($setting)) {
      return $app->json(FALSE);
    }

    return $app->json($setting->setValue($value)->save());
  }

}
