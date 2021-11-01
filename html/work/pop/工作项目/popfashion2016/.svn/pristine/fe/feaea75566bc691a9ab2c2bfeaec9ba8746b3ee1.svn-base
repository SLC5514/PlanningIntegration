/*
    #author     lut000
    #date       2017/05/04
*/

var pattern_model=null;


$(function(){
    // 局部变量
    var def={
        window_origin:"//"+window.location.host,                                                       //域名
        img_origin:page_img_path_str,                                                                       //图片域名
        // img_origin:"https://imgf4.pop-fashion.com",                                                                       //图片域名
        href_obj:pop_fashion_global.fn.getLocationParameter(),                                              //链接参数
        timer:null,                                                                                         //定时对象
        upload_href:"/ajax/virtualsplupload/",                                                              //上传路径
        account_id:P_UserId,                                          										//用户id
        img_rpath:page_img_path_str+"/global/images/pattern/",                                              //图片路径
        last_index:0,
        pattern_src:"",                                                                                     //默认图案
        pattern_color:"",                                                                                   //默认图案颜色
        pattern_full:"true",                                                                                  //默认是否满身
        file_type:"",                                                                                       //图片类型
        file_name:"",                                                                                       //图片名称
        is_set_cut:false,                                                                                   //是否建立上传对象
        cut_model:null,                                                                                     //剪裁对象
        loading_ele:$(".js-loading-div"),                                                                   //加载等待
        bg_ele:$(".js-bg-div"),                                                                             //背景对象
        last_sid:"",                                                                                        //上次上传的sid
        is_set_model:false                                                                                  //是否生成模型
    };
    // 切换内容列表
    $(".js-pattern-tab-list>li").on("click",function(){
        var nindex=$(this).index();
        $(this).children("img").attr("src",def.img_rpath+"icon"+(nindex+1)+"-active.png");
        $(this).siblings("li.now-choice").each(function(){
            $(this).children("img").attr("src",def.img_rpath+"icon"+(def.last_index+1)+".png");
        });
        $(this).addClass("now-choice").siblings("li").removeClass("now-choice");
        def.last_index=nindex;

        // 切换内容时执行一次
        $(".js-pattern-list-content>ul").hide();
        $("#pattern_list_" + $(this).data('classifyId')).show();
        $(".ps-scrollbar-y-rail").hide();
        $(".js-pattern-list-content").perfectScrollbar();
        $(".js-pattern-list-content").perfectScrollbar("update");
    });
    def.pattern_src=$(".js-pattern-list").eq(0).children("li").attr("data-src") || "";
    def.pattern_color=$(".js-pattern-pic").attr("data-color") || "";
    def.pattern_full=$(".js-pattern-pic").attr("data-full") || "true";

    // 点击生成虚拟样衣
    $(".js-pattern-list>li").on("click",function(){
        //if(!$(this).hasClass("now-choice")){
            // 点击列表中条目时获取并暂存当前选中的tab的文字：男装，女装等
            window.pattern_classify_name = $(".pattern-tab-list .now-choice span").text();
            var mark_src=$(this).attr("data-model-src")?$(this).attr("data-model-src"):"";
            

            def.loading_ele.fadeIn(200);

            if(mark_src==""){
                $(".js-pattern-div").hide();
                msg.msg({"txt":"模型图片为空！"},1200);
            }else{
                $(".js-pattern-div").show();
                $(".js-mask-pic").attr("src",mark_src);
                if(def.is_set_model==false){
                    // 生成模型
                    var pattern_src=$(this).attr("data-src")?$(this).attr("data-src"):"";
                    // 生成模型
                    var is_repeat="no-repeat";
                    if(def.pattern_full=="false"){
                        $(".js-set-type").eq(1).addClass("now-choice").siblings("li").removeClass("now-choice");
                        is_repeat="no-repeat";
                    }else{
                        $(".js-set-type").eq(0).addClass("now-choice").siblings("li").removeClass("now-choice");
                        is_repeat="repeat";
                    }
                    var ncolor=def.pattern_color==""?"transparent":def.pattern_color;
                    $(".js-pattern-div").css({"background-image":"url('"+pattern_src+"')","background-color":ncolor,"background-repeat":is_repeat});
                    $(".js-pattern-pic").attr("src",pattern_src);
                    def.is_set_model=true;

                    var opts={
                        color_layer:def.pattern_color,
                        pattern_type:def.pattern_full=="false"?2:1
                    };

                    if(document.documentElement.clientWidth<=1500){
                        opts.limit_x_val=600;
                        opts.limit_y_val=600;
                    }

                    pattern_model=new setPattern(opts);
                }else{
                    // 更新模型
                    pattern_model.changePattern({
                        is_upload:false
                    });
                }
                setStartSty(0);
            }
            $(".js-pattern-list>li").removeClass("now-choice");
            $(this).addClass("now-choice");
        //}
    });
    
    
    function setStartSty(num){     //初始化位置
        clearTimeout(def.timer);
        num++;
        if(num>20){
            def.loading_ele.fadeOut(200);
            return;
        }
        if(pattern_model.is_maded==false){
            def.timer=setTimeout(function(){
                setStartSty(num);
            },200);
        }else{
            def.loading_ele.fadeOut(200);
        }
    };
    
    //切换图案局部满身
    $(".js-set-type").on("click",function(){
        var type=$(this).attr("data-type");
        if(type==1){
            $(".js-pattern-div").css({"background-repeat":"repeat"});
        }else{
            $(".js-pattern-div").css({"background-repeat":"no-repeat"});
        }
        def.loading_ele.fadeIn(200);

        pattern_model.changePattern({
            pattern_type:type
        });
        $(this).addClass("now-choice").siblings("li").removeClass("now-choice");
        setStartSty(0);
    });
    
    // 重置模型
    $(".js-reset-pattern-btn").on("click",function(){
        var is_repeat="no-repeat";
        if(def.pattern_full=="false"){
            $(".js-set-type").eq(1).addClass("now-choice").siblings("li").removeClass("now-choice");
            is_repeat="no-repeat";
        }else{
            $(".js-set-type").eq(0).addClass("now-choice").siblings("li").removeClass("now-choice");
            is_repeat="repeat";
        }
        var ncolor=def.pattern_color==""?"transparent":def.pattern_color;
        $(".js-pattern-div").css({"background-image":"url('"+def.pattern_src+"')","background-color":ncolor,"background-repeat":is_repeat});
//      $(".js-pattern-pic").attr("src",def.pattern_src);
        def.loading_ele.fadeIn(200);
        pattern_model.color_layer=def.pattern_color;
        pattern_model.changePattern({
            is_upload:false,
            pattern_type:def.pattern_full=="false"?2:1
        });
        setStartSty(0);

//      $(".js-recommend-list>li.recommend-item").removeClass("recommend-item");
        $(".js-color-series-div>span").css({"left":"-5px"});
    });



    

    $(".js-account-id").val(def.account_id);                                                       //设置用户id
    //上传图片
    $(".js-upload-ele").on("change",function(){
        if(def.is_set_cut==false){
            def.is_set_cut=true;
            def.cut_model=new cutPic({
                "box":$(".js-cut-box")[0]
            });
        }
        var file_path=this.value;
        def.file_type="image/"+file_path.substr(file_path.lastIndexOf(".")+1);
        def.file_name=file_path.substr(file_path.lastIndexOf("\\")+1);
        def.file_name=def.file_name.substr(0,def.file_name.lastIndexOf("."));


        // def.file_type=def.file_type.replace("jpeg","jpg");

        // var form_ele=$(".js-form")[0];
        // var form_data=new FormData(form_ele);
        // form_data.append("accountId",def.account_id);
        //upLoadFunc();
        // getImageInfo(this);
        $(".js-form").submit();
        
    });
    
    // 确定上传
    $(".js-save-btn").on("click",function(){
        def.cut_model.def.out_data.is_get_blob=false;
        def.cut_model.getCutData(def.file_type);
        $(".js-edit-pic-box").fadeOut(200);
        def.bg_ele.fadeOut(400);
        def.loading_ele.fadeIn(200);
        // $(".js-form")[0].reset();
        var timer=null;
        checkBlob(0);
        function checkBlob(num){
            clearTimeout(timer,50);
            num++;
            if(num>200){def.loading_ele.fadeOut(400);return};
            timer=setTimeout(function(){
                if(def.cut_model.def.out_data.is_get_blob==false){
                    checkBlob(num);
                }else{
                    // var form_data=new FormData();
                    // form_data.append("material",def.cut_model.def.out_data.blob_obj,def.file_name);
                    // form_data.append("sid",def.last_sid);
                    // form_data.append("accountId",def.account_id);
                    var base64=def.cut_model.def.out_data.base64;
                    var return_obj={
                        material:base64,
                        sid:def.last_sid,
                        accountId:def.account_id
                    };
                    upLoadFunc(return_obj,reSetPattern);
                }
            },50);
        };
    });
    //取消上传
    $(".js-edit-pic-box>button").bind("click",function(){
        $(this).parent().fadeOut(200);
        def.bg_ele.fadeOut(400);
        $(".js-form")[0].reset();
    });
    
    //上传
    function upLoadFunc(data,callback){
        $.ajax({
            url:def.upload_href,
            type: 'POST',
            cache: false,
            data: data,
            dataType:"json",
            // processData: false,
            // contentType: false,
            success:function(data){
                if(data && data.data){
                    // def.last_sid=data.data.id;
                    var src=data.data.imgPath;
                    callback.call(null,src);
                }
                // $(".js-form")[0].reset();
            },
            error:function(){
                def.loading_ele.fadeOut(400);
            }
        });

    };
    setAjaxForm();
    // 模拟form表单提交
    function setAjaxForm(){
        var options = { 
            target:        '#upload-src',   // target element(s) to be updated with server response 
            beforeSubmit:function(arr,$from,options){
                def.loading_ele.fadeIn(200);
            },
            dataType:"json",
            success:function(data){
                if(data && data.data){
                    var obj=data.data || {};
                    var src=obj.imgPath || "";
                    var sid=obj.id || "";
                    def.last_sid=sid;
                    setImagePreview(src);
                }
                
            },
            error:function(){
                // console.log("图片上传失败！");
                def.loading_ele.fadeOut(400);
            },
            clearForm:true,
            url:def.upload_href,
            type:"post"
            //timeout:   3000 
        }; 
     
        // bind to the form's submit event 
        $('.js-form').submit(function() { 
            $(this).ajaxSubmit(options); 
            return false; 
        });
        // $(".js-form").ajaxSubmit(options);

    };
    //上传更新图案
    function reSetPattern(src){
    	def.pattern_full=true;
        src=def.img_origin+src;
        def.pattern_src=src;
        def.pattern_color="transparent";
        pattern_model.color_layer="";
        $(".js-recommend-list>li.recommend-item").removeClass("recommend-item");
        $(".js-set-type").eq(0).addClass("now-choice").siblings("li").removeClass("now-choice");
        $(".js-pattern-pic").attr("src",src);
        $(".js-pattern-div").css({
        	"background-repeat":"repeat",
        	"background-image":"url('"+src+"')",
        	"background-color":"transparent"
    	});
        // 更新模型
        pattern_model.changePattern({
            is_upload:true,
            pattern_type:def.pattern_full=="false"?2:1
        });
        setStartSty(0);
        $(".js-color-series-div>span").css({"left":"-5px"});
    };

    //图片预览
    function setImagePreview(src){
        var oimg=new Image();
        var tag=def.cut_model.cut_img;
        oimg.onload=oimg.onerror=function(){
            $(".js-edit-pic-box").show();
            setCenterFunc($(".js-edit-pic-box"));
            def.loading_ele.fadeOut(400);
            def.bg_ele.show();
            tag.width=this.width;
            tag.height=this.height;
            def.cut_model.imageinit();
        }
        // oimg.src=def.img_rpath+"/28ee5eee04e907a2b91526f4bf0d64c9.jpg";
        oimg.src=def.img_origin+src;
        tag.src=def.img_origin+src;
    };




    // 页面初始化
    $(".js-pattern-tab-list>li").eq(0).click();
    $("#pattern_list_11808>li").eq(0).click();

    /*相似面料*/
    var recommend_lun={
        lun_ele:$('.js-recommend-list'),    //滚动ul
        lef_btn:$('.js-recommend-left'),
        right_btn:$('.js-recommend-right'),
        item_w:156,     //推荐li宽度
        list_len:1,     //推荐li总数目
        lun_len:1,      //滚动总页数 
        lun_w:156,      //一次滚动宽度
        hist_left:0,    //历史滚动距离
        is_lun:false        //是否轮播状态
            
    };
    
    $(window).on('resize',function(){
        //滚动初始化
        general.fn.throttle(lunInit);
        general.fn.throttle(function(){
            var ow=document.documentElement.clientWidth;
            if(pattern_model!=null){
                if(ow<=1500){
                    pattern_model.def.limit_x_val=600;
                    pattern_model.def.limit_y_val=600;
                }else{
                    pattern_model.def.limit_x_val=600;
                    pattern_model.def.limit_y_val=770;
                }
                pattern_model.changePattern();
            }
        });
    });
    
    function setCenterFunc(tag,par){
        var oh=tag.outerHeight();
        var ph=par!=undefined?par.height():(document.documentElement.clientHeight || document.body.clientHeight);
        tag.css({"top":(ph-oh)/2+"px"});
    };
	
	
	//色彩标记显示
	$('.js-series-canvas').on('click',function(){
		$(this).siblings('span').css({
			"display":"inline-block"
		})
	})
    
    //滚动初始化
    // lunInit();
    function lunInit(){
        recommend_lun.list_len=recommend_lun.lun_ele.children('li').length;         //读取li数目
        if(recommend_lun.list_len>0){
            recommend_lun.item_w=recommend_lun.lun_ele.children('li').outerWidth(true);     //获取li宽度
        }
        recommend_lun.lun_ele.css({"width":(recommend_lun.item_w*recommend_lun.list_len+20)+'px'});     //写入ul宽度
//                  recommend_lun.hist_left=parseFloat(recommend_lun.lun_ele.css('margin-left'));       //当前历史距离
        
        //大小屏包含框宽度
        var contain_w=$('.js-recommend-pics').width();
        recommend_lun.lun_len=Math.floor(contain_w/recommend_lun.item_w);
        recommend_lun.lun_w = recommend_lun.item_w*recommend_lun.lun_len;       //大屏滚动宽度
        
        
        if(recommend_lun.lun_len<=1){
            recommend_lun.lun_ele.stop().css({"margin-left":0});
            recommend_lun.hist_left=0;
            recommend_lun.lef_btn.removeClass('recommend-btn-on');
            recommend_lun.right_btn.removeClass('recommend-btn-on');
        }else{
            recommend_lun.lef_btn.addClass('recommend-btn-on');
            recommend_lun.right_btn.addClass('recommend-btn-on');
        }
        
        if(recommend_lun.hist_left>=-1){        //最左边了 禁止左滑
            recommend_lun.lef_btn.removeClass('recommend-btn-on');
        }else if(Math.abs(recommend_lun.hist_left)+recommend_lun.lun_w>=recommend_lun.item_w*recommend_lun.list_len-10){        //最右边了 禁止左滑
            recommend_lun.right_btn.removeClass('recommend-btn-on');
        }
        
    };
    
    //轮播左滑
    recommend_lun.lef_btn.on('click',function(){
        if($(this).hasClass('recommend-btn-on')){
            if(recommend_lun.lun_len<=1 || recommend_lun.is_lun==true){
                return;
            }
            recommend_lun.is_lun=true;      //轮播状态
            
            recommend_lun.hist_left=recommend_lun.hist_left+recommend_lun.lun_w;
            //不到一个轮播宽度的时候到最左端
            recommend_lun.hist_left=recommend_lun.hist_left>0?0:recommend_lun.hist_left;
            
            recommend_lun.lun_ele.stop().animate({
                "margin-left":recommend_lun.hist_left+'px'
            },500,function(){
//                          recommend_lun.hist_left=recommend_lun.hist_left+recommend_lun.lun_w;
                recommend_lun.right_btn.addClass('recommend-btn-on');
                if(recommend_lun.hist_left >= -1){      //最左边了 禁止左滑
                    recommend_lun.lef_btn.removeClass('recommend-btn-on');
                }
                
                recommend_lun.is_lun=false;     //跳出轮播状态
            });
        }
    });
    
    //轮播右滑
    recommend_lun.right_btn.on('click',function(){
        if($(this).hasClass('recommend-btn-on')){
            if(recommend_lun.lun_len<=1 || recommend_lun.is_lun==true){
                return;
            }
            recommend_lun.is_lun=true;      //轮播状态
            recommend_lun.hist_left=recommend_lun.hist_left-recommend_lun.lun_w;
            
            $('.js-recommend-list').stop().animate({
                "margin-left":recommend_lun.hist_left+'px'
            },500,function(){
                recommend_lun.lef_btn.addClass('recommend-btn-on');
                if(Math.abs(recommend_lun.hist_left)+recommend_lun.lun_w >= recommend_lun.item_w*recommend_lun.list_len-10){        //最右边了 禁止左滑
                    recommend_lun.right_btn.removeClass('recommend-btn-on');
                }
                
                recommend_lun.is_lun=false;     //跳出轮播状态
            });
        }
        
    });
    //点击列表切换图案
    recommend_lun.lun_ele.on("click","li",function(){
        var pattern_src=$(this).attr("data-src") || "";
        var is_full=$(this).attr("data-full")!=undefined?$(this).attr("data-full"):"true";
        var color=$(this).attr("data-color")!=undefined?$(this).attr("data-color"):"";
        var _href=$(this).attr("data-href")!=undefined?$(this).attr("data-href"):"";
        
        if(pattern_src!=""){
        	def.pattern_full=is_full;
        	def.pattern_src=pattern_src;

            // 普通用户切换图片时验证剩余可用次数
            var canUse = true;
            $.ajax({
                url: '/details/virtualspl/?act=changeImg&path=' + encodeURIComponent(pattern_src),
                dataType: 'JSON',
                async: false
            }).done(function (res) {
                /**
                 * 返回示例
                 * res = {code: 0, msg: '', data: null}; // 可正常访问
                 * res = {code: 0, msg: '', data: {max: 20, used: 12, free: 8}}; // 普通用户, 返回剩余次数信息
                 * res = {code: 1, msg: '', data: null}; // 未登录
                 * res = {code: 2, msg: '', data: null}; // 无权限或次数已用完
                 */
                if (res.code === 0) {
                    var trialInfo = res.data;
                    if (trialInfo) {
                        var txt = '您当前为普通用户，拥有 <span>' + trialInfo.max + '</span> 次免费体验机会！还剩余 <span>' + trialInfo.free + '</span> 次！</p>';
                        $('#trial_time_info').html(txt);
                    }
                } else if (res.code === 1) {
                    canUse = false;
                    msg.msg({txt: '需要登录'}, 1200);
                } else if (res.code === 2) {
                    canUse = false;
                    msg.msg({txt: '没有权限或试用次数已用完'}, 1200);
                }
            }).fail(function () {
            });
            if (!canUse) {
                return;
            }

            def.loading_ele.fadeIn(200);
            $(".js-pattern-div").css({"background-image":"url('"+pattern_src+"')"});
            $(".js-pattern-pic").attr("src",pattern_src);
            // 更新模型
            if(color!=""){
                $(".js-pattern-div").css({"background-color":color});
                pattern_model.color_layer=color;
                def.pattern_color=color;
            }else{
                pattern_model.color_layer="";
                $(".js-pattern-div").css({"background-color":"transparent"});
                def.pattern_color="transparent";
                $(".js-color-series-div>span").css({"left":"-5px"});
            }
            if(is_full=="false"){
                $(".js-pattern-div").css({"background-repeat":"no-repeat"});
                $(".js-set-type").eq(1).addClass("now-choice").siblings("li").removeClass("now-choice");
            }else{
                $(".js-pattern-div").css({"background-repeat":"repeat"});
                $(".js-set-type").eq(0).addClass("now-choice").siblings("li").removeClass("now-choice");
            }

            pattern_model.changePattern({
                is_upload:false,
                pattern_type:is_full=="false"?2:1
            });
            setStartSty(0);
        }else{
            msg.msg({"txt":"图片为空无法查看虚拟样衣。"},1200);
        }
        $(this).addClass("recommend-item").siblings("li").removeClass("recommend-item");

        if(_href!=""){
            $(".js-detail-btn").attr("href",_href);
        }
    });
    $(".js-recommend-show-btn").on("click",function(){
        if(!$(this).hasClass("recommend-hide-btn")){
            $(this).siblings("div").slideUp();
            $(this).addClass("recommend-hide-btn");
        }else{
            $(this).siblings("div").slideDown();
            $(this).removeClass("recommend-hide-btn");
        }
    });
    getRecommendList();
    //获取推荐图案
    function getRecommendList(opt){
        pop_fashion_global.fn.subAjax({
            url:def.window_origin+"/ajax/virtualsplpicmatch/",
            message:"msg",
            ctp:"application/x-www-form-urlencoded",
            data:{
                path:encodeURIComponent(def.pattern_src)
                // path:encodeURIComponent("https://imgf1.pop-fashion.com/fashion/stylegraphic/201709010045619344/women/2017PF/159/top/jersey/201709010001785965/big/9136c9902e9cc3741f409e1213fed05e.jpg") || "",
            },
            successFunc:setRecommendDom,
            errorFunc:function(){
                noRecommendDom();
            }
        });
    };
    function setRecommendDom(data){
        var tar=recommend_lun.lun_ele,_html='';
        if(data && data.data && data.data.length>0){
            var arr=data.data;
            for(var i=0,len=arr.length;i<len;i++){
                var detail_href=arr[i]["href"] || "";
                var big_src=arr[i]["big"] || "";
                var mbig=arr[i]["mbig"] || "";
                var small_src=arr[i]["small"] || "";
                var d_color=arr[i]["defaultColor"] || "";
                var full=arr[i]["isFull"]!=undefined?arr[i]["isFull"]:true;
                var nsrc=mbig!=""?mbig:small_src;
                // big_src=def.img_rpath+"icon1.png";
                // full=false;
                _html+='<li data-src="'+nsrc+'" data-color="'+d_color+'" data-full="'+full+'" data-href="'+detail_href+'">';
                _html+='<a href="javascript:void(0);" title="查看详情">';
                _html+='<img src="'+small_src+'" alt="pic"/>';
                _html+='</a>';
                _html+='</li>';
            }

            tar.html(_html);
            lunInit();
        }else{
            noRecommendDom();
        }
    };
    function noRecommendDom(){
        $(".js-recommend-box").hide();
    };
});