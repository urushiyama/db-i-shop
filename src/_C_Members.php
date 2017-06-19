<?php
require_once  '_C_AuthenticatedUsers.php';

class Members extends AuthenticatedUsers {

  protected static $table = 'members';

  static function validateValues(array $params) {
    extract($params);
    if (isset($name)) {
      if ($name == '') {
        ApplicationException::create(ApplicationException::EMPTY_USER_NAME);
      }
      $visible_name = str_replace([' ', '　', "\n", "\t"], '', $name);
      if ($visible_name == '') {
        ApplicationException::create(ApplicationException::INVISIBLE_USER_NAME);
      }
    }
    if (isset($password)) {
      if ($password == '') {
        ApplicationException::create(ApplicationException::EMPTY_PASSWORD);
      }
      if (isset($confirmation)) {
        if ($password !== $confirmation) {
          ApplicationException::create(ApplicationException::FAILED_PASSWORD_CONFIRMATION);
        }
      }
    }
  }
}
//
// if (realpath($argv[0]) == __FILE__) {
//   require_once 'config.php';
//   ModelBase::setConnectionInfo(['host'=>$dbserver, 'dbname'=>$dbname, 'user'=>$user, 'password'=>$password]);
//   ModelBase::initDb();
//   print_r(Members::dump());
//   $member = new Members(['name'=>'Foo Bar', 'password'=>'foobar']);
//   echo "ID=>".$member->id.", Name=>".$member->name.", password=>".$member->password_digest."\n";
//   echo $member->save()."\n";
//   $member->name='hogehoge';
//   echo $member->reload()."\n";
//   echo "ID=>".$member->id.", Name=>".$member->name.", password=>".$member->password_digest."\n";
//   print_r(Members::dump());
//   $member->destroy();
//   print_r(Members::dump());
//   print_r(Members::find_by(['name'=>'Aran Hartl']));
// }

 ?>
