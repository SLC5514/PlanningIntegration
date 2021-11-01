$(function() {
    $('.js-new-trends-fix').hide();
    reportDataFunc();
    var win_wid = $(window).width();
    function reportDataFunc(col) {
        $.ajax({
            url:'/ajax/getEpidemicData/',
            type:'post',
            dataType:'json',
            data:{col:col},
            success:function(data){
                var list = data.data.list || [];
                var _html = "";                
                for(var i = 0; i < list.length; i++){
                    var cover = list[i].cover ? list[i].cover : "";
                    var detail_url = list[i].detail_url ? list[i].detail_url : "";
                    var title = list[i].title ? list[i].title : "";
                    var description = list[i].description ? list[i].description : "";
                    var create_time = list[i].create_time ? list[i].create_time : "";
                    create_time=create_time.substr(0,10);
                    if(win_wid > 750) {
                        _html += '<div class="report-box clearfix">';
                        _html += '<a href="'+detail_url+'" target="_blank">'
                        _html += '<div class="fl report-img">';
                        _html += '<img src="'+cover+'"/>';
                        _html += '</div>';
                        _html += '<div class="report-infor fl">';
                        _html += '<h3>'+title+'</h3>';
                        _html += '<div class="vip-only"></div>';
                        _html += '<p class="report-intro">'+description+'</p>';
                        _html += '<div class="report-bottom clearfix">';
                        _html += '<span class="fl b-date"><i></i>'+create_time+'</span>';
                        _html += '<span class="fr b-btn">立即订阅</span>';
                        _html += '</div></div></a></div>'
                    }else{
                        _html += '<div class="report-box">';
                        _html += '<a href="'+detail_url+'" target="_blank">'
                        _html += '<div class="report-img">';
                        _html += '<img src="'+cover+'"/>';
                        _html += '</div>';
                        _html += '<div class="report-infor">';
                        _html += '<h3>'+title+'</h3>';
                        _html += '<div class="report-middle clearfix">';
                        _html += '<div class="vip-only fl"></div>';
                        _html += '<span class="fr b-date"><i></i>'+create_time+'</span></div>'
                        _html += '<p class="report-intro">'+description+'</p>';    
                        _html += '<span class="b-btn">立即订阅</span></div></a></div>';
                    }
                }
                $(".js-report-list-data").html(_html);
            }
        })
    };
    
    if(win_wid > 750) {
        $(window).on("scroll", function() {
            var scrollTop = $(this).scrollTop();
            var nav_top = $(".js-report-list-nav").offset().top;
            var nav_left = $(".js-report-list-nav").offset().left;
            if(scrollTop >= nav_top){
                $(".report-tab-nav").css({'position':'fixed','left':nav_left,'top':'40px'});
            }else{
                $(".report-tab-nav").css({'position':'absolute','left':0,'top':'0'});
            }
        });

        // 筛选项
        $.ajax({
            url:'/ajax/getEpidemicColumn/',
            type:'get',
            dataType:'json',
            success: function (data) {
                var nav_list = data.data.list || ""
                var _html1 = '';
                for(var i = 0; i < nav_list.length; i++){
                    var name = nav_list[i].name ? nav_list[i].name : "";
                    var col = nav_list[i].col ? nav_list[i].col : "";
                    if(col == 'all'){
                        _html1 += '<a data-col = '+col+' class="select">'+name+'<i></i></a>';
                    }else{
                        _html1 += '<a data-col = '+col+'>'+name+'<i></i></a>'                    
                    }
                }
                $(".js-nav-data").html(_html1);
            }
        })

        // 筛选项切换
        $(".js-nav-data").on("click", 'a', function() {
            $(this).addClass('select').siblings('a').removeClass('select');
            var col = $(this).data("col") || "";
            reportDataFunc(col);
        });
    }


})