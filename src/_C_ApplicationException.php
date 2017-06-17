<?php
class ApplicationException extends RuntimeException implements IteratorAggregate {

  const OK = [
    'id'=>0,
    'message'=>[
      'en'=>'Success!',
      'jp'=>'処理が成功しました。'
    ]
  ];

  const SHORT_PASSWORD = [
    'id'=>1021,
    'message'=>[
      'en'=>'Your password is weak. You sholud change password more longer.',
      'jp'=>'パスワードの長さが短いため、不正ログインの恐れがあります。パスワードをより長いものに変更してください。'
    ]
  ];

  const INVALID_USER_NAME = [
    'id'=>2011,
    'message'=>[
      'en'=>'User name is invalid.',
      'jp'=>'不正なユーザー名です。'
    ]
  ];

  const EMPTY_USER_NAME = [
    'id'=>2012,
    'message'=>[
      'en'=>'User name must not be empty.',
      'jp'=>'ユーザー名が空になっています。'
    ]
  ];

  const DUPLICATED_USER_NAME = [
    'id'=>2015,
    'message'=>[
      'en'=>'User name is already used. Please use different name.',
      'jp'=>'指定したユーザー名は既に使われています。他の名前をお試しください。'
    ]
  ];

  const INVISIBLE_USER_NAME = [
    'id'=>2016,
    'message'=>[
      'en'=>'At least one visible character is required for user name.',
      'jp'=>'ユーザー名には可視文字を少なくとも1文字以上含める必要があります。'
    ]
  ];

  const INVALID_PASSWORD = [
    'id'=>2021,
    'message'=>[
      'en'=>'Password is invalid.',
      'jp'=>'不正なパスワードです。'
    ]
  ];

  const EMPTY_PASSWORD = [
    'id'=>2022,
    'message'=>[
      'en'=>'Password must not be empty.',
      'jp'=>'パスワードが空になっています。'
    ]
  ];

  const FATAL_ERROR = [
    'id'=>2999,
    'message'=>[
      'en'=>'FATAL ERROR occurred. If you reload the brower and see this error again, please <a href="https://github.com/urushiyama/db-i-shop/issues">contact me</a>. (Because this application is made for an assignment and is in private repository, you cannot follow the link above.)',
      'jp'=>'致命的なエラーが発生しました。ウェブページをリロードしてもこのエラーが発生する場合は、お手数ですが<a href="https://github.com/urushiyama/db-i-shop/issues">こちら</a>にご報告ください。（授業課題のためリポジトリはPrivateにしており、そのためリンク先にはアクセスできません。）'
    ]
  ];

  const LOCALE = 'ja';

  private static $exception = null;

  public function getIterator() {
    $ex = $this;
    $array = [];
    do {
      $array[] = $ex;
    } while ($ex = $ex->getPrevious());
    return new ArrayIterator(array_reverse($array));
  }

  static function create($ex) {
    self::$exception = new self($ex['message'][LOCALE], $ex['id'], self::$exception);
  }

  static function raise() {
    $ex = self::$exception;
    self::$exception = null;
    throw $ex;
  }

  static function reset() {
    self::$exception = null;
  }

  static function isRaised() {
    return (self::$exception === null);
  }

  static function getExceptions($ex) {
    foreach ($ex as $value) {
      yield ['message'=>$value->getMessage(), 'id'=>$value->getCode()];
    }
  }
}
 ?>
