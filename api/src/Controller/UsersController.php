<?php
/**
 * @file
 * Contains \PiTher\Controller\UsersController.
 */

namespace PiTher\Controller;

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
    return [
      'get' => [
        '/logout' => 'logout',
        '/users/{name}' => 'get',
      ],
      'post' => [
        '/login' => 'login',
        '/users' => 'create',
      ],
      'patch' => [
        '/users/{name}' => 'modify',
      ],
      'delete' => [
        '/users/{name}' => 'delete',
      ],
    ];
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function logout(Request $request, Application $app) {
    throw new BadRequestHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function login(Request $request, Application $app) {
    throw new BadRequestHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function get(Request $request, Application $app) {
    throw new BadRequestHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function create(Request $request, Application $app) {
    throw new BadRequestHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function modify(Request $request, Application $app) {
    throw new BadRequestHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function delete(Request $request, Application $app) {
    throw new BadRequestHttpException();
  }
}
