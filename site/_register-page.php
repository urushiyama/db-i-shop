<?php
$login_type = isset($_GET['login-as']) ? htmlspecialchars($_GET['login-as'])
                                    : SessionController::LOGIN_TYPE_MEMBER;
if (isset($_POST['login_type'])) $login_type = htmlspecialchars($_POST['login_type']);
$user_name = isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name'])
                                    : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password'])
                                    : '';

 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2><?=SessionController::LOGIN_TYPE[$login_type]['name'] ?>の新規登録</h2>
  </div>
  <div class="box-login-form-content">
    <form method="post" action="." class="box-content-column">
      <input type="hidden" name="a" value="register-account">
      <input type="hidden" name="login_type" value="<?=$login_type ?>">
      <div class="box-content-row">
        <p>ユーザ名</p>
        <input type="text" name="user_name" placeholder="ユーザ名" value="<?=$user_name ?>">
      </div>
      <div class="box-content-row">
        <p>パスワード</p>
        <input type="password" name="password" placeholder="パスワード" value="<?=$password ?>">
      </div>
      <div class="box-content-row">
        <p>パスワードの再入力</p>
        <input type="password" name="password-confirmation" placeholder="パスワード（再入力）">
      </div>
      <div class="box-content-row">
        <input type="submit" value="新規登録">
        <p> </p>
        <input type="button" name="back" value="キャンセル" onclick="history.back();return false">
      </div>
    </form>
  </div>
</div>
