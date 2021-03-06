<?php
/**
 * @file
 * Contains \PiTher\Model\Rule.
 */

namespace PiTher\Model;
use Doctrine\DBAL\DBALException;

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

  /**
   * @return bool
   */
  public static function deleteAll() {
    static::$db->exec("DELETE FROM rules");
    return TRUE;
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
   * @param int $id
   * @param int $day
   * @param string $start
   * @param string $end
   * @param float $temp
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
   *
   * @return $this
   */
  public function setDay($day) {
    if ($day >= 1 && $day <= 7) {
      $this->day = $day;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param string $start
   *
   * @return $this
   */
  public function setStart($start) {
    $this->start = $start;
    return TRUE;
  }

  /**
   * @param string $end
   *
   * @return $this
   */
  public function setEnd($end) {
    $this->end = $end;
    return TRUE;
  }

  /**
   * @param float $temp
   *
   * @return $this
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
      $data = array_filter($data, function($val) {
        if ($val === FALSE) {
          return FALSE;
        }
        return TRUE;
      });
      try {
        static::$db->insert('rules', $data);
        $this->id = static::$db->lastInsertId();
        return TRUE;
      }
      catch (DBALException $e) {
        return FALSE;
      }
    }
    return static::$db->update('rules', $data, ['id' => $this->id]) > 0;
  }

}
