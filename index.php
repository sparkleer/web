<?php

header('Content-Type: text/html; charset=utf-8');
define('DS', DIRECTORY_SEPARATOR);
define("ROOT", getcwd());

require (ROOT.DS."config".DS."config.php");



$url = isset($_GET["url"]) ? $_GET["url"] : NULL;
require (ROOT.DS."core".DS."router.php");


?>