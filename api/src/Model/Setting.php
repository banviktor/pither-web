<?php
/**
 * @file
 * Contains \PiTher\Model\Setting.
 */

namespace PiTher\Model;

/**
 * Class Setting
 * @package PiTher\Model
 */
class Setting extends Model {

  /**
   * @param array $row
   *
   * @return \PiTher\Model\Setting
   */
  public static function loadByRow(array $row) {
    return new Setting($row['id'], $row['value']);
  }

  /**
   * @param $id
   *
   * @return \PiTher\Model\Setting
   */
  public static function load($id) {
    $row = static::$db->fetchAssoc("SELECT * FROM settings WHERE id = ?", [$id]);
    if (empty($row)) {
      return NULL;
    }
    return static::loadByRow($row);
  }

  /**
   * @return \PiTher\Model\Setting[]
   */
  public static function loadAll() {
    $res = static::$db->fetchAll("SELECT * FROM settings");
    $settings = [];
    foreach ($res as $row) {
      $settings[] = static::loadByRow($row);
    }
    return $settings;
  }

  /**
   * @var string $id
   */
  protected $id;
  /**
   * @var mixed $value
   */
  protected $value;

  /**
   * Setting constructor.
   * @param string $id
   * @param mixed $value
   */
  protected function __construct($id, $value) {
    $this->id = $id;
    $this->value = $value;
  }

  /**
   * @return array
   */
  public function get() {
    return [$this->id => $this->value];
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @param mixed $value
   *
   * @return $this
   */
  public function setValue($value) {
    $this->value = $value;
    return $this;
  }

  /**
   * @return bool
   */
  public function save() {
    return $this->db()->update('settings', ['value' => $this->value], ['id' => $this->id]) > 0;
  }

}
