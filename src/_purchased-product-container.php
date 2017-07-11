<?php if (SessionController::currentLoginType() == SessionController::LOGIN_TYPE_MEMBER): ?>
<?php
$renderer = new Renderer('_not-found-page.php');
$user = SessionController::currentUser();
$histories = PurchasedProducts::lookup("member_id = :member_id",['member_id'=>$user->id]);
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>購入履歴</h2>
  </div>
  <div class="search-form-content">
    <?php foreach ($histories as $history): ?>
      <?=$renderer->render([
        'template'=>'_brief-purchased-product-container.php',
        'product_id'=>$history['product_id'],
        'purchased_date'=>$history['purchased_date'],
        'purchased_units'=>$history['purchased_units']
      ])?>
    <?php endforeach ?>
  </div>
</div>
<?php endif ?>