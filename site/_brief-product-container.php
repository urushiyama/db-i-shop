<?php
if (!isset($image)) $image = 'http://lorempixel.com/64/64/technics/'.rand(1, 10);
if (!isset($product_id)) $product_id = 0;
if (!isset($product_name)) $product_name = 'Product Name';
if (!isset($dealer_name)) $dealer_name = 'Dealer Name';
if (!isset($delivery_type)) $delivery_type = '宅急便';
if (!isset($delivery_cost)) $delivery_cost = 864;
if (!isset($product_price)) $product_price = 1280;
if (!isset($product_stock)) $product_stock = 10;
 ?>
<div class="floating-box">
  <div class="box-content-row">
    <div class="box-content-column" style="flex: 1 1 256px;">
      <img class="product-thumbnail minimum" src="<?=htmlspecialchars($image, ENT_QUOTES) ?>" alt="商品のサムネイル">
    </div>
    <div class="box-content-column box-align-left" style="flex: 1 1 800px;">
      <a class="product-name" href=""><?=htmlspecialchars($product_name) ?></a>
      <em><?=htmlspecialchars($dealer_name) ?> が出品</em>
    </div>
    <div class="box-content-column box-align-right" style="flex: 1 1 400px;">
      <p class="price minimum"><?=htmlspecialchars($product_price) ?>円</p>
      <p><?=htmlspecialchars($delivery_type) ?> 送料<?=htmlspecialchars($delivery_cost) ?>円</p>
    </div>
    <form class="box-content-column" style="flex: 1 1 0;">
      <div class="box-content-row" style="margin: 0;">
        <input type="hidden" name="product_id" value="<?=htmlspecialchars($product_id, ENT_QUOTES) ?>">
        <input class="minimum-width-input" type="number" name="units" value="1" min="1" max="<?=htmlspecialchars($product_stock, ENT_QUOTES)?>">
        <p>残り<?=htmlspecialchars($product_stock) ?>個</p>
      </div>
      <div class="box-content-row" style="margin: 0;">
        <input type="submit" value="買い物かごに入れる">
      </div>
    </form>
  </div>
</div>
