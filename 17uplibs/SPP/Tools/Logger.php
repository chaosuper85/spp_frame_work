<?php

define('LNS', "///...///");

class SPP_Tools_Logger {

    /**
     * 文件型日志类
     */
    const LOG_FILE           = "SPP_Tools_Log_File";
    /**
     * 计时器分隔符
     */
    const LOG_INTERMEDIATE_SPLITTER = '_';
    /**
     * 日志记录函数名
     */
    const LOG_LEVEL_DEBUG        = "debug";
    const LOG_LEVEL_ERROR        = "error";
    const LOG_LEVEL_FATAL        = "fatal";
    const LOG_LEVEL_INFO         = "info";
    const LOG_LEVEL_INTERMEDIATE = "intermediate";
    const LOG_LEVEL_NOTICE       = "notice";
    const LOG_LEVEL_TRACE        = "trace";
    const LOG_LEVEL_TRANSFER     = "transfer";
    const LOG_LEVEL_STATS        = "stats";
    const LOG_LEVEL_UNION        = "union";
    const LOG_LEVEL_WARN         = "warn";
    const LOG_LEVEL_HYBRID       = "hybrid";

    const LOG_LEVEL_DB           = "db";

    /**
     * 计时点开关
     */
    private static $_intermediate = false;
    /**
     * 初始化后的日志类对象
     */
    private static $_logger = null;
    /**
     * 日志类名数组
     */
    private static $_log_class = array(
        self::LOG_FILE,
    );
    /**
     * 日志记录函数对应日志级别
     */
    private static $_log_level_map = array(
        self::LOG_LEVEL_DEBUG        => SPP_Tools_Log_Abstract::LL_DEBUG,
        self::LOG_LEVEL_ERROR        => SPP_Tools_Log_Abstract::LL_FATAL,
        self::LOG_LEVEL_FATAL        => SPP_Tools_Log_Abstract::LL_FATAL,
        self::LOG_LEVEL_INFO         => SPP_Tools_Log_Abstract::LL_TRACE,
        self::LOG_LEVEL_INTERMEDIATE => SPP_Tools_Log_Abstract::LL_TRACE,
        self::LOG_LEVEL_NOTICE       => SPP_Tools_Log_Abstract::LL_NOTICE,
        self::LOG_LEVEL_TRACE        => SPP_Tools_Log_Abstract::LL_TRACE,
        self::LOG_LEVEL_TRANSFER     => SPP_Tools_Log_Abstract::LL_TRACE,
        self::LOG_LEVEL_STATS        => SPP_Tools_Log_Abstract::LL_TRACE,
        self::LOG_LEVEL_UNION        => SPP_Tools_Log_Abstract::LL_TRACE,
        self::LOG_LEVEL_WARN         => SPP_Tools_Log_Abstract::LL_WARNING,
    	self::LOG_LEVEL_HYBRID       => SPP_Tools_Log_Abstract::LL_TRACE,

        self::LOG_LEVEL_DB           => SPP_Tools_Log_Abstract::LL_TRACE,
    );
    /**
     * 向前兼容的日志记录函数名
     */
    private static $_special_log_name = array(
        self::LOG_LEVEL_INTERMEDIATE => 'intermediate',
        self::LOG_LEVEL_STATS        => 'pp-stats',
        self::LOG_LEVEL_TRANSFER     => 'transfer',
        self::LOG_LEVEL_UNION        => 'union',
    	self::LOG_LEVEL_HYBRID       => 'hybrid',
        self::LOG_LEVEL_DB           => 'db'
    );

    /**
     * 实现以下功能：
     * 1.抽象调用不同的日志记录函数
     * 2.调用本类定义好的函数
     * 3.调用实例化后的日志记录类中可被调用的方法（不一定是静态的）
     * 
     * @param string $method
     * @param array $argv
     * @return mixed
     */
    public static function __callStatic($method, $argv) {
        if (isset(self::$_log_level_map[$method])) {
            if ($method == self::LOG_LEVEL_INTERMEDIATE && !self::$_intermediate) {
                return;
            }
            if (is_null(self::$_logger)) {
                return;
            }
            $log_level = self::$_log_level_map[$method];
            $log_name = array_slice($argv, -2);
            if (count($log_name) == 2 && $log_name[0] === LNS) {
                $log_name = $log_name[1];
            } else if (isset(self::$_special_log_name[$method])) {
                $log_name = self::$_special_log_name[$method];
            } else {
                $log_name = null;
            }
            $format = array_shift($argv);
            if (!is_string($format)) {
                $format = !empty($format) ? json_encode($format) : "";
                if (!empty($argv)) {
                    $format .= " " . json_encode($argv);
                }
                $argv = array();
            }
            if ($method == self::LOG_LEVEL_INTERMEDIATE) {
                $argv = str_replace(self::LOG_INTERMEDIATE_SPLITTER, "", trim(@vsprintf($format, $argv)));
                if (empty($argv)) {
                    return;
                }
            }
            return self::$_logger->write_log($log_level, $log_name, $format, $argv);
        }
        if (@method_exists('Logger', $method)) {
            return @call_user_func_array(array('Logger', $method), $argv);
        } else if (@method_exists(self::$_logger, $method)) {
            return @call_user_func_array(array(self::$_logger, $method), $argv);
        } else {
            return null;
        }
    }

    /*
     * 初始化具体的日志类
     * 
     * 要求第一个参数是日志记录类名，第二个参数是日志级别，其余参数是实例化日志记录类时需要的参数，注意参数顺序
     */
    public static function init() {
        $argv = func_get_args();
        if (func_num_args() < 3 || !in_array($argv[0], self::$_log_class) || !@isset(SPP_Tools_Log_Abstract::$log_level_name[$argv[1]])) {
            return false;
        }
        if (!is_null(self::$_logger)) {
            return true;
        }
        try {
            $class = new ReflectionClass($argv[0]);
            switch ($argv[0]) {
                case self::LOG_FILE:
                    self::$_logger = @$class->newInstanceArgs(array_slice($argv, 2, 3));
                    self::$_logger->set_log_level($argv[1]);
                    defined('APP') ? self::$_logger->set_app_name(APP) : null;
                    break;
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 是否完成日志初始化
     */
    public static function is_init() {
        return !is_null(self::$_logger);
    }

    public static function intermediateOn() {
        return (self::$_intermediate = true);
    }

    public static function intermediateOff() {
        return (self::$_intermediate = false);
    }
}
