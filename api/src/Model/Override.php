<?php
/**
 * @file
 * Contains \PiTher\Model\Override.
 */

namespace PiTher\Model;

/**
 * Class Override
 * @package PiTher\Model
 */
class Override extends Model {

  /**
   * @param array $row
   *
   * @return \PiTher\Model\Override
   */
  public static function loadByRow(array $row) {
    return new Override($row['id'], $row['start'], $row['end'], $row['temp']);
  }

  /**
   * @param $id
   *
   * @return \PiTher\Model\Override
   */
  public static function load($id) {
    $row = static::$db->fetchAssoc("SELECT * FROM overrides WHERE id = ?", [$id]);
    if (empty($row)) {
      return NULL;
    }
    return static::loadByRow($row);
  }

  /**
   * @return \PiTher\Model\Override[]
   */
  public static function loadAll() {
    $overrides = [];
    $res = static::$db->fetchAll("SELECT * FROM overrides");
    foreach ($res as $row) {
      $overrides[] = static::loadByRow($row);
    }
    return $overrides;
  }

  /**
   * @return bool
   */
  public static function deleteAll() {
    static::$db->exec("DELETE FROM overrides");
    return TRUE;
  }

  /** @var int $id */
  protected $id;
  /** @var string $start */
  protected $start;
  /** @var string $end */
  protected $end;
  /** @var float $temp */
  protected $temp;

  /**
   * Override constructor.
   * @param int $id
   * @param string $start
   * @param string $end
   * @param float $temp
   */
  public function __construct($id, $start, $end, $temp) {
    $this->id = $id;
    $this->start = $start;
    $this->end = $end;
    $this->temp = $temp;
  }

  /**
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getStart() {
    return strtotime($this->start);
  }

  /**
   * @return string
   */
  public function getEnd() {
    return strtotime($this->end);
  }

  /**
   * @return float
   */
  public function getTemp() {
    return $this->temp;
  }

  /**
   * @param string|int $start
   *
   * @return bool
   */
  public function setStart($start) {
    if (is_numeric($start)) {
      $start = date('Y-m-d H:i:s', $start);
    }
    $this->start = $start;
    return TRUE;
  }

  /**
   * @param string|int $end
   *
   * @return bool
   */
  public function setEnd($end) {
    if (is_numeric($end)) {
      $end = date('Y-m-d H:i:s', $end);
    }
    $this->end = $end;
    return TRUE;
  }

  /**
   * @param float $temp
   *
   * @return bool
   */
  public function setTemp($temp) {
    $this->temp = $temp;
    return TRUE;
  }

  /**
   * @return bool
   */
  public function delete() {
    if ($this->id <= 0) {
      return FALSE;
    }
    return static::$db->delete('overrides', ['id' => $this->id]) > 0;
  }

  /**
   * @return bool
   */
  public function save() {
    $data = [
      'start' => $this->start,
      'end' => $this->end,
      'temp' => $this->temp,
    ];
    if ($this->id <= 0) {
      $data = array_filter($data, function($val) {
        if ($val === FALSE) {
          return FALSE;
        }
        return TRUE;
      });
      return static::$db->insert('overrides', $data) > 0;
    }
    return static::$db->update('overrides', $data, ['id' => $this->id]) > 0;
  }

}
