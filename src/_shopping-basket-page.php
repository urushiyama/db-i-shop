<?php
require_once '_C_Renderer.php';
$renderer = new Renderer('_not-found-page.php');

$cart_items = SessionController::loadCart();

$price_total = 0;
$delivery_cost_min = 0;
$delivery_cost_max = 0;
$total_min = 0;
$total_max = 0;
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>買い物かごの中身一覧</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-column">
      <?php
        foreach ($cart_items as $item) {
          print $renderer->render(['template'=>'_brief-product-container.php', 'button_template'=>'_brief-product-basket-button-container.php', 'product_id'=>$item['product']->id, 'purchase_units'=>$item['units']]);
          $price_total += $item['product']->price * $item['units'];
          $delivery_type = DeliveryTypes::find_by(['id'=>$item['product']->delivery_type_id]);
          if ($delivery_type != null) {
            $delivery_cost_min += $delivery_type->charge;
            $delivery_cost_max += $delivery_type->charge * $item['units'];
          }
        }
        $total_min = $price_total + $delivery_cost_min;
        $total_max = $price_total + $delivery_cost_max;
      ?>
    </div>
    <div class="box-content-row" style="border-top: solid 1px black;">
      <p>価格 <span class="price"><?=htmlspecialchars($price_total) ?>円</span> + 税</p>
      <p>送料 <span class="price"><?=htmlspecialchars($delivery_cost_min) ?>~ <?=htmlspecialchars($delivery_cost_max) ?>円</span> + 税</p>
      <p>合計 <span class="price"><?=htmlspecialchars($total_min) ?> ~ <?=htmlspecialchars($total_max) ?>円</span> + 税</p>
    </div>
    <form method="post" action="?p=purchased" class="box-content-row">
      <input type="hidden" name="a" value="purchase-product">
      <?php if ($cart_items): ?>
        <input type="submit" value="購入する">
      <?php else: ?>
        <input type="submit" value="購入する" disabled>
      <?php endif; ?>
      <p></p>
      <input type="button" value="買い物を続ける" onclick="location.href='?p=search-product'">
    </form>
  </div>
</div>
