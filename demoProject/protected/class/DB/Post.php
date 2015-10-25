<?php

/**
 * 功能:  post 表映射类
 * @author SPP
 * @versi:on v 1.0  2015/07/13
 * @package MappingClass  
 */
class DB_Post extends SPP_BaseTable {
    /**
     *
     * Class Member Mapping to Table "post" Field "id"
     * 类的属性成员，指向 post 表的 id 字段
     * Refer to $this->mMapHash['id'];
     * 指向 $this->mMapHash['id'] 的同一变量;
     *
     * @access public
     * @var integer
     */
    public $mId;
    
    /**
     *
     * Class Member Mapping to Table "post" Field "title"
     * 类的属性成员，指向 post 表的 title 字段
     * Refer to $this->mMapHash['title'];
     * 指向 $this->mMapHash['title'] 的同一变量;
     *
     * @access public
     * @var string
     */
    public $mTitle;
    
    /**
     *
     * Class Member Mapping to Table "post" Field "category_id"
     * 类的属性成员，指向 post 表的 category_id 字段
     * Refer to $this->mMapHash['category_id'];
     * 指向 $this->mMapHash['category_id'] 的同一变量;
     *
     * @access public
     * @var integer
     */
    public $mCategoryId;
    
    /**
     *
     * Class Member Mapping to Table "post" Field "user_id"
     * 类的属性成员，指向 post 表的 user_id 字段
     * Refer to $this->mMapHash['user_id'];
     * 指向 $this->mMapHash['user_id'] 的同一变量;
     *
     * @access public
     * @var integer
     */
    public $mUserId;
    
    /**
     *
     * Class Member Mapping to Table "post" Field "status"
     * 类的属性成员，指向 post 表的 status 字段
     * Refer to $this->mMapHash['status'];
     * 指向 $this->mMapHash['status'] 的同一变量;
     *
     * @access public
     * @var integer
     */
    public $mStatus;
    
    /**
     * 构造函数
     *
     * @access public
     */
    public function __construct() {
        parent::__construct("post", 0);
    }
}
