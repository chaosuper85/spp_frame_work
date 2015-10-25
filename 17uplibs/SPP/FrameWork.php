<?PHP
class SPP_FrameWork {
    /**
     * 自动加载的缓存文件
     *
     * @var string
     */
    private $mAutoLoadCacheFile;
    
    /**
     * 自动加载文件的数组对象
     *
     * @var array
     */
    private static $mLoadArray = array();
    
    /**
     * 是否新发现的加载文件
     *
     * @var boolean
     */
    private static $mNewClassFound = false;
    /**
     * 构造函数
     *
     * @param string $pConfigFile            
     */
    public function __construct($pConfigFile = null) {
        if (('WINNT' == PHP_OS) && getenv('TEMP')) {
            $cache_path = getenv('TEMP');
        } else {
            $cache_path = '/tmp';
        }
        SPP_Config_Config::init($pConfigFile);
        $class_path = SPP_Config_Config::getSystemPath('class');
        if (isset($class_path) && ! empty($class_path)) {
            SPP_FrameWork::add_include_path($class_path . '/');
        }
        if (file_exists($cache_path) && is_writable($cache_path)) {
            $front = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            $this->mAutoLoadCacheFile = realpath($cache_path) . '/spp_framework_' . md5($front . $_SERVER['PHP_SELF']);
        }
        if (! is_null($this->mAutoLoadCacheFile) && file_exists($this->mAutoLoadCacheFile)) {
            self::$mLoadArray = unserialize(file_get_contents($this->mAutoLoadCacheFile));
            foreach (self::$mLoadArray as $file) {
                if (file_exists($file)) {
                    require_once ($file);
                }
            }
        }
    }
    public function __destruct() {
        if (self::$mNewClassFound && ! is_null($this->mAutoLoadCacheFile) && SPP_Config_Config::getMode() == "online") {
            file_put_contents($this->mAutoLoadCacheFile, serialize(self::$mLoadArray));
        }
    }
    
    /**
     * 自动加载方法
     *
     * @param string $pClassName            
     */
    public static function _autoload($pClassName) {
        $tKey = APP_NAME . 'autoload:' . $pClassName;
        if (xcache_isset($tKey)) {
            $tFileName = xcache_get($tKey);
            require_once $tFileName;
            self::$mLoadArray[$pClassName] = $tFileName;
            self::$mNewClassFound = true;
            return;
        }
        
        $path_array = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($path_array as $path) {
            $tFileName = "{$path}/{$pClassName}.php";
            if (file_exists($tFileName)) {
                require_once ($tFileName);
                xcache_set($tKey, $tFileName);
                self::$mLoadArray[$pClassName] = $tFileName;
                self::$mNewClassFound = true;
                return;
            } else {
                $file = (false === strpos($pClassName, '_')) ? $pClassName : implode("/", explode("_", $pClassName));
                $tFileName = "{$path}/{$file}.php";
                if (file_exists($tFileName)) {
                    require_once ($tFileName);
                    xcache_set($tKey, $tFileName);
                    self::$mLoadArray[$pClassName] = $tFileName;
                    self::$mNewClassFound = true;
                    return;
                }
            }
        }
    }
    
    /**
     * 获取Class路径
     *
     * @param string $package_name            
     * @return string
     */
    public static function getClassPath($package_name) {
        return self::$mLoadArray[$package_name];
    }
    
    /**
     * 添加include路径
     *
     * @param string $path            
     */
    static function add_include_path($path) {
        $path_array = explode(PATH_SEPARATOR, ini_get('include_path'));
        array_unshift($path_array, $path);
        ini_set('include_path', implode(PATH_SEPARATOR, array_unique($path_array)));
    }
    public function run() {
        try {

            $tController = new SPP_Controller();
            $tController->run();
        } catch(Exception $e) {
            $page = new SPP_BasePage();
            $page->showMessage($e->getMessage());
        }
    }
}

/**
 * 设置时区
 */
date_default_timezone_set('Asia/Shanghai');
/**
 * 处理APP_NAME,如果没有
 */
if (! defined('APP_NAME')) {
    $call_stack = debug_backtrace();
    define('APP_NAME', md5($call_stack[0]['file']));
}
/**
 * 处理调试模式
 */
if (! defined('APP_DEBUG')) {
    define('APP_DEBUG', false);
}
if (APP_DEBUG === true) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
/**
 * 添加include路径
 */
SPP_FrameWork::add_include_path(realpath(dirname(__FILE__) . '/../'));
// SPP_FrameWork::add_include_path(realpath(dirname(__FILE__) . '/'));

/**
 * 注册自动加载方法
 */
spl_autoload_register(array('SPP_FrameWork','_autoload' ));
function __autoload($pClassName) {
    SPP_FrameWork::_autoload($pClassName);
}
/*
$class_path = SPP_Config_Config::getSystemPath('class');
if (isset($class_path) && ! empty($class_path)) {
    SPP_FrameWork::add_include_path($class_path . '/');
}
*/
