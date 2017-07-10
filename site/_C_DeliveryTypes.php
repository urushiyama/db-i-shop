<?php
class DeliveryTypes extends ModelBase {

  protected static $table = "delivery_types";

  public $id;
  public $name;
  public $charge;

  function __construct(array $params) {
    extract($params);
    foreach (['id', 'name', 'charge'] as $var_name) {
      if (isset($$var_name)) $this->$var_name = $$var_name;
    }
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

  static function query($sql, array $params = []) {
    $results = ModelBase::query($sql, $params);
    return array_map(function ($res) {
      return new static([
        'id'=>$res['id'],
        'name'=>$res['name'],
        'charge'=>$res['charge']
      ]);
    }, $results);
  }

  static function lookup($sql, array $params = [], array $keys = [], $table='') {
    if ($table=='') $table = self::$table;
    $results = ModelBase::lookup($sql, $params, $keys, $table);
    return array_map(function ($res) {
      return new static([
        'id'=>$res['id'],
        'name'=>$res['name'],
        'charge'=>$res['charge']
      ]);
    }, $results);
  }

  function reload() {
    # reload instance from db
    $rows = self::lookup("id = :id", ['id'=>$this->id], [], static::$table);
    if (!$rows) return false;
    $this->name = $rows[0]->name;
    $this->charge = $rows[0]->charge;
    return true;
  }

  function save() {
    # save instance value
    if ($this->id == false) {
      $res = static::insert([
        'name'=>$this->name,
        'charge'=>$this->charge
    ]);
      if ($res === false) return false;
      $this->id = $res;
      return true;
    } else {
      return static::update("id = :id", [
        'name'=>$this->name,
        'charge'=>$this->charge,
        'id'=>$this->id]);
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
      $res = static::lookup($query, $params, [], static::$table);
      return ($res) ? new static([
                      'id'      =>$res[0]->id,
                      'name'    =>$res[0]->name,
                      'charge'  =>$res[0]->charge
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
        ApplicationException::create(ApplicationException::EMPTY_DELIVERY_TYPE_NAME);
      }
      $visible_name = str_replace([' ', '　', "\n", "\t"], '', $name);
      if ($visible_name == '') {
        ApplicationException::create(ApplicationException::INVISIBLE_DELIVERY_TYPE_NAME);
      }
    }
    if (isset($charge)) {
      if ($charge < 0) {
        ApplicationException::create(ApplicationException::INVALID_DELIVERY_TYPE_CHARGE);
      }
    }
  }
}
 ?>
