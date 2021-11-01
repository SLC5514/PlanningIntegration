$(function () {
    ;
    (function ($) {
        $.fn.animPic = function (options) {
            var defaultVal = {bw: "67%", sw: "33%", anit: 500, delayt: 200, init0: 0};
            var obj = $.extend(defaultVal, options);
            return this.each(function () {
                var selfBox = $(this);
                var selv = selfBox[0];
                var selfBoxLi = selfBox.find('li.libox');
                selv.bigWidth = selfBoxLi.eq(obj.init0).width();
                selfBoxLi.find(".bigbg").css("left", -selv.bigWidth);
                selfBoxLi.eq(obj.init0).addClass("bigW");

                var showBgTxt = function (sel) {

                    sel.find(".po_box").fadeOut(50);
                    //sel.find(".bigbg").show().animate({left: 0},300);
                    sel.find(".bigbg").css('left', 0).hide();
                    sel.find(".bigbg").fadeIn(obj.anit);
                }
                var hideBgTxt = function (sel) {
                    sel.find(".po_box").show();
                    sel.find(".bigbg").stop().hide().css("left", -selv.bigWidth);
                }
                selfBox.on("mouseenter", ".libox", function () {
                    var self = $(this);
                    var selfBoxLi = self.closest('ul').find("li.libox");
                    selv.ind = self.index();
                    clearTimeout(selv.mouseTimer);
                    selv.mouseTimer = setTimeout(function () {
                        self.find(".po_box").fadeOut(50);
                        if (selv.ind != obj.init0 && !self.is(":animated")) {
                            //console.log(selv.className+"selv.oldInd:"+selv.oldInd);

                            if (selv.oldInd == undefined) {
                                hideBgTxt(selfBoxLi.eq(obj.init0));
                                selfBoxLi.eq(obj.init0).stop(false, true).animate({width: obj.sw}, obj.anit);
                                selfBoxLi.eq(selv.ind).stop(false, true).animate({width: obj.bw}, obj.anit, function () {
                                    showBgTxt(self);
                                    selv.oldInd = selv.ind;
                                    selfBoxLi.eq(obj.init0).removeClass("bigW");
                                });
                            }
                            if (selv.oldInd == selv.ind) {
                                hideBgTxt(selfBoxLi.eq(selv.oldInd));
                                selfBoxLi.eq(selv.ind).siblings('li').stop(false, true).animate({width: obj.sw}, obj.anit);
                                selfBoxLi.eq(selv.ind).stop(false, true).animate({width: obj.bw}, obj.anit, function () {
                                    showBgTxt(self);
                                    selfBoxLi.eq(obj.init0).removeClass("bigW");
                                });
                            }
                            if (selv.oldInd != undefined && selv.oldInd != selv.ind) {
                                hideBgTxt(selfBoxLi.eq(selv.oldInd));
                                selfBoxLi.eq(selv.ind).siblings('li').stop(false, true).animate({width: obj.sw}, obj.anit);
                                selfBoxLi.eq(selv.ind).stop(false, true).animate({width: obj.bw}, obj.anit, function () {
                                    showBgTxt(self);
                                    selfBoxLi.eq(obj.init0).removeClass("bigW");
                                });
                            }
                            selv.oldInd = selv.ind;
                            selfBoxLi.eq(obj.init0).removeClass("bigW");
                        }
                        else if (selv.ind == obj.init0 && !self.is(":animated")) {
                            if (self.hasClass('bigW')) {
                                showBgTxt(self);
                            }
                            else {
                                hideBgTxt(selfBoxLi.eq(selv.oldInd));
                                selfBoxLi.eq(selv.oldInd).stop(false, true).animate({width: obj.sw}, obj.anit);
                                selfBoxLi.eq(selv.ind).stop(false, true).animate({width: obj.bw}, obj.anit, function () {
                                    showBgTxt(self);
                                    selfBoxLi.eq(obj.init0).addClass("bigW");
                                });
                            }
                            selv.oldInd = selv.ind;
                        }
                    }, obj.delayt);
                });

                selfBox.on("mouseleave", function () {
                    var self = $(this);
                    var selfBoxLi = self.find("li.libox");
                    clearTimeout(selv.mouseTimer);
                    if (selv.oldInd != obj.init0) {
                        hideBgTxt(selfBoxLi.eq(selv.oldInd));
                        //console.log(selv.oldInd);
                        selfBoxLi.eq(selv.oldInd).stop().animate({width: obj.sw}, obj.anit);
                        selfBoxLi.eq(obj.init0).stop().animate({width: obj.bw}, obj.anit);
                    }
                    else {
                        hideBgTxt(selfBoxLi.eq(obj.init0));
                    }
                    selfBoxLi.eq(obj.init0).addClass("bigW");
                    selv.oldInd = obj.init0;
                });
            });
        }
    })(jQuery);

    $(".index_con1").animPic({bw: "67%", sw: "33%", anit: 460, delayt: 300, init0: 0});
    $(".index_con2").animPic({bw: "50%", sw: "25%", anit: 300, delayt: 250, init0: 0});
    $(".index_con3").animPic({bw: "50%", sw: "25%", anit: 300, delayt: 250, init0: 2});


    //////////////////////////////////////////交叉页ajax代码/////////////////////////////////////////////////////////
    var $search = $("#jiaocha_text");
    //交叉页关键字搜索跳转
    $("#go_search11").click(function () {
        var keyword = $search.val();
        if ($.trim(keyword) == '' || keyword == $search.get(0).defaultValue) {
            return false;
        }
        window.open("/search/" + oCommon.getGenIndInfo() + "?key=" + encodeURIComponent(encodeURIComponent(keyword)).replace(/\'/g, '%27'));
    })
    $("body").on('click', ".key_search", function () {
        var key = $(this).find('span').html();
        window.open("/search/" + oCommon.getGenIndInfo() + "?key=" + encodeURIComponent(encodeURIComponent(key)).replace(/\'/g, '%27'));
    })

    //keyup Enter 搜索
    $search.keyup(function () {
        if (event.keyCode == 13) {
            var $obj = $(this);
            var keyword = $obj.val();
            if ($.trim(keyword) == '' || keyword == this.defaultValue) {
                return false;
            }
            window.open("/search/" + oCommon.getGenIndInfo() + "?key=" + encodeURIComponent(encodeURIComponent(keyword)).replace(/\'/g, '%27'));
        }
    })
    $search.focus(function () {
        var $obj = $(this);
        var text = $obj.val();
        if (text == this.defaultValue) {
            $obj.val('');
        }
    })
    //搜索框没有输入，则失去焦点时恢复默认值
    $search.on('blur', function (ev) {
        var $obj = $(this);
        var keywords = $obj.val();
        if ($.trim(keywords) == '') {
            $obj.val(this.defaultValue);
        }
    });
    // 上一页
    $('.con_width').on('click', '.btn_left', function () {
        var $obj = $(this);
        var $info = $obj.siblings('.info');
        var total = parseInt($info.data('tot'));   // 总页码
        var page = parseInt($info.data('page'));   // 页码    1 2 3
        var _page = --page;
        if (total < 2 || _page < 1) {
            $(this).removeClass('cur');
            return false;
        }
        // 左右翻页控制
        if (total < 2 || _page < 2) {
            $(this).removeClass('cur').siblings('span').addClass('cur');
        } else {
            $(this).addClass('cur').siblings('span').addClass('cur');
        }
        $info.data('page', _page);
        var column = parseInt($info.data('col'));  // 栏目
        fillHtml(column, page);
    });
    // 下一页
    $('.con_width').on('click', '.btn_right', function () {
        var $obj = $(this);
        var $info = $obj.siblings('.info');
        var total = parseInt($info.data('tot'));   // 总页码
        var page = parseInt($info.data('page'));   // 页码  1 2 3
        var _page = ++page;
        // 总数小于2|页数大于2|页面大于总数
        if (total < 2 || _page > 3 || _page > total) {
            $(this).removeClass('cur');
            return false;
        }
        // 左右翻页控制
        if (total < 2 || _page > 2 || _page >= total) {
            $(this).removeClass('cur').siblings('span').addClass('cur');
        } else {
            $(this).addClass('cur').siblings('span').addClass('cur');
        }
        $info.data('page', page);
        var column = parseInt($info.data('col'));  // 栏目
        fillHtml(column, _page);
    });
    function fillHtml(column, page) {
        var page = parseInt(page);
        if (column == 0) {
            return false;
        }
        var isWideScreen = $('.con_width').width() >= 1500 ? true : false;
        $.ajax({
            url: '/ajax/crosspagescroll/',
            type: 'POST',
            dataType: 'json',
            data: {col: column, page: page},
            success: function (data) {
                var str = '';
                switch (column) {
                    case 1: // 未来趋势
                    case 2: // 潮流解析
                        for (var i in data.lists) {
                            var tablename = data.lists[i].tablename;
                            var id = data.lists[i].id;
                            var iColumnId = data.lists[i].iColumnId;
                            var smallPath = data.lists[i].smallPath;
                            var columnName = data.lists[i].columnName ? data.lists[i].columnName : '';
                            var columnLink = data.lists[i].columnLink ? data.lists[i].columnLink : 'javascript:void(0);';
                            var columnEnName = data.lists[i].columnEnName ? data.lists[i].columnEnName : '';
                            var title = data.lists[i].title ? data.lists[i].title : '';
                            var description = data.lists[i].description ? data.lists[i].description : '';
                            str += '<li><a href="/details/report/t_' + tablename + '-id_' + id + '-col_' + iColumnId + '/" class="img_a" target="_blank" title="'+title+'"><img alt="' + title + '" src="' + smallPath + '"/>';
                            str += '<p></p><div><div class="txtAll"><p class="txtC">' + columnName + '</p></div><p class="txtTitle">' + title + '</p></div></a>';
                            if (columnName != '') {
                                str += '<a href="' + columnLink + '" target="_blank" class="link_a" title="'+columnName+'"><span class="arrow">' + columnName + '</span></a>';
                            }
                            str += '</li>';
                        }
                        if (column == 2) {
                            $('.analyzeData_ul').html(str);
                        } else {
                            $('.trendData_ul').html(str);
                        }
                        break;
                    case 3: // T台发布
                        for (var i in data.lists) {
                            str += '<li class="clearfix"><div class="left_pic fl"><a href="/runways/inside/id_' + data.lists[i].id + '/" class="img_a" target="_blank">';
                            str += data.lists[i].smallPath;
                            if (data.lists[i].region_text != null) {
                                str += '<span class="arrow">' + data.lists[i].region_text + '</span>';
                            }
                            str += '</a> </div><div class="right_cont fl">' + '<p class="title" title="' + data.lists[i].nme + '">' + '<a href="/runways/inside/id_' + data.lists[i].id + '/" class="img_a" target="_blank" title="'+data.lists[i].nme+'">' + data.lists[i].nme + '</a></p>';
                            str += '<div class="cont_bd"><span>设计师：<em>' + data.lists[i].designer + '</em></span><span>时间：<em>' + data.lists[i].for_date_text + '</em></span><span>地点：<em>' + data.lists[i].region_text + '</em></span></div>';
                            str += '<ul class="date_liulan"><li class="dateLi"><span class="date"></span>' + data.lists[i].create_time + '</li><li class="liulanLi"><span class="liulan"></span>浏览（' + data.lists[i].view_count + '）</li></ul>';
                            str += '</div></li>';
                        }
                        $('.runwaysData_ul').html(str);
                        break;
                    case 4: // 款式站
                        var _li = '';
                        _li += '<li class="bomLi bom-nav">';
                        _li += '<a href="##LINK##" target="_blank">';
                        _li += '<img class="hover-pic" src="/global/images/##STYLE##-content.jpg">';
                        _li += '<div class="bom-nav-cont">';
                        _li += '<img src="/global/images/content-nav-icon.png">';
                        _li += '<h3>##WORD##款式</h3>';
                        _li += '<p>最新上线&amp;品牌趋势</p>';
                        _li += '<div class="js-btn-hover"><span style="left: 0px;">查看全部</span><i style="right: -100px;"></i></div>';
                        _li += '</div>';
                        _li += '</a>';
                        _li += '</li>';

                        if (data.gen == 1) {
                            _li = _li.replace('##LINK##', '/stylezone/gen_1-ind_0-dis_1/').replace('##STYLE##', 'man').replace('##WORD##', '男装');
                        }
                        else if (data.gen == 2) {
                            _li = _li.replace('##LINK##', '/stylezone/gen_2-ind_0-dis_1/').replace('##STYLE##', 'woman').replace('##WORD##', '女装');
                        }
                        else if (data.gen == 5) {
                            _li = _li.replace('##LINK##', '/stylezone/gen_5-ind_0-dis_1/').replace('##STYLE##', 'child').replace('##WORD##', '童装');
                        }
                        else {
                            _li = '';
                        }

                        if ($.inArray(data.gen, [1,2,5]) >= 0) {
                            data.lists.splice(11,1);
                        }

                        for (var i in data.lists) {
                            var $_list = data.lists[i];
                            var tablename = $_list.tablename;
                            var id = $_list.id;
                            var iColumnId = $_list.iColumnId;
                            var columnName = typeof($_list.columnName) != 'undefined' ? $_list.columnName : '';
                            var columnLink = $_list.columnLink ? $_list.columnLink : 'javascript:void(0);';
                            var smallPath = $_list.smallPath;
                            var create_time = $_list.create_time ? $_list.create_time : $_list.update_time;
                            var link_season = $_list.link_season
                            var seasonName = $_list.seasonName != null && typeof($_list.seasonName) != 'undefined' ? $_list.seasonName : '';
                            var link_category = $_list.link_category;
                            var iCategory_text = $_list.iCategory_text != null && typeof($_list.iCategory_text) != 'undefined' ? $_list.iCategory_text : '';
                            var link_subcategory = $_list.link_subcategory;
                            var iSubcategory_text = $_list.iSubcategory_text != null && typeof($_list.iSubcategory_text) != 'undefined' ? $_list.iSubcategory_text : '';
                            var link_brand = $_list.link_brand;
                            var brand_text = $_list.brand_text != null && typeof($_list.brand_text) != 'undefined' ? $_list.brand_text : '';
                            var title = $_list.title;

                            str += '<li class="bomLi clearfix">';
                            str += '<a href="/details/style/t_' + tablename + '-id_' + id + '-col_' + iColumnId + '/" class="img_a" target="_blank" title="'+title+'">';
                            str += '<img alt="' + title + '" src="' + smallPath + '">';
                            str += '<div class="hovertime"></div>';
                            str += '<p class="Dtime"><em></em>' + create_time + '</p>';
                            str += '</a>';
                            str += '<div class="picTxt">';
                            str += '<p><a href="' + link_season + '" target="_blank" title="'+seasonName+'">' + seasonName + '</a></p>';
                            str += '<p><a target="_blank" href="' + link_category + '" title="'+iCategory_text+'">' + iCategory_text + '</a><a target="_blank" href="' + link_subcategory + '" title="'+iSubcategory_text+'">' + iSubcategory_text + '</a></p>';
                            str += '</div>';
                            str += '<p class="Picpp">';
                            if (brand_text != '') {
                                str += '<a href="' + link_brand + '" target="_blank" title="'+brand_text+'">' + brand_text + '</a></p>';
                            } else {
                                str += '其它</p>';
                            }
                            if (columnName != '') {
                                str += '<div class="cate_hovertime"></div><p class="cate_hover"><a href="' + columnLink + '" target="_blank" title="'+columnName+'">' + columnName + '</a></p>';
                            }
                            str += '</li>';
                        }
                        var $content = $('.cont_section4_lists');
                        str = _li + str;
                        $content.find('ul').html(str);
                        if (!isWideScreen) {
                            var len5 = $content.find('li').length;
                            if (len5 > 12) {
                                $(".cont_section4_lists li:last").hide();
                                $(".cont_section4_lists li:last").prev().hide();
                                $(".cont_section4_lists li:eq(6)").removeClass('bomLi');
                            }
                        }
                        break;
                    case 5: // 品牌库
                        for (var i in data.lists) {
                            var id = data.lists[i].id;
                            var name = data.lists[i].name;
                            var b_name = data.lists[i].b_name;
                            var count = data.lists[i].count;
                            str += '<li><a href="/brands/detail/id_' + id + '/" target="_blank"><div class="showpp">';
                            str += '<p class="bt">' + name + ' <br>' + b_name + '</p>';
                            str += '<span>&mdash;&mdash;</span>';
                            str += '<p class="gongyou">共更新：<em>' + count + '</em>款</p>';
                            str += '</div></a></li>';
                        }
                        $('.brandsData_ul').html(str);
                        if (!isWideScreen) {
                            var len8 = $(".cont_section6_list li").length;
                            if (len8 > 3) {
                                $(".cont_section6_list li:last").hide();
                            }
                        }
                        break;
                    case 6: // 手稿合辑
                        for (var i in data.lists) {
                            var tablename = data.lists[i].tablename;
                            var id = data.lists[i].id;
                            var iColumnId = data.lists[i].iColumnId;
                            var iPreviewMode = data.lists[i].iPreviewMode
                            var smallPath = data.lists[i].smallPath;
                            var create_time = data.lists[i].create_time
                            var name = data.lists[i].name
                            str += '<li>';
                            str += '<a href="/books/seclist/t_' + tablename + '-id_' + id + '-col_' + iColumnId + '-yl_' + iPreviewMode + '/" class="img_a" target="_blank" title="'+name+'"><img alt="' + name + '" src="' + smallPath + '"/></a>';
                            /*str += '<p class="Dtime"><em></em>'+create_time+'</p>';*/
                            str += '<p class="" title="' + (name ? name : '') + '">' + '<a href="/books/seclist/t_' + tablename + '-id_' + id + '-col_' + iColumnId + '-yl_' + iPreviewMode + '/" target="_blank" title="'+(name ? name : '')+'">' + (name ? name : '') + '</a></p>';
                            str += '</li>';
                        }
                        $('.booksData_ul').html(str);
                        if (!isWideScreen) {
                            var len6 = $(".cont_section5_list li").length;
                            if (len6 > 4) {
                                $(".cont_section5_list li:last").hide();
                            }
                        }
                        break;
                    case 7: // 设计素材
                        for (var i in data.lists) {
                            var tablename = data.lists[i].tablename;
                            var id = data.lists[i].id;
                            var iColumnId = data.lists[i].iColumnId;
                            var smallPath = data.lists[i].smallPath;
                            var title = data.lists[i].title;

                            str += '<li><a href="/details/style/t_' + tablename + '-id_' + id + '-col_' + iColumnId + '/" target="_blank" title="'+title+'"><img alt="' + title + '" src="' + smallPath + '"></a></li>';
                        }
                        $('.referencesData_ul').html(str);
                        if (!isWideScreen) {
                            var len7 = $(".cont_section5_right_listN li").length;
                            if (len7 > 2) {
                                $(".cont_section5_right_listN").find('li:last').hide();
                            }
                        }
                        break;
                    case 8: // 灵感源
                        for (var i in data.lists) {
                            var tablename = data.lists[i].tablename;
                            var id = data.lists[i].id;
                            var iColumnId = data.lists[i].iColumnId;
                            var smallPath = data.lists[i].smallPath;
                            var title = data.lists[i].title;

                            str += '<li><a href="/details/style/t_' + tablename + '-id_' + id + '-col_' + iColumnId + '/" target="_blank" title="'+title+'"><img alt="' + title + '" src="' + smallPath + '"/></a></li>';
                        }
                        $('.inspirationData_ul').html(str);
                        break;
                    default :
                        return false;
                }
            }
        });
    }

    //懒加载
    $(".lazyload img").lazyload({effect: "fadeIn"});
});
