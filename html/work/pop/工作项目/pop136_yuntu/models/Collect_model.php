<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo 收藏model
 */
class Collect_model extends POP_Model
{
    // 云图的收藏表
    const T_YUNTU_COLLECT = '`yuntu`.`t_collect`';
    public $userId;

    public function __construct()
    {
        parent::__construct();
        $this->userId = getUserId();
    }

    /**
     * 获取收藏列表
     *
     * @param int $offset 起始位置
     * @param int $limit 查询多少条数据
     * @param string $sDatabase 数据库库名 默认是服装库的
     * @param string $change pattern--图案收藏列表  trends--趋势解读/图案趋势收藏列表
     * @return mixed
     */
    public function getList($offset, $limit, $sDatabase = 'fashion', $change = 'pattern')
    {
        if (!$this->userId) {
            return false;
        }
        $this->load->model(['PatternLibrary_model', 'TrendsPattern_model']);

        $whereSql = '';
        switch ($change) {
            // 趋势解读/图案趋势收藏列表
            case 'trends':
                $whereSql = " AND sTableName = 't_trend_report' ";
                break;
            // 图案收藏列表
            case 'pattern':
                $whereSql = " AND sTableName !='t_trend_report' ";
                break;
        }

        $list = array();
        $sql = "SELECT COUNT(*) AS total FROM " . self::T_YUNTU_COLLECT . " WHERE iStatus=1 AND sAccountId=? AND sDatabase=? {$whereSql} ORDER BY dCreateTime DESC ";
        $res = $this->query($sql, [$this->userId, $sDatabase]);

        // 无收藏时，返回空数组，$total为 0
        $total = intval($res[0]['total']); // 获取总的数量
        if ($total <= 0) {
            return [$list, $total];
        }

        // 获取列表 iStatus=1 为 已收藏
        $sql = "SELECT * FROM " . self::T_YUNTU_COLLECT . " WHERE iStatus=1 AND sAccountId=? AND sDatabase=? {$whereSql} ORDER BY dCreateTime DESC LIMIT {$offset}, {$limit}";
        $result = $this->query($sql, [$this->userId, $sDatabase]);

        if (!empty($result)) {
            switch ($change) {
                case 'pattern':
                    foreach ($result as $key => $val) {
                        // true -- 获取列表数据
                        $detailInfo = $this->PatternLibrary_model->getDetail($val["sTableName"], $val["iPriId"], [], true);
                        // 特殊处理，云图没有走solr的缘故
                        if (strpos($detailInfo['cover'], '.comsmall/') === false && !empty($detailInfo['cover'])) {
                            $row["t"] = getProductTableName($val["sTableName"]);// 假表名
                            $row["id"] = $val["iPriId"];
                            $row["url"] = $this->PatternLibrary_model->getLink();
                            $row["collect_status"] = $val['iStatus'] == 1 ? 1 : 0; // 1--已收藏 0--未收藏
                            $row["index"] = $offset++;

                            $list[$key] = array_filter(array_merge($row, $detailInfo));
                        } else {
                            $total = $total - 1;
                        }
                    }
                    break;
                case 'trends':
                    $this->load->model(["Graphicitem_model", "Collect_model"]);

                    foreach ($result as $key => $val) {
                        $tableName = $val["sTableName"];
                        $id = $val["iPriId"];
                        $t = getProductTableName($val["sTableName"]);

                        $_result = OpPopFashionMerger::getProductData($id, $tableName);
                        $_res = array_values($_result);
                        $res = $_res[0];
                        // t_trend_report iStatus状态 0=>正常 1=>已删除 2=>未发布 3=>预览
                        if ($res['iStatus'] != 0) {
                            $total = $total - 1;
                            continue;
                        }

                        $row['t'] = $t;
                        $row['id'] = $id;
                        $row['col'] = $colId = !empty($res['iOriginColumn']) ? $res['iOriginColumn'] : 126; // 126--图案趋势（2020-06-22）
                        $row['title'] = htmlspecialchars(stripcslashes($res['sTitle']));//标题
                        $row['publish_time'] = !empty($res['dPubTime']) ? date('Y-m-d', strtotime($res['dPubTime'])) : '';// 发布时间
                        $row['memo'] = htmlspecialchars(trim(strip_tags($res['sDesc']))); // 描述
                        $row['view'] = $this->Graphicitem_model->getViews($tableName, $id, $res);
                        $row["url"] = $this->TrendsPattern_model->getLink();
                        $imgPath = getImagePath($val["sTableName"], $res);
                        $row["cover"] = $imgPath['smallPath'];// 封面图/小图
                        $row["labels"] = $this->TrendsPattern_model->getLabelsInfo($colId, $tableName, $id, [], 'list');// 获取标签
                        $row['detail_url'] = '/trendspattern/detail/t_' . $row['t'] . '-id_' . $id . '-col_' . $colId . '/';// 详情链接
                        if (!empty($row["cover"])) {
                            // 收藏
                            if ($tableName == 't_trend_report') {
                                $row['collect_status'] = $this->Collect_model->getCollectStatus($row['t'], $id, 'fashion', true);
                            } else {
                                $row['collect_status'] = 0;
                            }
                            $row["index"] = $offset++;
                            $list[$key] = $row;
                        } else {
                            $total = $total - 1;
                        }
                    }
                    break;
            }
        }
        return [$list, $total];
    }

    /**
     * 图案库获取列表/详情的用户收藏状态
     *
     * @param string $table 假表名
     * @param $id
     * @param string $sDatabase
     * @param bool $is_pattern 是否是图案详情，true--是
     * @return mixed
     */
    public function getCollectStatus($table, $id, $sDatabase = 'fashion', $is_pattern = false)
    {
        if (!$this->userId) {
            return false;
        }
        $tablename = getProductTableName($table);// 转为真表名
        $sql = "SELECT id,iStatus FROM " . self::T_YUNTU_COLLECT . " WHERE sAccountId=? AND sTableName=? AND iPriId=? AND sDatabase=? ORDER BY dCreateTime DESC LIMIT 1";
        $res = $this->query($sql, [$this->userId, $tablename, $id, $sDatabase]);

        // 图案库详情，返回iStatus状态值
        if ($is_pattern) {
            return $res[0]['iStatus'] == 1 ? 1 : 0;
        }

        // 我的收藏列表返回数组
        return $res;
    }

    /**
     * 取消收藏 || 加入收藏
     * @param string $table 假表名
     * @param int $id
     * @param string $handle 参数： join--加入收藏 || cancel--取消收藏
     * @param string $sDatabase
     * @return mixed
     */
    public function setCollectStatus($table, $id, $handle = 'join', $sDatabase = 'fashion')
    {
        if (!$this->userId || !$table || !$id) {
            return false;
        }

        $res = $this->getCollectStatus($table, $id, $sDatabase);
        $tablename = getProductTableName($table);// 真表名

        // 数据库中有数据时 iStatus 为 0--未收藏 || 1--已收藏 处理
        if ($res) {
            if ($res[0]['iStatus'] == 1 && $handle == 'cancel') {
                $res = true;
            }
            if ($res[0]['iStatus'] == 0 && $handle == 'join') {
                $res = true;
            }
        }

        $rows = [];
        $nowTime = date("Y-m-d H:i:s");
        switch ($handle) {
            // 取消收藏
            case 'cancel' :
                if ($res) {
                    $data_update = [
                        'iStatus' => 0,
                        'dUpdateTime' => $nowTime,
                    ];
                    $condition = [
                        'sAccountId' => $this->userId,
                        'sTableName' => $tablename, // 真表名入库
                        'sDatabase' => $sDatabase,
                        'iPriId' => $id
                    ];
                    $result = $this->executeUpdate(self::T_YUNTU_COLLECT, $data_update, $condition);
                    if ($result) {
                        $rows['code'] = 10;
                        $rows['msg'] = '取消收藏成功';
                    }
                }
                break;

            // 加入收藏
            case "join":
            default:
                if (!$res) {
                    $data_save = [
                        'sAccountId' => $this->userId,
                        'sDatabase' => $sDatabase,
                        'sTableName' => $tablename, // 真表名入库
                        'iPriId' => $id,
                        'iStatus' => 1,
                        'dCreateTime' => $nowTime,
                    ];
                    $result = $this->executeSave(self::T_YUNTU_COLLECT, $data_save);
                } else {
                    // 收藏表有数据并且'iStatus' = 0
                    if ($res[0]['iStatus'] == 1) {
                        return false;
                    }
                    $data_update = [
                        'iStatus' => 1,
                        'dUpdateTime' => $nowTime,
                    ];
                    $condition = [
                        'sAccountId' => $this->userId,
                        'sTableName' => $tablename,
                        'sDatabase' => $sDatabase,
                        'iPriId' => $id
                    ];
                    $result = $this->executeUpdate(self::T_YUNTU_COLLECT, $data_update, $condition);
                }
                if ($result) {
                    $rows['code'] = 20;
                    $rows['msg'] = '加入收藏成功';
                }
                break;
        }
        return $rows;
    }

}