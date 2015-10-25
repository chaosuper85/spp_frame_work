<?php
/**
 * 通用数据库抽象层，优化性能，使用了 Singleton 设计模式
 */
abstract class SPP_SqlCommand extends SPP_Object {
    /**
     *
     * @var Object/resource 数据库连接对象或资源
     */
    public $mLink;
    /**
     *
     * @var string 数据库HOST
     */
    public $mDatabaseHost;
    /**
     *
     * @var string 数据库连接用户名
     */
    public $mDatabaseUsername;
    /**
     *
     * @var string 数据库密码
     */
    public $mDatabasePassword;
    /**
     *
     * @var string 数据库库名
     */
    public $mDatabaseName;
    /**
     *
     * @var int 数据库连接端口
     */
    public $mDatabasePort;
    /**
     *
     * @var string 数据库编码
     */
    public $mCharset = 'UTF8';
    /**
     *
     * @var array 查询数组
     */
    private $mQueryArray = array();
    
    /**
     *
     * @var array 数据库实例
     */
    static $theInstances = array();
    
    /**
     * 返回一个数据库对象
     *
     * @param int $pDBConfig            
     * @return SqlCommand
     */
    static function &getInstance($pDBConfig = 0) {
        if (empty(SPP_SqlCommand::$theInstances[$pDBConfig])) {
            $tDbConfigs = SPP_Config_Config::$mDataBases;
            $tDbConfig = $tDbConfigs[$pDBConfig];
            $class_name = 'SPP_' . $tDbConfig->type . 'Command';
            $tDriver = '';
            if (isset($tDbConfig->driver)) {
                $tDriver = $tDbConfig->driver;
            }
            if ('MySql' == $tDbConfig->type && 'mysqli' == $tDriver) {
                $class_name = 'SPP_MySqliCommand';
            }
            if (! class_exists($class_name)) {
                die('不支持该数据库类型：' . $class_name . '!支持类型:MySql');
            }
            SPP_Tools_Logger::warn("class_name:%s",$class_name);
            self::$theInstances[$pDBConfig] = new $class_name();
            self::$theInstances[$pDBConfig]->resetDB($tDbConfigs[$pDBConfig]);
            self::$theInstances[$pDBConfig]->connectDB();
        }
        SPP_Tools_Logger::warn("pDBConfig:%s",$pDBConfig);
        return self::$theInstances[$pDBConfig];
    }
    /**
     * 构造函数
     *
     * @param number $pDBConfig            
     */
    function __construct($pDBConfig = 0) {
        parent::__construct();
    }
    function __destruct() {
        $this->closeDBForce();
        parent::__destruct();
    }
    /**
     * 重置数据库连接数据
     *
     * @param number $pDBConfig            
     */
    function resetDB($pDBConfig) {
        $this->mDatabaseHost = $pDBConfig->host;
        $this->mDatabaseUsername = $pDBConfig->username;
        $this->mDatabasePassword = $pDBConfig->password;
        $this->mDatabaseName = $pDBConfig->database;
        $this->mDatabasePort = $pDBConfig->port;
        if (isset($pDBConfig->charset)) {
            $this->mCharset = $pDBConfig->charset;
        }
        
        $this->closeDBForce();
    }
    /**
     * 连接数据库
     */
    function connectDB() {
        if (! $this->isResource($this->mLink)) {
            $this->mLink = $this->db_connect();
        }
    }
    /**
     * 关闭数据库连接
     */
    function closeDBForce() {
        if ($this->isResource($this->mLink)) {
            $this->db_close();
        }
    }
    
    /**
     * 执行没有返回结果的SQL
     *
     * @param string $sql            
     * @return bool
     */
    function ExecuteNonQuery($sql) {
        return $this->ExecuteQuery($sql);
    }
    
    /**
     * 执行插入SQL
     *
     * @param string $sql            
     * @return int
     */
    function ExecuteInsertQuery($sql) {
        $result = $this->ExecuteQuery($sql);
        if ($result) {
            if ($this->db_affected_rows($result) > 0) {
                return $this->db_insert_id();
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    /**
     * 执行数量查询
     *
     * @param string $pSql            
     * @return number
     */
    function ExecuteCountQuery($pSql) {
        $tCountSql = preg_replace("/select.+?\sfrom\s/i", "SELECT count(*) FROM ", $pSql);
        return $this->ExecuteScalar($tCountSql);
    }
    
    /**
     * 从结果集中取得全部数据
     *
     * @param Object $result            
     * @param string $pResultType            
     * @return multitype:NULL
     */
    function db_fetch_all($result, $pResultType) {
        $return = array();
        while($return[] = $this->db_fetch_array($result, $pResultType)) {
        }
        $this->db_free_result($result);
        array_pop($return);
        return $return;
    }
    
    /**
     * 执行查询,返回结果为数组
     *
     * @param string $sql            
     * @param number $pPageNo            
     * @param number $pPageSize            
     * @param string $pResultType            
     * @return multitype:NULL
     */
    function ExecuteArrayQuery($sql, $pPageNo = 0, $pPageSize = 10, $pResultType = 'both') {
        if ($pPageNo > 0) {
            $result = $this->PreparePagedArrayQuery($sql, $pPageNo, $pPageSize, $pResultType);
        } else {
            $result = $this->ExecuteQuery($sql);
        }
        return $this->db_fetch_all($result, $pResultType);
    }
    /**
     * 执行查询,返回结果为标量
     *
     * @param string $sql            
     * @return int
     */
    function ExecuteScalar($sql) {
        $result = $this->ExecuteQuery($sql);
        $return = 0;
        
        if ($row = $this->db_fetch_array($result, 'num')) {
            $return = $row[0];
        }
        $this->db_free_result($result);
        return $return;
    }
    
    /**
     * 执行SQL查询
     *
     * @param string $pQuery            
     * @return mix
     */
    function ExecuteQuery($pQuery) {
        $this->connectDB();
        if ('debug' == SPP_Config_Config::getMode()) {
            SPP_Util_Debug::setDebugInfo(array($this->mUniqueObjectID,get_class($this),'SQLQuery',$pQuery ));
        }
        return $this->db_query($pQuery);
    }
    /**
     * 获取表相关数据
     *
     * @param string $pTable            
     */
    public abstract function getTableFieldHash($pTable);
    /**
     * 获取主键
     *
     * @param string $pTable            
     */
    public abstract function getTablePrimeKey($pTable);
    /**
     * 获取上一次插入数据库的id
     */
    public abstract function db_insert_id();
    /**
     * SQL语句影响记录条数
     *
     * @param Object $pResult            
     * @return number
     */
    public abstract function db_affected_rows($pResult);
    public abstract function db_num_rows($pResult);
    public abstract function db_fetch_array($pResult, $pResultType = 'both');
    public abstract function db_free_result($pResult);
    
    /**
     * 执行SQL语句
     *
     * @param string $pQuery            
     * @return mix
     */
    public abstract function db_query($pQuery);
    /**
     * 连接数据库
     */
    public abstract function db_connect();
    /**
     * 关闭数据库连接
     */
    public abstract function db_close();
    public abstract function db_data_seek($result_identifier, $row_number);
    /**
     * 准备分页查询
     *
     * @param string $sql            
     * @param number $pPageNo            
     * @param number $pPageSize            
     */
    public abstract function PreparePagedArrayQuery($sql, $pPageNo = 0, $pPageSize = 10);
    /**
     * 判断连接是否有效
     *
     * @param Object/Resource $pLink            
     */
    public abstract function isResource($pLink);
    public abstract function escapeString($pValue);
}
