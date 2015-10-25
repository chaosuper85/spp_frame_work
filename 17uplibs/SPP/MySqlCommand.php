<?php
/**
 * MySQL 的数据库抽象层，优化性能，使用了 Singleton 设计模式
 */
class SPP_MySqlCommand extends SPP_SqlCommand {
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
     * 获取表字段的Hash数据
     *
     * @param string $pTable            
     * @return array
     */
    function getTableFieldHash($pTable) {
        $this->connectDB();
        $fields = mysql_list_fields($this->mDatabaseName, $pTable, $this->mLink);
        $columns = mysql_num_fields($fields);
        for($i = 0; $i < $columns; $i ++) {
            $field_name = mysql_field_name($fields, $i);
            $return[$field_name]['name'] = $field_name;
            $return[$field_name]['type'] = mysql_field_type($fields, $i);
            $return[$field_name]['length'] = mysql_field_len($fields, $i);
            $return[$field_name]['flags'] = explode(" ", mysql_field_flags($fields, $i));
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
        $fields = mysql_list_fields($this->mDatabaseName, $pTable, $this->mLink);
        $columns = mysql_num_fields($fields);
        $return = array();
        for($i = 0; $i < $columns; $i ++) {
            $field_name = mysql_field_name($fields, $i);
            if (in_array("primary_key", explode(" ", mysql_field_flags($fields, $i)))) {
                $return[] = $field_name;
            }
        }
        return $return;
    }
    
    /**
     * 链接数据库
     *
     * @return object
     */
    function db_connect() {
        $connection = mysql_connect("{$this->mDatabaseHost}:{$this->mDatabasePort}", $this->mDatabaseUsername, $this->mDatabasePassword);
        if (! $this->isResource($connection)) {
            die('Data Connect Error');
        }
        mysql_select_db($this->mDatabaseName, $connection);
        mysql_query("SET NAMES " . $this->mCharset, $connection);
        return $connection;
    }
    public function db_num_rows($pResult) {
        return mysql_num_rows($pResult);
    }
    public function db_fetch_array($pResult, $pResultType = 'both') {
        return mysql_fetch_array($pResult, $this->mResultType[$pResultType]);
    }
    public function db_free_result($pResult) {
        return mysql_free_result($pResult);
    }
    
    /**
     * 执行SQL语句
     *
     * @param string $pQuery            
     * @return mix
     */
    public function db_query($pQuery) {
        if (count(SPP_SqlCommand::$theInstances) > 1) {
            mysql_select_db($this->mDatabaseName, $this->mLink);
        }
        $return = mysql_query($pQuery, $this->mLink);
        if (mysql_errno()) {
            throw new Exception("DataBase Error: {$pQuery}");
        }
        return $return;
    }
    public function db_close() {
        return mysql_close($this->mLink);
    }
    public function db_insert_id() {
        return mysql_insert_id($this->mLink);
    }
    public function db_affected_rows($pResult) {
        return mysql_affected_rows($this->mLink);
    }
    public function db_data_seek($result_identifier, $row_number) {
        return mysql_data_seek($result_identifier, $row_number);
    }
    function PreparePagedArrayQuery($sql, $pPageNo = 0, $pPageSize = 10) {
        $sql .= " limit " . (($pPageNo - 1) * $pPageSize) . "," . $pPageSize;
        return $this->ExecuteQuery($sql);
    }
    public function isResource($pLink) {
        return is_resource($pLink);
    }
    /**
     * 函数转义 SQL 语句中使用的字符串中的特殊字符
     * @param string $pValue
     * @return string
     */
    public function escapeString($pValue) {
        return "'" . mysql_escape_string($pValue) . "'";
    }
}
?>
