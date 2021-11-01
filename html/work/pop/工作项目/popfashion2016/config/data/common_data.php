<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//获取数据表正式表名，或真实表名对应简称(工作台)
$popTableNameKeyValue = [
	'perform'				=> 'product_perform',
	'brand'					=> 'product_brand',
	'marketphotoitem'		=> 'product_marketphoto_item',
	'stylegraphic'			=> 'product_style_graphic',
	'stylegraphicdetails'	=> 'product_style_graphic_details',
	'stylegraphicchina'		=> 'product_style_graphic_china',
	'brochures'				=> 'product_brochures',
	'brochuresphoto'		=> 'product_brochures_photo',
	'styledetail'			=> 'product_style_detail',
	'streetitem'			=> 'product_streetitem',
	'graphicitem'			=> 'product_graphicitem',
	'fabricgalleryitem'		=> 'product_fabricgallery_item',
	'ordermeeting'			=> 'product_ordermeeting',
	'templateitem'			=> 'product_templateitem',
	'designrefrence'		=> 'product_design_refrence',
	'refrencegroup'			=> 'product_vector_refrence_group',
	'branddb'				=> 'brand_library',
	'presscon'    			=> 'product_presscon',
	'presscondetails' 		=> 'product_presscon_details',
	'hat'    				=> 'product_hat',
	'wrapsitem'    			=> 'product_wrapsitem',
	'docerationitem'    	=> 'product_docerationitem', // 首饰，暂时没有用到
	'glove'    				=> 'product_glove',
	'shopset'    			=> 'product_shopset',
	'inspirationdb'    		=> 't_inspiration_img_db',
	'analysis'				=> 'fs_analysis',
	'commodity'				=> 'fs_commodity',
	'design'				=> 'fs_design',
	'inspiration'			=> 'fs_inspiration',
	'trend'					=> 'fs_trend', // 趋势5个表
	'designrefrencedetails'	=> 'product_design_refrence_details',
	'report'				=> 't_trend_report', // 趋势报告
	'moscon'				=> 'mostrend_content', // mostrend 报告
	'spt'					=> 'specialtopic',     //关键主题
	'sptg'					=> 'specialtopic_graphic', //图案主题
	'pic'					=> 'picture',
	'tideleader'			=> 'product_tideleader', // 潮流领袖
	'vectorrefrence'		=> 'product_vector_refrence', // 矢量书稿
	'refrencelist'			=> 'product_vector_refrence_list', // 适量书稿列表，集合两张表到getProductData方法里面取数据
	'fabricgalleryitemdetail'	=> 'product_fabricgallery_item_detail',
	'uploadpicmaterial'		=> 'fm_upload_pic_material',
	'quickprint'		    => 't_digital_quick_print',
	'graphictechnics'		=> 'product_graphic_craft_item',
	'video'		            => 'product_video',// 视频专栏
    'styles'                => 'styles',//八爪鱼款式数据
];

//POP服装积分个人专享服务( 1：下载、2：充值、3：推荐VIP、4：每日首次登陆、5：完善信息、6：分配积分、7：注册子账号 、8：推荐非VIP)
$popIntegralConsumeTypes = [
	1 => '下载',
	2 => '充值',
	3 => '推荐VIP',
	4 => '每日首次登陆',
	5 => '完善信息',
	6 => '分配积分',
	7 => '注册个人专属账号',
	8 => '推荐非VIP',
];

/*
  + 性别归类数组 用于获取性别权限
*/
$popGender = [
	1 => ['男装', 'man', 'men', 1],
	2 => ['女装', 'women', 'woman', 2],
	5 => ['童装', '男童', '女童', 'child', 'child-boy', 'child-girl', 5]
];
/*
  + 服装老包年会员对应新网站权限 除 “未来趋势、潮流解析” 栏目外所有
  + 高端老会员对应新会员权限：高端全部 TO 新权限全部
							  高端女装 TO 除去男装、童装外所有权限
							  高端男装 TO 除去女装、童装外所有权限
*/
$aPowerOldToNew = array(
	'P_Fashion' => array(
		'column' => array(
			3,5,
			93,94,95,96,97,
			50,51,52,53,54,55,56,57,
			70,71,72,73,113,114,115,131,132,
			80,81,82,83,84,85,
			90,91,116,
		),
		'gender' => array(
			1,2,5
		),
		'industry' => array(
			6,7,8,9,10,11,12,158,159,11215,11243
		)
	),
	'P_M_All'=> array(
		'column' => array(
			3,5,
			20,21,22,23,
			30,31,32,33,34,35,36,37,38,40,
			93,94,95,96,97,
			50,51,52,53,54,55,56,57,
			70,71,72,73,113,114,115,131,132,
			80,81,82,83,84,85,
			90,91,116
		),
		'gender' => array(
			1,2,5
		),
		'industry' => array(
			6,7,8,9,10,11,12,158,159,11215,11243
		)
	),
	'P_M_Women'=> array(
		'column' => array(
			3,5,
			20,21,22,23,
			30,31,32,33,34,35,36,37,38,40,
			93,94,95,96,97,
			50,51,52,53,54,55,56,57,
			70,71,72,73,113,114,115,131,132,
			80,81,82,83,84,85,
			90,91,116
		),
		'gender' => array(
			2
		),
		'industry' => array(
			6,7,8,9,10,11,12,158,159,11215,11243
		)
	),
	'P_M_Man'=> array(
		'column' => array(
			3,5,
			20,21,22,23,
			30,31,32,33,34,35,36,37,38,40,
			93,94,95,96,97,
			50,51,52,53,54,55,56,57,
			70,71,72,73,113,114,115,131,132,
			80,81,82,83,84,85,
			90,91,116
		),
		'gender' => array(
			1
		),
		'industry' => array(
			6,7,8,9,10,11,12,158,159,11215,11243
		)
	),
);

//搜索排序字段
$popSearchSortFields = [];

//搜索排序方式
$popSearchSorts = [ 'DESC' , 'ASC' ];

//普通用户不能访问的栏目id
$aArticleColumnIds = [90, 30, 31, 32, 33, 34, 35, 36, 37, 38, 40, 20, 21, 22, 23, 3, 70, 71, 72, 73, 113, 114, 115, 116, 131, 132];

//所有栏目介绍
$aColumnAbstract = [
	1 => '能聚焦，快反应（大数据支撑下的趋势前瞻观点，层层深入的趋势预测-企划设计-时时提炼的趋势快反应）让您更自信的进行风格决策，稳定产品研发步调的同时，兼顾迅速应对市场的变化。' , //未来趋势
	2 => '有依据；够全面——理性的数据分析，感性的流行变化推荐。了解全球潮流动向、全面完整解析市场变化、重点观察中国潮流趋势动态' , //潮流解析
	3 => '全球30多个国家和地区，2000多个先锋品牌，秀场动态时时同步跟踪更新，并根据特色款式进行款式提炼推荐和深度品牌解析与城市维度单品提炼和综合分析' , //T台发布
	4 => '零时差、无国界，实时更新最新款式资讯。全线索、多渠道，全面汇聚市场流行单品。' , //款式库
	5 => '提供全球4万多品牌时时产品变化和款式资料，同时提供重点地区重点品牌全维度多视角、货品结构与设计特点深入解析，关注品牌前世今生动态变化与原因分析' , //品牌库
	6 => '从趋势书籍到单品书籍，到快反系列，POP独家的书籍体系POP服装趋势首创资讯趋势O2O' , //手稿合辑
	7 => '全球权威面辅料、原材料展会，精选下一季最新面料工艺，推荐优质面辅料供应商。' , //设计素材
    8  => '一切与时尚有关的，影响时尚发展方向的灵感源头，涵盖灵感事件，艺术家作品，设计作品，生活趋势等。',//灵感源
	20 => '1次/2周，提前3个月POP独家发布流行趋势快反应胶囊系列。快速应对市场变化又符合开发与生产周期' , //关注 快反应(pop独家）
	21 => '提早12个月企划设计指导及单品趋势提案；POP特有1次/季度提前6个月发布核心重点单品组货搭配' , //企划 企划设计
	22 => '2次/年，提前16-17个月发布主未来趋势元素预测，内容涵盖色彩、图案图像、细节工艺、面/辅料、廓形剪裁。POP特有提前销售期6个月趋势点补充报告，聚集锁定未来趋势 ' , //预测 趋势预测
	23 => '提早18个月的主题风格展望；聚焦未来热门新兴风格所在。涵盖多领域、全性别全行业的风格主题呈现。' , //前瞻
	30 => '全球重点秀场关键趋势点提炼与解析。深度解读时装周，结合数据权威提供最前沿先锋的T台潮流情报。' , //T台分析
	31 => '全球知名原料/成衣专业展会趋势重点提炼及推荐，协助客户预知下一季度的专业走向。' , //展会分析
	32 => '国际大牌及showroom订货会分析，早于市场发布6个月，提供从品牌货品结构到单品款式、流行元素全面完整解读。' , //订货会分析
	33 => '批发市场作为流行点最活跃的渠道，定期进行流行点的采集与提炼，可以结合自身定位进行有选择性的参考。' , //批发市场分析
	34 => '全球重点线上销售渠道市场调研；结合互联网销售特性大数据指导下的流行重点，时时跟踪和单品特色点提炼，并季度性进行总结。' , //品牌在线分析
	35 => '全球重点地区城市零售市场调研；全面解析市场当季流行重点，为下季度产品计划提供市场依据与支持。POP独有标杆品牌分析系统客观解读品牌变化。' , //零售市场分析
	36 => '最抢先、可参考的各地区各大牌每季形象，包含橱窗、挂杆、氛围等等' , //店铺形象
	37 => '秀场内外、素人及搭配达人的街拍展示及延展。根据搭配理念和特色单品进行款式组合与类似单品素材推荐。' , //街拍分析
	38 => '聚焦潮流KOL，解析着重搭配及品牌选择，进行分析单品及品牌推荐。前沿人物的动态捕捉与解析给予客户消费者最关注的趋势标杆。' , //潮流领袖
	40 => '行业最新动态及信息，重点事件的关注' , //行业报道
	50 => '全球秀场动态时时同步跟踪更新，并根据特色款式进行款式提炼推荐，并且细分到品名。' , //秀场提炼
	51 => '全球知名原料/成衣专业展会，及时发布展会信息' , //展会图库
	52 => '早于市场发布8个月的国际大牌showroom订货会款式及内部订货会素材信息。' , //订货会
	53 => '批发市场作为流行点最活跃的渠道，定期进行流行点的采集与提炼。' , //批发市场
	54 => '全球品牌实体店、批发市场、展会专人拍摄，最新款式、面料、设计点等提炼采集。' , //全球实拍
	55 => '精选欧美地区奢侈品牌款式和其他地区精选高知名度的时尚名牌，以及大牌的预售款。' , //名牌精选
	56 => '捕捉街头时尚，凝聚时下街头时尚达人的搭配理念及关键单品，预测时尚趋势和搭配方式' , //街拍图库
	57 => '聚焦潮流明星，达人等KOL，捕捉前沿人物的风格，热爱品牌 和潮流搭配方式及单品', //潮流领袖 款式
	70 => '栏目提供近160多个种类，来自全球知名且市场价值昂贵的高端趋势企划类手稿和实用的款式书刊以及POP内部高端趋势刊，低廉的价格，高端的尊享。' , //趋势手稿
	71 => '品牌产品系列展示，全球一线品牌到市场热门品牌到新锐设计师品牌，时尚大片品牌灵魂与当季搭配展示。' , //Lookbook
	72 => '栏目提供市场上各类流行款式集合及POP特推会员刊，让你快速了解当下流行款式，及时应对款式研发设计。' , //单品合辑
	73 => '图案及款式手稿矢量文件书籍' , //矢量文件
	80 => '展示款式廓形走向，研究了解款式结构变化。包含趋势经典款式、潮流热度款式、趋势概念款式。' , //款式模板
	81 => '分为剪裁细节趋势（款式细节趋势报告及时下潮流解析细节提炼分析），款式细节提炼特色款式特色亮点细节部位，让设计聚焦剪裁细节。' , //款式细节
	82 => '全面深入的提供海量图案设计素材以及原创画稿，助力设计开发！' , //图案素材
	83 => '暂无描述' , //面料商城
	84 => '从帽子、袜子、围巾、领带、手套、首饰等六大单品从各个品牌新款发布会、商场、街头、杂志等等方面展示最新潮流款式图片，并预测下一季可能流行的新款。' , //服饰品
	85 => '橱窗及店铺形象、秀场氛围等服装相关领域各项信息' , //店铺陈列
	90 => '一切与时尚有关的，影响时尚发展方向的灵感源头，涵盖灵感事件，艺术家作品，设计作品，生活趋势等。' , //灵感报告
	91 => '一切与时尚有关的，影响时尚发展方向的灵感源头，涵盖灵感事件，艺术家作品，设计作品，生活趋势等。' , //灵感图库
	116 => ' POP独有的一年2季的大主题视频，涵盖主题精髓，代表LOOK等。' , //灵感视频
	112 => '精选中、深度报告解析及权威趋势观点，侧重数据分析，验证未来趋势在市场中的实际体现，解读最新流行动向。' , // 潮流解析/权威数据
	113 => '对发布会进行产品研究，包括对T台的主题、色彩、廓形、工艺等视角的深度分析，T台品牌深度分析，以及纽约、伦敦、米兰、巴黎的T台城市分析。' , // T台系列
	114 => '热门品牌解析及新锐品牌推荐，包括标杆品牌解析、订货会品牌解析、新锐设计师品牌解析' , // 品牌系列
	115 => '应对市场变化，快速总结，POP独家的APM趋势快反系列' , // 快反应系列
	117 => '全球权威面辅料、原材料展会，精选下一季最新面料工艺，推荐优质面辅料供应商。' , // 面料图库
	120 => '对热门品牌花型进行矢量处理，帮助客户对图案基型进行设计变化' , // 大牌花型
	121 => '一键云打印，所见即所得。优质供应商最新海量数码花型展示，对应店铺多品种底布挑选，直接下单打印。' , // 数码云打印
	122 => '集合新锐设计师品牌款式、精炼款式提倡原创设计。',//设计师品牌
	123 => '每日更新海量欧美，日韩，国内等品牌在线款式，紧跟一年四季的款式产品。',//款式流行
	124 => '精选最新工艺灵感照片和热门工艺实物照片，包含辅料市场实拍和T台大牌高清细节图。',//图案工艺
    125 => '2次/年色彩趋势企划，提早18个月的色彩趋势预测。基于流行色彩大数据分析，聚焦未来色彩的实际应用及应对市场的色彩快反应。涵盖全性别、全行业、多角度的全面色彩指导',
    126 => '2次/年图案趋势企划，为设计师提供图案的灵感和设计方向。紧跟当下热门元素，实时提炼、预测、延伸创作，为设计师提供专业的图案趋势指导和大量原创矢量图库',
    127 => '2次/年面料趋势企划，内容包含面料色彩、面料花型等趋势热点。为企业和设计师提供最新最流行的面料灵感方向以及新一季面料趋势企划做设计引导。为面料商开发新品面料做方向指导',
    128 => '预测未来工艺趋势方向，并实时提炼最新、最热工艺趋势预测，内容涵盖细节工艺、辅料工艺、面料工艺以及图案工艺。为设计师提供最权威、最快速的工艺重点',
    129 => '聚焦未来廓形最新变化趋势。通过数据对比分析，全面总结提炼关键单品种的廓形应用，为设计师在产品开发中提供廓形趋势点预测',
    130 => '栏目提供900余种日本、韩国、欧美等知名杂志在线阅读及下载，省时更省钱的看遍全球所有专业杂志。',// 杂志前线杂志前线杂志前线
    131 => '品牌产品系列展示，全球一线品牌到市场热门品牌到新锐设计师品牌，时尚大片品牌灵魂与当季搭配展示。',// 订货会合辑
    132 => '爆款数据作为流行点最活跃的渠道，定期进行流行点的采集与提炼，可以结合自身定位进行有选择性的参考。' , //爆款数据
];

// 分类软件栏目 中英文
$sColumnEnToCn = [
	'perform'		=> '秀场提炼',
    'marketphoto'	=> '商场爆款',
    'ordermeeting'	=> '订货会',
    'stylegraphic'	=> '款式流行',
    'street'		=> '街拍图库',
    'tideleader'	=> '潮流领袖',
    'styledetail'	=> '款式细节',
    'shopset'		=> '橱窗陈列',
    'accessories'	=> '服饰品'
];

