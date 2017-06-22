<?php
class Mailers {

  public $from;
  public $to;
  public $subject;
  public $body;

  function __construct($params = []) {
    if (isset($params['from'])) $this->from = $params['from'];
    if (isset($params['to'])) $this->to = $params['to'];
    if (isset($params['subject'])) $this->subject = $params['subject'];
    if (isset($params['body'])) $this->body = $params['body'];
  }

  static function verify($email) {
    return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  function send(MainController $con) {
    $con->other_params = [
      'from'=>$this->from,
      'to'=>$this->to,
      'subject'=>$this->subject,
      'body'=>$this->body
    ];
    $con->page = 'preview-email';
  }
}


 ?>
