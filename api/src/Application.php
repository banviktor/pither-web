<?php
/**
 * @file
 * Contains \PiTher\Application.
 */

namespace PiTher;

use PiTher\Controller\HeatingController;
use PiTher\Controller\OverridesController;
use PiTher\Controller\RulesController;
use PiTher\Controller\SensorController;
use PiTher\Controller\SettingsController;
use PiTher\Controller\UsersController;

/**
 * Class Application
 * @package PiTher
 */
class Application extends \Silex\Application {
  public function run(Request $request = NULL) {
    HeatingController::init($this);
    OverridesController::init($this);
    RulesController::init($this);
    SensorController::init($this);
    SettingsController::init($this);
    UsersController::init($this);
    parent::run($request);
  }
}
