<?php
$user = SessionController::currentUser();
if ($user instanceof Members && $user->isAdmin()) $admin=true;
if (!isset($box_title)) $box_title = '商品を探す';
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2><?=htmlspecialchars($box_title)?></h2>
  </div>
  <div class="search-form-content">
    <form method="get" action="./?p=search-product" class="box-content-column">
      <input type="hidden" name="a" value="search-product">
      <div class="box-content-row">
        <input type="text" name="query" placeholder="商品名を入力して検索">
        <input type="submit" name="submit[search]" value="検索">
      </div>
      <?php if ($advanced_search): ?>
      <div class="box-content-column">
        <p>条件で絞り込む</p>
      </div>
      <div class="box-content-column">
        <div class="box-content-row">
          <input type="number" name="min-price" placeholder="下限">
          <p>円以上</p>
          <input type="number" name="max-price" placeholder="上限">
          <p>円未満</p>
        </div>
      </div>
      <?php endif ?>
      <?php if (SessionController::currentLoginType() == SessionController::LOGIN_TYPE_DEALER): ?>
      <div class="box-content-column">
        <div class="box-content-row">
          <input type="submit" name="submit[dealing]" value="あなたの出品の一覧を表示">
        </div>
      </div>
      <?php endif ?>
      <?php if ($admin): ?>
      <div class="box-content-column">
        <div class="box-content-row">
          <input type="checkbox" name="show-banned" value="1" checked="checked">
          <p>出品停止中の商品を表示</p>
          <input type="checkbox" name="show-not-banned" value="1" checked="checked">
          <p>出品継続中の商品を表示</p>
        </div>
        <div class="box-content-row">
          <input type="submit" name="submit[search]" value="絞り込み検索">
          <p>または</p>
          <input type="submit" name="submit[index]" value="全ての出品を表示">
        </div>
      </div>
      <?php endif ?>
    </form>
  </div>
</div>
