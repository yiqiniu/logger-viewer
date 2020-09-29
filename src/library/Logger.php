<?php


namespace yiqiniu\logger\library;


use think\App;

class Logger
{

    private static $_config = [];


    //public function
    protected $namespace = '\\yiqiniu\\logger\\library\\drives\\';
    /**
     * @var App
     */
    protected $app;
    // 保存

    /**
     * Logger constructor.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }

    /**
     * 获取日志配置
     * @access public
     * @param null|string $name 名称
     * @param mixed       $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null)
    {
        if (!empty(self::$_config)) {
            $config = self::$_config;
        } else {
            $config = Base::getConfig($this->app, false);
        }

        if (!is_null($name)) {
            return $config[$name] ?? [];
        }
        return $config;
    }

    protected function save()
    {

    }
}