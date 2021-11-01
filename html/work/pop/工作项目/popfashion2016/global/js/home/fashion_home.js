/*
    fanglinna
    201908296
    个性化首页
*/
require.config({
    baseUrl:"/global/js",
    urlArgs:"r="+(new Date()).getTime(),
    paths:{
        "general":["common/general-1.0"]
    },
    shim:{
        "general":{
            exports:"general"
        }
    }
});
require(["general"], function(general) {
    $(function() {
        var def = {
            selected_labels :[],    //个性化选中ID
            preference_select:[],    //偏好选择选中id
            brand_labels: [],       //品牌标签选中id
            delete_label:[],          //删除个性化标签
            delete_brand_label: [],   //删除品牌个性化标签
            IMG_URL:'https://imgf2.pop-fashion.com',
            selected_count:0                     //个性化标签选中的数量

        };

        // 首页轮播
        
        $('.swiper-container-banner ul').roundabout({
            duration: 1000,
            minZ: 10,							// 最小的层级
            maxZ: 20,
            minScale: 0.4,
            autoplay: true,
            autoplayDuration: 4000,
            minScale: 0.86,
            minOpacity: 0,
            maxOpacity: 1,
            reflect: false,
            startingChild: 0,
            enableDrag: true,
            easing: 'swing',
            autoplayPauseOnHover: true,          //鼠标移入取消自动播放
            btnNext:".js-swiper-next",
            btnPrev:".js-swiper-prev",
            clickToFocus: true            
        });

        function initBrand() {
            $(".js-search-brand-labels").val("");
            $(".js-brand-labels-searchlist").hide().html("");
            $(".js-prompt-layer").hide().html('最多能自主添加14个标签，可删除已选标签再重试');
        }

        // 品牌联想筛选
        $(".js-interested-select-box").on("keyup", ".js-search-brand-labels", function() {
            $(".js-prompt-layer").hide();
            var text = $.trim($(this).val());
            $.ajax({
                url: '/home/g_brands/?' +Math.random(),
                type: 'post',
                dataType: 'json',
                data: {searchKey: text},
                success: function(data) {
                    var _html = "";                    
                    var arr = data.data || [];
                    var arr_new = arr.slice(0,10);
                    if(arr.length) {
                        for(var i=0; i<arr_new.length; i++){
                            var name = arr_new[i].name || "";
                            var id = arr_new[i].id || "";
                            _html += '<a data-id="'+id+'">'+name+'</a>'
                        }
                        $(".js-brand-labels-searchlist").show().html(_html);
                    }else{
                        $(".js-brand-labels-searchlist").hide().html("");
                        $(".js-prompt-layer").show().html('搜索不到该品牌');
                    }
                }

            })
        })

        // 选择品牌
        $(".js-interested-select-box").on("click", ".js-brand-labels-searchlist a", function() {
            $(".js-prompt-layer").hide();
            var brand_arr = [];
            if($(".js-interested-select-box .selected").length >= 14){
                $(".js-prompt-layer").show();
                return false;
            }
            $(".js-interested-select-brand a").each(function() {
                var on_id = $(this).data("id").toString();
                brand_arr.push(on_id);
            });
            var id = $(this).data("id").toString();
            var name = $(this).text();
            if($.inArray(id,brand_arr) != -1){
                if($(".js-interested-select-brand a[data-id="+id+"]").hasClass("selected")){
                    $(".js-prompt-layer").show().html('该品牌已选择');
                    return false;
                }else{
                    $(".js-interested-select-brand a[data-id="+id+"]").addClass("selected");
                    initBrand();
                }
            }else{
                var _html = '<a data-id="'+id+'" class="selected">'+name+'<em></em></a>'
                $(".js-interested-select-brand").prepend(_html);
                initBrand();
            }            
        });

        $("body").on("click", '.js-interested-select-contain', function(e) {
            initBrand();
        });
        $("body").on("click", '.brand-input,.js-interested-select-box a', function(e) {
            e.stopPropagation();
        });

        // 个性化标签渲染DOM
        $(".js-tags-show-box>span").on("click", function() {
            var _display = $(".js-remove-tag").css("display");
            if(_display == "block") {
                return false;
            }
            $.ajax({
                url:'/home/g_cate/?'+Math.random(),
                dataType:"json",
                success:function(data){                    
                    var arr = data.data || "";
                    def.selected_count = data.info.selected_count || 0;
                    var _html = "";
                    for(var key in arr){
                        if(key == "brand"){
                            _html += '<li>';
                            _html += '<div class="clearfix"><label class="fl">品牌</label><i></i><div class="fl brand-input"><input type="type" placeholder="选择关注的品牌" class="js-search-brand-labels" /><div class="brand-labels-searchlist js-brand-labels-searchlist"></div></div></div>';
                            _html += '<div class="interested-select-brand js-interested-select-brand">';
                            for(var j in arr[key].label){
                                if($.inArray(j,arr[key].selected) != -1){
                                    _html+='<a class="selected" data-id='+j+'>'+arr[key].label[j]+'<em></em></a>';
                                }else{
                                    _html+='<a data-id='+j+'>'+arr[key].label[j]+'<em></em></a>';
                                }
                            }
                            _html += '</div>';
                            _html += '</li>';                    
                        }else{
                            _html+='<li class="clearfix">';
                            if(key == "sea"){
                                _html+='<label class="fl">季节</label>';
                            }else if(key == "style"){
                                _html+='<label class="fl">风格</label>';
                            }else if(key == "category"){
                                _html+='<label class="fl">单品</label>';
                            }else{
                                _html+='<label class="fl">'+key+'</label>';
                            }
                            _html +='<i></i>';                                              
                            _html+='<div class="interested-select-labels fl">';                        
                            for(var j in arr[key].label){
                                if($.inArray(j,arr[key].selected) != -1){
                                    _html+='<a class="selected" data-id='+j+'><span>'+arr[key].label[j]+'</span><em></em></a>';
                                }else{
                                    _html+='<a data-id='+j+'><span>'+arr[key].label[j]+'</span><em></em></a>';
                                }
                            }
                            _html+='</div></li>';
                        }                       
                    }
                    $(".js-interested-select-box>ul").html(_html);
                    $(".js-interested-select-bg,.js-interested-select-contain").show();
                }
            })
        });

        // 关闭个性化弹窗
        $(".js-interested-select-close,.js-cancel-interested-select").on("click", function() {
            $(".js-interested-select-bg,.js-interested-select-contain").hide();
            $(".js-prompt-layer").hide();
            def.selected_labels = [];
            def.brand_labels =[];
        })


        // 选中个性化标签
        function selectLabel(self) {
            if(self.hasClass("selected")){
                self.removeClass("selected");
                $(".js-prompt-layer").hide();
            }else{
                if($(".js-interested-select-box .selected").length >= 14){
                    $(".js-prompt-layer").show();
                    return false;
                }             
                self.addClass("selected");   
            }
        }
        // 选中个性化标签
        $(".js-interested-select-box").on("click", '.interested-select-labels>a', function() {
            selectLabel($(this))
        });

        // 选中品牌标签
        $(".js-interested-select-box").on("click", '.js-interested-select-brand>a', function() {
            selectLabel($(this))
        })

        // 保存选中标签
        $(".js-confirm-interested-select").on("click", function(e) {
            e.stopPropagation();
            // 选择其他标签
            $(".interested-select-labels a.selected").each(function() {
                var id = $(this).data("id").toString();
                def.selected_labels.push(id)
            });
            // 选中品牌标签
            $(".js-interested-select-brand a.selected").each(function() {
                var id = $(this).data("id").toString();
                def.brand_labels.push(id)
            });
            $.ajax({
                url:'/home/s_label/?'+Math.random(),
                type:'POST',
                dataType:"json",
                data:{a:'save',label_arr:def.selected_labels,brand_arr:def.brand_labels},
                success:function(data){
                    if(data.code == 100){
                        $(".js-prompt-layer").show();
                        def.brand_labels = [];
                        def.selected_labels = [];
                        return false;
                    }else{
                        getLabelsFunc();
                        $(".js-interested-select-bg,.js-interested-select-contain").hide();
                        $(".js-prompt-layer").hide();
                        def.brand_labels = [];
                        def.selected_labels = [];
                    }
                }
            })
        });

        // 刷新获取个性化标签数据
        function getLabelsFunc(){    
            $.ajax({
                url:'/home/grl/?'+Math.random(),
                dataType:"json",
                success:function(data){
                    var arr = data.data || [];
                    var user_label = data.info.user_label || 0;
                    var _html = "";
                    if(arr.length>0){
                        for(var i=0;i<arr.length;i++){
                            var id = arr[i].id || "";
                            var name = arr[i].name || "";
                            var link = arr[i].link || "";
                            var cover = arr[i].cover || "";
                            var num_sort = arr[i].num_sort || "";
                            var label_type = arr[i].label_type || "";
                            _html += '<div><a href="'+link+'" data-id="'+id+'" data-type="'+label_type+'" title="'+name+'"><span>'+name+'</span></a>';
                            if(!num_sort){
                                _html+='<em class="js-remove-tag"></em>';
                            }
                            _html+='</div>';
                        }
                    }
                    $(".js-tags-list-box").html(_html);
                    $(".js-tags-list-box div").each(function(i) {                        
                        if(user_label < 14){
                            $(".js-tags-show-box>span").show();
                        }else{
                            $(".js-tags-show-box>span").hide();
                        }
                    });
                    def.selected_labels = [];
                }
            })
        }

        // 编辑标签
        $(".js-follow-editor").on("click", function() {
            if($(this).hasClass("on")){
                $(this).text("编辑").removeClass("on");
                $(".js-tags-list-box").find("em").hide();
                $(".js-tags-show-box>span").on("mouseenter", function() {
                    $(this).addClass("on");
                }).on("mouseleave", function() {
                    $(this).removeClass("on");
                });
            }else{
                $(this).text("完成").addClass("on");
                $(".js-tags-list-box").find("em").show();
                $(".js-tags-show-box>span").unbind("mouseenter")
            }
        });

        $(".js-tags-show-box>span").on("mouseenter", function() {
            $(this).addClass("on");
        }).on("mouseleave", function() {
            $(this).removeClass("on");
        });
        
        // 删除个性化标签
        $(".js-tags-show-box").on("click", '.js-remove-tag',function(e) {
            e.preventDefault();
            $(this).parent("div").addClass("del-on");
            var  id = $(this).siblings("a").data("id");
            var  type = $(this).siblings("a").data("type");
            if(type == "list"){
                def.delete_label.push(id);
            }else{
                def.delete_brand_label.push(id);
            }
            $(".js-delete-relate-contain,.js-del-label-bg").show();
            $(".js-delete-relate-contain>p").html("确认删除该关注吗？")
            $(".js-delete-labels").css("display","inline-block");           
            
        }); 
        $(".js-delete-relate-contain").on("click", '.js-delete-labels', function() {
            $.ajax({
                type:'POST',
                url:'/home/s_label/?'+Math.random(),
                dataType:"json",
                data:{a:'del',label_arr:def.delete_label,brand_arr:def.delete_brand_label},
                success:function() {
                    $(".js-delete-relate-contain,.js-del-label-bg").hide();
                    def.delete_label = [];
                    def.delete_brand_label = [];
                    $(".js-tags-list-box").find(".del-on").remove();
                    if($(".js-tags-list-box a").length < 20){
                        $(".js-tags-show-box>span").show();
                    }else{
                        $(".js-tags-show-box>span").hide();
                    }
                }
            })
        });   
        
        // 取消提示弹窗
        $(".js-cancel-delete").on("click", function() {
            $(".js-delete-relate-contain,.js-preference-select-bg,.js-del-label-bg,.js-myboard-bg,.js-add-my-borads").hide();
            def.delete_label = [];
            def.delete_brand_label = [];
        });
        
        // ？提示语弹窗
        $(".js-intro-title>i").on("mouseenter", function() {
            $(this).siblings(".js-intro-content").show();
        }).on("mouseleave", function() {
            $(this).siblings(".js-intro-content").hide();
        });

        // 偏好弹窗7天过期
        var preference_select = $.cookie("preference_select");
        if(preference_select == null) {
            $.ajax({
                type:'POST',
                url:'/home/s_relate/?'+Math.random(),
                dataType:"json",
                success:function(data){
                    def.preference_select = data.data || [];
                    if(data.data.length > 0){
                        $(".js-modular-labels-box a").each(function() {
                            var id = $(this).data("id");
                            if($.inArray(id,def.preference_select) != -1){
                                $(this).addClass("selected");
                            }
                        });
                        return false;
                    }else{
                        $(".js-preference-select-bg,.js-preference-select-contain").show();                        
                    }                    
                }
            });
        }

        // 选中偏好标签下拉
        $(".js-modular-labels-box").on("click", "a", function() {
            preLabelFunc($(this));
        });

        // 选中偏好标签弹窗
        $(".js-labels-select-box").on("click", "li", function() {
            preLabelFunc($(this));
        })

        // 选择偏好弹窗数据
        function preLabelFunc(self) {
            var label_id = self.data("id").toString();
            var index_id = def.preference_select.indexOf(label_id);
            if(self.hasClass("selected")){
                self.removeClass("selected");
                def.preference_select.splice(index_id,1);
            }else{
                self.addClass("selected");                
                def.preference_select.push(label_id);
            }
        }

        // 保存偏好选择条件
        $(".js-labels-save-set").on("click", function(e) {                      
            saveLabelFunc();
        });

        $(".js-preference-select-bg,.js-preference-select-close").on("click", function(e) {
            e.stopPropagation(); 
            saveLabelFunc();
        })

        function saveLabelFunc(){
            $.ajax({
                type:'POST',
                url:'/home/s_relate/?'+Math.random(),
                dataType:"json",
                data:{type:1,label_arr:def.preference_select},
                success:function() {
                    $(".js-my-recommend-nav").hide();
                    $.cookie("preference_select","preference_select",{ expires: 7, path: '/' });
                    window.location.reload();
                }
            })
        }

        // top榜单标签下拉筛选
        $(".js-labels-filter").on("mouseenter", function() {
            $(this).find("div.label-filter-downlist").css("visibility","visible");
        }).on("mouseleave", function() {
            $(this).find("div.label-filter-downlist").css("visibility","hidden");
        });
        
        // 滚动条
        $(".contentHolder").perfectScrollbar();             

        // 品牌排行榜        
        var sex_id = "";
        var sex_data = {};              
        function brandTopFunc(sex_id) {
            if(sex_id == "" || sex_id == undefined){
                sex_data = {};
            }else{
                sex_data.sex = sex_id;
            }
            $.ajax({
                url: '/home/g_brand_top/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                data: sex_data,
                success: function (data) {
                    var arr = data.data || [];
                    var _html = '';
                    if(arr.length > 0){
                        for(var i = 0; i < arr.length; i++){
                            var link = arr[i].link || "";
                            var name = arr[i].name || "";
                            var num = arr[i].num || "";
                            _html += '<li class="brand-position'+(i+1)+'"><a href="'+link+'" target="blank">'+name+'</a>';
                            _html += '<div><i></i><p>品牌：'+name+'<br/>热度：'+num+'</p></div></li>';
                        }
                        $(".js-brand-ranking-data").html(_html);
                        $(".js-loading-brand-data").hide();
                        $(".js-brand-ranking-data").show();
                        $(".js-top-data-brand").hide();
                    }else{
                        $(".js-top-data-brand").show();
                        $(".js-loading-brand-data").hide();
                        $(".js-brand-ranking-data").html("")
                    }                    
                }
            })
        }

        // 品牌筛选性别
        $(".js-label-filter-downlist>div>a").on("click", function() {
            var id = $(this).attr("data-id");
            var val = $(this).text();
            $(this).parents().siblings("span").html(val+'<i></i>');
            $(".js-label-filter-downlist").css("visibility","hidden");
            brandTopFunc(id);
        });

        // 图案排行榜
        var pattern_id = $(".js-pattern-label-filter>span").attr("data-id");        
        function patternTopFunc(pattern_id) {
            $.ajax({
                url: '/home/g_pattern_top/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                data: {pattern_id:pattern_id},
                success: function(data) {
                    var arr = data.data || [];                    
                    var _html = '';
                    var total_arr = [];
                    var timer;
                    if(arr.length > 0){
                        for(var i = 0; i < arr.length; i++) {
                            var link = arr[i].link || "";
                            var sName = arr[i].sName || "";
                            var total = arr[i].total || 0;
                            var total_wid = (Math.ceil(total / arr[0].total *100) + '%');
                            total_arr.push(total_wid);
                            _html += '<li>';
                            _html += '<a href="'+link+'" target="_blank">';
                            _html += '<em>'+(i+1)+'</em>';
                            _html += '<span>'+sName+'</span>';
                            _html += '<div class="pattern-color-value-box"><div style="width:0">';
                            _html += '<div class="hover-infor"><i></i>';
                            _html += '<p>图案：'+sName+'<br/>热度：'+total+'</p>';
                            _html += '</div></div>';
                            _html +='</div>';
                            _html +='<strong>'+total+'</strong></a></li>';
                        }
                        $(".js-pattern-ranking-data").html(_html);
                        clearTimeout(timer);
                        timer = setTimeout(function() {
                            total_arr.forEach(function(val,index) {
                                $(".pattern-color-value-box>div").eq(index).css("width",val);
                            })
                        },100)
                        $(".js-loading-pattern-data").hide();
                        $(".js-top-data-sPattern").hide();
                    }else{
                        $(".js-top-data-sPattern").show();
                        $(".js-loading-pattern-data").hide();
                        $(".js-pattern-ranking-data").html("");
                    }                              
                }
            })
        }
        // 图案筛选
        $(".js-pattern-label-downlist>div>a").on("click", function() {
            var id = $(this).attr("data-id");
            var val = $(this).text();
            $(this).parents().siblings("span").html(val+'<i></i>');
            $(".js-pattern-label-downlist").css("visibility","hidden");
            patternTopFunc(id);
        });

        // 色彩排行榜

        // 一级色系
        var sea = $(".js-season-label-filter>span").attr("data-id");
        colorTopFunc(sea);
        function colorTopFunc(sea) {
            $.ajax({
                url: '/home/g_color/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                data: {sea: sea},
                success: function (data) {
                    var arr = data.data || [];
                    var _html = '';
                    var group_arr = [];
                    var percentage_arr = [];
                    var timer;
                    if(arr.length > 0) {
                        for(var i = 0; i < arr.length; i++){
                            var link = arr[i].link || "";
                            var percentage = arr[i].percentage || 0;
                            var per_val = (Math.ceil(percentage / arr[0].percentage *100) + '%')
                            var sAlias = arr[i].sAlias || ""
                            var sName = arr[i].sName || "";
                            var id = arr[i].id || "";
                            percentage_arr.push(per_val);
                            _html += '<li data-id="'+id+'"><a href="'+link+'" target="_blank"><span>'+percentage+'%</span>';
                            _html += '<div><em style="height:0;background:'+sAlias+'"></em></div>';
                            _html += '<p>'+sName+'</p></a></li>';
                        }
                        $(".js-ranking-top-color>ul").html(_html);
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            percentage_arr.forEach(function (val,index) {
                                $(".js-ranking-top-color em").eq(index).css("height",val);
                            })
                        },100)
                        for(var j = 0; j < arr.length; j+=4) {
                            group_arr.push(arr.slice(j,j+4));
                        }
                        var color_html = '';
                        for(var k = 0; k < group_arr.length; k++) {
                            color_html += '<div class="color-value js-color-value">';
                            for(var n = 0; n < group_arr[k].length; n++){         
                                color_html += '<a data-id="'+group_arr[k][n].id+'" style="background:'+group_arr[k][n].sAlias+'"></a>'; 
                            }
                            color_html +='</div>';
                        }
                        $(".js-ranking-all-color").html(color_html);
                        $(".ranking-all-box,.js-ranking-top-color").show();
                        $(".js-loading-color-data").hide();
                        $(".js-top-data-color").hide();
                        if(group_arr.length == 1){
                            $(".js-ranking-all-color").find(".js-color-value>a").css("border-radius","5px");
                        }
                    }else{
                        $(".js-top-data-color").show();
                        $(".ranking-all-box,.js-ranking-top-color").hide();
                        $(".js-loading-color-data").hide();
                        $(".js-ranking-top-color>ul").html("");
                        $(".js-ranking-all-color").html("");
                    }
                    
                }
            })
        }

        $(".js-season-label-downlist>div>a").on("click", function() {
            sea = $(this).attr("data-id");
            var val = $(this).text();
            $(this).parents().siblings("span").html(val+'<i></i>');
            $(".js-season-label-downlist").css("visibility","hidden");
            colorTopFunc(sea);
        });

        // 二级色系
        var first_color;
        $(".js-ranking-all-color").on("click", ".js-color-value>a", function() {
            first_color = $(this).attr("data-id");
            $(this).addClass("on").siblings("a").removeClass("on");
            $(this).parent("div").siblings("div").find("a").removeClass("on");
            colorChildTop(sea,first_color)
        })
        function colorChildTop() {
            $.ajax({
                url: '/home/g_second_color/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                data:{sea:sea,first_color:first_color},
                success: function(data) {
                    var arr = data.data || [];
                    var _html = '';
                    var percentage_arr = [];
                    var timer;
                    for(var i = 0; i < arr.length; i++){
                        var link = arr[i].link || "";
                        var percentage = arr[i].percentage || 0;
                        var per_val = (Math.ceil(percentage / arr[0].percentage *100) + '%')
                        var sAlias = arr[i].sAlias || ""
                        var sName = arr[i].sName || "";
                        var id = arr[i].id || "";
                        percentage_arr.push(per_val);
                        _html += '<li data-id="'+id+'"><a href="'+link+'" target="_blank"><span>'+percentage+'%</span>';
                        _html += '<div><em style="height:0;background:'+sAlias+'"></em></div>';
                        _html += '<p>'+sName+'</p></a></li>';
                    }
                    $(".js-ranking-top-color>ul").html(_html);
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        percentage_arr.forEach(function (val,index) {
                            $(".js-ranking-top-color em").eq(index).css("height",val);
                        })
                    },100)
                }
            })
        }

        // 热门-最新报告
        function reportFunc() {
            $.ajax({
                type:'POST',
                url:'/home/g_report/?'+ Math.random(),
                dataType:"json",
                success:function(data) {
                    var arr = data.data || {};
                    var hot_report = arr.hot_report || [];
                    var new_report = arr.new_report || [];                
                    hotReportFunc(hot_report)
                    newReportFunc(new_report)
                }
            })
        }       

        // 热门报告
        function hotReportFunc(hot_report) {
            var hot_html = "";
            for(var i=0,len=hot_report.length; i<len; i++){
                var link = hot_report[i].link || '';
                var id = hot_report[i].id || '';
                var t = hot_report[i].t || '';
                var col_id = hot_report[i].col.id || '';
                var title = hot_report[i].title || '';
                var view_count = hot_report[i].view_count || '';
                hot_html += '<li>';
                hot_html +='<a href="'+link+'" data-id="'+id+'" data-t="'+t+'" data-col="'+col_id+'" target="_blank">';
                if(i == 0){
                    hot_html +='<span class="r-ranking-num first-num">1</span>';
                }else if(i == 1){
                    hot_html += '<span class="r-ranking-num second-num">2</span>';
                }else if(i == 2){
                    hot_html += '<span class="r-ranking-num third-num">3</span>';
                }else{
                    hot_html += '<span class="r-ranking-num">'+parseInt(i+1)+'</span>';
                }
                hot_html += '<p class="r-report-title"><span>'+title+'</span></p>';
                hot_html += '<span class="r-view-num"><i></i>'+view_count+'</span>';
            }
            $(".js-hot-reports-list").html(hot_html);
        }

        // 最新报告
        function newReportFunc(new_report) {            
            // 最新报告
            var new_html1 = "",new_html2 = "",new_html3 = "";
            for(var j=0,len=new_report.length; j<len; j++){
                var link = new_report[j].link || '';
                var id = new_report[j].id || '';
                var t = new_report[j].t || '';
                var col_id = new_report[j].col.id || '';
                var col_name = new_report[j].col.name || '';
                var col_link = new_report[j].col.link || '';
                var title = new_report[j].title || '';
                var view_count = new_report[j].view_count || '';
                var create_time = new_report[j].create_time || '';
                var iCollectStatus = new_report[j].iCollectStatus || 0;
                var cover = new_report[j].cover || '';
                var label = new_report[j].label;
                if(j <6){
                    new_html1 += '<li data-id="'+id+'" data-t="'+t+'" data-col="'+col_id+'">';
                    new_html1 += '<div class="img-box">';
                    new_html1 += '<a href="'+link+'" target="_blank">';
                    new_html1 += '<img src="'+cover+'"/>';
                    new_html1 += '</a>';
                    new_html1 += '<div class="col-date-box">';
                    new_html1 += '<a href="'+col_link+'" class="fl">'+col_name+'</a>';
                    new_html1 += '<span class="fr">'+create_time+'</span>';
                    new_html1 += '</div>';
                    if(iCollectStatus && iCollectStatus != undefined){
                        if(iCollectStatus){
                            new_html1 += '<div class="collect-state js-collect-state"></div>';
                        }else{
                            new_html1 += '<div class="collect-state js-collect-state on-state"></div>';
                        }
                    }
                    new_html1 += '</div>';
                    new_html1 += '<div class="text-labels">';
                    new_html1 += '<div class="text-position js-text-position">';
                    new_html1 += '<p>';
                    new_html1 += '<a href="'+link+'" target="_blank">'+title+'</a>';
                    new_html1 += '</p><div>';                  
                    for(var k=0;k<label.length;k++){
                        new_html1 += '<span>'
                        new_html1 += ' <a href="'+label[k].lLink+'">'+label[k].name+'</a>'
                        new_html1 += '</span>';
                    }
                    new_html1 += '</div></div></div></li>';
                }else if(j>=6 && j<12){
                    new_html2 += '<li data-id="'+id+'" data-t="'+t+'" data-col="'+col_id+'">';
                    new_html2 += '<div class="img-box">';
                    new_html2 += '<a href="'+link+'" target="_blank">';
                    new_html2 += '<img src="'+cover+'"/>';
                    new_html2 += '</a>';
                    new_html2 += '<div class="col-date-box">';
                    new_html2 += '<a href="'+col_link+'" class="fl">'+col_name+'</a>';
                    new_html2 += '<span class="fr">'+create_time+'</span>';
                    new_html2 += '</div>';
                    if(iCollectStatus && iCollectStatus != undefined){
                        if(iCollectStatus){
                            new_html2 += '<div class="collect-state js-collect-state"></div>';
                        }else{
                            new_html2 += '<div class="collect-state js-collect-state on-state"></div>';
                        }
                    }
                    new_html2 += '</div>';
                    new_html2 += '<div class="text-labels">';
                    new_html2 += '<div class="text-position js-text-position">';
                    new_html2 += '<p>';
                    new_html2 += '<a href="'+link+'" target="_blank">'+title+'</a>';
                    new_html2 += '</p><div>';                  
                    for(var k=0;k<label.length;k++){
                        new_html2 += '<span>'
                        new_html2 += ' <a href="'+label[k].lLink+'">'+label[k].name+'</a>'
                        new_html2 += '</span>';
                    }
                    new_html2 += '</div></div></div></li>';
                }else{
                    new_html3 += '<li data-id="'+id+'" data-t="'+t+'" data-col="'+col_id+'">';
                    new_html3 += '<div class="img-box">';
                    new_html3 += '<a href="'+link+'" target="_blank">';
                    new_html3 += '<img src="'+cover+'"/>';
                    new_html3 += '</a>';
                    new_html3 += '<div class="col-date-box">';
                    new_html3 += '<a href="'+col_link+'" class="fl">'+col_name+'</a>';
                    new_html3 += '<span class="fr">'+create_time+'</span>';
                    new_html3 += '</div>';
                    if(iCollectStatus && iCollectStatus != undefined){
                        if(iCollectStatus){
                            new_html3 += '<div class="collect-state js-collect-state"></div>';
                        }else{
                            new_html3 += '<div class="collect-state js-collect-state on-state"></div>';
                        }
                    }
                    new_html3 += '</div>';
                    new_html3 += '<div class="text-labels">';
                    new_html3 += '<div class="text-position js-text-position">';
                    new_html3 += '<p>';
                    new_html3 += '<a href="'+link+'" target="_blank">'+title+'</a>';
                    new_html3 += '</p><div>';                  
                    for(var k=0;k<label.length;k++){
                        new_html3 += '<span>'
                        new_html3 += ' <a href="'+label[k].lLink+'">'+label[k].name+'</a>'
                        new_html3 += '</span>';
                    }
                    new_html3 += '</div></div></div></li>';
                }                    
            }
            $(".js-report-list-box>ul").eq(0).html(new_html1);
            $(".js-report-list-box>ul").eq(1).html(new_html2);
            $(".js-report-list-box>ul").eq(2).html(new_html3);
        }

        // 推荐数据
        function recommendLikeFunc(type,url){
            $.ajax({
                type:'GET',
                url:url+'&'+Math.random(),
                dataType:"json",
                success:function(data){
                    var _html = "";
                    var arr = data.data || [];
                    if(arr.length > 0){
                        for(var i=0; i<arr.length; i++){
                            var id = arr[i].id || "";
                            var link = arr[i].link || "";
                            var cover = arr[i].cover || "";
                            var t = arr[i].t || "";
                            var request_id = arr[i].request_id || "";
                            _html+='<li>';
                            _html+='<a href="'+link+'" data-id="'+id+'" data-t="'+t+'" data-request_id="'+request_id+'" target="blank">';
                            if(type == 1){
                                _html+='<img src="'+def.IMG_URL+'/global/images/loading/style210.jpg" data-original="'+cover+'"/>';
                            }else{
                                _html+='<img src="'+def.IMG_URL+'/global/images/loading/patterns270.jpg" data-original="'+cover+'"/>';
                            }                            
                            _html+='</a>';
                            _html+='</li>';
                        }
                        if(type == 1){
                            $(".js-style-lists-contain>ul").html(_html);
                        }else{
                            $(".js-pattern-lists-contain>ul").html(_html);
                        }
                        $(".lazyload img").lazyload({effect: "show"});
                    }
                }
            })
        }

        // 款式图案推荐上报行为
        $(".js-datagrand-click").on("click", "ul>li>a", function() {
            var this_t = $(this).data("t");
            var this_id = $(this).data("id");
            var request_id = $(this).data("request_id");
            var action_data = {t:this_t,id:this_id,request_id:request_id,action_type:"rec_click",userid:P_AccountId,scene_type:'personal_picture_clothing'};
            $.ajax({
                type: "get",
                dataType: "jsonp",
                url: 'https://api.pop136.com/internal/datagrand.php',
                data:action_data,
                success:function(){}
            })
        });
        
        // 热门报告
        $(".js-hot-reports-list").on("mouseenter",'a', function() {
            $(this).find("span").addClass("ellipse");
        }).on("mouseleave", 'a', function() {
            $(this).find("span").removeClass("ellipse");
        });
        
        // 收藏报告        
        var isClick = false;
        $(".js-report-list-box>ul").eq(0).show();
        $(".js-collect-state").on('click', function() {
            if(isClick==false){         
                listsCollect($(this));
            }
        });
        function listsCollect(obj){
            isClick=true;
            var data_a = obj.parents('li');
            var iColumnId =data_a.data('col')||'';
            var sTableName = data_a.data('t')||'';
            var iPriId = data_a.data('id')||'';
            if(obj.hasClass('on-state')){            
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
                        obj.removeClass('on-state');
                        oCommon.showTips(data.msg);
                    }
                    // 加入成功
                    else if(parseInt(data.code) === 20) {
                        obj.addClass('on-state');
                        oCommon.showTips(data.msg);
                    }
                    isClick=false;
                }
            });
        }

        // 报告翻页
        $(".js-right-jump").on("click", function() {
            var i_num = $(".js-jump-num").text();
            i_num ++;
            if(i_num > 3){
                return false;
            }
            $(".js-jump-num").text(i_num)
            $(".js-report-list-box>ul").eq(i_num-1).show().siblings().hide();
        });

        $(".js-left-jump").on("click", function() {
            var i_num = $(".js-jump-num").text();
            i_num --;
            if(i_num < 1){
                return false;
            }
            $(".js-jump-num").text(i_num)
            $(".js-report-list-box>ul").eq(i_num-1).show().siblings().hide();
        });

        // 最新T台        
        function runwayFunc() {
            $.ajax({
                url: '/home/g_t_show/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    var arr = data.data || [];
                    var _html = '';
                    for(var i = 0; i < arr.length; i++){
                        var cover = arr[i].cover || "";
                        var name_text = arr[i].nme || "";
                        var link = arr[i].link || "";
                        _html += '<li>';
                        _html += '<a href="'+link+'" target="_blank">';
                        _html += '<div class="pic-box">';
                        _html += ''+cover+'</div>';
                        _html += '<p>'+name_text+'</p>';
                        _html += '</a>';
                        _html += '</li>';
                    }
                    $(".js-runway-lists").html(_html);
                    $(".lazyload img").lazyload({effect: "show"})
                }
            })
        }

        // 广告大片        
        function advertFunc() {
            $.ajax({
                url: '/home/g_look_book/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    var arr = data.data || [];
                    var _html = '';
                    for(var i = 0; i < arr.length; i++){
                        var cover = arr[i].list.cover || "";
                        var name_text = arr[i].list.name_text || "";
                        var link = arr[i].list.link || "";
                        _html += '<li>';
                        _html += '<a href="'+link+'" target="_blank">';
                        _html += '<div class="pic-box">';
                        _html += '<img src="'+cover+'"/></div>';
                        _html += '<p>'+name_text+'</p>';
                        _html += '</a>';
                        _html += '</li>';
                    }
                    $(".js-lookbook-lists").html(_html);
                }
            })
        }

        // 专属看板提示
        $(".js-save-search-null").on("click", function() {
            $(".js-add-my-borads,.js-myboard-bg").show();
        });

        // 关闭专属看板提示
        $(".js-close-my-borads").on("click", function() {
            $(".js-add-my-borads,.js-myboard-bg").hide();
        })

        // 专属看板提示打开新页面
        $(".js-find-news").on("click", function() {
            window.open("//www.pop-fashion.com/styles/");
            $(".js-add-my-borads,.js-myboard-bg").hide();
        });

        // 获取专属看板数据        
        function myBoardFunc() {
            $.ajax({
                url: '/styleajax/getScreeningList/?'+Math.random(),
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    var obj_data = data.data || {};
                    var list = obj_data.list || [];
                    var count = obj_data.count || 0;
                    var _html ='';
                    for(var i = 0; i < list.length; i++) {
                        var attr = list[i].attr || {};
                        var colName = list[i].colName || "";
                        var sScreenUrl = list[i].sScreenUrl || "";
                        var filter_create_time = list[i].filter_create_time || "";
                        var updateTime=list[i]["updateTime"]?list[i]["updateTime"]:"";
                        filter_create_time = filter_create_time.substring(0,10);
                        var attr_value='';
                        _html += '<a href="'+sScreenUrl+'" target="_blank"><em></em>';
                        for(var key in attr){ 
                            attr_value += attr[key];
                        }
                        _html += '<div>'+colName+attr_value+'</div>';
                        _html += '<p>创建于'+filter_create_time+'</p>';
                        if(updateTime == "今日更新"){
                            _html += '<i></i>';
                        }
                        _html += '</a>';
                    }
                    $(".js-borad-search-list").html(_html);
                    if(list.length >= 10){
                        $(".js-save-search-null").hide();
                    }
                }
            })
        }


        // 滚动加载数据
        var oh = document.documentElement.clientHeight || document.body.clientHeight;
        var get_time = 0;
        $(window).on("scroll", function() {
            var scrollTop = $(this).scrollTop();
            var top_hei = $(".brand-pattern-ranking-content").length > 0 ? $(".brand-pattern-ranking-content").offset().top : 0;
            var report_hei = $(".reports-infor-contain").length > 0 ? $(".reports-infor-contain").offset().top : 0;
            var style_hei = $(".style-recommend-contain").length > 0 ? $(".style-recommend-contain").offset().top : 0;
            var pattern_hei = $(".pattern-recommend-contain").length > 0 ? $(".pattern-recommend-contain").offset().top : 0;
            var runway_hei = $(".runway-recommend-contain").length > 0 ? $(".runway-recommend-contain").offset().top : 0;
            var lookbook_hei = $(".lookbook-recommend-contain").length > 0 ? $(".lookbook-recommend-contain").offset().top : 0;
            var board_hei = $(".my-board-contain").length > 0 ? $(".my-board-contain").offset().top : 0;
            if(scrollTop + oh > top_hei){                
                if(get_time == 0){
                    if(modules.power_modules.top_show) {
                        patternTopFunc(pattern_id);
                        brandTopFunc(sex_id);
                    }
                    get_time = 1;                
                }
            }
            if(scrollTop + oh > report_hei){ 
                // 热门、最新报告
                if(get_time == 1){
                    reportFunc();
                    get_time =2;
                }                
            }
            if(scrollTop + oh > style_hei){
                // 款式推荐
                if(get_time == 2){
                    recommendLikeFunc(1,'/patterns/patternRecommend/?col=4&page_size=30');
                    get_time = 3;
                }
            }
            if(scrollTop + oh > pattern_hei){
                // 图案推荐
                if(get_time == 3){                    
                    recommendLikeFunc(2,'/patterns/patternRecommend/?col=82&page_size=30');
                    get_time = 4;
                }
            }
            if(scrollTop + oh > runway_hei){
                // 最新T台
                if(get_time == 4){     
                    if(modules.power_modules.t_show_relate){
                        runwayFunc()
                    }               
                    get_time = 5;
                }
            }
            if(scrollTop + oh > lookbook_hei){
                // 广告大片
                if(get_time == 5){
                    if(modules.power_modules.advert_relate)  {
                        advertFunc();
                    }                
                    get_time = 6;
                }
            }
            if(scrollTop + oh > board_hei){
                // 专属看板
                if(get_time == 6){  
                    if(modules.power_modules.look_show) {
                        myBoardFunc()
                    }                  
                    get_time = 7;
                }
            }
        });

        // 个性化设置
        var modular_def = {
            look_show:true,    //专属看板是否显示
            top_show:true,      //排行榜是否显示
            top_relate:true,    //排行榜个性化是否开启
            new_report_relate:true,  //最新报告个性化是否开启
            module_popular_select:[],  //排行榜内容选择
            hot_report_relate:true,   //热门报告个性化是否开启
            t_show:true,             //T台是否开启
            advert_show:true        //广告大片是否开启
        };

        // 重置
        function initFunc() {
            $(".js-modular-labels-box>a").removeClass("selected");
            $(".js-recommend-nav-tab a").eq(0).addClass("s-on").siblings("a").removeClass("s-on");
            $(".modular-show-box").eq(0).show().siblings().hide();
        }

        $(".js-myRecommend").on("click",function(e) {
            e.stopPropagation();
            initFunc()
            myRecommendFunc();
        })

        function myRecommendFunc() {
            $.ajax({
                type:'POST',
                url:'/home/s_relate/?' +Math.random(),
                data:{type:4},
                success:function(data) {
                    var arr = data.data || {};
                    modular_def.look_show = arr.look_show;
                    modular_def.top_show = arr.top_show;
                    modular_def.top_relate = arr.top_relate;
                    modular_def.new_report_relate =arr.new_report_relate;
                    modular_def.module_popular_select = arr.module_popular_select || [];
                    modular_def.hot_report_relate = arr.hot_report_relate;
                    modular_def.t_show = arr.t_show_relate;
                    modular_def.advert_show = arr.advert_relate;
                    def.preference_select = arr.like || [];
                    if(modular_def.look_show){
                        $(".mo-show-span>input").prop("checked", true);
                    }else{
                        $(".mo-hide-span>input").prop("checked", true);
                    }
                    if(modular_def.top_show){
                        $(".top-show-span>input").prop("checked",true);
                    }else{
                        $(".top-hide-span>input").prop("checked",true);
                    }
                    if(modular_def.t_show){
                        $(".runway-show-span>input").prop("checked",true);
                    }else{
                        $(".runway-hide-span>input").prop("checked",true);
                    }
                    if(modular_def.advert_show){
                        $(".advert-show-span>input").prop("checked",true);
                    }else{
                        $(".advert-hide-span>input").prop("checked",true);
                    }
                    if(modular_def.top_relate){
                        $(".relate-show-span>input").prop("checked",true);
                    }else{
                        $(".relate-hide-span>input").prop("checked",true);
                    }
                    if(modular_def.new_report_relate){
                        $(".new-show-span>input").prop("checked",true);
                    }else{
                        $(".new-hide-span>input").prop("checked",true);
                    }
                    if(modular_def.hot_report_relate){
                        $(".hot-show-span>input").prop("checked",true);
                    }else{
                        $(".hot-hide-span>input").prop("checked",true);
                    }
                    $(".js-modular-labels-box a").each(function() {
                        var id = $(this).data("id").toString();
                        if($.inArray(id,def.preference_select) != -1){
                            $(this).addClass("selected");
                        }
                    });
                    $(".js-my-recommend-nav").show();                
                }
            })
        }

        // 关闭个性化设置
        $(".js-my-recommend-close").on("click", function() {
            $(".js-my-recommend-nav").hide();
        });



        $(".js-recommend-nav-tab a").on("click", function() {
            var index = $(this).index();
            $(this).addClass("s-on").siblings().removeClass("s-on");
            $(".modular-show-box").eq(index).show().siblings().hide();
        })

        // 专属看板是否显示
        $(".look-modular-box").on("click", "input", function() {
            modular_def.look_show = parseInt($(this).val());
        })

        // 热门排行榜是否显示
        $(".top-modular-box").on("click", "input", function() {
            modular_def.top_show = parseInt($(this).val());
        })
        
        // 最新T台是否显示
        $(".runway-modular-box").on("click", "input", function() {
            modular_def.t_show = parseInt($(this).val());
        })

        // 广告大片是否显示
        $(".advert-modular-box").on("click", "input", function() {
            modular_def.advert_show = parseInt($(this).val());
        })

        // 是否开启个性排行榜
        $(".relate-modular-box").on("click", "input", function() {
            modular_def.top_relate = parseInt($(this).val());
        });

        // 是否开启个性热门报告
        $(".hot-modular-box").on("click", "input", function() {
            modular_def.hot_report_relate = parseInt($(this).val());
        })

        // 是否开启个性最新报告
        $(".new-modular-box").on("click", "input", function() {
            modular_def.new_report_relate = parseInt($(this).val());
        });



        // 模板设置
        $(".js-modular-save-set").on("click", function() {
            $.ajax({
                type:'POST',
                url:'/home/s_relate/?' + Math.random(),
                data:{type:2,look_show:modular_def.look_show,top_show:modular_def.top_show,t_show:modular_def.t_show,advert_show:modular_def.advert_show},
                success:function() {
                    $(".js-my-recommend-nav").hide();
                    window.location.reload()
                }
            })
        });

        // 个性化设置
        $(".js-relate-save-set").on("click", function() {
            $.ajax({
                type:'POST',
                url:'/home/s_relate/?'+ Math.random(),
                data:{type:3,top_relate:modular_def.top_relate,new_report_relate:modular_def.new_report_relate,hot_report_relate:modular_def.hot_report_relate},
                success:function() {
                    $(".js-my-recommend-nav").hide();
                    window.location.reload()
                }
            })
        });

        
    })
})
