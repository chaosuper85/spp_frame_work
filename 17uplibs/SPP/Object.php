<?php

/**
 * 所有开发类的基类
 */
abstract class SPP_Object {
    /**
     * 当前实例的标识ID
     *
     * @var string
     */
    protected $mUniqueObjectID = 'init';
    
    /**
     * 构造函数
     */
    public function __construct() {
        if (SPP_Config_Config::getMode() == 'debug') {
            $this->mUniqueObjectID = uniqid();
            $tInfoArray = array($this->mUniqueObjectID,get_class($this),'Object','Constructed' );
            SPP_Util_Debug::setDebugInfo($tInfoArray);
        }
    }
    
    /**
     * 析构函数
     */
    public function __destruct() {
        if (SPP_Config_Config::getMode() == 'debug') {
            $tInfoArray = array($this->mUniqueObjectID,get_class($this),'Object','Destructed' );
            SPP_Util_Debug::setDebugInfo($tInfoArray);
        }
    }
}