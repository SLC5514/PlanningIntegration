
/*
	#author		shuzhan
	#date 		2017/12/27
*/

// ie判别
if (!!window.ActiveXObject || "ActiveXObject" in window){
	var ua = navigator.userAgent.toLowerCase();
	var ie_version =  ua.match(/msie ([\d.]+)/)[1];
    if(ie_version <= 8 ){
        window.location.href = '/software';
    }
}

$(function(){
	
	var image_init=false;		//图片还没有
	var cut_img = $('.js-cut-pic');	
	var cut_model=null;
	
	var cut_pos = '';		//切图参数
	
	if($('.no-picture-search').length<=0){
		imageinit();		//初始写入图片
	}
	
	//获取t和id
	var td_val = $('.js-tid-val').val()||'';
	td_val = td_val.split('_')||[];
    var picmatch_t = td_val[0]||'';
    var picmatch_id = td_val[1]||'';
    
    
    //图片初始化    
	function imageinit(){
		cut_model=new Cropper(cut_img[0],{
            background:false,
            checkImageOrigin:true,
            aspectRatio: NaN,
            viewMode: 1,
            checkCrossOrigin:true,
            dragMode: 'move',
            autoCropArea:0.3,
            restore: false,
            guides: false,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            minCropBoxWidth:250,
            minCropBoxHeight:250
        });
        
        image_init = true;
	}
	
	function ajaxCutPicUpload(type) {
		if(image_init == false){
			return;
		}
	
		var img_w = 0,img_h = 0,x = 0,y = 0,w = 0,h = 0;
		var o1 = cut_model.getCanvasData()||{};
		var o2 = cut_model.getCropBoxData()||{};
		img_w = Math.round(o1.width)||714;
		img_h = Math.round(o1.height)||557;
		x = Math.round(o2.left - o1.left)||0;
		y = Math.round(o2.top - o1.top)||0;
		w = Math.round(o2.width)||250;
		h = Math.round(o2.height)||250;
		
		cut_pos = x +','+ y +','+ img_w +','+ img_h +','+ w +','+ h;
		
		var options = {
			beforeSubmit: function() {
				return true;
			},
			type:'post',
			url:'/picmatch/dealdata/?time='+Math.random(),
			dataType:'json',
			async:false,
			data: {
				cut_pos: cut_pos
			},
			success: function(obj) {
				if(obj.success == 1) {
					//type为1时,找相似图案;type为2时,搜相关面料;
					if(type==3){
						window.open( '/coloranalysis/?path='+obj.imgPath );
					}else if(type==2){
						window.open( 'http://www.uliaobao.com/s/search/toSerchImage?imageUrl='+obj.imgPath+'&cutImg=0' );
					}else{
						window.open( obj.path );
					}
					return true;
				} else {
					oCommon.noPower( '', '上传失败' );
					return false;
				}
			},
			error: function(error) {
				oCommon.noPower( '', '上传失败' );
				return false;
			}
		};
		$('#cutFileForm').ajaxSubmit(options);
	}
	
	
	
	//-------------------------------------------------
	
	//找相似图案
	$('.js-find-a').click(function(){
		ajaxCutPicUpload(1);
	});
	
	
	//搜相关面料
	$('.js-search-a').click(function(){
		ajaxCutPicUpload(2);
	});
	
	
	//匹配搭色款式
	$('.js-analysis-a').click(function(){
		ajaxCutPicUpload(3);
	});
	
	
	
	
});









