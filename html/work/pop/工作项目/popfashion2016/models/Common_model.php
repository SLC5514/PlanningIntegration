<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends POP_Model
{
    const AD_TOP = 1;    // 头部广告位置
    const AD_LEFT = 2;  // 左侧边栏广告位置
    const AD_DETAIL_RIGHT = 3;  // 详情页右侧边栏广告位置

    public $associates = [];
    public $colAssociates = [];
    public $paramsArr;

    private $sufix = '/';
    private $defaultParams = [];

    public function __construct()
    {
        parent::__construct();
        // 对应关系
        $this->associates = [
            'gen' => '性别',
            'gcen' => '子性别',
            'ind' => '行业',
            'sea' => '季节',
            'cat' => '单品',
            'reg' => '地区',
            'bra' => '品牌',
            'exh' => '展会名称',
            'col' => '栏目',
            'vie' => '观点',
            'use' => '局部/满身',
            'for' => '矢量/位图',
            'tec' => '图案工艺',
            'con' => '图案内容',
            'par' => '款式部位',
            'boo' => '书名',
            'typ' => '类型',
            'ver' => '版本',
            'key' => '关键字',
            'sor' => '排序',
            'dis' => '展现形式',
            'tim' => '时间范围',
            'page' => '页码',
            'type' => '灵感分类',
            'sha' => '共享',
            'id' => '表Id',
            't' => '表名',
            'top' => '主题类型',
            'com' => '色彩組合',
            'aco' => '色系',
            'vis' => '视角',
            'bpos' => '品牌定位',
            'fas' => '时装周分类',
            'mar' => '市场类型',
            'dtyp' => '陈列类型',
            'rep' => '报道类型',
            'str' => '街拍类型',
            'tid' => '潮流类型',
            'regs' => '地域风格',
            'age' => '年龄层',
            'sta' => '明星标签',
            'wea' => '穿着风格',
            'cha' => '性格风格',
            'relt' => '趋势专题',
            'cont' => '书籍类型',
            'coll' => '子账号收藏',
            'hot' => '热门元素',
            'ihot' => '热门推荐',
            'auth' => '只看数据',
            'uli' => '只看面料',
            'vect' => '含矢量文件',
            'mes' => '活动消息类型',
            'stat' => '活动开始结束状态',
            'chi' => ',活动子类型',
            'off' => '是否官方',
            'are' => '活动区域',
            'cit' => '活动城市',
            'beg' => '活动开始时间',
            'end' => '活动结束时间',
            'che' => '活动是否审核',
            'the' => '活动主题',
            'ctn' => '联系人',
            'ctp' => '联系方式',
            'cpt' => '企业类型',
            'cra' => '面料工艺',
            'mat' => '面料材质',
            'man' => '风格',
            'shap' => '廓形',
            'spe' => '细节',
            'tech' => '工艺',
            'protech' => '工艺来源',
            'pat' => '图案',
            'fab' => '面料',
            'acc' => '辅料',
            'popky' => 'pop快印',
            'mct' => '商品类型',
            'mst' => '商品子类型',
            'prop' => '款式图/手稿图',
            'stp' => '图案专题',
            'tect' => '工艺类型',
            'no' => '期数',
            'ds' => '分类',//全球实拍的分类 -- iDataSource
            'btype' => '类型',
            'site' => '站点',
            'label' => '标签',
            'match' => '匹配', // 全站搜索 【最新/匹配】（针对全站搜索报告栏目）
            'starin' => '明星/ins', // 明星/ins
            'tlive' => '报告视频',//报告解读标识（对应 solr字段 sVersion）
        ];
        // 每个栏目所拥有属性 'gen','ind' 除外
        $this->colAssociates = [
            // 趋势解读  'ver' : ver_video [ 2020-03-26新增 ]
            '1' => ['key', 'sea', 'gcen', 'cat', 'relt', 'coll', 'ihot', 'uli', 'man', 'age'],
            '20' => ['key', 'sea', 'gcen', 'cat', 'no', 'coll', 'ihot', 'uli', 'man', 'age'],
            '21' => ['key', 'sea', 'gcen', 'cat', 'relt', 'coll', 'ihot', 'uli', 'man', 'age'],
            '125' => ['key', 'sea', 'gcen', 'cat', 'relt', 'coll', 'ihot', 'uli', 'man', 'age'],
            '126' => ['key', 'sea', 'gcen', 'cat', 'relt', 'coll', 'ihot', 'uli', 'man', 'age'],
            '127' => ['key', 'sea', 'gcen', 'cat', 'relt', 'coll', 'ihot', 'uli', 'man', 'age'],
            '128' => ['key', 'sea', 'gcen', 'cat', 'relt', 'tect', 'coll', 'ihot', 'uli', 'man', 'age'],
            '129' => ['key', 'sea', 'gcen', 'cat', 'relt', 'coll', 'ihot', 'uli', 'man', 'age'],
            // 流行分析
            '2' => ['key', 'sea', 'vis', 'gcen', 'cat', 'bra', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '30' => ['key', 'sea', 'vis', 'gcen', 'cat', 'bra', 'fas', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '31' => ['key', 'sea', 'gcen', 'exh', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '32' => ['key', 'sea', 'vis', 'gcen', 'cat', 'bra', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '33' => ['key', 'sea', 'reg', 'gcen', 'cat', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],// 爆款数据
            '132' => ['key', 'sea', 'reg', 'gcen', 'cat', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '34' => ['key', 'sea', 'bra', 'gcen', 'cat', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '35' => ['key', 'sea', 'bra', 'gcen', 'cat', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '37' => ['key', 'sea', 'reg', 'gcen', 'cat', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '38' => ['key', 'sea', 'gcen', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            '40' => ['key', 'sea', 'bra', 'gcen', 'cat', 'rep', 'coll', 'ihot', 'auth', 'uli', 'man', 'age'],
            // 款式
            '4' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'coll'],
            '50' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'aco', 'coll'],
            '52' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'gcen', 'age', 'coll'],
            '54' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'coll'],
            '54_1' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'mar', 'coll'],
            '54_2' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'coll'],
            '54_3' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'gcen', 'age', 'exh', 'coll'],
            '55' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'coll'],
            '56' => ['dis', 'key', 'sea', 'reg', 'cat', 'gcen', 'age', 'coll'],
            '57' => ['dis', 'key', 'sea', 'reg', 'cat', 'gcen', 'sta', 'coll', 'starin'],// 明星/ins(潮流领袖)数据实际无年龄层age
            '122' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'coll'],
            '123' => ['dis', 'key', 'sea', 'reg', 'bra', 'cat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'gcen', 'age', 'coll'],
            // 图案
            '82' => ['key', 'gcen', 'use', 'for', 'con', 'coll', 'aco'],
            '120' => ['key', 'gcen', 'use', 'bra', 'con', 'coll'],
            'iSpecialTopicPatterns' => ['key', 'gcen', 'sea', 'relt', 'coll', 'ihot', 'uli'],// 趋势专题--特殊
            '121' => ['key', 'use', 'con', 'coll'],
            '124' => ['key', 'tec', 'bra', 'coll', 'protech'],
            // T台
            '3' => ['key', 'sea', 'reg', 'typ', 'ver', 'bra', 'coll',],
            // 读物
            '70' => ['key', 'sea', 'boo', 'reg', 'cat', 'coll', 'btype'],
            '71' => ['key', 'sea', 'bra', 'reg', 'cat', 'typ', 'coll'],
            '131' => ['key', 'sea', 'bra', 'reg', 'cat', 'coll'],
            '72' => ['key', 'sea', 'boo', 'reg', 'cat', 'coll'],
            '115' => ['key', 'sea', 'coll'],
            // 素材
            '90' => ['key', 'type', 'coll'],
            '116' => ['key', 'coll'],
            '80' => ['key', 'cat', 'coll'],
            '81' => ['key', 'cat', 'par', 'coll'],
            '84' => ['key', 'sea', 'reg', 'cat', 'coll'],
            '85' => ['key', 'sea', 'reg', 'bra', 'coll'],
            '117' => ['key', 'exh', 'mat', 'cra', 'con', 'gcen', 'coll'],
            // 视频专栏
            '10' => ['key', 'ver', 'sea', 'bra', 'reg', 'gen', 'coll']
        ];
    }

    /**
     * [getColumns 获取指定的栏目及子栏目信息]
     * @param integer $columnPid [栏目id]
     * @param boolean $params [是否重组树型结构的数组，以适用于小栏目的显示]
     * @return mixed
     */
    public function getColumns($columnPid, $params, $power = [])
    {
        $paramsArr = $this->parseParams($params);

        // 报告栏目--年龄层，Tab切换不带age参数
        if (in_array($columnPid, array(1, 2))) {
            if (isset($paramsArr['age']) && !empty($paramsArr['age'])) {
                unset($paramsArr['age']);
            }
        }

        // 字典中 有效栏目集合
        $res = GetCategory::getColumn();

        // 前台页面不显示栏目 6-books 9-图案 83-优料宝 112-权威数据 121-数码云打印
        $specialArray = [6, 9, 83, 112,121];

        // 趋势手稿栏目，仅VIP,试用用户可以使用
        if ($columnPid === 6 && !in_array($power['P_UserType'], array(1, 2, 3))) {
            $specialArray[] = 70;
        }

        // 栏目介绍
        $aColumnAbstract = getCommonData('common_data', 'aColumnAbstract');

        // 主栏目部分，栏目ID为7的替换成8(仅仅主栏目部分)
        $col = ($columnPid == 7) ? 8 : $columnPid;
        $tmpVal = $columnPid == 7 ? $res[8] : $res[$columnPid];
        if (!in_array($columnPid, $specialArray)) {
            $params = $this->getColParams($columnPid, $paramsArr);
            $columns[$col] = [
                'sName' => $tmpVal['sName'],
                'sEnName' => $tmpVal['sEnName'],
                "sLink" => $this->getLink($tmpVal['iColumnId'], $params),
                'rootUrl'=>$this->columnRootPath($tmpVal['iColumnId']),
                'sPresentation' => $aColumnAbstract[$tmpVal['iColumnId']]
            ];
        }

        // 其子栏目部分
        if (is_array($res[$columnPid]['col'])) {
            foreach ($res[$columnPid]['col'] as $key => $value) {
                $col = $value['iColumnId'];
                $new_params = $paramsArr;
                $selectItem = array('sManner');
                if (in_array($columnPid, array(1, 2, 4))) {
                    if ($columnPid == 1) {
                        $model = 'trends_model';
                    } elseif ($columnPid == 2) {
                        $model = 'analysis_model';
                    } elseif ($columnPid == 4) {
                        $model = 'styles_model';
                    }
                    $this->load->model($model);
                    $smanner = $this->$model->getSelectItems($selectItem, $col);
                    if (!isset($smanner['sManner']) || count($smanner['sManner']) < 1) {
                        unset($new_params['man']);
                    }
                }
                $params = $this->getColParams($col, $new_params);
                if (!in_array($col, $specialArray)) {
                    $columns[$col] = [
                        'sName' => $value['sName'],
                        'sEnName' => $value['sEnName'],
                        'sLink' => $this->getLink($col, $params),
                        'rootUrl'=>$this->columnRootPath($col),
                        'sPresentation' => $aColumnAbstract[$col]
                    ];
                    if ($col == 120) {
                        $params = $this->getColParams('iSpecialTopicPatterns', $new_params);
                        $params = !empty($params) ? ($params . '/') : '';
                        $columns['iSpecialTopicPatterns'] = [
                            'sName' => '图案专题',
                            'sEnName' => 'Pattern Theme',
                            'sLink' => '/patterns/specialtopicpatterns/' . $params,
                            'rootUrl'=>'/patterns/specialtopicpatterns/',
                            'sPresentation' => '未来图案趋势预测的主题呈现，注重关键元素的变化延伸，帮助客户解决图案的系列性和扩展性。'
                        ];
                    }
                }
                unset($new_params);
            }
        }

        // 特殊(灵感源，灵感视频补进栏目7)
        if ($columnPid == 7) {
            // 灵感源
            $tmpCol = $columns[8];
            if (isset($columns[8])) {
                // 顺序重组
                unset($columns[8]);
                $columns[8] = $tmpCol;
            }
            // 灵感视频
            foreach ($res[8]['col'] as $value) {
                if ($value['iColumnId'] != 116) {
                    continue;
                }

                $columns[116] = [
                    'sName' => $value['sName'],
                    'sEnName' => $value['sEnName'],
                    "sLink" => $this->getLink(116, $params),
                    'rootUrl'=>$this->columnRootPath(116),
                    'sPresentation' => $aColumnAbstract[116]
                ];
            }
        }

        return $columns;
    }

    public function getColParams($col, &$paramsArr)
    {
        $col = !empty($paramsArr['ds']) && $col == 54 ? "{$col}_{$paramsArr['ds']}" : $col;
        $associates = is_array($this->colAssociates[$col]) ? $this->colAssociates[$col] : [];
        $_paramsArr = [];
        foreach ($paramsArr as $key => $val) {
            if (in_array($key, $associates)) {
                $_paramsArr[$key] = $val;
            }
            // 特殊处理：报告视频角标
            if ($key == 'tlive') {
                $_paramsArr[$key] = $val;
            }
        }
        return $this->parseParams($_paramsArr, 2);
    }

    /**
     * [getAds 获取指定栏目下面列表页的num条广告]
     * @param integer $columnId [栏目id]
     * @param integer $position [广告位置]
     * @param integer $num [广告条数 0=>不限制]
     * @param integer $columnPid [栏目父id 默认不取父栏目广告]
     * @param bool $notLimit
     * @return array [array]              [description]
     */
    public function getAds($columnId, $position = self::AD_TOP, $num = 5, $columnPid = 0, $notLimit = false)
    {
        $ads = [];
        if ($notLimit) {
            $num = '';
        }
        $refresh = $this->input->get_post('refresh', true) ? true : false;
        $adInfo = $this->getPoster($columnId, $position, $num, $refresh, $columnPid);
        if (is_array($adInfo) && !empty($adInfo)) {
            $count = count($adInfo);
            foreach ($adInfo as $key => $val) {
                if (strlen($val['sImagePath']) > 30) {
                    $ads[$key] = [
                        'sTitle' => $val['sTitle'],// 广告标题
                        'subTitle' => $val['subTitle'] ? $val['subTitle'] : "",// 广告副标题
                        'sMemo' => $val['sMemo'] ? $val['sMemo'] : "",// 广告描述
                        'sImagePath' => STATIC_URL3 . $val['sImagePath'],
                        'sLink' => $val['sUrl'],
                        'sNewWindow' => $val['sNewWindow'],// 是否新窗口打开 0=>本窗口打开,1=>新窗口打开
                        'count' => $count
                    ];
                }
            }
        }

        return $ads;
    }

    //字符串替换,$flip = FALSE,默认不反转
    public function strReplace($str, $flip = false)
    {
        $arr1 = ['pop380', 'pop381', 'pop382', 'pop383', 'pop384', 'pop385', 'pop386', 'pop387', 'pop388', 'pop389', 'pop390', 'pop391', 'pop392', 'pop35'];
        $arr2 = ['-', '_', '~', '!', '.', '*', '(', ')', '&', '<', '>', "'", '+', '#'];
        return $flip ? str_replace($arr2, $arr1, $str) : str_replace($arr1, $arr2, $str);
    }

    // 获取您已选择的条件的名称
    public function getDefaultParams($type = '', $params = '', $columnId = 0)
    {
        $lang = $this->input->cookie('lang');
        $lang = !empty($lang) ? $lang : '1';
        switch ($lang) {
            case '1'://中文
                $getName = 'sName';
                break;
            case '2'://英文
                $getName = 'sEnName';
                break;
            case '3'://韩文
                $getName = 'sKoreanName';
                break;
            default:
                $getName = 'sName';
        }

        if (!empty($params)) {
            $_params = $this->parseParams($params, 1);
            $this->load->model('category_model');
            if (isset($_params[$type])) {
                $typeArr = [];
                $value = $_params[$type];
                switch ($type) {
                    // 关键字
                    case 'key':
                        $this->defaultParams[$type] = $value;
                        break;
                    // 品牌
                    case 'bra':
                        $brandInfo = OpPopFashionMerger::getBrandData(intval($value));
                        $en_cn_name = isset($brandInfo['b_name']) && $brandInfo['b_name'] && $brandInfo['b_name'] != $brandInfo['name'] ? $brandInfo['name'] . '/' . $brandInfo['b_name'] : $brandInfo['name'];
                        $this->defaultParams[$type] = $en_cn_name ? $en_cn_name : '其他';
                        break;
                    // 地区
                    case 'reg':
                        if ($value == 'other') {
                            $this->defaultParams[$type] = $_params[$type];
                        } else {
                            // LOOKBOOK里面的地区特殊处理
                            // if ($columnId == 71) {
                            // require getenv('BASEPATH').'/category/popfashion/conf/category_conf.php';
                            // $this->defaultParams[$type] = $inlandAbroad[intval($value)];
                            // }
                            // 款式库，零售市场，地区特殊处理
                            if ($columnId == 54 && $value == 10001) {
                                $this->defaultParams[$type] = '国内全部';
                            } elseif ($columnId == 54 && $value == 10002) {
                                $this->defaultParams[$type] = '国外全部';
                            } else {
                                $this->defaultParams[$type] = GetCategory::getFieldFromId(intval($value));
                            }
                        }
                        break;
                    case 'gen':// 性别
                    case 'gcen':// 子性别
                    case 'ind':// 行业
                    case 'cat':// 单品
                    case 'sea':// 季节
                    case 'par': // 款式部位
                    case 'vis': // 视角
                    case 'age': //  年龄层
                    case 'bpos': //  品牌定位
                    case 'regs': //  地域风格
                    case 'wea': //  穿着
                    case 'cha': //  性格风格
                    case 'fas': //  时装周专题
                    case 'str': //  街拍类型
                    case 'dtyp': // 陈列类型
                    case 'rep': // 报道类型
                    case 'tid': // 潮流类型
                    case 'cont': // 书籍类型
                    case 'cra': // 面料工艺
                    case 'mat': // 面料材质
                    case 'man': // 风格
                    case 'shap': // 廓形
                    case 'spe': // 细节
                    case 'tech': // 工艺
                    case 'pat': // 图案
                    case 'fab': // 面料
                    case 'acc': // 辅料
                    case 'no':// 期数
                    case 'tect':// 工艺类型
                        $this->defaultParams[$type] = ltrim(GetCategory::getOtherFromIds(intval($value), ["{$getName}"]));
                        break;
                    case 'mar': // 市场类型
                        if ($columnId == 54) {
                            $typeArr = $this->category_model->getAll('sMarketType');
                            $this->defaultParams[$type] = $typeArr[$value];
                        } else {
                            $this->defaultParams[$type] = ltrim(GetCategory::getOtherFromIds(intval($value), ["{$getName}"]));
                        }
                        break;
                    // 书名
                    case 'boo':
                        if (in_array($columnId, array(71, 131))) {
                            $brandInfo = OpPopFashionMerger::getBrandData(intval($value));
                            $this->defaultParams[$type] = $brandInfo['name'];
                        } else {
                            $this->defaultParams[$type] = $this->strReplace($value);
                        }
                        break;
                    // 展会名称
                    case 'exh':
                        $typeArr = $this->category_model->getAll('sExhibitionName');
                        foreach ($typeArr as $val) {
                            if (is_array($val['attrs']) && in_array($value, array_keys($val['attrs']))) {
                                $this->defaultParams[$type] = $val['attrs'][$value]['sName'];
                                break 2;
                            }
                        }
                    // 趋势专题
                    case 'relt':
                        $typeArr = $this->category_model->getAll('iRelationTheme');

                        // 条件成立说明是一级趋势专题
                        if (isset($typeArr[$value])) {
                            $this->defaultParams[$type] = $typeArr[$value]['sName'];
                            break;
                        } else {
                            foreach ($typeArr as $val) {
                                if (is_array($val['attrs']) && in_array($value, array_keys($val['attrs']))) {
                                    $this->defaultParams[$type] = $val['attrs'][$value]['sName'];
                                    break 2;
                                }
                            }
                        }

                    // 观点
                    case 'vie':
                        $typeArr = $this->category_model->getAll('sViewpoint');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 灵感源类型
                    case 'type':
                        $typeArr = $this->category_model->getAll('sCommonTag');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 类型
                    case 'typ':
                        $typeArr = $this->category_model->getAll('sClass');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 版本
                    case 'ver':
                        $typeArr = $this->category_model->getAll('sVersion');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 图案工艺
                    case 'tec':
                        $typeArr = $this->category_model->getAll('sPatternTechnology');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 工艺来源
                    case 'protech':
                        $typeArr = $this->category_model->getAll('sCraftSource');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 图案内容
                    case 'con':
                        // $typeArr = $this->category_model->getAll('sPatternContent');
                        $this->defaultParams[$type] = GetCategory::getAttrNameById($value);
                        break;
                    // 图案应用
                    case 'use':
                        $typeArr = $this->category_model->getAll('sPatternUse');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 图案格式
                    case 'for':
                        $typeArr = $this->category_model->getAll('sPatternFormat');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 色彩组合
                    case 'com':
                        $typeArr = $this->category_model->getAll('sColorMerge');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 色系
                    case 'aco':
                        //$typeArr = $this->category_model->getAll('sAssortColor');
                        //$this->defaultParams[$type] = $typeArr[$value];
                        $this->load->model('styles_model');
                        $typeArr = $this->styles_model->getColorDict($value, 'sAssortColor');
                        $this->defaultParams[$type] = $typeArr;
                        break;
                    // 热门元素
                    case 'hot':
                        $typeArr = $this->styles_model->getLabels(true);
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 明星/达人
                    case 'sta':
                        $typeArr = $this->category_model->getAll('sStarLabel');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    case 'top':
                        //趋势-关注，主题类型的特殊处理
                        if ($value == 1) {
                            $this->defaultParams[$type] = '图案主题';
                        } elseif ($value == 2) {
                            $this->defaultParams[$type] = '款式主题';
                        }
                        break;
                    case 'btype':
                        $typeArr = $this->category_model->getAll('iTrendOrManual');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                    // 明星/ins 筛选
                    case 'starin':
                        $typeArr = $this->category_model->getAll('iStarIns');
                        $this->defaultParams[$type] = $typeArr[$value];
                        break;
                }
            }
        }

        //$this->defaultParams，私有数组变量
        return !empty($this->defaultParams[$type]) ? $this->defaultParams[$type] : '';
    }

    /**
     * [getBrands 获取品牌首字母]
     * @return array  $brands
     */
    public function getBrands()
    {
        $brands = array_merge(range('A', 'Z'), ['0-9'], ['OTHER']);
//		array_unshift($brands, '0-9');
        return $brands;
    }

    /**
     * [getTips 获取您已选择内容]
     * @param integer $columnId [栏目id]
     * @param string $params [传的参数，以&分割]
     * @return [type]           [description]
     */
    public function getTips($columnId, $params = '', $anchor = 'anchor')
    {
        if (!empty($params)) {
            $paramsInfo = $this->parseParams($params);
        }

        $tips = [];
        $_columnId = $columnId;
        if ($columnId == 54 && $paramsInfo['ds']) {
            $_columnId = $columnId . '_' . $paramsInfo['ds'];
        }
        $defaultParams = isset($this->colAssociates[$_columnId]) ? $this->colAssociates[$_columnId] : [];

        // 没有性别(82=>图案素材，84=>服饰品,90=>灵感报告,91=>灵感图库,117=>展会面料)
        if (!in_array($columnId, [84, 90, 91, 116, 121, 124, 117])) {
            // 性别 优先选择的性别
            $gender = $this->getGenderByRequest($params);
            // 全部 所有性别
            $subList = [0 => ['value' => '全部', 'key' => 0, 'type' => 142]];
            if ($gender == 0) {
                $subList = [];
                $_value = '全部';
            } else {
                $_value = GetCategory::getOtherFromIds($gender, ['sName']);
            }
            $genderAll = GetCategory::getGender();
            if (is_array($genderAll)) {
                foreach ($genderAll as $key => $value) {
                    if ($key == 3 || $key == 4 || $key == $gender || ($key == 5 && $columnId == 115)) continue;
                    $subList[] = ['value' => GetCategory::getOtherFromIds($key, ['sName']), 'key' => $key, 'type' => 142];
                }
            }

            $tips['gen'] = ['name' => '性别', 'value' => $_value, 'link' => '#', 'em' => '<em class="downlist_icon"></em>', 'sub' => $subList];

        }

        // 没有行业(3=>T台发布,82=>图案素材,84=>服饰品,85=>店铺陈列,90=>灵感报告,91=>灵感图库,120=>大牌花型,121=>数码云打印)
        if (!in_array($columnId, [3, 82, 84, 85, 90, 91, 116, 117, 120, 121, 124])) {
            // 行业
            $industry = $this->getIndustryByRequest($params);
            // 所有行业
            $industryAll = GetCategory::getTrade();
            // 全部
            $subList = [0 => ['value' => '全部', 'key' => 0, 'type' => 158]];

            if ($industry == 0) {
                $subList = [];
                $_value = '全部';
            } else {
                $kSort = [0];
                $_value = GetCategory::getOtherFromIds($industry, ['sName']);
                unset($industryAll[$industry]);
            }

            if (is_array($industryAll)) {
                foreach ($industryAll as $key => $value) {
                    if ($key == 11215 || $key == 12 || $key == 11243 || $key == 159) continue;    // 去掉综合，帽子围巾，11243=>图案,159=>梭织
                    $subList[] = ['value' => GetCategory::getOtherFromIds($key, ['sName']), 'key' => $key, 'type' => 158];
                    $kSort[] = $key;
                }
                // array_multisort($kSort, $subList);
            }
            $tips['ind'] = ['name' => '行业', 'value' => $_value, 'link' => '##', 'em' => '<em class="downlist_icon"></em>', 'sub' => $subList];
        }

        if (!empty($columnId) && !empty($params)) {
            $sufix = $this->getSufix();
            $__link = $this->columnRootPath($columnId);

            foreach ($defaultParams as $type) {

                if (in_array($type, ['coll', 'ihot', 'auth', 'uli', 'dis'])) {
                    continue;
                }
                // 关键字特殊处理
                if ($type == 'key') {
                    $search = $this->getSearchSufix('', true);
                    if (!empty($search)) {
                        $link = $this->replaceParams($params);
                        if (!empty($link)) {
                            $_link = $__link . trim($link, '/') . $sufix . ($anchor ? '#' . $anchor : '');
                        }
                        $tips[$type] = ['name' => $this->associates[$type], 'value' => $search, 'link' => $_link, 'em' => ''];
                    }
                } // 由于性别的特殊处理，导致重复的性别提示，需干掉
                elseif (in_array($type, ['gen', 'ind', 'cont'])) {
                    continue;
                } else {
                    if (!empty($paramsInfo[$type])) {
                        $link = $this->replaceParams($params, $type, null);
                        $_link = $__link . (!empty($link) ? @trim($link, '/') . $sufix : '') . $this->getSearchSufix() . ($anchor ? '#' . $anchor : '');

                        // 色系的特殊处理,tip里面的色块显示
                        if ($type == 'aco') {
                            $_val = $this->getDefaultParams('aco', $params, $columnId);

                            //色系返回按钮
                            $_backLink = "";
                            if (isset($_val['Pid'])) {
                                $backLink = $this->replaceParams($params, $type, $_val['Pid']);
                                $_backLink = $__link . (!empty($backLink) ? @trim($backLink, '/') . $sufix : '') . $this->getSearchSufix() . ($anchor ? '#' . $anchor : '');
                            }
                            $_backLink = empty($_backLink) ? $_link : $_backLink;

                            $_value = '<div title="' . $_val['sName'] . '" style="width:35px; height:17px; background-color:' . $_val['sAlias'] . ';margin-left:5px;"></div>';
                            $tips[$type] = ['name' => '色系', 'title' => $_val['sName'], 'value' => $_value, 'link' => $_link, 'em' => '', 'backLink' => $_backLink];
                        } // T台发布，地区=>other时，去除tip
                        elseif ($columnId == 3 && $type == 'reg' && $paramsInfo[$type] == 'other') {
                            continue;
                        } else {
                            $_backLink = "";
                            if ($type == 'cat' || $type == 'con') {
                                if ($type == 'cat') {
                                    $Pid = GetCategory::getCatIdsBySubcatIds($paramsInfo['cat']);
                                } else {
                                    $Pid = GetCategory::getOtherFromIds($paramsInfo['con'], ['iAttributePid']);
                                    $Pid = intval($Pid) == 11698 ? null : intval($Pid);
                                }
                                $backLink = $this->replaceParams($params, $type, $Pid);
                                $_backLink = $__link . (!empty($backLink) ? @trim($backLink, '/') . $sufix : '') . $this->getSearchSufix() . ($anchor ? '#' . $anchor : '');
                            }
                            $_backLink = empty($_backLink) ? $_link : $_backLink;
                            $tips[$type] = ['name' => $this->associates[$type], 'value' => $this->getDefaultParams($type, $params, $columnId), 'link' => $_link, 'em' => '', 'backLink' => $_backLink];
                        }
                    }
                }
            }
        } elseif (!empty($columnId)) {
            // 关键字特殊处理
            $search = $this->getSearchSufix('', true);
            if (!empty($search)) {
                $value = $this->associates['key'];
                $link = $this->columnRootPath($columnId) . ($anchor ? '#' . $anchor : '');
                $tips['key'] = ['name' => $value, 'value' => $search, 'link' => $link, 'em' => ''];
            }
        }

        return $tips;
    }

    // 设置link
    public function getLink($columnId, $params = '', $type = '', $value = '', $filter = true, $anchor = '')
    {
        $link = $this->columnRootPath($columnId);
        $_params = !empty($params) ? $this->replaceParams($params) : '';
        // 直接追加编码的参数
        if (empty($type) || empty($value)) {

            //设计素材【图案素材 款式模板 款式细节 店铺陈列】不带key
            if (in_array($columnId, [80, 81, 82, 85, 117, 120, 121])) {
                return $link . trim($_params, '/') . $this->getSufix($_params) . ($anchor ? '#' . $anchor : '');
            }

            return $link . trim($_params, '/') . $this->getSufix($_params) . $this->getSearchSufix() . ($anchor ? '#' . $anchor : '');
        }

        if ($type == 'key') {
            $search = $this->getSearchSufix($value);
            $type = $value = '';
        } else {
            $search = $this->getSearchSufix();
        }

        $params = $this->replaceParams($params, $type, $value, $filter);

        return $link . trim($params, '/') . $this->getSufix($params) . $search . ($anchor ? '#' . $anchor : '');
    }


    // 获取搜索的后缀 ?key=%253Cimg%253E  或者返回 htmlspecialchars(关键字)   先编码再转换
    public function getSearchSufix($_keyword = '', $self = false)
    {
        if (empty($_keyword)) {
            $keyword = $this->input->get_post('key', true);
        } else {
            $keyword = $_keyword;    // 传值均为已编码关键字	即通过$this->input->get_post('', TRUE)方法获取
        }
        if ($self) {
            // 从搜索页面过来的可能带有 ||| 多个词里分开显示
            //$keyword = str_replace('|||', ' ', htmlspecialchars(htmlspecialchars_decode($keyword)));    注释，不知道为什么做此操作

            $keyword = str_replace('|||', ' ', $keyword);

            return $keyword;
        }
        if ($keyword) {
            if (stripos($keyword, '?key=') !== false) {
                $keywords = '?key=' . htmlspecialchars(rawurlencode(rawurlencode(str_replace('?key=', '', $keyword))));
            } else {
                $keywords = '?key=' . htmlspecialchars(rawurlencode(rawurlencode($keyword)));
            }
        } else {
            $keywords = '';
        }
        return $keywords;
    }

    // 获取清除全部
    public function getClearLink($columnId, $params, $all = '')
    {
        $link = $this->columnRootPath($columnId);
        if (empty($params)) {
            return $link;
        }
        $paramsInfo = $this->parseParams($params);// 所有url参数的数组

        $_columnId = $columnId;
        if ($columnId == 54 && $paramsInfo['ds']) {
            $_columnId = $columnId . '_' . $paramsInfo['ds'];
        }
        $associates = isset($this->colAssociates[$_columnId]) ? $this->colAssociates[$_columnId] : [];

        $search = $this->getSearchSufix();
        // 清除全部
        if (empty($all)) {
            $search = '';
            // 若有性别行业，则留下
            unset($paramsInfo['gen'], $paramsInfo['ind']);
            // 未来趋势有视角，则留下
            $columnPid = GetCategory::getOtherFromColId($columnId);
            if ($columnPid == 0) $columnPid = $columnId;
            if ($columnPid == 1 && isset($paramsInfo['vis'])) {
                unset($paramsInfo['vis']);
            }

            // 款式栏目，保留单张或成册参数
            if ($columnPid == 4 && isset($paramsInfo['dis'])) {
                unset($paramsInfo['dis']);
            }
        }
        // 清除部分
        if (is_array($paramsInfo) && count($paramsInfo)) {
            foreach ($paramsInfo as $type => $val) {
                if ($all) {
                    if ($type == $all) {
                        // 指定条件清除
                        $params = $this->replaceParams($params, $type);
                        break;
                    }
                } else {
                    // 清除全部（循环清除） 报告栏目的tlive也要删除
                    if (in_array($type, $associates) || $type == 'page' || $type == 'tlive')
                        $params = $this->replaceParams($params, $type, null);
                }
            }
        }
        if (!empty($params)) {
            $link .= trim($params, '/') . $this->getSufix() . $search;
        }

        return $link;
    }

    // 子栏目对应的根目录
    public function columnRootPath($columnId)
    {
        $columnIds = [
            // 款式详情页
            0 => '/styles/detail/',
            // 款式库
            4 => '/styles/', 50 => '/styles/runways/', 52 => '/styles/shows/',
            54 => '/styles/retail/', 55 => '/styles/online/', 56 => '/styles/streetsnaps/',
            57 => '/styles/trendsetters/', 122 => '/styles/designerbrand/', 123 => '/styles/popular/',
            // 趋势
            1 => '/trends/', 20 => '/trends/capsules/', 21 => '/trends/design/', 22 => '/trends/forecast/', 23 => '/trends/vision/',
            // 潮流解析
            2 => '/analysis/', 30 => '/analysis/runways/', 31 => '/analysis/fairs/', 32 => '/analysis/shows/',
            33 => '/analysis/market/', 132 => '/analysis/tops/', 34 => '/analysis/online/', 35 => '/analysis/retail/', 36 => '/analysis/visual/',
            37 => '/analysis/street/', 38 => '/analysis/trendsetters/', 40 => '/analysis/reports/', 112 => '/analysis/authorized/',
            // 设计素材
            80 => '/references/design/', 81 => '/references/details/', 84 => '/references/accessories/', 85 => '/references/visual/',
            117 => '/references/fabricgallery/',
            // 手稿合辑
            6 => '/books/', 70 => '/books/store/', 71 => '/books/lookbook/', 72 => '/books/collections/', 73 => '/books/vector/',
            113 => '/books/runway/', 114 => '/books/brands/', 115 => '/books/fast/', 131 => '/books/ordermeeting/',
            // T台发布
            3 => '/runways/',
            // 灵感源
            8 => '/inspiration/', 90 => '/inspiration/report/', 91 => '/inspiration/library/', 116 => '/inspiration/video/',
            //服装杂志
            101 => '/books/magazine/',
            //图案库
            9 => '/patterns/', 82 => '/patterns/graphics/', 120 => '/patterns/topbrands/', 121 => '/patterns/digitalprint/',
            //视频专栏
            10 => '/video/',
            // 图案专题
            'iSpecialTopicPatterns' => '/patterns/specialtopicpatterns/',
        ];

        if (!$columnIds[$columnId]) {
            $columnIds[$columnId] = GetCategory::getOtherFromColId($columnId, 'sLink');
        }

        return $columnId == 5 ? '' : $columnIds[$columnId];
    }

    // 替换或生成参数中对应的值
    public function replaceParams($params, $type = '', $value = '', $filter = true)
    {
        // 新增
        if (!$params) {
            $params = $type . $this->getSep('_') . $value;
            $_params = $this->parseParams($params, 1, $filter);
        } else {
            $_params = $this->parseParams($params, 1, $filter);
            // 追加或替换参数时将页码参数去掉
            if (isset($_params['page'])) {
                $_params['page'] = 1;
            }
            if (isset($_params[$type])) {
                // 删除
                if (empty($value)) {
                    unset($_params[$type]);
                } // 替换
                else {
                    $_params[$type] = $value;
                }
            } // 追加
            elseif (!empty($value)) {
                if (array_key_exists($type, $this->associates)) {
                    $_params[$type] = $value;
                }
            }
        }
        return !empty($_params) ? $this->parseParams($_params, 2, false) : '';
    }

    // 参数还原
    public function restoreParams($params = '')
    {
        if (empty($params)) {
            return $params;
        }
        return rawurldecode($params);
    }

    // 解析params(cat_2-sea_3-gen_1-in_2)	1=>decode,2=>encode
    public function parseParams($params, $action = 1, $filter = true)
    {
        if (empty($params)) {
            return $action == 1 ? [] : '';
        }

        $params = $this->security->xss_clean($params);
        switch ($action) {
            // 字符串转为对应数组	/a=b&c=d&e=f.html => /a_b-c_d-e_f/ => ['a'=>'b','c'=>'d','e'=>'f']
            case 1:
            default:
                $params = trim($params, '-');
                $params = $this->replaceSep($params);
                parse_str($params, $_params);
                if (isset($params['boo'])) {
                    $_params['boo'] = $this->strReplace($_params['boo']);
                }
                if ($filter) {
                    $params = $this->filterParams($_params);
                } else {
                    $params = $_params;
                }
                break;
            // 数组转为字符串		['a'=>'b','c'=>'d','e'=>'f'] => /a=b&c=d&e=f.html=> ['a'=>'b','c'=>'d','e'=>'f'] => /a_b-c_d-e_f/
            case 2:
                if ($filter) {
                    $params = $this->filterParams($params);
                }
                if (isset($params['key'])) {
                    $params['key'] = rawurlencode($params['key']);
                }
                if (isset($params['boo'])) {
                    $_params['boo'] = $this->strReplace($params['boo'], true);
                    $params['boo'] = rawurlencode($params['boo']);
                }
                $params = http_build_query($params);
                $params = $this->replaceSep($params, true);
                break;
            // 只进行过滤		['a'=>'b','c'=>'d','e'=>'f'] => ['a'=>'b','c'=>'d','e'=>'f']		/a_b-c_d-e_f/ => /a_b-c_d-e_f/
            case 3:
                if (is_array($params)) {
                    $params = $this->filterParams($params);
                } else {
                    $params = trim($params, '-');
                    $params = $this->replaceSep($params);
                    parse_str($params, $params);
                    $params = $this->filterParams($params);
                    $params = http_build_query($params);
                    $params = $this->replaceSep($params, true);
                }
                break;
        }
        return $params;
    }

    // 获取性别
    public function getGenderByRequest($params = [])
    {
        if (empty($params)) {
            return intval($this->input->cookie("gender"));
        }
        if (is_array($params)) {
            return isset($params['gen']) ? intval($params['gen']) : intval($this->input->cookie("gender"));
        } else {
            $params = $this->parseParams($params);
            return isset($params['gen']) ? intval($params['gen']) : intval($this->input->cookie("gender"));
        }
    }

    // 获取性别
    public function getIndustryByRequest($params = [])
    {
        if (empty($params)) {
            return intval($this->input->cookie("industry"));
        }

        if (is_array($params)) {
            return isset($params['ind']) ? intval($params['ind']) : intval($this->input->cookie("industry"));
        } else {
            $params = $this->parseParams($params);
            return isset($params['ind']) ? intval($params['ind']) : intval($this->input->cookie("industry"));
        }
    }

    /**
     * [getPowers 获取用户权限]
     *
     * @param string $columnPid [栏目父id]
     * @param string $params 查询参数
     * @param string $columnId 子栏目id
     *
     * @return array|bool [array]   $powers
     */
    public function getPowers($columnPid = '', $params = '', $columnId = '')
    {

        if (empty($columnPid) && empty($columnId)) {
            return false;
        }
        if (empty($columnPid) && !empty($columnId)) {
            $columnPid = GetCategory::getOtherFromColId($columnId);
        }
        $Columns = $this->getColumns($columnPid, $params);
        $colIds = array_keys($Columns);
        $powers = memberPower('list', ['P_Column' => $colIds]);

        //遮罩
        if (!empty($params) && !empty($columnId)) {
            $page = $this->getPage($params);
            if (isset($powers['P_Shade']) && $powers['P_Shade']) {
                //报告类栏目没有遮罩
                if ($page >= 2 && $columnPid != 1 && $columnPid != 2 && $columnPid != 6 && $columnId != 90 && $columnId != 116) {
                    $powers['shade'] = 'shuiy';
                }
            }
        }

        //乱序 P_Sort => true(乱序) | false(不乱序)
        if (isset($powers['P_Sort']) && $powers['P_Sort']) {
            $random = 'random_' . microtime(true);
            $powers['sort'] = $random;
        }

        //用户身份1 主账号vip , 2子账号vip 、3试用 4普通 5游客
        switch ($powers['P_UserType']) {
            case '1':
            case '2':
                $powers['userType'] = 'vip';
                break;
            case '3':
            case '4':
            case '5':
                $powers['userType'] = 'normal';
                break;
            default:
                $powers['userType'] = 'normal';
                break;
        }
        return $powers;
    }

    /**
     * [getKeyword 获取用户搜索的关键字,用于拼接solr条件]
     *
     * @param string $params [url参数]
     * @param array $powers
     *
     * @return mixed|string [string]  $keyword
     */
    public function getKeyword($params = '', $powers = [])
    {
        if (isset($params['key']) && !empty($params['key'])) {
            $keyword = $params['key'];
        } else {
            $keyword = '';
        }
        $keyword = $this->getSearchSufix($keyword, true);

        //检索 P_Search => true(可以检索) | false(不可以检索)
        if (!empty($powers) && !$powers['P_Search']) {
            $keyword = '';
        }
        return $keyword;
    }

    //获取solr查询的排序条件
    public function getSort($params = '', $powers = [], $columnPid = '')
    {
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        if (!empty($params)) {
            $paramsArr = $this->parseParams($params, 1);
            if (isset($paramsArr['sor'])) {
                //1=>更新时间,2=>浏览量,3=>收藏量
                switch ($paramsArr['sor']) {
                    case '2':
                        $arSort = ['iViewCount' => 'DESC', 'pri_id' => 'DESC'];
                        break;
                    case '3':
                        $arSort = ['iCollectCount' => 'DESC', 'pri_id' => 'DESC'];
                        break;
                }
            }
            /*if( $paramsArr['dis'] == 3 ){
                $arSortTmp['sCategory'] = 'ASC';
                $arSort = array_merge( $arSortTmp,$arSort );
            }*/
        }
        //未来趋势,潮流解析栏目不乱序
        if (!empty($powers) && $powers['sort'] && $columnPid != 1 && $columnPid != 2) {
            $random = $powers['sort'];
            $arSort = [$random => 'DESC'];
        }

        return $arSort;
    }

    /*
     * getTimeText 获取参数中的时间范围，默认为时间范围
     * $params string||array 参数字符串或数组
     * $time_text  string
     */
    public function getTimeText($params)
    {
        $time_text = '全部时段';
        if (!empty($params)) {
            if (is_array($params)) {
                $paramsArr = $params;
            } else {
                $paramsArr = $this->parseParams($params);
            }
            if (isset($paramsArr['tim'])) {
                switch ($paramsArr['tim']) {
                    case '1':
                        $time_text = '近7日';
                        break;
                    case '2':
                        $time_text = '近30日';
                        break;
                    case '3':
                        $time_text = '近半年';
                        break;
                    default:
                        $time_text = '全部时段';
                        break;
                }
            }
        }
        return $time_text;
    }


    /*
     * getPage 获取当前页码，默认为第一页
     * $params string||array 参数字符串或数组
     * $page  integer
     */
    public function getPage($params)
    {
        if (is_array($params)) {
            $paramsArr = $params;
        } else {
            $paramsArr = $this->parseParams($params);
        }
        $page = isset($paramsArr['page']) && !empty($paramsArr['page']) ? intval($paramsArr['page']) : 1;

        return $page;
    }


    /**
     * [getTimeRange 获取时间范围]
     * @param string $timeRange 空=>不限，1=>近7日,2=>近30日,3=>近半年
     * @return string [string]  $yearTime
     */
    public function getTimeRange($timeRange = '')
    {
        if (!$timeRange) {
            return '';
        }

        $endTime = date("Y-m-d\TH:i:s\Z", strtotime("-2 hour"));
        switch ($timeRange) {
            case '1':
                $yearTime = date('Y-m-d\T00:00:00\Z', strtotime('-7 days'));
                return '[' . $yearTime . ' TO ' . $endTime . ']';
                break;
            case '2':
                $yearTime = date('Y-m-d\T00:00:00\Z', strtotime('-30 day'));
                return '[' . $yearTime . ' TO ' . $endTime . ']';
                break;
            case '3':
                $yearTime = date('Y-m-d\T00:00:00\Z', strtotime('-6 months'));
                return '[' . $yearTime . ' TO ' . $endTime . ']';
                break;
            default:
                return '';
                break;
        }
    }


    /**
     * 根据关键字获取女装貌似数据
     * $combine string 搜索关键字  --> 分析 发布会 趋势 等...
     * $sGender intval 查询条件中的性别  -->女装 男装 童装 ...
     * $sIndustry intval 查询条件中的的品名    --> 毛衫 ...
     * $dCreateTime string solr倒序查询字段   -->dCreateTime ...
     * $offset intval  偏移量
     * $limit intval 查询条数
     * $bool boolean TRUE 为数据库里获取数据 FALSE为memche缓存获取数据
     */
    public function getCombineData($combine, $sGender, $sIndustry, $dCreateTime, $offset, $limit, $bool = true)
    {
        $arSort['dCreateTime'] = $dCreateTime;
        $conditions['sGender'] = $sGender;
        $conditions['sIndustry'] = $sIndustry;
        //$conditions['combine'] = $combine;
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        $countIds = [];
        $tableName = [];
        foreach ($result as $value) {
            array_push($countIds, $value['pri_id']);
            $tableName['tablename'] = $value['tablename'];
        }
        $result_data = OpPopFashionMerger::getProductData($countIds, $tableName['tablename'], $bool);

        return $result_data;
    }

    // 获取性别行业参数
    public function getGenIndInfo($columnId = 0)
    {
        // 性别行业参数获取
        $params = $this->uri->rsegment(3);
        $genInd = '';
        if ($params) {
            $paramsInfo = $this->parseParams($params);
            $gen = intval($paramsInfo['gen']);
            $ind = intval($paramsInfo['ind']);
            $columnId = intval($columnId);
            switch ($columnId) {
                case 0:    // all
                case 1:    // 未来趋势
                case 2:    // 潮流解析
                case 4:    // 款式站
                case 6:    // 手稿合辑
                case 7:    // 设计素材
                    // 性别行业参数设置
                    if ($gen && $ind) {
                        $genInd = 'gen_' . $gen . '-ind_' . $ind . '/';
                    } elseif ($gen) {
                        $genInd = 'gen_' . $gen . '/';
                    } elseif ($ind) {
                        $genInd = 'ind_' . $ind . '/';
                    }
                    break;
                case 3:    // T台发布
                case 5:    // 品牌库
                    // 性别行业参数设置
                    if ($gen) {
                        $genInd = 'gen_' . $gen . '/';
                    }
                    break;
            }
        }
        return $genInd;
    }


    // 款式独有性别方法
    public function genderSpecialSelect($columnId, $params)
    {
        return [
            ['name' => '男童', 'link' => $this->getLink($columnId, $params, 'gcen', 3, true, 'anchor')],
            ['name' => '女童', 'link' => $this->getLink($columnId, $params, 'gcen', 4, true, 'anchor')],
            //['name' => '童装', 'link' => $this->getLink($columnId, $params, 'gen', 5, TRUE, 'anchor')]
        ];
    }

    // 性别童装的特殊处理
    public function childGender($gender, &$conditions)
    {
        // 童装特殊处理 OR
        if ($gender == 5) {
            $conditions['aLabelIds'] = [3, 4, 5];
        } elseif ($gender) {
            $conditions['aLabelIds'][] = $gender;
        }
    }

    // 子性别的特殊处理
    public function childCGender($parmas, &$conditions)
    {
        if (empty($parmas) || !isset($parmas['gcen'])) {
            return false;
        }
        $conditions['other'][] = 'aLabelIds:' . intval($parmas['gcen']);
    }

    /**
     * [getKeyValMap   获取查询简称跟Solor字段对应关系]
     * @param integer $key [栏目id]
     * @param boolean $flip [为真时，表明通过键取值，否则通过值取键]
     * @return string   [中文字符]
     */
    public function getKeyValMap($key = '', $flip = true)
    {
        $ret = [
            'sea' => 'iSeason', 'vie' => 'sViewpoint', 'cat' => 'sCategory', 'exh' => 'sExhibitionName',
            'typ' => 'sClass', 'ver' => 'sVersion', 'par' => 'sStylePart', 'use' => 'sPatternUse',
            'for' => 'sPatternFormat', 'tec' => 'sPatternTechnology', 'con' => 'sPatternContent',
            'com' => 'sColorMerge', 'aco' => 'sAssortColor', 'vis' => 'sVisualAngle', 'age' => 'sAgeLayer',
            'bpos' => 'sMarketHotPosition', 'regs' => 'sRegionalStyle', 'wea' => 'sWearing', 'cha' => 'sCharacterStyle',
            'fas' => 'sFashionWeek', 'mar' => 'sMarketType', 'str' => 'sStreetBeatType', 'dtyp' => 'sDisplayType',
            'rep' => 'sReportType', 'tid' => 'sTidalType', 'relt' => 'iRelationTheme', 'cont' => 'sContentDirection',
            'coll' => 'aCollectAccount', 'ihot' => 'iHot', 'auth' => 'iIsAuthorized', 'uli' => 'iUliaobao',
            'mes' => 'iActivityMessageType', 'chi' => 'iActivityChildType', 'off' => 'iOfficial',
            'are' => 'iActivityArea', 'cit' => 'iActivityCity', 'the' => 'sTheme', 'cra' => 'sFabricCraft',
            'mat' => 'sMaterial', 'man' => 'sManner', 'shap' => 'sShape', 'spe' => 'sSpecifics',
            'tech' => 'sTechnologys', 'pat' => 'sPattern', 'fab' => 'sFabric', 'acc' => 'sAccessory',
            'tect' => 'iTechnologyType', 'no' => 'iNo', 'protech' => 'sCraftSource',
        ];
        if (!$flip) {
            $ret = array_flip($ret);
        }
        if (!empty($key)) {
            $ret = $ret[$key];
        }
        return $ret;
    }

    public function getHotKeyWords($iColumnId, $params, $refresh = false)
    {
        $isStp = false;// 是否图案专题
        if ($iColumnId == 'iSpecialTopicPatterns') {
            $isStp = true;
            $iColumnId = 82; // 图案专题取图案素材的热门关键词
        }
        $manualRefresh = $this->input->get('refresh');
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_hot_keywords_new_' . $iColumnId;
        $this->load->driver('cache');
        $hotRecommWords = $this->cache->memcached->get($memKey);
        $result = array();
        if (empty($hotRecommWords) || $manualRefresh || $refresh) {
            $condition = "`iStatus`=0 AND `iColumnId`='$iColumnId'";
            $hotkeywords = $this->db()->select('iColumnId,sKeywords')
                ->where($condition)
                ->get(self::T_FASHION_T_FM_HOT_KEYWORDS)
                ->result_array();
            $hotRecommWords = $memVal = $item = [];
            foreach ($hotkeywords as $hotkeyword) {
                $item = $this->keyword_to_format_arr($hotkeyword['sKeywords']);
                $hotRecommWords = array_merge($hotRecommWords, $item);
            }
            $this->cache->memcached->save($memKey, $hotRecommWords, 86400);
        }
        $iColumnId = $isStp ? 'iSpecialTopicPatterns' : $iColumnId; // 图案专题特殊处理
        foreach ($hotRecommWords as $hotRecommWord) {
            if (empty($hotRecommWord['url'])) {
                $hotRecommWord['url'] = $this->getLink($iColumnId, $params, 'key', $hotRecommWord['keyword'], true, 'anchor');
            }
            $result[] = [
                'link' => $hotRecommWord['url'],
                'name' => $hotRecommWord['keyword']
            ];
        }
        $result = array_slice($result,-6,6);//最多取6个，其他截取掉
        return $result;
    }

    //老格式关键词转换成新格式数组输出，新关键词本身为json，输出数组
    private function keyword_to_format_arr($sKeywords)
    {
        if (empty($sKeywords)) {
            return array();
        }
        $keys_arr = json_decode($sKeywords, true);
        if (is_null($keys_arr) || !is_array($keys_arr)) {
            $keys_arr = explode(',', $sKeywords);
            if (is_array($keys_arr)) {
                foreach ($keys_arr as $key => $item) {
                    $keys_arr[$key] = array('keyword' => $item, 'url' => '');
                }
            }
        }
        return $keys_arr;
    }

    /******************************************** 华丽的私有方法分隔线 ***********************************************/
    // 获取连接符
    private function getSep($sep = '&')
    {
        return $sep;
    }

    // 获取后缀
    private function getSufix($ret = true)
    {
        if ($ret) {
            return $this->sufix;
        } else {
            return '';
        }
    }

    // 参数关联处理
    private function getAssociate($flip = false)
    {
        if ($flip) {
            return array_flip($this->associates);
        }

        return $this->associates;
    }

    private function inAssociate($type)
    {
        return array_key_exists($type, $this->associates);
    }

    /**
     * [getPoster 获取指定栏目下的广告图片]
     * @param integer $columnId [栏目id 1-0(性别-行业)表示交叉页]
     * @param integer $position [广告位置]
     * @param string $limit [查询条数]
     * @param bool $refresh
     * @param integer $columnPid [栏目父id 默认为0 表示不取父栏目广告]
     * @return array
     */
    private function getPoster($columnId, $position, $limit = '', $refresh = false, $columnPid = 0)
    {
        $table = self::T_FASHION_FM_AD;
        $power = memberPower('other');
        // 用户类型 与 广告的对应关系
        $user_type_arr = [
            1 => 1,//vip
            2 => 1,
            3 => 2,//试用
            4 => 3,//普通
            5 => 4,//游客
        ];
        $user_type = $user_type_arr[$power['P_UserType']];

        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . $table . '_' . $user_type . '_' . $columnId . '_' . $position;

        // 特殊处理，交叉页广告需要分性别
        if (strstr($columnId, '-')) {
            $sGender_sIndustry = explode('-', $columnId);
            $sGender = intval($sGender_sIndustry[0]);
            $sIndustry = intval($sGender_sIndustry[1]);
            $genInd = 0;
            if ($sGender) {
                $genInd = $sGender;
            } elseif ($sIndustry) {
                $genInd = $sIndustry;
            }
            $columnId = -1;
            $mem_key .= ($genInd ? $genInd : '');
        }
        $memcache_obj = OpMemcache::getInstance();
        $resultAd = $memcache_obj->get($mem_key);

        if (!$resultAd || $refresh) {

            $time = date('Y-m-d H:i:s');
            $where = "dStartTime <= '" . $time . "'";
            // 语言(1=>中文,2=>英文,3=>韩文)
            $lang = intval($this->input->cookie('lang'));
            $sLanguage = $lang ? $lang : 1;
            $where .= " AND FIND_IN_SET({$sLanguage}, sLanguage) ";
            //用户类型
            if ($user_type) {
                $where .= " AND FIND_IN_SET({$user_type}, iUserType) ";
            }

            if ($limit != '') {
                $limit = ' LIMIT ' . $limit;
            }

            switch ($columnId) {
                // 交叉页
                case -1:
                    $bind = [$position];
                    if ($genInd) {
                        $where .= ' AND FIND_IN_SET(?, sGender)';
                        $bind[] = $genInd;
                    }
                    $sql = "SELECT iAdId,iColumnId,iPosition,sImagePath,sUrl,dCreateTime,sTitle,subTitle,sMemo,sBackground,sNewWindow,dStartTime,dEndTime, IF(dEndTime>'$time', 1, 0) e  FROM {$table} WHERE iPosition = ? AND iStatus = 1 AND " . $where . "  ORDER BY e DESC, iOrder DESC " . $limit;
                    $res = $this->query($sql, $bind);
                    break;
                default:
                    if ($columnPid) {//主栏目广告属子栏目广告
                        $sql = "SELECT iAdId,iColumnId,iPosition,sImagePath,sUrl,dCreateTime,sTitle,subTitle,sMemo,sBackground,sNewWindow,dStartTime,dEndTime, IF(dEndTime>'$time', 1, 0) e  FROM {$table} WHERE (iColumnId = ? OR iColumnId = ?) AND  iPosition = ? AND iStatus = 1 AND  " . $where . "  ORDER BY e DESC, iOrder DESC,dCreateTime DESC " . $limit;
                        $res = $this->query($sql, [$columnId, $columnPid, $position]);
                    } else {
                        $sql = "SELECT iAdId,iColumnId,iPosition,sImagePath,sUrl,dCreateTime,sTitle,subTitle,sMemo,sBackground,sNewWindow,dStartTime,dEndTime, IF(dEndTime>'$time', 1, 0) e  FROM {$table} WHERE iColumnId = ? AND  iPosition = ? AND iStatus = 1 AND  " . $where . "  ORDER BY e DESC, iOrder DESC,dCreateTime DESC " . $limit;
                        $res = $this->query($sql, [$columnId, $position]);
                    }
                    break;
            }

            $resultAd = [];
            foreach ($res as $key => $val) {
                if (strripos($val['sUrl'], 'http') !== false) { //加广告点击量统计
                    $val['sUrl'] = '/statistics/link/?url=' . base64_encode($val['iAdId']) . '_' . base64_encode($val['sUrl']);
                }
                if ($val['e'] == 1) {
                    unset($val['e']);
                    $resultAd[] = $val;
                }
            }

            if (empty($resultAd)) {
                if (strripos($res[0]['sUrl'], 'http') !== false) { //加广告点击量统计
                    $res[0]['sUrl'] = '/statistics/link/?url=' . base64_encode($res[0]['iAdId']) . '_' . base64_encode($res[0]['sUrl']);
                }
                $resultAd[] = $res[0];
            }

            if (!empty($resultAd) && count($resultAd) > 0) {
                $memcache_obj->set($mem_key, $resultAd, 0, 3600);
            }
        }

        if ($position == 3) {//款式详情特殊处理，随机显示一条
            $iKey = array_rand($resultAd);
            $resultAd = [$resultAd[$iKey]];
        }

        if ($position == 12 && count($resultAd) > 2) {//行业资源详情侧边广告，随机显示两条
            $resultAd_temp = [];
            $aKey = array_rand($resultAd, 2);
            $resultAd_temp[] = $resultAd[$aKey[0]];
            $resultAd_temp[] = $resultAd[$aKey[1]];
            $resultAd = $resultAd_temp;
        }

        return $resultAd;
    }

    // 过滤参数
    private function filterParams(array $params)
    {
        // 参数过滤
        $_params = [];
        foreach ($params as $type => $val) {
            $_type = strtolower($type);
            if (array_key_exists($_type, $this->associates)) {
                $_val = strtolower($val);
                // 非数字型的
                // 版本
                if ($_type == 'ver') {
                    if (!in_array($_val, ['focus', 'live', 'special', 'video'])) {
                        continue;
                    }
                } // 共享
                elseif ($_type == 'sha') {
                    if (!in_array($_val, ['vector', 'trends', 'runway', 'brands', 'fast', 'collect'])) {
                        continue;
                    }
                } // 类型
                elseif ($_type == 'typ') {
                    if (!in_array($_val, ['bridal', 'cruise', 'secret', 'special', 'tshowpic']) && !is_numeric($_val)) {
                        continue;
                    }
                } // 书名,活动开始时间,活动结束时间
                elseif ($_type == 'boo' || $_type == 'beg' || $_type == 'end' || $_type == 'ctn' || $_type == 'ctp') {
                    $val = rawurldecode($val);
                } // 地区
                elseif ($_type == 'reg') {
                    if (strtolower($val) != 'other' && !is_numeric($val)) {
                        continue;
                    }
                } // 表名
                elseif ($_type == 't') {
                    $table = getCommonData('common_data', 'popTableNameKeyValue');
                    if (!in_array(strtolower($val), array_flip($table))) {
                        continue;
                    }
                } else {
                    // 值为数字型的，传了非数字的，过滤掉。不作容错，抱歉！
                    if (!is_numeric($val)) {
                        continue;
                    }
                }
                $_params[$type] = $val;
            }
        }
        return $_params;
    }

    private function replaceSep($params, $flip = false)
    {
        if (is_array($params)) return $params;
        if ($flip) {
            return str_replace(['=', '&'], ['_', '-'], $params);
        }
        return str_replace(['_', '-'], ['=', '&'], $params);
    }

    //获取趋势参考的随机数
    public function getRandAuthKey()
    {
        $MemTime = strtotime(date('Y-m-d 23:59:59')) - time();
        $MemcacheKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_rand_auth_key';
        $this->load->driver('cache');
        $ret = $this->cache->memcached->get($MemcacheKey);
        if (!$ret || $this->input->get('refresh')) {
            $table = "`wgsn`.`auth_key`";
            $sql = "SELECT * FROM $table LIMIT 1";
            $res = $this->query($sql);
            $ret = $res[0]['key'];
            $this->cache->memcached->save($MemcacheKey, $ret, $MemTime);
        }
        return $ret;
    }

    public function getMiddlePageTag($table, $id, $col)
    {
        $res = [];
        switch ($table) {
            case 't_trend_report':
                $res = $this->report_model->getTrendReport($id, $col);
                break;
            case 'fs_analysis':
            case 'fs_commodity':
            case 'fs_design':
            case 'fs_inspiration':
            case 'fs_trend':
                $res = $this->report_model->getFsDatas($table, $id, 'id', $col);
                break;
            case 'mostrend_content':
                $res = $this->report_model->getMosReport($id, $col);
                break;
            case 'specialtopic':
                $res = $this->report_model->getSpecialtopic($id, $col);
                break;
            case 'specialtopic_graphic':
                $res = $this->report_model->getSpecialtopicGraphic($id, $col);
                break;
            case '':
                break;
            case '':
                break;
            case '':
                break;
            case '':
                break;
        }

        $tags = [];
        $tags['colName'] = $res['colsName'];
        $tags['colLink'] = $res['colLink'];

        $tags['seaName'] = $res['iSeason_text'];
        $tags['seaLink'] = $res['seasonLink'];

        $tags['genInfo'] = $res['genderInfo'];
        $tags['indInfo'] = $res['industryInfo'];
        $tags['catInfo'] = $res['categoryInfo'];

        return $tags;
    }


    /**
     * 获取推荐弹窗报告列表
     *
     * @return array
     */
    public function getRecommendFrame()
    {
        $params = '';
        $cond = $lists = [];
        //栏目
        $cond['other'][] = "(iColumnId:1 OR iColumnId:2)";
        // 性别
        $gender = $this->getGenderByRequest($params);
        // 行业
        $industry = $this->getIndustryByRequest($params);
        $gender && $condition['other'][] = "aLabelIds:{$gender}";
        $industry && $condition['other'][] = "aLabelIds:{$industry}";
        // 排序
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $cond, $result, 0, 6, $arSort);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            $_info['id'] = $id;

            switch ($tableName) {
                case 'specialtopic':
                case 'specialtopic_graphic':
                case 'mostrend_content':
                    $_info['title'] = htmlspecialchars(stripcslashes($info['title']));
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    break;
                case 't_trend_report':
                    $_info['title'] = $info['sTitle'];
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['sDesc'])));//摘要
                    break;
                case 'fs_analysis':
                case 'fs_commodity':
                case 'fs_design':
                case 'fs_inspiration':
                case 'fs_trend':
                    $_info['title'] = htmlspecialchars(stripcslashes($info['title']));
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['abstract']))); //摘要
                    break;
                default:
                    //标题
                    $_info['title'] = htmlspecialchars(stripcslashes($info['title']));
                    $_info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    break;
            }

            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $_info['cover'] = getFixedImgPath($imgPath['cover']);

            $lists[$key]['list'] = $_info;
            $lists[$key]['columnId'] = $val['iColumnId'][1];
            $lists[$key]['tableName'] = getProductTableName($tableName);
        }

        return $lists;
    }

    /**
     * 获取栏目页底部广告
     *
     * @param int|string $columnId 子栏目id
     * @param int|string $column_Pid 主栏目id
     * @return mixed
     */
    public function getAdsBottom($columnId, $column_Pid)
    {
        $columnPid = $columnId == $column_Pid ? $column_Pid : 0;
        // 18 栏目底部广告
        $ads = $this->getAds($columnId, $position = 18, $num = 5, $columnPid);
        return $ads;
    }

    /**
     * vip用户登录过期提示条
     */
    public function vipExpireTips($userId)
    {
        $tableName = self::T_FASHION_FM_PRIVILEGE;
        $sql = "select iAccountId,dEndTime from $tableName where `iAccountId`=? ";
        $result = $this->query($sql, $userId);
        $endTime = array_column($result, 'dEndTime');
        arsort($endTime);
        $endTime = array_slice($endTime, 0, 1)[0];
        $endTime = date('Y-m-d 00:00:00', strtotime($endTime));
        $nowTime_90 = date('Y-m-d 00:00:00', strtotime('+90 days'));
        $nowTime_7 = date('Y-m-d 00:00:00', strtotime('+7 days'));
        $nowTime_1 = date('Y-m-d 00:00:00', strtotime('+1 days'));
        $flag = '';
        if ($nowTime_1 > $endTime) {
            $flag = 'today';
        } elseif ($nowTime_7 >= $endTime || $nowTime_1 == $endTime) {
            $time = strtotime(date('Y-m-d 00:00:00'));
            $end = strtotime($endTime);
            $flag = ceil(($end - $time) / 86400);
        } elseif ($nowTime_90 >= $endTime) {
            $flag = 90;
        }

        return $flag;
    }

}
