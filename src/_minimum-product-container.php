<?php
require_once '_C_Products.php';
require_once '_C_Dealers.php';
if (!isset($image)) $image = 'http://lorempixel.com/64/64/technics/'.rand(1, 10);
if (!isset($product_id)) $product_id = 0;
$product = Products::find_by(['id'=>$product_id]);

if ($product) {
  $product_name = $product->name;
  $dealer = Dealers::find_by(['id'=>$product->dealer_id]);
  if ($dealer) $dealer_name = $dealer->name;
  $product_condition = $product->condition_type;
}

switch ($product_condition) {
  case 'new':
    $product_condition_name = '新品';
    break;
  case 'used':
    $product_condition_name = '中古';
    break;
  default:
    $product_condition_name = '状態不明';
    $product_condition = '';
    break;
}
 ?>
<div class="floating-box" style="margin: 8px;">
  <a class="box-content-row">
    <div class="box-content-column" style="flex: 1 1 256px;">
      <img class="product-thumbnail minimum" src="<?=htmlspecialchars($image, ENT_QUOTES) ?>" alt="商品のサムネイル">
    </div>
    <div class="box-content-column box-align-left" style="flex: 1 1 300px;">
      <p class="product-name minimum"><?=htmlspecialchars($product_name) ?>[<?=htmlspecialchars($product_condition_name)?>]</p>
      <em>
        <?php if ($dealer_name != ""): ?>
        <?=htmlspecialchars($dealer_name) ?> が出品
        <?php else: ?>
        出品者不明
        <? endif ?>
      </em>
    </div>
  </a>
</div>
