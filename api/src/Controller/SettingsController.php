<?php
/**
 * @file
 * Contains \PiTher\Controller\SettingsController.
 */

namespace PiTher\Controller;

use PiTher\Model\Setting;
use PiTher\ResponseData;
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
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_settings'])) {
      $rd->addPermissionError('access_settings');
    }
    else {
      $settings = [];
      foreach (Setting::loadAll() as $setting) {
        $settings[$setting->getId()] = $setting->getValue();
      }
      $rd->setData($settings);
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function get(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_settings'])) {
      $rd->addPermissionError('access_settings');
    }
    else {
      $id = $request->get('id');
      $setting = Setting::load($id);
      if ($setting !== NULL) {
        $rd->setData($setting->getValue());
      }
      else {
        $rd->addError("Setting does not exist.");
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function updateAll(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['access_settings', 'manage_settings'])) {
      $rd->addPermissionError('manage settings');
    }
    else {
      $input = $this->getInput($request, ['settings']);
      if ($input !== NULL) {
        foreach ($input->settings as $key => $value) {
          $setting = Setting::load($key);
          if ($setting !== NULL) {
            $setting->setValue($value)->save();
          }
          else {
            $rd->addError("Setting $key not found.");
          }
        }
      }
      else {
        $rd->addError("Invalid request.");
      }
    }

    return $rd->toResponse();
  }

}
