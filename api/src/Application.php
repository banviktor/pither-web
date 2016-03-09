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
use Silex\Provider\DoctrineServiceProvider;

/**
 * Class Application
 * @package PiTher
 */
class Application extends \Silex\Application {

  /**
   * {@inheritdoc}
   */
  public function run(Request $request = NULL) {
    include __DIR__ . '/../config.php';

    $this->register(new DoctrineServiceProvider(), array(
      'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host' => $config['mysql']['host'],
        'dbname' => $config['mysql']['db'],
        'user' => $config['mysql']['user'],
        'password' => $config['mysql']['pass'],
        'charset'   => 'utf8mb4',
      ),
    ));

    HeatingController::init($this);
    OverridesController::init($this);
    RulesController::init($this);
    SensorController::init($this);
    SettingsController::init($this);
    UsersController::init($this);

    parent::run($request);
  }

}
