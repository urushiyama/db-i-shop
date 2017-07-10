<?php
require_once '_C_Renderer.php';
require_once '_C_SessionController.php';
require_once '_C_Products.php';
require_once '_C_Dealers.php';
require_once '_C_DeliveryTypes.php';
$renderer = new Renderer('_not-found-page.php');

if (!isset($image)) $image = 'http://lorempixel.com/256/256/technics/'.rand(1, 10);
if (isset($_GET['product_id'])) $product_id = $_GET['product_id'];
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
  $product_price = $product->price;
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

if (SessionController::currentLoginType() == LOGIN_TYPE_MEMBER && SessionController::currentUser()->isAdmin()){
  $admin = 1;
}
 ?>
<?=$renderer->render(['template'=>'_search-product-container.php']) ?>
<?php if ($product): ?>
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
          <em>
            <?php if ($dealer_name != ""): ?>
            <?=htmlspecialchars($dealer_name) ?> が出品
            <?php else: ?>
            出品者不明
            <? endif ?>
          </em>
          <p class="price"><?=htmlspecialchars($product_price) ?>円</p>
          <p>
            <?php if ($delivery_type): ?>
            <?=htmlspecialchars($delivery_name) ?> <span class="price minimum">送料<?=htmlspecialchars($delivery_cost) ?>円</span>
            <?php else: ?>
              配送方法不明
            <?php endif ?>
          </p>
          <p>商品の状態: <span class="product-condition-<?=htmlspecialchars($product_condition, ENT_QUOTES)?>"><?=htmlspecialchars($product_condition_name) ?></span></p>
          <form action="." method="post" class="box-content-row box-align-baseline">
            <input type="hidden" name="a" value="add-to-cart">
            <input type="hidden" name="product_id" value="<?=htmlspecialchars($product_id, ENT_QUOTES) ?>">
            <input class="minimum-width-input" type="number" name="units" value="1" min="1" max="<?=htmlspecialchars($product_stock, ENT_QUOTES)?>">
            <p>残り<?=htmlspecialchars($product_stock) ?>個</p>
            <?php if ($admin): ?>
              <?php if ($product_banned): ?>
            <input type="submit" name="submit[ban]" value="出品の一時停止">
              <?php else: ?>
            <input type="submit" name="submit[unban]" value="出品の許可">
              <?php endif ?>
            <?php else: ?>
            <input type="submit" value="買い物かごに入れる">
            <?php endif ?>
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
<?php else: ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>商品詳細</h2>
  </div>
  <div class="box-login-form-content">
    <p>お探しの商品は見つかりませんでした。</p>
  </div>
</div>
<?php endif ?>
