<?php
class Renderer {
  public $error_page;

  function __construct($on_error) {
    $this->error_page = $on_error;
  }

  function render($params) {
    extract($params);
    ob_start();
    file_exists($template) ? include $template : include $this->error_page;
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
  }
}
 ?>
