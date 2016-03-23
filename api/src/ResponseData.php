<?php
/**
 * @file
 * Contains \Pither\ResponseData.
 */

namespace PiTher;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ResponseData
 * @package PiTher
 */
class ResponseData {

  /** @var bool $success */
  protected $success = TRUE;
  /** @var mixed $data */
  protected $data = NULL;
  /** @var string[] $errors */
  protected $errors = [];
  /** @var int $statusCode */
  protected $statusCode = 200;

  /**
   * @param bool $success
   *
   * @return $this
   */
  public function setSuccess($success) {
    $this->success = $success == TRUE;
    return $this;
  }

  /**
   * @param mixed $data
   *
   * @return $this
   */
  public function setData($data) {
    $this->data = $data;
    return $this;
  }

  /**
   * @param string $error
   *
   * @return $this
   */
  public function addError($error) {
    $this->success = FALSE;
    $this->errors[] = $error;
    return $this;
  }

  /**
   * @return bool
   */
  public function hasErrors() {
    return !empty($this->errors);
  }

  /**
   * @param int $status_code
   *
   * @return $this
   */
  public function setStatusCode($status_code) {
    $this->statusCode = $status_code;
    return $this;
  }

  /**
   * @param string $action
   *
   * @return $this
   */
  public function addPermissionError($action = 'do that') {
    $this->statusCode = 403;
    $this->addError("You don't have the required permissions to $action.");
    return $this;
  }

  /**
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function toResponse() {
    $data = new \stdClass();
    $data->success = $this->success;
    $data->errors = $this->errors;
    $data->data = $this->data;
    return new JsonResponse($data, $this->statusCode);
  }

}
