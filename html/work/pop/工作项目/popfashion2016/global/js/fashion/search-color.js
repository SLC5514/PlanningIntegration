var def={
    getColorSta:true,           //二级颜色未加载成功前禁止点击事件
    search:{                    //搜索接口参数
        aco:[],
        col:['50','82'],
        gen:'',
        ind:'',
        sea:'',
        cat:'',
        con:'',
        isNumCheck:'',          //是否计数。vip不要传递
        page:1
    },
    total:1,            //总页数
    col_obj:[],         //选择的颜色存储
    extra_obj:[],           //临时存储选择的颜色
    dataStatus:false,       //是否还在加载数据
    colorStatus:false,      //返回颜色选择页面后禁止滚动加载数据
    is_model:true,          //为选择一级款式标签
    is_pattern:true         //未选择一级图案标签
}
$('.js-screen-btn').on('click',function(event){
    var sta=$(this).attr('class');
    if(sta.indexOf('action')>0){
        $(this).removeClass('action');
    }else{
        $(this).addClass('action');
        if($($('.js-cat-list')[1]).width()<501){
            $('.js-cat-list').next().hide();
        }
        if($($('.js-sea-list')[1]).width()<501){
            $('.js-sea-list').next().hide();
        }
        if($($('.js-ind-list')[1]).width()<501){
            $('.js-ind-list').next().hide();
        }
    }
    event.stopPropagation();
})
$('.screen-list').on('click',function (e) {
    e.stopPropagation();
})
$(document).bind("click", function(e){
    if (!$(e.target).closest(".js-screen-btn").length) {
        $('.js-screen-btn').removeClass('action');
    }
})
function scrollFun(){
    var scroll_t = $(window).scrollTop()||0;
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
    var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
    if (scroll_t <= 80) {
        $('.js-head-slide').css('visibility','hidden');
    }else{
        $('.js-head-slide').css('visibility','visible');
    }
    $('.menu-hot-list').hide();
    $('.js-screen-btn').removeClass('action');
    masonryFun();
    if(scrollHeight > clientHeight && scrollTop + clientHeight >= scrollHeight-1000 && def.search.page < def.total && def.dataStatus && def.colorStatus) {
        def.search.page++;
        getList();
    }
}
$(window).on("scroll",function(){
    var scrollTop = $(this).scrollTop();
    var scrollHeight = $(document).height();
    var windowHeight = $(this).height();
    if (scrollTop + windowHeight > scrollHeight-100) {
        $('.js-color-select').addClass('action');
    } else {
        $('.js-color-select').removeClass('action');
    }
    scrollFun();
});
function init(){
    getColor();
    getSecondColor(1);
    reWid();
}
init();
//筛选项数据获取
function getFilter(){
    $.ajax({
        url: '/colorsearch/get_filter/',
        type: 'POST',
        data:def.search,
        dataType: 'json',
        success: function(data) {
            $('.js-cat-list').next().show();
            $('.js-sea-list').next().show();
            $('.js-ind-list').next().show();
            $('.js-pat-list').parent().show();
            $('.js-model-list').show();
            var list=data.data.filters;
            var html_cat="",html_sea="",html_ind="",html_gen="",html_pat="";
            for(var item in list){
                if(item=='sCategory' && list[item]){
                    if(list[item]){
                        html_cat+='<span class="js-item action cat-all" data-item="cat" data-id="all">全部</span>';
                        for(var item1 in list[item]){
                            html_cat+='<span class="js-item cat-'+item1+'" data-item="cat" data-id="'+ item1 +'">'+ list[item][item1] +'</span>';
                        }
                    }else{
                        $('.js-cat-list').next().hide();
                        $('.js-cat-list').parent().hide();
                    }
                }else if(item=='iSeason' && list[item]){
                    if(list[item]){
                        html_sea+='<span class="js-item action sea-all" data-item="sea" data-id="all">全部</span>';
                        for(var i=0;i<list[item].length;i++){
                            html_sea+='<span class="js-item sea-'+ list[item][i].id +'" data-item="sea" data-id="'+ list[item][i].id +'">'+ list[item][i].name+'</span>';
                        }
                    }else{
                        $('.js-sea-list').next().hide();
                        $('.js-sea-list').parent().hide();
                    }
                }else if(item=='sIndustry' && list[item]){
                    if(list[item]){
                        html_ind+='<span class="js-item action ind-all" data-item="ind" data-id="all">全部</span>';
                        for(var item1 in list[item]){
                            html_ind+='<span class="js-item ind-'+item1+'" data-item="ind" data-id="'+ item1 +'">'+ list[item][item1] +'</span>';
                        }
                    }else{
                        $('.js-ind-list').next().hide();
                        $('.js-ind-list').parent().hide();
                    }
                }else if(item=='sGender' && list[item]){
                    if(list[item]){
                        html_gen+='<span class="js-item action gen-all" data-item="gen" data-id="all">全部</span>';
                        for(var item1 in list[item]){
                            html_gen+='<span class="js-item gen-'+item1+'" data-item="gen" data-id="'+ item1 +'">'+ list[item][item1] +'</span>';
                        }
                    }else{
                        $('.js-gen-list').next().hide();
                        $('.js-gen-list').parent().hide();
                    }
                }else if(item=='sPatternContent' && list[item]){
                    if(list[item]){
                        html_pat+='<span class="js-item action con-all" data-item="con" data-id="all">全部</span>';
                        for(var item1 in list[item]){
                            html_pat+='<span class="js-item con-'+item1+'" data-item="con" data-id="'+ item1 +'">'+ list[item][item1] +'</span>';
                        }
                    }else{
                        $('.js-pat-list').parent().hide();
                    }
                }
            }
            if(html_cat!=''){
                $('.js-cat-list').html('');
                $('.js-cat-list').append(html_cat);
            }else{
                $('.js-cat-list').parent().hide();
            }
            if(html_sea!=''){
                $('.js-sea-list').html('');
                $('.js-sea-list').append(html_sea);
            }else{
                $('.js-sea-list').parent().hide();
            }
            if(html_ind!=''){
                $('.js-ind-list').html('');
                $('.js-ind-list').append(html_ind);
            }else{
                $('.js-ind-list').parent().hide();
            }
            if(html_gen!=''){
                $('.js-gen-list').html('');
                $('.js-gen-list').append(html_gen);
            }else{
                $('.js-gen-list').parent().hide();
            }
            if(html_pat!=''){
                $('.js-pat-list').html('');
                $('.js-pat-list').append(html_pat);
            }else{
                $('.js-pat-list').parent().hide();
            }
            if(html_ind=='' && html_sea=="" && html_cat==""){
                $('.js-model-list').hide();
            }
            $('.js-item').on('click',function (e) {
                if(!def.dataStatus){
                    return;
                }
                var cla=$(this).attr('class');
                if(cla.indexOf('action')>0){
                    return;
                }
                var data=$(this).data();
                if(data.item=="con"){
                    if(!def.is_pattern){
                        $('.screen-list>span').show();
                        $('.screen-list>span>label').html('图案');
                        setTimeout(function(){
                            $('.screen-list>span').hide();
                        },3000)
                        return;
                    }
                }else if(data.item!="gen"){
                    if(!def.is_model){
                        $('.screen-list>span').show();
                        $('.screen-list>span>label').html('款式');
                        setTimeout(function(){
                            $('.screen-list>span').hide();
                        },3000)
                        return;
                    }
                }
                $("."+data.item+"-"+data.id).addClass('action').siblings().removeClass('action');
                if(data.id != 'all'){
                    def.search[data.item]=data.id;
                }else{
                    def.search[data.item]='';
                }
                $('.js-moving-list').html('');
                $('.js-moving-list').css('visibility','hidden');
                def.search.page=1;
                $('.js-list-load').show();
                getList();
                e.stopPropagation();
            })
        },
        error: function(err) {}
    })
}

//搜索数据获取
function getList(isNumCheck){
    if(def.search.page!=1){
        $('.js-data-load').show();
    }
    $('.js-no-data').hide();
    if(P_UserType != '1'){
        def.search.isNumCheck=isNumCheck;
    }
    if(!def.dataStatus){
        return;
    }
    def.dataStatus=false;
    $.ajax({
        url: '/colorsearch/get_list/',
        type: 'POST',
        data:def.search,
        dataType: 'json',
        success: function(data) {
            if(data.code=='1001' && P_UserType !='1'){
                $('.js-authority-bg').show();
                $('.js-screen').hide();
                $('.js-upload-img').hide();
                $('.js-moving-list').hide();
                $('.js-free-times').hide();
                $('.js-color-select').hide();
                $('.js-moving-list').html('');
                def.dataStatus=false;
                return;
            }
            if(data.code=='999' && def.search.page==1){
                $('.js-no-data').show();
                $('.moving-water').hide();
                $('.js-screen-txt').hide();
                def.dataStatus=true;
                return;
            }
            def.total = data.info.total/data.info.pageSize < 1 ? 1 : Math.ceil(data.info.total/data.info.pageSize);
            if(def.total==0){
                $('.js-no-data').show();
                $('.moving-water').hide();
                $('.js-screen-txt').hide();
                def.dataStatus=true;
                return;
            }
            if(data.data!=''){
                $('.moving-water').show();
                $('.js-screen-txt').show();
                var num=data.info.free;
                var list=data.data;
                var html="";
                for(var i=0;i<list.length;i++){
                    var label="";
                    html+='<a href="'+ list[i].detail_url +'" target="_blank" class="item">'+
                    '<img src="https://imgf2.pop-fashion.com/global/images/loading/patterns270.jpg" data-original="'+list[i].cover+'">'+
                    '<div class="label-bg"></div>';
                    for(var j=0;j<list[i].labels.length;j++){
                        label += list[i].labels[j]+" ";
                    }
                    html += '<div class="moving-label">'+label+'</div>'+
                    '</a>';
                }
                if(num && num < 20){
                    $('.js-free-times').show();
                    $('.js-num').html(num);
                }
                $('.js-select-btn').show();
                $('.js-return-btn').hide();
                $('.js-select-tips').show();
                $('.js-screen').show();
                $('.js-color-selected img').hide();
                $('.js-upload-img').hide();
                $('.js-screen-btn').show();
                $('.js-list-load').hide();
                $('.js-data-load').hide();
                $('.js-moving-list').append(html);
                var lazyloadimg = $(".js-moving-list img");
                lazyloadimg.length && lazyloadimg.lazyload({
                    effect : "show",
                    failure_limit:12,
                    threshold: 500,
                    load:masonryFun()
                });
                setTimeout(function () {
                    masonryFun();
                },3000)
            }else{

            }
            def.dataStatus=true;
        },
        error: function(err) {}
    })
}
function masonryFun() {
    var $contain = $("body").find(".js-moving-list");
    $contain.imagesLoaded(function() {
        $contain.masonry({
            gutterWidth: 0,
            itemSelector: '.item',
            isAnimated: false
        });
        $contain.masonry("reload");
        $('.js-moving-list').css('visibility','visible');
        $('.js-color-select').removeClass('action');
    })
}
function getColor(){
    $.ajax({
        url: '/Colorsearch/g_first_color/',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var html='';
            var list=data.data || [];
            if(list == []){
                return;
            }
            for(var i=0;i<list.length;i++){
                if(i==0){
                    html += '<span class="selection" id="'+list[i].id+'" data-id="'+list[i].id+'"><img src="https://imgf2.pop-fashion.com/global/images/common/select.png"/><i style="background-color: '+list[i].sAlias+';"></i></span>';
                }else{
                    html += '<span id="'+list[i].id+'" data-id="'+list[i].id+'"><img src="https://imgf2.pop-fashion.com/global/images/common/select.png"/><i style="background-color: '+list[i].sAlias+';"></i></span>';
                }
            }
            $('.js-color-list').append(html);
            $('.js-color-list span').on('click',function(){
                if(!def.getColorSta){
                    return;
                }
                $(this).siblings().removeClass('selection');
                $(this).addClass('selection');
                var id=$(this).attr('data-id');
                getSecondColor(id);
            })
        },
        error: function(err) {}
    })
}
function getSecondColor(id){
    def.getColorSta=false;
    $.ajax({
        url: '/Colorsearch/g_second_color/',
        type: 'POST',
        data:{"pid":id},
        dataType: 'json',
        success: function(data) {
            var html='';
            var list=data.data || [];
            if(list == []){
                return;
            }
            if(def.col_obj.length!=0){
                for(var j=0;j<def.col_obj.length;j++){
                    for(var i=0;i<list.length;i++){
                        if(def.col_obj[j][list[i].id]){
                            list[i].action='1';
                        }
                    }
                }
            }
            for(var i=0;i<list.length;i++){
                if(list[i].action=='1'){
                    html += '<div class="color-box action js-color-box" id="col_'+list[i].id+'" data-id="'+list[i].id+'" data-ipid="'+list[i].iPid+'" data-salias="'+list[i].sAlias+'" data-sname="'+list[i].sName+'" data-scolorNumber="'+list[i].sColorNumber+'" style="background:'+list[i].sAlias+';">';
                }else{
                    html += '<div class="color-box js-color-box" id="col_'+list[i].id+'" data-id="'+list[i].id+'" data-ipid="'+list[i].iPid+'" data-salias="'+list[i].sAlias+'" data-sname="'+list[i].sName+'" data-scolorNumber="'+list[i].sColorNumber+'" style="background:'+list[i].sAlias+';">';
                }
                html += '<div class="color-txt">'+
                    '<span>'+list[i].sName+'</span>'+
                    '<span>'+list[i].sColorNumber+'</span>'+
                    '<span>'+list[i].sAlias+'</span>'+
                    '</div>'+
                    '<img src="https://imgf2.pop-fashion.com/global/images/common/select.png"/></div>';
            }
            $('.js-color-opt').html('');
            $('.js-color-opt').append(html);
            $('.js-color-box').on('click',function(){
                var data=$(this).data();
                var ad=$(this).attr('class');
                // var obj={
                //     [data.id]:data
                // }
                var obj = {};
                obj[data.id] = data;
                if(ad.indexOf('action')>0){
                    del_col(data.id)
                    // $(this).removeClass('action');
                }else{
                    add_col(obj,data.id,data.ipid);
                }
            })
            def.getColorSta=true;
        },
        error: function(err) {}
    })
}
 //删除颜色
 function del_col(id){
    $('.js-color-list span').removeClass('action');
    $('#col_'+id).removeClass('action');
    for(var i=0;i<def.col_obj.length;i++){
        for(var item in def.col_obj[i]){
            if(def.col_obj[i][item].id == id){
                def.col_obj.splice(i,1);
            }
        }
    }
    for(var i=0;i<def.col_obj.length;i++){
        for(var item in def.col_obj[i]){
            $('#'+def.col_obj[i][item].ipid).addClass("action");
        }
    }
    if(def.col_obj.length==0){
        $('.js-not-select').show();
        $('.js-color-selected').hide();
        $('.js-color-select').removeClass('actionbg');
        $('.js-sea-color').html('');
        return;
    }else{
        addSelected_col();
    }
}
//添加颜色
function add_col(obj,id,ipid){
    if(def.col_obj.length<3){
        $('.js-search-ipt').attr('placeholder','');
        def.col_obj.push(obj);
        addSelected_col();
        $('#col_'+id).addClass('action');
        $('#'+ipid).addClass('action');
    }else{
        $('.js-color-head>span').show();
        $('.js-color-head>span').html('最多可支持3种颜色搜索');
        setTimeout(function () {
            $('.js-color-head>span').hide();
        },3000)
    }
}
function addSelected_col(isSta){
    var html="",_html="";
    $('.js-not-select').hide();
    $('.js-color-select').addClass('actionbg');
    $('.js-color-selected').show();    
    for(var i=0;i<def.col_obj.length;i++){
        if(def.col_obj.length==2){
            for(var item in def.col_obj[i]){
                html += '<div class="color" data-id="'+def.col_obj[i][item].id+'" style="background: '+def.col_obj[i][item].salias+';height:50%;">'+
                '<span style="padding-top: 10px;">'+def.col_obj[i][item].sname+'</span>'+
                '<span>'+def.col_obj[i][item].scolornumber+'<img src="https://imgf2.pop-fashion.com/global/images/common/col-close.png"/></span>'+
                '<span>'+def.col_obj[i][item].salias+'</span></div>';
                if(def.col_obj[i][item].id=='149'){
                    _html +='<span style="background:'+def.col_obj[i][item].salias+';border:1px solid #DFDFDF;height: 22px;"></span>'
                }else{
                    _html +='<span style="background:'+def.col_obj[i][item].salias+';"></span>'
                }
            }
        }else if(def.col_obj.length==3){
            for(var item in def.col_obj[i]){
                html += '<div class="color" data-id="'+def.col_obj[i][item].id+'" style="background: '+def.col_obj[i][item].salias+';height:33.33%;">'+
                '<span style="padding-top: 10px;">'+def.col_obj[i][item].sname+'</span>'+
                '<span>'+def.col_obj[i][item].scolornumber+'<img src="https://imgf2.pop-fashion.com/global/images/common/col-close.png"/>'+
                '<span>'+def.col_obj[i][item].salias+'</span></div>';
                if(def.col_obj[i][item].id=='149'){
                    _html +='<span style="background:'+def.col_obj[i][item].salias+';border:1px solid #DFDFDF;height: 22px;"></span>'
                }else{
                    _html +='<span style="background:'+def.col_obj[i][item].salias+';"></span>'
                }
            }
        }else{
            for(var item in def.col_obj[i]){
                html += '<div class="color" data-id="'+def.col_obj[i][item].id+'" style="background: '+def.col_obj[i][item].salias+';">'+
                '<span style="padding-top: 10px;">'+def.col_obj[i][item].sname+'</span>'+
                '<span>'+def.col_obj[i][item].scolornumber+'<img src="https://imgf2.pop-fashion.com/global/images/common/col-close.png"/></span>'+
                '<span>'+def.col_obj[i][item].salias+'</span></div>';
                if(def.col_obj[i][item].id=='149'){
                    _html +='<span style="background:'+def.col_obj[i][item].salias+';border:1px solid #DFDFDF;height: 22px;"></span>'
                }else{
                    _html +='<span style="background:'+def.col_obj[i][item].salias+';"></span>'
                }
            }
        }
    }
    $('.js-color-selected').html('');
    $('.js-color-selected').append(html);
    $('.js-sea-color').show();
    $('.js-sea-color').html('');
    $('.js-sea-color').append(_html);
    if(isSta){
        $('.js-color-selected img').hide();
    }
    $('.js-color-selected img').on('click',function(){
        var id=$(this).parent().parent().data("id");
        del_col(id);
    })
}
$('.js-color-btn').on('click',function (){
    if(def.col_obj.length!=0){
        def.search.aco=[];
        for(var i=0;i<def.col_obj.length;i++){
            for(var item in def.col_obj[i]){
                def.search.aco.push(item);
            }
        }
        $('.js-model').addClass('action');
        $('.js-pattern').addClass('action');
        def.search.col=['50','82'];
        def.search.page=0;
        def.colorStatus=true;
        def.dataStatus=true;
        def.is_model=true;
        def.is_pattern=true;
        $('.js-moving-list').html('');
        $('.js-moving-list').css('visibility','hidden');
        $('.js-screen-txt').show();
        $('.js-screen-txt').html('搜索内容为<span>款式--秀场提炼</span>和<span>图案素材</span>,如果只想看款式或图案，可以在筛选中设置！<i></i>');
        $('.js-list-load').show();
        getFilter();
        getList(1);
    }else{
        $('.js-color-head>span').show();
        $('.js-color-head>span').html('请先选择颜色');
        setTimeout(function () {
            $('.js-color-head>span').hide();
        },3000)
    }
})
$('.js-select-btn').on('click',function (){
    $('.js-screen').hide();
    $('.js-upload-img').show();
    $('.js-return-btn').show();
    $('.js-select-tips').hide();
    $('.js-no-data').hide();
    $('.js-color-selected img').show();
    def.colorStatus=false;
    def.extra_obj = JSON.parse(JSON.stringify(def.col_obj));
    var list=def.extra_obj;
    for(var i=0;i<list.length;i++){
        for(var item in list[i]){
            $('#col_'+list[i][item].id).addClass('action');
            $('#'+list[i][item].ipid).addClass('action');
        }
    }
    $(this).hide();
})
$('.js-return-btn').on('click',function (){
    $('.js-screen').show();
    $('.js-upload-img').hide();
    $('.js-select-btn').show();
    $('.js-select-tips').show();
    $('.js-color-selected img').hide();
    $('.js-no-data').hide();
    def.colorStatus=true;
    var list=def.col_obj;
    for(var i=0;i<list.length;i++){
        for(var item in list[i]){
            $('#col_'+list[i][item].id).removeClass('action');
            $('#'+list[i][item].ipid).removeClass('action');
        }
    }
    def.col_obj = JSON.parse(JSON.stringify(def.extra_obj));
    addSelected_col(true);
    $(this).hide();
})
$('.js-sea-color').on('click',function () {
    var stu=$(this).data('stu');
    if(stu==1){
        $('#search_box1').focus();
    }else{
        $('#search_box2').focus();
    }
})
$('.js-model').on('click',function () {
    if(!def.dataStatus){
        return;
    }
    var cla=$(this).attr('class');
    if(cla.indexOf('action')>0){
        if(def.search.col.length==1){
            return;
        }
        def.search.col.splice(def.search.col.indexOf('50'),1);
        def.search.ind='';
        def.search.sea='';
        def.search.cat='';
        $('.js-model').removeClass('action');
        $('.js-model').next().find('.model-box').each(function () {
            $(this).find('span').removeClass('action');
        })
        def.is_model=false;
        $('.js-screen-txt').show();
        $('.js-screen-txt').html('搜索内容为<span>图案素材</span>,如果只想看款式或图案，可以在筛选中设置！<i></i>');
    }else{
        $('.js-model').addClass('action');
        def.search.col.push('50');
        $('.js-model').next().find('.model-box').each(function () {
            $(this).find('span').removeClass('action');
            $($(this).find('span')[1]).addClass('action');
        })
        def.is_model=true;
        $('.js-screen-txt').show();
        $('.js-screen-txt').html('搜索内容为<span>款式--秀场提炼</span>和<span>图案素材</span>,如果只想看款式或图案，可以在筛选中设置！<i></i>');
    }
    $('.js-moving-list').html('');
    $('.js-moving-list').css('visibility','hidden');
    def.search.page=1;
    getList();
})
$('.js-pattern').on('click',function () {
    if(!def.dataStatus){
        return;
    }
    var cla=$(this).attr('class');
    if(cla.indexOf('action')>0){
        if(def.search.col.length==1){
            return;
        }
        def.search.col.splice(def.search.col.indexOf('82'),1);
        def.search.con='';
        $('.js-pattern').removeClass('action');
        $('.js-pattern').next().find('span').removeClass('action');
        def.is_pattern=false;
        $('.js-screen-txt').show();
        $('.js-screen-txt').html('搜索内容为<span>款式--秀场提炼</span>,如果只想看款式或图案，可以在筛选中设置！<i></i>');
    }else{
        $('.js-pattern').addClass('action');
        def.search.col.push('82');
        $($('.js-pattern').next().find('span')[0]).addClass('action');
        def.is_pattern=true;
        $('.js-screen-txt').show();
        $('.js-screen-txt').html('搜索内容为<span>款式--秀场提炼</span>和<span>图案素材</span>,如果只想看款式或图案，可以在筛选中设置！<i></i>');
    }
    def.search.page=1;
    $('.js-moving-list').html('');
    $('.js-moving-list').css('visibility','hidden');
    getList();
})
$('.js-search-ipt').on('keypress', function(event){
    if(event.which == 13){
        var keywords = $.trim($(this).val());
        if(keywords == '' || keywords == '搜索你想要的') {
            return false;
        }
        if(keywords){
            window.location.href = "/search/?key="+encodeURIComponent(encodeURIComponent(keywords));
        }
    }
})
$('.js-search-btn').on('click',function(){
    var val=$(this).parent().find('.js-search-ipt').val();
    window.location.href='/search/?key='+encodeURIComponent(encodeURIComponent(val));
})
$('.js-search-ipt').focus(function() {
    $('.js-sea-color').hide();
    $(this).attr('placeholder','搜索你想要的');
}).blur(function() {
    var val=$(this).val();
    if(val == "" && def.original_img != ""){
        $('.js-sea-color').show();
        $(this).attr('placeholder','')
    }else{
        $('.js-sea-color').hide();
        $(this).attr('placeholder','搜索你想要的');
    }
})
$(document).on("click",'.js-screen-txt i',function(){
    $('.js-screen-txt').hide();
})
$('.js-model-btn').on('click',function(e){
    var cla=$(this).attr('class');
    if(cla.indexOf('action')>0){
        $(this).removeClass('action');
        $(this).html('展开<i></i>');
        $(this).prev().css('maxHeight','28px');
    }else{
        $(this).addClass('action');
        $(this).html('收起<i></i>');
        $(this).prev().css('maxHeight','initial');
    }
    e.stopPropagation();
})
$(window).resize(function(){
    reWid();
});
function reWid(){
    var wid=window.innerWidth;
    if(wid<=1200){
        $('.js-color-select').css('left','0');
    }else if(wid>1200 && wid<1500){
        $('.js-color-select').css('left',((wid-1200)/2-13)+'px');
    }else if(wid==1500){
        $('.js-color-select').css('left','0');
    }else{
        $('.js-color-select').css('left',((wid-1500)/2-13)+'px');
    }
}