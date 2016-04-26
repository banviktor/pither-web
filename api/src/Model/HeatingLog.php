<?php
/**
 * @file
 * Contains \PiTher\Model\HeatingLog.
 */

namespace PiTher\Model;

/**
 * Class HeatingLog
 * @package PiTher\Model
 */
class HeatingLog extends Model {

  /**
   * @return bool
   */
  public static function getCurrent() {
    $row = static::$db->fetchAssoc("SELECT state = 1 AS state FROM log_heating ORDER BY date DESC LIMIT 1");
    if (empty($row)) {
      return FALSE;
    }
    return $row['state'] == 1;
  }

  /**
   * @param int $start
   * @param int $end
   *
   * @return array
   */
  public static function getInterval($start, $end) {
    $log = [];

    $start = date('Y-m-d H:i:s', $start);
    $end = date('Y-m-d H:i:s', $end);

    $res = static::$db->fetchAll("SELECT date, state = 1 AS state FROM log_heating WHERE date >= ? AND date <= ? ORDER BY date ASC", [$start, $end]);
    foreach ($res as $row) {
      $log[$row['date']] = $row['state'];
    }

    return $log;
  }

}
