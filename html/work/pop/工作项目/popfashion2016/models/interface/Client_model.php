<?php
class Client_model extends POP_Model
{
	
	
	private $childUserTable = "`fashion`.`fashion_user_child"; //服装子账号表
	//用户表
	private $UserTables = [
		1 => "`fashion`.`fashion_user",
		2 => "`popbags`.`shoe_user`",
		3 => "`pop136`.`fashion_user",
		4 => "`decoration`.`shoe_user`",
		5 => "`hometextile`.`shoe_user`",
	];
	//用户绑定信息表
	private $clientTables = [
		1 => "`fashion`.`client_log`",
		2 => "`popbags`.`client_log`",
		3 => "`new_pop_shoe`.`client_log_shoe`",
		4 => "`decoration`.`client_log`",
		5 => "`hometextile`.`client_log`",
	];
	private $logClient = '`pop136`.`log_client`';//客户端登录错误日志表
	
	/*------------------------------------------------------------------------------------------------------------------
	* @todo  客户端绑定 
	* @param $account   账号
	* @param $password  密码
	* @param $Site      站点
	* @return 返回主账号信息，无则为空
	*-----------------------------------------------------------------------------------------------------------------*/
	public function getUserInfo($account,$password,$Site){
		$UserInfo = [];
		$UserTable = $this->UserTables[$Site];
		$isChild = false;
		//查询是否是子账号
		if($Site == 1){
			$ChildUser = $this->db()->where(['sChildAccount' => $account,'sPassword'=>$password])->get($this->childUserTable)->row();
			$MainAccounId = $ChildUser->iParentID;
			$isChild = true; 
		}
		//查询账号信息
		if(!empty($MainAccounId)){
			$UserInfo = $this->db()->where( ['id' => $MainAccounId] )->get($UserTable)->row_array();
		}else{
			if(in_array($Site,[1,3])){
				$UserInfo = $this->db()->where( ['account' => $account,'passwd'=>$password] )->get($UserTable)->row_array();
			}else{
				$UserInfo = $this->db()->where( ['account' => $account,'password'=>$password] )->get($UserTable)->row_array();
			}
			//分表站点无，但是pop136表中存在时
			if(empty($UserInfo) && $Site!=3){
				$UserInfo = $this->db()->where( ['account' => $account,'passwd'=>$password] )->get($this->UserTables[3])->row_array();
			}
		}
		if( !empty($UserInfo) ){
			$this->userType($UserInfo,$Site);
		}
		$UserInfo["isChild"] = $isChild;//是否子账号
		return $UserInfo;
	}
// 是否vip/试用账号(用户类型)【userType normal=>普通， trial=>试用VIP ，vip=>VIP】
	private function userType(&$UserInfo,$Site){
		$userType = "normal";
		$nowTime = time();
		$now = date('Y-m-d H:i:s',$nowTime);
		$UserId = $UserInfo["id"];
		switch($Site){
			case 1: //服装
                $sql = 'SELECT iType FROM `fashion`.`fm_privilege` WHERE iAccountId = ? AND dEndTime >=  ? AND dStartTime <= ? order by dEndTime DESC LIMIT 1';
                $aPowerInfo = $this->query($sql, [$UserId, $now, $now]);
				if($aPowerInfo[0]["iType"]==3){
					$userType = 'vip';
				}elseif($aPowerInfo[0]["iType"]==1){
					$userType = 'trial';
				}
				break;
            case 5: //家纺
                $sql = 'SELECT i_type FROM `pop_home_textiles`.`t_privilege` WHERE i_user_id = ? AND d_end_time >=  ? AND d_start_time <= ? order by d_end_time DESC';
                $aPowerInfo = $this->query($sql, [$UserId, $now, $now]);
                foreach ($aPowerInfo as $_temp){
                    if($_temp["i_type"]==1){
                        $userType = 'vip';
                        break;
                    }elseif($_temp["i_type"]==2){
                        $userType = 'trial';
                    }
                }
                break;
			case 2: //箱包
			case 4: //首饰
				if($UserInfo["vip_type"]==1 && strtotime($UserInfo["consume_end_time"]) > $nowTime){
					$userType = 'vip';
				}elseif($UserInfo["trail"] && strtotime($UserInfo["trail_begin_time"]) < $nowTime && strtotime($UserInfo["trail_end_time"]) > $nowTime){
					$userType = 'trial';
				}
				break;
			case 3: //鞋子
				$power = json_decode($UserInfo["shoe_power"],true);
				if( $power["vip_type"]==1 && strtotime($power["vip_start_time"])<$nowTime && strtotime($power["vip_end_time"])>$nowTime )
				{
					$userType = 'vip';
				}elseif($power['trial_status'] == 1 && $nowTime > strtotime($power['trial_start_time']) && $nowTime < strtotime($power['trial_end_time']))
				{
					$userType = 'trial';
				}
				$UserInfo["client_type"] = $power["client_type"];
				$UserInfo["client_num"] = $power["client_num"];
				break;
		}
		$UserInfo["userType"] = $userType;
	}
	//查询当前用户绑定信息
	public function getBindingInfo($site,$account,$mac){
		$mac = explode('|',$mac);
		$table = $this->clientTables[$site];
		$BindingInfo = [];
		if(!empty($table)){
			$BindingInfo = $this->db()->where(['user_account' => $account])->where_in('mac',$mac)->get($table)->row_array();
		}
		return $BindingInfo;
	}
	//查询当前用户绑定总数
	public function getBindingCount($site,$account){
		$table = $this->clientTables[$site];
		$BindingInfo = [];
		if(!empty($table)){
			$BindingInfo = $this->db()->from($table)->where(['user_account' => $account])->count_all_results();
		}
		return $BindingInfo;
	}
	//用户绑定
	public function addBindingInfo($site,$account,$mac){
		$data = array(
			'user_account' => trim($account),
			'mac' => trim($mac),
			'create_time' => date("Y-m-d H:i:s")
		);
		$result = $this->db()->insert($this->clientTables[$site], $data);
		return $result;
	}
	//写入登录日志
	public function addLoginLog($data){
		$result = $this->db()->insert($this->logClient, $data);
		return $result;
	}
}