<?php

/**
 * 个人中心-活动管理控制器
 * Date: 2016/11/19
 */
class Smarttrends extends POP_Controller
{
    public function index()
    {
        $this->display('intelligent.html');
    }

    //界面渲染
    public function colordata($params = '')
    {
        $this->load->model('Colortrends_model');
        $this->load->model('Common_model');
        $paramsArr = $this->Common_model->parseParams($params, 1);

        if (empty($paramsArr['reg']) && empty($paramsArr['reg'])) {
            $seoArray = [
                'title' => "服装色彩数据分析_服装流行色推荐_色彩流行趋势-POP服装趋势网",
                'keywords' => "服装色彩数据分析,服装流行色,服装色彩推荐,服装色彩流行趋势",
                'description' => "POP服装趋势网智能+色彩数据栏目为服装企业和设计师提供服装流行色彩全方位分析、色彩走势情况预测、未来色彩流行推荐等，从各个方向为设计师提供全面及时的色彩数据解读。"
            ];
        } else {
            $region_name = $this->Colortrends_model->regions[intval($paramsArr['reg'])]['name'];
            $gender_name = $this->Colortrends_model->genders[intval($paramsArr['gen'])]['name'];
            if ($gender_name == '全部') {
                $gender_name = '全部性别';
            }
            $seoArray = [
                'title' => "{$gender_name}色彩数据分析_{$gender_name}流行色推荐_{$region_name}色彩流行趋势-POP服装趋势网",
                'keywords' => "{$gender_name}色彩数据分析,{$gender_name}流行色,{$gender_name}色彩推荐,{地域}色彩流行趋势",
                'description' => "POP服装趋势网智能+色彩数据栏目为服装企业和设计师提供{$gender_name}流行色彩全方位分析、{$region_name}色彩走势情况预测、未来色彩流行推荐等，从各个方向为设计师提供全面及时的色彩"
            ];
        }
        $allYearSeason = $this->Colortrends_model->getAllYearSeason();
        //删除 '早春', '春夏', '早秋', '秋冬'的最老的一个年份，因为无法对上一个季节进行对比
        $showYearSeason = $this->deleteEarliestSeason($allYearSeason);
        foreach ($this->Colortrends_model->regions as $key => $item) {
            $item['link'] = '/smarttrends/colordata/' . $this->getNewParams('reg', $item['id'], $paramsArr);
            $regions[$key] = $item;
        }
        foreach ($this->Colortrends_model->genders as $key => $item) {
            $item['link'] = '/smarttrends/colordata/' . $this->getNewParams('gen', $item['id'], $paramsArr);
            $genders[$key] = $item;
        }
        $lastRecommendColor = $this->Colortrends_model->getLastRecommendColor();
        $recommend_color = array('colors' => [], 'interpret' => '', 'season_name' => '');
        if (!empty($lastRecommendColor['recommend_colors'])) {
            $recommend_color['season_name'] = $lastRecommendColor['year'].$lastRecommendColor['season'];
            foreach ($lastRecommendColor['recommend_colors'] as $item) {
                if (empty($paramsArr['gen']) || in_array($paramsArr['gen'], $item['gender'])) {
                    $recommend_color['colors'][] = array(
                        'cn_name' => $item['cn_name'],
                        'en_name' => $item['en_name'],
                        'panton_color' => $item['panton_color'],
                        'color_number' => $item['color_number'],
                        'color_id' => $item['color_level']['iSecondLevel'],
                        'season_id' => $lastRecommendColor['season_id'],
                    );
                }
            }
            $recommend_color['interpret'] = $lastRecommendColor['introduction'];
        }
        $selected_region_id = intval($paramsArr['reg']);
        $this->assign('selected_region_name', $regions[$selected_region_id]['name']);
        $this->assign('recommend_color', $recommend_color);
        $this->assign('regions', $regions);
        $this->assign('genders', $genders);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('showYearSeason', $showYearSeason);
        $this->assign('params', $params);
        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
        $this->display('colorData.html');
    }

    //删除 '早春', '春夏', '早秋', '秋冬'的最老的一个年份
    private function deleteEarliestSeason($allYearSeason)
    {
        $showYearSeason = array_reverse($allYearSeason);
        foreach ($this->Colortrends_model->season_cns as $season_cn) {
            foreach ($showYearSeason as $key => $item) {
                if ($item['season'] == $season_cn) {
                    unset($showYearSeason[$key]);
                    break;
                }
            }
        }
        $showYearSeason = array_reverse($showYearSeason);
        return $showYearSeason;
    }

    //拼接参数URL
    private function getNewParams($key, $val, Array $paramsArr)
    {
        if (empty($val) && isset($paramsArr[$key])) {
            unset($paramsArr[$key]);
        } else {
            $paramsArr[$key] = $val;
        }
        $params = $this->Common_model->parseParams($paramsArr, 2);
        return empty($params) ? '' : ($params . '/');
    }
}