<?php
//ini_set('display_errors', 'On');
error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_DEPRECATED);

define('APP_NAME', 'debugProject');
require_once '/Users/chaosuper/17house/code_git/debugProject/17uplibs/SPP/FrameWork.php';

$logDIR = '/var/logs/'.APP_NAME.'/';
$ret = SPP_Tools_Logger::init(SPP_Tools_Logger::LOG_FILE, SPP_Tools_Log_Abstract::LL_TRACE, $logDIR);


$spp= new SPP_FrameWork('/Users/chaosuper/17house/code_git/debugProject/demoProject/protected/config.json');
//$logDIR = __DIR__ . '/../../var/logs/www';
//$ret = SPP_Tools_Logger::init(SPP_Tools_Logger::LOG_FILE, SPP_Tools_Log_Abstract::LL_TRACE, $logDIR);
$spp->run();

