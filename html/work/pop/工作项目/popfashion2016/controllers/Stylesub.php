<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 款式二级列表页
 * @author  pop
 * @time  2018-01-24
 */
class Stylesub extends POP_Controller
{
	private $columnPid = 4;
	private $pageSize = 24;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['common_model', 'styles_model', 'details_model']);
		$power = memberPower('other');
		$memberFlag = 0;
		if ($power['P_UserType'] == 5) {//游客
			$memberFlag = 1;
		}
		if ($power['P_UserType'] == 4) {//普通
			$memberFlag = 2;
		}
		$this->assign('memberFlag', $memberFlag);
		$this->assign('columnPid', $this->columnPid);
	}

	/**
	 * 款式库-全部
	 * @param string $params
	 */
	public function index($params = '')
	{
		$this->common($this->columnPid, $params);
	}

	/**
	 * 款式库-T台款式
	 * @param string $params
	 */
	public function runways($params = '')
	{
		$this->common(50, $params);
	}

	/**
	 * 款式库-订货会
	 * @param string $params
	 */
	public function shows($params = '')
	{
		$this->common(52, $params);
	}

	/**
	 * 款式库-街拍
	 * @param string $params
	 */
	public function streetsnaps($params = '')
	{
		$this->common(56, $params);
	}

	/**
	 * 款式库-潮流领袖
	 * @param string $params
	 */
	public function trendsetters($params = '')
	{
		$this->common(57, $params);
	}

	/**
	 * 款式库-全球实拍
	 * iDataSource ds_2-零售市场 ds_1-批发市场 ds_3-展会图库
	 * @param string $params
	 */
	public function retail($params = '')
	{
		$this->common(54, $params);
	}

	/**
	 * 款式库-设计师品牌
	 * @param string $params
	 */
	public function designerbrand($params = '')
	{
		$this->common(122, $params);
	}

	/**
	 * 款式库-名牌精选（原：品牌在线）
	 * @param string $params
	 */
	public function online($params = '')
	{
		$this->common(55, $params);
	}

	/**
	 * 款式库-款式流行
	 * @param string $params
	 */
	public function popular($params = '')
	{
		$this->common(123, $params);
	}

    /**
     * 款式库-common
     * @param $columnId
     * @param string $params
     */
    private function common($columnId, $params)
    {
        // 1、参数处理
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1); //1代表把参数转化成数组
        if ($columnId == 54) {
            // 全球实拍默认零售市场
            $paramsArr['ds'] = isset($paramsArr['ds']) ? $paramsArr['ds'] : 2;
        }

        // 2、判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];

        // 3、页面标题
        $pageTitle = [];
        $tkdGroup = []; // tkd成册文字
        $_group_conditions = $this->styles_model->getConditions($params, $columnId, $powers, true);//去除 cat sea gen

        // 4、ajax获取列表
        $paramsArr['dis'] = 1; // 单张
        $params = $this->common_model->parseParams($paramsArr, 2);
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->styles_model->ajaxSubList($params, $columnId, $powers, $this->pageSize);
            return;
        }
        // 判断是否要显示水印或遮罩
        isShowWatermark($paramsArr, ['isStyleSub' => true]);

        // 性别:0-全部, 1-男装, 2-女装, 5-童装
        $iGender = intval($paramsArr['gen']);//$this->common_model->getGenderByRequest($params);
        $genderArr = [0 => '全部性别', 1 => '男装', 2 => '女装', 5 => '童装'];
        $sGender = isset($genderArr[$iGender]) ? $genderArr[$iGender] : '';
        $this->assign('sGender', $sGender);

        // 季节
        $iSeason = $paramsArr['sea'];
        $sSeason = trim(GetCategory::getOtherFromIds($iSeason, ['sName']));
        $tkdGroup['sea'] = $sSeason;
        $this->assign('sSeason', $sSeason);


        //反爬
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();

        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

        // 非游客
        if ($powers['P_UserType'] != 5) {

            // 50秀场提炼、52订货会、122设计师品牌、55名牌精选、123款式流行、54_2全球实拍-零售市场
            if (in_array($columnId, [4, 50, 52, 122, 55, 123])
                || ($columnId == 54 && $paramsArr['ds'] == 2)) {
                // 品牌季节
                $iBrand = intval($paramsArr['bra']);
                $sBrand = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');
                $sBrand = $sBrand ? $sBrand : '更多款式';
                $pageTitle['bra'] = $sBrand;
                $tkdGroup['bra'] = $sBrand;
            } else if ($columnId == 56) {
                // 56 街拍图库, 取地区季节
                $iRegion = $paramsArr['reg'];
                $sRegion = GetCategory::getFieldFromId($iRegion);
                $pageTitle['reg'] = $sRegion;
                $tkdGroup['bra'] = $sRegion;
            } elseif ($columnId == 57) {
                // 57 潮流领袖, 取人物季节
                $iStar = intval($paramsArr['sta']);
                $sStar = OpPopFashionMerger::getOldCategoryLabel($iStar)['cat_name'];
                $pageTitle['sta'] = $sStar;
                $tkdGroup['bra'] = $sStar;
            } elseif ($columnId == 54 && $paramsArr['ds'] == 1) {
                // 54_1 全球实拍-批发市场, 取市场季节
                $iMarket = intval($paramsArr['mar']);
                $sMarket = $this->details_model->getMarketName($iMarket);
                $pageTitle['mar'] = $sMarket;
                $tkdGroup['bra'] = $sMarket;
            } elseif ($columnId == 54 && $paramsArr['ds'] == 3) {
                // 54_3 全球实拍-展会图库, 取展会季节
                $iExhibition = intval($paramsArr['exh']);
                $sExhibition = $this->details_model->getMarketName($iExhibition);
                $pageTitle['exh'] = $sExhibition;
                $tkdGroup['bra'] = $sExhibition;
            }
            // 取季节: 根据 品牌(或地区、人物、市场、展会)
            $aSeason = $this->getGroupSeason($columnId, $_group_conditions, $params);
            $this->assign('aSeason', $aSeason);
            $_group_conditions['other'][] = '(aLabelIds:' . $iSeason . ')';
            // var_dump($aSeason);

            // 取性别: 根据 品牌(或地区、人物、市场、展会) + 季节
            $aGender = $this->getGroupGender($columnId, $_group_conditions, $params);
            $this->assign('aGender', $aGender);
            $this->common_model->childGender($iGender, $_group_conditions);

            // 取单品: 根据 品牌(或地区、人物、市场、展会) + 性别 + 季节
            $aCategory = $this->getGroupCategory($columnId, $_group_conditions, $params);
            $this->assign('aCategory', $aCategory);
            // var_dump($aCategory);
        }

        $pageTitle = $this->getPageTitle($pageTitle, $paramsArr, $columnId);
        $this->assign('pageTitle', $pageTitle);
        $this->getTDK($tkdGroup);

        // 4、获取列表和分页
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $rootUrl = str_replace('/styles/', '/stylesub/', $rootUrl);
        $this->getLists($params, $columnId, $powers, $rootUrl);

        // 5、页面渲染
        unset($paramsArr['dis']);
        $params = $this->common_model->parseParams($paramsArr, 2);
        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('params', $params);
        $this->assign('userType', $userType);
        $this->assign('rootUrl', $rootUrl);
        $this->display('lists/styles_list_inside.html');
    }

	/**
	 * 获取列表数据和分组列表数据和分页
	 * @param string $params
	 * @param int|string $columnId
	 * @param array $powers
	 * @param string $rootUrl
	 */
	private function getLists($params, $columnId, $powers, $rootUrl)
	{
		$this->benchmark->mark('getList');
		$paramsArr = $this->common_model->parseParams($params, 1);

		list($list, $info) = $this->styles_model->ajaxSubList($params, $columnId, $powers, $this->pageSize, $isAjax = false);
		$totalCount = $info['totalCount'];
		$page = $info['page'];
		$pageSize = $info['pageSize'] * 4;

		// 生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl);

		$this->assign('list', $list);
		// var_dump($list);
		$this->assign('totalCount', $totalCount);
		$this->assign('page', $page);
		$this->assign('pageSize', $pageSize);
		$this->assign('pageHtml', $pageHtml);
		$this->assign('powers', $powers);
		// 滚动加载更多
		$this->assign('showScrollLoad', count($list) >= $info['pageSize']);

		$this->benchmark->mark('getListEnd');
	}

    private function getGroupGender($columnId, $conditions, $params)
	{
		$groupField = 'sGender';
		$paramsArr = $this->common_model->parseParams($params, 1);
		// 性别:0-全部, 1-男装, 2-女装, 5-童装
        $iGender = $this->common_model->getGenderByRequest($paramsArr);
		$idArr = $this->_getGroupAttr($conditions, $groupField);
		$result = [];
		$_tempChild = [];// 性别筛选项-童装（3，4均为童装）
		foreach ($idArr as $id) {
            // 去除 男童,女童 替换为5-童装
            if (in_array($id, [3, 4, 5])) {

                $_tempChild = [
                    'id'       => 5,
                    'name'     => trim(GetCategory::getOtherFromIds(5, ['sName'])),
                    'selected' => 5 == $iGender,
                    'link'     => $this->getLink($columnId, $params, $type = 'gen', $value = 5)
                ];

                continue;
            }

			$result[] = [
				'id'       => $id,
				'name'     => trim(GetCategory::getOtherFromIds($id, ['sName'])),
				'selected' => $id == $iGender,
				'link'     => $this->getLink($columnId, $params, $type = 'gen', $value = $id)
			];
		}
        if ($_tempChild) {
            $result[] = $_tempChild;
            $idArr[] = 5;
        }

        if ($iGender && !in_array($iGender, $idArr)) {
            // 根据单品取不到数据, 去掉cat条件后重定向
            $paramsArr['gen'] = 0;
            $redirectUrl = $this->getLink($columnId, $paramsArr);
            // var_dump($redirectUrl);
            header('Location: ' . $redirectUrl);
            return;
        }

        // 全部性别, 将性别的值设置为0,不改变cookie中的值
        $_paramsArr = $paramsArr;
        $_paramsArr['gen'] = 0;
        $_params = $this->common_model->parseParams($_paramsArr, 2);
        $link = $this->getLink($columnId, $_params);
        $all = [
            'id'       => '',
            'name'     => '全性别',
            'selected' => 0 == $iGender,
            'link'     => $link
        ];
        $result = array_merge([$all], $result);
		// var_dump($result);
		return $result;
	}

	/**
	 * 获取季节筛选条件
	 * @param $columnId
	 * @param $conditions
	 * @param $params
	 * @return array
	 */
    private function getGroupSeason($columnId, $conditions, $params)
	{
		$groupField = 'iSeason';
		$idArr = $this->_getGroupAttr($conditions, $groupField);

		$paramsArr = $this->common_model->parseParams($params, 1);
		$iSeason = isset($paramsArr['sea']) ? $paramsArr['sea'] : '';
		$result = [];
		$result[] = [
			'id'       => $iSeason,
			'name'     => trim(GetCategory::getOtherFromIds($iSeason, ['sName'])),
			'selected' => true,
			'link'     => $this->getLink($columnId, $params, $type = 'sea', $value = $iSeason)
		];
		foreach ($idArr as $id) {
			if ($id == $iSeason) {
				continue;
			}
			$result[] = [
				'id'       => $id,
				'name'     => trim(GetCategory::getOtherFromIds($id, ['sName'])),
				'selected' => false,
				'link'     => $this->getLink($columnId, $params, $type = 'sea', $value = $id)
			];
		}
		// var_dump($result);
		return $result;
	}

	/**
	 * 获取单品筛选条件
	 * @param $columnId
	 * @param $conditions
	 * @param $params
	 * @return array|null
	 */
    private function getGroupCategory($columnId, $conditions, $params)
	{
		$solrParams = array();
		$solrParams['group'] = 'true';
		$solrParams['group.offset'] = 0;
		$solrParams['group.limit'] = 1;//每组取得数据条数
		$solrParams['group.ngroups'] = 'true';
		$solrParams['group.field'] = ['sCategory', 'sSubCategory'];
		$arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');

		POPSearch::wrapQueryPopFashionMerger('', $conditions, $res, 0, 20, $arSort, $solrParams);
		// var_dump($res);
		$resCate = $res['sCategory'];
		$resSubCate = $res['sSubCategory'];
		// 单品和品名id数组
		$cateIdArr = $subCateIdArr = [];
		if ($resCate['groups']) {
			foreach ($resCate['groups'] as $group) {
				$cateIdArr = array_merge($cateIdArr, explode(',', $group['groupValue']));
			}
			$cateIdArr = array_filter($cateIdArr);
			$cateIdArr = is_array($cateIdArr) ? array_unique($cateIdArr) : [];
		}
		if ($resSubCate['groups']) {
			foreach ($resSubCate['groups'] as $group) {
				$subCateIdArr = array_merge($subCateIdArr, explode(',', $group['groupValue']));
			}
			$subCateIdArr = array_filter($subCateIdArr);
			$subCateIdArr = is_array($subCateIdArr) ? array_unique($subCateIdArr) : [];
		}
		sort($cateIdArr);
		sort($subCateIdArr);
		// 单品、品名id数组
		$idArr = array_merge($cateIdArr, $subCateIdArr);
		// var_dump($cateIdArr, $subCateIdArr, $idArr);
		$paramsArr = $this->common_model->parseParams($params, 1);
		// 参数中的单品/品名id
		$attrId = isset($paramsArr['cat']) ? intval($paramsArr['cat']) : '0';

		// 结果
		$result = [];
		// 全部单品, 去掉cat参数
		$_paramsArr = $paramsArr;
		unset($_paramsArr['cat']);
		$_params = $this->common_model->parseParams($_paramsArr, 2);
		$result[] = [
			'id'           => '',
			'name'         => '全部',
			'selected'     => '' == $attrId,
			'link'         => $this->getLink($columnId, $_params),
			'aSubCategory' => [] // 该单品下的品名
		];

		// 判断attrId是单品还是品名, 3-单品、4-品名
		$_cateType = intval(GetCategory::getOtherFromIds($attrId, ['iType']));
		// 参数中的单品/品名id(attrId)不在根据当前条件group所得的结果集里
		if ($attrId && !in_array($attrId, $idArr)) {
			// attrId是品名, 取对应单品进行判断
			if ($_cateType == 4) {
				$_ret = GetCategory::getCatIdsBySubcatIds($attrId);
				// 其对应单品在结果集里, 重定向至该单品
				if (in_array($_ret, $idArr)) {
					$paramsArr['cat'] = $_ret;
					$redirectUrl = $this->getLink($columnId, $paramsArr);
					header('Location: ' . $redirectUrl);
					return null;
				}
			}
			// 去掉单品/品名条件重定向
			unset($paramsArr['cat']);
			$redirectUrl = $this->getLink($columnId, $paramsArr);
			header('Location: ' . $redirectUrl);
			return null;
		}

		// 处理单品
		$cateSelected = false; // 是否有选中的单品
		foreach ($cateIdArr as $id) {
			if ($id == $attrId) {
				$cateSelected = true;
			}
			$result[$id] = [
				'id'           => $id,
				'name'         => trim(GetCategory::getOtherFromIds($id, ['sName'])),
				'selected'     => $id == $attrId,
				'link'         => $this->getLink($columnId, $params, $type = 'cat', $value = $id),
				'aSubCategory' => [] // 该单品下的品名
			];
		}

		// 处理品名
		foreach ($subCateIdArr as $id) {
			$_cateId = GetCategory::getCatIdsBySubcatIds($id);
			if (key_exists($_cateId, $result)) {
				// 单品下面添加一个“全部”品名
				if (count($result[$_cateId]['aSubCategory']) == 0) {
					$_cateInfo = $result[$_cateId];
					$result[$_cateId]['aSubCategory'][0] = [
						'id'       => $_cateInfo['id'],
						'name'     => '全部',
						'selected' => $_cateInfo['selected'],
						'link'     => $_cateInfo['link']
					];
				}
				// 添加当前品名
				$_name = trim(GetCategory::getOtherFromIds($id, ['sName']));
				$result[$_cateId]['aSubCategory'][$id] = [
					'id'       => $id,
					'name'     => $_name,
					'selected' => $id == $attrId,
					'link'     => $this->getLink($columnId, $params, $type = 'cat', $value = $id)
				];
				if ($id == $attrId) {
					// 若该品名选中, 同时选中其所在单品
					$result[$_cateId]['selected'] = true;
					$cateSelected = true;
					$result[$_cateId]['name'] .= '/' . $_name;
				}
			}
		}

		// 没有选中单品/品名则选中全部
		if (!$cateSelected) {
			$result[0]['selected'] = true;
		}

		// var_dump($result);
		return $result;
	}

	private function _getGroupAttr($conditions, $groupField)
	{
		$params = array();
		$params['group'] = 'true';
		$params['group.offset'] = 0;
		$params['group.limit'] = 1;//每组取得数据条数
		$params['group.ngroups'] = 'true';
		$params['group.field'] = $groupField;
		$arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');

		POPSearch::wrapQueryPopFashionMerger('', $conditions, $res, 0, 20, $arSort, $params);
		$idArr = [];
		if ($res[$groupField]['groups']) {
			foreach ($res[$groupField]['groups'] as $group) {
				$idArr = array_merge($idArr, explode(',', $group['groupValue']));
			}
		}
		$idArr = array_filter($idArr);
		$idArr = is_array($idArr) ? array_unique($idArr) : [];
		return $idArr;
	}

	/**
	 * 根据查询参数生成页面标题
	 * @param array $pageTitle
	 * @param array $paramsArr
	 * @param int $columnId
	 * @return string
	 */
	private function getPageTitle($pageTitle, $paramsArr, $columnId)
	{
		$arr = ['bra', 'mar', 'exh', 'sta', 'reg', 'key', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'age', 'coll', 'aco'];
		foreach ($paramsArr as $param => $val) {
			if (isset($pageTitle[$param]) || !in_array($param, $arr)) {
				continue;
			}

			if (in_array($param, ['reg', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'acc', 'age'])) {
				$pageTitle[$param] = trim(GetCategory::getOtherFromIds($val, ['sName']));
			}
			// 品牌
			if ($param == 'bra') {
				$pageTitle[$param] = GetCategory::getBrandOtherFormId($val, 'en_cn_brand');
			}
			// 市场
			if ($param == 'mar') {
				$pageTitle[$param] = $this->details_model->getMarketName($val);
			}
			// 展会
			if ($param == 'exh') {
				$pageTitle[$param] = $this->details_model->getMarketName($val);
			}
			// 明星达人
			if ($param == 'sta') {
				$pageTitle[$param] = OpPopFashionMerger::getOldCategoryLabel($val)['cat_name'];
			}
			// 地区
			if ($param == 'reg') {
				$pageTitle[$param] = GetCategory::getFieldFromId($val);
			}
			// 色系
			if ($param == 'aco') {
				$pageTitle[$param] = $this->styles_model->getColorDict($val, 'sAssortColor')['sName'];
			}
			// 关键词
			if ($param == 'key') {
				$pageTitle['key'] = $val;
			}
			// 收藏
			if ($param == 'coll') {
				$pageTitle['coll'] = $val == 1 ? '我的收藏' : '';
			}

		}
		// 栏目名称
		$columnsArr = [
			'4'    => '全部款式',
			'50'   => '秀场提炼',
			'52'   => '订货会精选',
			'52_1' => '订货会款式图', // prop_1
			'52_2' => '订货会手稿图', // prop_2
			'54'   => '全球实拍',
			'54_1' => '全球实拍/批发市场', // ds_1
			'54_2' => '全球实拍/零售市场', // ds_2
			'54_3' => '全球实拍/展会图库', // ds_3
			'55'   => '名牌精选',
			'56'   => '街拍图库',
			'57'   => '潮流领袖',
			'122'  => '设计师品牌',
			'123'  => '款式流行',
		];
		if ($columnId == 52 && !isset($paramsArr['prop'])) {
			$paramsArr['prop'] = 1;
		}
		// 行业
        $ind = $this->common_model->getIndustryByRequest($paramsArr);
        if ($ind) {
            $pageTitle['ind'] = trim(GetCategory::getOtherFromIds($ind, ['sName']));
        }

		$colKey = (string)$columnId;
		if (isset($paramsArr['prop'])) {
			$colKey .= '_' . $paramsArr['prop'];
		} elseif (isset($paramsArr['ds'])) {
			$colKey .= '_' . $paramsArr['ds'];
		}
		if (key_exists($colKey, $columnsArr)) {
			$pageTitle['col'] = $columnsArr[$colKey];
		}
		// 时间范围
        if (isset($paramsArr['tim'])) {
            $pageTitle['tim'] = $this->common_model->getTimeText($paramsArr);
        }

		$pageTitle = array_filter($pageTitle);
		return implode('&nbsp;&nbsp;', $pageTitle);
	}

	/**
	 * tkd
	 * @param $tkdGroup
	 */
	private function getTDK($tkdGroup)
	{
		$t = $tkdGroup['sea'] . '_' . $tkdGroup['bra'];
		$k = $tkdGroup['sea'] . ',' . $tkdGroup['bra'];
		$d = $tkdGroup['sea'] . '/' . $tkdGroup['bra'];
		$this->assign('title', $t . '_款式-POP服装趋势网');
		$this->assign('keywords', $k . ',款式,品牌');
		$this->assign('description', 'POP服装趋势网款式栏目为您提供最新、最前沿的' . $d . '图片资讯，为您及时提供与' . $d . '相关的服装款式、色彩、面料、印花图案等方面资讯，为您提供有价值的服装设计资讯服务。');
	}

	/**
	 * 生成二级列表url
	 * @param $columnId
	 * @param string|array $params
	 * @param string $type
	 * @param string $value
	 * @param bool $filter
	 * @param string $anchor
	 * @return string
	 */
	private function getLink($columnId, $params = '', $type = '', $value = '', $filter = TRUE, $anchor = '')
	{
		$params = is_array($params) ? $this->common_model->parseParams($params, 2) : $params;
		$link = $this->common_model->getLink($columnId, $params, $type, $value, $filter, $anchor);
		$link = str_replace('/styles/', '/stylesub/', $link);
		return $link;
	}

}