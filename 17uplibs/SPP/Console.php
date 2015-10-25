<?php
/**
 * 命令行运行PHP程序基类, 支持类似GET方式传递的参数形式
 * DEMO: php spp.php Cron_Cli 'a=2&b=3'
 * @author chenshijie
 *
 */
class SPP_Console extends SPP_Object implements SPP_Runnable {
    /**
     * 命令行参数
     *
     * @var array
     */
    protected $mArguments = null;
    // protected $mArgumentOpt = '';
    // php spp.php Cron_Cli 'a=2&b=3'
    /**
     * 构造函数, 解析命令行参数
     */
    function __construct() {
        parent::__construct();
        if (isset($_SERVER['argv'][2])) {
            parse_str(trim($_SERVER['argv'][2]), $this->mArguments);
        }
    }
    public function run() {
    }
}