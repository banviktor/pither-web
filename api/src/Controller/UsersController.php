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
    $routes['get']['/logout'] = 'logout';
    $routes['post']['/login'] = 'login';
    return $routes;
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
  public function login(Request $request, Application $app) {
    $user = $request->get('user');
    $pass = $request->get('pass');

    $obj = User::loadByCredentials($user, $pass);
    if ($obj) {
      $_SESSION['uid'] = $obj->getId();
      return $app->json($obj->get());
    }
    return $app->json(FALSE);
  }

}
