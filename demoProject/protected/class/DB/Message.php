<?php

/**
 * 功能:  message 表映射类
 * @author SPP
 * @versi:on v 1.0  2015/07/13
 * @package MappingClass  
 */
class DB_Message extends SPP_BaseTable {
    /**
     * id
     * Class Member Mapping to Table "message" Field "id"
     * 类的属性成员，指向 message 表的 id 字段
     * Refer to $this->mMapHash['id'];
     * 指向 $this->mMapHash['id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mId;
    
    /**
     * 发送用户ID
     * Class Member Mapping to Table "message" Field "from_user_id"
     * 类的属性成员，指向 message 表的 from_user_id 字段
     * Refer to $this->mMapHash['from_user_id'];
     * 指向 $this->mMapHash['from_user_id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mFromUserId;
    
    /**
     * 目标用户ID
     * Class Member Mapping to Table "message" Field "to_user_id"
     * 类的属性成员，指向 message 表的 to_user_id 字段
     * Refer to $this->mMapHash['to_user_id'];
     * 指向 $this->mMapHash['to_user_id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mToUserId;
    
    /**
     * 消息内容
     *
     * Class Member Mapping to Table "message" Field "content"
     * 类的属性成员，指向 message 表的 content 字段
     * Refer to $this->mMapHash['content'];
     * 指向 $this->mMapHash['content'] 的同一变量;
     * 
     * @access public
     * @var string
     */
    public $mContent;
    
    /**
     * 发送日期
     * Class Member Mapping to Table "message" Field "create_time"
     * 类的属性成员，指向 message 表的 create_time 字段
     * Refer to $this->mMapHash['create_time'];
     * 指向 $this->mMapHash['create_time'] 的同一变量;
     * 
     * @access public
     * @var string
     */
    public $mCreateTime;
    
    /**
     *
     * Class Member Mapping to Table "message" Field "status"
     * 类的属性成员，指向 message 表的 status 字段
     * Refer to $this->mMapHash['status'];
     * 指向 $this->mMapHash['status'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mStatus;
    
    /**
     * 回复消息ID
     * Class Member Mapping to Table "message" Field "reply_msg_id"
     * 类的属性成员，指向 message 表的 reply_msg_id 字段
     * Refer to $this->mMapHash['reply_msg_id'];
     * 指向 $this->mMapHash['reply_msg_id'] 的同一变量;
     * 
     * @access public
     * @var integer
     */
    public $mReplyMsgId;
    
    /**
     * 是否已读
     * Class Member Mapping to Table "message" Field "is_read"
     * 类的属性成员，指向 message 表的 is_read 字段
     * Refer to $this->mMapHash['is_read'];
     * 指向 $this->mMapHash['is_read'] 的同一变量;
     *
     * @access public
     * @var integer
     */
    public $mIsRead;
    
    
    const MESSAGE_READ = 1;
    const MESSAGE_UNREAD = 0;
    /**
     * 构造函数
     * 
     * @access public
     */
    public function __construct() {
        parent::__construct("message", 0);
    }
    
    public function getMsgByToUserId($pToUserId, $pPage, $pPageSize = 2) {
        $this->mToUserId = $pToUserId;
        $tList = $this->_list($pPage, $pPageSize);
        return $tList;
    }
    
    public function getUserUnReadMsgCount($pToUserId) {
        $this->mToUserId = $pToUserId;
        $this->mIsRead = self::MESSAGE_UNREAD;
        return $this->_count();
    }
}
