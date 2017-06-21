<?php
require_once '_C_Renderer.php';
$renderer = new Renderer('_not-found-page.php');
$total = 12800;
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>買い物かごの中身一覧</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-column">
      <?php
        print $renderer->render(['template'=>'_brief-product-container.php', 'button_template'=>'_brief-product-basket-button-container.php']);
        print $renderer->render(['template'=>'_brief-product-container.php', 'button_template'=>'_brief-product-basket-button-container.php', 'product_price'=>580]);
      ?>
    </div>
    <div class="box-content-row" style="border-top: solid 1px black;">
      <p></p>
      <p></p>
      <p>合計 <span class="price"><?=htmlspecialchars($total) ?>円</span> + 税</p>
    </div>
    <div class="box-content-row">
      <input type="button" value="購入する" onclick="location.href='?p=purchased'">
      <p></p>
      <input type="button" value="買い物を続ける" onclick="location.href='?p=search-product'">
    </div>
  </div>
</div>
