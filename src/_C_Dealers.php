<?php
require_once  '_C_AuthenticatedUsers.php';

class Dealers extends AuthenticatedUsers {

  protected static $table = 'dealers';

  static function validateValues(array $params) {
    extract($params);
    if (isset($name)) {
      if ($name == '') {
        ApplicationException::create(ApplicationException::EMPTY_USER_NAME);
      }
      $visible_name = str_replace([' ', '　', "\n", "\t"], '', $name);
      if ($visible_name == '') {
        ApplicationException::create(ApplicationException::INVISIBLE_USER_NAME);
      }
    }
    if (isset($password)) {
      if ($password == '') {
        ApplicationException::create(ApplicationException::EMPTY_PASSWORD);
      }
      if (isset($confirmation)) {
        if ($password !== $confirmation) {
          ApplicationException::create(ApplicationException::FAILED_PASSWORD_CONFIRMATION);
        }
      }
    }
  }
}
 ?>
