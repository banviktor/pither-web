<?php
/**
 * @file
 * Contains \PiTher\Application.
 */

namespace PiTher;

use PiTher\Controller\HeatingLogController;
use PiTher\Controller\OverridesController;
use PiTher\Controller\RulesController;
use PiTher\Controller\SettingsController;
use PiTher\Controller\TargetLogController;
use PiTher\Controller\TemperatureLogController;
use PiTher\Controller\UsersController;
use PiTher\Model\Model;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;

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

    Model::init($this);
    HeatingLogController::init($this);
    OverridesController::init($this);
    RulesController::init($this);
    SettingsController::init($this);
    TargetLogController::init($this);
    TemperatureLogController::init($this);
    UsersController::init($this);

    $this->error(function($exception) {
      return $this->json(FALSE);
    });

    parent::run($request);
  }

}
