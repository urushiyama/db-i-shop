<?php
class ApplicationException extends RuntimeException implements IteratorAggregate {

  const OK = [
    'id'=>0,
    'message'=>[
      'en'=>'Success!',
      'ja'=>'処理が成功しました。'
    ]
  ];

  const SHORT_PASSWORD = [
    'id'=>1021,
    'message'=>[
      'en'=>'Your password is weak. You sholud change password more longer.',
      'ja'=>'パスワードの長さが短いため、不正ログインの恐れがあります。パスワードをより長いものに変更してください。'
    ]
  ];

  const INVALID_USER_NAME = [
    'id'=>2011,
    'message'=>[
      'en'=>'User name is invalid.',
      'ja'=>'不正なユーザー名です。'
    ]
  ];

  const EMPTY_USER_NAME = [
    'id'=>2012,
    'message'=>[
      'en'=>'User name must not be empty.',
      'ja'=>'ユーザー名が空になっています。'
    ]
  ];

  const DUPLICATED_USER_NAME = [
    'id'=>2015,
    'message'=>[
      'en'=>'User name is already used. Please use different name.',
      'ja'=>'指定したユーザー名は既に使われています。他の名前をお試しください。'
    ]
  ];

  const INVISIBLE_USER_NAME = [
    'id'=>2016,
    'message'=>[
      'en'=>'At least one visible character is required for user name.',
      'ja'=>'ユーザー名には可視文字を少なくとも1文字以上含める必要があります。'
    ]
  ];

  const INVALID_PASSWORD = [
    'id'=>2021,
    'message'=>[
      'en'=>'Password is invalid.',
      'ja'=>'不正なパスワードです。'
    ]
  ];

  const EMPTY_PASSWORD = [
    'id'=>2022,
    'message'=>[
      'en'=>'Password must not be empty.',
      'ja'=>'パスワードが空になっています。'
    ]
  ];

  const FAILED_PASSWORD_CONFIRMATION = [
    'id'=>2031,
    'message'=>[
      'en'=>'Password confirmation failed.',
      'ja'=>'パスワードと再確認の入力が異なります。'
    ]
  ];

  const INVALID_EMAIL = [
    'id'=>2041,
    'message'=>[
      'en'=>'Email is invalid.',
      'ja'=>'不正なメールアドレスです。'
    ]
  ];

  const INVALID_PRODUCT_NAME = [
    'id'=>2051,
    'message'=>[
      'en'=>'Product name is invalid.',
      'ja'=>'不正な商品名です。'
    ]
  ];

  const EMPTY_PRODUCT_NAME = [
    'id'=>2052,
    'message'=>[
      'en'=>'Product name must not be empty.',
      'ja'=>'商品名が空になっています。'
    ]
  ];

  const INVISIBLE_PRODUCT_NAME = [
    'id'=>2056,
    'message'=>[
      'en'=>'At least one visible character is required for product name.',
      'ja'=>'商品名には可視文字を少なくとも1文字以上含める必要があります。'
    ]
  ];

  const INVALID_PRODUCT_CONDITION_TYPE = [
    'id'=>2053,
    'message'=>[
      'en'=>'Product condition_type is invalid. ONLY \'new\' or \'used\' is allowed.',
      'ja'=>'不正な商品の状態です。\'new\'と\'used\'のみ設定可能です。'
    ]
  ];

  const INVALID_PRODUCT_STOCK = [
    'id'=>2054,
    'message'=>[
      'en'=>'Product stock is invalid.',
      'ja'=>'不正な在庫数です。'
    ]
  ];

  const INVALID_PRODUCT_PRICE = [
    'id'=>2055,
    'message'=>[
      'en'=>'Product price is invalid.',
      'ja'=>'不正な商品価格です。'
    ]
  ];

  const INVALID_PRODUCT_CREATED_DATE = [
    'id'=>2057,
    'message'=>[
      'en'=>'Product created_date is invalid.',
      'ja'=>'不正な作成日時です。'
    ]
  ];

  const INVALID_DELIVERY_TYPE_NAME = [
    'id'=>2061,
    'message'=>[
      'en'=>'Delivery type\'s name is invalid.',
      'ja'=>'不正な配送手法名です。'
    ]
  ];

  const INVISIBLE_DELIVERY_TYPE_NAME = [
    'id'=>2066,
    'message'=>[
      'en'=>'At least one visible character is required for delivery type\'s name.',
      'ja'=>'配送手法名には可視文字を少なくとも1文字以上含める必要があります。'
    ]
  ];

  const INVALID_DELIVERY_TYPE_CHARGE = [
    'id'=>2062,
    'message'=>[
      'en'=>'Delivery type\'s charge is invalid.',
      'ja'=>'不正な配送料金です。'
    ]
  ];

  const PRODUCT_DEALER_NOT_FOUND = [
    'id'=>2951,
    'message'=>[
      'en'=>'Product dealer is not found.',
      'ja'=>'商品を登録した販売業者が見つかりません。'
    ]
  ];

  const PRODUCT_DELIVERY_TYPE_NOT_FOUND = [
    'id'=>2952,
    'message'=>[
      'en'=>'Product delivery_type is not found.',
      'ja'=>'商品の配送方法が見つかりません。'
    ]
  ];

  const LOGIN_FAILED = [
    'id'=>2921,
    'message'=>[
      'en'=>'Login failed.',
      'ja'=>'ログインに失敗しました。'
    ]
  ];

  const INVALID_LOGIN_COMBINATION = [
    'id'=>2922,
    'message'=>[
      'en'=>'Invalid user name / password combination. Check if the inputs are correct.',
      'ja'=>'無効なユーザー名とパスワードの組み合わせです。入力に誤りがないか確認してください。'
    ]
  ];

  const PURCHASE_BY_NON_MEMBER = [
    'id'=>2932,
    'message'=>[
      'en'=>'You can\'t purchase products when you are not loginned as a member.',
      'ja'=>'会員としてログインしていないため、商品を購入できません。'
    ]
  ];

  const INVALID_PURCHASE_UNITS = [
    'id'=>2933,
    'message'=>[
      'en'=>'Purchase units is invalid.',
      'ja'=>'不正な購入個数です。'
    ]
  ];

  const REMOVE_MISSING_PURCHASE_ITEM = [
    'id'=>2934,
    'message'=>[
      'en'=>'Removing purchase item do not exists in cart.',
      'ja'=>'買い物かごから削除しようとした商品が買い物かごの中に見つかりません。'
    ]
  ];

  const LACK_OF_PRODUCT = [
    'id'=>2935,
    'message'=>[
      'en'=>'You cannot purchase products as much as you want in the basket for the lack of product stock.',
      'ja'=>'買い物かごに入れた商品の在庫が不足しているため、指定した個数分を購入することができません。'
    ]
  ];

  const INVALID_OPERATION = [
    'id'=>2991,
    'message'=>[
      'en'=>'Invaild operation detected.',
      'ja'=>'不正な操作を検出しました。'
    ]
  ];

  const FATAL_ERROR = [
    'id'=>2999,
    'message'=>[
      'en'=>'FATAL ERROR occurred. If you reload the brower and see this error again, please <a href="https://github.com/urushiyama/db-i-shop/issues">contact me</a>. (Because this application is made for an assignment and is in private repository, you cannot follow the link above.)',
      'ja'=>'致命的なエラーが発生しました。ウェブページをリロードしてもこのエラーが発生する場合は、お手数ですが<a href="https://github.com/urushiyama/db-i-shop/issues">こちら</a>にご報告ください。（授業課題のためリポジトリはPrivateにしており、そのためリンク先にはアクセスできません。）'
    ]
  ];

  private static $locale = 'ja';

  private static $exception = null;

  public function getIterator() {
    $ex = $this;
    $array = [];
    do {
      $array[] = $ex;
    } while ($ex = $ex->getPrevious());
    return new ArrayIterator(array_reverse($array));
  }

  static function setLocale($locale='ja') {
    self::$locale = $locale;
  }

  static function create($ex) {
    self::$exception = new self($ex['message'][self::$locale], $ex['id'], self::$exception);
  }

  static function raise() {
    $ex = self::$exception;
    self::$exception = null;
    throw $ex;
  }

  static function reset() {
    self::$exception = null;
  }

  static function isStored() {
    return (self::$exception !== null);
  }

  static function getExceptions($ex) {
    foreach ($ex as $value) {
      yield ['message'=>$value->getMessage(), 'id'=>$value->getCode()];
    }
  }
}
 ?>
