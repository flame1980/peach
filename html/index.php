<?php

require_once(dirname(dirname(__FILE__)).'/config.php');
require_once(LIBRARY_DIR.'/Dispatcher.php');

$dispatcher = new Dispatcher();
$dispatcher->dispatch();
exit;
