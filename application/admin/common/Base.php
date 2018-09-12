<?php
/**
 * Created by PhpStorm.
 * User: GAX
 * Date: 2018-09-12 13:25
 * 网络部-程序小组
 */

namespace app\admin\common;
use think\Controller;
use think\captcha\Captcha;
use think\facade\Session;

class Base extends Controller
{
    protected function verify()
    {
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    3,
            // 关闭验证码杂点
            'useNoise'    =>    false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    protected function isLogin()
    {
        if (!Session::has('user_id')){
            return $this->error('你都没有登录！想干嘛？','admin/user/login');
        }
    }
}