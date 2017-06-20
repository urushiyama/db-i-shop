<?php
require_once  '_C_AuthenticatedUsers.php';

class Members extends AuthenticatedUsers {

  protected static $table = 'members';

  private $admin = false;

  function isAdmin() {
    return ($this->admin == true);
  }

  function __construct(array $params) {
    extract($params);
    if (isset($id)) $this->id = $id;
    $this->name = $name;
    if (isset($password)) $this->setPassword($password);
    if (isset($password_digest)) $this->password_digest = $password_digest;
    if (isset($admin)) $this->admin = $admin;
  }

  function reload() {
    # reload instance from db
    $row = static::lookup("id = :id", ['id'=>$this->id], ['name', 'password', 'admin']);
    if (!$rows) return false;
    $this->name = $rows[0]['name'];
    $this->password_digest = $rows[0]['password'];
    $this->password = '';
    $this->admin = $rows[0]['admin'];
    return true;
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
                      'password_digest'=>$res[0]['password'],
                      'admin'=>$res[0]['admin']
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
 ?>
