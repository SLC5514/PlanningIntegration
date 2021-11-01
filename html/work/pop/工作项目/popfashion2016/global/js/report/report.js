$(function(){

    var link_reg_bef = /^http+s?:\/\//i;

    var statistics_action_token = '';
    if( typeof statistics_token == 'undefined'){
        statistics_action_token = '';
    }else{
        statistics_action_token = statistics_token;
    }

//      var pop_layer=$('#pop_layer');
    var hiddenInfo = $('#reportInfo');
    var table = hiddenInfo.data('t') || '';
    var id = hiddenInfo.data('id') || '';
    var col = hiddenInfo.data('col') || '';
    
    var P_UserType_action = '4';
    if(P_UserType=='1'||P_UserType=='2'){
        P_UserType_action = '1';
    }else if(P_UserType=='3'){
        P_UserType_action = '2';
    }else if(P_UserType=='4'){
        P_UserType_action = '3';
    }else if(P_UserType=='5'){
        P_UserType_action = '4';
    }
    //切换图片数据
    var _def = {
        down_list: [],
        page:0
    }
    var def = {
        show_img_model:null,
        is_set_img_model:false,
        p_visit: pVisit,        //有权限为1
        not_vip: userTryOut,
        is_review: false,       // 真实查看
        
        action_down: true,   //下载统计
        action_timer: null,   
        action : {
            token: statistics_action_token,

            iUserType: P_UserType_action,       //用户类型
            
            iUserId: P_AccountId,           //用户ID
            sChildUserId: '',       //子账号id
            sTableName: table,      //假表名
            iPrid: id,              //当前详情ID
            
            
            iColumnId: '',          //主栏目ID
            iSubColumnId: col,          //子栏目ID
            sSelfUrl: location.href,            //当前URL
            
            sRefererUrl: document.referrer||'',     //上个页面URL
            iSite: 1,               //站点
            sLang: 'cn',            //语言
            iVisit: pVisit||0,          //是否能访问 0-无访问权限 1-有访问权限
        }
    };
    
    if(P_UserType=='2'){
        def.action.sChildUserId = P_UserId; //子账号  
    }
    
    // 浏览量
    actionView();
    function actionView(){
        var action_obj = def.action;

        $.ajax({
            "url": "//api.pop136.com/internal/statistics.php?action=view&" + Math.random(),
            "data": action_obj,
            "type": 'get',
            'dataType': "jsonp",
            'jsonp': "callback"
        });
    }
    
    // 真实查看
    function actionRview(){
        def.is_review = true;
        var action_obj = def.action;
        $.ajax({
            "url": "//api.pop136.com/internal/statistics.php?action=rview&" + Math.random(),
            "data": action_obj,
            "type": 'get',
            'dataType': "jsonp",
            'jsonp': "callback"
        });
    }
    
    // 下载统计
    function actionDown(type){
        if(def.action_down){
            def.action_down = false;

            def.action.type = type || 'pdf';// pdf|zip 报告下载类型
            $.ajax({
                "url": "//api.pop136.com/internal/statistics.php?action=down&" + Math.random(),
                "data": def.action,
                "type": 'get',
                'dataType': "jsonp",
                'jsonp': "callback"
            });
            
            clearTimeout(def.action_timer);
            def.action_timer = setTimeout(function(){
                def.action_down = true;
            }, 3000);
        }
    }
    
    
    $('.lazyload img').lazyload({
        effect: "fadeIn",
        threshold: 200
    });

    if (def.p_visit == 1) {
        //统计浏览量
//      viewCount(table, id, col);
    }else{      //没有权限
        $('.js-nav-right').show();
    }
    
    //没有标签则隐藏
    if($('.js-msg-i4 ul>li').length<=0){
        $('.js-msg-i4').hide();
        $('.msg-i4-null').show();
    }
    
    /*副标题文字限制*/
    if(def.p_visit==1){     //有权限时
        var report_msg=$('.report-msg>div').text()||'';
        var new_report_msg=general.fn.cutByWidth(report_msg,424,14)||'';
        if(new_report_msg!=report_msg){
            $('.report-msg>div').html(new_report_msg+' '+'<span>更多</span>');
        }
    }else{
        $('.js-report-msg').addClass('cursor-words');
    }
    
    
    /*副标题文字限制*/
    $('.js-see-list>li>a>p').each(function(){
        var _t=$(this).text();
        var new_t=general.fn.cutByWidth(_t,160,14)||'';
        $(this).text(new_t);
    });

    //右边导航栏控制记忆  
    var report_nav_control=$.cookie('report_nav_control',{ path:'/', domain:'.pop-fashion.com'})||'';
    if(report_nav_control==1){
        $('.js-nav-control-disc').hide();       //控制说明显示
    }else{
        $('.js-nav-control-disc').show();
    }

    var report_prompt_disc=$.cookie('report_prompt_disc',{ path:'/', domain:'.pop-fashion.com'});
    if(report_prompt_disc==1){
        $('.js-prompt>.prompt-disc').hide();        //控制说明显示
    }else{
        $('.js-prompt>.prompt-disc').show();
    }
    
    
    //右边导航显示小的
    var page_min_w=1420;
    function navMinShow(){
        $('.js-nav-right').hide();
        $('.js-nav-right-min').fadeIn(200);

        $('.report-footer>div,.js-trial-pr').stop().animate({       //右边空白
            "padding-right":"0px"
        },100);
        $('.report-cont').stop().animate({      //右边空白
            "padding-right":"100px"
        },100);
        $('.report-contant').css({
            "min-width":"1200px"
        })
        $(".js-fiter-img").hide();
    }
    //右边导航显示大的
    function navMaxShow(){
        $('.js-nav-right-min').hide();
        $('.js-nav-right').stop().show().animate({
            "right":"0"
        },100);
        $('.report-cont,.report-footer>div,.js-trial-pr').stop().animate({      //右边空白
            "padding-right":"300px"
        },100);
        
        $('.report-contant').css({
            "min-width":page_min_w+"px"
        })
        $(".js-fiter-img").show();
    }
    
    $('.js-see-nav1>ul>li>a,.js-see-nav2>ul>li>a').on('click',function(){
        var i=$(this).parent().index();
        $('.js-see-nav1>ul>li').eq(i).addClass('this-item').siblings('li').removeClass('this-item');
        $('.js-see-nav2>ul>li').eq(i).addClass('this-item').siblings('li').removeClass('this-item');
    })


    var nav_control_w=1420;
    if($('.layer-main').hasClass('layer-main-max')){        //大报告时
        $('body,html').addClass('min_w1302');
        page_min_w=1600;
        nav_control_w=1610;
    }
    pageSize();
    function pageSize(){        //监听屏幕
        if(def.p_visit!=1){     //没有权限
            return;
        }
        
        var _w=$(window).outerWidth(true);
        var _t=$(window).scrollTop();
        if(_t>200){         //返回顶部显示
            $('.js-go-top').show();
        }else{
            $('.js-go-top').hide();
        }

        if(_w>nav_control_w){
            navMaxShow();
        }else{
            navMinShow();
        }

        if(_w>900){
            $('.report-down,.report-collect,.report-share').removeClass('no-detail');
        }else{
            $('.report-down,.report-collect,.report-share').addClass('no-detail');
        }

    }
    
    //相关面料模块
    relevantAbout();
    function relevantAbout(){
        if(def.p_visit!=1){     //没有权限
            return;
        }
        pop_fashion_global.fn.subAjax({
            "url":"/ajax/getfabricrel/?" + Math.random(),
            ctp:"application/x-www-form-urlencoded",
            data:{
                id: id,
                t: table,
                col: col
            },
            successFunc:function(data){
                var _data=data['data']||{},fabric_data=_data.fabricData||[],_html='';
                var static_url3='https://imgf3.pop-fashion.com';
                if(fabric_data.length>0){
                    for (var i=0;i<fabric_data.length;i++) {
                        var _link=fabric_data[i]['detailUrl']||'';  //链接
                        var imagePath=fabric_data[i]['imagePath']||'';  //图片
                        var _title=fabric_data[i]['fabricTitle']||'';   //title
                        var price=fabric_data[i]['price']||'';
                        var _fid = fabric_data[i]['fabricId']||''
                        if(price!==''){
                            price='￥'+price;
                        }
                        
                        _html+='<li><a href="'+_link+'" data-id="'+_fid+'" target="_blank" title="查看详情"><div class="relevant-pic"><div>';
                        _html+='<img src="'+static_url3+'/global/images/loading/search_loding.gif" data-original="'+imagePath+'" alt="pic"/></div></div>';  
                        _html+='<div class="relevant-disc"><p>'+_title+'</p>';  
                        _html+='<span>'+price+'</span></div></a></li>';  
                    }
                }
                if(_html!=''){
                    $('.relevant-list').html(_html).show();
                    //图片懒加载
                    $('.relevant-list img').lazyload({
                        effect: "fadeIn",
                        threshold: 200
                    });
                }else{
                    $('.js-relevant-about,.js-report-relevant').hide();
                }
                $('.js-list-load1').fadeOut(200);
            }
        })
    }

    //获取看了又看模块
    seeModel();
    function seeModel(){
        if(def.p_visit!=1 || showMoreRec != 1){     //没有权限
            return;
        }
        var is_suc=false;
        
        pop_fashion_global.fn.subAjax({
            "url":"/ajax/getmorereportrec/?" + Math.random(),
            ctp:"application/x-www-form-urlencoded",
            data:{
                t:table,
                id:id,
                col:col,
                type:"report"
            },
            successFunc:function(data){
                var _data=data['data']||{},_report=_data.report||[],_html='';
                var static_url3=_data['STATIC_URL3']||'https://imgf3.pop-fashion.com';
                if(_report.length>0){
                    for (var i=0;i<_report.length;i++) {
                        var _link=_report[i]['link']||'';   //链接
                        var smallPath=_report[i]['smallPath']||'';  //图片
                        var _title=_report[i]['title']||''; //title
                        var _description=_report[i]['description']||''; 
                        _description=general.fn.cutByWidth(_description,640,14)||_description;          
                        
                        _html+='<li><a href="'+_link+'" target="_blank" title="查看详情"><div class="see-pic1"><div>';
                        _html+='<img src="'+static_url3+'/global/images/loading/search_loding.gif" data-original="'+smallPath+'" alt="pic"/></div></div>';  
                        _html+='<div class="see-disc1"><div>'+_title+'</div>';  
                        _html+='<p>'+_description+'</p></div></a></li>';  
                    }
                }
                
                if(_html!=''){
                    $('.js-see-list1').html(_html).show();
                    //图片懒加载
                    $('.js-see-list1 img').lazyload({
                        effect: "fadeIn",
                        threshold: 200
                    });
                }
                if(is_suc==true){       //加载完
                    $('.js-list-load2').fadeOut(200);
                }
                
                is_suc=true;
            },
            errorFunc:function(){
                if(is_suc==true){       //加载完
                    $('.js-list-load2').fadeOut(200);
                }
                is_suc=true;
            }
        });

        pop_fashion_global.fn.subAjax({
            "url":"/ajax/getmorereportrec/?" + Math.random(),
            ctp:"application/x-www-form-urlencoded",
            data:{
                t:table,
                id:id,
                col:col,
                type:"style"
            },
            successFunc:function(data){
                var _data=data['data']||{},_style=_data.style||[],_html='';
                var static_url3=_data['STATIC_URL3']||'https://imgf3.pop-fashion.com';
                if(_style.length>0){
                    for (var i=0;i<_style.length;i++) {
                        var _link=_style[i]['link']||'';    //链接
                        var smallPath=_style[i]['smallPath']||'';   //图片
                        var _title=_style[i]['title']||'';  //title
                        var brandText=_style[i]['brandText']||'';   
                        
                        
                        _html+='<li><a href="'+_link+'" target="_blank" title="查看详情"><div class="see-pic2"><div>';
                        _html+='<img src="'+static_url3+'/global/images/loading/search_loding.gif" data-original="'+smallPath+'" alt="pic"/></div></div>';  
                        _html+='<div class="see-disc2"><div>'+brandText+'</div>';  
                        _html+='<p>'+_title+'</p></div></a></li>';  
                    }
                }
                if(_html!=''){
                    $('.js-see-list2').html(_html).show();
                    //图片懒加载
                    $('.js-see-list2 img').lazyload({
                        effect: "fadeIn",
                        threshold: 200
                    });
                }
                if(is_suc==true){       //加载完
                    $('.js-list-load2').fadeOut(200);
                }
                is_suc=true;
            },
            errorFunc:function(){
                if(is_suc==true){       //加载完
                    $('.js-list-load2').fadeOut(200);
                }
                is_suc=true;
            }
        })
    }
    
    var is_collc=false;
    function ocommCollect( _this, iColumnId, sTableName, iPriId, iType, callback, status, para) {
        
        if(is_collc==true){
            return;
        }
        is_collc=true;
        var that = oCommon;
        if(status == 0) {
            var text = '您是否确认取消收藏？';
            var url = '/collect/setcollect/'+iColumnId+'-'+sTableName+'-'+iPriId+'-'+iType+'-'+para+'/cancel/?'+Math.random();
        }else {
            var text = '您是否确认收藏？';
            var url = '/collect/setcollect/'+iColumnId+'-'+sTableName+'-'+iPriId+'-'+iType+'-'+para+'/?'+Math.random();
        }
        iColumnId = parseInt( iColumnId );
        iPriId = parseInt( iPriId );
        iType = parseInt( iType );
        if( !iColumnId || !iPriId || !iType || !sTableName ) {
            that.noPower(-4);
            return false;
        }

        $.ajax({
            type : 'get',
            url : url,
            anyc : true,
            success : function( e ) {
                is_collc=false;
                e = parseInt( e );
                if( e < 0 ) {
                    that.noPower(e); return ;
                } else {
                    if (typeof callback == 'function') {
                        callback();
                    } else {
                        window.location.href=location.href;return ;
                    }
                }
            },
            error: function(){
                is_collc=false;
            }
        });
    }
    
    //收藏
    function reportCollect(){
        $(this).off('click');
        var self = $('.js-report-collect');
        var iscollected = self.data('iscollected')*1||0;
        if (iscollected == 2) {
            var usertype = self.data('mainaccount');
            var layer_bg = $('.layer_bg');  // 弹层
            var collectSureBox = $(".collect_layer_sure");
            layer_bg.show();
            collectSureBox.find( 'p' ).html( '对不起，只有设计师专属账号才能使用此功能，<br/>请您<a href="/member/associate/" target="_blank" title="添加">添加</a>或登录设计师专属账号！' );
            collectSureBox.show();
            setTimeout(function(){
                collectSureBox.find('.closeBtn').trigger('click');
            },3000);
            
            $('.js-report-collect').on('click',function(){
                reportCollect();
            })
            return;
        }
        if (iscollected) {
            var status = 0;
        }else {
            var status = 1;
        }
        var colpara = self.data('colpara');
        var params = colpara.split('-');
        var callback = function () {
            if(status == 0) {
                $('#layer-collect').text('已取消收藏');
                self.removeClass('is-collected').find('span').html('加入收藏');
                self.data('iscollected', 0);
                layer.open({
                    type: 1 //Page层类型
                    // ,area: ['160px', '100px']
                    ,title:false
                    ,shade: 0.4 //遮罩透明度
                    ,closeBtn:false
                    ,btn:[]
                    ,time:2000
                    ,skin: 'demo-class'
                    //,content: '<div style="text-align:center;">收藏成功</div>'
                    ,content: $('#layer-collect')
                });
            }else {
                $('#layer-collect').text('收藏成功');
                self.addClass('is-collected').find('span').html('已收藏');
                self.data('iscollected', 1);
                layer.open({
                    type: 1 //Page层类型
                    // ,area: ['160px', '100px']
                    ,title:false
                    ,shade: 0.4 //遮罩透明度
                    ,closeBtn:false
                    ,btn:[]
                    ,time:2000
                    ,skin: 'demo-class'
                    //,content: '<div style="text-align:center;">收藏成功</div>'
                    ,content: $('#layer-collect')
                });
            }
        };
        ocommCollect(self, params[0], params[1], params[2], params[3], callback, status);
        
        $('.js-report-collect').on('click',function(){
            reportCollect();
        })
    }

    //--------------------------事件绑定-------------------------------
    
    // 收起展开更多款式
    $('.more_style_close').on('click', function () {
        $self = $(this);
        var parent = $self.parent();
        var ullist = parent.find('.ullist');
        ullist.slideUp(300);
        $self.hide();
        parent.find('.more_style_open').show();
    });
    
    $('.more_style_open').on('click', function () {
        $self = $(this);
        var parent = $self.parent();
        var ullist = parent.find('.ullist');
        ullist.slideDown(300);
        $self.hide();
        parent.find('.more_style_close').show();
    });
	
	//点击下载本文PDF 判断用户权限
    $('body').on('click', '.J_DOWNLOAD_FILE, .dl_jpg, .dl_ai', function () {
        return oCommon.downloadPrivilege();
    });

    $(window).on('resize',function(){
        pageSize();
    });

    var scroll_item_index = -1;
    var canRun = true;
    $(window).on('scroll',function(){
        /* if (!canRun) {
            // 判断是否已空闲，如果在执行中，则直接return  滚动事件节流
            return;
        } */
        if(def.p_visit!=1){     //没有权限
            return;
        }
        var _t=$(window).scrollTop();
        if(_t>200){
            $('.js-go-top').show();
        }else{
            $('.js-go-top').hide();
        }
        
        var layer_list = $('.layer-main>.indexdiv'),layer_list_len=layer_list.length;
        
        layer_list.each(function(i,n){
            var this_t=$(this).offset().top;
            if(_t>this_t-2){
                scroll_item_index = i;  
                $('.js-see-nav1>ul>li').eq(scroll_item_index).addClass('this-item').siblings('li').removeClass('this-item');
                $('.js-see-nav2>ul>li').eq(scroll_item_index).addClass('this-item').siblings('li').removeClass('this-item');    
            }
        });
        
            
        //如果有大纲导航
        if($('.layer-indexdiv-main').length > 0){
            var max_nav_top=$('.layer-indexdiv-main').offset().top+$('.layer-indexdiv-main').height();
            if(_t>=max_nav_top){
                $('.js-see-nav1>ul>li').removeClass('this-item');
                $('.js-see-nav2>ul>li').removeClass('this-item');
            }
        }
        //真实浏览数据判断条件
        if(!def.is_review){
            if(layer_list_len>=6){
                if(scroll_item_index+1>=6){
                    actionRview();
                }
            }else if(6>layer_list_len && layer_list_len>0){
                if(scroll_item_index+1==layer_list_len){
                    actionRview();
                }
            }else{
                if($(".report_bottom_infor").length >0 ){
                    var setTop=$(".report_bottom_infor").offset().top;
                    if(setTop-(_t+600)<=0){
                        actionRview();
                    }
                }
            }
        }
        upScroll();
    });
    function upScroll(){
        /* var Scroll = $('.tit-info').offset().top; */
        var _t = $(window).scrollTop();
        if (200 > _t) {
            $('.js-sTitle').hide();
        } else {
            $('.js-sTitle').show();
        }
    }
    upScroll();
    // 键盘上下键子专题跳转
    var max_num = $(".js-see-nav1 li").length;
    var _href;
    $(window).on('keydown', function(event) {
        var event = event ? event: (window.event ? window.event: null);        
        if(event && event.which == 40){
            scroll_item_index++;
            _href= $(".js-see-nav1 li").eq(scroll_item_index).find("a").attr("href");
            if(scroll_item_index > max_num-1){
                scroll_item_index = max_num-1;
                return false;
            }
            $('.js-see-nav1>ul>li').eq(scroll_item_index).find("a")[0].click();
            return false;
        }else if(event && event.which == 38){
            if(scroll_item_index >= max_num-1 && $(".js-see-nav1 li.this-item").length == 0){
                scroll_item_index = scroll_item_index +1;
            }
            scroll_item_index--; 
            _href= $(".js-see-nav1 li").eq(scroll_item_index).find("a").attr("href");
            if(scroll_item_index <= -1){
                scroll_item_index = 0;
                return false;
            }
            $('.js-see-nav1>ul>li').eq(scroll_item_index).find("a")[0].click();
            return false;
        }   //向上        
    });   
    
    $('.report-msg').hover(function(){
        var _t=$(this).find('span');
        if(_t.length>0){
            $(this).find('.report-messages').stop().show();
        }
    },function(){
        $(this).find('.report-messages').stop().hide();
    });

    //回到顶部
    $('.js-go-top').on('click',function(){
        $("html,body").animate({
            "scrollTop":"0"
        },700);
    });

    //关闭导航提示
    $('.js-nav-control-disc>div').on('click',function(ev){
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
        $('.js-nav-control-disc').hide();
        $.cookie('report_nav_control',1,{ path:'/', expires: 365, domain:'.pop-fashion.com'});
    })
    
    //文案提示框
    $('.js-nav-control-disc,.prompt-disc').on('click',function(ev){
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
    })

    //nav收缩
    $('.js-nav-control1').on('click',function(){
        navMinShow();
    })

    //nav展开
    $('.js-nav-control2').on('click',function(){
        navMaxShow();
        bookMsgTxtFn();
    })

    //点击显示底部说明
    $('.js-prompt').on('click',function(){
        $(this).find('.prompt-disc').toggle();
    })

    $('.dic-close').on('click',function(ev){
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
        $.cookie('report_prompt_disc',1,{ path:'/', expires: 365, domain:'.pop-fashion.com'});
        $(this).parents('.prompt-disc').hide();
    })
    
    //导航按键收缩
    $(document).on('keydown',function(ev){      //按键
        if(def.p_visit!=1){     //没有权限
            return;
        }
        var e=ev||event;
        var _v=e.keyCode;
        if(_v==37){
            navMaxShow();
        }else if(_v==39){
            navMinShow();
        }
    })
    
    //收藏
    $('.js-report-collect').on('click',function(){
        reportCollect();
    })

    // 下载pdf, 图片打包下载
    $("#J_DownPDF1, .report-down-imgpack").on("click", function () {
        if (!oCommon.downloadPrivilege()) {
            return;
        }
        var self = $(this);
        var path = self.data("path");
        var rename = self.data("rename");
        if (typeof path === 'undefined') {
            return;
        }
        var fileType = path.split('.').pop(); // 下载文件格式
        path += '?rename=' + rename + '.' + fileType;
        // var params = {table: table, id: id, iColumnId: col, action: 'DownCount', fileType: fileType};

        // 统计下载包的下载量
        var type = self.hasClass('report-down-imgpack') ? 'zip' : 'pdf';

        // 下载统计
        actionDown(type);
        window.location.href = '/download/dlsingle/?dl_link=' + encodeURIComponent(path) + '&' + Math.random();
    });
    function bodyScroll(event){  
        event.preventDefault();  
    } 
    // 自动排版
    $(".js-report-section").on("mouseenter",".js-show-bg-btn",function(){
        $(this).next('.js-txt-div').addClass('on');
    }).on("mouseleave", ".js-show-bg-btn", function() {
        $(this).next('.js-txt-div').removeClass('on');
    });
    $(".js-report-section").on("click",".js-show-bg-btn",function(e){
        var src = $(this).attr("data-src") || "";
        var id = $(this).attr("data-id") || "";
        var rename = $(this).attr("data-rename") || "";
        if(id.indexOf('col_') > 0){
            var col = id.substring(id.indexOf('col_'),id.length+4);
            if(col && (col != 80 && col != 81 && col != 84 && col != 85 && col != 117 && col != 124)){
                $('.js-show-pic-section').addClass('status'); 
                $('.js-recommend').show();
            }else{
                $('.js-show-pic-section').removeClass('status');
                $('.js-recommend').hide();
            }
        }else{
            $('.js-show-pic-section').addClass('status');
            $('.js-recommend').hide();
        }
        //禁止浏览器滚动
        document.body.style.overflow='hidden';       
        document.addEventListener("touchmove",bodyScroll,false);//禁止页面滑动
        // src="https://imgf3.pop-fashion.com/fashion/styledetail/2018022604465019456/EuropeUS/man/1718AW/159/outerwear/sleeve/big/150bc1f1ec320992b5c0934088e39da6.jpg";
        if(src!=""){
            modelChangeImg(src);
            $('.js-show-pic-section').show();
            $('.js-show-pic-bg').show();
            var nimg = new Image();
            nimg.src = $('.js-down-img')[0].src;
            $('.js-down-img')[0].onload = function(){
                $('.js-gifImg').hide();
                $('.js-down-img').show();
            };
            $('.js-src').each(function () {
                $(this).attr('data-src', src)
                $(this).attr('data-id', id)
                $(this).attr('data-rename',rename)
            })
            downPage(id);
        }else{
            //alert("图片链接为空！");
        }
        e.stopPropagation();
    });

    var report_box={
        ow:2000,
        oh:1125,
        nh:626,
        nw:$(".js-report-section").eq(0).width()
    };
    var report_info={
        k1:report_box.nw/report_box.ow
    };
    var img_prefix = 'https://imgf3.pop-fashion.com';
    var img_imgf1 = 'https://imgf1.pop-fashion.com';
    var canvas=document.createElement("canvas");
    var download_list_length=0;
    var report_href='/details/report/t_'+table+'-id_'+id+'-col_'+col+'/';
    $(".js-report-section").each(function(nindex){
        var self=$(this);
        var key=$(this).attr("data-pageid") || "";
        var data=flashPages[key]["sContent"] || {};
        var arr1=data["grids"] || [];
        var arr2=data["layers"] || [];
        var _html=(window.is_generate_pdf!=undefined && window.is_generate_pdf)?'<div class="report-watermark"><a href="'+report_href+'" title="查看详情"></a></div>':'';
        var main_img_src=data.photo?(data.photo.url?data.photo.url:""):"";

        if( main_img_src.indexOf('fm.pop-fashion.com') != -1 ){
            main_img_src = img_imgf1 + main_img_src.split('fm.pop-fashion.com')[1];
        }

        var grid_w=0,
            grid_h=0,
            grid_x=0,
            grid_y=0,
            photo={},
            img_src="",
            brand="",
            description="",
            scale=1,
            rotation=0,
            rotationX=0,
            rotationY=0,
            img_w=0,
            img_h=0,
            img_x=0,
            img_y=0,
            rotation90=0,
            photo_w=0,
            photo_h=0,
            minus_x=0,
            minus_y=0,
            img_href="",
            download_src="",
            big_src="",
            img_id="",
            is_local=false
            video_id='',
            rename = '';


        for(var i=0,len1=arr1.length;i<len1;i++){
            getVal(arr1,i,1);

            domFunc(1,i);
        }
        // console.log("格子数据：nindex");
        // console.log(arr1);
        // console.log("------------------------");
        // console.log("浮层数据：nindex");
        // console.log(arr2);
        // console.log("------------------------");
        for(var j=0,len2=arr2.length;j<len2;j++){
            getVal(arr2,j,2);
            domFunc(2,j);
        }
        _html+='<img class="big-pic-bg" src="'+main_img_src+'" alt="pic">';                                   //整个背景图片

        self.html(_html);

        function getVideoMsg(str) {
            var matchArr = str.match(/#YK#\s*(.+)\s*#YK#\s*(\S*)\s*/);
            if (!matchArr) { return [] }
            return [matchArr[1], matchArr[2]];
        }

        function getVal(arr,i,type){
            is_local=arr[i]["isLocal"];
            download_src=arr[i]["download"] || "";
            grid_w=arr[i]["width"]*report_info.k1 || 0;
            grid_h=arr[i]["height"]*report_info.k1 || 0;
            if(type==1){
                grid_x=arr[i]["x"]*report_info.k1 || 0;
                grid_y=arr[i]["y"]*report_info.k1 || 0;
            }else{
                grid_x=(arr[i]["x"]+report_box.ow/2)*report_info.k1 || 0;
                grid_y=(arr[i]["y"]+report_box.oh/2)*report_info.k1 || 0;
            }
            

            photo=arr[i]["photo"] || {};
            rename = arr[i]["photo"].rename || "";
            img_src=photo["big"] || "";
            img_id=photo["id"] || "";
            big_src=photo["big"] || "";
            img_src = img_src ? (link_reg_bef.test(img_src) ? img_src : (img_prefix + img_src)):'';
            brand=photo["brand"] || "";
            description=photo["description"] || "";
            video_id=getVideoMsg(description)[0];
            scale=photo["scale"] || 1; // 图片缩放比
            rotation=photo["rotation"] || 0; // 图片旋转: [0]↑, [90]→, [-180]↓, [-90]←
            // rotation 取90或-90时减数宽高互换
            rotation90 = Math.abs(rotation) === 90;
            rotationX = photo["rotationX"] || 0; // 图片沿X轴镜像: 由 <=> 甲
            rotationY = photo["rotationY"] || 0; // 图片沿Y轴镜像: 部 <=> 陪
            photo_w = photo['width'] || 0; // 图片缩放后的宽
            photo_h = photo['height'] || 0; // 图片缩放后的高
            img_w = rotation90 ? photo_h : photo_w; // 图片css的宽
            img_h = rotation90 ? photo_w : photo_h || 0; // 图片css的高
            img_x = photo["x"] || 0; // 缩放后的图片中心点到格子左上角的x
            img_y = photo["y"] || 0; // 缩放后的图片中心点到格子左上角的y
            img_href = photo["link"] || ""; // 图片链接
            // 计算图片到格子左上角的坐标
            minus_x = rotation90 ? photo_h / 2 : photo_w / 2;
            minus_y = rotation90 ? photo_w / 2 : photo_h / 2;
            img_x = (img_x - minus_x) * report_info.k1 || 0;
            img_y = (img_y - minus_y) * report_info.k1 || 0;
        };

        function domFunc(type,i){
            // 报告视频
            if (video_id) {
                if(type==1){
                    _html+='<div class="report-video-box report-box" style="left:'+Math.round(grid_x)+'px;top:'+Math.round(grid_y)+'px;width:'+Math.round(grid_w)+'px;height:'+Math.round(grid_h)+'px" data-src="'+img_src+'" data-id="'+img_id+'" >';
                }else{
                    _html+='<div class="report-video-box report-box up-box" data-index="'+(i+1000)+'" style="z-index:'+(i+1000)+';left:'+Math.round(grid_x)+'px;top:'+Math.round(grid_y)+'px;width:'+Math.round(grid_w)+'px;height:'+Math.round(grid_h)+'px" data-src="'+img_src+'" data-id="'+img_id+'" >';
                }
                _html+='<div class="report-video"><button class="video-play js-video-play" data-videoId="'+video_id+'" title="播放"><i></i>视频</button></div>';
                _html+='</div>';
                return;
            }

            // js替换
            if(img_src.indexOf('fm.pop-fashion.com') != -1 ){
                img_src = img_imgf1 + img_src.split('fm.pop-fashion.com')[1];
            }

            var isPdf = $('.pop-report .layer-main').data('type') || '';

            if(type==1){
                _html+='<div class="report-box js-report-box js-show-bg-btn" style="left:'+Math.round(grid_x)+'px;top:'+Math.round(grid_y)+'px;width:'+Math.round(grid_w)+'px;height:'+Math.round(grid_h)+'px" data-src="'+img_src+'" data-id="'+img_id+'" >';
            }else{
                _html+='<div class="report-box up-box js-report-box js-show-bg-btn" data-index="'+(i+1000)+'" style="z-index:'+(i+1000)+';left:'+Math.round(grid_x)+'px;top:'+Math.round(grid_y)+'px;width:'+Math.round(grid_w)+'px;height:'+Math.round(grid_h)+'px" data-src="'+img_src+'" data-id="'+img_id+'" >';
            }
            // _html+='<img style="left:'+Math.round(img_x)+'px;top:'+Math.round(img_y)+'px;width:'+Math.round(img_w*report_info.k1)+'px;height:'+Math.round(img_h*report_info.k1)+'px;transform:rotate('+rotation+'deg) rotateX('+rotationX+'deg) rotateY('+rotationY+'deg)" ';
            // _html+='src="'+img_src+'" alt="'+description+'">';
            // _html+='src="https://imgf3.pop-fashion.com/global/images/report/bg-opa0.png" data-original="'+img_src+'" alt="'+description+'">';

            if (isPdf != 'pdf') {
                /* if(type !== 1 && grid_y < 0){
                    // 浮层超出画布上边界
                    _html+='<div class="tool-div" style="top: ' + Math.abs(grid_y) + 'px;">';
                } else {
                    _html+='<div class="tool-div">';
                } */
                _html+='<div class="tool-div">';

                if(img_src!=''){
                    // js替换
                    if( img_src.indexOf('fm.pop-fashion.com') != -1 ){
                        img_src = img_imgf1 + img_src.split('fm.pop-fashion.com')[1];
                    }
                    _html+='<button class="js-show-bg-btn" data-src="'+img_src+'" data-id="'+img_id+'" data-rename="'+rename+'" title="查看大图"></button>';
                    if(is_local==true){
                        if(isIE()==true && !canvas.msToBlob){
                            _html+='<a class="download-btn js-download-btn5" href="/download/dlsingle/?dl_link='+encodeURIComponent(img_src)+'" title="下载" target="_blank"></a>';
                        }else{
                            // js替换
                            if( img_src.indexOf('fm.pop-fashion.com') != -1 ){
                                img_src = img_imgf1 + img_src.split('fm.pop-fashion.com')[1];
                            }
                            // 前端下载
                            _html+='<a class="download-btn js-download-btn1" href="javascript:void(0);" data-src="'+img_src+'" title="下载"></a>';
                        }
                    }else{
                        // 非本地图
                        _html+='<a class="download-btn js-download-btn2" href="javascript:void(0);" data-rename="'+rename+'" data-id="'+img_id+'" data-src="'+img_src+'" title="下载"></a>';
                    }
                }
                

                if(img_href!=""){
                    _html+='<a class="detail-btn" href="'+img_href+'" title="详情" target="_blank"></a>';
                }
                _html+='</div>';
            }            
            // _html+='<div class="report-bg"></div>';
            _html+='</div>';
            if(brand!=""){
                // 校正底部越界
                var _top = Math.round(grid_y)+Math.round(grid_h)-20;
                _top = _top > 0 ? _top : 0;
                if(isPdf != 'pdf'){
                    _html+='<div class="txt-div txt-detail-div js-txt-div" style="position:absolute;top: ' + _top + 'px;left: '+ Math.round(grid_x)+'px;z-index: '+(i+2000)+';">';
                    _html+='<a href="/search/?key='+encodeURIComponent(encodeURIComponent(brand))+'" target="_blank">'+brand+'</a>';
                }else{
                    _html+='<div class="txt-div js-txt-div" style="position:absolute;top: ' + _top + 'px;left: '+ Math.round(grid_x)+'px;z-index: '+(i+2000)+';">';
                    _html+='<h3>'+brand+'</h3>';
                }
                // _html+='<p>'+description+'</p>';
                _html+='</div>';
            }
        }

    });

    // 报告视频
    var $rpt_player = $("#reportPlayer");
    var reportPlayer;
    $(".js-report-section").on('click', '.js-video-play', function(){
        $('.js-report-video-bg').show();
        $('.js-report-video-section').show();
        if($rpt_player.length){
            reportPlayer = video_player('reportPlayer', $(this).attr("data-videoId"));
        }
    });
    $('.js-report-video-section').on('click', '.close', function() {
        $('.js-report-video-bg').hide();
        $('.js-report-video-section').hide();
        // try { reportPlayer.pauseVideo(); } catch {}
        $rpt_player.html('');
    })
    function video_player(player_id,vid){
        var params = {
            styleid: '0',
            client_id: '6304acd0252780d2',
            vid: vid,
            newPlayer: true,
            autoplay: true,
            show_related: false
        };
        return new YKU.Player(player_id, params);
    }

    if(window.is_generate_pdf==undefined || !window.is_generate_pdf){
        $('.js-report-section img').lazyload({
            effect: "fadeIn",
            threshold: 200
        });
    }
    $(".js-report-section").on('click', '.js-txt-div', function(e){
        e.stopPropagation();        
    });
    
    
    //导航拉开去除其他弹框
    $('.js-all-nav-hv').on('mouseenter',function(){
        $(".bd_weixin_popup,.bd_weixin_popup_bg").hide();
    });

    // 关闭下载框
    $(".js-down-close").on("click",function(){
        $(".js-download-img-box").hide();
        $(".js-bg-div").hide();
    });

    $(".js-report-section").on("click",".js-download-btn1",function(e){
        var src=$(this).attr("data-src") || "";
        if(src!=""){
            setCanvas(src);
        }else{
            alert("无法下载图片！");
        }
    });

    var encrypt = $("#encrypt");
    var cur_params;
    $(".js-download-btn2").on("click", function (event) {
        event.stopPropagation();
        if (!oCommon.downloadPrivilege()) {
			return false;
		}
        var id=$(this).attr("data-id") || "";
        var src=$(this).attr("data-src") || "";
        var rename = $(this).attr("data-rename") == undefined || $(this).attr("data-rename") == "" ? "" : $(this).attr("data-rename");
        src = src + '?rename=' + rename
        if(!isNaN(id)){
            down(src,"","","");
            // 下载统计
            actionDown();
            return false;
        }        
        var _t=id.split('t_')[1]||'';
	    _t = _t.split('-')[0]||'';
	    var _col=id.split('col_')[1]||'';
	    _col = parseFloat(_col)||0;
	    var _id=id.split('id_')[1]||'';
	    _id = _id.split('-col_')[0]||'';        
        var timeStamp = encrypt.data("timestamp");
        var sign = encrypt.data("sign");
        var token = encrypt.data("token");
        var s_p = {t:_t,id:_id,col:_col,timeStamp:timeStamp,sign:sign};            
        var s_p_str = JSON.stringify(s_p);
        cur_params ={s_p:rsa_encrypt(s_p_str),token:token}
        if(id!=""){
            getDownload(cur_params, src);
        }else{
            alert("无法下载图片！");
        }
    });
    
    $(".js-report-section").on("click",".js-download-btn5",function(){
        // 下载统计
        actionDown();
    });

    // 下载按钮
    $(".js-download-img-box").on("click",".js-download-file-btn",function(){
        var bp=$(this).attr("data-bp") || "";
        var table=$(this).attr("data-table") || "";
        var id=$(this).attr("data-id") || "";
        var col=$(this).attr("data-col") || "";
        down(bp,table,id,col);
        if(download_list_length<2){
            $(".js-download-img-box").hide();
            $(".js-bg-div").hide();
        }
        // 下载统计
        actionDown();
        
    });

    function getDownload(params,src){		
        $.ajax({
            "url":"/details/style/",
            type:"post",
            dataType:"json",
            data:params,
            success:function(data){
                if(data.code == 1) {
                    alert("图片已失效");
                    return false;
                }
                var sign = encrypt.data("sign");
                var ndata = JSON.parse(aes_decrypt(data.data,sign));
                var id=ndata.id || "";
                var t=ndata.t || "";
                var col=ndata.col || "";
                var download_type=ndata["downloadType"] || "";
                if(download_type!=""){
                    setDownloadDom(download_type,t,id,col);
                }else{
                    down(src,t,id,col);
                    // 下载统计
                    actionDown();
                }
            }
        })
    };


    function setDownloadDom(str,t,id,col){
        download_list_length=0;
        var _html='';
        var data=JSON.parse(str);
        var obj=data[0]["aDownInfo"] || {};
        var is_more=false;
        var eps = '';
        for(var key in obj){
            var name="."+key;
            var type=obj[key].type;
            var bp=obj[key].bp;
            var size=obj[key].size;
            if (name == '.eps') {
              eps = '<span class="eps-txt"><span style="position: relative;top: 2px;">*</span> 支持ai、cdr软件工具</span>';
            } else {
              eps = '';
            }
            _html+='<tr>';
            _html+='<td>'+name+eps+'</td>';
            _html+='<td>'+type+'</td>';
            _html+='<td>'+size+'</td>';
            _html+='<td><a class="js-download-file-btn" data-bp="'+bp+'" data-table="'+t+'" data-id="'+id+'" data-col="'+col+'" href="javascript:void(0);" title="下载">下载</a></td>';
            _html+='</tr>';
            download_list_length++;
        }
        $(".js-download-tbody").html(_html);
        $(".js-download-img-box").show();
        $(".js-bg-div").show();
    };


    function setCanvas(src){
        var ctx=canvas.getContent("2d");
        var img=new Image();
        var ow=0,oh=0;
        var re=/\.+(jpg|png|gif|jpeg)$/ig;
        img.onload=function(){
            ow=this.width;
            oh=this.height;
            canvas.width=ow;
            canvas.height=oh;
            ctx.clearRect(0,0,ow,oh);
            ctx.drawImage(this,0,0,ow,oh);


            var type=src.match(re)!=null?(src.match(re))[0]:".png";
            var filename='pop-fashion'+(new Date()).getTime()+type;
            type=type.substr(1);
            var base64_str=getCanvasBase64(canvas,type);

            saveFile(base64_str,filename,canvas);

        };
        img.src=src;
    };

    function getCanvasBase64(canvas,type){
        return canvas.toDataURL(type);
    }
    function saveFile(data,filename,canvas){                              //下载
        if(isIE()==true) { // 是ie浏览器
            if(canvas.msToBlob){
                // 支持msToBlob
                // console.log("msToBlob下载");
                var blob = canvas.msToBlob(data);
                window.navigator.msSaveBlob(blob,filename);
            }
        }else{
            var save_link = document.createElementNS('http://www.w3.org/1999/xhtml', 'a');
            save_link.href = data;
            save_link.download = filename;

            var event = document.createEvent('MouseEvents');
            event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            save_link.dispatchEvent(event);
        }
        // 下载统计
        actionDown();
    };
    function isIE(){
        return !!window.ActiveXObject || "ActiveXObject" in window;
    }  
    //手机端隐藏侧边栏
    if (isPhone() != null) {
        $(".js-nav-right-min").hide();
    }
    function isPhone() {
        var ua = navigator.userAgent;
        var ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
            isIphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
            isAndroid = ua.match(/(Android)\s+([\d.]+)/),
            isMobile = isIphone || isAndroid;
        return isMobile;
    }
    /* 新加详情改版 */
    //鼠标右键下载
    if(P_UserType != '1' && P_UserType != '2' ){
        $(document).ready(function() {
            $('.js-down-img').bind("contextmenu", function(e) {
                return false;
            });
        });
    }
    /* $('.js-down-img').mousedown(function(e) {
        if (3 == e.which) {
            var _x = e.clientX,
                _y = e.clientY;
            $('.js-down-box').css('display', 'block');
            $('.js-down-box').css('left', _x + 'px');
            $('.js-down-box').css('top', _y+'px');
        } else if (1 == e.which) {
            $('.js-down-box').css('display', 'none');
        }
    }) */
    //关闭详情
    $('.js-down-close1').click(function () {
        $('.js-show-pic-bg').hide();
        $('.js-show-pic-section').hide();
        /* $('.js-down-box').css('display', 'none'); */
    })
    //切换图片数据获取
    function downPage (id) {
        _def.down_list = [];
        var gridList=[],layerList=[];
        for (var item in flashPages) {
            if (flashPages[item].sContent.grids != undefined && flashPages[item].sContent.grids != '') {
                for (var _item in flashPages[item].sContent.grids) {
                    var _id = flashPages[item].sContent.grids[_item].photo.id;
                    if (_id == id) {
                       for (var grid in flashPages[item].sContent.grids) {
                            if(flashPages[item].sContent.grids[grid].photo.big!=''){
                                gridList.push(flashPages[item].sContent.grids[grid]);
                            }
                        }
                        for (var layer in flashPages[item].sContent.layers) {
                            if(flashPages[item].sContent.layers[layer].photo.big!=''){
                                layerList.push(flashPages[item].sContent.layers[layer]);
                            }
                        }
                        _def.page = _item;
                    }
                }
            }
            if (flashPages[item].sContent.layers != undefined && flashPages[item].sContent.layers != '') {
                for (var _item in flashPages[item].sContent.layers) {
                    var _id = flashPages[item].sContent.layers[_item].photo.id;
                    if (_id == id) {
                        for (var layer in flashPages[item].sContent.layers) {
                            if(flashPages[item].sContent.layers[layer].photo.big!=''){
                                layerList.push(flashPages[item].sContent.layers[layer]);
                            }
                        }
                        for (var grid in flashPages[item].sContent.grids) {
                            if(flashPages[item].sContent.grids[grid].photo.big!=''){
                                gridList.push(flashPages[item].sContent.grids[grid]);
                            }
                        }
                        _def.page = _item;
                    }
                }
            }
        }
        _def.down_list=gridList.concat(layerList);
    }
    $('.js-i-lfet').on('click', function () {
        --_def.page;
        if (_def.page < 0) {
            _def.page = _def.down_list.length-1;
        }
        var src = _def.down_list[_def.page].photo.big;
        var id = _def.down_list[_def.page].photo.id;
        var rename = _def.down_list[_def.page].photo.rename;
        // src = src ? (src.indexOf('http:') < 0 ? (img_prefix + src) : src) : '';

        if( src.indexOf('fm.pop-fashion.com') != -1 ){
            src = img_prefix + src.split('fm.pop-fashion.com')[1];
        }
        src = src ? (link_reg_bef.test(src) ? src : (img_prefix + src)) : '';

        modelChangeImg(src);
        $('.js-src').each(function () {
            $(this).attr('data-src', src)
            $(this).attr('data-id', id)
            $(this).attr("data-rename",rename)
        })
        $('.js-show-pic-section').addClass('status');
        if(id.indexOf('col') > 0){
            $('.js-recommend').show();
        }else{
            $('.js-recommend').hide();
        }
        /* $('.js-down-box').hide(); */
    }); 
    $('.js-i-right').on('click', function () {
        ++_def.page;
        if (_def.page >= _def.down_list.length) {
            _def.page = 0;
        }
        var src = _def.down_list[_def.page].photo.big;
        var id = _def.down_list[_def.page].photo.id;
        var rename = _def.down_list[_def.page].photo.rename;
        // src = src ? (src.indexOf('http:') < 0 ? (img_prefix + src) : src) : '';

        if( src.indexOf('fm.pop-fashion.com') != -1 ){
            src = img_prefix + src.split('fm.pop-fashion.com')[1];
        }
        src = src ? (link_reg_bef.test(src) ? src : (img_prefix + src)) : '';

        modelChangeImg(src);
        $('.js-src').each(function () {
            $(this).attr('data-src', src)
            $(this).attr('data-id', id)
            $(this).attr("data-rename",rename)
        })
        $('.js-show-pic-section').addClass('status');
        if(id.indexOf('col') > 0){
            $('.js-recommend').show();
        }else{
            $('.js-recommend').hide();
        }
        /* $('.js-down-box').hide(); */
    });
    
    function modelChangeImg (src) {
        if(def.is_set_img_model==false){
            def.is_set_img_model=true;
            def.show_img_model=new scaleImg({
                "box":".js-show-pic-section",
                "src":src
            });
        }else{
            def.show_img_model.changeImg(src);
        }
    }

    // ie10 filter
    if (!!window.ActiveXObject || "ActiveXObject" in window){ 
        $(".js-fiter-img").find("img").hide(); 
    }else{ 
        $(".js-fiter-img").find("img").show();
    }
    
    /* 新加相关推荐代码*/
    var _id='';
    $('.js-recommend').click(function(){
        var classList=$('.js-show-pic-section')[0].className;
        if(classList.indexOf('status')>0){
            $('.js-show-pic-section').removeClass('status');
            var id = $('.js-src')[0].dataset.id + '';
            if(id.indexOf('col') < 0){
                return;
            }
            var list=id.split('-');
            var def = {
                guess_data:{
                    t:list[0].substring(list[0].indexOf('_')+1,list[0].length),
                    id:list[1].substring(list[1].indexOf('_')+1,list[1].length),
                    col:list[2].substring(list[2].indexOf('_')+1,list[2].length),
                    pageSize:21
                },
                is_add_dom:false
            }
            if(def.guess_data.id == _id){
                _id=def.guess_data.id;
                return;
            }else{
                _id=def.guess_data.id;
            }
            $('.js-loading').show();
            getData()
            function getData(){
                def.is_add_dom = true;
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: '/patterns/getLikeDataByPopId/',
                    data:def.guess_data,
                    success:function(data){
                        def.is_add_dom = false;
                        var arr=data.data||[];
                        $('.js-loading').hide();
                        if(!arr.length || data.code == 1001){
                            $(".js-guess-imgs").html('');
                            return false;
                        }
                        loadData(arr);
                    }
                })
            }
            function loadData(totalArr){
                var ele = $(".js-guess-imgs");
                var _html = "";
                for(var i=0;i<totalArr.length;i++){
                    var cover=totalArr[i]["cover"]?totalArr[i]["cover"]:"";
                    var title=totalArr[i]["title"]?totalArr[i]["title"]:"";
                    var tableName= totalArr[i]["t"]?totalArr[i]["t"]:"";
                    var id=totalArr[i]["id"]?totalArr[i]["id"]:"";
                    var columnId=totalArr[i]["columnId"]?totalArr[i]["columnId"]:"";
                    var request_id = totalArr[i]["request_id"]?totalArr[i]["request_id"]:"";
                    var link=totalArr[i]["link"]?totalArr[i]["link"]:"";
                    cover = cover.indexOf("pop-fashion") != -1 ? cover : static_url+cover;
                    if(def.guess_data.col == '9' || def.guess_data.col == '82' || def.guess_data.col == '120'){
                        _html+='<a class="item action" href="'+link+'" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-request_id="'+request_id+'" target="_blank">';
                    }else{
                        _html+='<a class="item" href="'+link+'" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-request_id="'+request_id+'" target="_blank">';
                    }
                    _html+='<img alt="'+title+'" src="'+cover+'" />';
                    _html+='</a>';
                    
                }
                ele.html(_html);
                $('.Scrollbar').mCustomScrollbar({
                    theme:'dark'
                });
            }
        }else{
            $('.js-show-pic-section').addClass('status');
        }
    })
    $('.js-show-pic-section').on("click", ".js-guess-imgs a", function() {
        var this_t = $(this).data("t");
        var this_id = $(this).data("id");
        var request_id = $(this).data("request_id");
        actionFunc(this_t,this_id,'rec_click',request_id,'relate_picture_clothing');
    });
    // 用户行为统计
    var action_data = {
        t:"",
        id:"",
        site:1,
        action_type:"",
        timestamp:"",
        userid:P_AccountId
    };
    function actionFunc(tablename,id,action_type,request_id,scene_type,func){
        action_data.request_id = request_id;
        action_data.scene_type = scene_type;
        action_data.t= tablename;
        action_data.id=id;
        action_data.action_type = action_type;
        action_data.timestamp = new Date().getTime();
        $.ajax({
            type: "get",
            dataType: "jsonp",
            url: 'https://api.pop136.com/internal/datagrand.php',
            data:action_data,
            success:func
        })
    }

    // 报告成册
    bookMsgTxtFn();
    function bookMsgTxtFn() {
        var bookMsgTxtSize = 1650;
        if (bookMsgTxtSize > $(window).width()) { bookMsgTxtSize = $(window).width() - 25 }
        $('.js-book-msg').text(general.fn.cutByWidth($('.js-book-msg').text(), bookMsgTxtSize, 12));
    }
    $('.js-right-tab').on('click', 'li', function() {
        var idx = $(this).index();
        var len = $(this).parent().children().length;
        $('.js-right-tab li').eq(idx).addClass('active').siblings().removeClass('active');
        $('.js-right-tab li').eq(idx + len).addClass('active').siblings().removeClass('active');
        if ($(this).hasClass('right-tab-text')) {
            $('.right-cont-book').hide();
            $('.right-cont-text').show();
        } else {
            $('.right-cont-book').show();
            $('.right-cont-text').hide();
        }
    })
    var topicBox = $('.js-see-nav-topic');
    var topicList = topicBox.find('.topic-list>li');
    var itemH = 34;
    for (var i = 0; i < topicList.length; i++) {
        var itemEl = $(topicList[i]);
        var title = itemEl.find('.title>span');
        if (title.data().ismin) {
            title.text(general.fn.cutByWidth(title.text(), 185, 14));
        } else {
            title.text(general.fn.cutByWidth(title.text(), 420, 14));
        }

        var listBox = itemEl.find('ul');
        var listItems = listBox.children();
        listBox.attr('data-h', listBox.children().length * itemH);
        for (var j = 0; j < listItems.length; j++) {
            var item = $(listItems[j]);
            if (item.data().id == id) {
                item.addClass('on');
                break;
            }
        }
        if (listBox.find('.on').length) {
            itemEl.addClass('on');
            listBox.css('height', listBox.data().h + 'px');
        }
        if (def.p_visit != 1) {
            itemEl.addClass('on');
            listBox.css('height', listBox.data().h + 'px');
        }
    }
    topicBox.on('click', '.title', function() {
        var idx = $(this).parents('li').index();
        var len = $(this).parents('ul').children().length;
        var cont = [topicList.eq(idx), topicList.eq(idx + len)];
        for (var i = 0; i < cont.length; i++) {
            if (!cont[i].length) { continue; }
            var el = cont[i].find('ul');
            if (cont[i].hasClass('on')) {
                cont[i].removeClass('on');
                el.css('height', 0);
            } else {
                cont[i].addClass('on');
                el.css('height', el.data().h + 'px');
            }
        }
    })

});