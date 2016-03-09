<?php
/**
 * @file
 * Contains \PiTher\Model\User.
 */

namespace PiTher\Model;

/**
 * Class User
 * @package PiTher
 */
class User extends Model {

  /**
   * Returns the current user.
   *
   * @return \PiTher\Model\User
   *   The current user.
   */
  public static function currentUser() {
    if (!empty($_SESSION['uid'])) {
      return User::load($_SESSION['uid']);
    }
    return User::anonymous();
  }

  /**
   * Loads the anonymous user.
   *
   * @return \PiTher\Model\User
   */
  public static function anonymous() {
    return new User(0, 'Anonymous', '', 'c', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
  }

  /**
   * Loads a User by its ID.
   *
   * @param $id
   *
   * @return null|\PiTher\Model\User
   */
  public static function load($id) {
    $row = static::$db->fetchAssoc("SELECT `id`, `name`, `email`, `unit`, `last_login`, `created` FROM `users` WHERE `id` = ?", [$id]);
    if (empty($row)) {
      return NULL;
    }
    return new User($row['id'], $row['name'], $row['email'], $row['unit'], $row['last_login'], $row['created']);
  }

  /**
   * Loads a User by its credentials.
   *
   * @param string $user
   *   Either a name or an e-mail address.
   * @param string $pass
   *   Password of the user.
   *
   * @return null|\PiTher\Model\User
   */
  public static function loadByCredentials($user, $pass) {
    if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
      $auth_field = "email";
    }
    else {
      $auth_field = "name";
    }
    $sql = "SELECT `id`, `name`, `email`, `unit`, `last_login`, `created` FROM `users` WHERE `" . $auth_field . "` = ? AND `pass` = SHA1(?)";
    $row = static::$db->fetchAssoc($sql, [$user, $pass]);
    if (empty($row)) {
      return NULL;
    }
    return new User($row['id'], $row['name'], $row['email'], $row['unit'], $row['last_login'], $row['created']);
  }

  /**
   * @var string $id
   */
  protected $id;

  /**
   * @var string $name
   */
  protected $name;

  /**
   * @var string $email
   */
  protected $email;

  /**
   * @var string $unit
   */
  protected $unit;

  /**
   * @var string $last_login
   */
  protected $last_login;

  /**
   * @var string $created
   */
  protected $created;

  /**
   * @var array $roles
   */
  protected $roles = [];

  /**
   * @var array $perms
   */
  protected $perms = [];

  /**
   * User constructor.
   */
  public function __construct($id, $name, $email, $unit, $last_login, $created) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->unit = $unit;
    $this->last_login = $last_login;
    $this->created = $created;

    // Fetch roles.
    $roles = static::$db->fetchAll("SELECT `r`.`id`, `r`.`title` FROM `roles` `r`, `users_roles` `ur` WHERE `ur`.`role_id` = `r`.`id` AND `ur`.`user_id` = ?", [$id]);
    foreach ($roles as $role) {
      $this->roles[$role['id']] = $role['title'];
    }

    // Fetch permissions.
    $perms = static::$db->fetchAll("SELECT `p`.`id`, `p`.`title` FROM `users_roles` `ur`, `roles_permissions` `rp`, `permissions` `p` WHERE `ur`.`role_id` = `rp`.`role_id` AND `rp`.`perm_id` = `p`.`id` AND `ur`.`user_id` = ?", [$id]);
    foreach ($perms as $perm) {
      $this->perms[$perm['id']] = $perm['title'];
    }
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @return string
   */
  public function getUnit() {
    return $this->unit;
  }

  /**
   * @return string
   */
  public function getLastLogin() {
    return $this->last_login;
  }

  /**
   * @return string
   */
  public function getCreated() {
    return $this->created;
  }

  /**
   * @return array
   */
  public function getRoles() {
    return $this->roles;
  }

  /**
   * @return array
   */
  public function getPerms() {
    return $this->perms;
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
    return count(array_intersect(array_keys($this->getPerms()), $permissions)) >= count($permissions);
  }

}
