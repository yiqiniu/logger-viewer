<?php


namespace yiqiniu\logger\library;


use think\App;

class Base
{

    // 配置文件
    const  YIQINIU_LOG_CONFIG = 'yqn_logger';
    // 视图配置
    const  YIQINIU_LOG_CONFIG_VIEW = 'yqn_logger.view';
    // 日志配置
    const  YIQINIU_LOG_CONFIG_LOG = 'yqn_logger.log';

    /**
     * 检测配置文件是否存在,不存在复制系统配置目录中
     */
    public static function checkConfigFileExist(App $app)
    {
        $dest_file = $app->getConfigPath() . self::YIQINIU_LOG_CONFIG . '.php';
        if (!file_exists($dest_file)) {

            $src_file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . self::YIQINIU_LOG_CONFIG . '.php';
            if(file_exists($src_file)){
                copy($src_file, $dest_file);
            }

        }
    }


    /**
     * 获取配置文件
     * @param App  $app
     * @param bool $bView
     * @return array|mixed
     */
    public static function getConfig(App $app, bool $bView = true)
    {

        if ($bView) {
            $config = $app->config->get(self::YIQINIU_LOG_CONFIG_VIEW);
        } else {
            $config = $app->config->get(self::YIQINIU_LOG_CONFIG_LOG);
        }

        /*$config = [];
         $version = $app->version();
        // thinkphp 6.0
        if (strpos($version, '6.') === 0 || strpos($version, '5.1') === 0) {
        }*/
        return $config;
    }


}