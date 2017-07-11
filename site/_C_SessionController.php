<?php
require_once '_C_Members.php';

class SessionController {

  const LOGIN_TYPE_MEMBER = 1;
  const LOGIN_TYPE_MEMBER_NAME = '会員';
  const LOGIN_TYPE_MEMBER_MODEL = 'Members';
  const LOGIN_TYPE_DEALER = 2;
  const LOGIN_TYPE_DEALER_NAME = '販売業者';
  const LOGIN_TYPE_DEALER_MODEL = 'Dealers';

  const LOGIN_TYPE = [
    self::LOGIN_TYPE_MEMBER=>[name=> self::LOGIN_TYPE_MEMBER_NAME, model=> self::LOGIN_TYPE_MEMBER_MODEL],
    self::LOGIN_TYPE_MEMBER_NAME=>self::LOGIN_TYPE_MEMBER,
    self::LOGIN_TYPE_DEALER=>[name=> self::LOGIN_TYPE_DEALER_NAME, model=> self::LOGIN_TYPE_DEALER_MODEL],
    self::LOGIN_TYPE_DEALER_NAME=>self::LOGIN_TYPE_DEALER
  ];

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

  static function login(AuthenticatedUsers $user, $login_type) {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['login_type'] = $login_type;
    self::$current_user = $user;
    self::$login_type = $login_type;
    self::resetCart();
    return true;
  }

  static function isLoggedIn() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['login_type'])) return false;
    if (!array_key_exists($_SESSION['login_type'], self::LOGIN_TYPE)) return false;
    $model = self::LOGIN_TYPE[$_SESSION['login_type']]['model'];
    $user = $model::find_by(['id'=>$_SESSION['user_id']]);
    return (bool) $user;
  }

  static function currentUser() {
    if (!isset($_SESSION['user_id'])
        || !isset($_SESSION['login_type'])
        || !array_key_exists($_SESSION['login_type'], self::LOGIN_TYPE)) {
      self::$current_user = null;
      self::$login_type = null;
      return null;
    }
    if (self::$current_user) return self::$current_user;
    $model = self::LOGIN_TYPE[$_SESSION['login_type']]['model'];
    $res = $model::find_by(['id'=>$_SESSION['user_id']]);
    if (!$res) {
      self::logout();
      return null;
    }
    return $res;
  }

  static function currentLoginType() {
    return self::isLoggedIn() ? $_SESSION['login_type'] : false;
  }

  static function logout() {
    self::$current_user = null;
    self::$login_type = null;
    unset($_SESSION['user_id']);
    unset($_SESSION['login_type']);
  }

  static function resetCart() {
    $_SESSION['cart'] = [];
    $_SESSION['cart-units'] = [];
  }

  static function addToCart($product_id, $units) {
    if (self::isInCart($product_id)) {
      // 注文個数の変更
      $product = Products::find_by(['id'=>$product_id]);
      $cart_units = $_SESSION['cart-units'];
      $old_units = $cart_units[array_search($product_id, $_SESSION['cart'])];
      if ($product->stock + $old_units < $units) {
        $units = $product->stock + $old_units;
      }
      $cart_units[array_search($product_id, $_SESSION['cart'])] = $units;
      $_SESSION['cart-units'] = $cart_units;
    } else {
      $product = Products::find_by(['id'=>$product_id]);
      if ($product->stock < $units) {
        // 注文個数の修正
        $units = $product->stock;
      }
      $cart = $_SESSION['cart'];
      array_push($cart, $product_id);
      $_SESSION['cart'] = $cart;
      $cart_units = $_SESSION['cart-units'];
      array_push($cart_units, $units);
      $_SESSION['cart-units'] = $cart_units;
    }
    date_default_timezone_set('UTC');
    $_SESSION['cart-expired-at'] = date('Y-m-d H:i:s', strtotime('+30 minute'));
  }

  static function removeFromCart($product_id) {
    if (self::isInCart($product_id)) {
      $key = array_search($product_id, $_SESSION['cart']);
      $units =$_SESSION['cart-units'][$key];
      unset($_SESSION['cart'][$key]);
      unset($_SESSION['cart-units'][$key]);
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      $_SESSION['cart-units'] = array_values($_SESSION['cart-units']);
    }
  }

  static function loadCart() {
    if (strtotime($_SESSION['cart-expired-at']) <= time()) {
      self::resetCart();
    }
    date_default_timezone_set('UTC');
    $_SESSION['cart-expired-at'] = date('Y-m-d H:i:s', strtotime('+30 minute'));
    return array_map(function ($product_id, $units) {
      return [
        'product'=>Products::find_by(['id'=>$product_id]),
        'units'=>$units
      ];
    }, $_SESSION['cart'], $_SESSION['cart-units']);
  }
  
  static function isInCart($product_id) {
    if (strtotime($_SESSION['cart-expired-at']) <= time()) {
      self::resetCart();
    }
    date_default_timezone_set('UTC');
    $_SESSION['cart-expired-at'] = date('Y-m-d H:i:s', strtotime('+30 minute'));
    return in_array($product_id, $_SESSION['cart']);
  }
}
 ?>
