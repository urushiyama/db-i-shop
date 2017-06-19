<?php
require_once '_C_SessionController.php';
require_once '_C_ApplicationException.php';
require_once '_C_Members.php';

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
    ApplicationException::setLocale('ja');
    if (array_key_exists($con->action, self::$action_table)) {
      $func = self::$action_table[$con->action];
      self::$func($con);
    } else {
      ApplicationException::create(ApplicationException::FATAL_ERROR);
    }
    if (ApplicationException::isStored()) {
      ApplicationException::raise();
    }
  }

  static function registerAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - password-confirmation : password
    #   - login_type : hidden
    if (!isset($_POST['user_name'])
        || !isset($_POST['password'])
        || !isset($_POST['password-confirmation'])
        || !isset($_POST['login_type'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if (!array_key_exists($_POST['login_type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if ($_POST['password'] !== $_POST['password-confirmation']) {
      ApplicationException::create(ApplicationException::FAILED_PASSWORD_CONFIRMATION);
    }
    if ($_POST['user_name'] == '') {
      ApplicationException::create(ApplicationException::EMPTY_USER_NAME);
    }
    $user_name = str_replace([' ', '　', "\n", "\t"], '', $_POST['user_name']);
    if ($user_name == '') {
      ApplicationException::create(ApplicationException::INVISIBLE_USER_NAME);
    }
    if ($_POST['password'] == '') {
      ApplicationException::create(ApplicationException::EMPTY_PASSWORD);
    }
    $model = SessionController::LOGIN_TYPE[$_POST['login_type']]['model'];
    include 'config.php';
    $model::setConnectionInfo([
      'host'=>$dbserver,
      'dbname'=>$dbname,
      'user'=>$user,
      'password'=>$password
    ]);
    $model::initDb();
    $user = $model::find_by(['name'=>$_POST['user_name']]);
    if ($user) {
      ApplicationException::create(ApplicationException::DUPLICATED_USER_NAME);
    }
    if (ApplicationException::isStored()) return false;
    /* Succeed for registration */
    if (strlen($_POST['password']) < 6) {
      ApplicationException::create(ApplicationException::SHORT_PASSWORD);
    }
    $new = Members::create(['name'=>$_POST['user_name'], 'password'=>$_POST['password']]);
    SessionController::login($new, $_POST['login_type']);
  }

  static function loginAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - login_type : hidden
    if (!isset($_POST['user_name'])
        || !isset($_POST['password'])
        || !isset($_POST['login_type'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if (!array_key_exists($_POST['login_type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_POST['login_type']]['model'];
    include 'config.php';
    $model::setConnectionInfo([
      'host'=>$dbserver,
      'dbname'=>$dbname,
      'user'=>$user,
      'password'=>$password
    ]);
    $model::initDb();
    $user = $model::find_by(['name'=>$_POST['user_name']]);
    if (!$user) {
      ApplicationException::create(ApplicationException::INVALID_LOGIN_COMBINATION);
      return false;
    }
    if (!$user->authenticate($_POST['password'])) {
      ApplicationException::create(ApplicationException::INVALID_LOGIN_COMBINATION);
      return false;
    }
    SessionController::login($user, $_POST['login_type']);
    return true;
  }

  static function updateAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - password : password
    #   - password-confirmation : password
  }

  static function logoutAccount(MainController $con) {
    # - no params
    SessionController::logout();
  }

  static function deleteAccount(MainController $con) {
    # - no params
    $user = SessionController::currentUser();
    if ($user == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return;
    }
    include 'config.php';
    $model = get_class($user);
    $model::setConnectionInfo([
      'host'=>$dbserver,
      'dbname'=>$dbname,
      'user'=>$user,
      'password'=>$password
    ]);
    $model::initDb();
    $user->destroy();
    SessionController::destroy();
  }
}
 ?>
