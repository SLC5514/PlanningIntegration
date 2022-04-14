<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 图案库控制器
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/2 13:39
 */
class PatternLibrary extends POP_Controller
{
    const COL = 3;

    /*------------------------------------------------------------------------------------------------------------------
     * 图案库列表页
     *----------------------------------------------------------------------------------------------------------------*/
    function __construct()
    {
        parent::__construct();
        $this->load->model(["PatternLibrary_model", "Mihui_model"]);
        $this->assign("col", self::COL);
        $this->get_use_type(self::COL);        
        $this->checkPower();
    }

    public function index($params = "")
    {
        $paramsArr = decodeParams($params);
        $selected = $this->PatternLibrary_model->getSelected($paramsArr, true);
        $this->setTDK("patternlibrary", "index", $selected);
        $this->assign("params", $params);
        $this->assign("key", rawurlencode(rawurlencode(getKeyWord())));
        $this->display("pattern/pattern.html");
    }

    /*------------------------------------------------------------------------------------------------------------------
     * [接口]获取列表（包含 已选择、列表、分页HTML）
     *----------------------------------------------------------------------------------------------------------------*/
    public function getList()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        $key = $this->input->get_post('key');
        //区分栏目
        $column = $this->input->get_post('column');

        $result = [];
        //已选中
        $result["selected"] = $this->PatternLibrary_model->getSelected($paramsArr);
        //取列表
        $limit =$this->input->get_post('pageSize') ? $this->input->get_post('pageSize') : 90;
        $paramsArr = decodeParams($params);
        $page = empty($paramsArr["page"]) ? 1 : intval($paramsArr["page"]);
        $offset = ($page - 1) * $limit;
        $list = $info = [];
        $total = $this->PatternLibrary_model->getList($list, $paramsArr, $offset, $limit,$column);
        $result["list"] = $list;
        $info["total"] = $total;
        $info["pagesize"] = $limit;
        //取分页
        $result["pageHtml"] = $this->makePageHtml($paramsArr, $total, $limit, $page, "/patternlibrary/");
        outPrintApiJson(0, "OK", $result, $info);
    }

    /*------------------------------------------------------------------------------------------------------------------
     * [接口]头部筛选条件（group较慢单独接口）
     *----------------------------------------------------------------------------------------------------------------*/
    public function filterconditions()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        $fileds = ["sPatternContent", "sGender", "sPatternUse", "sPatternFormat", "sAssortColor"];
        //过滤已选中
        foreach ($fileds as $key => $filed) {
            $temp = $this->PatternLibrary_model->solrCondArr[$filed];
            if (isset($paramsArr[$temp])) {
                unset($fileds[$key]);
            }
        }
        $reslut = $this->PatternLibrary_model->getGroupCategory($fileds, $paramsArr);
        outPrintApiJson(0, "OK", $reslut);
    }

    /*------------------------------------------------------------------------------------------------------------------
     * [接口]取饼图(较慢，单独接口)
     *----------------------------------------------------------------------------------------------------------------*/
    public function getPieData()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        $data = $this->PatternLibrary_model->pieData($paramsArr);
        $aco = empty($paramsArr["aco"]) ? "" : intval($paramsArr["aco"]);
        $con = empty($paramsArr["con"]) ? "" : intval($paramsArr["con"]);
        $info = ["aco" => $aco, "con" => $con];
        outPrintApiJson(0, "OK", $data, $info);
    }

    /*------------------------------------------------------------------------------------------------------------------
     * 图案库详情页
     *----------------------------------------------------------------------------------------------------------------*/
    public function detail()
    {
        $this->load->model('Download_model');
        $id = $this->input->get_post("id");
        $table = $tableName = $this->input->get_post("t");
        $table = getProductTableName($table);
        $miHui_detail = $pattern_detail = false;
        if (empty($table) || empty($id)) {
            $data = [];
        } else {
            if ($table == 't_mihui_design_graphic') {
                //智能设计详情
                $data = $this->Mihui_model->getDetailById($id, $table);
                $miHui_detail = true; // 只有智能设计的详情页有重新编辑
                if (empty($data)) {
                    outPrintApiJson(0, 'id不存在');
                }
                $imginfo = api_getPicSize($data["detail_img"][0]["bigPath"]);
                $pathinfo = pathinfo($data["detail_img"][0]["smallPath"]);

                $downloadType = [
                    [
                        "sSmallName" => $pathinfo["basename"],//小图名称
                        "aDownInfo" => [
                            $pathinfo["extension"] => [
                                "type" => $imginfo["type"] ? $imginfo["type"] : "--",//长X宽 尺寸
                                "size" => $imginfo["size"] ? $imginfo["size"] : "--",//文件大小
                                "bp" => urlencode($data["detail_img"][0]["bigPath"]) //大图路径
                            ]
                        ]
                    ]
                ];
                //有PSD时
                $serial = $data['detail_img'][0]["sThirdPicId"];
                if (!empty($data['detail_img'][0]["vectorInfo"])) {
                    $vectorInfo = json_decode($data['detail_img'][0]["vectorInfo"], true);
                    $path_url = $vectorInfo["url"];
                    $size = $vectorInfo["size"];
                    if ($size > 0 && $size < 1024) {
                        $p = 0;
                        $format = "B";
                    }
                    if ($size >= 1024 && $size < pow(1024, 2)) {
                        $p = 1;
                        $format = 'KB';
                    }
                    if ($size >= pow(1024, 2) && $size < pow(1024, 3)) {
                        $p = 2;
                        $format = 'MB';
                    }
                    $size = $size / pow(1024, $p);
                    $size = ceil($size) . $format;

                    $status = 1;
                } else {
                    $generate_status = $this->User_model->mihui_psd_generate_status($serial);
                    $status = $generate_status ? 2 : 3;
                    $size = "--";
                    $path_url = "";
                }
                $downloadType[0]["aDownInfo"]["psd"] = [
                    "type" => "--",//长X宽 尺寸
                    "size" => $size,//文件大小
                    "bp" => $path_url,
                    "status" => $status,
                    "serial" => $serial
                ];
                $data['downloadType'] = $downloadType;
            } else {//图案库详情
                $this->checkPower(self::COL);
                $data = $this->PatternLibrary_model->getDetail($table, $id);
                $downloadType = $this->Download_model->getPicType($id, $table);
                $data['downloadType'] = $downloadType;
                $pattern_detail = true;
            }
        }

        // 详情页获取收藏状态 getProductTableName($table) 转为假表名
        $this->load->model('Collect_model');

        // 图案库 收藏
        if ($pattern_detail) {
            $data['pattern_detail'] = true;
            $sDatabase = 'fashion';
            $data['collect_status'] = $this->Collect_model->getCollectStatus($tableName, $id, $sDatabase, true);
        }

        $this->assign("miHui_detail", $miHui_detail);// 米绘
        $this->assign("pattern_detail", $pattern_detail);// 收藏

        if ($this->input->is_ajax_request()) {
            outPrintApiJson(0, 'ok', $data);
        } else {
            $this->display("pattern/detail.html");
        }

    }

    /*------------------------------------------------------------------------------------------------------------------
    * [接口]获取详情，底部列表
    *----------------------------------------------------------------------------------------------------------------*/
    public function getBottomDataList()
    {
        $this->checkPower(self::COL);
        $limit = 90;
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        $page = empty($paramsArr["page"]) ? 1 : intval($paramsArr["page"]);
        $offset = ($page - 1) * $limit;
        $list = $data = $info = [];
        $total = $this->PatternLibrary_model->getList($list, $paramsArr, $offset, $limit);
        foreach ($list as $key => $val) {
            $data[$key]["t"] = $val["t"];
            $data[$key]["id"] = $val["id"];
            $data[$key]["cover"] = $val["cover"];
            $data[$key]["index"] = $val["index"];
        }
        $info["total"] = $total;
        $info["pagesize"] = $limit;
        outPrintApiJson(0, "OK", $data, $info);

    }

    /**
     * 智能设计花型列表
     */
    public function mihuiDesignList()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        $page = empty($paramsArr["page"]) ? 1 : intval($paramsArr["page"]);
        $pageSize = intval($paramsArr["pageSize"]);
        $pageSize = $pageSize > 0 && $pageSize <= 100 ? $pageSize : 60;
        $data = $info = [];
        $userId = getUserId();
        $openid = $this->Mihui_model->getOpenIdByUserId($userId);
        if ($openid) {

            $offset = ($page - 1) * $pageSize;
            list($lists, $total) = $this->Mihui_model->getMihuiLists($openid, $offset, $pageSize);
            //取分页
            $pageHtml = $this->makePageHtml($paramsArr, $total, $pageSize, $page, "/mihui/mihuiDesignList/");

            $data = [
                'list' => $lists,
            ];
            $info = [
                'total' => $total,
                'pagesize' => $pageSize,
            ];
        }

        if ($this->input->is_ajax_request()) {
            if (intval($total) > 0) {
                outPrintApiJson(0, '请求成功', $data, $info);
            } else {
                outPrintApiJson(0, '没有数据');
            }
        }

        $this->assign('col', 4);
        $this->display('design/designs-list.html');
    }

    /**
     * 批量删除米绘设计的图片
     */
    public function batchdelmihuipic()
    {
        $ids = $this->input->post('ids', true);
        if (empty($ids)) {
            outPrintApiJson(0, '没有选择图片id');
        }
        $delIds = trim(implode(',', $ids), ',');

        $sql = "delete from " . Mihui_model::POP_YUNTU_T_MIHUI_DESIGN_GRAPHIC . "  where id in({$delIds})";
        $affectrows = $this->Mihui_model->db->query($sql);
        if ($affectrows !== false) {
            outPrintApiJson(0, '删除成功');
        }
        outPrintApiJson(0, '删除失败');
    }

}