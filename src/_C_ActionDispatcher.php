<?php
class ActionDispatcher {

  static $action_table = [
    'register-account'=>'registerAccount',
    'login-account'=>'loginAccount',
    'logout-account'=>'logoutAccount',
    'delete-account'=>'deleteAccount',
  ];

  static function act(MainController $con) {
    if (array_key_exists($con->action, self::$action_table)) {
      $func = self::$action_table[$con->action];
      return self::$func($con);
    }
    return false;
  }

  static function registerAccount(MainController $con) {
    # code...
    return false;
  }

  static function loginAccount(MainController $con) {
    # code...
    return false;
  }

  static function logoutAccount(MainController $con) {
    return false;
  }

  static function deleteAccount(MainController $con) {
    return false;
  }
}
 ?>
