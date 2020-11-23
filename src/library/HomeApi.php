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
        //$template->assign(['root' => $root_list]);
        // 读取模板文件渲染输出
        $template->fetch('index');
    }

    /**
     * 读取目录列表
     */
    public function treelist()
    {
        $list = Logger::getInstance($this->app)->treelist();
        return $this->result($list, count($list));
    }

    /**
     * 读取文件列表
     */
    public function filelist()
    {
        $path = input('get.path');
        $list = Logger::getInstance($this->app)->filelist($path);
        return $this->result($list, count($list));
    }

    /**
     * 读取文件内容
     */
    public function filecontent()
    {
        $path = input('get.path');
        $list = Logger::getInstance($this->app)->read($path);
        return $this->result($list);
    }

    /**
     * 删除
     */
    public function delete()
    {
        $path = input('post.path');
        $flag = input('post.dir', 0);
        $result = Logger::getInstance($this->app)->delete($path, $flag > 0);
        return $this->result([], 0, '删除完成');
    }


    /**
     * 返回结果
     * @param $data
     * @param int $count
     * @param string $msg
     * @return \think\response\Json
     */
    public function result($data, $count = 0, $msg = '')
    {

        return json(["code" => 0,
            "msg" => $msg,
            "count" => $count,
            "data" => $data
        ]);
    }


}