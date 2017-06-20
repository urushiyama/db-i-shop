<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');

 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>検索結果</h2>
  </div>
  <div class="box-login-form-content">
    <?=$renderer->render(['template'=>'_brief-product-container.php']) ?>
    <?=$renderer->render(['template'=>'_brief-product-container.php', 'product_name'=>'Lorem Keeper', 'dealer_name'=>'Foo Bar Co.Ltd.', 'product_price'=>14860]) ?>
  </div>
</div>
