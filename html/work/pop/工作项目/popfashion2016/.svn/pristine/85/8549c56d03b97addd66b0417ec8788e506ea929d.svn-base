<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 款式库ajax请求
 */
class Styleajax extends POP_Controller
{
    public $arr = [];

    public function __construct()
    {
        $this->arr = [
            'gen' => 'iGender',
            'gcen' => 'iSubGender',
            'ind' => 'iIndustry',
            'sea' => 'iSeason',
            'reg' => 'iRegion',
            'exh' => 'sExhibitionName',
            'mar' => 'sMarketType',
            'bpos' => 'sMarketHotPosition',
            'sta' => 'sStarLabel',
            'bra' => 'iBrand',
            'cat' => 'iCategory',
            'aco' => 'sAssortColor',
            'age' => 'sAgeLayer',
            'wea' => 'sDress',
            'regs' => 'sRegionStyle',
            'cha' => 'sCharacterStyle',
            'fas' => 'sFashionWeek',
            'str' => 'iStreetBeatType',
            'man' => 'sManner',
            'shap' => 'sShape',
            'spe' => 'sSpecifics',
            'tech' => 'sTechnology',
            'pat' => 'sPattern',
            'fab' => 'sFabric',
            'acc' => 'sAccessory',
            'key' => 'sKeywords',
            'prop' => 'iStyleOrManuscript',
            'ds' => 'iGlobalPhotoShotTab',
        ];
        parent::__construct();
        $this->load->model(['common_model', 'styles_model']);

        if (!$this->input->is_ajax_request()) {
            echo 'No direct script access allowed';
            exit;
        }
    }

    /**
     * 保存筛选条件
     * 接口返回值说明
     * code: 状态值含义
     * 0=> 保存成功，列表请求成功，删除成功,置顶成功
     * 1=> 未登陆，无法保存
     * 2=> 子账号VIP保存记录>10条
     * 3=> 试用用户保存记录大于1条
     * 4=> 主账号VIP用户保存记录>1条 ---- 20190904修改主账号保存记录同子账号相同
     * 5=> 普通用户保存记录>1条
     * 6=>删除失败
     * 7=> 没有可保存的筛选条件
     * 8=> 保存失败
     * 9=> 列表为空
     * 10=>是否需要置顶
     */
    public function ScreeningCondition()
    {
        //前台字段映射数据表字段

        //获取全部post数据
        $post = $this->input->post();
        //获取栏目ID
        $columnId = $post['colId'];
        //获取关键字
        $keywords = $post['key'];
        //获取性别
        $gender = $this->input->cookie('gender', TRUE);
        //获取行业
        $industry = $this->input->cookie('industry', TRUE);
        //获取参数
        $params = $post['params'];
        //参数处理
        $params = $this->common_model->restoreParams($params); //参数还原
        $paramsArr = $this->common_model->parseParams($params, 1);//参数字符串转为对应数组
        if (!isset($paramsArr['gen'])) {
            if ($gender) {
                $paramsArr['gen'] = $gender;
            }
        }
        if (!isset($paramsArr['ind'])) {
            if ($industry) {
                $paramsArr['ind'] = $industry;
            }
        }
        if ($paramsArr['gen'] == 0) {
            $paramsArr['gen'] = 0;
        }
        if ($paramsArr['ind'] == 0) {
            $paramsArr['ind'] = 0;
        }

        if ($keywords) {
            $search = $this->common_model->getSearchSufix('', TRUE);
            if (stripos($search, '?key=') !== FALSE) {
                $keywords = ltrim($search, '?key=');
            } else {
                $keywords = $search;
            }
            $paramsArr['key'] = $keywords;
        }
        //获取登录状态和权限 账号类型 P_UserType => 1 主账号vip , 2子账号vip 、3试用 4普通 5游客
        $powers = memberPower('other');
        //获取用户ID
        $userId = getUserId();
        //过滤不需要插入的字段
        $ignoreFields = ['dis', 'sor', 'tim', 'coll', 'page'];
        foreach ($ignoreFields as $val) {
            if (key_exists($val, $paramsArr)) {
                unset($paramsArr[$val]);
            }
        }
        ksort($paramsArr);
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $this->common_model->getSearchSufix($keywords);

        // 去掉params中的key
        $_paramsArr = $paramsArr;
        unset($_paramsArr['key']);
        $param = $this->common_model->parseParams($_paramsArr, 2);//数组转为字符串
        if ($param == 'gen_0-ind_0' && $keywords == '' && $powers['P_UserType'] != 5) {
            echo json_encode(array('code' => 7, 'msg' => '没有可保存的筛选条件', 'data' => array()));
            die;
        }
        if ($param) {
            $link = trim($rootUrl . $param . '/' . $search);
        } elseif (($keywords != null) && !isset($param)) {
            $link = trim($rootUrl . $search);
        }

        //根据用户类型判断用户的保存条数,并把条件记录写入表中
        $conditions = $this->styles_model->getSaveConditions($powers['P_UserType'], $userId);
        $saveCondition = $this->styles_model->saveCondition($this->arr, $paramsArr, $powers['P_UserType'], $userId, $conditions, $link, $columnId);

        //返回值
        echo json_encode($saveCondition);
        return;
    }

    /**
     * 把重复记录置顶
     */
    public function listTop()
    {
        $id = $this->input->post('id');
        $rs = $this->styles_model->listTop($id, $this->arr);
        echo json_encode($rs);
        return;
    }

    /**
     * 获取条件列表
     */
    public function getScreeningList()
    {
        //获取用户ID
        $userId = getUserId();
        //获取登录状态和权限 账号类型 P_UserType => 1 主账号vip , 2子账号vip 、3试用 4普通 5游客
        $powers = memberPower('other');
        if ($powers['P_UserType'] == 5) {
            echo json_encode(array('code' => 9, 'msg' => '列表为空', 'data' => array()));
            die;
        }

        //获取筛选条件列表
        $conditions = $this->styles_model->getSaveConditions($powers['P_UserType'], $userId);
        $conList = $this->styles_model->getScreeningList($conditions, $powers, $this->arr);

        echo json_encode($conList);
        return;
    }

    /**
     * 删除条件列表
     */
    public function delScreeningList()
    {
        $id = $this->input->post('id');
        $id = trim(intval($id));
        if ($id) {
            $data = $this->styles_model->delScreeningList($id, $this->arr);
        } else {
            $code = 6;
            $msg = 'id不存在';
            $data = array('code' => $code, 'msg' => $msg, 'data' => array());
        }
        echo json_encode($data);
        return;
    }

}