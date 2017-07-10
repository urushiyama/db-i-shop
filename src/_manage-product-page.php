<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');

 ?>
<?=$renderer->render(['template'=>'_link-to-register-product-container.php']); ?>
<?=$renderer->render(['template'=>'_search-product-container.php', 'box_title'=>'出品した商品を検索']); ?>
<?=$renderer->render(['template'=>'_search-result-container.php', 'button_template'=>'_brief-product-edit-button-container.php', 'search_page'=>'manage-product', 'results'=>$results]); ?>
