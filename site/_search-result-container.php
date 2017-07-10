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
    <?php if ($brief_template=='_brief-account-edit-button-container.php'): ?>
      <?php foreach ($results as $account): ?>
        <?=$renderer->render(['template'=>$brief_template, 'account_id'=>$account->id]) ?>
      <?php endforeach; ?>
    <?php else: ?>
      <?php if (isset($button_template)): ?>
        <?php foreach ($results as $product): ?>
            <?=$renderer->render(['template'=>$brief_template, 'button_template'=>$button_template, 'product_id'=>$product->id])?>
        <?php endforeach ?>
      <?php elseif (SessionController::currentLoginType() == SessionController::LOGIN_TYPE_DEALER): ?>
        <?php foreach ($results as $product): ?>
            <?=$renderer->render(['template'=>$brief_template, 'button_template'=>'_brief-product-edit-button-container.php', 'product_id'=>$product->id])?>
        <?php endforeach ?>
      <?php else: ?>
        <?php foreach ($results as $product): ?>
            <?=$renderer->render(['template'=>$brief_template, 'product_id'=>$product->id])?>
        <?php endforeach ?>
      <?php endif ?>
    <?php endif ?>
    <div class="box-content-column">
      <div class="box-content-row">
        <?php if ($start_from > 10): ?>
        <input type="button" value="前の10件" onclick="location.href='?p=<?=htmlspecialchars($search_page, ENT_QUOTES)?>&amp;a=<?=htmlspecialchars($search_action, ENT_QUOTES)?>&amp;account-type=<?=$account_type?>&amp;query=<?=$query ?>&amp;start=<?=$start_from - 10 ?>'">
        <?php else: ?>
          <input type="button" value="前の10件" disabled>
        <?php endif ?>
        <?php if ($start_from+10 <= $max): ?>
        <input type="button" value="次の10件" onclick="location.href='?p=<?=htmlspecialchars($search_page, ENT_QUOTES)?>&amp;a=<?=htmlspecialchars($search_action, ENT_QUOTES)?>&amp;account-type=<?=$account_type?>&amp;query=<?=$query ?>&amp;start=<?=$start_from + 10 ?>'">
        <?php else: ?>
          <input type="button" value="次の10件" disabled>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>
