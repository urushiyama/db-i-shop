<?php
require_once '_C_Renderer.php';
require_once '_C_Products.php';
require_once '_C_Dealers.php';
require_once '_C_DeliveryTypes.php';
$renderer = new Renderer('_not-found-page.php');

if (!isset($button_template)) $button_template = '_brief-product-purchase-button-container.php';

if (!isset($image)) $image = 'http://lorempixel.com/64/64/technics/'.rand(1, 10);

if (!isset($product_id)) $product_id = 0;
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
<div class="floating-box">
  <div class="box-content-row">
    <div class="box-content-column" style="flex: 1 1 256px;">
      <img class="product-thumbnail minimum" src="<?=htmlspecialchars($image, ENT_QUOTES) ?>" alt="商品のサムネイル">
    </div>
    <div class="box-content-column box-align-left" style="flex: 1 1 800px;">
      <a class="product-name" href="?p=product-detail&amp;product_id=<?=htmlspecialchars(urlencode($product_id)) ?>"><?=htmlspecialchars($product_name) ?></a>
      <em>
        <?php if ($dealer_name != ""): ?>
        <?=htmlspecialchars($dealer_name) ?> が出品
        <?php else: ?>
        出品者不明
        <? endif ?>
      </em>
    </div>
    <div class="box-content-column box-align-right" style="flex: 1 1 400px;">
      <p class="price minimum"><?=htmlspecialchars($product_price) ?>円</p>
      <p>
        <?php if ($delivery_type): ?>
        <?=htmlspecialchars($delivery_name) ?>送料<?=htmlspecialchars($delivery_cost) ?>円
        <?php else: ?>
          配送方法不明
        <?php endif ?>
      </p>
    </div>
    <div class="box-content-column" style="flex: 1 1 0;">
      <div class="box-content-row" style="margin: 0;">
        <p>残り<?=htmlspecialchars($product_stock) ?>個</p>
      </div>
      <form action="." method="post" class="box-content-row" style="margin: 0;">
        <input type="hidden" name="a" value="toggle-product-suspention">
        <input type="hidden" name="product_id" value="<?=htmlspecialchars($product_id, ENT_QUOTES) ?>">
        <?php if ($product_banned): ?>
        <input type="submit" name="submit[unban]" value="出品の許可">
        <?php else: ?>
        <input type="button" name="submit[ban]" value="出品の一時停止">
        <?php endif ?>
      </form>
    </div>
  </div>
</div>
