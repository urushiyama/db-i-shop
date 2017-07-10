<?php
require_once '_C_Renderer.php';

$renderer = new Renderer('_not-found-page.php');

 ?>
<?=$renderer->render(['template'=>'_search-product-container.php', 'advanced'=>true]); ?>
<?=$renderer->render(['template'=>'_search-result-container.php', 'search_action'=>'search-product', 'max'=>$max, 'results'=>$results]); ?>
