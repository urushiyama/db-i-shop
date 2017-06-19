<?php
require_once  '_C_ModelBase.php';

class Members extends ModelBase {

  protected static $table = 'members';

  public $id = false;
  public $name;
  private $password;
  public $password_digest;

  function __construct(array $params) {
    extract($params);
    if (isset($id)) $this->id = $id;
    $this->name = $name;
    if (isset($password)) $this->setPassword($password);
    if (isset($password_digest)) $this->password_digest = $password_digest;
  }

  function authenticate($password) {
    return password_verify($password, $this->password_digest);
  }

  function setPassword($password) {
    $this->password = $password;
    $this->password_digest = password_hash($password, PASSWORD_DEFAULT);
  }

  static function create(array $params) {
    # INSERT new record and return it as new instance
    $instance = new self($params);
    $instance->save();
    return $instance;
  }

  static function dump() {
    return parent::query("SELECT * FROM ".self::$table);
  }

  function reload() {
    # reload instance from db
    $rows = static::lookup("id = :id", ['id'=>$this->id], ['name', 'password']);
    if (!$rows) return false;
    $this->name = $rows[0]['name'];
    $this->password_digest = $rows[0]['password'];
    $this->password = '';
    return true;
  }

  function save() {
    # save instance value
    if ($this->id == false) {
      $res = static::insert(['name'=>$this->name, 'password'=>$this->password_digest]);
      if ($res === false) return false;
      $this->id = $res;
      return true;
    } else {
      return static::update("id = :id", ['name'=>$this->name, 'password'=>$this->password_digest, 'id'=>$this->id]);
    }
  }

  function destroy() {
    # delete instance from db
    if ($this->id === false) return false;
    return static::delete("id = :id", ['id'=>$this->id]);
  }

  static function find_by(array $params) {
    if ($params) {
      $query = join(',', array_map(function ($key, $value) { return "${key}=:${value}";},
                                  array_keys($params), array_keys($params)
                                ));
      $res = static::lookup($query, $params);
      return ($res) ? new self([
                      'id'=>$res[0]['id'],
                      'name'=>$res[0]['name'],
                      'password_digest'=>$res[0]['password']
                      ]) : null;
    } else {
      return null;
    }
    return null;
  }

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

if (realpath($argv[0]) == __FILE__) {
  require_once 'config.php';
  ModelBase::setConnectionInfo(['host'=>$dbserver, 'dbname'=>$dbname, 'user'=>$user, 'password'=>$password]);
  ModelBase::initDb();
  print_r(Members::dump());
  $member = new Members(['name'=>'Foo Bar', 'password'=>'foobar']);
  echo "ID=>".$member->id.", Name=>".$member->name.", password=>".$member->password_digest."\n";
  echo $member->save()."\n";
  $member->name='hogehoge';
  echo $member->reload()."\n";
  echo "ID=>".$member->id.", Name=>".$member->name.", password=>".$member->password_digest."\n";
  print_r(Members::dump());
  $member->destroy();
  print_r(Members::dump());
  print_r(Members::find_by(['name'=>'Aran Hartl']));
}

 ?>
