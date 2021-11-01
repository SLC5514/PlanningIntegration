<?php
defined('BASEPATH') OR exit('No	direct script access allowed');

class Details extends POP_Controller
{
    private $isAjax;
    // 读物、T台详情页右侧更多推荐
    const FM_TEM_BOOK_MORE_REC_MEMCACHE_KEY = 'FM_TEM_BOOK_MORE_REC_2017102501_';

    /**
     *
     * Class constructor
     * @return    void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'details_model']);
    }

    public function reportbook($book_id = '')
    {
        $this->load->model('Report_model');
        $reportBookCatalog = $this->Report_model->getReportBookCatalog($book_id);
        $this->assign('current_report_id', (int)$this->input->get_post('report_id'));
        $reportBookCatalog['subtitle'] = [
            GetCategory::getAttrNameById($reportBookCatalog['iSeason']),
            GetCategory::getAttrNameById($reportBookCatalog['iIndustry']),
            GetCategory::getAttrNameById($reportBookCatalog['iGender']),
        ];
        $this->assign('title', $reportBookCatalog['sTitle'] . '-POP服装趋势网');
        $this->assign('keywords', $reportBookCatalog['sTitle']);
        // 图案工艺详情F5页面描述特殊处理
        $this->assign('description', $reportBookCatalog['sMemo']);
        $this->assign('reportBookCatalog', $reportBookCatalog);
        $this->display('reportBookList.html');
    }

    //请求参数解密 RSA-私钥解密
    public function decrypt_param($params = '')
    {
        if (!$params) return [];
        $this->load->model('Secret_model');
        $json = $this->Secret_model->rsa_decrypt($params);
        $data = json_decode($json, true);
        return $data ? $data : [];
    }

    //ajax返回值 AES加密
    public function encrypt_data($string = '', $key)
    {
        if (!$string) return '';
        $this->load->model('Secret_model');
        //加密数据
        $json = $this->Secret_model->aes_encrypt($string, $key);
        return $json;

    }

    //请求接口验证失败的用户信息处理 (rpc远程调用加队列)
    private function push_to_mq($parent_id = '', $child_id = '', $time = '', $status = 0)
    {
        $ip = $this->input->ip_address();
        $client = new Yar_Client("http://mq.pop136.com/api_decrypt.php");
        $client->SetOpt(YAR_OPT_CONNECT_TIMEOUT, 3000);//链接超时时间3s
        $client->pushUserData($parent_id, $child_id, $ip, $time, $status);

    }

    //调用详情接口时加解密参数
    private function secret_params()
    {
        //AES_KEY
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();

        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

    }

    //记录vip浏览历史
    private function _setFashionHistoryRedis($col_pid, $pop_id, $col)
    {
        $uid = getUserId();
        $d = date('Ymd');
        // $d = "20200417";
        //设置有效时间30天
        $time_out = strtotime(date('Y-m-d 00:00:00', strtotime('+30 day')));
        $pop_key_time_out = strtotime(date('Y-m-d 00:00:00', strtotime('+1 day')));

        $redisConn = getRedisConnection();
        $pop_ids_key = "fashion_history:{$uid}_{$col_pid}_{$d}";//存放当天浏览的所有pop_id
        $pop_key = "fashion_history_pop_ids:{$uid}_{$col_pid}_{$d}_{$pop_id}";//存放当前pop_id的list键 用于判断pop_id是否已存在

        $pop_id .= '-' . $col;
        if (!$redisConn->exists($pop_ids_key)) {
            $redisConn->lPush($pop_ids_key, $pop_id);
            $redisConn->expireAt($pop_ids_key, $time_out);//30天
            $redisConn->set($pop_key, $pop_ids_key);
            $redisConn->expireAt($pop_key, $pop_key_time_out);//1天 当天浏览过就不再记录
        } else {
            if (!$redisConn->exists($pop_key)) {
                $redisConn->lPush($pop_ids_key, $pop_id);
                $redisConn->set($pop_key, $pop_ids_key);
                $redisConn->expireAt($pop_key, $pop_key_time_out);//1天 当天浏览过就不再记录
            }
        }

    }


    // 款式详情页
    public function style($params = '')
    {
        $this->isAjax = $this->input->is_ajax_request();
        $this->benchmark->mark('action');
        $s_p = $this->input->get_post('s_p');

        if ($s_p) {//加密后的请求参数
            $paramsArr = $this->decrypt_param($s_p);
            $params = $this->common_model->parseParams($paramsArr, 2);
        } else {
            // 参数需全部编码, $params示例：t_perform-id_66666-col_52
            $params = $this->common_model->restoreParams($params);
            $paramsArr = $this->common_model->parseParams($params);
        }

        $id = (int)$paramsArr['id'];
        $table = $paramsArr['t'];
        $column = (int)$paramsArr['col']; // 栏目id
        if (!$id || !$table || !$column) {
            $this->isAjax ? exit(json_encode(['success' => 0, 'code' => 1, 'msg' => '参数错误'])) : show_404();
        }

        if ($column != 121) {
            // 121 数码云打印 - 游客可以访问
            checkDetailVisitor();
        }
        $columnPid = GetCategory::getOtherFromColId($column);
        $columnPid = empty($columnPid) ? $column : $columnPid;
        $this->assign('columnPid', $columnPid);
        $paramsPost = $this->input->post('p', true); // 参数
        $paramsPost = isset($paramsPost) && !empty($paramsPost) ? $paramsPost : '';

        // 将table转为真实表名
        $realTable = getProductTableName($table);
        $detailResult = OpPopFashionMerger::getProductData($id, $realTable);

        // 图案是家纺与服装共用solr，现在只取服装的. 收藏查看详情，是家纺的返回404
        if (in_array($realTable, array('product_graphicitem'))) {
            $allow_site = 'fashion';
            $channel_site = isset($detailResult[$id]['channel']) ? $detailResult[$id]['channel'] : '';// jiafang,fashion
            if ($detailResult && $channel_site) {
                if (!in_array($allow_site, explode(',', $channel_site))) {
                    $detailResult = array();
                }
            }
        }

        $productData = $detailResult[$id];
        // 数据不存在
        if (!$realTable || !$id || !$column || empty($detailResult)) {
            $this->isAjax ? exit(json_encode(['success' => 0, 'code' => 1, 'msg' => 'xx'])) : show_404();
        }
        $index = intval($paramsArr['index']);
        // 获取详情数据
        $tableInfo = $this->details_model->getPicInfo($id, $realTable, $paramsPost, $column, $index, $detailResult, $columnPid);

        if (is_array($tableInfo['detail_img'])) {
            $_detailList = [];
            foreach ($tableInfo['detail_img'] as $key => $val) {
                $_temp = $val;

                if ($column == 121) {
                    // 数码云打印没有mbig，仍然取small
                    $mbigPath = $val['smallPath'];
                } else {
                    $mbigPath = str_replace('/big/', '/mbig/', $val['bigPath']);
                }

                $_temp['mbigPath'] = $mbigPath;
                // 重命名
                $_temp['rename'] = $this->details_model->changeRenameRule($column, $tableInfo, $val);
                $_temp['urlencodePath'] = urlencode(parse_url($val['bigPath'], PHP_URL_PATH));
                $_temp['smallUrlencodePath'] = urlencode(parse_url($val['smallPath'], PHP_URL_PATH));
                $_temp['mBigUrlencodePath'] = urlencode(parse_url($mbigPath, PHP_URL_PATH));
                $_temp['staticURL'] = STATIC_URL1;
                $_temp['sign'] = substr(md5('popfashion_fitting' . $id . $mbigPath), 0, 5);

                $_detailList[] = $_temp;
            }
            $tableInfo['detail_img'] = $_detailList;
            unset($_temp, $_detailList);
        }

        // 获取权限
        $res = [];
        $res['id'] = $id;
        $res['t'] = $table;
        $res['col'] = $column;
        if (!empty($productData["sColorDetails"]) && $column == 50) {
            $sColorDetails = json_decode($productData["sColorDetails"], true);
            foreach ($sColorDetails as $val) {
                $pieData = [
                    'id' => $val["FirstLevel"],
                    'name' => $val["pantonColorNumber"],
                    'sColorNumber' => $val["Color"],
                    'value' => $val["Percent"],
                ];
                $pieDatas[] = $pieData;
            }
            $res['colorPieData'] = $pieDatas;
        }

        // 所属品牌
        $__brandId = 0;
        if (isset($productData['brand_tid'])) {
            $__brandId = $productData['brand_tid'];
        } elseif (isset($productData['iBrand'])) {
            $__brandId = $productData['iBrand'];
        } elseif (isset($productData['iBrandId'])) {
            $__brandId = $productData['iBrandId'];
        }

        if ($__brandId) {
            $res['brandInfo'] = ['id' => $__brandId, 'link' => "/brands/detail/id_{$__brandId}/", 'name' => GetCategory::getBrandOtherFormId($__brandId, 'en_cn_brand')];
        }
        //性别
        $sGender = '';
        $sIndustry = '';
        $genNames = ['sGender', 'iGender', 'typ', 'type', 'gender'];
        foreach ($genNames as $genName) {
            if (isset($detailResult[$id][$genName])) {
                $sGender = $detailResult[$id][$genName];
                break;
            }
        }
        $indNames = ['sIndustry', 'iIndustry'];
        foreach ($indNames as $indName) {
            if (isset($detailResult[$id][$indName])) {
                $sIndustry = $detailResult[$id][$indName];
                break;
            }
        }

        // 获取权限
        if ($column == 82 || $column == 120) {
            $powers = memberPower('detail', ['P_Industry' => $sIndustry, 'P_Column' => $column]);
        } elseif (in_array($column, [80, 81, 84, 85, 117])) {
            $powers = memberPower('detail', ['P_Column' => $column]);
        } elseif ($column == 121) {
            $powers = [
                "P_Collect" => false,
                "P_Visit" => true,
                "P_SingleDownLoad" => true,
                "P_PdfDownLoad" => true,
                "P_UserType" => 1,
                "P_AccountFrom" => 1// 账号来源 P_AccountFrom => 1 服装 2 高端
            ];
        } else {
            $powers = memberPower('detail', ['P_Gender' => $sGender, 'P_Industry' => $sIndustry, 'P_Column' => $column]);
        }

        $aLogonMessage = $this->member_model->logonMessage();
        $iAccountType = $aLogonMessage['iAccountType'];//子账号还是主账号
        unset($aLogonMessage);
        $userType = $powers['P_UserType'];
        $realUserPower = memberPower("other");//用户真正属性，不随内容变化
        $realUserType = $realUserPower["P_UserType"];
        //========================↓↓↓ 数码云打印 ↓↓↓==========================
        if ($column == 121) {
            $_downloadType = json_decode($this->details_model->getMaterialDown($tableInfo, $realTable), true);
            $iUlbId = $tableInfo['iUlbId'];
            foreach ($_downloadType as $key => $val) {
                $json['data'][$key] = array(
                    'smallPic' => urldecode(STATIC_URL1 . $detailResult[$id]["sPath"] . 'small/' . $val['sSmallName']),
                    'bigPic' => urldecode(STATIC_URL1 . $val["aDownInfo"]["jpg"]["bp"]),
                    'aiPicName' => basename(urldecode($val['sSmallName']), '.jpg'),
                    'isSelected' => 0,
                );
            }
            $res['popky_json'] = empty($json) ? false : json_encode($json);
            $_BASEENV_ = getenv('BASEENV');
            switch ($_BASEENV_) {
                case '1': //线上
                    $res['popky_action'] = "http://www.uliaobao.com/s/trade/popOrder";  //post的URL路径
                    break;
                case '2'://测试
                    $res['popky_action'] = "http://uat.www.uliaobao.com/s/trade/popOrder";  //post的URL路径
                    break;
                case '3'://线下(开发环境)
                    $res['popky_action'] = "http://dev.www.uliaobao.com/s/trade/popOrder";  //post的URL路径
                    break;
            }
        }
        //========================↑↑↑ 数码云打印 ↑↑↑==========================

        // 是否能下载	P_SingleDownLoad
        $download = $powers['P_SingleDownLoad'];
        $visit = $powers['P_Visit'];
        $collect = $powers['P_Collect'];

        // 是否能访问页面
        $res['visit'] = $visit;
        if ($res['iQuickPrint']) {
            $res['visit'] = $visit = true;
        }
        $res['download'] = $download;
        $res['_general'] = "";
        $res['userType'] = $userType;
        $res["realUserType"] = $realUserType;
        if ($userType == 2 || ($userType == 3 && $iAccountType == 2)) {  // 1 主账号VIP, 2子账号VIP
            $res['_workbench'] = 1;
        } else {
            if ($userType == 4) {  //普通用户
                $res['_general'] = 1;
            }
            $res['_workbench'] = 0;
        }
        // 能访问
        if ($visit) {
            $res['visit_title'] = '';
        } else {
            $pow1 = $pow2 = [];
            // 行业
            $pow1['industryStr'] = $sIndustry ? GetCategory::getOtherFromIds($sIndustry, ['sName']) : '';
            // 性别
            $pow1['sGenderStr'] = $sGender ? GetCategory::getOtherFromIds($sGender, ['sName']) : '';
            // 无权限栏目名称
            $pow2['colPsName'] = GetCategory::getOtherFromColId($columnPid, 'sName');
            $pow2['colsName'] = GetCategory::getOtherFromColId($column, 'sName');

            $res['pow1'] = implode($pow1, '/');
            $res['pow2'] = implode($pow2, '/');
            $res['visit_title'] = '对不起，您购买的套餐权限不可以浏览本内容！需要的权限为：';

            $res['download'] = 0;
        }


        // 数据库名称
        switch ($realTable) {
            case 'picture':
            case 'mostrend_content':
                $sDatabase = OpPopFashionMerger::POP_TREND_DATABASE_NAME;
                break;
            case 'brand_library':
                $sDatabase = OpPopFashionMerger::POP_POP136_DATABASE_NAME;
                break;
            default:
                $sDatabase = OpPopFashionMerger::POP_FASHION_DATABASE_NAME;
                break;
        }

        // 获取款式模板|图案素材的下载格式
        $downloadType = [];
        if (in_array($column, [80, 82, 117, 120])) {
            $downloadType = $this->details_model->getMaterialDown($tableInfo, $realTable);
        } else {
            $details = $tableInfo['detail_img'];
            if (!empty($details)) {
                foreach ($details as $dk => $detail) {
                    $realImgLoc = rtrim(FCPATH, '/') . parse_url($detail['bigPath'], PHP_URL_PATH);
                    if (getenv('BASEENV') == false) {
                        $imgInfo = ['type' => '--', 'size' => '--'];
                    } else {
                        $imgInfo = api_getPicSize($realImgLoc);
                    }
                    $details[$dk]['shape'] = $imgInfo['type'];
                    $details[$dk]['size'] = $imgInfo['size'];
                }
            }
            $tableInfo['detail_img'] = $details;
        }
        $this->load->model('collect_model');
        $iType = 0;
        // 收藏类别处理	素材类[3=>款式模板, 4=>款式细节, 5=>图案素材, 6=>服饰品, 7=>店铺陈列, 13=>款式库]
        switch ($column) {
            case 80:
                $iType = 3;
                break;
            case 81:
                $iType = 4;
                break;
            case 82:
            case 117:
            case 120:
            case 124:
                $iType = 5;
                break;
            case 84:
                $iType = 6;
                break;
            case 85:
                $iType = 7;
                break;
            case 91:
                $iType = 10;
                break;
            case 4:
            case 50:
            case 52:
            case 54:
            case 55:
            case 56:
            case 57:
            case 122:
            case 123:
                $iType = 13;
                break;
        }

        // 素材类收藏
        $res['colpara'] = '';
        $res['collected'] = 0;
        if ($iType) {
            if ($collect) {// 能收藏
                $collectParams = $column . '-' . $table . '-' . $id . '-' . $iType;
                $res['colpara'] = $collectParams;

                $sChildID = getUserId();
                // 是否已经收藏
                $res['collected'] = intval($this->collect_model->existCollectStatus($sChildID, $sDatabase, $realTable, $id));
            } else {// 不能收藏
                $res['collected'] = 2;
            }
        }

        //记录vip浏览历史
        if (in_array($powers['P_UserType'], [1, 2, 3]) && $powers['P_Visit']) {
            //全部款式 图案素材和大牌花型
            if (in_array($columnPid, [4]) || in_array($column, [82, 120])) {
                $pop_id = $realTable . '_' . $id;
                $this->_setFashionHistoryRedis($columnPid, $pop_id, $column);
            }
        }


        // Ajax请求数据处理   图案素材 图案工艺 大牌花型栏目返回数据加密
        if ($this->isAjax) {
            if ($column == 121 && !$s_p && $tableInfo) {
                //数码云打印栏目详情 游客可以访问，不加反爬
                echo $this->styleAjax($params, $res, $rename_pre, $tableInfo, $downloadType);

            } else {
                //验证token和参数
                //加入队列
                $verifyKeys = $this->_verifyParamKeys($s_p);
                if ($verifyKeys) {
                    $timeStamp = $paramsArr['timeStamp'];
                    $AES_KEY = $this->Secret_model->get_symmetric_key($timeStamp);

                    $res = $this->styleAjax($params, $res, $rename_pre, $tableInfo, $downloadType);
                    $data = $this->encrypt_data($res, $AES_KEY);
                    echo json_encode(['code' => 0, 'msg' => 'ok', 'data' => $data]);
                } else {
                    exit(json_encode(['code' => 0, 'msg' => '加密参数验证失败']));
                }
            }

            return;
        }

        // TDK
        $this->assign('title', $tableInfo['title'] . '-POP服装趋势网');
        $this->assign('keywords', $tableInfo['title']);
        // 图案工艺详情F5页面描述特殊处理
        if ($column == 124) {
            $this->assign('description', "您可以对{$tableInfo['title']}进行直接下载、分享或是查找与它相似的图案。");
        } else {
            $this->assign('description', $tableInfo['title']);
        }

        //加密参数
        $this->secret_params();

        $this->assign('isF5', 1);
        $this->assign('t', $table);
        $this->assign('id', $id);
        $this->assign('col', $column);
        $this->assign('userType', $userType);
        $this->assign("realUserType", $realUserType);
        $this->assign('visit', $res["visit"]);
        $this->assign('columnId', $column);
        $this->assign('stitle', $tableInfo['title']);

        $this->benchmark->mark('actionEnd');
        $this->display('style_detail_F5.html');
    }

    /**
     * 图案详情ajax访问
     * @param string $params 请求参数 示例t_perform-id_66666-col_52
     * @param $res
     * @param string $rename_pre 下载重命名
     * @param $tableInfo
     * @param $downloadType
     */
    private function styleAjax($params = '', $res, $rename_pre, $tableInfo, $downloadType)
    {
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params);
        $id = intval($paramsArr['id']);
        $table = $paramsArr['t'];
        $column = intval($paramsArr['col']); // 栏目id
        $columnPid = GetCategory::getOtherFromColId($column);
        $columnPid = empty($columnPid) ? $column : $columnPid;
        $this->assign('columnPid', $columnPid);
        $paramsPost = $this->input->post('p', true); // 参数
        $paramsPost = isset($paramsPost) && !empty($paramsPost) ? $paramsPost : '';
        $paramsPostArr = $this->common_model->parseParams($paramsPost);

        // 是否聚合页
        $isGroup = 0;
        if (isset($paramsPostArr['dis']) && ($paramsPostArr['dis'] == 2 || $paramsPostArr['dis'] == 3)) {
            $isGroup = 1;
        }

        // 款式库(上下一款) T台发布|设计素材|灵感图库(上下一张) 手稿合辑(上下一页)
        $res['word'] = '款';
        if (in_array($column, [3, 7, 80, 81, 82, 84, 85, 91, 117, 120, 121, 124])) {
            $res['word'] = '张';
        } elseif (in_array($column, [6, 70, 71, 72, 73, 113, 114, 115, 131])) {
            $res['word'] = '页';
        }
        //图案素材、大牌花型、图案工艺栏目去掉自有版权
        if (in_array($column, [82, 120, 124])) {
            unset($tableInfo["source"]);
        }
        // 是否个人中心--我的工作室
        $uc = intval($this->input->post('isuc'));
        if ($uc) {
            $wbid = intval($this->input->post('wbid'));
            $index = intval($this->input->post('index'));
            $this->load->model('member_model');
            $sAccountId = getUserId();

            $_res = $this->member_model->getUCWorkbenchList($wbid, $sAccountId, $index);

            // 处理一下, http://img1.fm.pop-fashion.com 改为 https://imgf1.pop-fashion.com
            $this->load->model('Details_model');
            $_res[0]['sSmallPath'] = $this->Details_model->getImgfPath($_res[0]['sSmallPath']);
            $_res[0]['sBigPath'] = $this->Details_model->getImgfPath($_res[0]['sBigPath']);

            $smallPath = (strpos($_res[0]['sSmallPath'], 'https://') == 0) ? $_res[0]['sSmallPath'] : getStaticUrl($_res[0]['sSmallPath']) . $_res[0]['sSmallPath'];
            $bigPath = (strpos($_res[0]['sBigPath'], 'https://') == 0) ? $_res[0]['sBigPath'] : getStaticUrl($_res[0]['sBigPath']) . $_res[0]['sBigPath'];

            $res['stitle'] = $tableInfo['title'];

            $absolutelyPath = rtrim(FCPATH, '/') . parse_url($bigPath, PHP_URL_PATH);
            if (getenv('BASEENV') == false) {
                $imgInfo = ['type' => '--', 'size' => '--'];
            } else {
                $imgInfo = api_getPicSize($absolutelyPath);
            }
            $shape = $imgInfo['type'];
            $size = $imgInfo['size'];

            // 图片下载重命名
            if (!empty($tableInfo['rename_rule'])) {
                $rename_str = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($tableInfo['rename_rule'])) . '_' . $id . '.' . pathinfo(basename($bigPath), PATHINFO_EXTENSION);
            } else {
                $rename_str = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($tableInfo['title'])) . '_' . $id . '.' . pathinfo(basename($bigPath), PATHINFO_EXTENSION);
            }

            $res['detailList'] = [[
                'smallPath' => $smallPath,
                'bigPath' => $bigPath,
                'simpleName' => $table,
                'id' => $id,
                'columnid' => $column,
                'shape' => $shape,
                'size' => $size,
                'rename' => $rename_str
            ]];
            $res['detailCnt'] = 1;
            $res['styleInfo'] = $tableInfo['relation_info'];
            $res['styleInfoCnt'] = count($res['styleInfo']);
            $res['relationLabels'] = $tableInfo['relationLabels'];
            $res['relationLabelsCnt'] = count($tableInfo['relationLabels']);
            $res['view_count'] = $tableInfo['view_count'];
            $res['updateTime'] = $tableInfo['updateTime'];
            // $res['source'] = $tableInfo['source'];
            $res['wbid'] = $wbid;
            $res['isuc'] = 1;
        } else {
            $res['stitle'] = $tableInfo['title'];
            $res['detailList'] = $tableInfo['detail_img'];
            $res['detailCnt'] = count($tableInfo['detail_img']);
            $res['styleInfo'] = $tableInfo['relation_info'];
            $res['supllierInfo'] = $tableInfo['supllierInfo'];//供应商信息
            $res['styleInfoCnt'] = count($res['styleInfo']);
            $res['relationLabels'] = $tableInfo['relationLabels'];
            $res['relationLabelsCnt'] = count($tableInfo['relationLabels']);
            $res['view_count'] = $tableInfo['view_count'];
            $res['updateTime'] = $tableInfo['updateTime'];
            // $res['source'] = $tableInfo['source'];
            $res['iUlbId'] = $tableInfo['iUlbId'];
            // 聚合页
            if ($isGroup) {
                $res['isGroup'] = $isGroup;

                $type = intval($this->input->post('type')); // 聚合参数的值（以品牌聚合就是品牌的id，以单品/品名聚合的就是单品/品名的id）
                $res['type'] = $type;
            }
            $res['isuc'] = 0;
        }
        // 取详情标签
        $aLabels = $this->details_model->getLabelInfo($table, $id, $params, $column);
        $res['styleInfo'] = $aLabels;
        if ($downloadType && $res["visit"]) {
            $res['downloadType'] = $downloadType;
            $res['downloadTypeCnt'] = count($downloadType);
        }
        if (!$res["visit"]) {
            $res['cover'] = $res['detailList'][0]["smallPath"];
            unset($res['detailList']);
        }
        $res['styleInfoCnt'] = count($res['styleInfo']);
        $res['STATIC_URL3'] = STATIC_URL3;
        $res['col'] = $column;

        if (isset($tableInfo['iSeason']) && !empty($tableInfo['iSeason'])) {
            $res['seasonInfo'] = ['id' => $tableInfo['iSeason'], 'name' => GetCategory::getOtherFromIds($tableInfo['iSeason'], ['sName'])];
        }

        return json_encode($res);
    }


    /**
     * 图案详情 游客跳中间页
     * @param $column
     * @param $columnPid
     * @param $tableInfo
     * @param $realTable
     * @param $res
     * @param $userType
     */
    private function styleVisitor($column, $columnPid, $tableInfo, $realTable, $res, $userType)
    {

        $aTopAds = $this->common_model->getAds($column, 8, 1, $columnPid);// 顶部广告
        $aBroadsideAd = $this->common_model->getAds($column, 9, 2, $columnPid);// 侧边广告
        $this->assign('aTopAds', $aTopAds);
        $this->assign('aBroadsideAd', $aBroadsideAd);

        // 标题
        $title = $tableInfo['title'];

        $tableName = $res['t'];
        $id = $res['id'];
        $columnId = $res['col'];
        $labels = $this->details_model->getLabelInfo($tableName, $id, '', $columnId, $type = 'detail', true);

        $descriptionHtml = '';

        $_path = getImagePath($realTable, $tableInfo);// 封面图路径
        $cover = getFixedImgPath($_path['cover']);
        $this->assign('cover', $cover);
        $this->assign('tit', $title);
        $this->assign('theme', $title);
        $this->assign('column', $column);
        $this->assign('title', $title . '-POP服装趋势网');
        $this->assign('keywords', $title);
        $this->assign('description', $title);

        // 中间页标签
        $this->assign('labels', $labels);
        $this->assign('labelsOrigin', 'style');

        if ($userType == 5) {
            $this->assign('descriptionHtml', $descriptionHtml);
            $this->display('mid_tourist.html');
        } elseif ($userType == 4) {
            $this->assign('bookDescription', $descriptionHtml);
            $this->display('mid_vip.html');
        }
    }

    /**
     * 手稿合辑和T台发布详情页
     * @param string $params
     */
    public function bookpresscon($params = '')
    {
        checkDetailVisitor();
        $this->benchmark->mark('bookpresscon');
        $this->load->model('details_model');
        $this->isAjax = $this->input->is_ajax_request();

        //详情反扒
        $s_p = $this->input->get_post('s_p', true);
        $paramsArr = [];
        if ($s_p) {//加密后的请求参数
            $paramsArr = $this->decrypt_param($s_p);
        } else {
            // 参数需全部编码, $params示例：t_perform-id_66666-col_52
            $params = $this->common_model->restoreParams($params);
            $paramsArr = $this->common_model->parseParams($params);
        }

        // 参数(表别名 id)
        $id = intval($paramsArr['id']);
        $table = $paramsArr['t'];
        $column = intval($paramsArr['col']);        // 栏目id

        if (!$paramsArr || !$id || !$table || !$column) {
            $this->isAjax ? exit(json_encode(['success' => 0, 'code' => 1, 'msg' => '参数错误'])) : show_404();
        }

        $indexF5 = intval($paramsArr['index']);        //图片索引
        $columnPid = GetCategory::getOtherFromColId($paramsArr['col']);
        $columnPid = empty($columnPid) ? $paramsArr['col'] : $columnPid;
        $this->assign('columnPid', $columnPid);

        // 将table转为真实表名
        $realTable = getProductTableName($table);
        // 是否书籍
        $isBook = false;
        // 是否F5
        $isF5 = true;

        $_detailResult = OpPopFashionMerger::getProductData($id, $realTable, $this->input->get("refresh", true));
        // 获取当前URL的数据
        $tableInfo = $this->details_model->getPicInfo($id, $realTable, '', $column, $indexF5, $_detailResult, $columnPid);
        // 获取权限
        $res = [];
        $detailResult = $_detailResult[$id];

        // T台发布
        $arr = [];
        if ($column == 3) {
            $arr['P_Column'] = 3;
            if ($detailResult['sGender']) {
                $arr['P_Gender'] = GetCategory::getOtherFromIds($detailResult['sGender'], ['sName']);
            } else {
                $arr['P_Gender'] = '';
            }
        } // 矢量书稿
        elseif ($column == 73) {
            $arr['P_Column'] = 73;
            $arr['P_Gender'] = $detailResult['iGender'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
            $isBook = true;
        } // 单品合集
        elseif ($column == 72) {
            $arr['P_Column'] = 72;
            $arr['P_Gender'] = $detailResult['typ'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
            $isBook = true;
        } // lookbook
        elseif ($column == 71) {
            $arr['P_Column'] = 71;
            $arr['P_Gender'] = $detailResult['iGender'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
            $isBook = true;
        } // 书店
        elseif ($column == 70) {
            $arr['P_Column'] = 70;
            $arr['P_Gender'] = $detailResult['type'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
            $isBook = true;
        } // T台系列
        elseif ($column == 113) {
            $arr['P_Column'] = 113;
            $arr['P_Gender'] = $detailResult['typ'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
        } // 品牌系列
        elseif ($column == 114) {
            $arr['P_Column'] = 114;
            $arr['P_Gender'] = $detailResult['typ'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
        } // 快反应系列
        elseif ($column == 115) {
            $arr['P_Column'] = 115;
            $arr['P_Gender'] = $detailResult['typ'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
            $isBook = true;
        }// 订货会合辑
        elseif ($column == 131) {
            $arr['P_Column'] = 131;
            $arr['P_Gender'] = $detailResult['iGender'];
            $arr['P_Industry'] = $detailResult['iIndustry'];
            $isBook = true;
        }

        $powers = memberPower('detail', $arr);
        $userType = $powers['P_UserType'];

        // 手稿合辑栏目-游客可以看免费预览书籍
        if (($userType == 5 || $userType == 4) && $column == 3) {
            //顶部广告
            $aTopAds = $this->common_model->getAds($column, 8, 1, $columnPid);
            //侧边广告
            $aBroadsideAd = $this->common_model->getAds($column, 9, 1, $columnPid);
            $this->assign('aTopAds', $aTopAds);
            $this->assign('aBroadsideAd', $aBroadsideAd);

            $descriptionHtml = '';
            $this->assign('descriptionHtml', $descriptionHtml);
            $this->assign('tit', $tableInfo['title']);
            $this->assign('theme', $tableInfo['title']);
            $this->assign('column', $column);
            $this->assign('cover', $this->details_model->getImgfPath($tableInfo['detail_img'][0]['smallPath']));
            $this->assign('title', $tableInfo['title'] . '-POP服装趋势网');
            $this->assign('keywords', $tableInfo['title']);
            $this->assign('description', $tableInfo['title']);
            // 取中间页标签
            $middlePageTags = [];
            $relationInfo = $tableInfo['relation_info'];
            $middlePageTags['colName'] = $relationInfo['classify']['name'];
            $middlePageTags['colLink'] = $relationInfo['classify']['link'];
            if (isset($relationInfo['brand']) && $relationInfo['brand']['name']) {
                $middlePageTags['brandName'] = $relationInfo['brand']['name'];
                $middlePageTags['brandLink'] = $relationInfo['brand']['link'];
            }
            if (isset($relationInfo['region']) && $relationInfo['region']['name']) {
                $middlePageTags['regName'] = $relationInfo['region']['name'];
                $middlePageTags['regLink'] = $relationInfo['region']['link'];
            }
            if (isset($relationInfo['season']) && $relationInfo['season']['name']) {
                $middlePageTags['seaName'] = $relationInfo['season']['name'];
                $middlePageTags['seaLink'] = $relationInfo['season']['link'];
            }
            if (isset($relationInfo['gender']) && $relationInfo['gender']['name']) {
                $middlePageTags['genInfo'][] = [
                    'sName' => $relationInfo['gender']['name'],
                    'link' => $relationInfo['gender']['link']
                ];
            }
            if (isset($relationInfo['industry']) && $relationInfo['industry']['name']) {
                $middlePageTags['indInfo'][] = [
                    'sName' => $relationInfo['industry']['name'],
                    'link' => $relationInfo['industry']['link']
                ];
            }
            if (isset($relationInfo['catagory']) && $relationInfo['catagory']['name']) {
                $middlePageTags['catInfo'][] = [
                    'sName' => $relationInfo['catagory']['name'],
                    'link' => $relationInfo['catagory']['link']
                ];
            }
            if (isset($relationInfo['subcatagory']) && $relationInfo['subcatagory']['name']) {
                $middlePageTags['scatInfo'][] = [
                    'sName' => $relationInfo['subcatagory']['name'],
                    'link' => $relationInfo['subcatagory']['link']
                ];
            }
            $this->assign('middlePageTags', $middlePageTags);

            if ($userType == 5) {
                $this->display('mid_tourist.html');
            } else {
                $this->display('mid_vip.html');
            }
        } else {
            // 是否能下载	P_SingleDownLoad
            $download = $powers['P_SingleDownLoad'];
            $visit = $powers['P_Visit'];

            //通过真实表名取主表，过滤lookbook数据
            $tableNameRelation = [
                'product_vector_refrence' => 'product_vector_refrence_group',
                'product_design_refrence_details' => 'product_design_refrence',
                'product_brochures_photo' => 'product_brochures',
            ];
            //预览模式
            if ($realTable == 'product_design_refrence_details') {// 单品合集
                $tt = OpPopFashionMerger::getProductData($detailResult['pid'], $tableNameRelation[$realTable]);
                $iPreviewMode = $tt[$detailResult['pid']]['iPreviewMode'];
            } elseif ($realTable == 'product_vector_refrence') {// 矢量书稿
                $tt = OpPopFashionMerger::getProductData($detailResult['groupid'], $tableNameRelation[$realTable]);
                $iPreviewMode = $tt[$detailResult['groupid']]['iPreviewMode'];
            } elseif ($realTable == 'product_brochures_photo') {
                $tt = OpPopFashionMerger::getProductData($detailResult['borochid'], $tableNameRelation[$realTable]);
                $iPreviewMode = $tt[$detailResult['borochid']]['iPreviewMode'];
            } elseif ($realTable == 'mostrend_content') {// 书店
                $iPreviewMode = $detailResult['iPreviewMode'];
            }
            // 快速预览  所有用户都只有权限查看前三条数据
            if ($iPreviewMode == 1) {
                if ($indexF5 < 3) {
                    $visit = true;
                } else {
                    $visit = false;
                }
            }
            // 是否能访问页面
            $res['visit'] = $visit;
            $res['download'] = $visit ? $download : 0;

            //如果没有查看权限，将大图路径隐藏
            if (!$visit) {
                $tableInfo['detail_img'][0]['bigPath'] = '/';
            }

            if (in_array($userType, [1, 2])) {
                $qqlink = '/service/aftersales/';
                $telnumber = '4008 210 662';
            } else {
                $qqlink = '/service/presales/';
                $telnumber = '4008 210 500';
            }
            $res['telnumber'] = $telnumber;
            $res['qqlink'] = $qqlink;
            $this->assign('telnumber', $telnumber);
            $this->assign('qqlink', $qqlink);

            // 获取款式模板|图案素材的下载格式
            $downloadType = [];
            if ($column == 73) {
                $downloadType = $this->details_model->getMaterialDown($tableInfo, $realTable);
            } else {
                $details = $tableInfo['detail_img'];
                $prefix = rtrim(FCPATH, '/');
                foreach ($details as $dk => $detail) {
                    $realImgLoc = $prefix . parse_url($detail['bigPath'], PHP_URL_PATH);
                    if (getenv('BASEENV') == false) {
                        $imgInfo = ['type' => '--', 'size' => '--'];
                    } else {
                        $imgInfo = api_getPicSize($realImgLoc);
                    }
                    $details[$dk]['shape'] = $imgInfo['type'];
                    $details[$dk]['size'] = $imgInfo['size'];
                }
                $tableInfo['detail_img'] = $details;
            }

            // 取合辑id
            $pid = $id;
            foreach (['pid', 'groupid', 'borochid', 'main_id'] as $val) {
                if (isset($detailResult[$val])) {
                    $pid = $detailResult[$val];
                }
            }
            // 书籍特殊处理：获取 是否收藏、下载链接、描述、书号、购买链接
            if ($isBook) {
                //收藏判断状态  1已收藏 -1无收藏权限 0未收藏
                $bIsCollect = 0;
                $powerArr = memberPower('other');
                $tableName = $table;
                $bookId = $id;
                $totalCount = 0;
                $page = 1;
                $pageSize = 1;
                $refresh = false;
                $relateLinks = [];
                $this->load->model('books_model', 'book');
                $tableArr = [
                    'brochuresphoto' => 'brochures',
                    'designrefrencedetails' => 'designrefrence',
                    'vectorrefrence' => 'refrencegroup'
                ];
                if (key_exists($tableName, $tableArr)) {
                    $tableName = $tableArr[$tableName];
                    $bookId = $pid;
                }
                switch ($tableName) {
                    case 'designrefrence': // 趋势手稿&单品合集
                        $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $pageSize, $refresh);
                        $database = 'fashion';
                        $collectName = $result['collectName'];
                        // 书籍编号
                        $sBookNumber = $result['sBookNumber'];
                        // 购买链接
                        $sBuyingLinks = $result['sBuyingLinks'];
                        // 描述
                        $bookDescription = $result[$tableName][0]['detail'];
                        $relateLinks = $this->book->getVectorLinks($tableName, $bookId);
                        break;
                    case 'moscon': // m书店
                        $bookInfo = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, 1, 10, $refresh);
                        $database = 'trends_new';
                        $collectName = $bookInfo['collectName'];
                        // 书籍编号
                        $sBookNumber = '';
                        // 购买链接
                        $sBuyingLinks = $bookInfo['sBuyingLinks'];
                        // 描述
                        $bookDescription = $bookInfo['description'];
                        break;
                    case 'brochures': // lookbook
                        $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $pageSize, $refresh);
                        $database = 'fashion';
                        $collectName = $result['collectName'];
                        // 书籍编号
                        $sBookNumber = $result['sBookNumber'];
                        // 购买链接
                        $sBuyingLinks = $result['sBuyingLinks'];
                        // 描述
                        $bookMemo = $this->book->getBookMemo($result[$tableName][0]['name'], $refresh);
                        $bookDescription = trim($result[$tableName][0]['detail']) ? trim($result[$tableName][0]['detail']) : trim($bookMemo);
                        break;
                    case 'refrencegroup': // 矢量书稿
                        $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $pageSize, $refresh);
                        $database = 'fashion';
                        $collectName = $result['collectName'];
                        // 书籍编号
                        $sBookNumber = $result['sBookNumber'];
                        // 购买链接
                        $sBuyingLinks = $result['sBuyingLinks'];
                        // 描述
                        $bookDescription = $result[$tableName][0]['digest'];
                        $relateLinks = $this->book->getVectorLinks($tableName, $bookId);
                        break;
                    default:
                        $database = ''; // db
                        $collectName = ''; // 收藏名
                        $sBookNumber = ''; // 书号
                        $sBuyingLinks = ''; // 购买链接
                        $bookDescription = ''; // 描述
                        break;
                }
                if ($powerArr['P_Collect']) {
                    //子账号
                    $this->load->model('collect_model');
                    $aLogonMessage = get_cookie_value();
                    $whetherCollect = $this->collect_model->existCollectStatus($aLogonMessage['sChildID'], $database, $collectName, $bookId);
                    if ($whetherCollect) {
                        $bIsCollect = 1;
                    }
                } else {
                    $bIsCollect = -1;
                }

                //下载链接  高端趋势书店 矢量文件 单张 | POP书店 单品合集 lookbook 成册 (从Books.php复制过来的代码)
                $realTableName = getProductTableName($tableName);
                if ($realTableName == 'product_vector_refrence_group') {
                    $realTableName = 'product_vector_refrence_list';
                }
                // 这行getProductData与上面重复了，以后再改
                $data = OpPopFashionMerger::getProductData($bookId, $realTableName);
                $data = $data[$bookId];
                $mainID = $bookId;
                $mtab = $tableName;
                $downLoadUrl = '';
                if (!in_array($column, array(71, 131)) && $relateLinks['vector'] && count($relateLinks) == 2) {
                    $relateKey = key($relateLinks);
                    $mainID = intval($relateLinks[$relateKey]['id']);
                    $mtab = trim($relateLinks[$relateKey]['t']);
                }
                if (!in_array($tableName, ['refrencegroup'])) {
                    //成册下载链接
                    if ($tableName == 'moscon') {
                        if ($data['pdf_name']) {
                            if ($relateLinks['vector'] && count($relateLinks) == 2) {
                                $downLoadUrl = $this->config->item('base_url') . 'download/book/' . $tableName . '-' . $bookId . '-' . $column . '-' . $mtab . '-' . $mainID . '/';
                            } else {
                                $downLoadUrl = $this->config->item('base_url') . 'download/book/' . $tableName . '-' . $bookId . '-' . $column . '/';
                            }
                        }
                    } else {
                        if ($relateLinks['vector'] && count($relateLinks) == 2) {
                            $downLoadUrl = $this->config->item('base_url') . 'download/book/' . $tableName . '-' . $bookId . '-' . $column . '-' . $mtab . '-' . $mainID . '/';
                        } else {
                            $downLoadUrl = $this->config->item('base_url') . 'download/book/' . $tableName . '-' . $bookId . '-' . $column . '/';
                        }
                    }
                } else {
                    $res['download'] = false;
                }
                //免费预览，所有用户均不可下载
                if ($iPreviewMode == 1) {
                    $downLoadUrl = '';
                    $res['download'] = false;
                }

                $power = $this->common_model->getPowers($columnPid, $params, $column);
                //书名
                $bookName = '';
                $bookKeys = ['nme', 'name_code', 'sBookName', 'name'];
                foreach ($bookKeys as $bookKey) {
                    if (isset($detailResult[$bookKey])) {
                        $bookName = $detailResult[$bookKey];
                        break;
                    }
                }

                // 右侧标签
                // 书名
                $labelBookName = [
                    'name' => $bookName,
                    'link' => $this->common_model->getLink($column, '', 'key', $bookName)
                ];
                $iSeason = OpPopFashionMerger::getNewDataByOld($detailResult['for_date'], $column, 5);
                $iSeason = isset($iSeason['s']) ? $iSeason['s'] : 0;
                $sSeason = '';
                if ($iSeason) {
                    $sSeason = GetCategory::getOtherFromIds($iSeason, ['sName', 'sEnName'], 'array');
                    $sSeason = isset($sSeason['sName']) ? $sSeason['sName'][0] : '';
                }
                $labelSeason = $sSeason == '' ? ['name' => ''] : [
                    'name' => $sSeason,
                    'link' => $this->common_model->getLink($column, '', 'key', $sSeason)
                ];
                $labelRegion = $detailResult['region'] == 'OthersEur' ? ['name' => ''] : [
                    'name' => $detailResult['region_text'],
                    'link' => $this->common_model->getLink($column, '', 'key', $detailResult['region_text'])
                ];
                // 单品是主表的iCategory
                if ($data['iCategory']) {
                    $cateInfo = GetCategory::getOtherFromIds($data['iCategory'], ['sName', 'sEnName'], 'array');
                    $labelCategory = [
                        'name' => $cateInfo['sName'][0],
                        'link' => $this->common_model->getLink($column, '', 'key', $cateInfo['sName'][0])
                    ];
                    $tableInfo['relation_info']['catagory'] = $labelCategory;
                }
                $tableInfo['relation_info']['region'] = $labelRegion;
                $tableInfo['relation_info']['season'] = $labelSeason;
                $tableInfo['relation_info']['bookName'] = $labelBookName;
                // 矢量的右侧不要标签
                if ($tableName == 'vectorrefrence' || $tableName == 'refrencegroup') {
                    $tableInfo['relation_info'] = [];
                }

                // 右侧更多
                $param = "boo_{$bookName}-sor_1";
                if ($tableName == 'brochures') {
                    // 广告大片不能通过书名查找，要通过品牌 查找
                    $param = "bra_{$data['brand_tid']}-sor_1";
                    // 书名
                    $labelBookName = [
                        'name' => $bookName,
                        'link' => $this->common_model->getLink($column, '', 'bra', $data['brand_tid'])
                    ];
                    $tableInfo['relation_info']['bookName'] = $labelBookName;
                }
                $moreList = $this->getMoreBooks($pid, $tableName, $param, $column, $power);

                $res['fastView'] = $iPreviewMode == 1 ? 1 : 0;
                $res['bookNumber'] = $sBookNumber;
                $res['buyLink'] = $sBuyingLinks;
                $res['description'] = $bookDescription;
                $res['isCollect'] = $bIsCollect;
                $res['downloadUrl'] = $download ? $downLoadUrl : '';
                $res['collectType'] = 8; // 收藏类型
                $res['collectPara'] = "mid={$mainID}&mtab={$mtab}"; // 收藏参数
                $res['moreList'] = $moreList;
                $res['moreListCnt'] = count($moreList);
                $res['listUrl'] = '/books/seclist/id_' . $pid . '-t_' . $tableName . '-yl_' . $iPreviewMode . '-col_' . $column . '/'; // 二级列表页url
                $res['listId'] = $pid; // 二级列表页id
                $res['listTable'] = $tableName; // 二级列表页table
            } else { // T台
                //收藏判断收藏状态  1已收藏 -1无收藏权限 0未收藏
                $bIsCollect = 0;
                if ($powers['P_Collect']) {
                    //子账号
                    $this->load->model('collect_model');
                    $aLogonMessage = get_cookie_value();
                    if ($this->collect_model->existCollectStatus($aLogonMessage['sChildID'], OpPopFashionMerger::POP_FASHION_DATABASE_NAME, OpPopFashionMerger::POP_Table_Name_Product_Presscon, $pid)) {
                        $bIsCollect = 1;
                    }
                } else {
                    $bIsCollect = -1;
                }

                $listTable = OpPopFashionMerger::POP_Table_Name_Product_Presscon;
                $pressConArr = OpPopFashionMerger::getProductData($pid, $listTable);
                $data = $pressConArr[$pid];
                $version = $paramsArr['ver'];
                //下载按钮状态
                if ($version == 'live') {
                    $buttonState = isset($data['rar_file']) && !empty($data['rar_file']) ? 1 : 0;
                } else {
                    $index = 'rar_file_' . $version;
                    $buttonState = isset($data[$index]) && !empty($data[$index]) ? 1 : 0;
                }

                $downLoadUrl = $this->config->item('base_url') . 'download/presscon/' . $version . '-' . $pid . '/';

                // 右侧更多
                $moreList = $this->getMorePress($pid, $listTable, $tableInfo['brand_tid']);

                $res['fastView'] = $iPreviewMode == 1 ? 1 : 0;
                $res['isCollect'] = $bIsCollect;
                $res['downloadUrl'] = $downLoadUrl;
                $res['downButtonState'] = $buttonState;
                $res['collectType'] = 1; // 收藏类型
                $res['collectPara'] = "ver={$version}"; // 收藏参数
                $res['moreList'] = $moreList;
                $res['moreListCnt'] = count($moreList);
                $res['listUrl'] = '/runways/inside/id_' . $pid . '/'; // 二级列表页url
                $res['listId'] = $pid; // 二级列表页id
                $res['listTable'] = getProductTableName($listTable); // 二级列表页table
            }

            // ajax请求
            if ($this->isAjax) {

                $verifyKeys = $this->_verifyParamKeys($s_p);
                if ($verifyKeys) {
                    $timeStamp = $paramsArr['timeStamp'];
                    $AES_KEY = $this->Secret_model->get_symmetric_key($timeStamp);

                    // 列表页
                    $res['info'] = '款式';
                    // 款式库(上下一款) T台发布|设计素材|灵感图库(上下一张) 手稿合辑(上下一页)
                    if (in_array($column, [4, 50, 52, 54, 55, 56, 57])) {
                        $res['word'] = '款';
                    } elseif (in_array($column, [3, 7, 80, 81, 82, 84, 85, 91, 117])) {
                        if ($column == 3) {
                            $res['info'] = '发布会';
                        } elseif (in_array($column, [80, 81, 82, 84, 85, 117])) {
                            $res['info'] = '素材';
                        } elseif ($column == 91) {
                            $res['info'] = '图片';
                        }
                        $res['word'] = '张';
                    } elseif (in_array($column, [6, 70, 71, 72, 73, 113, 114, 115, 131])) {
                        $res['word'] = '页';
                    } else {
                        $res['word'] = '款';
                    }
                    // 从数据库直接取当前数据
                    // 构造前后一页的链接
                    $total = $paramsArr['total'];
                    $index = $paramsArr['index'];
                    $this->load->model('member_model');
                    $sAccountId = getUserId();
                    $res['stitle'] = $tableInfo['title'];
                    $res['detailList'] = $tableInfo['detail_img'];
                    $res['detailCnt'] = count($tableInfo['detail_img']);
                    $res['styleInfo'] = $tableInfo['relation_info'];
                    $res['styleInfoCnt'] = count($res['styleInfo']);
                    $res['relationLabels'] = $tableInfo['relationLabels'];
                    $res['relationLabelsCnt'] = count($res['relationLabels']);
                    $res['ads'] = $tableInfo['ads'];
                    $res['adsCnt'] = count($tableInfo['ads']);
                    $res['view_count'] = $tableInfo['view_count'] < 100 ? $tableInfo['view_count'] + 800 : $tableInfo['view_count'];
                    $res['updateTime'] = date('Y-m-d', strtotime($tableInfo['updateTime']));
                    // $res['source'] = $tableInfo['source'];
                    $pid = $paramsArr['pid'];
                    if ($downloadType) {
                        $res['downloadType'] = $downloadType;
                        $res['downloadTypeCnt'] = count($downloadType);
                    }
                    $res['STATIC_URL3'] = STATIC_URL3;
                    $data = $this->encrypt_data(json_encode($res), $AES_KEY);
                    echo json_encode(['code' => 0, 'msg' => 'ok', 'data' => $data]);
                    return;
                }

            }
            // 发布会，设计素材，灵感图库显示信息
            $res['info'] = '';
            if (in_array($column, [3, 7, 80, 81, 82, 84, 85, 91, 117])) {
                if ($column == 3) {
                    $res['info'] = '发布会';
                } elseif (in_array($column, [80, 81, 82, 84, 85, 117])) {
                    $res['info'] = '素材';
                } elseif ($column == 91) {
                    $res['info'] = '图片';
                }
            }
            $this->assign('info', $res['info']);
            //普通用户
            if ($userType == 4) {
                $this->assign('joinGZT', 1);
            }
            // 访问
            $this->assign('visit', $visit);
            // 下载
            $this->assign('download', $res['download']);
            $this->assign('downloadType', $downloadType);
            $this->assign('table', $table);
            $this->assign('stitle', $tableInfo['title']);
            $this->assign('source', '');
            $this->assign('view_count', $tableInfo['view_count']);
            $this->assign('detailList', $tableInfo['detail_img'][0]);
            $this->assign('styleInfo', $tableInfo['relation_info']);
            $this->assign('relationLabels', $tableInfo['relationLabels']);
            $this->assign('updateTime', $tableInfo['updateTime']);
            $this->assign('ads', $tableInfo['ads']);
            $this->assign('title', $tableInfo['title'] . '-POP服装趋势网');
            $this->assign('keywords', $tableInfo['title']);
            $this->assign('description', 'description');
            $this->assign('isF5', true);
            $this->assign('isBook', $isBook);
            $this->assign('columnId', $column);

            // 给更多推荐用
            $this->assign('t', $table);
            $this->assign('id', $id);
            $this->assign('col', $column);

            //详情反扒参数
            $this->secret_params();

            $this->benchmark->mark('bookpressconEnd');
            $this->display('bookpresscon_detail_F5.html');
        }
    }


    /**
     * 报告详情
     * 2017-08-18
     */
    public function report($param = '')
    {
        $this->benchmark->mark('report');
        $this->load->model(['report_model', 'member_model', 'collect_model']);

        $curUrl = config_item('base_url') . 'details/report/' . $param . '/';
        $this->assign('curUrl', $curUrl);

        /** table 的可能取值 'fs_analysis', 'fs_commodity', 'fs_design', 'fs_inspiration', 'fs_trend',
         * 't_trend_report', 'trends_new'.'mostrend_content'(id), 'specialtopic', 'specialtopic_graphic'
         */
        $paramsArr = $this->common_model->parseParams($param, 1, false);
        $columnPid = GetCategory::getOtherFromColId($paramsArr['col']);
        $columnPid = empty($columnPid) ? $paramsArr['col'] : $columnPid;
        $this->assign('columnPid', $columnPid);
        $table = getProductTableName($paramsArr['t']);
        $id = intval($paramsArr['id']);
        $column = $col = intval($paramsArr['col']);
        $sreportID = $table . '_' . $id; //报告唯一标识ID
        $isPdf = isset($_GET['pdf']) && $_GET['pdf'];

        //预览报告详情链接密钥验证
        $previewFlag = false;
        //$hash_link = $this->input->get("Hash", true);
        //$hash_check = strtolower(md5($sreportID . date("Y-m-d", time()) . POP_GLOBAL_KEYS));

        //--------------------验证PDF链接有效性-----------------------------------
        $hash_link = $this->input->get("sign", true);
        $hash_check = Permit::checkSign($hash_link);
        if (!$hash_check && $isPdf) {
            show_404();
            exit;
        }

        /*
         $hash_check = md5( md5( 'fmreport' . '-' . $paramsArr['t'] . '-' . $id.'-'.date( 'YmdH' ) ) );
         if ( $isPdf && $hash_link != $hash_check) {
             //找不到内容时，跳404
             show_404();
             exit;
         }
         */
        //-------------------------------------------------------


        if ($hash_check) {
            //验证通过
            Permit::upSign($hash_link);
            $previewFlag = true;
        }

        $this->isAjax = $this->input->is_ajax_request();

        $res = [];
        $fsFashionTables = [
            'fs_analysis',
            'fs_commodity',
            'fs_design',
            'fs_inspiration',
            'fs_trend'
        ];
        $isMos = false;

        // 服装趋势类栏目5个表
        if (in_array($table, $fsFashionTables, true)) {
            $res = $this->report_model->getFsDatas($table, $id, 'id', $col);
        }
        $iReportBook = 0;
        switch ($table) {
            case 't_trend_report':
                $res = $this->report_model->getTrendReport($id, $col);
                $iReportBook = $this->report_model->getReportBookId('t_trend_report', $res['id']);
                break;
            case 'mostrend_content':
                // 查主表数据
                $res = $this->report_model->getMosReport($id, $col);
                $isMos = true;
                break;
            case 'specialtopic':
                $res = $this->report_model->getSpecialtopic($id, $col);
                break;
            case 'specialtopic_graphic':
                $res = $this->report_model->getSpecialtopicGraphic($id, $col);
                break;
        }

        if ($res === false) {
            //找不到内容时，跳404
            show_404();
            exit;
        }
        // 取详情标签
        $aDetailLabels = $this->report_model->getLabelInfo($table, $id, $col, '', 'detail');
        $res['aDetailLabels'] = $aDetailLabels;

        //报告详情广告
        $res['ads'] = $this->common_model->getAds($col, 6, 1, $columnPid);

        // 浏览，pdf下载，收藏等权限
        $powerDetails = memberPower('detail', array(
            'P_Gender' => $res['gender'],
            'P_Industry' => $res['industry'],
            'P_Column' => $col
        ));

        if ($previewFlag) {
            $powerDetails = [
                'P_Collect' => false,
                'P_Visit' => true,
                'P_SingleDownLoad' => false,
                'P_PdfDownLoad' => false,
                'P_UserType' => 1,
                'P_AccountFrom' => 1
            ];
        }
        //获取权限
        $powers = memberPower('other');

        //20200330新的免费试读报告id
        if ($powers['P_UserType'] == 4) {
            $free_report_ids = $this->_getFreeReport();
            $powerDetails['P_Visit'] = in_array($id, $free_report_ids) ? true : false;
        }


        //是否属于试读 仅限潮流解析和趋势栏目
        if (in_array($columnPid, [1, 2])) {
            $tmpTime = strtotime('-19 month');
            $tmpTime2 = strtotime('-18 month');
            if (isset($res['iRecChannel']) && $res['iRecChannel'] == 1 && !empty($res['iCreateTime']) && $res['iCreateTime'] >= $tmpTime && $res['iCreateTime'] < $tmpTime2) {
                $powerDetails['P_Visit'] = $powers['P_UserType'] == 5 ? false : true;
                $powerDetails['P_Tryout'] = true;
            }
        }

        // 免费试读列表
        if (!$isPdf && in_array($powers['P_UserType'], [4, 5])) {
            if (in_array($columnPid, [1, 2])) {
                $_list = $this->report_model->getShiDu($table, $id, $col);
                $this->assign("tryOutList", $_list['reports']);
            }
        }


        // 是否已经收藏
        $res['iscollected'] = 0;
        $sDatabase = ($table == 'mostrend_content') ? 'trends_new' : 'fashion';
        // 1 主账号 2 子账号
        if ($powerDetails['P_Collect']) {
            $sChildID = getUserId();
            $res['iscollected'] = intval($this->collect_model->existCollectStatus($sChildID, $sDatabase, $table, $id));
        }

        // 性别行业
        $pow = [];
        if (!empty($res['genderText']) && !empty($res['industryText'])) {
            $pow[] = $res['genderText'] . '/' . $res['industryText'];
        } elseif (!empty($res['genderText'])) {
            $pow[] = $res['genderText'];
        } elseif (!empty($res['industryText'])) {
            $pow[] = $res['industryText'];
        }

        // 栏目名称
        if (!empty($res['colsName']) && !empty($res['colPsName'])) {
            $pow[] = $res['colPsName'] . '/' . $res['colsName'];
        } elseif (!empty($res['colPsName'])) {
            $pow[] = $res['colPsName'];
        } elseif (!empty($res['colsName'])) {
            $pow[] = $res['colsName'];
        }
        $power12 = $pow ? implode(' ', $pow) : '';

        //==============================相关面料begin===================================
        $this->load->model('details_model');
        $showFabric = $this->details_model->getFabricMatchResult($sreportID, 1);
        // 是否显示相关面料
        $this->assign('showFabric', $showFabric);
        //==============================相关面料end=====================================
        // 是否显示看了又看，灵感源(90)、灵感视频(116)不显示 /ajax/getmorereportrec/
        $showMoreRec = true;
        if (in_array($col, [90, 116])) {
            $showMoreRec = false;
        }
        $this->assign('showMoreRec', $showMoreRec);

        $cover = '';
        if (isset($res['cover']) && !empty($res['cover'])) {
            $cover = $this->details_model->getImgfPath($res['cover'], true);
        }
        $this->assign('cover', $cover);

        $title = $res['title'] . '-POP服装趋势网';
        $keywords = $res['title'];
        $description = $res['description'];
        if ($isPdf) {
            $this->report_model->setRefresh(true);
            $this->report_model->setNoCache(true);
            $keywords = '报告详情';
            $description = '报告详情';
        }

        $res['pdfPrefix'] = strpos($res['pdfPath'], 'http:') === false ? STATIC_URL1 : '';

        // flash报告页
        $flashPages = [];

        if ($res['subTopic']) {
            // 按权重倒序
            usort($res['subTopic'], function ($a, $b) {
                if ($a['iSort'] == $b['iSort']) {
                    return $a['iSubTopicId'] > $b['iSubTopicId'];
                }
                return $a['iSort'] < $b['iSort'];
            });
            foreach ($res['subTopic'] as $k => $subTopic) {
                foreach ($subTopic['subPage'] as $key => $subPage) {
                    if ($subPage['pageType'] == 4) {
                        $flashPages[$subPage['iPageId']] = $subPage;
                    }
                    // 替换video路径
                    if ($subPage['content']) {
                        $res['subTopic'][$k]['subPage'][$key]['content'] = $this->details_model->getVedioPath($subPage['content']);
                    }
                }
            }
        }

        // 中间页  封面图的图片域名替换
        if ($res['cover']) {
            $res['cover'] = $this->details_model->getImgfPath($res['cover'], true);
        }
        $res['reportBookCatalog'] = $this->report_model->getReportBookCatalog($iReportBook);
        //记录vip报告浏览历史
        if ($powerDetails['P_Visit'] && in_array($powers['P_UserType'], [1, 2, 3]) && in_array($columnPid, [1, 2])) {
            $pop_id = $table . '_' . $id;
            $this->_setFashionHistoryRedis(1, $pop_id, $col);//设置栏目id为1
        }

        $this->assign('flashPages', $flashPages);// flash报告页

        // 灵感视频 详情新模板 
        switch ($col) {
            case 116: // 灵感视频 详情新模板
                $pageList = 'detail/inspiration_video_detail.html';
                //-- 中间页 ------------------------------------------------
                //顶部广告
                $aTopAds = $this->common_model->getAds($col, 8, 1, 3);
                //侧边广告
                $aBroadsideAd = $this->common_model->getAds($col, 9, 2, 3);
                $this->assign('aTopAds', $aTopAds);
                $this->assign('aBroadsideAd', $aBroadsideAd);

                $tags = [];
                $infoBrand = $res['moreTags']['infoBrand'];
                if (!empty($infoBrand)) {
                    $tags['brandName'] = $infoBrand[0]['sName'];
                    $tags['brandLink'] = $infoBrand[0]['link'];
                }
                $tags['colLink'] = $this->common_model->getLink($col);
                $tags['colName'] = GetCategory::getOtherFromColId($col, 'sName');
                $this->assign('middlePageTags', $tags);
                if ($powers['P_UserType'] == 5 || ($powers['P_UserType'] == 4)) {
                    $this->assign('title', $res['title'] . '-POP趋势资讯网');
                    $this->assign('keywords', $res['title']);
                    $this->assign('description', $res['title']);
                    $this->assign("P_UserType", $powers['P_UserType']);
                    $this->assign("is_inspiration_video", true);// 灵感视频
                    $this->assign('column', $col);
                    $this->assign("cover", $res['cover']);
                    $this->assign('data', $res);
                    $this->assign('tit', $res['title']);
                    $this->display('mid_tourist.html');
                    die;
                }
                break;
            default:
                $pageList = 'reportDetail.html';
                break;
        }

        $isFashionShows = isFashionShows($table);
        $this->assign('isFashionShows', $isFashionShows);
        $this->assign('isMos', $isMos);
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);
        $this->assign('res', $res);
        $this->assign('isPdf', $isPdf);
        $this->assign('power', $power12); // 无当前页访问权限时的栏目名等信息
        $this->assign('powerDetails', $powerDetails);
        $this->assign('P_UserType', $powers['P_UserType']);//只传other获取到的P_UserType才是真的会员类型
        $this->assign('cntSubTopic', count($res['subTopic']));
        $rename = htmlspecialchars_decode($res['title']);
        $rename = preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9\_]/iu", "", $rename);
        $this->assign('dlRename', $rename);

        // 看了又看
        $this->assign('t', $paramsArr['t']);
        $this->assign('id', $id);
        $this->assign('col', $col);
        $this->secret_params();

        $this->display($pageList);
    }

    /**
     * 报告详情
     * 给后台生成pdf用
     * 需要绕过权限控制
     * @param string $param
     */
    public function reportpdf($param = '')
    {
        $_GET['pdf'] = 1;
        $this->report($param);
    }

    //读物-杂志前线
    //'t='+table+'&id='+id+'&ver='+ver
    public function magazine($params = '')
    {
        checkDetailVisitor();
        $powers = memberPower('other');
        $aLogonMessage = $this->member_model->logonMessage();
        $iAccountType = $aLogonMessage['iAccountType'];
        unset($aLogonMessage);

        $iColumnId = 130;

        $this->load->model('books_model');

        //详情反扒
        $s_p = $this->input->get_post('s_p');
        if ($s_p) {
            $paramsArr = $this->decrypt_param($s_p);
        } else {
            $params = $this->common_model->restoreParams($params);
            $paramsArr = $this->common_model->parseParams($params, 1, false);
        }

        $total = $paramsArr['total'];
        $index = $paramsArr['index'];
        $ver = isset($paramsArr['ver']) && !empty($paramsArr['ver']) ? trim($paramsArr['ver']) : '';
        $TempIdArr = explode('I', $paramsArr['id']);
        unset($TempIdArr[count($TempIdArr) - 1]);
        $magazineId = rtrim(implode('I', $TempIdArr), 'I');

        if (!$paramsArr || empty($TempIdArr) || !$magazineId) {
            $this->input->is_ajax_request() ? exit(json_encode(['code' => 1, 'msg' => '参数错误', 'data' => []])) : show_404();
            return;
        }


        $picList = $magazineInfo = [];
        $list = $this->books_model->getMagazineDetails($paramsArr, $picList);
        $this->books_model->getMagazineInfo($magazineId, $magazineInfo, $ver);
        $tableInfo = $picList[0];
        $magazineInfo = $magazineInfo[0];

        //大图路径
        if ($ver == "isSimple") {
            if ($tableInfo['is_old']) {
                $arr_id = (array)$tableInfo['id'];
                $big_img_name = md5(end(explode("/", $tableInfo['img_url'])) . $tableInfo['list_img_name'] . 'www.pop-fashion.com') . '.jpg'; //拼大图片名字
                $big_image = STATIC_URL2 . IMAGE_MAGAZINE_PATH . '/' . $tableInfo['img_url'] . '/simple/images/' . $big_img_name;
                $small_img_url = IMAGE_MAGAZINE_PATH . '/' . $tableInfo['img_url'] . '/simple/thumbnails/' . $tableInfo['list_img_name'];
            } else {
                $arr_id = (array)$tableInfo['id'];
                if ($tableInfo['source'] != "") { // source
                    $web_site = $tableInfo['source'];
                } else {
                    $web_site = $tableInfo['site'];
                }
                $big_image = STATIC_URL2 . '/' . $web_site . '/' . substr($tableInfo['create_date'], 0, 4) . '/' . $tableInfo['create_date'] . '/' . $tableInfo['serial_number'] . "/detail/big/" . md5($arr_id['$id']) . '_' . $tableInfo['list_img_name'];
                $small_img_url = '/' . $web_site . '/' . substr($tableInfo['create_date'], 0, 4) . '/' . $tableInfo['create_date'] . '/' . $tableInfo['serial_number'] . "/detail/small/" . $tableInfo['list_img_name'];
            }
        } else {
            $arr = explode('I', $magazineId);
            $vcount = count($arr);
            if ($vcount == 2) {
                // 老数据
                $big_img_name = md5(end(explode("/", $tableInfo['img_url'])) . $tableInfo['list_img_name'] . 'www.pop-fashion.com') . '.jpg'; //拼大图片名字
                $big_image = STATIC_URL2 . IMAGE_MAGAZINE_PATH . '/' . $tableInfo['img_url'] . '/detail/images/' . $big_img_name;
                $small_img_url = IMAGE_MAGAZINE_PATH . '/' . $tableInfo['img_url'] . '/detail/thumbnails/' . $tableInfo['list_img_name'];
            } else {
                $arr_id = (array)$tableInfo['id'];
                if ($tableInfo['source'] != "") { // source
                    $web_site = $tableInfo['source'];
                } else {
                    $web_site = $tableInfo['site'];
                }
                $big_image = STATIC_URL2 . '/' . $web_site . '/' . substr($tableInfo['create_date'], 0, 4) . '/' . $tableInfo['create_date'] . '/' . $tableInfo['serial_number'] . "/detail/big/" . md5($arr_id['$id']) . '_' . $tableInfo['list_img_name'];
                $small_img_url = '/' . $web_site . '/' . substr($tableInfo['create_date'], 0, 4) . '/' . $tableInfo['create_date'] . '/' . $tableInfo['serial_number'] . "/detail/small/" . $tableInfo['list_img_name'];
            }
        }

        //图片
        $tableInfo['detail_img'][0]['smallPath'] = $tableInfo['list_img_name'] ? $small_img_url : '';
        $tableInfo['detail_img'][0]['bigPath'] = $tableInfo['list_img_name'] ? $big_image : '';
        $tableInfo['detail_img'][0]['simpleName'] = 'imgcol_set';
        $tableInfo['detail_img'][0]['id'] = $paramsArr['id'];
        $tableInfo['detail_img'][0]['columnid'] = '';
        // 单张下载重定向
        $rename_rule = $this->details_model->getRenameData($iColumnId, $magazineInfo['column_title'], $paramsArr['id'], $tableInfo['detail_img'][0]['bigPath']);
        $tableInfo['detail_img'][0]['rename'] = $rename_rule;

        $realImgLoc = parse_url($big_image, PHP_URL_PATH);
        if (getenv('BASEENV') == false) {
            $imgInfo = ['type' => rand(500, 800) . '*' . rand(200, 500), 'size' => rand(700, 999) . 'KB'];
        } else {
            $imgInfo = api_getPicSize($realImgLoc);
        }
        $tableInfo['detail_img'][0]['shape'] = $imgInfo['type'];
        $tableInfo['detail_img'][0]['size'] = $imgInfo['size'];
        $_category = explode(',', $magazineInfo['attribute']['category']);
        $_categoryname = $_category[0];
        $_subcategoryname = $_category[1];
        //款式信息
        $tableInfo['relation_info'] = [
            'bookName' => [
                'name' => $magazineInfo['set_brand'],
                'link' => $this->common_model->getLink($iColumnId, $params, 'key', $magazineInfo['set_brand'])
            ],
            'catagory' => [
                'name' => $_categoryname,
                'link' => $this->common_model->getLink($iColumnId, $params, 'key', $_categoryname)
            ],
            'subcatagory' => [
                'name' => $_subcategoryname,
                'link' => $this->common_model->getLink($iColumnId, $params, 'key', $_subcategoryname)
            ],
            'season' => [
                'name' => $magazineInfo['attribute']['fordate'],
                'link' => $this->common_model->getLink($iColumnId, $params, 'key', $magazineInfo['attribute']['fordate'])
            ],
        ];

        //来源
        // $sSourceText = $this->details_model->_getSourceText($tableInfo['source']);

        // ajax请求
        if ($this->input->is_ajax_request()) {
            $verifyKeys = $this->_verifyParamKeys($s_p);
            if ($verifyKeys) {
                $timeStamp = $paramsArr['timeStamp'];
                $AES_KEY = $this->Secret_model->get_symmetric_key($timeStamp);

                // 从数据库直接取当前数据
                $res['visit'] = true;
                $res['stitle'] = $magazineInfo['column_title'];
                $res['detailList'] = $tableInfo['detail_img'];
                $res['styleInfo'] = $tableInfo['relation_info'];
                $res['styleInfoCnt'] = count($tableInfo['relation_info']);
                $res['detailCnt'] = count($tableInfo['detail_img']);
                $res['total'] = $total;
                $res['view_count'] = $magazineInfo['view_count'];
                $res['updateTime'] = date('Y-m-d', $tableInfo['update_time']);
                // $res['source'] = $sSourceText ? $sSourceText : '失效';
                $res["info"] = "杂志";
                $res["word"] = "页";

                $viewModel = $ver;
                if ($viewModel == 'isSimple') {
                    //下载链接
                    $downLoadUrl = $this->config->item('base_url') . 'download/magazine/' . $magazineId . '-' . $viewModel . '/';
                } else {
                    //下载链接
                    $downLoadUrl = $this->config->item('base_url') . 'download/magazine/' . $magazineId . '/';
                }
                $rename = htmlspecialchars_decode($magazineInfo['column_title']);
                $rename = preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9\_]/iu", "", $rename);
                $this->assign('dlRename', urlencode($rename));
                $downLoadUrl .= '?rename=' . $rename;
                $download = true;
                if ($powers['P_UserType'] == 3 && $this->iAccountType == 2) {
                    $download = false;
                }

                if ($magazineInfo['control_down'] == 1 && date('Y-m-d H:i:s', strtotime('-15 day')) < date("Y-m-d H:i:s", $magazineInfo['create_time'])) {
                    $download = false;
                }
                $res['description'] = $magazineInfo['description'];
                $res['download'] = $download;
                $res['downloadUrl'] = $download ? $downLoadUrl : '';

                // 杂志书名
                $bookName = $magazineInfo['set_brand'];
                // 右侧更多
                $res['moreList'] = $this->getMoreMagazine($magazineId, $bookName);
                $res['moreListCnt'] = count($res['moreList']);
                $res['listUrl'] = '/books/innermaga/id_' . $magazineId . '/';

                $data = $this->encrypt_data(json_encode($res), $AES_KEY);
                // $data = $res;
                echo json_encode(['code' => 0, 'msg' => 'ok', 'data' => $data]);
                return;
            }

        }

        $this->secret_params();

        $this->assign('stitle', $magazineInfo['column_title']);
        $this->assign('title', $magazineInfo['column_title'] . '-POP服装趋势网');
        $this->assign('keywords', $magazineInfo['column_title']);
        $this->assign('description', $magazineInfo['description']);
        $this->assign('isF5', true);
        $this->assign('isMagazine', true);

        $this->display('bookpresscon_detail_F5.html');
    }


    /**
     * 虚拟样衣
     */
    public function virtualSpl($params = '')
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        // ie8及以下不支持
        if (strstr($ua, 'MSIE 6') || strstr($ua, 'MSIE 7') || strstr($ua, 'MSIE 8')) {
            header('Location:/software');
        }
        $columnId = 82;
        $columnPid = GetCategory::getOtherFromColId($columnId);
        $columnPid = empty($columnPid) ? $columnId : $columnPid;
        $id = $this->input->get_post('id', true);
        $codepic = $this->input->get_post('codepic', true);
        $pic = rawurldecode(urldecode($this->common_model->strReplace($codepic)));
        $sign = $this->input->get_post('sign', true);
        $table = $this->input->get_post('t', true);
        $col = $this->input->get_post('col', true);
        $detailSrc = "/details/style/t_{$table}-id_{$id}-col_{$col}/";
        $this->assign('detailSrc', $detailSrc);
        $act = $this->input->get_post('act', true);
        switch ($act) {
            case 'proxy': // 暂时用来解决前端合成图片时跨域问题
                ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 6.0)');
                header('Content-type: image/jpg');
                $img = $this->input->get_post('proxy_img', true);
                $img = urldecode($img);
                echo file_get_contents($img);
                exit();
                break;
            case 'transfer': // 从列表页跳转过来中转
                $tableName = getProductTableName($table);
                $productData = OpPopFashionMerger::getProductData($id, $tableName);
                if (!isset($productData[$id])) {
                    die('获取数据失败');
                }
                $productData = $productData[$id];
                $imgPath = getImagePath($tableName, $productData);// 图片路径
                $power = memberPower();
                // 默认模拟成品用小图, vip用中图
                $vImg = $imgPath['smallPath'];
                if ($power['P_UserType'] == 1 || $power['P_UserType'] == 2) {
                    $vImg = str_replace('/big/', '/mbig/', $imgPath['bigPath']);
                }
                $sign = substr(md5('popfashion_fitting' . $id . $vImg), 0, 5);
                $vParams = [
                    'id' => $id,
                    't' => $table,
                    'col' => $col,
                    'sign' => $sign,
                    'codepic' => urlencode($vImg)
                ];
                $redirectUrl = '/details/virtualspl/?' . http_build_query($vParams);
                header('Location: ' . $redirectUrl);
                exit();
                break;
            case 'changeImg': // 模拟成品 前端切换图片也计入试用次数
                if (!$this->input->is_ajax_request()) {
                    header('Location: /');
                    return;
                }
                $jo = getJsonInstance();
                $userId = getUserId();
                if (!$userId) {
                    $jo->code(1)->msg('需要登录')->out();
                }
                $path = $this->input->get_post('path', true);
                $power = memberPower();
                if ($power['P_UserType'] != 4) {
                    $jo->code(0)->msg('非普通用户没有次数限制')->out();
                }
                $res = checkTrailInfo($path, 'virtualSpl:images', true); // ['canUse' => true, 'trialInfo' => ['max' ...]]
                if ($res['canUse']) {
                    $jo->code(0)->data($res['trialInfo'])->msg('ok')->out();
                } else {
                    $jo->code(2)->msg('没有权限或试用次数已用完')->out();
                }
                break;
            case 'html':
            default:
                if ($sign != substr(md5('popfashion_fitting' . $id . $pic), 0, 5)) {
                    show_404();
                }
                $this->load->database();
                if (is_null($this->db)) {
                    show_404();
                }
                checkTrailInfo($pic, 'virtualSpl:images');
                // 获取分类列表
                $sql = "SELECT id,iClassifyId,sThumbnailPath,sLargePath,iWeight,sShowSite FROM t_virtual_spl_template WHERE `iAccountId`=0 AND `iStatus`=1 AND FIND_IN_SET(1,`sShowSite`) ORDER BY `iWeight` DESC";
                $query = $this->db->query($sql);
                $lists = $query->result_array();
                $list = array();
                foreach ($lists as $k => $v) {
                    if (!key_exists($v['iClassifyId'], $list)) {
                        $list[$v['iClassifyId']] = array();
                    }
                    $list[$v['iClassifyId']][] = $v;
                }
                $lists = $list;
                $table = getProductTableName($table);
                $info = OpPopFashionMerger::getProductData($id, $table);
                $sDefaultColor = isset($info[$id]['sDefaultColor']) ? $info[$id]['sDefaultColor'] : '';
                $sApplication = isset($info[$id]['sApplication']) ? $info[$id]['sApplication'] : '';
                $sApplication = $sApplication == '14102' ? 'false' : 'true'; // 14102 局部, 14103 满身

                $this->assign('columnPid', $columnPid);
                $this->assign('lists', $lists);
                $this->assign('codepic', $codepic);
                $this->assign('id', $id);
                $this->assign('sign', $sign);
                $this->assign('bg_pic', $pic);
                $this->assign('title', '女|男|童装款式模板-POP服装趋势网在线3D试衣分类-POP服装趋势网');
                $this->assign('keywords', '女|男|童装3D试衣图');
                $this->assign('description', '您可在左侧栏中选择女|男|童装款式模板，并生成不同的3D虚拟样衣图。');
                $this->assign('defaultColor', $sDefaultColor); // 默认色
                $this->assign('isFull', $sApplication); // 是否满身

                $this->display('virtual_spl.tpl.html');
                break;
        }
    }


    private function dealPageTurnData($result)
    {
        foreach ($result as $key => $val) {
            $tb = getProductTableName($val['tablename']);
            $id = $val['pri_id'];
            $_pageTurnList[] = [
                'col' => end($val['iColumnId']),
                'tb' => $tb,
                'id' => $id
            ];
        }
        return $_pageTurnList;
    }

    /**
     * 书籍详情页 右侧更多
     * @param $id
     * @param $bookName
     * @param $columnId
     * @param $powers
     * @param string $type
     * @return array
     */
    private function getMoreBooks($id, $tableName, $param, $columnId, $powers, $type = 'books')
    {
        $cacheKey = self::FM_TEM_BOOK_MORE_REC_MEMCACHE_KEY . $tableName . '_' . $id;
        $this->load->driver('cache');
        $list = $this->cache->memcached->get($cacheKey);
        if ($list && !$_GET['refresh']) {
            return $list;
        }
        $offset = 0;
        $pageSize = 5; // 需要4条，不包含当前的，所以先取5个再排除当前的或者最后一个
        $_list = $list = [];
        $this->load->model('books_model');
        $totalCount = $this->books_model->getBookLists($param, $columnId, $_list, $offset, $pageSize, $powers, $type);
        foreach ($_list as $key => $val) {
            if ($val['list']['id'] != $id) {
                $list[] = [
                    'title' => $val['list']['title'],
                    'cover' => $this->details_model->getImgfPath($val['list']['cover']),
                    'link' => "/books/seclist/id_{$val['list']['id']}-t_{$val['tableName']}-yl_{$val['list']['iPreviewMode']}-col_{$val['columnId']}/",
                ];
            }
        }
        $list = array_slice($list, 0, $pageSize - 1);
        $this->cache->memcached->save($cacheKey, $list, 3600 * 8);
        return $list;
    }

    /**
     * T台详情页 右侧更多
     * 本品牌其他发布会，以及相似品牌发布会
     */
    private function getMorePress($pid, $tableName, $brandId = 0, $gender = 0, $industry = 0)
    {
        $cacheKey = self::FM_TEM_BOOK_MORE_REC_MEMCACHE_KEY . $tableName . '_' . $pid;
        $this->load->driver('cache');
        $list = $this->cache->memcached->get($cacheKey);
        if ($list && !$_GET['refresh']) {
            return $list;
        }
        $this->load->model('brands_model');
        $list = [];
        $columnId = 3; // 发布会

        $similarBrands = $this->brands_model->similarBrands($brandId, $gender, $industry);//相似款信息
        $brands = [];
        foreach ($similarBrands as $brand) {
            $brands[] = $brand['id'];
        }
        $condition = [];
        $condition['iColumnId'] = $columnId;
        $condition['-pri_id'] = $pid;
        $condition['dCreateTime'] = $this->common_model->getTimeRange();
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $res = [];
        $condition['iBrand'] = $brandId;
        POPSearch::wrapQueryPopFashionMerger('', $condition, $res, 0, 4, $arSort);
        $res = is_array($res) ? $res : [];
        if (count($res) < 4) {
            unset($condition['iBrand']);
            $condition['other'][] = "iBrand:(" . implode(' OR ', $brands) . ")";
            $_res = [];
            POPSearch::wrapQueryPopFashionMerger('', $condition, $_res, 0, 4 - count($res), $arSort);
            $_res = is_array($_res) ? $_res : [];
            $res = array_merge($res, $_res);
        }

        foreach ($res as $key => $val) {
            $priId = $val['pri_id'];
            $row = OpPopFashionMerger::getProductData($priId, $val['tablename']);
            $row = $row[$priId];
            $list[] = [
                'title' => $row['nme'],
                'cover' => json_decode($row['sCoverImg'], true),
                'link' => "/runways/inside/id_{$priId}/",
            ];
        }
        $list = array_slice($list, 0, 4);
        $this->cache->memcached->save($cacheKey, $list, 3600 * 8);
        return $list;
    }

    /**
     * 杂志详情页 右侧更多
     * 相同书名(set_brand)的杂志
     */
    private function getMoreMagazine($magazineId, $bookName)
    {
        $options = [$bookName];
        $offset = 0;
        $pageSize = 5;
        $totalCount = 0;
        $aSort = array('order_time' => 'DESC');
        $this->load->model('books_model');
        $_list = $this->books_model->getMagazineLists($options, $offset, $pageSize, $aSort, $totalCount);
        $list = [];
        foreach ($_list as $key => $val) {
            if ($val['url_id'] != $magazineId) {
                $list[] = [
                    'title' => $val['column_title'],
                    'cover' => STATIC_URL2 . '/' . ltrim($val['magazine_cover'], '/'),
                    'link' => "/books/innermaga/id_{$val['url_id']}/"
                ];
            }
        }
        return array_slice($list, 0, 4);
    }

    //反扒参数验证
    private function _verifyParamKeys($s_p)
    {

        $user_info = get_cookie_value();

        if ($s_p) {//加密后的请求参数
            $paramsArr = $this->decrypt_param($s_p);
        }

        //验证参数
        $this->load->model('Secret_model');
        $timeStamp = $paramsArr['timeStamp'];//生成aes-key的时间戳
        $SIGN = $paramsArr['sign'];//AES-KEY

        if (!empty($user_info['id'])) {
            $parent_id = $user_info['id'];
            $child_id = $user_info['sChildID'];

            //解密失败
            if ($s_p && !$paramsArr) {
                //加入队列
                $this->push_to_mq($parent_id, $child_id, time());
                echo json_encode(['code' => 1, 'msg' => '请刷新页面', 'data' => []]);
                return false;
            }

            $token = $this->input->post('token', true);//TOKEN
            $token_info = explode('_', $token);
            $user_id = count($token_info) == 3 ? $token_info[0] : getUserId();
            if (is_numeric($user_id)) {
                $parent_id = $user_id;
                $user_id = '';
            }

            //校验token
            $flag = $this->Secret_model->check_token($token);
            if (!$token || !$flag) {//未登陆 或 token验证失败
                // 验证失败 加入队列
                $this->push_to_mq($parent_id, $user_id, time());
                echo json_encode(['code' => 1, 'msg' => '请刷新页面', 'data' => []]);
                return false;
            }

            //校验AES_KEY
            $AES_KEY = $this->Secret_model->get_symmetric_key($timeStamp);
            if ($SIGN == $AES_KEY) {
                return true;
            } else {
                // 验证失败 加入队列
                $this->push_to_mq($parent_id, $user_id, time());
                return false;
            }

        } else {//游客不验证token

            if ($s_p && !$paramsArr) {
                echo json_encode(['code' => 1, 'msg' => '请刷新页面', 'data' => []]);
                return false;
            }

            //校验AES_KEY
            $AES_KEY = $this->Secret_model->get_symmetric_key($timeStamp);
            if ($SIGN == $AES_KEY) {
                return true;
            } else {
                return false;
            }
        }

    }

    //获取免费试读报告
    private function _getFreeReport()
    {
        $free_report_id = $this->details_model->query("select iTopicId from t_trend_report where iFreeReport=? limit 3", [1]);

        return is_array($free_report_id) && !empty($free_report_id) ? array_column($free_report_id, 'iTopicId') : [];
    }

}
