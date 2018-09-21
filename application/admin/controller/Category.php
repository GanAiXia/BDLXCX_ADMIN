<?php
/**
 * Created by PhpStorm.
 * User: GAX
 * Date: 2018-09-14 10:21
 * 网络部-程序小组
 */

namespace app\admin\controller;
use app\admin\common\Base;

class Category extends Base
{
    public function typesCate()
    {
       return $this->fetch('typescate');
    }

    public function typesCateAdd()
    {
        return $this->fetch('typescateadd');
    }
}