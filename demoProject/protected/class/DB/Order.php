<?php

/**
 * 功能:  order 表映射类
 * @author SPP
 * @versi:on v 1.0  2015/07/13
 * @package MappingClass  
 */
class DB_Order extends SPP_BaseTable {
    /**
     *
     * Class Member Mapping to Table "order" Field "id"
     * 类的属性成员，指向 order 表的 id 字段
     * Refer to $this->mMapHash['id'];
     * 指向 $this->mMapHash['id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mId;
    
    /**
     * 用户ID
     * Class Member Mapping to Table "order" Field "user_id"
     * 类的属性成员，指向 order 表的 user_id 字段
     * Refer to $this->mMapHash['user_id'];
     * 指向 $this->mMapHash['user_id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mUserId;
    
    /**
     * 订单描述
     * Class Member Mapping to Table "order" Field "order_desc"
     * 类的属性成员，指向 order 表的 order_desc 字段
     * Refer to $this->mMapHash['order_desc'];
     * 指向 $this->mMapHash['order_desc'] 的同一变量;
     * 
     * @access public
     * @var string
     */
    public $mOrderDesc;
    
    /**
     * 订单时间
     * Class Member Mapping to Table "order" Field "order_time"
     * 类的属性成员，指向 order 表的 order_time 字段
     * Refer to $this->mMapHash['order_time'];
     * 指向 $this->mMapHash['order_time'] 的同一变量;
     * 
     * @access public
     * @var string
     */
    public $mOrderTime;
    
    /**
     * 状态
     * Class Member Mapping to Table "order" Field "status"
     * 类的属性成员，指向 order 表的 status 字段
     * Refer to $this->mMapHash['status'];
     * 指向 $this->mMapHash['status'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mStatus;
    
    /**
     * 商品数量
     * Class Member Mapping to Table "order" Field "product_cnt"
     * 类的属性成员，指向 order 表的 product_cnt 字段
     * Refer to $this->mMapHash['product_cnt'];
     * 指向 $this->mMapHash['product_cnt'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mProductCnt;
    
    /**
     * 订单ID
     * Class Member Mapping to Table "order" Field "product_id"
     * 类的属性成员，指向 order 表的 product_id 字段
     * Refer to $this->mMapHash['product_id'];
     * 指向 $this->mMapHash['product_id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mProductId;
    
    
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;
    /**
     * 构造函数
     * 
     * @access public
     */
    public function __construct($pOrderId = 0) {
        parent::__construct("order", 0);
        if($pOrderId > 0) {
            $this->mId = $pOrderId;
            if(!$this->_select()) {
                $this->mId = 0;
            }
        }
    }
}
