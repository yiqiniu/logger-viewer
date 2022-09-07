<?php
declare(strict_types=1);

namespace yiqiniu\logger\library;


use think\App;
use think\Template;
use yiqiniu\logger\library\traits\AuthTrait;

/**
 * Class HomeApi
 * @package yiqiniu\logger\library
 */
class HomeApi
{
    use AuthTrait;

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


    /**
     * 登录验证
     */
    public function login()
    {
        // 设置模板引擎参数
        $template = new Template($this->tpl_config);
        // 读取模板文件渲染输出
        if ($this->checkLogin()) {
            return redirect('/viewer/index');
        }
        $template->fetch('login');
    }


    /**
     * 认证
     */
    public function auth()
    {
        $post = input('post.');
        if (empty($post['username']) || empty($post['password'])) {
            return json(["code" => 1, "msg" => '用户名或密码不能为空']);
        }
        $password = $this->getPassword($post['password']);
        if (!$this->checkUserinfo($post['username'], $password)) {
            return json(["code" => 1, "msg" => '用户名或密码错误']);
        }
        return json(["code" => 0, "msg" => '登录成功', 'url' => url('/logger/index')]);
    }


    /**
     * 退出登录
     * @return \think\response\Json
     */
    public function logout()
    {
        cookie('logger_info', null);
        return redirect('/viewer/login');
    }

    /**
     * 显示内容
     */
    public function index()
    {
        // 设置模板引擎参数
        $template = new Template($this->tpl_config);
        if (!$this->checkLogin()) {
            $template->fetch('login');
            return;
        }


        // 读取模板文件渲染输出
        $template->fetch('index');
    }

    /**
     * 读取目录列表
     */
    public function treelist()
    {
        if (!$this->checkLogin()) {
            return redirect('/viewer/login');
        }
        $list = Logger::getInstance($this->app)->treelist();
        return $this->result($list, count($list));
    }

    /**
     * 读取文件列表
     */
    public function filelist()
    {
        if (!$this->checkLogin()) {
            return redirect('/viewer/login');
        }
        $path = input('get.path', '');
        $list = Logger::getInstance($this->app)->filelist($path);

        return $this->result($list, count($list));
    }

    /**
     * 读取文件内容
     */
    public function read()
    {
        if (!$this->checkLogin()) {
            return redirect('/viewer/login');
        }
        $path = input('get.path');
        $page = input('get.page', 1);
        $page_size = input('get.page_size', 50);
        $content = Logger::getInstance($this->app)->page($path, (int)$page, (int)$page_size);
        return $content;
        //$prefix = '<div style="padding: 5px; font-size: 16px; line-height: 25px;white-space:nowrap;">';
        //$suffix = '</div>';
        //return $prefix . str_replace(["\r\n", "\r", "\n"], '<br/>', htmlspecialchars($content)) . $suffix;
    }

    /**
     * 删除
     */
    public function delete()
    {
        if (!$this->checkLogin()) {
            return redirect('/viewer/login');
        }
        $path = input('post.path');
        $result = Logger::getInstance($this->app)->delete($path);
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

    /**
     * 日志查看器
     * @return void
     */
    public function view()
    {
        // 设置模板引擎参数
        $template = new Template($this->tpl_config);
        if (!$this->checkLogin()) {
            $template->fetch('login');
            return;
        }
        $path = input('get.path');
        $template->assign(['path' => $path]);
        // 读取模板文件渲染输出
        $template->fetch('view');
    }


}