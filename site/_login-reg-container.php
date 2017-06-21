<?php
if (!isset($login_as)) $login_as = SessionController::LOGIN_TYPE_MEMBER;
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2><?= SessionController::LOGIN_TYPE[$login_as]['name'] ?>としてログイン・新規登録する</h2>
  </div>
  <div class="box-login-form-content">
    <form class="box-content-row">
      <input type="button" name="login" value="ログイン" onclick="location.href='?p=login&amp;login-as=<?=urlencode($login_as) ?>'">
      <p>または</p>
      <input type="button" name="register" value="新規登録" onclick="location.href='?p=register&amp;login-as=<?=urlencode($login_as) ?>'">
    </form>
  </div>
</div>
