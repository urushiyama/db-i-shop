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

const LOGIN_TYPE_MEMBER = 1;
const LOGIN_TYPE_MEMBER_NAME = '会員';
const LOGIN_TYPE_MEMBER_MODEL = 'Members';
const LOGIN_TYPE_DEALER = 2;
const LOGIN_TYPE_DEALER_NAME = '販売業者';
const LOGIN_TYPE_DEALER_MODEL = 'Dealers';

const LOGIN_TYPE = [
  LOGIN_TYPE_MEMBER=>[name=> LOGIN_TYPE_MEMBER_NAME, model=> LOGIN_TYPE_MEMBER_MODEL],
  LOGIN_TYPE_MEMBER_NAME=>LOGIN_TYPE_MEMBER,
  LOGIN_TYPE_DEALER=>[name=> LOGIN_TYPE_DEALER_NAME, model=> LOGIN_TYPE_DEALER_MODEL],
  LOGIN_TYPE_DEALER_NAME=>LOGIN_TYPE_DEALER
];

$system_name = system_name();
?>
