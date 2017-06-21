<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');
$start_from = (isset($_GET['start'])) ? $_GET['start'] : 1;
$query = (isset($_GET['query'])) ? urlencode(htmlspecialchars($_GET['query'])) : '';
$max = 100; // 検索結果の個数
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>検索結果</h2>
  </div>
  <div class="box-login-form-content">
    <?=$renderer->render(['template'=>'_brief-product-container.php']) ?>
    <?=$renderer->render(['template'=>'_brief-product-container.php', 'product_name'=>'Lorem Keeper', 'dealer_name'=>'Foo Bar Co.Ltd.', 'product_price'=>14860]) ?>
    <div class="box-content-column">
      <div class="box-content-row">
        <? if ($start_from > 10): ?>
        <input type="button" value="前の10件" onclick="?p=search-product&amp;query=<?=$query ?>&amp;start=<?=$start_from - 10 ?>">
        <? endif ?>
        <? if ($start_from+10 <= $max): ?>
        <input type="button" value="次の10件" onclick="?p=search-product&amp;query=<?=$query ?>&amp;start=<?=$start_from + 10 ?>">
        <? endif ?>
      </div>
    </form>
  </div>
</div>
