<?php


return [

    'log' => [
        //默认位置
        'default_type' => 'file',
        // 文件方式
        'file' => [
            //查看日志根目录
            'base_dir' => function_exists('runtime_path') ? runtime_path() : '',
            //子目录列表
            'log_dir' => [],
            //显示文件的扩展名
            'format' => ['txt', 'log'],
        ],

        //数据库配置
        'database' => [
            'host' => ''
        ],
    ],


    'auth' => [
        // true启动加密  false不启用
        'enable' => true,
        //加密key
        'encrypt_key' => 'dc748e626aee0ca9111bae791ca76c37',
        //允许登录的用户
        'user' => [
            //用户名: login   密码：123456
            'login' => 'e10adc3949ba59abbe56e057f20f883e'
        ]
    ],
    //视图层配置
    'view' => [
        'tpl_replace_string' => [
            '__RES__' => 'https://www.layuicdn.com/',
        ],
        'tpl_cache' => false,
    ],

];