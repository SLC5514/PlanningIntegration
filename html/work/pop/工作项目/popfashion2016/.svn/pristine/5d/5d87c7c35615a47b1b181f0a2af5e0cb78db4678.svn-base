<?php
/**
 * 款式库首页 趋势指数
 */


class POP_TrendExponent
{
    /**
     * 构建趋势指数数据
     * @param $conditions
     * @param $field
     * @param $gender
     * @param $arSort
     * @return array
     */
    public function buildTrendExponentData($conditions, $field, $gender, $arSort)
    {

        $exponentData = $_exponent = array();
        $groupParams = array();
        $groupParams['group'] = 'true';
        $groupParams['group.ngroups'] = 'true';
        $groupParams['group.field'][] = $field;

        $page = 1;
        $limit = 1000;
        $loop = true;

        if ($field == 'sBrand') {
            $conditions['iBrand']= '{0 TO *}';
        }
        elseif ($field == 'sSubCategory') {
            $conditions['sSubCategory']= '*';
        }
        $conditions['other'][] = '-tablename:(product_tideleader OR product_streetitem)';

        // 品牌数据
        do {
            $offset = ($page - 1) * $limit;
            $res = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort, $groupParams);
            $page++;

            if ($res) {
                $_data = POPSearch::getGroupData($result);
                $_data = $_data[$field]['groups'];

                if (count($_data) > 0) {
                    arsort($_data, SORT_NUMERIC);
                    $_exponent += array_slice($_data, 0, 10, true);
                }
                else {
                    $loop = false;
                }
            }
            else {
                $loop = false;
            }

        } while ($loop);

        arsort($_exponent, SORT_NUMERIC);
        $_exponent = array_slice($_exponent, 0, 10, true);

        foreach ($_exponent as $_id => $_num) {
            $_tmp = array();
            $_tmp['id'] = $_id;
            $_tmp['total'] = $_num;

            if ($field == 'sBrand') {
                $_tmp['name'] = GetCategory::getBrandOtherFormId($_id);
                // 品牌按单品成册 去除行业
                $_tmp['link'] = '/styles/bra_'. $_id . ($gender == 'all' ? '' : '-gen_' . $gender) . '-dis_3-ind_0/';
            }
            elseif ($field == 'sSubCategory') {
                $_tmp['name'] = GetCategory::getOtherFromIds($_id, ['sName']);
                // 单品按品牌成册 去除行业
                $_tmp['link'] = '/styles/cat_' . $_id . ($gender == 'all' ? '' : '-gen_' . $gender) . '-dis_2-ind_0/';
            }


            $exponentData[] = $_tmp;
        }


        return $exponentData;
    }


    /**
     * 获取趋势指数数据
     * @param bool $refresh
     * @return
     */
    public function getTrendExponentData($refresh = false)
    {
        $OpMemcache = OpMemcache::getInstance();
        $endTime = strtotime(date('Y-m-d 00:00:00'));
        $startTime = strtotime('-7 days', $endTime);
        $endTimeSolr = date('Y-m-d\T00:00:00\Z', $endTime);
        $startTimeSolr = date('Y-m-d\T00:00:00\Z', $startTime);

        $dCreateTime = "[{$startTimeSolr} TO {$endTimeSolr}]";
        $conditions = ['iColumnId' => 4, 'dCreateTime' => $dCreateTime];
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];

        $aGender = ['all',1,2,5];

        $memKey = OpPopFashionMerger::POP_FASHION_MERGER_MEMCACHE_KEY_PREFIX . '_Trend_Exponent_Data';

        $trendExponent = $OpMemcache->get($memKey);// 趋势指数

        if (!$trendExponent || $refresh) {
            // 遍历性别
            foreach ($aGender as $_v) {

                switch ($_v) {
                    case 1:
                    case 2:
                        $conditions['aLabelIds'] = $_v;
                        break;
                    case 5:
                        $conditions['aLabelIds'] = [3,4,5];
                        break;
                    case 'all':
                    default :
                        break;
                }
                $_tmp = [];
                // 品牌趋势指数
                $_tmp['brand'] = $this->buildTrendExponentData($conditions, 'sBrand', $_v, $arSort);
                // 单品趋势指数
                $_tmp['category'] = $this->buildTrendExponentData($conditions, 'sSubCategory', $_v, $arSort);
                $trendExponent[$_v] = $_tmp;
            }

            $OpMemcache->set($memKey, $trendExponent, 0, 86400);
        }


        return $trendExponent;
    }
}