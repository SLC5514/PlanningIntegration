<?php
/**
 * app注册类
 * Class Favorite_model
 */

class Favorite_model extends POP_Model
{
    // 云图的收藏表
    const T_YUNTU_COLLECT = '`yuntu`.`t_collect`';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取收藏列表
     *
     * @param int $offset
     * @param int $limit
     * @param string $sDatabase 数据库库名
     * @return mixed
     */
    public function getList($offset, $limit, $userId, $col = 'pattern', $sDatabase = 'fashion')
    {
        if (!$userId) {
            return false;
        }

        // 通过 t_trend_report 来区分 报告 与 图案
        switch ($col) {
            case 'pattern':
                $where_sql = " AND sTableName != 't_trend_report' ";
                break;
            case 'report':
                $where_sql = " AND sTableName = 't_trend_report' ";
                break;
        }

        $list = array();
        $sql = "SELECT COUNT(*) AS total FROM " . self::T_YUNTU_COLLECT . " WHERE iStatus=1  {$where_sql}  AND  sAccountId=? AND sDatabase=? ORDER BY dCreateTime DESC,id DESC ";
        $res = $this->query($sql, [$userId, $sDatabase]);
        $total = intval($res[0]['total']);// 获取符合条件的总数
        if ($total <= 0) { // 无收藏时
            return [$list, $total];
        }

        // 获取列表
        $sql = "SELECT * FROM " . self::T_YUNTU_COLLECT . " WHERE iStatus=1 {$where_sql} AND  sAccountId=? AND sDatabase=? ORDER BY dCreateTime DESC,id DESC LIMIT {$offset}, {$limit}";
        $result = $this->query($sql, [$userId, $sDatabase]);

        $list = array();
        if (!empty($result)) {
            foreach ($result as $key => $val) {
                $row["popId"] = getProductTableName($val["sTableName"]) . "_" . $val["iPriId"];
                $row['id'] = $val["iPriId"];
                $row['t'] = getProductTableName($val["sTableName"]);
                $_result = OpPopFashionMerger::getProductData($val["iPriId"], $val["sTableName"]);
                $_res = array_values($_result);
                $res = $_res[0];

                // t_trend_report iStatus状态 0=>正常 1=>已删除 2=>未发布 3=>预览
                if ($res['iStatus'] != 0 && $val["sTableName"] == 't_trend_report') {
                    $total = $total - 1;
                    continue;
                }

                $imgPath = getImagePath($val["sTableName"], $res);

                // TODO 收藏状态 0未收藏 1已收藏
                $row['iFavoriteType'] = isset($val['iStatus']) && !empty($val['iStatus']) ? $val['iStatus'] : 0;// iStatus:1-收藏，0-未收藏
                if (!empty($res) && isset($res)) {
                    switch ($val["sTableName"]) {
                        // 报告
                        case 't_trend_report':
                            $row['title'] = htmlspecialchars(stripcslashes($res['sTitle']));//标题
                            $row['intro'] = htmlspecialchars(trim(strip_tags($res['sDesc']))); // 描述
                            $row["cover_pic"] = $row["small"] = $row["imgPath"] = $imgPath['smallPath'];// 封面图/小图
                            $row['big'] = $res['sBigImgPath'];// 大图
                            if (!empty($imgPath['smallPath'])) {
                                $list[$key] = $row;
                            } else {
                                $total = $total - 1;
                            }
                            break;
                        // 图案
                        case 'product_graphicitem':
                        case 'product_fabricgallery_item':
                        default:
                            // 列表取中图，没有取大图
                            $row["small"] = $row["imgPath"] = $imgPath['smallPath'];
                            $this->load->model("Graphicitem_model");
                            $row["mbig"] = $this->Graphicitem_model->getPath($imgPath['bigPath']);
                            $row["big"] = $imgPath['bigPath'];
                            $row['memo'] = $row['intro'] = $res['memo'];// 我的收藏列表描述
                            if (!empty($imgPath['bigPath'])) {
                                $list[$key] = $row;
                            } else {
                                $total = $total - 1;
                            }
                            break;
                    }
                }
            }
        }
        return [$list, $total];
    }

    /**
     * 取消收藏 || 加入收藏
     * @param $table
     * @param $id
     * @param string $sDatabase
     * @return mixed
     */
    public function setCollectStatus($table, $id, $userId, $handle = 'join', $sDatabase = 'fashion')
    {
        if (!$table || !$id || !$userId) {
            return false;
        }

        // 判断该条数据是否收藏
        $tablename = getProductTableName($table);// 真表名

        $sql = "SELECT id,iStatus FROM " . self::T_YUNTU_COLLECT . " WHERE sAccountId=? AND sTableName=? AND iPriId=? AND sDatabase=? ORDER BY dCreateTime DESC LIMIT 1";
        $res = $this->query($sql, [$userId, $tablename, $id, $sDatabase]);

        // 数据库中有数据时 iStatus 为 0 || 1 处理
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
            case 'cancel' : // 已收藏的取消收藏
                if ($res) {
                    $data_update = [
                        'iStatus' => 0,
                        'dUpdateTime' => $nowTime,
                    ];
                    $condition = [
                        'sAccountId' => $userId,
                        'sDatabase' => $sDatabase,
                        'sTableName' => $tablename,
                        'iPriId' => $id
                    ];
                    $result = $this->executeUpdate(self::T_YUNTU_COLLECT, $data_update, $condition);

                    // 取消收藏的返回值
                    if ($result) {
                        $rows['code'] = 10;
                        $rows['msg'] = '取消收藏成功';
                    }
                }
                break;
            case "join": // 加入收藏
                if (!$res) {
                    // 1, 数据库表没有记录的时候，插入数据
                    $data_save = [
                        'sAccountId' => $userId,
                        'sDatabase' => $sDatabase,
                        'sTableName' => $tablename, // 真表名入库
                        'iPriId' => $id,
                        'iStatus' => 1,
                        'dCreateTime' => $nowTime,
                    ];
                    $result = $this->executeSave(self::T_YUNTU_COLLECT, $data_save);
                } else {
                    // 已加入收藏，不要重复收藏 （1，join）
                    if ($res[0]['iStatus'] == 1) {
                        return false;
                    }
                    $data_update = [
                        'iStatus' => 1,
                        'dUpdateTime' => $nowTime,
                    ];
                    $condition = [
                        'sAccountId' => $userId,
                        'sTableName' => $tablename,
                        'sDatabase' => $sDatabase,
                        'iPriId' => $id
                    ];
                    $result = $this->executeUpdate(self::T_YUNTU_COLLECT, $data_update, $condition);
                }

                // 加入收藏的返回值
                if ($result) {
                    $rows['code'] = 20;
                    $rows['msg'] = '加入收藏成功';
                }
                break;
        }
        return $rows;
    }

}