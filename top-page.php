<?php
require 'config.php';
require 'view_config.php';

$page_name = '';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="top-page.css">
    <title><?= page_title($page_name) ?></title>
  </head>
  <body>
    <div class="system-logo">
      <?= $system_name ?>
    </div>
    <div class="search-form">
      <div class="search-form-title">
        商品を探す
      </div>
      <div class="search-form-content">
        <form>
          <input type="text">
          <input type="submit">
        </form>
      </div>
    </div>
    <div class="box-login-form">
      <div class="box-login-form-title">
        会員としてログイン・新規登録する
      </div>
      <div class="box-login-form-content">
        <form>
          <input type="button">
          または
          <input type="button">
        <form>
      </div>
    </div>
    <div class="box-login-form">
      <div class="box-login-form-title">
        販売業者としてログイン・新規登録する
      </div>
      <div class="box-login-form-content">
        <form>
          <input type="button">
          または
          <input type="button">
        <form>
      </div>
    </div>
  </body>
</html>
