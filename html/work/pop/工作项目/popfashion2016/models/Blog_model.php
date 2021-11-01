<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/20
 * Time: 10:56
 */
class Blog_model extends POP_Model
{
    private $table = '`pop_blog`.`t_blog`';
    private $label_table = '`pop_blog`.`t_blog_label`';
    private $hot_label_table = '`pop_blog`.`t_blog_hotlabel`';

    //获取详情
    public function get_detail($id)
    {
        $hash = $this->input->get("hash");
        $time = $this->input->get("time");
        $s_hash = md5(POP_GLOBAL_KEYS . "_" . $id . "_" . $time);
        if (!empty($hash) && $hash == $s_hash && (time() - $time) <= 600) {
            $sql = "SELECT * FROM {$this->table} WHERE id=?";
            $result = $this->db()->query($sql, [$id])->row_array();

        } else {
            $sql = "SELECT * FROM {$this->table} WHERE id=? AND iPublishStatus!=2 AND iStatus=1";
            $result = $this->db()->query($sql, [$id])->row_array();
        }
        $result["sLabel"] = $this->get_tags($result["sLabel"]);
        return $result;
    }

    //获取标签
    public function get_tags($ids, $paramsArr = [])
    {
        $ids = is_array($ids) ? implode(",", $ids) : $ids;
        if (empty($ids)) {
            return [];
        }
        $sql = "SELECT id,sLabelName FROM {$this->label_table} WHERE id IN ($ids)";
        $res = $this->db()->query($sql)->result_array();
        $result = [];
        foreach ($res as $val) {
            $result[$val['id']]['name'] = $val["sLabelName"];
            $result[$val['id']]['link'] = $this->get_link($paramsArr, 'label', $val['id']);
            $result[$val['id']]['clear_link'] = $this->get_link($paramsArr, 'label', '');
        }
        return $result;
    }

    //获取上一页下一页
    public function get_pre_next($id, $dPublishTime, $site = '', $label = '')
    {
        $now_time = date('Y-m-d H:i:s');
        $site_where = empty($site) ? 'AND sWebsite NOT IN(2,4)' : " AND FIND_IN_SET($site,sWebsite)";
        $site_where .= empty($label) ? ' ' : " AND FIND_IN_SET($label,sLabel) ";
        $pre_sql = "SELECT id,sWebsite FROM {$this->table} WHERE iPublishStatus=1 AND iStatus=1 AND dPublishTime<=? AND dPublishTime>=? AND id!=? {$site_where} ORDER BY dPublishTime ASC,id ASC LIMIT 1";
        $next_sql = "SELECT id,sWebsite FROM {$this->table} WHERE iPublishStatus=1 AND iStatus=1  AND dPublishTime<=? AND id!=? {$site_where} ORDER BY dPublishTime DESC,id DESC LIMIT 1";
        $pre_res = $this->db()->query($pre_sql, [$now_time, $dPublishTime, $id])->row_array();
        $next_res = $this->db()->query($next_sql, [$dPublishTime, $id])->row_array();
        $result["pre"] = $result["next"] = "";
        if (!empty($pre_res["id"])) {
            $paramsArr = [];
            $paramsArr["id"] = intval($pre_res["id"]);
            $site && $paramsArr["site"] = $site;
            $label && $paramsArr['label'] = $label;
            $params = $this->common_model->parseParams($paramsArr, 2, false);
            $result["pre"] = "/blog/detail/$params/";
        }
        if (!empty($next_res["id"])) {
            $paramsArr = [];
            $paramsArr["id"] = intval($next_res["id"]);
            $site && $paramsArr["site"] = $site;
            $label && $paramsArr['label'] = $label;
            $params = $this->common_model->parseParams($paramsArr, 2, false);
            $result["next"] = "/blog/detail/$params/";
        }
        return $result;
    }

    //获取列表
    public function get_list($offset = 0, $limit = 10, $site = "", $order = "dPublishTime DESC,id DESC", $label = '')
    {
        $now_time = date('Y-m-d H:i:s');

        $filed = "id,sTitle,sCoverImgPath,sAuthor,dPublishTime";
        $where = "iPublishStatus=1 AND iStatus=1 AND dPublishTime <= '{$now_time}'";
        $where .= empty($site) ? ' AND sWebsite NOT IN(2,4)' : " AND FIND_IN_SET($site,sWebsite)";
        $where .= !empty($label) ? " AND FIND_IN_SET($label,sLabel)" : '';

        $this->db()->select($filed)->from($this->table)->where($where);
        $count = $this->db->count_all_results("", FALSE);
        $query = $this->db->order_by($order)->limit($limit, $offset)->get();
        $result = $query->result_array();
        $params = [];
        foreach ($result as $key => $val) {
            $params['id'] = $val["id"];
            $params['site'] = $site;
            $label && $params['label'] = $label;
            $params = array_filter($params);
            $paramStr = $this->common_model->parseParams($params, 2);
            $result[$key]["url"] = "/blog/detail/{$paramStr}/";
        }
        return array($result, $count);
    }

    /**
     * 取当前站点下的热门标签
     * @param $site
     * @return mixed
     */
    public function get_hot_label($site)
    {
        $site = empty($site) ? 7 : $site;
        $sql = "select id,sHotLabel from {$this->hot_label_table} where id=? AND iStatus=1";
        $result = $this->query($sql, [$site]);
        return $result[0];
    }

    /**
     * 获取link
     * @param array $paramsArr
     * @param array $type
     * @param string $val
     * @return string
     */
    public function get_link($paramsArr = [], $type, $val)
    {
        $root_link = "/blog/";
        if (isset($paramsArr[$type])) {
            if (empty($val)) {
                unset($paramsArr[$type]);
            } else {
                $paramsArr[$type] = $val;
            }
        } else {
            if (!empty($val)) {
                $paramsArr[$type] = $val;
            }
        }
        $link = empty($paramsArr) ? $root_link : $root_link . $this->common_model->parseParams($paramsArr, 2) . '/';
        return $link;
    }

    //取热门标签(列表和详情通用)
    public function get_hot_tag_data($paramsArr = array(), $site = '')
    {
        $hot_labels = $selected_tag = [];
        $labels = $this->get_hot_label($site);
        $hot_labels = $this->get_tags($labels['sHotLabel'], $paramsArr);
        $selected_tag = [];
        if (!empty($paramsArr['label'])) {
            $selected_tag = $this->get_tags($paramsArr['label'], $paramsArr)[$paramsArr['label']];
        }
        $hot_tag['hot_labels'] = $hot_labels;
        $hot_tag['selected_tag'] = $selected_tag;
        return $hot_tag;
    }

}