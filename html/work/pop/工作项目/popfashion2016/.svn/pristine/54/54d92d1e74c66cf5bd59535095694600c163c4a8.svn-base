// 列表页共用对象
var pop_list_page_obj={
    page_size : 35,
    page: $("#J_GoPage").val()
};


// 批量管理变量
var delete_def={
	admin_btn:true,        //是否批量管理
	js_select:$(".js-select-checkbox,.js-select-bg"),
	js_delete:$(".js-delete-layer,.js-delete-section"),
	all_cur:false,         //是否全部选中
	delete_suc:false,
    per_cur:false,         //是否单张选中
    js_input:$(".js-input-packages-name,.js-delete-layer")
}

$(document).ready(function () {
	//创建工作台弹框
	$("#creatTab").on("click", function () {
		$("#tableName").val('');
		$("#tableStatus").val('');
		$("#workBenchId").val('');
		$(".pop_work_e,.shadow_black").show();
	});
	//弹框关闭
	$(".pop_work .closebtn").on("click", function () {
		$(this).closest(".pop_work").hide();
		$(".shadow_black").hide();
		$('.errTip').hide();
	});
	//弹框关闭
	$(".closebtn").on("click", function () {
		$(this).closest(".delete_pop").hide();
		$(".shadow_black").hide();
	});
	
	//创建、编辑工作台
	$(".pop_work .saveBtn").on("click", function () {
		var tableName = $.trim($("#tableName").val());
		var describe = $('#tableStatus').val();
        var workBenchId = $('#workBenchId').val();
        var reg_n = "^[\\w\\u4e00-\\u9fa5]+$";
		if (tableName == "")
		{            
			$(".pop_work .errTip").show();
		}
		else
		{
            if(!RegExp(reg_n, 'ig').test(tableName) || tableName.length > 20 ){
                alert('请输入不超过20字符的数字、字母、中文！');
                return false;
            }else{
                $(".pop_work .errTip").hide();
            }
			$.ajax({
				type: "POST",
				url: "/ajax/createworbenchuc/"+Math.random(),
				data: {tableName: tableName, describe: describe, workBenchId: workBenchId},
				datatype: "json",
				success: function (status) {
					$(".shadow_black,.pop_work").hide();
					if ( isNaN(status) )
					{
						$('.workBenchTips').html(status);
						$('.shadow_black,.pop_work_tips').show();
					}
					else
					{
						location.reload();
					}
				}
			})
		}
		
	});
	//删除工作台弹框
	$(".workList .delete_btn").each(function () {
		$(this).on('click', function () {
			var info = $(this).parents('li');
			$("#DeleteId").val(info.data('id'));
			$(".shadow_black,.delete_pop").show();
			return false;
		});
	});
	//删除工作台
	$("#workBenchDel").on('click', function () {
		var DeleteWorkBenchId = $("#DeleteId").val();
		if( DeleteWorkBenchId ){
			$.ajax({
				type: "POST",
				url: "/ajax/deleteworkbench/?"+Math.random(),
				data: {DeleteWorkBenchId: DeleteWorkBenchId},
				datatype: "json",
				success: function (status) {
					$(".shadow_black,.delete_pop").hide();
					if (status != '')
					{
						//alert(status);
					}
					location.reload();
				}
			})
		}	
	});
	/*
	 *编辑工作台弹框
	 * 工作台页面
	 */
	$(".workList .edit_btn").each(function () {
		$(this).on('click', function (ev) {
			$(".pop_work").find("h1").text("编辑工作台").end().find(".saveBtn").text("保存");
			$("#tableName").val('');
			$("#tableStatus").val('');
			$("#workBenchId").val('');
			var info = $(this).parents('li');
			$("#tableName").val(info.data('name'));
			$("#tableStatus").val(info.data('describe'));
			$("#workBenchId").val(info.data('id'));
			
			$(".shadow_black,.pop_work_e").show();
			return false;
		});
	});
	/*
	 *编辑工作台弹框
	 *flag 1:单张页面 
	 */
	$(".per_edit").each(function () {
		$(this).on('click', function (ev) {
			$(".pop_work").find("h2").text("编辑工作台").end().find(".saveBtn").text("保存");
			$("#tableName").val('');
			$("#tableStatus").val('');
			$("#workBenchId").val('');
			if ($(this).data('flag') == 1)
			{
				$("#tableName").val($(this).data('name'));
				$("#tableStatus").val($(this).data('describe'));
				$("#workBenchId").val($(this).data('id'));
			}
			$(".alertbox_bg,.pop_work_e,.shadow_black").show();
			return false;
		});
	});
    //工作台生成下载包跳转详情页
    var w_link = "";
    $(".js-upload-btn").on("click", function() {
        w_link = $(this).siblings("a").attr("href");
        $(".shadow_black, .js-download-package").show();
    });

    $(".js-download-package").on("click", "a", function() {
        location.href = w_link;
    });    
	
	//workbench单张下载
	$(".img_p .pic_zz1").on('click', function (ev) {
		stopPropagation(ev);
		var path = $(this).parents('a').data('path');
		var id = $(this).parents('a').data('id');
		var table = $(this).parents('a').data('t');
		var columnid = $(this).parents('a').data('col');
		var _rename = $(this).parents('a').data('rename')==undefined||$(this).parents('a').data('rename')==""?"":"?rename="+$(this).parents('a').data('rename');
		//下载量统计
		downCount( table,id,columnid );
		var path = path+_rename;
		window.location.href="/download/dlsingle/?dl_link=" + encodeURIComponent(encodeURIComponent(path)) + '&' + Math.random();
		return false;
	});
	//灵感图库单张下载
	$(".p_icon1").on('click', function (ev) {
		stopPropagation(ev);
		var path = $(this).parents('a').data('path');
		var id = $(this).parents('a').data('id');
		var table = $(this).parents('a').data('t');
		var columnid = $(this).parents('a').data('col');
		// alert(1);
		//下载量统计
		downCount( table,id,columnid );
		window.location.href="/download/dlsingle/?dl_link=" + encodeURIComponent(encodeURIComponent(path)) + '&' + Math.random();
		return false;
	});
	
	//workbench单张删除弹框
	$(".pic_zz2").each(function () {
		$(this).on('click', function () {
			var info = $(this).parents('li');
			$("#DeleteId").val(info.data('id'));
			$(".shadow_black,.delete_pop").show();
			return false;
		});
	});
	//workbench单张删除
	$("#picDelete").on('click', function () {
		var picId = $("#DeleteId").val();
		$.ajax({
			type: "POST",
			url: "/ajax/picdelete",
			data: {picId: picId},
			datatype: "json",
			success: function (status) {
				$(".alertbox_bg,.alertbox_wt_d").hide();
				if (status != '')
				{
					//alert(status);
				}
				location.reload();
			}
		})
		return false;
	});
	
	//取消收藏弹窗打开
	$(".concelcollect").on('click', function (ev) {
		stopPropagation(ev);
		var pid=$(this).data('pid');
		var type=$(this).data('type');
		var t=$(this).data('t');
		var col=$(this).data('col');
		$(".record").data('pid',pid);
		$(".record").data('type',type);
		$(".record").data('t',t);
		$(".record").data('col',col);
		$(".pop_shoucang,.shadow_black").show();
	});
	//取消收藏
	$(".record").on('click', function () {
		$(".pop_shoucang,.shadow_black").hide();
		var pid=$(this).data('pid');
		var type=$(this).data('type');
		var t=$(this).data('t');
		var col=$(this).data('col');
		url = '/collect/setcollect/' + col + '-' + t + '-' + pid + '-' + type +'/cancel/?' + Math.random();
		$.ajax({
			type:'POST',
			data:{ pid:pid, t:t },
			url:url,
			success: function (status) {
					location.reload();
				}
		})
	});
	//取消收藏弹框关闭
	$(".cancel").on("click", function () {
		$(this).closest(".pop_shoucang").hide();
		$(".pop_shoucang,.shadow_black").hide();
	});
	
	//懒加载
	var lazyloadimg = $(".lazyload img");
	if($(".inspiration").length == 0){
		lazyloadimg.length && lazyloadimg.lazyload({effect : "fadeIn"});
	}

	// workbench批量删除
	// 
	// 批量管理按钮click
	if($(".js-detail-manage").find('li').length==0){
		$(".js-select-btn").hide();
	}
	//选中workbench单张

	$(".js-select-checkbox").on('click',function(){
		$(this).parent('li').toggleClass('cur');
	});

	// 选中workbench全部
	 $(".js-all-select").on("click",function(){
	 	var li_len=$("#benchlist").find('li').length;
		var li_cur=$("#benchlist").find('li.cur').length;
		// console.log(li_len,li_cur);
		if(li_len!=li_cur){
			$("#benchlist").find('li').addClass('cur');
		}else{
			$("#benchlist").find('li').removeClass('cur');
		} 	
	 });
	

	// 批量删除确认弹窗
	$(".js-all-delete").on("click",function(){
		var lis=$("#benchlist").find('li.cur');
		if(lis.length>0){
			delete_def.js_delete.show();
			$(".js-delete-section").find("p").text('确定从工作台删除选中的内容？');
			$(".js-select-comfirm").show();
			$(".js-select-cancel").css('width','50%').text('取消');
		}else{
			delete_def.js_delete.show();
			$(".js-delete-section").find("p").text('请选择需要删除的内容！');
			$(".js-select-comfirm").hide();
			$(".js-select-cancel").css('width','100%').text('确定');
		}		
	});
	// 批量删除取消弹窗
	$(".js-select-cancel").on('click',function(){
		delete_def.js_delete.hide();
	});

	//确认删除 
	$(".js-select-comfirm").on('click',function(){		
		var random =Math.random();
		var def_id=[];
		var lis=$("#benchlist").find('li.cur');		
		for(var i=0,len=lis.length;i<len;i++){
			var lis_id=lis.eq(i).data("id");
			def_id.push(lis_id);
		}
		def_id=def_id.join(',');
		pop_fashion_global.fn.subAjax({
			url:'/ajax/batchdelete/?'+random,
			data:{picID:def_id},
			ctp:'application/x-www-form-urlencoded',
			successFunc:function(data){
				delete_def.delete_suc=true;
				lis.remove();
				delete_def.js_delete.hide();
				window.location.reload(true);
			}
		});
    });
    
    // 详情批量下载
    $(".js-all-download").on("click", function() {
        var lis=$("#benchlist").find('li.cur');
		if(lis.length>0){
            delete_def.js_delete.hide();
			delete_def.js_input.show();
		}else{
			delete_def.js_delete.show();
			$(".js-delete-section").find("p").text('请选择需要下载的内容！');
			$(".js-select-comfirm").hide();
			$(".js-select-cancel").css('width','100%').text('确定');
		}        
    });

    // 填写下载包名称
    function packageInit() {
        $(".js-textarea-box").css("border-color",'#E0E0E0');
        $(".js-input-packages-name").find("p").css("color","#2B2B2B");
        $(".js-textarea-box").val("");
    }
    $(".js-input-packages-name").on("click", '.js-cancel-btn', function() {
        delete_def.js_input.hide();
        packageInit();
    });

    $(".js-input-packages-name").on("click", '.js-confirm-btn', function() {
        var text_box = $(".js-textarea-box");
        var sDescribe = text_box.val();
        if(sDescribe == ""){
            text_box.css("border-color",'#F26E32');
            $(".js-input-packages-name").find("p").css("color","#F26E32");
        }else{
            var info=$("#benchlist").find('li.cur');
            var iWorkbenchId = info.find("a").data('wbid');
            var sName = $(".js-work-name").text();
            var regEx = "^[\\u4E00-\\u9FA5A-Za-z0-9]+$";
            if(!RegExp(regEx, 'ig').test(sDescribe)){
                alert('请输入不超过20字符的数字、字母、中文!');
                return false;
            }
            var def_img = [];
            for(var i=0,len=info.length;i<len;i++){
                var info_img=info.eq(i).find("a").data("path");
                def_img.push(info_img);
            }
            $.ajax({
                type: "POST",
                url: "/ajax/workbenchdown/?"+Math.random(),
                data: {iWorkbenchId: iWorkbenchId, sName: sName, sDescribe: sDescribe, images: def_img},
                datatype: "json",
                success: function (status) {
                    if (status == 1)
                    {
                        delete_def.js_input.hide();
                        packageInit();
                        $(".shadow_black , .xiazai_pop").show();
                    }
                    else
                    {   
                        delete_def.js_input.hide();
                        packageInit();
                        $('.workBenchTips').html('加入下载包失败');
                        $('.pop_work_tips').show();
                    }
                }
            })
        }
    });

    $(".js-textarea-box").on("keyup", function() {        
        var text_val = $(this).val();        
        if(text_val.length > 20){
            alert("请输入不超过20字符的数字、字母、中文!");
            text_val = text_val.substr(0,20);
            $(this).val(text_val)
            return false;
        }
    });

    $(".js-confirmBtn").on("click", function() {
        $(".shadow_black , .xiazai_pop").hide();
    });
});

//下载量统计
function downCount( table,id,columnid ) {
	id = parseInt(id);
	columnid = parseInt(columnid);
	var params={ table:table , id:id , iColumnId:columnid ,action:'DownCount'};
	// alert(t);
	$.ajax({
		type: "get",
		url: "/ajax/setcount/?" + Math.random(),
		data: params,
		async: false,
		success: function(data) {
		}
	});
}

// Javascript
function stopPropagation(e) {
	var e = e || window.event;
	if (e.stopPropagation) { //W3C阻止冒泡方法  
		e.stopPropagation();
	} else if (e.preventDefault) { //W3C阻止冒泡方法  
		e.preventDefault();
	} else {
		e.cancelBubble = true; //IE阻止冒泡方法  
	}
}












