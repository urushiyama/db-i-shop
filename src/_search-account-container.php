<?php
$user = SessionController::currentUser();
if ($user instanceof Members && $user->isAdmin()) $admin=true;
if (!isset($account_type)) $account_type = SessionController::LOGIN_TYPE_MEMBER;
 ?>
<div class="box-login-form">
  <div class="box-login-form-title">
    <h2><?= htmlspecialchars(SessionController::LOGIN_TYPE[$account_type]['name']) ?>を探す</h2>
  </div>
  <div class="search-form-content">
    <form method="get" action="." class="box-content-column">
      <input type="hidden" name="a" value="search-account">
      <input type="hidden" name="account-type" value="<?=htmlspecialchars($account_type, ENT_QUOTES) ?>">
      <div class="box-content-column">
        <div class="box-content-row">
          <label for="query">ユーザ名</label>
          <input type="text" id="query" name="query" placeholder="ユーザ名を入力して検索">
        </div>
        <div class="box-content-row">
          <input type="submit" name="submit[search]" value="検索">
          <p>または</p>
          <input type="submit" name="submit[index]" value="全ての<?= htmlspecialchars(SessionController::LOGIN_TYPE[$account_type]['name'], ENT_QUOTES) ?>を表示">
        </div>
      </div>
    </form>
  </div>
</div>
