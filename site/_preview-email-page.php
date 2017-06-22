<div class="box-login-form">
  <div class="box-login-form-title">
    <h2>送信メールのプレビュー</h2>
  </div>
  <div class="box-login-form-content">
    <div class="box-content-column">
      <div class="box-content-column" style="align-items: left;">
        <p>From: <?=$from?></p>
        <p>To: <?=$to?></p>
        <p>Subject: <?=$subject?></p>
        <p>Mime-Version: 1.0</p>
        <p>Content-Type: text/plain; charset=utf-8</p>
        <p>Content-Transfer-Encoding: 8bit</p>
      </div>
      <div class="box-content-row" style="border-top: solid 1px black;">
<pre style="text-align: left;">
<?=$body?>
</pre>
      </div>
      <div class="box-content-row">
        <input type="button" name="back" value="管理画面に戻る" onclick="location.href='?p=manage-account'">
      </div>
    </form>
  </div>
</div>
