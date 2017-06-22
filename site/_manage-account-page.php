<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');
if (!isset($account_type)) $account_type = SessionController::LOGIN_TYPE_MEMBER;
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2><?= SessionController::LOGIN_TYPE[$account_type]['name'] ?>を登録する</h2>
  </div>
  <div class="box-login-form-content">
    <form class="box-content-column">
      <input type="hidden" name="a" value="send-register-email">
      <div class="box-content-row">
        <label for="user_name">ユーザ名</label>
        <input type="text" id="user_name" name="user_name" placeholder="ユーザ名">
      </div>
      <div class="box-content-row">
        <label for="email">パスワード</label>
        <input type="email" id="email" name="email" placeholder="メールアドレス">
      </div>
      <div class="box-content-row">
        <input type="submit" value="会員登録用メールを送信">
      </div>
    </form>
  </div>
</div>
<?=$renderer->render(['template'=>'_search-account-container.php', 'account_type'=>$account_type]); ?>
<?=$renderer->render(['template'=>'_search-result-container.php', 'brief_template'=>'_brief-account-edit-button-container.php']); ?>
