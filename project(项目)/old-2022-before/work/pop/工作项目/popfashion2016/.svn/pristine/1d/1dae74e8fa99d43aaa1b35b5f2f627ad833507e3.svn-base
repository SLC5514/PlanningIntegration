// 收集到工作台 && 下载图片

var POPTOOL = POPTOOL || {};
POPTOOL.collect = {};

(function ($) {
    var layer_bg = $('.layer_bg');  // 弹层
    var collectDoc = $('.collect_layer');   //收集到工作台
    var collectPic = $('.collectPic');  // 收藏的图片小图
    var tabName = $('#tabName');    // 请选择工作台
    var J_CollectDescription = $('#picDescribe');  
    var body = $('body');
    var selectionList = $('.selectionList');    // 工作台列表
    var tip12 = $('.tip12');
    var dynamicTip = $('.dynamicTip');
    var smallImage;
    var bigImage;
    var tablename;
    var tableid;
    var column;
    var $detail;
    // 收藏图片到工作台
    var collectSureBox = $(".collect_layer_sure");

    function closeCollectLayer() {
        layer_bg.hide();
        collectDoc.hide();
        $('.warnTips').hide();
        /*$("#tabName").html("请先选择工作台");
        $("#tabName").data('workbenchid',0);*/
        $("#picDescribe").html('');
    }

    //加入到工作台
    $('.js-detail-contant').on("click", ".collect", function (e) {
        // 简单的权限控制
        var self = $(this);
        if(self.hasClass('pow')){
            var usertype = self.data('usertype');
            if (4 == usertype) {
                //普通用户点击无反应
                return; 
            }
            // 没有权限
            layer_bg.show();
            collectSureBox.find( 'p' ).html( '对不起，只有设计师专属账号才能使用此功能，<br/>请您<a href="/member/associate/" title="添加" target="_blank">添加</a>或登录设计师专属账号！' );
            collectSureBox.show();
            collectSureBoxHide();
            return;
        }
        // 获取当前图片的路径
        $detail = $('#J_DetailList').find('img').filter('.on');
        smallImage = $detail.data('sp');
        bigImage = $detail.data('bp');
        tablename = $detail.data('t');
        tableid = $detail.data('id');
        column = $detail.data('col');
        collectPic.attr('src', smallImage);

        // 生成工作台列表
        var J_WorkBenchList = $('#J_Workbench');
        var url = '/ajax/getworkbenchlist/'+Math.random();
        var params = {t:tablename,col:column,id:tableid};
        $.post(url,params,function(data){
            layer_bg.show();
            collectDoc.show();
            POPTOOL.pop.render(J_WorkBenchList, 'T_Workbench', data);

            var memoArr = [];
            var memohtml;
            $.each($('.keylist a'), function () {
                memoArr.push($(this).html());
            });
            memohtml = memoArr.join('');
            J_CollectDescription.val(memohtml).attr('disabled', 'disabled');
			
			//设置默认选中的工作台
			 $.each($('#J_Workbench li'), function () {
				if( $(this).data('workbenchid') == data.checkedWorkbench.id ){
					tabName.text(data['checkedWorkbench']['name']).data('workbenchid', data['checkedWorkbench']['id']);
				}
            });
            // 检查是否可以收藏
            var workbenchid = parseInt(tabName.data('workbenchid'));
            var selTxt = tabName.html();
            checkImgCollected({tablename: tablename, tableid: tableid, workbenchid: workbenchid, selTxt: selTxt, smallImage:smallImage});
        },'json');
    });

    collectDoc.on("click", ".close", closeCollectLayer);

    collectDoc.on("click", ".selections", function (ev) {
        var selectionList = $('.selectionList');
        var createInp = $(".selectionList .create");
        //createInp.val("快速创建一个新的工作台");
        var cratabInVal = $.trim(createInp.val());
        createInp.inputText({
            txt: cratabInVal,
            lightColor: "#999",
            color: "#3f3f3f",
            fontSize: "12px"
        });
        selectionList.show();
        stopPropagation(ev);
    });

    collectDoc.on("click", function () {
        // tip12.hide();
        // dynamicTip.hide();
        // $('.selectionList').hide();
        $(".selectionList .create").css("color", "#999").val("快速创建一个新的工作台");
    });

    var checkImgCollected = function (objParam) {
        var params = {t: objParam.tablename, id: objParam.tableid, wbid: objParam.workbenchid, sp:objParam.smallImage};
        var url = '/ajax/checkimgcollected/'+Math.random();
        $.post(url, params,function(data){
            tabName.text(objParam.selTxt).data('workbenchid', objParam.workbenchid);
            if (data.s == 1) {
                // 可以收藏
                tip12.hide();
                dynamicTip.hide();
                selectionList.hide();
            } else if (data.s == 0 && data.code === 1) {
                // 超出99张
                tip12.hide();
                selectionList.hide();
                dynamicTip.find(".tipText").html("每个工作台最多存放99张图片，您可以选择其他工作台或新建工作台").end().show();
            } else if (data.s == 0 && data.code === 4) {
                // 已加入
                tip12.hide();
                selectionList.hide();
                dynamicTip.find(".tipText").html("您已经收集过此图片，无需再收集").end().show();
            }else {
                // 已存在 || 表名不正确
                selectionList.hide();
                dynamicTip.hide();
                tip12.show().find('a').attr('href', '/member/workbenchdetail/workid=' + objParam.workbenchid).html(objParam.selTxt);
            }
        },'json');
    };
	//保存当前选中的工作台
	var saveCheckedWorkbench = function (objParam) {
        var params = { wbid: objParam.workbenchid };
        var url = '/ajax/savecheckedworkbench/'+Math.random();
		$.ajax({  
			url : url,  
			async : false, // 注意此处需要同步，因为返回完数据后，下面才能让结果的第一条selected  
			type : "POST", 
			data:params,
			success : function(data) { }
		}); 
    };
    // 点击工作台列表之后选中
    collectDoc.on("click", ".selectionList li", function (ev) {
        var self = $(this);
        var selTxt = self.text();
        var workbenchid = self.data('workbenchid');
        // 判断图片是否已经被收藏过 （tablename, id, workbenchid)
        checkImgCollected({tablename: tablename, tableid: tableid, workbenchid: workbenchid, selTxt: selTxt , smallImage:smallImage});
		saveCheckedWorkbench({ workbenchid: workbenchid });	//保存当前选中的工作台
        stopPropagation(ev);
    });

    // 取消收藏
    tip12.find('.close').on('click', closeCollectLayer);
    // 快速创建工作台 
    collectDoc.on("click", ".creatBtn", function (ev) {
        var self = $(this);
        var J_WorkBenchList = $('#J_Workbench');
        var selTxt = $.trim(self.prev(".create").val());
        dynamicTip.hide();
        if (selTxt.length > 40) {
            dynamicTip.find(".tipText").html("工作台名称长度不能超过40个字").end().show();
            return false;
        }
        if (selTxt == "快速创建一个新的工作台") {
            return false;
        }
        if (selTxt != "") {
            $.post('/ajax/createworkbench/'+Math.random(), {name: selTxt}, function (data) {
                if (data.s == 1) {
                    selectionList.hide();
                    tabName.text(selTxt).data('workbenchid', data.id);
                    dynamicTip.find(".tipText").html(data.msg).end().show();
                    J_WorkBenchList.prepend($.trim(template('T_Workbench', {"workbench":[{'id':data.id,'name':selTxt}]})));				
					saveCheckedWorkbench({ workbenchid: data.id });	//保存当前选中的工作台
                    self.closest(".selectionList").hide();
					tip12.hide();
                    $(".selectionList .create").css("color", "#999").val("快速创建一个新的工作台");
                } else if (data.s == 0 && data.code==3) {
                    $("#tabName").data('workbenchid',"0");
					tip12.hide();
                } else {
                    dynamicTip.find(".tipText").html(data.msg).end().show();
					tip12.hide();
                }
            },'json');
        }
        stopPropagation(ev);
    });
    var collectSureTime = null;
    collectDoc.on("click", "#sure", function (ev) {
        var workbenchId = tabName.data('workbenchid');
        var nameTab = $("#tabName").html();
        if (workbenchId > 0 && nameTab != "请先选择工作台") {
            //layer_bg.hide();                   
            var param = {
                wkb: workbenchId,
                t: tablename,
                id: tableid,
                col: column,
                sp: smallImage,
                bp: bigImage,
                desc: J_CollectDescription.val()
            };
            $.post('/ajax/createworkbenchimage/'+Math.random(), param, function (data) {
                if (data.s == 1) {
                    collectDoc.hide();
                    // var nameTab = $("#tabName").html();
                    collectSureBox.find('.tabName').attr("href",  "/member/workbenchdetail/workid=" + workbenchId).html(nameTab).end().show();   
                    collectSureBoxHide();
                } else if (data.s == 0 && data.code == 2) {
                    $(".warnTips").hide();
                    dynamicTip.find(".tipText").html("加入失败请重试").end().show();
                    return false;
                } else if (data.s == 0 && data.code === 4) {
                    // 已收藏
                    $(".warnTips").hide()
                    dynamicTip.find(".tipText").html("您已经收集过此图片，无需再收集").end().show();
                    return false;
                } else{
                    // 一个工作台最多可收集99张图片
                    $(".warnTips").hide();
                    dynamicTip.find(".tipText").html("每个工作台最多存放99张图片，您可以选择其他工作台或新建工作台").end().show();
                    return false;
                }                
            }, 'json');
        } else {
            $(".warnTips").hide();
           dynamicTip.find(".tipText").html("请先选择工作台").end().show();
        }
        stopPropagation(ev);
    });
    collectSureBox.on("click",".closeBtn",function(ev){
        clearInterval(collectSureTime);
        layer_bg.fadeOut(500);
        collectSureBox.fadeOut(500);
        stopPropagation(ev);
    });
    function collectSureBoxHide(){
       collectSureTime = setTimeout(function(){
              collectSureBox.find('.closeBtn').trigger('click');  
       },3000);
    }

    $(".download_size").click(function () {
        $(".pop_download").css("zIndex", 105);
        $("#J_DownNumContanier").trigger("click");
    });

    // 下载
    $('body').on('click', '#J_DownNumContanier', function () {
        if (!oCommon.downloadPrivilege()) {
            return;
        }
        // 简单的权限控制
        if($(this).hasClass('pow')){
            return;
        }
        // 权限控制
        $detail = $('#J_DetailList').find('img').filter('.on');
        var column = $detail.data('col');
        var id = $detail.data('id');
        var t = $detail.data('t');
        if(column==null || id==null || t==null){
           return false;
        }
        // 80=>款式模板（矢量图） 82=>图案素材
        if ($.inArray(column, [73,80,82,120]) != -1) {
            // 获取下载格式
            var smallImagePath = $detail.data('sp');
			var _smallName = smallImagePath.split('/');
			var i = _smallName.length-1;
			var smallName = _smallName[i];//当前小图名称
			var pop_download_table = $(".pop_download").find("table tbody");
			var sDownHtml='';
			var downloadType = pop_download_table.data('downtype');//下载类型详细信息
			var eps = '';
      if( downloadType ){
				for( var k in downloadType ){
					if( smallName == downloadType[k].sSmallName ){
						var downInfo = downloadType[k].aDownInfo;
						if(downInfo){
							for( var suffix in downInfo ){
                if (suffix == 'eps') {
                  eps = '<span class="eps-txt"><span style="position: relative;top: 2px;">*</span> 支持ai、cdr软件工具</span>';
                } else {
                  eps = '';
                }
								sDownHtml += "<tr><td>."+ suffix + eps +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='download_btn' href='javascript:void(0);' title='下载' data-bp='"+ downInfo[suffix].bp +"'>下载</a></td></tr>";
							}
						}
					}
				}
			}
			pop_download_table.html(sDownHtml);
            $('.shadow_black').show();
            $('.pop_download').show();
        } else {
			var _rename = $detail.data('rename')==undefined||$detail.data('rename')==""?"":"?rename="+$detail.data('rename');
            bigImage = $detail.data('bp')+_rename;
            down(bigImage,t,id,column);
        }
    });
    // 素材类下载
    $('body').on('click', '.download_btn', function () {
        // 简单的权限控制
        if($(this).hasClass('pow')){
            return;
        }
        $detail = $('#J_DetailList').find('img').filter('.on');
        bigImage = $(this).data('bp');
        bigImage_suffix =  bigImage.split('.');
        if( bigImage_suffix.pop() == 'jpg'){
			var _rename = $detail.data('rename')==undefined||$detail.data('rename')==""?"":"?rename="+$detail.data('rename');
            bigImage = $detail.data('bp')+_rename;
        }
        column = $detail.data('col');
        id = $detail.data('id');
        t = $detail.data('t');
        down(bigImage,t,id,column);
    });
    // xiazaiclose
    $('body').on('click', '.load_close_btn', function () {
        $('.shadow_black').hide();
        $('.pop_download').hide();
    }); 

    // 手稿合辑和T台发布二级列表页收藏
    $('body').on("click", "#collect", function () {
        var self = $(this);
        var iscollected = self.data('iscollected');
        if (iscollected) {
            var status = 0;
        }else {
            var status = 1;
        }
        // if (iscollected == -1) {
        //     oCommon.noPower(-2);
        //     return;
        // }
        var col = self.data('col');
        var tab = self.data('tab');
        var id = self.data('id');
        var type = self.data('type');
        var para = self.data('para');
        var callback = function () {
            if(status == 0) {
                self.addClass('nzt').find('span').eq(0).html('收藏');
                self.data('iscollected', 0);
            }else {
                self.removeClass('nzt').find('span').eq(0).html('已收藏');
                self.data('iscollected', 1);
            }
            if (typeof(isF5) != 'undefined' && isF5) {
                window.location.reload();
            }
        };
        oCommon.collect(this, col, tab, id, type, callback, status, para);
    });   
    
})(jQuery);

