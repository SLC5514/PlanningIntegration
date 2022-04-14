<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoopRequire extends POP_Controller
{
    private $msg = [
        1001 => "token验证失败!",
        1002 => "已超时",
        1011 => "参数错误！",
        1012 => "唯一串不存在！",
        1013 => "轮询超时!",
    ];
    private $stop = ["stop" => 1]; //停止定时器传此参数

    public function start()
    {
        $this->checkToken();
        $MemcacheKey = $this->getMemcacheKey();
        $this->updateMemcache($MemcacheKey);
    }

    //验证token
    private function checkToken()
    {
        if (!isset($_GET["token"]) || !isset($_GET["time"])) {
            outPrintApiJson(1011, $this->msg[1011], $this->stop);
        } else {
            $cToken = trim($_GET["token"]);
            $time = trim($_GET["time"]);
        }
        $userId = @getUserId();
        $sToken = @getToken($userId, $time);
        if ($cToken != $sToken) {
            outPrintApiJson(1001, $this->msg[1001], $this->stop);
        }
    }

    //验证唯一串是否存在,存在则返回Memcache的Key
    private function getMemcacheKey()
    {
        if (!isset($_COOKIE["POP_UID"])) {
            outPrintApiJson(1012, $this->msg[1012], $this->stop);
        } else {
            $popUid = trim($_COOKIE["POP_UID"]);
        }
        $MemcacheKey = UID_MEMKEY_PRE . $popUid;
        return $MemcacheKey;
    }

    //更新memcache
    private function updateMemcache($MemcacheKey)
    {
        $this->load->driver('cache');
        $main_user_id = getUserId(true);
        $timeOut = USER_TIME_OUT;
        $value = $this->cache->memcached->get($MemcacheKey);
        if ($value && $value + $timeOut > time()) {
            $this->cache->memcached->save($MemcacheKey, $value, UID_MEMKEY_TIME);
            outPrintApiJson(0, "OK", ["stop" => 0], date("Y-m-d H:i:s"));
        } else {
            outPrintApiJson(1013, $this->msg[1013], $this->stop);
        }
    }
}
