<?php

class SPP_Tools_Log_File extends SPP_Tools_Log_Abstract {
    
    const
        /**
         * 默认日志名
         */
        DEFAULT_LOG_NAME   = 'log',
        /**
         * 默认日志路径
         */
        DEFAULT_LOG_PATH   = '/tmp',
        /**
         * 日志目录权限
         */
        LOG_DIR_MODE       = 0755,
        /**
         * 日志字段格式
         */
        LOG_FIELD_FORMAT   = "[%s]",
        /**
         * 单一日志文件最大数量
         */
        LOG_FILE_MAX_COUNT = 100,
        /**
         * 单一日志文件最大文件大小
         */
        LOG_FILE_MAX_SIZE  = 209715200,
        /**
         * 日志文件权限
         */
        LOG_FILE_MODE      = 0666,
        /**
         * 默认日志扩展名
         */
        LOG_FILE_SUFFIX    = '.log',
        /**
         * 日志文件名分隔符
         */
        LOG_FILE_SEPARATE  = "_",
        /**
         * 日志名分隔符
         */
        LOG_NAME_SEPARATE  = ".",
        /**
         * 日志换行符
         */
        LOG_NEW_LINE       = "\n",
        /**
         * 日志路径分隔符
         */
        LOG_PATH_SEPARATE  = "/",
        /**
         * 日志切分用空格
         */
        LOG_SPACE          = "",
        /**
         * 日志文件名按时间分割格式，yymmdd和yymmddhh两种
         */
        LOG_SPLIT_DAY      = "ymd",
        LOG_SPLIT_HOUR     = "ymdH",
        /**
         * 日志名中需要过滤的特殊字符
         */
        SEPARATE_CHARS     = "./",

        /**
         * 日志区分wf的级别数值
         */

        LOG_WF_VALUE = 3,

        /**
         * 日志wf后缀名
         */
        LOG_WF_SUFFIX = ".wf";

    /**
     * 默认日志名
     */
    private $_default_log_name     = self::DEFAULT_LOG_NAME;
    /**
     * 日志文件句柄缓存
     */
    private $_log_file_handler     = array();
    /**
     * 日志文件路径缓存
     */
    private $_log_file_path        = array();
    /**
     * 日志路径
     */
    private $_log_path             = self::DEFAULT_LOG_PATH;
    /**
     * 日志文件名按时间分割格式
     */
    private $_log_split            = self::LOG_SPLIT_DAY;
    private static $_log_split_set = array(
        self::LOG_SPLIT_DAY,
        self::LOG_SPLIT_HOUR,
    );

    /**
     * 构造函数，设置日志路径、日志文件名按时间分割格式和默认日志名
     * 
     * @param string $log_path
     * @param string $log_split date函数可处理的format字符串
     * @param string $default_log_name
     */
    public function __construct($log_path = self::DEFAULT_LOG_PATH, $log_split = self::LOG_SPLIT_DAY, $default_log_name = self::DEFAULT_LOG_NAME) {
        parent::__construct();
        $this->set_log_path($log_path);
        $this->set_log_split($log_split);
        $this->set_default_log_name($default_log_name);
    }

    /**
     * 析构函数，将文件句柄关闭
     */
    public function __destruct() {
        parent::__destruct();
        $this->_reset_log_file_handler();
    }

    /**
     * 格式化日志内容
     * 
     * @param array $basic_fields
     * @param string $format
     * @param array $args
     * @return string
     */
    private function _format_content($basic_fields, $format, $args) {
        $content = "";
        $back_trace = "";
        foreach ($basic_fields as $field => $v) {
            if ($field == SPP_Tools_Log_Abstract::BF_EXCEPTION) {
                continue;
            }
            if ($field != SPP_Tools_Log_Abstract::BF_BACK_TRACE) {
                $content .= $this->_format_field($v);
            } else {
                $back_trace = $v;
            }
        }
        empty($back_trace) ? $back_trace = self::LOG_NEW_LINE : null;
        return ($content .= " " . str_replace(array("\n", "\t"), array("", " "), trim(@vsprintf($format, $args))) . $back_trace . self::LOG_NEW_LINE);
    }

    /**
     * 格式化日志字段，非标量用json_encode处理
     * 
     * @param mixed $field
     * @return string
     */
    private function _format_field($field) {
        $value = "";
        if (is_scalar($field)) {
            $value = $field;
        } else {
            false !== ($value = @json_encode($field)) ? null : $value = "Error:" . @json_last_error() . ", " . @json_last_error_msg();
        }
        return sprintf(self::LOG_FIELD_FORMAT, self::LOG_SPACE . $value . self::LOG_SPACE);
    }

    /**
     * 格式化日志文件名，根据日志文件名按时间分割格式
     * 
     * @param string $log_name
     * @return string
     */
    private function _format_file($log_name) {
        return $log_name . self::LOG_FILE_SEPARATE . date($this->_log_split) . self::LOG_FILE_SUFFIX;
    }

    /**
     * 格式化日志路径和文件名
     * 
     * @param mixed $log_name
     * @return array
     */
    private function _format_path($log_name) {
        $log_name = str_replace(self::LOG_NAME_SEPARATE, self::LOG_PATH_SEPARATE, trim($log_name, self::SEPARATE_CHARS));
        $log_path = $this->_log_path;
        $log_file = $this->_default_log_name;
        if (!empty($log_name)) {
            $items = explode(self::LOG_PATH_SEPARATE, $log_name);
            array_unshift($items, $log_path);
            $log_path = implode(self::LOG_PATH_SEPARATE, array_slice($items, 0, -1));
            $items = array_slice($items, -1);
            $log_file = array_shift($items);
        }
        return array($log_path, $this->_format_file($log_file));
    }

    /**
     * 判断日志文件是否有效
     * 
     * @param string $filename
     * @return boolean
     */
    private function _is_valid_file($filename) {
        return (@file_exists($filename) && @is_writable($filename));
    }

    /**
     * 获取日志文件句柄，如果日志文件及目录不存在，则根据规则进行生成
     * 
     * @param mixed $log_name
     * @return mixed
     */
    private function _open_file($log_name) {
        if (isset($this->_log_file_handler[$log_name])) {
            return $this->_log_file_handler[$log_name];
        }
        list($log_path, $log_file) = $this->_format_path($log_name);
        if (!@file_exists($log_path) && !@mkdir($log_path, self::LOG_DIR_MODE, true)) {
            return false;
        }
        $filename = $log_path . self::LOG_PATH_SEPARATE . $log_file;
        if (!@file_exists($filename) && (!@touch($filename) || !@chmod($filename, self::LOG_FILE_MODE))) {
            return false;
        }
        $filename_wf = $filename . self::LOG_WF_SUFFIX;
        if (!@file_exists($filename_wf) && (!@touch($filename_wf) || !@chmod($filename_wf, self::LOG_FILE_MODE))) {
            return false;
        }
        $handler = @fopen($filename, 'a+');
        if (!@is_resource($handler)) {
            return false;
        }
        $handler_wf = @fopen($filename_wf, 'a+');
        if (!@is_resource($handler_wf)) {
            @fclose($handler);
            return false;
        }
        $this->_log_file_handler[$log_name] = array($handler, $handler_wf);
        $this->_log_file_path[$log_name]    = array($filename, $filename_wf);
        return array($handler, $handler_wf);
    }

    /**
     * 关闭所有日志文件句柄
     */
    private function _reset_log_file_handler() {
        foreach ($this->_log_file_handler as $key => $handler) {
            if (@is_resource($handler[0])) {
                @fclose($handler[0]);
            }
            if (@is_resource($handler[1])) {
                @fclose($handler[1]);
            }
            unset($this->_log_file_handler[$key]);
        }
    }

    /**
     * 将日志内容写入文件，会对单一文件大小和数量进行判断
     * 
     * @param integer $log_level
     * @param mixed $log_name
     * @param array $basic_fields
     * @param string $format
     * @param array $args
     * @return mixed 异常返回false，否则返回fwrite的返回值
     */
    protected function _write_log($log_level, $log_name, $basic_fields, $format, $args) {
        @clearstatcache();
        if (false === ($handler_array = $this->_open_file($log_name))) {
            return false;
        }
        if ($log_level > self::LOG_WF_VALUE) {
            $handler = $handler_array[0];
        } else {
            $handler = $handler_array[1];
        }
        if (!@flock($handler, LOCK_EX | LOCK_NB)) {
            return false;
        }
        $filename_array = $this->_log_file_path[$log_name];
        if ($log_level > self::LOG_WF_VALUE) {
            $filename = $filename_array[0];
        } else {
            $filename = $filename_array[1];
        }
        if (@filesize($filename) >= self::LOG_FILE_MAX_SIZE) {
            for ($i = 1; $i < self::LOG_FILE_MAX_COUNT; $i++) {
                if (!@is_file($filename . '.' . $i)) {
                    break;
                }
            }
            @copy($filename, $filename . '.' . $i) ? @ftruncate($handler, 0) : null;
        }
        $ret = @fwrite($handler, $this->_format_content($basic_fields, $format, $args));
        @flock($handler, LOCK_UN);
        return $ret;
    }

    /**
     * 获取默认日志名
     * 
     * @return string
     */
    public function get_default_log_name() {
        return $this->_default_log_name;
    }

    /**
     * 获取日志路径
     * 
     * @return string
     */
    public function get_log_path() {
        return $this->_log_path;
    }

    /**
     * 获取日志文件名按时间分割格式
     * 
     * @return string
     */
    public function get_log_split() {
        return $this->_log_split;
    }

    /**
     * 设置应用名称，并根据应用名称设置日志路径
     * 
     * @param string $app_name
     * @return boolean
     */
    public function set_app_name($app_name) {
        $app_name = str_replace(self::LOG_NAME_SEPARATE, self::LOG_PATH_SEPARATE, trim($app_name, self::SEPARATE_CHARS));
        if (empty($app_name)) {
            return false;
        }
        parent::set_app_name($app_name);
        if ($this->_is_valid_file($this->_log_path)) {
            $log_path = $this->_log_path . self::LOG_PATH_SEPARATE . $app_name;
            if (!@file_exists($log_path) && !@mkdir($log_path, self::LOG_DIR_MODE, true)) {
                return false;
            }
            $this->set_log_path($log_path);
            return true;
        }
        return false;
    }

    /**
     * 设置默认日志名
     * 
     * @return mixed
     */
    public function set_default_log_name($default_log_name) {
        $default_log_name = str_replace(self::LOG_NAME_SEPARATE, self::LOG_PATH_SEPARATE, trim($default_log_name, self::SEPARATE_CHARS));
        if (!empty($default_log_name)) {
            $this->_default_log_name = $default_log_name;
            return $this->_default_log_name;
        }
        return false;
    }

    /**
     * 设置日志路径
     * 
     * @param string $log_path
     * @return mixed
     */
    public function set_log_path($log_path) {
        if ($this->_is_valid_file($log_path)) {
            $this->_log_path = $log_path;
            $this->_reset_log_file_handler();
            return $this->_log_path;
        }
        return false;
    }

    /**
     * 设置日志文件名按时间分割格式
     * 
     * @param string $log_split
     * @return mixed
     */
    public function set_log_split($log_split) {
        if (in_array($log_split, self::$_log_split_set)) {
            if ($this->_log_split != $log_split) {
                $this->_reset_log_file_handler();
            }
            $this->_log_split = $log_split;
            return $this->_log_split;
        }
        return false;
    }

}
