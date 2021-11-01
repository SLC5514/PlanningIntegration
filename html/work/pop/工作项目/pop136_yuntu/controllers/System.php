<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 10:42
 */

/**
 * 系统提示类
 *
 */
class System extends POP_Controller
{
	//中间页面
	public function systemNotice(){
		$type = $this->input->get("type");
		$userId = getUserId();
		if($type==1 && empty($userId)){
			header("Location:/user/login/");
		}
		
		$this->assign("userId",$userId);
		$this->display('system/system-notice.html');
	}

    //米绘、虚拟样衣、图搜图。快照中间页
    public function snapshot(){
        $type = $this->input->get("type");
        $cols = [1=>1,2=>2,3=>4];
        $this->assign("col",$cols[$type]);
        $this->assign("type",$type);
        $this->display("system/no-permission.html");
    }
	//系统升级页面
	public function upgrade(){
		$this->display( 'system/system-upgrade.html' );
	}
}