<?php


return [


    'log' => [
        //默认位置
        'default_type' => 'file',
        // 文件方式
        'file' => [
            'log_dir' => '',
        ],
        //数据库配置
        'database' => [
            'host' => ''
        ],
    ],


    //视图层配置
    'view' => [
        'tpl_replace_string' => [
            '__RES__' => 'https://www.layuicdn.com/',
        ],
        'tpl_cache' => false,
    ],

];