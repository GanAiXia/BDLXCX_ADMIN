<?php
namespace app\admin\controller;
use app\admin\common\Base;

class Index extends Base
{
    public function index()
    {
        $this->isLogin();
        $this->assign('百达丽微信后台首页');
        return $this->fetch('index');
    }
}
