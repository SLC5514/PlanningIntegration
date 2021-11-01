<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 个性化首页model处理
 * Class RelateHome_model
 * @property-read common_model $common_model
 */
class RelateHome_model extends POP_Model
{
    const RELATE_RECOMMEND_LABELS_HOME_TABLE = '`fashion`.`recommend_labels_home`';//推荐标签表
    const RELATE_RELATE_HOME_HOME_TABLE = '`fashion`.`relate_home`';//偏好设置/模块个性化设置表
    const RELATE_FM_AD_TABLE = '`fashion`.`fm_ad`';//后台广告表
    const RELATE_T_RELATE_LABEL_IMG_TABLE = '`fashion`.`t_relate_label_img`';//后台今日推荐配图管理

    const MODULE_LOOK_SHOW = 1;//专属看板模块
    const MODULE_TOP_SHOW = 2;//热门排行榜模块
    const MODULE_TOP_RELATE = 4;//热门排行榜是否开启个性化
    const MODULE_NEW_REPORT_RELATE = 8;//最新报告是否开启个性化
    const MODULE_HOT_REPORT_RELATE = 16;//热门报告是否开启个性化
    const MODULE_T_SHOW_RELATE = 32;//T台模块
    const MODULE_ADVERT_RELATE = 64;//广告大片模块
    // const MODULE_ALL_ON = 31;
    const MODULE_ALL_ON = 127;//默认所有模块都开启 二期新增T台/广告大片模块

    const RELATE_LABEL_MEM_KEY = 'RELATE_HOME_LABELS_COVER';//头部单品季节风格元素等标签的缓存 第二天8点更新缓存
    const RELATE_STYLE_TOP_MEM_KEY = 'RELATE_HOME_STYLE_TOP_COVER';//个性化首页款式排行榜top5缓存 0点更新缓存


    public $assoc;
    public $user_like;//用户偏好选择
    public $module;//用户模块设置
    public $group_field;

    public function __construct()
    {
        parent::__construct();
        //获取用户模块设置
        $this->module = $this->get_module_setting();
        //款式筛选标签条件对应键
        $this->assoc = [
            'sea' => 'sea', 'category' => 'cat', 'style' => 'man', '廓形' => 'shap', '细节' => 'spe', '工艺' => 'tech', '图案' => 'pat', '面料' => 'fab', '辅料' => 'acc', 'brand' => 'bra'
        ];

        //专属看板筛选条件
        $this->arr = [
            'gen' => 'iGender', 'gcen' => 'iSubGender', 'ind' => 'iIndustry', 'sea' => 'iSeason', 'reg' => 'iRegion', 'exh' => 'sExhibitionName', 'mar' => 'sMarketType', 'bpos' => 'sMarketHotPosition', 'sta' => 'sStarLabel', 'bra' => 'iBrand', 'cat' => 'iCategory', 'aco' => 'sAssortColor', 'age' => 'sAgeLayer', 'wea' => 'sDress', 'regs' => 'sRegionStyle', 'cha' => 'sCharacterStyle', 'fas' => 'sFashionWeek', 'str' => 'iStreetBeatType', 'man' => 'sManner', 'shap' => 'sShape', 'spe' => 'sSpecifics', 'tech' => 'sTechnology', 'pat' => 'sPattern', 'fab' => 'sFabric', 'acc' => 'sAccessory', 'key' => 'sKeywords', 'prop' => 'iStyleOrManuscript', 'ds' => 'iGlobalPhotoShotTab'
        ];
        //用户偏好数组
        $this->user_like = ['gen' => [], 'ind' => [], 'cate' => []];
        $this->get_like();

    }


    //当前用户已选中的偏好
    public function get_like()
    {

        //性别 行业 裙裤
        $gender = GetCategory::getSomeAttr(1);
        $ind = GetCategory::getSomeAttr(2);
        // $cate = ['11116'=>'裤','11118'=>'裙'];
        $cate = ['11116' => '裤/裙'];

        //已选中
        $res = $this->get_relate_set();
        $have_like = $res['sLike'] ? explode(',', $res['sLike']) : [];
        //用户已选则的偏好
        $this->user_like['gen'] = array_values(array_intersect(array_flip($gender), $have_like));
        $this->user_like['ind'] = array_values(array_intersect(array_flip($ind), $have_like));
        $this->user_like['cate'] = array_values(array_intersect(array_flip($cate), $have_like));
        if ($this->user_like['cate']) {
            $this->user_like['cate'] = [11116, 11118];
        }
        if (in_array(5, $this->user_like['gen'])) {
            $this->user_like['gen'] = array_merge($this->user_like['gen'], [3, 4]);
        }

        return $have_like;
    }

    //返回用户模块、个性化设置、偏好设置权限数组
    public function get_module_setting()
    {
        $user_id = getUserId();
        if (!$user_id) {
            return [];
        }
        $res = $this->get_relate_set();
        $like = $res['sLike'] ? explode(',', $res['sLike']) : [];
        if (in_array('11116', $like)) {
            array_push($like, '11118');
        } elseif (in_array(5, $like)) {
            $like = array_merge($like, ['3', '4']);
        }

        //如果用户从未设置过推荐页 默认全部开启
        if ($res) {
            $module_button = 0;
            if ($res['iModuleButton']) {
                $module_button = $res['iModuleButton'];
            }
            //二期暂时不用
            // $module_popular_select = $res['sModulePopularSelect'] ? explode(',', $res['sModulePopularSelect']) : [];
        } else {
            $module_button = self::MODULE_ALL_ON;
            //二期暂时不用
            // $module_popular_select = [1, 2, 3, 4];

        }

        //各模块状态数组
        $module['user_exists'] = $res;
        $module['look_show'] = self::MODULE_LOOK_SHOW & $module_button ? 1 : 0;//专属看板是否显示
        $module['top_show'] = self::MODULE_TOP_SHOW & $module_button ? 1 : 0;//top榜是否显示
        $module['top_relate'] = self::MODULE_TOP_RELATE & $module_button ? 1 : 0;//top榜是否开启个性化
        $module['new_report_relate'] = self::MODULE_NEW_REPORT_RELATE & $module_button ? 1 : 0;//最新报告是否开启个性化
        $module['hot_report_relate'] = self::MODULE_HOT_REPORT_RELATE & $module_button ? 1 : 0;//热门报告是否开启个性化
        $module['t_show_relate'] = self::MODULE_T_SHOW_RELATE & $module_button ? 1 : 0;//t台是否展示
        $module['advert_relate'] = self::MODULE_ADVERT_RELATE & $module_button ? 1 : 0;//广告大片是否展示


        $module['like'] = $res['sLike'] ? explode(',', $res['sLike']) : [];//偏好设置  return [1,2,5,6,……]
        $module['like_name'] = [];
        if ($like) {
            foreach ($like as $id) {
                if (!in_array($id, [3, 4])) {
                    $module['like_name'][] = GetCategory::getAttrNameById($id);//偏好的名称
                }
            }
        }
        $module['iModuleButton'] = $module_button;
        //top榜显示内容- 1：图案；2：色彩；3：风格；4：品牌；5：面料；
        // $module['module_popular_select'] = $module_popular_select; // return [1,2,3,……]
        return $module;
    }

    //后台有配图的所有标签
    public function g_cate()
    {
        $return = $data = [];

        // 品牌
        $have_brand = $this->get_brands();// 已选中的品牌
        // 联想品牌 Gucci、Burberry、Off-White、Dolce & Gabbana、channel 常驻在搜索框后方
        $b = [];
        $brand_attr = ['82445' => 'Gucci', '61919' => 'Burberry', '32723' => 'Off-White', '141067' => 'Dolce & Gabbana', '3586' => 'Chanel'];
        foreach ($brand_attr as $brandId => $name) {
            $b[$brandId] = GetCategory::getBrandOtherFormId($brandId, 'name');
        }
        $_aBrands = $b + $have_brand;
        $data['brand'] = $aBrands = !empty($_aBrands) ? array_unique(array_filter($_aBrands)) : [];

        // 季节、风格、元素 (廓形、细节、工艺、图案、面料、辅料)
        $type = [5 => 'sea', 28 => 'style', 29 => 'ele'];
        foreach ($type as $_type => $name) {
            $data[$name] = GetCategory::getSomeAttr($_type);
        }

        //取季节前7个
        // $data['sea'] = array_slice($data['sea'], 0, 15, true);

        //取单品品名 平铺显示没有层级结构
        $data['category'] = [];
        $category = GetCategory::getSingle('', true, true);
        foreach ($category as $key => $value) {
            $cate = [$key => $value['sName']] + $value['Pinming'];
            $data['category'] += $cate;
        }


        //元素
        $e = [];
        $element = [11768 => '廓形', 11797 => '细节', 11755 => '工艺', 11787 => '图案', 11776 => '面料', 11752 => '辅料',];
        foreach ($element as $pid => $name) {
            if ($data['ele'][$pid]['attrs']) {
                foreach ($data['ele'][$pid]['attrs'] as $id => $val) {
                    $e[$name][$id] = $val['sName'];
                }
            }
        }
        unset($data['ele']);
        $data += $e;
        //已选中的标签
        list($sel, $is_exist) = $this->get_user_have_label();
        //今日推荐配图存在的标签
        foreach ($data as $pid => $value) {
            $selected = $brand_selected = [];
            switch ($pid) {
                case 'brand':
                    if (!empty($have_brand)) {
                        foreach ($have_brand as $brand_id => $brand_name) {
                            if (array_key_exists($brand_id, $value)) {
                                $brand_selected[] = (string)$brand_id;
                            }
                        }
                    }
                    // 品牌标签
                    $s[$pid] = array_unique(array_filter($brand_selected));// 已选中品牌标签数组
                    $return[$pid]['selected'] = array_unique(array_filter($brand_selected));
                    break;
                default:
                    if ($is_exist) {
                        foreach ($sel as $id) {
                            if (array_key_exists($id, $value)) {
                                $selected[] = $id;
                            }
                        }
                    }
                    // 其他标签
                    $s[$pid] = array_unique(array_filter($selected));//已选中标签数组
                    $return[$pid]['selected'] = array_unique(array_filter($selected));
                    break;
            }
            $return[$pid]['label'] = $value;

        }
        $return['sea']['label'] = array_slice($return['sea']['label'], 0, 7, true);

        // 品牌选中项，补充
        if (!empty($have_brand)) {
            $sel['brand'] = array_unique(array_filter(array_keys($have_brand)));
        }

        return array($return, $s, $sel);
    }

    // 获取数据库品牌  品牌跟属性的ID可能重复的，无法区分的,新建字段
    protected function get_brands()
    {
        // 获取品牌
        $data = $this->get_user_have_brand_label();
        if (empty($data)) return [];

        $brand = [];
        foreach ($data as $id) {
            $brand_name = GetCategory::getBrandOtherFormId($id, 'en_cn_brand');
            if (!empty($brand_name)) {
                $brand[$id] = (string)$brand_name;
            }
        }
        return $brand;
    }

    //二期-头部banner
    public function get_banner($position = 28, $limit = 6)
    {
        $t = self::RELATE_FM_AD_TABLE;
        $date = date('Y-m-d H:i:s');
        $where = ' dStartTime <=? AND dEndTime>? AND  iPosition=?  AND iStatus=? ';
        $val = [$date, $date, $position, 1, $limit];
        $sql = "SELECT iAdId,sGender,iStatus,sTitle,sUrl,iPosition,dStartTime,dEndTime,sImagePath,dUpdateTime FROM {$t} where {$where
}  ORDER BY  iAdId desc, dUpdateTime desc LIMIT ?";
        $ads = $this->query($sql, $val);
        foreach ($ads as $key => &$val) {
            $val['sUrl'] = "/statistics/link/?url=" . base64_encode($val["iAdId"]) . "_" . base64_encode($val["sUrl"]);
        }

        return $ads;
    }

    /**获取已选标签的封面图-后台今日推荐配图管理 缓存1小时
     * @param array $have_label 已选的标签id
     * @return array
     */
    /*
    public function get_label_imgs($have_label = [])
    {
        $this->load->driver('cache', ['adapter' => 'memcached']);
        $mem_key = 'FASHION_RELATE_HOME_LABEL_IMGS';

        $imgs = $this->cache->memcached->get($mem_key);

        if (empty($imgs) || $this->input->get_post('refresh')) {
            //取后台今日推荐配图
            $table = self::RELATE_T_RELATE_LABEL_IMG_TABLE;
            $sql = " select id,iLabelId,sImgUrl from {$table} where sImgUrl !='' AND iDisplay='1'";
            $labels_img = $this->query($sql);
            $imgs = [];
            foreach ($labels_img as $key => $value) {
                $imgs[$value['iLabelId']] = [
                    'id' => $value['id'],
                    'label_id' => $value['iLabelId'],
                    'cover' => $value['sImgUrl'],
                ];
            }
            $this->cache->memcached->save($mem_key, $imgs, 3600);

        }


        $return = [];
        if (!empty($have_label)) {

            foreach ($have_label as $id) {
                if ($imgs[$id]) {
                    $return[$id] = $imgs[$id];
                }

            }

        }

        return !empty($return) ? $return : $imgs;
    }
    */

    // 二期-获取20个头部标签数据
    public function get_recommoned_label()
    {
        //后台推荐标签
        $fixed_label = $this->get_fixed_label();

        //取列表封面图 跳转链接
        list($have_label, $is_exist) = $this->get_user_have_label();// 其他标签
        $have_brand_label = $this->get_user_have_brand_label();// 品牌标签单独获取

        list($labels, $selected,) = $this->g_cate();
        if ((empty($have_label) && empty($have_brand_label)) || !$selected) {
            return [$fixed_label, array()];
        }

        $return_data = array_fill_keys($have_label, []);// 其他标签
        $return_brands_data = array_fill_keys($have_brand_label, []);// 品牌标签单独获取

        foreach ($selected as $key => $value) {
            switch ($key) {
                case 'brand': // 品牌
                    $tmp = [];
                    $rs = array_intersect($value, $have_brand_label);
                    if (!$rs) continue;
                    foreach ($rs as $id) {
                        $params = $this->assoc[$key] . '_' . $id . '-dis_1-ind_0-gen_0';
                        $tmp['id'] = $id;
                        $tmp['name'] = GetCategory::getBrandOtherFormId($id, 'name');
                        $tmp['link'] = $this->common_model->getLink(4, $params);
                        $tmp['label_type'] = 'brand';
                        $return_brands_data[$id] = $tmp;
                    }
                    break;
                default:
                    $return = [];
                    //取交集 获取当前选中id的标签分类
                    $rs = array_intersect($value, $have_label);
                    if (!$rs) continue;
                    foreach ($rs as $id) {
                        $params = $this->assoc[$key] . '_' . $id . '-dis_1-ind_0-gen_0';
                        $return['id'] = $id;
                        $return['name'] = GetCategory::getAttrNameById($id);
                        $return['link'] = $this->common_model->getLink(4, $params);
                        $return['label_type'] = 'list';
                        $return_data[$id] = $return;
                    }
                    break;
            }
        }

        $return_data = array_reverse($return_data);
        $return_data = array_merge($return_brands_data, $return_data);// 品牌
        $return_data = array_merge($return_data, $fixed_label);
        $return_data = array_values(array_filter($return_data));
        $have_label = array_values(array_filter($have_label));
        $have_brand_label = array_values(array_filter($have_brand_label));// 品牌

        return [$return_data, $have_label, $have_brand_label];
    }

    //固定5个标签-获取后台标签管理中与用户偏好选择相符合的5个标签
    public function get_fixed_label()
    {
        $t = self::RELATE_FM_AD_TABLE;
        $ads = $ad_arr = $num_arr = [];

        $like_ad = [
            'id' => 0,
            'name' => '猜你喜欢',
            'link' => '/patterns/patternRecommend/',
            'label_type' => 'list',
            'num_sort' => 1,
        ];

        $like = array_merge($this->user_like['gen'], $this->user_like['ind']);
        $like_ids = array_values($like);

        $date = date('Y-m-d H:i:s');
        $where = ' dStartTime <=? AND dEndTime>? AND  iPosition=?  AND iStatus=? ';
        $sql = "SELECT iAdId,sGender,iStatus,sTitle,sUrl,iPosition,dStartTime,dEndTime,sImagePath,dUpdateTime FROM {$t} where {$where}  ORDER BY dUpdateTime desc ";
        $ads = $this->query($sql, [$date, $date, 26, 1]);
        if (!$ads) {
            return [$like_ad];
        }
        //取出所有广告，取交集计算出最符合用户偏好的广告
        $ad_arr = $ad_arr1 = [];
        foreach ($ads as $key => $value) {

            //没有选择偏好
            $ad_arr[$key] = [
                'id' => $value['iAdId'],
                'name' => $value['sTitle'],
                'link' => $value['sUrl'],
                'label_type' => 'list',
                'num_sort' => true,
            ];

            if ($like_ids) {//有偏好
                $ad_label = explode(',', $value['sGender']);
                $num = count(array_intersect($like_ids, $ad_label));
                if (!$num) {
                    unset($ads[$key]);
                } else {
                    $num_arr[] = $num;
                    $ad_arr1[$key] = [
                        'id' => $value['iAdId'],
                        'name' => $value['sTitle'],
                        'link' => $value['sUrl'],
                        'label_type' => 'list',
                        'num_sort' => $num . strtotime($value['dUpdateTime']),
                        'gender' => $value['sGender']
                    ];

                }
            }

        }

        if ($num_arr) {
            $ad_arr = twoDimensionSort($ad_arr1, 'num_sort');
        }

        $ad_arr = array_slice($ad_arr, 0, 5);
        array_unshift($ad_arr, $like_ad);

        return $ad_arr;
    }

    //新增、修改用户自选标签
    public function mod_label($label_arr, $brand_arr, $a = '')
    {
        // 个性化标签为空时，也可以保存，相当于用户什么也不选
        $label_arr = !empty($label_arr) ? array_filter(array_unique($label_arr)) : [];
        $brand_arr = !empty($brand_arr) ? array_filter(array_unique($brand_arr)) : [];

        $data = $cond = [];
        $user_id = getUserId();
        //已选标签
        list($have_labels, $is_exist) = $this->get_user_have_label();
        //已选品牌标签
        $have_brand_labels = $this->get_user_have_brand_label();

        if ($a == 'save') {
            // 1, 修改
            if ($is_exist || !empty($have_brand_labels)) {
                //自选标签最多14个 , sLabelIds 前端传递可见的标签
                $label_all_count = count($label_arr) + count($brand_arr); // 选择  品牌 + 其他标签\
                if (intval($label_all_count) > 14) {
                    return false;
                }
                $cond['iUserId'] = $user_id;
                $cond['iStatus'] = '1';
                //追加标签
                $data['sLabelIds'] = !empty($label_arr) ? trim(implode(',', $label_arr), ',') : '';
                // 品牌标签--追加标签
                $data['sBrandIds'] = !empty($brand_arr) ? trim(implode(',', $brand_arr), ',') : '';
                return $this->executeUpdate(self::RELATE_RECOMMEND_LABELS_HOME_TABLE, $data, $cond);
            } else {
                // 2,新增
                $data['iUserId'] = $user_id;
                // 其他标签
                $data['sLabelIds'] = !empty($label_arr) ? trim(implode(',', $label_arr), ',') : '';
                // 品牌标签
                $data['sBrandIds'] = !empty($brand_arr) ? trim(implode(',', $brand_arr), ',') : '';
                return $this->executeSave(self::RELATE_RECOMMEND_LABELS_HOME_TABLE, $data);
            }

        } elseif ($a == 'del') {
            // 3, 删除
            $cond['iUserId'] = $user_id;
            $cond['iStatus'] = '1';
            // 其他标签
            $labels = array_diff($have_labels, $label_arr);
            $data['sLabelIds'] = !empty($labels) ? trim(implode(',', $labels), ',') : '';
            // 品牌标签
            $brand_labels = array_diff($have_brand_labels, $brand_arr);
            $data['sBrandIds'] = !empty($brand_labels) ? trim(implode(',', $brand_labels), ',') : '';
            return $this->executeUpdate(self::RELATE_RECOMMEND_LABELS_HOME_TABLE, $data, $cond);
        }

    }

    //获取当前用户已选择的标签id
    public function get_user_have_label()
    {
        $t = self::RELATE_RECOMMEND_LABELS_HOME_TABLE;

        $labels = [];

        $user_id = getUserId();
        $sql = "select * from {$t} where iUserId=? and iStatus=? order by dCreateTime desc limit 1";
        $res = $this->query($sql, [$user_id, 1]);

        if ($res && !empty($res[0]['sLabelIds'])) {
            $labels = explode(',', $res[0]['sLabelIds']);
            $labels = array_unique(array_filter($labels));
        }
        return [$labels, !empty($res) ? true : false];
    }

    //获取当前用户已选择的品牌标签id
    public function get_user_have_brand_label()
    {
        $t = self::RELATE_RECOMMEND_LABELS_HOME_TABLE;

        $labels = [];
        $user_id = getUserId();
        $sql = "select * from {$t} where iUserId=? and iStatus=? order by dCreateTime desc limit 1";
        $res = $this->query($sql, [$user_id, 1]);

        if ($res && !empty($res[0]['sBrandIds'])) {
            $labels = explode(',', $res[0]['sBrandIds']);
        }
        return array_unique(array_filter($labels));
    }

    //保存偏好设置
    public function save_like($label_arr)
    {
        $user_id = getUserId();

        $t = self::RELATE_RELATE_HOME_HOME_TABLE;
        $like_id = $label_arr ? implode(',', $label_arr) : '';

        $data = [
            'sLike' => $like_id,
            'iUserId' => $user_id,
        ];
        if (!empty($label_arr)) {
            if ($this->module['user_exists']) {
                return $this->executeUpdate($t, $data, ['iUserId' => $user_id]);
            } else {
                return $this->executeSave($t, $data);
            }
        } else {
            if ($this->module['user_exists']) {
                return $this->executeUpdate($t, $data, ['iUserId' => $user_id]);
            } else {
                return true;
            }
        }

    }

    //获取用户各模块设置
    public function get_relate_set()
    {
        $t = self::RELATE_RELATE_HOME_HOME_TABLE;
        $user_id = getUserId();
        $sql = "select * from {$t} where iUserId=?  limit 1";
        $res = $this->query($sql, [$user_id]);
        return $res ? $res[0] : [];
    }

    //保存模块设置
    public function save_module($params = [])
    {
        if (!$params) {
            return false;
        }

        $user_id = getUserId();
        $data = [];

        //已有模块权限
        $is_module_val = $val = $this->module['iModuleButton'];

        // 默认所有模块都开启 .如果模块之前设置过，需要先删除之前的设置
        if (isset($params['look_show'])) {//专属看板
            //判断此模块是否已存在
            $module_val = $is_module_val & self::MODULE_LOOK_SHOW;
            if (empty($params['look_show']) && $module_val) {//  选择隐藏模块 && 已存在此模块 就减掉此模块权限
                $val = $is_module_val - self::MODULE_LOOK_SHOW;
            }
            if (!empty($params['look_show']) && !$module_val) {// 选择展示模块 && 不存在此模块 就加上此模块权限
                $val = $is_module_val | self::MODULE_LOOK_SHOW;
            }

        }
        //热门排行榜
        if (isset($params['top_show'])) {
            //判断此模块是否已存在
            $module_val = $is_module_val & self::MODULE_TOP_SHOW;
            if (empty($params['top_show']) && $module_val) {//  选择隐藏模块 && 已存在此模块 就减掉此模块权限
                $val = $val - self::MODULE_TOP_SHOW;
            }
            if (!empty($params['top_show']) && !$module_val) {// 选择展示模块 && 不存在此模块 就加上此模块权限
                $val = $val | self::MODULE_TOP_SHOW;
            }
        }
        //T台
        if (isset($params['t_show'])) {
            //判断此模块是否已存在
            $module_val = $is_module_val & self::MODULE_T_SHOW_RELATE;
            if (empty($params['t_show']) && $module_val) {//  选择隐藏模块 && 已存在此模块 就减掉此模块权限
                $val = $val - self::MODULE_T_SHOW_RELATE;
            }
            if (!empty($params['t_show']) && !$module_val) {// 选择展示模块 && 不存在此模块 就加上此模块权限
                $val = $val | self::MODULE_T_SHOW_RELATE;
            }
        }
        //广告大片
        if (isset($params['advert_show'])) {
            //判断此模块是否已存在
            $module_val = $is_module_val & self::MODULE_ADVERT_RELATE;
            if (empty($params['advert_show']) && $module_val) {//  选择隐藏模块 && 已存在此模块 就减掉此模块权限
                $val = $val - self::MODULE_ADVERT_RELATE;
            }
            if (!empty($params['advert_show']) && !$module_val) {// 选择展示模块 && 不存在此模块 就加上此模块权限
                $val = $val | self::MODULE_ADVERT_RELATE;
            }
        }
        // if (isset($params['module_popular_select']) && !empty($params['module_popular_select'])) {
        //     $data['sModulePopularSelect'] = implode(',', $params['module_popular_select']);
        // }
        // if (!isset($params['module_popular_select']) || empty($params['module_popular_select'])) {
        //     $data['sModulePopularSelect'] = '';
        // }
        $data['sModulePopularSelect'] = '';
        $data['iModuleButton'] = $val;
        $data['iUserId'] = $user_id;

        if (!$this->module['user_exists']) {
            return $this->executeSave(self::RELATE_RELATE_HOME_HOME_TABLE, $data);
        } else {
            $condition = ['iUserId' => $user_id];
            return $this->executeUpdate(self::RELATE_RELATE_HOME_HOME_TABLE, $data, $condition);
        }
    }

    //保存个性化设置
    public function save_relate($params = [])
    {
        if (!$params) {
            return false;
        }
        $user_id = getUserId();
        $data = [];

        //判断模块是否开启
        $is_module_val = $val = $this->module['iModuleButton'];
        if (isset($params['top_relate'])) {

            $module_val = $is_module_val & self::MODULE_TOP_RELATE;
            if (empty($params['top_relate']) && $module_val) {
                $val = $is_module_val - self::MODULE_TOP_RELATE;
            }
            if (!empty($params['top_relate']) && !$module_val) {
                $val = $is_module_val | self::MODULE_TOP_RELATE;
            }
        }

        if (isset($params['new_report_relate'])) {
            $module_val = $is_module_val & self::MODULE_NEW_REPORT_RELATE;
            if (empty($params['new_report_relate']) && $module_val) {
                $val = $val - self::MODULE_NEW_REPORT_RELATE;
            }
            if (!empty($params['new_report_relate']) && !$module_val) {
                $val = $val | self::MODULE_NEW_REPORT_RELATE;
            }
        }
        if (isset($params['hot_report_relate'])) {
            $module_val = $is_module_val & self::MODULE_HOT_REPORT_RELATE;
            if (empty($params['hot_report_relate']) && $module_val) {
                $val = $val - self::MODULE_HOT_REPORT_RELATE;
            }
            if (!empty($params['hot_report_relate']) && !$module_val) {
                $val = $val | self::MODULE_HOT_REPORT_RELATE;
            }
        }

        $data['iModuleButton'] = $val;
        $data['iUserId'] = $user_id;

        if (!$this->module['user_exists']) {
            return $this->executeSave(self::RELATE_RELATE_HOME_HOME_TABLE, $data);
        } else {
            $condition = ['iUserId' => $user_id];
            return $this->executeUpdate(self::RELATE_RELATE_HOME_HOME_TABLE, $data, $condition);
        }

    }

    //最新报告
    public function get_new_report($is_on = true)
    {
        $result = $conditions = [];
        $conditions['iColumnId'] = [1, 2];
        $limit = 18;
        if ($is_on && !empty($this->user_like['gen'])) {//开启个性化 && 性别偏好不为空
            $conditions['aLabelIds'] = $this->user_like['gen'];
        }
        // 排序
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];

        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort, array('fl' => '*,score'));
        if (!$result) {
            return [];
        }
        $collect = $this->getListDataCollectStatus($result);
        return $this->deal_report_data($result, $collect);
    }

    //热门报告
    public function get_hot_report($is_on = true)
    {
        $result = $conditions = [];
        $conditions['dCreateTime'] = $this->common_model->getTimeRange(2);
        $conditions['iColumnId'] = [1, 2];
        $param = 'sor_2';
        $limit = 8;
        //性别偏好
        if ($is_on && $this->user_like['gen']) {//开启个性化
            $conditions['aLabelIds'] = $this->user_like['gen'];
        }
        // 排序-浏览量
        $arSort = $this->common_model->getSort($param);

        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);
        if (empty($result)) {
            $lists = [];
        } else {
            $lists = $this->deal_report_data($result);
            $lists = twoDimensionSort($lists, 'view_count');
        }
        $num = count($result);
        if (!$is_on || $num == 8) {
            return $lists;
        }
        //如果不足8条只查浏览量
        $limit = !$num ? 8 : 8 - $num;
        unset($conditions['aLabelIds']);

        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);
        $list2 = [];
        if (!empty($result)) {
            $list2 = $this->deal_report_data($result);
        }
        $return = array_merge($lists, $list2);
        $return = !empty($return) ? twoDimensionSort($return, 'view_count') : [];

        return $return;
    }

    //处理报告数据
    public function deal_report_data($result, $collect = [])
    {
        if (empty($result)) {
            return [];
        }
        $this->load->model(['report_model', 'statistics_model']);
        foreach ($result as $rs) {
            $id = $rs['pri_id'];
            $table_name = $rs['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $table_name);
            if (!$data) {
                continue;
            }
            $info = $data[$id];
            $_info['id'] = $id;
            $_info['t'] = getProductTableName($table_name);
            $_info['title'] = htmlspecialchars(stripcslashes($info['title']));
            $_info['link'] = "/details/report/t_{$_info['t']}-id_{$id}-col_{$rs['iColumnId'][1]}/";
            $_info['score'] = $rs['score'];
            $sub_col = array_pop($rs['iColumnId']);
            $_info['col'] = [
                'id' => $sub_col,
                'name' => GetCategory::getOtherFromColId($sub_col, 'sName'),
                'link' => $this->common_model->columnRootPath($sub_col),
            ];
            // 图片路径
            $imgPath = getImagePath($table_name, $info);
            $_info['cover'] = getFixedImgPath($imgPath['cover']);

            // 发布时间
            switch ($table_name) {
                case 'specialtopic':
                case 'specialtopic_graphic':
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    $_info['create_time'] = $info['addtime'];
                    break;
                case 'mostrend_content':
                    $_info['create_time'] = $info['release_time'];
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    break;
                case 't_trend_report':
                    $_info['title'] = $info['sTitle'];
                    $_info['create_time'] = date('Y-m-d', strtotime($info['dPubTime']));
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['sDesc'])));//摘要
                    break;
                case 'fs_analysis':
                case 'fs_commodity':
                case 'fs_design':
                case 'fs_inspiration':
                case 'fs_trend':
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['abstract']))); //摘要
                    break;
            }

            $_info['create_time'] = date('Y-m-d', strtotime($_info['create_time']));
            // 获取浏览量，浏览量的数据实时存放在memecache中，memecache中的数据通过消息队列，异步永久化到数据库中。
            $_info['view_count'] = $this->statistics_model->getViews($table_name, $id, $info);
            //是否收藏
            if ($collect) {
                $_info['iCollectStatus'] = array_search($rs['pop_id'], $collect) === false ? 0 : 1; // 是否收藏
            }

            // 取列表页标签
            $_labels = $this->report_model->getLabelInfo($table_name, $id, $rs['iColumnId'][0], 'list');

            $_info['label'] = $_labels;
            $return[] = $_info;
        }

        return $return;
    }

    //T台
    public function g_t_show($limit = 6)
    {

        $conditions = ['iColumnId' => 3,];
        // 性别偏好
        if ($this->user_like['gen']) {
            $conditions['aLabelIds'] = $this->user_like['gen'];
        }
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $result = [];

        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];

            $info['nme'] = stripcslashes($info['nme']);
            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $info['cover'] = $imgPath['cover'];

            $info['columnId'] = $val['iColumnId'][1];
            $info['tableName'] = getProductTableName($tableName);
            $info['link'] = "/runways/inside/id_{$id}/";

            $lists[] = $info;
        }

        return $lists;

    }

    //广告大片
    public function g_advert($limit = 6)
    {
        $conditions = ['iColumnId' => 71,];
        $conditions['iDisplay'] = 1;
        // 性别偏好
        if ($this->user_like['gen']) {
            $conditions['aLabelIds'] = $this->user_like['gen'];
        }
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];

        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);

        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];

            $data = OpPopFashionMerger::getProductData($id, $tableName == 'product_vector_refrence_group' ? 'product_vector_refrence_list' : $tableName);
            $info = $data[$id];

            // 手稿合辑前台显示标题
            if (isset($info['name_text'])) {
                $info['title'] = $info['name_text'];
            }
            $info['title'] = stripcslashes($info['title']);

            //书名
            if (isset($info['nme'])) {
                $info['bookname'] = $info['nme'];
            } elseif (isset($info['name_code'])) {
                $info['bookname'] = $info['name_code'];
            } else {
                $info['bookname'] = $info['sBookName'];
            }
            $info['pop_id'] = $val['pop_id'];

            // 图片路径
            $imgPath = getImagePath($tableName, $info);

            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $info['cover'] = $this->getImgfPath($info['cover'], true);// 替换图片
            $yl = $info['iPreviewMode'] ? $info['iPreviewMode'] : 2;
            $t = getProductTableName($tableName);
            $info['link'] = "/books/seclist/id_{$id}-t_{$t}-yl_{$yl}-col_71/";
            $lists[$key]['list'] = $info;

            $lists[$key]['columnId'] = $val['iColumnId'][1];
            $lists[$key]['tableName'] = getProductTableName($tableName);
            $lists[$key]['total'] = $totalCount;

        }
        return $lists;
    }

    //替换域名为imgf1/f2/f3
    public function getImgfPath($image, $is_ture = false)
    {
        if (!$image) return '';
        if (substr($image, 0, 4) == 'http') {
            $new_https = explode('pop-fashion.com', $image);
            $imgList = getStaticUrl($new_https[1]) . $new_https[1];
        } else {
            $imgList = $image; // 不加域名
            if ($is_ture) { // 加域名
                $imgList = getStaticUrl($image) . $image;
            }

        }
        return $imgList;
    }

    /*--------------------------------二期-热门排行榜处理start----------------------------------------------*/

    //色系排行榜-group季节 取前20个季节
    public function get_color_season()
    {
        $this->load->model('Colortrends_model');
        $allYearSeason = $this->Colortrends_model->getAllYearSeason();
        if(is_array($allYearSeason)){
            $total = count($allYearSeason);
            foreach ($allYearSeason as $item) {
                $color_season[] = [
                    'id' => $item['season_id'],
                    'name' => $item['year'].$item['season'],
                    'iSort' => $total,
                ];
                $total--;
            }
        }
        $color_season = array_slice($color_season,0,20);
        return $color_season;
    }

    //取一级色系
    public function get_first_color($sea_id = '')
    {

        if (!$sea_id) {
            return [];
        }

        $field = 'iFirstColorFirstLevel';
        $cond = ['iColumnId' => 50,];
        $cond['aLabelIds'] = $sea_id;//季节
        $sAssortColor = $this->get_facet_data($field, $cond);
        foreach ($sAssortColor as &$val) {
            $val['link'] = "/styles/runways/sea_{$sea_id}-dis_1-aco_{$val['id']}/#anchor";
        }
        return !empty($sAssortColor) ? array_slice($sAssortColor, 0, 16) : [];
    }

    //取二级色系
    public function get_second_color($sea_id, $first_color)
    {

        if (!$sea_id || !$first_color) {
            return [];
        }

        $field = 'iFirstColorSecondLevel';
        $cond = ['iColumnId' => 50,];
        $cond['aLabelIds'] = $sea_id;//季节
        $cond['iFirstColorFirstLevel'] = $first_color;//季节

        $sAssortColor = $this->get_facet_data($field, $cond);
        foreach ($sAssortColor as &$val) {
            $val['link'] = "/styles/runways/sea_{$sea_id}-dis_1-aco_{$val['id']}/#anchor";
        }
        return !empty($sAssortColor) ? array_slice($sAssortColor, 0, 10) : [];
    }

    public function get_group_data($field, $cond)
    {
        $params['group'] = 'true';
        $params['group.field'] = $field;
        $params['group.ngroups'] = 'true';

        POPSearch::wrapQueryPopFashionMerger('', $cond, $group_data, 0, 150, ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'], $params);
        if (!is_array($field)) {
            $total = $group_data[$field]['ngroups'];
            $group_values = $group_data[$field]['groups'];
            foreach ($group_values as $value) {
                if (!$value['groupValue']) continue;
                $attr = GetCategory::getAttrById($value['groupValue'], ['sName', 'iSort']);
                $data[] = ['id' => $value['groupValue'], 'name' => $attr['sName'], 'iSort' => $attr['iSort']];
            }
        }
        $data = twoDimensionSort($data, 'id');
        $data = twoDimensionSort($data, 'iSort');
        $data = array_slice($data, 0, 20, true);

        return $data;
    }

    public function get_facet_data($field, $cond)
    {

        $this->load->model('styles_model');
        $param['facet'] = 'true';
        $param['facet.field'] = [$field];
        $param['raw'] = true;

        // $_GET['debug_solr'] = 1;
        $resAnalysis = POPSearch::wrapQueryPopFashionMerger('', $cond, $a, 0, 1, ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'], $param);

        $facet_value = array_filter($resAnalysis['facet_counts']['facet_fields'][$field]);
        $total = $resAnalysis['response']['numFound'];

        if (empty($facet_value)) {
            return [];
        }

        $colors = $this->styles_model->getColorDict();

        $dataAnalysis = [];
        foreach ($facet_value as $colorid => $count) {
            if (isset($colors[$colorid]) && !empty($count)) {
                $tmp = [];
                $tmp['id'] = $colorid;
                $tmp['name'] = $colors[$colorid]['sName'];
                $tmp['percentage'] = round(($count / $total) * 100, 1);
                $tmp['itemStyle']['normal']['color'] = $colors[$colorid]['sColor'];
                $dataAnalysis[] = $tmp;
            }
        }
        if (!empty($dataAnalysis)) {
            $dataAnalysis = array_values($dataAnalysis);
        }

        foreach ($dataAnalysis as $color) {
            $sAssortColor[] = [
                'id' => $color['id'],
                'sName' => $color['name'],
                'percentage' => $color['percentage'],//占比
                'sAlias' => $color['itemStyle']['normal']['color'],
            ];
        }

        return $sAssortColor;
    }

    //品牌热门排行榜-statistics.fz_style_data_statistics
    public function get_brand_top($sex = '')
    {
        // todo 查询data_hunter只有从库
        $global_db = OpGlobalDb::getInstance('data_hunter', 'write_dh');
        $this->load->driver('cache', ['adapter' => 'memcached']);

        $expir_time = 86400;
        if (!$sex) {
            //性别未选择时 偏好中性别的优先顺序女-男-童
            if (empty($this->user_like['gen'])) {
                $sex = ['男装', '女装', '童装', '女童', '男童'];
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_ALL';
            }
            if (in_array(5, $this->user_like['gen'])) {
                $sex = '童装';
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_5';
            }
            if (in_array(1, $this->user_like['gen'])) {
                $sex = '男装';
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_1';
            }
            if (in_array(2, $this->user_like['gen'])) {
                $sex = '女装';
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_2';
            }
            if (count(array_intersect([1, 2, 5], $this->user_like['gen'])) >= 3) {
                $sex = ['男装', '女装', '童装', '女童', '男童'];
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_ALL';
            }

        } else {
            if ($sex == 5) {
                $sex = ['童装', '女童', '男童'];
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_5';
            } elseif ($sex == 'all') {
                $sex = ['男装', '女装', '童装', '女童', '男童'];
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_ALL';
            } else {
                $sex = GetCategory::getAttrNameById($sex);
                $mem_key = 'FASHION_RELATE_HOME_BRAND_TOP_' . $sex;
            }
        }

        //近1个月品牌下载量
        $start = date('Y-m-d 00:00:00', strtotime('-1 month'));
        if (!is_array($sex)) {
            $sex = [$sex];
        }
        $gender_val = implode("','", $sex);

        $res = $this->cache->memcached->get($mem_key);
        //加缓存1天
        if (empty($res) || $this->input->get('refresh')) {

            // todo data_hunter 在从库
            $where = "  d_update_time>='{$start}' AND s_brand!='--' AND s_brand!=''  ";
            $sql = " select s_brand,sum(i_downloads) as down_num from data_hunter.fz_style_data_statistics 
 where s_gender in('{$gender_val}') and  s_column not in ('图案工艺', '图案素材', '大牌花型') and {$where} 
 group by s_brand order by down_num desc limit 10";

            $global_db->select($sql, $res);
            $this->cache->memcached->save($mem_key, $res, $expir_time);

        }
        if (empty($res)) {
            return [];
        }

        $brand_names = array_column($res, 's_brand');
        $num = array_column($res, 'down_num');
        $brands = array_combine($brand_names, $num);

        //查询品牌名
        $query = $this->db
            ->select('id,en_cn_brand,name')
            ->where_in('name', $brand_names)
            ->limit(10)
            ->get('pop136.brand_library');

        foreach ($query->result() as $row) {
            $brand_data[] = [
                'id' => $row->id,
                'name' => $row->en_cn_brand,
                'num' => $brands[$row->name],
                'link' => "/styles/bra_{$row->id}/#anchor",
            ];

        }
        $brand_data = twoDimensionSort($brand_data, 'num');

        return $brand_data;

    }

    //图案排行榜-获取图案内容筛选项
    public function get_pattern_filter()
    {
        $this->load->model('Category_model');
        $data = [];

        $sPatternContents = GetCategory::getSomeAttr(25, '', false);
        $_tmp = [];
        foreach ($sPatternContents as $val) {
            // 图案内容一级标签设置了前台不显示
            if (!$val['iDisplay']) {
                continue;
            }
            $_tmp[$val['iAttributeId']]['sName'] = $val['sName'];
            foreach ($val['attrs'] as $v) {
                // 图案内容二级标签设置了前台不显示
                if (!$v['iDisplay']) {
                    continue;
                }
                // 过去的一年时间内，该标签有上新数据
                if (strtotime($v['dLastUpdateTime']) > strtotime('-1 year')) {
                    $_tmp[$val['iAttributeId']]['attrs'][$v['iAttributeId']] = ['sName' => $v['sName']];
                }
            }
            // 一级标签，过去一年内没有上新数据，且其下二级标签也没有上新数据，则前台不显示改标签
            // if (strtotime($val['dLastUpdateTime']) < strtotime('-1 year') && empty($_tmp[$val['iAttributeId']]['attrs'])) {
            //     unset($_tmp[$val['iAttributeId']]);
            // }

        }

        foreach ($_tmp as $key => $val) {
            if (in_array($val['sName'], ['素色', '条纹'])) {
                unset($_tmp[$key]);
            } else {
                $data[] = [
                    'name' => $val['sName'],
                    'id' => $key,
                ];
            }
        }
        return $data;

    }

    //图案排行榜
    public function get_pattern_top($pattern_id = '')
    {

        if (!$pattern_id) {
            return [];
        }
        $table = '`fashion`.`t_relate_home_hot_pattern`';
        $start = date('Y-m-d 00:00:00', strtotime("-1 month"));
        $end = date('Y-m-d 00:00:00');

        $sql = "SELECT iPatternId,sName,sum(iTotal) as total FROM {$table} where  iPatternPid=? and dDownDate between ? and ?  GROUP BY iPatternId ORDER BY total desc limit 6";

        $res = $this->query($sql, [$pattern_id, $start, $end]);
        foreach ($res as &$val) {
            $val['link'] = "/patterns/graphics/con_{$val['iPatternId']}/#anchor";
        }
        return $res;
    }


    /*--------------------------------二期-热门排行榜处理end----------------------------------------------*/


}