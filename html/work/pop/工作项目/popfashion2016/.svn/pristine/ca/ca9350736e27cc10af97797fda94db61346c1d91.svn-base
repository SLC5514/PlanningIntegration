<?php
/*@todo 色彩趋势
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Colortrends extends POP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Colortrends_model');
        $this->load->model('Common_model');
        $this->columnRootPath = $this->Common_model->columnRootPath(50);
    }

    private function checkPower()
    {
        $power = memberPower('other');
        if (!in_array($power['P_UserType'], [1, 2, 3])) {
            $result = array(
                'msg' => '对不起，您无此模块权限，请联系客服！',
                'code' => '1001',
            );
            echo json_encode($result);
            exit;
        }
    }

    //获取分析数据
    public function getColorData($params = '')
    {
        $season_id = $this->input->get_post('season');
        if (empty($season_id)) {
            $result = array(
                'msg' => '季节参数不能为空',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $paramsArr = $this->Common_model->parseParams($params, 1);
        $condition = $this->getCondition($params);
        $fields[] = !empty($_REQUEST['category']) ? 'sSubCategory' : 'sCategory';
        $fields[] = !empty($_REQUEST['color']) ? 'iFirstColorSecondLevel' : 'iFirstColorFirstLevel';
        $result = $this->Colortrends_model->getCountPercentByFacet($condition, $fields);

        //款式列表
        $data['list'] = $this->Colortrends_model->getList($condition, 28);

        //色彩饼图与单品
        $data['category_total'] = (int)$result['category']['total'];
        $data['color_total'] = (int)$result['color']['total'];
        $data['color'] = is_array($result['color']['items']) ? array_values($result['color']['items']) : [];
        $data['colour_percent'] = [
            'colour' => isset($result['color']['percent_colour']) ? $result['color']['percent_colour'] : 0,
            'no_colour' => isset($result['color']['percent_no_colour']) ? $result['color']['percent_no_colour'] : 0
        ];
        $data['category'] = is_array($result['category']['items']) ? array_values($result['category']['items']) : [];

        //------------------------------------------------趋势解读start------------------------------------------------
        $season_name = $this->Colortrends_model->getSeasonById($season_id);
        $region_name = $this->Colortrends_model->regions[intval($paramsArr['reg'])]['name'];
        $gender_name = $this->Colortrends_model->genders[intval($paramsArr['gen'])]['name'];
        if ($gender_name == '全部') {
            $gender_name = '全部性别';
        }
        //趋势解读--获取上一年的数据做对比
        $yearsGroupBySeason = $this->Colortrends_model->getYearsGroupBySeason($this->Colortrends_model->getSeasonById($season_id, 'season'));
        $pre_season_item = $this->getNextSeason($yearsGroupBySeason, $season_id);
        $pre_season_id = $pre_season_item['season_id']; //上一年的id
        $pre_season_name = $pre_season_item['year'] . $pre_season_item['season'];//上一年的季节

        //获取当前条件下，上一年的百分比信息
        $pre_condition = $condition;
        $pre_condition['iSeason'] = $pre_season_id;
        $pre_result = $this->Colortrends_model->getCountPercentByFacet($pre_condition, $fields);

        $interpret_type = 'all';
        if (!empty($_REQUEST['color']) && !empty($_REQUEST['category'])) {
            $interpret_type = $_REQUEST['lastclick'] == 'category' ? 'category' : 'color';
        } elseif (!empty($_REQUEST['color'])) {
            $interpret_type = 'color';
        } elseif (!empty($_REQUEST['category'])) {
            $interpret_type = 'category';
        }
        switch ($interpret_type) {
            case 'color':
                $color_id = $_REQUEST['color'];
                $color_name = $this->Colortrends_model->getColorById($color_id);
                $color_pid = $this->Colortrends_model->getColorById($color_id, 'iPid');
                if ($color_pid == 0) {
                    unset($condition['aAutoColors']);
                    unset($pre_condition['aAutoColors']);
                    $field = 'iFirstColorFirstLevel';
                } else {
                    $condition['aAutoColors'] = $pre_condition['aAutoColors'] = $color_pid;
                    $field = 'iFirstColorSecondLevel';
                }
                //获取两年的一级色的百分比
                $first_color_result = $this->Colortrends_model->getCountPercentByFacet($condition, [$field]);
                $pre_first_color_result = $this->Colortrends_model->getCountPercentByFacet($pre_condition, [$field]);

                $first_color_percent = (float)$first_color_result['color']['items'][$color_id]['percent'];//一级色占比
                $pre_first_color_percent = (float)$pre_first_color_result['color']['items'][$color_id]['percent'];//上一个季节一级色占比
                if ($first_color_percent - $pre_first_color_percent >= 3) {
                    $first_color_trend = '增长';
                } elseif ($first_color_percent - $pre_first_color_percent <= -3) {
                    $first_color_trend = '下降';
                } else {
                    $first_color_trend = '平稳';
                }
                $data['interpret'] = "{$pre_season_name}{$region_name}{$gender_name}中，{$color_name}整体占比为（{$pre_first_color_percent}%），而在{$season_name}{$region_name}中，{$color_name}整体占比为（{$first_color_percent}%）整体呈现{$first_color_trend}趋势。";
                if (count($result['color']['items']) > 3) {
                    $max_color = $min_color = array();
                    $max_diff = 0;
                    $min_diff = 100;
                    $color_ids = array_merge(array_keys((array)$result['color']['items']), array_keys((array)$pre_result['color']['items']));
                    foreach ($color_ids as $color_id) {
                        $diff = (float)$result['color']['items'][$color_id]['percent'] - (float)$pre_result['color']['items'][$color_id]['percent'];
                        if ($diff > $max_diff) {
                            $max_diff = $diff;
                            $max_color = $result['color']['items'][$color_id];
                        }
                        if ($diff < $min_diff) {
                            $min_diff = $diff;
                            $min_color = $pre_result['color']['items'][$color_id];
                        }
                    }
                    $temp = array_values(twoDimensionSort($result['color']['items'], 'percent'));
                    $data['interpret'] .= "其中{$temp[0]['name']}为（{$temp[0]['percent']}%）占比最高，依次为{$temp[1]['name']}（{$temp[1]['percent']}%）、{$temp[2]['name']}（{$temp[2]['percent']}%）。相对比{$pre_season_name}，{$max_color['name']}增长趋势（{$max_diff}%）最为突出，{$min_color['name']}下降趋势（{$min_diff}%）最为明显。";
                }
                break;
            case 'category':
                $category_id = $_REQUEST['category'];
                $condition = $this->unsetConditionOtherByVal($condition, $category_id);
                $pre_condition = $this->unsetConditionOtherByVal($pre_condition, $category_id);
                $all_category = GetCategory::getSingle();
                if (isset($all_category[$category_id])) {
                    $field = 'sCategory';
                } else {
                    $field = 'sSubCategory';
                    $category_pid = GetCategory::getCatIdsBySubcatIds([$category_id])[0];
                    $condition['other'][] = "(aLabelIds:$category_pid)";
                    $pre_condition['other'][] = "(aLabelIds:$category_pid)";
                }
                //获取两年的一级色的百分比
                $category_result = $this->Colortrends_model->getCountPercentByFacet($condition, [$field]);
                $pre_category_result = $this->Colortrends_model->getCountPercentByFacet($pre_condition, [$field]);
                $category_percent = (float)$category_result['category']['items'][$category_id]['percent'];//一级色占比
                $category_name = GetCategory::getAttrNameById($category_id);
                $pre_category_percent = (float)$pre_category_result['category']['items'][$category_id]['percent'];//上一个季节一级色占比
                if ($category_percent - $pre_category_percent >= 3) {
                    $category_trend = '增长';
                } elseif ($category_percent - $pre_category_percent <= -3) {
                    $category_trend = '下降';
                } else {
                    $category_trend = '平稳';
                }
                $data['interpret'] = "{$season_name}{$region_name}中，{$gender_name}下，{$category_name}整体占（{$category_percent}%），相较{$pre_season_name}呈{$category_trend}趋势。";
                if (count($result['category']['items']) > 3) {
                    $temp = array_values(twoDimensionSort($result['category']['items'], 'percent'));
                    $data['interpret'] .= "其中{$temp[0]['name']}占比最高（{$temp[0]['percent']}%），依次为{$temp[1]['name']}（{$temp[1]['percent']}%）和{$temp[2]['name']}（{$temp[2]['percent']}%）。";
                }
                break;
            case 'all':
            default:
                $pre_colour_percent = (float)$pre_result['color']['percent_colour'];
                $colour_percent_diff = $data['colour_percent']['colour'] - $pre_colour_percent;
                if ($colour_percent_diff >= 3) {
                    $colour_percent_trend = '增长';
                } elseif ($colour_percent_diff < -3) {
                    $colour_percent_trend = '下降';
                } else {
                    $colour_percent_trend = '平稳';
                }
                $data['interpret'] = "{$season_name}{$region_name}发布会中，{$gender_name}的无彩色系占比（{$data['colour_percent']['no_colour']}%），有彩色系占比（{$data['colour_percent']['colour']}%）。相对于{$pre_season_name}{$region_name}，有彩色系呈{$colour_percent_trend}趋势。";
                if (count($result['color']['items']) > 3) {
                    $max_color = $min_color = array();
                    $max_diff = 0;
                    $min_diff = 100;
                    $color_ids = array_merge(array_keys((array)$result['color']['items']), array_keys((array)$pre_result['color']['items']));
                    foreach ($color_ids as $color_id) {
                        $diff = (float)$result['color']['items'][$color_id]['percent'] - (float)$pre_result['color']['items'][$color_id]['percent'];
                        if ($diff > $max_diff) {
                            $max_diff = $diff;
                            $max_color = $result['color']['items'][$color_id];
                        }
                        if ($diff < $min_diff) {
                            $min_diff = $diff;
                            $min_color = $pre_result['color']['items'][$color_id];
                        }
                    }
                    $temp = twoDimensionSort($result['color']['items'], 'percent');
                    //去除无色系
                    foreach ($temp as $key => $v) {
                        if ($v['colour'] == false) {
                            unset($temp[$key]);
                        }
                    }
                    $temp = array_values($temp);
                    $data['interpret'] .= "在有彩色系中，占比最高的为{$temp[0]['name']}（{$temp[0]['percent']}%），依次为{$temp[1]['name']}（{$temp[1]['percent']}%）和{$temp[2]['name']}（{$temp[2]['percent']}%）。相较于{$pre_season_name}{$region_name}，{$max_color['name']}增长趋势最为突出（{$max_diff}%），{$min_color['name']}下降趋势（{$min_diff}%）最为明显。";
                }
        }


        //------------------------------------------------趋势解读end------------------------------------------------

        $linkArray = $paramsArr;
        $linkArray['sea'] = $_REQUEST['season'];
        $linkArray['aco'] = $_REQUEST['color'];
        $linkArray['cat'] = $_REQUEST['category'];
        $linkArray['dis'] = 1;
        $linkArray = array_filter($linkArray);
        $data['more_link'] = $this->columnRootPath . (!empty($linkArray) ? ($this->Common_model->parseParams($linkArray, 2) . '/') : '');
        echo json_encode(['code' => 0, 'data' => $data, 'msg' => 'SUCCESS']);
    }

    private function unsetConditionOtherByVal($condition, $val)
    {
        if (is_array($condition['other'])) {
            foreach ($condition['other'] as $key => $item) {
                $v = preg_replace("/(.*?)(\w+?)(\s)*:(\s)*([a-z0-9]+)(.*)/i", "$5", $item);
                if ($val == trim($v)) {
                    unset($condition['other'][$key]);
                }
            }
        } elseif (!empty($condition['other'])) {
            $v = preg_replace("/(.*?)(\w+?)(\s)*:(\s)*([a-z0-9]+)(.*)/i", "$5", $condition['other']);
            if ($val == trim($v)) {
                unset($condition['other']);
            }
        }
        if (isset($condition['other']) && empty($condition['other'])) {
            unset($condition['other']);
        }
        return $condition;
    }

    private function getNextSeason($yearsGroupBySeason, $current_season_id)
    {
        while (true) {
            $current_item = current($yearsGroupBySeason);
            $next_item = next($yearsGroupBySeason);
            if ($current_item['season_id'] == $current_season_id) {
                return $next_item;
            }
            if (empty($next_item) || $current_item['season_id'] == $current_season_id) {
                break;
            }
        }
        return false;
    }

    //获取三年内的色彩走势
    public function threeColorTrends($params = '')
    {
        $this->checkPower();
        $season_cn = $this->input->get_post('sea_cn');
        if (!in_array($season_cn, $this->Colortrends_model->season_cns)) {
            $result = array(
                'msg' => '必须季节参数早春、春夏、早秋、秋冬中的一个',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $YearsGroupBySeason = $this->Colortrends_model->getYearsGroupBySeason($season_cn);
        $threeYearSameSeason = array_slice($YearsGroupBySeason, 0, 3);
        if (count($threeYearSameSeason) < 3) {
            $result = array(
                'msg' => '当前季节少于三个年份，无法对比！',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $condition = $this->getCondition($params);
        $field = !empty($_REQUEST['color']) ? 'iFirstColorSecondLevel' : 'iFirstColorFirstLevel';
        $colors = $this->Colortrends_model->getAllColors(intval($_REQUEST['color']));
        $result = $two_digit_years = array();
        $threeYearSameSeason = array_reverse($threeYearSameSeason);
        $solrRes = $seasons = array();
        foreach ($threeYearSameSeason as $item) {
            $two_digit_years[$item['year']] = date('y', strtotime($item['year'] . '-01-01 00:00:00'));
            $condition['iSeason'] = $item['season_id'];
            $fields = $field == 'iFirstColorSecondLevel' ? [$field, 'sCategory'] : [$field];
            $solrRes[] = $this->Colortrends_model->getCountPercentByFacet($condition, $fields);
            $seasons[] = $item['season_id'];
        }

        $result['name'] = implode('/', $two_digit_years) . $season_cn;
        foreach ($colors as $color) {
            $item = [
                'id' => $color['id'],
                'name' => $color['sName'],
                'color_number' => $color['sColor'],
                'pantone_number' => $color['sColorNumber'],
            ];
            for ($i = 1; $i <= 3; $i++) {
                $j = $i - 1;
                $item['percent_' . $i] = (float)$solrRes[$j]['color']['items'][$color['id']]['percent'];
            }
            if ($item['percent_3'] > $item['percent_2'] && $item['percent_2'] > $item['percent_1']) {
                $item['three_trend'] = "up";
            } elseif ($item['percent_3'] < $item['percent_2'] && $item['percent_2'] < $item['percent_1']) {
                $item['three_trend'] = "down";
            } else {
                $item['three_trend'] = "none";
            }
            $item['two_year_diff'] = $item['percent_3'] - $item['percent_2'];
            $result['data'][] = $item;
        }
        //二级页面会有单品
        if ($field == 'iFirstColorSecondLevel') {
            $allCategory = GetCategory::getSingle();
            foreach ($allCategory as $id => $name) {
                $item = ['id' => $id, 'name' => $name,];
                for ($i = 1; $i <= 3; $i++) {
                    $j = $i - 1;
                    $item['percent_' . $i] = (float)$solrRes[$j]['category']['items'][$id]['percent'];
                }
                if (($item['percent_1'] + $item['percent_2'] + $item['percent_3']) <= 0.3) {
                    continue;
                }
                $result['category'][] = $item;
            }
        }
        $seasons = array_reverse($seasons);
        foreach ($seasons as $season) {
            $season_names_arr[] = $this->Colortrends_model->getSeasonById($season);
        }
        $season_names = implode('、', $season_names_arr);

        $paramsArr = $this->Common_model->parseParams($params, 1);
        $region_name = $this->Colortrends_model->regions[intval($paramsArr['reg'])]['name'];
        $gender_name = $this->Colortrends_model->genders[intval($paramsArr['gen'])]['name'];
        if ($gender_name == '全部') {
            $gender_name = '全部性别';
        }
        $temp_data = twoDimensionSort($result['data'], 'two_year_diff');
        $two_year_trend = array();
        $three_year_up = $three_year_down = '';
        foreach ($temp_data as $item) {
            if (empty($three_year_up) && $item['three_trend'] == 'up') {
                $three_year_up = $item;
            }
            if ($item['three_trend'] == 'down') {
                $three_year_down = $item;
            }
            if ($item['two_year_diff'] > 3) {
                $two_year_trend['up'][] = $item['name'];
            } elseif ($item['two_year_diff'] < -3) {
                $two_year_trend['down'][] = $item['name'];
            } else {
                $two_year_trend['steady'][] = $item['name'];
            }
        }
        //取中间三个
        $length = !empty($_REQUEST['color']) ? 1 : 2;
        $count = count($two_year_trend['steady']);
        if ($count > 3) {
            $two_year_trend['steady'] = array_slice($two_year_trend['steady'], ceil($count / 2) - 2, $length);
        }
        $two_year_trend_steady_names = !empty($two_year_trend['steady']) ? implode("、", $two_year_trend['steady']) : "";
        $two_year_trend_up_names = !empty($two_year_trend['up']) ? implode("、", array_slice($two_year_trend['up'], 0, $length)) : "";
        $two_year_trend_dwon_names = !empty($two_year_trend['dwon']) ? implode("、", array_slice(array_reverse($two_year_trend['dwon']), 0, $length)) : "";
        if (!empty($_REQUEST['color'])) {
            $color_id = $_REQUEST['color'];
            foreach ($threeYearSameSeason as $item) {
                $condition['iSeason'] = $item['season_id'];
                $_solrRes = $this->Colortrends_model->getCountPercentByFacet($condition, ['iFirstColorFirstLevel']);
                $percents[] = $_solrRes['color']['items'][$color_id]['percent'];
            }
            if (floatval($percents[2]) - floatval($percents['1']) > 3) {
                $color_trend = "上升";
            } elseif (floatval($percents[2]) - floatval($percents['1']) < -3) {
                $color_trend = "下降";
            } else {
                $color_trend = "平稳";
            }
            $color_name = $this->Colortrends_model->getColorById($color_id);
            $result['interpret'] = "在{$gender_name}下，最近三季色彩走势中，{$color_name}整体呈现{$color_trend}趋势；";
            $result['interpret'] .= empty($three_year_up) ? "" : "其中{$three_year_up['name']}呈三年连续增长趋势，相对比{$season_names_arr[1]}上升了（{$three_year_up['two_year_diff']}%）。";
            $result['interpret'] .= empty($three_year_down) ? "" : "{$three_year_down['name']}呈里连续三年下降趋势，相对比{$season_names_arr[1]}下降了（{$three_year_down['two_year_diff']}%）。";
            $result['interpret'] .= empty($two_year_trend_steady_names) ? "" : ($two_year_trend_steady_names . "呈平稳趋势。");
            $result['interpret'] .= empty($two_year_trend_up_names) ? "" : ($two_year_trend_up_names . "在波动中呈上升趋势。");
            $result['interpret'] .= empty($two_year_trend_dwon_names) ? "" : ($two_year_trend_dwon_names . "在波动中呈下降趋势。");
        } else {
            $result['interpret'] = "在{$region_name}{$season_names}三季色彩走势中，{$gender_name}下，";
            $result['interpret'] .= empty($three_year_up) ? "" : "{$three_year_up['name']}呈三年连续增长趋势，相较于{$season_names_arr[1]}上升（{$three_year_up['two_year_diff']}%）。";
            $result['interpret'] .= empty($three_year_down) ? "" : "{$three_year_down['name']}呈三年连续下降趋势，相较于{$season_names_arr[1]}下降（{$three_year_down['two_year_diff']}%）。";
            $result['interpret'] .= empty($two_year_trend_steady_names) ? "" : ($two_year_trend_steady_names . "呈平稳趋势。");
            $result['interpret'] .= empty($two_year_trend_up_names) ? "" : ($two_year_trend_up_names . "在波动中呈上升趋势。");
            $result['interpret'] .= empty($two_year_trend_dwon_names) ? "" : ($two_year_trend_dwon_names . "在波动中呈下降趋势。");
        }

        $result['code'] = 0;
        $result['msg'] = 'SUCCESS';
        echo json_encode($result);
        exit;
    }

    //色彩走势二级页面右侧列表
    public function threeColorTrendsList($params = '')
    {
        $this->checkPower();
        $paramsArr = $this->Common_model->parseParams($params, 1);
        $season_cn = $this->input->get_post('sea_cn');
        if (!in_array($season_cn, $this->Colortrends_model->season_cns)) {
            $result = array(
                'msg' => '必须季节参数早春、春夏、早秋、秋冬中的一个',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $YearsGroupBySeason = $this->Colortrends_model->getYearsGroupBySeason($season_cn);
        $threeYearSameSeason = array_slice($YearsGroupBySeason, 0, 3);
        $season_ids = array_column($threeYearSameSeason, 'season_id');
        $condition = $this->getCondition($params);
        $condition['iSeason'] = $season_ids;
        $arSort = array('iViewCount' => 'DESC', 'pri_id' => 'DESC');

        $solr_params['fl'] = 'pop_id,tablename,pri_id,iColumnId';
        $solr_params['group'] = 'true';
        $solr_params['group.offset'] = 0;
        $solr_params['group.limit'] = 24;//每组取得数据条数
        $solr_params['group.ngroups'] = 'true';
        $solr_params['group.field'] = 'iSeason';
        $solr_params['group.sort'] = ['iViewCount' => 'DESC', 'pri_id' => 'DESC'];    // 按最热门的排序

        POPSearch::wrapQueryPopFashionMerger('', $condition, $solr_result, 0, 3, $arSort, $solr_params);
        $lists = array_fill_keys(array_reverse($season_ids), null);
        foreach ($solr_result['iSeason']['groups'] as $item) {
            $rows = $item['groupValue'] != $season_ids[0] ? array_slice($item['doclist']['docs'], 0, 12) : $item['doclist']['docs'];
            $linkArray = $paramsArr;
            $linkArray['sea'] = $item['groupValue'];
            $linkArray['aco'] = $_REQUEST['color'];
            $linkArray['aco'] = $_REQUEST['color'];
            $linkArray['dis'] = 1;
            $linkArray['sor'] = 2;
            $lists[$item['groupValue']]['title'] = $YearsGroupBySeason[$item['groupValue']]['year'] . $YearsGroupBySeason[$item['groupValue']]['season'];
            $lists[$item['groupValue']]['link'] = $this->columnRootPath . $this->Common_model->parseParams($linkArray, 2) . '/';
            $lists[$item['groupValue']]['data'] = $this->Colortrends_model->dealSolorResult($rows);

        }
        $lists = array_filter($lists);
        $result['code'] = 0;
        $result['msg'] = 'SUCCESS';
        $result['lists'] = $lists;
        echo json_encode($result);
    }

    //上升&下降色近两年的
    public function upOrDownColor($params = '')
    {
        $this->checkPower();
        $season_cn = $this->input->get_post('sea_cn');
        $paramsArr = $this->Common_model->parseParams($params, 1);
        if (!in_array($season_cn, $this->Colortrends_model->season_cns)) {
            $result = array(
                'msg' => '必须季节参数早春、春夏、早秋、秋冬中的一个',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $YearsGroupBySeason = $this->Colortrends_model->getYearsGroupBySeason($season_cn);
        $twoYearSameSeason = array_slice($YearsGroupBySeason, 0, 2);
        $condition = $this->getCondition($params);
        foreach ($twoYearSameSeason as $item) {
            $result['seasons'][] = $item['year'] . $item['season'];
            $condition['iSeason'] = $item['season_id'];
            $facetData = $this->Colortrends_model->solorFacet($condition, ['iFirstColorSecondLevel']);
            if (!empty($facetData['iFirstColorSecondLevel'])) {
                $total = array_sum($facetData['iFirstColorSecondLevel']);
                foreach ($facetData['iFirstColorSecondLevel'] as $color_id => $count) {
                    $color_count[$color_id][$item['season_id']] = round(($count / $total) * 100, 5);
                }
            }
        }
        list($season_one, $season_two) = array_column($twoYearSameSeason, 'season_id');
        $all_colors = $this->Colortrends_model->getAllColors();
        $colors = array();
        if (!empty($color_count)) {
            foreach ($color_count as $color_id => $item) {
                $percent_diff = $item[$season_one] - $item[$season_two];
                $colors["$percent_diff"] = [
                    'id' => $color_id,
                    'percent_1' => $item[$season_one],
                    'percent_2' => $item[$season_two],
                    'percent_diff' => round($percent_diff, 2),
                    'name' => $all_colors[$color_id]['sName'],
                    'color_number' => $all_colors[$color_id]['sColor'],
                    'pantone_number' => $all_colors[$color_id]['sColorNumber'],
                ];
            }
        }
        krsort($colors);
        $top['up'] = array_values(array_slice($colors, 0, 10));
        ksort($colors);
        $top['down'] = array_values(array_slice($colors, 0, 10));
        $result['top'] = $top;
        $result['code'] = 0;
        $result['msg'] = 'SUCCESS';
        $region_name = $this->Colortrends_model->regions[intval($paramsArr['reg'])]['name'];
        $result['interpret'] = "相较于{$result['seasons'][1]}，{$result['seasons'][0]}{$region_name}中，呈明显上升趋势的色彩TOP10，以{$top['up'][0]['name']}（{$top['up'][0]['percent_diff']}%）、{$top['up'][1]['name']}（{$top['up'][1]['percent_diff']}%）、{$top['up'][2]['name']}（{$top['up'][2]['percent_diff']}%）上升最为明显；呈明显下降趋势的色彩TOP10中，以{$top['down'][0]['name']}（{$top['down'][0]['percent_diff']}%）、{$top['down'][1]['name']}（{$top['down'][1]['percent_diff']}%）、{$top['down'][2]['name']}（{$top['down'][2]['percent_diff']}%）下降最为明显。";
        echo json_encode($result);
    }

    //上升与下降色二级
    public function upOrDownColorSecond($params = '')
    {
        $this->checkPower();
        $season_cn = $this->input->get_post('sea_cn');
        $paramsArr = $this->Common_model->parseParams($params, 1);
        if (!in_array($season_cn, $this->Colortrends_model->season_cns)) {
            $result = array(
                'msg' => '必须季节参数早春、春夏、早秋、秋冬中的一个',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $color_id = (int)$_REQUEST['color'];
        $all_colors = $this->Colortrends_model->getAllColors();
        if (empty($all_colors[$color_id]['iPid'])) {
            $result = array(
                'msg' => '二级页面必须有一级色',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        } else {
            $color_pid = $all_colors[$color_id]['iPid'];
        }
        //兄弟颜色
        $siblings = $this->Colortrends_model->getAllColors($color_pid);
        $YearsGroupBySeason = $this->Colortrends_model->getYearsGroupBySeason($season_cn);
        $FourYearSameSeason = array_reverse(array_slice($YearsGroupBySeason, 0, 4));
        $condition = $this->getCondition($params);
        //查询条件替换成二级色，aAutoColors范围更大
        $condition['iFirstColorSecondLevel'] = $condition['aAutoColors'];
        unset($condition['aAutoColors']);
        //获取当前颜色季节对比
        //var_dump($FourYearSameSeason);exit;
        $result['code'] = 0;
        $result['msg'] = 'SUCCESS';
        $result['color_line_chart'] = $result['color_pie_chart'] = array();
        $_condition = array_merge($condition, ['iSeason' => array_column($FourYearSameSeason, 'season_id')]);
        $line_res = $this->Colortrends_model->solorFacet($_condition, ['iSeason']);
        unset($_condition['iFirstColorSecondLevel']);
        $_condition['aAutoColors'] = $color_pid;
        $line_total_res = $this->Colortrends_model->solorFacet($_condition, ['iSeason']);
        $result['color_name'] = $all_colors[$color_id]['sName'];
        $result['p_color_name'] = $all_colors[$color_pid]['sName'];
        foreach ($FourYearSameSeason as $item) {
            $result['color_line_chart'][] = [
                'id' => $item['season_id'],
                'percent' => empty($line_total_res['iSeason'][$item['season_id']]) ? 0 : round((intval($line_res['iSeason'][$item['season_id']]) / $line_total_res['iSeason'][$item['season_id']]) * 100, 2),
                'year' => $item['year'],
                'season_name' => $item['year'] . $item['season'],
            ];
        }
        $_condition = $condition;
        unset($_condition['iFirstColorSecondLevel']);
        $_condition['aAutoColors'] = $color_pid;
        $pie_res = $this->Colortrends_model->solorFacet($_condition, ['iFirstColorSecondLevel']);
        //交集去除非当前兄弟二级色的
        $pie_res = array_intersect_key((array)$pie_res['iFirstColorSecondLevel'], $siblings);
        $result['pie_res_total'] = array_sum($pie_res);
        foreach ($siblings as $item) {
            $result['color_pie_chart'][] = [
                'id' => $item['id'],
                'count' => intval($pie_res[$item['id']]),
                'name' => $item['sName'],
                'color_number' => $item['sColor'],
                'pantone_number' => $item['sColorNumber'],
            ];
        }
        $pre_percent = 0;
        $result['interpret'] = '';
        $trend = '平稳';
        foreach ($result['color_line_chart'] as $key => $item) {
            if ($key == 0) {
                $result['interpret'] = "{$item['season_name']}{$result['color_name']}在整个{$result['p_color_name']}系中占比为（{$item['percent']}%），";
            } else {
                if ($item['percent'] > $pre_percent) {
                    $result['interpret'] .= "{$item['season_name']}占比增长为（{$item['percent']}%），";
                } else {
                    $result['interpret'] .= "{$item['season_name']}占比下降为（{$item['percent']}%），";
                }
            }
            if ($item['percent'] - $pre_percent > 3) {
                $trend = '上升';
            } elseif ($item['percent'] - $pre_percent < -3) {
                $trend = '下降';
            } else {
                $trend = '平稳';
            }
            $pre_percent = $item['percent'];
        }

        $cn_number = [0 => '零', 1 => '一', 2 => '二', 3 => '三', 4 => '四'];
        $num = count($result['color_line_chart']);
        $result['interpret'] .= "连续{$cn_number[$num]}年同季节对比，{$result['color_name']}在波动{$trend}趋势。";
        echo json_encode($result);
    }

    //今年色彩推荐
    public function recommendColorList($params = '')
    {
        $this->checkPower();
        $season_id = $this->input->get_post('season');
        $color_id = $this->input->get_post('color');
        if (empty($season_id) || empty($color_id)) {
            $result = array(
                'msg' => '季节和颜色ID不能为空！',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $condition = $this->getCondition($params);
        $result = array('code' => 0, 'msg' => '', 'more_link' => '', 'list' => []);
        $result['list'] = $this->Colortrends_model->getList($condition, 28);
        $paramsArr = $this->Common_model->parseParams($params, 1);
        $linkArray = $paramsArr;
        $linkArray['sea'] = $season_id;
        $linkArray['aco'] = $color_id;
        $linkArray['dis'] = 1;
        $linkArray = array_filter($linkArray);
        $result['more_link'] = $this->columnRootPath . (!empty($linkArray) ? ($this->Common_model->parseParams($linkArray, 2) . '/') : '');
        echo json_encode($result);
    }

    //获取大图详情
    public function getImageDetail()
    {
        $t = $this->input->get_post('t');
        $id = $this->input->get_post('id');
        $col = $this->input->get_post('col');
        if (empty($t) || empty($id)) {
            $result = array(
                'msg' => '表名和ID不能为空！',
                'code' => '1',
            );
            echo json_encode($result);
            exit;
        }
        $table = getProductTableName($t);
        $rows = OpPopFashionMerger::getProductData($id, $table);
        $result = array(
            'msg' => 'SUCCESS',
            'code' => 0,
        );
        $result['big_img'] = $result['url'] = '';
        if (!empty($rows[$id])) {
            $powers = memberPower('detail', ['P_Gender' => $rows[$id]['iGender'], 'P_Industry' => $rows[$id]['iIndustry'], 'P_Column' => 50]);
            if (in_array($powers['P_UserType'], [1, 2, 3])) {
                $images = getImagePath($table, $rows[$id]);
                $result['big_img'] = $images['bigPath'];
            } else {
                $result = array(
                    'msg' => '无权限',
                    'code' => 1001,
                );
                $result['url'] = "/details/style/t_{$t}-id_{$id}-col_{$col}/";
            }
        }
        echo json_encode($result);
        exit;
    }


    //获取solr查询条件
    private function getCondition($params = '')
    {
        $paramsArr = $this->Common_model->parseParams($params, 1);
        $request = $_REQUEST;
        $request = array_merge($request, $paramsArr);
        $condition = ['iColumnId' => 50, 'tablename' => 'product_perform', 'dCreateTime' => $this->Common_model->getTimeRange()];
        // $condition = ['iColumnId' => 50, 'tablename' => 'product_perform'];
        foreach ($request as $key => $val) {
            if (empty($val)) {
                continue;
            }
            switch ($key) {
                case 'color':
                    $condition['aAutoColors'] = $val;
                    break;
                case 'season':
                    $condition['iSeason'] = $val;
                    break;
                case 'category':
                case 'gen':
                    $condition['other'][] = "(aLabelIds:$val)";
                    break;
                case 'reg':
                    $condition['other'][] = "(iRegion:{$val} OR iArea:{$val} OR iContinent:{$val} OR  iCountry:{$val})";
                    break;
            }
        }
        return $condition;
    }
}

