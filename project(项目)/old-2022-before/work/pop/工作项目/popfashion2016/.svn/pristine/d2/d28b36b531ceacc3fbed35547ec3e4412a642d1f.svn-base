/**
 * 商拍页面
 * Created by Administrator on 2016/7/21.
 */

$(".business_content ul li input[type='text']")
.focus(function(){
    if ($(this).val() == this.defaultValue) {
        $(this).val('');
    }
})
.blur(function(){
    if ($(this).val() == '') {
        $(this).val(this.defaultValue)
    } else {
        $(this).css('color', 'black');
    }
});

var $businessArea    = $("input[name='businessArea']");
var $brandName       = $("input[name='brandName']");
var $telephone       = $("input[name='telephone']");
var $category        = $(".check_box input:radio[name='category']");
var uid             = $.cookie('userinfo_id');
var uname           = $.cookie("_MemberName");

$(".business_photo").on("click", ".sub_button", function(){
    var regMobile = /^1\d{10}$/; //手机号码
    var regTel=/^(\d{3,4}\-)?[1-9]\d{6,7}$/; //固定电话

    if(uid == null || uname == null){
        layer.alert("对不起，您还没登入，不能提交！");
        return false;
    }
    if ($businessArea.val() == $businessArea.get(0).defaultValue) {
        layer.alert("请填写商拍地区");
        return false;
    }
    if ($brandName.val() == $brandName.get(0).defaultValue) {
        layer.alert("请填写名牌名称");
        return false;
    }
    if ($telephone.val() == $telephone.get(0).defaultValue || !(regMobile.test($telephone.val()) || regTel.test($telephone.val()))) {
        layer.alert("请正确填写联系电话");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/styles/addbusiness/",
        data: {"businessArea":$businessArea.val(), "brandName":$brandName.val(), "telephone":$telephone.val(), "category":$category.filter(":checked").val()},
        success: function(msg){
            layer.alert(msg);
        }
    });
});

