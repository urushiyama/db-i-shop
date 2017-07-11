<?php
require_once '_C_Renderer.php';
require_once '_C_SessionController.php';
$renderer = new Renderer('_not-found-page.php');

$output = '';

if ($login_type = SessionController::currentLoginType()) {
  if ($login_type == SessionController::LOGIN_TYPE_MEMBER && SessionController::currentUser()->isAdmin()) {
    $output .= $renderer->render(['template'=>'_link-to-manage-account-container.php']);
    $output .= $renderer->render(['template'=>'_search-product-container.php', 'box_title'=>'商品の検索']);
    $output .= $renderer->render(['template'=>"_account-info-container.php"]);

  } elseif ($login_type == SessionController::LOGIN_TYPE_MEMBER) {
    $output .= $renderer->render(['template'=>"_search-product-container.php"]);
    $output .= $renderer->render(['template'=>"_account-info-container.php"]);
    $output .= $renderer->render(['template'=>"_login-reg-container.php", 'login_as'=>SessionController::LOGIN_TYPE_DEALER]);
    $output .= $renderer->render(['template'=>"_purchased-product-container.php"]);
  } elseif ($login_type == SessionController::LOGIN_TYPE_DEALER) {
    $output .= $renderer->render(['template'=>"_link-to-manage-product-container.php"]);
    $output .= $renderer->render(['template'=>"_search-product-container.php", 'box_title'=>'全ての業者の商品を検索する']);
    $output .= $renderer->render(['template'=>"_account-info-container.php"]);
    $output .= $renderer->render(['template'=>"_login-reg-container.php", 'login_as'=>SessionController::LOGIN_TYPE_MEMBER]);
  }
} else {
  $output .= $renderer->render(['template'=>"_search-product-container.php"]);
  $output .= $renderer->render(['template'=>"_login-reg-container.php", 'login_as'=>SessionController::LOGIN_TYPE_MEMBER]);
  $output .= $renderer->render(['template'=>"_login-reg-container.php", 'login_as'=>SessionController::LOGIN_TYPE_DEALER]);
}
 ?>

<?=$output?>
