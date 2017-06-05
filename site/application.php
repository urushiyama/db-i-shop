<?php
require_once 'config.php';
require_once 'view_config.php';

$page_name = '';
$page_file = '_login-page.php';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="application.css">
    <title><?= page_title($page_name) ?></title>
  </head>
  <body>
    <div class="system-logo">
      <?= $system_name ?>
    </div>
    <?
      include $page_file;
    ?>
  </body>
</html>
