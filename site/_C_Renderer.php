<?php
class Renderer {
  public $error_page;
  private $error_page_exists;

  function __construct($on_error) {
    $this->error_page = $on_error;
    $this->error_page_exists = file_exists($on_error);
  }

  function render($params) {
    extract($params);
    $output = '';
    ob_start();
    if (file_exists($template)) {
      include $template;
      $output = ob_get_contents();
    } elseif ($this->error_page_exists) {
      include $this->error_page;
      $output = ob_get_contents();
    }
    ob_end_clean();
    return $output;
  }
}
 ?>
