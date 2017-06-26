<?php
require_once '_C_SessionController.php';
if (!isset($product_id)) $product_id = 0;
if (!isset($product_stock)) $product_stock = 0;
if (!isset($product_dealer)) $product_dealer = 0;
 ?>
<form action="." method="post" class="box-content-column" style="flex: 1 1 0;">
  <input type="hidden" name="a" value="edit-product">
  <input type="hidden" name="product_id" value="<?=htmlspecialchars($product_id, ENT_QUOTES) ?>">
  <div class="box-content-row" style="margin: 0;">
    <input class="minimum-width-input" type="number" name="units" value="1" min="1" max="<?=htmlspecialchars($product_stock, ENT_QUOTES)?>">
    <p>残り<?=htmlspecialchars($product_stock) ?>個</p>
  </div>
  <div class="box-content-row" style="margin: 0;">
    <?php if (SessionController::currentLoginType() == SessionController::LOGIN_TYPE_DEALER && SessionController::currentUser()->id == $product_dealer): ?>
    <input type="submit" name="submit[update]" value="更新">
    <input type="submit" name="submit[delete]" value="削除">
    <?php else: ?>
    <input type="button" value="詳細をみる" onclick="location.href='?p=product-detail&amp;product-id=<?=htmlspecialchars(urlencode($product_id), ENT_QUOTES) ?>'">
    <?php endif ?>
  </div>
</form>
