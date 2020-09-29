<?php
declare(strict_types=1);

namespace yiqiniu\logger\library;


use think\App;
use think\Template;

class HomeApi
{

    protected static $_instance = null;
    protected $app;

    protected $tpl_config = [
        'view_path' => '',
        'cache_path' => '',
        'view_suffix' => 'html',
        'tpl_cache' => false,
        'tpl_replace_string' => [],
    ];

    /**
     * HomeApi constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $config = Base::getConfig($app);
        if (!empty($config)) {
            $this->tpl_config = array_merge($this->tpl_config, $config);
        }
        //视图路径
        $this->tpl_config['view_path'] = __DIR__ . '/../' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
        //视图缓存路径
        $this->tpl_config['cache_path'] = $this->app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logger_views' . DIRECTORY_SEPARATOR;
        //模板参数
        $this->tpl_config['tpl_replace_string'] = [
            '__RES__' => 'https://www.layuicdn.com/',
        ];
    }

    public static function getInstance(App $app)
    {
        if (self::$_instance === null) {
            self::$_instance = new static($app);
        }
        return self::$_instance;
    }

    public function index()
    {
        // 设置模板引擎参数
        $template = new Template($this->tpl_config);
        // 模板变量赋值
        $template->assign(['name' => 'think']);
        // 读取模板文件渲染输出
        $template->fetch('index');
    }

    /**
     * 读取文件列表
     */
    public function filelist()
    {
        echo '文件列表';
    }

    /**
     * 读取文件内容
     */
    public function filecontent()
    {
        echo '文件内容';
    }

    /**
     * 删除
     */
    public function delete()
    {
        echo '删除';
    }


    /**
     * 获取配置文件
     * @return array
     */
    protected function getconfig()
    {

    }


}