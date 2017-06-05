<?php
if (isset($login_type)) {
  if ($login_type == '会員') {
    include '_search-product-container.php';
    $login_as = '販売業者'; include '_login-reg-container.php';
  } elseif ($login_type == '販売業者') {
    include '_search-product-container.php';
    $login_as = '会員'; include '_login-reg-container.php';
  }
} else {
  include '_search-product-container.php';
  $login_as = '会員'; include '_login-reg-container.php';
  $login_as = '販売業者'; include '_login-reg-container.php';
}
 ?>
