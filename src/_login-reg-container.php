<?php
if (!isset($login_as)) $login_as = "会員";
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <?= $login_as ?>としてログイン・新規登録する
  </div>
  <div class="box-login-form-content">
    <form>
      <input type="button" name="login" value="ログイン" onclick="location.href='?p=login&amp;login-as=<?=urldecode($login_as) ?>'">
      <p>または</p>
      <input type="button" name="register" value="新規登録" onclick="location.href='?p=register&amp;login-as=<?=urldecode($login_as) ?>'">
    </form>
  </div>
</div>
