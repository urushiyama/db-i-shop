<?php
$user_name = "hogehoge"; // ログイン試行に用いたユーザ名を入れる。
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    ログインエラー
  </div>
  <div class="box-login-form-content">
    <form class="box-content-column">
      <div class="box-content-row">
        <p>ログインに失敗しました。次のことを確認してください。</p>
      </div>
      <div class="box-content-row">
        <ul>
          <li>ユーザー名に打ち間違えがないか。</li>
          <li>パスワードに打ち間違えがないか。</li>
        </ul>
      </div>
      <div class="box-content-row">
        <p><?= "あなたはユーザー名として${user_name}を入力しました。" ?></p>
      </div>
      <div class="box-content-row">
        <input type="button" value="再試行" onclick="location.replace('./?p=login');return false">
      </div>
    </form>
  </div>
</div>
