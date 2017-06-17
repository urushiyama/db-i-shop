<?php
require_once '_C_Renderer.php';
require_once '_C_SessionController.php';
require_once '_C_ActionDispatcher.php';
require_once '_C_ApplicationException.php';

class MainController {

  public $page;
  public $action;
  private $renderer;
  private $template = 'application.php';
  private $not_found = '_not-found-page.php';

  function __construct(array $params = []) {
    if (isset($params['template'])) $this->template = $params['template'];
    if (isset($params['not_found'])) $this->not_found = $params['not_found'];
    $this->renderer = new Renderer($not_found);
  }

  function run() {
    SessionController::start();
    $this->readPageParam();
    $this->readActionParam();
    $flashes = [];
    if ($this->action !== '') {
      try {
        ActionDispatcher::act($this);
      } catch (Exception $e) {
        $exceptions = ApplicationException::getExceptions();
        foreach ($exceptions as $dict) {
          $level = 'info';
          if ($dict['id'] >= 2000) {
            $level = 'danger';
          } elseif ($dict['id']>=1000) {
            $level = 'warn';
          }
          $flashes[] = ['level'=>$level, 'message'=>$dict['message']];
        }
      }
    }
    if (empty($flashes)) {
      print $this->renderer->render(['template'=>$this->template, 'page'=>$this->page]);
    } else {
      print $this->renderer->render(['template'=>$this->template, 'page'=>$this->page, 'flashes'=>$flashes]);
    }
  }

  private function readPageParam() {
    if (isset($_GET["p"])) {
      $this->page = htmlspecialchars($_GET["p"]);
    } else {
      $this->page = 'top';
    }
  }

  private function readActionParam() {
    if (isset($_POST["a"])) {
      $this->action = htmlspecialchars($_POST["a"]);
    } else {
      $this->action = '';
    }
  }
}

$main = new MainController();
$main->run();
 ?>
