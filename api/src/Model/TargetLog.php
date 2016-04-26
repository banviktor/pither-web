<?php
/**
 * @file
 * Contains \PiTher\Model\TargetLog.
 */

namespace PiTher\Model;

/**
 * Class TargetLog
 * @package PiTher\Model
 */
class TargetLog extends Model {

  /**
   * @return float
   */
  public static function getCurrent() {
    $row = static::$db->fetchAssoc("SELECT temp FROM log_target ORDER BY date DESC LIMIT 1");
    if (empty($row)) {
      return 0;
    }
    return $row['temp'];
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

    $res = static::$db->fetchAll("SELECT date, temp FROM log_target WHERE date >= ? AND date <= ? ORDER BY date ASC", [$start, $end]);
    foreach ($res as $row) {
      $log[$row['date']] = $row['temp'];
    }

    return $log;
  }

}
