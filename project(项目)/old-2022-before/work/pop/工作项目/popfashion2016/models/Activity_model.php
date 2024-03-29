<?php

/**
 * 个人中心-活动管理 model
 * User: Administrator
 * Date: 2016/11/19
 * @property-read common_model $common_model
 */
class Activity_model extends POP_Model
{
    const TABLENAME = '`fashion`.`t_industry_activity`';
    const ACTIVITY_DICT = '`fashion`.`t_dict_industry_activity`';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'details_model']);
    }


    /**
     * @param $iTypes
     * @param int $pid
     * @param bool $unlimited
     * @param array $col 所属栏目类型
     * @return array
     */
    public function getActivityDictByiType($iTypes, $pid = 0, $unlimited = true, $col = '')
    {
        if (is_array($iTypes)) {
            $strType = implode(',', $iTypes);
        } else {
            $strType = intval($iTypes);
        }
        if (is_array($pid)) {
            $pid = implode(',', $pid);
        } else {
            $pid = intval($pid);
        }

        //区分类型所属栏目
        $where_col = '';
        if ($col && ($iTypes != 1)) {
            $where_col .= "find_in_set('" . $col . "',sColumn) AND ";
        }

        $sql = "SELECT `id`,`pid`,`sName`,`iType`,`sColumn` FROM " . self::ACTIVITY_DICT . " WHERE {$where_col} `iStatus` = 1 AND `pid` IN ({$pid}) AND `iType` IN ({$strType}) ORDER BY `iSort`";
        $rows = $this->query($sql);
        $result = [];
        if (empty($rows)) {
            return $result;
        }
        foreach ($rows as $row) {
            $resultTmp = [];
            $resultTmp['id'] = $row['id'];
            $resultTmp['sName'] = $row['sName'];

            $_sql = "SELECT `id`,`sName` FROM " . self::ACTIVITY_DICT . " WHERE `iStatus` = 1 AND `pid` = {$row['id']} ORDER BY `iSort`";
            $_rows = $this->query($_sql);
            if ($_rows && $unlimited) {
                foreach ($_rows as $key => $_row) {
                    $_rows[$key]['sub'] = $this->getActivityDictByiType($iTypes, $_row['id']);
                }
                $resultTmp['child'] = $_rows;
            }
            $result[$row['id']] = $resultTmp;
        }

        return $result;
    }


    public function addActivity($data)
    {
        return $this->executeSave(self::TABLENAME, $data);
    }

    public function updActivity($data, $condition)
    {
        return $this->executeUpdate(self::TABLENAME, $data, $condition);
    }

    public function updateSolrByActId($activityId)
    {
        $activityInfo = $this->getOneActivityById($activityId);
        return OpPopIndustryActivity::feedTIndustryActivity([$activityInfo]);
    }

    public function delSolrByPopIds($pop_ids)
    {
        return OpPopIndustryActivity::deleteIndustryActivity($pop_ids);
    }


    public function getOneActivityById($activityId, $condition = [])
    {
        if (!$activityId) return 0;

        $activityId = intval($activityId);
        if (empty($condition)) {
            $sql = "SELECT * FROM " . self::TABLENAME . " WHERE `id`=?";
            $result = $this->db()->query($sql, $activityId)->row_array();
        } else {
            $result = $this->db()->select('*')->from(self::TABLENAME)
                ->where($condition)
                ->where('id', $activityId)
                ->get()->row_array();
        }
        // $this->db()->close();

        // 替换 http://img1.fm.pop-fashion.com 为 https://imgf1.pop-fashion.com
        $result['sPosterUrl'] = $this->details_model->getImgfPath($result['sPosterUrl'], true);
        $result['sImageFigure'] = $this->details_model->getImgfPath($result['sImageFigure'], true);
        $result['sImageEnterprise'] = $this->details_model->getImgfPath($result['sImageEnterprise'], true);

        return $result;
    }

    /* *
     * @todo  通过id查询活动字典表其它字段
     * @param $ids     int or string or array 
     * @param $filed   array   [想要获取的字段]
     * @param $retType string or array [返回方式]
     * @return $ret    string or array
     */
    public function getOtherByIds($ids, $field = array('sName'), $retType = '')
    {
        if (empty($ids)) return;
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . self::ACTIVITY_DICT;
        $this->load->driver('cache');
        $activityDict = $this->cache->memcached->get($memKey);
        if (empty($activityDict) || $this->input->get('refresh')) {
            $activityDict = array();
            $sql = 'SELECT * FROM ' . self::ACTIVITY_DICT . ' WHERE iStatus=1 ORDER BY `iType`, `iSort` DESC';
            $rows = $this->query($sql);
            foreach ($rows as $key => $val) {
                $activityDict[$val['id']] = $val;
            }
            $this->cache->memcached->save($memKey, $activityDict, 3600 * 24 * 30);
        }
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $ret = $retType == 'array' ? array() : '';
        foreach ($ids as $i => $id) {
            if (!$activityDict[$id]) {
                continue;
            }
            foreach ($field as $f) {
                if ($retType == 'array') {
                    $ret[$f][] = $activityDict[$id][$f];
                } else {
                    if ($i == 0) {
                        $ret = $activityDict[$id][$f];
                    } else {
                        $ret = $ret . ',' . $activityDict[$id][$f];
                    }
                }
            }
        }
        return $ret;
    }

    /* *
     * @todo    getActivityLists [获取活动列表页数据]
     * @param   $params   string  [参数]
     * @param   $offset   intval  [偏移量]
     * @param   $limit    intval  [条数]
     * @return  $lists    array   [列表数据]
     * @return  $totalCount  intval [数据总条数]
     */
    public function getActivityLists($conditions, &$lists, $offset = 0, $limit = 10, $arSort)
    {
        // 排序
        if (!$arSort) {
            $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC'];
        }
        $result = [];
        $totalCount = POPSearch::wrapQueryPopIndustryActivity('', $conditions, $result, $offset, $limit, $arSort);
        $lists = $this->getListsBySolrResult($result);
        return $totalCount;
    }

    /* *
     * @todo    getListsBySolrResult [通过solr取出的数据获取数据表里的数据]
     * @param   $result   array   [solr取出的数据]
     * @return  $lists    array   [数据表里的数据]
     */
    public function getListsBySolrResult($result = [])
    {
        if (empty($result)) return;
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tablename = $val['tablename'];
            $data = OpPopIndustryActivity::getProductData($id, $tablename, $refresh = true);
            $info = $this->reHandleInfo($data[$id]);
            $lists[] = $info;
        }
        return $lists;
    }

    /* *
     * @todo    reHandleInfo [对每条记录再次处理成html页面需要的]
     * @param   $info     array   [处理前]
     * @return  $info    array    [处理后]
     */
    public function reHandleInfo($info = [])
    {
        if (empty($info)) return $info;

        $info['sTheme'] = $info['sTheme'];// 主题
        $info['sContactsName'] = $info['sContactsName'];// 主办方联系人
        $info['sActivityContent'] = htmlspecialchars_decode($info['sActivityContent']);// 活动详情
        // 替换文本域的图片
        $info['sActivityContent'] = $this->replaceTextAreaImg($info['sActivityContent']);

        switch ($info['iActivityMessageType']) {
            case '1':
            case '2':
                $info['sSponsor'] = $info['sSponsor'];// 主办方

                if ($info['iActivityType'] == 17) {// 网址（线上）
                    $info['sWebsiteURL'] = $info['sActivityURL'];
                } elseif ($info['iActivityType'] == 18) {// 地点（线下）
                    $info['region'] = $info['sActivityDetailAddress'];
                    if ($info['region'] && $info['sMapAddress']) {
                        $regionLink = 'http://ditu.amap.com/search?query=' . urlencode($info['sMapAddress']);
                        $info['regionLink'] = urlencode($regionLink);
                    } else {
                        $info['regionLink'] = '';
                    }
                }
                // 开始时间
                $info['dStartTime'] = $info['dActivityStartTime'] ? date('Y-m-d H:i', strtotime($info['dActivityStartTime'])) : '';
                $info['dStartDate'] = $info['dActivityStartTime'] ? date('Y-m-d', strtotime($info['dActivityStartTime'])) : '';
                // 结束时间
                $info['dEndTime'] = $info['dActivityEndTime'] ? date('Y-m-d H:i', strtotime($info['dActivityEndTime'])) : '';
                $info['dEndTDate'] = $info['dActivityEndTime'] ? date('Y-m-d', strtotime($info['dActivityEndTime'])) : '';

                $info['iActivityChildTypeName'] = $this->getOtherByIds($info['iActivityChildType'], ['sName'], 'string');// 类型
                // 活动类型中文名
                $info['iActivityTypeName'] = $this->getOtherByIds($info['iActivityType'], ['sName'], 'string');

                //面包屑导航条连接
                if ($info['iActivityMessageType'] == 1) {
                    $mes = 'mes_1';
                    $name = '时尚教育';
                } elseif ($info['iActivityMessageType'] == 2) {
                    $mes = 'mes_2';
                    $name = '商贸对接';
                } else {
                    $mes = 'mes_3';
                    $name = '精英联盟';
                }
                $info['crumbsNavigation'] = ['link' => '/activity/lists/' . $mes . '/', 'name' => $name];
                break;
            case '3':
                $info['sCompanyType'] = $this->getOtherByIds($info['iCompanyType'], ['sName'], 'string');//公司类型
                $info['detailAddress'] = $info['sActivityDetailAddress'];
                if ($info['detailAddress'] && $info['sMapAddress']) {
                    $regionLink = 'http://ditu.amap.com/search?query=' . $info['sMapAddress'];
                    $info['regionLink'] = urlencode($regionLink);
                }
                // 城市
                $info['sActivityCity'] = $this->getOtherByIds($info['iActivityCity'], ['sName'], 'string');
                if ($info['iOfficial'] == 1) {
                    $info['companyLink'] = $info['sActivityURL'];// 官网地址
                } else {
                    $info['companyLink'] = '/activity/alliancedetail/?id=' . $info['id'];// 详情地址
                }

                break;
        }

        // 替换 http://img1.fm.pop-fashion.com 为 https://imgf1.pop-fashion.com
        $info['sPosterUrl'] = $this->details_model->getImgfPath($info['sPosterUrl'], true);
        $info['sImageFigure'] = $this->details_model->getImgfPath($info['sImageFigure'], true);
        $info['sImageEnterprise'] = $this->details_model->getImgfPath($info['sImageEnterprise'], true);

        $info['dPublishDate'] = $info['dPublishTime'] && $info['dPublishTime'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($info['dPublishTime'])) : '';// 发布时间
        $info['dCheckDate'] = $info['dCheckTime'] && $info['dCheckTime'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($info['dCheckTime'])) : '';// 审核时间
        // 高德地图活动地点链接
        if ($info['fLongitude'] > 0 || $info['fLatitude'] > 0) {
            $w_h = '285*224';
            if ($info['iActivityMessageType'] == 1) {
                $w_h = '285*224';
            } elseif ($info['iActivityMessageType'] == 3) {
                $w_h = '590*134';
            }
            $info['regionImg'] = '//restapi.amap.com/v3/staticmap?location=' . $info['fLongitude'] . ',' . $info['fLatitude'] . '&zoom=15&size=' . $w_h . '&markers=mid,,A:' . $info['fLongitude'] . ',' . $info['fLatitude'] . '&key=75e8144f141694ae4b8133880f7bf6b6';
        }
        return $info;
    }


    /**
     * 替换文本域的img
     * 调用Blog/detail
     * @param $str
     * @return mixed|string
     */
    public function replaceTextAreaImg($str)
    {
        if (!$str) return '';

        $data = array(
            1 => "http://img1.fm.pop-fashion.com",
            2 => "http://img2.fm.pop-fashion.com",
            3 => "http://img3.fm.pop-fashion.com",

        );
        // 替换http://img[1-3].fm.pop-fashion.com
        $dataReplace = array(
            1 => "https://imgf1.pop-fashion.com",
            2 => "https://imgf2.pop-fashion.com",
            3 => "https://imgf3.pop-fashion.com",

        );
        if (strpos($str, $data[1]) !== false) {
            $str = str_replace($data[1], $dataReplace[1], $str);
        }
        if (strpos($str, $data[2]) !== false) {
            $str = str_replace($data[2], $dataReplace[2], $str);
        }
        if (strpos($str, $data[3]) !== false) {
            $str = str_replace($data[3], $dataReplace[3], $str);
        }

        return $str;
    }

    /* *
     * @todo    getRecLists [通过某条记录获取此记录的推荐列表]
     * @param   $info   array   [某条记录]
     * @return  $lists  array   [推荐列表]
     */
    public function getRecLists($info, &$lists)
    {
        if (empty($info)) return;
        $conditions['iStatus'] = 1;
        $conditions['iPublishStatus'] = 1;
        $conditions['tablename'] = 't_industry_activity';
        $conditions['-pri_id'] = $info['id']; // 排除掉本身
        $conditions['iActivityChildType'] = $info['iActivityChildType'];
        $nowTime = date('Y-m-d\TH:i:s\Z');
        $conditions['dActivityEndTime'] = '{' . $nowTime . ' TO *]';
        $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC'];
        $result = [];
        $totalCount = POPSearch::wrapQueryPopIndustryActivity('', $conditions, $result, 0, 2, $arSort);
        $lists = $this->getListsBySolrResult($result);
        return $totalCount;
    }

    /* *
     * @todo    getActivityLists [获取管理页列表数据]
     * @param   $params   string  [solr条件]
     * @param   $offset   intval  [偏移量]
     * @param   $limit    intval  [条数]
     * @return  $lists    array   [列表数据]
     * @return  $totalCount  intval [数据总条数]
     */
    public function getManageLists($conditions = [], &$lists, $offset = 0, $limit = 10)
    {
        $conditions['iStatus'] = 1;
        $conditions['tablename'] = 't_industry_activity';
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $result = [];
        $totalCount = POPSearch::wrapQueryPopIndustryActivity('', $conditions, $result, $offset, $limit, $arSort);
        $lists = $this->getListsBySolrResult($result);
        return $totalCount;
    }

    /* *
     * @todo    getConditions   [获取solr的查询条件]
     * @param   $paramsArr      string  [参数]
     * @param   $mesType      消息类型（1，2，3）
     * @return  $conditions  array   [solr条件]
     */
    public function getConditions($paramsArr, $mesType)
    {
        // 默认条件
        $conditions['iStatus'] = 1;
        $conditions['iPublishStatus'] = 1;
        $conditions['tablename'] = 't_industry_activity';

        switch ($mesType) {
            case 1:
            case 2://商贸对接
                // 活动子类型
                if (isset($paramsArr['chi'])) {
                    $conditions['iActivityChildType'] = $paramsArr['chi'];
                }

                $nowTime = date('Y-m-d\TH:i:s\Z');// 时间
                if (isset($paramsArr['tim'])) {
                    switch ($paramsArr['tim']) {
                        case '1': // 今天
                            $startTime = date('Y-m-d\T00:00:00\Z');
                            break;
                        case '2': // 近一周
                            $startTime = date('Y-m-d\T00:00:00\Z', strtotime('-7 days'));
                            break;
                        case '3': // 近一个月
                            $startTime = date('Y-m-d\T00:00:00\Z', strtotime('-1 month'));
                            break;
                        case '4': // 近半年
                            $startTime = date('Y-m-d\T00:00:00\Z', strtotime('-6 month'));
                            break;
                        case '5': // 近一年
                            $startTime = date('Y-m-d\T00:00:00\Z', strtotime('-1 year'));
                            break;
                        default: // 全部（时间不限）
                            $startTime = '*';
                            break;
                    }
                    $conditions['dPublishTime'] = '[' . $startTime . ' TO ' . $nowTime . ']';
                }
                // 活动开始结束状态
                if (isset($paramsArr['stat'])) {
                    switch ($paramsArr['stat']) {
                        case '1': // 即将开始
                            $conditions['dActivityStartTime'] = '{' . $nowTime . ' TO *]';
                            break;
                        case '2': // 进行中
                            $conditions['dActivityStartTime'] = '[* TO ' . $nowTime . '}';
                            $conditions['dActivityEndTime'] = '{' . $nowTime . ' TO *]';
                            break;
                        case '3': // 已结束
                            $conditions['dActivityEndTime'] = '[* TO ' . $nowTime . '}';
                            break;
                    }
                }
                // 是否官方
                if (isset($paramsArr['off'])) {
                    $conditions['iOfficial'] = 1;
                }
                break;

            case 3:
                if (isset($paramsArr['cpt'])) {
                    $conditions['iCompanyType'] = $paramsArr['cpt'];
                }
                if (isset($paramsArr['cit'])) {
                    $conditions['iActivityCity'] = $paramsArr['cit'];
                }
                break;
        }
        // 活动消息类型
        $conditions['iActivityMessageType'] = $mesType;
        return $conditions;
    }


    public function getFeedBackQuestion($type){
        $time = date('Y-m-d H:i:s');
        if($type == 1){
            $query = $this->db()->where('status', 1)->from('feedback_question')->where('start_time <', $time)->where('end_time >', $time)->get()->row_array();
        }else{
            $query = $this->db()->where('status', 0)->from('feedback_question')->where('start_time <', $time)->where('end_time >', $time)->get()->result_array();
        }
        return $query;
    }


    public function saveAnswer($id, $answer, $username, $user_type){
        $query = $this->db()->insert('feedback_answer',['fq_id'=>$id, 'question_one'=>$answer[0], 'question_two'=>$answer[1], 'question_three'=>$answer[2], 'user_name'=>$username, 'user_type'=>$user_type]);
        return $query;
    }
}