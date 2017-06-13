<?php
class ModelBase {

  private static $connection_info;
  protected static $db = false;  // PDO instance, shared by all children classes
  protected static $table; // table name, should be protected to avoid sql injection.

  function __construct() {
    # create new instance without modifing database.
  }

  static function initDb() {
    extract(self::$connection_info); // host, dbname, user, password
    $dsn = "mysql:host=${host};dbname=${dbname}";
    self::$db = new PDO($dsn, $user, $password);
    self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  // set DBMS connection information
  static function setConnectionInfo(array $info) {
    self::$connection_info = $info;
  }

  /* some protected database helper method (query & CLUD) */

  protected static function query($sql, array $params = []) {
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $params);
    $res = $stmt->execute();
    if ($res === false) $res = "false";
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  protected static function insert(array $data) {
    $keys = array_keys($data);
    $fields = join(',', $keys);
    $values = join(',', array_map(function ($k) {return ":${k}";}, $keys));
    $sql = "INSERT INTO ".static::$table." (${fields}) VALUES (${values})";
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $data);
    $res = $stmt->execute();
    if ($res === false) return false;
    $sql = "SELECT LAST_INSERT_ID()";
    $res = (int) self::$db->query($sql)->fetchColumn();
    return $res;
  }

  protected static function lookup($where, array $params = [], array $keys = []) {
    $fields = join(',', $keys) or $fields = '*';
    $sql = self::appendWhere("SELECT ${fields} FROM ".static::$table, $where);
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $params);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  protected static function update($where, array $data) {
    $set = join(',', array_map(function ($key, $value) { return "${key}=:${value}";},
                                array_keys($data), array_keys($data)
                              ));
    $sql = self::appendWhere("UPDATE ".static::$table." SET ${set}", $where);
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $data);
    return $stmt->execute();
  }

  protected static function delete($where, array $params = []) {
    $sql = self::appendWhere("DELETE FROM ".static::$table, $where);
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $params);
    return $stmt->execute();
  }

  /* DRY: private functions */

  private static function appendWhere($sql, $where) {
    $_where = ($where != '') ? " WHERE $where" : '';
    return $sql.$_where;
  }

  private static function bindParams($stmt, array $params = []) {
    if ($params) {
      foreach ($params as $key => $value) {
        $stmt->bindValue(":${key}", $value);
      }
    }
  }
}
 ?>
