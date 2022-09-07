<?php

namespace yiqiniu\logger;

use think\Route;
use think\Service;
use yiqiniu\logger\library\Base;
use yiqiniu\logger\library\HomeApi;


class BootService extends Service
{

    //配置文件名
    private const CONFIG_FILE = 'yqn_logger.php';

    public function boot(Route $route)
    {

        //检查配置文件是否存在
        Base::checkConfigFileExist($this->app);

        // 查看日志的路由
        $route->group('viewer', function () use ($route) {
            $route->get('/', function () {
                return HomeApi::getInstance($this->app)->index();
            });
            $route->get('login', function () {
                return HomeApi::getInstance($this->app)->login();
            });
            $route->get('logout', function () {
                return HomeApi::getInstance($this->app)->logout();
            });
            $route->post('auth', function () {
                return HomeApi::getInstance($this->app)->auth();
            });
            $route->get('index', function () {
                return HomeApi::getInstance($this->app)->index();
            });
            $route->get('tree', function () {
                return HomeApi::getInstance($this->app)->treelist();
            });
            $route->get('list', function () {
                return HomeApi::getInstance($this->app)->filelist();
            });
            // 查看日志
            $route->get('view', function () {
                return HomeApi::getInstance($this->app)->view();
            });
            // 读取内容
            $route->get('read', function () {
                return HomeApi::getInstance($this->app)->read();
            });
            $route->post('del', function () {
                return HomeApi::getInstance($this->app)->delete();
            });
        })->mergeRuleRegex();
    }
}



