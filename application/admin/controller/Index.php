<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\facade\Session;
use app\admin\model\User;

class Index extends Base
{
    public function index()
    {
        $this->isLogin();
        $userId = Session::get('user_id');
        $userinfo = User::get($userId);
        $loginTime = $userinfo["login_time"];
        $loginTimes = $userinfo["login_count"];
//        halt($userinfo);
        $this->assign('loginTime',$loginTime);
        $this->assign('loginTimes',$loginTimes);
        $this->assign('百达丽微信后台首页');
        return $this->fetch('index');
    }
}
