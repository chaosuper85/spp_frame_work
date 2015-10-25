<?php
class Page_Config extends SPP_BasePage {
    
    public $mDebugMode = false;
    public function __construct() {
        parent::__construct();
    }
    
    public function run() {
        echo '<pre>Configuration from config.json <br>';
        $tConfig = SPP_Config_Config::$mConfigData;
        echo '<pre> redis: host->'.$tConfig->redis->host;
        echo '<pre> Get System Path';
        echo '<pre> temp:'.SPP_Config_Config::getSystemPath('temp');
        echo '<hr>';
        echo '<pre>Configuration from custom configuration file : protected/classConfig/Config.php<br>';
        echo '<pre> TestVal:'. Config_Config::$TestVal;
    }
}