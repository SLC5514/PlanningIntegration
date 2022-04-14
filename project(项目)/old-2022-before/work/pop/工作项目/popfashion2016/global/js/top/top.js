$(function(){
    var def={
        custom:[],     //自定义数据临时存储
        custom_sta:'', //自定义参数
        count:0,       //总条数
        page:1,        //页码
        param:{        //增、删、改公共参数集合
            act:'',
            filter_id:'',
            time_range:'',
            gender:'',
            season:'',
            category:'',
            industry:''
        },
        type:'',          //请求接口类型
        dataStatus:false,  //禁止下拉频繁加载
        exist_count:0
    }
    function animateBottom(){
        var i = 0;
        var ul_ML;
        var customer=$(".js-list>ul");
        var wid=domWidth(customer);
        var next = $(".js-next-logo");
        var prve = $(".js-prev-logo");
        $(".js-list").css("width", wid.moreW + 'px');
        //计算DOM宽度
        function domWidth(dom){
            var change_1W = parseInt(dom.css("width"));
            var ulLeg = dom.length;
            var ul_marR = parseInt(dom.css("marginRight"));
            var moreW = (change_1W * ulLeg) + (ulLeg * ul_marR);
            return {"moreW":moreW,"change_1W":change_1W,"ul_marR":ul_marR,"ulLeg":ulLeg};
        }
        next.click(function () {
            if (i < wid.ulLeg - 1) {
                i++;
            }
            if(i>=2){
                i=2;
            }
            ul_ML = (i * wid.change_1W) + i * wid.ul_marR;
            $(".js-list").animate({ "marginLeft": -ul_ML + 'px' }, 500);
        })
        prve.click(function () {
            if (i > 0) {
                i--;
            }
            ul_ML = (i * wid.change_1W) + i * wid.ul_marR;
            $(".js-list").animate({ "marginLeft": -ul_ML + 'px' }, 500);
        })
    }
    function init(){
        /* 底部数据渲染 */
        getTopsReport();
        /* 登录判断 */
        if(P_UserType=='5'){
            def.type='hot';
            def.custom_sta='';
            def.dataStatus=true;
            topstyles();
        }else{
            /* 获取自定义榜单ID */
            customFilterId();
        }
        def.exist_count=$('.js-custom').data('count') || 0;
        $('.js-add').on('click',function(){
            if(P_UserType=='4'){
                if(def.exist_count==1){
                    $('.js-vip').show();
                    $('.mongolia').show();
                    return;
                }
                /* 获取初始化标签 */
                def.param.act="";
                getStyleLabels();
                def.param.filter_id=''
                $('.mongolia').show();
                $('.js-exist-layer').show();
            }else if(P_UserType=='5'){
                $('.visitor-user').slideDown(400);
            }else{
                if(def.exist_count==10){
                    $('.js-limit').show();
                    $('.mongolia').show();
                    return;
                }
                /* 获取初始化标签 */
                def.param.act="";
                getStyleLabels();
                def.param.filter_id=''
                $('.mongolia').show();
                $('.js-exist-layer').show();
            }
        })
        $('.js-add-close').on('click',function(){
            $('.mongolia').hide();
            $('.js-exist-layer').hide();
            $('.js-vip').hide();
            $('.js-limit').hide();
            $('.js-exist-list li').each(function(){
                $(this).removeClass('action');
            })
            $(".contentHolder").perfectScrollbar('destroy');
            def.param={
                act:'',
                filter_id:'',
                time_range:'',
                gender:'',
                season:'',
                category:'',
                industry:''
            }
        })
        $('.js-create').on('click',function(){
            def.param.act="modify";
            getStyleLabels();
        })
        $('.visitor-close').on('click',function(){
            $('.visitor-user').slideUp(200);
        })
        $('.js-des-close').on('click',function(){
            $('.custom-des').hide();
        })
        $('.js-topstyles span').on('click',function(){
            if(P_UserType=='5'){
                $('.visitor-user').slideDown(400);
                return;
            }
            var data=$(this).data();
            $(this).siblings().removeClass('action');
            $(this).addClass('action');
            $('.js-ordinary-user').hide();
            if(data.id=="custom"){
                $('.js-other').hide();
                $('.js-custom-box').show();
                def.custom_sta='custom';
                def.page=1;
                // def.count=$('.js-custom').data('count') || 0;
                def.dataStatus=true;
                $('.js-custom-list').html('');
                topstyles();
            }else{
                $('.js-other').show();
                $('.js-custom-box').hide();
                def.custom_sta='';
                def.type=data.id;
                def.page=1;
                def.dataStatus=true;
                if(def.custom.length==0){
                    $('.js-top-list').html('');
                }else{
                    $('.js-other-list').html('');
                }
                topstyles();
            }
            var sum=100;
            if(P_UserType=='4'){
                sum=30;
            }
            if(data.id=='hot'){
                $('.js-other-des').html('热门榜将综合为你推荐最受欢迎的'+sum+'张款式图');
            }else if(data.id=='1' || data.id=='2' || data.id=='5'){
                $('.js-other-des').html('TOP榜为你推荐'+$(this).text().substring(0,2)+'最受欢迎的'+sum+'张款式图');
            }else{
                $('.js-other-des').html('TOP榜为你推荐'+$(this).text().substring(0,2)+'地区最受欢迎的'+sum+'张款式图');
            }
        })
        /* 点击获取初始化标签 */
        $('body').on("click",'.js-edit-icon',function(){ 
            def.param.act="";
            var data=$(this).data();
            getStyleLabels(data);
        })
        $('body').on('click','.js-custom-icon',function(event){
            $('.js-edit').show();
        })
        $(document).on("click", function(e){
            if (!$(e.target).closest(".js-custom-icon").length) {
                $('.js-edit').hide();
            }
        })
        $('body').on('click','.js-delete-icon',function(){
            var id=$(this).data('filter_id');
            def.param.act="del";
            def.param.filter_id=id;
            getStyleLabels();
        })
    }
    init();
    /* 自定义列表获取所有热榜ID */
    function customFilterId(){
        $.ajax({
            url: '/top/customFilterId/',
            type: 'POST',
            dataType:'json',
            data:{
                type:'custom'
            },
            success: function(data) {
                if(data.info.filter_count > 0){
                    def.custom=data.data;
                    def.custom_sta='custom';
                    def.count=data.info.filter_count;
                }else{
                    def.type='hot';
                }
                def.dataStatus=true;
                topstyles();
            }
        })
    }
    /*榜单列表数据 */
    function topstyles(){
        if(!def.dataStatus){
            return;
        }
        $('.js-top-load').show();
        $('.js-list-btn').hide();
        def.dataStatus=false;
        var data={}
        var limit=50;
        if(P_UserType=='4'||P_UserType=='5'){
            limit=30
        }
        if(def.custom_sta=='custom'){
            data={
                type:def.custom_sta,
                filter_id:def.custom[def.page-1].filter_id
            }
        }else{
            data={
                type:def.type,
                page:def.page,
                limit:limit
            }
        }
        $.ajax({
            url: '/smarttrends/topstyles/',
            type: 'POST',
            dataType:'json',
            data:data,
            success: function(data) {
                var list=data.data
                var c_html='';
                if(def.custom_sta=='custom'){
                    if(list.length<=3){
                        c_html += '<div class="custom-item">'+
                            '<div class="head">'+
                            '<span>'+def.custom[def.page-1].title+'</span>'+
                            '<label>最'+def.custom[def.page-1].time.name+'最受欢迎的款式</label>'+
                            '</div>'+
                            '<div class="custom-no-data">'+
                            '<img src="https://imgf2.pop-fashion.com/global/images/top/no-data.png"/>'+
                            '<span>您当前数据比较少,请删除后再添加</span>'+
                            '</div>'+
                            '<a href="'+def.custom[def.page-1].detail_link+'" class="custom-btn">查看详情</a>'+
                            '<div class="custom-icon js-custom-icon">'+
                            '<div class="edit js-edit">'+
                            '<span class="edit-icon js-edit-icon"><i></i>编辑</span>'+
                            '<span class="delete-icon js-delete-icon"><i></i>删除</span>'+
                            '</div></div></div>';
                    }else{
                        c_html += '<div class="custom-item">'+
                        '<div class="head">'+
                        '<span>'+def.custom[def.page-1].title+'</span>'+
                        '<label>最'+def.custom[def.page-1].time.name+'最受欢迎的款式</label>'+
                        '</div>'+
                        '<ul class="clearfix">';
                        for(var i=0;i<list.length;i++){
                            c_html += '<li><a href="/details/style/t_'+list[i].t+'-id_'+list[i].id+'-col_'+list[i].col+'/" target="_blank">'+
                                '<img src="'+list[i].cover+'"/>'+
                                '<div class="item-hover"></div>'+
                                '</a></li>';
                        }
                        c_html += '</ul>'+
                            '<a href="'+def.custom[def.page-1].detail_link+'" class="custom-btn">查看详情</a>'+
                            '<div class="custom-icon js-custom-icon">'+
                            '<div class="edit js-edit">'+
                            '<span class="edit-icon js-edit-icon" data-filter_id="'+def.custom[def.page-1].filter_id+'" data-time_range="'+def.custom[def.page-1].iTimeRange+'"  data-gender="'+def.custom[def.page-1].iGender+'" data-season="'+def.custom[def.page-1].iSeason+'" data-category="'+def.custom[def.page-1].iCategory+'" data-industry="'+def.custom[def.page-1].iIndustry+'"><i></i>编辑</span>'+
                            '<span class="delete-icon js-delete-icon" data-filter_id="'+def.custom[def.page-1].filter_id+'"><i></i>删除</span>'+
                            '</div></div></div>';
                    }
                    $('.js-custom-list').append(c_html);
                    $('.js-list-btn').show();
                }else{
                    def.count=data.info.totalPage;
                    for(var i=0;i<list.length;i++){
                        c_html += '<a class="list-item js-prevent" href="/details/style/t_'+list[i].t+'-id_'+list[i].id+'-col_'+list[i].col+'/" target="_blank">'+
                            '<img src="'+list[i].cover+'"/>'+
                            '<div class="item-hover"></div>'+
                            '</a>';
                    }
                    if(def.custom.length==0){
                        $('.js-top-list').append(c_html);
                    }else{
                        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                        if(def.page==1 && scrollTop>=500){
                            $("html").scrollTop(500);
                        }
                        $('.js-other-list').append(c_html);
                    }
                    $('.js-prevent').on('click',function(e){
                        if(P_UserType!='5') {
                            return;
                        }
                        $('.visitor-user').slideDown(400);
                        e.preventDefault();
                    })
                    $('.js-list-btn').show();
                    if(P_UserType=='4'){
                        $('.js-ordinary-user').show();
                        $('.js-list-btn').hide();
                    }
                }
                $('.js-top-load').hide();
                def.dataStatus=true;
            }
        })
    }
    /* 内容动态居中 */
    function cen(box){
        var wid=box.outerWidth();
        box.css('margin-left','-'+wid/2+'px');
    }
    /* 筛选标签 */
    function getStyleLabels(json){
        if(def.param.act=="modify"){
            if(def.param.time_range==''){
                $('.js-tips').html('请选择时间');
                cen($('.js-tips'));
                $('.js-tips').show();
                setTimeout(function(){
                    $('.js-tips').hide();
                }, 2000);
                return;
            }
        }
        $.ajax({
            url: '/top/getStyleLabels/',
            type: 'POST',
            dataType:'json',
            data:def.param,
            success: function(data) {
                if(def.param.act=="modify"){
                    if(data.code=='200'){
                        if(def.param.filter_id != ''){
                            $('.js-tips').html('编辑成功');
                        }else{
                            $('.js-tips').html('生成成功');
                        }
                        cen($('.js-tips'));
                        $('.js-tips').show();
                        setTimeout(function(){
                            $('.js-tips').hide();
                            location.reload();
                        }, 1000);
                    }else if(data.code=='1003'){
                        $('.js-tips').html('最近关注这个方向的伙伴们太少啦，换其他试试吧！');
                        cen($('.js-tips'));
                        $('.js-tips').show();
                        setTimeout(function(){
                            $('.js-tips').hide();
                        }, 2000);
                    }else if(data.code=='1002'){
                        $('.js-tips').html('榜单已经生成过啦，你可以<a href="/top/detail/?filter_id='+data.info.filter_id+'">去看看吧</a>!');
                        cen($('.js-tips'));
                        $('.js-tips').show();
                        setTimeout(function(){
                            $('.js-tips').hide();
                        }, 2000);
                    }else if(data.code=='1004'){
                        $('.js-tips').html('生成榜单数量已满');
                        cen($('.js-tips'));
                        $('.js-tips').show();
                        setTimeout(function(){
                            $('.js-tips').hide();
                        }, 2000);
                    }else if(data.code=='1005'){
                        $('.js-tips').html('生成热榜出现异常请重试!');
                        cen($('.js-tips'));
                        $('.js-tips').show();
                        setTimeout(function(){
                            $('.js-tips').hide();
                        }, 2000);
                    }
                }else if(def.param.act=="del"){
                    location.reload();
                }else{
                    var html_time_range='',html_gender='',html_category='',html_season='',html_industry='';
                    var list=data.data
                    for(var item in list){
                        if(item=='time_range'){
                            if(list[item].length>10){
                                html_time_range += '<div class="exist-item clearfix" style="margin-bottom: 60px;">';
                            }else{
                                html_time_range += '<div class="exist-item clearfix">';
                            }
                            html_time_range += '<div class="item-head">时间</div>'+
                            '<ul class="clearfix">';
                            for(var i=0;i<list[item].length;i++){
                                if(i>=10){
                                    html_time_range += '<li data-type="'+list[item][i].type+'" data-sta="1" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }else{
                                    html_time_range += '<li data-type="'+list[item][i].type+'" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }
                            }
                            html_time_range += '</ul>';
                            if(list[item].length>10){
                                html_time_range +='<div class="more js-more">展开更多<i></i></div>';
                            }
                            html_time_range +='</div>';
                        }else if(item=='gender'){
                            if(list[item].length>10){
                                html_gender += '<div class="exist-item clearfix" style="margin-bottom: 60px;">';
                            }else{
                                html_gender += '<div class="exist-item clearfix">';
                            }
                            html_gender += '<div class="item-head">性别</div>'+
                            '<ul class="clearfix">';
                            for(var i=0;i<list[item].length;i++){
                                if(i>=10){
                                    html_gender += '<li data-type="'+list[item][i].type+'" data-sta="1" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }else{
                                    html_gender += '<li data-type="'+list[item][i].type+'" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }
                            }
                            html_gender += '</ul>';
                            if(list[item].length>10){
                                html_gender +='<div class="more js-more">展开更多<i></i></div>';
                            }
                            html_gender +='</div>';
                        }else if(item=='category'){
                            if(list[item].length>10){
                                html_category += '<div class="exist-item clearfix" style="margin-bottom: 60px;">';
                            }else{
                                html_category += '<div class="exist-item clearfix">';
                            }
                            html_category += '<div class="item-head">单品</div>'+
                            '<ul class="clearfix">';
                            for(var i=0;i<list[item].length;i++){
                                if(i>=10){
                                    html_category += '<li data-type="'+list[item][i].type+'" data-sta="1" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }else{
                                    html_category += '<li data-type="'+list[item][i].type+'" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }
                            }
                            html_category += '</ul>';
                            if(list[item].length>10){
                                html_category +='<div class="more js-more">展开更多<i></i></div>';
                            }
                            html_category +='</div>';
                        }else if(item=='season'){
                            if(list[item].length>10){
                                html_season += '<div class="exist-item clearfix" style="margin-bottom: 60px;">';
                            }else{
                                html_season += '<div class="exist-item clearfix">';
                            }
                            html_season += '<div class="item-head">季节</div>'+
                            '<ul class="clearfix">';
                            for(var i=0;i<list[item].length;i++){
                                if(i>=10){
                                    html_season += '<li data-type="'+list[item][i].type+'" data-sta="1" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }else{
                                    html_season += '<li data-type="'+list[item][i].type+'" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }
                            }
                            html_season += '</ul>';
                            if(list[item].length>10){
                                html_season +='<div class="more js-more">展开更多<i></i></div>';
                            }
                            html_season +='</div>';
                        }else if(item=='industry'){
                            if(list[item].length>10){
                                html_industry += '<div class="exist-item clearfix" style="margin-bottom: 60px;">';
                            }else{
                                html_industry += '<div class="exist-item clearfix">';
                            }
                            html_industry += '<div class="item-head">行业</div>'+
                            '<ul class="clearfix">';
                            for(var i=0;i<list[item].length;i++){
                                if(i>=10){
                                    html_industry += '<li data-type="'+list[item][i].type+'" data-sta="1" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }else{
                                    html_industry += '<li data-type="'+list[item][i].type+'" data-id="'+list[item][i].id+'"><span>'+list[item][i].name+'</span><div class="bg"></div><i></i></li>';
                                }
                            }
                            html_industry += '</ul>';
                            if(list[item].length>10){
                                html_industry +='<div class="more js-more">展开更多<i></i></div>';
                            }
                            html_industry +='</div>';
                        }
                    }
                    var html=html_time_range+html_gender+html_category+html_season+html_industry;
                    $('.js-exist-list').html('');
                    $('.js-exist-list').append(html);
                    $(".contentHolder").perfectScrollbar();
                    $(".contentHolder").scrollTop(0);
                    $('.js-more').on('click',function(){
                        var cla=$(this).parent().attr('class');
                        if(cla.indexOf('action')>0){
                            $(this).parent().removeClass('action');
                            $(this).html('展开更多<i></i>');
                            $(this).removeClass('action');
                        }else{
                            $(this).parent().addClass('action');
                            $(this).html('收起<i></i>');
                            $(this).addClass('action');
                        }
                        $(".contentHolder").perfectScrollbar('update');
                        $(".contentHolder").scrollTop(0);
                    })
                    if(json){
                        def.param.filter_id=json.filter_id;
                        def.param.time_range=json.time_range;
                        def.param.gender=json.gender;
                        def.param.season=json.season;
                        def.param.category=json.category;
                        def.param.industry=json.industry;
                        $('.js-exist-list li').each(function(){
                            var _data=$(this).data();
                            for(var item in def.param){
                                if(item==_data.type && def.param[item]==_data.id){
                                    if(_data.sta==1){
                                        $(this).parent().next().trigger('click');
                                    }
                                    $(this).addClass('action');
                                    return;
                                }
                            }
                        })
                        $('.mongolia').show();
                        $('.js-exist-layer').show();
                        $(".contentHolder").perfectScrollbar('update');
                        $(".contentHolder").scrollTop(0);
                    }
                    $('.js-exist-list ul li').on('click',function(){
                        var cla=$(this).attr('class') || '';
                        var data=$(this).data();
                        if(cla.indexOf('action')>-1){
                            $(this).removeClass('action');
                            def.param[data.type]='';
                        }else{
                            $(this).siblings().removeClass('action');
                            $(this).addClass('action');
                            def.param[data.type]=data.id;
                        }
                    })
                }
            }
        })
    }
    /* 底部爆款 */
    function getTopsReport(){
        $.ajax({
            url: '/top/getTopsReport/',
            type: 'POST',
            dataType:'json',
            data:def.param,
            success: function(data) {
                var isWidth=document.body.scrollWidth > 1500;
                var html='';
                var list=data.data;
                if(isWidth){
                    for(var i=0;i<list.length;i++){
                        if(i%5==0){
                            html += '<ul class="clearfix">';
                        }
                        html += '<li>'+
                            '<div class="img-box">'+
                            '<a class="js-prevent" href="/details/report/t_report-id_'+list[i].list.id+'-col_132/" target="_blank"><img src="'+list[i].list.cover+'" /></a>'+
                            '<div class="col-date-box">'+
                            '<a class="js-prevent" href="/details/report/t_report-id_'+list[i].list.id+'-col_132/" class="fl">'+list[i].list.dUpdateTime.substring(0,list[i].list.dUpdateTime.indexOf(' '))+'</a>'+
                            '<span class="fr">浏览（'+list[i].list.view_count+'）</span>'+
                            '</div>'+
                            '</div>'+
                            '<div class="text-labels">'+
                            '<div class="text-position">'+
                            '<p><a class="js-prevent" href="/details/report/t_report-id_'+list[i].list.id+'-col_132/" target="_blank">'+list[i].list.title+'</a></p>'+
                            '<div>';
                        for(var j=0;j<list[i].labels.length;j++){
                            html += '<span><a class="js-prevent" href="'+list[i].labels[j].dLink+'">'+list[i].labels[j].name+'</a></span>';
                        }
                        html += '</div></div></div></li>';
                        if(i%5==4){
                            html += '</ul>';
                        }
                    }
                }else{
                    for(var i=0;i<list.length;i++){
                        if(i%4==0){
                            html += '<ul class="clearfix">';
                        }
                        html += '<li>'+
                            '<div class="img-box">'+
                            '<a class="js-prevent" href="/details/report/t_report-id_'+list[i].list.id+'-col_132/" target="_blank"><img src="'+list[i].list.cover+'" /></a>'+
                            '<div class="col-date-box">'+
                            '<a class="js-prevent" href="/details/report/t_report-id_'+list[i].list.id+'-col_132/" class="fl">'+list[i].list.dUpdateTime.substring(0,list[i].list.dUpdateTime.indexOf(' '))+'</a>'+
                            '<span class="fr">浏览（'+list[i].list.view_count+'）</span>'+
                            '</div>'+
                            '</div>'+
                            '<div class="text-labels">'+
                            '<div class="text-position">'+
                            '<p><a class="js-prevent" href="/details/report/t_report-id_'+list[i].list.id+'-col_132/" target="_blank">'+list[i].list.title+'</a></p>'+
                            '<div>';
                        for(var j=0;j<list[i].labels.length;j++){
                            html += '<span><a class="js-prevent" href="'+list[i].labels[j].dLink+'">'+list[i].labels[j].name+'</a></span>';
                        }
                        html += '</div></div></div></li>';
                        if(i%4==3){
                            html += '</ul>';
                        }
                    }
                }
                $('.js-list').append(html);
                $('.js-prevent').on('click',function(e){
                    if(P_UserType!='5') {
                        return;
                    }
                    $('.visitor-user').slideDown(400);
                    e.preventDefault();
                })
                /* 底部动效 */
                animateBottom();
            }
        })
    }
    function scrollFun(){
        /* var scroll_t = $(window).scrollTop()||0; */
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
        var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
        /* 手机缩屏后高度过小 */
        var _height=1000;
        if(clientHeight<1000){
            _height=4000;
        }
        if(scrollHeight > clientHeight && scrollTop + clientHeight >= scrollHeight-_height && def.page < def.count && def.dataStatus) {
            def.page++;
            topstyles();
        }
    }
    $(window).on("scroll",function(){
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(scrollTop>=500){
            $('.js-tag').addClass('action');
        }else{
            $('.js-tag').removeClass('action');
        }
        scrollFun();
    });
})

