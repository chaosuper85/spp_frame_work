<?php
/**
 * Util 常用函数类库
 */
class SPP_Util_Util {
    static function Pagination($pPageNo, $pPageSize, $pResultCount, $pageHash) {
        $pageHash = array('mPage' => $pPageNo,'mPageSize' => $pPageSize,'mPageCount' => 1,'mNextPage' => 1,'mPreviousPage' => 1,'mFirstPage' => 1,'mLastPage' => 1,'mRecordCount' => $pResultCount,'mStartRecord' => 1,'mEndRecord' => $pResultCount );
        $pageHash['mLastPage'] = $pageHash['mPageCount'] = ceil($pageHash['mRecordCount'] / $pageHash['mPageSize']);
        $pageHash['mStartRecord'] = ($pageHash['mPage'] - 1) * $pageHash['mPageSize'] + 1;
        $pageHash['mEndRecord'] = min($pageHash['mRecordCount'], $pageHash['mPage'] * $pageHash['mPageSize']);
        $pageHash['mNextPage'] = min($pageHash['mPageCount'], $pageHash['mPage'] + 1);
        $pageHash['mPreviousPage'] = max(1, $pageHash['mPage'] - 1);
    }
    static function addSqlSlashes($pString) {
        return str_replace("'", "\'", $pString);
    }
    static function go2info($code, $back_url = "/") {
        self::directGoToUrl("Info.php?code={$code}&back_url=" . rawurlencode($back_url));
        exit();
    }

    /**
     * 直接跳转
     *
     * @param string $pUrl
     */
    static function directGoToUrl($pUrl = '/') {
        echo "<meta http-equiv='refresh' content='0;URL={$pUrl}'>";
        exit();
    }
    static function setSerializeObject($pConfigName, $pObject) {
        File::writeFile(SPP_Util_Util::getSerializeObjectPath($pConfigName), serialize($pObject));
    }
    static function getSerializeObject($pConfigName) {
        File::writeFile($config_filename, serialize($this->mTableColumnHash));
    }
    static function removeSerializeObject($pConfigName) {
    }
    static function getSerializeObjectPath($pConfigName) {
    }
    static function transformArray2Hash($pArray, $pPosKey = 0, $pPosValue = 1) {
        $return = Array();
        for($i = 0; $i < count($pArray); $i ++) {
            $return[$pArray[$i][$pPosKey]] = $pArray[$i][$pPosValue];
        }
        return $return;
    }
    public static function magicName($pString) {
        return implode("", array_map('ucfirst', explode('_', $pString)));
    }
    static function chop($pString) {
        return substr($pString, 0, strlen($pString) - 1);
    }
    static function strlen_utf8($str) {
        $i = 0;
        $count = 0;
        $len = strlen($str);
        while($i < $len) {
            $chr = ord($str[$i]);
            $count ++;
            $i ++;
            if ($i >= $len)
                break;

            if ($chr & 0x80) {
                $chr <<= 1;
                while($chr & 0x80) {
                    $i ++;
                    $chr <<= 1;
                }
            }
        }
        return $count;
    }
    static function substr_utf8($pTitle, $pLength, $sign = false) {
        if (strlen($pTitle) <= $pLength) {
            return $pTitle;
        }
        $tmpstr = "";
        if ($sign) {
            for($i = 0; $i < $pLength; $i ++) {
                if (ord(substr($pTitle, $i, 1)) > 0xa0) {
                    $tmpstr .= substr($pTitle, $i, 2);
                    $i ++;
                } else {
                    $tmpstr .= substr($pTitle, $i, 1);
                }
            }
            return $tmpstr;
        }
        for($i = 0; $i < $pLength - 4; $i ++) {
            if (ord(substr($pTitle, $i, 1)) > 0xa0) {
                $tmpstr .= substr($pTitle, $i, 2);
                $i ++;
            } else {
                $tmpstr .= substr($pTitle, $i, 1);
            }
        }
        return $tmpstr . "...";
    }
    static function subStrDoubleBytes($pTitle, $pLength, $sign = false) {
        if (strlen($pTitle) <= $pLength) {
            return $pTitle;
        }
        $tmpstr = "";
        if ($sign) {
            for($i = 0; $i < $pLength; $i ++) {
                if (ord(substr($pTitle, $i, 1)) > 0xa0) {
                    $tmpstr .= substr($pTitle, $i, 2);
                    $i ++;
                } else {
                    $tmpstr .= substr($pTitle, $i, 1);
                }
            }
            return $tmpstr;
        }
        for($i = 0; $i < $pLength - 4; $i ++) {
            if (ord(substr($pTitle, $i, 1)) > 0xa0) {
                $tmpstr .= substr($pTitle, $i, 2);
                $i ++;
            } else {
                $tmpstr .= substr($pTitle, $i, 1);
            }
        }
        return $tmpstr . "...";
    }
    static function addSpaceStrDoubleBytes($pString) {
        $tmpstr = "";
        $sign = 1;
        for($i = 0; $i < strlen($pString); $i ++) {
            if (ord(substr($pString, $i, 1)) > 0xa0) {
                if ($sign == 0)
                    $tmpstr .= " ";
                $tmpstr .= substr($pString, $i, 2) . " ";
                $sign = 1;
                $i ++;
            } else {
                $tmpstr .= substr($pString, $i, 1);
                $sign = 0;
            }
        }
        return $tmpstr;
    }
    static function array_chunk($pInputArray, $pSize, $pPreserveKeys) {
        $row = 0;
        $cell = 0;
        if (is_array($pInputArray)) {
            foreach($pInputArray as $temp) {
                $return[$row][$cell] = $temp;
                $cell ++;
                if ($cell == $pSize) {
                    $row ++;
                    $cell = 0;
                }
            }
        }
        return $return;
    }

    /*
     * 返回浮点型毫秒数
     */
    static function getmicrotime() {
        list($usec, $sec) = explode(" ", microtime());
        return (( float ) $usec + ( float ) $sec);
    }

    /*
     * require PHP5
     */
    static function auto_load_files($autoLoadPath) {
        $dir = new DirectoryIterator($autoLoadPath);
        foreach($dir as $file) {
            if (! $file->isDir()) {
                require_once ($file->getPathname());
            }
        }
    }
    public static function Array2Hash($pArray, $pOffset = 0) {
        $return = Array();
        for($i = 0; $i < count($pArray); $i ++) {
            if (count($pArray[$i]) == 2) {
                $keys = array_keys($pArray[$i]);
                $return[$pArray[$i][$keys[$pOffset]]] = $pArray[$i][$keys[1]];
            } else {
                $return[$pArray[$i][$pOffset]] = $pArray[$i];
            }
        }
        return $return;
    }
    function ObjectArray2Hash($pArray, $pOffset = 0) {
        $return = Array();
        for($i = 0; $i < count($pArray); $i ++) {
            $return[$pArray[$i]->$pOffset] = $pArray[$i];
        }
        return $return;
    }
    public static function Array2Array($pArray) {
        $return = Array();
        for($i = 0; $i < count($pArray); $i ++) {
            $keys = array_keys($pArray[$i]);
            for($j = 0; $j < count($pArray[$i]); $j ++) {
                $return[$keys[$j]][$i] = $pArray[$i][$keys[$j]];
            }
        }
        return $return;
    }

    /**
     * 从数组中提取指定列作为字符串
     *
     * @param array $pArray
     * @param int $pOffset
     * @return string
     */
    public static function Array2String($pArray, $pOffset = 0) {
        if (is_array($pArray) && count($pArray) > 0) {
            $return = self::Array2Array($pArray);
            if (array_key_exists($pOffset, $return)) {
                return "'" . implode("','", array_unique($return[$pOffset])) . "'";
            } else {
                $keys = array_keys($return);
                return "'" . implode("','", array_unique($return[$keys[0]])) . "'";
            }
        } elseif (is_object($pArray) && get_class($pArray) == 'SPP_SqlCommandIterator' && count($pArray) > 0) {
            foreach($pArray as $item) {
                $return[] = $item[$pOffset];
            }
            return "'" . implode("','", array_unique($return)) . "'";
        } else {
            return "";
        }
    }

    /**
     * 计算文件大小
     *
     * @param int $Size
     * @return string
     */
    function CountFileSize($Size) {
        return self::byte_format($Size);
    }

    /**
     * 字节格式化
     *
     * @param int $input
     * @param int $dec
     * @return string
     */
    function byte_format($input, $dec = 0) {
        $prefix_arr = array("B","K","M","G","T" );
        $value = round($input, $dec);
        $i = 0;
        while($value > 1024) {
            $value /= 1024;
            $i ++;
        }
        $return_str = round($value, $dec) . $prefix_arr[$i];
        return $return_str;
    }

    /**
     * 格式化数字
     *
     * @param string $input
     * @param int $dec
     * @return string
     */
    function number_format($input, $dec = 0) {
        $input = ereg_replace("[^0-9\.]", "", $input);
        return $return_str;
    }

    /**
     * 将HTML代码转化成JavaScript代码
     *
     * @param string $pHtmlCode
     * @return string
     */
    function html2js($pHtmlCode) {
        $return = 'document.write("';
        $return .= str_replace("\r\n", '\r\n', addslashes($pHtmlCode));
        $return .= '");';
        return $return;
    }

    /**
     * 将一个表的一个或多个字段通过外键引用填充到另一个表的结果数据中
     *
     * @param array $pArray
     *            需要填充的数组
     * @param object $pObject
     *            外键关联到的实例
     * @param mixed $pProperty
     *            需填充的字段名，如果只填充一个字段可以是一个string，填充多个字段，则是一个array，需改变填充后的字段名，则传递一个hash
     * @param string $pJoinColumn
     *            外键字段
     * @param string $pJoinedColumn
     *            外键关联到的字段
     * @return void
     */
    static public function fills(&$messages, &$pObject, $pProperty, $pJoinColumn, $pJoinedColumn) {
        if (count($messages) > 0) {
            $user_list = self::Array2String($messages, $pJoinColumn);
            if (empty($pObject->mAdditionalCondition)) {
                $pObject->mAdditionalCondition = "{$pJoinedColumn} in ({$user_list})";
            } else {
                $pObject->mAdditionalCondition .= " and {$pJoinedColumn} in ({$user_list})";
            }
            $users = self::Array2Hash($pObject->_list(), $pJoinedColumn);
            if (count($users) > 0)
                foreach($messages as &$item) {
                    if (is_array($pProperty)) {
                        foreach($pProperty as $key => $value) {
                            if (isset($users[$item[$pJoinColumn]][$value])) {
                                if (is_string($key)) {
                                    $item[$key] = $users[$item[$pJoinColumn]][$value];
                                } else {
                                    $item[$value] = $users[$item[$pJoinColumn]][$value];
                                }
                            }
                        }
                    } else {
                        $item[$pProperty] = $users[$item[$pJoinColumn]][$pProperty];
                    }
                }
        }
    }
    public static function getIP() {
        if (! empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (! empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (! empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = '';
        }
        preg_match("/[\d\.]{7,15}/", $cip, $cips);
        $cip = isset($cips[0]) ? $cips[0] : 'unknown';
        unset($cips);
        return $cip;
    }

	/**  
	 * 定位当前的城市
	 * @return array
	 */
	public function getposition()
	{    
			$data = array();
			//没有则重新获取  //获得登录用户的真实地址
			$ip = SPP_Util_Util::getIP();
		//	$ip = "124.193.169.202";
			$url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$ip;
			$data = json_decode(file_get_contents($url));
			return (array)$data;
	} 

    /**
     * 读取远程url内容
     *
     * @param string $url
     * @param array $params
     * @param string $method  GET/POST
     * @param string $pwd  user:pwd
     */
    public static function getRemoteContent($url, $params=array(), $method='POST', $pwd=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (strtoupper($method) == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        if (!is_null($pwd)){
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $pwd);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = @curl_exec($ch);
        return $result;
    }

    /**
     * 并发请求API接口
     *
     * @param array $requests
     * 示例:
     * array(
     *   array(
     *        'url' => 'http://api.17house.com',
     *        'params' => array(
     *            'type' => 'hot_thread',
     *            'sort' => 'time desc',
     *                ...
     *        ),
     *        'result' => 'xxxx'  //执行后追加
     *   ),
     *   array(
     *        'url' => 'http://api.17house.com',
     *        'params' => array(
     *            'type' => 'sold_list',
     *                ...
     *        ),
     *        'result' => 'xxxx'  //执行后追加
     *   ),
     *   .....
     *   )
     */
    public static function getMultiRemoteContent(&$requests)
    {
        $active = null;
        $conn = array();
        $mh = curl_multi_init();

        foreach ($requests as $i=>$request){
            $conn[$i] = curl_init($request['url']);
            curl_setopt($conn[$i], CURLOPT_HEADER, false);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
            if (isset($request['params']) && !empty($request['params'])){
                curl_setopt($conn[$i], CURLOPT_POST, 1);
                curl_setopt($conn[$i], CURLOPT_POSTFIELDS, $request['params']);
            }
            curl_multi_add_handle($mh, $conn[$i]);
        }

        do{
            curl_multi_exec($mh, $active);
        }while ($active > 0);

//        do{
//            $mrc = curl_multi_exec($mh, $active);
//        }while ($mrc == CURLM_CALL_MULTI_PERFORM);
//
//        while ($active && $mrc == CURLM_OK) {
//        	if (curl_multi_select($mh) != -1) {
//        		do {
//        		    $mrc = curl_multi_exec($mh, $active);
//        		}while ($mrc == CURLM_CALL_MULTI_PERFORM);
//        	}else{
//        	    usleep(100);
//        	}
//        }

        foreach ($requests as $i=>$request){
            $requests[$i]['result'] = curl_multi_getcontent($conn[$i]);
            curl_multi_remove_handle($mh, $conn[$i]);
        }

        curl_multi_close($mh);
    }

     /**
     * 判断来源是否来源手机
     *
     * @param $pua
     * @return bool
     */
    public static function isMobile($pua = null) {
        if (! $pua) {
            $pua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }

        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $pua) ||
                 preg_match(
                        '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
                        substr($pua, 0, 4))) {
            return true;
        }

        return false;
    }

    /**
     * 判断访问来源是否PC
     * @date 2015-07-14
     *
     * @param $pua
     * @return bool
     */
    public static function isPC($pua = null) {
        $check = true;
        if (! $pua) {
            $pua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        }
        if (SPP_Util_Util::isMobile($pua)) {
            $check = false;
        }
        return $check;
    }
		
	 public static function UCAuthcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;

        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }	
}
