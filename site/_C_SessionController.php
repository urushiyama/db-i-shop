<?php
require_once '_C_Members.php';

class SessionController {

  private static $current_user = null;

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
    if (!isset($name) || !isset($password)) return false;
    $user = Members::find_by(['name'=>$name]);
    if (!$user) return false;
    if (!$user->authenticate($password)) return false;
    $_SESSION['user_id'] = $user->id;
    self::$current_user = $user;
    return $user;
  }

  static function isLoggedIn() {
    if (!isset($_SESSION['user_id'])) return false;
    return (bool) Members::find_by(['id'=>$_SESSION['user_id']]);
  }

  static function current_user() {
    if (!isset($_SESSION['user_id'])) {
      self::$current_user = null;
      return null;
    }
    if (self::$current_user) return self::$current_user;
    $res = Members::find_by(['id'=>$_SESSION['user_id']]);
    if (!$res) {
      self::logout();
      return null;
    }
    return $res;
  }

  static function logout() {
    self::$current_user = null;
    unset($_SESSION['user_id']);
  }
}
 ?>
