<?php

namespace yiqiniu\logger\library\traits;

use yiqiniu\logger\library\Base;

/**
 * 认证处理
 * Trait AuthTrait
 */
trait AuthTrait
{

    //登录认证
    protected $auth_config = [
        'enable' => false,
        'encrypt_key' => 'dc748e626aee0ca9111bae791ca76c37',
        'user' => []
    ];

    /**
     * 获取配置参数
     * @return array|mixed
     */
    protected function getAuthConfig()
    {
        $auth = Base::getConfig($this->app, false, 'auth');
        if (!empty($auth)) {
            $auth = array_merge($this->auth_config, $auth);
        }
        return $auth;
    }

    /**
     * 判断 用户是否可能登录
     * @param $username
     * @param $passowrd
     */
    protected function checkUserinfo($username, $passowrd, $auth = null)
    {
        if (empty($auth)) {
            $auth = $this->getAuthConfig();
            if (empty($auth) || empty($auth['user'])) {
                return false;
            }
        }
        $userlist = $auth['user'];
        if (isset($userlist[$username]) && $userlist[$username] === $passowrd) {
            $user = json_encode(['username' => $username, 'password' => $passowrd]);
            cookie('logger_info', $this->_encrypt($user, $auth['encrypt_key']));
            return true;
        }
        return false;
    }

    /**
     * 获取加密后的密码
     * @param $pass
     * @return string
     */
    protected function getPassword($pass)
    {
        return md5($pass);
    }

    /**
     * 判断登录
     * @return bool
     */
    protected function checkLogin()
    {
        $auth = $this->getAuthConfig();
        if (!empty($auth)) {
            if ($auth['enable']) {
                $login = cookie('logger_info');
                if (!empty($login)) {
                    $userinfo = $this->_decrypt($login, $auth['encrypt_key']);
                    try {
                        // 检测用户是否可以登录
                        $userinfo = json_decode($userinfo, true);
                        if (empty($userinfo) || !$this->checkUserinfo($userinfo['username'], $userinfo['password'], $auth)) {
                            return false;
                        }
                        return true;
                    } catch (\think\Exception $e) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return true;
        }
        return true;

    }


    /**
     * 简单对称加密算法之加密
     * @param String $string 需要加密的字串
     * @param String $skey 加密EKY
     * @return String
     * @author Anyon Zou <zoujingli@qq.com>
     * @date 2013-08-13 19:30
     * @update 2014-10-10 10:10
     */
    private function _encrypt($string, $skey)
    {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key] .= $value;
        return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
    }

    /**
     * 简单对称加密算法之解密
     * @param String $string 需要解密的字串
     * @param String $skey 解密KEY
     * @return String
     * @author Anyon Zou <zoujingli@qq.com>
     * @date 2013-08-13 19:30
     * @update 2014-10-10 10:10
     */
    private function _decrypt($string, $skey)
    {
        $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
    }

}