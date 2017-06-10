<?php
require './_C_renderer.php';

// PageController
if (isset($_GET["p"])) {
  $page = htmlspecialchars($_GET["p"]);
} else {
  $page = 'top';
}

$renderer = new Renderer("_not-found-page.php");
$renderer.render([template=>"application.php", page=>$page]);

 ?>
