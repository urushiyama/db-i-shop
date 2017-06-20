<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>登録情報削除の確認</h2>
  </div>
  <div class="box-login-form-content">
    <form method="post" action="./?p=top" class="box-content-column">
      <input type="hidden" name="a" value="delete-account">
      <div class="box-content-row">
        <b>本当に登録情報を削除しますか？この操作は取り消せません。</b>
      </div>
      <div class="box-content-row">
        <ul>
          <li>今までの購入履歴や検索履歴は全て消去され、復元できなくなります。</li>
          <li>削除したアカウントでは2度とログインできなくなります。</li>
        </ul>
      </div>
      <div class="box-content-row">
        <input type="submit" value="登録情報を削除する">
        <p> </p>
        <input type="button" value="キャンセル" onclick="history.back(); return false">
      </div>
    </form>
  </div>
</div>
