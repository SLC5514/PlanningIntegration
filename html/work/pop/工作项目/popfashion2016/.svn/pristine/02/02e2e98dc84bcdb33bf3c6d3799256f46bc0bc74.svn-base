// 搜索页
;$(function(){
    var jsSearch = $.trim($('#jsString').val());
    if (jsSearch) {
		var params = $('#params').val();
		params = params ? params + '/' : '';
		var keywords = $.trim($('#keyword').text());
        keywords = keywords ? '?key=' + encodeURIComponent(keywords) : '';	//encodeURIComponent(encodeURIComponent('女装'));

        $.get('/ajax/getsearchdata/' + params + keywords, {cols : jsSearch, time: Math.random()}, function(data){
			var total = 0;
			$('.num_bg').each(function(){
				var $self = $(this);
				total += parseInt($self.text());
			});
            if (typeof data.book !== 'undefined') {
                total += parseInt(data.book.cnt);
            }
            if (typeof data.look !== 'undefined') {
                total += parseInt(data.look.cnt);
            }
            if (typeof data.orderm !== 'undefined') {
                total += parseInt(data.orderm.cnt);
            }
            if (typeof data.fore !== 'undefined') {
                total += parseInt(data.fore.cnt);
            }
            if (typeof data.insp !== 'undefined') {
                total += parseInt(data.insp.cnt);
            }
            if (typeof data.refe !== 'undefined') {
                total += parseInt(data.refe.cnt);
            }
            if (typeof data.runw !== 'undefined') {
                total += parseInt(data.runw.cnt);
            }
            if (typeof data.patterns !== 'undefined') { // 图案
                total += parseInt(data.patterns.cnt);
            }
            if (typeof data.other !== 'undefined') {  // 其他
                total += parseInt(data.other.cnt);
            }
            $('#total').html(total + ' <font style="color: black; font-weight: normal;">条</font>');
            $('#main').html(template('tremain', data));
            $(".lazyload img").lazyload({effect : "fadeIn"});
            reportFunc();
            lastLi();
            // console.log(data);
        },'json');
    }
});
// 款式最后一个数据
function lastLi(){
    if($(".con_width").width()==1200){
        if($(".js-style-main li").length>=6){
            $(".js-style-main li").last().addClass('last-hide');
        }
        if($(".js-analysis-main li").length>=4){
            $(".js-analysis-main li").last().addClass('last-hide');
        }
        if($(".js-trend-main li").length>=4){
            $(".js-trend-main li").last().addClass('last-hide');
        }
        if($(".js-runway-main li").length>=6){
            $(".js-runway-main li").last().addClass('last-hide');
        }
        if($(".js-books-main li").length>=6){
            $(".js-books-main li").last().addClass('last-hide');
        }
        if($(".js-lookbook-main li").length>=6){
            $(".js-lookbook-main li").last().addClass('last-hide');
        }
        if($(".js-ordermeeting-main li").length>=6){
            $(".js-ordermeeting-main li").last().addClass('last-hide');
        }
        if($(".js-tp-main li").length>=6){
            $(".js-tp-main li").last().addClass('last-hide');
        }
    }else{
        $(".js-style-main li").last().removeClass('last-hide');
        $(".js-analysis-main li").last().removeClass('last-hide');
        $(".js-trend-main li").last().removeClass('last-hide');
        $(".js-runway-main li").last().removeClass('last-hide');
        $(".js-books-main li").last().removeClass('last-hide');
        $(".js-lookbook-main li").last().removeClass('last-hide');
        $(".js-ordermeeting-main li").last().removeClass('last-hide');
        $(".js-tp-main li").last().removeClass('last-hide');
    }
}
lastLi();
$(window).resize(function(){
    lastLi();
});
// 报告简介交互效果
$(".js-analysis-main").on('mouseenter mouseleave',function(e){
    if(e.type=='mouseenter'){
        $(this).find(".js-ay-img-description").stop(true).slideDown(100);
    }else{
        $(this).find(".js-ay-img-description").stop(true).slideUp(100);
    }
});
function reportFunc(){
    
    $(".js-trend-main li").on('mouseenter mouseleave',function(e){
        if(e.type=='mouseenter'){
            $(this).find(".js-tr-img-description").stop(true).slideDown(100);
        }else{
            $(this).find(".js-tr-img-description").stop(true).slideUp(100);
        }
    });
    // 报告详情介绍截取
    $(".ay-img-description").each(function(){
        var report_str =$(this).text() || '';
        var wid =$(".con_width").width();
        var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,532,12)
        $(this).text(report_new_str);
    });
    $('.js-major').each(function(){
        var report_str =$(this).text() || '';
        var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,380,20)
        $(this).text(report_new_str);
    })
}
reportFunc();

//点击图片和标题跳转到二级列表页
/* $(".all_search_main").on("click",".click_jump, .yl, .wz",function() {
    var $self = $(this).parents('li').children('a');
    var _url = $self.data('url');
    var _tab = $self.data('t');
    var _id = $self.data('id');
    var _yl = $self.data('yl');
    var _col = $self.data('col');

    window.open(_url+encodeURIComponent('id_'+_id+'-t_'+_tab+'-yl_'+_yl+'-col_'+_col)+'/');
}); */

/* $('.js_station_item').each(function(){
    var imgBox=$(this).find('img');
    var i=imgBox.length;
    if(i==1){
        imgBox.addClass('state');
    }
}) */

//更多内容
var searchKey = "";
var arr = {
    col:'',
    gen:'',
    ind:'',
    sea:'',
    match:''
};
decoFun();
var pathname = location.pathname;
var search = location.search.split('=')[1] || '';
var params = pathname.split('/')[2] || '';
var data={}
data.col=arr['col'];
if(params){
    data.params=params;
}
if(search){
    data.key=decodeURIComponent(decodeURIComponent(search));
}
// 筛选项
$.ajax({
    url: '/search/filterconditions',
    type: 'POST',
    data: data,
    success: function(resHtml) {
        $('.js_select_items_box').html(resHtml);
        $('.js-search_head a').each(function(){
            var num=$(this).data('num');
            var id=$(this).data('id');
            var _id=$('.js-all-num').data('id');
            if(id == _id){
                $('.js-all-num').text(num);
            }else if(id == '82' && (_id == '82' || _id == '120')){
                $('.js-all-num').text(num);
            }
        });

        // 搜索引擎优化
        initHistoryHot();
        scrolldoc();

        $('.js-search-tips').on('click', '.sel', function() {
            window.location.href = "/search/?key=" + encodeURIComponent(encodeURIComponent($(this).text()));
        })

        $('.js-search-ipt')
        .on('click', function(e) {
            e.stopPropagation();
        })
        .on('focus', function() {
            if ($(this).val()) {
                $(this).siblings('.search_listDown').find('.menu-hot-list').hide();
                $(this).autocomplete("search");
            } else {
                initIpt($(this).siblings('.search_listDown'));
            }
        })
        .on('input', function() {
            if ($(this).val()) {
                $(this).siblings('.search_listDown').find('.menu-hot-list').hide();
            } else {
                initIpt($(this).siblings('.search_listDown'));
            }
        })
        .on('keydown', function(e) {
            var listDown = $(this).siblings('.search_listDown');
            var historyLen = listDown.find('.menu-hot-list .history-list li').length;
            var historyCount = listDown.find('.menu-hot-list .history-list li.ui-state-focus').index();
            var hotCount = listDown.find('.menu-hot-list .hot-list li.ui-state-focus').index();
            var count = historyCount != -1 ? historyCount : (hotCount != -1 ? historyLen + hotCount : -1);
            if (e.keyCode == 40) {
                count++;
                if (count > 9) {
                    count = 0;
                }
                listDown.find('.menu-hot-list li').removeClass('ui-state-focus').eq(count).addClass('ui-state-focus');
            } else if (e.keyCode == 38) {
                count--;
                if (count < 0) {
                    count = 9;
                }
                listDown.find('.menu-hot-list li').removeClass('ui-state-focus').eq(count).addClass('ui-state-focus');
            } else if (e.keyCode == 13) {
                if (count == -1 || $(this).val()) {
                    return;
                } else {
                    listDown.find('.menu-hot-list li').eq(count).trigger('click');
                }
            }
        })

        // 清除历史记录
        $('.js-clear-operat').click(function(e) {
            e.stopPropagation();
            $('.js-search-ipt').focus();
            initHistoryHot('', 'clear');
            return false;
        })
        // 全站搜索样式
        $(".js-search-ipt").on('focus', function (e) {
            e.stopPropagation();
            $('.tel-icon').hide();
        });
        $(document).scroll(function () {
            if ($('.js-search-ipt').is(':focus')) {
                $('.js-search-ipt').blur();
                $("body").trigger('click');
            }
        });

        $("body").on('click', function (e) {
            var itext = $(".js-search-ipt");
            var key = itext.val();
            $('.search_listDown').find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
            $('.tel-icon').show();
        });

        $('.js-search-ipt').on('keypress', function(event){
            if(event.which == 13){
                var keywords = $.trim($(this).val());
                if(keywords == '' || keywords == '搜索你想要的') {
                    return false;
                }
                if(keywords){
                    window.location.href = "/search/"+oCommon.getGenIndInfo()+"?key="+encodeURIComponent(encodeURIComponent(keywords));
                    searchHis(keywords);
                    initHistoryHot(keywords);
                }
            }
        })
        $(".js-search-btn").on('click', function (e) {
            var keywords = $.trim($(this).parent().children(".js-search-ipt").val());
            if(keywords == '' || keywords == '搜索你想要的') {
                return false;
            }
            if(keywords){
                window.location.href = "/search/"+oCommon.getGenIndInfo()+"?key="+encodeURIComponent(encodeURIComponent(keywords));
                searchHis(keywords);
                initHistoryHot(keywords);
            }
        });
        var boxSel = $(".contentHolder");
        $('.js-figure-btn1').on('click',function(){
            $("#uploadImg").trigger("click");
        })
        boxSel.perfectScrollbar();
    },
    error: function(err) {}
})
// 搜索引擎优化相关事件
// 热门推荐数据
var hotRec = [];
// 搜索记录
function searchHis(val) {
    $.ajax({
        url: '/suggestion/story/',
        method: 'post',
        data: {
            keyword: val
        }
    })
}
// 初始化热门
function initHistoryHot(val, type) {
    // 历史记录
    var history = pop_fashion_global.fn.getSto('search-history') || [];
    if (type == 'del') {
        var idx = history.indexOf(String(val));
        if (idx != -1) {
            history.splice(idx, 1);
            pop_fashion_global.fn.setSto('search-history', history);
        }
    } else if (type == 'clear') {
        history = [];
        pop_fashion_global.fn.setSto('search-history', history);
    } else if (val) {
        var idx = history.indexOf(String(val));
        if (idx != -1) {
            history.splice(idx, 1);
        }
        history.unshift(String(val));
        if (history.length > 5) {
            history.pop();
        }
        pop_fashion_global.fn.setSto('search-history', history);
    }
    var historyStr = '';
    history.forEach(function(v) {
        historyStr += '<li class="ui-menu-item" data-label="' + v + '">' + v + '<div class="fr"><i class="his-clear"></i></div></li>';
    })
    $('.search_listDown').find('.history-list').html(historyStr);
    if (!historyStr) {
        $('.js-history-label').hide();
    } else {
        $('.js-history-label').show();
    }
    // 热门推荐
    var hotStr = '';
    var count = 10 - history.length;
    for (var i = 0; i < count; i++) {
        hotStr += '<li class="ui-menu-item" data-label="' + (hotRec[i] && hotRec[i].keyword || '暂无数据') + '"><i class="serial"></i>' + (hotRec[i] && hotRec[i].keyword || '暂无数据') + '</li>';
    }
    $('.search_listDown').find('.hot-list').html(hotStr);
    // 初始化事件
    initHisHotFn();
}
// 搜索优化事件初始
function initHisHotFn () {
    $('.js-search-ipt').siblings('.search_listDown').find('.menu-hot-list li').hover(function() {
        var listDown = $(this).parents('.search_listDown');
        var historyLen = listDown.find('.menu-hot-list .history-list li').length;
        var selfCount = $(this).index();
        var isHistory = $(this).parents('.history-list').length;
        var isHot = $(this).parents('.hot-list').length;
        var count = isHistory ? selfCount : (isHot ? historyLen + selfCount : -1);
        if (count != -1) {
            listDown.find('.menu-hot-list li').removeClass('ui-state-focus').eq(count).addClass('ui-state-focus');
        } else {
            $(this).addClass('ui-state-focus');
        }
    }, function() {
        $(this).parents('.search_listDown').find('.menu-hot-list li').removeClass('ui-state-focus');
    }).on('click', function(e) {
        e.stopPropagation();
        if ($(this).data().label == '暂无数据') {
            return false;
        }
        $('.js-search-ipt').val($(this).data().label);
        $(this).parents('.search_listDown').find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
        $('.tel-icon').show();
        $('.js-search-ipt').blur();
        window.location.href = "/search/?key=" + encodeURIComponent(encodeURIComponent($(this).data().label));
        searchHis($(this).data().label);
        initHistoryHot($(this).data().label);
        return false;
    }).find('.his-clear').on('click', function(e) {
        e.stopPropagation();
        $('.js-search-ipt').focus();
        initHistoryHot($(this).parents('.ui-menu-item').data().label, 'del');
        return false;
    })
}
// 搜索品牌初始
function scrolldoc () {
    $('.js-search-ipt').each(function (i) {
        var _class;
        var $self = $(this);
        // head_topx42	滚动
        if (i) {
            _class = '.menu:last';
        }
        // head_topx40	刚进去
        else {
            _class = '.menu:first';
        }
        var $box = $self;
        $box.autocomplete({
            source: getBrandsData,
            appendTo: _class,
            minLength: 0,
            delay: 300,
            position: { my: "left0 top+50", at: "left top" },
            _renderItem: function (ul, item) {
                return $("<li>")
                    .data("id", item.value)
                    .append(item.label)
                    .appendTo(ul);
            },
            _renderMenu: function (ul, items) {
                $(this).val(ui.item.label).data("id", ui.item.value);
            },
            select: function (event, ui) {
                $(this).val(ui.item.label);
                $(this).val(ui.item.label).data("id", ui.item.value);
                $(".js-search-ipt").blur();
                if (ui.item.label && ui.item.value) {
                    window.location.href = "/search/?key=" + encodeURIComponent(encodeURIComponent(ui.item.label));
                    searchHis(ui.item.label);
                    initHistoryHot(ui.item.label);
                }
                return false;
            },
            focus: function (event, ui) {
                event.stopPropagation();
                return false;
            },
            open: function (event, ui) {
                if (!$self.is(':focus')) {
                    $box.autocomplete('close');
                    event.stopPropagation();
                    return false;
                }
                $('.search_listDown').eq(i).find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
                var $scroll = $('#ui-id-' + parseInt(i + 1));
                $scroll.perfectScrollbar('destroy');
                $scroll.perfectScrollbar();
            },
            close: function (event, ui) {
                if ($.trim($self.val()) == '' || $.trim($self.val()) == '时尚资讯一网打尽') {
                    if ($(this).is(':focus')) {
                        $('.search_listDown').eq(i).find('.menu-hot-list').show();
                    } else {
                        $('.search_listDown').eq(i).find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
                    }
                }
            }
        });
    });
}
// 输入框状态初始
var iptOut = null;
function initIpt(listDown) {
    if (!hotRec.length) {
        $('.search_listDown').find('.hot-list').html('<div class="loading">加载中...</div>');
        $.ajax({
            url: '/suggestion/index/',
            method: 'post',
            data: {
                keyword: ''
            },
            success: function(res) {
                var resJson = res;
                if (typeof res == 'string') {
                    resJson = JSON.parse(res);
                }
                if (resJson.code == 0) {
                    hotRec = resJson.data;
                }
                initHistoryHot();
            },
            error: function() {
                $('.search_listDown').find('.hot-list').html('<div class="loading">加载失败!</div>');
            }
        })
    } else {
        initHistoryHot();
    }
    clearTimeout(iptOut);
    iptOut = setTimeout(function(){
        listDown.find('.menu-hot-list').show();
    }, 600);
}
var brandCache = {};
function getBrandsData (request, resposeCallback) {
    var text = $.trim(request.term);
    if (!text) {
        resposeCallback([]);
        return;
    } else if (brandCache[text]) {
        resposeCallback(brandCache[text]);
        return;
    }
    $.ajax({
        url: '/suggestion/index/',
        method: 'post',
        data: {
            keyword: text
        },
        success: function(res) {
            var resJson = res;
            if (typeof res == 'string') {
                resJson = JSON.parse(res);
            }
            if (resJson.code == 0) {
                var list = []
                resJson.data.forEach(function(v) {
                    list.push({
                        label: v.keyword,
                        value: v.score
                    })
                })
                brandCache[text] = list;
                resposeCallback(list);
            }
        }
    })
}


if($('.more_box').length>0){
    client();
}
function client(){
    var i = 0;
    var j = 0;
    var ul_ML;
    var mainUl = $('.main-ul');
    var next = $(".i-right");
    var prve = $(".i-left");
    var customer=$(".js-more>li");
    var wid=domWidth(customer);
    $('.js-more').css("width", wid.moreW + 'px');
    var screenWidth = document.documentElement.clientWidth;
    if(screenWidth <= 1500){
        j = 6;
    } else {
        j = 7;
    }
    //计算DOM宽度
    function domWidth(dom){
        var change_1W = parseInt(dom.css("width"));
        var ulLeg = dom.length;
        var ul_marR = parseInt(dom.css("marginRight"));
        var ul_padR = parseInt(dom.css("paddingRight"));
        var ul_padL = parseInt(dom.css("paddingLeft"));
        var moreW = (change_1W + ul_marR + ul_padR + ul_padL) * ulLeg;
        var wid=mainUl.css("width");
        if(moreW <= parseInt(wid.substring(0,wid.indexOf('px')))+20 ){
            next.hide();
            prve.hide();
        }
        return {"moreW":moreW,"change_1W":change_1W,"ul_marR":ul_marR,"ulLeg":ulLeg,"ul_padR":ul_padR,"ul_padL":ul_padL};
    }
    next.click(function () {
        if (i <= wid.ulLeg - j) {
            i++;
        }
        ul_ML = (i * wid.change_1W) + i * wid.ul_marR + i * wid.ul_padR + i * wid.ul_padL;
        $('.js-more').animate({ "marginLeft": -ul_ML + 'px' }, 500);
    })

    prve.click(function () {
        if (i > 0) {
            i--;
        }
        ul_ML = (i * wid.change_1W) + i * wid.ul_marR + i * wid.ul_padR + i * wid.ul_padL;
        $('.js-more').animate({ "marginLeft": -ul_ML + 'px' }, 500);
    })
}

//分解url
function decoFun(){
    var list = [];
    var pathname = window.location.href;
    if(pathname.indexOf("key") > 0){
        var len=pathname.indexOf("#") == -1 ? pathname.length : pathname.indexOf("#");
        searchKey = pathname.substring(pathname.indexOf("key")+4,len);
    }
    var str = pathname.substring(pathname.indexOf('search/')+7,pathname.length);
    str=str.substring(0,str.indexOf('/'));
    if(str.length>0){
        list=str.split('-');
    }
    if(list.length>0){
        for(var i=0;i<list.length;i++){
            var name=list[i].substring(0,list[i].indexOf('_'));
            var val=list[i].substring(list[i].indexOf('_')+1,list[i].length);
            arr[name]=val;
        }
    }
}

$("body").on('click','.js-jump',function(){
    var name = $(this).data("name");
    var val = $(this).data("id");
    arr[name] = val;
    //图案栏目删除季节
    /* if(name == "col" && (val == 82 || val == 120)){
        arr['sea'] = '';
    } */
    //cookie
    if(name == "gen"){
        if(arr['gen'] == ''){
            $.cookie('gender', null, { path:'/', domain:'.pop-fashion.com', expires:-1 });
        }else{
            $.cookie('gender', arr['gen'], {domain:'.pop-fashion.com', path:'/'});
        }
    }else if(name == "ind"){
        if(arr['ind'] == ''){
            $.cookie('industry', null, { path:'/', domain:'.pop-fashion.com', expires:-1 });
        }else{
            $.cookie('industry', arr['ind'], {domain:'.pop-fashion.com', path:'/'});
        }
    }
    var url = 'https://www.pop-fashion.com/search/';
    location.href = url + getUrl();
})

function getUrl(){
    var url = '';
    if(JSON.stringify(arr) != "{}"){
        for(var item in arr){
            if(item!='page'){
                if(arr[item] == 'all'){
                    arr[item] = '';
                }
                if(arr[item] != ''){
                    url += item + '_' + arr[item] + '-';
                }
            }
        }
        if(url){
            url = url.substring(0,url.length-1) + '/';
        }
        if(searchKey != ""){
            url += '?key=' + searchKey;
        }
    }else{
        if(searchKey != ""){
            url += '?key=' + searchKey;
        }
    }
    return url;
}


function IEVersion() {
    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串  
    var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1; //判断是否IE<11浏览器  
    var isEdge = userAgent.indexOf("Edge") > -1 && !isIE; //判断是否IE的Edge浏览器  
    var isIE11 = userAgent.indexOf('Trident') > -1 && userAgent.indexOf("rv:11.0") > -1;
    if(isIE) {
        var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
        reIE.test(userAgent);
        var fIEVersion = parseFloat(RegExp["$1"]);
        if(fIEVersion == 7) {
            return 7;
        } else if(fIEVersion == 8) {
            return 8;
        } else if(fIEVersion == 9) {
            return 9;
        } else if(fIEVersion == 10) {
            return 10;
        } else {
            return 6;//IE版本<=7
        }   
    } else if(isEdge) {
        return 'edge';//edge
    } else if(isIE11) {
        return 11; //IE11  
    }else{
        return -1;//不是ie浏览器
    }
}



//后三个内容异步加载
var sta=false;
var have_column=$('#have_column').val();
var list=have_column.split(',');
var sum=0;
function getSearchAll(){
    var url = getUrl();
    var key = '';
    var params = '';
    if(url.indexOf('key=')>0){
        key = url.substring(url.indexOf('key=')+4,url.length);
        params=url.substring(0,url.indexOf('/?key'));
    }else{
        params=url.substring(0,url.indexOf('/'));
    }
    sta = true;
    var column=list[sum];
    $('.js-search-load').show();
    $.ajax({
        url: '/search/getsearchall/',
        type: 'POST',
        data: {
            params: params,
            key: key,
            column: column
        },
        success: function(data) {
            var data=data;
            if(IEVersion() != -1){
                data = $.parseJSON(data);
            }
            var allHtml='';
            sta = false;
            $('.js-search-load').hide();
            sum++;
            if(data.code=='0'){
                for(var item in data.data){
                    if(item == 82){
                        var list=data.data[item].data || [];
                        if(list.length > 0){
                            allHtml += '<div class="pattern_box box js-box-style">'
                                +'<div class="main-title">图案<span>PATTERN</span></div>'
                                +'<div class="new_pattern clearfix">';
                            for(var i=0;i<list.length;i++){
                                allHtml += '<div data-url="' + list[i].detailUrl + '" class="pattern_item js-item">'
                                    +'<a href="' + list[i].detailUrl + '" target="_blank" class="img_box">'
                                    +'<img src="' + list[i].cover + '">'
                                    +'<div class="time"></div>'
                                    +'<span>' + list[i].publish_time + '</span>'
                                    +'<div class="shuiyin"></div>'
                                    +'</a>'
                                    +'<div class="js-page-text">'
                                    +'<div class="label js-label">';
                                for(var j=0;j<list[i].labels.length;j++){
                                    if(j == list[i].labels.length-1){
                                        allHtml += '<a href="' + list[i].labels[j].lLink + '" target="_blank">' + list[i].labels[j].name + '</a>';
                                    }else{
                                        allHtml += '<a href="' + list[i].labels[j].lLink + '" target="_blank">' + list[i].labels[j].name + '</a><s></s>';
                                    }
                                }
                                allHtml += '</div></div></div>'
                            }
                            allHtml += '</div>';
                            if(data.data[item].more_url){
                                allHtml += '<a href="' + data.data[item].more_url + '" class="more-btn"><span>查看更多<i></i></span></a>';
                            }
                            allHtml += '</div>';
                        }
                    }else if(item == 3){
                        var list=data.data[item].data || [];
                        if(list.length > 0){
                            allHtml += '<div class="station_box box">'
                                +'<div class="main-title">T台<span>T STATION</span></div>'
                                +'<div class="new_station clearfix lazyload">';
                            for(var i=0;i<list.length;i++){
                                allHtml += '<a href="' + list[i].detailUrl + '" class="station_item js_station_item" target="_blank"><div class="cover">' + list[i].cover
                                    +'</div><div class="station">'
                                    +'<div class="station_title js-station_title">' + list[i].title + '</div>'
                                    +'<div class="details">'
                                    +'<span>设计师：' + list[i].brand_name + '</span>'
                                    +'<span>时间：' + list[i].seasonName + '</span>'
                                    +'<span>地点：' + list[i].regionName + '</span>'
                                    +'</div>'
                                    +'<span class="time">' + list[i].publish_time + '</span>'
                                    +'<span class="browse">浏览（' + list[i].view_count + '）</span>'
                                    +'</div></a>'
                            }
                            allHtml += '</div>';
                            if(data.data[item].more_url){
                                allHtml += '<a href="' + data.data[item].more_url + '" class="more-btn"><span>查看更多<i></i></span></a>';
                            }
                            allHtml += '</div>';
                        }
                    }else if(item == 71){
                        var list=data.data[item].data || [];
                        if(list.length > 0){
                            allHtml += '<div class="ad_box box">'
                                +'<div class="main-title">广告大片<span>BIOCKBUSTER</span></div>'
                                +'<div class="new_ad hei clearfix">';
                            for(var i=0;i<list.length;i++){
                                allHtml += '<div class="ad_item" target="_blank">'
                                    +'<div class="img_box">'
                                    +'<img src="' + list[i].cover + '" />'
                                    +'<div class="hover-link">';
                                if(list[i].sBuyingLinks){
                                    allHtml+='<div class="buy-link">';
                                }else{
                                    allHtml+='<div>'
                                }
                                if(list[i].iPreviewMode == 1){
                                    if(P_UserType=='5'){
                                        allHtml += '<a class="wz js-general-user-status" href="javascript:void(0);" title="免费预览"><i></i>免费预览</a>';
                                    }else{
                                        allHtml += '<a itemprop="full browser" class="wz" href="' + list[i].detailUrl + '" target="_blank"><i></i>免费预览</a>';
                                    }
                                }else{
                                    if(P_UserType=='5'){
                                        allHtml += '<a class="wz js-general-user-status" href="javascript:void(0);" title="完整浏览"><i></i>完整浏览</a>';
                                    }else{
                                        allHtml += '<a itemprop="full browser" class="wz" href="' + list[i].detailUrl + '" target="_blank"><i></i>完整浏览</a>';
                                    }
                                }
                                if(list[i].sBuyingLinks){
                                    allHtml += '<a href="" class="buy" target="_blank" title="购买"><i></i>购买</a>';
                                }
                                allHtml += '</div>'
                                    +'<p class="update-time"><img src="https://imgf2.pop-fashion.com/global/images/lists/books-list/time.png">' + list[i].publish_time + '</p>'
                                    +'</div></div>'
                                    +'<div class="ad">'
                                    +'<span class="js-ad-span">' + list[i].title + '</span>'
                                    +'</div></div>';
                            }
                            allHtml += '</div>';
                            if(data.data[item].more_url){
                                allHtml += '<a href="' + data.data[item].more_url + '" class="more-btn"><span>查看更多<i></i></span></a>';
                            }
                            allHtml += '</div>';
                        }
                    }
                }
            }
            $('.js_search_main').append(allHtml);
            //图片懒加载
            $('.lazyload img').lazyload({
                effect: "fadeIn",
                threshold: 200
            });
            $('.js-ad-span').each(function(){
                var report_str =$(this).text() || '';
                var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,260,14)
                $(this).text(report_new_str);
            })
            $('.js-station_title').each(function(){
                var report_str =$(this).text() || '';
                var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,400,14)
                $(this).text(report_new_str);
            })
        },
        error: function(err) {}
    })
}
//图片懒加载
$('.lazyload img').lazyload({
    effect: "fadeIn",
    threshold: 200
});
$('body').on('click','.js-general-user-status',function(){
    $('.js-general-user-info-fixbox').slideDown(400);
});
$('.js-general-user-info-fixbox>div>button').on('click',function(){
    $('.js-general-user-info-fixbox').slideUp(200);
});
$('.js-ad-span').each(function(){
    var report_str =$(this).text() || '';
    var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,260,14);
    $(this).text(report_new_str);
})
$('.js-station_title').each(function(){
    var report_str =$(this).text() || '';
    var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,400,14);
    $(this).text(report_new_str);
})
$(window).on("scroll",function(){
    var scroll_t = $(window).scrollTop()||0;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
    var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;

    if(scrollHeight > clientHeight && scrollTop + clientHeight >= (scrollHeight - 1500) && sum < list.length && !sta){
        if(arr['col'] == 'all' || arr['col'] == ''){
            getSearchAll();
        }
    }
});



