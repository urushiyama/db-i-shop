<?php
require_once '_C_SessionController.php';
require_once '_C_Members.php';
require_once '_C_Dealers.php';

if (!isset($account_id)) $account_id = 0;
if (!isset($account_type)) $account_type = SessionController::LOGIN_TYPE_MEMBER;
if (!isset($account_name)) $account_name = 'NULL';

$model = SessionController::LOGIN_TYPE[$account_type]['model'];
$user = $model::find_by(['id'=>$account_id]);
 ?>
<?php if ($user): ?>
<?php $account_name = $user->name; ?>
<div class="floating-box">
  <form action="." method="post" class="box-content-row">
    <input type="hidden" name="a" value="delete-account">
    <input type="hidden" name="account-type" value="<?=htmlspecialchars($account_type) ?>">
    <input type="hidden" name="account-id" value="<?=htmlspecialchars($account_id, ENT_QUOTES) ?>">
      <p style="flex: 10 8;"><?=htmlspecialchars($account_name) ?></p>
      <input type="submit" value="登録を削除する" style="flex: 1 1 256px;">
  </form>
</div>
<?php endif ?>
