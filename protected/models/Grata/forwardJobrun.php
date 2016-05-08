<?php
require_once dirname(__FILE__).'/forwordGrata.php';

$forwordGrata = new forwordGrata();
while($forwordGrata->ststus())
{
  $forwordGrata->pushMsg();
}

?>
