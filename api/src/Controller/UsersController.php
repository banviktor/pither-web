<?php
/**
 * @file
 * Contains \PiTher\Controller\UsersController.
 */

namespace PiTher\Controller;

use PiTher\Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    $id = $request->get('id');
    if (User::currentUser()->getId() != $id) {
      $this->checkPermissions(['manage_users']);
    }
    $user = User::load($id);
    return $app->json($user->get());
  }

  /**
   * {@inheritdoc}
   */
  public function getAll(Request $request, Application $app) {
    $this->checkPermissions(['manage_users']);
    $users = [];
    foreach (User::loadAll() as $user) {
      $users[] = $user->get();
    }
    return $app->json($users);
  }

  /**
   * {@inheritdoc}
   */
  public function delete(Request $request, Application $app) {
    $this->checkPermissions(['manage_users']);
    $id = $request->get('id');
    $user = User::load($id);
    return $app->json($user->delete());
  }

  /**
   * {@inheritdoc}
   */
  public function update(Request $request, Application $app) {
    $input = $this->getInput($request, ['id'], ['email', 'name', 'pass', 'unit', 'roles']);
    $id = $input->id;
    $user = User::load($id);

    $manage_users = $this->checkPermissions(['manage_users'], TRUE);
    $self = $id != 0 && User::currentUser()->getId() == $user->getId();
    if (!$manage_users && !$self) {
      return $app->json(FALSE);
    }

    if ($email = $input->email) {
      $user->setEmail($email);
    }
    if ($name = $input->name) {
      $user->setName($name);
    }
    if ($pass = $input->pass) {
      $user->setPass($pass);
    }
    if ($unit = $input->unit) {
      $user->setUnit($unit);
    }
    if ($roles = $input->roles) {
      if (is_object($roles)) {
        $set_roles = [];
        foreach (get_object_vars($roles) as $var => $val) {
          $set_roles[$var] = $val == TRUE;
        }
      }
      else {
        $roles = explode(',', $roles);
        $set_roles = [];
        foreach ($roles as $role_id) {
          $set_roles[$role_id] = TRUE;
        }
      }
      $set_roles = array_filter($set_roles);
      $user->setRoles($set_roles);
    }

    return $app->json($user->save());
  }

  /**
   * {@inheritdoc}
   */
  public function create(Request $request, Application $app) {
    $this->checkPermissions(['create_users']);
    $input = $this->getInput($request, ['name', 'email', 'pass'], ['unit', 'roles']);
    if (!$input) {
      return $app->json(FALSE);
    }

    $user = new User(-1, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);
    if ($email = $input->email) {
      $user->setEmail($email);
    }
    if ($name = $input->name) {
      $user->setName($name);
    }
    if ($pass = $input->pass) {
      $user->setPass($pass);
    }
    if ($unit = $input->unit) {
      $user->setUnit($unit);
    }
    if ($roles = $input->roles) {
      if (is_object($roles)) {
        $set_roles = [];
        foreach (get_object_vars($roles) as $var => $val) {
          $set_roles[$var] = $val == TRUE;
        }
      }
      else {
        $roles = explode(',', $roles);
        $set_roles = [];
        foreach ($roles as $role_id) {
          $set_roles[$role_id] = TRUE;
        }
      }
      $set_roles = array_filter($set_roles);
      $user->setRoles($set_roles);
    }

    return $app->json($user->save() ? $user->getId() : FALSE);
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function login(Request $request, Application $app) {
    $input = $this->getInput($request, ['email', 'pass']);
    if (!$input) {
      return $app->json(FALSE);
    }

    $user = User::loadByCredentials($input->email, $input->pass);
    if ($user) {
      $_SESSION['uid'] = $user->getId();
      $user->setLastLogin()->save();
      return $app->json(TRUE);
    }
    return $app->json(FALSE);
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function logout(Request $request, Application $app) {
    session_unset();
    return $app->json(TRUE);
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function self(Request $request, Application $app) {
    return $app->json(User::currentUser()->get());
  }

}
