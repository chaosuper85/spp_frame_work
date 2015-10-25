<?php
class Config_Config extends SPP_Object {
    /**
     * 测试用配置变量
     * 
     * @var string
     */
    public static $TestVal = '123456';
    /**
     * 管理员ID 列表
     * 
     * @var array
     */
    public static $AdminUserIdList = array(1,2 );
    /**
     * 判断用户是否为管理员
     * 
     * @param int $pUserId            
     * @return bool
     */
    public static function isAdmin($pUserId) {
        return in_array($pUserId, self::$AdminUserIdList);
    }
}
