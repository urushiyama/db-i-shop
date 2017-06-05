<?php
if (!isset($login_as)) $login_as = "会員";
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <?= $login_as ?>としてログイン・新規登録する
  </div>
  <div class="box-login-form-content">
    <form>
      <input type="button" name="login" value="ログイン">
      <p>または</p>
      <input type="button" name="register" value="新規登録">
    </form>
  </div>
</div>
