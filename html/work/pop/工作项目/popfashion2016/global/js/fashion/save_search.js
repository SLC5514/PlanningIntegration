var pop_fashion_style_page={
		is_global_page:false,     //是否是全球实拍
		is_load_list:false,        //是否加载保存搜索列表
		type:0,                   //默认登录弹窗
		top_id:"" ,					//置顶ID
		delete_id:"",				//删除ID
		count:0,                    //列表数量
		layer:$(".js-save-search-layer"),
		is_delete:false
	};
$(function(){	
	// 引导
	var f_ele1= $('.js-shadow1,.js-style-guide1');
	var ncookie=getCookie("pop_fashion_guide") || "";
	if(ncookie==""){
		f_ele1.show();
	}else{
		var arr=ncookie.split("-");
		if(arr[1]!=2){
			if(pop_fashion_style_page.is_global_page==true){
				$(".js-shadow1").show();
				$(".js-style-guide2").show();
			}
		}
	}
	f_ele1.on('click',function(){
		setCookie("pop_fashion_guide","1");
		if(pop_fashion_style_page.is_global_page==true){
			$(".js-style-guide1").hide();
			$(".js-style-guide2").show();
		}else{
			f_ele1.hide();
		}
	});
	$(".js-style-guide2").on('click',function(){
		setCookie("pop_fashion_guide","1-2");
		$(this).hide();
		$(".js-shadow1").hide();
	});
	//存储cookie
	function setCookie(key_name,val){
		$.cookie(key_name,val,{expires:365, path:'/', domain:'.pop-fashion.com'});//一年
	};
	function getCookie(key_name){
		return $.cookie(key_name);
	}

	//type 0：登录 1：删除 2：置顶 3:保存成功 4：保存十条 5：开通子账号 6：普通用户 7:未选择搜索 8：试用账号
	// 是否登录	
	var random = Math.random();
	var shadow_layer=$(".js-shadow-save,.js-save-search-layer");
	// 保存搜索按钮click
	$(".js-save-search-btn").on("click",function(){
		savaSearch($(this));
	});
	// 保存搜索条件
	function savaSearch(obj){
		var save_obj={};
		save_obj.params=$("#link").data('param');
		save_obj.key=$("#link").data('search');
		save_obj.colId=$("#link").data('col');		
		pop_fashion_global.fn.subAjax({
			url:'/styleajax/ScreeningCondition/?'+random,
			data:save_obj,
			message:'msg',
			ctp:'application/x-www-form-urlencoded',			
			successFunc:function(data){
				pop_fashion_style_page.is_load_list=false;
				pop_fashion_style_page.type=3;
				var val='搜索保存成功！';
				pop_fashion_style_page.layer.find(".save-txt").html(val);
				pop_fashion_style_page.layer.find(".js-cancel-btn").text("确定");
				pop_fashion_style_page.layer.find(".login-btn").hide();					
				pop_fashion_style_page.layer.find(".reg-btn").hide();
				shadow_layer.show();
				pop_fashion_style_page.count++;
				$(".js-save-search-look>a").find('span').text(pop_fashion_style_page.count);
			},
			errorFunc:function(data){
				errorCode(data);
			}
		});
	}
	// 保存按钮code
	function errorCode(data){
		if(data.code==1){
			pop_fashion_style_page.type=0;
			pop_fashion_style_page.layer.find(".login-btn").addClass("loginLayer");
			shadow_layer.show();
		}else if(data.code==5){    //普通用户记录>1
			pop_fashion_style_page.type=6;
			var val='你当前为普通账号，支持保存一条常用搜索，想保存更多？<br/>了解下会员特权吧！';
			pop_fashion_style_page.layer.find(".save-txt").html(val);
			pop_fashion_style_page.layer.find(".js-cancel-btn").text("取消");
			pop_fashion_style_page.layer.find(".reg-btn").hide();
			pop_fashion_style_page.layer.find(".login-btn").show().text('了解会员');
			shadow_layer.show();
		}else if(data.code==10){      //已保存
			pop_fashion_style_page.type=2;			
			pop_fashion_style_page.top_id=data.id;
			var val='您已保存过该条件，是否需要将该条件移至首行？';
			pop_fashion_style_page.layer.find(".save-txt").html(val);
			pop_fashion_style_page.layer.find(".js-cancel-btn").text("取消");
			pop_fashion_style_page.layer.find(".login-btn").text("确定").show();					
			pop_fashion_style_page.layer.find(".reg-btn").hide();
			shadow_layer.show();
		}else if(data.code==2){				//已保存十条
			pop_fashion_style_page.type=4;				
			var val='最多保存十条哦，管理下常用的筛选吧';
			pop_fashion_style_page.layer.find(".save-txt").html(val);
			pop_fashion_style_page.layer.find(".js-cancel-btn").text("好的");
			pop_fashion_style_page.layer.find(".reg-btn").hide();
			pop_fashion_style_page.layer.find(".login-btn").hide();
			shadow_layer.show();
		}else if(data.code==4){
			pop_fashion_style_page.type=5;
			var val='你当前为会员主账号，支持保存一条常用搜索，想保存更多？<br/>快速开通子账号';
			pop_fashion_style_page.layer.find(".save-txt").html(val);
			pop_fashion_style_page.layer.find(".js-cancel-btn").text("取消");
			pop_fashion_style_page.layer.find(".reg-btn").hide();
			pop_fashion_style_page.layer.find(".login-btn").show().text('开通子账号');
			shadow_layer.show();
		}else if(data.code==3){
			pop_fashion_style_page.type=8;
			var val='你当前为试用账号，支持保存一条常用搜索，想保存更多？<br/>了解下会员特权吧！';
			pop_fashion_style_page.layer.find(".save-txt").html(val);
			pop_fashion_style_page.layer.find(".reg-btn").hide();
			pop_fashion_style_page.layer.find(".login-btn").show().text('了解会员');
			shadow_layer.show();
		}else if(data.code==7){
			pop_fashion_style_page.type=7;    //未选择搜索条件
			var val='请先选择需要保存的搜索条件！';
			pop_fashion_style_page.layer.find(".save-txt").html(val);
			pop_fashion_style_page.layer.find(".js-cancel-btn").text("确定");
			pop_fashion_style_page.layer.find(".reg-btn").hide();
			pop_fashion_style_page.layer.find(".login-btn").hide();
			shadow_layer.show();
		}
	}
	getList(1);
	// 获取保存搜索列表
	function getList(type){
		if(pop_fashion_style_page.is_load_list==false){
			pop_fashion_style_page.is_load_list=true;
			pop_fashion_global.fn.subAjax({
				url:'/styleajax/getScreeningList/?'+random,
				message:'msg',
				ctp:'application/x-www-form-urlencoded',
				successFunc:function(data){
					getListSuccess(data,type);					
				},
				errorFunc:function(data){
					if(data.code==9){
						if(type!=1){
							$(".js-guide-save").show();
						}
					}else{
						pop_fashion_style_page.is_load_list=false;
					}
					$(".js-save-search-list ul").html("");						
					$(".js-save-search-look>a").find('span').text(0);	
					pop_fashion_style_page.count=0;	
				}
			});
		}else{
			if(pop_fashion_style_page.count==0){
				$(".js-guide-save").show();
			}else{				
				$(".js-save-search-list").show();
			}	
		}	
	}
	// 获取保存列表成功FUNCTION
	function getListSuccess(data,type){
		var arr=data.data.list;
		var _html="",tar=$(".js-save-search-list ul");
		pop_fashion_style_page.count=data.data["count"]?data.data["count"]:0;
		for(var i=0,len=arr.length;i<len;i++){
			var arr_span=arr[i].attr;
			var colName=arr[i]["colName"]?arr[i]["colName"]:"";
			var updateTime=arr[i]["updateTime"]?arr[i]["updateTime"]:"";
			var sScreenUrl=arr[i]["sScreenUrl"]?arr[i]["sScreenUrl"]:"";
			var id=arr[i]["id"]?arr[i]["id"]:"";
			_html+='<li data-id="'+id+'">';
			_html+='<a href="'+sScreenUrl+'" class="clearfix">';
			_html+='<span class="search-column fl">'+colName+'</span>'
			_html+='<em class="search-column-icon fl"></em>';
			_html+='<div class="search-option fl">';
			for(var x in arr_span){
				var s_key=arr_span[x];
				_html+='<span>'+s_key+'</span>';  //循环span
			}
			_html+='</div>';
			_html+='<div class="search-li-right fr">';
			_html+='<span class="save-time">'+updateTime+'</span>';  //更新时间						
			_html+='</div>';
			_html+='</a>';
			_html+='<button class="delete-save-btn js-delate-btn">删除</button>';
			_html+='</li>';	
		}
		tar.html(_html);
		if(type!=1){
			if(pop_fashion_style_page.count==0){
				$(".js-guide-save").show();
			}else{				
				$(".js-save-search-list").show();
			}
		}
		$(".js-save-search-look>a").find('span').text(pop_fashion_style_page.count);
	}
	var timer=null;
	// hover出现保存搜索下拉
	$(".js-save-search-look").on("mouseenter mouseleave",function(e){
		if(e.type=="mouseenter"){
			$(".js-save-search-look>a").addClass("success-hover");
			getList();
		}else{
            $(".js-save-search-look>a").removeClass("success-hover");
            $(".js-guide-save").hide();
            $(".js-save-search-list").hide();
		}
	});
	$(".js-save-search-list").on('mouseenter',function(){
		$(".js-save-search-look>a").addClass("success-hover");
		$(".js-guide-save").show();
		$(".js-save-search-list").show();
	}).on('mouseleave',function(){
        $(".js-save-search-look>a").removeClass("success-hover");
        $(".js-guide-save").hide();
        $(".js-save-search-list").hide();
	});

	// 删除筛选条件
	function deleteList(){
		if(pop_fashion_style_page.is_delete==false){
			pop_fashion_style_page.is_delete=true;
			pop_fashion_global.fn.subAjax({
				url:'/styleajax/delScreeningList/?'+random,
				data:{id:pop_fashion_style_page.delete_id},
				ctp:'application/x-www-form-urlencoded',
				message:'msg',
				successFunc:function(data){
					$(".js-save-search-list>ul").find("li.js-now").remove();
					pop_fashion_style_page.count--;
					pop_fashion_style_page.count=pop_fashion_style_page.count<0?0:pop_fashion_style_page.count;
					$(".js-save-search-look>a").find('span').text(pop_fashion_style_page.count);	
					shadow_layer.hide();
					pop_fashion_style_page.is_delete=false;
				},
				errorFunc:function(){pop_fashion_style_page.is_delete=false;}
			});
		}
	}

	// 点击删除-弹层
	$("body").on('click','.js-delate-btn',function(){
		$(this).parent().addClass("js-now");	
		$(this).parent().siblings("li").removeClass("js-now");	
		pop_fashion_style_page.delete_id=$(this).parent().data("id");
		pop_fashion_style_page.type=1;  //删除成功
		var val='确认删除这条搜索条件？';
		pop_fashion_style_page.layer.find(".save-txt").html(val);
		pop_fashion_style_page.layer.find(".js-cancel-btn").text("取消");
		pop_fashion_style_page.layer.find(".reg-btn").hide();
		pop_fashion_style_page.layer.find(".login-btn").show().text("确定");
		shadow_layer.show();
	});

	// 弹层按钮事件-	
	$(".js-comfirm-btn").on('click',function(){
		var self=$(this);
		// 是否置顶
		if(pop_fashion_style_page.type==2){
			// 置顶成功
			pop_fashion_global.fn.subAjax({
				url:'/styleajax/listTop/?'+random,
				data:{id:pop_fashion_style_page.top_id},
				ctp:'application/x-www-form-urlencoded',
				successFunc:function(data){
					pop_fashion_style_page.is_load_list=false;
					$(".js-save-search-list ul").html("");				
					shadow_layer.hide();
				}
			});
		}else if(pop_fashion_style_page.type==1){
			// 删除成功self			
			deleteList();
		}else if(pop_fashion_style_page.type==6 || pop_fashion_style_page.type==8){
			window.location.href='/service/joinmember/';
		}else if(pop_fashion_style_page.type==5){
			window.location.href='/member/associate/';
		}
	});
	// 关闭弹层
	$(".js-cancel-btn").on('click',function(){
		shadow_layer.hide();
	});
});