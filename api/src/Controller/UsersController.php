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
    $this->checkPermissions(['manage_users']);
    $id = $request->get('id');
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
  public function modify(Request $request, Application $app) {
    $id = $request->get('id');
    $user = User::load($id);

    $manage_users = $this->checkPermissions(['manage_users'], TRUE);
    $self = $id != 0 && User::currentUser()->getId() == $user->getId();
    if (!$manage_users && !$self) {
      return $app->json(FALSE);
    }

    if ($email = $request->get('email')) {
      $user->setEmail($email);
    }
    if ($name = $request->get('name')) {
      $user->setName($name);
    }
    if ($pass = $request->get('pass')) {
      $user->setPass($pass);
    }
    if ($unit = $request->get('unit')) {
      $user->setUnit($unit);
    }
    if ($role_id = $request->get('grant_role')) {
      $user->grantRole($role_id);
    }
    if ($role_id = $request->get('revoke_role')) {
      $user->revokeRole($role_id);
    }
    if ($roles = $request->get('roles')) {
      $roles = explode(',', $roles);
      $set_roles = [];
      foreach ($roles as $role_id) {
        $set_roles[$role_id] = TRUE;
      }
      $user->setRoles($set_roles);
    }

    return $app->json($user->save());
  }

  /**
   * {@inheritdoc}
   */
  public function create(Request $request, Application $app) {
    $this->checkPermissions(['create_users']);
    $required = ['name', 'email', 'pass'];
    foreach ($required as $field) {
      if (!$request->request->has($field)) {
        return $app->json(FALSE);
      }
    }

    $user = new User(-1, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);
    if ($email = $request->get('email')) {
      $user->setEmail($email);
    }
    if ($name = $request->get('name')) {
      $user->setName($name);
    }
    if ($pass = $request->get('pass')) {
      $user->setPass($pass);
    }
    if ($unit = $request->get('unit')) {
      $user->setUnit($unit);
    }
    if ($roles = $request->get('roles')) {
      $roles = explode(',', $roles);
      $set_roles = [];
      foreach ($roles as $role_id) {
        $set_roles[$role_id] = TRUE;
      }
      $user->setRoles($set_roles);
    }

    return $app->json($user->save());
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function login(Request $request, Application $app) {
    $name_email = $request->get('user');
    $pass = $request->get('pass');

    $user = User::loadByCredentials($name_email, $pass);
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
