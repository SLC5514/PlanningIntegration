/**
 * Created by Administrator on 2016/11/21.
 */

$(function () {
    if ($("#fLng").val() > 0 || $("#fLat").val() > 0) {
        search_map($("input[name='companyAddr']").get(0));
        markAuto($("#fLng").val(), $("#fLat").val());
        //添加已标记提示
        $("#mark").show();
    }

    $(".a_activity_box").on("click", "span", function () {
        $(this).siblings("ul").show();
        $(this).closest('li').css('z-index', '6');
    }).on("mouseleave", function () {
        $(this).find("ul").hide();
        $(this).closest('li').css('z-index', '0');
    });

    //公司类型、地区切换
    $(".company_type, .act_area, .act_city").on("click", "li", function () {
        var self = $(this);
        var id = self.data('id');

        if (self.parent('ul').hasClass('act_area')) {
            getAddrDateAndRender(id);
        }

        self.parents('.a_activity_box').find('input').val(id);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");

        if ($(".act_area").siblings('input').val() == 2) {//国外
            $("#container").hide();

            $("input[name='activityAddrMap']").val('');
            $("#fLng").val('');
            $("#fLat").val('');

            $("#mark").hide();
            //重置地图
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
        } else {
            $("#container").show();
        }
        self.parent().hide();
    });

    //保存不发布
    $("#saveNotRelease").on("click", function () {
        var options = ajaxSendRequest('notrelease');
        $(this).closest('form').ajaxSubmit(options);
        return false;
    });
    //发布
    $("#saveAndRelease").on("click", function () {
        var options = ajaxSendRequest('release');
        $(this).closest('form').ajaxSubmit(options);
        return false;
    });
    //保存并预览
    $("#saveAndPreview").on("click", function () {
        var options = ajaxSendRequest('preview');
        $(this).closest('form').ajaxSubmit(options);
        return false;
    });

    var validatorForm = function () {
        var messages = {
            companyName:"请输入企业名称",
            sImageFigure:"请上传形象展示图",
            sImageEnterprise:"请上传企业店招图",
            companyType:"请选择公司类型",
            companyArea:"请选择地区",
            companyCity:"请选择城市",
            companyAddr:"请输入详细地址",
            contactName:"请输入联系人",
            contactPhone:"请输入联系方式",
            contactPhoneError:"请正确输入联系方式",
            companyAbstract:"请输入公司简介",
            officialWebUrl:"请输入企业官网",
            officialWebUrlError:"请确认企业官网是否正确",
        };
        var form = document.forms["alliance_form"];
        //alert();return false;
        if (form.companyName.value == "") {
            layer.alert(messages.companyName);
            return false;
        }
        if (form.sImageFigure.value == "") {
            layer.alert(messages.sImageFigure);
            return false;
        }
        if (form.sImageEnterprise.value == "") {
            layer.alert(messages.sImageEnterprise);
            return false;
        }
        if (form.companyType.value == "") {
            layer.alert(messages.companyType);
            return false;
        }
        if (form.companyArea.value == "") {
            layer.alert(messages.companyArea);
            return false;
        }
        if (form.companyCity.value == "") {
            layer.alert(messages.companyCity);
            return false;
        }
        if (form.companyAddr.value == "") {
            layer.alert(messages.companyAddr);
            return false;
        }
        //国内
        if (form.companyArea.value == 1) {
            if ( !(form.fLng.value > 0 || form.fLat.value > 0) ) {
                layer.alert('请在地图上标记地址');
                return false;
            }
        }

        if (form.companyAddr.value == "") {
            layer.alert(messages.companyAddr);
            return false;
        }
        if (form.contactName.value == "") {
            layer.alert(messages.contactName);
            return false;
        }
        var contactPhone = form.contactPhone.value;
        if (contactPhone == "") {
            layer.alert(messages.contactPhone);
            return false;
        } else if (!(/^1[34578]\d{9}$/.test(contactPhone) || /^(\d{3,4}|\d{3,4}(\-|\s)?)+\d+\-?\d+$/.test(contactPhone))){
            layer.alert(messages.contactPhoneError);
            return false;
        }
        if (form.companyAbstract.value == "") {
            layer.alert(messages.companyAbstract);
            return false;
        }
        var officialWebUrl = form.officialWebUrl.value;
        if ($("#official").is(":checked")) {
            if (officialWebUrl == "") {
                layer.alert(messages.officialWebUrl);
                return false;
            }
        }
        if (officialWebUrl != "") {
            if (!/^(http|https):\/\/(.*)?$/.test(officialWebUrl)) {
                layer.alert(messages.officialWebUrlError);
                return false;
            }
        }
        return true;
    }

    var ajaxSendRequest = function (url) {

        return {
            beforeSubmit: validatorForm,
            url: '/activity/allianceop/'+url+'/',
            type: 'post',
            dataType: 'JSON',
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
            },
            clearForm: true,
            timeout: 3000
        };
    };

    var getAddrDateAndRender = function (id) {
        $.post('/activity/addrdate/',{'id':id}, function (data) {
            var areaTemplate = '';
            var cityTemplate = '';
            var area = $(".act_addr .act_area");

            for (var i in data.area) {
                areaTemplate += "<li data-id=\""+data.area[i].id+"\">"+data.area[i].sName+"</li>";
            }
            $("input[name='companyArea']").val(data.defaultArea.id);
            area.siblings("span").html(data.defaultArea.sName+"<i></i>");
            area.html(areaTemplate);

            var city = $(".act_addr .act_city");
            for (var i in data.city) {
                cityTemplate += "<li data-id=\""+data.city[i].id+"\">"+data.city[i].sName+"</li>";
            }
            $("input[name='companyCity']").val(data.defaultCity.id);
            city.siblings("span").html(data.defaultCity.sName+"<i></i>");
            city.html(cityTemplate);

            $(".post_form .act_addr").show();
        }, "JSON");
    };


});

var ajaxPosterUpload = function(_this, _target) {
    var objTarget;
    if (_target == 'figure') {
        objTarget = $("#sImageFigure");
    } else if (_target == 'enterprise') {
        objTarget = $("#sImageEnterprise");
    }
    var options = {
        beforeSubmit: function() {
            return true;
        },
        type:'POST',
        url:'/activity/upload/'+_target+'/?'+Math.random(),
        dataType:'json',
        async:false,
        success: function(data) {
            if(data.error == 0) {
                objTarget.val(data.url);
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