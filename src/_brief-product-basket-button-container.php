<?php
if (!isset($product_id)) $product_id = 0;
if (!isset($product_stock)) $product_stock = 0;
$product = Products::find_by(['id'=>$product_id]);
if ($product) {
  $product_name = $product->name;
  $dealer = Dealers::find_by(['id'=>$product->dealer_id]);
  if ($dealer) $dealer_name = $dealer->name;
  $delivery_type = DeliveryTypes::find_by(['id'=>$product->delivery_type_id]);
  if ($delivery_type) {
    $delivery_name = $delivery_type->name;
    $delivery_cost = $delivery_type->charge;
  }
  $product_stock = $product->stock;
  $product_price = $product->price;
  $product_condition = $product->condition_type;
  $product_description = $product->description;
}

switch ($product_condition) {
  case 'new':
    $product_condition_name = '新品';
    break;
  case 'used':
    $product_condition_name = '中古';
    break;
  default:
    $product_condition_name = '不明';
    $product_condition = '';
    break;
}
 ?>
<div class="box-content-column" style="flex: 1 1 0;">
  <div class="box-content-row" style="margin: 0;">
    <input type="hidden" name="product_id" value="<?=htmlspecialchars($product_id, ENT_QUOTES) ?>">
    個数<input class="minimum-width-input" type="number" name="units" value="1" min="1" max="<?=htmlspecialchars($product_stock, ENT_QUOTES)?>">
    <p>残り<?=htmlspecialchars($product_stock) ?>個</p>
  </div>
  <div class="box-content-row" style="margin: 0;">
    <input type="button" value="買い物かごから削除" onclick="removeFromCart(<?=htmlspecialchars($product_id, ENT_QUOTES) ?>);">
  </div>
</div>
