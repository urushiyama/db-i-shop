<?php
require_once '_C_SessionController.php';

$user = SessionController::currentUser();
 ?>
<? if ($user): ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <?= htmlspecialchars($user->name) ?>さんの会員情報
  </div>
  <div class="box-login-form-content">
    <form>
      <input type="button" name="login" value="会員情報の更新/削除" onclick="location.href='?p=register-update'">
      <p>または</p>
      <input type="button" name="register" value="ログアウト" onclick="location.href='?p=logout'">
    </form>
  </div>
</div>
<? endif ?>
