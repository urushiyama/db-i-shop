<?php
class ModelBase {

  private static $connection_info;
  protected static $db = false;  // PDO instance, shared by all children classes
  protected static $table; // table name, should be protected to avoid sql injection.

  static function getTable() {
    return static::$table;
  }

  function __construct() {
    # create new instance without modifing database.
  }

  static function initDb() {
    extract(self::$connection_info); // host, dbname, user, password
    $dsn = "mysql:host=${host};dbname=${dbname};charset=utf8;";
    self::$db = new PDO($dsn, $user, $password);
    self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  // set DBMS connection information
  static function setConnectionInfo(array $info) {
    self::$connection_info = $info;
  }

  /* some protected database helper method (query & CLUD) */

  static function query($sql, array $params = []) {
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $params);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  static function insert(array $data) {
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

  static function lookup($where, array $params = [], array $keys = [], $table='') {
    $fields = join(',', $keys) or $fields = '*';
    if ($table=='') $table = static::$table;
    $sql = self::appendWhere("SELECT ${fields} FROM ".$table, $where);
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $params);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  static function update($where, array $data) {
    $set = join(',', array_map(function ($key, $value) { return "${key}=:${value}";},
                                array_keys($data), array_keys($data)
                              ));
    $sql = self::appendWhere("UPDATE ".static::$table." SET ${set}", $where);
    $stmt = self::$db->prepare($sql);
    self::bindParams($stmt, $data);
    return $stmt->execute();
  }

  static function delete($where, array $params = []) {
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
