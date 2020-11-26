# 日志查看器
## 使用方法
 composer require yiqiniu/logger-viewer=dev.master
 
 
## 访问方法
    http:*****/logger
    
 
## 自定义认证
   配置文件 yqn_logger.php 
   
   ```
    'auth' => [
        // true启动加密  false不启用
        'enable' => true,
        //加密key
        'encrypt_key' => '',
        //允许登录的用户,密码采用md5加密
        'user' => [

            'login' => 'e10adc3949ba59abbe56e057f20f883e'
        ]
    ],
```