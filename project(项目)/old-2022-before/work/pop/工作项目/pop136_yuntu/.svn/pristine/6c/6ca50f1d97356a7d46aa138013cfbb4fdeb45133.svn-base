<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $this->checkPower();
        $user_id = getUserId(true);
        $this->load->model('User_model');
        $lastPowerApply3D = $this->User_model->lastPowerApply3D($user_id);
        $powerInfo = $this->User_model->getUserPowerDate($user_id);
        if(!empty($powerInfo)){
            $dEndTime = array();
            foreach ($powerInfo as $item) {
                $dEndTime[$item["sColumn"]] = $item["dEndTime"];
            }
            $maxEndTime = max($dEndTime);
            if ( strtotime($maxEndTime) > strtotime('+2 month')) {
                $this->assign("canTrailTime3D", "2个月");
            } else {
                $this->assign("canTrailTime3D", ceil((strtotime($maxEndTime)-time())/86400)."天");
            }

        }
        $this->assign("lastPowerApply3D", $lastPowerApply3D);
        $this->setTDK("home", "index");
        $this->display('home.html');
    }

}