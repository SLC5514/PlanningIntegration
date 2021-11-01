<?php

/**
 * Created by PhpStorm.
 * User: dsx
 * Date: 2019/6/24
 * Time: 16:41
 */
class DataGrand_model extends POP_Model
{

    public $powers;
    public $allColumn;
    public $allIndustry;
    public $allGender = array(1 => '男', 2 => '女', 5 => '童');

    public function __construct()
    {
        parent::__construct();
        $this->powers = array(
            'user_type' => 5,//用户身份类型 1VIP  4普通 5游客
            'user_power' => array('have_gen' => '', 'have_ind' => '', 'have_col' => '',),//当前用户所拥有的性别 行业 栏目权限
        );

        $this->allColumn = $this->getColumns();//所有栏目信息
        $this->allIndustry = $this->getAllInd();//所有行业信息
    }

    //查询服装权限表 获取推荐列表权限
    public function getRecommendPower()
    {

        $userInfo = get_cookie_value();
        if (!$userInfo['id']) {
            //游客
            return $this->powers;
        }
        $userId = $userInfo['id'];
        $now = date('Y-m-d H:i:s');
        $sql = "select * from fashion.fm_privilege where iAccountId=? and dStartTime<=? and dEndTime>=? and iType in(1,3)";
        $result = $this->db()->query($sql, array($userId, $now, $now))->result_array();
        if (!$result) {
            //普通
            $this->powers['user_type'] = 4;
            return $this->powers;
        }

        $genderArr = $indArr = $columnArr = array();
        foreach ($result as $value) {
            $genderArr = array_merge($genderArr, explode(',', $value['sGender']));
            $indArr = array_merge($indArr, explode(',', $value['sIndustry']));;
            $columnArr = array_merge($columnArr, explode(',', $value['sColumn']));;
        }
        $columnArr = array_unique($columnArr);
        $genderArr = array_unique($genderArr);
        $indArr = array_unique($indArr);

        $haveColumn = array_intersect(array_keys($this->allColumn), $columnArr);

        if (empty($haveColumn)) {
            //1.不是款式/图案栏目vip，但是其他栏目vip的用户，按普通用户权限推荐；
            $this->powers['user_type'] = 4;

        } else {
            $this->powers['user_type'] = 1;
            /* vip
                性别  		行业
                不全 		不全	    按性别查询
                不全		全     	按性别查询
                全			全		不带性别行业查询
                全	        不全	    按行业查询
            */

            $numInd = count($this->allIndustry);//全行业
            $haveInd = array_intersect(array_keys($this->allIndustry), $indArr);

            if ((count($genderArr) < 3 && count($haveInd) < $numInd) || (count($genderArr) < 3 && count($haveInd) == $numInd)) {
                $this->powers['user_power']['have_gen'] = implode(',', $genderArr);
            }
            if (count($genderArr) == 3 && count($haveInd) < $numInd) {
                $this->powers['user_power']['have_ind'] = implode(',', $indArr);
            }

        }

        return $this->powers;
    }

    //获取推荐列表条件数组
    public function getRecommendCondition($paramsArr)
    {
        $this->getRecommendPower();
        $conditions = $cateId = $combine = array();
        $userInfo = get_cookie_value();
        $userId = $userInfo['id'];
        if (!$userId || $this->powers['user_type'] == 5) {
            return array();
        }
        //常规条件
        $conditions['userid'] = $userId;
        $conditions['cnt'] = isset($paramsArr['page_size']) && !empty($paramsArr['page_size']) ? $paramsArr['page_size']
            : 60;
        $conditions['scene_type'] = 'personal_picture_clothing';

        $patternPrefix = array(
            'col' => '服装_图案',
            'gen' => '服装_图案_性别_',
        );
        $stylePrefix = array(
            'col' => '服装_款式',
            'ind' => '服装_款式_行业_',
            'gen' => '服装_款式_性别_',
        );

        $gen_name = $ind_name = array();
        if (!empty($this->powers['user_power']['have_gen'])) {
            $gender = explode(',', $this->powers['user_power']['have_gen']);
            foreach ($gender as $id) {
                $gen_name[] = $this->allGender[$id];
            }
        }
        if (!empty($this->powers['user_power']['have_ind'])) {
            $ind = explode(',', $this->powers['user_power']['have_ind']);
            foreach ($ind as $id) {
                $ind_name[] = $this->allIndustry[$id];
            }
        }

        //性别
        if (array_filter($gen_name)) {
            foreach ($gen_name as $name) {
                $combine[] = $stylePrefix['gen'] . $name;
                $combine[] = $patternPrefix['gen'] . $name;
            }
        }
        //行业
        if (array_filter($ind_name)) {
            foreach ($ind_name as $name) {
                $combine[] = $stylePrefix['ind'] . $name;
                $combine['pattern'] = $patternPrefix['col'];
            }
        }


        if (!empty($paramsArr)) {

            $col = !empty($paramsArr['col']) ? $paramsArr['col'] : '';
            if (is_array($col)) {
                //栏目
                foreach ($col as $id) {
                    if ($id == 4) {
                        $cateId[] = $stylePrefix['col'];
                    } elseif ($id == 82) {
                        $cateId[] = $patternPrefix['col'] . '_' . $this->allColumn[$id];
                    } else {
                        $cateId[] = $stylePrefix['col'] . '_' . $this->allColumn[$id];
                    }
                }
            }else{
                $cateId[] = $col == 4 ? $stylePrefix['col'] : $patternPrefix['col'];

            }

        } else {
            $cateId[] = $stylePrefix['col'];
            $cateId[] = $patternPrefix['col'];
        }

        //普通用户不带性别行业
        $conditions['cateid'] = array_filter($cateId) ? implode(',', array_filter($cateId)) : '';
        if ($this->powers['user_type'] == 1) {
            $conditions['combine'] = array_filter($combine) ? implode(',', array_filter($combine)) : '';
        }

        return $conditions;
    }

    //获取个性化推荐列表数据
    public function recommendList($paramsArr)
    {
        $json = $this->getJsonOutputObj();
        $return = $ids = $data = array();
        //获取推荐条件
        $conditions = $this->getRecommendCondition($paramsArr);
        if (!$conditions) {
            $json->code(0)->msg('没有权限')->data($return)->out();
        }

        //调用达观个性化接口
        $response = DataGrandCommon::getRecGrandData($conditions);
        $status = !empty($response) ? strtolower($response['status']) : '';

        if ($status == 'ok' && !empty($response['recdata'])) {
            $result = $response['recdata'];
            $request_id = $response['request_id'];
            $return = $this->dealData($result, $request_id);
        }

        $json->code(0)->msg('ok')->data($return)->info(['scene_type' => 'relate_picture_clothing'])->out();

    }

    public function dealData($pop_ids, $request_id, $type = '')
    {
        $return = $ids = array();
        if (empty($pop_ids)) {
            return $return;
        }
        foreach ($pop_ids as $key => $val) {
            $pop_id = $val['itemid'];
            $index = stripos($pop_id, '_');
            $end = strripos($pop_id, '_');
            $table = substr($pop_id, $index + 1, $end - 2);
            $id = substr($pop_id, $end + 1);
            $ids[$table][] = $id;
        }
        if (!$ids) {
            return $return;
        }
        foreach ($ids as $table => $idArr) {
            if (!$idArr) {
                break;
            }

            $res = OpPopFashionMerger::getProductData($idArr, $table);
            if ($res) {
                foreach ($res as $info) {
                    if (empty($info)) {
                        continue;
                    }
                    $info['table'] = $table;

                    $t = getProductTableName($table);
                    $imgPath = getImagePath($table, $info);// 图片路径
                    list($colId, $colName) = $this->getColId($info);
                    $createTime = isset($info['create_time']) ? $info['create_time'] : $info['dCreateTime'];
                    if ($type == 'relate') {
                        $_type = 'relate_picture_clothing';
                    } else {
                        $_type = 'personal_picture_clothing';
                    }
                    $return[] = [
                        'id' => $info['id'],
                        'create_time' => $createTime,
                        'cover' => getFixedImgPath($imgPath['cover']),
                        'columnId' => $colId,
                        't' => $t,
                        'link' => "/details/style/t_{$t}-id_{$info['id']}-col_{$colId}/?request_id={$request_id}&scene_type={$_type}",
                        'request_id' => $request_id,
                    ];

                }
            }

        }

        return $return;
    }

    //猜你喜欢
    public function getLikeDataByPopId($table, $id, $col = '',$cnt=20)
    {
        $jo = $this->getJsonOutputObj();
        $lists = array();
        $arrCookie = get_cookie_value();
        $userId = $arrCookie['id'];
        if (!$userId) {
            $jo->code(0)->data($lists)->msg('未登陆')->out();
        }

        if (!empty($table) && !empty($id) || !empty($col)) {
            if (in_array($col, [9, 82, 120])) {
                $cateid = '服装_图案';
            } else {
                $cateid = '服装_款式';
            }
            $itemid = '1_' . $table . '_' . $id;
            $cond = array(
                'userid' => $userId,
                'itemid' => $itemid,
                'cateid' => $cateid,
                'start' => 0,
                'cnt' => $cnt,
                'scene_type' => 'relate_picture_clothing',
            );
            $response = DataGrandCommon::getRelateGrandData($cond);

            if ($response['status'] == 'OK') {
                $pop_ids = $response['recdata'];
                if (!empty($pop_ids)) {
                    $lists = $this->dealData($pop_ids, $response['request_id'], 'relate');
                }
            }
        }

        $jo->code(0)->data($lists)->msg('ok')->info(['scene_type' => 'relate_picture_clothing'])
            ->out();

    }


    public function getColumns()
    {
        //图案素材款式栏目
        $allColumn = array(9 => '图案', 82 => '图案素材', 4 => '款式');
        $subColumns = GetCategory::getColumn(4);
        $subColumns = DataGrandCommon::array_column($subColumns, 'sName', 'iColumnId');
        $allColumn = $allColumn + $subColumns;
        return $this->allColumn = $allColumn;

    }


    public function getAllInd()
    {
        $sql = "select iAttributeId,sName from fashion.t_dict_attribute where iType=? and iAttributePid!=0";
        $res = $this->db()->query($sql, [2])->result_array();
        $return = DataGrandCommon::array_column($res, 'sName', 'iAttributeId');
        return $this->allIndustry = $return;
    }

    public function getColId($data = array())
    {
        //栏目
        switch ($data['table']) {
            case 'product_graphicitem':
            case 'product_fabricgallery_item':
                $col_id = 82;
                if ($data['memo'] && strpos($data['memo'], '大牌图案') !== false) {
                    $col_id = 120;  // 图案库--大牌花型栏目
                }
                break;
            case 'product_marketphoto_item':
                $col_id = 54;
                break;
            case 'product_perform':
                $col_id = 50;
                break;
            case 'product_ordermeeting':
                $col_id = 52;
                break;
            case 'product_streetitem':
                $col_id = 56;
                break;
            case 'product_tideleader':
                $col_id = 57;
                break;
            case 'product_style_graphic':
            case 'product_style_graphic_china':
            case 'product_brand':
            default:
                $col_id = OpPopFashionMerger::getBrandSubColId($data['brand_tid']);// 122|123|55
                break;
        }
        $col_name = GetCategory::getOtherFromColId($col_id, 'sName');
        return array($col_id, $col_name);
    }


}