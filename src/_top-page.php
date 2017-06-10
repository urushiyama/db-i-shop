<?php
require_once '_C_Renderer.php';
$renderer = new Renderer('_not-found-page.php');

$output = '';

if (isset($login_type)) {
  if ($login_type == '会員') {
    $output .= $renderer->render([template=>"_search-product-container.php"]);
    $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>'販売業者']);
  } elseif ($login_type == '販売業者') {
    $output .= $renderer->render([template=>"_search-product-container.php"]);
    $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>'会員']);
  }
} else {
  $output .= $renderer->render([template=>"_search-product-container.php"]);
  $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>'会員']);
  $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>'販売業者']);
}
 ?>

<?=$output?>
