/*
	#author		lut000
	#date 		2017/12/07
    #porpuse    词云
    #edition    1.0
*/
!function(g){
    var wordsCloud=function(opt){
        this.box=opt.box!=undefined?this.getEle(opt.box)[0]:this.getEle(".js-wordcloud-box");

        this.def={
            data:opt.data || [],                                        //数据
            radius:600,                                                 //半径
            min_opacity:0.8,                                            //最低透明度
            max_opacity:1.0,                                            //最高透明度
            pw:0,                                                       //容器宽
            ph:0,                                                       //容器高
            origin_x:0,                                                 //原点坐标x
            origin_y:0,                                                 //原点坐标y
            kx:1.2,                                                     //x轴坐标修正系数
            ky:0.8,                                                     //y轴坐标修正系数
            points:[],                                                  //已经定位的点
        };


        this.init();
    };
    wordsCloud.prototype={

        init:function(){                                                //初始化
            var a=this;
            a.getBaseVal();
            a.setPoints();
        },
        getBaseVal:function(){                                          //初始化数据
            var a=this,d=a.def;
            d.pw=a.box.offsetWidth;
            d.ph=a.box.offsetHeight;
            d.origin_x=d.pw/2;
            d.origin_y=d.ph/2;
        },
        setPoints:function(){                                           //设置点
            var a=this,d=a.def,ndata=d.data,len=ndata.length,radius=d.radius,rotate=Math.PI*2,points=d.points;
            x=0,y=0,nradius=0,
            _x=d.origin_x,_y=d.origin_y,kx=d.kx,ky=d.ky,
            color="255,255,255",
            opacity=1
            ;
            if(len<1){console.log("数据为空！");return false;}
            var o_fragment=document.createDocumentFragment(),oa=null;
            var ntimes=new Date();

            for(var i=0;i<len;i++){
                var txt=ndata[i]["word"] || "";
                var url=ndata[i]["url"] || "";
                var font_size=ndata[i]["frq"] || "";
                var nw=a.textSize({
                    "fontsize":font_size
                },txt);

                /*--循环碰撞测验--*/
                while(true) {
                    nradius=radius*Math.random();
                    x=kx*nradius*Math.cos(rotate*Math.random())+_x;
                    y=ky*nradius*Math.sin(rotate*Math.random())+_y;
                    x=Math.round(x);
                    y=Math.round(y);
                    var isCollision = a.collisionFunc(points,x,y,nw,font_size,i);              
                    if (isCollision==false) break;
                };
                
                opacity=a.setOpacity();
                points.push({
                    "x":x,
                    "y":y,
                    "ow":nw,
                    "oh":font_size,
                    "opacity":opacity
                });

                oa=document.createElement("a");
                oa.title=txt;
                oa.href=url;
                oa.innerText=txt;
                oa.title=txt;
                oa.target="_blank";
                oa.style.cssText="top:"+y+"px;left:"+x+"px;font-size:"+font_size+"px;color:rgba("+color+","+opacity+");color:rgb("+color+")\\0;filter: alpha(opacity="+opacity*100+")";
                o_fragment.appendChild(oa);
            };

            a.box.appendChild(o_fragment);
        },
        setOpacity:function(){                                                     //设置透明度
            var a=this,d=a.def;
            var opacity=Math.random();
            if(opacity<d.min_opacity){opacity=d.min_opacity}
            return opacity;
        },
        collisionFunc:function(data,x,y,w,h){                                      //触碰检测
            var a=this,d=a.def,pw=d.pw,ph=d.ph,result=false;
            var is_front_x=false,is_front_y=false;
            if(x<0 || x+w>pw){
                result=true;
                return result;
            } 
            if(y<0 || y+h>ph){
                result=true;
                return result;
            }
            for(var j=0,len2=data.length;j<len2;j++){
                var prev_x=data[j]["x"];
                var prev_y=data[j]["y"];
                var prev_ow=data[j]["ow"];
                var prev_oh=data[j]["oh"];
                is_front_x=x>=prev_x?true:false;
                is_front_y=y>=prev_y?true:false;
                if(is_front_x==true){
                    if(is_front_y==true){
                        if(prev_x+prev_ow>x){
                            if(prev_y+prev_oh>y){
                                result=true;
                                break;
                            }
                        }
                    }else{
                        if(prev_x+prev_ow>x){
                            if(y+h>prev_y){
                                result=true;
                                break;
                            }
                        }
                    }
                }else{
                    if(is_front_y==true){
                        if(prev_x<x+w){
                            if(prev_y+prev_oh>y){
                                result=true;
                                break;
                            }
                        }
                    }else{
                        if(prev_x<x+w){
                            if(y+h>prev_y){
                                result=true;
                                break;
                            }
                        }
                    }
                }
            };
            return result;
        },
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
        textSize:function(cssList,text) {               // 通过元素获取文字宽高
            var a=this;
            var span = document.createElement("span");
            var result = {};
            result.width = span.offsetWidth;
            result.height = span.offsetWidth; 
            span.style.visibility = "hidden";
            span.style.cssText="line-height:1em;display:inline;padding:0;margin:0;border:none;letter-spacing:0px";
            span.style.fontSize=cssList["fontsize"]!==undefined?cssList["fontsize"]+"px":"14px";
            span.style.lineHeight=cssList["lineheight"]!==undefined?cssList["lineheight"]:"1em";
            document.body.appendChild(span);
            if (typeof span.textContent != "undefined"){span.textContent = text;}else{span.innerText = text;}
            result.width = span.offsetWidth - result.width;
            result.height = span.offsetHeight - result.height;
            span.parentNode.removeChild(span);
            return result.width;
        },
    };
    // 模块化
    if(typeof module !=="undefined" && module.exports){
        module.exports=wordsCloud;
    }else if(typeof define=="function" && define.amd){
        define(function(){
            return wordsCloud;
        });
    }else{
        g.wordsCloud=wordsCloud;
    };
}(window);