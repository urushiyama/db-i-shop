<?php
require_once '_C_SessionController.php';
require_once '_C_ApplicationException.php';
require_once '_C_Members.php';
require_once '_C_Dealers.php';

class ActionDispatcher {

  static $action_table = [
    'register-account'=>'registerAccount',
    'login-account'=>'loginAccount',
    'update-account'=>'updateAccount',
    'logout-account'=>'logoutAccount',
    'delete-account'=>'deleteAccount',
  ];

  static function act(MainController $con) {
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
      $con->page = 'register';
      return false;
    }
    if (!array_key_exists($_POST['login_type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'register';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_POST['login_type']]['model'];
    $model::validateValues([
      'name'=>$_POST['user_name'],
      'password'=>$_POST['password'],
      'confirmation'=>$_POST['password-confirmation']
    ]);
    $user = $model::find_by(['name'=>$_POST['user_name']]);
    if ($user) {
      ApplicationException::create(ApplicationException::DUPLICATED_USER_NAME);
    }
    if (ApplicationException::isStored()) {
      $con->page = 'register';
      return false;
    }
    /* Succeed for registration */
    if (strlen($_POST['password']) < 6) {
      ApplicationException::create(ApplicationException::SHORT_PASSWORD);
    }
    $new = $model::create(['name'=>$_POST['user_name'], 'password'=>$_POST['password']]);
    SessionController::login($new, $_POST['login_type']);
    $con->page = 'top';
    return true;
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
      $con->page = 'login';
      return false;
    }
    if (!array_key_exists($_POST['login_type'], SessionController::LOGIN_TYPE)) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'login';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_POST['login_type']]['model'];
    $user = $model::find_by(['name'=>$_POST['user_name']]);
    if (!$user) {
      ApplicationException::create(ApplicationException::INVALID_LOGIN_COMBINATION);
      $con->page = 'login';
      return false;
    }
    if (!$user->authenticate($_POST['password'])) {
      ApplicationException::create(ApplicationException::INVALID_LOGIN_COMBINATION);
      $con->page = 'login';
      return false;
    }
    SessionController::login($user, $_POST['login_type']);
    $con->page = 'top';
    return true;
  }

  static function updateAccount(MainController $con) {
    # - params
    #   - user_name : text
    #   - old-password : password
    #   - password : password
    #   - password-confirmation : password
    if (!isset($_POST['user_name'])
        || !isset($_POST['old-password'])
        || !isset($_POST['password'])
        || !isset($_POST['password-confirmation'])) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      $con->page = 'register-update';
      return false;
    }
    $user = SessionController::currentUser();
    if ($user == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    if (!$user->authenticate($_POST['old-password'])) {
      ApplicationException::create(ApplicationException::INVALID_PASSWORD);
      $con->page = 'register-update';
      return false;
    }
    $model = SessionController::LOGIN_TYPE[$_SESSION['login_type']]['model'];
    $model::validateValues([
      'name'=>$_POST['user_name'],
      'password'=>$_POST['password'],
      'confirmation'=>$_POST['password-confirmation']
    ]);
    $registrated = $model::find_by(['name'=>$_POST['user_name']]);
    if ($registrated && $user->id !== $registrated->id) {
      ApplicationException::create(ApplicationException::DUPLICATED_USER_NAME);
    }
    if (ApplicationException::isStored()) {
      $con->page = 'register-update';
      return false;
    }
    $user->name = $_POST['user_name'];
    $user->setPassword($_POST['password']);
    $user->save();
    SessionController::login($user, $_SESSION['login_type']);
    $con->page = 'top';
    return true;
  }

  static function logoutAccount(MainController $con) {
    # - no params
    SessionController::logout();
    return true;
  }

  static function deleteAccount(MainController $con) {
    # - no params
    $user = SessionController::currentUser();
    if ($user == null) {
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
      return false;
    }
    $user->destroy();
    SessionController::destroy();
    return true;
  }
}
 ?>
