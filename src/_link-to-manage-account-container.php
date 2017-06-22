<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>ユーザーを管理する</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-column">
      <div class="box-content-row">
        <input type="button" value="<?=SessionController::LOGIN_TYPE[SessionController::LOGIN_TYPE_MEMBER]['name']?>の管理"
         onclick="location.href='?p=manage-account&amp;account_type=<?=SessionController::LOGIN_TYPE_MEMBER?>'">
        <p></p>
        <input type="button" value="<?=SessionController::LOGIN_TYPE[SessionController::LOGIN_TYPE_DEALER]['name']?>の管理"
         onclick="location.href='?p=manage-account&amp;account_type=<?=SessionController::LOGIN_TYPE_DEALER?>'">
      </div>
    </div>
  </div>
</div>
