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
    return $app->json($user->edit($request->request->all()));
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function login(Request $request, Application $app) {
    $user = $request->get('user');
    $pass = $request->get('pass');

    $obj = User::loadByCredentials($user, $pass);
    if ($obj) {
      $_SESSION['uid'] = $obj->getId();
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
