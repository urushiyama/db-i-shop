<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');
$start_from = (isset($_GET['start'])) ? $_GET['start'] : 1;
$query = (isset($_GET['query'])) ? urlencode(htmlspecialchars($_GET['query'])) : '';
$max = 100; // 検索結果の個数

$template = '_brief-product-container.php';
if (SessionController::currentLoginType() == SessionController::LOGIN_TYPE_MEMBER && SessionController::currentUser()->isAdmin()){
  $admin = 1;
  $template = '_admin-brief-product-container.php';
}

 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>検索結果</h2>
  </div>
  <div class="box-login-form-content">
    <? if (isset($button_template)): ?>
    <?=$renderer->render(['template'=>$template, 'button_template'=>$button_template])?>
    <?=$renderer->render(['template'=>$template, 'product_name'=>'Lorem Keeper', 'dealer_name'=>'Foo Bar Co.Ltd.', 'product_price'=>14860, 'button_template'=>$button_template]) ?>
    <? else: ?>
    <?=$renderer->render(['template'=>$template])?>
    <?=$renderer->render(['template'=>$template, 'product_name'=>'Lorem Keeper', 'dealer_name'=>'Foo Bar Co.Ltd.', 'product_price'=>14860]) ?>
    <? endif ?>
    <div class="box-content-column">
      <div class="box-content-row">
        <? if ($start_from > 10): ?>
        <input type="button" value="前の10件" onclick="?p=search-product&amp;query=<?=$query ?>&amp;start=<?=$start_from - 10 ?>">
        <? endif ?>
        <? if ($start_from+10 <= $max): ?>
        <input type="button" value="次の10件" onclick="?p=search-product&amp;query=<?=$query ?>&amp;start=<?=$start_from + 10 ?>">
        <? endif ?>
      </div>
    </div>
  </div>
</div>
