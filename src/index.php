<?php
require_once './_C_Renderer.php';

class MainController {

  private $page;
  private $renderer;
  private $template = 'application.php';
  private $not_found = '_not-found-page.php';

  function __construct(array $params = []) {
    if (isset($params['template'])) $this->template = $params['template'];
    if (isset($params['not_found'])) $this->not_found = $params['not_found'];
    $this->renderer = new Renderer($not_found);
  }

  function run() {
    $this->readPageParam();
    print $this->renderer->render([template=>"application.php", page=>$this->page]);
  }

  private function readPageParam() {
    if (isset($_GET["p"])) {
      $this->page = htmlspecialchars($_GET["p"]);
    } else {
      $this->page = 'top';
    }
  }
}

$main = new MainController();
$main->run();
 ?>
