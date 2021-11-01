$(function(){
	
	var def={
		_url:'/ajax/',			//请求路径
		has_list:false		//是否有列表数据了
	}
	
    
    pop_fashion_global.fn.subAjaxGet({
		url: def._url+"getProbationItems",
        isError:false,
        successFunc: setList,
        errorFunc: function(){
        	
        }
	})
	//写入dom
	function setList(data){
		if(data){
			var arr=data['data']||[];
	 		var identity=arr["identity"]||{},category1=arr["category"]["1"]||{},category2=arr["category"]["2"]||{},_html1='',_html2='',_html3='';
	 		for(i in identity){
	 			_html1+='<li data-id="'+i+'">'+identity[i].name+'</li>';
	 		}
	 		
	 		for(j in category1){
	 			_html2+='<li data-id="'+j+'">'+category1[j]+'</li>';
	 		}
	 		
	 		for(k in category2){
	 			_html3+='<li data-id="'+k+'">'+category2[k]+'</li>';
	 		}
	 		
	 		$('.js-card-list').html(_html1);
	 		$('.js-label-list1').html(_html2);
	 		$('.js-label-list2').html(_html3);
		 	
		}
	}
	
	
	//列表提交
	function listCommit(){
		var tel_account='',sms_code='',cellphonePatt = /^1\d{10}$/;;
		if($('.js-sms-verify').length>=1){		//如果有短信验证码
			tel_account = $.trim($('.js-phone-val').val());
//	        var img_code = $.trim($('.js-graphical').val());
	        sms_code = $.trim($('.js-sms-val').val());
	        if (tel_account == '' || !cellphonePatt.test(tel_account)) {
	            layer.alert("手机号格式不正确，必须是手机号码");
	            return false;
	        }
	        if( sms_code == '' ) {
	            layer.alert("短信验证码不能为空");
	            return false;
	        }
		}
		
		//前面列表是否有选择
		var is_card_list=($('.js-card-list>.this-item').length<=0)?true:false;
		var label_list1=($('.js-label-list1>.this-item').length<=0)?true:false;
		var label_list2=($('.js-label-list2>.this-item').length<=0)?true:false;
		
		if(is_card_list==true){
			layer.alert('请选择您的身份！');
		}else if(label_list1==true && label_list2==true){
			layer.alert('请选择您感兴趣的类别！');
		}else if(label_list1==true || label_list2==true){
//			oCommon.noPower( '','请分别选择您感兴趣的类别！' );
			layer.alert('请分别选择您感兴趣的类别！');
		}else{
			var _identity=$('.js-card-list>.this-item').data('id');
			var _company=$('.js-company').val()||'';
			var _obj={'1':[],'2':[]};
			
			$('.js-label-list1>.this-item').each(function(n,item){
				var this_id=$(this).data('id');
			  	_obj['1'].push(this_id);
			});
			$('.js-label-list2>.this-item').each(function(n,item){
				var this_id=$(this).data('id');
			  	_obj['2'].push(this_id);
			});
			
			//请求参数
			var prom={
				identity:_identity,
				company:_company,
				category:_obj,
				mobile : tel_account,	//电话号码
				sms_code:sms_code		//短信验证码
			};
			
			$('.js-receive-trial').off('click',listCommit);	//提交中
			$.ajax({
	            type: "GET",
	            url: def._url+"obtainProbation",
	            timeout: 20000,
	            data:prom,
	            dataType: "json",
	            success: function(data){
	            	var _code=data.code||0;
	            	if(_code==0){
	            		window.location.href='/probation/completed/';
	            	}else{
//	            		oCommon.noPower( '',data['message'] );
						layer.alert(data['message']);
	            	}
	            	$('.js-receive-trial').on('click',listCommit);
	            },
	            error: function(){
	            	$('.js-receive-trial').on('click',listCommit);
	            }
	        });
			
		}
	}
	
	//获取短信验证码
	function getSms(){
		var cellphonePatt = /^1\d{10}$/;
        var tel_account = $.trim($('.js-phone-val').val());
        var img_code = $.trim($('.js-graphical').val());
		
        if (tel_account == '' || !cellphonePatt.test(tel_account)) {
            layer.alert("个人手机号格式不正确，必须是手机号码");
            return false;
        }
        if( img_code != $.cookie('checkcode') ) {
            layer.alert("图形验证码不正确");
            return false;
        }
        
        $('.js-get-sms').off('click',getSms);	//取消绑定
        $.ajax({
            url: '/member/sendMessage/?'+Math.random(),
            type: 'post',
            dataType: 'json',
            async: false,
            data: {
            	"freeCellphone" :tel_account,
            	"act":"reg",
            	"check_code":img_code
            },
            success:function(data){
            	if (data.status == 1) {
	            	var count=60;
	            	$('.js-get-sms').text("60秒后可重新获取");
	            	var countdown = setInterval(function(){
	            		$('.js-get-sms').text('还有'+count+'秒');
	                    if (count == 0) {
	                    	clearInterval(countdown);
	                    	$('.js-get-sms').text('获取验证码');
	                    	$('.js-get-sms').on('click',getSms);
	                    }
	                    count--;
	            	}, 1000);
            	}else{
            		$('.js-get-sms').on('click',getSms);
            		layer.alert((data['info']||''));
            	}
            },
            error:function(){
            	$('.js-get-sms').on('click',getSms);
            }
            
        })
        
	}
	
	
	/*---------------------事件绑定--------------------*/
	
	/*注册成功tab1 页面翻页*/
	$('.js-on-trial').on('click',function(){
		$(this).fadeOut(300);
		$('.js-register-suc').animate({
			'padding':'55px 0 14px'
		},300,function(){
			$('.js-register-suc').addClass('register-center');
			$('.js-trial-section').slideDown(1500);
		})
		
	})
	
	/*填表信息列表选择*/
	$('.js-card-list').on('click','li',function(){
		$(this).toggleClass('this-item').siblings('.this-item').removeClass('this-item');
		var _id=$(this).data('id');
		if(_id==6){
			$('.js-company').addClass('bg-f9').val('').attr('disabled','disabled');
		}else{
			if($('.js-card-list>.this-item').length>0){
				$('.js-company').removeClass('bg-f9').removeAttr("disabled");
			}else{
				$('.js-company').addClass('bg-f9').attr('disabled','disabled');
			}
		}
	})
	
	//input输入功能控制
	$('.js-label-list1,.js-label-list2').on('click','li',function(){
		$(this).toggleClass('this-item');
	})
	
	//试用列表页提交
	$('.js-receive-trial').on('click',listCommit);
	
	$('.js-get-sms').on('click',getSms);

	
})
