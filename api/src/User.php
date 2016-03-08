<?php
/**
 * @file
 * Contains \PiTher\User.
 */

namespace PiTher;

/**
 * Class User
 * @package PiTher
 */
class User {
  /**
   * Returns the current user.
   *
   * @return \PiTher\User
   *   The current user.
   */
  public static function currentUser() {
    return new User();
  }

  /**
   * User constructor.
   */
  public function __construct() {
  }

  /**
   * Returns the list of permissions the user has.
   *
   * @return array
   *   The list of permissions the user has.
   */
  public function getPermissions() {
    return [];
  }

  /**
   * Returns the list of roles the user has.
   *
   * @return array
   *   The list of roles the user has.
   */
  public function getRoles() {
    return [];
  }

  /**
   * Returns whether the user has the given permissions.
   *
   * @param array $permissions
   *   The permissions to look for.
   *
   * @return bool
   *   Whether the user has the given permissions.
   */
  public function hasPermissions(array $permissions) {
    return count(array_intersect($this->getPermissions(), $permissions)) >= count($permissions);
  }
}
