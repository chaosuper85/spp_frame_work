<?php

/**
 * 功能:  user 表映射类
 * @author SPP
 * @versi:on v 1.0  2015/07/13
 * @package MappingClass  
 */
class DB_User extends SPP_BaseTable {
    /**
     *
     * Class Member Mapping to Table "user" Field "id"
     * 类的属性成员，指向 user 表的 id 字段
     * Refer to $this->mMapHash['id'];
     * 指向 $this->mMapHash['id'] 的同一变量;
     *
     * @access public
     * @var integer
     */
    public $mId;
    
    /**
     *
     * Class Member Mapping to Table "user" Field "name"
     * 类的属性成员，指向 user 表的 name 字段
     * Refer to $this->mMapHash['name'];
     * 指向 $this->mMapHash['name'] 的同一变量;
     *
     * @access public
     * @var string
     */
    public $mName;
    
    /**
     *
     * Class Member Mapping to Table "user" Field "password"
     * 类的属性成员，指向 user 表的 password 字段
     * Refer to $this->mMapHash['password'];
     * 指向 $this->mMapHash['password'] 的同一变量;
     *
     * @access public
     * @var string
     */
    public $mPassword;
    
    /**
     *
     * Class Member Mapping to Table "user" Field "email"
     * 类的属性成员，指向 user 表的 email 字段
     * Refer to $this->mMapHash['email'];
     * 指向 $this->mMapHash['email'] 的同一变量;
     *
     * @access public
     * @var string
     */
    public $mEmail;
    
    /**
     *
     * Class Member Mapping to Table "user" Field "status"
     * 类的属性成员，指向 user 表的 status 字段
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
        parent::__construct("user", 0);
    }
    /**
     * 用户登陆
     *
     * @param string $pEmail
     *            邮箱
     * @param string $pPassword
     *            密码
     * @return boolean
     */
    public function login($pEmail, $pPassword) {
        $this->mEmail = $pEmail;
        if ($this->_select()) {
            if (md5(md5($pPassword)) == $this->mPassword) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
