/**
 * Created by limeng on 2016/3/4.
 */
var validator = {
	//个人中心--子账号修改信息--提交验证
	'validateChild' : function(){
		var gender = $("#sGender").val();
		var email = $("#sEmail").val();
		var company = $("#sCompanyName").val();
		var positon = $("#iPositionsID").val();
		var name = $("#sName").val();

		if (gender == "") {
			layer.alert("请选择性别");
			return false;
		}
		if (email == "" || !RegExp("^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$", "ig").test(email)) {
			layer.alert("请正确填写email");
			return false;
		}
		if (company == "") {
			layer.alert("请填写公司名称");
			return false;
		}
		if (positon == "") {
			layer.alert("请选择您的职位");
			return false;
		}
		if (name == "") {
			layer.alert("请正确填写您的姓名");
			return false;
		}
	},

	//个人中心--主账号修改信息--检查是否选择领域
	'checkBoxs' : function(){
		var count = 0;
		$("#domain :checkbox").each(function(){
			if ($(this).prop("checked") == true){
				count++;
			}
		});
		return count;
	},

	//个人中心--主账号修改信息--提交验证
	'validateMain' : function(){
		var country     = $("#country").val();
		var province    = $("#province").val();
		var city        = $("#city").val();
		var tel         = $("#telp").val();
		var mobile       = $("input[name=mobile]").val();
		var address     = $("#address").val();
		var comType     = $("#com_type").val();
		var domain      = this.checkBoxs();

		if (country == "" || province == "" || city == "") {
			layer.alert("请选择所属地区");
			return false;
		}
		if (tel == "" || !RegExp("^(0\\d{2,3}-\\d{7,8})$").test(tel)) {
			layer.alert("请正确填写固定电话");
			return false;
		}
		if (mobile != "" && !RegExp("^\\d{11}$").test(mobile)) {
			layer.alert("请正确填写移动电话");
			return false;
		}
		if ($.trim(address) == "") {
			layer.alert("请正确填写您的联系地址");
			return false;
		}
		if (comType == "") {
			layer.alert("请选择您的职业");
			return false;
		}
		if (domain == 0) {
			layer.alert("请选择您所涉及的领域");
			return false;
		}
	}
};
