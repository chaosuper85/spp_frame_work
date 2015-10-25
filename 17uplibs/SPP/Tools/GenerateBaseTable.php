<?php
set_time_limit(0);
class SPP_Tools_GenerateBaseTable {
    /**
     * 文件路径
     *
     * @var string
     */
    private $mFilePath = '';
    /**
     * 作者
     *
     * @var string
     */
    private $mAuthor = 'FrameWork';
    /**
     * 数据库索引
     *
     * @var int
     */
    private $mInstance = 0;
    private $mClassPrefix = '';
    public function __construct($pInstance, $pFilePath, $pAuthor, $pModelPrefix = '') {
        $this->mInstance = $pInstance;
        $this->mFilePath = $pFilePath;
        $this->mAuthor = $pAuthor;
        if (! empty($pModelPrefix)) {
            $this->mClassPrefix = "{$pModelPrefix}_";
        }
    }
    
    /**
     * 生成代码
     *
     * @param array $pTableArray            
     */
    public function generateCode($pTableArray) {
        for($i = 0; $i < count($pTableArray); $i ++) {
            $this->generateBaseTableClass($pTableArray[$i]);
            echo "$pTableArray[$i]---------OK<br/>";
        }
    }
    
    /**
     * 生成映射类代码
     *
     * @param string $ptable_name            
     */
    public function generateBaseTableClass($ptable_name) {
        $table_name = $ptable_name;
        // $table_name = 'show_suit';
        $table_name_array = explode('_', $table_name);
        //
        $table_name_array = array_map("ucfirst", $table_name_array);
        $class_name = implode('', $table_name_array);
        $sql_cmd = SPP_SqlCommand::getInstance($this->mInstance);
        $sql = "show full fields from `{$table_name}`";
        $result_array = $sql_cmd->ExecuteArrayQuery($sql);
        file_put_contents("{$this->mFilePath}/$class_name.php", "<?php\n");
        file_put_contents("{$this->mFilePath}/$class_name.php", "\n", FILE_APPEND);
        $string = "/**\n";
        $string .= " * 功能:  {$table_name} 表映射类\n";
        $string .= " * @author {$this->mAuthor}\n";
        $date = date("Y/m/d");
        $string .= " * @versi:on v 1.0  {$date}\n";
        $string .= " * @package MappingClass  \n";
        $string .= " */\n";
        $string .= "class {$this->mClassPrefix}{$class_name} extends SPP_BaseTable {\n";
        file_put_contents("{$this->mFilePath}/$class_name.php", $string, FILE_APPEND);
        
        for($i = 0; $i < count($result_array); $i ++) {
            $string = "    /**\n";
            $string .= "     * {$result_array[$i]['Comment']}\n";
            $string .= "     * Class Member Mapping to Table \"{$table_name}\" Field \"{$result_array[$i]['Field']}\"\n";
            $string .= "     * 类的属性成员，指向 {$table_name} 表的 {$result_array[$i]['Field']} 字段\n";
            $string .= "     * Refer to \$this->mMapHash['{$result_array[$i]['Field']}'];\n";
            $string .= "     * 指向 \$this->mMapHash['{$result_array[$i]['Field']}'] 的同一变量;\n";
            $string .= "     * @access public\n";
            $var_type = "integer";
            if (stristr($result_array[$i]['Type'], "int") == false) {
                $var_type = "string";
            }
            $string .= "     * @var {$var_type}\n";
            $string .= "     */\n";
            $var_name = $this->getVarName($result_array[$i]['Field']);
            $string .= "    public \${$var_name};\n\n";
            file_put_contents("{$this->mFilePath}/$class_name.php", $string, FILE_APPEND);
        }
        "";
        $string = "    /**\n";
        $string .= "     * 构造函数\n";
        $string .= "     * @access public\n";
        $string .= "     */\n";
        $string .= "    public function __construct() {\n";
        $string .= "        parent::__construct(\"{$table_name}\",{$this->mInstance});\n";
        $string .= "    }\n";
        file_put_contents("{$this->mFilePath}/$class_name.php", $string, FILE_APPEND);
        file_put_contents("{$this->mFilePath}/$class_name.php", "}\n", FILE_APPEND);
    }
    
    /**
     * 获取变量名称
     *
     * @param string $pField            
     * @return string
     */
    function getVarName($pField) {
        $a = explode('_', $pField);
        $b = array_map('ucfirst', $a);
        $c = implode('', $b);
        return "m{$c}";
    }
}
