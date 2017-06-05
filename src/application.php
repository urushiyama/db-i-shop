<?php
require_once 'config.php';
require_once 'view_config.php';

if (!isset($page_name)) $page_name = '';
if (!isset($page_file)) $page_file = '_login-page.php';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="application.css">
    <title><?= page_title($page_name) ?></title>
  </head>
  <body>
    <div class="system-logo">
      <a href="#" class="system-logo-link"><?= $system_name ?></a>
    </div>
    <?
      include $page_file;
    ?>
  </body>
</html>
