<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Report_model
 * @property-read common_model $common_model
 */
class Report_model extends POP_Model
{
    const MEM_EXPIRE = 3600; // 1hour
    const MEM_EXPIRE_30 = 2592000; //30day
    const PDF_NOT_EXIST = -1;
    const PDF_GENERATING = 0;
    const MEM_CACHE = false;

    public $refresh = 0;
    public $noCache = 0;

    public function __construct()
    {
        parent::__construct();
        $this->refresh = $this->input->get('refresh');
        $this->load->model(['common_model', 'statistics_model']);
    }

    /**
     *  刷新数据缓存
     */
    public function setRefresh($_refresh = false)
    {
        $this->refresh = $_refresh;
    }

    /**
     *  不缓存memcache
     */
    public function setNoCache($_noCache = false)
    {
        $this->noCache = $_noCache;
    }

    /**
     * mostrend网站通过小图名获取大图名
     * @param string $path
     * @param array $pic_name
     * @return string
     */
    public function getMostrendPicName($path = '', $pic_name = [])
    {
        $bigPics = [];
        if (strpos($path, 'inspiration') > 0) {
            // 灵感报告
            foreach ($pic_name as $file) {
                $big_name = getFixedImgPath($path . 'pic/' . $file);
                array_push($bigPics, getStaticUrl($big_name) . $big_name);
            }
        } else {
            foreach ($pic_name as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $bigMd5Name = md5($filename . 'big!@#') . '.jpg';
                //大图： path + 'big/' + md5(图片名+'big!@#').jpg
                $big_name = getFixedImgPath($path . 'big/' . $bigMd5Name);
                array_push($bigPics, getStaticUrl($big_name) . $big_name);
            }
        }

        return $bigPics;
    }

    /**
     * 通过labelid 获取labelname(memcache)
     * @param string $sRelationLabels
     * @return mixed
     */
    public function getLabelsByIds($sRelationLabels = '')
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 't_label_db_' . md5($sRelationLabels);
        $result = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $result === false) {
            if ($sRelationLabels) {
                $labelSting = '(' . $sRelationLabels . ')';
                $andSql = "AND iLabelsId IN $labelSting";
            } else {
                $andSql = '';
            }

            $sql = "SELECT iLabelsId, sLabelName FROM t_label_db WHERE iStatus=0 {$andSql} ORDER BY iLabelsId DESC";
            $result = $this->query($sql);
            $this->cache->memcached->save($mem_key, $result, self::MEM_EXPIRE);
        }

        return $result;
    }

    /**
     * 通过 id 获取 笔名(memcache)
     * @param int $id
     * @return string
     */
    public function getPenNameById($id = 0)
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'penname';
        $result = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $result === false) {
            $sql = "SELECT id,sPenName FROM fashion_pop_login_name";
            $array = $this->query($sql);
            $result = [];
            foreach ($array as $val) {
                $result[$val['id']] = $val['sPenName'];
            }
            $this->cache->memcached->save($mem_key, $result, self::MEM_EXPIRE_30);
        }
        return isset($result[$id]) ? $result[$id] : '';
    }

    public static function getUlbLink($iUlbId = '')
    {
        $baseenv = getenv('BASEENV');
        if (1 == $baseenv) {
            return 'http://www.uliaobao.com/s/trend/' . $iUlbId;
        } elseif (2 == $baseenv) {
            return 'http://uat.www.uliaobao.com/s/trend/' . $iUlbId;
        } else {
            return 'http://dev.www.uliaobao.com/s/trend/' . $iUlbId;
        }
    }

    /**
     * 获取服装趋势类栏目5个表的数据
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function getFsDatas($table = '', $id = 0, $field = 'id', $col = 0)
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20171218_' . $table . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            $sql = "SELECT * FROM $table WHERE {$field} = ?";
            $query = $this->db()->query($sql, [$id]);
            $num = $query->num_rows();
            if ($num === 0) {
                return false;
            } else {
                $row = $query->row_array();
                $res = [];
                $id = $res['id'] = $row['id'];
                $res['iUlbId'] = $row['iUlbId'];
                $res['view_count'] = $row['view_count'];

                if ($res['iUlbId']) {
                    $res['ulbReportLink'] = self::getUlbLink($res['iUlbId']);
                }
                $res['table'] = $table;
                $res['authorPenName'] = $this->getPenNameById($row['iPublisher']);
                $res['title'] = htmlspecialchars(stripslashes($row['title']));
                $res['description'] = htmlspecialchars($row['abstract']); //摘要
                $res['updateDate'] = date('Y年m月d日', strtotime($row['order_time']));
                $res['iCreateTime'] = strtotime($row['order_time']); // 发布时间
                $res['bigImgPath'] = '';

                if ($col) {
                    $res['col'] = $col;
                } else {
                    $colInfo = $this->getColFromSolr($table, $id);
                    $res['col'] = $colInfo[1];
                }
                $res['colsName'] = GetCategory::getOtherFromColId($res['col'], 'sName');

                $this->load->model('common_model');
                $res['colLink'] = $this->common_model->getLink($res['col']);

                $res['gender'] = $row['type'];
                $res['industry'] = $row['sIndustry'];
                $res['colPid'] = $colInfo[0];
                $res['colPsName'] = GetCategory::getOtherFromColId($res['colPid'], 'sName');
                // 行业
                $res['industryInfo'] = [];
                if ($row['sIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($row['sIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $res['industryInfo'][$key]['sName'] = $industryArr['sName'][$key];
                            $res['industryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'ind', $item);
                        }
                    }
                }
                $res['industryText'] = isset($res['industryInfo'][0]['sName']) ? $res['industryInfo'][$key]['sName'] : '';
                //9:灵感报告 11:趋势 12:分析
                // colid:90 pid:1   pid:2
                $collectType = $this->getCollectTypeByColumnId($res['col']);
                $res['colpara'] = $res['col'] . '-' . getProductTableName($table) . '-' . $id . '-' . $collectType;
                //季节
                $res['iSeason_text'] = GetCategory::getNewEnName(5, $row['fordate']);
                $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$res['iSeason_text']}");
                $res['iSeason'] = isset($seasonId[0]) ? $seasonId[0] : 0;
                if ($res['iSeason']) {
                    $res['seasonLink'] = $this->common_model->getLink($res['col'], '', 'sea', $res['iSeason']);
                } else {
                    $res['seasonLink'] = '';
                }
                //性别
                $res['genderInfo'] = [];
                if ($row['type']) {
                    $typeArr = explode(',', $row['type']);
                    if (is_array($typeArr)) {
                        foreach ($typeArr as $key => $value) {
                            $genderId = GetCategory::getIdsFrom(1, 'sOriginalName,' . $value);
                            if ($genderId) {
                                $genderArr = GetCategory::getOtherFromIds($genderId[0], ['iAttributeId', 'sName'], 'array');
                                $res['genderInfo'][$key]['sName'] = $genderArr['sName'][0];
                                $res['genderInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'gen', $genderArr['iAttributeId'][0]);
                            }
                        }
                    }
                }
                $res['genderText'] = isset($res['genderInfo'][0]['sName']) ? $res['genderInfo'][0]['sName'] : '';
                //单品
                $res['categoryInfo'] = [];
                if ($row['sCategory']) {

                    $categoryArr = GetCategory::getOtherFromIds($row['sCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr) && count($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $res['categoryInfo'][$key]['sName'] = $categoryArr['sName'][$key];
                            $res['categoryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'cat', $item);
                        }
                    }
                }
                //关联标签
                $res['tagInfo'] = [];

                if ($row['sRelationLabels']) {
                    $sRelationLabels = $this->getLabelsByIds($row['sRelationLabels']);
                    if (is_array($sRelationLabels)) {
                        foreach ($sRelationLabels as $key => $item) {
                            $res['tagInfo'][$key]['sName'] = $item['sLabelName'];
                            $res['tagInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'key', urlencode($item['sLabelName']));
                        }
                    }
                }
                //地区
                $res['iRegion'] = GetCategory::getRegionIdFromEnName($row['region']);
                $res['iRegion_text'] = GetCategory::getFieldFromId($res['iRegion']);
                //列表页封面图，显示在中间页
                $_path = getImagePath($table, $row);
                $res['cover'] = getFixedImgPath($_path['cover']);
                //图片还是文字
                $res['is_pic'] = $row['is_pic'];
                if ($row['is_pic'] == 1) {
                    //图片
                    $groupId = $row['group_id'];
                    $sql = "SELECT big_name FROM fashion_shows_pic WHERE group_id = ?";
                    $result = $this->query($sql, [$groupId]);
                    $res['isPic'] = 1;
                    $res['images'] = [];
                    foreach ($result as $key => $value) {
                        $big_name = getFixedImgPath($value['big_name']);
                        array_push($res['images'], getStaticUrl($big_name) . $big_name);
                    }
                } else {
                    $res['content'] = $row['content'];
                }
                $res['pdfPath'] = self::PDF_NOT_EXIST;

                // 获取更多标签信息
                $this->getMoreReportInfo($row, $res);

                //是否推荐
                $tmpRes = OpPopFashionMerger::getProductData($id, $table);
                $res['iReconmmendMutual'] = $tmpRes[$id]['iReconmmendMutual'];
                unset($tmpRes);
                $res['iRecChannel'] = OpPopFashionMerger::getRecommend($table, $row)['iRecChannel'];
            }
            $this->cache->memcached->save($mem_key, $res, self::MEM_EXPIRE);
        }
        if ($res['is_pic'] != 1) {
            $content = stripslashes($res['content']);
            $report_content = str_replace('\\', '', $content);
            $report_content = str_replace('&quot;', '', $report_content);
            $report_content = str_replace('#content-main', '#J_ReportTitleContainer', $report_content);
            $report_content = str_replace('<strong></strong>', '', $report_content);
            $report_content = $this->replaceFsImage($report_content);

            /*
			$report_content = str_replace('down_qushi.php?path=//', 'download/dlsingle/?dl_link=/', $report_content);
			$report_content = str_replace('down_qushi.php?path=/', 'download/dlsingle/?dl_link=/', $report_content);
			*/
            $report_content = str_replace('href="/down_qushi.php?path=//', 'class ="J_DOWNLOAD_FILE" href="/download/dlsingle/?dl_link=/', $report_content);
            $report_content = str_replace('href="/down_qushi.php?path=/', 'class ="J_DOWNLOAD_FILE" href="/download/dlsingle/?dl_link=/', $report_content);

            if ($row['is_old'] != 1) {
                $report_content = str_replace('tabs_', 'tabs', $report_content);
                $report_content = str_replace('tabs', 'tabs_', $report_content);
            }
            $pattern = "#href=\"[\s]*(/fashion/fashion_shows/image/(.*?).(pdf|eps|ai|cdr))\"(.*)?>#i";
            preg_match_all($pattern, $report_content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $report_content = str_replace($match[1], '/download/dlsingle/?dl_link=' . urlencode('/' . ltrim(htmlspecialchars_decode($match[1]), '/')), $report_content);
            }
            $report_content = str_replace('">点击下载本文PDF</a>', '" class="J_DOWNLOAD_FILE">点击下载本文PDF</a>', $report_content);

            $res['isPic'] = 0;
            $res['reportContent'] = $report_content;
        }
        $res['viewNum'] = $this->statistics_model->getViews($res['table'], $res['id'], $res);

        // $this->db()->close();
        return $res;
    }

    private function replaceFsImage($content)
    {
        if (!function_exists("callback")) {
            function callback($matches)
            {
                $loadingImg = STATIC_URL1 . '/global/images/loading/search_loding.gif';
                return $matches[1] . $loadingImg . '" data-original="' . getStaticUrl($matches[4]) . $matches[4];
            }
        }
        $preg = "#((href|src)=\"|url)([\(]?[\s]*)((/fashion/fashion_shows/|/009/)([^\"|\']*?)\.(jpg|png|gif|jpeg))([\)]?)#i";
        $content = preg_replace($preg, "$1$3" . STATIC_URL1 . "$4$8", $content);
        $content = preg_replace_callback($preg, 'callback', $content);

        return $content;
    }

    public function getExistReportBookIds(array $report_lists)
    {
        $iBooks = [];
        foreach ($report_lists as $item) {
            $iBooks[] = $item['list']['iBook'];
        }
        $iBooks = array_unique(array_filter($iBooks));
        if (!empty($iBooks)) {
            $sql = 'SELECT id FROM t_report_book WHERE id IN(' . implode(',', $iBooks) . ') AND iStatus=0';
            $rows = $this->db()->query($sql)->result_array();
            return empty($rows) ? [] : array_column($rows, 'id');
        } else {
            return [];
        }
    }

    public function getReportBookId($table, $id)
    {
        $iBooks = array();
        $iBook = 0;
        if ($table == 't_trend_report' && !empty($id)) {
            $where = is_array($id) ? ('iTopicId IN(' . implode(',', $id) . ')') : 'iTopicId=' . intval($id);
            $sql = "SELECT iBook FROM {$table} WHERE {$where}";
            if (is_array($id)) {
                $rows = $this->db()->query($sql)->result_array();
                foreach ($rows as $row) {
                    $iBooks[] = $row['iBook'];
                }
            } else {
                $row = $this->db()->query($sql)->row_array();
                $iBook = $row['iBook'];
            }
        }
        return is_array($id) ? $iBooks : $iBook;
    }

    //获取报告书籍目录
    public function getReportBookCatalog($book_id)
    {
        if (empty($book_id)) {
            return [];
        }
        $sql = "SELECT * FROM t_report_book WHERE id=$book_id AND iStatus=0";
        $row = $this->db()->query($sql)->row_array();
        if (empty($row)) {
            return array();
        }
        $row['sCatalog'] = json_decode($row['sCatalog'], true);
        $all_report_ids = array();
        foreach ($row['sCatalog'] as $item) {
            $all_report_ids[] = $item['report_ids'];
        }
        $all_report_ids = implode(',', $all_report_ids);
        $all_report_title = $this->getReportTiles($all_report_ids);
        foreach ($row['sCatalog'] as &$item) {
            $report_ids = explode(',', $item['report_ids']);
            foreach ($report_ids as $report_id) {
                if (!empty($all_report_title[$report_id])) {
                    $item['reports'][] = array(
                        'id' => $report_id,
                        'title' => $all_report_title[$report_id]['sTitle'],
                        'link' => "/details/report/t_report-id_{$report_id}-col_{$all_report_title[$report_id]['iOriginColumn']}/",
                    );
                }
            }
        }
        return $row;
    }

    private function getReportTiles($report_ids_str)
    {
        $result = array();
        if (!empty($report_ids_str)) {
            $report_ids = explode(',', $report_ids_str);
            $result = array_fill_keys($report_ids, null);
            $sql = "SELECT iTopicId,sTitle,iOriginColumn FROM t_trend_report WHERE iTopicId IN($report_ids_str) AND iStatus=0";
            $rows = $this->db()->query($sql)->result_array();
            foreach ($rows as $row) {
                $result[$row['iTopicId']] = $row;
            }
            $result = array_filter($result);
        }
        return $result;
    }

    /**
     * 获取报告信息
     * @param int $id
     * @return array|int
     */
    public function getTrendReport($id = 0, $col = 0)
    {
        $this->load->driver('cache');
        $table = 't_trend_report';
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20171218_' . $table . '_' . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            $sql = "SELECT * FROM t_trend_report WHERE iTopicId=? AND iStatus!=1";
            $query = $this->db()->query($sql, [$id]);
            $num = $query->num_rows();
            if ($num === 0) {
                return false;
            } else {
                $row = $query->row_array();
                $res = [];
                $res['id'] = $id;
                $res['iViewCount'] = $row['iViewCount'];

                // 品牌
                $res['iBrandTid'] = $row['iBrandTid'];
                $res['brandName'] = GetCategory::getBrandOtherFormId($row['iBrandTid'], 'en_cn_brand');

                $res['sLiveUrl'] = $row['sLiveUrl'];// 视频直播链接
                $res['dLiveStartTime'] = $row['dLiveStartTime'] ? $row['dLiveStartTime'] : '';// 视频直播开始时间
                $res['dLiveEndTime'] = $row['dLiveEndTime'] ? $row['dLiveEndTime'] : '';// 视频直播结束时间

                $res['table'] = $table;
                $res['authorPenName'] = $this->getPenNameById($row['iPublisher']);
                $res['iBook'] = $row['iBook'];//报告书籍ID
                if ($col) {
                    $res['col'] = $col;
                } else {
                    $colInfo = $this->getColFromSolr($table, $id);
                    $res['col'] = $colInfo[1];
                }
                $res['colsName'] = GetCategory::getOtherFromColId($res['col'], 'sName');

                $res['gender'] = $row['iGender'];
                $res['industry'] = $row['sIndustry'];
                $res['colPid'] = $colInfo[0];
                $res['colPsName'] = GetCategory::getOtherFromColId($res['colPid'], 'sName');

                $res['title'] = htmlspecialchars(stripslashes($row['sTitle']));
                $res['description'] = strip_tags($row['sDesc']);

                $res['updateDate'] = date('Y年m月d日', strtotime($row['dPubTime']));
                $res['iCreateTime'] = strtotime($row['dPubTime']); // 发布时间
                $res['bigImgPath'] = $row['sBigImgPath'];

                $this->load->model('common_model');
                $res['colLink'] = $this->common_model->getLink($res['col']);
                // 行业
                $res['industryInfo'] = [];
                if ($row['sIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($row['sIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $res['industryInfo'][$key]['sName'] = $industryArr['sName'][$key];
                            $res['industryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'ind', $item);
                        }
                    }
                }
                $res['industryText'] = isset($res['industryInfo'][0]['sName']) ? $res['industryInfo'][$key]['sName'] : '';
                //9:灵感报告 11:趋势 12:分析
                // colid:90 pid:1   pid:2
                $collectType = $this->getCollectTypeByColumnId($res['col']);
                $res['colpara'] = $res['col'] . '-' . getProductTableName($table) . '-' . $id . '-' . $collectType;
                //季节
                $res['iSeason'] = $row['iSeason'];
                if ($res['iSeason']) {
                    $seasonArr = GetCategory::getOtherFromIds($res['iSeason'], ['sName'], 'array');
                    $res['iSeason_text'] = $seasonArr['sName'][0];
                    $res['seasonLink'] = $this->common_model->getLink($res['col'], '', 'sea', $res['iSeason']);
                } else {
                    $res['iSeason_text'] = '';
                    $res['seasonLink'] = '';
                }
                //性别
                $res['genderInfo'] = [];
                if ($row['iGender']) {
                    $genderArr = GetCategory::getOtherFromIds($row['iGender'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($genderArr)) {
                        foreach ($genderArr['iAttributeId'] as $key => $val) {
                            $res['genderInfo'][$key]['sName'] = $genderArr['sName'][$key];
                            $res['genderInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'gen', $genderArr['iAttributeId'][$key]);
                        }
                    }
                }
                $res['genderText'] = isset($res['genderInfo'][0]['sName']) ? $res['genderInfo'][0]['sName'] : '';
                //单品
                $res['categoryInfo'] = [];
                if ($row['sCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($row['sCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $res['categoryInfo'][$key]['sName'] = $categoryArr['sName'][$key];
                            $res['categoryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'cat', $item);
                        }
                    }
                }
                //关联标签
                $res['tagInfo'] = [];
                $sql = "SELECT sLabelsIds FROM t_trend_report_label WHERE iTopicId = ? AND iSubTopicId=0;";
                $query = $this->db()->query($sql, [$id]);
                $labelRow = $query->row_array();
                $row['sRelationLabels'] = $labelRow['sLabelsIds'];
                if ($row['sRelationLabels']) {
                    $sRelationLabels = $this->getLabelsByIds($row['sRelationLabels']);
                    if (is_array($sRelationLabels)) {
                        foreach ($sRelationLabels as $key => $item) {
                            $res['tagInfo'][$key]['sName'] = $item['sLabelName'];
                            $res['tagInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'key', urlencode($item['sLabelName']));
                        }
                    }
                }
                //列表页封面图，显示在中间页
                $_path = getImagePath($table, $row);
                $res['cover'] = getFixedImgPath($_path['cover']);
                //$res['cover'] = getFixedImgPath($row['sImgPath']);

                // 获取更多标签信息
                $this->getMoreReportInfo($row, $res);

                //是否推荐
                $tmpRes = OpPopFashionMerger::getProductData($id, $table);
                $res['iReconmmendMutual'] = $tmpRes[$id]['iReconmmendMutual'];
                unset($tmpRes);

                $subReoprtPages = json_decode($row['sUniVal'], true);
                // 子主题页排序BEGIN
                if (is_array($subReoprtPages)) {
                    foreach ($subReoprtPages as $key => $sub) {
                        $volume = [];
                        $pageSorts = [];
                        foreach ($sub as $pageSort => $page) {
                            $volume[] = intval($page['pageSort']);
                            $pageSorts[] = intval($pageSort);
                        }
                        if (array_sum($volume) > 0) {
                            // array_multisort($volume, SORT_DESC, $subReoprtPages[$key]);
                        }
                        array_multisort($volume, SORT_DESC, $pageSorts, SORT_ASC, $subReoprtPages[$key]);
                    }
                }

                // 子主题页排序END
                $sql = "SELECT * FROM t_trend_subreport WHERE iTopicId=? AND iStatus!=1";
                $result = $this->query($sql, [$id]);

                $res['subTopic'] = [];
                foreach ($result as $key => $val) {
                    array_push($res['subTopic'], $val);
                    $res['subTopic'][$key]['subPage'] = isset($subReoprtPages[$key]) ? array_values($subReoprtPages[$key]) : [];
                }

                $sql = "SELECT sZipname,iExpansion,iImgType FROM t_trend_subreport_page WHERE iTopicId=? AND iPageType=1 ORDER BY iPageId ASC";
                $sZipnameRes = $this->query($sql, [$id]);

                $i = 0;
                foreach ($res['subTopic'] as $key => $subTopic) {
                    foreach ($subTopic['subPage'] as $k => $page) {
                        if ($page['pageType'] == 1) {
                            $res['subTopic'][$key]['subPage'][$k]['zipname'] = $sZipnameRes[$i]['sZipname'];
                            $res['subTopic'][$key]['subPage'][$k]['expansion'] = $sZipnameRes[$i]['iExpansion'];
                            $res['subTopic'][$key]['subPage'][$k]['imgtype'] = $sZipnameRes[$i]['iImgType'];
                            $i++;
                        } else {
                            $res['subTopic'][$key]['subPage'][$k]['zipname'] = '';
                            $res['subTopic'][$key]['subPage'][$k]['expansion'] = 1;
                            $res['subTopic'][$key]['subPage'][$k]['imgtype'] = 1;
                        }
                        $res['subTopic'][$key]['subPage'][$k]['content'] = $page['content'] ? preg_replace("#(=\")([\s]*/fashion/fashion_shows/image/(.*?)\.(jpg|JPG))#", "$1" . STATIC_URL1 . "$2", stripslashes($page['content'])) : '';
                        // 款式图
                        if ($page['pageType'] == 1) {
                            if (isset($page['stylePic']) && is_array($page['stylePic'])) {
                                // 款式图排序BEGIN
                                $picSort = [];
                                foreach ($page['stylePic'] as $k2 => $pic) {
                                    if ($pic['indexShow'] == 0) {
                                        // 没有添加到报告中的图片不显示
                                        unset($res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]);
                                        unset($page['stylePic'][$k2]);
                                        continue;
                                    }
                                    $picSort[] = $pic['imgSort'];
                                }
                                array_multisort($picSort, SORT_DESC, $res['subTopic'][$key]['subPage'][$k]['stylePic']);
                                array_multisort($picSort, SORT_DESC, $page['stylePic']);
                                // 款式图排序END

                                foreach ($page['stylePic'] as $k2 => $pic) {
                                    if ($pic['indexShow'] == 0) {
                                        // 没有添加到报告中的图片不显示
                                        continue;
                                    }
                                    $old_id = $pic['id'];

                                    $eachImg = OpPopFashionMerger::getProductData($old_id, $pic['table'], self::MEM_CACHE);
                                    $eachImg = $eachImg[$old_id];
                                    // 图片所在的栏目id
                                    $imgCol = $this->getColFromSolr($pic['table'], $eachImg['id']);
                                    $imgCol = $imgCol[1];
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['col'] = $imgCol;

                                    if ($pic['table'] == 'product_graphicitem') {
                                        $arr = OpPopFashionMerger:: getPatternAttribute($eachImg['memo']);
                                        $this->load->model('category_model');
                                        $sPatternUse = $this->category_model->getAll('sPatternUse');
                                        //图案内容
                                        // $PatternID = current(explode(',',$eachImg['sPatternContent']));
                                        $PatternID = $eachImg['sPatternContent'];
                                        $sPatternContent = trim(GetCategory::getOtherFromIds($PatternID, ['sName']));
                                        $sPatternUseName = $sPatternUse[$arr['sPatternUse']];

                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['genderName'] = $sPatternContent;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sCategoryName'] = $sPatternUseName;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sSubcategoryName'] = '';
                                    } else {
                                        // 单品id
                                        $itemId = $eachImg['iCategory'];
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iCategory'] = 0;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sCategoryName'] = '';
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['categoryLink'] = '';
                                        $categoryArr = GetCategory::getOtherFromIds($itemId, ['iAttributeId', 'sName'], 'array');
                                        if ($categoryArr) {
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iCategory'] = $categoryArr['iAttributeId'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sCategoryName'] = $categoryArr['sName'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['categoryLink'] = $this->common_model->getLink($imgCol, '', 'cat', $itemId);
                                        }
                                        // 品名id
                                        $prodNameId = $eachImg['iSubcategory'];
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iSubcategory'] = 0;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sSubcategoryName'] = '';
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['subCategoryLink'] = '';
                                        $subCategoryArr = GetCategory::getOtherFromIds($prodNameId, ['iAttributeId', 'sName'], 'array');
                                        if ($subCategoryArr) {
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iSubcategory'] = $subCategoryArr['iAttributeId'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sSubcategoryName'] = $subCategoryArr['sName'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['subCategoryLink'] = $this->common_model->getLink($imgCol, '', 'cat', $prodNameId);
                                        }
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['genderName'] = GetCategory::getNewEnName(1, $eachImg['typ'], 'sName');
                                    }
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['link'] = $this->getDetailLink('style', $pic['table'], $old_id, $imgCol);
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['colsName'] = GetCategory::getOtherFromColId($imgCol, 'sName');
                                    $brandInfo = OpPopFashionMerger::getBrandData($eachImg['brand_tid']);
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['brandName'] = $brandInfo['name'];
                                }
                            }
                        }
                    }
                }
                // flash报告暂时只有flash子报告页(iPageType=4)
                $res['iIsFlash'] = $row['iIsFlash'];
                if ($row['iIsFlash']) {
                    $subPages = $this->getSubReportPages($row['iTopicId']);
                    foreach ($res['subTopic'] as $key => $subTopic) {
                        if (key_exists($subTopic['iSubTopicId'], $subPages)) {
                            foreach ($subPages[$subTopic['iSubTopicId']] as $subPage) {
                                $_sContent = json_decode($subPage['sContent'], true);
                                // 格子数据中的详情链接
                                foreach ($_sContent['grids'] as &$grid) {
                                    // 款式库的图
                                    $_popId = $grid['photo']['id'];
                                    if ($grid['photo']['isLocal'] === false && $_popId) {
                                        // $_popId: t_product_tideleader-id_3150-col_57
                                        $_aPopId = explode('-', $_popId);
                                        $_table = substr($_aPopId[0], 2); // product_tideleader
                                        // $_id = substr($_aPopId[1], 3); // 3150
                                        // $_col = substr($_aPopId[2], 4); // 57

                                        // 表名转换
                                        $_table = getProductTableName($_table);
                                        $_aPopId[0] = 't_' . $_table;
                                        $_popId = implode('-', $_aPopId);
                                        $grid['photo']['id'] = $_popId;
                                        // 格子中图片详情链接, 如果没有手动输入链接则用图片对应报告的详情链接
                                        if (trim($grid['photo']['link']) == '') {
                                            $grid['photo']['link'] = '/details/style/' . $_popId . '/';
                                        }
                                        // 格子中图片下载链接
                                        // $grid['photo']['download'] = '/download/dlsingle/?dl_link=' . urlencode(STATIC_URL1 . $grid['photo']['big'] . '&t=' . time());
                                        $grid['photo']['download'] = '/details/style/' . $_popId . '/';
                                    } else {
                                        // 格子中图片下载链接
                                        $grid['photo']['download'] = '/download/dlsingle/?dl_link=' . urlencode(STATIC_URL1 . $grid['photo']['big']);
                                    }
                                    // 公共重命名规则
                                    $brand_str = !empty($grid['photo']['brand']) ? $grid['photo']['brand'] : (!empty($res['brandName']) ? $res['brandName'] : '');
                                    if (!empty($grid['photo']['big']) && !empty($brand_str)) {
                                        $grid['photo']['rename'] = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($brand_str)) . '_' . $subTopic['iSubTopicId'] . '.' . pathinfo(basename($grid['photo']['big']), PATHINFO_EXTENSION);
                                    }
                                }
                                unset($_sContent['gridSources']);
                                $subPage['sContent'] = $_sContent;
                                $res['subTopic'][$key]['subPage'][] = $subPage;
                            }
                        }
                    }
                }
                $res['iRecChannel'] = OpPopFashionMerger::getRecommend($table, $row)['iRecChannel'];
                if (!$this->noCache) {
                    $this->cache->memcached->save($mem_key, $res, self::MEM_EXPIRE);
                }
            }
        }

        $res['viewNum'] = $this->statistics_model->getViews($table, $id, $res);

        // 报告直播
        $live_time = date("Y-m-d H:i:s");
        if (!empty($res['sLiveUrl'])) {// 视频直播状态
            if (strtotime($live_time) <= strtotime($res['dLiveStartTime'])) {
                $res['live_status'] = 1; // 立即预约
            } elseif (strtotime($live_time) >= strtotime($res['dLiveEndTime'])) {
                $res['live_status'] = 2;// 已结束(回看解读)
            } elseif ((strtotime($live_time) <= strtotime($res['dLiveEndTime'])) && (strtotime($live_time) >= strtotime($res['dLiveStartTime']))) {
                $res['live_status'] = 3;// 进行中(立即收看)
            }
        }

        // 取pdf和图片打包路径
        $sql = "SELECT iPdfStatus,sPdfFilePath,iImgStatus,sImgPackPath FROM `fashion`.`t_trend_report` WHERE iTopicId=? AND iStatus!=1";
        $query = $this->db()->query($sql, [$id]);
        $row = $query->row_array();
        if ($row) {
            $res['iPdfStatus'] = $row['iPdfStatus'];
            $res['sPdfFilePath'] = $row['iPdfStatus'] == 2 ? $row['sPdfFilePath'] : '';
            $res['iImgStatus'] = $row['iImgStatus'];
            $res['sImgPackPath'] = $row['iImgStatus'] == 2 ? $row['sImgPackPath'] : '';
            $res['pdfPath'] = $res['sPdfFilePath'] ? $res['sPdfFilePath'] : 0;
        } else {
            $res['iPdfStatus'] = 0;
            $res['sPdfFilePath'] = '';
            $res['iImgStatus'] = 0;
            $res['sImgPackPath'] = '';
            $res['pdfPath'] = 0;
        }

        return $res;
    }

    /**
     * 获取子报告页
     * @param $iTopicId
     * @param int $iSubTopicId 子报告id, 可选, 默认0取主报告下所有子报告页
     * @param int $iPageType 子报告页类型, 可选, 默认0取全部类型
     * @return array
     */
    public function getSubReportPages($iTopicId, $iSubTopicId = 0, $iPageType = 0)
    {
        if (!$iTopicId) {
            return [];
        }
        $where = [];
        $where[] = ' iTopicId=' . $iTopicId . ' ';
        $where[] = ' iStatus<>1';
        if ($iSubTopicId) {
            $where[] = ' iSubTopicId=' . $iSubTopicId . ' ';
        }
        if ($iPageType) {
            $where[] = ' iPageType=' . $iPageType . ' ';
        }
        $_where = empty($where) ? '' : implode(' AND ', $where);
        $fields = 'iPageId,iTopicId,iSubTopicId,sTitle,iPageType,iPageType as pageType,iSort,sContent,iPriority';
        $sql = "SELECT {$fields} FROM `fashion`.`t_trend_subreport_page` WHERE {$_where}  ORDER BY iSort DESC LIMIT 100";
        $query = $this->db()->query($sql);
        $num = $query->num_rows();
        if ($num === 0) {
            return [];
        }
        $result = [];
        $rows = $query->result_array();
        foreach ($rows as $row) {
            if (!isset($result[$row['iSubTopicId']])) {
                $result[$row['iSubTopicId']] = [];
            }
            $result[$row['iSubTopicId']][] = $row;
        }
        return $result;
    }

    /**
     * 获取mostrend报告类数据信息
     * @param int $id
     * @return array|int
     */
    public function getMosReport($id = 0, $col = 0)
    {
        $this->load->driver('cache');
        $id = intval($id);
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20171218_mostrend_content_' . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            // 查主表数据
            // status : '0为草稿1为定时2为发布3为删除';
            $sql = "SELECT * FROM `trends_new`.`mostrend_content` WHERE id=? AND `status`=2";
            $query = $this->db()->query($sql, [$id]);
            $num = $query->num_rows();
            if ($num === 0) {
                return false;
            } else {
                $row = $query->row_array();
                $res = [];
                $res['id'] = $id;
                $table = 'mostrend_content';
                $res['table'] = 'mostrend_content';
                $res['authorPenName'] = '';

                if ($col) {
                    $res['col'] = $col;
                } else {
                    $colInfo = $this->getColFromSolr($table, $id);
                    $res['col'] = $colInfo[1];
                }

                $res['colsName'] = GetCategory::getOtherFromColId($res['col'], 'sName');
                $res['click_rate'] = $row['click_rate'];
                $res['gender'] = $row['channel'];
                $res['industry'] = $row['iIndustry'];
                $res['brandName'] = !empty($row['brand']) ? $row['brand'] : '';
                $res['colPid'] = $colInfo[0];
                $res['colPsName'] = GetCategory::getOtherFromColId($res['colPid'], 'sName');

                $res['title'] = htmlspecialchars(stripslashes($row['title']));
                $res['description'] = strip_tags($row['title_describe']);
                $res['updateDate'] = date('Y年m月d日', strtotime($row['release_time']));
                $res['iCreateTime'] = strtotime($row['release_time']); //创建时间
                $res['sRelationLabels'] = $row['sRelationLabels'];

                //是否推荐
                $tmpRes = OpPopFashionMerger::getProductData($id, $table);
                $res['iReconmmendMutual'] = $tmpRes[$id]['iReconmmendMutual'];
                unset($tmpRes);

                $this->load->model('common_model');
                $res['colLink'] = $this->common_model->getLink($res['col']);
                // 行业
                $res['industryInfo'] = [];
                if ($row['iIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($row['iIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $res['industryInfo'][$key]['sName'] = $industryArr['sName'][$key];
                            $res['industryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'ind', $item);
                        }
                    }
                }
                $res['industryText'] = isset($res['industryInfo'][0]['sName']) ? $res['industryInfo'][$key]['sName'] : '';
                //9:灵感报告 11:趋势 12:分析
                // colid:90 pid:1   pid:2
                $collectType = $this->getCollectTypeByColumnId($res['col']);
                $res['colpara'] = $res['col'] . '-' . getProductTableName($table) . '-' . $id . '-' . $collectType;
                //季节
                $res['iSeason_text'] = GetCategory::getNewEnName(5, $row['popular_time']);
                $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$row['popular_time']}");
                $res['iSeason'] = isset($seasonId[0]) ? $seasonId[0] : 0;
                if ($res['iSeason']) {
                    $res['seasonLink'] = $this->common_model->getLink($res['col'], '', 'sea', $res['iSeason']);
                } else {
                    $res['seasonLink'] = '';
                }
                //性别
                $res['genderInfo'] = [];
                if ($row['sGender']) {
                    $typeArr = explode(',', trim($row['sGender'], ','));
                    if (is_array($typeArr)) {
                        $gendersarr = GetCategory::getGender($res['col']);
                        foreach ($typeArr as $key => $value) {
                            if ($gendersarr) {
                                $res['genderInfo'][$key]['sName'] = $gendersarr[$value];
                                $res['genderInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'gen', $value);
                            }
                        }
                    }
                } elseif ($row['channel']) {
                    $typeArr = explode(',', $row['channel']);
                    if (is_array($typeArr)) {
                        foreach ($typeArr as $key => $value) {
                            $genderId = GetCategory::getIdsFrom(1, 'sOriginalName,' . $value, $res['col']);
                            if ($genderId) {
                                $genderArr = GetCategory::getOtherFromIds($genderId[0], ['iAttributeId', 'sName'], 'array');
                                $res['genderInfo'][$key]['sName'] = $genderArr['sName'][0];
                                $res['genderInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'gen', $genderArr['iAttributeId'][0]);
                            }
                        }
                    }
                }
                $res['genderText'] = isset($res['genderInfo'][0]['sName']) ? $res['genderInfo'][0]['sName'] : '';
                //单品
                $res['categoryInfo'] = [];
                if ($row['iCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($row['iCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $res['categoryInfo'][$key]['sName'] = $categoryArr['sName'][$key];
                            $res['categoryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'cat', $item);
                        }
                    }
                }
                //关联标签
                $res['tagInfo'] = [];
                if ($row['sRelationLabels']) {
                    $sRelationLabels = $this->getLabelsByIds($row['sRelationLabels']);
                    if (is_array($sRelationLabels)) {
                        foreach ($sRelationLabels as $key => $item) {
                            $res['tagInfo'][$key]['sName'] = $item['sLabelName'];
                            $res['tagInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'key', urlencode($item['sLabelName']));
                        }
                    }
                }

                $path = $row['path'];
                if ($row['clu_child'] == 'news') {
                    $res['isPic'] = 0;
                    $res['reportContent'] = $this->replaceFsImage($row['content']);
                } else {
                    $res['isPic'] = 1;

                    //主题下面的图片和素材图
                    $pictg = [];
                    $sql = "SELECT * FROM `trends_new`.`picture` WHERE mostrendid=? AND `status`=2 ORDER BY id ASC";
                    $query = $this->db()->query($sql, [$id]);
                    $num = $query->num_rows();
                    if ($num == 0) {
                        $picture = [];
                        $pic_name = json_decode($row['pic_name'], true);
                        foreach ($pic_name as $key => $pic) {
                            $picture[$key]['image'] = STATIC_URL1 . '/' . $path . "mid/" . md5(pathinfo($pic, PATHINFO_FILENAME) . $path . 'mid!@#') . '.jpg';
                        }
                    } else {
                        $picture = $query->result_array();
                        $picture = $this->_findChildren($picture, 0, "pictureid");
                        $res['images'] = [];
                        if ($row['pic_name']) {
                            $pic_name = json_decode($row['pic_name'], true);
                            $bigPics = $this->getMostrendPicName($path, $pic_name);
                            $res['images'] = $bigPics;
                        }
                        foreach ($picture as $key => $value) {
                            $picture[$key]['image'] = $res['images'][$key];
                        }
                    }
                    $res['picture'] = $picture;
                }
                //地区
                $res['iRegion_text'] = '';
                if ($row['iRegion']) {
                    $res['iRegion_text'] = GetCategory::getFieldFromId($row['iRegion']);
                }

                // 获取更多标签信息
                $this->getMoreReportInfo($row, $res);

                $res['iRegion'] = $row['iRegion'];
                //列表页封面图，显示在中间页
                $_path = getImagePath($table, $row);
                $res['cover'] = getFixedImgPath($_path['cover']);
                //$res['cover'] = getFixedImgPath($path . '/' . $row['big_thumbnail']);
                $res['pdfPath'] = $this->getMosPdfPath($row['path'], $row['pdf_name']);
                $res['iRecChannel'] = OpPopFashionMerger::getRecommend($table, $row)['iRecChannel'];
                $this->cache->memcached->save($mem_key, $res, self::MEM_EXPIRE);
            }
        }
        $res['viewNum'] = $this->statistics_model->getViews($res['table'], $res['id'], $res);
        // $this->db()->close();
        return $res;
    }

    /**
     * 获取关键主题的数据信息
     * @param int $id
     * @return array|int
     */
    public function getSpecialtopic($id = 0, $col = 0)
    {
        $this->load->driver('cache');

        $table = 'specialtopic';
        $tableImg = 'specialtopic_img';
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20171218_' . $table . '_' . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            $sql = "SELECT * FROM $table WHERE id = ? AND pid=0";
            $query = $this->db()->query($sql, [$id]);
            $num = $query->num_rows();
            if ($num === 0) {
                return false;
            } else {
                $row = $query->row_array();
                $res = [];
                $res['id'] = $id;
                $res['table'] = $table;
                $res['authorPenName'] = $this->getPenNameById($row['iPublisher']);

                if ($col) {
                    $res['col'] = $col;
                } else {
                    $colInfo = $this->getColFromSolr($table, $id);
                    $res['col'] = $colInfo[1];
                }
                $res['colsName'] = GetCategory::getOtherFromColId($res['col'], 'sName');

                $res['views'] = $row['views'];
                $res['gender'] = $row['iGender'];
                $res['industry'] = $row['iIndustry'];
                $res['colPid'] = $colInfo[0];
                $res['colPsName'] = GetCategory::getOtherFromColId($res['colPid'], 'sName');

                // 品牌
                $res['iBrandTid'] = $row['iBrandTid'];
                $res['brandName'] = GetCategory::getBrandOtherFormId($row['iBrandTid'], 'en_cn_brand');

                $res['title'] = htmlspecialchars(stripslashes($row['title']));
                $res['description'] = trim(strip_tags($row['description'])); //摘要
                $res['updateDate'] = date('Y年m月d日', strtotime($row['addtime']));
                $res['iCreateTime'] = strtotime($row['addtime']); //创建时间
                $res['bigImgPath'] = $row['special_coverpic'];
                $res['subTopic'] = [];
                $this->load->model('common_model');
                $res['colLink'] = $this->common_model->getLink($res['col']);
                // 行业
                $res['industryInfo'] = [];
                if ($row['iIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($row['iIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $res['industryInfo'][$key]['sName'] = $industryArr['sName'][$key];
                            $res['industryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'ind', $item);
                        }
                    }
                }
                $res['industryText'] = isset($res['industryInfo'][0]['sName']) ? $res['industryInfo'][$key]['sName'] : '';
                //9:灵感报告 11:趋势 12:分析
                // colid:90 pid:1   pid:2
                $collectType = $this->getCollectTypeByColumnId($res['col']);
                $res['colpara'] = $res['col'] . '-' . getProductTableName($table) . '-' . $id . '-' . $collectType;
                //季节
                $res['iSeason'] = $row['iSeason'];
                if ($res['iSeason']) {
                    $seasonArr = GetCategory::getOtherFromIds($res['iSeason'], ['sName'], 'array');
                    $res['iSeason_text'] = $seasonArr['sName'][0];
                    $res['seasonLink'] = $this->common_model->getLink($res['col'], '', 'sea', $res['iSeason']);
                } else {
                    $res['iSeason_text'] = '';
                    $res['seasonLink'] = '';
                }
                //性别
                $res['iGender'] = $row['iGender'];
                $res['genderInfo'] = [];
                if ($row['iGender']) {
                    $genderArr = GetCategory::getOtherFromIds($row['iGender'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($genderArr)) {
                        foreach ($genderArr['iAttributeId'] as $key => $val) {
                            $res['genderInfo'][$key]['sName'] = $genderArr['sName'][$key];
                            $res['genderInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'gen', $genderArr['iAttributeId'][$key]);
                        }
                    }
                }
                $res['genderText'] = isset($res['genderInfo'][0]['sName']) ? $res['genderInfo'][0]['sName'] : '';
                //单品
                $res['iCategory'] = $row['iCategory'];
                $res['categoryInfo'] = [];
                if ($row['iCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($row['iCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $res['categoryInfo'][$key]['sName'] = $categoryArr['sName'][$key];
                            $res['categoryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'cat', $item);
                        }
                    }
                }
                //关联标签
                $res['tagInfo'] = [];

                if ($row['sRelationLabels']) {
                    $sRelationLabels = $this->getLabelsByIds($row['sRelationLabels']);
                    if (is_array($sRelationLabels)) {
                        foreach ($sRelationLabels as $key => $item) {
                            $res['tagInfo'][$key]['sName'] = $item['sLabelName'];
                            $res['tagInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'key', urlencode($item['sLabelName']));
                        }
                    }
                }
                //地区
                $res['iRegion_text'] = GetCategory::getFieldFromId($row['iRegion']);
                $res['iRegion'] = $row['iRegion'];
                //列表页封面图，显示在中间页

                //列表页封面图，显示在中间页
                $_path = getImagePath($table, $row);
                $res['cover'] = getFixedImgPath($_path['cover']);
                //$res['cover'] = getFixedImgPath($row['sCoverImg']);

                // 获取更多标签信息
                $this->getMoreReportInfo($row, $res);

                //是否推荐
                $tmpRes = OpPopFashionMerger::getProductData($id, $table);
                $res['iReconmmendMutual'] = $tmpRes[$id]['iReconmmendMutual'];
                unset($tmpRes);

                $sql = "SELECT id, stitle sTitle, sdescription sDesc,zipname FROM {$table} WHERE pid = {$id} ORDER BY id ASC";
                $query = $this->db()->query($sql);
                $result = $query->result_array();

                foreach ($result as $key => $val) {
                    $val['sDesc'] = htmlspecialchars_decode(stripcslashes($val['sDesc']));
                    array_push($res['subTopic'], $val);

                    $specialId = $val['id'];
                    $sql = "SELECT  channel `table`, old_id id, spic_path imgurl FROM {$tableImg} WHERE special_id = ? ORDER BY id ASC";
                    $query = $this->db()->query($sql, [$specialId]);
                    $imgResult = $query->result_array();

                    foreach ($imgResult as $k => $img) {
                        $old_id = $img['id'];
                        $eachImg = OpPopFashionMerger::getProductData($old_id, $img['table'], self::MEM_CACHE);
                        $eachImg = $eachImg[$old_id];
                        // 图片所在的栏目id
                        $imgCol = $this->getColFromSolr($img['table'], $eachImg['id']);
                        $imgCol = $imgCol[1];
                        $imgResult[$k]['col'] = $imgCol;
                        // 单品id
                        $itemId = intval($eachImg['iCategory']);
                        $imgResult[$k]['iCategory'] = 0;
                        $imgResult[$k]['sCategoryName'] = '';
                        $imgResult[$k]['categoryLink'] = '';
                        if ($itemId) {
                            $categoryArr = GetCategory::getOtherFromIds($itemId, ['iAttributeId', 'sName'], 'array');
                            if ($categoryArr) {
                                $imgResult[$k]['iCategory'] = $categoryArr['iAttributeId'][0];
                                $imgResult[$k]['sCategoryName'] = $categoryArr['sName'][0];
                                $imgResult[$k]['categoryLink'] = $this->common_model->getLink($imgCol, '', 'cat', $itemId);
                                unset($categoryArr);
                            }
                        }
                        // 品名id
                        $prodNameId = $eachImg['iSubcategory'];
                        $imgResult[$k]['iSubcategory'] = 0;
                        $imgResult[$k]['sSubcategoryName'] = '';
                        $imgResult[$k]['subCategoryLink'] = '';
                        if ($prodNameId) {
                            $subCategoryArr = GetCategory::getOtherFromIds($prodNameId, ['iAttributeId', 'sName'], 'array');
                            if ($subCategoryArr) {
                                $imgResult[$k]['iSubcategory'] = $subCategoryArr['iAttributeId'][0];
                                $imgResult[$k]['sSubcategoryName'] = $subCategoryArr['sName'][0];
                                $imgResult[$k]['subCategoryLink'] = $this->common_model->getLink($imgCol, '', 'cat', $prodNameId);
                                unset($subCategoryArr);
                            }
                        }
                        $imgResult[$k]['link'] = $this->getDetailLink('style', $img['table'], $old_id, $imgCol);

                        $imgResult[$k]['colsName'] = GetCategory::getOtherFromColId($imgCol, 'sName');
                        $imgResult[$k]['genderName'] = GetCategory::getNewEnName(1, $eachImg['typ'], 'sName');
                        $brandInfo = OpPopFashionMerger::getBrandData($eachImg['brand_tid']);
                        $imgResult[$k]['brandName'] = $brandInfo['name'];
                    }
                    $res['subTopic'][$key]['images'] = $imgResult;
                    unset($imgResult);
                }
            }
            $res['iRecChannel'] = OpPopFashionMerger::getRecommend($table, $row)['iRecChannel'];
            if (!$this->noCache) {
                $this->cache->memcached->save($mem_key, $res, self::MEM_EXPIRE);
            }
        }
        $res['viewNum'] = $this->statistics_model->getViews($res['table'], $res['id'], $res);
        $res['pdfPath'] = $this->getPdfPath(getProductTableName($table), $id, $col);
        // $this->db()->close();
        return $res;
    }

    public function getSpecialtopicGraphic($id, $col = 0)
    {
        $this->load->driver('cache');
        $table = 'specialtopic_graphic';
        $tableImg = 'specialtopic_img_graphic';
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20171218_' . $table . '_' . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            $sql = "SELECT * FROM $table WHERE id = ? AND pid=0";
            $query = $this->db()->query($sql, [$id]);
            $num = $query->num_rows();
            if ($num === 0) {
                return false;
            } else {
                $row = $query->row_array();

                $res = [];
                $res['id'] = $id;
                $res['table'] = $table;
                $res['authorPenName'] = $this->getPenNameById($row['iPublisher']);

                if ($col) {
                    $res['col'] = $col;
                } else {
                    $colInfo = $this->getColFromSolr($table, $id);
                    $res['col'] = $colInfo[1];
                }
                $res['colsName'] = GetCategory::getOtherFromColId($res['col'], 'sName');

                $res['views'] = $row['views'];
                $res['gender'] = $row['iGender'];
                $res['industry'] = $row['iIndustry'];
                $res['colPid'] = $colInfo[0];
                $res['colPsName'] = GetCategory::getOtherFromColId($res['colPid'], 'sName');

                $res['title'] = htmlspecialchars(stripslashes($row['title']));
                $res['description'] = trim(strip_tags(htmlspecialchars_decode(stripcslashes($row['description'])))); //摘要
                $res['updateDate'] = date('Y年m月d日', strtotime($row['addtime']));
                $res['iCreateTime'] = strtotime($row['addtime']); //创建时间
                $res['bigImgPath'] = $row['special_coverpic'];
                $res['subTopic'] = [];

                $this->load->model('common_model');
                $res['colLink'] = $this->common_model->getLink($res['col']);
                // 行业
                $res['industryInfo'] = [];
                if ($row['iIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($row['iIndustry'], ['iAttributeId', 'sName'], 'array');
                    foreach ($industryArr['iAttributeId'] as $key => $item) {
                        $res['industryInfo'][$key]['sName'] = $industryArr['sName'][$key];
                        $res['industryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'ind', $item);
                    }
                }
                $res['industryText'] = isset($res['industryInfo'][0]['sName']) ? $res['industryInfo'][$key]['sName'] : '';
                //9:灵感报告 11:趋势 12:分析
                // colid:90 pid:1   pid:2
                $collectType = $this->getCollectTypeByColumnId($res['col']);
                $res['colpara'] = $res['col'] . '-' . getProductTableName($table) . '-' . $id . '-' . $collectType;
                //季节
                $res['iSeason'] = $row['iSeason'];
                if ($res['iSeason']) {
                    $seasonArr = GetCategory::getOtherFromIds($res['iSeason'], ['sName'], 'array');
                    $res['iSeason_text'] = $seasonArr['sName'][0];
                    $res['seasonLink'] = $this->common_model->getLink($res['col'], '', 'sea', $res['iSeason']);
                } else {
                    $res['iSeason_text'] = '';
                    $res['seasonLink'] = '';
                }
                //性别
                $res['genderInfo'] = [];
                if ($row['iGender']) {
                    $genderArr = GetCategory::getOtherFromIds($row['iGender'], ['iAttributeId', 'sName'], 'array');
                    foreach ($genderArr['iAttributeId'] as $key => $val) {
                        $res['genderInfo'][$key]['sName'] = $genderArr['sName'][$key];
                        $res['genderInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'gen', $genderArr['iAttributeId'][$key]);
                    }
                }
                $res['genderText'] = isset($res['genderInfo'][0]['sName']) ? $res['genderInfo'][0]['sName'] : '';
                //单品
                $res['categoryInfo'] = [];
                if ($row['iCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($row['iCategory'], ['iAttributeId', 'sName'], 'array');
                    foreach ($categoryArr['iAttributeId'] as $key => $item) {
                        $res['categoryInfo'][$key]['sName'] = $categoryArr['sName'][$key];
                        $res['categoryInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'cat', $item);
                    }
                }
                //关联标签
                $res['tagInfo'] = [];

                if ($row['sRelationLabels']) {
                    $sRelationLabels = $this->getLabelsByIds($row['sRelationLabels']);
                    foreach ($sRelationLabels as $key => $item) {
                        $res['tagInfo'][$key]['sName'] = $item['sLabelName'];
                        $res['tagInfo'][$key]['link'] = $this->common_model->getLink($res['col'], '', 'key', urlencode($item['sLabelName']));
                    }
                }
                //地区，图案主题没有地区字段
                $res['iRegion_text'] = '';
                $res['iRegion'] = 0;
                //列表页封面图，显示在中间页
                $res['cover'] = getFixedImgPath($row['sCoverImg']);

                //是否推荐
                $tmpRes = OpPopFashionMerger::getProductData($id, $table);
                $res['iReconmmendMutual'] = $tmpRes[$id]['iReconmmendMutual'];
                unset($tmpRes);

                // 获取更多标签信息
                $this->getMoreReportInfo($row, $res);

                $sql = "SELECT id, stitle sTitle, sdescription sDesc FROM {$table} WHERE pid = {$id} ORDER BY id ASC";
                $query = $this->db()->query($sql);
                $result = $query->result_array();

                foreach ($result as $key => $val) {
                    $val['sDesc'] = trim(strip_tags(htmlspecialchars_decode(stripcslashes($val['sDesc']))));
                    array_push($res['subTopic'], $val);

                    $specialId = $val['id'];
                    $sql = "SELECT  channel `table`, old_id id, spic_path imgurl FROM {$tableImg} WHERE special_id = ? ORDER BY id ASC";
                    $query = $this->db()->query($sql, [$specialId]);
                    $imgResult = $query->result_array();

                    foreach ($imgResult as $k => $img) {
                        $old_id = $img['id'];
                        $eachImg = OpPopFashionMerger::getProductData($old_id, $img['table'], self::MEM_CACHE);
                        $eachImg = $eachImg[$old_id];
                        // 图片所在的栏目id
                        $imgCol = $this->getColFromSolr($img['table'], $eachImg['id']);
                        $imgCol = $imgCol[1];
                        $imgResult[$k]['col'] = $imgCol;

                        $memoArr = explode(' ', trim($eachImg['memo']));

                        $keyword1 = array_shift($memoArr);
                        $imgResult[$k]['sCategoryName'] = $keyword1;
                        $imgResult[$k]['categoryLink'] = $this->common_model->getLink($imgCol, '', 'key', $keyword1);;

                        $keyword2 = array_shift($memoArr);
                        $imgResult[$k]['sSubcategoryName'] = $keyword2;
                        $imgResult[$k]['categoryLink'] = $this->common_model->getLink($imgCol, '', 'key', $keyword2);
                        $imgResult[$k]['link'] = $this->getDetailLink('style', $img['table'], $old_id, $imgCol);

                        $imgResult[$k]['colsName'] = GetCategory::getOtherFromColId($imgCol, 'sName');
                    }
                    $res['subTopic'][$key]['images'] = $imgResult;
                }
            }
            $res['iRecChannel'] = OpPopFashionMerger::getRecommend($table, $row)['iRecChannel'];
            if (!$this->noCache) {
                $this->cache->memcached->save($mem_key, $res, self::MEM_EXPIRE);
            }
        }
        $res['viewNum'] = $this->statistics_model->getViews($res['table'], $res['id'], $res);
        $res['pdfPath'] = $this->getPdfPath(getProductTableName($table), $id, $col);
        // $this->db()->close();
        return $res;
    }

    /**
     * 获取报告中品牌、品名、标签、视角、栏目等信息
     * @param array $row 原始数据
     * @param array $res 返回结果
     */
    private function getMoreReportInfo(array $row, array &$res)
    {
        $moreTags = [];
        $colId = $res['col'];
        $colPid = GetCategory::getOtherFromColId($colId, 'iColumnPid');
        // 灵感源90、灵感视频116 因为没有父栏目“全部”所以关键词搜索取子栏目
        if ($colId == 90 || $colId == 116) {
            $colPid = $colId;
        }

        // 季节
        if ($res['iSeason']) {
            $seasonArr = GetCategory::getOtherFromIds($res['iSeason'], ['sName'], 'array');
            $res['iSeason_text'] = $seasonArr['sName'][0];
            $res['seasonLink'] = $this->common_model->getLink($colPid, '', 'key', $res['iSeason_text']);
            // 拼一致结构
            $moreTags['infoSeason'][] = [
                'sName' => $res['iSeason_text'],
                'link' => $res['seasonLink']
            ];
        }
        // 品牌
        if ($row['iBrandTid']) {
            $brandName = GetCategory::getBrandOtherFormId($row['iBrandTid'], 'en_cn_brand');
            if (!empty($brandName)) {
                // 拼一致结构
                $moreTags['infoBrand'][] = [
                    'sName' => $brandName,
                    'link' => $this->common_model->getLink($colPid, '', 'key', $brandName)
                ];
            }
        }
        //单品
        if ($row['sCategory']) {
            $retArr = GetCategory::getOtherFromIds($row['sCategory'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr)) {
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoCategory'][] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 品名
        if ($row['sSubCategory']) {
            $retArr = GetCategory::getOtherFromIds($row['sSubCategory'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoSubCategory'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoSubCategory'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 标签
        if (is_array($res['tagInfo']) && $res['tagInfo']) {
            $moreTags['infoRelationLabels'] = [];
            foreach ($res['tagInfo'] as $key => $item) {
                $moreTags['infoRelationLabels'][$key] = [
                    'sName' => $item['sName'],
                    'link' => $this->common_model->getLink($colPid, '', 'key', urlencode($item['sName']))
                ];
            }
        }

        // 行业
        if ($row['sIndustry']) {
            $retArr = GetCategory::getOtherFromIds($row['sIndustry'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr)) {
                $moreTags['infoIndustry'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoIndustry'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 视角
        if ($row['sVisualAngle']) {
            $retArr = GetCategory::getOtherFromIds($row['sVisualAngle'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoVisualAngle'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoVisualAngle'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 栏目
        if ($res['colsName']) {
            // 拼一致结构
            $moreTags['infoColumn'][] = [
                'sName' => $res['colsName'],
                'link' => $res['colLink']
            ];
        }
        // ---------以下分栏目(iOriginColumn)---------
        // 工艺趋势 128 - 工艺类型(iTechnologyType)
        if ($row['iOriginColumn'] == 128 && $row['iTechnologyType']) {
            $retArr = GetCategory::getOtherFromIds($row['iTechnologyType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoTechnologyType'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoTechnologyType'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 企划/组货 21 - 年龄层(sAgeLayer)
        if ($row['iOriginColumn'] == 21 && $row['sAgeLayer']) {
            $retArr = GetCategory::getOtherFromIds($row['sAgeLayer'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoAgeLayer'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoAgeLayer'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 快反应 20 - 地域风格(sRegionalStyle)
        if ($row['iOriginColumn'] == 20 && $row['sRegionalStyle']) {
            $retArr = GetCategory::getOtherFromIds($row['sRegionalStyle'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoRegionStyle'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoRegionStyle'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // T台分析 30 - 时装周名称(sFashionWeek)
        if ($row['iOriginColumn'] == 30 && $row['sFashionWeek']) {
            $retArr = GetCategory::getOtherFromIds($row['sFashionWeek'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoFashionWeek'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoFashionWeek'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 市场分析 33 - 市场类型(sMarketType)（仅显示二级类型）
        if (in_array($row['iOriginColumn'], array(33, 132)) && $row['sMarketType']) {
            $retArr = GetCategory::getOtherFromIds($row['sMarketType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoMarketType'] = [];
                // 仅显示二级类型
                if (count($retArr['iAttributeId']) > 1) {
                    array_shift($retArr['iAttributeId']);
                    array_shift($retArr['sName']);
                }
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoMarketType'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 展会分析 31 - 展会名称(iExhibitionName)
        if ($row['iOriginColumn'] == 31 && $row['iExhibitionName']) {
            $this->load->model('category_model');
            $sNameRet = $this->category_model->getAll('sExhibitionName');
            $sNames = [];
            foreach ($sNameRet as $item) {
                if (isset($item['attrs'])) {
                    $sNames += $item['attrs'];
                }
            }
            if (key_exists($row['iExhibitionName'], $sNames)) {
                $sName = $sNames[$row['iExhibitionName']]['sName'];
                $moreTags['infoExhibitionName'][] = [
                    'sName' => $sName,
                    'link' => $this->common_model->getLink($colPid, '', 'key', $sName)
                ];
            }
        }
        // 潮流领袖 38 - 明星标签(iStarLabel)  潮流类型(iTidalType)
        if ($row['iOriginColumn'] == 38 && $row['iStarLabel']) {
            $this->load->model('category_model');
            $starLabels = $this->category_model->getAll('sStarLabel');
            $starLabel = $starLabels[$row['iStarLabel']];
            $moreTags['infoStarLabel'][] = [
                'sName' => $starLabel,
                'link' => $this->common_model->getLink($colPid, '', 'key', $starLabel)
            ];
        }
        // 街拍分析 37 - 街拍类型(iStreetBeatType)
        if ($row['iOriginColumn'] == 37 && $row['iStreetBeatType']) {
            $retArr = GetCategory::getOtherFromIds($row['iStreetBeatType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoStreetBeatType'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoStreetBeatType'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        // 行业报道 40 - 报道类型(sReportType)
        if ($row['iOriginColumn'] == 40 && $row['sReportType']) {
            $retArr = GetCategory::getOtherFromIds($row['sReportType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $moreTags['infoReportType'] = [];
                foreach ($retArr['iAttributeId'] as $key => $item) {
                    $moreTags['infoReportType'][$key] = [
                        'sName' => $retArr['sName'][$key],
                        'link' => $this->common_model->getLink($colPid, '', 'key', $retArr['sName'][$key])
                    ];
                }
            }
        }
        $res['moreTags'] = $moreTags;
    }

    /**
     * 获取报告中品牌、品名、标签、视角、栏目等标签信息
     * @param string $tableName 表名
     * @param string|int $id
     * @param string|int $colId
     * @param string $params 筛选条件, 列表里生成标签链接用
     * @param string $type 取详情or列表标签[detail|list]
     * @return array|bool
     * 2017-11-15
     */
    public function getLabelInfo($tableName, $id, $colId, $params = '', $type = 'detail')
    {
        // colPid: 趋势解读 1, 流行分析2
        $colPid = GetCategory::getOtherFromColId($colId, 'iColumnPid');
        // 详情页key
        $dKeys = [
            /**
             * 报告: 季节sea, 品牌bra, 单品cat, 品名scat, 标签label, 行业ind, 视角vis, 栏目col
             */
            // 趋势解读
            '1' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col'],
            // 流行分析
            '2' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col'],
            // 工艺趋势: 工艺类型 tect
            '128' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'tect'],
            // 企划组货: 年龄层 age
            '21' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'age'],
            // 快反应: 地域风格 regs
            '20' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'regs'],
            // T台分析: 时装周名称 fas
            '30' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'fas'],
            // 市场分析: 市场类型 mar
            '33' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'mar'],
            // 爆款数据: 市场类型 mar ，标签与市场分析一致，2019-09-03
            '132' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'mar'],
            // 展会分析: 展会名称 exh
            '31' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'exh'],
            // 潮流领袖: 明星标签 sta
            '38' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'sta'],
            // 街拍分析: 街拍类型 str
            '37' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'str'],
            // 行业报道: 报道类型 rep
            '40' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col', 'rep'],

            // 灵感源
            '90' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col'],
            // 灵感视频
            '116' => ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col']
        ];


        // 列表页key
        $lKeys = [
            // 趋势解读列表: 子栏目名称col, 季节sea, 快反应期数no, 趋势专题relt, 单品, 品名, 工艺类型
            '1' => ['col', 'sea', 'relt', 'cat', 'scat'],
            '20' => ['sea', 'no', 'cat', 'scat'],
            '21' => ['sea', 'relt', 'cat', 'scat'],
            '125' => ['sea', 'relt', 'cat', 'scat'],
            '126' => ['sea', 'relt', 'cat', 'scat'],
            '127' => ['sea', 'relt', 'cat', 'scat'],
            '128' => ['sea', 'relt', 'cat', 'scat', 'tect'],
            '129' => ['sea', 'relt', 'cat', 'scat'],
            // 流行分析列表: 子栏目名称, 季节, 时周专题, 视角, 地区, 展会名称, 报道类型, 品牌, 单品, 品名
            '2' => ['col', 'sea', 'bra', 'cat', 'scat'],
            '30' => ['sea', 'fas', 'vis', 'exh', 'bra', 'cat', 'scat'],
            '31' => ['sea', 'exh'],
            '32' => ['sea', 'vis', 'bra', 'cat', 'scat'],
            '33' => ['sea', 'reg', 'cat', 'scat'], // 市场分析
            '132' => ['sea', 'reg', 'cat', 'scat'],// 爆款数据 ，标签与市场分析一致，2019-09-03
            '34' => ['sea', 'bra', 'cat', 'scat'],
            '35' => ['sea', 'bra', 'cat', 'scat'],
            '37' => ['sea', 'reg', 'cat', 'scat'],
            '38' => ['sea'],
            '40' => ['sea', 'rep', 'bra', 'cat', 'scat']
        ];

        switch ($type) {
            case 'list':
                $keys = $lKeys[$colId];
                break;
            case 'detail':
            default:
                if (key_exists($colId, $dKeys)) {
                    $keys = $dKeys[$colId];
                } elseif (key_exists($colPid, $dKeys)) {
                    $keys = $dKeys[$colPid];
                } else {
                    return false;
                }
                break;
        }

        $aLabel = $this->getLabelInfoOrigin($tableName, $id, $colId, $params);

        if (!$aLabel) {
            return false;
        }
        $aResult = [];
        foreach ($keys as $key) {
            if (key_exists($key, $aLabel)) {
                $aResult = array_merge($aResult, $aLabel[$key]);
            }
        }
        return $aResult;
    }

    /**
     * 获取报告标签信息 原始信息
     * @param string $tableName 表名
     * @param string|int $id
     * @param string $params 筛选条件, 列表里生成标签链接用
     * @param string|int $colId
     * @return array|bool
     * 2017-11-15
     */
    public function getLabelInfoOrigin($tableName, $id, $colId, $params = '')
    {
        $aTable = ['t_trend_report', 'mostrend_content', 'specialtopic', 'specialtopic_graphic'];
        $fsFashionTables = [
            'fs_analysis',
            'fs_commodity',
            'fs_design',
            'fs_inspiration',
            'fs_trend'
        ];
        if (!in_array($tableName, $aTable) && !in_array($tableName, $fsFashionTables)) {
            return false;
        }

        $productData = OpPopFashionMerger::getProductData($id, $tableName);
        $res = $productData[$id];

        $aLabel = [];
        $colPid = (int)GetCategory::getOtherFromColId($colId, 'iColumnPid');

        $__colId = $colId;
        if ($colPid === 0) {
            // 该变量用来取栏目链接
            $__colId = isset($res['iOriginColumn']) && $res['iOriginColumn'] ? $res['iOriginColumn'] : (isset($res['iColumnId']) && $res['iColumnId'] ? $res['iColumnId'] : $colId);
        }

        // 灵感源90、灵感视频116 因为没有父栏目“全部”所以关键词搜索取子栏目
        if ($colId == 90 || $colId == 116 || $colPid === 0) {
            $colPid = $colId;
        }

        // 季节
        if ($res['iSeason'] || $res['fordate']) {
            if ($res['iSeason']) {
                $seasonId = $res['iSeason'];
                $seasonArr = GetCategory::getOtherFromIds($seasonId, ['sName'], 'array');
                $sName = $seasonArr['sName'][0];
            } else {
                $sName = GetCategory::getNewEnName(5, $res['fordate']);
                $seasonId = current(GetCategory::getIdsFrom(5, "sOriginalName,{$sName}"));
            }
            $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
            $slLink = $this->common_model->getLink($colId, $params, 'sea', $seasonId);
            $aLabel['sea'][] = [
                'id' => $seasonId,
                'name' => $sName,
                'dLink' => $sDLink,
                'lLink' => $slLink
            ];
        }

        // 品牌
        if ($res['iBrandTid']) {
            $labelId = $res['iBrandTid'];
            $sName = GetCategory::getBrandOtherFormId($labelId, 'en_cn_brand');
            $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
            $slLink = $this->common_model->getLink($colId, $params, 'bra', $labelId);
            if (!empty($sName)) {
                $aLabel['bra'][] = [
                    'id' => $labelId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        // 单品
        if ($res['sCategory'] || $res['iCategory']) {
            $category = $res['sCategory'] ? $res['sCategory'] : $res['iCategory'];
            $retArr = GetCategory::getOtherFromIds($category, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr)) {
                $aLabel['cat'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'cat', $labelId);
                    $aLabel['cat'][] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 品名
        if ($res['sSubCategory']) {
            $subCategory = $res['sSubCategory'] ? $res['sSubCategory'] : $res['iSubcategory'];
            $retArr = GetCategory::getOtherFromIds($subCategory, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'cat', $labelId);
                    $aLabel['scat'][] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 关联标签
        if ($res['sRelationLabels']) {
            $sRelationLabels = $this->getLabelsByIds($res['sRelationLabels']);
            $aLabel['label'] = [];
            foreach ($sRelationLabels as $key => $item) {
                $labelId = $item['iLabelsId'];
                $sName = $item['sLabelName'];
                $sDLink = $this->common_model->getLink($colPid, '', 'key', urlencode($sName));
                $slLink = ''; // 不取列表标签
                $aLabel['label'][] = [
                    'id' => $labelId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        // 行业
        if ($res['sIndustry']) {
            $retArr = GetCategory::getOtherFromIds($res['sIndustry'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr)) {
                $aLabel['ind'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'ind', $labelId);
                    $aLabel['ind'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 视角
        if ($res['sVisualAngle'] || $res['sView'] || $res['iView']) {
            $sVisualAngle = $res['sVisualAngle'] ? $res['sVisualAngle'] : ($res['sView'] ? $res['sView'] : $res['iView']);
            $retArr = GetCategory::getOtherFromIds($sVisualAngle, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['vis'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'vis', $labelId);
                    $aLabel['vis'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 栏目
        $sLLink = $this->common_model->getLink($__colId, $params);
        $aLabel['col'][] = [
            'id' => $__colId,
            'name' => GetCategory::getOtherFromColId($__colId, 'sName'),
            'dLink' => $sLLink,
            'lLink' => $sLLink
        ];

        // 期数
        if ($res['iNo']) {
            $labelId = $res['iNo'];
            $sName = GetCategory::getOtherFromIds($labelId);
            $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
            $slLink = $this->common_model->getLink($colId, $params, 'no', $labelId);
            if (!empty($sName)) {
                $aLabel['no'][] = [
                    'id' => $labelId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        // 地区
        if ($res['iRegion']) {
            $labelId = $res['iRegion'];
            $sName = GetCategory::getFieldFromId($labelId);
            $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
            $slLink = $this->common_model->getLink($colId, $params, 'reg', $labelId);
            if (!empty($sName)) {
                $aLabel['reg'][] = [
                    'id' => $labelId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        // 趋势专题
        if ($res['iRelatedTopics'] || $res['iRelatedTopicsId']) {
            $iReltId = $res['iRelatedTopics'] ? $res['iRelatedTopics'] : $res['iRelatedTopicsId'];
            $this->category_model || $this->load->model('category_model');
            $_tmpArr = $this->category_model->getAll('iRelationTheme');
            $aRelThemes = [];
            foreach ($_tmpArr as $key => $value) {
                if ($value && isset($value['attrs'])) {
                    foreach ($value['attrs'] as $k => $v) {
                        $aRelThemes[$k] = $v['sName'];
                    }
                }
            }
            if (key_exists($iReltId, $aRelThemes)) {
                $aLabel['relt'] = [];
                $sName = $aRelThemes[$iReltId];
                $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                $slLink = $this->common_model->getLink($colId, $params, 'relt', $iReltId);
                $aLabel['relt'][$key] = [
                    'id' => $iReltId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        // ---------以下分栏目(iOriginColumn)---------
        // 工艺趋势 128 - 工艺类型(iTechnologyType)
        if ($colId == 128 && $res['iTechnologyType']) {
            $retArr = GetCategory::getOtherFromIds($res['iTechnologyType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['tect'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'tect', $labelId);
                    $aLabel['tect'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 企划/组货 21 - 年龄层(sAgeLayer)  todo 这个栏目没有这个属性
        if ($colId == 21 && $res['sAgeLayer']) {
            $retArr = GetCategory::getOtherFromIds($res['sAgeLayer'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['age'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'age', $labelId);
                    $aLabel['age'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 快反应 20 - 地域风格(sRegionalStyle)  todo 这个栏目没有这个属性
        if ($colId == 20 && $res['sRegionalStyle']) {
            $retArr = GetCategory::getOtherFromIds($res['sRegionalStyle'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['regs'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'regs', $labelId);
                    $aLabel['regs'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // T台分析 30 - 时装周名称(sFashionWeek)
        if ($colId == 30 && $res['sFashionWeek']) {
            $retArr = GetCategory::getOtherFromIds($res['sFashionWeek'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['fas'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'fas', $labelId);
                    $aLabel['fas'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 市场分析 33 - 市场类型(sMarketType)（仅显示二级类型）
        if (in_array($colId, array(33, 132)) && $res['sMarketType']) {
            $retArr = GetCategory::getOtherFromIds($res['sMarketType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['mar'] = [];
                // 仅显示二级类型
                if (count($retArr['iAttributeId']) > 1) {
                    array_shift($retArr['iAttributeId']);
                    array_shift($retArr['sName']);
                }
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'mar', $labelId);
                    $aLabel['mar'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 展会分析 31 - 展会名称(iExhibitionName)
        if ($colId == 31 && $res['iExhibitionName']) {
            $labelId = $res['iExhibitionName'];
            $this->load->model('category_model');
            $sNameRet = $this->category_model->getAll('sExhibitionName');
            $aName = [];
            foreach ($sNameRet as $item) {
                if (isset($item['attrs'])) {
                    $aName += $item['attrs'];
                }
            }
            if (key_exists($res['iExhibitionName'], $aName)) {
                $sName = $aName[$res['iExhibitionName']]['sName'];
                $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                $slLink = $this->common_model->getLink($colId, $params, 'exh', $labelId);
                $aLabel['exh'][] = [
                    'id' => $labelId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        // 潮流领袖 38 - 明星标签(iStarLabel)  潮流类型(iTidalType)
        if ($colId == 38 && $res['iStarLabel']) {
            $labelId = $res['iStarLabel'];
            $this->load->model('category_model');
            $aStarLabels = $this->category_model->getAll('sStarLabel');
            $sName = $aStarLabels[$res['iStarLabel']];
            $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
            $slLink = $this->common_model->getLink($colId, $params, 'sta', $labelId);
            $aLabel['sta'][] = [
                'id' => $labelId,
                'name' => $sName,
                'dLink' => $sDLink,
                'lLink' => $slLink
            ];
        }

        // 街拍分析 37 - 街拍类型(iStreetBeatType)
        if ($colId == 37 && $res['iStreetBeatType']) {
            $retArr = GetCategory::getOtherFromIds($res['iStreetBeatType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['str'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'str', $labelId);
                    $aLabel['str'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 行业报道 40 - 报道类型(sReportType)
        if ($colId == 40 && $res['sReportType']) {
            $retArr = GetCategory::getOtherFromIds($res['sReportType'], ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['rep'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sDLink = $this->common_model->getLink($colPid, '', 'key', $sName);
                    $slLink = $this->common_model->getLink($colId, $params, 'rep', $labelId);
                    $aLabel['rep'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        return $aLabel;
    }

    /**
     * 通过表名id 获取关联标签数组
     * @param string $table
     * @param int $id
     * @return array
     */
    public function getStyleLabelInfo($table = '', $id = 0)
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_t_style_label_' . $table . '_' . $id;
        $sLabelsIds = $this->cache->memcached->get($mem_key);
        if ($sLabelsIds === false || $this->refresh) {
            $sql = "SELECT sLabelsIds FROM t_style_label WHERE sTableName=? AND iPriId=?";
            $query = $this->db()->query($sql, [$table, $id]);
            $row = $query->row_array();
            if ($row['sLabelsIds']) {
                $sLabelsIds = explode(',', $row['sLabelsIds']);
                $tmp = array_unique($sLabelsIds);
                $sLabelsIds = [];
                foreach ($tmp as $id) {
                    if ($id > 0) {
                        array_push($sLabelsIds, $id);
                    }
                }
            } else {
                $sLabelsIds = [];
            }
            $this->cache->memcached->save($mem_key, $sLabelsIds, self::MEM_EXPIRE);
        }
        // $this->db()->close();

        return $sLabelsIds;
    }

    /**
     * 通过id获取label
     * @param int $id
     * @return array
     */
    public function getReportLabelById($id = 0)
    {
        $table = 't_trend_report_label';
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . $table . '_' . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            $sql = "SELECT sLabelsIds FROM t_trend_report_label WHERE iTopicId = ?";
            $result = $this->query($sql, [$id]);
            $labels = '';
            foreach ($result as $v) {
                if ($v['sLabelsIds']) {
                    $labels .= $v['sLabelsIds'] . ',';
                }
            }
            $labels = trim($labels, ',');
            $res = [];
            if ($labels) {
                $res = array_unique(explode(',', $labels));
            }
            $this->cache->memcached->save($mem_key, $res, self::MEM_EXPIRE);
        }
        return $res;
    }

    /**
     * 获取更多推荐(报告或款式)列表
     * @param  string $table 真实表名
     * @param  int $id 表id
     * @param  int $columnId 子栏目id
     * @param  array $minNum 取数据数
     * @return array    $list
     * @desc   通过$table+$id获取 关联标签|栏目|品牌|单品|视角|季节|性别|行业
     */
    public function getMoreRecList($table = '', $id = 0, $columnId = 0, &$productData = [], $minNum = ['report' => 4, 'style' => 6])
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_2017112901_' . $table . '_' . $id . '_' . $columnId;
        $res = $this->cache->memcached->get($mem_key);
        if (!$this->refresh && $res !== false) {
            return $res;
        }
        $res = [];
        $iColumnPid = GetCategory::getOtherFromColId($columnId, 'iColumnPid');
        $iColumnPid = intval($iColumnPid);

        if ($iColumnPid === 0) {
            return $res;
        }

        if (empty($productData)) {
            $result = OpPopFashionMerger::getProductData($id, $table, self::MEM_CACHE);
        } else {
            $result = $productData;
        }
        $result = $result[$id];
        $imgPath = getImagePath($table, $result);
        $metadataTmp = $table . '_' . $id;
        // 报告表关联标签的特殊处理
        if ($table == 't_trend_report') {
            $aRelationLabels = $this->getReportLabelById($id);
            $result['sRelationLabels'] = implode(',', $aRelationLabels);
        }

        $conRpt = []; // solr 查询条件(报告)
        $conStl = []; // solr 查询条件(款式)

        // 成册solrPopId的特殊处理
        // 手稿合辑
        if ($iColumnPid == 6) {
            switch ($table) {
                case 'product_brochures_photo':
                    $p_table = 'product_brochures';
                    $p_id = $result['borochid'];
                    break;
                case 'product_design_refrence_details':
                    $p_table = 'product_design_refrence';
                    $p_id = $result['pid'];
                    break;
                case 'product_vector_refrence':
                    $p_table = 'product_vector_refrence_group';
                    $p_id = $result['groupid'];
                    break;
                default:
                    $p_table = $table;
                    $p_id = $id;
                    break;
            }
            $solrPopId = $p_table . '_' . $p_id;
        } else {
            $solrPopId = $table . '_' . $id;    // 排除掉自己
        }

        // 参考 http://192.168.3.184:8000/svn/web_new/Document/高端服装网合并文档/栏目关联及推荐关系-6.7R1.xlsx
        // 未来趋势
        if ($iColumnPid === 1) {
            //============ 关联标签条件 ============//
            if (isset($result['sRelationLabels']) && $result['sRelationLabels']) {
                $sRelationLabels = getSolrORString($result['sRelationLabels']);
                array_push($conRpt, 'aRelationLabels:' . $sRelationLabels);
                array_push($conStl, 'aRelationLabels:' . $sRelationLabels);
            }
            //============ 关联标签条件 ============//

            //============ 趋势主题条件 ============//
            if (isset($result['iRelatedTopics']) && $result['iRelatedTopics']) {
                array_push($conRpt, 'iRelationTheme:' . $result['iRelatedTopics']);
            }
            //============ 趋势主题条件 ============//

            //============ 年龄层条件 ============//
            if (isset($result['sAgeLayer']) && $result['sAgeLayer']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sAgeLayer']));
            }
            if (isset($result['sAgeLevel']) && $result['sAgeLevel']) {
                // specialtopic_graphic|trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sAgeLevel']));
            }
            //============ 年龄层条件 ============//

            //============ 季节条件 ============//
            if ($iSeason) {
                $tmpSeason = GetCategory::getSeason($iColumnPid, 'sEnName');
                if (isset($tmpSeason[$iSeason])) {
                    array_push($conStl, 'iSeasonSuffix:' . substr($tmpSeason[$iSeason], -2));
                }
            }
            //============ 季节条件 ============//

            //============ 品名条件 ============//
            if (isset($result['sSubcategory']) && $result['sSubcategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
            }
            if (isset($result['iSubcategory']) && $result['iSubcategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
            }
            //============ 品名条件 ============//

            //============ 单品条件 ============//
            if (isset($result['sCategory']) && $result['sCategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sCategory']));
            }
            if (isset($result['iCategory']) && $result['iCategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iCategory']));
            }
            //============ 单品条件 ============//

            //============ 视角条件 ============//
            $sVisualAngle = false;
            if (isset($result['iView'])) {
                // trends_new.`mostrend_content`|specialtopic|specialtopic_graphic
                $sVisualAngle = getSolrORString($result['iView']);
            } elseif (isset($result['sView'])) {
                // fs_*
                $sVisualAngle = getSolrORString($result['sView']);
            } elseif (isset($result['sVisualAngle'])) {
                // t_trend_report
                $sVisualAngle = getSolrORString($result['sVisualAngle']);
            }
            if ($sVisualAngle) {
                //报告类栏目
                array_push($conRpt, 'aLabelIds:' . $sVisualAngle);
            }
            //============ 视角条件 ============//

            //============ 流行时间条件 ============//
            $iSeason = false;
            if (isset($result['popular_time'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['popular_time'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['fordate'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['fordate'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['iSeason'])) {
                $iSeason = intval($result['iSeason']);
            }
            if ($iSeason) {
                array_push($conRpt, 'aLabelIds:' . $iSeason); //季节
                array_push($conStl, 'aLabelIds:' . $iSeason); //季节
            }
            //============ 流行时间条件 ============//

            //============ 行业条件 ============//
            $sIndustry = $sIndustry2 = false;
            if (isset($result['iIndustry'])) {
                $sIndustry = getSolrAndString($result['iIndustry']);
                $sIndustry2 = getSolrORString($result['iIndustry']);
            } elseif (isset($result['sIndustry'])) {
                $sIndustry = getSolrAndString($result['sIndustry']);
                $sIndustry2 = getSolrORString($result['sIndustry']);
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry2) {
                array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
            }
            //============ 行业条件 ============//

            //============ 性别条件 ============//
            $sGender = $sGender2 = false;
            if (isset($result['channel'])) {
                // 高端的性别
                $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['channel'], $iColumnPid);
                $sGender = $sGender2 = $ids[0];
            } elseif (isset($result['iGender'])) {
                $sGender = $sGender2 = intval($result['iGender']);
            } elseif (isset($result['type'])) {
                $types = explode(',', $result['type']);
                $ids = [];
                foreach ($types as $val) {
                    $tmp = GetCategory::getIdsFrom(1, "sOriginalName," . $val, $iColumnPid);
                    array_push($ids, $tmp[0]);
                }
                if ($ids) {
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
            }
            if ($sGender) {
                array_push($conRpt, 'aLabelIds:' . $sGender); //性别
            }
            if ($sGender2) {
                array_push($conStl, 'aLabelIds:' . $sGender2); //性别
            }
            //============ 性别条件 ============//

            //============ 栏目条件 ============//
            array_push($conRpt, 'iColumnId:' . getSolrORString('1,2')); //报告类栏目
            array_push($conStl, 'iColumnId:4'); //款式类栏目
            //============ 栏目条件 ============//

            array_push($conRpt, '-pop_id:' . $solrPopId);
            array_push($conStl, '-pop_id:' . $solrPopId);
        }
        // 潮流解析
        if ($iColumnPid === 2) {
            //============ 关联标签条件 ============//
            if (isset($result['sRelationLabels']) && $result['sRelationLabels']) {
                $sRelationLabels = getSolrORString($result['sRelationLabels']);
                array_push($conRpt, 'aRelationLabels:' . $sRelationLabels);
                array_push($conStl, 'aRelationLabels:' . $sRelationLabels);
            }
            //============ 关联标签条件 ============//

            //============ 品名条件 ============//
            if (isset($result['sSubcategory']) && $result['sSubcategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
            }
            if (isset($result['iSubcategory']) && $result['iSubcategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
            }
            //============ 品名条件 ============//

            //============ 单品条件 ============//
            if (isset($result['sCategory']) && $result['sCategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sCategory']));
            }
            if (isset($result['iCategory']) && $result['iCategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iCategory']));
            }
            //============ 单品条件 ============//

            //============ 品牌条件 ============//
            if (isset($result['iBrandTid']) && $result['iBrandTid'] > 0) {
                array_push($conRpt, 'iBrand:' . intval($result['iBrandTid']));
                array_push($conStl, 'iBrand:' . intval($result['iBrandTid']));
            } elseif (isset($result['brand']) && $result['brand']) {
                // 高端的直接是brand
                $brandTid = OpPopFashionMerger::getBrandtidByName($result['brand'], 'en_cn_brand', self::MEM_CACHE);
                if ($brandTid > 0) {
                    array_push($conRpt, 'iBrand:' . intval($brandTid));
                    array_push($conStl, 'iBrand:' . intval($brandTid));
                }
            }
            //============ 品牌条件 ============//

            //============ 视角条件 ============//
            $sVisualAngle = false;
            if (isset($result['iView'])) {
                // trends_new.`mostrend_content`|specialtopic|specialtopic_graphic
                $sVisualAngle = getSolrORString($result['iView']);
            } elseif (isset($result['sView'])) {
                // fs_*
                $sVisualAngle = getSolrORString($result['sView']);
            } elseif (isset($result['sVisualAngle'])) {
                // t_trend_report
                $sVisualAngle = getSolrORString($result['sVisualAngle']);
            }
            if ($sVisualAngle) {
                //报告类栏目
                array_push($conRpt, 'aLabelIds:' . $sVisualAngle);
            }
            //============ 视角条件 ============//

            //============ 流行时间条件 ============//
            $iSeason = false;
            if (isset($result['popular_time'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['popular_time'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['fordate'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['fordate'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['iSeason'])) {
                $iSeason = intval($result['iSeason']);
            }
            if ($iSeason) {
                array_push($conRpt, 'aLabelIds:' . $iSeason); //季节
                array_push($conStl, 'aLabelIds:' . $iSeason); //季节
            }
            //============ 流行时间条件 ============//

            //============ 季节条件 ============//
            if ($iSeason) {
                $tmpSeason = GetCategory::getSeason($iColumnPid, 'sEnName');
                if (isset($tmpSeason[$iSeason])) {
                    array_push($conStl, 'iSeasonSuffix:' . substr($tmpSeason[$iSeason], -2));
                }
            }
            //============ 季节条件 ============//

            //============ 行业条件 ============//
            $sIndustry = $sIndustry2 = false;
            if (isset($result['iIndustry'])) {
                $sIndustry = getSolrAndString($result['iIndustry']);
                $sIndustry2 = getSolrORString($result['iIndustry']);
            } elseif (isset($result['sIndustry'])) {
                $sIndustry = getSolrAndString($result['sIndustry']);
                $sIndustry2 = getSolrORString($result['sIndustry']);
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry2) {
                array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
            }
            //============ 行业条件 ============//

            //============ 性别条件 ============//
            $sGender = $sGender2 = false;
            if (isset($result['channel'])) {
                // 高端的性别
                $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['channel'], $iColumnPid);
                $sGender = $sGender2 = $ids[0];
            } elseif (isset($result['iGender'])) {
                $sGender = $sGender2 = intval($result['iGender']);
            } elseif (isset($result['type'])) {
                $types = explode(',', $result['type']);
                $ids = [];
                foreach ($types as $val) {
                    $tmp = GetCategory::getIdsFrom(1, "sOriginalName," . $val, $iColumnPid);
                    array_push($ids, $tmp[0]);
                }
                if ($ids) {
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
            }
            if ($sGender) {
                array_push($conRpt, 'aLabelIds:' . $sGender); //性别
            }
            if ($sGender2) {
                array_push($conStl, 'aLabelIds:' . $sGender2); //性别
            }
            //============ 性别条件 ============//


            //============ 栏目条件 ============//
            array_push($conRpt, 'iColumnId:' . getSolrORString('1,2')); //报告类栏目
            $_stlColumnId = $this->reportReferStyle($columnId, 4, false);
            array_push($conStl, 'iColumnId:' . $_stlColumnId); //款式类栏目
            //============ 栏目条件 ============//

            array_push($conRpt, '-pop_id:' . $solrPopId);
            array_push($conStl, '-pop_id:' . $solrPopId);
        }
        // 款式站
        if ($iColumnPid === 4) {
            //============ 关联标签 ============//
            $sLabelsIds = $this->getStyleLabelInfo($table, $id);
            $sLabelsIds = getSolrORString(implode(',', $sLabelsIds));
            if ($sLabelsIds) {
                array_push($conRpt, 'aRelationLabels:' . $sLabelsIds);
                array_push($conStl, 'aRelationLabels:' . $sLabelsIds);
            }
            //============ 关联标签 ============//

            //============ 款式类季节条件 ============//
            $iSeason = false;
            if (isset($result['for_date'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['for_date'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['iFordate'])) {
                $iSeason = intval($result['iFordate']);
            }
            if ($iSeason) {
                $tmpSeason = GetCategory::getSeason($iColumnPid, 'sEnName');
                if (isset($tmpSeason[$iSeason]) && $tmpSeason[$iSeason]) {
                    array_push($conStl, 'iSeasonSuffix:' . substr($tmpSeason[$iSeason], -2));
                }
            }
            //============ 款式类季节条件 ============//

            //============ 轮廓外形状条件 ============//
            // 173
            $aid = $this->getLabelId($table, $id, 173);
            if ($aid) {
                array_push($conStl, 'aLabelIds:' . getSolrORString($aid));
            }
            //============ 轮廓外形状条件 ============//

            //============ 面料品名条件 ============//
            // 276
            $aid = $this->getLabelId($table, $id, 276);
            if ($aid) {
                array_push($conStl, 'aLabelIds:' . getSolrORString($aid));
            }
            //============ 面料品名条件 ============//

            //============ 表面装饰条件 ============//
            // 11370
            $aid = $this->getLabelId($table, $id, 11370);
            if ($aid) {
                array_push($conStl, 'aLabelIds:' . getSolrORString($aid));
            }
            //============ 表面装饰条件 ============//

            //============ 制作工艺条件 ============//
            // 11336
            $aid = $this->getLabelId($table, $id, 11336);
            if ($aid) {
                array_push($conStl, 'aLabelIds:' . getSolrORString($aid));
            }
            //============ 制作工艺条件 ============//

            //============ 图案内容大类条件 ============//
            // 285
            $aid = $this->getLabelId($table, $id, 285);
            if ($aid) {
                array_push($conStl, 'aLabelIds:' . getSolrORString($aid));
            }
            //============ 图案内容大类条件 ============//

            //============ 品名条件 ============//
            $sSubCategory = false;
            if (isset($result['iSubcategory'])) {
                $sSubCategory = getSolrORString($result['iSubcategory']);
            }
            if ($sSubCategory) {
                array_push($conRpt, 'aLabelIds:' . $sSubCategory); //报告类栏目
                array_push($conStl, 'aLabelIds:' . $sSubCategory); //款式类栏目
            }
            //============ 品名条件 ============//

            //============ 单品条件 ============//
            $sCategory = false;
            if (isset($result['iCategory'])) {
                $sCategory = getSolrORString($result['iCategory']);
            }
            if ($sCategory) {
                array_push($conRpt, 'aLabelIds:' . $sCategory); //报告类栏目
                array_push($conStl, 'aLabelIds:' . $sCategory); //款式类栏目
            }
            //============ 单品条件 ============//

            //============ 品牌条件 ============//
            if (isset($result['brand_tid']) && $result['brand_tid'] > 0) {
                array_push($conRpt, 'iBrand:' . intval($result['brand_tid']));
            }
            //============ 品牌条件 ============//
            //============ 流行时间和报告的季节 ============//
            if ($iSeason) {
                array_push($conRpt, 'aLabelIds:' . $iSeason); //款式流行时间
                array_push($conStl, 'aLabelIds:' . $iSeason); //报告流行时间
                $tmpSeason = GetCategory::getSeason($iColumnPid, 'sEnName');
                if (isset($tmpSeason[$iSeason]) && $tmpSeason[$iSeason]) {
                    array_push($conRpt, 'iSeasonSuffix:' . substr($tmpSeason[$iSeason], -2));// 报告季节
                }

            }
            //============ 行业条件 ============//
            $sIndustry = $sIndustry2 = false;
            if (isset($result['iIndustry'])) {
                $sIndustry = getSolrAndString($result['iIndustry']);
                $sIndustry2 = getSolrORString($result['iIndustry']);
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry2) {
                array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
            }
            //============ 行业条件 ============//

            //============ 性别条件 ============//
            $sGender = $sGender2 = false;
            if (isset($result['iType'])) {
                $sGender = $sGender2 = intval($result['iType']);
            } elseif (isset($result['typ'])) {
                $types = explode(',', $result['typ']);
            } elseif (isset($result['gender'])) {
                $types = explode(',', $result['typ']);
            }
            $ids = [];
            if (is_array($types)) {
                foreach ($types as $val) {
                    $tmp = GetCategory::getIdsFrom(1, "sOriginalName," . $val, $iColumnPid);
                    array_push($ids, $tmp[0]);
                }
            }
            if ($ids) {
                // 报告
                $sGender = implode(',', $ids);
                $sGender = getSolrAndString($sGender);
                // 款式
                $ids2 = $this->getGenderIdForSolr($ids);
                $sGender2 = implode(',', $ids2);
                $sGender2 = getSolrORString($sGender2);
            }
            if ($sGender) {
                array_push($conRpt, 'aLabelIds:' . $sGender); //性别
            }
            if ($sGender2) {
                array_push($conStl, 'aLabelIds:' . $sGender2); //性别
            }
            //============ 性别条件 ============//

            //============ 栏目条件 ============//
            $_rptColumnId = $this->reportReferStyle($columnId, 2, true);
            if (is_array($_rptColumnId)) {
                $_rptColumnId = "(" . implode(" OR ", $_rptColumnId) . ")";
            }
            array_push($conRpt, 'iColumnId:' . $_rptColumnId); //报告类栏目
            array_push($conStl, 'iColumnId:4'); //款式类栏目
            //============ 栏目条件 ============//

            array_push($conRpt, '-pop_id:' . $solrPopId);
            array_push($conStl, '-pop_id:' . $solrPopId);
        }
        // 手稿合辑
        if ($iColumnPid === 6) {
            // 图册类没有关联标签

            //============ 品名条件 ============//
            if (isset($result['iSubcategory']) && $result['iSubcategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
            }
            //============ 品名条件 ============//

            //============ 单品条件 ============//
            $sCategory = false;
            if (isset($result['iCategory'])) {
                $sCategory = getSolrORString($result['iCategory']);
            }
            if ($sCategory) {
                array_push($conStl, 'aLabelIds:' . $sCategory); //报告类栏目
                array_push($conRpt, 'aLabelIds:' . $sCategory); //款式类栏目
            }
            //============ 单品条件 ============//

            //============ 品牌条件 ============//
            if ($table === 'product_brochures') {
                $brandName = $result['name'];
            } elseif ($table === 'mostrend_content') {
                $brandName = $result['brand'];
            }
            if ($brandName) {
                $brandTid = OpPopFashionMerger::getBrandtidByName($brandName, 'name', self::MEM_CACHE);
                if ($brandTid > 0) {
                    array_push($conStl, 'iBrand:' . intval($brandTid));
                    array_push($conRpt, 'iBrand:' . intval($brandTid));
                }
            }
            //============ 品牌条件 ============//

            //============ 书籍类型条件 ============//
            // 11442
            $aid = $this->getLabelId($table, $id, 11442);
            if ($aid) {
                array_push($conStl, 'aLabelIds:' . getSolrORString($aid));
            }
            //============ 书籍类型条件 ============//

            //============ 流行时间条件 ============//
            $iSeason = false;
            if (isset($result['popular_time'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['popular_time'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['for_date'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['for_date'], $iColumnPid);
                $iSeason = $ids[0];
            }
            if ($iSeason) {
                array_push($conStl, 'aLabelIds:' . $iSeason); //季节
            }
            //============ 流行时间条件 ============//

            //============ 行业条件 ============//
            $sIndustry = $sIndustry2 = false;
            if (isset($result['iIndustry'])) {
                $sIndustry = getSolrAndString($result['iIndustry']);
                $sIndustry2 = getSolrORString($result['iIndustry']);
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry2) {
                array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
            }
            //============ 行业条件 ============//

            //============ 性别条件 ============//
            $sGender = $sGender2 = false;
            if (isset($result['channel'])) {
                // 高端的性别
                $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['channel'], $iColumnPid);
                $sGender = $sGender2 = $ids[0];
            } elseif (isset($result['iGender'])) {
                $sGender = $sGender2 = intval($result['iGender']);
            } else {
                if (isset($result['type'])) {
                    $types = explode(',', $result['type']);
                }
                if (isset($result['typ'])) {
                    $types = explode(',', $result['typ']);
                }
                $ids = [];
                foreach ($types as $val) {
                    $tmp = GetCategory::getIdsFrom(1, "sOriginalName," . $val, $iColumnPid);
                    array_push($ids, $tmp[0]);
                }
                if ($ids) {
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
            }
            if ($sGender) {
                array_push($conRpt, 'aLabelIds:' . $sGender); //性别
            }
            if ($sGender2) {
                array_push($conStl, 'aLabelIds:' . $sGender2); //性别
            }

            //============ 性别条件 ============//

            //============ 流行时间条件 ============//
            if ($iSeason) {
                array_push($conRpt, 'aLabelIds:' . $iSeason); //季节
            }
            //============ 流行时间条件 ============//

            //============ 栏目条件 ============//
            if ($columnId == 71) {
                array_push($conStl, 'iColumnId:71'); // lookbook栏目
            } else {
                array_push($conStl, 'iColumnId:(70 OR 72 OR 113 OR 114 OR 115)'); //图册类栏目
            }
            array_push($conRpt, 'iColumnId:(1 OR 2)'); //报告类栏目
            //============ 栏目条件 ============//

            array_push($conRpt, '-pop_id:' . $solrPopId);
            array_push($conStl, '-pop_id:' . $solrPopId);
        }
        // 设计素材
        if ($iColumnPid === 7) {
            // 款式模板
            if ($columnId == 80) {
                //============ 品名条件 ============//
                if (isset($result['iSubcategory']) && $result['iSubcategory']) {
                    // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                    array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
                    array_push($conStl, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
                }
                //============ 品名条件 ============//

                //============ 单品条件 ============//
                $sCategory = false;
                if (isset($result['iCategory'])) {
                    $sCategory = getSolrORString($result['iCategory']);
                }
                if ($sCategory) {
                    array_push($conRpt, 'aLabelIds:' . $sCategory); //报告类栏目
                    array_push($conStl, 'aLabelIds:' . $sCategory); //款式类栏目
                }
                //============ 单品条件 ============//

                //============ 行业条件 ============//
                $sIndustry = $sIndustry2 = false;
                if (isset($result['iIndustry'])) {
                    $sIndustry = getSolrAndString($result['iIndustry']);
                    $sIndustry2 = getSolrORString($result['iIndustry']);
                }
                if ($sIndustry) {
                    array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
                }
                if ($sIndustry2) {
                    array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
                }
                //============ 行业条件 ============//

                //============ 性别条件 ============//
                $sGender = $sGender2 = false;
                if (isset($result['typ'])) {
                    $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['typ'], $iColumnPid);
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
                if ($sGender) {
                    array_push($conRpt, 'aLabelIds:' . $sGender); //性别
                }
                if ($sGender2) {
                    array_push($conStl, 'aLabelIds:' . $sGender2); //性别
                }
                //============ 性别条件 ============//

                array_push($conRpt, 'iColumnId:(1 OR 2)'); //趋势栏目
                array_push($conStl, 'iColumnId:4'); //款式类栏目

                array_push($conRpt, '-pop_id:' . $solrPopId);
                array_push($conStl, '-pop_id:' . $solrPopId);
            }
            // 款式细节
            if ($columnId == 81) {
                //============ 关联标签 ============//
                /*
				$sLabelsIds = $this->getStyleLabelInfo($table, $id);
				$sLabelsIds = getSolrORString(implode(',', $sLabelsIds));
				if ($sLabelsIds) {
					array_push($conRpt, 'aRelationLabels:' . $sLabelsIds);
				}
				*/
                //============ 关联标签 ============//

                //============ 视角条件 ============//
                array_push($conRpt, 'aLabelIds:(17 OR 18)');
                //============ 视角条件 ============//

                //============ 单品条件 ============//
                $sCategory = false;
                if (isset($result['iCategory'])) {
                    $sCategory = getSolrORString($result['iCategory']);
                }
                if ($sCategory) {
                    //array_push($conRpt, 'aLabelIds:' . $sCategory); //报告类栏目
                    array_push($conStl, 'aLabelIds:' . $sCategory); //款式类栏目
                }
                //============ 单品条件 ============//

                //============ 款式部位条件 ============//
                //177
                if ($result['iStylePart']) {
                    array_push($conStl, 'aLabelIds:' . $result['iStylePart']);
                }
                //============ 款式部位条件 ============//

                //============ 行业条件 ============//
                $sIndustry = $sIndustry2 = false;
                if (isset($result['iIndustry'])) {
                    $sIndustry = getSolrAndString($result['iIndustry']);
                    $sIndustry2 = getSolrORString($result['iIndustry']);
                }
                if ($sIndustry) {
                    array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
                }
                if ($sIndustry2) {
                    array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
                }
                //============ 行业条件 ============//

                //============ 性别条件 ============//
                $sGender = $sGender2 = false;
                if (isset($result['typ'])) {
                    $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['typ'], $iColumnPid);
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
                if ($sGender) {
                    array_push($conRpt, 'aLabelIds:' . $sGender); //性别
                }
                if ($sGender2) {
                    array_push($conStl, 'aLabelIds:' . $sGender2); //性别
                }
                //============ 性别条件 ============//

                array_push($conRpt, '-pop_id:' . $solrPopId);
                array_push($conStl, '-pop_id:' . $solrPopId);

                array_push($conRpt, 'iColumnId:2'); //分析栏目
                array_push($conStl, 'iColumnId:4'); //款式类栏目
            }
            // 展会面料
            if ($columnId == 117) {
                //============ 关联标签 ============//
                /*
					$sLabelsIds = $this->getStyleLabelInfo($table, $id);
					$sLabelsIds = getSolrORString(implode(',', $sLabelsIds));
					if ($sLabelsIds) {
						array_push($conRpt, 'aRelationLabels:' . $sLabelsIds);
					}
					*/
                //============ 关联标签 ============//

                //============ 图案内容条件 ============//
                if (isset($result['sContent'])) {
                    //array_push($conStl, 'aPatternContent:' . getSolrORString($result['sContent']));
                }
                //============ 图案内容条件 ============//

                //============ 视角条件 ============//
                array_push($conRpt, 'aLabelIds:' . 15);
                //============ 视角条件 ============//

                //============ 性别条件 ============//
                // 与众不同，性别条件存在memo中
                $sGender = $sGender2 = false;
                if ($result['memo']) {
                    $memo = explode(' ', $result['memo']);
                    $popGender = getCommonData('common_data', 'popGender');
                    if (is_array($popGender)) {
                        foreach ($popGender as $id => $gender) {
                            foreach ($memo as $value) {
                                if (in_array($value, $gender)) {
                                    $ids = array($id);
                                    // 报告
                                    $sGender = implode(',', $ids);
                                    $sGender = getSolrAndString($sGender);
                                    // 款式
                                    $ids2 = $this->getGenderIdForSolr($ids);
                                    $sGender2 = implode(',', $ids2);
                                    $sGender2 = getSolrORString($sGender2);
                                    break 2;
                                }
                            }
                        }
                    }
                }

                if ($sGender) {
                    array_push($conRpt, 'aLabelIds:' . $sGender); //性别
                }
                /*if ($sGender2) {
						array_push($conStl, 'aLabelIds:' . $sGender2); //性别
					}*/
                //============ 性别条件 ============//

                //============ 栏目条件 ============//
                array_push($conRpt, 'iColumnId:(1 OR 2)'); //报告类栏目
                array_push($conStl, 'iColumnId:' . $columnId); //报告类栏目
                //============ 栏目条件 ============//

                array_push($conRpt, '-pop_id:' . $solrPopId);
                array_push($conStl, '-pop_id:' . $solrPopId);
            }
            // 面料？
            if ($columnId == 83) {
                //
            }
            // 服饰品
            if ($columnId == 84) {
                array_push($conRpt, 'iColumnId:2'); // 分析栏目
                array_push($conStl, 'iColumnId:84'); //服饰品栏目

                array_push($conRpt, '-pop_id:' . $solrPopId);
                array_push($conStl, '-pop_id:' . $solrPopId);
            }
            // 橱窗陈列
            if ($columnId == 85) {
                //============ 行业条件 ============//
                $sIndustry = $sIndustry2 = false;
                if (isset($result['iIndustry'])) {
                    $sIndustry = getSolrAndString($result['iIndustry']);
                    $sIndustry2 = getSolrORString($result['iIndustry']);
                }
                if ($sIndustry) {
                    array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
                }
                if ($sIndustry2) {
                    array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
                }
                //============ 行业条件 ============//

                //============ 性别条件 ============//
                // 与众不同，性别条件存在attribute中
                $sGender = $sGender2 = false;
                if ($result['memo']) {
                    $memo = explode(' ', $result['attribute']);
                    $popGender = getCommonData('common_data', 'popGender');
                    if (is_array($popGender)) {
                        foreach ($popGender as $id => $gender) {
                            foreach ($memo as $value) {
                                if (in_array($value, $gender)) {
                                    $ids = array($id);
                                    // 报告
                                    $sGender = implode(',', $ids);
                                    $sGender = getSolrAndString($sGender);
                                    // 款式
                                    $ids2 = $this->getGenderIdForSolr($ids);
                                    $sGender2 = implode(',', $ids2);
                                    $sGender2 = getSolrORString($sGender2);
                                    break 2;
                                }
                            }
                        }
                    }
                }
                if ($sGender) {
                    array_push($conRpt, 'aLabelIds:' . $sGender); //性别
                }
                if ($sGender2) {
                    array_push($conStl, 'aLabelIds:' . $sGender2); //性别
                }
                //============ 性别条件 ============//

                //============ 季节条件 ============//
                $iSeason = intval($result['iSeason']);
                if ($iSeason) {
                    array_push($conRpt, 'aLabelIds:' . $iSeason); //季节
                    array_push($conStl, 'aLabelIds:' . $iSeason); //季节
                }
                //============ 季节条件 ============//

                //============ 品牌条件 ============//
                $brandTid = $result['brand_tid'];
                if ($brandTid) {
                    array_push($conStl, 'iBrand:' . intval($brandTid));
                }
                //============ 品牌条件 ============//

                //============ 栏目条件 ============//
                array_push($conRpt, 'iColumnId:2'); //潮流解析栏目
                array_push($conStl, 'iColumnId:85'); //橱窗陈列
                //============ 栏目条件 ============//

                //============ 关联标签 ============//
                $sLabelsIds = $this->getStyleLabelInfo($table, $id);
                $sLabelsIds = getSolrORString(implode(',', $sLabelsIds));
                if ($sLabelsIds) {
                    array_push($conRpt, 'aRelationLabels:' . $sLabelsIds);
                    array_push($conStl, 'aRelationLabels:' . $sLabelsIds);
                }
                //============ 关联标签 ============//

                array_push($conRpt, '-pop_id:' . $solrPopId);
                array_push($conStl, '-pop_id:' . $solrPopId);
            }
        }
        // 灵感源
        if ($iColumnPid == 8) {
            //============ 栏目条件 ============//
            array_push($conRpt, 'iColumnId:90'); //灵感报告栏目
            array_push($conStl, 'iColumnId:91'); //灵感图栏目
            //============ 栏目条件 ============//

            //============ 关联标签件 ============//
            if (isset($result['sRelationLabels']) && $result['sRelationLabels']) {
                $sRelationLabels = getSolrORString($result['sRelationLabels']);
                array_push($conRpt, 'aRelationLabels:' . $sRelationLabels);
                array_push($conStl, 'aRelationLabels:' . $sRelationLabels);
            }
            //============ 关联标签件 ============//

            array_push($conRpt, '-pop_id:' . $solrPopId);
            array_push($conStl, '-pop_id:' . $solrPopId);
        }
        // 图案库
        if ($iColumnPid == 9) {
            // 图案素材 大牌花型
            if ($columnId == 82 || $columnId == 120) {
                //============ 关联标签 ============//
                /*
                $sLabelsIds = $this->getStyleLabelInfo($table, $id);
                $sLabelsIds = getSolrORString(implode(',', $sLabelsIds));
                if ($sLabelsIds) {
                    array_push($conRpt, 'aRelationLabels:' . $sLabelsIds);
                }
                */
                //============ 关联标签 ============//

                //============ 图案内容条件 ============//
                if (isset($result['sContent'])) {
                    //array_push($conStl, 'aPatternContent:' . getSolrORString($result['sContent']));
                }
                //============ 图案内容条件 ============//

                //============ 视角条件 ============//
                array_push($conRpt, 'aLabelIds:' . 15);
                //============ 视角条件 ============//

                //============ 性别条件 ============//
                // 与众不同，性别条件存在memo中
                $sGender = $sGender2 = false;
                if ($result['memo']) {
                    $memo = explode(' ', $result['memo']);
                    $popGender = getCommonData('common_data', 'popGender');
                    if (is_array($popGender)) {
                        foreach ($popGender as $id => $gender) {
                            foreach ($memo as $value) {
                                if (in_array($value, $gender)) {
                                    $ids = array($id);
                                    // 报告
                                    $sGender = implode(',', $ids);
                                    $sGender = getSolrAndString($sGender);
                                    // 款式
                                    $ids2 = $this->getGenderIdForSolr($ids);
                                    $sGender2 = implode(',', $ids2);
                                    $sGender2 = getSolrORString($sGender2);
                                    break 2;
                                }
                            }
                        }
                    }
                }

                if ($sGender) {
                    array_push($conRpt, 'aLabelIds:' . $sGender); //性别
                }
                /*if ($sGender2) {
                    array_push($conStl, 'aLabelIds:' . $sGender2); //性别
                }*/
                //============ 性别条件 ============//

                //============ 栏目条件 ============//
                array_push($conRpt, 'iColumnId:(1 OR 2)'); //报告类栏目
                array_push($conStl, 'iColumnId:' . $columnId); //报告类栏目
                //============ 栏目条件 ============//

                array_push($conRpt, '-pop_id:' . $solrPopId);
                array_push($conStl, '-pop_id:' . $solrPopId);
            }
        }


        // 处理报告类数据
        if (isset($minNum['report']) && $minNum['report'] > 0) {

            $reportRst = [];
            $this->findDataBySolr($conRpt, $minNum['report'], $reportRst);

            $reports = [];
            foreach ($reportRst as $value) {
                $productData = $this->dealWithProductData($value['pri_id'], $value['tablename']);
                $imgArr = getImagePath($value['tablename'], $productData);

                $result = [];
                $result['smallPath'] = $imgArr['cover']; // 报告图封面图
                // id
                //$result['id'] = $value['pri_id'];
                // 栏目id
                $colId = $value['iColumnId'][1];
                $result['col'] = $colId;
                //$result['colsName'] = GetCategory::getOtherFromColId($colId, 'sName');
                //$result['colsEnName'] = GetCategory::getOtherFromColId($colId, 'sEnName');
                $result['link'] = $this->getDetailLink('report', $value['tablename'], $value['pri_id'], $colId); // 报告链接

                // 标题
                if (isset($productData['sTitle'])) {
                    $result['title'] = $productData['sTitle'];
                } else {
                    $result['title'] = $productData['title'];
                }
                $result['title'] = stripslashes($result['title']);
                // 描述
                $descArr = ['abstract', 'description', 'sDesc', 'title_describe', 'sdescription'];
                foreach ($descArr as $item) {
                    if (isset($productData[$item])) {
                        $result['description'] = strip_tags($productData[$item]);
                        break;
                    }
                }

                // 浏览量
                $result['view_count'] = $productData['view_count'];
                if (isset($productData['views'])) {
                    $result['view_count'] = $productData['views'];
                }
                if (isset($productData['click_rate'])) {
                    $result['view_count'] = $productData['click_rate'];
                }
                if (isset($productData['iViewCount'])) {
                    $result['view_count'] = $productData['iViewCount'];
                }
                // 显示时间
                $result['order_time'] = $productData['order_time'];
                if (isset($productData['addtime'])) {
                    $result['order_time'] = $productData['addtime'];
                }
                if (isset($productData['release_time'])) {
                    $result['order_time'] = $productData['release_time'];
                }
                if (isset($productData['dPubTime'])) {
                    $result['order_time'] = $productData['dPubTime'];
                }
                $result['order_time'] = substr($result['order_time'], 0, 10);
                array_push($reports, $result);
            }
            $res['reports'] = $reports;
        }

        // 处理款式类数据
        if (isset($minNum['style']) && $minNum['style'] > 0) {
            $styleRst = [];
            if ($columnId == 82 || $columnId == 117) {
                //当栏目ID为82时，使用第三方图片匹配，而不使用规则匹配
                $postData['url'] = $imgPath['cover'];
                $this->load->model('picmatch_model');
                $result = $this->picmatch_model->getInterfaceData($postData, true, $columnId);

                if ($result['is_err'] == 0) {
                    $result['results'] = array_slice($result['results'], 0, $minNum['style']);
                    foreach ($result['results'] as $val) {
                        if ($metadataTmp == $val['metadata']) {
                            continue;
                        }
                        $metadata = explode('_', $val['metadata']);
                        $id = intval(array_pop($metadata));
                        $tablename = implode('_', $metadata);
                        $_res = OpPopFashionMerger::getProductData($id, $tablename, self::MEM_CACHE);
                        if (strpos($_res[$id][memo], '实物面料') !== false) {
                            $_col = 117;//实物面料特殊，属于展会面料栏目
                        } else {
                            $_col = 82;
                        }
                        $styleRst[] = ['tablename' => $tablename, 'pri_id' => $id, 'iColumnId' => array(1 => $_col)];
                    }
                }
            } else {
                $this->findDataBySolr($conStl, $minNum['style'], $styleRst);
            }
            $pics = [];
            foreach ($styleRst as $value) {
                $tablename = $value['tablename'];
                $pri_id = $value['pri_id'];
                $productData = $this->dealWithProductData($pri_id, $tablename);
                $imgArr = getImagePath($tablename, $productData);

                $result = [];
                $result['smallPath'] = $imgArr['smallPath']; // 款式图小图路径
                // id
                $result['id'] = $value['pri_id'];
                // 栏目id
                $colId = $value['iColumnId'][1];
                // 链接
                if ($iColumnPid === 6) {
                    //书籍类到二级列表页
                    $result['link'] = "/books/seclist/" . "id_" . $pri_id . "-t_" . $productData['t'] . "-yl_" . $productData['iPreviewMode'] . "-col_" . $colId . "/";
                } else {
                    // 这里还是用 $value['tablename']， 请勿修改
                    $result['link'] = $this->getDetailLink('style', $value['tablename'], $pri_id, $colId);
                }
                //季节
                $result['seasonText'] = '';
                $result['seasonLink'] = '';
                $seasonId = 0;
                if (isset($productData['for_date'])) {
                    $season = $productData['for_date'];
                    $seasonId = GetCategory::getIdsFrom(5, "sOriginalName," . $season);
                    $seasonId = $seasonId ? $seasonId[0] : 0;
                } elseif (isset($productData['fordate'])) {
                    $season = $productData['fordate'];
                    $seasonId = GetCategory::getIdsFrom(5, "sOriginalName," . $season);
                    $seasonId = $seasonId ? $seasonId[0] : 0;
                } elseif (isset($productData['popular_time'])) {
                    $season = $productData['popular_time'];
                    $seasonId = GetCategory::getIdsFrom(5, "sOriginalName," . $season);
                    $seasonId = $seasonId ? $seasonId[0] : 0;
                } elseif (isset($productData['iFordate'])) {
                    $seasonId = $productData['iFordate'];
                } elseif (isset($productData['iSeason'])) {
                    $seasonId = $productData['iSeason'];
                }
                if ($seasonId) {
                    $seasonArr = GetCategory::getOtherFromIds($seasonId, ['iAttributeId', 'sName'], 'array');
                    if ($seasonArr) {
                        $result['seasonText'] = $seasonArr['sName'][0];
                        $result['seasonLink'] = $this->common_model->getLink($colId, '', 'sea', $seasonId);
                    }
                }
                //单品
                $result['categoryText'] = '';
                $result['categoryLink'] = '';
                if (!isset($productData['iCategory']) && isset($productData['sCategory'])) {
                    $productData['iCategory'] = $productData['sCategory'];
                }
                if (isset($productData['iCategory'])) {
                    $categoryArr = GetCategory::getOtherFromIds($productData['iCategory'], ['iAttributeId', 'sName'], 'array');
                    if ($categoryArr) {
                        $result['categoryText'] = $categoryArr['sName'][0];
                        $result['categoryLink'] = $this->common_model->getLink($colId, '', 'cat', $categoryArr['iAttributeId'][0]);
                    }
                }
                // 品名
                $result['subCategoryText'] = '';
                $result['subCategoryLink'] = '';
                if (!isset($productData['iSubcategory']) && isset($productData['sSubcategory'])) {
                    $productData['iSubcategory'] = $productData['sSubcategory'];
                }
                if (isset($productData['iSubcategory'])) {
                    $categoryArr = GetCategory::getOtherFromIds($productData['iSubcategory'], ['iAttributeId', 'sName'], 'array');
                    if ($categoryArr) {
                        $result['subCategoryText'] = $categoryArr['sName'][0];
                        $result['subCategoryLink'] = $this->common_model->getLink($colId, '', 'cat', $categoryArr['iAttributeId'][0]);
                    }
                }
                // 品牌
                $result['brandText'] = '';
                $result['brandLink'] = '';
                if (isset($productData['brand_tid'])) {
                    $brandInfo = OpPopFashionMerger::getBrandData($productData['brand_tid']);
                } elseif (isset($productData['iBrandTid'])) {
                    $brandInfo = OpPopFashionMerger::getBrandData($productData['iBrandTid']);
                } elseif (isset($productData['brand'])) {
                    $brandTid = OpPopFashionMerger::getBrandtidByName($productData['brand'], 'en_cn_brand');
                    $brandInfo = OpPopFashionMerger::getBrandData($brandTid);
                } elseif (isset($productData['name'])) {
                    $brandTid = OpPopFashionMerger::getBrandtidByName($productData['name'], 'name');
                    $brandInfo = OpPopFashionMerger::getBrandData($brandTid);
                }
                if ($brandInfo) {
                    $result['brandText'] = $brandInfo['name'];
                    $result['brandLink'] = $this->common_model->getLink($colId, '', 'bra', $brandInfo['id']);
                }
                // 标题
                if ($iColumnPid == 6) {
                    switch ($tablename) {
                        case 'product_design_refrence':
                        case 'product_brochures':
                            $title = $productData['name_text'];
                            break;
                        case 'product_vector_refrence_group':
                            $title = $productData['theme'];
                            break;
                        default:
                            $title = $productData['title'];
                            break;
                    }
                    // 模板取数据用
                    $result['brandText'] = $title;
                    $result['brandLink'] = $result['link'];
                }
                //栏目82特殊
                if (isset($productData['memo']) && $colId == 82) {
                    $arr = OpPopFashionMerger::getPatternAttribute($productData['memo']);
                    $this->load->model('category_model');
                    $sPatternUse = $this->category_model->getAll('sPatternUse');
                    $result['sPatternUseName'] = $sPatternUse[$arr['sPatternUse']];
                    $result['sPatternContent'] = trim(GetCategory::getOtherFromIds($productData['sPatternContent'], ['sName']));
                }
                if ($colId == 120) {
                    $arr = OpPopFashionMerger:: getPatternAttribute($productData['memo']);

                    $this->load->model('category_model');
                    $sPatternUse = $this->category_model->getAll('sPatternUse');
                    $result['sPatternUseName'] = $sPatternUse[$arr['sPatternUse']];
                    $result['sPatternContent'] = trim(GetCategory::getOtherFromIds($productData['sPatternContent'], ['sName']));
                }
                //栏目91特殊
                if ($colId == 91 && isset($productData['title'])) {
                    $result['seasonLink'] = 'javascript:void(0);';
                    $result['sTitle'] = $productData['title'];
                }

                // 全球实拍54，取批发市场名/展会名
                if ($colId == 54) {
                    $this->load->model('details_model');
                    $marketName = $this->details_model->getMarketName($productData['source']);
                    $result['brandText'] = $marketName;
                }
                // 潮流领袖57,tideleader，取人名
                if ($colId == 57) {
                    $this->load->model('category_model');
                    $typeArr = $this->category_model->getAll('sStarLabel');
                    $starName = $typeArr[$productData['iStarLabel']];// 人名
                    $result['brandText'] = $starName;
                }

                $result['brandText'] = empty($result['brandText']) ? '其他' : $result['brandText'];

                $result['title'] = implode(' ', [$result['seasonText'], $result['categoryText'], $result['subCategoryText']]);
                array_push($pics, $result);
            }
            $res['pics'] = $pics;
        }

        $this->cache->memcached->save($mem_key, $res, 600);

        return $res;
    }

    public function getGenderIdForSolr($ids)
    {
        if ($ids) {
            if (in_array(5, $ids)) {
                array_push($ids, 3);
                array_push($ids, 4);
                $ids = array_unique($ids);
            }
        }
        return $ids;
    }

    /**
     * 获取标签特征值
     * @param string $table
     * @param int $id
     * @param int $key
     * @return string
     */
    public function getLabelId($table, $id, $key)
    {
        $sql = "SELECT sAttrValueIds FROM t_style_attr WHERE sTableName=? AND iPriId=? AND iAttrKeyId=?";
        $query = $this->db()->query($sql, [$table, $id, $key]);
        $result = $query->row_array();

        // $this->db()->close();
        return isset($result['sAttrValueIds']) ? $result['sAttrValueIds'] : 0;
    }

    // 报告栏目与款式栏目的相互对应
    private function reportReferStyle($columnId, $columnPid = 0, $style = false)
    {
        // 潮流解析与款式库的对应
        $refers = [
            30 => 50, 31 => 54, 32 => 52, 33 => 54, 34 => 55, 35 => 54, 37 => 56, 132 => 54
        ];
        if ($style) {
            $refers = [54 => [31, 33, 35, 132], 50 => 30, 52 => 32, 55 => 34, 56 => 37];
        }

        if ($refers[$columnId]) {
            return $refers[$columnId];
        } else {
            // 不存在则返回其父id
            if (!$columnPid) {
                $columnPid = GetCategory::getOtherFromColId($columnId, 'iColumnPid');
            }
            return intval($columnPid);
        }
    }

    private function dealWithProductData($id, $table)
    {
        $result = OpPopFashionMerger::getProductData($id, $table == 'product_vector_refrence_group' ? 'product_vector_refrence_list' : $table, self::MEM_CACHE);
        $result = $result[$id];
        $result['tablename'] = $table;
        $result['t'] = getProductTableName($table);

        return $result;
    }

    /**
     * 递归查询solr
     * @param $cond
     * @param $minNum
     * @param $result
     * @return mixed
     */
    private function findDataBySolr($cond = [], $minNum = 0, &$result, $isShiDu = false, $times = -1)
    {
        if (count($cond) == 0) {
            return 0;
        }

        if ($times < 0) {
            $times = count($cond);
        } else {
            $times--;
        }

        //试读多加固定不能删除条件
        if ($isShiDu) {
            $tempCond = [];
            if (empty($cond["iHot"])) {
                $tempCond[] = "iHot:1";
            }
            if (empty($cond["dCreateTime"])) {
                $tempCond[] = 'dCreateTime:[' . date('Y-m-d\TH:i:s\Z', strtotime('-19 month')) . ' TO ' . date('Y-m-d\TH:i:s\Z', strtotime('-18 month')) . '}';
            }
            if ($tempCond) {
                array_splice($cond, -2, 0, $tempCond);
            }
        }

        $conditions = ['other' => implode(' AND ', $cond)];

        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $minNum, $arSort, ['fl' => 'pri_id,tablename,iColumnId']);

        if ($totalCount < $minNum && (($isShiDu && $times > 4) || $times > 2)) {
            array_shift($cond);
            return $this->findDataBySolr($cond, $minNum, $result, $isShiDu, $times);
        } else {
            return $totalCount;
        }
    }

    /**
     * 报告专用通过表数据的栏目id 获取收藏的对应的类别
     * @param $col
     * @return int
     */
    private function getCollectTypeByColumnId($col)
    {
        //9:灵感报告 11:趋势 12:分析
        // colid:90 pid:1   pid:2
        if ($col == 90) {
            $collectType = 9;
        } else {
            $iColumnPid = GetCategory::getOtherFromColId($col, 'iColumnPid');
            if ($iColumnPid == 1) {
                $collectType = 11;
            } else {
                $collectType = 12;
            }
        }

        return $collectType;
    }

    /**
     * 从solr中获取栏目id
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function getColFromSolr($table = '', $id = 0)
    {
        $cond = ['pop_id:' . $table . '_' . $id];
        $con1 = ['other' => implode(' AND ', $cond)];
        $arSort = ['pri_id' => 'desc'];
        $result = [];
        POPSearch::wrapQueryPopFashionMerger('', $con1, $result, 0, 1, $arSort, ['fl' => 'pri_id,tablename,iColumnId']);

        return $result[0]['iColumnId'];
    }

    /**
     * 获取详情页链接
     * @param string $cat 类别
     * @param string $table 表名
     * @param int $id id
     * @param int $col 子栏目id
     * @return string
     */
    public function getDetailLink($cat = '', $table = '', $id = 0, $col = 0)
    {
        return "/details/" . $cat . "/t_" . getProductTableName($table) . "-id_" . $id . "-col_" . $col . "/";
    }

    public function getPdfPath($t = '', $id = 0, $col = 0)
    {
        $pdf = "/fashion/reportpdf/{$t}/{$col}/{$id}" . '/' . $t . '-' . $col . '-' . $id . '.pdf';
        return api_file_exists(substr(APPPATH, 0, -1) . $pdf) ? $pdf : self::PDF_GENERATING;
    }

    public function getMosPdfPath($path, $pdf_name)
    {
        $pdfPath = self::PDF_NOT_EXIST;
        if ($pdf_name && api_file_exists(APPPATH . $path . $pdf_name)) {
            $pdfPath = "/" . $path . $pdf_name;
        }

        return $pdfPath;
    }


    //无限层级 _findChildren("数组","初始值0","上级id","id")
    public function _findChildren($list, $p_id = 0, $superiorid = "pid", $originalid = "id")
    { //数据层级化，
        $r = array();
        if ($list) {
            foreach ($list as $id => $item) {
                //echo $item[$superiorid] ."==". $p_id."<br />";
                if ($item[$superiorid] == $p_id) {
                    $length = count($r);
                    $r[$length] = $item;
                    if ($t = $this->_findChildren($list, $item[$originalid], $superiorid, $originalid)) {
                        $r[$length]['children'] = $t;
                    }
                }
            }
        }
        return $r;
    }

    //获取中间页推荐的试读
    public function getShiDu($table = '', $id = 0, $columnId = 0, &$productData = [])
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_shidu_' . $table . '_' . $id . '_' . $columnId . '-2019081901';
        $res = $this->cache->memcached->get($mem_key);
        if (!$this->refresh && $res !== false) {
            return $res;
        }
        $res = [];
        $iColumnPid = GetCategory::getOtherFromColId($columnId, 'iColumnPid');
        $iColumnPid = intval($iColumnPid);
        if ($iColumnPid === 0) {
            return $res;
        }
        $this->load->helper('common_helper');
        $this->load->model('common_model');

        if (empty($productData)) {
            $result = OpPopFashionMerger::getProductData($id, $table, self::MEM_CACHE);
        } else {
            $result = $productData;
        }
        $result = $result[$id];
        $imgPath = getImagePath($table, $result);
        $metadataTmp = $table . '_' . $id;
        // 报告表关联标签的特殊处理
        if ($table == 't_trend_report') {
            $aRelationLabels = $this->getReportLabelById($id);
            $result['sRelationLabels'] = implode(',', $aRelationLabels);
        }

        $conRpt = []; // solr 查询条件(报告)
        $conStl = []; // solr 查询条件(款式)
        $solrPopId = $table . '_' . $id;
        if ($iColumnPid === 1) {
            //============ 关联标签条件 ============//
            if (isset($result['sRelationLabels']) && $result['sRelationLabels']) {
                $sRelationLabels = getSolrORString($result['sRelationLabels']);
                array_push($conRpt, 'aRelationLabels:' . $sRelationLabels);
                array_push($conStl, 'aRelationLabels:' . $sRelationLabels);
            }
            //============ 关联标签条件 ============//

            //============ 趋势主题条件 ============//
            if (isset($result['iRelatedTopics']) && $result['iRelatedTopics']) {
                array_push($conRpt, 'iRelationTheme:' . $result['iRelatedTopics']);
            }
            //============ 趋势主题条件 ============//

            //============ 年龄层条件 ============//
            if (isset($result['sAgeLayer']) && $result['sAgeLayer']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sAgeLayer']));
            }
            if (isset($result['sAgeLevel']) && $result['sAgeLevel']) {
                // specialtopic_graphic|trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sAgeLevel']));
            }
            //============ 年龄层条件 ============//

            //============ 季节条件 ============//
            if ($iSeason) {
                $tmpSeason = GetCategory::getSeason($iColumnPid, 'sEnName');
                if (isset($tmpSeason[$iSeason])) {
                    array_push($conStl, 'iSeasonSuffix:' . substr($tmpSeason[$iSeason], -2));
                }
            }
            //============ 季节条件 ============//

            //============ 品名条件 ============//
            if (isset($result['sSubcategory']) && $result['sSubcategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
            }
            if (isset($result['iSubcategory']) && $result['iSubcategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
            }
            //============ 品名条件 ============//

            //============ 单品条件 ============//
            if (isset($result['sCategory']) && $result['sCategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sCategory']));
            }
            if (isset($result['iCategory']) && $result['iCategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iCategory']));
            }
            //============ 单品条件 ============//

            //============ 视角条件 ============//
            $sVisualAngle = false;
            if (isset($result['iView'])) {
                // trends_new.`mostrend_content`|specialtopic|specialtopic_graphic
                $sVisualAngle = getSolrORString($result['iView']);
            } elseif (isset($result['sView'])) {
                // fs_*
                $sVisualAngle = getSolrORString($result['sView']);
            } elseif (isset($result['sVisualAngle'])) {
                // t_trend_report
                $sVisualAngle = getSolrORString($result['sVisualAngle']);
            }
            if ($sVisualAngle) {
                //报告类栏目
                array_push($conRpt, 'aLabelIds:' . $sVisualAngle);
            }
            //============ 视角条件 ============//

            //============ 流行时间条件 ============//
            $iSeason = false;
            if (isset($result['popular_time'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['popular_time'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['fordate'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['fordate'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['iSeason'])) {
                $iSeason = intval($result['iSeason']);
            }
            if ($iSeason) {
                array_push($conRpt, 'aLabelIds:' . $iSeason); //季节
                array_push($conStl, 'aLabelIds:' . $iSeason); //季节
            }
            //============ 流行时间条件 ============//

            //============ 行业条件 ============//
            $sIndustry = $sIndustry2 = false;
            if (isset($result['iIndustry'])) {
                $sIndustry = getSolrAndString($result['iIndustry']);
                $sIndustry2 = getSolrORString($result['iIndustry']);
            } elseif (isset($result['sIndustry'])) {
                $sIndustry = getSolrAndString($result['sIndustry']);
                $sIndustry2 = getSolrORString($result['sIndustry']);
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry2) {
                array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
            }
            //============ 行业条件 ============//

            //============ 性别条件 ============//
            $sGender = $sGender2 = false;
            if (isset($result['channel'])) {
                // 高端的性别
                $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['channel'], $iColumnPid);
                $sGender = $sGender2 = $ids[0];
            } elseif (isset($result['iGender'])) {
                $sGender = $sGender2 = intval($result['iGender']);
            } elseif (isset($result['type'])) {
                $types = explode(',', $result['type']);
                $ids = [];
                foreach ($types as $val) {
                    $tmp = GetCategory::getIdsFrom(1, "sOriginalName," . $val, $iColumnPid);
                    array_push($ids, $tmp[0]);
                }
                if ($ids) {
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
            }
            if ($sGender) {
                array_push($conRpt, 'aLabelIds:' . $sGender); //性别
            }
            if ($sGender2) {
                array_push($conStl, 'aLabelIds:' . $sGender2); //性别
            }
            //============ 性别条件 ============//

            //============ 栏目条件 ============//
            array_push($conRpt, 'iColumnId:' . getSolrORString('1,2')); //报告类栏目
            array_push($conStl, 'iColumnId:4'); //款式类栏目
            //============ 栏目条件 ============//

            array_push($conRpt, '-pop_id:' . $solrPopId);
            array_push($conStl, '-pop_id:' . $solrPopId);
        }
        // 潮流解析
        if ($iColumnPid === 2) {
            //============ 关联标签条件 ============//
            if (isset($result['sRelationLabels']) && $result['sRelationLabels']) {
                $sRelationLabels = getSolrORString($result['sRelationLabels']);
                array_push($conRpt, 'aRelationLabels:' . $sRelationLabels);
                array_push($conStl, 'aRelationLabels:' . $sRelationLabels);
            }
            //============ 关联标签条件 ============//

            //============ 品名条件 ============//
            if (isset($result['sSubcategory']) && $result['sSubcategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sSubcategory']));
            }
            if (isset($result['iSubcategory']) && $result['iSubcategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iSubcategory']));
            }
            //============ 品名条件 ============//

            //============ 单品条件 ============//
            if (isset($result['sCategory']) && $result['sCategory']) {
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['sCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['sCategory']));
            }
            if (isset($result['iCategory']) && $result['iCategory']) {
                // specialtopic  specialtopic_graphic  trends_new.`mostrend_content`
                array_push($conRpt, 'aLabelIds:' . getSolrORString($result['iCategory']));
                array_push($conStl, 'aLabelIds:' . getSolrORString($result['iCategory']));
            }
            //============ 单品条件 ============//

            //============ 品牌条件 ============//
            if (isset($result['iBrandTid']) && $result['iBrandTid'] > 0) {
                array_push($conRpt, 'iBrand:' . intval($result['iBrandTid']));
                array_push($conStl, 'iBrand:' . intval($result['iBrandTid']));
            } elseif (isset($result['brand']) && $result['brand']) {
                // 高端的直接是brand
                $brandTid = OpPopFashionMerger::getBrandtidByName($result['brand'], 'en_cn_brand', self::MEM_CACHE);
                if ($brandTid > 0) {
                    array_push($conRpt, 'iBrand:' . intval($brandTid));
                    array_push($conStl, 'iBrand:' . intval($brandTid));
                }
            }
            //============ 品牌条件 ============//

            //============ 视角条件 ============//
            $sVisualAngle = false;
            if (isset($result['iView'])) {
                // trends_new.`mostrend_content`|specialtopic|specialtopic_graphic
                $sVisualAngle = getSolrORString($result['iView']);
            } elseif (isset($result['sView'])) {
                // fs_*
                $sVisualAngle = getSolrORString($result['sView']);
            } elseif (isset($result['sVisualAngle'])) {
                // t_trend_report
                $sVisualAngle = getSolrORString($result['sVisualAngle']);
            }
            if ($sVisualAngle) {
                //报告类栏目
                array_push($conRpt, 'aLabelIds:' . $sVisualAngle);
            }
            //============ 视角条件 ============//

            //============ 流行时间条件 ============//
            $iSeason = false;
            if (isset($result['popular_time'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['popular_time'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['fordate'])) {
                $ids = GetCategory::getIdsFrom(5, "sOriginalName," . $result['fordate'], $iColumnPid);
                $iSeason = $ids[0];
            } elseif (isset($result['iSeason'])) {
                $iSeason = intval($result['iSeason']);
            }
            if ($iSeason) {
                array_push($conRpt, 'aLabelIds:' . $iSeason); //季节
                array_push($conStl, 'aLabelIds:' . $iSeason); //季节
            }
            //============ 流行时间条件 ============//

            //============ 季节条件 ============//
            if ($iSeason) {
                $tmpSeason = GetCategory::getSeason($iColumnPid, 'sEnName');
                if (isset($tmpSeason[$iSeason])) {
                    array_push($conStl, 'iSeasonSuffix:' . substr($tmpSeason[$iSeason], -2));
                }
            }
            //============ 季节条件 ============//

            //============ 行业条件 ============//
            $sIndustry = $sIndustry2 = false;
            if (isset($result['iIndustry'])) {
                $sIndustry = getSolrAndString($result['iIndustry']);
                $sIndustry2 = getSolrORString($result['iIndustry']);
            } elseif (isset($result['sIndustry'])) {
                $sIndustry = getSolrAndString($result['sIndustry']);
                $sIndustry2 = getSolrORString($result['sIndustry']);
            }
            if ($sIndustry) {
                array_push($conRpt, 'aLabelIds:' . $sIndustry); //行业
            }
            if ($sIndustry2) {
                array_push($conStl, 'aLabelIds:' . $sIndustry2); //行业
            }
            //============ 行业条件 ============//

            //============ 性别条件 ============//
            $sGender = $sGender2 = false;
            if (isset($result['channel'])) {
                // 高端的性别
                $ids = GetCategory::getIdsFrom(1, "sOriginalName," . $result['channel'], $iColumnPid);
                $sGender = $sGender2 = $ids[0];
            } elseif (isset($result['iGender'])) {
                $sGender = $sGender2 = intval($result['iGender']);
            } elseif (isset($result['type'])) {
                $types = explode(',', $result['type']);
                $ids = [];
                foreach ($types as $val) {
                    $tmp = GetCategory::getIdsFrom(1, "sOriginalName," . $val, $iColumnPid);
                    array_push($ids, $tmp[0]);
                }
                if ($ids) {
                    // 报告
                    $sGender = implode(',', $ids);
                    $sGender = getSolrAndString($sGender);
                    // 款式
                    $ids2 = $this->getGenderIdForSolr($ids);
                    $sGender2 = implode(',', $ids2);
                    $sGender2 = getSolrORString($sGender2);
                }
            }
            if ($sGender) {
                array_push($conRpt, 'aLabelIds:' . $sGender); //性别
            }
            if ($sGender2) {
                array_push($conStl, 'aLabelIds:' . $sGender2); //性别
            }
            //============ 性别条件 ============//

            //============ 栏目条件 ============//
            array_push($conRpt, 'iColumnId:' . getSolrORString('1,2')); //报告类栏目
            $_stlColumnId = $this->reportReferStyle($columnId, 4, false);
            //============ 栏目条件 ============//
            array_push($conRpt, '-pop_id:' . $solrPopId);

        }

        $reportRst = [];
        $this->findDataBySolr($conRpt, 4, $reportRst, true);
        $reports = [];
        foreach ($reportRst as $value) {
            $productData = $this->dealWithProductData($value['pri_id'], $value['tablename']);
            $imgArr = getImagePath($value['tablename'], $productData);

            $result = [];
            $result['smallPath'] = $imgArr['cover']; // 报告图封面图
            $colId = $value['iColumnId'][1];
            $result['col'] = $colId;
            $result['link'] = $this->getDetailLink('report', $value['tablename'], $value['pri_id'], $colId); // 报告链接

            // 标题
            if (isset($productData['sTitle'])) {
                $result['title'] = $productData['sTitle'];
            } else {
                $result['title'] = $productData['title'];
            }
            // 描述
            if (isset($productData['abstract'])) {
                $result['description'] = $productData['abstract'];
            } else {
                $result['description'] = $productData['description'];
            }

            // 浏览量
            $result['view_count'] = $productData['view_count'];
            if (isset($productData['views'])) {
                $result['view_count'] = $productData['views'];
            }
            if (isset($productData['click_rate'])) {
                $result['view_count'] = $productData['click_rate'];
            }
            if (isset($productData['iViewCount'])) {
                $result['view_count'] = $productData['iViewCount'];
            }
            // 显示时间
            $result['order_time'] = $productData['order_time'];
            if (isset($productData['addtime'])) {
                $result['order_time'] = $productData['addtime'];
            }
            if (isset($productData['release_time'])) {
                $result['order_time'] = $productData['release_time'];
            }
            if (isset($productData['dPubTime'])) {
                $result['order_time'] = $productData['dPubTime'];
            }
            $result['order_time'] = substr($result['order_time'], 0, 10);
            array_push($reports, $result);
        }
        $res['reports'] = $reports;
        $this->cache->memcached->save($mem_key, $res, 600);
        return $res;
    }
}
