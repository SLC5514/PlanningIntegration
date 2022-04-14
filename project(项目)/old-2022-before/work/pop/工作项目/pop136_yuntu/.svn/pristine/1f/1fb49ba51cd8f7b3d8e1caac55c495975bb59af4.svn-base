<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 云图APP -- 以图搜图
 * 继承：APP_Controller
 */

require_once FCPATH . "/core/APP_Controller.php";

class Searchpicture extends APP_Controller
{
    public $pageSize = 10;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取用户是否绑定设备或者设备超限
     * vip用户调用
     * token
     * http://yuntu.pop136.com/api/searchpicture/getbinddeviceinfo
     */
    public function getBindDeviceInfo()
    {
        $this->load->model(['Login_model', 'Account_model']);
        $deviceNumber = $this->input->get_post('deviceNumber');// 设备号，唯一

        // 验证token
        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        // data
        $data = array();
        $data['userId'] = $userId;

        // 判断权限
        $priInfo = $this->Account_model->getUserPowerDate($userId);
        if (!$priInfo) {
            outPrintApiJson(10071, '非VIP用户');
        }

        // 1,判断当前用户在当前设备是否绑定--告诉用户剩余绑定数
        list($all, $total) = $this->Login_model->checkBindDeviceLimit($userId);
        $bindType = $this->Login_model->checkBindDeviceStatus($userId, $deviceNumber);
        // 防错，避免添加权限时，没有分配绑定设备权限
        if (!$all) {
            // 1022,固定
            outPrintApiJson(1022, '该用户没有开通设备绑定权限');
        }
        // 绑定设备超限并且没有绑定该设备。提示总共多少台设备可以绑定
        if (!$total && !$bindType) {
            // 1026,固定
            $data['total'] = $all; // 剩余绑定设备数
            outPrintApiJson(1026, '设备超限，请联系客服', $data);
        }
        if (!$bindType && $total) {
            // 1021,固定    number-剩余绑定设备数为零时，显示total-总的设备数
            //             total-总的设备数不为零时，显示number-剩余绑定设备数
            $deviceData = [
                'userId' => $userId,
                'number' => $total // 总的设备数
            ];
            outPrintApiJson(1021, '该用户没有绑定当前设备', $deviceData);
        }

        outPrintApiJson(0, 'OK');
    }

    /**
     * 获取普通用户的以图搜图的次数
     * http://yuntu.pop136.com/api/searchpicture/gettrynum
     */
    public function getTryNum()
    {
        $this->load->model('Account_model');

        // 验证token
        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        // 没有权限为普通用户 3--以图搜图入口
        $type = 3;
        $priInfo = $this->Account_model->getUserPowerDate($userId);// 判断权限
        if (!$priInfo) {
            $total = $this->Account_model->getYuntuTryNum($userId, $type);
            if (intval($total) >= 3) {
                // 1014,固定
                outPrintApiJson(1014, '普通用户，试用次数结束');
            } else {
                $this->Account_model->addYuntuTryNum($userId, $type);
            }
        }

        // data
        outPrintApiJson(0, 'OK');
    }

    /**
     *  上传图片
     *  post  file  token
     *  http://yuntu.pop136.com/api/searchpicture/uploadpic
     *
     *  线下测试用:  url:'/api/searchpicture/uploadpic/?'+Math.random()  userfile
     */
    public function uploadPic()
    {
        $this->load->model('SearchPicture_model');

        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        // 图片上传
        if (empty($_FILES['file']["tmp_name"])) {
            outPrintApiJson(10073, "参数错误上传图片不存在");
        }

        $this->load->library('POP_Uploadpic');
        $savePath = '/yuntu/upload_pic_material/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';    //图片路径
        $imgPath = $this->pop_uploadpic->uploadPic($_FILES['file'], $savePath);

        //处理返回值
        if ($imgPath == false) {
            outPrintApiJson(10074, "上传图片失败，错误：" . $this->pop_uploadpic->getError());
        } else {
            $result = $this->SearchPicture_model->getUploadPic($userId, $imgPath);
            if (!$result) {
                outPrintApiJson(10077, "以图搜图,上传图片失败");
            }
            outPrintApiJson(0, "OK", $result);
        }

    }

    /**
     *  获取以图搜图列表
     *  图案工艺--以图搜图没有--> $patternTechnology = $this->input->get_post("patternTechnology")
     *  post token
     *  http://yuntu.pop136.com/api/searchpicture/getlist
     *
     *  图片位置：/data/htdocs/pop-apimaterial/yuntu/upload_pic_material/2019/05/07/2507b8070bbe74c832f9fc7ef8bbe7cf.jpg
     *  图片utl: http://img1.yuntu.pop-fashion.com/material/yuntu/upload_pic_material/2019/05/07/2507b8070bbe74c832f9fc7ef8bbe7cf.jpg
     */
    public function getList()
    {
        $this->load->model('SearchPicture_model');
        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        $path = $this->input->get_post("path");// 获取完整域名的图片
        if (empty($path)) {
            outPrintApiJson(10075, "图片不能为空！");
        }
        // 传递的图片处理
        $path = preg_replace('/http(s)?:\/\/(.*?)\//', '/', $path);
        $path = STATIC_URL1 . $path;

        $page = (int)$this->input->get_post("page");

        // 1-最新发布(时间倒序) 2-最受欢迎（浏览量） 3-以图搜图匹配度最高,默认
        $sort = $this->input->get_post("newStorgreet");// 单选
        $sort = $sort ? (int)$sort : 3;

        $patternContent = $this->input->get_post("patternContent");// 图案内容（多选）
        $format = $this->input->get_post("format");// 图案格式（多选）
        $gender = $this->input->get_post("gender");// 性别（多选）
        $application = $this->input->get_post("application");// 局部/满身（多选）

        $patternContent = $this->getParamData($patternContent);
        $format = $this->getParamData($format);
        $gender = $this->getParamData($gender);
        $application = $this->getParamData($application);

        $page = max($page, 1);
        $offset = ($page - 1) * $this->pageSize;

        $paramsArr = array_filter(compact('patternContent', 'format', 'gender', 'application'));

        $info = array();
        list($list, $total) = $this->SearchPicture_model->getList($path, $paramsArr, $offset, $this->pageSize, $sort);
        if (!$list || $total <= 0) {
            outPrintApiJson(10076, "以图搜图，获取数据失败");
        }

        $info["total"] = $total;// 条件筛选后的总数
        $info["pageSize"] = $this->pageSize;// 每页显示多少条

        outPrintApiJson(0, "OK", $list, $info);
    }

    /********************************************* 私有方法 *****************************************/

    // 列表接收的参数转为数组
    private function getParamData($item = '')
    {
        return isset($item) && !empty($item) ? explode(',', $item) : array();
    }
}