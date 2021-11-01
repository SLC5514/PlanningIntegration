/**
 * Created by zouzhuxing on 2016/5/11.
 */
var oBrand = {
    options:{
        pagesize: 80,   // 100为一页
        result: $('#brand_result'),
        search: $('#searchInput')
    },
    isRequest : true,
    // 初始化
    init:function(){
        // 懒得写了，用JS代替
        this.initParams();
        // 条件点击操作跳转
        this.clickJump();
        // 输入框操作
        this.inputSearch();
    },

    //------------------搜索选项调转---begin------------------

    // 懒得写了，用JS代替
    initParams:function(){
        var $ul = $('#s_leibie');
        var $a = $ul.find('a');
        var params = this.getParamsConditions();
        if (params.length) {
            $a.each(function(){
                var id = $(this).data('id');
                if ($.inArray(id, params) > -1) {
                    $(this).addClass('cur').siblings().removeClass('cur');
                }
            });
        }
    },

    // 获取参数条件 不含关键字(取URL上的条件)
    getParamsConditions:function(){
        // 搜索条件 1#-1#390#512#58
        var params = $('#s_leibie').data('params');
        var arrparams = [];
        if (params) {
            if ((params+'').indexOf('#') != -1) {
                var aparams = params.split('#');
                for (var i in aparams) {
                    if($.trim(aparams[i])!=''){
                        arrparams.push(parseInt(aparams[i]));
                    }
                }
            } else {
                arrparams.push(parseInt(params));
            }
        }
        return arrparams;
    },

    // 条件点击操作跳转
    clickJump:function(){
        var $b = $('.downlist a');
        //行业和性别被点击
        $b.on('click',function(){
            var me  = $(this).parent('.downlist').siblings('a');
            var url = $(this).data('url');
            if(me.prop('id')=='Gender'){
                if($(this).data('id')!=0){
                    $.cookie('gender', $(this).data('id'), {domain:'.pop-fashion.com', path:'/'});
                }else{
                    $.cookie('gender', "", { domain:'.pop-fashion.com', path:'/', expires: -1 });  // 清除COOKIE
                }
            }else{
                if($(this).data('id')!=0){
                	 $.cookie('industry', $(this).data('id'), {domain:'.pop-fashion.com', path:'/'});
                }else{
                    $.cookie('industry', "", { domain:'.pop-fashion.com', path:'/', expires: -1 });  // 清除COOKIE
                }
            }
            location.href = '/brands/' + url;
        });
    },

    //------------------搜索选项调转---end--------------------

    // 可用字母
    initLetter:function(letters, operate){
        if (operate == 'append') {
            return;
        }
        var len = letters.length;
        if (len) {
            $('#az').children('a').each(function(){
                var initial = $(this).data('id');

                if ($.inArray(initial, letters) > -1 || initial == 'ALL') {
                    $(this).removeClass('isUnable');
                } else {
                    $(this).addClass('isUnable');
                }
            });
        } else {
            $('#az').children('a').each(function(){
                $(this).addClass('isUnable');
            });
        }
    },

    // 渲染页面
    render:function(res, handeln, operate){
        var self = this, html = '', residue = '';

        if (res.code == "success" && res.total > 0) {
            html = res.data;
            residue = res.residue;

            //追加
            if (operate == 'append') {
                self.options.result.find('li:last').find('.brand_result_list').append(residue);
                self.options.result.append(html);
            }
            //重置
            if (operate == 'reset') {
                self.options.result.html(html);
            }
        } else {
            if (operate == 'append') {
                return;
            }
            if (operate == 'reset') {
                html = '<p style="color:#d8b056;;text-align:center;">目前该条件下没有品牌，请尝试用其他条件进行检索...</p>';
                self.options.result.html(html);
            }
        }
        // 品牌总数
        $('.all_searchContent span').html(res.total);

        if (!handeln) {
            self.initLetter(res.letter, operate);
        }
    },

    // 获取数据并渲染 handeln 1=>手动触发 0=>默认
    getDataAndRender:function(search, initial, handeln, page, operate){
        var self = this;
        var params ='/'+ $("#s_leibie").data('params')+'/';
        var rec = $("#s_leibie").data('rec');
        var data = {'searchKey':search, 'initial':initial, 'rec':rec};
        if (page) {
            data.page = parseInt(page);
        }

        $.ajax({
            type: "POST",
            url: "/ajax/getbranddata"+params,
            data: data,
            dataType: "json",
            async: false,
            beforeSend: function () {
                if (page == 1) {
                    self.options.result.html("<p style='text-align: center;'><img src='/global/images/loading/search_loding.gif'/></p>");
                }
                return self.isRequest;
            },
            success: function (res) {
                self.render(res,handeln,operate);
                self.isRequest = false;
            },
            complete: function () {
                self.isRequest = true;
            }
        });
    },

    // 输入框操作
    inputSearch:function(){
        var self = this;
        var $inputObj = $('#searchInput');

        // 输入框获取焦点操作 失去焦点操作 keyup enter事件
        $inputObj.on('focus blur keyup', function(e){
            var text = $.trim($(this).val());
            var search = $.trim(self.options.search.val());
            var searchFirstLetter = search.slice(0,1).toUpperCase();
            switch (e.type) {
                case 'focus':
                    if (text == this.defaultValue) {
                        $(this).val('');
                    }
                    break;

                case 'blur':
                    if (text == '') {
                        $(this).val(this.defaultValue);
                    }
                    break;

                case 'keyup':

                    //输入框内容为空或默认值，选中全部品牌
                    if (!search || (search == self.options.search.get(0).defaultValue)) {
                        $("#az").children('a:first').addClass('check').siblings().removeClass('check');
                    } else {
                        if ($.inArray(searchFirstLetter,['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']) > -1) {
                        } else if ($.inArray(parseInt(searchFirstLetter),[0,1,2,3,4,5,6,7,8,9]) > -1) {
                            searchFirstLetter = 'NUMBER';
                        } else {
                            searchFirstLetter = 'OTHER';
                        }
                        $('#az').children('a[data-id="'+searchFirstLetter+'"]').addClass('check').siblings().removeClass('check');
                    }

                    // 取消回车
                    if(e.keyCode == 13){
                        return false;
                    }
                    // 渲染
                    if (text != '') {
                        self.getDataAndRender(text,'ALL', 0, 1, 'reset');
                    }
                    // 恢复默认
                    else {
                        self.getDataAndRender('','ALL', 0, 1, 'reset');
                    }
                    break;
            }
        });
    },

    // 运行
    run:function(){
        var az = $("#az")
        var self = this;
        //0、页面第一次加载时触发或带有搜索内容  仅处理是否选中状态
        //1、带有搜索内容的首字母点击时触发     仅处理是否选中状态，不处理首字母是否可用状态
        var handeln = 0;
        var page = 1;
        // 滚动条处理
        var $scroll = $(".contentHolder");

        self.init();
        self.getDataAndRender("", "ALL", handeln, page, 'reset');

        // 滚动条处理
        $scroll.perfectScrollbar('destroy');
        $scroll.perfectScrollbar();

        //给每个字母绑定点击事件
        az.children('a').each(function() {
            $(this).on('click', function () {
                var initial = $(this).data('id');
                var search = $.trim(self.options.search.val());

                //如果搜索框的内容为空或默认值，请求全部品牌数据
                if (!search || search == self.options.search.get(0).defaultValue) {
                    search = "";
                }
                handeln = 1;
                //不可用，或当前选项下，不重复请求
                if (($(this).hasClass('isUnable') || $(this).hasClass('check'))) return false;

                //点击事件时一定要切换选中状态
                $(this).addClass('check').siblings('a').removeClass('check');

                $scroll.scrollTop(0);
                $scroll.perfectScrollbar('update');
                page = 1;

                //请求数据并渲染页面
                self.getDataAndRender(search, initial, handeln, page, 'reset');
            });
        });

        // 滚动时请求数据并渲染  追加
        $scroll.scroll(function(){
            var $bself = $(this);
            var search = $.trim(self.options.search.val());
            var initial = $("#az").children('a.check').data('id');
            //如果搜索框的内容为空或默认值，请求全部品牌数据
            if (!search || search == self.options.search.get(0).defaultValue) {
                search = "";
                handeln = 0;
            } else {
                handeln = 1;
            }
            if ($bself.scrollTop()/($bself.get(0).scrollHeight-$bself.height()) > 0.95 && self.isRequest) {
                page++;
                self.getDataAndRender(search, initial, handeln, page, 'append');
            }
        });
    }
};


$(function(){
    oBrand.run();

    var Conup=$('.updata_con .downArrow_box');
    var Conul =$('.updata_con .updata_list')
    Conup.on('click', function(){
        var hei = Conul.height();
        var autoHei =Conul.css('height','auto').height();
        if (hei == 357) {
            Conul.height(hei).animate({height : autoHei},300);
            Conup.find('.downArrow').addClass('cur');
        } else {
            Conul.animate({height:'357px'},300);
            Conup.find('.downArrow').removeClass('cur');
        }
    });
});