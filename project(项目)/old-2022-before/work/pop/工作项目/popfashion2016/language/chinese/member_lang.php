<?php
/*
*@说明: 中文语言--用户相关
*@Date: 2016-09-07
*@注意: 键不能有重复
*/
	$lang=[

		//=======controller中=======

		//子账号提示
		'member_sonAccount_1'=>'对不起，只有子账号能访问！',

		//职业
		'member_job_1' => '服装设计师',
		'member_job_2' => '服装生产销售人员',
		'member_job_3' => '模特',
		'member_job_4' => '从事摄影艺术者',
		'member_job_5' => '时尚爱好者',
		'member_job_6' => '其它',

		//涉及领域
		'member_sphere_man'=>'男装',
		'member_sphere_woman'=>'女装',
		'member_sphere_child'=>'童装',
		'member_sphere_baby'=>'婴儿装',
		'member_sphere_shoeAndHat'=>'鞋帽',
		'member_sphere_decoration'=>'饰品',
		'member_sphere_other'=>'其他',

		//修改主账号、子账号
		'member_modify_other'=>'其他',
		'member_modify_fail'=>'很抱歉，修改失败！',
		'member_modify_email_error'=>'邮箱格式错误！',
		//--修改密码
		'member_modify_pwd_error1'=>'密码格式不正确! 6-20位字符，可由数字、字母、特殊字符组成！',
		'member_modify_pwd_error2'=>'旧密码不正确！',
		'member_modify_pwd_error3'=>'对不起，该密码不符合系统要求，请尝试设置其它密码！',
		'member_modify_pwd_error4'=>'两次密码输入不一致！',
		'member_modify_pwd_error5'=>'用户未登录，请先登录！',

		//短信或语音或图片--验证码错误
		'member_verificationCode_error'=>'验证码错误',

		//绑定手机
		'member_bind_phone_1'=>'绑定失败，请重试！',
		'member_bind_phone_2'=>'请输入正确的手机号码',
		//验证码错误使用 member_verificationCode_error
		'member_bind_phone_4'=>'您未登录，请先登录',
		'member_bind_phone_5'=>'对不起！您尚未绑定手机，无法进行此操作。',
		'member_bind_phone_6'=>'短信发送成功!',
		'member_bind_phone_7'=>'短信发送失败,请联系客服。',
		'member_bind_phone_8'=>'对不起！一小时之内您只能获取一次验证码。',

		//关联账号
		'member_Associate_power'=>'对不起，您的操作权限不够！',
		//--添加子账号
		//验证码错误使用 member_verificationCode_error
		'member_addChild_lack'=>'个人账号数量已满',
		'member_addChild_success'=>'添加成功',
		'member_addChild_fail'=>'添加失败',
		'member_addChild_pwdError1'=>'密码格式不正确',
		'member_addChild_pwdError2'=>'对不起，该密码不符合系统要求，请尝试设置其它密码',
		'member_addChild_sonAccount_error'=>'请填写需要添加的个人专属账号',
		'member_addChild_sendCode_success'=>'验证码已发送',
		//--积分分配
		//验证码错误使用 member_verificationCode_error
		'member_allot_integral_lack'=>'您的积分不够',
		'member_allot_integral_success'=>'分配成功',
		'member_allot_integral_fail'=>'分配失败',
		//--解除关联
		//验证码错误使用 member_verificationCode_error
		'member_unAssociate_success'=>'解除成功',
		'member_unAssociate_fail'=>'解除失败',

		//重置密码
		'member_reset_pwd_infoError'=>'信息错误',
		'member_reset_pwd_success'=>'重置成功',
		'member_reset_pwd_fail'=>'重置失败',

		//分享操作
		'member_share_fail'=>'分享操作失败！',

		//上传头像
		'member_uploadAvatar_fail'=>'上传失败',

		//=======model中=======
		//用户登录提示部分
		'member_login_1'=>'用户名不能为空',
		'member_login_2'=>'密码不能为空',
		'member_login_3'=>'验证码不能为空',
		//验证码错误使用 member_verificationCode_error
		'member_login_5'=>'用户名或密码错误',
		'member_login_6'=>'您的账号有异常，请联系客服热线 4008-210-662',
		'member_login_7'=>'您绑定了客户端，请使用客户端登陆',
		'member_login_8'=>'对不起，您的账号尚未在此电脑上绑定',
		'member_login_9'=>'您绑定了固定IP登录，请在绑定IP内登录',
		'member_login_10'=>'您的子账号已经解绑',

		//注册提示
		'member_reg_1'=>'注册失败...',

		//用户验证提示
		'member_verify_1'=>'手机号码不能为空',
		'member_verify_2'=>'请输入真实手机号码',
		'member_verify_3'=>'图片验证码错误或者为空',
		'member_verify_4'=>'您的访问频率过高, 请您稍后再试!',
		'member_verify_5'=>'同手机号不可重复注册账号',
		'member_verify_6'=>'秒后再获取',//$N秒后再获取
		'member_verify_7'=>'发送失败',//语音或短信发送失败
		'member_verify_8'=>'手机号错误！',
		'member_verify_9'=>'请输入正确的手机号',//与 member_reg_2 雷同
		'member_verify_10'=>'个人专属账号已存在',
		'member_verify_11'=>'请在{$codeExpireTime}秒后重新获取',//{$codeExpireTime}为时间
		'member_verify_12'=>'信息发送失败，请重新发送！',
		'member_verify_13'=>'验证码已过期',
		'member_verify_14'=>'解绑失败...',
		'member_verify_15'=>'短信验证码错误！',

		//订单部分
		'member_order_1'=>'登陆信息异常！',
		'member_order_2'=>'请选择正确的支付方式',
		'member_order_3'=>'支付金额有误！',
		'member_order_0'=>'订单生成失败！',

		//自动登录
		'member_autoLogin_1'=>'该连接已失效，请重新登录,如有疑问请联系客服人员。',
		'member_autoLogin_2'=>'账号被删除',
		'member_autoLogin_3'=>'您绑定了客户端，请使用客户端登陆',
		'member_autoLogin_4'=>'IP地址非法',
		'member_autoLogin_5'=>'登陆失败，请重新登陆',

		//用户反馈
		'member_feedback_success'=>'反馈成功',
		'member_feedback_fail'=>'反馈失败',
	];
?>