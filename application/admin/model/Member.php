<?php
/**
 * Created by PhpStorm.
 * User: GAX
 * Date: 2018-09-13 10:36
 * 网络部-程序小组
 */

namespace app\admin\model;
use think\Model;

class Member extends Model
{
    protected $pk = 'id';
    protected $table = 'bdl_member';

    protected $autoWriteTimestamp = true;
    //定义时间戳字段名:默认为create_time和create_time,如果一致可省略
    //如果想关闭某个时间戳字段,将值设置为false即可:$create_time = false
    protected $createTime = 'create_time'; //创建时间字段
    protected $loginTime = 'login_time'; //创建时间字段
    protected $dateFormat = 'Y年m月d日'; //时间字段取出后的默认时间格式

}