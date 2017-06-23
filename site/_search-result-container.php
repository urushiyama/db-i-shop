<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');
$start_from = (isset($_GET['start'])) ? $_GET['start'] : 1;
$query = (isset($_GET['query'])) ? urlencode(htmlspecialchars($_GET['query'])) : '';
if (!isset($max)) $max = 100; // 検索結果の個数
if (!isset($search_page)) $search_page = 'search-product';

if (!isset($brief_template) && SessionController::currentLoginType() == SessionController::LOGIN_TYPE_MEMBER && SessionController::currentUser()->isAdmin()){
  $admin = 1;
  $brief_template = '_admin-brief-product-container.php';
  $search_target = 'account';
}
if (!isset($brief_template)) $brief_template = '_brief-product-container.php';
if (!isset($results)) $results = [];
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>検索結果</h2>
  </div>
  <div class="box-login-form-content">
    <? if ($brief_template=='_brief-account-edit-button-container.php'): ?>
      <?php foreach ($results as $account): ?>
        <?=$renderer->render(['template'=>$brief_template, 'account_id'=>$account->id]) ?>
      <?php endforeach; ?>
    <? else: ?>
      <? if (isset($button_template)): ?>
      <?=$renderer->render(['template'=>$brief_template, 'button_template'=>$button_template])?>
      <?=$renderer->render(['template'=>$brief_template, 'product_name'=>'Lorem Keeper', 'dealer_name'=>'Foo Bar Co.Ltd.', 'product_price'=>14860, 'button_template'=>$button_template]) ?>
      <? else: ?>
      <?=$renderer->render(['template'=>$brief_template])?>
      <?=$renderer->render(['template'=>$brief_template, 'product_name'=>'Lorem Keeper', 'dealer_name'=>'Foo Bar Co.Ltd.', 'product_price'=>14860]) ?>
      <? endif ?>
    <? endif ?>
    <div class="box-content-column">
      <div class="box-content-row">
        <? if ($start_from > 10): ?>
        <input type="button" value="前の10件" onclick="location.href='?p=<?=htmlspecialchars($search_page, ENT_QUOTES)?>&amp;a=<?=htmlspecialchars($search_action, ENT_QUOTES)?>&amp;account-type=<?=$account_type?>&amp;query=<?=$query ?>&amp;start=<?=$start_from - 10 ?>'">
        <? endif ?>
        <? if ($start_from+10 <= $max): ?>
        <input type="button" value="次の10件" onclick="location.href='?p=<?=htmlspecialchars($search_page, ENT_QUOTES)?>&amp;a=<?=htmlspecialchars($search_action, ENT_QUOTES)?>&amp;account-type=<?=$account_type?>&amp;query=<?=$query ?>&amp;start=<?=$start_from + 10 ?>'">
        <? endif ?>
      </div>
    </div>
  </div>
</div>
