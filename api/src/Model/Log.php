<?php
/**
 * @file
 * Contains \PiTher\Model\Log.
 */

namespace PiTher\Model;

/**
 * Class TemperatureLog
 * @package PiTher\Model
 */
class Log extends Model {

  /**
   * @param int $start
   * @param int $end
   *
   * @return array
   */
  public static function getValues($type, $start, $end, $interval) {
    $log = [];

    $start = date('Y-m-d H:i:s', $start);
    $end = date('Y-m-d H:i:s', $end);

    $table = $type == 'temp' ? 'log_temp' : 'log_target';
    $query = @"SELECT ceil(UNIX_TIMESTAMP(date) / ?) * ? AS date, avg(temp) AS temp
FROM $table
WHERE date >= ? AND date <= ?
GROUP BY ceil(UNIX_TIMESTAMP(date) / ?) * ?;";
    $res = static::$db->fetchAll($query, [$interval, $interval, $start, $end, $interval, $interval]);
    foreach ($res as $row) {
      $log[date('Y-m-d H:i:s', $row['date'] + 7200)] = round($row['temp'], 1);
    }

    return $log;
  }

  public static function cleanOld() {
    static::$db->exec("DELETE FROM log_temp WHERE date < DATE_SUB(NOW(), INTERVAL 29 DAY)");
    static::$db->exec("DELETE FROM log_target WHERE date < DATE_SUB(NOW(), INTERVAL 29 DAY)");
  }

}
