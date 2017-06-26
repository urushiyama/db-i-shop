<?php
class Products extends ModelBase {

  protected static $table = "products";

  public $id;
  public $name;
  public $condition_type;
  public $stock;
  public $price;
  public $description;
  public $created_date;
  public $suspention;
  public $dealer_id;
  public $delivery_type_id;
  // public $coupon_id;

  function __construct(array $params) {
    extract($params);
    foreach ([
      'id', 'name', 'condition_type', 'stock', 'price', 'description',
      'created_date', 'suspention', 'dealer_id', 'delivery_type_id'
      ] as $var_name) {
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
        'condition_type'=>$res['condition_type'],
        'stock'=>$res['stock'],
        'price'=>$res['price'],
        'description'=>$res['description'],
        'created_date'=>$res['created_date'],
        'suspention'=>$res['suspention'],
        'dealer_id'=>$res['dealer_id'],
        'delivery_type_id'=>$res['delivery_type_id']
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
        'condition_type'=>$res['condition_type'],
        'stock'=>$res['stock'],
        'price'=>$res['price'],
        'description'=>$res['description'],
        'created_date'=>$res['created_date'],
        'suspention'=>$res['suspention'],
        'dealer_id'=>$res['dealer_id'],
        'delivery_type_id'=>$res['delivery_type_id']
      ]);
    }, $results);
  }

  function reload() {
    # reload instance from db
    $rows = self::lookup("id = :id", ['id'=>$this->id], [], static::$table);
    if (!$rows) return false;
    $this->name = $rows[0]->name;
    $this->condition_type = $rows[0]->condition_type;
    $this->stock = $rows[0]->stock;
    $this->price = $rows[0]->price;
    $this->description = $rows[0]->description;
    $this->created_date = $rows[0]->created_date;
    $this->suspention = $rows[0]->suspention;
    $this->dealer_id = $res[0]->dealer_id;
    $this->delivery_type_id = $res[0]->delivery_type_id;
    return true;
  }

  function save() {
    # save instance value
    if ($this->id == false) {
      $res = static::insert([
        'name'=>$this->name,
        'condition_type'=>$this->condition_type,
        'stock'=>$this->stock,
        'price'=>$this->price,
        'description'=>$this->description,
        'created_date'=>$this->created_date,
        'suspention'=>$this->suspention,
        'dealer_id'=>$this->dealer_id,
        'delivery_type_id'=>$this->delivery_type_id
    ]);
      if ($res === false) return false;
      $this->id = $res;
      return true;
    } else {
      return static::update("id = :id", [
        'name'=>$this->name,
        'condition_type'=>$this->condition_type,
        'stock'=>$this->stock,
        'price'=>$this->price,
        'description'=>$this->description,
        'created_date'=>$this->created_date,
        'suspention'=>$this->suspention,
        'dealer_id'=>$this->dealer_id,
        'delivery_type_id'=>$this->delivery_type_id,
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
                      'id'              =>$res[0]->id,
                      'name'            =>$res[0]->name,
                      'condition_type'  =>$rows[0]->condition_type,
                      'stock'           =>$rows[0]->stock,
                      'price'           =>$rows[0]->price,
                      'description'     =>$rows[0]->description,
                      'created_date'    =>$rows[0]->created_date,
                      'suspention'      =>$rows[0]->suspention,
                      'dealer_id'       =>$res[0]->dealer_id,
                      'delivery_type_id'=>$res[0]->delivery_type_id
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
        ApplicationException::create(ApplicationException::EMPTY_PRODUCT_NAME);
      }
      $visible_name = str_replace([' ', '　', "\n", "\t"], '', $name);
      if ($visible_name == '') {
        ApplicationException::create(ApplicationException::INVISIBLE_PRODUCT_NAME);
      }
    }
    if (isset($condition_type)) {
      if ($condition_type != 'new' && $condition_type != 'used') {
        ApplicationException::create(ApplicationException::INVALID_PRODUCT_CONDITION_TYPE);
      }
    }
    if (isset($stock)) {
      if ($stock < 0) {
        ApplicationException::create(ApplicationException::INVALID_PRODUCT_STOCK);
      }
    }
    if (isset($price)) {
      if ($price < 0) {
        ApplicationException::create(ApplicationException::INVALID_PRODUCT_PRICE);
      }
    }
    if (isset($created_date)) {
      if (!preg_match('/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[01])\s+([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])/', $created_date)) {
        ApplicationException::create(ApplicationException::INVALID_PRODUCT_CREATED_DATE);
      }
    }
    if (isset($dealer_id)) {
      $dealer = Dealers::find_by(['id'=>$dealer_id]);
      if (!$dealer) {
        ApplicationException::create(ApplicationException::PRODUCT_DEALER_NOT_FOUND);
      }
    }
    if (isset($delivery_type_id)) {
      $delivery_type = DeliveryTypes::find_by(['id'=>$delivery_type_id]);
      if (!$delivery_type) {
        ApplicationException::create(ApplicationException::PRODUCT_DELIVERY_TYPE_NOT_FOUND);
      }
    }
  }
}
 ?>
