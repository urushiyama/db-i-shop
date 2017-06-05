<?php
// PageController
if (isset($_GET["p"])) {
  $page = htmlspecialchars($_GET["p"]);
} else {
  $page = 'top';
}

if ($page != 'top') {
  $page_name = $page;
} else {
  $page_name = '';
}

$page_file = "_${page}-page.php";
include 'application.php'
 ?>
