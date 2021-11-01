<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*@todo  用户权限
*@param  $powerSource 	list : 列表权限 detail ：详情权限 other ： 其他
*@param  $aParameter 	性别 P_Gender => string,行业 P_Industry => string,栏目 P_Column => array or int,
列表返回信息：
return array(
	遮罩 P_Shade	=> true(有遮罩) | false(不遮罩)
	乱序 P_Sort		=> true(乱序) | false(不乱序)
	检索 P_Search	=> true(能检索) | false(不能检索)
	收藏 P_Collect	=> true(能收藏) | false(不能收藏)
	账号类型 P_UserType => 1 主账号vip , 2子账号vip 、3试用 4普通 5游客
	账号来源 P_AccountFrom		=> 1 服装 2 高端
);

款式素材报告详情：
	return array(
		访问P_Visit					=> true(可以) | false(不可以)
		单张下载P_SingleDownLoad	=> true(可以) | false(不可以)
		收藏/打包下载 P_Collect		=> true(可以) | false(不可以)
		pdf下载 P_PdfDownLoad		=> true(可以) | false(不可以)
		账号类型 P_UserType			=> 1 主账号VIP , 2子账号VIP 、3试用 4普通 5游客
		账号来源 P_AccountFrom		=> 1 服装 2 高端
	);

其他other
	return array(
		账号类型P_UserType => 1 vip , 2子账号vip 、3试用 4普通 5游客
		有效期 P_ExpireTime =>''
		VIP类型 P_VIPType => 0 :不是VIP 1：新服装VIP 2：老服装VIP 3：mostrendVIP 4:试用VIP
	);

*@return $aPower权限数组

 -----------------------------------------------------------------
 * @date : 2017-04-25
 * @function : 
		列表访问：
		------------------------------------
					遮罩		乱序	    检索
		游客：		有		不乱	    可以
		普通用户：	无		不乱	    可以

		详情访问：
		------------------------------------
					访问		下载	关注/收藏/打包下载
		游客：		不能		不能		不能		
		普通用户：	不能		不能		不能


*/
function memberPower( $powerSource = 'other', $aParameter = array('P_Gender' => '' ,'P_Industry' => '' , 'P_Column' => 0 ) ) {
	$CI = & get_instance();
	$CI->load->model('member_model');
	//$CI->member_model->checkIpConflit();

	if( !in_array( $powerSource , array('list', 'detail', 'other') ) )
	{
		return FALSE;
	}

	$aPowerInfo = $aGetIndustry = $aGender = $aColumn = $aIndustry = $aGender = $aColumns = array();
	$aPowerOldToNew = getCommonData('common_data','aPowerOldToNew');
	$aArticleColumnIds = getCommonData('common_data','aArticleColumnIds');				//文章类栏目Id
	$aListPower = array(
		'P_Shade' => TRUE,
		'P_Sort' => TRUE,
		'P_Search' => FALSE,
		'P_Collect' => FALSE,
		'P_UserType' => 5,
		'P_AccountFrom' => 1
	);

	$aDetailPower = array(
		'P_Collect' => FALSE,
		'P_Visit' => FALSE,
		'P_SingleDownLoad' => FALSE,
		'P_PdfDownLoad' => FALSE,
		'P_UserType' => 5,
		'P_AccountFrom' => 1
	);

	$aOtherPower = array(
		'P_UserType' => 5,
		'P_ExpireTime' =>'',
		'P_VIPType'=>0
	);

	/*
		+ $isVip	当前栏目是否是vip
		+ $isAllVip	整站是否是vip或试用vip
	*/
	$isVip = $isTiral = $isUser = $isVisitor = $isAllVip = FALSE;

	$aCookieUserInfo = get_cookie_value();

	if( !empty( $aCookieUserInfo ) ) {
		//新权限vip判断
		$aPowerInfo = $CI->member_model->checkNewVip( intval( $aCookieUserInfo['id'] ) );

		//性别
		if( $aParameter['P_Gender'] ) {
			$popGender = getCommonData('common_data','popGender');
			$aGenderArray = explode( ',', $aParameter['P_Gender'] );
			foreach( $aGenderArray as $val ) {
				foreach( $popGender as $key => $iGender ) {
					if( in_array( $val, $iGender ) ) {
						$aGender[] = $key;
					}
				}
			}
		}

		//行业
		if( $aParameter['P_Industry'] ) {
			$aGetIndustry = explode( ',' , $aParameter['P_Industry'] );
		}
		//栏目
		if( !is_array( $aParameter['P_Column'] ) ) {
			$aGetColumn = array( $aParameter['P_Column'] );
		} else {
			$aGetColumn = $aParameter['P_Column'];
		}

		if( $aPowerInfo ) {
			//验证新服装vip和试用vip
			switch ( $powerSource ) {
				case 'list' :
					if( empty( $aParameter['P_Column'] ) ) {
						return FALSE;
					}
					foreach( $aPowerInfo as $iPowerInfo ) {
						if( $iPowerInfo['iType'] == 3 ) {
							$aColumn = array_merge( $aColumn, explode( ',' , $iPowerInfo['sColumn'] ) );
						}
					}
					$sColumn = array_intersect( $aGetColumn , array_unique( $aColumn ) );
					if( $sColumn ) {
						$isVip = TRUE;
					} else {
						$isVip = FALSE;
						foreach( $aPowerInfo as $iPowerInfo ) {
							if( $iPowerInfo['iType'] == 1 ) {
								$aColumn = array_merge( $aColumn, explode( ',' , $iPowerInfo['sColumn'] ) );
							}
						}
						$sColumn = array_intersect( $aGetColumn , array_unique( $aColumn ) );
						if( $sColumn ) {
							$isTiral = TRUE;
						}
					}
					break;

				case 'detail' :
					if( empty( $aParameter['P_Column'] ) ) {
						return FALSE;
					}

					foreach( $aPowerInfo as $iPowerInfo ) {
						if( $iPowerInfo['iType'] == 3  && $isVip === FALSE ) {
							$isAllVip = TRUE;
							$sColumn = array_intersect( $aGetColumn , explode( ',' , $iPowerInfo['sColumn'] ) );
							if( empty( $sColumn ) ) {
								$isVip = FALSE;
							} else {
								$isVip = TRUE;
							}

							if( $aGender && $isVip == TRUE ) {
								$sGender = array_intersect( $aGender , explode( ',' , $iPowerInfo['sGender'] ) );
								if( empty( $sGender ) ) {
									$isVip = FALSE;
								} else {
									$isVip = TRUE;
								}
							}

							if( $aGetIndustry  && $isVip == TRUE ) {
								$sIndustry = array_intersect( $aGetIndustry , explode( ',' , $iPowerInfo['sIndustry'] ) );
								if( empty( $sIndustry ) ) {
									$isVip = FALSE;
								} else {
									$isVip = TRUE;
								}
							}
						}
					}

					if( $isVip == FALSE ) {
						foreach( $aPowerInfo as $iPowerInfo ) {
							if( $iPowerInfo['iType'] == 1 && $isTiral === FALSE ) {
								$isAllVip = TRUE;
								$sColumn = array_intersect( $aGetColumn , explode( ',' , $iPowerInfo['sColumn'] ) );
								if( !empty( $sColumn ) ) {
									$isTiral = TRUE;
								}

								if( $aGender && $isTiral === TRUE ) {
									$sGender = array_intersect( $aGender , explode( ',' , $iPowerInfo['sGender'] ) );
									if( !empty( $sGender ) ) {
										$isTiral = TRUE;
									} else {
										$isTiral = FALSE;
									}
								}

								if( $aGetIndustry && $isTiral === TRUE ) {
									$sIndustry = array_intersect( $aGetIndustry , explode( ',' , $iPowerInfo['sIndustry'] ) );
									if( !empty( $sIndustry ) ) {
										$isTiral = TRUE;
									} else {
										$isTiral = FALSE;
									}
								}
							}
						}
					}


					break;

				case 'other' :
					foreach( $aPowerInfo as $iPowerInfo ) {
						if( $iPowerInfo['iType'] == 3 ) {
							$aOtherPower['P_ExpireTime'] = $iPowerInfo['dEndTime'];
							$aOtherPower['P_VIPType'] = 1;
							$isVip = TRUE;
							CONTINUE 2;
						}
					}
					if( $isVip === FALSE ) {
						foreach( $aPowerInfo as $iPowerInfo ) {
							if( $iPowerInfo['iType'] == 1 ) {
								$aOtherPower['P_ExpireTime'] = $iPowerInfo['dEndTime'];
								$aOtherPower['P_VIPType'] = 4;
								$isTiral = TRUE;
								CONTINUE 2;
							}
						}
					}

					break;
				default :
					break;
			}
		}

		//普通用户
		if( $isTiral === FALSE && $isVip === FALSE ) {
			$isUser = TRUE;
		}
	} else {
		//游客
		$isVisitor = TRUE;
	}


	//vip
	if( $isVip === TRUE ) {
		if( $aCookieUserInfo['iAccountType'] == 2 ) {
			$aListPower['P_Shade'] = FALSE;
			$aListPower['P_Sort'] = FALSE;
			$aListPower['P_Search'] = TRUE;
			$aListPower['P_Collect'] = TRUE;
			$aListPower['P_UserType'] = 2;

			$aDetailPower['P_Collect'] = TRUE;
			$aDetailPower['P_Visit'] = TRUE;
			$aDetailPower['P_SingleDownLoad'] = TRUE;
			$aDetailPower['P_PdfDownLoad'] = TRUE;
			$aDetailPower['P_UserType'] = 2;

            $aOtherPower['P_Collect'] = TRUE;
			$aOtherPower['P_UserType'] = 2;
		} else {
			$aListPower['P_Shade'] = FALSE;
			$aListPower['P_Sort'] = FALSE;
			$aListPower['P_Search'] = TRUE;
			$aListPower['P_Collect'] = FALSE;
			$aListPower['P_UserType'] = 1;

			$aDetailPower['P_Collect'] = FALSE;
			$aDetailPower['P_Visit'] = TRUE;
			$aDetailPower['P_SingleDownLoad'] = TRUE;
			$aDetailPower['P_PdfDownLoad'] = TRUE;
			$aDetailPower['P_UserType'] = 1;

            $aOtherPower['P_Collect'] = FALSE;
			$aOtherPower['P_UserType'] = 1;
		}
	}

	//试用
	if( $isTiral === TRUE ) {
		if( $aCookieUserInfo['iAccountType'] == 2 ) {
			//试用--子账号
			$aListPower['P_Shade'] = FALSE;
			$aListPower['P_Sort'] = FALSE;
			$aListPower['P_Search'] = TRUE;
			$aListPower['P_Collect'] = TRUE;
			$aListPower['P_UserType'] = 3;

			$aDetailPower['P_Collect'] = TRUE;
			$aDetailPower['P_Visit'] = TRUE;
			$aDetailPower['P_SingleDownLoad'] = TRUE;
			$aDetailPower['P_PdfDownLoad'] = FALSE;
			$aDetailPower['P_UserType'] = 3;

            $aOtherPower['P_Collect'] = TRUE;
			$aOtherPower['P_UserType'] = 3;
		}else{
			$aListPower['P_Shade'] = FALSE;
			$aListPower['P_Sort'] = FALSE;
			$aListPower['P_Search'] = TRUE;
			$aListPower['P_Collect'] = FALSE;
			$aListPower['P_UserType'] = 3;

			$aDetailPower['P_Collect'] = FALSE;
			$aDetailPower['P_Visit'] = TRUE;
			$aDetailPower['P_SingleDownLoad'] = TRUE;
			$aDetailPower['P_PdfDownLoad'] = FALSE;
			$aDetailPower['P_UserType'] = 3;

            $aOtherPower['P_Collect'] = FALSE;
			$aOtherPower['P_UserType'] = 3;
		}
	}

	//普通用户
	if( $isUser === TRUE ) {
		$aListPower['P_Shade'] = false;
		$aListPower['P_Sort'] = false;
		$aListPower['P_Search'] = TRUE;
		$aListPower['P_Collect'] = FALSE;
		$aListPower['P_UserType'] = 4;

		if( $powerSource == 'detail' ) {
			if( in_array( intval( $aParameter['P_Column'] ), $aArticleColumnIds ) || $isAllVip == TRUE ) {
				//文章类不允许查看
				$aDetailPower['P_Visit'] = FALSE;
			} else {
				$aDetailPower['P_Visit'] = FALSE;
			}
		}

		$aDetailPower['P_Collect'] = FALSE;
		$aDetailPower['P_SingleDownLoad'] = TRUE;
		$aDetailPower['P_PdfDownLoad'] = FALSE;
		$aDetailPower['P_UserType'] = 4;

        $aOtherPower['P_Collect'] = FALSE;
        $aOtherPower['P_UserType'] = 4;
		$aOtherPower['P_ExpireTime'] = $CI->member_model->getExpiredVipUserInfo(intval( $aCookieUserInfo['id'] ))['dEndTime'];
	}

	//游客
	if( $isVisitor === TRUE ) {
		$aListPower['P_Shade'] = TRUE;
		$aListPower['P_Sort'] = false;
		$aListPower['P_Search'] = TRUE;
		$aListPower['P_Collect'] = FALSE;
		$aListPower['P_UserType'] = 5;

		$aDetailPower['P_Collect'] = FALSE;
		$aDetailPower['P_Visit'] = FALSE;
		$aDetailPower['P_SingleDownLoad'] = FALSE;
		$aDetailPower['P_PdfDownLoad'] = FALSE;
		$aDetailPower['P_UserType'] = 5;

        $aOtherPower['P_Collect'] = FALSE;
		$aOtherPower['P_UserType'] = 5;
	}

    $res = [];
	if( $powerSource == 'list' ) {
		//报告类数据栏目增加搜索权限，默认为true
		$pc_ids = array( 1,2,3,6,20,21,22,23,30,31,32,33,34,35,36,37,38,40,70,71,72,73,113,114,115,90,116,131,132);

		if( is_numeric( $aParameter[ 'P_Column' ] ) && intval( $aParameter[ 'P_Column' ] ) > 0 ) {
			if( in_array( $aParameter[ 'P_Column' ] , $pc_ids ) ) {
				$aListPower[ 'P_Search' ] = true;
				$aListPower[ 'P_Sort' ] = false;
			}
		} elseif( is_array( $aParameter[ 'P_Column' ] ) ) {
			if( array_intersect( $aParameter[ 'P_Column' ] , $pc_ids ) ) {
				$aListPower[ 'P_Search' ] = true;
				$aListPower[ 'P_Sort' ] = false;
			}
		}
		//款式 素材 灵感 普通会员无水印
		$style_ids = array( 4,7,8,50,51,52,53,54,55,56,57,80,81,82,84,85,90,91,116,117 );
		if( $isVisitor !== true ) {
			$aListPower['P_Shade'] = FALSE;
		}
		$res = $aListPower;
	}
	elseif ( $powerSource == 'detail' ) {
		$res = $aDetailPower;
	}
	elseif( $powerSource == 'other' ) {
		$res = $aOtherPower;
	}

	return $res;
}

if (! function_exists( 'yearTimeDiff' ) ) {
	function yearTimeDiff($format='', $cate = '' , $bFix = TRUE )
	{
        switch ($format) {
            case 'solr':
                return '*';
                break;
            case 'solr_current':
            default:
                return date("Y-m-d\T23:59:59\Z");
                break;
        }
	}
}

if (!function_exists('checkTrailInfo')) {
    /**
     * 普通用户20次试用控制
     * @param $path string 图片地址
     * @param $keySuffix string Redis键后缀 "site:{$siteId}:user:{$userId}:{$keySuffix}"
     * @param bool $isAjax
     * @return array
     */
    function checkTrailInfo($path, $keySuffix, $isAjax = false)
    {
        // 有无权限使用
        $canUse = true;
        // 当前用户id
        $userId = getUserId();
        $userId = $userId ? $userId : 0;
        $path = preg_replace('/^(http|https)(.)*\.com/', '', $path);
        // 试用次数信息
        $trialInfo = [];
        if (!$userId) {
            // 未登录
            $canUse = false;
        } else {
            // 权限信息
            $powers = memberPower();
            // 普通用户
            if ($powers['P_UserType'] == 4) {
                // 站点id
                $siteId = SITE_ID;
                // 允许每个用户最大使用次数(图片不重复)
                $lenMax = 20;
                // 当前已使用过的次数
                $len = 0;
                // redis 中记录的键 前缀
                $lockPrefix = 'LOCK:';
                // redis 中记录的键
                $key = "site:{$siteId}:user:{$userId}:{$keySuffix}";
                // picmatch 业务中使用的redis并发锁
                $keyLock = $lockPrefix . $key;
                // 并发锁有效时长(秒)
                $lockTime = 1;
                // 获取一个 redis 连接
                $redis = getRedisConnection();

                redo:
                $redoCnt = 0;
                // 获取一个并发锁
                $_isLock = $redis->setnx($keyLock, time());
                if ($_isLock) {
                    // 能拿到锁
                    $len = $redis->hLen($key);
                    if ($len >= $lenMax && !$redis->hExists($key, $path)) {
                        // 可用次数已用完
                        $canUse = false;
                    } else {
                        $redis->hIncrBy($key, $path, 1);
                        $len = $redis->hLen($key);
                    }
                    // 释放锁
                    $redis->del($keyLock);
                } else {
                    // 异常情况, 通过过期释放锁
                    $redis->expire($keyLock, $lockTime);
                    sleep($lockTime);
                    if (++$redoCnt < 2) {
                        goto redo;
                    }
                }
                // 已使用次数
                $used = $len > $lenMax ? $lenMax : $len;
                // 剩余次数
                $free = $lenMax - $len;
                // 试用次数信息
                $trialInfo = ['max' => $lenMax, 'used' => $used, 'free' => $free];
            }
        }
        if ($isAjax) {
            return compact('canUse', 'trialInfo');
        }
        $c = &get_instance();
        $c->assign('isLogin', (bool)$userId);
        $c->assign('trialInfo', $trialInfo);
        $c->assign('canUse', $canUse);
        return [];
    }
}

if (!function_exists('isShowWatermark')) {
    /**
     * 判断是否要显示水印或遮罩
     * @param $paramsArr
     * @param array $options 可选项：isAjax=false, isStyle=false, isStyleSub=false
     * @return array
     * @date 2018-11-07
     */
    function isShowWatermark($paramsArr, $options = [])
    {
        // 是否显示遮罩
        $showMask = false;
        // 是否显示水印
        $showWatermark = false;

        // 是否有筛选条件
        $withConditions = false;
        // 页码
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // 是否ajax
        $isAjax = isset($options['isAjax']) ? $options['isAjax'] : false;
        // 是否款式栏目, 款式栏目需要区分单张成册
        $isStyle = isset($options['isStyle']) ? $options['isStyle'] : false;
        // 是否款式二级列表
        $isStyleSub = isset($options['isStyleSub']) ? $options['isStyleSub'] : false;

        // 忽略这几个筛选条件: 栏目, 行业, 性别
        $condIgnored = ['col', 'ind', 'gen', 'page'];
        if (isset($_GET['key']) && $_GET['key']) {
            $paramsArr['key'] = $_GET['key'];
        }
        // 非款式栏目或者款式单张
        if (!$isStyle || (isset($paramsArr['dis']) && $paramsArr['dis'] == 1)) {
            $condIgnored[] = 'dis';
            foreach ($condIgnored as $item) {
                unset($paramsArr[$item]);
            }
            // 除去忽略的几个筛选条件仍有筛选条件
            if ($paramsArr) {
                $withConditions = true;
            }
        }

        $powers = memberPower();
        switch ($powers['P_UserType']) {
            case 4: // 普通用户
                // 有筛选条件, 大于1页, 款式二级列表页 加水印
                if ($withConditions || $page > 1 || $isStyleSub) {
                    $showWatermark = true;
                }
                break;
            case 5: // 游客
                // 有筛选条件, 大于1页 显示遮罩
                if ($withConditions || $page > 1) {
                    $showMask = true;
                }
                break;
            default:
                break;
        }
        if (!$isAjax) {
            $c = &get_instance();
            $c->assign('showMask', $showMask);
            $c->assign('showWatermark', $showWatermark);
            $c->assign('withConditions', $withConditions);
        }
        $result = compact('showWatermark', 'showMask', 'withConditions');
        // var_dump($result);
        return $result;
    }
}