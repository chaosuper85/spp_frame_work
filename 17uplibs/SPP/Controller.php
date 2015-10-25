<?php
class SPP_Controller {
    /**
     * 不启用SESSION
     *
     * @var int
     */
    const SESSION_OFF = 0;
    /**
     * 启用SESSION
     *
     * @var int
     */
    const SESSION_ON = 1;
    
    /**
     * 控制器实例
     *
     * @var unknown
     */
    private $mController;
    /**
     * SESSION状态
     *
     * @var number
     */
    public static $mSessionStatus = self::SESSION_OFF;
    /**
     * 控制器类名称
     *
     * @var unknown
     */
    private $mClassName;
    private $mRunModeCli = false;
    private $mPage = null;
    /**
     * 构造函数
     */
    function __construct() {
        if ('cli' == php_sapi_name()) {
            $this->mRunModeCli = true;
        }
        $tAppConfigs = SPP_Config_Config::getAppConfigs();
        if (isset($tAppConfigs->session) && ! $this->mRunModeCli) {
            if ('redis' == $tAppConfigs->session->type) {
                session_save_path(
                        "tcp://{$tAppConfigs->session->host}:{$tAppConfigs->session->port}?persistent=1&weight=1&timeout=1&database={$tAppConfigs->session->database}");
                session_module_name('redis');
                self::sessionStart();
            } else {
                self::sessionStart();
            }
        }
        if (! $this->mRunModeCli) {
            if (strpos($_SERVER['PHP_SELF'], '/protected/') !== false) {
                $_SERVER['PHP_SELF'] = $_SERVER['REQUEST_URI'];
            }
            preg_match("/^(_([^_]+)_){0,1}([^\/]*?)(\.|$)/i", basename($_SERVER['PHP_SELF']), $matches);
            $tClassName = implode('_', array_map('ucfirst', explode('_', $matches[3])));
        } else {
            $tClassName = $_SERVER['argv'][1];
        }
        $this->mClassName = $this->_getClass($tClassName);
    }
    
    /**
     * 可获控制器类名
     *
     * @param string $pClassName            
     * @return string
     */
    private function _getClass($pClassName) {
        $tKey = APP_NAME . 'class' . $pClassName;
        // @TODO 用xcache实现,以后考虑通过配置文件配置,支持其他方式的缓存
        if (xcache_isset($tKey)) {
            return xcache_get($tKey);
        }
        $tClassNames = array('Page_' . $pClassName,$pClassName );
        foreach ($tClassNames as $tClassName) {
            $tInterfaces = @class_implements($tClassName);
            if (is_array($tInterfaces) && in_array('SPP_Runnable', $tInterfaces)) {
                xcache_set($tKey, $tClassName);
                return $this->mClassName = $tClassName;
            }
        }
    }
    
    /**
     * 启用SESSION
     */
    public static function sessionStart() {
        if (self::SESSION_OFF == self::$mSessionStatus) {
            session_name(constant('APP_NAME'));
            session_start();
            self::$mSessionStatus = self::SESSION_ON;
        }
    }
    
    /**
     * 控制器入口方法
     */
    function run() {
        if ($this->mClassName) {
            //$tPage = new $this->mClassName();
            $this->mPage = new $this->mClassName();
            //if ($tPage instanceof SPP_BasePage) {
            if ($this->mPage instanceof SPP_BasePage) {
                //$this->doFilter($tPage);
                $this->doFilter($this->mPage);
            } else {
                $this->mPage->run();
            }
        } else {
            @file_put_contents(SPP_Config_Config::getSystemPath('log') . "/error.log", "Empty ClassName\r\n", 
                    FILE_APPEND);
            @file_put_contents(SPP_Config_Config::getSystemPath('log') . "/error.log", 
                    "SERVER:{$_SERVER['REQUEST_URI']}\r\n", FILE_APPEND);
            SPP_Util_Util::directGoToUrl('/');
        }
    }
    
    /**
     * 析构函数
     */
    public function __destruct() {
        if (SPP_Config_Config::getMode() == 'debug') {
            if(isset($this->mPage->mDebugMode) && $this->mPage->mDebugMode == false) {
                //@TODO 某些不需要现实debug信息的地方如何查看debug信息?
            } else {
                SPP_Util_Debug::dumpinfo();
            }
        }
    }
    
    /**
     * 过滤, 按照页面类定义的FILTERS常量,依次执行doFilter方法
     */
    function doFilter($pPage) {
        $tClassName = get_class($pPage);
        $tFilters = '';
        $const = "{$tClassName}::FILTERS";
        if (defined($const)) {
            $tFilters = constant($const);
        }
        if (! empty($tFilters)) {
            $tFilters1 = explode(',', $tFilters);
            $tFilters2 = array();
            while (count($tFilters1) > 0) {
                $filter = array_shift($tFilters1);
                $filter = new $filter();
                $tFilters2[] = $filter;
                $filter->doBeforeRun($pPage);
            }
            $pPage->run();
            while (count($tFilters2) > 0) {
                $filter = array_pop($tFilters2);
                $filter->doAfterRun($pPage);
            }
        } else {
            $pPage->run();
        }
    }
}
