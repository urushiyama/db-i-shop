<?php
$system_name = 'URIKAI';

function page_title($page_name) {
  if ($page_name == '') {
    return $system_name;
  } else {
    return "$page_name | $system_name";
  }
}
?>
