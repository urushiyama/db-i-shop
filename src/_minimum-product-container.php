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
<div class="floating-box" style="margin: 8px;">
  <a class="box-content-row">
    <div class="box-content-column" style="flex: 1 1 256px;">
      <img class="product-thumbnail minimum" src="<?=htmlspecialchars($image, ENT_QUOTES) ?>" alt="商品のサムネイル">
    </div>
    <div class="box-content-column box-align-left" style="flex: 1 1 300px;">
      <p class="product-name minimum" href=""><?=htmlspecialchars($product_name) ?></p>
      <em><?=htmlspecialchars($dealer_name) ?></em>
    </div>
  </a>
</div>
