<?php
require_once  '_C_ModelBase.php';

class AuthenticatedUsers extends ModelBase {

  protected static $table;

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
    $instance = new static($params);
    $instance->save();
    return $instance;
  }

  static function dump() {
    return parent::query("SELECT * FROM ".static::$table);
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
      return ($res) ? new static([
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
  }
}
 ?>
