<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>送信メールのプレビュー</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-column">
      <div class="box-content-column" style="align-items: left;">
        <p>From: </p>
        <p>To: </p>
        <p>Subject: Welcome to <?=$system_name ?></p>
        <p>Mime-Version: 1.0</p>
        <p>Content-Type: text/plain; charset=utf-8</p>
        <p>Content-Transfer-Encoding: 8bit</p>
      </div>
      <div class="box-content-row" style="border-top: solid 1px black;">
<pre style="text-align: left;">
Hi, <?=$account_name?>!

Thank you for your registration for <?=$system_name?>.

Your account was successfully created.
Your temporary password is:
<?=$password?>

Please change password after login.

Login page:
https://cgi.u.tsukuba.ac.jp/~s1511427/db-i-shop/site/?p=login&login-as=<?=$account_type?>

If you have nothing to do with this email, just ignore it, please.


<?=$system_name?> Team
</pre>
      </div>
      <div class="box-content-row">
        <input type="button" name="back" value="管理画面に戻る" onclick="location.href='?p=manage-account'">
      </div>
    </form>
  </div>
</div>
