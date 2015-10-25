<?php

abstract class SPP_Tools_Log_Abstract {

    /**
     * 基础日志字段，无特殊说明表示默认所有状态使用
     * 
     * app_name，应用名称
     * back_trace，debug_backtrace的返回，默认debug和fatal时使用
     * code_block，代码段，默认debug时使用
     * code_line，打日志的位置
     * exec_time，从开始到当前日志时的执行时间，单位毫秒(ms)
     * log_id，唯一id用于跟踪问题
     * log_level，日志级别的字符串表示
     * memory，内存使用情况，默认debug和fatal时使用
     * method，HTTP请求的方式
     * pid，PHP脚本进程id
     * req_ip，用户发请求时的ip
     * req_vars，$_REQUEST的内容，默认debug和fatal时使用
     * server_host，接收请求的服务器名
     * server_ip，接收请求的服务器ip
     * sessions，$_SESSION的内容，默认debug和fatal时使用
     * step_time，上次日志到这次日志的执行时间，单位毫秒(ms)
     * timestamp，当前日志的时间戳，带时区和微秒
     * uri，请求的REQUEST_URI
     */
    const
        BF_APP_NAME    = 'app_name',
        BF_BACK_TRACE  = 'back_trace',
        BF_CODE_BLOCK  = 'code_block',
        BF_CODE_LINE   = 'code_line',
        BF_EXCEPTION   = 'exception',
        BF_EXEC_TIME   = 'exec_time',
        BF_LOG_ID      = 'log_id',
        BF_LOG_LEVEL   = 'log_level',
        BF_MEMORY      = 'memory',
        BF_METHOD      = 'method',
        BF_PROCESS_ID  = 'pid',
        BF_REQ_IP      = 'req_ip',
        BF_REQ_VARS    = 'req_vars',
        BF_SERVER_HOST = 'server_host',
        BF_SERVER_IP   = 'server_ip',
        BF_SESSION     = 'sessions',
        BF_STEP_TIME   = 'step_time',
        BF_TIMESTAMP   = 'timestamp',
        BF_URI         = 'uri';

    /**
     * 日志级别
     */
    const
        LL_FATAL   = 1, //error is a alias of fatal
        LL_WARNING = 2,
        LL_NOTICE  = 4,
        LL_TRACE   = 8, //info is a alias of trace
        LL_DEBUG   = 16;

    /**
     * 应用名称
     */
    private $_app_name = "";
    /**
     * 基础日志字段的获取方式
     * 
     * 第一个字段表示callable的callback
     * 第二个字段表示是否需要静态化，如果为true表示第一次初始化后，以结果值的形式静态化，如果为false表示每次输出日志都会按照callback的返回动态获取
     * 第三个字段表示传入callback的参数
     */
    private $_basic_fields = array(
        self::BF_APP_NAME    => array(array('SPP_Tools_Log_Abstract', 'get_app_name'),    false, array()),
        self::BF_BACK_TRACE  => array(array('SPP_Tools_Log_Abstract', '_bf_back_trace'),  false, array()),
        self::BF_CODE_BLOCK  => array(array('SPP_Tools_Log_Abstract', '_bf_code_block'),  false, array()),
        self::BF_CODE_LINE   => array(array('SPP_Tools_Log_Abstract', '_bf_code_line'),   false, array()),
        self::BF_EXEC_TIME   => array(array('SPP_Tools_Log_Abstract', '_bf_exec_time'),   false, array()),
        self::BF_LOG_ID      => array(array('SPP_Tools_Log_Abstract', '_bf_log_id'),      true,  array()),
        self::BF_LOG_LEVEL   => array(array('SPP_Tools_Log_Abstract', '_bf_log_level'),   false, array()),
        self::BF_MEMORY      => array(array('SPP_Tools_Log_Abstract', '_bf_memory'),      false, array()),
        self::BF_METHOD      => array(array('SPP_Tools_Log_Abstract', '_bf_method'),      true,  array()),
        self::BF_PROCESS_ID  => array(array('SPP_Tools_Log_Abstract', '_bf_pid'),         true,  array()),
        self::BF_REQ_IP      => array(array('SPP_Tools_Log_Abstract', '_bf_req_ip'),      true,  array()),
        self::BF_REQ_VARS    => array(array('SPP_Tools_Log_Abstract', '_bf_req_vars'),    true,  array()),
        self::BF_SERVER_HOST => array(array('SPP_Tools_Log_Abstract', '_bf_server_host'), true,  array()),
        self::BF_SERVER_IP   => array(array('SPP_Tools_Log_Abstract', '_bf_server_ip'),   true,  array()),
        self::BF_SESSION     => array(array('SPP_Tools_Log_Abstract', '_bf_sessions'),    true,  array()),
        self::BF_STEP_TIME   => array(array('SPP_Tools_Log_Abstract', '_bf_step_time'),   false, array()),
        self::BF_TIMESTAMP   => array(array('SPP_Tools_Log_Abstract', '_bf_timestamp'),   false, array()),
        self::BF_URI         => array(array('SPP_Tools_Log_Abstract', '_bf_uri'),         true,  array()),
    );

    /**
     * 基础日志字段的输出序列
     */
    private static $_basic_sequence = array(
        self::BF_APP_NAME,
        self::BF_LOG_LEVEL,
        self::BF_TIMESTAMP,
        self::BF_SERVER_IP,
        self::BF_SERVER_HOST,
        self::BF_REQ_IP,
        self::BF_PROCESS_ID,
        self::BF_LOG_ID,
        self::BF_METHOD,
        self::BF_URI,
        self::BF_EXEC_TIME,
        self::BF_STEP_TIME,
        self::BF_CODE_LINE,
    );

    /**
     * 输出控制日志级别
     * 
     * 默认为trace，当待输出日志级别大于该值则不输出
     */
    private $_log_level = self::LL_TRACE;
    /**
     * 日志级别的字符串表示
     */
    public static $log_level_name = array(
        self::LL_FATAL   => 'FATAL',
        self::LL_WARNING => 'WARNING',
        self::LL_NOTICE  => 'NOTICE',
        self::LL_TRACE   => 'TRACE',
        self::LL_DEBUG   => 'DEBUG',
    );

    /**
     * 当前待输出日志级别，默认为trace
     */
    private $_output_log_level = self::LL_TRACE;
    /**
     * 各个日志级别的日志字段输出序列
     */
    private $_output_sequences = array(
        self::LL_FATAL => array(
            self::BF_MEMORY,
            self::BF_REQ_VARS,
            self::BF_SESSION,
            self::BF_BACK_TRACE,
        ),
        self::LL_WARNING => array(),
        self::LL_NOTICE => array(),
        self::LL_TRACE => array(),
        self::LL_DEBUG => array(
            self::BF_MEMORY,
            self::BF_REQ_VARS,
            self::BF_SESSION,
            self::BF_BACK_TRACE,
            //self::BF_CODE_BLOCK,
        ),
    );

    /**
     * 上一次记录日志的时间点，带微秒的float
     */
    private $_timestamp_last  = 0;
    /**
     * 当前记录日志的时间点，带微秒的float
     */
    private $_timestamp_now   = 0;
    /**
     * 开始记录日志的时间点，带微秒的float
     */
    private $_timestamp_start = 0;

    public function __construct() {
        $this->_timestamp_last = $this->_timestamp_now = $this->_timestamp_start = isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : implode('.', $this->_get_timestamp(true));
        $this->_init_basic_fields();
        $this->_init_output_sequence();
    }

    public function __destruct() {
        
    }

    /**
     * 将前三个trace信息弹出，日志记录的调用信息，无用
     * 
     * @return array
     */
    private function _bf_back_trace() {
        if (!isset($this->_basic_fields[self::BF_EXCEPTION])) {
            $traces = array_slice(debug_backtrace(), 6);
        } else {
            if (is_array($this->_basic_fields[self::BF_EXCEPTION])) {
                $traces = $this->_basic_fields[self::BF_EXCEPTION];
            } else {
                return $this->_basic_fields[self::BF_EXCEPTION];
            }
        }
        $content = "\n";
        foreach ($traces as $k => $trace) {
            if (is_array($trace)) {
                $args = array();
                if (isset($trace['args'])) {
                    foreach ($trace['args'] as $arg) {
                        if (is_bool($arg)) {
                            $args[] = $arg ? "true" : "false";
                        } else if (is_numeric($arg)) {
                            $args[] = $arg;
                        } else if (is_string($arg)) {
                            $args[] = '"' . str_replace(array("\n", "\t"), array("", " "), trim($arg)) . '"';
                        } else if (is_scalar($arg)) {
                            $args[] = $arg;
                        } else {
                            $args[] = json_encode($arg);
                        }
                    }
                }
                $content .= "#$k "
                        . (isset($trace['file']) ? $trace['file'] : 'nofile')
                        .'(' . (isset($trace['line']) ? $trace['line'] : 'noline') . '): '
                        . (isset($trace['class']) ? $trace['class'] : '')
                        . (isset($trace['type']) ? $trace['type'] : '')
                        . (isset($trace['function']) ? $trace['function'] : '')
                        . '(' . implode(', ', $args) . ')'
                        . "\n";
            }
        }
        return $content;
    }

    /**
     * 打日志的前后3行代码
     * 
     * @return string
     */
    private function _bf_code_block() {
        $trace = array_slice(debug_backtrace(), 5);
        $code_block = array();
        if(!isset($trace[0]['file']) || !isset($trace[0]['line']) || !is_readable(@$trace[0]['file']) || !$fp = fopen($trace[0]['file'], 'r')) {
            return $code_block;
        }
        $bline = intval(@$trace[0]['line']) - 3;
        $eline = intval(@$trace[0]['line']) + 3;
        $line = 0;
        while(($row = fgets($fp)))
        {
            if(++$line > $eline) {
                break;
            }
            if($line < $bline) {
                continue;
            }
            if ($line == intval(@$trace[0]['line'])) {
                $line = ">>>$line";
            }
            $code_block[] = "$line: " . rtrim($row);
        }
        fclose($fp);
        return "\n" . implode("\n", $code_block);
    }

    /**
     * 打日志的位置，格式是文件名:行数
     * 
     * @return string
     */
    private function _bf_code_line() {
        $trace = array_slice(debug_backtrace(), 5);
        foreach ($trace as $v) {
            if (isset($v['file']) && isset($v['line'])) {
                return sprintf("%s:%s", $v['file'], $v['line']);
            }
        }
        return "";
    }

    /**
     * 到当前日志位置的执行时间
     * 
     * @return string
     */
    private function _bf_exec_time() {
        return sprintf("%s", 1000 * (floatval($this->_timestamp_now) - floatval($this->_timestamp_start)));
    }

    /**
     * 每次请求生成的log_id，便于跟进一次请求产生的日志信息
     * 
     * @return string
     */
    private function _bf_log_id() {
        return defined('LOG_ID') ? LOG_ID : md5(uniqid(mt_rand(), true));
    }

    /**
     * 日志级别
     * 
     * @return string
     */
    private function _bf_log_level() {
        return isset(self::$log_level_name[$this->_output_log_level]) ? self::$log_level_name[$this->_output_log_level] : self::$log_level_name[self::LL_TRACE];
    }

    /**
     * 如果有系统方法就使用，没有就使用系统命令
     * 
     * @return integer
     */
    private function _bf_memory() {
        if (function_exists('memory_get_usage')) {
            $memory = memory_get_usage();
        } else {
            $output = array();
            if (strncmp(PHP_OS, 'WIN', 3) === 0) {
                exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
                $memory = isset($output[5]) ? preg_replace('/[\D]/', '', $output[5]) * 1024 : 0;
            } else {
                $pid = getmypid();
                exec("ps -eo%mem,rss,pid | grep $pid", $output);
                $output = explode("  ", $output[0]);
                $memory = isset($output[1]) ? $output[1] * 1024 : 0;
            }
        }
        $unit = array('b','kb','mb','gb','tb','pb');
        return @round($memory/pow(1024,($i = floor(log($memory,1024)))),2) . $unit[$i];
    }

    /**
     * http请求的方法
     * 
     * @return string
     */
    private function _bf_method() {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";
    }

    /**
     * 进程id
     * 
     * @return integer
     */
    private function _bf_pid() {
        return intval(getmypid());
    }

    /**
     * 仅供参考，未必准确
     * 
     * @return string
     */
    private function _bf_req_ip() {
        $ips = array();
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ips[] = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = array_merge($ips, explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
	if (isset($_SERVER['REMOTE_ADDR'])) {
            $ips[] = $_SERVER['REMOTE_ADDR'];
        }
        foreach ($ips as $ip) {
            if (preg_match('/^[0-2]{0,1}[0-9]{0,1}[0-9]{1}\.[0-2]{0,1}[0-9]{0,1}[0-9]{1}\.[0-2]{0,1}[0-9]{0,1}[0-9]{1}\.[0-2]{0,1}[0-9]{0,1}[0-9]{1}$/', $ip)) {
                return $ip;
            }
        }
        return 'unknown';
    }

    /**
     * http请求数组内容
     * 
     * @return array
     */
    private function _bf_req_vars() {
        return empty($_REQUEST) ? "" : @json_encode($_REQUEST);
    }

    /**
     * 服务器名字
     * 
     * @return string
     */
    private function _bf_server_host() {
        return sprintf("%s", isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "");
    }

    /**
     * 服务器ip
     * 
     * @return string
     */
    private function _bf_server_ip() {
        return sprintf("%s", isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "");
    }

    /**
     * session信息
     * 
     * @return array
     */
    private function _bf_sessions() {
        return empty($_SESSION) ? "" : @json_encode($_SESSION);
    }

    /**
     * 从上次打日志到本次日志的执行时间
     * 
     * @return string
     */
    private function _bf_step_time() {
        $step_time = sprintf("%s", 1000 * (floatval($this->_timestamp_now) - floatval($this->_timestamp_last)));
        $this->_timestamp_last = $this->_timestamp_now;
        return $step_time;
    }

    /**
     * 格式为yyyy-mm-dd hh:mm:ss.微秒 时区
     * 
     * @return string
     */
    private function _bf_timestamp() {
        $timestamp = $this->_get_timestamp();
        $this->_timestamp_now = implode('.', $timestamp);
        return date("Y-m-d H:i:s", $timestamp[0]) . "." . str_pad($timestamp[1], 6, "0") . " " . date('O', $timestamp[0]);
    }

    /**
     * 请求的uri
     * 
     * @return string
     */
    private function _bf_uri() {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
    }

    /**
     * 根据日志级别及该日志级别对应的输出序列生成对应顺序的基本日志信息
     * 
     * @return mixed 当不存在对应日志级别输出序列或当前日志级别大于输出控制日志级别时返回false，否则返回基本日志信息数组
     */
    private function _gen_basic_fields($log_level) {
        if ($log_level > $this->_log_level || !isset($this->_output_sequences[$log_level])) {
            return false;
        }
        $basic_fields = array();
        $this->_output_log_level = $log_level;
        foreach ($this->_output_sequences[$log_level] as $field) {
            if (!isset($this->_basic_fields[$field])) {
                continue;
            }
            if (is_scalar($this->_basic_fields[$field])) {
                $basic_fields[$field] = $this->_basic_fields[$field];
            }
            if (is_array($this->_basic_fields[$field])) {
                $v = $this->_basic_fields[$field];
                $callable_name = array_shift($v);
                if (!is_callable($callable_name)) {
                    continue;
                }
                $need_static = array_shift($v);
                $args = array_shift($v);
                $basic_fields[$field] = @call_user_func_array($callable_name, $args);
            }
        }
        return $basic_fields;
    }

    /**
     * 获取时间戳，带微秒
     * 
     * @return array
     */
    private function _get_timestamp() {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr($usec, 2, 6);
        return array($sec, $usec);
    }

    /**
     * 初始化基本日志字段
     */
    private function _init_basic_fields() {
        foreach ($this->_basic_fields as $field => $v) {
            if (!is_array($v)) {
                continue;
            }
            $callable_name = array_shift($v);
            if (!is_callable($callable_name)) {
                continue;
            }
            $need_static = array_shift($v);
            if (!$need_static) {
                continue;
            }
            $args = array_shift($v);
            $this->_basic_fields[$field] = @call_user_func_array($callable_name, $args);
        }
    }

    /**
     * 初始化输出序列
     */
    private function _init_output_sequence() {
        foreach ($this->_output_sequences as $log_level => $sequence) {
            $this->_output_sequences[$log_level] = array_merge(self::$_basic_sequence, $sequence);
        }
    }

    /**
     * 日志输出函数，由子类实现，达到可向各种媒介输出日志的目的，由父类的write_log函数调用
     * 
     * @param integer $log_level
     * @param mixed $log_name
     * @param mixed $basic_fields
     * @param string $format
     * @param array $args
     * @return mixed
     */
    abstract protected function _write_log($log_level, $log_name, $basic_fields, $format, $args);

    /**
     * 获取应用名称
     * 
     * @return string
     */
    public function get_app_name() {
        return $this->_app_name;
    }

    /**
     * 获取基本日志信息，如果传递基本日志字段名，则返回对应信息，否则返回全部
     * 
     * @param mixed $field
     * @return string
     */
    public function get_basic_fields($field = null) {
        return (is_null($field) ? $this->_basic_fields : @$this->_basic_fields[$field]);
    }

    /**
     * 获取输出控制日志级别
     * 
     * @return integer
     */
    public function get_log_level() {
        return $this->_log_level;
    }

    /**
     * 获取日志输出序列，如果传递日志级别，则返回对应信息，否则返回全部
     * 
     * @param mixed $log_level
     * @return array
     */
    public function get_output_sequence($log_level = null) {
        return (is_null($log_level) ? $this->_output_sequences : @$this->_output_sequences[$log_level]);
    }

    /**
     * 设置应用名称
     * 
     * @param string $app_name
     * @return string
     */
    public function set_app_name($app_name) {
        return $this->_app_name = sprintf("%s", $app_name);
    }

    /**
     * 设置基本日志信息
     * 参数为一个或多个待设置基本日志信息组成的数组
     * 第一个字段表示callable的callback
     * 第二个字段表示是否需要静态化，如果为true则直接执行callback以结果值的形式静态化，结果值不能为数组，如果为false表示每次输出日志都会按照callback的返回动态获取
     * 第三个字段表示传入callback的参数
     * 
     * 如果是可用的基本日志信息，会自动加在当前所有日志输出序列的最后
     * 
     * @param array $fields
     * @return boolean
     */
    public function set_basic_fields($fields = array()) {
        if (!is_array($fields)) {
            return false;
        }
        $basic_fields = array();
        foreach ($fields as $field => $v) {
            if (is_scalar($v)) {
                $basic_fields[$field] = $v;
            }
            if (is_array($v) && !empty($v)) {
                $name = array_shift($v);
                $need_static = (bool)array_shift($v);
                $args = array_shift($v);
                if (is_callable($name, false, $callable_name)) {
                    $basic_fields[$field] = $need_static ? @call_user_func_array($callable_name, $args) : array($callable_name, $need_static, $args);
                }
            }
        }
        $keys = array_keys($basic_fields);
        foreach ($this->_output_sequences as $log_level => $sequence) {
            $this->_output_sequences[$log_level] = array_merge($sequence, $keys);
        }
        $this->_basic_fields = array_merge($this->_basic_fields, $basic_fields);
        return $this->_basic_fields;
    }

    /**
     * 设置输出控制日志级别
     * 
     * @param integer $log_level
     * @return mixed 日志级别非法返回null
     */
    public function set_log_level($log_level) {
        return (isset(self::$log_level_name[$log_level]) ? $this->_log_level = $log_level : null);
    }

    /**
     * 设置日志输出序列
     * 
     * @param array $output_sequence
     * @return mixed 当输入非法时返回false，否则返回设置后的所有日志级别的输出序列
     */
    public function set_output_sequence($output_sequence) {
        if (!is_array($output_sequence)) {
            return false;
        }
        foreach ($output_sequence as $log_level => $v) {
            if (!isset(self::$log_level_name[$log_level])) {
                continue;
            }
            if (!is_array($v)) {
                continue;
            }
            $this->_output_sequences[$log_level] = array_values($v);
        }
        return $this->_output_sequences;
    }

    /**
     * 日志输出，其中调用了子类实现的抽象方法
     * 
     * @param integer $log_level
     * @param mixed $log_name
     * @param string $format
     * @param array $args
     * @return mixed 如果生成基本日志信息失败，返回false，否则依赖于子类实现，建议子类在实现时，返回一些有意义的信息方便跟进
     */
    public function write_log($log_level, $log_name, $format, $args) {
        $basic_fields = $this->_gen_basic_fields($log_level);
        if (!$basic_fields) {
            return false;
        }
        return $this->_write_log($log_level, $log_name, $basic_fields, $format, $args);
    }

}
