<?php
/**
 * @file
 * Contains \PiTher\Controller\UsersController.
 */

namespace PiTher\Controller;

use PiTher\Model\User;
use PiTher\ResponseData;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UsersController
 * @package PiTher\Controller
 */
class UsersController extends Controller {

  /**
   * {@inheritdoc}
   */
  public static function routes() {
    $routes = parent::routes();
    $routes['post']['/login'] = 'login';
    $routes['get']['/logout'] = 'logout';
    $routes['get']['/self'] = 'self';
    return $routes;
  }

  /**
   * {@inheritdoc}
   */
  public function get(Request $request, Application $app) {
    $rd = new ResponseData();

    $id = $request->get('id');
    if (User::currentUser()->getId() != $id && !$this->checkPermissions(['manage_users'])) {
      $rd->addPermissionError('manage users');
    }
    else {
      $user = User::load($id);
      if ($user !== NULL) {
        $rd->setData($user->get());
      }
      else {
        $rd->addError('User not found.');
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['manage_users'])) {
      $rd->addPermissionError('manage users');
    }
    else {
      $users = [];
      foreach (User::loadAll() as $user) {
        $users[] = $user->get();
      }
      $rd->setData($users);
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function delete(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['manage_users'])) {
      $rd->addPermissionError('manage users');
    }
    else {
      $id = $request->get('id');
      $user = User::load($id);
      if ($user !== NULL) {
        if (!$user->delete()) {
          $rd->addError('Failed to delete user.');
        }
      }
      else {
        $rd->addError('User not found.');
      }
    }

    return $rd->toResponse();
  }

  public function deleteAll(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['manage_users'])) {
      $rd->addPermissionError('manage users');
    }
    else {
      $input = $this->getInput($request, ['ids']);
      if (!$input) {
        $rd->addError('Invalid request.');
      }
      else {
        $users = User::loadAll();
        $owner_remains = FALSE;
        foreach ($users as $user) {
          if ($user->hasRole('owner') && !in_array($user->getId(), $input->ids)) {
            $owner_remains = TRUE;
            break;
          }
        }
        if (!$owner_remains) {
          $rd->addError('You have to leave at least one user with the Owner role intact.');
        }
        else {
          if(!User::deleteAll($input->ids)) {
            $rd->addError('No users were deleted.');
          }
        }
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function update(Request $request, Application $app) {
    $rd = new ResponseData();

    $input = $this->getInput($request, ['id'], ['email', 'name', 'pass', 'unit', 'roles']);
    $manage_users = $this->checkPermissions(['manage_users']);
    $self = User::currentUser()->getId() == $input->id;
    if (!$manage_users && !$self) {
      $rd->addPermissionError('manage users');
    }
    else {
      $user = User::load($input->id);
      if ($user === NULL) {
        $rd->addError('User not found.');
      }
      else {
        if (isset($input->email) && !$user->setEmail($input->email)) {
          $rd->addError('Invalid e-mail address.');
        }
        if (isset($input->name) && !$user->setName($input->name)) {
          $rd->addError('Invalid name.');
        }
        if (isset($input->pass) && !$user->setPass($input->pass)) {
          $rd->addError('Invalid password');
        }
        if (isset($input->unit) && !$user->setUnit($input->unit)) {
          $rd->addError('Invalid temperature unit.');
        }
        if ($manage_users && isset($input->roles)) {
          if (is_object($input->roles)) {
            $set_roles = [];
            foreach (get_object_vars($input->roles) as $var => $val) {
              $set_roles[$var] = $val == TRUE;
            }
          }
          else {
            $roles = explode(',', $input->roles);
            $set_roles = [];
            foreach ($roles as $role_id) {
              $set_roles[$role_id] = TRUE;
            }
          }
          if (!$user->setRoles(array_filter($set_roles))) {
            $rd->addError('Invalid roles.');
          }
        }
        if (!$rd->hasErrors() && !$user->save()) {
          $rd->addError('Failed to update user.');
        }
      }
    }

    return $rd->toResponse();
  }

  /**
   * {@inheritdoc}
   */
  public function create(Request $request, Application $app) {
    $rd = new ResponseData();

    if (!$this->checkPermissions(['create_users'])) {
      $rd->addPermissionError('create users');
    }
    else {
      $input = $this->getInput($request, ['name', 'email', 'pass'], ['unit', 'roles']);
      if (!$input) {
        $rd->addError('Required fields were left empty.');
      }
      else {
        $user = new User(-1, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);
        if (!$user->setEmail($input->email)) {
          $rd->addError('Invalid e-mail address.');
        }
        if (!$user->setName($input->name)) {
          $rd->addError('Invalid name.');
        }
        if (!$user->setPass($input->pass)) {
          $rd->addError('Invalid password');
        }
        if (isset($input->unit) && !$user->setUnit($input->unit)) {
          $rd->addError('Invalid temperature unit.');
        }
        if (isset($input->roles)) {
          if (is_object($input->roles)) {
            $set_roles = [];
            foreach (get_object_vars($input->roles) as $var => $val) {
              $set_roles[$var] = $val == TRUE;
            }
          }
          else {
            $roles = explode(',', $input->roles);
            $set_roles = [];
            foreach ($roles as $role_id) {
              $set_roles[$role_id] = TRUE;
            }
          }
          if (!$user->setRoles(array_filter($set_roles))) {
            $rd->addError('Invalid roles.');
          }
        }
        if (!$rd->hasErrors()) {
          if ($user->save()) {
            $rd->setData($user->getId());
          }
          else {
            $rd->addError('Failed to create user.');
          }
        }
      }
    }

    return $rd->toResponse();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function login(Request $request, Application $app) {
    $rd = new ResponseData();

    $input = $this->getInput($request, ['email', 'pass']);
    if (!$input) {
      $rd->addError('Invalid request.');
    }
    else {
      $user = User::loadByCredentials($input->email, $input->pass);
      if (!$user) {
        $rd->addError('Wrong e-mail or password.');
      }
      else {
        $_SESSION['uid'] = $user->getId();
        $user->setLastLogin();
        $user->save();
        $rd->setData($user->get());
      }
    }

    return $rd->toResponse();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function logout(Request $request, Application $app) {
    $rd = new ResponseData();
    session_unset();

    return $rd->toResponse();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function self(Request $request, Application $app) {
    $rd = new ResponseData();
    $rd->setData(User::currentUser()->get());

    return $rd->toResponse();
  }

}
