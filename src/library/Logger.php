<?php


namespace yiqiniu\logger\library;


use think\App;
use think\Exception;
use yiqiniu\logger\library\contract\YqnLoggerInterface;
use yiqiniu\traits\Singleton;

class Logger implements YqnLoggerInterface
{
    use  Singleton;

    private static $_config = [];


    //public function
    protected $namespace = '';
    /**
     * @var App
     */
    protected $app;
    // 保存


    /**
     * @var
     *
     */
    protected $handler = null;

    /**
     * Logger constructor.
     */
    public function __construct()
    {

    }

    public function _initilize(App $app){
        $this->app = $app;
        $this->getDriver();
    }


    /**
     * 获取驱动实例
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    protected function getDriver()
    {
        if ($this->handler === null) {

            $name = $this->getConfig('default','file');

            $class = '\\yiqiniu\\logger\\library\\drives\\' . $name;

            if (!class_exists($class)) {
                throw  new Exception($class . 'file not found');
            }
            $handler = new $class($this->app);

            if ($handler->initOption($this->getConfig($name)) === false) {
                throw new Exception('初始化驱动失败');
            }
            $this->handler= $handler;
            return $handler ;
        }
        return $this->handler;

    }

    /**
     * 获取日志配置
     * @access public
     * @param null|string $name 名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null,$default='')
    {
        if (!empty(self::$_config)) {
            $config = self::$_config;
        } else {
            $config = Base::getConfig($this->app, false);
        }

        if (!is_null($name)) {
            return $config[$name] ?? $default;
        }
        return $config;
    }

    /**
     * 初始驱动
     * @param array $option
     * @return bool
     */
    public function initOption(array $option): bool
    {
        return true;
    }

    public function write(string $content): bool
    {
        return $this->handler->write($content);
    }

    public function treelist(): array
    {
        return $this->handler->treelist();
    }

    public function filelist(string $fileID = ''): array
    {
        return $this->handler->filelist($fileID);
    }

    public function read(string $fileID): string
    {
        return $this->handler->read($fileID);
    }

    public function delete(string $fileID,bool $isdir): bool
    {
        return $this->handler->delete($fileID,$isdir);
    }
}