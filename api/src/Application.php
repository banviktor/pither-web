<?php
/**
 * @file
 * Contains \PiTher\Application.
 */

namespace PiTher;

use PiTher\Controller\UsersController;

/**
 * Class Application
 * @package PiTher
 */
class Application extends \Silex\Application {
  public function run(Request $request = NULL) {
    UsersController::init($this);
    parent::run($request);
  }
}
