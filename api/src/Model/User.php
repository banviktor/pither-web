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
   * @param array $row
   *
   * @return \PiTher\Model\User
   */
  public static function loadByRow(array $row) {
    return new User($row['id'], $row['name'], $row['email'], $row['pass'], $row['unit'], $row['last_login'], $row['created']);
  }

  /**
   * @param int $id
   *
   * @return null|\PiTher\Model\User
   */
  public static function load($id) {
    $row = static::$db->fetchAssoc("SELECT * FROM `users` WHERE `id` = ?", [$id]);
    if (empty($row)) {
      return NULL;
    }
    return static::loadByRow($row);
  }

  /**
   * @return \PiTher\Model\User[]
   */
  public static function loadAll() {
    $users = [];
    $res = static::$db->fetchAll("SELECT * FROM `users`");
    foreach ($res as $row) {
      $users[] = static::loadByRow($row);
    }
    return $users;
  }

  /**
   * @param string $user
   * @param string $pass
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
    $sql = "SELECT * FROM `users` WHERE `" . $auth_field . "` = ? AND `pass` = SHA1(?)";
    $row = static::$db->fetchAssoc($sql, [$user, $pass]);
    if (empty($row)) {
      return NULL;
    }
    return static::loadByRow($row);
  }

  /**
   * @return null|\PiTher\Model\User
   */
  public static function currentUser() {
    if (!empty($_SESSION['uid'])) {
      return User::load($_SESSION['uid']);
    }
    return User::anonymous();
  }

  /**
   * @return \PiTher\Model\User
   */
  public static function anonymous() {
    return new User(0, 'Anonymous', '', '', 'c', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
  }

  /** @var int $id */
  protected $id;
  /** @var string $name */
  protected $name;
  /** @var string $email */
  protected $email;
  /** @var string $pass */
  protected $pass;
  /** @var string $unit */
  protected $unit;
  /** @var string $last_login */
  protected $last_login;
  /** @var string $created */
  protected $created;
  /** @var array $roles */
  protected $roles = [];
  /** @var array $perms */
  protected $perms = [];

  /**
   * User constructor.
   * @param int $id
   * @param string $name
   * @param string $email
   * @param string $pass
   * @param string $unit
   * @param string $last_login
   * @param string $created
   */
  public function __construct($id, $name, $email, $pass, $unit, $last_login, $created) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->pass = $pass;
    $this->unit = $unit;
    $this->last_login = $last_login;
    $this->created = $created;

    if ($id >= 0) {
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
    else {
      $this->roles = [];
      $this->perms = [];
    }
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
  public function getPass() {
    return $this->pass;
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
   * @param string $name
   *
   * @return $this
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @param string $email
   *
   * @return $this
   */
  public function setEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->email = $email;
    }
    return $this;
  }

  /**
   * @param string $pass
   *
   * @return $this
   */
  public function setPass($pass) {
    $this->pass = sha1($pass);
    return $this;
  }

  /**
   * @param string $unit
   *
   * @return $this
   */
  public function setUnit($unit) {
    if (in_array($unit, ['c', 'f', 'k'])) {
      $this->unit = $unit;
    }

    return $this;
  }

  /**
   * @param int $last_login
   *
   * @return $this
   */
  public function setLastLogin($last_login = NULL) {
    if (!$last_login) {
      $last_login = time();
    }
    if (is_numeric($last_login)) {
      $last_login = date('Y-m-d H:i:s', $last_login);
    }
    $this->last_login = $last_login;
    return $this;
  }

  /**
   * @param array $roles
   *
   * @return $this
   */
  public function setRoles($roles) {
    $this->roles = $roles;
    return $this;
  }

  /**
   * @param string $role_id
   *
   * @return $this
   */
  public function revokeRole($role_id) {
    unset($this->roles[$role_id]);
    return $this;
  }

  /**
   * @param string $role_id
   *
   * @return $this
   */
  public function grantRole($role_id) {
    $this->roles[$role_id] = TRUE;
    return $this;
  }

  public function hasPermissions(array $permissions) {
    return count(array_intersect(array_keys($this->getPerms()), $permissions)) >= count($permissions);
  }

  /**
   * @return bool
   * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
   */
  public function delete() {
    if ($this->id <= 0) {
      return FALSE;
    }
    return static::$db->delete('users', ['id' => $this->id]) > 0;
  }

  /**
   * @return bool
   */
  public function save() {
    $data = [
      'name' => $this->name,
      'email' => $this->email,
      'pass' => $this->pass,
      'unit' => $this->unit,
      'last_login' => $this->last_login,
    ];
    if ($this->id < 0) {
      static::$db->insert('users', $data);
    }
    else {
      static::$db->update('users', $data, ['id' => $this->id]);
    }

    static::$db->delete('users_roles', ['user_id' => $this->id]);
    foreach (array_keys($this->roles) as $role_id) {
      static::$db->insert('users_roles', [
        'user_id' => $this->id,
        'role_id' => $role_id,
      ]);
    }
    return TRUE;
  }

}
