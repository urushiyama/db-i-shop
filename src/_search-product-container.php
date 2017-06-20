<?php
$user = SessionController::currentUser();
if ($user instanceof Members && $user->isAdmin()) $admin=true;
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>商品を探す</h2>
  </div>
  <div class="search-form-content">
    <form method="get" action="." class="box-content-column">
      <input type="hidden" name="p" value="search-product">
      <div class="box-content-row">
        <input type="text" name="query" placeholder="商品名を入力して検索">
        <input type="submit" name="submit-search" value="検索">
      </div>
      <? if ($advanced_search): ?>
      <div class="box-content-column">
        <p>条件で絞り込む</p>
      </div>
      <div class="box-content-column">
        <div class="box-content-row">
          <input type="number" name="minimum-value" placeholder="下限">
          <p>円以上</p>
          <input type="number" name="maximum-value" placeholder="上限">
          <p>円未満</p>
        </div>
      </div>
      <? endif ?>
      <? if (SessionController::currentLoginType() == SessionController::LOGIN_TYPE_DEALER): ?>
      <div class="box-content-column">
        <div class="box-content-row">
          <input type="submit" name="submit-index-dealing" value="あなたの出品の一覧を表示">
        </div>
      </div>
      <? endif ?>
      <? if ($admin): ?>
      <div class="box-content-column">
        <div class="box-content-row">
          <input type="checkbox" name="show-banned" value="1" checked="checked">
          <p>出品停止中の商品を表示</p>
          <input type="checkbox" name="show-not-banned" value="1" checked="checked">
          <p>出品継続中の商品を表示</p>
        </div>
        <div class="box-content-row">
          <input type="submit" name="submit-search" value="絞り込み検索">
          <p>または</p>
          <input type="submit" name="submit-index" value="全ての出品を表示">
        </div>
      </div>
      <? endif ?>
    </form>
  </div>
</div>
