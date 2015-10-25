<?php
class SPP_Tools_Clean implements SPP_Runnable {
    function run() {
        /**
         * 清楚Xcache缓存
         */
        $this->clear_xcache();
        /**
         * 获取系统历史目录
         */
        $tCachePath = '/tmp';
        if (('WINNT' == PHP_OS) && getenv('TEMP')) {
            $tCachePath = getenv('TEMP');
        }
        /**
         * 清除加载文件cache
         */
        $this->clear_cache_file($tCachePath);
        /**
         * 清除表结构缓存
         */
        $this->clear_cache_file(SPP_Config_Config::getSystemPath('temp') . DIRECTORY_SEPARATOR . 'cache');
    }
    /**
     * 清除xcache缓存
     */
    private function clear_xcache() {
        $vcnt = xcache_count(XC_TYPE_VAR);
        for ($i = 0; $i < $vcnt; $i ++) {
            xcache_clear_cache(XC_TYPE_VAR, $i);
        }
        echo 'XCache缓存清楚完成!<br /><hr />';
    }
    
    /**
     * 删除文件缓存
     * 
     * @param string $pPath            
     */
    private function clear_cache_file($pPath) {
        echo "Cache File In {$pPath} <br>";
        $count = $success = $fail = 0;
        $it = new DirectoryIterator($pPath);
        foreach ($it as $file) {
            if (substr($file->getFilename(), 0, 4) == 'spp_') {
                $filename = $file->getPathname();
                @unlink($filename);
                echo $filename . ' ';
                if (file_exists($filename)) {
                    echo '清除失败!<br>';
                    $fail ++;
                } else {
                    echo '清除成功!<br>';
                    $success ++;
                }
                $count ++;
            }
        }
        echo "共发现{$count}个缓存文件，成功清除{$success}个，失败{$fail}个！<br /><hr />";
    }
}