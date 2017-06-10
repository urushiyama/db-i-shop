<?php
require_once './_C_Renderer.php';

// PageController
if (isset($_GET["p"])) {
  $page = htmlspecialchars($_GET["p"]);
} else {
  $page = 'top';
}

$renderer = new Renderer("_not-found-page.php");
print $renderer->render([template=>"application.php", page=>$page]);

 ?>
