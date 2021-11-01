/**
 * Created by Administrator on 2016/11/21.
 */

$(function () {

    //活动id
    var activityId = $("input[name='activityId']");
    //活动主题
    var activityTheme = $("input[name='activityTheme']");
    //活动海报
    var activityPoster = $("input[name='activityPoster']");
    //活动开始时间
    var activityBeginDate = $("input[name='activityBeginDate']");
    var activityBeginTime = $("input[name='activityBeginTime']");
    //截止时间
    var activityEndDate = $("input[name='activityEndDate']");
    var activityEndTime = $("input[name='activityEndTime']");
    //活动类型
    var activityType = $("input[name='activityType']");
    var activityChildType = $("input[name='activityChildType']");
    //活动地点
    var activityArea = $("input[name='activityArea']");
    var activityCity = $("input[name='activityCity']");
    var activityAddress = $("input[name='activityAddress']");
    var activityAddrMap = $("input[name='activityAddrMap']");

    var fLng = $("#fLng");//经度
    var fLat = $("#fLat");//纬度
    //活动网址
    var activityUrl = $("input[name='activityUrl']");
    var activityDetails = $("textarea[name='activityDetails']");

    var sponsor = $("input[name='sponsor']");
    var contactName = $("input[name='contactName']");
    var contactPhone = $("input[name='contactPhone']");


    if (fLng.val() > 0 || fLat.val() > 0) {
        search_map(activityAddress.get(0));
        markAuto(fLng.val(), fLat.val());
        //添加已标记提示
        $("#mark").show();
    }
    if (activityArea.val() == 2) {//国外
        //隐藏地图
        $("#container").hide();
    } else {
        $("#container").show();
    }

    //活动类型和活动地点点击
    $(".a_activity_box").on("mouseleave", function () {
        $(this).find("ul").hide();
        $(this).closest('li').css('z-index', '0');
    }).on("click", "span", function () {
        $(this).siblings("ul").show();
        $(this).closest('li').css('z-index', '6');
    });

    //活动类型切换
    $(".act_type .act_main_type li").click(function () {
        var self = $(this);
        var defTypeId = parseInt(activityType.val());
        var curTypeId = parseInt(self.data("id"));
        var col_id = $('#col').val();

        //拿当前值取活动子类型
        //if (curTypeId == defTypeId) {
        //    self.parent("ul").hide();
        //    return;
        //}

        if (curTypeId == 17) {
            //线上
            $(".post_form .website_li").show();
            $(".post_form .act_addr").hide();
        } else {
            //线下
            $(".post_form .website_li").hide();

            //取地区数据并渲染页面
            getAddrDateAndRender();
        }

        self.parent("ul").hide();
        self.parents('.a_activity_box').find('input').val(curTypeId);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");

        $.post('/activity/childtype/', {"id":curTypeId,"col":col_id}, function (data) {
            var tempaltes = "";
            var child = $(".act_child_type");
            for (var i in data.child) {
                tempaltes += "<li data-id=\""+data.child[i].id+"\">"+data.child[i].sName+"</li>";
            }
            child.html(tempaltes);

            child.siblings("span").html(data.defval.sName+"<i></i>");
            activityChildType.val(data.defval.id);
        },"JSON");
    });

    //活动子类型切换
    $(".act_child_type").on("click", "li", function () {
        var self = $(this);
        var id = self.data('id');

        self.parents('.a_activity_box').find('input').val(id);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");

        self.parent().hide();
    })

    //活动区域切换
    $(".act_addr .act_area").on("click", "li", function () {
        var self = $(this);
        var defAreaId = activityArea.val();
        var curAreaId = self.data("id");

        if (curAreaId == defAreaId) {
            self.parent("ul").hide();
            return;
        }
        if (curAreaId == 2) {//国外
            //隐藏地图
            $("#container").hide();
            $("#mark").hide();

            $("input[name='activityAddrMap']").val('');
            $("#fLng").val('');
            $("#fLat").val('');
            //重置地图
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
        } else {
            $("#container").show();
        }

        self.parent("ul").hide();
        self.parents('.a_activity_box').find('input').val(curAreaId);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");

        getAddrDateAndRender(curAreaId);
    })

    //活动城市切换
    $(".act_city").on("click", "li", function () {
        var self = $(this);
        var id = self.data('id');

        self.parents('.a_activity_box').find('input').val(id);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");
        self.parent("ul").hide();
    })


    //时间
    $(".k_time_hour").on("click", ".click_hour", function () {
        $(this).siblings(".hour_downlist").show();
        $(this).closest('li').css('z-index', '6');
    }).on("mouseleave", function () {
        $(this).find(".hour_downlist").hide();
        $(this).closest('li').css('z-index', '0');
    });

    $(".k_time_hour").on("click", "li", function () {
        $(this).closest(".k_time_hour").find("input").val($(this).text());
        $(this).closest(".hour_downlist").hide();
    });

    //保存不发布
    $("#saveNotRelease").on("click", function () {
        ajaxSendRequest('notrelease');
    });
    //发布
    $("#saveAndRelease").on("click", function () {
        ajaxSendRequest('release');
    });
    //保存并预览
    $("#saveAndPreview").on("click", function () {
        ajaxSendRequest('preview');
    });

    var ajaxSendRequest = function (url) {
        var paramsData = {
            "activityId": activityId.val(),
            "col":$('#col').val(),
            "activityTheme":activityTheme.val(),
            "activityPoster":activityPoster.val(),
            "activityBeginDate":activityBeginDate.val(),
            "activityBeginTime":activityBeginTime.val(),
            "activityEndDate":activityEndDate.val(),
            "activityEndTime":activityEndTime.val(),
            "activityType":activityType.val(),
            "activityChildType":activityChildType.val(),
            "activityArea":activityArea.val(),
            "activityCity":activityCity.val(),
            "activityAddress":activityAddress.val(),
            "activityAddrMap":activityAddrMap.val(),
            "fLng":fLng.val(),
            "fLat":fLat.val(),
            "activityUrl":activityUrl.val(),
            "activityDetails": editor.html(),
            "sponsor":sponsor.val(),
            "official":2,//非官方
            "contactName":contactName.val(),
            "contactPhone":contactPhone.val()
        }

        var official = $("input[name='official']:checked");
        if (official.length > 0) {
            paramsData.official = official.val();
        }

        var options = {
            url: '/activity/opsave/'+url+'/',
            type: 'POST',
            data: paramsData,
            beforeSend:formAjaxSubmitValidate,
            dataType: 'JSON',
            timeout: 3000,
            success: function (data) {
                if (data.status == "success") {
                    alert('保存成功');
                    if (data.url) {
                        window.open(data.url);
                    }

                    location.href = location.href;
                } else {
                    oCommon.noPower( '', data.status );
                }
            }
        };

        $.ajax(options);
    };

    var formAjaxSubmitValidate = function () {
        var message = {
            "activityTheme":"请填写活动主题",
            "activityPoster":"请上传活动海报",
            "activityTimeMsg":"开始时间不能大于结束时间",
            "activityType":"请选择活动类型",
            "activityUrl":"请正确填写活动网址，如 http://www.baidu.com",
            "activityAddr":"请填写活动地址",
            "activityDetails":"请填写活动详情",
            "sponsor":"请填写活动主办方",
            "contactName":"请填写主办方联系人",
            "contactPhone":"请填写主办方联系方式",
            "contactPhoneFormart":"请确认主办方联系方式格式是否正确",
            "marker":"请在地图上标记活动地址"
        };
        if (!activityTheme.val()) {
            layer.alert(message.activityTheme);
            return false;
        }
        if (!activityPoster.val()) {
            layer.alert(message.activityPoster);
            return false;
        }
        if (!activityBeginDate.val() || !activityEndDate.val() || activityBeginDate.val() > activityEndDate.val() || (activityBeginDate.val() == activityEndDate.val() && activityBeginTime.val() >= activityEndTime.val())) {
            layer.alert(message.activityTimeMsg);
            return false;
        }
        if (!activityType.val()) {
            layer.alert(message.activityType);
            return false;
        }
        if (activityType.val() == 18 && activityArea.val() == 1) {
            if (!(fLng.val()>0 || fLat.val()>0)) {
                layer.alert(message.marker);
                return false;
            }
        }

        if (activityType.val() && parseInt(activityType.val()) == 17) {//线上
            if (!activityUrl.val() || !/^(http|https):\/\/(.*)?$/.test(activityUrl.val())) {
                layer.alert(message.activityUrl);
                return false;
            }
        } else if (activityType.val() && parseInt(activityType.val()) == 18) {//线下
            if (!activityArea.val() || !activityAddress.val()) {
                layer.alert(message.activityAddr);
                return false;
            }
        }
        if (editor.isEmpty()) {
            layer.alert(message.activityDetails);
            return false;
        }
        if (!sponsor.val()) {
            layer.alert(message.sponsor);
            return false;
        }
        if (!contactName.val()) {
            layer.alert(message.contactName);
            return false;
        }
        if (!contactPhone.val()) {
            layer.alert(message.contactPhone);
            return false;
        } else if (!(/^1[34578]\d{9}$/.test(contactPhone.val())) && !/^(\d{3,4}|\d{3,4}(\-|\s)?)+\d+\-?\d+$/.test(contactPhone.val())) {
            layer.alert(message.contactPhoneFormart);
            return false;
        }

        return true;
    }

    var getAddrDateAndRender = function (id) {
        $.post('/activity/addrdate/',{'id':id}, function (data) {
            var areaTemplate = '';
            var cityTemplate = '';
            var area = $(".act_addr .act_area");

            for (var i in data.area) {
                areaTemplate += "<li data-id=\""+data.area[i].id+"\">"+data.area[i].sName+"</li>";
            }
            $("input[name='activityArea']").val(data.defaultArea.id);
            area.siblings("span").html(data.defaultArea.sName+"<i></i>");
            area.html(areaTemplate);

            var city = $(".act_addr .act_city");
            for (var i in data.city) {
                cityTemplate += "<li data-id=\""+data.city[i].id+"\">"+data.city[i].sName+"</li>";
            }
            $("input[name='activityCity']").val(data.defaultCity.id);
            city.siblings("span").html(data.defaultCity.sName+"<i></i>");
            city.html(cityTemplate);

            $(".post_form .act_addr").show();
        }, "JSON");
    };


});

var ajaxPosterUpload = function(_this) {
    var options = {
        beforeSubmit: function() {
            return true;
        },
        type:'POST',
        url:'/activity/upload/poster/?'+Math.random(),
        dataType:'json',
        async:false,
        success: function(data) {
            if(data.error == 0) {
                $("input[name='activityPoster']").val(data.url);
                $(_this).closest(".post_cover").find(".posterimg_outside img").attr("src", data.url);
                return true;
            } else {
                oCommon.noPower( '', '上传失败' );
                return false;
            }
        },
        error: function(error) {
            oCommon.noPower( '', error.responseText );
            return false;
        }
    };
    $(_this).closest('form').ajaxSubmit(options);
};


// 客户服务
$(function () {
    // tab切换
    var $tabli =$(".service_tab li");
    var $contain =$(".tab_contain");
    $contain.eq(0).show();
    $tabli.on('click', function () {
        var index =$(this).index();
        $(this).addClass('cur').siblings().removeClass('cur');
        $contain.eq(index).show().siblings().hide();
        if ($(".sv_auth_li.cur").length > 0) {
            $(".service_contain").css('background','#fff');
        }else{
            $(".service_contain").css('background','#f5f5f5');
        }
    });    
    $(window).scroll(function () {
        var conwidth =$(".contentW").width();
        var scrollTop = $(window).scrollTop();
        var display1 = $(".tab1").css('display');
        var display2 = $(".tab2").css('display');
        var effect_art1 = $(".effect_art1");
        var effect_art2 = $(".effect_art2");
        var client_sv_lists =$(".client_sv_lists");
        var welfareImg_01 = $(".welfare_01 .sv_img");
        var welfareFont_01 = $(".welfare_01 .sv_font");
        var welfareImg_02 = $(".welfare_02 .sv_img");
        var welfareFont_02 = $(".welfare_02 .sv_font");
        var welfareImg_03 = $(".welfare_03 .sv_img");
        var welfareFont_03 = $(".welfare_03 .sv_font");
        var welfareImg_04 = $(".welfare_04 .sv_img");
        var welfareFont_04 = $(".welfare_04 .sv_font");
        var welfareImg_05 = $(".welfare_05 .sv_img");
        var welfareFont_05 = $(".welfare_05 .sv_font");
        if (display1 == 'block') {
            if (scrollTop >=560) {
                effect_art1.stop().animate({left:0}, 500);
                effect_art2.stop().animate({right:0}, 500);
            }
            if (scrollTop >= 1200) {
                client_sv_lists.stop().animate({'top':'0'},500);
            }
        }
        if (display2 == 'block') {
            if (scrollTop > 1000) {
                welfareImg_01.stop().animate({left:0},500);
                welfareFont_01.stop().animate({left:0},500);
            }
            if (scrollTop >3100) {
                    welfareImg_04.stop().animate({right:0},500);
                    welfareFont_04.stop().animate({left:0},500);
                }
            if (scrollTop >3800) {
                welfareImg_05.stop().animate({bottom:-60},500);
                welfareFont_05.stop().animate({top:0},500);
            }   
            if (conwidth == 1500) {
                if (scrollTop > 1700) {
                    welfareImg_02.stop().animate({right:-150},500);
                    welfareFont_02.stop().animate({left:0},500);
                }
                if (scrollTop >2300) {
                    welfareImg_03.stop().animate({left:128},500);
                    welfareFont_03.stop().animate({right:144},500);
                }             
            }else if(conwidth ==1200){
                if (scrollTop > 1700) {
                    welfareImg_02.stop().animate({right:-200},500);
                    welfareFont_02.stop().animate({left:0},500);
                }
                if (scrollTop >2300) {
                    welfareImg_03.stop().animate({left:0},500);
                    welfareFont_03.stop().animate({right:0},500);
                }                        
            }
        }      
    });

    
    $('body').on('mouseenter', '.image_box', function () {
        var self = $(this);
        var index = self.closest('li').index();
        var conwidth =$(".contentW").width();
        if (conwidth == 1500) {
            var per = (index + 1) % 4;
            if(per == 0){
                self.closest('li').addClass('curright');
            }
        }else if (conwidth == 1200) {
            var per = (index +1) % 3;
            if (per == 0) {
                self.closest('li').addClass('curright');
            }
        } 
    });
    var timer;
    $('body').on('mouseenter', '.effect_box', function (e) {
        var self =$(this);
        clearTimeout(timer);
        timer = setTimeout(function() {
            self.closest('li').addClass('animate_hover');
			var img = self.closest('li').find('img');
			var alt = img.data("name");
			var title = img.data("position")+'：'+img.data("name");
			img.attr('alt',alt);
			img.attr('title',title);
        },500);
    });
    $('body').on('mouseleave', '.effect_box', function (e) {
        var self =$(this);
        clearTimeout(timer);
        self.closest('li').removeClass('animate_hover');
        var img = self.closest('li').find('img');
        var alt = img.data("title");
        var title = img.data("time")+img.data("title");
        img.attr('alt',alt);
        img.attr('title',title);
    });
    if(navigator.appName == "Microsoft Internet Explorer" && (navigator.appVersion.match(/7./i)=="7." || navigator.appVersion.match(/8./i)=="8." || navigator.appVersion.match(/9./i)=="9.")){
        $(".client_sv_lists li").addClass('ie_box');
        $('body').on('mouseenter', '.effect_box', function () {
            var self = $(this);            
            var small_img =self.find('.small_img');
            var src =self.find('img.big_image').attr('src');
            small_img.append('<img src="'+src+'">')
            self.find('.text_desc').stop(true,true).fadeIn(200);
        });
        $('body').on('mouseleave', '.client_sv_lists li', function() {
            $(this).find('.text_desc').stop(true,true).fadeOut(200);
        });
    }
});