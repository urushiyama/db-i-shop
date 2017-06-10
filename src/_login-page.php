<div class="box-login-form">
  <div class="box-login-form-title">
    ログインする
  </div>
  <div class="box-login-form-content">
    <form method="post" action="." class="box-content-column">
      <input type="hidden" name="a" value="login-account">
      <div class="box-content-row">
        <p>ユーザ名</p>
        <input type="text" name="user_name" placeholder="ユーザ名">
      </div>
      <div class="box-content-row">
        <p>パスワード</p>
        <input type="password" name="password" placeholder="パスワード">
      </div>
      <div class="box-content-row">
        <input type="submit" value="ログイン">
        <p> </p>
        <input type="button" name="back" value="戻る" onclick="history.back();return false">
      </div>
    </form>
  </div>
</div>
