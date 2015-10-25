<?php
/**
 * MySQL 的数据库抽象层，优化性能，使用了 Singleton 设计模式
 */
class SPP_MySqliCommand extends SPP_SqlCommand {
    /**
     * 默认数据库端口
     */
    const DB_PORT = 3306;
    
    /**
     * 返回结果类型
     *
     * @var array
     */
    private $mResultType = array('assoc' => MYSQL_ASSOC,'num' => MYSQL_NUM,'both' => MYSQL_BOTH );
    /**
     * 获取字段属性
     * 
     * @param array $pArray            
     * @return array
     */
    private function getFieldAttr($pArray) {
        $type = "";
        $length = 0;
        $flags = array();
        preg_match("/(.*)\((\d*)\)(.*)/", $pArray['Type'], $match);
        if (isset($match[2])) {
            $length = intval($match[2]);
            $type = trim($match[1]);
            if (isset($match[3]) && ! empty($match[3])) {
                $flags[] = trim($match[3]);
            }
        } else {
            preg_match("/(.*)\((.*)\)/", $pArray['Type'], $match);
            if (isset($match[1])) {
                $type = $match[1];
                if ('enum' == $type || 'set' == $type) {
                    $length = 1;
                    $temp = explode(',', $match[2]);
                    for($i = 0; $i < count($temp); $i ++) {
                        $temp[$i] = trim($temp[$i], "\"'");
                        $tLength = strlen($temp[$i]);
                        if ($tLength > $length) {
                            $length = $tLength;
                        }
                    }
                }
            } else {
                $type = $pArray['Type'];
            }
        }
        if ('text' == $type) {
            $length = 65535;
        } else if ('longblob' == $type || 'longtext' == $type) {
            $length = 4294967295;
        } else if ('mediumblob' == $type || 'mediumtext' == $type) {
            $length = 16777215;
        } else if ('tinyblog' == $type || 'tinytext' == $type) {
            $length = 255;
        } else if ('datetime' == $type) {
            $length = 19;
        } else if ('timestamp' == $type) {
            $length = 19;
        } else if ('date' == $type) {
            $length = 10;
            $flags[] = 'binary';
        }
        
        if (in_array($type, array('text','longblob','mediumblob','tinyblog','tinytext','mediumtext' ))) {
            $flags[] = $type;
            $type = 'blob';
        } else if (in_array($type, array('bit' ))) {
            $flags[] = $type;
            $type = 'int';
        } else if (in_array($type, array('enum','char','varchar','set','binary','varbinary' ))) {
            $flags[] = $type;
            $type = 'string';
        } else if (in_array($type, array('timestamp' ))) {
            $flags[] = 'binary';
        } else if (in_array($type, array('year' ))) {
            $flags[] = "unsigned";
            $flags[] = "zerofill";
        } else if (in_array($type, array('smallint','tinyint','bigint' ))) {
            $flags[] = $type;
            $type = 'int';
        }
        if ('NO' == $pArray['Null']) {
            $flags[] = 'not_null';
        }
        if ('PRI' == $pArray['Key']) {
            $flags[] = 'primary_key';
        } else if ('MUL' == $pArray['Key']) {
            $flags[] = 'multiple_key';
        } else if ('UNI' == $pArray['Key']) {
            $flags[] = 'unique_key';
        }
        if (! empty($pArray['Extra'])) {
            $flags[] = $pArray['Extra'];
        }
        // @TODO 未处理类型decimal,float,double,point等
        return array('name' => $pArray['Field'],'type' => $type,'length' => $length,'flags' => $flags );
    }
    
    /**
     * 获取表字段的Hash数据
     *
     * @param string $pTable            
     * @return array
     */
    function getTableFieldHash($pTable) {
        $this->connectDB();
        $tSql = "SHOW COLUMNS FROM `{$pTable}`;";
        $tList = mysqli_query($this->mLink, $tSql);
        $return = array();
        while(($row = $tList->fetch_assoc()) !== null) {
            $return[$row['Field']] = $this->getFieldAttr($row);
        }
        return $return;
    }
    
    /**
     * 获取主键
     *
     * @param string $pTable            
     * @return array
     */
    function getTablePrimeKey($pTable) {
        $this->connectDB();
        $tSql = "SHOW COLUMNS FROM `{$pTable}`;";
        $tList = mysqli_query($this->mLink, $tSql);
        $return = array();
        while(($row = $tList->fetch_assoc()) !== null) {
            if ('PRI' == $row['Key'])
                $return[] = $row['Field'];
        }
        return $return;
    }
    
    /**
     * 链接数据库
     *
     * @return object
     */
    function db_connect() {
        //$connection = mysqli_connect("{$this->mDatabaseHost}:{$this->mDatabasePort}", $this->mDatabaseUsername, $this->mDatabasePassword);
        $connection = mysqli_connect($this->mDatabaseHost, $this->mDatabaseUsername, $this->mDatabasePassword, $this->mDatabaseName, $this->mDatabasePort);
        
        if (! $this->isResource($connection)) {
            die('Data Connect Error');
        }
        // mysql_select_db($this->mDatabaseName, $connection);
        //$connection->select_db($this->mDatabaseName);
        mysqli_query($connection, "SET NAMES " . $this->mCharset);
        return $connection;
    }
    public function db_num_rows($pResult) {
        return mysql_num_rows($pResult);
    }
    public function db_fetch_array($pResult, $pResultType = 'both') {
        return mysqli_fetch_array($pResult, $this->mResultType[$pResultType]);
    }
    public function db_free_result($pResult) {
        return mysqli_free_result($pResult);
    }
    
    /**
     * 执行SQL语句
     *
     * @param string $pQuery            
     * @return mix
     */
    public function db_query($pQuery) {
        SPP_Tools_Logger::info('db_query:%s', $pQuery);
        if (count(SPP_SqlCommand::$theInstances) > 1) {
            mysqli_select_db($this->mLink, $this->mDatabaseName);
        }
        $return = mysqli_query($this->mLink, $pQuery);
        if (mysqli_errno($this->mLink)) {
            throw new Exception("DataBase Error: {$pQuery}");
        }
        return $return;
    }
    public function db_close() {
        return @mysqli_close($this->mLink);
    }
    public function db_insert_id() {
        return mysqli_insert_id($this->mLink);
    }
    public function db_affected_rows($pResult) {
        return mysqli_affected_rows($this->mLink);
    }
    public function db_data_seek($result_identifier, $row_number) {
        return mysqli_data_seek($result_identifier, $row_number);
    }
    function PreparePagedArrayQuery($sql, $pPageNo = 0, $pPageSize = 10) {
        $sql .= " limit " . (($pPageNo - 1) * $pPageSize) . "," . $pPageSize;
        return $this->ExecuteQuery($sql);
    }
    /**
     * 判断数据库连接是否有效
     * 
     * @param Object $pLink            
     * @return boolean
     */
    public function isResource($pLink) {
        if ($pLink) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 函数转义 SQL 语句中使用的字符串中的特殊字符
     * 
     * @param string $tValue            
     * @return string
     */
    public function escapeString($tValue) {
        if (! $this->isResource($this->mLink)) {
            $this->connectDB();
            //$this->mLink = $this->db_connect();
        }
        //$this->connectDB();
        //$this->mLink = $this->db_connect();
        var_dump("'" . $this->mLink->real_escape_string($tValue) . "'");
        //return "'" . mysqli_real_escape_string($this->mLink, $tValue) . "'";
        return "'" . $this->mLink->real_escape_string($tValue) . "'";
        
    }
}
?>
