/*
* # date                        2017-10-09
* # author                      lut
* # content                     cut img by pattern
* # edition                     development 1.2
*/
!function(g){
    if(!document.querySelector){
        // 粗略判断浏览器版本
        return;
    };
    var setPattern=function(opt){
        var options=opt?opt:{};
        // 获取元素
        this.box=this.getEle(".js-pattern-box")[0];                                             //包含框
        this.model_div=this.getEle(".pattern-area",this.box)[0];                                //模型div
        this.tool_box=this.getEle(".js-tool-div",this.box)[0];                                  //工具框
        this.line_ele=this.getEle("div",this.tool_box)[0];                                      //缩放标尺
        this.btn=this.getEle("button",this.line_ele)[0];                                        //拖拽按钮
        this.add_btn=this.getEle(".js-add-ratio",this.tool_box)[0];                             //放大
        this.reduce_btn=this.getEle(".js-reduce-ratio",this.tool_box)[0];                       //缩小
        this.download_btn=this.getEle(".js-download-btn")[0];                                   //下载按钮
        this.main_canvas=this.getEle(".js-main-canvas",this.box)[0];                            //主画布
        
        // 预置属性
        
        this.pattern_type=options.pattern_type!=undefined?options.pattern_type:1;               //1=满身，2=局部
        this.color_layer=options.color_layer!=undefined?options.color_layer:"";                 //是否设置颜色层

        this.rotate=0;                                                                          //图案旋转角度

        this.is_upload=false;                                                                   //是否上传

        this.canvas_ele1=document.createElement('canvas');                                      //副画布
        this.canvas_ele2=document.createElement('canvas');                                      //副画布
        this.is_maded=false;                                                                    //是否完成模型
        
        this.def={
            is_error:false,                                                                     //是否发生错误

            box:{
                offset_x:0,
                offset_y:0,
            },

            width:0,                                                                            //图片宽度
            height:0,                                                                           //图片高度
            k:0.1,                                                                              //点击缩放比例
            k2:10,                                                                              //滚轮缩放比例
            ratio:options["ratio"]!=undefined?options["ratio"]:0.4,                               //当前放大比例
            max_ratio:options["max_ratio"]!=undefined?options["max_ratio"]:4,                   //最大放大比例
            min_ratio:options["min_ratio"]!=undefined?options["min_ratio"]:0.06,                 //最小缩放比例
            btn:{                                                                               //拖拽按钮    
                y:0,                                                                            //top
                ow:0                                                                            //宽度
            },

            limit_x_val:options.limit_x_val || 770,                                             //最大宽度
            limit_y_val:options.limit_y_val || 770,                                             //最大高度
            line:0,                                                                             //按钮移动区间
            out_data:{                                                                          //输出数据
                ratio:1,                                                                        //缩放比例
                pos_x:0,                                                                        //相对位移x
                pos_y:0,                                                                        //相对位移y
                quality:1,                                                                      //图片质量
                file_type:"png"                                                                 //文件类型
            },

            model:{                                                                             //模型对象
                is_loaded:false,                                                                //是否已下载
                img:null,                                                                       //图片对象
                src:options.model_img || "",                                                    //src
                nwidth:0,                                                                       //模型当前宽度
                nheight:0                                                                       //模型当前高度
            },
            pattern:{                                                                           //图案对象
                relative_x:0,                                                                   //图案相对于鼠标位置x
                relative_y:0,
                ow:0,                                                                           //图案当前宽度
                oh:0,

                is_loaded:false,
                img:null,
                src:options.pattern_img || "",
                width:0,                                                                        //图案宽度
                height:0                                                                        //图案高度
            }
        };
        this.init();
    };

    // 原型

    setPattern.prototype={
        init:function(){                                                                        //初始化
            var a=this,d=a.def;
            a.bindFunc();
            a.getImgNatural(1);
            a.getImgNatural(2);
            
        },  
        changePattern:function(opt){                                                            //变化图案
            var a=this,d=a.def;
            if(typeof opt ==="object"){
                if(opt.is_upload!=undefined){
                    a.is_upload=opt.is_upload;
                }
                if(opt.pattern_type!=undefined){
                    a.pattern_type=opt.pattern_type;
                }
            }
            d.model.is_loaded=false;
            d.pattern.is_loaded=false;
            a.is_maded=false;
            a.getImgNatural(1);
            a.getImgNatural(2);
            a.bindFunc(2);
        },
        setBaseValue:function(){
            var a=this,d=a.def;
            d.box.offset_x=a.model_div.offsetLeft;
            d.box.offset_y=90;                                                                  //操作区域距浏览器顶部高度
        },
        setPosFunc:function(){                                                                  //初始化值
            var a=this,d=a.def,p=a.pattern_area,db=d.btn,main_canvas=a.main_canvas;
            db.ow=parseInt(a.getStyle(a.btn,"width"));
            d.line=parseInt(a.getStyle(a.line_ele,"height"))-db.ow;
            var _x=0,_y=0;
            if(a.pattern_type==2){
                _x=(d.model.nwidth-d.pattern.width*(d.model.nwidth/770*0.4))/2;     //缩小展示图案
                _y=(d.model.nheight-d.pattern.height*(d.model.nwidth/770*0.4))/2;
            };
            d.out_data.pos_x=Math.abs(_x);
            d.out_data.pos_y=Math.abs(_y);
            // a.color_layer="";
        },
        getImgNatural:function(type){                                                       //获取图片原始属性
            var a=this,d=a.def;
            var o_img=new Image();
            var proxy = '/virtualtryon/proxy/?proxy_img=';
            var src=type==1?d.pattern.src:d.model.src;
            if(a.isIE()==true){
                src=proxy+encodeURIComponent(src);
            }else{
                o_img.crossOrigin="anonymous";                                                      //跨域解决
            }
            
            
            var result_obj={w:0,h:0};
            if(src!=""){
                o_img.onload=function(){
                    result_obj.w=this.width;
                    result_obj.h=this.height;
                    if(type && type==1){

                        d.pattern.is_loaded=true;
                        d.pattern.img=o_img;
                        d.pattern.width=result_obj.w;
                        d.pattern.height=result_obj.h;

                    }else if(type && type==2){
                        var ow=this.width;
                        var oh=this.height;
                        var _ow=this.width;
                        var _oh=this.height;
                        //设置最小值
                        if (_ow > oh) {
                            var minR = _ow / 770 * 0.06;
                            minR = minR > 0.06 ? minR : 0.06;
                            d.min_ratio = minR;
                        } else {
                            var minR = _oh / 770 * 0.06;
                            minR = minR > 0.06 ? minR : 0.06;
                            d.min_ratio = minR;
                        }
                        //限制宽高
                        if(this.width<this.height){
                            if(this.height>d.limit_y_val){
                                ow=d.limit_y_val*this.width/this.height;
                                oh=d.limit_y_val;
                            }
                        }else{
                            if(this.width>d.limit_x_val){
                                ow=d.limit_x_val;
                                oh=d.limit_x_val*this.height/this.width;
                            }
                        }
//                         if(this.width>=2100){
//                             ow=2100;
//                             oh=this.height*(2100/this.width);
//                         }
                        //设置属性值
                        d.model.is_loaded=true;
                        d.model.img=o_img;
                        d.model.nwidth=_ow;
                        d.model.nheight=_oh;
                        a.main_canvas.style.width=ow+"px";
                        a.main_canvas.style.height=oh+"px";

                    }
                    
                };
                o_img.onerror=function(){
                    console.log("当前src="+o_img.src);
                    console.log("模拟成品图片加载失败");
                    a.clearCtxFunc();
                };
                o_img.src=src;                                                //跨域处理
                // o_img.src=src;
            }
            return result_obj;
        },
        bindFunc:function(ntype){           //事件绑定
            var a=this;
            var b=a.btn,d=a.def,db=d.btn,is_start_ratio=false,ry=0;
            var timer=null,ntimes=0;
            a.setBaseValue();
            setStartSty();
            if(ntype==2){return};
            
            // 下载图片
            a.addEvent(a.download_btn,"click",function(ev){
                if(d.model.is_loaded==false && d.pattern.is_loaded==false){
                    alert("图片加载失败，无法下载。");
                    return false;
                }
                a.setPic();
                a.stopBubble(ev);
            });


            /*---------------拖拽按钮缩放--------------*/
            a.addEvent(document.body,"mouseleave",reSetStateFunc);
            a.addEvent(b,"mousedown",startDragBtn);
            a.addEvent(b,"mousemove",moveFunc);
            a.addEvent(document.body,"mousemove",moveFunc);
            a.addEvent(b,"mouseup",endDragBtn);
            a.addEvent(document.body,"mouseup",endDragBtn);


            a.addEvent(a.add_btn,"click",function(){
                var y=db.y;
                y-=d.k*d.line;
                if(y<0){
                    y=0;
                }
                db.y=y;
                setZoomFunc();
            });
            a.addEvent(a.reduce_btn,"click",function(){
                var y=db.y;
                y+=d.k*d.line;
                
                if(y>d.line){
                    y=d.line;
                }
                db.y=y;
                setZoomFunc();
            });

            // 开始拖拽按钮
            function startDragBtn(ev){
                var e=ev || window.event;
                ry=e.clientY-b.offsetTop;
                is_start_ratio=true;
            };
            // 移动按钮
            function moveFunc(ev){
                if(is_start_ratio==true){
                    var e=ev || window.event;
                    var y=e.clientY,_y=y-ry;
                    if(_y<0){

                        _y=0;
                    }else if(_y>d.line){
                        _y=d.line;
                    }
                    db.y=_y;
                    setZoomFunc();
                }
            };
            // 结束拖拽按钮
            function endDragBtn(ev){
                is_start_ratio=false;
            };

            // 设置css属性
            function setZoomFunc(relative_x,relative_y,prev_ratio){
                var _y=db.y,y2=d.line-_y;
                if(typeof _y ==="number"){
                    var ratio1=_y/d.line;
                    var ratio2=y2/d.line;
                    var ratio3=y2/d.line;
                    if(ratio2<0.5){
                        ratio2=0.4*ratio2/0.5;
                    }else if(ratio2>0.5){
                        ratio2=d.max_ratio*ratio2-1.6;
                    }else{
                        ratio2=0.4;
                    }
                    //按图片大小适配显示大小
                    ratio2=d.model.nwidth/770*ratio2;
                    
                    ratio2=ratio2<d.min_ratio?d.min_ratio:ratio2;
                    d.ratio=ratio2.toFixed(3)-0;
                    b.style.top=_y+"px";

                    if(relative_x!=undefined){
                        d.out_data.pos_x=relative_x-(relative_x-d.out_data.pos_x)/prev_ratio*d.ratio;
                        d.out_data.pos_y=relative_y-(relative_y-d.out_data.pos_y)/prev_ratio*d.ratio;
                    }
                }
                a.drawCanvas();
            };

            /*---------------拖拽图片移动--------------*/
            var m=a.main_canvas,is_drag_start=false,drag_def={
                x:0,
                y:0,
                client_x:0,
                client_y:0
            };
            // a.addEvent(m,"click",checkColorFunc);
            a.addEvent(m,"mousedown",startDrag);
            a.addEvent(m,"mousemove",moveDragFunc);
            a.addEvent(document.body,"mousemove",moveDragFunc);
            a.addEvent(m,"mouseup",endDrag);
            a.addEvent(document.body,"mouseup",endDrag);


            // 开始拖拽
            function startDrag(ev){
                var e=ev || window.event;
                is_drag_start=true;
                drag_def.client_x=ev.clientX;
                drag_def.client_y=ev.clientY;
                drag_def.x=d.out_data.pos_x;
                drag_def.y=d.out_data.pos_y;

            };
            // 移动
            function moveDragFunc(ev){
                if(is_drag_start==true){
                    var e=ev || window.event;
                    var x=e.clientX,y=e.clientY,
                    _x=x-drag_def.client_x+drag_def.x,
                    _y=y-drag_def.client_y+drag_def.y;

                    d.out_data.pos_x=_x;
                    d.out_data.pos_y=_y;
                    a.drawCanvas();
                }
            };
            // 拖拽结束
            function endDrag(ev){
                is_drag_start=false;
            };

            //检测颜色
            function checkColorFunc(ev){
                var e=ev || window.event;
                var x=e.offsetX<0?0:e.offsetX;
                var y=e.offsetY<0?0:e.offsetY;
                a.getCanvasColorData(x,y);
            };

            /*滚轮放大*/
            a.addEvent(m,"mousewheel",scrollZoom);
            a.addEvent(m,"DOMMouseScroll",scrollZoom);



            function scrollZoom(ev){
                var e=ev || window.event;
                var wheel_delta=getWheelValue(e);
                var k=wheel_delta<0?d.k2:-d.k2;
                var _y=db.y;
                _y+=k;
                if(_y<0){
                    _y=0;
                }else if(_y>d.line){
                    _y=d.line;
                }
                db.y=_y;

                var relative_x=e.clientX-d.box.offset_x;
                var relative_y=e.clientY-d.box.offset_y;

                setZoomFunc(relative_x,relative_y,d.ratio);
                a.stopDefault(ev);
                a.stopBubble(ev);
            };
            //取得滚动值 
            function getWheelValue(e){ 
                return ( e.wheelDelta ? e.wheelDelta/120 : -( e.detail%3 == 0 ? e.detail/3 : e.detail ) ) ; 
            };
            function reSetStateFunc(){
                is_drag_start=false;
                is_start_ratio=false;
            };



            function setStartSty(){     //初始化位置
                clearTimeout(timer);
                if(ntimes<25){
                    ntimes++;
                    if(d.pattern.is_loaded==true && d.model.is_loaded==true){
                        a.setPosFunc();
                        db.y=0.5*d.line;
                        setZoomFunc();
                        a.is_maded=true;
                    }else{
                        timer=setTimeout(setStartSty,200);
                    }
                }else{
                    if(d.model.is_loaded==true){
                        a.drawCanvas();
                    }
                    d.is_error=true;
                }
                
            };
            function throttle(method,context,arr){          //函数节流
                clearTimeout(method.tid);
                method.tid=setTimeout(function(){
                    method.apply(context,arr);
                });
            };
        },

        /**
         * 模拟成品下载
         * @param pattern_img 背景图
         * @param mask_img 前景图
         * @param bx 背景图x坐标
         * @param by 背景图y坐标
         * @param ratio 背景图缩放比（显示大小 : 原始大小）
         * @param width 画布大小
         * @param height 画布大小
        */
        drawCanvas:function(){                                      //画图
            // 主画布，用于合成最终图片
            var a=this,
                d=a.def,
                width=d.model.nwidth,
                height=d.model.nheight,
                ratio=d.ratio,
                bx=d.out_data.pos_x,
                by=d.out_data.pos_y,
                mask_img=d.model.img,
                pattern_img=d.pattern.img;

            var canvas = a.main_canvas;
            canvas.width = width;
            canvas.height = height;
//             canvas.style.width = '430px';  //固定展示canvas宽为430，高为770
//             canvas.style.height = '770px';  
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0,0,width,height);
            
            //附加层
            var re=/^#[0-9,a-f]{3,6}$/ig;
            if(a.color_layer!="" && re.test(a.color_layer)){
                var ncanvas=a.canvas_ele2;
                ncanvas.width = width;
                ncanvas.height = height;
                var nctx=ncanvas.getContext("2d");
                nctx.clearRect(0,0,width,height);
                nctx.fillStyle=a.color_layer;
                nctx.fillRect(0,0,width,height);
                ctx.drawImage(ncanvas,0,0,width,height);
            }
            
            
            // 单张背景图缩放后的尺寸
            var bgw = d.pattern.width * ratio;
            var bgh = d.pattern.height * ratio;
            // 背景图画布，用于拼接指定个数的背景图为一张整图
            var canvas_bg = a.canvas_ele1;
            var ctx_bg = canvas_bg.getContext('2d');
            if(d.pattern.is_loaded==true){
                if(a.pattern_type==1){
                    // 左侧和上侧不足一个背景图的宽度和高度
                    var mw = bx % bgw;
                    var mh = by % bgh;
                    // 最终绘制背景图的起点坐标，非正数
                    var sx = mw > 0 ? mw - bgw : mw;
                    var sy = mh > 0 ? mh - bgh : mh;
                    // 需要的背景画布大小（从绘制起点开始到主画布右下角结束）
                    var bgw_total = a.rotate!=0?3*width + Math.abs(sx):width+Math.abs(sx);
                    var bgh_total = a.rotate!=0?3*height + Math.abs(sy):height+Math.abs(sy);

                    
                    // 换算使用原始大小背景图所需的背景图画布大小
                    canvas_bg.width = bgw_total / ratio;
                    canvas_bg.height = bgh_total / ratio;
                    
                    ctx_bg.clearRect(0,0,canvas_bg.width,canvas_bg.height);
                    ctx_bg.fillStyle = ctx_bg.createPattern(pattern_img, 'repeat');
                    // 使用原始大小的背景图平铺背景图画布
                    ctx_bg.fillRect(0,0,canvas_bg.width,canvas_bg.height);
                    // 将背景图画布内容缩放到所需比例并绘制到主画布

                    if(a.rotate!=0){
                        rotateFn(canvas_bg, Math.floor(sx)-width,Math.floor(sy)-height, Math.floor(bgw_total), Math.floor(bgh_total),a.rotate);
                    }else{
                        ctx.drawImage(canvas_bg, Math.floor(sx),Math.floor(sy), Math.floor(bgw_total), Math.floor(bgh_total));
                    }
                }else if(a.pattern_type==2){
                    //局部
                    
                    if(a.rotate!=0){
                        rotateFn(pattern_img,Math.floor(bx),Math.floor(by),Math.floor(bgw),Math.floor(bgh),a.rotate);
                    }else{
                        ctx.drawImage(pattern_img,Math.floor(bx),Math.floor(by),Math.floor(bgw),Math.floor(bgh));
                    }
                }
            }
            
            // 再将前景图绘制到主画布，图片合成结束
            if(d.model.is_loaded==true){
                ctx.drawImage(mask_img,0,0,width,height);
            }


            // 按图片中心旋转
            function rotateFn(img,x,y,w,h,deg){
                var _x = x+w/2;                 
                var _y = y+h/2;         
                ctx.save();       
                ctx.translate(_x,_y);               //将绘图原点移到画布中点
                ctx.rotate((Math.PI/180)*deg);      //旋转角度
                ctx.translate(-_x,-_y);             //将画布原点移动
                ctx.drawImage(img,x,y,w,h);             //绘制图片 
                ctx.restore();    
            };
        },
        clearCtxFunc:function(){                                        //清楚画布
            var a=this;
            var canvas = a.main_canvas;
            var ctx = canvas.getContext('2d');
            ctx.clearRect(0,0,canvas.width,canvas.height);
        },
        setPic:function(){                                              //生成图片
            var a=this,d=a.def,canvas=a.main_canvas;
            // 图片输出为png格式
            var type=d.out_data.file_type,
                quality=d.out_data.quality;
            var base64_str = canvas.toDataURL(type);
            // 文件名称
            var filename='popCloud-'+(new Date()).getTime()+'.'+type;
            //return;
            a.saveFile(base64_str,filename);
        },
        /**
         * base64图片过大，转换成blod类型下载
         * @param  {String} code base64数据
         */
        base64ToBlob:function (code) {
            let parts = code.split(';base64,');
            let contentType = parts[0].split(':')[1];
            let raw = window.atob(parts[1]);
            let rawLength = raw.length;
            let uInt8Array = new Uint8Array(rawLength);
            for (let i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }
            return new Blob([uInt8Array], {type: contentType});
        },
        /**
         * 在本地进行文件保存
         * @param  {String} data     要保存到本地的图片数据
         * @param  {String} filename 文件名
         */
        saveFile:function (data,filename){                              //下载
            var a=this;
            if(a.isIE()==true) { // 是ie浏览器
                var canvas=a.main_canvas;
                if(canvas.msToBlob){
                    // 支持msToBlob
                    // console.log("msToBlob下载");
                    var blob = canvas.msToBlob(data);
                    window.navigator.msSaveBlob(blob,filename);
                }else{
                    // 不支持msToBlob
                    // console.log('服务器下载');
                    var form=document.createElement("form");
                    form.setAttribute("method","post");
                    form.setAttribute("target","");
                    form.setAttribute("action","/api/virtualsplsimulatedown/");
                    var input1=document.createElement("input");
                    input1.setAttribute("name","imgbody");
                    input1.value=data;
                    form.appendChild(input1);
                    document.body.appendChild(form);
                    form.submit();//表单提交
                }
            }else{
                var save_link = document.createElementNS('http://www.w3.org/1999/xhtml', 'a');
                var blob = a.base64ToBlob(data);
                save_link.href = URL.createObjectURL(blob);
                save_link.download = filename;

                var event = document.createEvent('MouseEvents');
                event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                save_link.dispatchEvent(event);
            }
        },
        getCanvasColorData:function(x,y){
            var a=this,canvas=a.main_canvas,ctx=canvas.getContext("2d");
            var imgData=ctx.getImageData(x,y,1,1);
            var r=nimg_data.data[0];
            var g=nimg_data.data[1];
            var b=nimg_data.data[2];
            var a=nimg_data.data[3];

            var ff=toFF(r)+toFF(g)+toFF(b);

            function toFF(num){
                return num>16?num.toString(16).toUpperCase():"0"+num.toString(16).toUpperCase();
            };
        },

        /*---------------绑定事件-----------------*/
        addEvent: function(obj,type,func){                  // 事件绑定
            if(obj.addEventListener){
                obj.addEventListener(type,func,false);
            }else if(obj.attachEvent){
                // ie
                obj.attachEvent("on"+type,func);
            }else{
                obj.on[type]=func;
            }
        },
        removeEvent: function(obj,type,func){                   // 取消事件绑定       
            if(obj.removeEventListener){
                obj.removeEventListener(type,func,false);
            }
            else if(obj.detachEvent){
                obj.detachEvent("on"+type,func);
            }else{
                obj.on[type]=null;
            }
        },
        stopBubble: function(ev){                   // 阻止事件冒泡
            var e=ev || window.event;
            if(e && e.stopPropagation){
                e.stopPropagation();
            }else{
                window.event.cancelBubble=true;
            }
            return false;
        },
        stopDefault: function(ev){                  // 阻止浏览器默认事件
            var e=ev || window.event;
            if(e && e.preventDefault){
                e.preventDefault();
            }else{
                window.event.returnValue=false;
            }
            return false;
        },
        getStyle:function(obj,name){                    // 获取样式----------ie7需要单独处理
            if(obj.currentStyle){
                return obj.currentStyle[name];
            }else{
                return getComputedStyle(obj,false)[name];
            }
        },
        getEle:function(str,op){                                            //获取元素
            var a=this;
            var opar=document;
            if(arguments.length>1 && typeof op =="object"){
                opar=op;
            }
            if(str && typeof str =="string"){
                var re1=/^\..+/;
                var re2=/^#.+/;
                if(re1.test(str)){
                    if(document.querySelectorAll){

                        return opar.querySelectorAll(str);
                    }else{
                        return a.getByClass(opar,str.slice(1));
                    }
                }else if(re2.test(str)){
                    if(document.querySelector){
                        return opar.querySelector(str);
                    }else{
                        return opar.getElementById(str.slice(1));
                    }
                }else{
                    if(document.querySelectorAll){
                        return opar.querySelectorAll(str);
                    }else{
                        return opar.getElementsByTagName(str);
                    }
                }
            }else{
                return null;
            }
        },
        getByClass:function(oParent,sClass){                    // 获取class
            var aResult=[];
            if(arguments.length!=0 && arguments.length>1){
                var opar=null;
                if(typeof oParent =="object" && oParent.nodeName!="undefined"){
                    opar=oParent;
                }else{
                    opar=document;
                }
                var aEle=opar.getElementsByTagName("*");
                if(document.querySelectorAll){
                    aResult=opar.querySelectorAll("."+sClass);
                }else{
                    for(var i=0,tt=aEle.length;i<tt;i++){
                        if(aEle[i].className.indexOf(sClass)!=-1){
                            var arr_class=aEle[i].className.split(" ");
                            for(var j=0,len=arr_class.length;j<len;j++){
                                if(arr_class[j]==sClass){
                                    aResult.push(aEle[i]);
                                }
                            }
                        }
                    }
                }
            }
            return aResult;
        },
        isIE:function(){
            return !!window.ActiveXObject || "ActiveXObject" in window;
        }
    };
    g.setPattern=setPattern;
}(window);