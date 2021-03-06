<?php
/**
 * Created by PhpStorm.
 * User: GAX
 * Date: 2018-09-12 13:38
 * 网络部-程序小组
 */

namespace app\admin\controller;
use app\admin\common\Base;
use think\facade\Request;
use app\admin\model\User as UserModel;
use think\facade\Session;

class User extends Base
{
    public function verify()
    {
        return parent::verify(); // TODO: Change the autogenerated stub
    }

    public function login()
    {
        if (Session::has('user_id')){
            return $this->error('已经登陆了！要干嘛这是？','admin/index/index');
        }
        $this->assign('title','百达丽小程序系统登录');
        return $this->fetch('login');
    }

    public function checkLogin()
    {
        $status = 0;
        $result = '验证失败';
        $data = Request::param();
        $rule = [
            'adminuser|用户名'  => 'require',
            'pwd|密码'          => 'require',
            'captcha|验证码'    => 'captcha'
        ];
        $valires = $this->validate($data,$rule);

        if (true === $valires){
            $map = [
                'name'     => $data['adminuser'],
                'password' => md5($data['pwd'])
            ];
            $res = UserModel::get($map);
//            halt($res);
            if (null == $res){
                $result = '用户名或者密码错误';

            }else{
                $status = 1;
                $result = '验证成功！点击确认进入系统！';
                $loginTime = time();
                $res->where('id',$res->id)->update(['login_time' => $loginTime ]);
                $res->setInc('login_count');
                Session::set('user_id',$res->id);
                Session::set('user_info',$res->getData());
            }
            return ['status'=>$status,'message'=>$result,'data'=>$data];
        }else{
            return ['status'=>$status,'message'=>$valires,'data'=>$data];
        }

    }

    public function loginOut()
    {
        Session::delete('user_id');
        Session::delete('user_info');
        return $this->success('即将退出','admin/user/login');
    }
}