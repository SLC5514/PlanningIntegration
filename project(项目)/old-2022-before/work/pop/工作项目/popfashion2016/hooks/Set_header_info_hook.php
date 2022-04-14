<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'hooks/POP_Hooks.php';

/**
 * 在你的控制器实例化之后立即执行，控制器的任何方法都还尚未调用。
 */
class Set_header_info_hook extends POP_Hooks
{
    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        // 反馈类型 后台的配置在\data\htdocs\common\category\popfashion\conf\fm_home_feedback_conf.php
        $feedBackConf = array(
            array('id' => 1, 'sName' => '服务咨询'),
            array('id' => 2, 'sName' => '账号购买'),
            array('id' => 3, 'sName' => '商务合作'),
            array('id' => 5, 'sName' => '申请试用'),
            array('id' => 6, 'sName' => '反馈意见'),
            array('id' => 4, 'sName' => '其他')
        );

        $this->ci->smarty->assign('feedbackTypes', $feedBackConf);

        log_message('info', 'Hi, My name is Set_header_info_hook!');
    }

    /**
     * 设置头部的信息
     */
    public function setHeaderInfo()
    {
        log_message('info', 'setHeaderInfo');
        $this->setMemberPower();
        $this->setHeaderAccount();
        // $this->setScreening();
        $this->setGenIndInfo();
        $this->setBrandAllTime();
        $this->randAuthKey();
        $this->messageShow();
        // $this->probationPopup();
    }

    private function setMemberPower()
    {
        // 获取用户身份(游客|普通用户|vip)
        $power = memberPower('other');
        $P_UserType = $power['P_UserType'];
        //ip冲突判断
        if (in_array($P_UserType, [1, 2])) {
            $this->ci->load->model('member_model');
            $this->ci->member_model->checkIpConflit();
        }
        $canChangLoginAccount = $this->ci->member_model->canChangLoginAccount();
        $isCollect = isset($power['P_Collect']) && $power['P_Collect'] ? 1 : 0;

        $this->ci->smarty->assign('canChangLoginAccount', $canChangLoginAccount ? true : false);
        $this->ci->smarty->assign('P_Collect', $isCollect);
        $this->ci->smarty->assign('P_UserId', getUserId());
        $this->ci->smarty->assign('P_UserType', $P_UserType);
    }

    // 设置头部账号信息
    private function setHeaderAccount()
    {
        // 头部的登录注册变换
        $account = '';
        $user_info = get_cookie_value();
        if ($user_info) {
            if (!empty($user_info)) {
                if ($user_info['iAccountType'] == 1) {
                    //主账号
                    $account = $user_info['account'];
                } else {
                    //子账号
                    $account = $user_info['sChildAccount'];
                }
                $this->ci->smarty->assign('P_AccountType', $user_info['iAccountType'] == 1 ? 'main' : 'child');
                $this->ci->smarty->assign('P_AccountId', $user_info['id']);// 账号id
            }
            $this->ci->smarty->assign('account', $account);
        }
        //主账号与子账号组合token(此token用于数据统计，如要修改请一并修改)
        $statistics_token = md5("LR9UxOvtqviGWw0u_" . intval($user_info['id']) . "_" . trim($user_info['sChildID']) . date('Ymd'));
        $this->ci->smarty->assign("statistics_token", $statistics_token);
    }

    // 设置专区通道
    private function setScreening()
    {
        $gens = GetCategory::getGender();//所有性别
        $inds = GetCategory::getTrade();//所有行业
        //性别去除3=>男童,4=>女童，行业去除11215=>综合，12=>帽子围巾，11243=>图案,159=>梭织
        unset($gens[3], $gens[4], $inds[11215], $inds[12], $inds[11243], $inds[159]);
        $this->ci->smarty->assign('gens', $gens);
        $this->ci->smarty->assign('inds', $inds);
    }

    // 设置性别行业cookie
    private function setGenIndInfo()
    {
        // 性别行业参数获取
        $params = $this->ci->uri->rsegment(3, []);
        if ($params) {
            $this->ci->load->model('common_model');
            $paramsInfo = $this->ci->common_model->parseParams($params);
            $gen = intval($paramsInfo['gen']);
            $ind = intval($paramsInfo['ind']);
            if ($gen && $ind) {
                $this->ci->input->set_cookie('gender', $gen, 0);
                $this->ci->input->set_cookie('industry', $ind, 0);
            } elseif ($gen) {
                $this->ci->input->set_cookie('gender', $gen, 0);
            } elseif ($ind) {
                $this->ci->input->set_cookie('industry', $ind, 0);
            }
        }
    }

    private function setBrandAllTime()
    {
        //获取js时间戳
        $brandPath = APPPATH . 'global/js/fashion/brandAll.js';
        $brandAllStaticTime = getFileModifyTime($brandPath, 'brand');    //品牌时间戳
        $this->ci->smarty->assign('brandAllStaticTime', $brandAllStaticTime);
    }

    //趋势参考的随机数
    private function randAuthKey()
    {
        $this->ci->load->model('common_model');
        $randAuthKey = $this->ci->common_model->getRandAuthKey();
        $this->ci->smarty->assign('randAuthKey', $randAuthKey);
    }

    //头部消息感叹号判断
    private function messageShow()
    {
        $this->ci->load->model('member_model');
        $userId = getUserId();
        if ($userId) {
            $orderMessage = $this->ci->member_model->checkNewMessage(1);
            $issetNewMessage = $this->ci->member_model->checkNewMessage(0);
        } else {
            $issetNewMessage = 0;
            $orderMessage = 0;
        }
        if ($orderMessage > 0) {
            $this->ci->smarty->assign('orderMessage', $orderMessage);
        }
        if ($issetNewMessage > 0) {
            $this->ci->smarty->assign('issetNewMessage', $issetNewMessage);
        }
    }

    // 是否显示免费试用结束弹窗
    private function probationPopup()
    {
        $sAccountId = $this->ci->input->cookie('userinfo_id', TRUE);
        if (is_null($sAccountId)) {
            // 未登录，不显示
            $show = 0;
        } else {
            $this->ci->load->model('pop_model');
            // 取试用记录表数据
            $sql = "SELECT id, iAccountId, iIdentityId,sCompany,sCategory,iShowPopup,dCreateTime FROM t_probation_record WHERE iAccountId=? LIMIT 1";
            $row1 = $this->ci->pop_model->query($sql, $sAccountId);
            $row1 = $row1[0];
            if (!is_null($row1) && $row1['iShowPopup']) {
                // 使用记录中对应记录需要显示弹窗，再取权限表数据
                $now = date('Y-m-d H:i:s', time());
                $sql = "SELECT iPrivId,iAccountId,dEndTime FROM fm_privilege WHERE iAccountId= ? AND dEndTime< ? AND iFrom=1 ORDER BY dCreateTime LIMIT 1";
                $row = $this->ci->pop_model->query($sql, array($sAccountId, $now));
                $row = $row[0];
                if (is_null($row)) {
                    // 权限表找不到对应数据，不显示
                    $show = 0;
                } else {
                    // 权限表有对应数据,试用结束,显示
                    $show = 1;
                }
            } else {
                $show = 0;
            }
        }
        $this->ci->smarty->assign('showProbationPopup', $show);
    }
}