$(function() {
    var def = {
        col : obj_col,
        dateArr:[],
        page:1,
        length:null,
        arrIndex:0,
        is_screen_date:false,
        screen_date:null,
        totalPage:1,
        is_loading:false,
        flag:0,
        list:[]
    }
    // 筛选日历
    function beforeDay(num){
        //设置日期，当前日期的前num天
        var myDate = new Date(); //获取今天日期
        myDate.setDate(myDate.getDate() - num);
        var flag = 1;
        var weeks_html= "";
        var year,month,day;
        for(var j=0;j<myDate.getDay();j++){
            weeks_html+='<a style="visibility: hidden;"></a>';
        }
        myDate.setDate(myDate.getDate() + 1)
        for (var i = 0; i < num; i++) {
            year = myDate.getFullYear();
            month = myDate.getMonth()+1;
            day = myDate.getDate();
            if(month < 10){
                month = '0' + month;
            }
            if(day < 10){
                day = '0' + day;
            }
            if(day==1){
                weeks_html += '<a data-curdate="'+year+'-'+month+'-'+day+'">'+(myDate.getMonth()+1)+'月</a>'; 
            }else{
                weeks_html += '<a data-curdate="'+year+'-'+month+'-'+day+'">'+myDate.getDate()+'</a>'; 
            }
            myDate.setDate(myDate.getDate() + flag);
        }
        $(".js-day-date").append(weeks_html);
    }
    beforeDay(30);

    // 显示筛选日历
    $(".js-screen-date>a").on("click", function() {
        $(this).siblings(".screen-date-downlist").show();
    });
    $("body").on("click", function() {
        $(".screen-date-downlist").hide();
    });
    $(".js-screen-date").on("click", function(e) {
        e.stopPropagation();
    });

    $(".js-day-date>a").on("click", function() {
        if($(this).hasClass("no-click") || $(this).hasClass("on")){
            return false;
        }
        $(this).addClass("on").siblings("a").removeClass("on");
        def.is_screen_date = true;
        def.screen_date = $(this).data("curdate");
        $('.js-confirm-clear').data('Date',def.screen_date);
        $(".history-loading").show();
        getListFunc();
        $(".screen-date-downlist").hide();
    });

    // 滚动导航吸顶
    $(window).on('scroll', function() {
        var scrollTop = $(this).scrollTop();
        var $navTop = $(".js-history-section").offset().top;
        if(scrollTop > $navTop){
            $(".js-history-section>div").addClass("fixed-history-tab-box");
        }else{
            $(".js-history-section>div").removeClass("fixed-history-tab-box");            
        }
    });

    // 加载筛选日期
    dateFunc()
    function dateFunc() {
        def.is_loading = true;
        $.ajax({
            url: '/PersonalCenter/get_date_count/?' +Math.random(),
            type: 'post',
            dataType:'json',
            data: {'col':def.col},
            success: function(data) {
                var list_date = data.data || {};
                var info = data.info || {};
                def.flag = info.flag || "";
                for(var key in list_date){
                    if(list_date[key] == 0){
                        $(".js-day-date>a[data-curdate='"+key+"']").css("color","#d7d7d7").addClass("no-click");
                    }else{
                        // console.log()
                        def.dateArr.push(key);
                    }
                }
                if(def.flag == 0) {                 //有权限有记录
                    getListFunc();
                }else if(def.flag == 1){             //有权限无记录
                    $(".history-loading").hide();
                    $(".js-empty-history-box").show().find("p").html("你还没有浏览记录，看看下面的推荐内容吧");
                    if(def.col == 9){
                        styleGuess(82);
                    }else if(def.col == 4){
                        styleGuess(4);
                    }else{
                        reportGuess();
                    }
                }else if(def.flag == 2){              // 无权限无记录
                    $(".history-loading").hide();
                    $(".js-empty-history-box").show().find("p").html("抱歉，你当前账号为普通用户，无法查看浏览历史");
                    $(".js-screen-date").hide();
                    if(def.col == 9){
                        styleGuess(82);
                    }else if(def.col == 4){
                        styleGuess(4);
                    }else{
                        reportGuess();
                    }

                }else if(def.flag == 3){                //无权限有记录
                    $(".js-empty-history-box").show().find("p").html("你的VIP已到期，不再为你保存新的浏览历史");
                    if(def.dateArr.length > 0){                        
                        getListFunc();
                    }else{
                        $(".history-loading").hide();
                    }

                }
            }
        });
    } 

    //获取列表DOM
    var obj_date = "";      //当前日期
    function getListFunc() {
        var data ={};        
        if(def.is_screen_date){
            obj_date = def.screen_date;
            def.length = "";
        }else{
            obj_date = def.dateArr[def.arrIndex];
        }
        if(!is_scroll){
            data = {'col':def.col,date:obj_date,page:def.page}
        }else{
            data = {'col':def.col,date:obj_date,length:def.length,page:def.page}
        }
        def.is_loading = true; 
        $.ajax({
            url: '/PersonalCenter/get_p_his/?' + Math.random(),
            type: 'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if(!is_scroll){
                    allImgFunc(data)    
                }else{
                    imgFunc(data)
                }
                var load_top = $(".history-loading").offset().top;
                var win_hei = $(window).height();
                if(load_top <= win_hei){
                    continueFunc();
                }
                def.is_loading = false;
            }
        })
    }

    // 加载下一天
    function allImgFunc(data) {
        var _html = ""; 
        var tempobj = data.data || {};     
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() +1;
        var day = date.getDate();
        var cur_date = year +'年'+ month +'月'+ day +'日';
        var reg =/(\d{4})\-(\d{2})\-(\d{2})/;
        for(var key in tempobj){
            def.list = tempobj[key].list;
            def.length = tempobj[key].length;
            def.totalPage =tempobj[key].totalPage;
            var key_date = key.replace(reg,"$1年$2月$3日");
            if(key_date == cur_date){
                key_date = "今天";
            };
            date.setDate(date.getDate() - 1);
            var b_year = date.getFullYear();
            var b_month = date.getMonth() +1;
            var b_day = date.getDate();
            var before_date = b_year +'年'+ b_month +'月'+ b_day +'日';
            if(key_date == before_date){
                key_date = "昨天";
            }
            if(def.col == 4){
                _html += '<div class="browse-history-list" data-date="'+obj_date+'">';
            }else if(def.col == 9){
                _html += '<div class="browse-history-list browse-history-pattern" data-date="'+obj_date+'">';
            }else{
                _html += '<div class="browse-history-list browse-history-report" data-date="'+obj_date+'">';
            }                    
            _html += '<p><i></i>'+key_date+'</p>';
            _html += '<ul class="clearfix">';
            for(var i = 0; i < def.list.length; i++) {
                var id = def.list[i].id;
                var t = def.list[i].t;
                var col = def.list[i].col;
                var small_url = def.list[i].small_url;
                var detail_url = def.list[i].detail_url;
                var title = def.list[i].title || "";
                var sub_col = def.list[i].sub_col || "";
                if(def.col ==1){
                    _html += '<li><a href="'+detail_url+'" data-id="'+id+'" data-col="'+col+'" data-t="'+t+'" data-date="'+obj_date+'" data-sub_col="'+sub_col+'" target="_blank"><div><img src="'+small_url+'"/><p class="history-delete-layer js-history-delete-layer"><i></i></p></div><p title="'+title+'">'+title+'</p></a></li>';
                }else{
                    _html += '<li><a href="'+detail_url+'" data-id="'+id+'" data-col="'+col+'" data-t="'+t+'" data-date="'+obj_date+'" data-sub_col="'+sub_col+'" target="_blank"><div><img src="'+small_url+'"/><p class="history-delete-layer js-history-delete-layer"><i></i></p></div></a></li>';            
                }
            }
            _html += '</ul></div>';
        }
        if(def.is_screen_date){
            $(".js-browse-history-img").html(_html);
        }else{
            $(".js-browse-history-img").append(_html);
        }
    }
    
    // 加载下一页
    function imgFunc(data) {
        var list_html = "";
        var tempobj = data.data || {};
        for(var key in tempobj){
            def.list = tempobj[key].list;
            def.length = tempobj[key].length;
            def.totalPage =tempobj[key].totalPage;
            for(var i = 0; i < def.list.length; i++) {
                var id = def.list[i].id;
                var t = def.list[i].t;
                var col = def.list[i].col;
                var small_url = def.list[i].small_url;
                var detail_url = def.list[i].detail_url;
                var title = def.list[i].title || "";
                var sub_col = def.list[i].sub_col || "";
                if(def.col == 1){
                    list_html += '<li><a href="'+detail_url+'" data-id="'+id+'" data-col="'+col+'" data-t="'+t+'" data-date="'+obj_date+'" data-sub_col="'+sub_col+'" target="_blank"><div><img src="'+small_url+'"/><p class="history-delete-layer js-history-delete-layer"><i></i></p></div><p title="'+title+'">'+title+'</p></a></li>';
                }else{
                    list_html += '<li><a href="'+detail_url+'" data-id="'+id+'" data-col="'+col+'" data-t="'+t+'" data-date="'+obj_date+'" data-sub_col="'+sub_col+'" target="_blank"><div><img src="'+small_url+'"/><p class="history-delete-layer js-history-delete-layer"><i></i></p></div></a></li>';            
                }
            }
        }
        $(".browse-history-img>div[data-date='"+obj_date+"']>ul").append(list_html);             
    }

    // 滚动加载
    var is_scroll = false;
    $(window).on("scroll", function() {
        var scrollTop = $(this).scrollTop();
        var load_top = $(".history-loading").offset().top;
        var w_top = $(window).height();
        if(scrollTop + w_top >= load_top && !def.is_loading &&  def.flag != 1 && def.flag !=2){
            continueFunc();
        }
    });

    // 追加DOm
    function continueFunc() {
        if((def.arrIndex == def.dateArr.length-1 && !def.is_screen_date) || (def.is_screen_date && def.page == def.totalPage)){
            $(".history-loading").hide();
            return false;
        }
        if(def.page == def.totalPage || def.list.length == 0){
            def.arrIndex ++;
            def.page = 1;
            is_scroll = false;
        }else{
            is_scroll = true;
            def.page ++ ;
        }            
        getListFunc()
    };


    // 款式猜你喜欢
    function styleGuess(s_col) {
        var guess_html = "";
        $.ajax({
            url: '/patterns/patternRecommend/?page_size=16',
            type: 'post',
            dataType:'json',
            data: {'col': [s_col]},
            success: function (data) {
                var arr = data.data || []                
                if(def.col == 4){
                    guess_html += '<div class="browse-style-guesslike">';
                }else if(def.col == 9){
                    guess_html += '<div class="browse-pattern-guesslike browse-history-pattern">';
                }                
                guess_html += '<p><i></i>猜你喜欢</p>';
                guess_html += '<ul class="clearfix">';
                for(var i = 0; i < arr.length; i++) {
                    var id = arr[i].id || "";
                    var t = arr[i].t || "";
                    var columnId = arr[i].columnId || "";
                    var cover = arr[i].cover || "";
                    var link = arr[i].link || "";
                    var request_id = arr[i].request_id || "";
                    guess_html += '<li><a href="'+link+'" data-id="'+id+'" data-col="'+columnId+'" data-t="'+t+'" data-request_id="'+request_id+'" target="_blank"><div><img src="'+cover+'"/><p class="history-delete-layer js-history-delete-layer"></p></div></a></li>';            
                }
                guess_html += '</ul></div>';
                $(".js-browse-history-img").append(guess_html)
            }
        })
    }
    //报告猜你喜欢
    function reportGuess() {
        var guess_html = "";
        $.ajax({
            url: '/PersonalCenter/g_report/',
            type: 'get',
            dataType:'json',
            success: function (data) {
                var arr = data.data || []
                guess_html += '<div class="browse-report-guesslike browse-history-report">';                
                guess_html += '<p><i></i>猜你喜欢</p>';
                guess_html += '<ul class="clearfix">';
                for(var i = 0; i < arr.length; i++) {
                    var id = arr[i].id;
                    var t = arr[i].t;
                    var col = arr[i].col.id;
                    var cover = arr[i].cover;
                    var link = arr[i].link;
                    var title = arr[i].title;
                    guess_html += '<li><a href="'+link+'" data-id="'+id+'" data-col="'+col+'" data-t="'+t+'" target="_blank"><div><img src="'+cover+'"/><p class="history-delete-layer js-history-delete-layer"></p></div><p>'+title+'</p></a></li>';            
                }
                guess_html += '</ul></div>';
                $(".js-browse-history-img").append(guess_html)
            }
        })
    }

    // 清除数据
    $(".js-clear-condition>a").on("click", function() {
        $(".js-clear-history-layer").show();
        $(".js-bg-div").show();
    });

    // 点击清楚全部
    var del_data = {};
    $(".js-confirm-clear").on("click", function() {
        var date=$(this).data('Date') || '';
        if(date != ''){
            del_data = {col:def.col,date:obj_date};
        }else{
            del_data = {col:def.col,date:''};
        }
        
        deleteFunc(del_data, function() {window.location.reload();})
    });

    // 清楚单张
    $(".js-browse-history-img").on("click", '.js-history-delete-layer>i', function(e) {
        e.preventDefault();
        var self = $(this).parents("li");
        var t = self.find("a").data("t");
        var id = self.find("a").data("id");
        var date = self.find("a").data("date");
        var sub_col = self.find("a").data("sub_col");
        del_data = {col:def.col,date:date,t:t,id:id,sub_col:sub_col};
        deleteFunc(del_data, function() {self.remove();})
        
    })

    function deleteFunc(del_data,func) {
        $.ajax({
            url: '/PersonalCenter/del_his/',
            type: 'post',            
            data: del_data,
            success: func
        })
    }

    // 取消删除
    $(".js-cancel-clear").on('click', function() {
        $(".js-clear-history-layer").hide();
        $(".js-bg-div").hide();
    });

    // 行为上报
    $(".browse-history-list").on("click", "ul>li", function() {
        var this_t = $(this).find("a").data("t");
        var this_id = $(this).find("a").data("id");
        var request_id = $(this).find("a").data("request_id");
        var action_data = {t:this_t,id:this_id,request_id:request_id,action_type:"rec_click",userid:P_AccountId,scene_type:'personal_picture_clothing'};
        $.ajax({
			type: "get",
			dataType: "jsonp",
			url: 'https://api.pop136.com/internal/datagrand.php',
            data:action_data,
			success:function(){}
		})
    });
});