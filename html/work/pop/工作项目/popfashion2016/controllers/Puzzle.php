<?php

/*
  + 拼图接口
*/

class Puzzle extends POP_Controller
{
    // 签名字符串
    const PUZZLE_HASH_KEY = 'Pop_Puzzle';
    // 站点
    private $site = 1; // 1-服装趋势，2-箱包，3-鞋子，4-首饰，5-家纺，多个‘,’隔开
    // 返回状态码
    const RETURN_CODE_SUCCESS = 1; // 成功
    const RETURN_CODE_FAILURE = 0; // 失败
    const RETURN_CODE_HASH_ERROR = 1001; // hash值不正确
    // 表名
    const TABLE_TREND_SUBREPORT_PUZZLE = 't_trend_subreport_puzzle'; // 模版拼图信息表；不用了,数据库表没有
    const TABLE_PUZZLE_TEMPLATES = 't_puzzle_templates'; // 模版表
    const TABLE_PUZZLE_LOCAL_PHOTOS = 't_puzzle_local_photos'; // 本地图片表

	const TABLE_TREND_REPORT = 't_trend_report'; // 主报告 表
	const TABLE_TREND_SUB_REPORT = 't_trend_subreport'; // 子报告 表
	const TABLE_TREND_SUB_REPORT_PAGE = 't_trend_subreport_page'; // 子报告页 表

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /**
     * 输出json_encode结果到浏览器
     * @param int $code 1-成功，0-失败
     * @param string $message 提示消息
     * @param array $data 返回数据
     * @param bool $die 是否输出后die
     */
    private function outputJson($code = self::RETURN_CODE_FAILURE, $message = false, $data = false, $die = true)
    {
        $ret = array(
            'code' => $code,
            //'message' => $message,
            //'data'    => $data
        );
        if ($message !== false) {
            $ret['message'] = $message;
        }
        if ($data !== false) {
            $ret['data'] = $data;
        }
	    //跨域访问的时候才会存在此字段
	    // $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
	    // $allow_origin = array(
		//     'http://ed.pop-fashion.com',
	    // );
	    // if (in_array($origin, $allow_origin)) {
		//     header('Access-Control-Allow-Origin:' . $origin);
	    // }

	    header('Content-Type: application/json; charset=utf-8');
        if (defined('JSON_UNESCAPED_UNICODE')) {
            echo json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo json_encode($ret);
        }
        $die && die();
    }

    /**
     * 签名验证
     * @param bool $allowGet 签名参数默认只允许post
     */
    private function signVerify($allowGet = false)
    {
        if ($allowGet) {
            $hash = $this->input->get_post('hash', true);
        } else {
            $hash = $this->input->post('hash', true);
        }
        $sign = md5(self::PUZZLE_HASH_KEY . date('Y-m-d'));
        $sign = strtolower($sign);
        if ($hash !== $sign) {
            $this->outputJson(self::RETURN_CODE_HASH_ERROR, 'hash值不正确', false);
        }
        // $site 区分取哪个站的库
        $_site = $this->input->get_post('site', true);
        $this->site = $_site ? intval($_site) : 1;
    }


    /**
     * 获取版面数据
     */
    public function getPuzzleLayout()
    {
        $this->signVerify();
        $id = $this->input->post('id', true);
        if (empty($id)) {
            // 未传id则为新建，直接返回空
            $this->outputJson(self::RETURN_CODE_SUCCESS, false, []);
        }
        $sql = "SELECT id,iPageId,sPuzzleId,sContent FROM `" . self::TABLE_TREND_SUBREPORT_PUZZLE . "` WHERE sPuzzleId=?";
        $query = $this->common_model->db()->query($sql, [$id]);
        $row = $query->row_array();
        $this->common_model->db()->close();
        if (is_null($row)) {
            $detail = [];
        } else {
            $detail = $row['sContent'];
            $detail = json_decode($detail, true);
        }
        $this->outputJson(self::RETURN_CODE_SUCCESS, false, $detail);
    }

    /**
     * 获取模板数据
     * 测试数据[{"x":0,"y":0,"width":100,"height":100}]
     */
    public function getPuzzleTemplates()
    {
        $this->signVerify();
        $sql = "SELECT id,sTemplateThumbPhoto,sTemplateAreaDetails,sSite FROM `" . self::TABLE_PUZZLE_TEMPLATES . "` WHERE sSite=? AND iStatus=1";
        $query = $this->common_model->db()->query($sql, 1);
        $ret = array();
        $imgUrl = [
            1 => 'http://img1.fm.pop-fashion.com', // 服装
            2 => 'http://img1.fm.pop-fashion.com', // 箱包
            3 => 'http://img1.s.pop-fashion.com', // 鞋子
            4 => 'http://img1.fm.pop-fashion.com', // 首饰
            5 => 'http://img1.fm.pop-fashion.com', // 家纺
        ];
        foreach ($query->result_array() as $row) {
            $ret[] = [
                'id' => $row['id'],
                'thumb' => $imgUrl[$this->site] . $row['sTemplateThumbPhoto'],
                'grids' => json_decode($row['sTemplateAreaDetails'])
            ];
        }
        if ($ret) {
            // 为了解决flash跨域，第一张模板图使用服装图片域名，其他的使用所在站点的图片域名
            $ret[0]['thumb'] = str_replace($imgUrl[$this->site], 'http://img1.fm.pop-fashion.com', $ret[0]['thumb']);
        }
        $this->common_model->db()->close();
        $this->outputJson(self::RETURN_CODE_SUCCESS, false, $ret);
    }

    /*
      + 上传本地图片
    */
    public function uploadLocalPhoto()
    {
        // $this->signVerify(true);

        $fileName = uniqid('puzzle_local_') . '.jpg';
        $date = date('Ymd');
        $savePath = "/puzzle/local/{$date}/";

        $sPuzzleContent = $this->receiveStreamData();
        if (isset($_FILES['Filedata'])) {
            $file = $_FILES['Filedata'];
            $sPuzzleContent = file_get_contents($file['tmp_name']);
            $fileName = uniqid('puzzle_local_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        } else if ($sPuzzleContent) {
            // $sPuzzleContent = $sPuzzleContent;
        } else {
            $this->outputJson(self::RETURN_CODE_FAILURE, '未上传本地图片或二进制数据流错误');
        }

        // 调用upload接口上传图片获取图片路径
        $body = [
            'fName' => $fileName,
            'fStream' => $sPuzzleContent,
            'fTargetPath' => $savePath
        ];
        $this->load->library('POP_Uploadpic');
        $json = $this->pop_uploadpic->curlRequest($body);
        $res = json_decode($json, TRUE);
        if (!$res['status']) {
            $this->outputJson(self::RETURN_CODE_FAILURE, $res['info']);
        } else {
            $sPicUrl = $res['info'];
            $sql = "INSERT INTO `" . self::TABLE_PUZZLE_LOCAL_PHOTOS . "` SET sLocalPhoto=?";
            $this->common_model->db()->query($sql, $sPicUrl);
            $id = $this->common_model->db()->insert_id();
	        $data = ['id' => $id, 'photo' => 'http://img1.fm.pop-fashion.com' . $sPicUrl];
            $this->outputJson(self::RETURN_CODE_SUCCESS, false, $data);
        }
    }

    /**
     * 上传版面
     * 测试数据{ "title":"this is a page title", "description":"this is a page description", "grids":[ {"x":0, "y":0, "width":100, "height":100, "photo":{"id":1, "small":"1.jpg", "big":"11.jpg", "x":0, "y":0, "width":100, "height":100, "scale":1.5, "rotation":90, "brand":"brand name", "description":"this is a photo description", "link":"http://domain.com", "linkType":1, "isLocal":true}} ], "gridSources":[ {"id":1, "small":"1.jpg", "big":"11.jpg", "rank":1} ], "layers":[ {"x":0,"y":0,"width":100,"height":100, "photo":{"id":1,"big":"11.jpg","x":0, "y":0, "width":100, "height":100, "scale":1.5, "rotation":90, "brand":"brand name", "description":"this is a photo description", "link":"http://baidu.com", "linkType":1, "rank":1}}] }
     */
	public function savePuzzleLayout()
	{
		$this->signVerify();
		$id = $this->input->post('id', true);
		$detail = $this->input->post('detail', true);
		$sql = "SELECT id FROM `" . self::TABLE_TREND_SUBREPORT_PUZZLE . "` WHERE id=? AND iStatus=1 LIMIT 1";
		$query = $this->common_model->db()->query($sql, [$id]);
		$row = $query->row_array();
		if (is_null($row)) {
			// 没有该版面id则插入
			$data = ['sContent' => $detail];
			$lastId = $this->common_model->executeSave(self::TABLE_TREND_SUBREPORT_PUZZLE, $data);
		} else {
			// 有该版面id则更新
			$sql = "UPDATE `" . self::TABLE_TREND_SUBREPORT_PUZZLE . "` SET sContent=? WHERE id=?";
			$this->common_model->db()->query($sql, [$detail, $id]);
		}
		$this->common_model->db()->close();
		$this->outputJson(self::RETURN_CODE_SUCCESS);
	}

	/**
	 * 保存单个子报告页
	 */
	public function savePuzzlePageSingle()
	{
		// $this->signVerify();
		// 主报告id
		$iTopicId = intval($this->input->post('iTopicId', true));
		// 子报告id
		$iSubTopicId = intval($this->input->post('iSubTopicId', true));
		if (!$iTopicId || !$iSubTopicId) {
			$this->outputJson(self::RETURN_CODE_FAILURE, '缺少主报告或子报告id');
		}
		// 子报告页信息
		$pageInfo = json_decode($this->input->post('pageInfo', true), true);
		// 对flash数据pageInfo额外处理
        $pageInfo = $this->dealPageInfo($pageInfo);
		if (!$pageInfo || !is_array($pageInfo)) {
			$this->outputJson(self::RETURN_CODE_FAILURE, '子报告页数据格式不正确', $pageInfo);
		}
		// 主报告
		$sql = "SELECT * FROM `" . self::TABLE_TREND_REPORT . "` WHERE iTopicId=? AND iStatus<>1 LIMIT 1";
		$query = $this->common_model->db()->query($sql, [$iTopicId]);
		$row = $query->result_array();
		if (empty($row)) {
			$this->outputJson(self::RETURN_CODE_FAILURE, 'id为' . $iTopicId . '的主报告不存在');
		}
		// 子报告
		$sql = "SELECT * FROM `" . self::TABLE_TREND_SUB_REPORT . "` WHERE iTopicId=? AND iSubTopicId=? AND iStatus<>1 LIMIT 1";
		$query = $this->common_model->db()->query($sql, [$iTopicId, $iSubTopicId]);
		$row = $query->result_array();
		if (empty($row)) {
			$this->outputJson(self::RETURN_CODE_FAILURE, 'id为' . $iSubTopicId . '的子报告不存在');
		}
		// 子报告页处理
		$pageId = 0;
		if ($pageInfo['id']) {
			$sql = "SELECT * FROM `" . self::TABLE_TREND_SUB_REPORT_PAGE . "` WHERE iPageId=? AND iStatus<>1 LIMIT 1";
			$query = $this->common_model->db()->query($sql, [$pageInfo['id']]);
			$row = $query->result_array();
			if (empty($row)) {
				$this->outputJson(self::RETURN_CODE_FAILURE, 'id为' . $pageInfo['id'] . '的子报告页不存在');
			}
			$pageId = $pageInfo['id'];
		}
		unset($pageInfo['id'], $pageInfo['tmpid']);
		if (!$pageId) {
			// 新增
			$data = [
				'iPageId'     => $pageId,
				'iTopicId'    => $iTopicId,
				'iSubTopicId' => $iSubTopicId,
				'sTitle'      => $pageInfo['title'],
				'iPageType'   => 4,
				'dCreateTime' => date('Y-m-d H:i:s'),
				'sContent'    => json_encode($pageInfo)
			];
			$pageId = $this->common_model->executeSave(self::TABLE_TREND_SUB_REPORT_PAGE, $data);
		} else {
			// 有id, 修改
			$sql = "UPDATE `" . self::TABLE_TREND_SUB_REPORT_PAGE . "` SET sTitle=?,sContent=?,dUpdateTime=? WHERE iPageId=?";
			$this->common_model->db()->query($sql, [$pageInfo['title'], json_encode($pageInfo), date('Y-m-d H:i:s'), $pageId]);
		}
		$this->outputJson(self::RETURN_CODE_SUCCESS, 'ok', intval($pageId));
	}

    /**
     * 子报告专题页数据处理（本地图和款式图区分isLocal，Flash未处理正确）
     * @param array $subReportPageInfo
     * @return array
     */
	private function dealPageInfo($subReportPageInfo)
    {
        if (!$subReportPageInfo) return [];

        foreach ($subReportPageInfo['grids'] as &$grid) {
            // flash处理有问题，这里校正为：isLocal为数字的即为true，否则false
        	$grid['photo']['isLocal'] = is_numeric($grid['photo']['id']);
            // isLocal为false的是款式库图，brand为空时取款式对应的品牌信息
	        if (!$grid['photo']['isLocal'] && !trim($grid['photo']['brand'])) {
		        // $_popId: t_product_tideleader-id_3150-col_57
		        $photoId = $grid['photo']['id'];
		        $_aPopId = explode('-', $photoId);
		        $_table = substr($_aPopId[0], 2); // product_tideleader
		        $_id = substr($_aPopId[1], 3); // 3150
		        // $_col = substr($_aPopId[2], 4); // 57
		        $productData = OpPopFashionMerger::getProductData($_id, $_table);
		        $productData = $productData[$_id];
		        $brandFields = ['iBrandId', 'brand_tid', 'brand_id'];
		        $iBrandId = 0;
		        foreach ($brandFields as $brandField) {
			        if ($productData[$brandField]) {
				        $iBrandId = $productData[$brandField];
			        }
		        }
		        $sBrand = GetCategory::getBrandOtherFormId($iBrandId);
		        $grid['photo']['brand'] = $sBrand;
	        }
        }
        unset($grid);

        return $subReportPageInfo;
    }

	/**
	 * 删除单个子报告页
	 */
	public function deletePuzzlePage()
	{
		// $this->signVerify();
		// 子报告页id
		$iPageId = intval($this->input->post('iPageId', true));
		$sql = "UPDATE `" . self::TABLE_TREND_SUB_REPORT_PAGE . "` SET iStatus=1,dUpdateTime=? WHERE iPageId=?";
		$this->common_model->db()->query($sql, [date('Y-m-d H:i:s'), $iPageId]);
		$this->outputJson(self::RETURN_CODE_SUCCESS, '删除子报告页成功:' . $iPageId);
	}

    /**
     * php 接收流文件
     * @return boolean
     */
    public function receiveStreamData()
    {
        $streamData = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        if (empty($streamData)) {
            $streamData = file_get_contents('php://input');
        }
        return $streamData == '' ? false : $streamData;
    }

    /**
     * 获取图片列表搜索条件
     *搜索条件数据
        https://www.pop-fashion.com/puzzle/getPuzzlePicsCon/
        这是请求列表数据的接口
            https://www.pop-fashion.com/puzzle/getpuzzlepicslist/
     * 获取选中图片接口
     * https://www.pop-fashion.com/puzzle/getPuzzleList
     */
    public function getPuzzlePicsCon(){
        //时间范围
        $industry = GetCategory:: getTrade();//行业
        $gender = GetCategory::getGender();//性别
        $sea = GetCategory::getSeason(); //季节
        $columns = GetCategory::getColumns([4,9]);//栏目
        //单品or品名
        $aSingles=GetCategory::getSingle('',true,true);
        //地区
        $reg = GetCategory::getRegionList();
        $reg_1 = "";
        foreach($reg as $k=>$v){
            if($v['iLevel']==1){
                $reg_1 .= "<option value='{$v['iRegionId']}'>{$v['sCnRegionName']}</option>";
            }
        }
        $this->assign('reg_1',$reg_1);
        //风格
        $manners = include getenv('BASEPATH') . '/category/popfashion/fm_conf/attr_28.php';
        $manner = $manners['attrs'];
        //元素
        $elements = include getenv('BASEPATH') . '/category/popfashion/fm_conf/attr_29.php';
        $element = $elements['attrs'];
        //图案内容
        $this->load->model('patterns_model');
        $selectItem = array('sPatternContent');
        $powers = $this->common_model->getPowers(9, '', 82);
        $patterns = $this->patterns_model->getSelectItems($selectItem, '', '', $powers);
        $patternContent = $patterns['sPatternContent'];
        $this->assign('industry',$industry);
        $this->assign('gender',$gender);
        $this->assign('sea',$sea);
        $this->assign('columns',$columns);
        $this->assign('aSingles',$aSingles);
        $this->assign('reg',$reg);
        $this->assign('manner',$manner);
        $this->assign('element',$element);
        $this->assign('patternContent',$patternContent);

        $this->display('puzzle_list.html');

    }

    /**
     * 获取对应二级列表
     * @param $id
     * @param $inputName
     */
    public function getSubList(){
        //获取选中的栏目
        $inputName = $this->input->get_post("inputName", true);
        $id = $this->input->get_post("id", true);
//        ajax.setRequestHeader("content-type","application/x-www-form-urlencoded");
        $id = $id ? intval($id) : 0;

        switch ($inputName){
            case 'cols'://栏目
                $column = $this->common_model->getColumns($id, '');
                $cols = [];
                if($id==9){
                    $_val['sName'] = $column[82]['sName'];
                    $_val['id'] = 82;
                    $cols[] = $_val;
                }else{
                    foreach($column as $k=>$v) {
                        if ($id == 4 && $k != 4) {
                            $_val['sName'] = $v['sName'];
                            $_val['id'] = $k;
                        }
                        $cols[] = $_val;
                    }
                }

                $data = ['code'=>0,'msg'=>'请求成功','data'=>$cols,'info'=>[]];
               echo json_encode($data);
                break;
            case 'cat'://单品
                //单品or品名
                $aSingles=GetCategory::getSingle('',true,true);
                $data = ['code'=>0,'msg'=>'请求成功','data'=>$aSingles[$id]['Pinming']];
                echo json_encode($data);
                break;
            case 'reg_1'://地区
            case 'reg_2'://地区
            case 'reg_3'://地区
                //地区0
                $reg = GetCategory::getRegionList();
                $reg_3 = [];
                foreach($reg as $k=>$v){
                    if($id == $v['iRegionPid']){
                        $_val['iRegionId']=$v['iRegionId'];
                        $_val['sCnRegionName']=$v['sCnRegionName'];
                        $reg_3[]=$_val;
                    }
                }
                $data = ['code'=>0,'msg'=>'请求成功','data'=>$reg_3];
                echo json_encode($data);
                break;
            case 'ele'://元素
                //元素
                $elements = include getenv('BASEPATH') . '/category/popfashion/fm_conf/attr_29.php';
                $element = $elements['attrs'];
                $ele = [];
                foreach($element as $k=>$v){
                    if($id==$v['iAttributeId']){
                        foreach($v['attrs'] as $_k=>$_v){
                            $ele['iAttributeId'] = $_v['iAttributeId'];
                            $ele['sName'] = $_v['sName'];
                        }
                    }
                }
                $data = ['code'=>0,'msg'=>'请求成功','data'=>$ele,'info'=>[]];
                echo json_encode($data);
                break;
            case 'con'://图案内容
                break;
        }
    }
    /**
     *
     * 获取拼图图片列表
     */
    public function getPuzzlePicsList($params = '')
    {
        $conditions = $_result = $paramsArr = $lists = [];
        //获取搜索条件
        //日期
        $startTime = $this->input->get_post('beginTime', true);
        $endTime = $this->input->get_post('endTime', true);

        if ($startTime && $endTime) {
            $conditions['dCreateTime'] = '[' . $startTime . 'T' . '00:00:00Z' . ' TO ' . $endTime . 'T' . '00:00:00Z' . ']';
        }
        if (empty($startTime) || empty($endTime)) {
            $conditions['dCreateTime'] = '[' . yearTimeDiff('solr') . ' TO ' . date("Y-m-d\TH:i:s\Z", strtotime("-2 hour")) . ']';
        }
        if ($startTime && empty($endTime)) {
            $conditions['dCreateTime'] = '[' . $startTime . 'T' . '00:00:00Z' . ' TO ' . date("Y-m-d\TH:i:s\Z", strtotime("-2 hour")) . ']';
        }

        //栏目
        $iColumnId = (int)$this->input->get_post('cols', true);
        $subCols = (int)$this->input->get_post('subCols', true);
        if ($subCols) {
            $conditions['iColumnId'] = $paramsArr['col'] = $subCols;
        }
        else {
            if ($iColumnId) {
                $conditions['iColumnId'] = $paramsArr['col'] = $iColumnId;
            }
        }
        //性别
        $gen = (int)$this->input->get_post('gen', true);
        if ($gen) {
            $paramsArr['gen'] = $gen;
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['gen'];
        }
        //行业
        $ind = (int)$this->input->get_post('ind', true);
        if ($ind) {
            $paramsArr['ind'] = $ind;
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['ind'];
        }
        //季节
        $sea = (int)$this->input->get_post('sea', true);
        if ($sea) {
            $paramsArr['sea'] = $sea;
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['sea'];
        }
        //关键字
        $keywords = $this->input->get_post('key', true);
        if ($keywords) {
            $conditions['other'][] = 'combine:(' . $keywords . ')';
        }

        //单品
        $cat = (int)$this->input->get_post('cat', true);
        $subCat = (int)$this->input->get_post('subCat', true);
        if ($subCat) {
            $paramsArr['cat'] = $subCat;
        }
        else {
            if ($cat) {
                $paramsArr['cat'] = $cat;
            }
        }
        if ($paramsArr['cat']) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cat'];
        }

        //地区
        $reg_1 = (int)$this->input->get_post('reg_1', true);
        $reg_2 = (int)$this->input->get_post('reg_2', true);
        $reg_3 = (int)$this->input->get_post('reg_3', true);
        $reg_4 = (int)$this->input->get_post('reg_4', true);

        if ($reg_4) {
            $paramsArr['reg'] = $reg_4;
        }
        else {
            if ($reg_3) {
                $paramsArr['reg'] = $reg_3;
            }
            else {
                if ($reg_2) {
                    $paramsArr['reg'] = $reg_2;
                }
                else {
                    if ($reg_1) {
                        $paramsArr['reg'] = $reg_1;
                    }
                }
            }
        }
        if (isset($paramsArr['reg']) && $iColumnId == 54 && ($paramsArr['reg'] == 10001 || $paramsArr['reg'] == 10002)) {
            $conditions['other'][] = "iRegion: ({$paramsArr['reg']})";
        }
        else {
            if (!empty($paramsArr['reg'])) {
                $conditions['other'][] = "(iRegion:{$paramsArr['reg']} OR iArea:{$paramsArr['reg']} OR iContinent:{$paramsArr['reg']} OR  iCountry:{$paramsArr['reg']})";
            }
        }

        //风格
        $man = (int)$this->input->get_post('manner', true);
        if ($man) {
            $paramsArr['man'] = $man;
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['man'];
        }

        //元素
        $ele = (int)$this->input->get_post('ele', true);
        $subEle = (int)$this->input->get_post('subEle', true);
        if ($ele) {
            switch ($ele) {
                case '11776'://面料
                    $paramsArr['fab'] = $ele;
                    if ($subEle) {
                        $paramsArr['fab'] = $subEle;
                    }
                    break;
                case '11787'://图案
                    $paramsArr['pat'] = $ele;
                    if ($subEle) {
                        $paramsArr['pat'] = $subEle;
                    }
                    break;
                case '11797'://细节
                    $paramsArr['spe'] = $ele;
                    if ($subEle) {
                        $paramsArr['spe'] = $subEle;
                    }
                    break;
                case '11752'://辅料
                    $paramsArr['acc'] = $ele;
                    if ($subEle) {
                        $paramsArr['acc'] = $subEle;
                    }
                    break;
                case '11755'://工艺
                    $paramsArr['tech'] = $ele;
                    if ($subEle) {
                        $paramsArr['tech'] = $subEle;
                    }
                    break;
                case '11768'://廓形
                    $paramsArr['shap'] = $ele;
                    if ($subEle) {
                        $paramsArr['shap'] = $subEle;
                    }
                    break;
            }
        }

        // 廓形
        if (isset($paramsArr['shap'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['shap'];
        }
        // 细节
        if (isset($paramsArr['spe'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['spe'];
        }
        // 工艺
        if (isset($paramsArr['tech'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['tech'];
        }
        // 图案
        if (isset($paramsArr['pat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['pat'];
        }
        // 面料
        if (isset($paramsArr['fab'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['fab'];
        }
        // 辅料
        if (isset($paramsArr['acc'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['acc'];
        }

        //图案内容  二级栏目暂不做处理
        $con = (int)$this->input->get_post('con', true);
        if ($paramsArr['col'] == 9 || $paramsArr['col'] == 82) {
            if ($con) {
                $paramsArr['con'] = $con;
                $conditions['other'][] = 'aLabelIds:' . $paramsArr['con'];
            }
        }

        $_limit = (int)$this->input->get_post('page_size', true);
        $page = (int)$this->input->get_post('page', true);
        $page = $page ? $page : 1;
        $_offset = ($page - 1) * $_limit;

        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $_result, $_offset, $_limit, ['dCreateTime' => 'DESC', 'pri_id' => 'DESC']);

        //总页数
        $count = ceil($totalCount / $_limit);
        foreach ($_result as $_val) {
            $id = $_val['pri_id'];
            $tableName = $_val['tablename'];

            $_temp = [];
            $_temp['pop_id'] = $_val['pop_id'];
            $_temp['id'] = $id;
            $_temp['table_name'] = getProductTableName($tableName);

            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];

            //单品,品名
            if ($info['sCategory']) {
                $_temp['cat'] = GetCategory::getOtherFromIds($info['sCategory'], ['sName']);
                $_temp['subCat'] = GetCategory::getOtherFromIds([$info['sSubCategory']], ['sName']);
            }
            elseif ($info['iCategory']) {
                $_temp['cat'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                $_temp['subCat'] = GetCategory::getOtherFromIds([$info['iSubcategory']], ['sName']);
            }
            elseif ($info['category_id']) {
                $_temp['cat'] = GetCategory::getOtherFromIds($info['category_id'], ['sName']);
                $_temp['subCat'] = GetCategory::getOtherFromIds([$info['sub_category_id']], ['sName']);
            }
            elseif ($tableName == 'product_graphicitem' && $info['category_text']) {
                $_temp['cat'] = $info['category_text'];
                $_temp['subCat'] = $info['sub_category_text'];
            }
            else {
                $_temp['cat'] = '--';
                $_temp['subCat'] = '--';
            }
            //品牌
            if ($info['brand_tid']) {
                $_temp['brandName'] = GetCategory::getBrandOtherFormId($info['brand_tid'], 'name');
            }
            elseif ($info['iBrandTid']) {
                $_temp['brandName'] = GetCategory::getBrandOtherFormId($info['iBrandTid'], 'name');
            }
            elseif ($info['brand_id']) {
                $_temp['brandName'] = GetCategory::getBrandOtherFormId($info['brand_id'], 'name');
            }
            else {
                $_temp['brandName'] = '--';
            }

            $imgPath = getImagePath($tableName, $info);
            $_temp['small_img'] = $imgPath['smallPath'];//小图
            $_temp['big_img'] = $imgPath['bigPath'];//大图
            array_push($lists, $_temp);
        }

        $data = ['code' => 0, 'msg' => '请求成功', 'data' => $lists, 'info' => ['count' => $count]];

        echo json_encode($data);
    }

    /**
     * 获取选中的图片数据
     */
    public function getPuzzleList(){
        $data = $this->input->get_post('data',true);
        if(isset($data) && $data){
            echo json_encode(array('code'=>0,'msg'=>'请求成功','data'=>array(),'info'=>array()));
        }else{
            echo json_encode(array('code'=>1,'msg'=>'请求失败','data'=>array(),'info'=>array()));
        }
    }


}

/*
  + 模板表
	CREATE TABLE `fashion`.`t_puzzle_templates` (
		`id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模板ID' ,
		`sTemplateThumbPhoto`  varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '模板图路径' ,
		`sTemplateAreaDetails`  varchar(8000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '模板区块详细,结构:\r\nArray\r\n(\r\n    [0] => Array\r\n        (\r\n            [x] => 0\r\n            [y] => 0\r\n            [width] => 100\r\n            [height] => 100\r\n        )\r\n   ......\r\n)' ,
		`sSite`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '模板使用站点：1-服装趋势，2-箱包，3-鞋子，4-首饰，5-家纺，多个‘,’隔开' ,
		`iStatus`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '模板状态 -1删除，1正常' ,
		PRIMARY KEY (`id`)
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
	COMMENT='模板表'
	AUTO_INCREMENT=1
	ROW_FORMAT=COMPACT;


  + 本地图片上传表
	CREATE TABLE `fashion`.`t_puzzle_local_photos` (
		`id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID' ,
		`sLocalPhoto`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '本地上传图片地址' ,
		`iStatus`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 -1删除，1正常' ,
		PRIMARY KEY (`id`)
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
	COMMENT='拼图本地图上传表'
	AUTO_INCREMENT=1
	ROW_FORMAT=COMPACT;

  + 模板拼图信息表
	CREATE TABLE `fashion`.`t_trend_subreport_puzzle` (
		`id`  int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID' ,
		`iPageId`  int(11) NOT NULL default 0 COMMENT '页id' ,
		`sPuzzleId`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '模板拼图ID',
		`sContent`  longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '拼图模板json数据' ,
		`iStatus`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 -1删除，1正常' ,
		PRIMARY KEY (`id`),
		INDEX `idx_sPuzzleId` (`sPuzzleId`) USING BTREE
	)
	ENGINE=InnoDB
	DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
	COMMENT='模板拼图信息表'
	ROW_FORMAT=COMPACT;



	后台调整：
	ALTER TABLE t_trend_subreport_page MODIFY COLUMN `iPageType` tinyint(1) NOT NULL COMMENT '页类型(1-款式页、2-自定义页、3-HTML页、4-模板拼图页)';
	ALTER TABLE t_trend_subreport_page ADD INDEX idx_iTopicId (iTopicId),ADD INDEX idx_iSubTopicId (iSubTopicId);

*/