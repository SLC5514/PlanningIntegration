<?php
/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/3 13:57
 */

class PatternLibrary_model extends POP_Model
{
    const POP_MEMCACHE_PREF = "FM_CLOUD_M_PatternLibrary_";   // memcache前缀
    //条件简写与Solr字段对应关系 s 代表查询字段 g 代表 Group字段
    public $condSolrArr = [
        "gen" => ["name" => "性别", "s" => "aLabelIds", "g" => "sGender"],              //性别
        "con" => ["name" => "图案内容", "s" => "aLabelIds", "g" => "sPatternContent"],      //图案内容
        "use" => ["name" => "局部/满身", "s" => "aPatternUse", "g" => "sPatternUse"],         //图案应用
        "for" => ["name" => "矢量/位图", "s" => "aPatternFormat", "g" => "sPatternFormat"],   //图案格式
        "aco" => ["name" => "色系", "s" => "aAutoColors", "g" => ["iFirstColorFirstLevel", "iFirstColorSecondLevel"]],    //色系(特殊查询字段有两个分一二级)
        "bra" => ["name" => "大牌图案", "s" => "iBrand"],
        "ibra" => ["name" => "全部品牌"]
    ];
    public $solrCondArr = [
        "sGender" => "gen",
        "sPatternContent" => "con",
        "sPatternUse" => "use",
        "sPatternFormat" => "for",
        "iFirstColorFirstLevel" => "aco",
        "iFirstColorSecondLevel" => "aco",
        "sAssortColor" => "aco",
    ];

    function __construct()
    {
        $this->load->model("Attr_model");
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 获取列表
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getList(&$list, $paramsArr, $offset, $limit,$column=3)
    {
        $this->load->model('Collect_model');

        $keyword = getKeyWord();
        $conditions = $this->getCondition($paramsArr);
        $arSort = ["dCreateTime" => "DESC", "pri_id" => "DESC"];
        $result = array();
        $totalCount = POPSearch::wrapQueryPopFashionMerger($keyword, $conditions, $result, $offset, $limit, $arSort);

        foreach ($result as $key => $val) {
            $row["t"] = getProductTableName($val["tablename"]);
            $row["id"] = $val["pri_id"];
            $row["url"] = $this->getLink();

            // 图案&趋势才有收藏,2020-06-16
            if (in_array($val["tablename"], array('product_fabricgallery_item', 'product_graphicitem'))) {
                $dataName = 'fashion';// 默认库是  服装库
                $row["collect_status"] = $this->Collect_model->getCollectStatus($row["t"], $val["pri_id"], $dataName, true);
            }
            $row["index"] = $offset++;

            $detailInfo = $this->getDetail($val["tablename"], $val["pri_id"], $paramsArr, true);
            //2d试衣栏目无论是否是VIP都用中图模拟
            if($column!=1){
                unset($detailInfo['mbigPath']);
            }
            $list[$key] = array_merge($row, $detailInfo);
        }
        return $totalCount;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 获取详细信息
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getDetail($table, $id, $paramsArr = [], $isList = false)
    {
        $_result = OpPopFashionMerger::getProductData($id, $table);
        $result = $_result[$id];

        $imgPath = getImagePath($table, $result);
        $detailInfo = array();
        if ($isList) {
            // 列表取值
            $detailInfo["cover"] = $imgPath["cover"];
            $detailInfo['mbigPath'] = str_replace('/big/', '/mbig/', $imgPath['bigPath']);
        } else {
            $detailInfo["detail_img"] = [];
            $t = getProductTableName($table);
            if ($imgPath['detailImg']) { //新数据
                foreach ($imgPath['detailImg'] as $key => $_detailpath) {
                    $detailInfo['detail_img'][$key]['smallPath'] = $_detailpath['smallPath'];
                    $detailInfo['detail_img'][$key]['bigPath'] = $_detailpath['bigPath'];
                    $detailInfo['detail_img'][$key]['mbigPath'] = str_replace('/big/', '/mbig/', $_detailpath['bigPath']);
                    $detailInfo['detail_img'][$key]['simpleName'] = $t;
                    $detailInfo['detail_img'][$key]['id'] = $id;
                }
            } else { //老数据
                $detailInfo['detail_img'][0]['smallPath'] = $imgPath['smallPath'];
                $detailInfo['detail_img'][0]['bigPath'] = $imgPath['bigPath'];
                $detailInfo['detail_img'][0]['mbigPath'] = str_replace('/big/', '/mbig/', $imgPath['bigPath']);
                $detailInfo['detail_img'][0]['simpleName'] = $t;
                $detailInfo['detail_img'][0]['id'] = $id;
            }
        }
        //图案内容
        $detailInfo["sPatternContent"] = $this->getNameLink("sPatternContent", $result["sPatternContent"], $paramsArr);
        //图案应用
        $detailInfo["sApplication"] = array_shift($this->getNameLink("sApplication", $result["sApplication"], $paramsArr));
        $detailInfo["sApplication"] = empty($detailInfo["sApplication"]) ? [] : $detailInfo["sApplication"];
        //图案格式
        $detailInfo["sFormat"] = array_shift($this->getNameLink("sFormat", $result["sFormat"], $paramsArr));
        $detailInfo["sFormat"] = empty($detailInfo["sFormat"]) ? [] : $detailInfo["sFormat"];
        //品牌
        if (!empty($result['iBrandId'])) {
            $brandInfo = OpPopFashionMerger::getBrandData($result['iBrandId']);
            $detailInfo["brand"]["name"] = $brandInfo["name"];
            $detailInfo["brand"]["link"] = $this->getLink($paramsArr, "bra", $result['iBrandId']);
        }
        //色系(列表颜色条)
        $colorDetails = isset($result['sColorDetails']) && $result['sColorDetails'] ? json_decode($result['sColorDetails'], true) : [];
        if ($colorDetails) {
            $detailInfo['colorProportion'] = $colorDetails;
        }
        //时间
        $detailInfo["dCreateTime"] = date("Y-m-d", strtotime($result["create_time"]));

        return $detailInfo;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * group出solr存在的选项数据
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getGroupCategory($field, $paramsArr = [], $limit = 150)
    {
        $params = $result = $returns = [];
        //色系
        if (in_array("sAssortColor", $field)) {
            $sColor = $this->getColorAnalysisData($paramsArr);
            foreach ($sColor as $color) {
                $sAssortColor[$color['id']] = [
                    'sName' => $color['name'],
                    'link' => $this->getLink($paramsArr, 'aco', $color['id']),
                    'sAlias' => $color['itemStyle']['normal']['color'],
                ];
            }
            $returns["sAssortColor"] = $sAssortColor;
            $tmpKey = array_search('sAssortColor', $field);
            unset($field["{$tmpKey}"]);
        }
        //先取图案内容不联动group
        if (in_array('sPatternContent', $field)) {
            $tmpKey = array_search('sPatternContent', $field);
            $this->getGroupPatternContent($returns, $paramsArr);
            unset($field["{$tmpKey}"]);
        }
        //分组信息
        $params['group'] = 'true';
        $params['group.limit'] = 0;
        $params['group.ngroups'] = 'true';
        $params['group.field'] = $field;
        //排序信息
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        //条件信息
        $condition = $this->getCondition($paramsArr);
        //group其他值
        $memKeyField = [];
        foreach ($paramsArr as $key => $val) {
            if (isset($this->condSolrArr[$key]) && $key != "con" && $key != "aco") {
                $memKeyField[$key] = $val;
            }
        }
        ksort($memKeyField);
        sort($field);
        $memcacheKey = self::POP_MEMCACHE_PREF . "getGroupCategory_" . md5(serialize($memKeyField) . implode("_", $field));
        $this->load->driver('cache');
        $result = $this->cache->memcached->get($memcacheKey);
        if (empty($result) || $this->input->get("refresh")) {
            POPSearch::wrapQueryPopFashionMerger('', $condition, $result, 0, $limit, $arSort, $params);
            $this->cache->memcached->save($memcacheKey, $result, 3600);
        }
        $this->getDealGroupData($result, $returns, $paramsArr);
        return $returns;
    }

    //处理group信息
    private function getDealGroupData($result, &$returns, $paramsArr)
    {
        if (!is_array($result)) {
            return false;
        }
        foreach ($result as $field => $value) {
            $groupValues = $_return = [];
            foreach ($value["groups"] as $val) {
                if (!empty($val["groupValue"])) {
                    $groupValue = explode(",", $val["groupValue"]);
                    $groupValues = array_merge($groupValue, $groupValues);
                }
            }
            $groupValues = array_unique($groupValues);
            $groupValues = array_filter($groupValues);

            $data = $this->Attr_model->$field;
            $data = empty($data) ? [] : $data;
            foreach ($data as $id => $val) {
                if (in_array($id, $groupValues)) {
                    $type = $this->solrCondArr[$field];
                    $_return[$id] = ["name" => $val, "link" => $this->getLink($paramsArr, $type, $id)];
                }
            }
            $returns[$field] = $_return;
        }
        return $returns;
    }

    //获取图案内容，图案内容不需要group
    private function getGroupPatternContent(&$returns, $paramsArr)
    {
        $sPatternContents = GetCategory::getSomeAttr(25, '', false);
        $reslut = [];
        foreach ($sPatternContents as $val) {
            $reslut[$val['iAttributeId']] = ['sName' => $val['sName'], "link" => $this->getLink($paramsArr, "con", $val['iAttributeId'])];
            foreach ($val['attrs'] as $v) {
                if (strtotime($v['dLastUpdateTime']) > strtotime('-1 year') && $v['iDisplay'] == 1) {
                    $reslut[$val['iAttributeId']]['attrs'][$v['iAttributeId']] = ['sName' => $v['sName'], "link" => $this->getLink($paramsArr, "con", $v['iAttributeId'])];
                }
            }
            if (strtotime($val['dLastUpdateTime']) < strtotime('-1 year') && empty($reslut[$val['iAttributeId']]['attrs'])) {
                unset($reslut[$val['iAttributeId']]);
            }
        }
        $returns['sPatternContent'] = $reslut;
        return $reslut;
    }


    /*------------------------------------------------------------------------------------------------------------------
    * 获取饼图
    *-----------------------------------------------------------------------------------------------------------------*/
    public function pieData($paramsArr)
    {
        //返回按钮处理
        $colorBack = $categoryBack = '';
        $tips = $this->getSelected($paramsArr);
        if (isset($paramsArr['aco']) && !empty($paramsArr['aco'])) {
            $colorBack = $tips['aco']['backLink'];
        }
        if (isset($paramsArr['con']) && !empty($paramsArr['con'])) {
            $categoryBack = $tips['con']['backLink'];
        }
        //获取条件和关键字
        $dataAnalysis = $this->getColorAnalysisData($paramsArr);
        $dataPatternContent = $this->getPatternContentPieData($paramsArr);

        return ['color' => $dataAnalysis, 'patternContent' => $dataPatternContent,
            'colorBack' => $colorBack, 'patternContentBack' => $categoryBack];
    }

    private function getColorAnalysisData($paramsArr)
    {
        $groups = [];
        $groups['group'] = 'true';
        $groups['group.ngroups'] = 'true';
        $conditions = $this->getCondition($paramsArr);
        $colors = $this->Attr_model->getColorDict($paramsArr["aco"]);
        if (!empty($colors["id"])) {
            if ($colors["iPid"] > 0) {
                $conditions["iFirstColorSecondLevel"] = $conditions["aAutoColors"]; //色盘取二级色用此条件
            } else {
                $conditions["iFirstColorFirstLevel"] = $conditions["aAutoColors"]; //色盘取二级色用此条件
            }
            unset($conditions["aAutoColors"]);
            $groups['group.field'] = 'iFirstColorSecondLevel';
        } else {
            $groups['group.field'] = 'iFirstColorFirstLevel';
        }
        $arSort = $resAnalysis = [];
        $conditions['other'][] = "-(iFirstColorSecondLevel:149)"; //去珍珠白
        $keyword = getKeyWord();
        //色彩分析
        POPSearch::wrapQueryPopFashionMerger($keyword, $conditions, $resAnalysis, 0, 30, $arSort, $groups);
        $resAnalysis = POPSearch::getGroupData($resAnalysis);
        $colors = $this->Attr_model->getColorDict();
        $dataAnalysis = [];
        foreach ($resAnalysis[$groups['group.field']]['groups'] as $colorid => $count) {
            if (isset($colors[$colorid])) {
                $tmp = [];
                $tmp['id'] = $colorid;
                $tmp['name'] = $colors[$colorid]['sName'];
                $tmp['sColorNumber'] = $colors[$colorid]['sColorNumber'];
                $tmp['value'] = $count;
                $tmp['itemStyle']['normal']['color'] = $colors[$colorid]['sColor'];
                //$tmp['label']['normal']['textStyle']['color'] = "#000";
                //$tmp['labelLine']['normal']['lineStyle']['color'] = "#000";
                $tmp['iSort'] = $colors[$colorid]['iSort'];
                $dataAnalysis[] = $tmp;
            }
        }
        if (!empty($dataAnalysis)) {
            $dataAnalysis = twoDimensionSort($dataAnalysis, 'iSort');
            $dataAnalysis = array_values($dataAnalysis);
        }
        return $dataAnalysis;
    }

    private function getPatternContentPieData($paramsArr)
    {
        $return = [];
        $allContents = GetCategory::getSomeAttr(25, '', false);
        $conditions = $this->getCondition($paramsArr);
        $arSort = array('dCreateTime' => 'DESC');
        unset($conditions['dCreateTime']);
        if (empty($paramsArr['con'])) {
            $Contents = $allContents;
        } else {
            $Contents = array();
            foreach ($allContents as $val) {
                if ($val['iAttributeId'] == $paramsArr['con']) {
                    $Contents = $val['attrs'];
                }
            }
            if (empty($Contents)) {
                $sName = GetCategory::getOtherFromIds($paramsArr['con'], ['sName']);
                $Contents = [['iDisplay' => 1, 'iAttributeId' => $paramsArr['con'], 'sName' => trim($sName), 'dLastUpdateTime' => date('Y-m-d')]];
            }
        }
        foreach ($Contents as $val) {
            if ($val['iDisplay'] == 1 && strtotime($val['dLastUpdateTime']) > strtotime('-1 year')) {
                $_conditions = $conditions;
                $_conditions['other'][] = 'aLabelIds:' . $val['iAttributeId'];
                $keyword = getKeyWord();
                $count = POPSearch::wrapQueryPopFashionMerger($keyword, $_conditions, $res, 0, 1, $arSort);
                if ($count > 0) {
                    $return[] = ['id' => $val['iAttributeId'], 'name' => $val['sName'], 'value' => $count];
                }
            }
        }
        return $return;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 获取已选中
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getSelected($paramsArr, $isSimple = false)
    {
        $selected = [];
        $key = getKeyWord();
        if (!empty($key)) {
            $selected["key"] = ["name" => "关键字", "value" => htmlspecialchars($key), "link" => $this->getLink($paramsArr, "key", "")];
        }
        foreach ($paramsArr as $k => $val) {
            $other = [];
            switch ($k) {
                case "use":
                    $value = $this->Attr_model->sPatternUse[$val];
                    break;
                case "for":
                    $value = $this->Attr_model->sPatternFormat[$val];
                    break;
                case "aco":
                    $_temp = $this->Attr_model->getColorDict($val, "sAssortColor");
                    $pid = !empty($_temp["Pid"]) ? $_temp["Pid"] : "";
                    $other["color"] = $_temp["sAlias"]; //color色码
                    $other["backLink"] = $this->getLink($paramsArr, $k, $pid); //色盘需要返回上一步
                    $value = $_temp["sName"];
                    break;
                case "bra":
                    $brandInfo = OpPopFashionMerger::getBrandData($val);
                    $value = $brandInfo["en_cn_brand"];
                    break;
                case "ibra":
                    $value = "全部品牌";
                    break;
                case "con":
                    $pid = (int)GetCategory::getOtherFromIds($val, ['iAttributePid']);
                    $pid = (!empty($pid) && $pid != 11698) ? $pid : "";
                    $other["backLink"] = $this->getLink($paramsArr, $k, $pid);
                default:
                    $value = $this->Attr_model->Attribute[$val];
            }
            if (!empty($value) && $k != 'page') {
                //PS：backLink为饼图中心返回按钮使用
                if ($isSimple) {
                    $selected[$k] = $value;
                } else {
                    $selected[$k] = ["name" => $this->condSolrArr[$k]["name"], "value" => $value, "cancelLink" => $this->getLink($paramsArr, $k, "")];
                    $selected[$k] = array_merge($selected[$k], $other);
                }
            }
        }
        return $selected;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 通过数据 中个属性的id获取其中文和列表链接
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getNameLink($key, $value, $paramsArr = [])
    {
        $return = [];
        switch ($key) {
            case "sPatternContent":
                $data = $this->Attr_model->Attribute;
                $type = "con";
                break;
            case "sApplication":
                $data = $this->Attr_model->sPatternUse;
                $type = "use";
                break;
            case "sFormat":
                $data = $this->Attr_model->sPatternFormat;
                $type = "for";
                break;
            default:
                $data = [];
                $type = "";
        }
        if (!empty($value)) {
            $array = is_array($value) ? $value : explode(",", $value);
            foreach ($array as $key => $value) {
                if (isset($data[$value])) {
                    $return[$key] = [
                        "name" => isset($data[$value]) ? $data[$value] : "",
                        "link" => $this->getLink($paramsArr, $type, $value)
                    ];
                }
            }
        }
        return $return;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 获取路径
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getLink($paramsArr = [], $type = '', $value = '')
    {
        $listRootLink = "/patternlibrary/"; //列表跟路径
        $get = getKeyWord() ? ["key" => getKeyWord()] : [];
        if ($type == "key") {
            //get中的参数 key
            if (empty($value) && isset($get["key"])) {
                unset($get["key"]);
            } else {
                $get["key"] = urlencode($value);
            }
        } else {
            //url中的参数
            if (isset($paramsArr['page'])) {
                unset($paramsArr['page']);
            }
            if (!empty($type) && empty($value) && isset($paramsArr[$type])) {
                unset($paramsArr[$type]);
            } elseif (!empty($type) && !empty($value)) {
                $paramsArr[$type] = $value;
            }
        }
        $params = empty($paramsArr) ? "" : (encodeParams($paramsArr) . "/");
        $get = empty($get) ? "" : ("?" . http_build_query($get));
        return $listRootLink . $params . $get;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 条件拼接（除key外）
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getCondition($paramsArr)
    {
        $conditions = [];
        $conditions['dCreateTime'] = '[* TO ' . date("Y-m-d\TH:i:s\Z", strtotime("-2 hour")) . ']'; //取两个小时前的数据
        $conditions['iColumnId'] = !empty($paramsArr["ibra"]) ? 120 : [82, 120]; //只是大牌时

        foreach ($paramsArr as $key => $value) {
            if (isset($this->condSolrArr[$key]) && $key != "ibra") {
                $solrField = $this->condSolrArr[$key]["s"];
                if ($solrField == "aLabelIds") {
                    $conditions["other"][] = "({$solrField}:{$value})";
                } else {
                    $conditions[$solrField] = $value;
                }
            }
        }
        return $conditions;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * 获取素材类下载信息
    *-----------------------------------------------------------------------------------------------------------------*/
    public function getMaterialDown($data, $tablename)
    {
        $basename = pathinfo($data['small_image_name'], PATHINFO_FILENAME);
        $sApppath = rtrim(APPPATH, '/');
        $fileArr = [];
        if ($tablename == 'product_graphicitem') {
            $geshiArr = ['eps', 'ai', 'cdr', 'psd'];
            if ($data['sPathDetails']) {
                $sPathDetails = json_decode($data['sPathDetails'], true);
                $key = 0;
                foreach ($sPathDetails as $_smallName => $_bigName) {
                    $basename = pathinfo($_smallName, PATHINFO_FILENAME);
                    $fileArr[$key]['sSmallName'] = $_smallName;
                    foreach ($geshiArr as $g) {
                        $file = $sApppath . $data['sPath'] . $g . '/' . $basename . '.' . $g;
                        $fileUpper = $sApppath . $data['sPath'] . $g . '/' . $basename . '.' . strtoupper($g);
                        if (api_file_exists($file)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                            //图片大小
                            $imageSize = api_getPicSize($file);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                        if (api_file_exists($fileUpper)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                            //图片大小
                            $imageSize = api_getPicSize($fileUpper);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                    }
                    //jpg格式
                    if (api_file_exists($sApppath . $data['sPath'] . 'hd/' . $_bigName)) {    //高清图存在则使用高清图
                        $sBigPath = $sApppath . $data['sPath'] . 'hd/' . $_bigName;
                    } else {
                        $sBigPath = $sApppath . $data['sPath'] . 'big/' . $_bigName;
                    }
                    $_ext = strtolower(pathinfo($_bigName, PATHINFO_EXTENSION));
                    //图片大小
                    $imageSize = api_getPicSize($sBigPath);
                    $fileArr[$key]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                    $fileArr[$key]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    //图片路径
                    $fileArr[$key]['aDownInfo'][$_ext]['bp'] = urlencode(str_replace($sApppath, '', $sBigPath));
                    $key++;
                }
            }
        } elseif ($tablename == 'product_fabricgallery_item') {
            $detail_img = $this->getFabricgalleryDetailImage('product_fabricgallery_item_detail', $data['related_flag'], $data['id'], 82);
            $geshiArr = ['eps', 'ai', 'cdr', 'psd', 'bmp'];
            if ($detail_img) {
                //细节图存在
                foreach ($detail_img as $key => $_img) {
                    $afileInfo = pathinfo($_img['smallPath']);
                    $basename = $afileInfo['filename'];
                    $fileArr[$key]['sSmallName'] = $afileInfo['basename'];//小图名称
                    $sBigName = pathinfo($_img['bigPath'], PATHINFO_BASENAME);
                    foreach ($geshiArr as $g) {
                        $file = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . $g;
                        $fileUpper = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . strtoupper($g);
                        if (api_file_exists($file)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                            //图片大小
                            $imageSize = api_getPicSize($file);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                        if (api_file_exists($fileUpper)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                            //图片大小
                            $imageSize = api_getPicSize($fileUpper);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                    }
                    //图片大小
                    $sBigPath = '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/big/' . $sBigName;
                    $imageSize = api_getPicSize($sApppath . $sBigPath);
                    $_ext = strtolower(pathinfo($sBigName, PATHINFO_EXTENSION));
                    $fileArr[$key]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                    $fileArr[$key]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    //图片路径
                    $fileArr[$key]['aDownInfo'][$_ext]['bp'] = urlencode($sBigPath);
                }
            } else {
                //只有主图
                $fileArr[0]['sSmallName'] = $data['small_image_name'];
                foreach ($geshiArr as $g) {
                    $file = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . $g;
                    $fileUpper = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . strtoupper($g);
                    if (api_file_exists($file)) {
                        //图片路径
                        $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                        //图片大小
                        $imageSize = api_getPicSize($file);
                        $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                        $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                    }
                    if (api_file_exists($fileUpper)) {
                        //图片路径
                        $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                        //图片大小
                        $imageSize = api_getPicSize($fileUpper);
                        $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                        $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                    }
                }
                $path = getImagePath($tablename, $data, $sApppath);
                //图片大小
                $imageSize = api_getPicSize($path['bigPath']);
                $_ext = strtolower(pathinfo($path['bigPath'], PATHINFO_EXTENSION));
                $fileArr[0]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                $fileArr[0]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                //图片路径
                $fileArr[0]['aDownInfo'][$_ext]['bp'] = urlencode(str_replace($sApppath, '', $path['bigPath']));
            }
        }
        return $fileArr;
    }

    // 获取面料图库细节图
    public function getFabricgalleryDetailImage($tablename, $related_flag, $id, $columnid)
    {
        $sql = "SELECT * FROM $tablename WHERE related_flag=? order by id asc";
        $result = $this->query($sql, [$related_flag]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        return $img;
    }
}