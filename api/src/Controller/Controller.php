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
   * @var \Silex\Application $app
   */
  protected static $app;

  /**
   * @param \Silex\Application $app
   */
  public static function init(&$app) {
    static::$app = $app;
    $class = get_called_class();
    foreach (static::routes() as $method => $routes) {
      foreach ($routes as $route => $handler) {
        $app->$method($route, $class . '::' . $handler);
      }
    }
  }

  /**
   * @return \Doctrine\DBAL\Connection
   */
  protected function db() {
    return static::$app['db'];
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
      'put' => [
        $route . '/{id}' => 'update',
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
   * Returns an object containing the required input fields as attributes.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param string[] $required
   *   The required input fields to look for.
   * @param string[] $optional
   *   The optional input fields to look for.
   *
   * @return \stdClass|FALSE
   *   The object containing the input fields or FALSE if not all required is set.
   */
  protected function getInput(Request $request, array $required, array $optional = []) {
    $input = new \stdClass();
    $fields = array_merge($required, $optional);

    if ($request->getMethod() != 'GET' && $request->getContentType() == 'json') {
      $json = json_decode($request->getContent());
      foreach ($fields as $key) {
        if (in_array($key, $required) && !isset($json->$key)) {
          return FALSE;
        }
        $input->$key = $json->$key;
      }
    }
    else {
      foreach ($fields as $key) {
        if (in_array($key, $required) && !$request->request->has($key) && !$request->attributes->has($key) && !$request->query->has($key)) {
          return FALSE;
        }
        $input->$key = $request->get($key, NULL);
      }
    }

    return $input;
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
    return '';
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
  public function update(Request $request, Application $app) {
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
