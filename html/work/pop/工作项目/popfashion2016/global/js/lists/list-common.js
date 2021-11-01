/*-----------------------新增列表页共用对象 lut000 2018/09/14---------------------------*/

// 列表页共用对象
var pop_list_page_obj={

};

/*---------------------end of change---------------------*/

$(function(){
    $('.js-question-mark').hover(function() {
      $('.js-column-msg').css({left: $(this).prev().width()-7 + 'px'}).addClass('on');
    }, function() {
      $('.js-column-msg').removeClass('on');
    })
    // 新广告位
    var linkData = $('#link').data();
    //  && linkData.url.split('/')[1] != 'inspiration' && linkData.col && linkData.col != 121
    if ($('.sp-box').length) {
      $.ajax({
        url : '/poprec/top/',
        type : 'post',
        data: {
          col: $('#link').data().col,
          params: $('#link').data().param
        },
        success : function(res){
          var resJson = JSON.parse(res);
          if (resJson.code == 0) {
            var banner = new BannerSlide({
              el: '.sp-box',
              imgList: resJson.data
            })
          } else {
            console.log(resJson.msg);
          }
        }
      });
    }

    // 无权限弹出层事件

    $('body').on('click','.js-general-user-status',function(){
        var columnid = $(this).attr("data-columnId") ? $(this).attr("data-columnId") : null;
        if(P_UserType==5){
            if(columnid == 3){
                $(".js-runway-general-title").html("想预览该场发布会图库？");
                $(".js-runway-general-intro").html("立即注册账号，您更能随时随地看全球发布会，全年各大时装周资讯及时上线");
                $(".js-runway-general-btn").show();
                $(".js-lookbook-general-btn").hide();
            }else if(columnid == 71){
                $(".js-runway-general-title").html("这是品牌产品系列展示，");
                $(".js-runway-general-intro").html("全球一线品牌、市场热门品牌、新锐设计师品牌，时尚大片品牌灵魂与当季搭配展示");
                $(".js-runway-general-btn").hide();
                $(".js-lookbook-general-btn").show();
            }
            $('.js-general-user-info-fixbox').slideDown(400);
        }
    });
    $('.js-general-user-info-fixbox>div>button').on('click',function(){
        $('.js-general-user-info-fixbox').slideUp(200);
    });

    // 滚动定位关键字搜索
    $(".js-input-search").on('focus', function(){
        $(this).siblings(".js-search-downdrop").slideDown(50);
    });
    $(".style-search-box").on('blur', function(e){
        e.stopPropagation();
        $(this).siblings(".js-search-downdrop").slideUp(50);
    });
    $("body").on('click',function(e){
        $(".js-search-downdrop").slideUp(50);
    });
    $(".js-input-search,.js-sear").on('click',function(e){
        e.stopPropagation();
    }); 

    // 滚动筛选项定位
    var $banner = $(".hot-banner-contain");
    var scroll_num = 100;    
    $(window).on("scroll", function() {
        var banner_display = $banner.css("display");
        if(banner_display == "none" || $banner.length == 0){
            scroll_num = 110;
        }else{
            scroll_num = 360;
        }
        var scrollTop = $(this).scrollTop();
        if(scrollTop > scroll_num){
            $(".js-select-common-fixed").show();
            // 筛选项下拉超出屏幕定位
            $('.js-select-common-fixed .showbox').each(function(){
                var $self = $(this);
                var $parent=$self.parent('li');
                var selfWid=$self.outerWidth(true);
                var rightWidth=parseInt(document.body.offsetWidth) -parseInt($parent.offset().left);
                var leftWidth = $parent.offset().left;
                var liWid=$parent.width();
                if(selfWid > rightWidth){
                    $self.css('left', -selfWid+liWid);
                }else if(selfWid > leftWidth){
                    $self.css('left', '0');
                }
            })
        }else{
            $(".js-select-common-fixed").hide();
        }
    })
    
     // 栏目下拉筛选
    $(".js-all-column-select").on("mouseenter", function() {
        $(".js-all-column-downlist").show();
    }).on("mouseleave", function() {
        $(".js-all-column-downlist").hide();
    });

    // 新手引导
    var new_guide = $.cookie("new_guide");
    if((new_guide == null || new_guide == undefined) && columnId != ""){
        $(".js-new-function-guide1,.js-common-bg-div").show();
    }
    // 第一步
    $(".js-step-btn1").on("click", function() {
        $(".js-new-function-guide1").hide();
        $(".js-new-function-guide2").show();
        $(".js-select-common-fixed .js-new-function-guide2").hide();
    });
    // 第二步
    $(".js-step-btn2").on("click", function() {
        $(".js-new-function-guide2").hide();
        $(".js-new-function-guide3").show();
    });
    // 第三步
    $(".js-step-btn3").on("click", function() {
        $(".js-new-function-guide3,.js-common-bg-div").hide();
        $.cookie("new_guide","1",{ expires: 365, path: '/', domain: '.pop-fashion.com'})
    });

    // 列表页收藏
    var isClick = false;
    $(".js-list-parent").on('click','.js-collect-btn', function() {
        if(isClick==false){         
            listsCollect($(this));
        }
    });
    function listsCollect(obj){
        isClick=true;
        var data_a = obj.parents('li').find('a.js-data-collect');
        var iColumnId =data_a.data('col')||'';
        var sTableName = data_a.data('t')||'';
        var iPriId = data_a.data('id')||'';
        if(obj.hasClass('cur')){            
            var curl ='/collect/setcollect/'+iColumnId+'-'+sTableName+'-'+iPriId+'/cancel/?'+Math.random(); 
        }else{
            var curl ='/collect/setcollect/'+iColumnId+'-'+sTableName+'-'+iPriId+'/?'+Math.random();
        }
        $.ajax({
            url : curl,
            type : 'get',
            dataType : 'json',
            success : function(data){
                // 取消成功
                if(parseInt(data.code) === 10){
                    obj.removeClass('cur');
                    oCommon.showTips(data.msg);
                }
                // 加入成功
                else if(parseInt(data.code) === 20) {
                    obj.addClass('cur');
                    oCommon.showTips(data.msg);
                }
                isClick=false;
            }
        });
    }
    
    
    // 有无视频版
    var link = $("#link");
    var url = link.attr("data-url");
    var param = link.attr("data-param") || "";
    var search = link.attr("data-search") || "";
    var new_url;
    var strs = param.split("-");
    // T台有视频版
    $(".js-only-video-select").on("click", function() {
        videoJumpFunc($(this));
    });

    function videoJumpFunc(obj) {
        var url_arr = [];
        var self = obj.find("a");
        if(self.hasClass('on')){
            self.removeClass('on');
            for(var i = 0; i < strs.length; i++){
                if(strs[i] != 'ver_video'){
                    url_arr.push(strs[i]);
                }
            }
            if(search == ""){
                new_url = url + url_arr.join("-") + '/#anchor';
            }else{
                if(url_arr.length == 0){
                    new_url = url + url_arr.join("-") +search+ '#anchor';                
                }else{
                    new_url = url + url_arr.join("-") + '/' +search+ '#anchor';
                }
            }
        }else{
            self.addClass('on');
            if(param == ""){
                new_url = search == "" ? url +'ver_video/#anchor' : url +'ver_video/'+search+'#anchor';
            }else{
                if(param.indexOf("ver_") != -1){
                    for(var i = 0; i < strs.length; i++){                    
                        if(strs[i].indexOf('ver_') != -1){                        
                            strs[i] = 'ver_video';
                        }
                        url_arr.push(strs[i])
                    }
                }else{
                    url_arr.push('ver_video')
                    for(var i = 0; i < strs.length; i++){
                        url_arr.push(strs[i])
                    }
                }                        
                
                new_url = search == "" ? url + url_arr.join("-") + '/#anchor' : url + url_arr.join("-")+'/'+ search + '#anchor';
            }
        }
        window.location.href = new_url;
    }    
});