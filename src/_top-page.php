<?php
require_once '_C_Renderer.php';
$renderer = new Renderer('_not-found-page.php');

$output = '';

if (isset($login_type)) {
  if ($login_type == LOGIN_TYPE_MEMBER) {
    $output .= $renderer->render([template=>"_search-product-container.php"]);
    $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>LOGIN_TYPE_DEALER]);
  } elseif ($login_type == LOGIN_TYPE_DEALER) {
    $output .= $renderer->render([template=>"_search-product-container.php"]);
    $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>LOGIN_TYPE_MEMBER]);
  }
} else {
  $output .= $renderer->render([template=>"_search-product-container.php"]);
  $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>LOGIN_TYPE_MEMBER]);
  $output .= $renderer->render([template=>"_login-reg-container.php", login_as=>LOGIN_TYPE_DEALER]);
}
 ?>

<?=$output?>
