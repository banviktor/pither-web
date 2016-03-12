<?php
/**
 * @file
 * Contains \PiTher\Model\Rule.
 */

namespace PiTher\Model;

/**
 * Class Rule
 * @package PiTher\Model
 */
class Rule extends Model {

  /**
   * @param array $row
   *
   * @return \PiTher\Model\Rule
   */
  public static function loadByRow(array $row) {
    return new Rule($row['id'], $row['day'], $row['start'], $row['end'], $row['temp']);
  }

  /**
   * @param $id
   *
   * @return \PiTher\Model\Rule
   */
  public static function load($id) {
    $row = static::$db->fetchAssoc("SELECT * FROM rules WHERE id = ?", [$id]);
    if (empty($row)) {
      return NULL;
    }
    return static::loadByRow($row);
  }

  /**
   * @return \PiTher\Model\Rule[]
   */
  public static function loadAll() {
    $rules = [];
    $res = static::$db->fetchAll("SELECT * FROM rules");
    foreach ($res as $row) {
      $rules[] = static::loadByRow($row);
    }
    return $rules;
  }

  /** @var int $id */
  protected $id;
  /** @var int $day */
  protected $day;
  /** @var string $start */
  protected $start;
  /** @var string $end */
  protected $end;
  /** @var  float $temp */
  protected $temp;

  /**
   * Rule constructor.
   * @param $id
   * @param $day
   * @param $start
   * @param $end
   * @param $temp
   */
  public function __construct($id, $day, $start, $end, $temp) {
    $this->id = $id;
    $this->day = $day;
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
   * @return int
   */
  public function getDay() {
    return $this->day;
  }

  /**
   * @return string
   */
  public function getStart() {
    return $this->start;
  }

  /**
   * @return string
   */
  public function getEnd() {
    return $this->end;
  }

  /**
   * @return float
   */
  public function getTemp() {
    return $this->temp;
  }

  /**
   * @param int $day
   */
  public function setDay($day) {
    $this->day = $day;
  }

  /**
   * @param string $start
   */
  public function setStart($start) {
    $this->start = $start;
  }

  /**
   * @param string $end
   */
  public function setEnd($end) {
    $this->end = $end;
  }

  /**
   * @param float $temp
   */
  public function setTemp($temp) {
    $this->temp = $temp;
  }

  /**
   * @return bool
   */
  public function delete() {
    if ($this->id <= 0) {
      return FALSE;
    }
    return static::$db->delete('rules', ['id' => $this->id]) > 0;
  }

  /**
   * @return bool
   */
  public function save() {
    $data = [
      'day' => $this->day,
      'start' => $this->start,
      'end' => $this->end,
      'temp' => $this->temp,
    ];
    if ($this->id <= 0) {
      return static::$db->insert('rules', $data);
    }
    return static::$db->update('rules', $data, ['id' => $this->id]) > 0;
  }
}
