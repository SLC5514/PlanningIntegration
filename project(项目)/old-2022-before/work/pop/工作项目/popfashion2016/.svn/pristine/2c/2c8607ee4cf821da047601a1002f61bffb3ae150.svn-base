;
var link = $("#link");
var global_param = link.data('param');
var global_search = link.data('search');
var global_url = link.data('url');
var oPopStyles = {
    // 阻止冒泡方法
    'stopPropagation':function(e){
        var e = e || window.event;
        // W3C阻止冒泡方法
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        // IE中阻止冒泡方法
        else {
            e.cancelBubble = true;
        }
    },
    // 阻止默认行为
    'stopPreventDefault':function(e){
        var e = e || window.event;
        // W3C阻止默认浏览器动作
        if ( e.preventDefault ) {
            e.preventDefault();
        }
        // IE中阻止默认动作
        else {
            window.event.returnValue = false;
        }
        return false;
    },
    // 对关键字进行处理
    'editKeyJump':function(val) {
        var param = global_param;
        // 页码如果存在则将页码去掉
        if (param.indexOf('-page_') !== -1) {
            var reg = RegExp('-page_([^-]*)', 'gi');
            param = param.replace(reg,'');
        }
        else if (param.indexOf('page_') !== -1) {
            var reg = RegExp('page_([^-]*)', 'gi');
            param = param.replace(reg,'');
        }
        searchHis(val)
        // 关键字条件
        val = encodeURIComponent(encodeURIComponent(val));
        if ($.trim(param) != '') {
            // 不存在则增加
            if (param.indexOf('?key=') === -1) {
                param = param.replace(/\/$/,'')+'/?key='+val;
            }
            // 否则替换
            else {
                param = param.replace(/\/$/,'').replace(/\?key=([^&]*)/gi,'/?key='+val);
            }
        } else {
            param = '?key='+val;
        }
        location.href = global_url+param.replace(/^\//,'')+'#anchor';
    },
    'searchClick':function(){
        var orgObj = this;
        $('.js-sear').on('click', function(){
            var $self = $(this);
            var $prev = $self.prev();
            var _keywords = $prev.val();
            if ($.trim(_keywords) == '' || _keywords.indexOf('在本页搜索') !== -1) {
                return false;
            }
            orgObj.editKeyJump(_keywords);
        });
        /**
         * 回车搜索
         */
        $('.js-input-search').on('keydown',function(ev){
            if (!oCommon.clickBeforeFlicker()) {
                return false;
            }
            var _keywords = $(this).val();
            if($.trim(_keywords) == '') {
                return;
            }
            var e = ev || window.event;
            var keycode = e.keyCode || e.which;
            if (keycode == 13) {
                orgObj.editKeyJump(_keywords);
            }
        });
        $('.js-input-search').on('focus',function(ev){
            var keywords = $(this).val();
            if (keywords == this.defaultValue) {
                $(this).val('');
            }
        });
        //搜索框没有输入，则失去焦点时恢复默认值
        $('.js-input-search').on('blur',function(ev){
            var keywords = $(this).val();
            if ($.trim(keywords) == '') {
                $(this).val(this.defaultValue);
            }
        });
        //点击条件跳转
        $(".prop,.sort,.disp,.time_range,.version,.aRelateBrands,.messageType,.childType,.activityState,.official").on('click',function(){
            var param = global_param;
            var $self = $(this);
            var key = $self.data('key');
            var val = $self.data('val');

            // T台二级列表无权限情况
            if( $(this).hasClass('version') && !$(this).hasClass('on') ){
                /* var POP_USER = $.cookie('POP_USER'), usertype_runw=''; */
                if( typeof P_UserType != 'undefined' ){
                    usertype_runw = P_UserType;
                }
                // POP_USER
                if((P_UserId == null || P_UserId == '') || usertype_runw==4 ) {
                    if(typeof msg !='undefined'){
                        msg.msg({"txt":'VIP用户专属资讯，快联系客服开通试用'},2000);
                    }
                    return;
                }
                
            }

            if ($self.hasClass('messageType')) {
                param = '';
            }
			if(param.indexOf('ds_')!=-1){
				param = param.replace(/[-]{0,1}(mar|exh|man|shap|spe|tech|pat|fab|acc)_[^-\/]*/,'');
			}
            param = orgObj.dealParam(param, key, val);
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'');
        });
        //含适量文件
        $(".contain_vector").on('click',function(){
            var param = global_param
            param = orgObj.dealParam(param, 'vect');
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
        });
        // 只看收藏
        $(".collect").on('click',function(){
            var usertype = $(this).data('usertype');
            var param = global_param
            param = orgObj.dealParam(param, 'coll');
            if(usertype != 2 && param.indexOf('coll_') !== -1 && !P_Collect) {
                if(usertype == 5) {
                    // 游客
                    oCommon.noPower(-6);
                }else if(usertype == 4) {
                    // 普通用户
                    oCommon.noPower(-7);
                }else {
                    oCommon.noPower(-2);
                }
                return;
            }
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
        });
        // 只看热门=>1，试读=>2
        $(".ihot").on('click',function(){
            var param = global_param;
            var val = $(this).data('val');
            param = orgObj.dealParam(param, 'ihot', val);
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
        });
        // 潮流解析只看数据
        $(".iIsAuthorized").on('click',function(){
            var param = global_param;
            param = orgObj.dealParam(param, 'auth');
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
        });
        // 只看面料
        $(".iUliaobao").on('click',function(){
            var param = global_param;
            param = orgObj.dealParam(param, 'uli');
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
        });
        $(".pop_ky").on('click',function(){
            var param = global_param;
            param = orgObj.dealParam(param, 'popky');
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
        });

        $(".js-live-video-select").on("click", function() {
            var param = global_param;
            param = orgObj.dealParam(param, 'tlive');
            param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
            location.href = global_url+param.replace(/^\//,'')+'#anchor';
            // videoJumpFunc($(this),'i_live_video','tlive_1');
        });
    },
    // 对参数进行处理
    'dealParam':function (param, type, _val) {
        var val, param_str;
        if(typeof _val == 'undefined') {
            val = 1;
        }else {
            val = _val;
        }
        if($.trim(param) == null || !param) {
            param_str = type+'_'+val;
        }else {
            var param_arr = param.split('-');
            // flag=false=》不存在本身的参数直接添加，falg=1=》去掉参数   page=true=>去掉page参数
            var flag = page = false;
            for(var i = 0 in param_arr) {
                var type_val_arr = param_arr[i].split('_');
                if(type_val_arr[0] == 'page') {
                    var page_index = i;
                    page = true;
                }
                // 存在自身的type
                if(type_val_arr[0] == type) {
                    if(type_val_arr[1] == val || type == 'coll' || type == 'vect') {
                        // 再次点击本身去掉参数
                        if(type == 'coll' || type == 'vect' || type == 'ihot' || type == 'uli' || type == 'auth' || type == 'off' || type == 'popky' || type == "tlive"){
                            var type_index = i;
                            flag = 1;
                        }else {
                            return param;
                        }

                    }else if(val == 'all') {
                        // 点击全部去掉本身的参数
                        var type_index = i;
                        flag = 1;
                    }else {
                        if(type == 'ihot') {
                            if(type_val_arr[1] == 3) {
                                type_val_arr[1] = 3 - val;
                            }else {
                                type_val_arr[1] = 3;
                            }
                        }else {
                            type_val_arr[1] = val;
                        }
                        flag = true;
                    }
                }
                type_val_str = type_val_arr.join('_');
                param_arr[i] = type_val_str;
            }
            if(page) {
                param_arr.splice(page_index, 1);
            }
            // 不存在本身的参数或者不是点击了全部则直接添加参数
            if(!flag && val != 'all') {
                param_arr.push(type+'_'+val);
            }else if(flag === 1) {
                param_arr.splice(type_index, 1);
            }
            param_str = param_arr.join('-');
        }
        return param_str;
    },
    // 色系hover变大效果
    'acoBigger':function(){
        $('body').on('mouseenter mouseleave', '.box_color', function(e){
            var $self = $(this);
            var e = e || window.event;
            $self.css({'position':'relative'});
            // 变大
            if (e.type == 'mouseenter') {
                $self.animate({width:'+=8px',height:'+=8px',left:'-=4px',top:'-=4px'}, 200);
                $self.find('span').css('display','inline-block');
            }
            // 还原
            else if (e.type == 'mouseleave') {
                $self.animate({width:'-=8px',height:'-=8px',left:'+=4px',top:'+=4px'}, 200);
                $self.find('span').css('display','none');
            }
        });
    },
    // 点击去掉标签，去除所有参数
    'clearAll': function(){
        $('.clearAll').on('click', function() {
            /*// 性别，行业需要清除cookie
            if($.cookie("gender")) {
                $.cookie('gender', null, { path:'/', domain:'.pop-fashion.com', expires:-1 });
            }
            if($.cookie("industry")) {
                $.cookie('industry', null, { path:'/', domain:'.pop-fashion.com', expires:-1 });
            }*/
            param = global_param;
            // 共享资料特殊处理
            if( $.trim(param) != null || param ) {
                param_arr = param.split('-');
                if(param_arr[0].indexOf('sha_') !== -1) {
                    param = param_arr[0];
                    location.href = global_url + param + '/';
                    return;
                }
            }
            // 款式单张, 清除条件要保留单张条件
            if (param) {
                var paramArr = param.split('-');
                // 单张
                if (paramArr.indexOf('dis_1') > -1) {
                    location.href = global_url + 'dis_1/';
                    return;
                }
            }
            location.href = global_url;
        });
    },
    'loadSelectItems':function(col) {
        var params = global_param;
        var refresh = oPopStyles.GetQueryString('refresh');
        var key = oPopStyles.GetQueryString('key');
        var url = '';
        if ($.inArray(parseInt(col), [4, 50,52, 54, 55, 56, 57, 122, 123]) != -1) {
            url = '/styles/filterConditions/';
        }else if($.inArray(parseInt(col), [1, 23, 22, 21, 20,125,126,127,128,129]) != -1) {
            url = '/trends/filterConditions/';
        }else if($.inArray(parseInt(col), [2, 30, 31, 32, 33, 34, 35, 36, 37, 38, 40, 112, 132]) != -1) {
            url = '/analysis/filterConditions/';
        }else if($.inArray(parseInt(col), [3]) != -1) {
            url = '/runways/filterConditions/';
        }else if($.inArray(parseInt(col), [7, 80, 81, 84, 85, 117]) != -1) {
            url = '/references/filterConditions/';
        }else if($.inArray(parseInt(col), [6, 70, 71, 72, 73, 113, 114, 115, 131]) != -1) {
            url = '/books/filterconditions/';
        }else if($.inArray(parseInt(col), [9, 82, 120, 121, 124]) != -1) {
            url = '/patterns/filterconditions/';
        }else if($.inArray(parseInt(col), [90, 91]) != -1) {
            url = '/inspiration/filterconditions/';
        }else if(col == 'iSpecialTopicPatterns') {
            url = '/trends/filterConditions/';
        }
        if(url == '') return;
        url = url + '?' + Math.random();
        $.ajax({
            type: 'post',
            url: url,
            data: {col:col, params:params, refresh:refresh, key:key},
            dataType: 'html',
            success: function(data) {
                // 其它
                var other = $(data).find('.other');
                $('body').append(other);
                // 筛选项
                var select_ul = $(data).find('.select_items').html();
                $('.js-screen-category').append(select_ul);
                $(".js-screen-category-list").show();
                $(".aspan_load").remove();
                $('.contentHolder').mCustomScrollbar({
                    theme:'dark-3',
                    mouseWheel:{
                        enable:true,
                        preventDefault: true 
                    }
                });
                // 未来趋势-视觉筛选、手稿合集-书籍类型
                var vis_li_leng = $(data).find('.vis ul li').length;
                if(vis_li_leng > 0) {
                    var vision_screen = $(data).find('.vis').html();
                    $('.vision_screen').html(vision_screen);
                    $('.vision_screen').show();
                }
            }
        });
    },
    'GetQueryString':function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg); //获取url中"?"符后的字符串并正则匹配
        var context = "";
        if (r != null)
           context = r[2];
        reg = null;
        r = null;
        return context == null || context == "" || context == "undefined" ? "" : context;
    },
    'run':function(){
        var orgObj = this;
        orgObj.searchClick();
        orgObj.acoBigger();
        orgObj.clearAll();
    }
};

$(function(){
    oPopStyles.run();
    
    $("#looktg").on('click', function () {
        var param = global_param;
        param = oPopStyles.dealParam(param, 'page', 1);
        param = oPopStyles.dealParam(param, 'page', 3);

        param = global_search ? param.replace(/\/$/,'')+'/'+global_search : param+'/';
        location.href = global_url+param.replace(/^\//,'')+'#anchor';
    });

    oCommon.flicker();
});

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
