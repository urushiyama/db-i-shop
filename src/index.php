<?php
require_once '_C_Renderer.php';
require_once '_C_SessionController.php';
require_once '_C_ActionDispatcher.php';
require_once '_C_ApplicationException.php';
require_once '_C_ModelBase.php';

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
    include 'config.php';
    ModelBase::setConnectionInfo([
      'host'=>$dbserver,
      'dbname'=>$dbname,
      'user'=>$user,
      'password'=>$password
    ]);
    ModelBase::initDb();
  }

  function run() {
    try {
      ApplicationException::reset();
      ApplicationException::setLocale('ja');
      SessionController::start();
      $this->readActionParam();
      $this->readPageParam();
      $flashes = [];
      if ($this->action !== '') {
          ActionDispatcher::act($this);
      }
    } catch (Exception $e) {
      if ($e instanceof ApplicationException) {
        $exceptions = ApplicationException::getExceptions($e);
        foreach ($exceptions as $dict) {
          $level = 'info';
          if ($dict['id'] >= 2000) {
            $level = 'danger';
          } elseif ($dict['id']>=1000) {
            $level = 'warn';
          }
          $flashes[] = ['level'=>$level, 'message'=>$dict['message']];
        }
      } else {
        $flashes[] = ['level'=>'danger', 'message'=>$e->getMessage()];
      }
    }
    if (empty($flashes)) {
      print $this->renderer->render(['template'=>$this->template, 'page'=>$this->page]);
    } else {
      print $this->renderer->render(['template'=>$this->template, 'page'=>$this->page, 'flashes'=>$flashes]);
    }
  }

  private function readActionParam() {
    if (isset($_POST["a"])) {
      $this->action = htmlspecialchars($_POST["a"]);
    } else {
      $this->action = '';
    }
  }

  private function readPageParam() {
    if (isset($_GET["p"])) {
      $this->page = htmlspecialchars($_GET["p"]);
    } else {
      $this->page = 'top';
    }
    if (!SessionController::isLoggedIn()) {
      $limited_pages = [
        'delete-account',
        'logout',
        'register-update'
      ];
      self::limitAccessPages($limited_pages);
    }
    if (SessionController::currentLoginType() === SessionController::LOGIN_TYPE_MEMBER) {
      $limited_pages = [
      ];
      self::limitAccessPages($limited_pages);
    }
    if (SessionController::currentLoginType() === SessionController::LOGIN_TYPE_DEALER) {
      $limited_pages = [
      ];
      self::limitAccessPages($limited_pages);
    }
    if (ApplicationException::isStored()) {
      ApplicationException::raise();
    }
  }

  private function limitAccessPages(array $limited_pages) {
    if (in_array($this->page, $limited_pages)) {
      $this->page = 'top';
      $this->action = '';
      ApplicationException::create(ApplicationException::INVALID_OPERATION);
    }
  }

}

$main = new MainController();
$main->run();
 ?>
