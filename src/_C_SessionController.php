<?php
require_once '_C_Members.php';

class SessionController {

  private static $current_user = null;
  private static $login_type = null;

  static function start() {
    session_start();
  }

  static function close() {
    session_write_close();
  }

  static function destroy() {
    // セッションの破棄。
    self::logout();
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
    }
    session_destroy();
  }

  static function login(array $params) {
    extract($params);
    if (!isset($name) || !isset($password) || !isset($login_type)) return false;
    if (!array_key_exists($login_type, LOGIN_TYPE)) return false;
    $model = LOGIN_TYPE[$login_type]['model'];
    $user = $model::find_by(['name'=>$name]);
    if (!$user) return false;
    if (!$user->authenticate($password)) return false;
    $_SESSION['user_id'] = $user->id;
    $_SESSION['login_type'] = $login_type;
    self::$current_user = $user;
    self::$login_type = $login_type;
    return $user;
  }

  static function isLoggedIn() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['login_type'])) return false;
    if (!array_key_exists($_SESSION['login_type'], LOGIN_TYPE)) return false;
    $model = LOGIN_TYPE[$login_type]['model'];
    $user = $model::find_by(['name'=>$name]);
    return (bool) $user;
  }

  static function current_user() {
    if (!isset($_SESSION['user_id'])
        || !isset($_SESSION['login_type'])
        || !array_key_exists($_SESSION['login_type'], LOGIN_TYPE)) {
      self::$current_user = null;
      self::$login_type = null;
      return null;
    }
    if (self::$current_user) return self::$current_user;
    $model = LOGIN_TYPE[$login_type]['model'];
    $res = $model::find_by(['id'=>$_SESSION['user_id']]);
    if (!$res) {
      self::logout();
      return null;
    }
    return $res;
  }

  static function logout() {
    self::$current_user = null;
    self::$login_type = null;
    unset($_SESSION['user_id']);
  }
}
 ?>
