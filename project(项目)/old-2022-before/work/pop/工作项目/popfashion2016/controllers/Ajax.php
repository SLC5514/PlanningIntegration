<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 所有简单的AJAX请求聚合页
 * @var $pop_outputjson POP_OutputJson
 */
class Ajax extends POP_Controller
{
    CONST MEMC = TRUE;
    // 在此列表中的请求不强制需要ajax访问
    private $filter = array(
        'virtualsplupload', // 虚拟样衣上传图片
    );

    /**
     * Class constructor
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $action = strtolower($this->uri->segment(2));
        // 如果不是AJAX直接返回
        if (!$this->input->is_ajax_request() && !in_array($action, $this->filter)) {
            //echo 'No direct script access allowed';
            //exit;
        }
        // 次数限制
        // 时间限制
        // ...
    }

    //游客引导弹窗数据
    public function grsData()
    {
        $return = array_fill_keys(['free_report', 'free_style'], []);

        $free_style_table = 't_free_styles';
        $free_report_table = 't_trend_report';
        $this->load->model('Member_model');
        //查询后台设为免费试读的报告
        $free_report = $this->Member_model->query("select * from {$free_report_table} where iFreeReport=? limit 3", [1]);
        if (is_array($free_report) && !empty($free_report)) {
            foreach ($free_report as $key => $info) {
                //报告
                $imgPath = getImagePath('t_trend_report', $info);
                $cover = getFixedImgPath($imgPath['cover']);
                $return['free_report'][$key] = [
                    'id' => $info['iTopicId'],
                    'title' => $info['sTitle'],//标题
                    'cover' => $cover,//封面图
                    'create_time' => $info['dPubTime'],// 发布时间
                    'detail_link' => "/details/report/t_report-id_{$info['iTopicId']}-col_{$info['iOriginColumn']}/",// 发布时间
                ];
            }
        }

        //查询后台免费款式图推荐
        $return['free_style'] = $this->Member_model->query("select * from {$free_style_table} where iStatus=? and dPublishTime<=? ORDER BY dCreateTime DESC limit 8", [1, date('Y-m-d H:i:s')]);

        $this->outputJson(0, '请求成功', $return);
        return;
    }

    //秀场提炼推荐T台
    public function getRunwaysData()
    {
        $id = $this->input->get('id', true);
        $t = $this->input->get('t', true);
        $col = $this->input->get('col', true);
        if (empty($id) || empty($t)) {
            $this->outputJson(0, '参数错误');
            die;
        }
        $tableName = getProductTableName($t);
        $tableName = strtolower($tableName);
        if ($tableName != 'product_perform' || $col != 50) {
            $this->outputJson(0, '参数错误');
            die;
        }

        $paramsArr = [];
        $_info = OpPopFashionMerger::getProductData($id, $tableName, true);
        $info = $_info[$id];
        if (!$info) {
            $this->outputJson(0, '数据不存在');
            die;
        }

        $this->load->model(['common_model', 'runways_model']);
        // 性别
        $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $col));
        // 季节
        $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
        $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
        $paramsArr['gen'] = !empty($iGender) ? $iGender : '';
        $paramsArr['sea'] = !empty($iSeason) ? $iSeason : '';
        $paramsArr['bra'] = !empty($info['brand_tid']) ? $info['brand_tid'] : '';
        if (count(array_filter($paramsArr)) < 3) {
            $this->outputJson(0, '数据条件为空');
            die;
        }
        if (in_array($paramsArr['gen'], [3, 4])) {
            $paramsArr['gen'] = 5;
        }
        $params = $this->common_model->parseParams($paramsArr, 2);

        // 获取列表
        $powers = $this->common_model->getPowers(3, $params, 3);
        $this->runways_model->ajaxList($params, 3, $powers);


    }

    /**
     * 品牌搜索历史记录
     * @return void
     */
    public function brandHistory()
    {
        $this->load->model('common_model');
        $table = 'brand_history_record';

        //当前登录的账号id
        $user_id = getUserId();
        if (!$user_id) {
            $this->outputJson([0, '用户id不存在']);
            return;
        }

        //当前账号下历史搜索记录
        $sql = "SELECT * FROM fashion.{$table} WHERE sUserId=? AND iStatus='1' ORDER BY dCreateTime ASC ";
        $result = $this->common_model->query($sql, [$user_id]);

        $act = $this->input->post('act');
        if ($act == 'save') {
            $brand_id = $this->input->post('brand_id');//品牌id
            if (!$brand_id) {
                $this->outputJson(0, '参数有误');
                return;
            }
            $data = [
                'iBrandId' => $brand_id,
                'sUserId' => $user_id,
            ];

            //如果品牌已存在，则更新，不做新增和替换
            $sql = "select id from {$table} where iBrandId=? and sUserId=? and iStatus='1' ";
            $rs = $this->common_model->query($sql, [$brand_id, $user_id]);

            if ($rs[0]['id']) {
                $data['dCreateTime'] = date('Y-m-d H:i:s');
                $cond = ['id' => $rs[0]['id']];
                $affect_rows = $this->common_model->executeUpdate($table, $data, $cond);
                if ($affect_rows) {
                    $this->outputJson(0, '保存成功');
                } else {
                    $this->outputJson(0, '保存失败');
                }
                return;
            } else {

                if ($result && count($result) >= 20) {
                    //超过20条 把老的品牌记录替换为最新的品牌记录
                    $condition['id'] = $result[0]['id'];
                    $this->common_model->executeUpdate($table, $data, $condition);
                } else {
                    //保存记录
                    $this->common_model->executeSave($table, $data);
                }

                $this->outputJson(0, '保存成功');
                return;
            }
        } elseif ($act == 'delete') {
            //清除记录
            $data = [
                'iStatus' => 0
            ];
            $condition = [
                'sUserId' => $user_id
            ];
            $this->common_model->executeUpdate($table, $data, $condition);
            $this->outputJson(0, '删除成功');
            return;
        } else {
            $col_id = $this->input->post('col_id');//栏目id
            $params = $this->input->post('params', true);//保留筛选条件
            //取出历史记录
            $sql = "select * from fashion.{$table} where sUserId=? AND iStatus='1' ORDER BY dCreateTime DESC,id DESC LIMIT 10";
            $res = $this->common_model->query($sql, [$user_id]);

            $brand_list = [];
            if ($res) {
                foreach ($res as $key => $value) {
                    $name = GetCategory::getBrandOtherFormId($value['iBrandId'], 'name');
                    $b_name = GetCategory::getBrandOtherFormId($value['iBrandId'], 'b_name');
                    $brand_list[$key] = [
                        'brand_id' => $value['iBrandId'],
                        'brand_name' => $b_name ? $name . '/' . $b_name : $name,
                        'brand_link' => $this->common_model->getLink($col_id, $params, 'bra', $value['iBrandId'], TRUE, 'anchor'),
                    ];
                }
            }
            $this->outputJson(0, 'ok', $brand_list);
            return;
        }
        return;
    }

    /**
     * 请求获取聚合页里面的更多搜索结果
     * @return void $list
     */
    public function getMoreSeaList()
    {
        $this->load->model(['common_model', 'details_model']);
        //获取请求参数
        $params = $this->input->post('p');
        $cat = intval($this->input->post('cat')); // 该变量用来区分聚合页是以品牌分组还是用单品/品名分组 2=》品牌 3=》单品/品名
        $type = intval($this->input->post('type'));
        $columnId = intval($this->input->post('col'));
        $columnPid = intval($this->input->post('colPid'));
        $limit = $this->input->post('limit');
        $limit = $limit ? intval($limit) : 12;
        $offset = intval($this->input->post('page')) - 1;
        $start = $offset * $limit;

        $paramsArr = $this->common_model->parseParams($params);

        $_keys = ['2' => 'bra', '3' => 'cat'];

        if ($type && key_exists($cat, $_keys)) {
            $paramsArr[$_keys[$cat]] = $type;
        }

        $paramsArr['iColumnId'] = $_colId = ($columnId != $columnPid) ? $columnPid : $columnId;

        //判断用户权限
        $columnPid = GetCategory::getOtherFromColId($columnId);
        $_powers = $this->common_model->getPowers($columnPid, $params);
        $arSort = $this->common_model->getSort($params, $_powers, $columnPid);
        $conditions = $this->details_model->getConditions($paramsArr, $_colId);

        //排除街拍图库和潮流领袖
        if ($conditions['iColumnId'] == 4) {
            $conditions['iColumnId'] = [50, 52, 54, 55, 122, 123];
        }

        // 为了生成的url中不含page和dis参数
        unset($paramsArr['page'], $paramsArr['dis']);

        $_params = $paramsArr ? $this->common_model->parseParams($paramsArr, 2) : '';
        $link = $this->common_model->getLink($_colId, $_params);

        $result = $data = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $start, $limit, $arSort);
        if ($totalCount) {
            foreach ($result as $key => $val) {
                $tmp = [];
                $id = $val['pri_id'];
                $tableName = $val['tablename'];
                $_data = OpPopFashionMerger::getProductData($id, $tableName);

                $tmp['tableName'] = getProductTableName($tableName);
                $tmp['id'] = $id;
                $p = count($val['iColumnId']) > 1 && $columnId == $val['iColumnId'][0];
                if ($p) {
                    $tmp['columnId'] = $val['iColumnId'][0];
                } else {
                    $tmp['columnId'] = $val['iColumnId'][1];
                }
                $tmp['offset'] = $start + $key;
                //图片路径
                $imgPath = getImagePath($tableName, $_data[$id]);
                $tmp['bigPath'] = getFixedImgPath($imgPath['bigPath']);
                $tmp['smallPath'] = getFixedImgPath($imgPath['smallPath']);

                $data[] = $tmp;
            }
        }

        echo json_encode(['result' => $data, 'total' => $totalCount, 'type' => $type, 'link' => $link]);
        return;
    }

    //Ajax异步提交反馈
    public function userFeedBack()
    {
        $feedback = $this->input->post('feedBackVal', TRUE);
        $this->load->model('member_model', 'member');
        $res = $this->member->userFeedBack($feedback);
        echo $res;
        return;
    }

    // 创建工作台
    public function createWorkbench()
    {
        $this->load->model('Workbench_model', 'wb');
        $name = $this->input->post('name');
        // 判断工作台是否已经存在
        $exist = $this->wb->isExistWBByName($name);
        // 存在
        if ($exist) {
            $res = ['s' => 0, 'code' => 1, 'msg' => $name . '已经存在'];
            echo json_encode($res);
            return;
        }
        // 判断是否超过数量限制
        $num = $this->wb->getWKNum();
        if ($num >= 999) {
            $res = ['s' => 0, 'code' => 2, 'msg' => '工作台数量已满999个'];
            echo json_encode($res);
            return;
        }
        $sAccountId = getUserId();
        $insert = ['sAccountId' => $sAccountId, 'sWbenchName' => $name, 'iStatus' => 0, 'dCreateTime' => date('Y-m-d H:i:s')];
        $insertId = $this->wb->createWorkbench($insert);
        if ($insertId) {
            $res = ['s' => 1, 'msg' => $name . '创建成功!', 'id' => $insertId];
        } else {
            $res = ['s' => 0, 'code' => 3, 'msg' => '对不起，' . $name . '创建失败，请稍后尝试'];
        }
        echo json_encode($res);
        return;
    }

    // 加入图片到工作台
    public function createWorkbenchImage()
    {
        $this->load->model('Workbench_img_model', 'wbi');
        $this->load->model('Workbench_model', 'wb');
        $wbid = $this->input->post('wkb');
        $wb_num = $this->wbi->getImageNumByWBid(intval($wbid));
        // 是否超过收藏限制
        if ($wb_num >= 9999) {
            $res = ['s' => 0, 'code' => 1, 'msg' => '每个工作台只能加入最多99张图片'];
            echo json_encode($res);
            return;
        }

        $tablename = $this->input->post('t');
        $id = $this->input->post('id');
        $columnId = $this->input->post('col');
        $smallpic = $this->input->post('sp');
        $bigpic = $this->input->post('bp');
        $desc = $this->input->post('desc');//图片描述
        $desc = !empty($desc) ? $desc : '';
        // 是否已经收藏过
        $img_num = $this->wbi->getImageNum($tablename, $id, $wbid, $smallpic);
        if ($img_num >= 1) {
            $res = ['s' => 0, 'code' => 4, 'msg' => '您已经收集过此图片无需再收集'];
            echo json_encode($res);
            return;
        }
        $insertId = $this->wbi->saveImage($wbid, $tablename, $id, $columnId, $smallpic, $bigpic, $desc);
        if ($insertId) {
            $res = ['s' => 1, 'msg' => '加入成功', 'id' => $insertId];
            //更新工作台列表时间
            $updateTime = date('Y-m-d H:i:s');
            $this->wb->updateWorkbenchTime($wbid, $updateTime);
        } else {
            $res = ['s' => 0, 'code' => 2, 'msg' => '加入失败'];
        }
        echo json_encode($res);
        return;
    }

    // 获取工作台列表
    public function getWorkbenchList($cnt = FALSE)
    {
        $col = $this->input->post('col', TRUE);
        $t = $this->input->post('t', TRUE);
        $id = $this->input->post('id', TRUE);
        $smallpic = $this->input->post('sp');//smallimg
        $realTable = getProductTableName($t);
        $this->load->model('details_model');
        $tableInfo = $this->details_model->getPicInfo(intval($id), $realTable, '', intval($col));
        $tableInfo['iGender'] = isset($tableInfo['iGender']) && !empty($tableInfo['iGender']) ? $tableInfo['iGender'] : 0;
        $tableInfo['iIndustry'] = isset($tableInfo['iIndustry']) && !empty($tableInfo['iIndustry']) ? $tableInfo['iIndustry'] : 0;
        // 权限判断
        $power = memberPower('detail', array('P_Gender' => $tableInfo['iGender'], 'P_Industry' => $tableInfo['iIndustry'], 'P_Column' => $col));
        $userType = $power['P_UserType'];
        if ($userType == 4 || $userType == 1) {
            $res = ['s' => 0, 'code' => 1, 'msg' => '对不起，您没有操作权限', 'userType' => $userType];
            echo json_encode($res);
            return;
        }
        $this->load->model('Workbench_model', 'wb');
        $res = $this->wb->getList('iWorkbenchId,sWbenchName', $cnt);
        $list = $res['list'];
        $total = $res['total'];//工作台总数
        $_res = ['workbench' => [], 'userType' => $userType];

        //默认选中的工作台
        $_res['checkedWorkbench'] = [];
        $sAccountId = getUserId();
        $memcacheKey = 'FM_TEM_CHECKED_WORKBENCH_' . $sAccountId;
        $this->load->driver('cache');
        $checkedId = $this->cache->memcached->get($memcacheKey);

        if (is_array($list) && count($list)) {
            foreach ($list as $val) {
                //判断工作台当前状态
                $result = $this->checkImgCollected($col, $t, $id, $val['iWorkbenchId'], $smallpic);
                $status = $result['status'];
                $msg = $result['msg'];
                $_res['workbench'][] = ['id' => $val['iWorkbenchId'], 'name' => $val['sWbenchName'], 'status' => $status, 'msg' => $msg];
            }
        }
        echo json_encode($_res);
        return;
    }

    /**
     * 图片是否可以加入工作台
     *
     * @param $col
     * @param $table
     * @param $id
     * @param $wbid
     * @param $smallpic
     * @return mixed
     */
    private function checkImgCollected($col, $table, $id, $wbid, $smallpic)
    {
        $this->load->model('Workbench_img_model', 'wbi');
        $wb_num = $this->wbi->getImageNumByWBid(intval($wbid));
        // 是否超过工作台限制
        if ($wb_num >= 9999) {
            $res['status'] = 1;
            $res['msg'] = '已加满';
        } else {
            $num = $this->wbi->getImageNum($table, $id, $wbid, $smallpic);
            // 是否已经在工作台
            if ($num) {
                $res['status'] = 1;
                $res['msg'] = '已加过';
            } else {
                $res['status'] = 0;
                $res['msg'] = '加入';
            }
        }
        return $res;
    }

    // 保存当前选中的工作台
    public function saveCheckedWorkbench()
    {
        $this->load->model('Workbench_model');
        // 工作台id
        $workBenchId = $this->input->post('wbid');
        $sAccountId = getUserId();
        $memcacheKey = 'FM_TEM_CHECKED_WORKBENCH_' . $sAccountId;

        $this->load->driver('cache');
        $this->cache->memcached->save($memcacheKey, $workBenchId, 0);
        return;
    }

    /**
     * 设置下载数、浏览数。
     *
     * url : Ajax/setCount ,
     * data:{'table':'假表名','id':'ID值','iColumnId':'栏目ID','action':'浏览(ViewCount)或下载(DownCount)'},
     * dataType:'post',
     */
    public function setCount()
    {
        $ret = 0;

        //假表名
        $table = $this->input->get('table');

        //id
        $id = $this->input->get('id');

        if ($table == 'imgcol_set') {
            //杂志栏目
            $_arr = explode('I', $id);
            $id = $_arr[2];
        } else {
            $table = getProductTableName($table);    //表名
        }
        //栏目id
        $iColumnId = $this->input->get('iColumnId');

        //浏览量或下载量
        $action = $this->input->get('action');

        if ($table && $id) {
            $this->load->model('statistics_model');
            switch ($action) {
                case 'ViewCount':
                    if ($this->statistics_model->setIncrViews($table, $id, $iColumnId)) {
                        $ret = 1;
                    }
                    break;
                case 'DownCount':
                    if ($this->statistics_model->setDownCount($table, $id, $iColumnId)) {
                        $ret = 1;
                    }
                    break;
            }
        }
        echo $ret;
        return;
    }

    // 根据SQL获取更多搜索结果
    public function getMoreSeaListBySQL()
    {
        $pid = intval($this->input->post('pid', TRUE));
        $col = intval($this->input->post('col', TRUE));
        $table = $this->input->post('table', TRUE);
        $ver = $this->input->post('ver', TRUE);
        $page = $this->input->post('page', TRUE);
        $limit = $this->input->post('limit', TRUE);
        $limit = $limit ? intval($limit) : 12;
        $offset = intval($page) - 1;
        $start = $offset * $limit;

        $jsonArr = [];
        switch ($table) {
            case 'brochuresphoto':
                $ttable = 'brochures';
                $this->load->model('books_model');
                $res = $this->books_model->getSecondLevelList($ttable, $pid, $totalCount, $page, $limit, TRUE);
                if (!empty($res['brochures'])) {
                    foreach ($res['brochures'] as $key => $val) {
                        $id = $val['id'];
                        $tableName = getProductTableName($table);//表名
                        $data = OpPopFashionMerger::getProductData($id, $tableName);

                        $jsonArr[$key]['tableName'] = $table;
                        $jsonArr[$key]['id'] = $id;
                        $jsonArr[$key]['columnId'] = $col;
                        $jsonArr[$key]['offset'] = $start + $key;
                        //图片路径
                        $imgPath = getImagePath($tableName, $data[$id]);
                        // $jsonArr[$key]['bigPath'] = getFixedImgPath($imgPath['bigPath']);
                        $jsonArr[$key]['smallPath'] = getFixedImgPath($imgPath['smallPath']);
                    }
                }
                break;
            case 'presscondetails':
                $this->load->model('runways_model');
                $res = $this->runways_model->getRunwaysInsideLists($pid, $ver, $totalCount, $start, $limit, TRUE);
                if (!empty($res)) {
                    foreach ($res as $key => $val) {
                        $id = $val['id'];
                        $tableName = getProductTableName($table);//表名
                        $data = OpPopFashionMerger::getProductData($id, $tableName);

                        $jsonArr[$key]['tableName'] = $table;
                        $jsonArr[$key]['id'] = $id;
                        $jsonArr[$key]['columnId'] = $col;
                        $jsonArr[$key]['ver'] = $ver;
                        $jsonArr[$key]['offset'] = $start + $key;
                        //图片路径
                        $imgPath = getImagePath($tableName, $data[$id]);
                        // $jsonArr[$key]['bigPath'] = getFixedImgPath($imgPath['bigPath']);
                        $jsonArr[$key]['smallPath'] = getFixedImgPath($imgPath['smallPath']);
                    }
                }
                break;
            // m书店
            case 'moscon':
                $this->load->model('books_model');
                $res = $this->books_model->getSecondLevelList('moscon', $pid, $totalCount, $page, $limit, TRUE);
                if (!empty($res)) {
                    $pic_name = json_decode($res['pic_name']);

                    //图片路径
                    $tableName = getProductTableName($table);//表名
                    $imgPath = getImagePath($tableName, $res);
                    foreach ($pic_name as $key => $val) {
                        $id = $res['id'];

                        $jsonArr[$key]['tableName'] = $table;
                        $jsonArr[$key]['id'] = $id;
                        $jsonArr[$key]['columnId'] = $col;
                        $jsonArr[$key]['offset'] = $start + $key;
                        // $jsonArr[$key]['bigPath'] = getFixedImgPath($imgPath['booklist'][$key]['bigPath']);
                        $jsonArr[$key]['smallPath'] = getFixedImgPath($imgPath['booklist'][$key]['smallPath']);
                    }
                }
                break;
            case 'designrefrencedetails':
                $ttable = 'designrefrence';
                $this->load->model('books_model');
                $res = $this->books_model->getSecondLevelList($ttable, $pid, $totalCount, $page, $limit, TRUE);
                if (!empty($res[$ttable])) {
                    foreach ($res[$ttable] as $key => $val) {
                        $id = $val['id'];
                        $tableName = getProductTableName($table);//表名
                        $data = OpPopFashionMerger::getProductData($id, $tableName);

                        $jsonArr[$key]['tableName'] = $table;
                        $jsonArr[$key]['id'] = $id;
                        $jsonArr[$key]['columnId'] = $col;
                        $jsonArr[$key]['offset'] = $start + $key;
                        //图片路径
                        $imgPath = getImagePath($tableName, $data[$id]);
                        // $jsonArr[$key]['bigPath'] = getFixedImgPath($imgPath['bigPath']);
                        $jsonArr[$key]['smallPath'] = getFixedImgPath($imgPath['smallPath']);
                    }
                }
                break;
            case 'vectorrefrence':
                $ttable = 'refrencegroup';
                $this->load->model('books_model');
                $res = $this->books_model->getSecondLevelList($ttable, $pid, $totalCount, $page, $limit, TRUE);
                if (!empty($res[$ttable])) {
                    foreach ($res[$ttable] as $key => $val) {
                        $id = $val['id'];
                        $tableName = getProductTableName($table);//表名
                        $data = OpPopFashionMerger::getProductData($id, $tableName);

                        $jsonArr[$key]['tableName'] = $table;
                        $jsonArr[$key]['id'] = $id;
                        $jsonArr[$key]['columnId'] = $col;
                        $jsonArr[$key]['offset'] = $start + $key;
                        //图片路径
                        $imgPath = getImagePath($tableName, $data[$id]);
                        // $jsonArr[$key]['bigPath'] = getFixedImgPath($imgPath['bigPath']);
                        $jsonArr[$key]['smallPath'] = getFixedImgPath($imgPath['smallPath']);
                    }
                }
                break;
        }
        $ret = ['result' => $jsonArr, 'total' => $totalCount];
        echo json_encode($ret);
        return;
    }

    /**
     * 服装杂志
     */
    public function getMagazinePicLists()
    {
        $this->load->model('books_model');

        $magaID = $this->input->post('id');
        $ver = $this->input->post('ver');
        $page = intval($this->input->post('page'));
        $limit = intval($this->input->post('limit'));

        $TempID = explode('I', $magaID);
        unset($TempID[count($TempID) - 1]);
        $magazineID = rtrim(implode('I', $TempID), 'I');

        $magazieInfo = [];
        $picLists = $this->books_model->getMagazineInfo($magazineID, $magazieInfo, $ver);

        $totalCount = count($picLists);

        $start = ($page - 1) * $limit;
        $end = $start + $limit;
        if ($start > $totalCount) {
            $start = $end = $totalCount;
        } else {
            if ($end > $totalCount) {
                $end = $totalCount;
            }
        }

        $jsonArr = [];
        for ($i = $start; $i < $end; $i++) {
            $val = $picLists[$i];
            //大图路径
            if ($ver == "isSimple") {
                if ($val['is_old']) {
                    $arr_id = (array)$val['id'];
                    $big_img_name = md5(end(explode("/", $val['img_url'])) . $val['list_img_name'] . 'www.pop-fashion.com') . '.jpg'; //拼大图片名字
                    $big_image = IMAGE_MAGAZINE_PATH . '/' . $val['img_url'] . '/simple/images/' . $big_img_name;
                    $small_img_url = IMAGE_MAGAZINE_PATH . '/' . $val['img_url'] . '/simple/thumbnails/' . $val['list_img_name'];
                } else {
                    $arr_id = (array)$val['id'];
                    if ($val['source'] != "") { // source
                        $web_site = $val['source'];
                    } else {
                        $web_site = $val['site'];
                    }
                    $big_image = '/' . $web_site . '/' . substr($val['create_date'], 0, 4) . '/' . $val['create_date'] . '/' . $val['serial_number'] . "/detail/big/" . md5($arr_id['$id']) . '_' . $val['list_img_name'];
                    $small_img_url = '/' . $web_site . '/' . substr($val['create_date'], 0, 4) . '/' . $val['create_date'] . '/' . $val['serial_number'] . "/detail/small/" . $val['list_img_name'];
                }
            } else {
                $arr = explode('I', $magazineID);
                $vcount = count($arr);
                if ($vcount == 2) {
                    // 老数据
                    $big_img_name = md5(end(explode("/", $val['img_url'])) . $val['list_img_name'] . 'www.pop-fashion.com') . '.jpg'; //拼大图片名字
                    $big_image = IMAGE_MAGAZINE_PATH . '/' . $val['img_url'] . '/detail/images/' . $big_img_name;
                    $small_img_url = IMAGE_MAGAZINE_PATH . '/' . $val['img_url'] . '/detail/thumbnails/' . $val['list_img_name'];
                } else {
                    $arr_id = (array)$val['id'];
                    if ($val['source'] != "") { // source
                        $web_site = $val['source'];
                    } else {
                        $web_site = $val['site'];
                    }
                    $big_image = '/' . $web_site . '/' . substr($val['create_date'], 0, 4) . '/' . $val['create_date'] . '/' . $val['serial_number'] . "/detail/big/" . md5($arr_id['$id']) . '_' . $val['list_img_name'];
                    $small_img_url = '/' . $web_site . '/' . substr($val['create_date'], 0, 4) . '/' . $val['create_date'] . '/' . $val['serial_number'] . "/detail/small/" . $val['list_img_name'];
                }
            }

            $jsonArr[$i]['tableName'] = 'imgcol_set';
            $jsonArr[$i]['id'] = $magazineID . 'I' . str_replace(array('.', '_'), array('qq', '-'), $picLists[$i]['list_img_name']);
            $jsonArr[$i]['columnId'] = '';
            $jsonArr[$i]['offset'] = $i;
            $jsonArr[$i]['bigPath'] = $big_image;
            $jsonArr[$i]['smallPath'] = STATIC_URL2 . $small_img_url;
        }

        $ret = ['result' => $jsonArr, 'total' => $totalCount];
        echo json_encode($ret);
        return;
    }

    // 个人中心创建编辑工作台
    public function createWorbenchUC()
    {
        $this->load->model('Workbench_model');
        $sAccountId = getUserId();
        $workBenchId = (int)$this->input->post('workBenchId');

        //工作台名称
        $sName = $this->input->post('tableName');
        //工作台描述
        $describe = $this->input->post('describe');
        if (empty($sName)) {
            echo '工作台名字不能为空';
            return;
        }

        $r = $this->Workbench_model->checkWorkBenchExisted($workBenchId, $sAccountId, $sName);
        if ($r) {
            echo '该工作台已存在';
            return;
        }

        if ($workBenchId > 0) {
            /*
             * 修改
             */
            $res = $this->Workbench_model->updateWorkBench($sName, $describe, $workBenchId);
            if ($res) {
                //echo '修改成功';
                return;
            } else {
                echo '修改失败';
                return;
            }
        } else {
            /*
             * 新增
             */
            $res = $this->Workbench_model->getWorkBenchByAccountId($sAccountId);
            if (count($res) >= 999) {
                echo '最多可添加999个工作台';
                return;
            } else {
                $data['sWbenchName'] = $sName;
                $data['sWbenchDesc'] = $describe;
                $data['sAccountId'] = $sAccountId;
                $data['dCreateTime'] = date('Y-m-d h:i:s');
                $result = $this->Workbench_model->createWorkbench($data);
                if ($result) {
                    echo $result;
                    return;
                } else {
                    echo '添加失败';
                    return;
                }
            }
        }
    }

    //个人中心删除工作台
    public function deleteWorkbench()
    {
        $this->load->model('Workbench_model');
        $DeleteWorkBenchId = intval($_POST['DeleteWorkBenchId']);
        if (!$DeleteWorkBenchId) {
            echo '删除失败';
            return;
        } else {
            $res = $this->Workbench_model->deleteWorkbench($DeleteWorkBenchId);
            if ($res) {
                //echo '删除成功';
                return;
            } else {
                echo '删除失败';
                return;
            }
        }
    }

    //个人中心生成下载包
    public function workbenchDown()
    {
        $this->load->model('Workbench_model');
        $sAccountId = getUserId();
        $iWorkbenchId = (int)$this->input->get_post('iWorkbenchId');
        $workBenchName = $this->input->get_post('sName');
        // 工作台描述参数，用作用户自定义下载包名称
        $workBenchDescribe = $this->input->get_post('sDescribe');
        $workBenchPicList = $this->input->get_post('images') ?: [];
        $total = count($workBenchPicList);

        // 图片路径
        $sFilePath = '';
        foreach ($workBenchPicList as $item) {
            // 将图片资源中的域名地址替换掉
            // $sFilePath .= preg_replace('/^http(s)?:\/\/[\w.-]+\//', '/data/htdocs/popfashion2016/', $item) . ' ';
            $sFilePath .= '/data/htdocs/popfashion2016' . parse_url($item, PHP_URL_PATH) . ' ';
        }

        $prefix = '/data/htdocs/popfashion2016';
        $filepath = '/fashion/zip/' . $sAccountId . '/' . date('Ymd') . '/';
        // 文件名称 工作台名称-用户命名下载包名称
        $filename = $workBenchName . '-' . $workBenchDescribe . time() . '.zip';

        $row['sAccountId'] = $sAccountId;
        $row['iWorkbenchId'] = $iWorkbenchId;
        $row['sZippath'] = $filepath . $filename;
        $row['iImgNum'] = $total;
        $row['dCreateTime'] = date('Y-m-d H:i:s');
        $row['sWbenchName'] = $workBenchName;
        $row['sWbenchDesc'] = $workBenchDescribe;

        $iDownloadId = $this->Workbench_model->insertDownBag($row);
        if ($iDownloadId) {
            $params = array(
                'iWorkbenchId' => $iWorkbenchId,
                'sAccountId' => $sAccountId,
                'sZipPath' => $prefix . $filepath . $filename,
                'sFilePath' => $sFilePath,
                'total' => $total,
                'iDownloadId' => $iDownloadId
            );
            $OpPopAsyncMsg = new OpPopAsyncMsg();
            $res = $OpPopAsyncMsg->pop_async_msg_send('zip', 'workbench', $params, 'workbench');
            if ($res) {
                echo 1;
                return;
            }
        }

        echo -1;
        return;
    }

    //我的工作台单张图片删除
    public function picDelete()
    {
        $this->load->model('Workbench_model');
        $picId = intval($_POST['picId']);
        if (!$picId) {
            echo '删除失败';
            return;
        } else {
            $res = $this->Workbench_model->deletePic($picId);
            if ($res) {
                echo '删除成功';
                return;
            } else {
                echo '删除失败';
                return;
            }
        }
    }

    //我的工作台批量图片删除
    public function batchDelete()
    {
        //加载model层
        $this->load->model('Workbench_model');
        //接收前台传送的数据
        $picId = $_POST['picID'];
        $data['code'] = 0;
        $data['msg'] = "";
        $data['info'] = array();
        $data['data'] = array();
        //判断接收的数据是否为空
        if (empty($picId)) {
            $data['code'] = 1;
            $data['msg'] = "没有传值";
        } else {
            //去除左右两边的逗号分割成数组转换成int类型
            $val = explode(",", trim($picId, ","));
            $val = array_map("intval", $val);
            //调用model层方法进行修改数据
            $res = $this->Workbench_model->deleteBath($val);
            //判断是否修改成功
            if ($res) {
                $data['code'] = 0;
                $data['msg'] = "ok";
            } else {
                $data['code'] = 2;
                $data['msg'] = "删除失败";
            }
        }
        echo json_encode($data);
        return;
    }

    // 获取面料列表
    public function getFabricList()
    {
        $path = $this->input->post('path', TRUE);
        if (empty($path)) {
            echo [];
            return;
        };
        $this->load->model('details_model');
        $list = $this->details_model->getFabricList($path, true);
        //应需求，隐藏面料列表--20161011
        if (isset($list['data'])) {
            echo json_encode(['fabricList' => $list, 'fabricListCnt' => count($list['data'])]);
        } else {
            echo json_encode(['fabricList' => $list, 'fabricListCnt' => 0]);
        }
        return;
    }

    // 交叉页上下翻页
    public function crossPageScroll()
    {
        $this->load->model('mutual_model');
        $this->load->model('common_model');
        $page = intval($this->input->post('page', TRUE));
        $column = intval($this->input->post('col', TRUE));
        $limit = 0;
        switch ($column) {
            case 1:    // 未来趋势
            case 2:    // 潮流解析
                $limit = 2;
                break;
            case 4:    //款式库
                $limit = 14;
                break;
            case 3: // T台发布
            case 7:    // 设计素材
            case 8:    // 灵感源
                $limit = 3;
                break;
            case 6:    // 手稿合辑
                $limit = 5;
                break;
            case 5:    // 品牌库
                $limit = 4;
                break;
            default:
                break;
        }
        $offset = --$page * $limit;
        // $list = $this->mutual_model->getCombineData($column, $offset, $limit);

        $iGender = $this->common_model->getGenderByRequest();
        $iIndustry = $this->common_model->getIndustryByRequest();

        $list = $this->mutual_model->getMutualData($iGender, $iIndustry);

        $lists = [];
        $i = 1;
        if (is_array($list[$column]['data'])) {
            foreach ($list[$column]['data'] as $key => $val) {
                if ($key >= $offset) {
                    $lists[] = $val;
                    $i++;
                }
                if ($i > $limit) {
                    break;
                }
            }
        }
        $lists = !empty($lists) ? $lists : [];
        echo json_encode(['lists' => $lists, 'gen' => $iGender, 'ind' => $iIndustry]);
        return;
    }

    // 更多推荐 非报告（款式等）
    public function getMoreRec()
    {
        $t = $this->input->post('t', TRUE);
        $id = intval($this->input->post('id', TRUE));
        $col = intval($this->input->post('col', TRUE));
        $table = getProductTableName($t);

        $this->load->model('report_model');
        $moreRec = $this->report_model->getMoreRecList($table, $id, $col);
        $recList = [];
        if (is_array($moreRec) && count($moreRec)) {
            $recList['report_one'] = array_slice($moreRec['reports'], 0, 2);
            $recList['report_two'] = array_slice($moreRec['reports'], 2, 2);
            $recList['style_one'] = array_slice($moreRec['pics'], 0, 3);
            $recList['style_two'] = array_slice($moreRec['pics'], 3, 3);
            $recList['STATIC_URL3'] = STATIC_URL3;
        }
        echo json_encode($recList);
        return;
    }

    /**
     * BI埋点
     * 获取报告的属性
     */
    public function getReportProp()
    {
    }


    // 搜索页面做后面5个异步	$params = 'gen_1-ind_7'
    public function getSearchData($params = '')
    {
        $this->load->model('common_model');
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        // jsSearch
        $result = [
            'fore' => ['data' => [], 'cnt' => 0], 'runw' => ['data' => [], 'cnt' => 0],
            'book' => ['data' => [], 'cnt' => 0], 'look' => ['data' => [], 'cnt' => 0],
            'orderm' => ['data' => [], 'cnt' => 0], 'patterns' => ['data' => [], 'cnt' => 0],
            'other' => ['data' => [], 'cnt' => 0]
        ];

        $jsSearch = $this->input->get('cols', TRUE);
        if ($jsSearch) {
            $jsSearcha = array_map(function ($val) {
                if ($val == 'other') {
                    return $val;
                } else {
                    return intval($val);
                }
            }, explode(',', $jsSearch));

            $this->load->model('search_model');
            // 构造condition
            $condition = [];

            $other = [];
            // 关键字
            $keyword = $this->input->get('key', TRUE);
            if ($keyword) {
                $other['k'] = "combine:(" . str_replace(['"', "'"], '', $keyword) . ')';
            }
            // 性别
            $gender = $this->common_model->getGenderByRequest($paramsArr);
            if ($gender) {
                if ($gender == 5) {
                    $_gender = 'aLabelIds:(3 OR 4 OR 5)';
                } else {
                    $_gender = 'aLabelIds:(' . $gender . ')';
                }
                $other['g'] = $_gender;
            }
            // 行业
            $industry = $this->common_model->getIndustryByRequest($paramsArr);
            if ($industry) {
                $other['i'] = '(aLabelIds:' . $industry . ')';
            }

            $columnName = [1 => 'fore', 3 => 'runw', 6 => 'book', 71 => 'look', 131 => 'orderm', 82 => 'patterns', 'other' => 'other'];
            foreach ($jsSearcha as $columnId) {
                // 未来趋势, T台发布, 刊物书店, Lookbook, 图案库-图案素材(82)
                if (in_array($columnId, [1, 3, 6, 71, 131, 82])) {
                    if (is_array($other) && count($other)) {
                        $condition['other'] = implode(' AND ', $other);
                    }
                    $result[$columnName[$columnId]] = $this->search_model->getInfoByColumnId($columnId, $condition, $params);
                } /**
                 * other 行业和性别都有 : (80,81);只有性别:(85,117);两者都无:90,116,84
                 * 灵感源(灵感报告90),灵感视频(116),款式模板(80),款式细节(81),店铺陈列(85),展会面料(117),服饰品(84)
                 */
                elseif ($columnId == 'other') {
                    if (is_array($other) && count($other)) {
                        $condition['other'] = implode(' AND ', $other);
                    }
                    $otherRes = [];
                    $otherRes[80] = $this->search_model->getDesign(80, $condition);
                    $otherRes[81] = $this->search_model->getDesign(81, $condition);

                    if (isset($other['i'])) {
                        unset($other['i']);
                    }
                    if (is_array($other) && count($other)) {
                        $condition['other'] = implode(' AND ', $other);
                    }
                    $otherRes[85] = $this->search_model->getDesign(85, $condition);

                    if (isset($other['g'])) {
                        unset($other['g']);
                    }
                    if (is_array($other) && count($other)) {
                        $condition['other'] = implode(' AND ', $other);
                    }
                    $otherRes[117] = $this->search_model->getDesign(117, $condition);

                    if (isset($other['i'])) {
                        unset($other['i']);
                    }
                    if (isset($other['g'])) {
                        unset($other['g']);
                    }
                    if (is_array($other) && count($other)) {
                        $condition['other'] = implode(' AND ', $other);
                    }
                    $otherRes[90] = $this->search_model->getDesign(90, $condition);
                    $otherRes[116] = $this->search_model->getDesign(116, $condition);
                    $otherRes[84] = $this->search_model->getDesign(84, $condition);

                    $total = array_sum(array_column($otherRes, 'cnt'));
                    $result[$columnName[$columnId]] = ['cnt' => $total, 'data' => $otherRes];
                }
            }
        }
        echo json_encode($result);
        return;
    }


    // 将用户试衣的的记录存入表t_3d_log中
    public function recordFitting()
    {
        $this->load->model('details_model');
        $userinfo_id = getUserId();
        $table = $this->input->get_post('table', TRUE);
        // 将table转为真实表名
        $realTable = getProductTableName($table);
        $id = $this->input->get_post('id', TRUE);
        $data['sAccountId'] = $userinfo_id;
        $data['sTableName'] = $realTable;
        $data['iPriId'] = $id;
        $insertId = $this->details_model->saveFittingLog($data);
        echo $insertId;
        return;
    }

    public function getBrandData($params = '')
    {
        $this->load->model('brands_model');
        $totalcount = 0;
        $result = ['code' => 0, 'msg' => '', 'data' => '', 'total' => $totalcount];

        $param = $this->common_model->parseParams($params, 1, false);
        //处理预选中的性别和行业
        $condition = [];
        $condition['iColumnId'] = '5';

        $gender = $this->common_model->getGenderByRequest($params);
        $industry = $this->common_model->getIndustryByRequest($params);
        if (!empty($gender)) {
            $condition['other'][] = 'aLabelIds:' . intval($gender);
        }
        if (!empty($industry)) {
            $condition['other'][] = 'aLabelIds:' . intval($industry);
        }
        if (!empty($param['age'])) {
            $condition['other'][] = 'aLabelIds:' . intval($param['age']);
        }
        if (!empty($param['bpos'])) {
            $condition['other'][] = 'aLabelIds:' . intval($param['bpos']);
        }
        if (!empty($param['reg'])) {
            $condition['other'][] = '(iArea:' . intval($param['reg']) . ' OR iContinent:' . intval($param['reg']) . ' OR iCountry:' . intval($param['reg']) . ' OR iRegion:' . intval($param['reg']) . ')';
        }
        //搜索框
        $seachKey = $this->input->post('searchKey', true);
        $seachKey = str_replace(' ', '*', strtolower($seachKey));
        if (!empty($seachKey)) {
            $condition['other'][] = '(sBrandName:' . $seachKey . '* OR sBrandBname:' . $seachKey . '*)';
        }
        //首字母
        $initial = $this->input->post('initial', true);
        $initial = in_array($initial, range('A', 'Z')) ? $initial : strtolower($initial);
        if (!empty($initial) && $initial != "all") {
            $condition['sBrandAlias'] = $initial;
        }

        //通过solr搜索符合条件的品牌数据返回
        $data = $brandsInfo = $brandids = [];

        $arSort = ['sBrandAlias' => 'ASC', 'sBrandName' => 'ASC'];
        $page = max(intval($this->input->post_get('page', true)), 1);
        $pagesize = 100;

        //从第二页开始，每次把上一次的最后一个取出来，用于和本次的第二条数据做对比
        //假设上次取的数据是以A字母开头的，如果这两条数据的首字母值一样
        //说明上次没有把A的数据取完
        if ($page > 1) {
            $offset = ($page - 1) * $pagesize - 1;
            $pagesize += 1;
        } else {
            $offset = ($page - 1) * $pagesize;
        }

        $this->benchmark->mark('actionAjax');
        $totalcount = POPSearch::wrapQueryPopFashionMerger('', $condition, $brandDataRes, $offset, $pagesize, $arSort);

        foreach ($brandDataRes as $val) {
            array_push($brandids, intval($val['pri_id']));
        }
        $brandsInfo = array_merge($brandsInfo, $this->brands_model->getBrandsFromId($brandids));

        //如果第一个和第二个的首字母值一样，记住该首字母
        $lastTimeAlias = 'AZ';
        if ($page > 1) {
            if ($brandsInfo[0]['alias'] == $brandsInfo[1]['alias']) {
                $lastTimeAlias = strtolower($brandsInfo[0]['alias']);
                unset($brandsInfo[0]);
            }
            $brandsInfo = array_values($brandsInfo);
        }

        $residueData = [];
        foreach ($brandsInfo as $brandInfo) {
            $brandid = $brandInfo['id'];
            $brandname = isset($brandInfo['b_name']) && !empty($brandInfo['b_name']) ? $brandInfo['name'] . '/' . $brandInfo['b_name'] : $brandInfo['name'];

            //如果该次取出的数据结果中，首字母和上次的最后一条的首字母相同，则这些数据用于追加到上一类中
            $alias = strtolower($brandInfo['alias']);
            if ($alias == $lastTimeAlias) {
                $residueData[] = ['i' => $brandid, 'n' => $brandname];
            } elseif (!empty($alias)) {
                $data[$alias][] = ['i' => $brandid, 'n' => $brandname];
            }
        }

        //查询有哪些首字母是可用的
        $groupParams = $letters = [];
        $groupParams['group'] = 'true';
        $groupParams['group.limit'] = 0;
        $groupParams['group.ngroups'] = 'true';
        $groupParams['group.field'] = 'sBrandAlias';
        POPSearch::wrapQueryPopFashionMerger('', $condition, $letterRes, 0, 28, $arSort, $groupParams);
        foreach ($letterRes['sBrandAlias']['groups'] as $val) {
            array_push($letters, strtoupper($val['groupValue']));
        }
        $this->benchmark->mark('actionAjaxEnd');

        //上个首字母分类的剩余数据
        $residueDataHtml = '';
        if ($residueData) {
            $residueHtml = [];
            foreach ($residueData as $_val) {
                array_push($residueHtml, '<a href="/brands/detail/id_' . $_val['i'] . '/" target="_blank" data-id="' . $_val['i'] . '">' . $_val['n'] . '</a>');
            }
            $residueDataHtml = implode(' ', $residueHtml);
        }

        //新首字母分类的数据
        $dataHTML = '';
        if ($data) {
            $aHtml = [];
            foreach ($data as $key => $val) {
                $key = strtoupper($key);
                $initial = $key == "NUMBER" ? '0-9' : ($key == "OTHER" ? '其他' : $key);

                array_push($aHtml, '<li class="clearfix"><h3 class="fl">' . $initial . '</h3><div class="brand_result_list">');
                if ($val) {
                    foreach ($val as $_val) {
                        array_push($aHtml, '<a href="/brands/detail/id_' . $_val['i'] . '/" target="_blank" data-id="' . $_val['i'] . '">' . $_val['n'] . '</a>');
                    }
                }
            }
            array_push($aHtml, '</div></li>');
            $dataHTML = implode(' ', $aHtml);
        }

        $result['code'] = 'success';
        $result['msg'] = '请求成功';
        $result['total'] = $totalcount;
        $result['letter'] = $letters;
        $result['data'] = $dataHTML;
        $result['residue'] = $residueDataHtml;
        echo json_encode($result);
        return;
    }

    // 客户服务查看更多
    public function getMoreCustomerCase()
    {
        $this->load->model('Customer_model');
        $page = $this->input->post('page');
        $pageSize = $this->input->post('pageSize');
        $totalCount = 0;
        $offset = ($page - 1) * ($pageSize);// 偏移量
        $jsonArr = [];
        $jsonArr['isHide'] = 0;
        $condition = [];
        $condition['iPublishStatus'] = 1;//发布
        $aLists = $this->Customer_model->getCustomerLists($condition, $offset, $pageSize, [], $totalCount);
        $jsonArr['list'] = $aLists;
        if ($offset + $pageSize >= $totalCount) {
            $jsonArr['isHide'] = 1;
        }
        echo json_encode($jsonArr);
        return;
    }


    /**
     * 点击领取免费试用后展示的免费试用项目
     */
    public function getProbationItems()
    {
        $identity = getCommonData('probation_data', 'identity');
        $category = getCommonData('probation_data', 'category');
        $data = [
            'code' => 0,
            'message' => '',
            'data' => [
                'identity' => $identity,
                'category' => $category
            ]
        ];
        echo json_encode($data);
        return;
    }

    /**
     * 领取试用
     * 参数：identity,company,category(categoryIds={"1":[101,102,103],"2":[201,202,203]})
     */
    public function obtainProbation()
    {
        $identityId = $this->input->get_post('identity', true);
        $company = $this->input->get_post('company', true);
        $categoryIds = $this->input->get_post('category', true);
        $sAccountId = getUserId();
        $mobile = $this->input->get_post('mobile', true); // 验证手机号
        $smsCode = $this->input->get_post('sms_code', true); // 短信验证码
        if (is_null($sAccountId)) {
            $this->outputJson(1, '请登录后再试');
            return;
        }
        $this->load->model('Member_model');
        $isTelVerified = $this->member_model->isTelVerified($sAccountId);
        if ($isTelVerified != 'isVerified') {
            // 尚未验证过手机号
            if (empty($mobile) || empty($smsCode)) {
                $this->outputJson(1, '请输入您的手机号和正确的短信验证码');
                return;
            }
            // 短信验证码是否正确
            $sql = "SELECT iVerifyID FROM " . POP_Model::T_POP136_GLOBAL_CELLPHONE_VERIFY . " WHERE sCellPhone=? AND sVerifyCode=? AND iVerifyStatus = 0 AND iWebsite = 10";
            $dbrow = $this->member_model->db()->query($sql, [$mobile, $smsCode])->row_array();
            if (empty($dbrow)) {
                $this->outputJson(1, '短信验证码不正确');
                return;
            }
            // 改状态为已验证
            $sql = "UPDATE " . POP_Model::T_POP136_GLOBAL_CELLPHONE_VERIFY . " SET iVerifyStatus=1,dVerifiedTime=? WHERE iVerifyID=?";
            $time = date('Y-m-d H:i:s');
            $this->member_model->db()->query($sql, [$time, $dbrow['iVerifyID']]);

            $sql = "UPDATE " . POP_Model::T_POP136_FASHION_USER . " SET tel=?,mobile=?,bind_mobile=? WHERE id=?";
            $this->member_model->db()->query($sql, [$mobile, $mobile, $mobile, $sAccountId]);
            $sql = "UPDATE " . POP_Model::T_FASHION_FASHION_USER . " SET tel=?,mobile=?,bind_mobile=? WHERE id=?";
            $this->member_model->db()->query($sql, [$mobile, $mobile, $mobile, $sAccountId]);
        }
        // 获取配置文件中的身份数据和栏目类别数据
        $identityData = getCommonData('probation_data', 'identity');
        $categoryData = getCommonData('probation_data', 'category');
        if (!array_key_exists($identityId, $identityData)) {
            $this->outputJson(1, '请选择您的身份');
            return;
        } else {
            if ($identityData[$identityId]['company'] == 0) {
                $company = '';
            }
        }

        // 用户提交的栏目id
        if (!is_array($categoryIds)) {
            $categoryIds = json_decode($categoryIds, true);
        }
        if (!is_array($categoryIds)) {
            $this->outputJson(1, '请传入正确的栏目id数据格式');
            return;
        }
        if (count($categoryIds) > 0 && count($categoryIds) < count($categoryData)) {
            $this->outputJson(1, '每个类别需要至少选择一个栏目');
            return;
        }
        $sCategory = [];
        foreach ($categoryIds as $k => $catArr) {
            $empty = true;
            foreach ($catArr as $id) {
                if (array_key_exists($id, $categoryData[$k])) {
                    $empty = false;
                    array_push($sCategory, $id);
                }
            }
            if ($empty) {
                $this->outputJson(1, '每个类别需要至少选择一个栏目');
                return;
            }
        }
        $sCategory = implode(',', $sCategory);
        $sql = "SELECT id, iAccountId, iIdentityId,sCompany,sCategory,iShowPopup,dCreateTime FROM t_probation_record WHERE iAccountId= ?";
        $query = $this->db->query($sql, $sAccountId);
        $row = $query->row_array();
        if (is_null($row)) {
            // 未领取过免费试用
            // 试用表
            $sql = "INSERT INTO t_probation_record SET iAccountId= ?,iIdentityId= ?,sCompany= ?,sCategory= ?";
            $query = $this->db->query($sql, array($sAccountId, $identityId, $company, $sCategory));
            // 权限表
            $sColumn = '4,50,52,54,55,56,57,9,82,120,121,122,123'; // 款式库，图案库
            $sIndustry = '6,7,8,9,10,11,12,158,159'; // 行业
            $now = time();
            $dStartTime = date('Y-m-d H:i:s', $now);
            $dEndTime = date('Y-m-d H:i:s', $now + 30 * 60); // 30分钟试用
            $dCreateTime = $dStartTime;
            $sql = "INSERT INTO fm_privilege SET iAccountId= ?,sGender='1,2,5',sIndustry= ?,sColumn= ?,iType=1,dStartTime= ?,dEndTime= ?,dCreateTime= ?,iFrom=1";
            $query = $this->db->query($sql, array($sAccountId, $sIndustry, $sColumn, $dStartTime, $dEndTime, $dCreateTime));
            $this->outputJson(0, '成功领用免费试用资格', [], [], false);
            // 刷新权限
            $M_ID = $sAccountId;
            $T = $now;
            $token = MD5(MD5($M_ID . '-' . $T . POP_GLOBAL_KEYS));
            $baseUrl = $this->config->item('base_url');
            $flushUrl = $baseUrl . 'api/clearprivilege/?M_ID=' . $M_ID . '&T=' . $T . '&token=' . $token;
            $opCurl = OpCurl::getInstance($flushUrl);
            $opCurl->createCurl();
            $ret = $opCurl->__tostring();
        } else {
            $this->outputJson(1, '您已领取过免费试用');
            return;
        }
    }

    /**
     * 免费试用结束弹窗提示只显示一次，显示一次后调用该接口之后就不再显示
     */
    public function probationPopupShown()
    {
        $sAccountId = getUserId();
        if (is_null($sAccountId)) {
            $this->outputJson(1, '请登录后再试');
            return;
        }
        $this->load->database();
        $sql = "SELECT id, iAccountId, iIdentityId,sCompany,sCategory,iShowPopup,dCreateTime FROM t_probation_record WHERE iAccountId= ? LIMIT 1";
        $query = $this->db->query($sql, $sAccountId);
        $row = $query->row_array();
        if (is_null($row)) {
            $this->outputJson(1, '您未领取过免费试用');
            return;
        }

        // 刷新权限
        $M_ID = $sAccountId;
        $T = time();
        $token = MD5(MD5($M_ID . '-' . $T . POP_GLOBAL_KEYS));
        $baseUrl = $this->config->item('base_url');
        $flushUrl = $baseUrl . 'api/clearprivilege/?M_ID=' . $M_ID . '&T=' . $T . '&token=' . $token;
        $opCurl = OpCurl::getInstance($flushUrl);
        $opCurl->createCurl();
        $ret = $opCurl->__tostring();

        // 是否已过试用期暂未做判断
        if ($row['iShowPopup']) {
            $sql = "UPDATE t_probation_record SET iShowPopup=0 WHERE iAccountId= ? ";
            $query = $this->db->query($sql, $sAccountId);
        }
        $this->outputJson(0, 'ok');
        return;
    }

    /**
     * 显示vip创建设计师专属帐号提示框 条件：
     * 1.是vip
     * 2.未绑定子账号
     * 3.不与电子合同同时显示
     * 4.每天显示一次（自然天）
     */
    public function showVipCreateChildAccountPopup()
    {
        $this->load->helper('cookie');
        // 提示vip创建设计师专属帐号cookie key
        $cookieKey = '_VIP_CREATE_CHILD_ACCOUNT';
        // 用户id
        $sAccountId = getUserId();
        // 是否显示过电子协议
        $showElectronic = get_cookie('_ELECTRONIC_CONTRACT_NEW', TRUE);
        // 是否显示过vip弹窗
        $cookieShowVip = get_cookie($cookieKey, TRUE);
        if (!is_null($showElectronic)) {
            // 显示电子协议弹窗，则不显示vip弹窗
            echo json_encode(['code' => 1, 'message' => '显示电子协议', 'data' => []]);
            return;
        }
        if (!is_null($cookieShowVip)) {
            // 当天已显示过则不再显示
            echo json_encode(['code' => 1, 'message' => '当天已显示', 'data' => []]);
            return;
        }

        $power = memberPower();
        if ($power['P_UserType'] != 1) {
            // 不是vip不显示
            echo json_encode(['code' => 1, 'message' => '不是vip', 'data' => []]);
            return;
        }
        // 查询子账号
        $table = POP_Model::T_FASHION_FASHION_USER_CHILD;
        $field = "count(1) cnt";
        $childWhere[] = array('iParentID', 'eq', $sAccountId);
        $childWhere[] = array('iStatus', 'eq', 1);
        $this->load->model('member_model');
        $childrow = $this->member_model->getSearchLists($table, $childWhere, array(), 1, 1, $field);
        if (intval($childrow['cnt']) > 0) {
            // 已绑定子账号则不显示
            echo json_encode(['code' => 1, 'message' => '已绑定子账号', 'data' => []]);
            return;
        }
        $end = mktime(23, 59, 59);
        $expire = $end - time();
        $expire = $expire <= 0 ? 1 : $expire;
        // 设置cookie当天不再显示
        set_cookie($cookieKey, $cookieKey, $expire);
        echo json_encode(['code' => 0, 'message' => '显示', 'data' => []]);
        return;
    }


    /**
     * 输出json_encode结果到浏览器
     * @param int $code 0-成功，1-失败
     * @param string $message 提示消息
     * @param array $data 返回数据
     * @param array $info 附加信息
     * @param bool $die 是否输出后die
     */
    private function outputJson($code = 0, $message = '', $data = array(), $info = array(), $die = false)
    {
        $ret = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'info' => $info
        );
        // header('Content-Type: application/json; charset=utf-8');
        if (defined('JSON_UNESCAPED_UNICODE')) {
            echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($ret);
        }
        $die && die();
    }

    //个人中心资料包列表调用接口
    public function orderPackageZip($page = '')
    {
        $this->load->model('Workbench_model');

        $sAccountId = getUserId();
        if (empty($sAccountId)) {
            $code = 1;
            $message = '未登录';
            $ret = array(
                'code' => $code,
                'data' => array(),
                'message' => $message
            );
            echo json_encode($ret);
            return;
        }

        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');
        $con['P_UserType'] = $vipTypeArr['P_UserType']; //1 or 2
        $con['P_ExpireTime'] = $vipTypeArr['P_ExpireTime']; //过期时间

        //分页
        $pageSize = 30;
        $page = $this->input->get('page', true);
        $page = isset($page) && !empty($page) ? intval($page) : 2;
        $offset = ($page - 1) * $pageSize;

        //获取此账号下所有资料包
        $totalCount = $this->Workbench_model->getMyOrderZipByAccountId($sAccountId, $con['P_UserType'], $con['P_ExpireTime']);
        $orderZipCount = count($totalCount);
        $pageCount = ceil($orderZipCount / $pageSize);
        if ($page >= $pageCount) {
            $page = $pageCount;
        } else {
            $page = $this->input->get('page', true);
            $page = isset($page) && !empty($page) ? intval($page) : 2;
        }
        $orderZip = $this->Workbench_model->getMyOrderZipList($sAccountId, $con['P_UserType'], $con['P_ExpireTime'], $offset, $pageSize);

        if (count($orderZip) > 0) {
            $code = 0;
            $message = '列表获取成功';
        } else {
            $code = 0;
            $message = '列表为空';
        }
        $ret = array(
            'code' => $code,
            'data' => $orderZip,
            'message' => $message,
            'orderZipCount' => $orderZipCount,
            'pageCount' => $pageCount
        );
        echo json_encode($ret);
        return;
    }

    /**
     * 获取款式库首页数据
     * @param page
     * @param gender
     */
    public function getStylezoneList()
    {
        $page = $this->input->get('page', true);
        $gender = $this->input->get('gender', true);
        $this->load->model('stylezone_model');
        $this->load->helper('cookie');
        $gender = intval($gender);
        if ($gender == 0) {
            $gender = get_cookie('gender', true);
        }
        $params = [];
        if (!empty($gender)) {
            $params[] = 'gen_' . $gender;
        }

        $page = intval($page) <= 0 ? 1 : intval($page);
        $page = $page > 8 ? 8 : $page;

        $limit = 30;
        $offset = ($page - 1) * $limit;
        $lists = [];

        $params[] = 'page_' . $page;
        $params = implode('-', $params);
        $totalCount = $this->stylezone_model->getList($params, $lists, $offset, $limit);

        if ($page >= 8 || count($lists) < $limit) {
            $isLastPage = true;
        } else {
            $tempLists = [];
            $this->stylezone_model->getList($params, $tempLists, $offset + $limit, 1);
            $isLastPage = count($tempLists) == 0;
        }

        $this->outputJson(0, 'ok', ['list' => $lists, 'isLastPage' => intval($isLastPage)]);
        return;
    }

    /**
     * 获取QQ弹窗位置广告信息
     */
    public function getAlertLayerAdForQQ()
    {
        $this->load->model('common_model');

        $info = $this->common_model->getAds(0, 16, 1);

        $this->outputJson(0, 'ok', $info);
        return;
    }

    /**
     * 获取vip用户到期时间信息
     */
    public function getVipExpireInfo()
    {
        // 获取用户身份
        $power = memberPower('other');
        if (empty($power['P_ExpireTime']) || in_array($power['P_UserType'], [3, 4, 5])) {// 试用|普通用户|游客
            $this->outputJson(1, '不需要提示');
            return;
        }
        $now = time();
        $expire = strtotime($power['P_ExpireTime']);
        $sign = $expire >= $now ? 1 : -1;
        $dDiff = ceil(abs($expire - $now) / (3600 * 24));
        $data = [
            'now' => date('Y-m-d H:i:s', $now),
            'expire' => $power['P_ExpireTime'],
            'days' => $sign * $dDiff
        ];
        $message = '';
        if ($sign > 0) {
            // 未过期
            if ($dDiff <= 30) {
                $message = "VIP资格将在{$dDiff}天内到期";
            } elseif ($dDiff <= 90) {
                $message = "VIP资格即将到期";
            }
        } else {
            // 已过期
            if ($dDiff <= 30) {
                $message = "VIP资格已过期{$dDiff}天";
            }
        }
        $this->outputJson(0, $message, $data);
        return;
    }

    /**
     * 报告更多推荐
     * type: report(报告)、style(款式)
     * 2017年8月22日
     */
    public function getMoreReportRec()
    {
        $t = $this->input->post('t', TRUE);
        $id = intval($this->input->post('id', TRUE));
        $col = intval($this->input->post('col', TRUE));
        $type = $this->input->post('type', TRUE);
        $table = getProductTableName($t);

        $this->load->model('report_model');
        $moreRec = $this->report_model->getMoreRecList($table, $id, $col, $a = [], ['style' => 12, 'report' => 6]);

        $recList = [];
        if (is_array($moreRec) && count($moreRec)) {
            $recList[$type] = $type == 'report' ? $moreRec['reports'] : $moreRec['pics'];
            $recList['STATIC_URL3'] = STATIC_URL3;
        }
        $this->outputJson(0, '', $recList);
        return;
    }

    /**
     * 异步BI埋点
     * 适用于报告（后期适用于款式）
     * 2017-09-01 16:52:42
     */
    public function ajaxMoreRecSensors()
    {
    }


    /**
     * 获取报告相关面料
     * 2017-08-22
     */
    public function getFabricRel()
    {
        $id = $this->input->post('id', TRUE);
        $table = $this->input->post('t', TRUE);
        $col = $this->input->post('col', TRUE);
        $sReportID = getProductTableName($table);
        $sReportID .= '_' . $id;
        $preview = $this->input->get('preview', TRUE);
        if (empty($id) && empty($table)) {
            $this->outputJson(1, '需要id和t参数');
            return;
        }

        $retData = [];
        if ($preview) { //预览
            $ULBfabricLink = getUlbApiLink('fabrics/findFabrics', ['reportId' => $sReportID, 'preview' => 0]);
            $opcurl = opcurl::getInstance($ULBfabricLink);
            $opcurl->createCurl();
            $ULBfabricData = $opcurl->__tostring();
        } else { //正式发布 已确认，调接口
            $this->load->model('details_model');
            $fabric = $this->details_model->getFabricMatchResult($sReportID, 1);
            if ($fabric) {
                $ULBfabricLink = getUlbApiLink('fabrics/findFabrics', ['reportId' => $sReportID, 'preview' => 1]);
                if (isset($_GET['debug'])) {
                    $retData['ULBfabricLink'] = $ULBfabricLink;
                }
                $opcurl = opcurl::getInstance($ULBfabricLink);
                $opcurl->createCurl();
                $ULBfabricData = $opcurl->__tostring();
            } else {
                $ULBfabricData = '';
            }
        }

        $ULBfabricData = json_decode($ULBfabricData, true);
        $retData['fabricMoreUrl'] = $ULBfabricData['moreUrl'] ? $ULBfabricData['moreUrl'] : [];
        $retData['fabricData'] = $ULBfabricData['data'] ? $ULBfabricData['data'] : [];
        $this->outputJson(0, '', $retData);
        return;
    }

    /**
     * 虚拟样衣底部 匹配图案
     */
    public function virtualSplPicMatch()
    {
        // 图片路径
        $this->load->model('details_model');
        $path = $this->input->get_post('path');
        $count = intval($this->input->get_post('count'));
        $count = $count <= 0 ? 63 : $count;
        if (empty($path)) {
            $this->outputJson(1, '缺少path参数');
            return;
        }
        $path = urldecode($path);

        // 兼容 HTTP 与 https
        if (strpos($path, 'http://') !== false) {
            $path = $this->details_model->getVedioPath($path);// 自适应
        } else {
            $path = stristr($path, 'https://') ? $path : STATIC_URL1 . $path;
        }


        $result = OpPopMalongInterface::getPicMatchList(OpPopMalongInterface::SITE_FASHION, $path, $count);
        $this->outputJson(0, 'ok', $result['data'], array('usetime' => $result['usetime']));
        return;
    }

    /**
     * 虚拟样衣上传图片
     */
    public function virtualSplUpload()
    {
        $imgField = 'material';
        $uploadSize = 1024 * 1024 * 5;
        // 帐户
        $accountId = $this->input->get_post('accountId');
        $accountId = empty($accountId) ? '' : $accountId;
        // 原图id
        $sid = intval($this->input->get_post('sid'));
        // 切图dataUrl数据(兼容ie9)
        $postImgData = $this->input->get_post($imgField);
        if ($sid > 0) {
            // 切图的原图id
            $sourceID = 'fm_upload_pic_material_' . $sid;
            // 图片上传目录 原图和切图区分
            $dirType = 'design_area'; // 切图
        } else {
            $sourceID = '';
            $dirType = 'upload_pic_material'; // 原图
        }
        $dirSite = '';
        $dirTime = date('Y/m/d');
        $fPrefix = 'material';
        // 上传目录
        $dirUpload = "/{$dirSite}{$dirType}/{$dirTime}/";
        $fileName = '';
        $fStream = '';
        $imgPath = '';
        $iBase64 = 0;

        if (!isset($_FILES[$imgField]) || $_FILES[$imgField]['size'] == 0) {
            if (empty($postImgData)) {
                $this->outputJson(1, '请上传要匹配的图片！');
                return;
            } else {
                // 将canvas的toDataUrl得到的数据保存为png图片
                $imgStr = $postImgData;
                $imgData = substr($imgStr, strpos($imgStr, ",") + 1);
                $fileName = md5(uniqid()) . '.png';
                $fStream = $imgData;
                $iBase64 = 1;
            }
        } else {
            $file = $_FILES[$imgField];
            if (!in_array(strtolower($file['type']), array('image/gif', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/png'))) {
                $this->outputJson(1, '请上传正确的图片格式！');
                return;
            }

            if ($file['size'] > $uploadSize) {
                $this->outputJson(1, '上传的图片大小不能超过' . ($uploadSize / 1024 / 1024) . 'MB！');
                return;
            }

            $fileExt = substr($file['type'], 6);
            $fileName = md5(uniqid()) . '.' . $fileExt;
            $fStream = file_get_contents($file['tmp_name']);
            $iBase64 = 0;
        }

        $apiUpload = OpCurl::getInstance("http://api.pop136.com/internal/UploadApi.php");
        $uploadData = array(
            'fName' => $fileName,
            'fTargetPath' => $dirUpload,
            'fPrefix' => $fPrefix,
            'iBase64' => $iBase64,
            'fStream' => $fStream
        );
        $uploadRet = json_decode($apiUpload->post($uploadData), true);
        if (isset($uploadRet['status']) && $uploadRet['status']) {
            $imgPath = $uploadRet['info'];
        } else {
            $this->outputJson(1, '保存上传图片失败！');
            return;
        }

        if ($sid > 0) { // 切图
            $table = 'fm_design_area';
            $rows = array(
                'sAccountId' => $accountId,
                'sDesignAreaImg' => $imgPath,
                'iSourceType' => 2, // 用户上传
                'sSourceId' => $sourceID,
                'dCreateTime' => date('Y-m-d H:i:s')
            );
        } else {
            $table = 'fm_upload_pic_material';
            $rows = array(
                'sAccountId' => $accountId,
                'sUploadPicImg' => $imgPath,
                'dCreateTime' => date('Y-m-d H:i:s')
            );
        }

        $this->load->model('common_model');
        $this->common_model->db()->insert($table, $rows);
        $id = $this->common_model->db()->insert_id();

        $this->outputJson(0, '上传成功！', array('id' => $id, 'imgPath' => $imgPath));
        return;
    }

    /**
     * 图案、款式详情页 获取底部搜索列表数据
     */
    public function getBottomDataList()
    {
        $params = $this->input->get_post('params');
        $page = intval($this->input->get_post('page'));
        $columnId = intval($this->input->get_post('col'));
        $columnPid = GetCategory::getOtherFromColId($columnId);
        $columnPid = $columnPid == 0 ? $columnId : $columnPid;
        // 若参数中没有性别条件则额外判断cookie中是否有
        if (!isset($paramsArr['gen'])) {
            $gen = $this->common_model->getGenderByRequest();
            $gen > 0 && $paramsArr['gen'] = $gen;
        }
        // 若参数中没有行业条件则额外判断cookie中是否有
        if (!isset($paramsArr['ind'])) {
            $ind = $this->common_model->getIndustryByRequest();
            $ind > 0 && $paramsArr['ind'] = $ind;
        }
        $paramsArr = $this->common_model->parseParams($params, 1);
        if ($page == 0) {
            $page = $this->common_model->getPage($paramsArr); //当前页
        }

        // 获取列表
        $lists = [];
        $totalCount = 0;
        $pageSize = getRequestPageSize(0);
        $offset = ($page - 1) * ($pageSize);// 偏移量
        //判断用户权限
        $powers = $this->common_model->getPowers($columnPid, $params, $columnId);
        switch ($columnPid) {
            case 4:// 款式
                $this->load->model('styles_model');
                $totalCount = $this->styles_model->getStyleLists($params, $columnId, $lists, $offset, $pageSize, $powers);
                break;
            case 7:// 素材
                $this->load->model('references_model');
                $totalCount = $this->references_model->getReferenceLists($params, $columnId, $lists, $offset, $pageSize, $powers);
                break;
            case 9:// 图案
                $this->load->model('Patterns_model');
                $totalCount = $this->Patterns_model->getPatternsLists($params, $columnId, $lists, $offset, $pageSize, $powers);
                break;
            default:
                $this->outputJson(1, '未获取到数据');
                return;
                break;
        }
        $result = [];
        if (!empty($lists)) {
            foreach ($lists as $key => $val) {
                $result[] = [
                    'col' => isset($val['columnId']) ? $val['columnId'] : $val['col'],
                    'tb' => isset($val['tableName']) ? $val['tableName'] : $val['table'],
                    'id' => intval($val['id']),
                    'cover' => $val['cover'],
                ];
            }
            $this->outputJson(0, 'ok', $result, ['page' => $page, 'pageSize' => $pageSize, 'total' => $totalCount]);
        } else {
            $this->outputJson(1, '未获取到数据');
        }
        return;
    }

    /**
     * 我的工作室 获取底部搜索列表数据
     * 需要的参数：wbid - 工作台id
     */
    public function getWorkbenchBottomDataList()
    {
        $this->load->helper('cookie');
        $this->load->model(['Workbench_model' => 'workbench']);
        $this->load->model('Details_model');
        $accountId = getUserId();
        if (empty($accountId)) {
            $this->outputJson(1, '缺少accountID');
            return;
        }
        $workbenchId = intval($this->input->get_post('wbid'));
        $page = (int)$this->input->get_post('page') ?: 1;
        $page = max($page, 1);
        $pageSize = (int)$this->input->get_post('pageSize') ?: 35;
        $totalCount = 0;
        $imgList = $this->workbench->getWorkbenchPic($accountId, $workbenchId, $page, $pageSize, $totalCount);

        $result = [];
        foreach ($imgList as $key => $val) {
            $result[] = [
                'col' => $val['iColumnId'],
                'tb' => getProductTableName($val['sTableName']),
                'id' => $val['iPriId'],
                'cover' => $this->Details_model->getImgfPath($val['sSmallPath']),// 图片域名转换
                'index' => $val['iImgId'],
            ];
        }
        $this->outputJson(0, 'ok', $result, ['isuc' => 1, 'wbid' => $workbenchId, 'total' => $totalCount, 'pageSize' => $pageSize, 'page' => $page]);
        return;
    }

    /**
     * 书籍详情页底部列表
     * @param id [必需] post 书籍id
     * @param t [必需] post  表名
     * @param page [必需] post  页码
     * @param pageSize [可选] post  分页大小
     */
    public function getBookBottomDataList()
    {
        $bookId = intval($this->input->post('id'));
        $tableName = $this->input->post('t');
        $page = intval($this->input->post('page'));
        $page = $page <= 0 ? 1 : $page;
        $totalCount = 0;
        $pageSize = intval($this->input->post('pageSize'));
        $pageSize = $pageSize <= 1 ? 30 : $pageSize; // 书籍默认分页30
        $refresh = $this->input->get('refresh', TRUE);
        $resultList = [];
        $this->load->model('books_model', 'book');
        switch ($tableName) {
            case 'designrefrence': // 趋势手稿&单品合集
                $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $pageSize, $refresh);
                $offset = ($page - 1) * ($pageSize);
                foreach ($result[$tableName] as $key => $val) {
                    $tmpArr = [];
                    $imgPath = getImagePath('product_design_refrence_details', $val);
                    $tmpArr['smallPicPath'] = $imgPath['smallPath'];
                    $tmpArr['id'] = $val['id'];
                    $tmpArr['t'] = 'designrefrencedetails';
                    $tmpArr['index'] = $offset++;
                    $tmpArr['pid'] = $val['pid'];
                    array_push($resultList, $tmpArr);
                }
                break;

            case 'moscon': // m书店
                $bookInfo = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, 1, 10, $refresh);
                $collectName = $bookInfo['collectName'];
                $pic_name = json_decode($bookInfo['pic_name']);
                is_array($pic_name) && asort($pic_name);
                $totalCount = count($pic_name);
                //分页数据
                $start = ($page - 1) * $pageSize;
                $end = 0;
                if ($start > ($totalCount - 1)) {
                    $start = $totalCount - 1;
                } elseif (($start + $pageSize) > $totalCount) {
                    $end = $totalCount;
                } else {
                    $end = $start + $pageSize;
                }
                $offset = ($page - 1) * ($pageSize);
                $imgPath = getImagePath($collectName, $bookInfo);
                $booklist = $imgPath['booklist'];
                for ($i = $start; $i < $end; $i++) {
                    $tmpArr = [];
                    $tmpArr['smallPicPath'] = $booklist[$i]['smallPath'];
                    $tmpArr['id'] = $bookId;
                    $tmpArr['t'] = 'moscon';
                    $tmpArr['index'] = $offset++;
                    array_push($resultList, $tmpArr);
                }
                break;

            case 'brochures': // lookbook
                $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $pageSize, $refresh);
                $offset = ($page - 1) * ($pageSize);
                foreach ($result[$tableName] as $key => $val) {
                    $tmpArr = [];
                    $fms = getImagePath();
                    $tmpArr['smallPicPath'] = $fms['smallPath'] . $val['pic'];
                    $tmpArr['id'] = $val['id'];
                    $tmpArr['t'] = 'brochuresphoto';
                    $tmpArr['index'] = $offset++;
                    array_push($resultList, $tmpArr);
                }
                break;

            case 'refrencegroup': // 矢量书稿
                $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $pageSize, $refresh);
                $offset = ($page - 1) * ($pageSize);
                foreach ($result[$tableName] as $key => $val) {
                    $tmpArr = [];
                    $imgPath = getImagePath('product_vector_refrence_list', $val);
                    $tmpArr['smallPicPath'] = $imgPath['smallPath'];
                    $tmpArr['id'] = $val['id'];
                    $tmpArr['t'] = 'vectorrefrence';
                    $tmpArr['index'] = $offset++;
                    array_push($resultList, $tmpArr);
                }
                break;
        }
        $this->outputJson(0, 'ok', $resultList, ['total' => $totalCount, 'pageSize' => $pageSize]);
        return;
    }

    /**
     * 获取发布会详情页底部列表
     * @param pid [必需] post 发布会id
     * @param ver [必需] post 版本类型
     * @param page [必需] post  页码
     * @param pageSize [可选] post  分页大小
     */
    public function getPressBottomDataList()
    {
        $pid = intval($this->input->post('pid'));
        $version = $this->input->post('ver');
        $page = intval($this->input->post('page'));
        $page = $page <= 0 ? 1 : $page;
        $totalCount = 0;
        $pageSize = intval($this->input->post('pageSize'));
        $pageSize = $pageSize <= 1 ? 35 : $pageSize; // T台默认分页35
        $offset = ($page - 1) * $pageSize;
        $resultList = [];
        $this->load->model('runways_model');
        $lists = $this->runways_model->getRunwaysInsideLists($pid, $version, $totalCount, $offset, $pageSize, TRUE);
        foreach ($lists as $key => $val) {
            $imgPath = getImagePath('product_presscon_details', $val);
            $tmpArr = [
                'id' => $val['id'],
                'smallPicPath' => $imgPath['smallPath'],
                't' => 'presscondetails',
                'ver' => $version,
                'index' => $offset + $key
            ];
            array_push($resultList, $tmpArr);
        }
        $this->outputJson(0, 'ok', $resultList, ['total' => $totalCount, 'pageSize' => $pageSize]);
        return;
    }

    /**
     * 获取杂志详情页底部列表
     * @param id [必需] post 杂志id
     * @param ver [可选] post 版本类型
     * @param t [可选] post table
     */
    public function getMagazineBottomDataList()
    {
        $this->load->model('books_model');
        $magazineID = $this->input->post_get('id');
        $magazineID = str_replace('|', 'I', $magazineID);
        $mArr = explode('I', $magazineID);
        $mArr = array_slice($mArr, 0, 3);
        $magazineID = implode('I', $mArr);
        $version = $this->input->post_get('ver');
        $table = $this->input->post_get('t');
        //浏览模式
        $viewModel = $this->input->post_get('model');
        $rootUrl = '/books/innermaga/';
        $list = $picLists = [];

        $picLists = $this->books_model->getMagazineInfo($magazineID, $list);
        $totalCount = count($picLists);
        $_result = [];
        for ($i = 0; $i < $totalCount; $i++) {
            if ($viewModel == "isSimple") {
                if ($picLists[$i]['is_old']) {
                    $middle_imageurl = IMAGE_MAGAZINE_PATH . '/' . $picLists[$i]['img_url'] . '/simple/thumbnails/' . $picLists[$i]['list_img_name'];
                } else {
                    if ($picLists[$i]['source'] != "") {
                        $web_site = $picLists[$i]['source'];
                    } else {
                        $web_site = $picLists[$i]['site'];
                    }
                    $middle_imageurl = '/' . $web_site . '/' . substr($picLists[$i]['create_date'], 0, 4) . '/' . $picLists[$i]['create_date'] . '/' . $picLists[$i]['serial_number'] . '/detail/small/' . $picLists[$i]['list_img_name'];
                }
            } else {
                if ($picLists[$i]['is_old']) {
                    // 老数据
                    $middle_imageurl = IMAGE_MAGAZINE_PATH . '/' . $picLists[$i]['img_url'] . '/detail/thumbnails/' . $picLists[$i]['list_img_name'];
                } else {
                    if ($picLists[$i]['source'] != "") {
                        $web_site = $picLists[$i]['source'];
                    } else {
                        $web_site = $picLists[$i]['site'];
                    }
                    $middle_imageurl = '/' . $web_site . '/' . substr($picLists[$i]['create_date'], 0, 4) . '/' . $picLists[$i]['create_date'] . '/' . $picLists[$i]['serial_number'] . '/detail/small/' . $picLists[$i]['list_img_name'];
                }
            }
            $magzine_bigview = $magazineID . 'I' . str_replace(array('.', '_'), array('qq', '-'), $picLists[$i]['list_img_name']);
            $_result[] = [
                'id' => $magzine_bigview,
                'smallPicPath' => $middle_imageurl,
                't' => $table,
                'index' => $i,
                'ver' => $version
            ];
        }
        $this->outputJson(0, 'ok', $_result, ['total' => $totalCount]);
        return;
    }


    public function fmFeedback()
    {
        $requestData = $this->input->get_post(null, true);

        $name = mysql_escape_string($requestData['name']);

        // 电话 正则匹配
        $tel = $requestData['tel'];
        $type = intval($requestData['type']);
        $content = mysql_escape_string($requestData['content']);


        if (!$name) {
            $this->outputJson(1, '姓名为空', '');
            return;
        }

        if (!$tel || !preg_match('/^([0-9]|[-])+$/', $tel)) {
            $this->outputJson(2, '电话格式不正确', '');
            return;
        }

        $sAccountId = getUserId();
        $sAccountId = $sAccountId ? $sAccountId : '';

        $data = [];
        $data['sContacterName'] = $name;
        $data['sContacterTel'] = $tel;
        $data['iFeedbackType'] = $type;
        $data['sContent'] = $content;
        $data['sAccountId'] = $sAccountId;
        $data['dCreateTime'] = date("Y-m-d H:i:s");

        $this->load->model('common_model');
        $insertId = $this->common_model->executeSave('fm_feedback', $data);

        if ($insertId) {
            $this->outputJson(0, 'ok', $insertId);
        }
        return;
    }

    /**
     * 获取详情页埋点属性
     * 所需参数：
     * id, table, col
     */
    public function getDetailSensorInfo()
    {
    }

    /**
     * 款式列表页下载单张
     * @ajaxParam $id
     * @ajaxParam $table 假表名
     * @ajaxParam $col
     */
    public function downloadStyleSingle()
    {
        $this->load->library('POP_OutputJson');
        $outputJson = $this->pop_outputjson;
        $outputJson->isExit(false);

        $id = $this->input->post_get('id');
        $table = $this->input->post_get('table');
        $col = $this->input->post_get('col');
        $params = $this->input->post_get('params');
        $columnPid = 4;

        $realTableName = getProductTableName($table);
        $productData = OpPopFashionMerger::getProductData($id, $realTableName);
        if (!$productData || !$productData[$id]) {
            $outputJson->code(1)->msg('获取详情信息失败')->out();
            return;
        }
        $productData = $productData[$id];
        // var_dump($productData);
        $sBigImg = $productData['sBigPath'];
        if (!$sBigImg) {
            $outputJson->code(1)->msg('获取下载链接失败')->out();
            return;
        }

        //性别
        $sGender = '';
        $sIndustry = '';
        $genNames = ['sGender', 'iGender', 'typ', 'type', 'gender'];
        foreach ($genNames as $genName) {
            if (isset($productData[$genName])) {
                $sGender = $productData[$genName];
                break;
            }
        }
        $indNames = ['sIndustry', 'iIndustry'];
        foreach ($indNames as $indName) {
            if (isset($productData[$indName])) {
                $sIndustry = $productData[$indName];
                break;
            }
        }
        $powers = memberPower('detail', ['P_Gender' => $sGender, 'P_Industry' => $sIndustry, 'P_Column' => $col]);
        $userType = $powers['P_UserType'];
        // var_dump($powers);

        if ($powers['P_Visit'] && in_array($userType, [1, 2])) {
            // ---------------------下载重命名 start-----------------
            // 获取详情数据
            $this->load->model('details_model');
            $_temp = [];
            $tableInfo = $this->details_model->getPicInfo($id, $realTableName, $params, $col, 1, $_temp, $columnPid);
            // 下载重命名
            if (!empty($tableInfo['rename_rule'])) {
                $rename = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($tableInfo['rename_rule'])) . '_' . $id . '.' . pathinfo(basename($sBigImg), PATHINFO_EXTENSION);
            } else {
                $rename = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($tableInfo['title'])) . '_' . $id . '.' . pathinfo(basename($sBigImg), PATHINFO_EXTENSION);
            }
            // ---------------------下载重命名 end-----------------

            $downUrl = STATIC_URL1 . $sBigImg . '?rename=' . $rename;
            $outputJson->code(0)->data($downUrl)->msg('ok')->out();
        } else {
            $aPower = [];
            $aGender = GetCategory::getAttrIdByOldName($sGender);
            $sGender = '';
            if (is_array($aGender) && isset($aGender['a'])) {
                foreach ($aGender['a'] as $genId) {
                    $sGender .= trim(GetCategory::getOtherFromIds($genId, ['sName']));
                }
            }
            $aPower[] = $sGender;
            $aPower[] = trim(GetCategory::getOtherFromIds($sIndustry, ['sName']));
            $aPower[] = GetCategory::getOtherFromColId($col, 'sName') . '栏目';
            $aPower = array_filter($aPower);
            $sPower = implode('、', $aPower);
            $outputJson->code(1)->msg('下载该款式需要 ' . $sPower . ' 权限')->data($aPower)->out();
        }
        return;
    }

    /**
     * 获取内容推荐弹框数据
     */
    public function getRecommendFrame()
    {
        $this->load->library('POP_OutputJson');
        $this->load->model(['common_model', 'styles_model', 'patterns_model', 'runways_model', 'member_model']);
        $this->pop_outputjson->isExit(false);

        $data = [];
        $params = '';
        //用户身份
        /**
         * 游客     ==>  ["P_UserType"]=> int(5) ["P_ExpireTime"]=> string(0) "" ["P_VIPType"]=> int(0) ["P_Collect"]=> bool(false)
         * 普通用户  ==> ["P_UserType"]=> int(4) ["P_ExpireTime"]=> NULL ["P_VIPType"]=> int(0) ["P_Collect"]=> bool(false)
         * 试用用户  ==> ["P_UserType"]=> int(3) ["P_ExpireTime"]=> string(19) "2019-11-18 15:02:00" ["P_VIPType"]=> int(4) ["P_Collect"]=> bool(false)
         * vip用户  ==>  ["P_UserType"]=> int(1) ["P_ExpireTime"]=> string(19) "2019-10-25 16:58:00" ["P_VIPType"]=> int(1) ["P_Collect"]=> bool(false)
         */
        $powers = memberPower();

        //把款式，图案，t台，品牌总数加入memcache缓存，有效期1小时
        $memcacheKey = 'POP_FASHION_HOME_RECOMMEND_FRAME_TOTALS';
        $this->load->driver('cache');
        $memcache_data = $this->cache->memcached->get($memcacheKey);
        if (empty($memcache_data)) {
            // 款式栏目 时间近7日 条件
            $conditions = $this->styles_model->getConditions($params, 4, $powers);
            $conditions['dCreateTime'] = $this->common_model->getTimeRange(1);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result);//款式总数
            $style_list = [
                'count' => $totalCount,
                'link' => "/styles/dis_2/#anchor",
            ];

            //图案素材 近7日更新总数
            $conditions = $this->patterns_model->getConditions($params, 82, $powers);
            $conditions['dCreateTime'] = $this->common_model->getTimeRange(1);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result);//款式总数
            $pattern_list = [
                'count' => $totalCount,
                'link' => "/patterns/graphics/",
            ];

            //T台 近7日更新总数
            $conditions = $this->runways_model->getConditions($params, 3, $powers);
            $conditions['dCreateTime'] = $this->common_model->getTimeRange(1);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result);//款式总数
            $runways_list = [
                'count' => $totalCount,
                'link' => "/runways/",
            ];

            //发现品牌 服装站点的品牌总数
            $count = $this->getRecommendBrandsCount();
            $brands = [
                'count' => $count,
                'link' => '/brands/',
            ];

            //款式，图案，T台，品牌
            $memcache_data = [
                'style' => $style_list,
                'pattern' => $pattern_list,
                'runways' => $runways_list,
                'brands' => $brands,
            ];

            $this->cache->memcached->save($memcacheKey, $memcache_data, 3600);
        }

        //推荐弹框广告(刷新即随机取3条)
        $recommend = $this->common_model->getAds(0, 19, '');
        if (is_array($recommend) && count($recommend) > 3) {
            $rand_key = array_rand($recommend, 3);
            foreach ($rand_key as $val) {
                $recommend_ads[] = $recommend[$val];
            }
        } else {
            $recommend_ads = $recommend;
        }
        //推荐弹框-引导条
        $guide_bar = $this->common_model->getAds(0, 20, 1);

        //报告列表（趋势分析共六条，不区分每个栏目显示几条）
        $report_list = $this->common_model->getRecommendFrame();

        $free_report = array();
        if (in_array($powers['P_UserType'], array(4, 5))) {
            // 报告列表--游客与普通用户取报告的两条(vip免费)数据
            $report_list = array_slice($report_list, 0, 2);
            // 1份最新更新免费试读报告
            $params = '';
            $gender = $this->common_model->getGenderByRequest($params);
            $industry = $this->common_model->getIndustryByRequest($params);
            $gender && $condition['other'][] = "aLabelIds:{$gender}";
            $industry && $condition['other'][] = "aLabelIds:{$industry}";
            $condition['iColumnId'] = [1, 2];
            $condition['iHot'] = 1;
            $condition["other"][] = 'dCreateTime:[' . date('Y-m-d\TH:i:s\Z', strtotime('-19 month')) . ' TO ' .
                date('Y-m-d\TH:i:s\Z', strtotime('-18 month')) . '}';
            $this->member_model->getRegisterFreeReport($params, $free_report, 0, 1, [], $condition);
        }
        $data = [
            'user_type' => $powers['P_UserType'],
            'report_list' => $report_list,// 报告栏目
            'free_report' => $free_report,// 1份最新更新免费试读报告，游客与普通用户专属
            'recommend_ads' => $recommend_ads,// 广告推荐
            'guide_bar' => $guide_bar, // 推荐弹框-引导条
            'style' => $memcache_data['style'],// 款式栏目 时间近7日
            'pattern' => $memcache_data['pattern'],// 图案素材 近7日更新总数
            'runways' => $memcache_data['runways'],// T台 近7日更新总数
            'brands' => $memcache_data['brands'], //服装站点的品牌总数
        ];

        $this->pop_outputjson->code(0)->msg('ok')->data($data)->out();
        return;
    }

    //首页内容推荐弹框 取服装站点的品牌总数
    private function getRecommendBrandsCount()
    {
        $params = [];
        $params['group'] = 'true';
        $params['group.limit'] = 0;
        $params['group.ngroups'] = 'true';
        $params['group.field'] = 'sBrand';
        $conditions = [];
        POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, 1, array('dCreateTime' => 'desc'), $params);
        return $result['sBrand']['ngroups'];
    }


    /**
     * 获取反馈数据
     * @param type 1未默认数据 0则未添加的数据
     * @param url 查询的url
     */
    public function getFeedbackQuestion()
    {
        $type = $this->input->get_post('type');
        $source = $this->input->get_post('url');
        $this->load->model('Activity_model');
        if (isset($type) && !empty($type)) {
            $res = $this->Activity_model->getFeedBackQuestion(1);
            $this->outputJson(1, '获取成功', $res);
        } else {
            $res = $this->Activity_model->getFeedBackQuestion(0);
            if (empty($res)) {
                $this->outputJson(0, '未查询到数据', []);
            } else {
                $question = '';
                foreach ($res as $v) {
                    foreach (json_decode($v['url'], true) as $url) {
                        if (strpos($url, '*') !== false) {
                            $path = substr($url, 0, strpos($url, '*') - 1);
                            if (strpos($source, $path) !== false) {
                                $question = $v;
                                break;
                            }
                        } else {
                            $new_source = preg_replace('/^(http)s?:\/\//', '', $source);
                            //if(strpos($source, 'http') !== false) {
                            //    $new_source = substr($source, 7);
                            //}elseif(strpos($source, 'https') !== false){
                            //    $new_source = substr($source, 8);
                            //}else{
                            //    $new_source = $source;
                            //}
                            if ($new_source == $url) {
                                $question = $v;
                                break;
                            }
                        }
                    }

                }
                $this->outputJson(1, '获取成功', $question);
            }
        }
    }


    /**
     * 保存反馈信息
     */
    public function setFeedback()
    {
        $id = $this->input->get_post('id');
        $answer = json_decode($this->input->get_post('answer'), true);
        $username = $this->input->get_post('username');
        $user_type = $this->input->get_post('user_type');
        if (!$id || !$answer || !$user_type) {
            $this->outputJson(0, '缺少参数', '');
        }
        $this->load->model('Activity_model');
        if ($save = $this->Activity_model->saveAnswer($id, $answer, $username, $user_type)) {
            $this->outputJson(1, '保存成功', $save);
        } else {
            $this->outputJson(0, '保存失败', $save);
        }
    }

    // 疫情专题接口,2020-02-14
    public function getEpidemicData()
    {
        $_columnId = $this->input->post_get('col');
        $columnId = (empty($_columnId) || $_columnId == 'all') ? 'all' : $_columnId;
        $this->load->model('Trends_model');
        $data = array();
        $total = $this->Trends_model->getEpidemicTopicData($columnId, $data);
        if (!empty($data)) {
            $this->outputJson(0, 'ok', array('list' => $data), array('total' => intval($total)));
        } else {
            $this->outputJson(10001, '无数据');
        }
    }

    // 疫情专题接口,获取有数据的栏目，2020-02-14
    public function getEpidemicColumn()
    {
        $this->load->model('Trends_model');
        $data = $this->Trends_model->getEpidemicTopicColumns();
        if (!empty($data)) {
            $this->outputJson(0, 'ok', array('list' => $data));
        } else {
            $this->outputJson(10002, '无栏目数据');
        }
    }

    // 港澳台注册用户，留咨
    public function getHMTUserData()
    {
        $table = 'pop136.t_leave_message';
        $data = array();
        $data['real_name'] = $real_name = $this->input->get_post('name');
        $data['area'] = $area = (int)$this->input->get_post('area');
        $data['cellphone'] = $cellphone = $this->input->get_post('tel');
        $data['email'] = $email = $this->input->get_post('email');
        if (empty($real_name)) {
            $this->outputJson(1, '请输入姓名');
        }
        if (empty($area)) {
            $this->outputJson(1, '请选择地区');
        }
        if (empty($cellphone)) {
            $this->outputJson(1, '请输入联系方式');
        }
        if (empty($email)) {
            $this->outputJson(1, '请输入邮箱地址');
        }
        $data['channel_url'] = 'https://www.pop-fashion.com/member/register/';
        $data['from'] = 'PC';
        $data['pid'] = trim($this->input->cookie('pidtt'));
        $data['web_code'] = 1; // 1 => '服装'

        $insertId = $this->common_model->executeSave($table, $data);
        if (!empty($insertId)) {
            $this->outputJson(0, 'ok');
        } else {
            $this->outputJson(1, '留咨失败，请重试');
        }
        return;
    }

}
