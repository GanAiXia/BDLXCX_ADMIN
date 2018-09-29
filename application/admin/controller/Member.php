<?php
/**
 * Created by PhpStorm.
 * User: GAX
 * Date: 2018-09-13 10:35
 * 网络部-程序小组
 */

namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Member as MemberModel;
use function PHPSTORM_META\type;
use think\facade\Request;
use app\admin\common\WXBizDataCrypt;
use think\response\Json;

class Member extends Base
{
    public function index()
    {

    }

    public function memberList()
    {
        $userInfo = MemberModel::all();
        $this->assign('userinfo',$userInfo);
        $this->assign('title','会员列表页');
        return $this->fetch('memberlist');
    }

    public function memberCheck()
    {
        $res = Request::post();
        if ($res){
//            halt($res);
            $appid = 'wx664a95db32be64ec';
            $secret = '906d04eb24bd04cae70c5f0a32c3a938';
//            $encryptedData= $res['encryptedData'];
//            $iv = $res['iv'];
            $code = $res['code'];
            $api = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";

            function httpGet($url){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 2);
                curl_setopt($curl, CURLOPT_TIMEOUT, 500);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($curl, CURLOPT_URL, $url);
                $res = curl_exec($curl);
                curl_close($curl);
                return $res;
            }
            $apiList = httpGet($api);

            return  $apiList;
        }
    }

    public function memberAdd()
    {
        $res = Request::post();
//        halt($res);
        if ($res){
            $userDef = $res['userDef'];
            $openid = $res['openId'];
            if ($userDef){
                $resuser = MemberModel::where('openid',$openid)
                            ->select();

                for ($i = 0; $i < count($resuser); $i++){
                    $userid = $resuser[$i]['id'];
//                    halt($userid);
                    $demo = MemberModel::get($userid);
                    $demo->userDef = "false";
                    $demo->save();
//                    halt($demo);
                }
                $data =  [
                    'username' => $res['userName'],
                    'nickname' => $res['nickName'],
                    'openid'   => $res['openId'],
                    'telphone' => $res['userPhone'],
                    'addres'   => $res['userAddrDetail'],
                    'sex'      => $res['gender'],
                    'avatarUrl'=> $res['avatarUrl'],
                    'userDef'  => 'true',
                    'sheng'    => $res['sheng'],
                    'shi'      => $res['shi'],
                    'qu'       => $res['qu'],
                    'addrdetail'=> $res['addrDetail']
                ];
                $resdata  = MemberModel::create($data);
                if ($resdata){
                    return Json::create(['data'=>$data,'res'=>1]);
                }else{
                    return Json::create(['data'=>$data,'res'=>0]);
                }

            }else{
                $data =  [
                    'username' => $res['userName'],
                    'nickname' => $res['nickName'],
                    'openid'   => $res['openId'],
                    'telphone' => $res['userPhone'],
                    'addres'   => $res['userAddrDetail'],
                    'sex'      => $res['gender'],
                    'avatarUrl'=> $res['avatarUrl'],
                    'userDef'  => 'false',
                    'sheng'    => $res['sheng'],
                    'shi'      => $res['shi'],
                    'qu'       => $res['qu'],
                    'addrdetail'=> $res['addrDetail']
                ];
                $resdata  = MemberModel::create($data);
                if ($resdata){
                    return Json::create(['data'=>$data,'res'=>1]);
                }else{
                    return Json::create(['data'=>$data,'res'=>0]);
                }

            }

        }else{
            return Json::create(['res'=>0]);
        }
    }

    public function getAddr()
    {
        $data = Request::post('openid');

        $res = MemberModel::where('openid',$data)
            ->select();
        return $res;
    }

    public function userDefchange()
    {
        $res = Request::post();
        $openid = $res['openId'];
        $userIndex = $res['userDefIndex'];
        $resuser = MemberModel::where('openid',$openid)
            ->select();

        for ($i = 0; $i < count($resuser); $i++){
            $userid = $resuser[$i]['id'];
//                    halt($userid);
            $demo = MemberModel::get($userid);
            $demo->userDef = "false";
            $demo->save();
//                    halt($demo);
        }

        $userdefnewid = $resuser[$userIndex]['id'];
        $userdefnew = MemberModel::get($userdefnewid);
        $userdefnew->userDef = "true";
        $userdefnew->save();

        return Json::create(['res'=>'修改成功']);

    }

    public function delAddr()
    {
        $data = Request::post();
        $openid = $data['openId'];
        $delIndex = $data['delIndex'];
        $resuser = MemberModel::where('openid',$openid)
            ->select();
        $userId = $resuser[$delIndex]['id'];
        $userDef = $resuser[$delIndex]['userDef'];
//        halt($userDef);
        if ($userDef == "false"){
            $res = MemberModel::where('id',$userId)
                ->delete();
        }else{
            $res = MemberModel::where('id',$userId)
                ->delete();
            $userDefault = MemberModel::where('openid',$openid)
                ->select();
            if (!empty($userDefault)){
                $userDefaultid = $userDefault[0]['id'];
                $userdefnew = MemberModel::get($userDefaultid);
                $userdefnew->userDef = "true";
                $userdefnew->save();
            }
        }

        return Json::create(['res'=>1]);

    }

    public function editAddr()
    {
        $data = Request::post();
        $openid = $data['openId'];
        $editIndex = $data['editIndex'];
        $resuser = MemberModel::where('openid',$openid)
            ->select();
//        halt($resuser[$editIndex]);
        return $resuser[$editIndex];
    }

    public function memberEdit()
    {
        $res = Request::post();
//        halt($res);
        if ($res){
            $userDef = $res['userDef'];
            $openid = $res['openId'];
            $editid = $res['editIndex'];
            $resuser = MemberModel::where('openid',$openid)
                ->select();
            if ($userDef === 'true'){
                for ($i = 0; $i < count($resuser); $i++){
                    $userid = $resuser[$i]['id'];
//                    halt($userid);
                    $demo = MemberModel::get($userid);
                    $demo->userDef = "false";
                    $demo->save();
//                    halt($demo);
                }
                $data =  [
                    'username' => $res['userName'],
                    'nickname' => $res['nickName'],
                    'openid'   => $res['openId'],
                    'telphone' => $res['userPhone'],
                    'addres'   => $res['userAddrDetail'],
                    'sex'      => $res['gender'],
                    'avatarUrl'=> $res['avatarUrl'],
                    'userDef'  => 'true',
                    'sheng'    => $res['sheng'],
                    'shi'      => $res['shi'],
                    'qu'       => $res['qu'],
                    'addrdetail'=> $res['addrDetail']
                ];
                $eidtUserId = $resuser[$editid]['id'];
                $resdata  = MemberModel::where('id',$eidtUserId)
                    ->update($data);
//                halt($resdata);
                if ($resdata){
                    return Json::create(['data'=>$data,'res'=>1]);
                }else{
                    return Json::create(['data'=>$data,'res'=>0]);
                }

            }else{
                $data =  [
                    'username' => $res['userName'],
                    'nickname' => $res['nickName'],
                    'openid'   => $res['openId'],
                    'telphone' => $res['userPhone'],
                    'addres'   => $res['userAddrDetail'],
                    'sex'      => $res['gender'],
                    'avatarUrl'=> $res['avatarUrl'],
                    'userDef'  => 'false',
                    'sheng'    => $res['sheng'],
                    'shi'      => $res['shi'],
                    'qu'       => $res['qu'],
                    'addrdetail'=> $res['addrDetail']
                ];
                $eidtUserId = $resuser[$editid]['id'];
                $resdata  = MemberModel::where('id',$eidtUserId)
                    ->update($data);
//                halt($resdata);
                if ($resdata){
                    return Json::create(['data'=>$data,'res'=>1]);
                }else{
                    return Json::create(['data'=>$data,'res'=>0]);
                }

            }

        }else{
            return Json::create(['res'=>0]);
        }
    }
}