<?php
if (!isset($login_as)) $login_as = "会員";
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <?=$login_as ?>の新規登録
  </div>
  <div class="box-login-form-content">
    <form method="post" action="?a=register-account" class="box-content-column">
      <div class="box-content-row">
        <p>ユーザ名</p>
        <input type="text" name="user_name" placeholder="ユーザ名">
      </div>
      <div class="box-content-row">
        <p>パスワード</p>
        <input type="password" name="password" placeholder="パスワード">
      </div>
      <div class="box-content-row">
        <p>パスワードの再入力</p>
        <input type="password" name="password-confirmation" placeholder="パスワード（再入力）">
      </div>
      <div class="box-content-row">
        <input type="button" name="register-account" value="新規登録">
        <p> </p>
        <input type="button" name="back" value="キャンセル">
      </div>
    </form>
  </div>
</div>
