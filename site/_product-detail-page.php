<?php
require_once '_C_Renderer.php';
require_once '_C_SessionController.php';
$renderer = new Renderer('_not-found-page.php');

if (!isset($image)) $image = 'http://lorempixel.com/256/256/technics/'.rand(1, 10);
if (!isset($product_id)) $product_id = 0;
if (!isset($product_name)) $product_name = 'Product Name';
if (!isset($dealer_name)) $dealer_name = 'Dealer Name';
if (!isset($delivery_type)) $delivery_type = '宅急便';
if (!isset($delivery_cost)) $delivery_cost = 864;
if (!isset($product_price)) $product_price = 1280;
if (!isset($product_stock)) $product_stock = 10;
if (!isset($product_condition)) $product_condition = 'new';
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
if (!isset($product_description)) $product_description = '商品の詳細';
if (SessionController::currentLoginType() == LOGIN_TYPE_MEMBER && SessionController::currentUser()->isAdmin()){
  $admin = 1;
}
 ?>
<?=$renderer->render(['template'=>'_search-product-container.php']) ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>商品詳細</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-column">
      <div class="box-content-row">
        <div class="box-content-column">
          <img class="product-thumbnail" src="<?=htmlspecialchars($image, ENT_QUOTES) ?>" alt="商品のサムネイル">
        </div>
        <div class="box-content-column box-align-left">
          <a class="product-name" href=""><?=htmlspecialchars($product_name) ?></a>
          <em><?=htmlspecialchars($dealer_name) ?> が出品</em>
          <p class="price"><?=htmlspecialchars($product_price) ?>円</p>
          <p><?=htmlspecialchars($delivery_type) ?> <span class="price minimum">送料<?=htmlspecialchars($delivery_cost) ?>円</span></p>
          <p>商品の状態: <span class="product-condition-<?=htmlspecialchars($product_condition, ENT_QUOTES)?>"><?=htmlspecialchars($product_condition_name) ?></span></p>
          <form action="." method="post" class="box-content-row box-align-baseline">
            <input type="hidden" name="a" value="add-to-cart">
            <input type="hidden" name="product_id" value="<?=htmlspecialchars($product_id, ENT_QUOTES) ?>">
            <input class="minimum-width-input" type="number" name="units" value="1" min="1" max="<?=htmlspecialchars($product_stock, ENT_QUOTES)?>">
            <p>残り<?=htmlspecialchars($product_stock) ?>個</p>
            <? if ($admin): ?>
              <? if ($product_banned): ?>
            <input type="submit" name="submit[ban]" value="出品の一時停止">
              <? else: ?>
            <input type="submit" name="submit[unban]" value="出品の許可">
              <? endif ?>
            <? else: ?>
            <input type="submit" value="買い物かごに入れる">
            <? endif ?>
          </form>
        </div>
      </div>
      <div class="box-content-column box-align-left" style="margin-top: 8px;margin-left: 64px;">
        <p>商品の概要</p>
        <p><?=htmlspecialchars($product_description)?></p>
      </div>
    </div>
  </div>
</div>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>こちらからも出品されています</h2>
  </div>
  <div class="box-login-form-content">
    <?=$renderer->render(['template'=>'_brief-product-container.php'])?>
  </div>
</div>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>関連商品</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-row">
      <?=$renderer->render(['template'=>'_minimum-product-container.php'])?>
      <?=$renderer->render(['template'=>'_minimum-product-container.php'])?>
      <?=$renderer->render(['template'=>'_minimum-product-container.php'])?>
      <p></p>
    </div>
      <a onclick="listAll()">全て表示</a>
  </div>
</div>
