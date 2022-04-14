/*
    # purpose tab
    # author  lut000
    # date    2017-12-12
    # edition 1.0
    # terminal pc
    # required jquery
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


                this.origin_items=this.getEle("li",this.ul);
                this.items=null;
                this.loop=opt["loop"]!==undefined?opt["loop"]:true;                    //是否自动播放
                this.interval=opt["interval"]!==undefined?opt["interval"]:5000;                    //自动播放间隔时间
                this.speed=opt["speed"]!==undefined?opt["speed"]:1000;                    //移动速度
                this.repeat=opt["repeat"]!==undefined?opt["repeat"]:true;                    //是否循环滚动
                this.index=opt["index"]!==undefined?opt["index"]:0;                    //当前的进度
                this.width=opt["width"];                    //包含框宽度
                this.height=opt["height"];                    //包含框高度
                this.ow=0;                 //移动距离x
                this.oh=0;                //移动距离y
                this.len=this.origin_items.length;                 //轮播长度
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
            if(a.len<1){
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
                if(a.len<=1){a.repeat=false;a.loop=false;}
                a.items=a.getEle("li",a.ul);
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
                if(len<=1){a.progres.style.display="none";}
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
            var len=a.len;
            var nindex=setIndex(index);
            a.setTab(nindex);
            function setIndex(index){              //计算进度
                if(index>=len){
                    return 0;
                }else if(index<=-1){
                    return len-1;
                }else{
                    return index;
                }
            }
        },
        setTab:function(index){                 //动画
            var a=this,s=a.speed,intv=a.interval,l=a.len,is_auto=a.loop;
            var nindex=index!==undefined?index:0;



            var ali=a.items;
            $(ali).eq(nindex).fadeIn(s,function(){
                a.index=nindex;
                if(is_auto===true){
                    clearTimeout(a.timer);
                    a.timer=setTimeout(function(){
                        a.toPage(1);
                    },intv);
                }
            }).siblings("li").fadeOut(s-200);

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
            var aLi=a.items;
            // 获取基础值

            a.ow=parseFloat(a.getStyle(a.box,"width")) || a.box.offsetWidth;      //此处单为兼容ie做出调整
            a.oh=parseFloat(a.getStyle(a.box,"height")) || a.box.offsetHeight;            // the same to prev
            // 循环赋值
            var len=aLi.length;

            for(var i=0;i<len;i++){
                aLi[i].style.cssText="width:"+a.ow+"px;height:"+a.oh+"px;z-index:"+(len-i)+";position:absolute;left:0px;top:0px;float:none";
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