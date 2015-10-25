<?php
ini_set('display_errors', 'On');
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_DEPRECATED);

define('APP_NAME', 'demo');
require_once '/Users/chenshijie/Workspace/17house/17uplibs/libs/SPP/FrameWork.php';
$spp= new SPP_FrameWork();
$spp->run();
