<?php
require 'config.php';
require 'view_config.php';

$page_name = 'ログイン';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="login-page.css">
    <title><?= page_title($page_name) ?></title>
  </head>
  <body>
    <div class="system-logo">
      <?= $system_name ?>
    </div>
    <div class="box-login-form">
      <div class="box-login-form-title">
        ログインする
      </div>
      <div class="box-login-form-content">
        <form class="box-content-column">
          <div class="box-content-row">
            <p>ユーザ名</p>
            <input type="text" name="user_name" placeholder="ユーザ名">
          </div>
          <div class="box-content-row">
            <p>パスワード</p>
            <input type="password" name="password" placeholder="パスワード">
          </div>
          <div class="box-content-row">
            <input type="button" name="login" value="ログイン">
            <p> </p>
            <input type="button" name="back" value="戻る">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
