<?php
function system_name() {
  return 'URIKAI';
}

function page_title($page_name) {
  $system_name = system_name();
  if ($page_name == '') {
    return $system_name;
  } else {
    return "$page_name | $system_name";
  }
}

$system_name = system_name();
?>
