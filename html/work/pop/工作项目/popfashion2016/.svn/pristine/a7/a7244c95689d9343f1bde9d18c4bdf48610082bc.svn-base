<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
  * @todo API
*/
class Api extends POP_Controller
{
    //接口返回的状态码
    public $jsonResult = ["status"=>0, "code"=>0, "msg"=>''];
    //hash密钥
    private $hashStr = '';
    //调试信息记录文件
    private $logFilename = '';

	public function __construct()
    {
		parent::__construct();

        //密钥规则
        $this->hashStr = strtolower(md5("popfashion_fabric_hash" . date("Y-m-d", time())));

        $filename = 'api_'.date('Y-m-d').'.txt';
        $logPath = APPPATH . 'logs/';
        $this->logFilename = $logPath.$filename;
	}

	/*
	  + 权限修改清除cache
	  + POP_GLOBAL_KEYS = acd2605364d8017f0828a26c60cc7f64
	  + /Api/clearPrivilege/?M_ID=106645&&T=1458877059&token=MD5( MD5( M_ID.'-'.T.POP_GLOBAL_KEYS ) )
	*/
	public function clearPrivilege()
    {
		$get = $this->input->get( array( 'M_ID', 'T', 'token' ), TRUE );
		$checkToken = MD5( MD5( $get['M_ID'] . '-' . $get['T'] . POP_GLOBAL_KEYS ) );
		if( ( time() - $get['T'] ) > 600 || $checkToken != $get['token'] )
		{
			return FALSE;
		}
		else
		{
			$this->load->model( 'member_model' );
			$cc = $this->member_model->checkNewVip( $get['M_ID'], TRUE );
			return TRUE;
		}
	}

    /**
     * 查询匹配报告记录列表
     */
    public function reportlists()
    {
        //验证失败
        if (!$this->checkEncryptionRule()) {
            echo json_encode($this->jsonResult);die;
        }
        $this->load->model("details_model");

        $postArg = $this->input->post("Arg");

        //--------------------------API LOGS------------------------------
        // $remoteIp = $this->input->ip_address();
        // $logContents = '';
        // $logContents .= 'Request Time : '. date("Y-m-d H:i:s", time()) . PHP_EOL;
        // $logContents .= 'Clinet IP : '. $remoteIp . PHP_EOL;
        // $logContents .= 'Request Params Arg : '. $postArg . PHP_EOL;
        // file_put_contents($this->logFilename, $logContents, FILE_APPEND);
        //--------------------------API LOGS------------------------------

        $postData = json_decode($postArg, true);
        if (empty($postData)) {
            $this->jsonResult['status'] = 1;
            $this->jsonResult['code'] = 3;
            $this->jsonResult["amount"] = 0;
            $this->jsonResult['msg'] = "参数无效";
            echo json_encode($this->jsonResult);die;
        }

        $dSearchStartTime = !empty($postData["dSearchStartTime"]) ? $postData["dSearchStartTime"]."T00:00:00Z" : '2000-10-01T00:00:00Z';
        $dSearchEndTime = !empty($postData["dSearchEndTime"]) ? $postData["dSearchEndTime"]."T23:59:59Z" : yearTimeDiff('solr_current');
        $iColumnId = $postData["iColumnId"] ? $postData["iColumnId"] : [1,2]; //未来趋势，潮流解析

        $condition = [];
        $condition["iColumnId"] = $iColumnId;
        $condition["other"][] = "(dCreateTime:[{$dSearchStartTime} TO {$dSearchEndTime}])";
        if (!empty($postData["iGender"]))
            $condition["other"][] = "(aLabelIds:{$postData["iGender"]})";  //iGender
        if (!empty($postData["iIndustry"]))
            $condition["other"][] = "(aLabelIds:{$postData["iIndustry"]})";  //iIndustry
        if (!empty($postData["iSeason"]))
            $condition["other"][] = "(aLabelIds:{$postData["iSeason"]})";  //iSeason
        if (!empty($postData["iVisualAngle"]))
            $condition["other"][] = "(aLabelIds:{$postData["iVisualAngle"]})";  //iVisualAngle
        if (!empty($postData["sKeywords"]))
            $condition["other"][] = "(combine:'{$postData["sKeywords"]}')";

        $sort = [];
        $sortValue = isset($postData["sSortValue"]) && !empty($postData["sSortValue"]) ? strtoupper($postData["sSortValue"]) : "DESC";
        switch ($postData["iSort"]) {
            case "V":
                $sort["iViewCount"] = $sortValue;
                // $sort["dCreateTime"] = $sortValue;
                break;
            case "T":
            default :
                $sort["dCreateTime"] = $sortValue;
                break;
        }

        $iPageSize = $postData["iPageSize"] ? intval($postData["iPageSize"]) : 10;
        $iPageNum = $postData["iPageNum"] ? intval($postData["iPageNum"]) : 1;
        $offset = ($iPageNum - 1)*$iPageSize;

        $result = [];
        $total = POPSearch::wrapQueryPopFashionMerger('', $condition, $result, $offset, $iPageSize, $sort);
        if (! $total) {
            $this->jsonResult['status'] = 1;
            $this->jsonResult['code'] = 2;
            $this->jsonResult["amount"] = 0;
            $this->jsonResult['msg'] = "没有查到有效的数据";
            echo json_encode($this->jsonResult);die;
        }

        foreach ($result as $val) {
            $pop_id = $val['pop_id'];
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $iColumnId = end($val['iColumnId']);

            $info = [];
            $info["sReportID"] = $pop_id;//报告唯一ID

            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $data = $data[$id];

            $imagePath = getImagePath($tableName, $data);

            switch ($tableName) {
                case 'specialtopic':
                    $info["sTitle"]         = $data["title"];//标题
                    $info["dCreateTime"]    = $data["addtime"];//创建时间
                    $info["iViewCount"]     = $data["views"];//点击量
                case 'specialtopic_graphic':
                    $info["sTitle"]         = $data["title"];//标题
                    $info["dCreateTime"]    = $data["addtime"];//创建时间
                    $info["iViewCount"]     = $data["views"];//点击量
                    break;
                case 'mostrend_content':
                    $info["sTitle"]         = $data["title"];//标题
                    $info["dCreateTime"]    = $data["release_time"];//创建时间
                    $info["iViewCount"]     = $data["click_rate"];//点击量
                    break;
                case 't_trend_report':
                    $info["sTitle"]         = $data["sTitle"];//标题
                    $info["dCreateTime"]    = $data["dCreateTime"];//创建时间
                    $info["iViewCount"]     = $data["iViewCount"];//点击量
                    break;
                case 'fs_design':
                case 'fs_trend':
                case 'fs_commodity':
                case 'fs_analysis':
                case 'fs_inspiration':
                    $info["sTitle"]         = $data["title"];//标题
                    $info["dCreateTime"]    = $data["create_time"];//创建时间
                    $info["iViewCount"]     = $data["view_count"];//点击量
                    break;
            }
            $fakeTableName = getProductTableName($tableName);

            $info["sCoverImg"]      = $imagePath["cover"];//封面图
            $info["sColumnName"]    = GetCategory::getOtherFromColId($iColumnId,'sName');//栏目名称
            $hash = strtolower(md5("{$pop_id}".date("Y-m-d", time()).POP_GLOBAL_KEYS)); //规则 pop_id+时间+密钥
            $info["sDetailLink"]    = config_item('base_url')."details/report/t_{$fakeTableName}-id_{$id}-col_{$iColumnId}/?Hash={$hash}";//预览报告详情链接地址
            $fabricInfo = $this->details_model->getFabricMatchResult($pop_id);
            $info["iStatus"]        = $fabricInfo['iStatus'] ? intval($fabricInfo['iStatus']) : 0;//发布会状态

            $infos[] = $info;
        }

        $this->jsonResult["status"] = 1;
        $this->jsonResult["amount"] = $total;
        $this->jsonResult["data"] = $infos;


        //--------------------------API LOGS------------------------------
        // if ($infos) {
        //     $logContents .= 'Request Status : SUCCESS' . PHP_EOL;
        // } else {
        //     $logContents .= 'Request Status : No Data' . PHP_EOL;
        // }
        // $logContents .= '-----------------------------' . PHP_EOL;
        // file_put_contents($this->logFilename, $logContents, FILE_APPEND);
        //--------------------------API LOGS------------------------------

        echo json_encode($this->jsonResult);
    }

    /**
     * 报告面料匹配状态接口
     */
    public function reportconfirm()
    {
        //验证失败
        if (!$this->checkEncryptionRule()) {
            echo json_encode($this->jsonResult);die;
        }
        $this->load->model("details_model");

        $postArg = $this->input->post("Arg");
        $postData = json_decode($postArg, true);
        if (empty($postData)) {
            $this->jsonResult['status'] = 1;
            $this->jsonResult['code'] = 3;
            $this->jsonResult['msg'] = "参数无效";
            echo json_encode($this->jsonResult);die;
        }

        $sReportID = $postData["sReportID"];
        $tableName = "`fashion`.`t_report_fabric_rel`";
        $res = $this->details_model->getFabricMatchResult($sReportID);

        if (!empty($res)) {
            //已存在，更新操作
            $condition = ["sReportID"=>$sReportID];
            $re = $this->details_model->executeUpdate($tableName, $postData, $condition);
        } else {
            //不存在，插入操作
            $postData["dCreateTime"] = date("Y-m-d H:i:s", time());
            $re = $this->details_model->executeSave($tableName, $postData);
        }
        $tb = preg_replace('/^(.*?)(_{1})([0-9]*)$/',"$1",$sReportID);
        $id = preg_replace('/^(.*?)(_{1})([0-9]*)$/',"$3",$sReportID);
        $idField = "t_trend_report"==$tb ? "iTopicId":"id";
        $tb = $tb == "mostrend_content" ? "`trends_new`.`mostrend_content`":$tb;
        if($tb == "t_trend_report"){
            $updateTimeField = "dUpdateTime";
        }elseif($tb == "specialtopic_graphic"){
            $updateTimeField = "updatetime";
        }else{
            $updateTimeField = "updateTime";
        }
        $re = $this->details_model->executeUpdate( $tb, array($updateTimeField=>date("Y-m-d H:i:s", time())), array($idField=>$id) );
        if ($re) {
            $this->jsonResult["status"] = 1;
            $this->jsonResult["msg"] = "SUCCESS";
        } else {
            $this->jsonResult["status"] = 1;
            $this->jsonResult['code'] = 4;
            $this->jsonResult['msg'] = "报告面料匹配状态更新失败";
        }

        echo json_encode($this->jsonResult);
    }

    /**
     * 报告面料字段数据接口
     */
    public function reportdict()
    {
        //验证失败
        if (!$this->checkEncryptionRule()) {
            echo json_encode($this->jsonResult);die;
        }

        $data = [];
        $data["gender"] = GetCategory::getGender();
        unset($data["gender"][3]);
        unset($data["gender"][4]);
        $data["industrye"] = GetCategory::getTrade();

        $columnsInfo = GetCategory::getColumns([1,2]);
        foreach ($columnsInfo as $column) {
            $columnId = $column["iColumnId"];
            $columns[$columnId]["name"] = $column["sName"];

            if (isset($column["col"]) && !empty($column["col"])) {
                $childs = $column["col"];
                foreach ($childs as $child) {
                    $childId = $child["iColumnId"];
                    $columns[$columnId]["child"][$childId] = $child["sName"];
                }
            }
        }
        $data["column"] = $columns;
        $data["season"] = GetCategory::getSeason();
        $data["visualAngle"] = GetCategory::getView();

        echo json_encode($data);
    }

    /**
     * 图案素材数据同步增量接口
     */
    public function graphicincr()
    {
        $postArg = $this->input->post('Arg');
        $hashStr = strtolower(md5("popfashion_fabric_hash" . $postArg.date("Y-m-d")));
        //验证失败
        if (!$this->checkEncryptionRule($hashStr)) {
            echo json_encode($this->jsonResult);die;
        }
        $postData = json_decode($postArg, true);
        $iDay = intval($postData['iDay']) ? intval($postData['iDay']) : 1;
        $iPageSize = intval($postData['iPageSize']) ? intval($postData['iPageSize']) : 1000;
        $iPageNum = intval($postData['iPageNum']) ? intval($postData['iPageNum']) : 1;

        $condition['iColumnId'] = 82;
        $startTime = date('Y-m-d', strtotime('-'.$iDay.' day')).'T'.date('H:i:s').'Z';
        $endTime = date("Y-m-d").'T'.date("H:i:s",strtotime("-2 hour")).'Z';
        $condition['other'] = 'dCreateTime:['.$startTime.' TO '.$endTime.']';
        $sort['dCreateTime'] = 'DESC';
        $offset = ($iPageNum - 1)*$iPageSize;
        $result = [];
        $total = POPSearch::wrapQueryPopFashionMerger('', $condition, $result, $offset, $iPageSize, $sort);
        foreach ($result as $key => $val) {
            $info = [];
            $info["sGraphicID "] = $val['pop_id'];// 图案素材唯一ID
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $data = $data[$id];
            $imagePath = getImagePath($tableName, $data);
            $info["sGraphicPath "]      = $imagePath["cover"];//封面图

            $infos[] = $info;
        }
        $this->jsonResult['status'] = 1;
        $this->jsonResult['amount'] = $total;
        $this->jsonResult['data'] = $infos;
        echo json_encode($this->jsonResult);
    }

    /**
     * 获取图案素材删除数据接口
     */
    public function graphicdel()
    {
        $postArg = $this->input->post('Arg');
        $hashStr = strtolower(md5("popfashion_fabric_hash" . $postArg.date("Y-m-d")));
        //验证失败
        if (!$this->checkEncryptionRule($hashStr)) {
            echo json_encode($this->jsonResult);die;
        }
        $postData = json_decode($postArg, true);
        $iDay = intval($postData['iDay']) ? intval($postData['iDay']) : 1;

        $createTime = date('Y-m-d H:i:s', strtotime('-'.$iDay.' day'));
        $sql = 'SELECT id, tablename FROM solr_delete_info WHERE create_time>=?';
        $this->load->model("POP_model");
        $res = $this->POP_model->query($sql, $createTime);
        $infos = [];
        if(!empty($res)) {
            foreach ($res as $key => $val) {
                $infos[] = $val['tablename'].'_'.$val['id'];
            }
        }
        $this->jsonResult['status'] = 1;
        $this->jsonResult['data'] = $infos;
        echo json_encode($this->jsonResult);
    }

    /**
     * 虚拟样衣图 模拟成品 下载
     * 前端下载因IE9版本兼容性问题导致需要走后端下载，所以提供此接口
     */
    public function virtualSplSimulateDown()
    {
        $json = ['code'=>0, 'message'=> '', 'data' => []];

        $imgBody = $this->input->post('imgbody') ;

        if (empty($imgBody)) {
            $json['code'] = 1;
            $json['message'] = '参数错误';

            echo json_encode($json);die;
        }

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgBody, $result)) {

            $imgBody = base64_decode(str_replace($result[1], '', $imgBody));
            $imgName = 'popfashion_' . time() . '.png';
            header("Content-type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header('Content-Disposition: attachment; filename="' . $imgName . '"');
            echo $imgBody;die;
        }
    }


    /**
     * 验证hash
     * @return bool
     */
    private function checkEncryptionRule($hashStr = '')
    {
        $hashTmp = $this->input->post('Hash');

        if ((empty($hashStr) && $hashTmp == $this->hashStr) || (!empty($hashStr) && $hashTmp == $hashStr)) {
            return true;
        } else {
            $this->jsonResult["code"] = 1;
            $this->jsonResult["msg"] = "签名验证不通过";
            return false;
        }
    }
}