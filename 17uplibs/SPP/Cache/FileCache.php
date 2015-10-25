<?php
class SPP_Cache_FileCache {
    /**
     * CacheKey
     *
     * @var string
     */
    private $mCacheKey;
    
    /**
     * 缓存时间
     *
     * @var int
     */
    private $mCacheTime = 0; // 时间单位为秒，0为无时间限制
    
    /**
     * 构造函数
     *
     * @param string $pCacheKey            
     * @param int $pCacheTime            
     */
    public function __construct($pCacheKey, $pCacheTime = 0) {
        //parent::__construct();
        $this->mCacheKey = md5($pCacheKey);
        $this->mCacheTime = $pCacheTime;
    }
    
    /**
     * 检查缓存状态
     *
     * @return boolean
     */
    public function check_cache_status() {
        if (file_exists($this->get_cache_file_name()) && (filemtime($this->get_cache_file_name()) + $this->mCacheTime > time() || $this->mCacheTime == 0)) {
            return true;
        }
        return false;
    }
    
    /**
     * 获取缓存内容
     *
     * @return string
     */
    public function get_cache_content() {
        return file_get_contents($this->get_cache_file_name());
    }
    
    /**
     * 设置缓存内容
     *
     * @param string $pContent            
     */
    public function put_cache_content($pContent) {
        file_put_contents($this->get_cache_file_name(), $pContent);
    }
    
    /**
     * 获取缓存文件名称
     *
     * @return string
     */
    private function get_cache_file_name() {
        return SPP_Config_Config::getSystemPath('temp') . "/cache/spp_cache_" . $this->mCacheKey;
    }
}
