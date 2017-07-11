<?php
class PurchasedProducts extends ModelBase {

  protected static $table = 'purchased_products';

  static function getTable() {
    return static::$table;
  }

  function __construct() {
    # create new instance without modifing database.
  }
}
 ?>
