/*
	#author		shuzhan
	#date 		2017/11/15
*/
require.config({
    paths:{
    	"ajaxform":["lib/ajaxform/ajaxform"],
        "mousewheel":["lib/jquery.mousewheel"],
        "cropper":["lib/cropper"]
    },
    shim:{
        "cut-pic":{
            deps:["jquery"]
        },
        "mousewheel":{
            deps:["jquery"]
        },
        "cropper":{
            deps:["jquery"]
        }
    }
});
require(["jquery","general","msg","ajaxform","mousewheel","cropper"],function(jquery,general,msg){        
	
	$(function(){
		
		var image_init=false;		//图片还没有
		var image_err = false;		//被切的图片是否出现错误
		var cut_img = $('.js-cut-pic');		
		
		var cut_pic_obj = {
			cut_pos: '',
			tb_id:'',
			photo_url:''
		};
		
		var start_src = Math.floor( (Math.random()*6) ) ;
		var start_arr = [
			'https://imgyt2.pop-fashion.com/fashion/material/upload_pic_material/2017/11/22/351d67ac89d359cead631116c761e632.jpg',
			'https://imgyt2.pop-fashion.com/fashion/material/upload_pic_material/2017/11/22/ba1a47af99ed23c9a5c1330f2bbc3f08.jpg',
			'https://imgyt2.pop-fashion.com/fashion/material/upload_pic_material/2017/11/22/64b430011558b6f41c92c0453a5f4e1e.jpg',
			'https://imgyt2.pop-fashion.com/fashion/material/upload_pic_material/2017/11/22/5417ae7f424189a598f1304a0b13dc80.jpg',
			'https://imgyt2.pop-fashion.com/fashion/material/upload_pic_material/2017/11/22/d65819dda4b88ceb6dd9883b40e98173.jpg',
			'https://imgyt2.pop-fashion.com/fashion/material/upload_pic_material/2017/11/22/4f3dfc02ea9f385b54db7bf78f2fb8ae.jpg'
		]
		start_src = start_arr[start_src];
		
		newImgSet(start_src,1);		//初始写入图片
		
		//图片初始化    
		function imageinit(_src, is_init){
			_src = _src||'';
			$('.js-loading-div,.js-bg-div').hide();
			if(is_init == 1){
				cut_pic_obj.tb_id = '';
				cut_pic_obj.photo_url = start_src;
				
				cut_img.cropper({
					background:false,
					aspectRatio: NaN,
			        viewMode: 1,
			        dragMode: 'move',
			        autoCropArea:0.46,
			        restore: false,
			        guides: false,
			        highlight: false,
			        cropBoxMovable: true,
			        cropBoxResizable: true,
			        minCropBoxWidth:250,
			        minCropBoxHeight:250,
			        built: function () {  
						image_init = true;
				    }
			    });
			}else{
				cut_img.cropper('replace', _src);
				image_init = true;
			}
		}
		
		
		//提交相似图案
		function ajaxCutPicUpload() {
			$('.js-loading-div,.js-bg-div').fadeIn(100);
			var img_w = 0,img_h = 0,x = 0,y = 0,w = 0,h = 0;
	    	var o1 = cut_img.cropper('getCanvasData')||{};
	    	var o2 = cut_img.cropper('getCropBoxData')||{};
	    	img_w = Math.round(o1.width)||540;
	    	img_h = Math.round(o1.height)||540;
	    	x = Math.round(o2.left - o1.left)||0;
	    	y = Math.round(o2.top - o1.top)||0;
	    	w = Math.round(o2.width)||250;
	    	h = Math.round(o2.height)||250;
			
			cut_pic_obj.cut_pos = x +','+ y +','+ img_w +','+ img_h +','+ w +','+ h;
//			console.log(cut_pic_obj.cut_pos);
			
			general.fn.subAjax({
        		'url':'/similarpatterns/docutpic/?'+Math.random(),
        		'data': cut_pic_obj,
        		'ctp':'application/x-www-form-urlencoded',
        		'success':function(data){  
        			$('.js-loading-div,.js-bg-div').fadeOut(100);
        			var _url = data.data.url || '';
					window.location.href = _url;		//跳转搜索列表页
        		},
	            error:function(){
	            	$('.js-loading-div,.js-bg-div').fadeOut(100);
	            	return false;
	            }
        	});
        	
		}
		
		
		//写入图片
		function newImgSet(_src, is_init){
			is_init = is_init||'',
			cut_img.attr({"src":_src});
			imageinit(_src, is_init);
		}
		
		//上传图片
		function picUpload(ele) {
			var options = {
				beforeSubmit: function() {
					return true;
				},
				type:'post',
				url:'/similarpatterns/uploadpic/?'+Math.random(),
				dataType:'json',
				success: function(data) {	
					var _code = data.code||0, _msg = data.msg||'上传错误，请稍后重试！';
					if(_code==1021){
						msg.msg({"txt":'上传文件类型错误！'},2000);
						return false;
					}else if(_code != 0){
						msg.msg({"txt":_msg},2000);
						return false;
					}
					
					var imgPath = data.data.imgPath||'', _url=data.data.url||'', _src=imgPath;
					if(_src.indexOf('pop-fashion.com')==-1){
						var _src = 'https://imgyt2.pop-fashion.com'+imgPath;
					}
					
					if(image_err == true){
						image_err = false;
						cut_img.cropper('enable');
					}
					newImgSet(_src);
					
					var _id = parseFloat(_url.split('-id_')[1])||'';
					var _t = (_url.split('-id_')[0]||'').split('t_')[1]||'';
					var t_id = _t+'_'+_id;
					
					cut_pic_obj.tb_id = t_id;
					cut_pic_obj.photo_url = imgPath;
					
					$('.js-img-upload')[0].reset();
				},
				error: function(error) {
					msg.msg();
					$('.js-img-upload')[0].reset();
				}
			};
			$('.js-img-upload').ajaxSubmit(options);
		}
		
		
		
		//----------------------------事件绑定--------------------------
		
		//图片加载错误的情况
		var load_timer1 = null;
		cut_img.on('error',function(){
			clearTimeout(load_timer1);
			load_timer1 = setTimeout(function(){
				var _src = cut_img.attr('src')||'';
				if(_src != ''){
					$('.js-loading-div,.js-bg-div').hide();
					image_init=false;		//请求新图
					msg.msg({"txt":"图片错误，请重新上传！"},1600);
					image_err = true;
					cut_img.cropper('disable');
				}
			},20);
		});
		
		
		$('#similar-file').on('change',function(){
			image_init=false;  //请求新图
			picUpload(this);
		});
		
		//点击相似图案
		$('.js-similar-to').on('click',function(){
			if(image_init == false){
				msg.msg({"txt":"图片为空！"},1600);
				return;
			}
			ajaxCutPicUpload();
		});
		
		
		
		
		
	})


});