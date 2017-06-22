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
