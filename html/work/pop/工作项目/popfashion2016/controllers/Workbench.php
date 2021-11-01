<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 我的工作台
 */
class Workbench extends POP_Controller
{
    CONST MEMC = true;
    public $sAccountId = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Workbench_model', 'workbench');
        $this->load->model('Member_model', 'user');
        $this->load->model('common_model');
        $this->load->helper('cookie');
        $this->load->helper('common');

        //通过cookie获取存储的用户信息，

        $this->arrCookie = get_cookie_value();
        if (empty($this->arrCookie)) {
            $this->load->helper('url');
            redirect('/home/');
        }
        $this->iAccountType = EncryptionDeciphering(get_cookie('_ACCOUNT_TYPE'), false);
        $this->sAccountId = getUserId();
        if ($this->iAccountType == 1) {
            //主账号
            // die('该功能只对子账号开放！');
            $id = $this->arrCookie['id'];
            $this->mainAccountInfo = $this->user->getUserById($id);

            //个人中心头部
            $this->_fecthHeader($this->mainAccountInfo, $personalHeader);
            $this->assign('personalHeader', $personalHeader);
        } else {
            //子账号
            $condition = array();
            $condition['sChildID'] = getUserId();
            $this->childAccountInfo = $this->user->getChildInfo($condition, 'base');
            $childInfo = $this->user->getChildInfo($condition);

            $Pid = $this->arrCookie['id'];
            $this->mainAccountInfo = $this->user->getUserById($Pid);

            $this->childAccountInfo['account'] = $childInfo['sChildAccount'];
            $this->childAccountInfo['create_time'] = $childInfo['dCreateTime'];
            $this->childAccountInfo['sChildID'] = $childInfo['sChildID'];
            $this->childAccountInfo['flag'] = $this->mainAccountInfo['flag'];
            $this->childAccountInfo['vip_type'] = $this->mainAccountInfo['vip_type'];
            $this->childAccountInfo['bao_date_end'] = $this->mainAccountInfo['bao_date_end'];

            //个人中心头部
            $this->_fecthHeader($this->childAccountInfo, $personalHeader);
            $this->assign('personalHeader', $personalHeader);
        }
        $this->assign('columnPid', 0);
    }

    //我的工作台
    public function index()
    {
        if ($this->iAccountType == 1) {
            //TDK
            $this->assign('title', '工作台-POP服装趋势网');
            $this->assign('description', '我的工作台');
            $this->assign('keywords', '工作台');
            $this->display('personalCenter/work_bench_nopower.html');
        } else {
            //获取账号下所有工作台
            $WorkList = $this->workbench->getWorkBenchByAccountId($this->sAccountId);
            $workCount = count($WorkList);
            foreach ($WorkList as $key => $val) {
                $val['sWbenchName'] = htmlspecialchars($val['sWbenchName']);
                $val['sWbenchDesc'] = htmlspecialchars($val['sWbenchDesc']);
                $WorkBenchPicList = array();
                //获取工作台下图片列表，显示4张
                $WorkList[$key]['imgList'] = $this->workbench->getWorkBenchPicList($this->sAccountId, $val['iWorkbenchId'], 4);
                $totalCount = count($WorkList[$key]['imgList']);
                $WorkList[$key]['totalCount'] = $totalCount;
                if ($WorkList[$key]['imgList']) {
                    foreach ($WorkList[$key]['imgList'] as $k => $v) {
                        //获取每张图片详细信息
                        // $WorkList[ $key ][ 'imgList' ][ $k ] = $this->workbench->getPic( $v['sDatabase'] , $v['sTableName'] , $v['iPriId'] );
                        $pic = OpPopFashionMerger::getProductData($v['iPriId'], $v['sTableName'], self::MEMC);
                        $WorkList[$key]['imgList'][$k] = $pic[$v['iPriId']];
                        $path = getImagePath($v['sTableName'], $WorkList[$key]['imgList'][$k]);
                        $WorkList[$key]['imgList'][$k]['smallPath'] = $path['smallPath'];
                        $WorkList[$key]['imgList'][$k]['bigPath'] = $path['bigPath'];
                    }
                    //图片少于4个的，用默认图片补上
                    $v = 4 - $totalCount;
                    for ($i = 4 - $v; $i <= 3; $i++) {
                        $WorkList[$key]['imgList'][$i]['smallPath'] = '';
                    }

                } else {
                    //图片少于4个的，用默认图片补上
                    $WorkList[$key]['imgList'][0]['smallPath'] = '';
                    $WorkList[$key]['imgList'][1]['smallPath'] = '';
                    $WorkList[$key]['imgList'][2]['smallPath'] = '';
                    $WorkList[$key]['imgList'][3]['smallPath'] = '';
                }
            }
            //获取账号下所有收藏
            $focus = $this->workbench->getCollectCount($this->sAccountId);
            //获取账号下所有下载包
            $downZip = $this->workbench->getMyDownLoadByAccountId($this->sAccountId);
            $this->assign('focus', $focus);
            $this->assign('downZip', $downZip);
            //TDK
            $this->assign('title', '工作台-POP服装趋势网');
            $this->assign('description', '我的工作台');
            $this->assign('keywords', '工作台');
            $this->assign('workCount', $workCount);
            $this->assign('WorkList', $WorkList);
            $this->display('personalCenter/work_bench.html');
        }

    }

    //创建编辑工作台
    public function modify()
    {
        $post = array_map('trim', $_POST);
        $workBenchId = intval($post['workBenchId']);
        $sName = $post['tableName'];
        $describe = $post['describe'];
        if (empty($sName)) {
            echo '工作台名字不能为空';
            exit;
        }

        $r = $this->workbench->checkWorkBenchExisted($workBenchId, $this->sAccountId, $sName);
        if ($r) {
            echo '该工作台已存在';
            exit;
        }

        if ($workBenchId > 0) {
            /*
             * 修改
             */
            $res = $this->workbench->updateWorkBench($sName, $describe, $workBenchId);
            if ($res) {
                //echo '修改成功';
                exit;
            } else {
                echo '修改失败';
                exit;
            }
        } else {
            /*
             * 新增
             */
            $res = $this->workbench->getWorkBenchByAccountId($this->sAccountId);
            if (count($res) >= 20) {
                echo '最多可添加20个工作台';
                exit;
            } else {
                $data['sWbenchName'] = $sName;
                $data['sWbenchDesc'] = $describe;
                $data['sAccountId'] = $this->sAccountId;
                $data['dCreateTime'] = date('Y-m-d h:i:s');
                $result = $this->workbench->createWorkbench($data);
                if ($result) {
                    echo $result;
                    exit;
                } else {
                    echo '添加失败';
                    exit;
                }
            }
        }
    }

    //删除工作台
    public function delete()
    {
        $DeleteWorkBenchId = intval($_POST['DeleteWorkBenchId']);
        if (!$DeleteWorkBenchId) {
            echo '删除失败';
            exit;
        } else {
            $res = $this->workbench->deleteWorkbench($DeleteWorkBenchId);
            if ($res) {
                //echo '删除成功';
                exit;
            } else {
                echo '删除失败';
                exit;
            }
        }
    }

    //生成下载包
    public function WorkBenchDown()
    {
        $post = array_map('trim', $_POST);
        $iWorkbenchId = intval($post['iWorkbenchId']);
        $sAccountId = trim($this->sAccountId);
        $workBenchPicList = $this->workbench->getWorkBenchPicList($this->sAccountId, $iWorkbenchId);

        $total = count($workBenchPicList);

        $sFilePath = '';
        foreach ($workBenchPicList as $k => $v) {
            $tt = array();
            // $workBenchPicList['imgList'][ $k ] = $this->workbench->getPic( $v[ 'sDatabase' ] , $v[ 'sTableName' ] , $v['iPriId'] );
            $pic = OpPopFashionMerger::getProductData($v['iPriId'], $v['sTableName'], self::MEMC);
            $workBenchPicList['imgList'][$k] = $pic[$v['iPriId']];
            $path = getImagePath($v['sTableName'], $workBenchPicList['imgList'][$k], '/data/htdocs/popfashion_2014');
            $workBenchPicList['imgList'][$k]['smallPath'] = $path['smallPath'];
            $workBenchPicList['imgList'][$k]['bigPath'] = $path['bigPath'];
            $sFilePath .= $path['bigPath'] . ' ';
        }

        $BASE = '/data/htdocs/popfashion_2014';
        $basepath = '/fashion/fashion_2016/zip/' . $sAccountId . '/' . date('Ymd') . '/';
        $filePath = md5(time() . $sAccountId) . '.zip';
        $sZipPath = $basepath . $filePath;
        $dCreateTime = date('Y-m-d H:i:s');

        $row['sAccountId'] = $sAccountId;
        $row['iWorkbenchId'] = $iWorkbenchId;
        $row['sZippath'] = $sZipPath;
        $row['iImgNum'] = $total;
        $row['dCreateTime'] = $dCreateTime;
        $row['sWbenchName'] = $post['sName'];
        $row['sWbenchDesc'] = $post['sDescribe'];

        $iDownloadId = $this->workbench->insertDownBag($row);
        if ($iDownloadId) {
            $params = array(
                'iWorkbenchId' => $iWorkbenchId,
                'sAccountId' => $sAccountId,
                'sZipPath' => $BASE . $sZipPath,
                'sFilePath' => $sFilePath,
                'total' => $total,
                'iDownloadId' => $iDownloadId
            );
            $OpPopAsyncMsg = new OpPopAsyncMsg();
            $res = $OpPopAsyncMsg->pop_async_msg_send('zip', 'workbench', $params, 'workbench');
            if ($res) {
                echo 1;
                exit;
            } else {
                echo -1;
                exit;
            }
        } else {
            echo -1;
            exit;
        }
    }

    //我的工作台统计单张图片下载量
    public function downCount()
    {
        $table = $_GET['table'];
        $id = intval($_GET['id']);
        $columnid = intval($_GET['columnid']);
        //获取真实表名
        $sTableName = getProductTableName($table);
        $this->load->model('Statistics_model');
        $return = $this->Statistics_model->setDownCount($sTableName, $id, $columnid);
        if ($return) {
            echo 1;
        } else {
            echo -1;
        }
    }

    //我的工作台单张图片删除
    public function picdelete()
    {
        $picId = intval($_POST['picId']);
        if (!$picId) {
            echo '删除失败';
            exit;
        } else {
            $res = $this->workbench->deletePic($picId);
            if ($res) {
                echo '删除成功';
                exit;
            } else {
                echo '删除失败';
                exit;
            }
        }
    }

    //我的下载包
    public function mydownload()
    {
        //获取账号下所有工作台
        $WorkList = $this->workbench->getWorkBenchByAccountId($this->sAccountId);
        //获取账号下所有收藏
        $focus = $this->workbench->getCollectCount($this->sAccountId);
        //获取账号下所有下载包
        $downZip = $this->workbench->getMyDownLoadByAccountId($this->sAccountId);
        $this->assign('focus', $focus);
        $this->assign('downZip', $downZip);
        $this->assign('WorkList', $WorkList);
        $this->assign('title', '工作台-POP服装趋势网');
        $this->assign('description', '我的工作台');
        $this->assign('keywords', '工作台');
        $this->display('personalCenter/my_down.html');
    }

    //我的收藏
    public function myfocus()
    {
        //获取账号下所有工作台
        $WorkList = $this->workbench->getWorkBenchByAccountId($this->sAccountId);
        //获取账号下所有下载包
        $downZip = $this->workbench->getMyDownLoadByAccountId($this->sAccountId);
        //获取账号下所有收藏
        $focusCount = $this->workbench->getCollectCount($this->sAccountId);
        $focusCount = $focusCount['total'];

        $count1 = $this->workbench->getCollectCount($this->sAccountId, 11);
        $count['trend'] = $count1['total'];
        $count2 = $this->workbench->getCollectCount($this->sAccountId, 12);
        $count['analysis'] = $count2['total'];
        $count3 = $this->workbench->getCollectCount($this->sAccountId, 1);
        $count['prescon'] = $count3['total'];
        $count4 = $this->workbench->getCollectCount($this->sAccountId, 8);
        $count['lookbook'] = $count4['total'];
        $count5 = $this->workbench->getCollectCount($this->sAccountId, 2);
        $count['brand'] = $count5['total'];
        $count6 = $this->workbench->getCollectCount($this->sAccountId, '3,4,5,6,7');
        $count['material'] = $count6['total'];
        $count7 = $this->workbench->getCollectCount($this->sAccountId, '9,10');
        $count['inspiration'] = $count7['total'];
        // var_dump($count);die;
        $this->assign('focusCount', $focusCount);
        $this->assign('count', $count);
        $this->assign('downZip', $downZip);
        $this->assign('WorkList', $WorkList);
        $this->assign('title', '我的收藏-POP服装趋势网');
        $this->assign('description', '我的收藏');
        $this->assign('keywords', '我的收藏');
        $this->display('personalCenter/my_focus.html');
    }

    //我的收藏详情
    public function focuslist($args = '')
    {
        $this->load->model('common_model', 'common');
        $params = $this->common->parseParams($args, 1, false);
        $column = isset($params['column']) && !empty($params['column']) ? $params['column'] : '';
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;

        // var_dump($column);
        switch ($column) {
            case 'trend':
                //pop关键主题.POP面料主题.POP趋势预测.POP企划.M企划设计.M主题预测
                $pageSize = 9;
                //获取此收藏下列表
                $trendsCount = $this->workbench->getCollectCount($this->sAccountId, 11, $page);
                $total = $trendsCount['total'];
                $trends = $this->workbench->getCollect($this->sAccountId, 11, $page);
                $offset = ($page - 1) * $pageSize;
                $i = 0;
                foreach ($trends as $key => $val) {

                    $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                    $trends[$key]['info'] = $pic[$val['iPriId']];
                    $trends[$key]['info']['index'] = $offset + $i;
                    $i++;
                    $path = getImagePath($val['sTableName'], $trends[$key]['info']);
                    $trends[$key]['info']['cover_pic'] = $path['cover'];
                    if (isset($trends[$key]['info']['title'])) {
                        $trends[$key]['info']['title'] = $this->workbench->cutString($trends[$key]['info']['title'], 20);
                    } else {
                        $trends[$key]['info']['title'] = $this->workbench->cutString($trends[$key]['info']['sTitle'], 20);
                    }
                    if (isset($trends[$key]['info']['updateTime'])) {
                        $trends[$key]['info']['updateTime'] = date('Y-m-d', strtotime($trends[$key]['info']['updateTime']));
                    } else {
                        $trends[$key]['info']['updateTime'] = date('Y-m-d', strtotime($trends[$key]['info']['dUpdateTime']));
                    }

                    //表名简写
                    $trends[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                    //子栏目
                    $columnArr = GetCategory::getColumn(1);
                    foreach ($columnArr as $k => $v) {
                        if ($v['iColumnId'] == $trends[$key]['iColumnId']) {
                            $trends[$key]['info']['columnName'] = $v['sName'] ? $v['sName'] : '';
                        }
                    }
                    $trends[$key]['info']['columnName'] = isset($trends[$key]['info']['columnName']) ? $trends[$key]['info']['columnName'] : '';
                }

                //分页
                $pageHtml = $this->makePageHtml($params, $total, $pageSize, $page, 'Workbench/focuslist/');
                $this->assign('total', $total);
                $this->assign('pageHtml', $pageHtml);
                $this->assign('trends', $trends);
                $this->assign('type', 11);
                $this->assign('col', 'trend');
                $this->assign('title', '趋势收藏-POP服装趋势网');
                $this->assign('description', '趋势收藏');
                $this->assign('keywords', '趋势收藏');
                $this->display('personalCenter/per_trend.html');
                break;
            case 'analysis':
                //POP趋势预测,M企划设计,M市场分析,POP 关键主题,POP灵感来源,POP市场解析
                $pageSize = 9;
                //获取此收藏下列表

                $count = $this->workbench->getCollectCount($this->sAccountId, 12, $page, $pageSize);
                $total = $count['total'];
                $analysis = $this->workbench->getCollect($this->sAccountId, 12, $page, $pageSize);
                $offset = ($page - 1) * $pageSize;
                $i = 0;
                foreach ($analysis as $key => $val) {
                    $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                    $analysis[$key]['info'] = $pic[$val['iPriId']];
                    //索引
                    $analysis[$key]['info']['index'] = $offset + $i;
                    $i++;
                    $path = getImagePath($val['sTableName'], $analysis[$key]['info']);
                    $analysis[$key]['info']['cover_pic'] = $path['cover'];
                    if (isset($analysis[$key]['info']['title'])) {
                        $analysis[$key]['info']['title'] = $this->workbench->cutString($analysis[$key]['info']['title'], 20);
                    } else {
                        $analysis[$key]['info']['title'] = $this->workbench->cutString($analysis[$key]['info']['sTitle'], 20);
                    }
                    if (isset($analysis[$key]['info']['updateTime'])) {
                        $analysis[$key]['info']['updateTime'] = date('Y-m-d', strtotime($analysis[$key]['info']['updateTime']));
                    } else {
                        $analysis[$key]['info']['updateTime'] = date('Y-m-d', strtotime($analysis[$key]['info']['dUpdateTime']));
                    }
                    //表名简写
                    $analysis[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                    $columnArr = GetCategory::getColumn(2);
                    foreach ($columnArr as $k => $v) {
                        if ($v['iColumnId'] == $analysis[$key]['iColumnId']) {
                            $analysis[$key]['info']['columnName'] = $v['sName'] ? $v['sName'] : '';
                        }
                    }
                    $analysis[$key]['info']['columnName'] = isset($analysis[$key]['info']['columnName']) ? $analysis[$key]['info']['columnName'] : '';
                }
                //分页
                $pageHtml = $this->makePageHtml($params, $total, $pageSize, $page, 'Workbench/focuslist/');
                $this->assign('total', $total);
                $this->assign('analysis', $analysis);
                $this->assign('type', 12);
                $this->assign('col', 'analysis');
                $this->assign('pageHtml', $pageHtml);
                $this->assign('title', '分析收藏-POP服装趋势网');
                $this->assign('description', '分析收藏');
                $this->assign('keywords', '分析收藏');
                $this->display('personalCenter/per_analysis.html');
                break;
            case 'presscon':
                $pageSize = 4;
                //获取此收藏下总数
                $pressconCount = $this->workbench->getCollectCount($this->sAccountId, 1, $page);
                $total = $pressconCount['total'];
                //获取此收藏下列表
                $pressconList = $this->workbench->getCollect($this->sAccountId, 1, $page, $pageSize);
                $type = 1;
                foreach ($pressconList as $key => $val) {
                    $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                    $pressconList[$key]['info'] = $pic[$val['iPriId']];
                    $times = array();
                    if (isset($pressconList[$key]['info']['create_time_special']) && $pressconList[$key]['info']['create_time_special']) {
                        array_push($times, strtotime($pressconList[$key]['info']['create_time_special']));
                    }
                    if (isset($pressconList[$key]['info']['create_time_focus']) && $pressconList[$key]['info']['create_time_focus']) {
                        array_push($times, strtotime($pressconList[$key]['info']['create_time_focus']));
                    }
                    if (isset($pressconList[$key]['info']['create_time']) && $pressconList[$key]['info']['create_time']) {
                        array_push($times, strtotime($pressconList[$key]['info']['create_time']));
                    }
                    if (isset($pressconList[$key]['info']['create_time_video']) && $pressconList[$key]['info']['create_time_video']) {
                        array_push($times, strtotime($pressconList[$key]['info']['create_time_video']));
                    }
                    $pressconList[$key]['updateTime'] = $times ? date('Y-m-d', min($times)) : '';
                    $path = getImagePath($val['sTableName'], $pressconList[$key]['info']);


                    $pressconList[$key]['info']['cover_image'] = $path['cover'];
                    // $pressconList[ $key ][ 'info' ][ 'name'] = $this->workbench->cutString( $pressconList[ $key ][ 'info' ][ 'nme' ], 35 );
                    //季节
                    $pressconList[$key]['info']['for_date_new'] = GetCategory::getNewEnName(5, $pressconList [$key]['info']['for_date']);
                    //地区
                    $region_id = getCategory::getRegionIdFromEnName($pressconList[$key]['info']['region']);
                    $pressconList[$key]['info']['region_new'] = getCategory::getFieldFromId($region_id);
                    //设计师
                    $pressconList[$key]['info']['designer'] = GetCategory::getBrandOtherFormId($pressconList [$key]['info']['brand_tid']);

                    //表名简写
                    $pressconList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);

                    $pressconList[$key]['info']['name'] = $pressconList[$key]['info']['for_date_new'] . $pressconList[$key]['info']['region_new'] . '《' . $pressconList[$key]['info']['designer'] . '》';
                }

                //分页
                $pageHtml = $this->makePageHtml($params, $total, $pageSize, $page, 'Workbench/focuslist/');
                $this->assign('pageHtml', $pageHtml);
                $this->assign('pressconList', $pressconList);
                $this->assign('total', $total);
                $this->assign('type', $type);
                $this->assign('title', 'T台发布收藏-POP服装趋势网');
                $this->assign('description', 'T台发布收藏');
                $this->assign('keywords', 'T台发布收藏');
                $this->display('personalCenter/per_presscon.html');
                break;
            case 'book':
                $pageSize = 4;
                //获取此收藏下总数
                $bookCount = $this->workbench->getCollectCount($this->sAccountId, 8);
                $total = $bookCount['total'];
                //获取此收藏下列表
                $picList = $this->workbench->getCollect($this->sAccountId, 8, $page, $pageSize);
                $type = 8;
                foreach ($picList as $key => $val) {
                    if ($val['sTableName'] == 'product_vector_refrence_group') {
                        $val['sTableName'] = 'product_vector_refrence_list';
                        $pic = OpPopFashionMerger::getProductData(array($val['iPriId']), $val['sTableName'], self::MEMC);
                    } else {
                        $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                    }

                    $picList[$key]['info'] = $pic[$val['iPriId']];
                    switch ($val['iColumnId']) {
                        case '71'://lookbook
                            //封面图显示处
                            if ($picList[$key]['info']['fas_image_address'] == '') {
                                $cover = '/fashion/brochures/graphic/' . $picList[$key]['info']['path'] . '/images/1.jpg';
                            } else {
                                $cover = '/fashion/brochures/graphic/' . $picList[$key]['info']['fas_image_address'];
                            }
                            $picList[$key]['info']['cover_image'] = $cover;
                            // 单品
                            $iCategory = GetCategory::getSingle();
                            $picList[$key]['info']['iCategory_text'] = $iCategory[$picList[$key]['info']['iCategory']];
                            //季节
                            $picList[$key]['info']['for_date_new'] = GetCategory::getNewEnName(5, $picList[$key]['info']['for_date']);
                            //性别
                            $picList [$key]['info']['type_new'] = GetCategory::getOtherFromIds($picList [$key]['info']['iGender'], ['sName']);
                            //时间
                            $picList[$key]['info']['updateTime'] = isset($picList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList[$key]['info']['updateTime'])) : '';

                            //表名缩写
                            $picList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                            break;
                        case '73'://矢量书稿
                            //封面图显示处
                            if ($picList [$key]['info']['cover_img'] != '') {
                                $cover_img = $picList [$key]['info']['cover_img'];
                                $cover = '/fashion/vectorrefrence/' . $cover_img;
                            } else {
                                $cover_img = $picList [$key]['info']['imgname'];
                                $cover = '/fashion/vectorrefrence/' . $picList [$key]['info']['dir_name'] . '/' . $picList [$key]['info']['type'] . '/' . $picList [$key]['info']['for_date'] . '/' . $picList [$key]['info']['category'] . '/small/' . $cover_img;
                            }
                            $picList[$key]['info']['cover_image'] = $cover;
                            //单品
                            $iCategory = GetCategory::getSingle();
                            $picList [$key]['info']['iCategory_text'] = $iCategory[$picList [$key]['info']['iCategory']];

                            //季节
                            $picList [$key]['info']['for_date_new'] = GetCategory::getNewEnName(5, $picList [$key]['info']['for_date']);
                            //性别
                            $picList [$key]['info']['type_new'] = GetCategory::getNewEnName(1, $picList [$key]['info']['type']);
                            //页数
                            $picList [$key]['info']['page_num'] = $picList [$key]['info']['total_num'];
                            //时间
                            $picList [$key]['info']['updateTime'] = isset($picList [$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList [$key]['info']['updateTime'])) : '';
                            //title
                            $picList [$key]['info']['name_text'] = $picList [$key]['info']['theme'];
                            //表名缩写
                            $picList [$key]['info']['simpleName'] = getProductTableName('product_vector_refrence_group');
                            break;
                        case '72'://款式合集（单品合集）
                            //封面图显示处
                            $picList[$key]['info']['cover_image'] = '/fashion/designreference/graphic/' . $picList[$key]['info']['dir_name'] . '/' . $picList[$key]['info']['typ'] . '/' . $picList[$key]['info']['region'] . '/' . $picList[$key]['info']['for_date'] . '/' . $picList[$key]['info']['category'] . '/' . $picList[$key]['info']['nme'] . '/images/1.jpg';
                            // 单品
                            $iCategory = GetCategory::getSingle();
                            $picList[$key]['info']['iCategory_text'] = $iCategory[$picList[$key]['info']['iCategory']];

                            //季节
                            $picList[$key]['info']['for_date_new'] = GetCategory::getNewEnName(5, $picList[$key]['info']['for_date']);

                            //性别
                            $picList[$key]['info']['type_new'] = GetCategory::getNewEnName(1, $picList[$key]['info']['typ']);

                            //时间
                            $picList[$key]['info']['updateTime'] = isset($picList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList [$key]['info']['updateTime'])) : '';

                            //表名缩写
                            $picList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                            break;
                        case '70'://书店
                            if ($val['sTableName'] == 'product_design_refrence') {//趋势手稿
                                //封面图显示处
                                $picList[$key]['info']['cover_image'] = '/fashion/designreference/graphic/' . $picList[$key]['info']['dir_name'] . '/' . $picList[$key]['info']['typ'] . '/' . $picList[$key]['info']['region'] . '/' . $picList[$key]['info']['for_date'] . '/' . $picList[$key]['info']['category'] . '/' . $picList[$key]['info']['nme'] . '/images/1.jpg';
                                // 单品
                                $iCategory = GetCategory::getSingle();
                                $picList[$key]['info']['iCategory_text'] = $iCategory[$picList[$key]['info']['iCategory']];

                                //季节
                                $picList[$key]['info']['for_date_new'] = GetCategory::getNewEnName(5, $picList[$key]['info']['for_date']);

                                //性别
                                $picList[$key]['info']['type_new'] = GetCategory::getNewEnName(1, $picList[$key]['info']['typ']);

                                //时间
                                $picList[$key]['info']['updateTime'] = isset($picList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList [$key]['info']['updateTime'])) : '';
                                //表名缩写
                                $picList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                            } else {//M书店
                                $picLis [$key]['info']['name_text'] = $picList [$key]['info']['title'];
                                // 单品
                                $iCategory = GetCategory::getSingle();
                                $picList[$key]['info']['iCategory_text'] = $iCategory[$picList[$key]['info']['iCategory']];
                                //季节
                                $picList[$key]['info']['for_date_new'] = GetCategory::getNewEnName(5, $picList[$key]['info']['for_date_text']);
                                //封面图
                                $picList[$key]['info']['cover_image'] = $picList[$key]['info']['path'] . $picList[$key]['info']['big_thumbnail'];
                                //性别
                                $picList[$key]['info']['type_new'] = $picList [$key]['info']['type_text'];
                                //时间
                                $picList[$key]['info']['updateTime'] = isset($picList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList [$key]['info']['updateTime'])) : '';
                                //页数
                                $picList [$key]['info']['page_num'] = $picList [$key]['info']['count'];
                                //表名缩写
                                $picList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                            }
                            break;
                    }
                    //子栏目
                    $columnArr = GetCategory::getColumn(6);
                    foreach ($columnArr as $k => $v) {
                        if ($v['iColumnId'] == $val['iColumnId']) {
                            $picList[$key]['info']['columnName'] = $v['sName'];
                        }
                    }
                }
                //分页
                $pageHtml = $this->makePageHtml($params, $total, $pageSize, $page, 'Workbench/focuslist/');
                $this->assign('pageHtml', $pageHtml);
                $this->assign('picList', $picList);
                $this->assign('total', $total);
                $this->assign('type', $type);
                $this->assign('title', '图册收藏-POP服装趋势网');
                $this->assign('description', '图册收藏');
                $this->assign('keywords', '图册收藏');
                $this->display('personalCenter/per_book.html');
                break;
            case 'brand':
                $pageSize = 10;
                //获取此收藏下总数
                $brandCount = $this->workbench->getCollectCount($this->sAccountId, 2, $page);
                $total = $brandCount['total'];
                $type = 2;
                //获取此收藏下列表
                $brandList = $this->workbench->getCollect($this->sAccountId, 2, $page);
                $this->load->model('brands_model');
                foreach ($brandList as $key => $val) {
                    $brandList[$key]['info'] = $this->workbench->getPic($val['sDatabase'], $val['sTableName'], $val['iPriId']);
                    // $brand = OpPopFashionMerger::getProductData( $val[ 'iPriId' ] , $val[ 'sTableName' ] , self::MEMC );
                    // $brandList[ $key ][ 'info' ] = $brand[ $val[ 'iPriId' ] ];
                    $brandList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                    //获取品牌描述
                    $brandDescribe = '';
                    $this->brands_model->getBrandName($val['iPriId'], $brandDescribe);
                    $brandList[$key]['info']['describe'] = $brandDescribe;
                    // var_dump($brandList[$key]['info']['simpleName']);

                }
                //分页
                $pageHtml = $this->makePageHtml($params, $total, $pageSize, $page, 'Workbench/focuslist/');
                $this->assign('pageHtml', $pageHtml);
                $this->assign('brandList', $brandList);
                $this->assign('total', $total);
                $this->assign('type', $type);
                $this->assign('title', '品牌收藏-POP服装趋势网');
                $this->assign('description', '品牌收藏');
                $this->assign('keywords', '品牌收藏');
                $this->display('personalCenter/per_brand.html');
                break;
            case 'material':
                //POP设计模板,M素材库-手绘画稿,POP款式细节,POP图案栏目,POP面料栏目,1.POP 帽子 围巾 领带 手套,1.pop 橱窗陈列
                $pageSize = 10;
                $action = isset($params['action']) && !empty($params['action']) ? $params['action'] : '';
                $this->assign('action', $action);
                //获取此收藏下总数
                $count = $this->workbench->getCollectCount($this->sAccountId, '3,4,5,6,7');
                $this->assign('count', $count);
                //获取款式模板下总数
                $count_template = $this->workbench->getCollectCount($this->sAccountId, 3);
                $this->assign('count_template', $count_template);
                //获取款式细节下总数
                $count_detail = $this->workbench->getCollectCount($this->sAccountId, 4);
                $this->assign('count_detail', $count_detail);
                //获取图案素材下总数
                $count_graphic = $this->workbench->getCollectCount($this->sAccountId, 5);
                $this->assign('count_graphic', $count_graphic);
                //获取图案素材下总数
                $count_accessories = $this->workbench->getCollectCount($this->sAccountId, 6);
                $this->assign('count_accessories', $count_accessories);

                //获取店铺陈列下总数
                $count_shopset = $this->workbench->getCollectCount($this->sAccountId, 7);
                $this->assign('count_shopset', $count_shopset);
                switch ($action) {//3=>款式模板 4=>款式细节 5=>图案素材 6=>服饰品 7=>店铺陈列
                    case 'template':
                        //获取此收藏下图片列表
                        $materialList = $this->workbench->getCollect($this->sAccountId, 3, $page, $pageSize);
                        $curtotal = $count_template['total'];
                        $type = 3;
                        $offset = ($page - 1) * $pageSize;
                        $i = 0;
                        foreach ($materialList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                            // var_dump( $pic );die;
                            $materialList[$key]['info'] = $pic[$val['iPriId']];
                            //索引
                            $materialList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            if ($materialList[$key]['info']) {
                                switch ($val['sTableName']) {
                                    case 'product_templateitem':
                                        //性别
                                        $materialList [$key]['info']['iGender_text'] = GetCategory::getNewEnName(1, $materialList[$key]['info']['type_text']);
                                        //单品
                                        $materialList [$key]['info']['iCategory_text'] = GetCategory::getOtherFromIds($materialList [$key]['info']['iCategory'], ['sName']);
                                        //时间
                                        $materialList[$key]['info']['updateTime'] = isset($materialList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($materialList[$key]['info']['updateTime'])) : '';
                                        $path = getImagePath($val['sTableName'], $materialList[$key]['info']);
                                        $materialList[$key]['info']['smallPath'] = $path['smallPath'];
                                        $materialList[$key]['info']['bigPath'] = $path['bigPath'];
                                        break;
                                    case 'picture':
                                        //小图
                                        $path = getImagePath($val['sTableName'], $materialList[$key]['info']);
                                        $materialList[$key]['info']['smallPath'] = $path['smallPath'];
                                        $materialList[$key]['info']['bigPath'] = $path['bigPath'];
                                        //单品
                                        $materialList [$key]['info']['iCategory_text'] = GetCategory::getOtherFromIds($materialList [$key]['info']['iCategory'], ['sName']);
                                        //性别
                                        $materialList [$key]['info']['iGender_text'] = GetCategory::getNewEnName(1, $materialList [$key]['info']['type_text']);
                                        //时间
                                        $materialList[$key]['info']['updateTime'] = isset($materialList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($materialList[$key]['info']['updateTime'])) : '';
                                        break;

                                }
                            }
                            //表名简写
                            $materialList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                            //分页
                            $pageHtml = $this->makePageHtml($params, $count_template['total'], $pageSize, $page, 'Workbench/focuslist/');
                        }
                        break;
                    case 'detail':
                        //获取此收藏下图片列表
                        $materialList = $this->workbench->getCollect($this->sAccountId, 4, $page, $pageSize);
                        $curtotal = $count_detail['total'];
                        $type = 4;
                        $offset = ($page - 1) * $pageSize;
                        $i = 0;
                        foreach ($materialList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                            $materialList[$key]['info'] = $pic[$val['iPriId']];
                            //索引
                            $materialList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            $path = getImagePath($val['sTableName'], $materialList[$key]['info']);
                            //性别
                            $materialList [$key]['info']['iGender_text'] = GetCategory::getNewEnName(1, $materialList [$key]['info']['typ']);
                            //单品
                            $materialList [$key]['info']['iCategory_text'] = GetCategory::getOtherFromIds($materialList [$key]['info']['iCategory'], ['sName']);
                            //时间
                            $materialList[$key]['info']['updateTime'] = isset($materialList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($materialList[$key]['info']['updateTime'])) : '';
                            $materialList[$key]['info']['smallPath'] = $path['smallPath'];
                            $materialList[$key]['info']['bigPath'] = $path['bigPath'];
                            //表名简写
                            $materialList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                        }

                        //分页
                        $pageHtml = $this->makePageHtml($params, $count_detail['total'], $pageSize, $page, 'Workbench/focuslist/');
                        break;
                    case 'graphic':
                        //获取此收藏下图片列表
                        $materialList = $this->workbench->getCollect($this->sAccountId, 5, $page, $pageSize);
                        $curtotal = $count_graphic['total'];
                        $type = 5;
                        $offset = ($page - 1) * $pageSize;
                        $i = 0;
                        foreach ($materialList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                            $materialList[$key]['info'] = $pic[$val['iPriId']];
                            //索引
                            $materialList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            $path = getImagePath($val['sTableName'], $materialList[$key]['info']);
                            //性别
                            $materialList [$key]['info']['iGender_text'] = '';
                            //单品
                            // $materialList [ $key ][ 'info' ]['iCategory_text'] = GetCategory::getOtherFromIds( $materialList [ $key ][ 'info' ]['iCategory'] );
                            $materialList [$key]['info']['iCategory_text'] = GetCategory::getNewEnName(3, $materialList [$key]['info']['category']);
                            //时间
                            $materialList[$key]['info']['updateTime'] = isset($materialList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($materialList[$key]['info']['updateTime'])) : '';
                            $materialList[$key]['info']['smallPath'] = $path['smallPath'];
                            $materialList[$key]['info']['bigPath'] = $path['bigPath'];
                            //表名简写
                            $materialList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                        }

                        //分页
                        $pageHtml = $this->makePageHtml($params, $count_graphic['total'], $pageSize, $page, 'Workbench/focuslist/');
                        break;
                    case 'accessories'://服饰品（POP 帽子 围巾 领带 手套）
                        //获取此收藏下图片列表
                        $materialList = $this->workbench->getCollect($this->sAccountId, 6, $page, $pageSize);
                        $curtotal = $count_accessories['total'];
                        $type = 6;
                        $offset = ($page - 1) * $pageSize;
                        $i = 0;
                        foreach ($materialList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                            $materialList[$key]['info'] = $pic[$val['iPriId']];
                            //索引
                            $materialList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            //性别
                            $materialList [$key]['info']['iGender_text'] = GetCategory::getNewEnName(1, $materialList [$key]['info']['type']);
                            //单品
                            $materialList [$key]['info']['iCategory_text'] = GetCategory::getOtherFromIds($materialList [$key]['info']['iCategory'], ['sName']);
                            //时间
                            $materialList[$key]['info']['updateTime'] = isset($materialList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($materialList[$key]['info']['updateTime'])) : '';
                            $path = getImagePath($val['sTableName'], $materialList[$key]['info']);
                            $materialList[$key]['info']['smallPath'] = $path['smallPath'];
                            $materialList[$key]['info']['bigPath'] = $path['bigPath'];
                            //表名简写
                            $materialList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                        }

                        //分页
                        $pageHtml = $this->makePageHtml($params, $count_accessories['total'], $pageSize, $page, 'Workbench/focuslist/');
                        break;
                    case 'shopset':
                        //获取此收藏下图片列表
                        $materialList = $this->workbench->getCollect($this->sAccountId, 7, $page, $pageSize);
                        $curtotal = $count_shopset['total'];
                        $type = 7;
                        $offset = ($page - 1) * $pageSize;
                        $i = 0;
                        foreach ($materialList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                            $materialList[$key]['info'] = $pic[$val['iPriId']];
                            //索引
                            $materialList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            $path = getImagePath($val['sTableName'], $materialList[$key]['info']);
                            //品牌
                            $materialList [$key]['info']['iGender_text'] = GetCategory::getBrandOtherFormId($materialList [$key]['info']['brand_tid']);
                            //地区
                            $materialList [$key]['info']['iCategory_text'] = GetCategory::getFieldFromId($materialList [$key]['info']['iRegion']);
                            //时间
                            $materialList[$key]['info']['updateTime'] = isset($materialList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($materialList[$key]['info']['updateTime'])) : '';
                            $materialList[$key]['info']['smallPath'] = $path['smallPath'];
                            $materialList[$key]['info']['bigPath'] = $path['bigPath'];
                            //表名简写
                            $materialList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                        }
                        //分页
                        $pageHtml = $this->makePageHtml($params, $count_shopset['total'], $pageSize, $page, 'Workbench/focuslist/');
                        break;
                }
                $this->assign('pageHtml', $pageHtml);
                $this->assign('curtotal', $curtotal);
                $this->assign('type', $type);
                $this->assign('materialList', $materialList);
                $this->assign('title', '素材收藏-POP服装趋势网');
                $this->assign('description', '素材收藏');
                $this->assign('keywords', '素材收藏');
                $this->display('personalCenter/per_material.html');
                break;
            case 'inspiration':
                $action = isset($params['action']) && !empty($params['action']) ? $params['action'] : '';
                $pageSize = 12;
                //获取此收藏下总数
                $count = $this->workbench->getCollectCount($this->sAccountId, '9,10');
                //获取灵感报告下总数
                $count_bg = $this->workbench->getCollectCount($this->sAccountId, 9);
                //获取灵感图库下总数
                $count_tk = $this->workbench->getCollectCount($this->sAccountId, 10);
                $this->assign('count', $count);
                $this->assign('count_bg', $count_bg);
                $this->assign('count_tk', $count_tk);
                if ($action == 'pic') {
                    //获取此收藏下列表
                    $picList = $this->workbench->getCollect($this->sAccountId, 10, $page);
                    $type = 10;
                    $offset = ($page - 1) * $pageSize;
                    $i = 0;
                    if (is_array($picList)) {
                        foreach ($picList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);
                            $picList[$key]['info'] = $pic[$val['iPriId']];
                            //索引
                            $picList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            $picList[$key]['info']['updateTime'] = isset($picList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList[$key]['info']['updateTime'])) : '';
                            $picList[$key]['info']['iInspirationType_text'] = GetCategory::getOtherFromIds($picList[$key]['info']['iInspirationType'], ['sName']);
                            //表名简写
                            $picList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                        }
                    }


                    //分页
                    $pageHtml = $this->makePageHtml($params, $count_tk['total'], $pageSize, $page, 'Workbench/focuslist/');
                    $this->assign('pageHtml', $pageHtml);
                    $this->assign('picList', $picList);
                    $this->assign('type', $type);
                    $this->assign('action', $action);
                    $this->assign('title', '灵感收藏-POP服装趋势网');
                    $this->assign('description', '灵感收藏');
                    $this->assign('keywords', '灵感收藏');
                    $this->display('personalCenter/per_inspiration_image.html');
                } else {
                    //获取此收藏下列表
                    $picList = $this->workbench->getCollect($this->sAccountId, 9, $page);
                    $type = 9;
                    $offset = ($page - 1) * $pageSize;
                    $i = 0;
                    if (is_array($picList)) {
                        foreach ($picList as $key => $val) {
                            $pic = OpPopFashionMerger::getProductData($val['iPriId'], $val['sTableName'], self::MEMC);

                            $picList[$key]['info'] = $pic[$val['iPriId']];
                            //图片索引
                            $picList[$key]['info']['index'] = $offset + $i;
                            $i++;
                            //图片路径
                            $path = getImagePath($val['sTableName'], $picList[$key]['info']);
                            $picList[$key]['info']['cover_pic'] = $path['cover'];
                            $picList[$key]['info']['title'] = $this->workbench->cutString($picList[$key]['info']['title'], 45);
                            $picList[$key]['info']['abstract'] = $this->workbench->cutString($picList[$key]['info']['abstract'], 43);
                            $picList[$key]['info']['updateTime'] = isset($picList[$key]['info']['updateTime']) ? date('Y-m-d', strtotime($picList[$key]['info']['updateTime'])) : '';
                            $picList[$key]['info']['iInspirationType_text'] = GetCategory::getOtherFromIds($picList[$key]['info']['iInspirationType'], ['sName']);
                            //表名简写
                            $picList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);
                        }
                    }
                    // var_dump($picList);die;
                    //分页
                    $pageHtml = $this->makePageHtml($params, $count_bg['total'], $pageSize, $page, 'Workbench/focuslist/');
                    $this->assign('pageHtml', $pageHtml);
                    $this->assign('picList', $picList);
                    $this->assign('type', $type);
                    $this->assign('col', 'inspiration');
                    $this->assign('action', $action);
                    $this->assign('title', '灵感收藏-POP服装趋势网');
                    $this->assign('description', '灵感收藏');
                    $this->assign('keywords', '灵感收藏');
                    $this->display('personalCenter/per_inspiration_report.html');
                }
                break;
        }
    }

    //个人中心头部
    private function _fecthHeader($accountInfo, &$header)
    {
        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');
        //用户类型
        $this->workbench->_getAccountType($this->iAccountType);
        //会员类型
        $this->workbench->_getVipType($vipTypeArr);
        //会员权限
        $this->workbench->_getVipPrivilege($vipTypeArr);

        $flag = $this->uri->segment(2);

        $website = 'fashion';
        $sIdPopAccount = $this->user->verifyUserWhetherVip($this->arrCookie['id'], $website);

        $avatarUri = $this->user->getAvatar(getUserId());

        $this->assign('accountType', $this->iAccountType);
        $this->assign('avatarUri', $avatarUri['sAvatar']);

        $this->assign('user', $accountInfo);
        $header = $this->fetch('personalCenter/per_Header.html');
    }


}