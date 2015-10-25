<?php
class SPP_Filter_Filter extends SPP_Object {
    /**
     * 过滤器配置文件
     *
     * @var array
     */
    private $_mFilterConfig;
    /**
     * 构造函数
     *
     * @param array $pFilterConfig            
     */
    public function __construct($pFilterConfig = null) {
        $this->mFilterConfig = $pFilterConfig;
        parent::__construct();
    }
    /**
     * 获取过滤器配置文件
     *
     * @return array
     */
    public function getFilterConfig() {
        return $this->mFilterConfig;
    }
    /**
     * 过滤
     *
     * @param SPP_BasePage $pChain            
     */
    public final function doFilter($pChain = null) {
        $this->doBeforeRun();
        if (! is_null($pChain)) {
            $pChain->run();
        }
        $this->doAfterRun();
    }
    public function doBeforeRun($pContext) {
    }
    public function doAfterRun($pContext) {
    }
}
?>
