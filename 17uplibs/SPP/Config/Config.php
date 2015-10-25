<?php

class SPP_Config_Config
{

    public static $mConfigData = array();

    public static $mDataBases = array();

    public static function init($pConfigFile = null)
    {
        $tAppConfigKey = 'cache_' . APP_NAME . '_config';
        if (APP_DEBUG && xcache_isset($tAppConfigKey)) {
            self::$mConfigData = json_decode(xcache_get($tAppConfigKey));
        } else {
            if (is_null($pConfigFile)) {
                $pConfigFile = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/config.json');
            }
            $tConfigFileContent = file_get_contents($pConfigFile);
            self::$mConfigData = json_decode($tConfigFileContent);
            if (APP_DEBUG && self::$mConfigData) {
                xcache_set($tAppConfigKey, $tConfigFileContent);
            }
        }
        self::$mDataBases = self::$mConfigData->dataBases;
    }

    /**
     * 获取系统目录
     *
     * @param string $pName            
     * @return string
     */
    public static function getSystemPath($pName)
    {
        return self::$mConfigData->systemPath->$pName;
    }

    public static function getAppConfigs()
    {
        return self::$mConfigData;
    }

    /**
     * 获取运行模式
     *
     * @return string
     */
    public static function getMode()
    {
        if (APP_DEBUG) {
            return 'debug';
        } else {
            return 'online';
        }
    }

    /**
     * 是否缓存
     *
     * @return bool
     */
    public static function isCached()
    {
        return false;
    }
}
