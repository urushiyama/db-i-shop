<?php
$user = SessionController::currentUser();
$user_name = isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name'])
                                    : $user->name;
$old_password = isset($_POST['old-password']) ? htmlspecialchars($_POST['old-password'])
                                    : '';
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password'])
                                    : '';
 ?>
<?php if ($user): ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>登録情報の更新</h2>
  </div>
  <div class="box-login-form-content">
    <form method="post" action="." class="box-content-column">
      <input type="hidden" name="a" value="update-account">
      <div class="box-content-row">
        <p>ユーザ名</p>
        <input type="text" name="user_name" placeholder="ユーザ名" value="<?=$user_name ?>">
      </div>
      <div class="box-content-row">
        <p>現在のパスワード</p>
        <input type="password" name="old-password" placeholder="現在のパスワード" value="<?=$old_password ?>">
      </div>
      <div class="box-content-row">
        <p>新しいパスワード</p>
        <input type="password" name="password" placeholder="新しいパスワード" value="<?=$password ?>">
      </div>
      <div class="box-content-row">
        <p>パスワードの再入力</p>
        <input type="password" name="password-confirmation" placeholder="パスワード（再入力）">
      </div>
      <div class="box-content-row">
        <input type="submit" value="更新">
        <p> </p>
        <input type="button" name="back" value="キャンセル" onclick="history.back();return false">
      </div>
    </form>
  </div>
</div>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>登録情報の削除</h2>
  </div>
  <div class="box-login-form-content">
    <form method="post" action="./?p=delete-account" class="box-content-column">
      <div class="box-content-row">
        <p>登録情報を削除しますか？</p>
      </div>
      <div class="box-content-row">
        <b>注意</b>
      </div>
      <div class="box-content-row">
        <ul>
          <li>今までの購入履歴や検索履歴は全て消去され、復元できなくなります。</li>
          <li>削除したアカウントでは2度とログインできなくなります。</li>
        </ul>
      </div>
      <div class="box-content-row">
        <p></p>
        <input type="submit" value="登録情報を削除する">
        <p></p>
      </div>
    </form>
  </div>
</div>
<?php endif ?>
