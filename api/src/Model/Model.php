<?php
/**
 * @file
 * Contains \PiTher\Model\Model.
 */

namespace PiTher\Model;

/**
 * Class Model
 * @package PiTher\Model
 */
abstract class Model {

  /**
   * @var \Silex\Application $app
   */
  protected static $app;

  /**
   * @var \Doctrine\DBAL\Connection $db
   */
  protected static $db;

  public static function init(&$app) {
    static::$app = $app;
    static::$db = &$app['db'];
  }

  /**
   * @return \Doctrine\DBAL\Connection
   */
  protected function db() {
    return static::$db;
  }

  /**
   * The array representation of the current object.
   *
   * @return array
   */
  public function get() {
    $vars = array_keys(get_class_vars(get_called_class()));
    $out = [];
    foreach ($vars as $var) {
      if ($var == 'app' || $var == 'db') {
        continue;
      }
      $out[$var] = $this->$var;
    }
    return $out;
  }

}
