<?php
/**
 * @file
 * Contains \PiTher\Controller\Controller.
 */

namespace PiTher\Controller;

use PiTher\Model\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class Controller
 * @package PiTher\Controller
 */
abstract class Controller {

  /**
   * @param \Silex\Application $app
   */
  public static function init(&$app) {
    $class = get_called_class();
    foreach (static::routes() as $method => $routes) {
      foreach ($routes as $route => $handler) {
        $app->$method($route, $class . '::' . $handler);
      }
    }
  }

  /**
   * @return array
   *   A list of routes the controller is supposed to handle. Format:
   *   ['method' => ['/path' => 'function']]
   */
  protected static function routes() {
    $class = end(explode('\\', get_called_class()));
    $route = '/' . strtolower(str_replace('Controller', '', $class));
    return [
      'get' => [
        $route => 'getAll',
        $route . '/{id}' => 'get',
      ],
      'post' => [
        $route => 'create',
      ],
      'patch' => [
        $route . '/{id}' => 'modify',
      ],
      'delete' => [
        $route => 'deleteAll',
        $route . '/{id}' => 'delete',
      ],
    ];
  }

  /**
   * Checks whether the current user has the given permissions.
   *
   * @param array $permissions
   *   The list of permissions to look for.
   * @param bool $soft_fail
   *   If fails, return FALSE.
   *
   * @return bool
   *   Whether the user has the given permissions.
   */
  protected function checkPermissions(array $permissions, $soft_fail = FALSE) {
    if (User::currentUser()->hasPermissions($permissions)) {
      return TRUE;
    }
    if ($soft_fail) {
      return FALSE;
    }
    throw new AccessDeniedHttpException();
  }

  /**
   * Checks whether the proper input parameters have been passed in the request.
   *
   * @param string $method
   *   The request method.
   * @param array $keys
   *   The keys to look for.
   *
   * @return bool
   *   TRUE if all of the keys exist, FALSE otherwise.
   */
  protected function checkInput($method, array $keys) {
    switch (strtoupper($method)) {
      case 'GET':
        $in = $_GET;
        break;

      case 'POST':
        $in = $_POST;
        break;

      default:
        return FALSE;
    }
    foreach ($keys as $key) {
      if (!isset($in[$key])) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function get(Request $request, Application $app) {
    throw new AccessDeniedHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function getAll(Request $request, Application $app) {
    throw new AccessDeniedHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function create(Request $request, Application $app) {
    throw new AccessDeniedHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function modify(Request $request, Application $app) {
    throw new AccessDeniedHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function delete(Request $request, Application $app) {
    throw new AccessDeniedHttpException();
  }

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Silex\Application $app
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function deleteAll(Request $request, Application $app) {
    throw new AccessDeniedHttpException();
  }

}
