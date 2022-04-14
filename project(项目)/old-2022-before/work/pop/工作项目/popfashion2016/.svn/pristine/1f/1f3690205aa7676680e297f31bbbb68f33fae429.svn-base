/**
 * Created by Administrator on 2016/11/21.
 */

$(function () {
    // 点击搜索后,页面form表单里面内容再次写入
    var input_obj = $('.cp_region input[type="hidden"]');
    $.each(input_obj, function() {
        var tempaltes = '';
        var self= $(this);
        var input_val = self.val();
        var li = self.siblings('ul').find('li[data-id="'+input_val+'"]');
        var text = li.html();
        self.siblings('span').html(text);
        li.hide().siblings('li').show();
        // 以下对地区的处理
        var type = li.data('type');
        if(input_val && type != 'undefind') {
            tempaltes += "<li style='display: none;' data-id='0'>请选择</li>";
            var child = li.parents('.cp_region').siblings().find(".act_child");

            $.post('/activity/childtype/', {"id":input_val, "iType":type}, function (data) {
                for (var i in data.child) {
                    tempaltes += "<li data-id=\""+data.child[i].id+"\">"+data.child[i].sName+"</li>";
                }
                child.html(tempaltes);
                var child_input = self.parents('.cp_region').next('.cp_region').find('input');
                var child_input_val = child_input.val();
                if(child_input_val) {
                    var child_li = child_input.siblings('ul').find('li[data-id="'+child_input_val+'"]');
                    var child_text = child_li.html();
                    child_li.hide().siblings('li').show();
                    child_input.siblings('span').html(child_text);
                    child_input.siblings('input').val(child_input_val);
                }else {
                    child_input.siblings("span").html('请选择');
                    child_input.siblings('input').val('0');
                }
            },"JSON");
        }
    });

    // 搜索条件点击
    $(".cp_type, .cp_region").on("mouseleave", function () {
        $(this).find("ul").hide();
        $(this).css('z-index', '0');
    }).on("click", "span,i", function () {
        $(this).siblings("ul").show();
        $(this).closest('.common_div').css('z-index', '5');
    });

    // 地区切换
    $(".cp_region .act_area li").click(function () {
        var self = $(this);
        var curId = parseInt(self.data("id"));
        var iType = 1;
        getChildByParent(self, curId, iType);
    });


    //类型和城市切换
    $(".act_child, .act_main_type").on("click", "li", function () {
        var self = $(this);
        var id = self.data('id');
        self.parents('.common_div').find('input').val(id);
        self.parents('.common_div').find('span').html(self.text()+"<i></i>");
        self.hide().siblings('li').show().parent().hide();
    });

    // 搜索提交
    $('.manage_btn .button').on('click', function() {
        var companyName = $('.manage_search input[name="companyName"]').val();
        var companyArea = $('.manage_search input[name="companyArea"]').val();
        var companyCity = $('.manage_search input[name="companyCity"]').val();
        var companyType = $('.manage_search input[name="companyType"]').val();
        var contactName = $('.manage_search input[name="contactName"]').val();
        var contactPhone = $('.manage_search input[name="contactPhone"]').val();
        var url = '/activity/alliancemanage/';
        var param = '';

        if(companyArea && companyArea != 0) {
            param += '-are_' + companyArea;
        }
        if(companyCity && companyCity != 0) {
            param += '-cit_' + companyCity;
        }
        if(companyType && companyType != 0) {
            param += '-typ_' + companyType;
        }
        if(contactName && contactName != 0) {
            param += '-ctn_' + encodeURIComponent(encodeURIComponent(contactName));
        }
        if(contactPhone && contactPhone != 0) {
            param += '-ctp_' + encodeURIComponent(encodeURIComponent(contactPhone));
        }
        if(param.substr(0, 1) == '-') {
            param = param.substr(1);
            url = url + param + '/';
        }
        if($.trim(companyName)) {
            url += '?key=' + encodeURIComponent(encodeURIComponent(companyName));
        }
        window.location.href = url;
    });

    // 删除活动
    $('.del_activity').on('click', function() {
        if(confirm('是否确定删除此活动！') == false){
            return false;
        }
        var id = $(this).data('id');
        $.ajax({
            url: '/activity/delete/',
            type: 'POST',
            dataType: 'json',
            data: {id:id},
            success: function(data) {
                if(data['status'] == 'success') {
                    alert('活动删除成功');
                    location = location;
                }else {
                    alert('活动删除失败');
                }
            }
        });
    });

    function getChildByParent (self, curId, iType) {
        var defId = parseInt(self.parent().siblings('input').val());
        //拿当前值取活动子类型
        if (curId == defId) {
            self.parent("ul").hide();
            return;
        }
        self.parent("ul").hide();
        self.parents('.cp_region').find('input').val(curId);
        self.parents('.cp_region').find('span').html(self.text()+"<i></i>");
        self.hide().siblings('li').show();
        var tempaltes = "";
        var child = self.parents('.cp_region').siblings().find(".act_child");
        if(curId) {
            tempaltes += "<li style='display: none;' data-id='0'>请选择</li>";
            $.post('/activity/childtype/', {"id":curId, "iType":iType}, function (data) {
                for (var i in data.child) {
                    tempaltes += "<li data-id=\""+data.child[i].id+"\">"+data.child[i].sName+"</li>";
                }
                child.html(tempaltes);
                child.siblings("span").html('请选择');
                child.siblings('input').val('0');
            },"JSON");
        }else {
            child.html(tempaltes);
            child.siblings("span").html('请选择');
            child.siblings('input').val('0');
        }
    }

});