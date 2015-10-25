<?php
class Page_TestMySqlI extends BasePage {
    public function run() {
        $con = @mysql_connect('127.0.0.1:3306', 'root', '');
        mysql_select_db('test');
        $fields = mysql_list_fields('test', 'user', $con);
        // var_dump($f);
        $columns = mysql_num_fields($fields);
        for($i = 0; $i < $columns; $i ++) {
            $field_name = mysql_field_name($fields, $i);
            $return[$field_name]['name'] = $field_name;
            $return[$field_name]['type'] = mysql_field_type($fields, $i);
            $return[$field_name]['length'] = mysql_field_len($fields, $i);
            $return[$field_name]['flags'] = explode(" ", mysql_field_flags($fields, $i));
        }
        
        $con = @mysqli_connect('127.0.0.1:3306', 'root', '');
        mysqli_select_db($con, 'test');
        $tSql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = 'test' and TABLE_NAME='user'";
        $tSql = "SHOW COLUMNS FROM user;";
        $list = mysqli_query($con, $tSql);
        while(($row = $list->fetch_assoc()) !== null) {
            $r[$row['Field']] = $this->getFieldAttr($row);
        }
        var_dump($return,$r);
        //var_dump($return[1], $rr[1]);
    }
    private function getFieldAttr($pArray) {
        $type = "";
        $length = 0;
        $flags = array();
        preg_match("/(.*)\((\d*)\)(.*)/", $pArray['Type'], $match);
        // var_dump($match);
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
        } else if (in_array($type, array('smallint', 'tinyint', 'bigint'))) {
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
        if(!empty($pArray['Extra'])) {
            $flags[] = $pArray['Extra'];
        }
        // @TODO 未处理类型decimal,float,double,point等
        return array('name' => $pArray['Field'],'type' => $type,'length' => $length,'flags' => $flags );
    }
}