<?php
/*@todo 色彩识别(色彩分析)
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Coloranalysis extends POP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pop_model');
    }

    public function index()
    {
        $path = $this->input->get('path', true);
        checkTrailInfo($path, 'coloranalysis:images');

        $T = "色彩分析_款式-POP服装趋势网";
        $D = "POP服装趋势网款式的色彩分析栏目为您提供最新、最前沿的色彩分析图片资讯，为您及时提供与色彩分析相关的服装款式、色彩、面料、印花图案等方面资讯，为您提供有价值的资讯服务。";
        $K = "色彩分析,颜色分析";
        $this->assign("title", $T);
        $this->assign("description", $D);
        $this->assign("keywords", $K);
        $this->display("color-analysis.html");
    }

    public function search()
    {
        $table = getProductTableName($this->input->get_post("t"));
        $id = (int)$this->input->get_post("id");
        $imgUrl = $this->input->get_post("imgUrl");
        if (empty($table) && empty($id) && empty($imgUrl)) {
            echo json_encode(["code" => 1, "message" => "参数不能为空"]);
            exit;
        }
        //$imgUrl= 'https://imgf1.pop-fashion.com/fashion/perform/14102102/women/London/2015SS/Dress/Short-sleeved_Dress/20141020135751/big/c506051847ce566e99d720eda573a885.jpg';
        $ColorDetail = $this->getColorDetail($imgUrl, $table, $id);
        if (empty($ColorDetail)) {
            echo json_encode(["code" => 1, "message" => "色彩未被识别"]);
            exit;
        }
        $diffNum = 8;
        $SolrColor = $pieDatas = [];
        if (is_array($ColorDetail)) {
            foreach ($ColorDetail as $val) {
                $pieData = [
                    'id' => $val["FirstLevel"],
                    'name' => $val["pantonColorNumber"],
                    'sColorNumber' => $val["Color"],
                    'value' => $val["Percent"],
                ];
                $pieDatas[] = $pieData;

                if ($val["Percent"] >= 30) {
                    $startNum = $val["Percent"] - $diffNum;
                    $endNum = $val["Percent"] + $diffNum;
                    $tmp = "((iFirstColorSecondLevel:{$val['SecondLevel']} AND iFirstProp:[ $startNum TO $endNum])";
                    $tmp .= " OR (iSecondColorSecondLevel:{$val['SecondLevel']} AND iSecondProp:[ $startNum TO $endNum])";
                    $tmp .= " OR (iThirdColorSecondLevel:{$val['SecondLevel']} AND iThirdProp:[ $startNum TO $endNum]))";
                    $SolrColor[] = $tmp;
                }
            }
        }
        $condition["iColumnId"] = 50;
        $condition["other"] = implode(" AND ", $SolrColor);
        $arSort = array("dCreateTime" => "DESC", "pri_id" => "DESC");
        $fls['fl'] = ['pop_id', 'pri_id', 'tablename', 'iColumnId'];
        $startTime = microtime(true);
        POPSearch::wrapQueryPopFashionMerger('', $condition, $result, 0, 300, $arSort, $fls);
        $list = $_Categorys = [];
        if (empty($result)) {
            $usetime = microtime(true) - $startTime;
            $usetime = round($usetime, 2);
            $result = array(
                "code" => 0,
                "data" => $list,
                "info" => ["pieDatas" => $pieDatas, "usetime" => $usetime, "Categorys" => $_Categorys, "count" => 0],
                "message" => "OK"
            );
            echo json_encode($result);
            exit;
        }

        foreach ($result as $key => $val) {
            $table = $val["tablename"];
            $id = $val["pri_id"];
            $group[$table][] = $id;
        }
        $count = 0;
        foreach ($group as $table => $ids) {
            $rows = OpPopFashionMerger::getProductData($ids, $table);
            $otherAttr = $this->getOtherAttr($table, $ids);
            $t = getProductTableName($table);
            foreach ($rows as $id => $row) {
                $row = array_merge($row, $otherAttr["{$table}_{$id}"]);
                $imgPath = getImagePath($table, $row);
                $info["id"] = $id;
                $info["t"] = $t;
                $info["cover"] = getFixedImgPath($imgPath['cover']);
                $info["colorProportion"] = json_decode($row["sColorDetails"], true);
                $info['iCategory'] = $row["iCategory"];
                $_Categorys[] = $info['iCategory'];
                $labels = $otherIds = [];
                $labels[] = $row["for_date_text"];
                $labels[] = $row["category_text"];
                $brand = OpPopFashionMerger::getBrandData($row["brand_tid"]);
                $labels[] = $brand["name"];
                $otherIds = [
                    $row["iCategory"],
                    $row["iSubcategory"],
                    $row["sManner"],
                    $row["sAgeLayer"],
                    $row["sShape"],
                    $row["sSpecifics"],
                    $row["sTechnology"],
                    $row["sPattern"],
                    $row["sFabric"],
                    $row["sAccessory"],
                ];
                $otherIds = array_filter($otherIds);
                $otherLabel = GetCategory::getOtherFromIds($otherIds, ["sName"], "array");
                $labels = array_merge($labels, $otherLabel["sName"]);
                $labels = array_filter($labels);
                $info["labels"] = $labels;
                $count++;
                $list[] = $info;
            }
        }
        $_Categorys = array_unique($_Categorys);
        $Categorys = GetCategory::getSingle();
        foreach ($Categorys as $key => $val) {
            if (!in_array($key, $_Categorys)) {
                unset($Categorys[$key]);
            }
        }
        $usetime = microtime(true) - $startTime;
        $usetime = round($usetime, 2);
        $result = array(
            "code" => 0,
            "data" => $list,
            "info" => ["pieDatas" => $pieDatas, "usetime" => $usetime, "Categorys" => $Categorys, "count" => $count],
            "message" => "OK"
        );
        echo json_encode($result);
    }

    public function getColorDetail($imgUrl = "", $table = "", $id = "", $col = 50)
    {
        if (!empty($table) && !empty($id)) {
            //来自详情
            $res = OpPopFashionMerger::getProductData($id, $table);
            return json_decode($res[$id]["sColorDetails"], true);
        } elseif (!empty($imgUrl) && ($table == "fm_upload_pic_material" || empty($table))) {
            //无表名和ID，来自用户上传
            return $this->InterfaceColorDetails($imgUrl, $col);
        }
    }

    private function getOtherAttr($table, $ids)
    {
        if (!is_array($ids)) {
            $ids = explode(",", $ids);
        }
        $return = $sqlIds = array();
        foreach ($ids as $id) {
            $popid = $table . '_' . $id;
            $sMemcacheKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_manner_element_new_' . $popid;
            $row = $this->cache->memcached->get($sMemcacheKey);
            if (!empty($row)) {
                $return[$popid] = $row;
            } else {
                $return[$popid] = array();
                $sqlIds[] = $id;
            }
        }
        if (is_array($sqlIds) && !empty($sqlIds)) {
            $sql = "SELECT `sTableName`,`iPriId`,`id`, `sManner`, `sAgeLayer`, `sShape`, `sSpecifics`, `sTechnology`, `sPattern`, `sFabric`, `sAccessory`, `dUpdateTime` FROM " . OpPopFashionMerger::POP_Table_Name_T_Manner_Element . " WHERE `sTableName` = '" . $table . "' AND `iPriId` IN (" . implode(",", $sqlIds) . ")";
            $result = $this->pop_model->query($sql);
            foreach ($result as $row) {
                $popid = $row["sTableName"] . "_" . $row["iPriId"];
                $return[$popid] = $row;
                $sMemcacheKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_manner_element_new_' . $popid;
                $this->cache->memcached->save($sMemcacheKey, $row, 3600);
            }
        }
        return $return;
    }

    //通过码隆接口获取图片颜色占比并处理，与接口一致
    private function InterfaceColorDetails($imgUrl, $col = 50)
    {
        // 50--秀场提炼要先扣图，在ColorThief
        if ($col == 50) {
            $imgUrl = OpPopColorLabelColorThiefRecognition::getOssImg($imgUrl);
            if (empty($imgUrl)) {
                return array();
            }
        }
        $use_scene = $col == 82 ? 'color_73' : 'color_74';
        // $aColorMatchResult = OpPopMalongInterface::getAutoMatchResult($use_scene, $imgUrl);
        $aColorMatchResult = OpPopColorLabelColorThiefRecognition::getColorThiefData($imgUrl);
        if (isset($aColorMatchResult['is_err']) && $aColorMatchResult['is_err'] == 0) {
            $aColorInfoDetail = array_values($aColorMatchResult['results']);
            //按百分比降序
            $_aPercentage = array();
            if ($aColorInfoDetail) {
                foreach ($aColorInfoDetail as $_key => $_row) {
                    $_aPercentage [$_key] = $_row['freq'];
                    $aColorInfoDetail[$_key]['sRGB'] = '';
                    $aColorInfoDetail[$_key]['aLevel'] = array(); //颜色分级
                    if ($_row['rgb'] && is_array($_row['rgb'])) {
                        $pantonColor = OpPopColorLabelRecognition::getColorLevel($_row['rgb']);
                        if (empty($pantonColor)) {
                            $pantonColor['id'] = $pantonColor['iSecondLevel'] = $pantonColor['iFirstLevel'] = 0;
                            $pantonColor['sColorNumber'] = 0;
                        }
                        $aColorInfoDetail[$_key]['sRGB'] = implode(',', $_row['rgb']);
                        $aColorInfoDetail[$_key]['aLevel'] = array($pantonColor['iSecondLevel'], $pantonColor['iFirstLevel']);
                        $aColorInfoDetail[$_key]['pantonId'] = $pantonColor['id'];
                        $aColorInfoDetail[$_key]['pantonColorNumber'] = $pantonColor['sColorNumber'];
                    }
                }
                array_multisort($_aPercentage, SORT_DESC, $aColorInfoDetail);
            }

            //匹配信息数据占比处理
            $iMatchingNum = sizeof($aColorInfoDetail);
            if ($iMatchingNum > 5) {
                foreach ($aColorInfoDetail as $_k => $_r) {
                    $aColorInfoDetail[$_k]['freq'] = sprintf("%.2f", $_r['freq']) * 100;
                }
            } else {
                $iT = 0;
                foreach ($aColorInfoDetail as $_k => $_r) {
                    if ($_k == $iMatchingNum - 1) {
                        $aColorInfoDetail[$_k]['freq'] = 100 - $iT;
                    } else {
                        $_proportion = sprintf("%.2f", $_r['freq']) * 100;
                        $aColorInfoDetail[$_k]['freq'] = $_proportion;
                        $iT += $_proportion;
                    }
                }
            }
            $aColorDetails = array();
            foreach ($aColorInfoDetail as $_n => $_color) {
                $aRGB = explode(',', $_color['sRGB']);
                $aColorDetails[$_n] = array(
                    'FirstLevel' => intval($_color['aLevel'][1]),        //一级
                    'SecondLevel' => intval($_color['aLevel'][0]),        //二级
                    'pantonId' => intval($_color['pantonId']),
                    'pantonColorNumber' => $_color['pantonColorNumber'],
                    'Color' => OpPopColorLabelRecognition::rgbToColor($aRGB),        //颜色
                    'RGB' => $aRGB,    //RGB
                    'Percent' => $_color['freq']        //占比
                );
            }
        }
        return $aColorDetails;
    }
}

