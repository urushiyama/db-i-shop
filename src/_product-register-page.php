<?php
require_once '_C_Renderer.php';
// require_once '_C_Crypt.php';
$renderer = new Renderer('_not-found-page.php');

if (!isset($image)) $image = 'http://lorempixel.com/256/256/technics/'.rand(1, 10);
if (!isset($product_id)) $product_id = 0;
$product_id = (isset($_GET['product_id'])) ? $_GET['product_id'] : 0;
if (!isset($product_name)) $product_name = 'Product Name';
if (!isset($dealer_name)) $dealer_name = 'Dealer Name';
if (!isset($delivery_type)) $delivery_type = '宅急便';
if (!isset($delivery_cost)) $delivery_cost = 864;
if (!isset($product_price)) $product_price = 1280;
if (!isset($product_stock)) $product_stock = 10;
if (!isset($product_condition)) $product_condition = 'new';
// switch ($product_condition) {
//   case 'new':
//     $product_condition_name = '新品';
//     break;
//   case 'used':
//     $product_condition_name = '中古';
//     break;
//   default:
//     $product_condition_name = '不明';
//     $product_condition = '';
//     break;
// }
if (!isset($product_description)) $product_description = '商品の詳細';
 ?>
<?=$renderer->render(['template'=>'_search-product-container.php']) ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>商品の登録・更新</h2>
  </div>
  <div class="box-login-form-content">
    <form class="box-content-column">
      <div action="." method="post" class="box-content-row">
        <input type="hidden" name="a" value="update-product">
        <div class="box-content-column">
          <img class="product-thumbnail" src="<?=htmlspecialchars($image, ENT_QUOTES) ?>" alt="商品のサムネイル">
        </div>
        <div class="box-content-column box-align-left">
          <input type="text" name="product-name" class="product-name" value="<?=htmlspecialchars($product_name, ENT_QUOTES) ?>">
          <p><input type="number" name="product-price" class="price" value="<?=htmlspecialchars($product_price, ENT_QUOTES)?>">円</p>
          <p>
            <select name="product-delivery">
              <option value="1">宅急便 864円</option>
              <option value="2">メール便 320円</option>
            </select>
          </p>
          <p>
            <label for="product-condition">商品の状態:</label>
            <select id="product-condition" class="product-condition" name="product-condition">
              <?php if ($product_condition === 'new'): ?>
              <option value="new" selected>新品</option>
              <option value="used">中古</option>
              <?php elseif ($product_condition === 'used'): ?>
              <option value="new">新品</option>
              <option value="used" selected>中古</option>
              <?php endif ?>
            </select>
          </p>
          <p>
            <label for="units">在庫</label>
            <input id="units" class="minimum-width-input" type="number" name="units" value="<?=htmlspecialchars($product_stock, ENT_QUOTES)?>" min="0">
          </p>
        </div>
      </div>
      <div class="box-content-column box-align-left" style="margin-top: 8px;margin-left: 64px;">
        <label for="product-description">商品の概要</label>
        <textarea id="product-description" name="product-description" rows="8" cols="80"><?=htmlspecialchars($product_description)?></textarea>
      </div>
      <div class="box-content-row">
        <input type="submit" value="登録・更新する">
        <p></p>
        <input type="button" value="キャンセル" onclick="location.href='?p=manage-dealed-product'">
      </div>
    </form>
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
