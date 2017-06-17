<?php
require_once 'config.php';
require_once 'view_config.php';
require_once '_C_Renderer.php';

$page_name = ($page == 'top') ? '' : $page;
$page_file = "_${page}-page.php";

$renderer = new Renderer("_not-found-page.php");
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="application.css">
    <title><?= page_title($page_name) ?></title>
  </head>
  <body>
    <div class="system-logo">
      <a href="./?p=top" class="system-logo-link"><?= $system_name ?></a>
    </div>
    <?
      if (isset($flashes)) {
        foreach ($flashes as $flash) {
          if (isset($flash['level']) && isset($flash['message'])) {
            print $renderer->render([
              'template'=>'_flash-container.php',
              'level'=>$flash['level'],
              'message'=>$flash['message']
            ]);
          }
        }
      }
      ?>
    <?=$renderer->render(['template'=>$page_file]); ?>
  </body>
</html>
