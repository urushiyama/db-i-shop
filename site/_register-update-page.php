<div class="box-login-form">
  <div class="box-login-form-title">
    登録情報の更新
  </div>
  <div class="box-login-form-content">
    <form method="post" action="?a=update-account" class="box-content-column">
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
        <input type="button" name="update-account" value="更新">
        <p> </p>
        <input type="button" name="back" value="キャンセル">
      </div>
    </form>
  </div>
</div>
<div class="box-login-form">
  <div class="box-login-form-title">
    登録情報の削除
  </div>
  <div class="box-login-form-content">
    <form method="post" action="?p=delete-account" class="box-content-column">
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
        <input type="submit" name="action[delete-account]" value="登録情報を削除する">
        <p></p>
      </div>
    </form>
  </div>
</div>
