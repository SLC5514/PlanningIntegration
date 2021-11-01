$(function(){
	//点击关闭弹层
	$('.gb').click(function(e) {
		$('.bangding').hide();
		chufa();
	});

	function chufa(){
		window.location.reload();
	};

	//添加子账户
	$('.tianjia').click(function(e) {
		$('.bangding').show()
		$('.bangding #bdzh .xinxi').show();
		$('.bangding #bdzh .suc').hide();
		$('#bdzh').show().siblings('.fenpei,.jiechu,#czmm').hide().siblings('.bangding_bg').show()
	});

	//分配积分
	$('.fp').click(function(e) {
		$('#spafp').click();
		$('.bangding').show();
		$('#count').val($(this).parent().prev().prev().prev().text());
		var count =  $('#count').val();
		$.ajax({
			type: "POST",
			url:  "/member/childInfo/",
			data: "count="+count,
			dataType: 'json',
			async: false,
			success: function(msg){
				if(msg.success == 1){
					$('#nowscore').html(msg.info.iIntegraNumber);
				}else{
					alert('找不到相关信息');
				}
			}
		});
		$('.fenpei').show().siblings('#bdzh,.jiechu,#czmm').hide().siblings('.bangding_bg').show()
	});

	//解除关联
	$('body').on( 'click','.jc',function(e) {
		$('#spajc').click();
		$('#hongcount').html($(this).parent().prev().prev().html());
		$('.bangding').show();
		$('.jiechu').show().siblings('#bdzh,.fenpei,#czmm').hide().siblings('.bangding_bg').show()
	});

	$('.zhifu a').click(function(e) {
		$(this).addClass("kuang").siblings().removeClass("kuang");
	});

	$('.page-wrap p a').click(function(e) {
		$(this).addClass("cur").siblings().removeClass("cur");
	});

	$('.fenpei .btn').click(function(e) {
		$('.fenpei .shouji').find('ul').toggle();

	});
});


