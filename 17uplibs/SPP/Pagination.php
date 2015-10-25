<?php
/**
 * Pagination SQL分页类
 */
class SPP_Pagination {
    /**
     * 第几页
     *
     * @var int
     */
    public $mPage = 1;
    /**
     * 每页记录条数
     *
     * @var int
     */
    public $mPageSize = 1;
    /**
     * 总页数
     *
     * @var int
     */
    public $mPageCount = 1;
    /**
     * 下一页
     *
     * @var int
     */
    public $mNextPage = 1;
    /**
     * 上一页
     *
     * @var int
     */
    public $mPreviousPage = 1;
    /**
     * 第一页
     *
     * @var int
     */
    public $mFirstPage = 1;
    /**
     * 最后一页
     *
     * @var unknown_type
     */
    public $mLastPage = 1;
    /**
     * 总纪录数
     *
     * @var int
     */
    public $mRecordCount = 0;
    /**
     * 纪录起始点
     *
     * @var int
     */
    public $mStartRecord = 0;
    /**
     * 纪录结束点
     *
     * @var unknown_type
     */
    public $mEndRecord = 0;
    /**
     *
     * @param number $pPage
     *            当前页
     * @param number $pPageSize
     *            页面大小
     * @param number $pRecordCount
     *            记录数
     */
    function __construct($pPage = 0, $pPageSize = 10, $pRecordCount = 0) {
        if ($pPage <= 0) {
            $pPage = 1;
        }
        if ($pPage === false) {
            $pPage = 1;
        }
        $this->mPage = $pPage;
        $this->mPageSize = $pPageSize;
        
        if ($pRecordCount < 1) {
            $pRecordCount = false;
        }
        
        if ($pRecordCount !== false) {
            $this->makePage($pRecordCount);
        }
    }
    /**
     * 计算分页数据
     *
     * @param number $pRecordCount            
     */
    function makePage($pRecordCount) {
        $this->mRecordCount = $pRecordCount;
        $this->mLastPage = $this->mPageCount = ( int ) ceil($this->mRecordCount / $this->mPageSize);
        $this->mStartRecord = ($this->mPage - 1) * $this->mPageSize + 1;
        $this->mEndRecord = min($this->mRecordCount, $this->mPage * $this->mPageSize);
        $this->mNextPage = min($this->mPageCount, $this->mPage + 1);
        $this->mPreviousPage = max(1, $this->mPage - 1);
    }
    /**
     * 获取分页属性
     *
     * @return string
     */
    function getHtmlAttribute() {
        $tPageAttr = "";
        $mPageHash = get_object_vars($this);
        foreach($mPageHash as $key => $value) {
            $tPageAttr .= "{$key}={$value} ";
        }
        return $tPageAttr;
    }
    
    /**
     * sql语句增加分页
     * 
     * @param string $pSql            
     */
    function makeSql($pSql) {
        $pSql .= " limit " . ($this->mStartRecord - 1) . "," . $this->mPageSize;
    }
}