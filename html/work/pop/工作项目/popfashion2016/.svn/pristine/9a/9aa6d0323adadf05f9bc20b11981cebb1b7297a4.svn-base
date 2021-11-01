var oFeedback = {
	run: function(){
		var gender = $.cookie('gender');
		var industry = $.cookie('industry');
		//首页行业筛选
		$('.js-area-box li').on('click',function(){
				var thisli = $(this);
				var url = '/mutual/';
				var id= thisli.children("a").data("id");
				var key =thisli.children("a").data("key");
				url += key+'_'+id+'/';
				window.location.href = url;
		});
		

		// 行业筛选
		$('.keycho').on('mouseenter mouseleave','.keyWord_downlist',function(e){
			var $box = $(this);
			var $downlist = $box.find('.downlist');
			if (e.type == 'mouseenter'){
				$box.addClass('Zindex4');        	
				$downlist.show();
			} else if (e.type == 'mouseleave') {
				$box.removeClass('Zindex4');         	
				$downlist.hide();
			}
	        
		});
	}
};
$(function(){
	oFeedback.run();
	var page =2;
	$(".js-click-load").on('click',function(){
		var dPushTime = $(".js-folder-date").val();
		var sOrderName= $(".js-folder-title").val();
		var sOrderLink = $(".js-click-link").attr('href');
		var sOrderPwd = $(".js-link-code").find('span').val();
		pop_fashion_global.fn.subAjax({
			url:'/ajax/orderpackagezip/?page='+page+'',
			data:{dPushTime: dPushTime,sOrderName: sOrderName,sOrderLink: sOrderLink,sOrderPwd: sOrderPwd},
			successFunc:function(data){
				if(data && data["data"].length >0){
					var arr = data["data"], _html='',data_box = $(".data-list-box").find('ul');
					var nowDate = (new Date()).getTime();
					var oldDate = nowDate-30*24*3600*1000;
					for(var i=0,len=arr.length;i<len;i++){						
						var dPushTime=arr[i]["dPushTime"]?arr[i]["dPushTime"]:"";
						var sOrderName=arr[i]["sOrderName"]?arr[i]["sOrderName"]:"";
						var sOrderLink=arr[i]["sOrderLink"]?arr[i]["sOrderLink"]:"";
						var sOrderPwd=arr[i]["sOrderPwd"]?arr[i]["sOrderPwd"]:"";
						dPushTime=dPushTime.substr(0,10);
						dPushTime=dPushTime.replace(/-/g,"/");
						var tdate=(new Date(dPushTime)).getTime();
						if(tdate < oldDate){
							_html+='<li class="invalid-folder">';
						}else{
							_html+='<li>';
						}
						_html+='<div class="data_bg">';
						_html+='<span class="folder-img"></span>';
						_html+='<span class="folder-date js-folder-date">'+dPushTime+'</span>';
						_html+='</div>';
						_html+='<p class="folder-title js-folder-title">'+sOrderName+'</p>';
						if(tdate < oldDate){
							_html+='<a href="javascript:void(0);" title="点击下载" class="click-down-img js-click-link">点击下载</a>';
						}else{
							_html+='<a href="'+sOrderLink+'" title="点击下载" target="_blank" class="click-down-img js-click-link">点击下载</a>';
						}						
						_html+='<p class="link-code js-link-code">提取码：<span>'+sOrderPwd+'</span></p>';
						_html+='</li>';
					}					
					data_box.append(_html);
					if(page >= data['pageCount']){
						$(".js-click-load").hide();
						return;
					}
					page++;
				}		
			},
			errorFunc:null
		});
	});

	
});