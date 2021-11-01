/*
    # purpose tab
    # author  lut000
    # date    2017-10-20
    # edition 1.0
    # terminal pc
    # required jquery

    # date    2017-0a.len-06   top change of margin-top
*/
!function(g){
    function tab(opt){
        if(opt && opt instanceof Object){
            if(opt["container"]!==undefined){
                this.box=opt["container"];         //包含框
                this.ul=this.getEle(".js-page-list",this.box)[0];              //移动对象
                this.prev=this.getEle(".js-prev",this.box)[0];              //前一个
                this.next=this.getEle(".js-next",this.box)[0];              //下一个
                this.progres=this.getEle(".js-progress-bar",this.box)[0];              //进度框
                this.progres_list=this.getEle("ol",this.progres)[0];              //进度
                this.pr_li=null;              //进度li


                this.direction=opt["direction"]!==undefined?opt["direction"]:"horizontal";                    //移动方向
                this.loop=opt["loop"]!==undefined?opt["loop"]:true;                    //是否自动播放
                this.interval=opt["interval"]!==undefined?opt["interval"]:5000;                    //自动播放间隔时间
                this.speed=opt["speed"]!==undefined?opt["speed"]:500;                    //移动速度
                this.repeat=opt["repeat"]!==undefined?opt["repeat"]:true;                    //是否循环滚动
                this.index=opt["index"]!==undefined?opt["index"]:0;                    //当前的进度
                this.width=opt["width"];                    //包含框宽度
                this.height=opt["height"];                    //包含框高度
                this.ow=0;                 //移动距离x
                this.oh=0;                //移动距离y
                this.len=0;                 //轮播长度
                this.timer=null;                //计时器
                // this.is_move
                this.init();
            }else{
                throw "container is undefined"; 
            }
        }else{
            throw "arguments are not Object";
        };
    };
    tab.prototype={             //方法
        init:function(){                //配置
            var a=this;
            var aLi=a.getEle("li",a.ul);
            if(aLi.length<1){
                throw "the dom of ul li is none";
            }else{
                // 配置属性
                if(a.width!==undefined){
                    a.box.style.width=a.width+"px";
                }
                if(a.height!==undefined){
                    a.box.style.height=a.height+"px";
                }
                // 增加dom
                a.len=aLi.length;
                if(a.len<=1){a.repeat=false;a.loop=false;}
                if(a.repeat===true){
                    var node1=aLi[0].cloneNode(true);
                    var node2=aLi[a.len-1].cloneNode(true);
                    a.ul.insertBefore(node2,aLi[0]);
                    a.ul.appendChild(node1);
                }
                // 生成进度
                var len=a.len,oLi,oFrag=document.createDocumentFragment();
                for(var i=0;i<len;i++){
                    var oLi=document.createElement("li");
                    oLi.setAttribute("data-index",i);
                    oFrag.appendChild(oLi);
                }
                a.emptyEle(a.progres_list)
                a.progres_list.appendChild(oFrag);
                a.pr_li=a.getEle("li",a.progres_list);
                if(a.len<=1){a.progres.style.display="none";}
                // 设置样式
                a.reStyle();
                // 开始
                if(a.loop==true){
                    a.setTab(a.index);
                }
                // 绑定事件
                a.bindFun();
            }
        },
        bindFun:function(){                 //绑定事件
            var a=this,n=a.next,p=a.prev;
            n.onclick=function(){
                clearTimeout(a.timer);
                a.toPage(1);
            };
            p.onclick=function(){
                clearTimeout(a.timer);
                a.toPage(2);
            };
            a.progres_list.onclick=function(e){
                var ev=e || window.event;
                var tag=ev.target || ev.srcElement;
                if(tag.tagName.toUpperCase()==="LI"){
                    clearTimeout(a.timer);
                    var n_index=tag.getAttribute("data-index");
                    a.setTab(n_index*1);
                }
                
            }
        },
        toPage:function(type){                 //下一个

            var a=this;
            var index=type==1?a.index+1:a.index-1;
            var nindex=setIndex(index);
            a.setTab(nindex);
            function setIndex(nindex){              //计算进度
                var a=this,is_auto=a.loop;
                if(nindex>a.len){
                    if(is_auto===false){
                        return;
                    }else{
                        nindex=-1;
                    }
                }else if(nindex<-1){
                    if(is_auto===false){
                        return;
                    }else{
                        nindex=-1;
                    }
                }
                return nindex
            }
        },
        setTab:function(index){                 //动画
            var a=this,arg=arguments,b=a.ul,l=a.len,w=a.ow,h=a.oh,d=a.direction,is_auto=a.loop,s=a.speed,intv=a.interval;
            var nindex=index!==undefined?index:0;
            var data={},set_data1={},val=w;            //动画对象
            if(d==="horizontal"){
                val=w;
                data={"margin-left":-(nindex+1)*val+"px"};
                set_data1={"margin-left":-l*val};
                set_data2={"margin-left":-val};
            }else{
                val=h;
                data={"margin-top":-(nindex+1)*val+"px"};
                set_data1={"margin-top":-l*val};
                set_data2={"margin-top":-val};
            }
            // 动画执行
            $(b).stop(true,true).animate(data,s,"linear",function(){
                if(nindex==-1){
                    $(b).css(set_data1);
                    a.index=a.len-1;
                }else if(nindex==a.len){
                    $(b).css(set_data2);
                    a.index=0;
                }else{
                    a.index=nindex;
                }
                if(is_auto===true){
                    clearTimeout(a.timer);
                    a.timer=setTimeout(function(){
                        a.toPage(1);
                    },intv);
                }
            });


            var n=nindex;
            if(n==a.len){
                n=0;
            }else if(n==-1){
                n=a.len-1;
            }
            for(var i=0;i<l;i++){
                if(i==n){
                    a.pr_li[i].className="current";
                }else{
                    a.pr_li[i].className=""; 
                }
            }
        },
        reStyle:function(){             //设置样式
            var a=this;
            var aLi=a.getEle("li",a.ul);
            // 获取基础值

            a.ow=parseFloat(a.getStyle(a.box,"width")) || a.box.offsetWidth;      //此处单为兼容ie做出调整
            a.oh=parseFloat(a.getStyle(a.box,"height")) || a.box.offsetHeight;            // the same to prev
            // 循环赋值
            var len=a.getEle("li",a.ul).length;
            for(var i=0;i<len;i++){
                if(a.direction==="vertical"){
                    aLi[i].style.height=a.oh+"px";
                    aLi[i].style.float="none";
                }else{
                    aLi[i].style.width=a.ow+"px";
                }
                
            }
            if(a.direction==="vertical"){
                if(a.len<=1){
                    a.ul.style.marginTop=0+"px";
                }else{
                    a.ul.style.marginTop=-a.oh+"px";
                }
                
            }else{
                if(a.len<=1){
                    a.ul.style.marginLeft=0+"px";
                    a.ul.style.width=a.ow*a.len+"px";
                }else{
                    a.ul.style.marginLeft=-a.ow+"px";
                    a.ul.style.width=a.ow*len+"px";
                }
            }
            
        },
        getEle:function(str,op){                                         //获取元素
            var opar=document;
            var a=this;
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
        getStyle: function(obj,name){                   // 获取样式
            if(window.getComputedStyle){
                return getComputedStyle(obj,false)[name];
            }else if(obj.currentStyle){
                return obj.currentStyle[name];
            }else{
                return null
            }
        },
        emptyEle:function(obj){                 //清除子节点
            var childrens=obj.childNodes;
            var len=childrens.length;
            if(len>0){
                obj.removeChild(childrens[len-1]);
                arguments.callee(obj);
            }
        }
    };
    // 模块化
    if(typeof module !=="undefined" && module.exports){
        module.exports=tab;
    }else if(typeof define=="function" && define.amd){
        define(function(){
            return tab;
        });
    }else{
        g.slideFun=tab;
    };
}(window);