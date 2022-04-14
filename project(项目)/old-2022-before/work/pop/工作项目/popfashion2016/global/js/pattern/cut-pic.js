!function(g){

	var cutPic=function(opt){
		var options=opt?opt:{};
        // 获取元素
        this.box=opt.box?opt.box:document.querySelector(".js-cut-box");                         //包含框
        this.cut_img=this.box.querySelector(".js-cut-img");                           			//剪裁图片


        this.is_set_model=false;																//是否生成模型


        this.def={
        	out_data:{																			
        		blob_obj:{},																	//二进制对象
        		is_get_blob:false																//是否已经生成blob
        	},

        	imgdefw:0,																			//图片默认宽度
			imgdefh:0,																			//图片默认高度
			imgOriginalW:0,																		//图片原始宽度
			imgOriginalH:0,																		//图片原始高度
			offsetx:75,																			//图片位置位移x
			offsety:-325,																		//图片位置位移y
			divx:400,																			//外框宽度
			divy:400,																			//外框高度
			cutx:250,																			//裁减宽度
			cuty:250,																			//裁减高度
			zoom:1,																				//缩放比例
			zmin:0,																				//最小比例
			zmax:2,																				//最大比例
			grip_pos:5,																			//拖动块位置0-最左 10 最右
			img_grip:null,																		//拖动块
			img_track:null,																		//拖动条
			grip_y:0,																			//拖动块y值
			grip_minx:0,																		//拖动块x最小值
			grip_maxx:0																			//拖动块x最大值
        };
        this.init();
	};


	// 原型
	cutPic.prototype={


		init:function(){																		//初始化
			var a=this;
			a.getDefInfo();
			a.bindFunc();
		},
		getDefInfo:function(){
			var a=this,d=a.def;
			d.divx=parseInt(a.getStyle(a.box,"width")) || 400;
			d.divy=parseInt(a.getStyle(a.box,"height")) || 400;
		},
		changePic:function(){
			a.getDefInfo();
		},
		imageinit:function(){																	//图片初始化
			var a=this,d=a.def,cm=a.cut_img,divx=d.divx,divy=d.divy,zoom=d.zoom,cutx=d.cutx;
			d.imgdefw=cm.width;
			d.imgdefh=cm.height;
			if(d.imgdefw > divx){
				zoom = divx / d.imgdefw;
				cm.width = divx;
				cm.height = Math.round(d.imgdefh * zoom);
			}

			cm.style.left = (Math.round((divx - cm.width) / 2))+'px';
			cm.style.top = (Math.round((divy - cm.height) / 2) - divy)+'px';

			//zmax =  zmin > 0.25 ? 8.0: 4.0 / Math.sqrt(zmin);
			if(d.imgdefw > cutx){
				d.zmin = cutx / d.imgdefw;
				d.grip_pos = 5 * (Math.log(zoom * d.zmax) / Math.log(d.zmax));
			}else{
				d.zmin = 1;
				d.grip_pos = 5;
			}
			d.zoom=zoom;
		},
		
		imageresize:function(flag){																//图片逐步缩放
			var a=this,d=a.def,zoom=d.zoom,cm=a.cut_img,imgdefw=d.imgdefw,imgdefh=d.imgdefh;
			if(flag){
				zoom = zoom * 1.05;
			}else{
				zoom = zoom / 1.05;
			}
			if(zoom <= d.zmin) zoom = d.zmin;
			if(zoom >= d.zmax) zoom = d.zmax;
			cm.width = Math.round(imgdefw * zoom);
			cm.height = Math.round(imgdefh * zoom);

			d.zoom=zoom;

			a.checkcutpos();
			d.grip_pos = 5 * (Math.log(zoom * d.zmax) / Math.log(d.zmax));
			//img_grip.style.left = (grip_minx + (grip_pos / 10 * (grip_maxx - grip_minx))) + "px";
		},

		
		getStylepos:function(e){																//获得style里面定位
			var a=this;  
			return {x:parseInt(a.getStyle(e,"left")), y:parseInt(a.getStyle(e,"top"))}; 
		},
		getPosition:function(e){  																//获得绝对定位
			var t=e.offsetTop;  
			var l=e.offsetLeft;  
			while(e=e.offsetParent){  
				t+=e.offsetTop;  
				l+=e.offsetLeft;  
			}
			return {x:l, y:t}; 
		},

		
		checkcutpos:function(){																	//检查图片位置
			var a=this,d=a.def,cm=a.cut_img,offsetx=d.offsetx,offsety=d.offsety,cutx=d.cutx,cuty=d.cuty;
			var imgpos = a.getStylepos(cm);
			max_x = Math.max(offsetx, offsetx + cutx - cm.clientWidth);
			min_x = Math.min(offsetx + cutx - cm.clientWidth, offsetx);
			if(imgpos.x > max_x) cm.style.left = max_x + 'px';
			else if(imgpos.x < min_x) cm.style.left = min_x + 'px';
			max_y = Math.max(offsety, offsety + cuty - cm.clientHeight);
			min_y = Math.min(offsety + cuty - cm.clientHeight, offsety);
			if(imgpos.y > max_y) cm.style.top = max_y + 'px';
			else if(imgpos.y < min_y) cm.style.top = min_y + 'px';
		},

		bindFunc:function(){
			var a=this;
			Drag.init(a.box,cut_img);
			cut_img.onDrag = function(){
				a.checkcutpos();
			};
			if(document.querySelector){
				document.querySelector(".js-cut-table1").addEventListener("mousewheel",zoomFunc,false);
				document.querySelector(".js-cut-table1").addEventListener("DOMMouseScroll",zoomFunc,false);
			}
			function zoomFunc(e){
				var delta=getWheelValue(e);
				a.imageresize(delta<0?false:true);
				e.preventDefault();
			};
			//取得滚动值 
            function getWheelValue(e){ 
                return ( e.wheelDelta ? e.wheelDelta/120 : -( e.detail%3 == 0 ? e.detail/3 : e.detail ) ) ; 
            };
		},
		getCutData:function(file_type){																	//获得图片裁减位置
			var a=this,d=a.def,cm=a.cut_img,offsetx=d.offsetx,offsety=d.offsety,zoom=d.zoom;
			var imgpos = a.getStylepos(cm);
			var x = Math.abs(offsetx-imgpos.x);															//相对剪裁框x
			var y = Math.abs(imgpos.y-offsety);
			var canvas=document.createElement("canvas");
			var src=cm.src;
			var proxy = '/details/virtualSpl/?act=proxy&proxy_img=';
			var oimg=document.createElement('img');
	        if(isIE()==true){
	            src=proxy+encodeURIComponent(src);
	        }else{
	            oimg.crossOrigin="anonymous";                                                      //跨域解决
	        }
			oimg.onload=function(){
				// canvas切图
				var ow=cm.width,oh=cm.height;															
				var sx=ow>=d.cutx?x/zoom:0,																//要剪裁的图片，相对自己的开始位置x
					sy=oh>=d.cuty?y/zoom:0,																//要剪裁的图片，相对自己的开始位置y
					swidth=ow>=d.cutx?d.cutx/zoom:this.width,											//需要剪裁的宽度
					sheight=oh>=d.cuty?d.cuty/zoom:this.height,											//剪裁的高度
					_x=ow>=d.cutx?0:x,																	//绘制图片的起始位置x
					_y=oh>=d.cuty?0:y,																	//绘制图片的起始位置y
					width=ow>=d.cutx?d.cutx:ow,															//绘制后图片的宽度
					height=oh>=d.cuty?d.cuty:oh;														//绘制后图片的高度

				canvas.width=d.cutx;
				canvas.height=d.cuty;
				var ctx=canvas.getContext("2d");
				ctx.clearRect(0,0,canvas.width,canvas.height);
				ctx.drawImage(oimg,sx,sy,swidth,sheight,_x,_y,width,height);

				// document.body.appendChild(canvas);

				// d.out_data.blob_obj=getCanvasDate(canvas,file_type);
				d.out_data.base64=getCanvasDate(canvas,file_type);
				d.out_data.is_get_blob=true;
			};
			oimg.onerror=function(){
				// console.log("截图下载失败："+src);
			};
			oimg.src=src;																			//正式数据
			// oimg.src="https://www.pop-fashion.com/material/upload_pic_material/2017/09/07/f8217157086709515ed5fe8932b38803.png";
			// oimg.src="https://www.pop-fashion.com/material/upload_pic_material/2017/09/07/fa90e6cb4e1b214d1e86b79a431a671f.png";
			





			//获取canvas formData
	        function getCanvasDate(ncanvas,file_type){
	            var base64_str=ncanvas.toDataURL(file_type,1);
	            // document.write(base64_str);
	            // var blob_obj=dataURLtoBlob(base64_str);
	            // var form_data=new FormData();
	            // form_data.append("file",blob_obj,"pic."+cav.file_type);

	            // return blob_obj;
	            return base64_str;
	        };
	         // 转化为blob
	        function dataURLtoBlob(dataurl) {
	        	
	            var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
	                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
	            while(n--){
	                u8arr[n] = bstr.charCodeAt(n);
	            }
	            return new Blob([u8arr], {type:mime});
	        };

			// return  x + ',' + y + ',' + cm.width + ',' + cm.height;
		},
	    getStyle:function(obj,name){                    // 获取样式----------ie7需要单独处理
            if(obj.currentStyle){
                return obj.currentStyle[name];
            }else{
                return getComputedStyle(obj,false)[name];
            }
        }
	};

	//缩放条拖动时触发
	function grip_Drag(clientX , clientY) {
		var posx = clientX;
		//img_grip.style.top = grip_y + "px";
		if(clientX < grip_minx){
			//img_grip.style.left = grip_minx + "px";
			posx = grip_minx;
		}
		if(clientX > grip_maxx){
			//img_grip.style.left = grip_maxx + "px";
			posx = grip_maxx;
		}
		grip_pos = (posx - grip_minx) * 10 / (grip_maxx - grip_minx);
		zoom = Math.pow(zmax, grip_pos / 5) / zmax;
		if(zoom < zmin) zoom = zmin;
		if(zoom > zmax) zoom = zmax;
		cut_img.width = Math.round(imgdefw * zoom);
		cut_img.height = Math.round(imgdefh * zoom);
		checkcutpos();
	}

	function ajaxCutPicUpload(type) {
		var options = {
			beforeSubmit: function() {
				return true;
			},
			type:'post',
			url:'/picmatch/dealdata/?time='+Math.random(),
			dataType:'json',
			async:false,
			data: {cut_pos: getcutpos()},
			success: function(obj) {
				if(obj.success == 1) {
					//type为1时,找相似图案;type为2时,搜相关面料;
					if(type==2){
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
				oCommon.noPower( '', error.responseText );
				return false;
			}
		};
		$('#cutFileForm').ajaxSubmit(options);
	}

	function referURL(url){
		var isIe=(document.all)?true:false;
		if(isIe) {
			var linka = document.createElement('a');
			linka.href=url;
			document.body.appendChild(linka);
			linka.click();
		} else{
			window.location.href = url;
		}
	};
	// ie判别
    function isIE(){  
        return !!window.ActiveXObject || "ActiveXObject" in window;
    }


	// 模块化
	if(typeof module !=="undefined" && module.exports){
		module.exports=cutPic;
	}else if(typeof define=="function" && define.amd){
		define(function(){
			return cutPic;
		});
	}else{
		g.cutPic=cutPic;
	}

}(window);