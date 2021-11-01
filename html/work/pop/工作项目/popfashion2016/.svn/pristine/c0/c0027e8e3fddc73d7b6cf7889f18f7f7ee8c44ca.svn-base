<?php

/*
 *@todo 简单行为分析，滑动验证码
 */

class SlideVerification
{
    /*
     * 判断token是否正确
     */
    public $error_message = "";
    public $error_code = 0;
    private $diff_time = 120;
    const MEMCAHE_PRE = 'POP_FASHION_SLIDEVERIFICATION_';//memcache前缀
    function __construct()
    {
        $this->memcache = OpMemcache::getInstance();
    }
    public function checkToken($token, $pop_uid, $action)
    {
        $tokenMemKey = self::MEMCAHE_PRE.$pop_uid.'_'.$action.'_TOEKN';
        $s_token = $this->memcache->get($tokenMemKey);
        $this->memcache->delete($tokenMemKey);
        if(!empty($token) && $s_token == $token){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 人类行为检测
     * 通过则返回 token
     * 不通过返回其他值
     */
    public function humanCheck($jsonData, $pop_uid, $action)
    {
        $actionInfo = json_decode($jsonData, true);
        try {
            if (empty($actionInfo["data"]) || empty($pop_uid) || empty($action)) {
                throw new Exception("参数缺失", 3000);
            }
            //验证完成时间合理性,暂定大于120ms
            $used_time = $actionInfo["end"] - $actionInfo["start"];
            if ($used_time < $this->diff_time) {
                throw new Exception("完成速度不合理", 3001);
            }
            //轨迹检测
            if (!$this->trackCheck($actionInfo)) {
                throw new Exception("运动轨迹不合理", 3002);
            }
            //请求频率检测
            $lastTimeMemKey = self::MEMCAHE_PRE.$pop_uid.'_'.$action.'_LASTTIME';
            $lastTime = $this->memcache->get($lastTimeMemKey);
            $nowTime = time();
            if( $nowTime - $lastTime < 1 ){
                throw new Exception("请求频率过高", 3003);
            }
           
		   /*
			//历史轨迹检测
            if (!$this->historyTrackCheck($actionInfo)) {
                throw new Exception("历史运动轨迹不合理", 3004);
            }
			*/
            $this->memcache->set($lastTimeMemKey,$nowTime,0,10);
            $token = md5($jsonData.$action.POP_GLOBAL_CRONTABS_KEYS);
            $tokenMemKey = self::MEMCAHE_PRE.$pop_uid.'_'.$action.'_TOEKN';
            $this->memcache->set($tokenMemKey,$token,0,3600);
            $result = array("code"=>0,"message"=>"OK","token"=>$token,"action"=>$action);
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            $this->error_code = $e->getCode();
            $result = array("code"=>$this->error_code ,"message"=>$this->error_code==3000?$this->error_message:"您的表现有点超人类了，请再试一次！");
        }
        return $result;
    }

    //验证轨迹合理性
    private function trackCheck($actionInfo)
    {
        $x_arr = $y_arr = $check_arr = [];
        $end_x = $num = 0;
        foreach ($actionInfo["data"] as $xy) {
            $x_arr[] = $end_x = $xy["x"];
            $y_arr[] = $xy["y"];
            $num++;
        }
        $used_time = $actionInfo["end"] - $actionInfo["start"];
        //轨迹至少应该有6个点
        if ($num <= 6) {
            $check_arr[1] = false;
        }
        //X轴完成时间应该越短应该超出(完成时间在180ms以内，鼠标x轴应有超出范围)
        if (($used_time < 180 && $end_x - $actionInfo["max_x"] < 10) || empty($actionInfo["max_x"])) {
            $check_arr[2] = false;
        }
        //y轴
        $y_variance = $this->variance($y_arr);
        if ($y_variance == 0 ) {
            $check_arr[3] = false;
        }
        if (empty($check_arr)) {
            return true;
        } else {
            return false;
        }
    }

    //验证与历史轨迹对比
    private function historyTrackCheck($actionInfo)
    {
        $nowTrack = json_encode($actionInfo["data"]);
        $memcaheKey = self::MEMCAHE_PRE."HISTORYTRACK";
        $historyTracks = $this->memcache->get($memcaheKey);
        if(is_array($historyTracks)){
            foreach ($historyTracks as $historyTrack){
                similar_text($historyTrack,$nowTrack,$percent);
                if($percent>98){
                    return false;
                }
            }
        }else{
            $historyTracks = [];
        }
        array_unshift($historyTracks,$nowTrack);
        if(count($historyTracks)>300){
            $historyTracks = array_slice($historyTracks,0,300);
        }
        $this->memcache->set($memcaheKey,$historyTracks);
        return true;
    }

    //方差计算公式
    private function variance($arr)
    {
        $length = count($arr);
        if ($length == 0) {
            return array(0, 0);
        }
        //求平均值
        $average = array_sum($arr) / $length;
        $count = 0;
        foreach ($arr as $v) {
            $count += pow($average - $v, 2);
        }
        $variance = $count / $length;
        return $variance;
    }
} 
