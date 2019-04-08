<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
//ignore_user_abort(true);
//set_time_limit(0);
$old = ini_set('memory_limit', '2192M'); 
$trello = new Trello();
$trello->Sync();
return;
?>