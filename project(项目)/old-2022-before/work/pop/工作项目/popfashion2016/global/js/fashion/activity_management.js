/**
 * Created by Administrator on 2016/11/21.
 */

$(function () {
    // 点击搜索后,页面form表单里面内容再次写入
    var input_obj = $('.a_activity_box input[type="hidden"]');
    $.each(input_obj, function() {
        var tempaltes = '';
        var self= $(this);
        var input_val = self.val();
        var li = self.siblings('ul').find('li[data-id="'+input_val+'"]');
        var text = li.html();
        self.siblings('span').html(text);
        li.hide().siblings('li').show();
        // 以下对活动类型和活动地区二级的处理
        var type = li.data('type');
        var col = $("#col").val();
        if(input_val && type != 'undefind') {            
            tempaltes += "<li style='display: none;' data-id='0'>请选择</li>";
            var child = li.parents('.a_activity_box').siblings().find(".act_child");

            $.post('/activity/childtype/', {"id":input_val, "iType":type,"col":col}, function (data) {
                for (var i in data.child) {
                    tempaltes += "<li data-id=\""+data.child[i].id+"\">"+data.child[i].sName+"</li>";
                }
                child.html(tempaltes);
                var child_input = self.parents('.a_activity_box').next('.a_activity_box').find('input');
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
    $(".a_activity_box").on("mouseleave", function () {
        $(this).find("ul").hide();
        $(this).css('z-index', '0');
    }).on("click", "span,i", function () {
        $(this).siblings("ul").show();
        $(this).closest('.common_div').css('z-index', '5');
    });

    //活动类型切换
    $(".a_activity_box .act_main_type li").click(function () {
        var self = $(this);
        var curId = parseInt(self.data("id"));
        var iType = 3;
        var col = $("#col").val();
        getChildByParent(self, curId, iType,col);
    });

    // 活动地区切换
    $(".a_activity_box .act_area li").click(function () {
        var self = $(this);
        var curId = parseInt(self.data("id"));
        var iType = 1;
        getChildByParent(self, curId, iType);
    });

    //类型和地区子类型切换
    $(".act_child").on("click", "li", function () {
        var self = $(this);
        var id = self.data('id');
        self.parents('.a_activity_box').find('input').val(id);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");
        self.hide().siblings('li').show().parent().hide();
    });
    // 搜索提交
    $('.manage_btn .button').on('click', function() {
        var sTheme = $('.manage_search input[name="sTheme"]').val();
        var activityArea = $('.manage_search input[name="activityArea"]').val();
        var activityCity = $('.manage_search input[name="activityCity"]').val();
        var iCheckStatus = $('.manage_search input[name="iCheckStatus"]').val();
        var activityType = $('.manage_search input[name="activityType"]').val();
        var activityChildType = $('.manage_search input[name="activityChildType"]').val();
        var activityBeginTime = $('.manage_search input[name="activityBeginTime"]').val();
        var activityEndTime = $('.manage_search input[name="activityEndTime"]').val();
        var iOfficial = $('.manage_search input[name="iOfficial"]').val();
        var col_id = $('#col').val();
        var url = '/activity/manage/';
        var param = '';
        if(col_id && col_id != 0) {
            param += '-msgtyp_' + col_id;
        }
        if(activityArea && activityArea != 0) {
            param += '-are_' + activityArea;
        }
        if(activityCity && activityCity != 0) {
            param += '-cit_' + activityCity;
        }
        if(iCheckStatus && iCheckStatus != 0) {
            param += '-che_' + iCheckStatus;
        }
        if(activityType && activityType != 0) {
            param += '-typ_' + activityType;
        }
        if(activityChildType && activityChildType != 0) {
            param += '-chi_' + activityChildType;
        }
        if(activityBeginTime && activityBeginTime != '请选择开始时间') {
            param += '-beg_' + oCommon.popReplace(activityBeginTime);
        }
        if(activityEndTime && activityEndTime != '请选择结束时间') {
            param += '-end_' + oCommon.popReplace(activityEndTime);
        }
        if(iOfficial && iOfficial != 0) {
            param += '-off_' + iOfficial;
        }
        if(param.substr(0, 1) == '-') {
            param = param.substr(1);
            url = url + param + '/';
        }
        if($.trim(sTheme)) {
            url += '?key=' + encodeURIComponent(encodeURIComponent(sTheme));
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
                    window.location.href = location.href;
                }else {
                    alert('活动删除失败');
                }
            }
        });
    });
    function getChildByParent (self, curId, iType,col) {
        var defId = parseInt(self.parent().siblings('input').val());
        //拿当前值取活动子类型
        if (curId == defId) {
            self.parent("ul").hide();
            return;
        }
        self.parent("ul").hide();
        self.parents('.a_activity_box').find('input').val(curId);
        self.parents('.a_activity_box').find('span').html(self.text()+"<i></i>");
        self.hide().siblings('li').show();
        var tempaltes = "";
        var child = self.parents('.a_activity_box').siblings().find(".act_child");
        if(curId) {
            tempaltes += "<li style='display: none;' data-id='0'>请选择</li>";
            $.post('/activity/childtype/', {"id":curId, "iType":iType,"col":col}, function (data) {
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