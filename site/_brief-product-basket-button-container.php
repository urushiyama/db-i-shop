<?php
if (!isset($product_id)) $product_id = 0;
if (!isset($product_stock)) $product_stock = 0;
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
