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
   * @param $id
   * @return \PiTher\Model\Setting
   */
  public static function load($id) {
    $row = static::$db->fetchAssoc("SELECT * FROM settings WHERE id = ?", [$id]);
    if (empty($row)) {
      return NULL;
    }
    return new Setting($row['id'], $row['value']);
  }

  /**
   * @return \PiTher\Model\Setting[]
   */
  public static function loadAll() {
    $res = static::$db->fetchAll("SELECT * FROM settings");
    $settings = [];
    foreach ($res as $row) {
      $settings[] = new Setting($row['id'], $row['value']);
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
   * @return bool
   */
  public function setValue($value) {
    if ($this->value == $value) {
      return TRUE;
    }
    $this->value = $value;
    $affected = $this->db()->update('settings', ['value' => $value], ['id' => $this->id]);
    return $affected > 0;
  }

}
