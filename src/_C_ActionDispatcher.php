<?php
require_once '_C_SessionController.php';
require_once '_C_ApplicationException.php';

class ActionDispatcher {

  static $action_table = [
    'register-account'=>'registerAccount',
    'login-account'=>'loginAccount',
    'update-account'=>'updateAccount',
    'logout-account'=>'logoutAccount',
    'delete-account'=>'deleteAccount',
  ];

  static function act(MainController $con) {
    ApplicationException::reset();
    if (array_key_exists($con->action, self::$action_table)) {
      $func = self::$action_table[$con->action];
      self::$func($con);
    } else {
      ApplicationException::raise(ApplicationException::FATAL_ERROR);
    }
    if (ApplicationException::isRaised()) {
      ApplicationException::raise();
    }
  }

  static function registerAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - password-confirmation : password
  }

  static function loginAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
  }

  static function updateAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - password-confirmation : password
  }

  static function logoutAccount(MainController $con) {
    # - no params
    
  }

  static function deleteAccount(MainController $con) {
    # - no params
  }
}
 ?>
