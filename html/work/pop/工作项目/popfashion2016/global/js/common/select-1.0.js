// 自执行
!function(g){
    // 获取元素  支持ie8+

    var method={
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

        getNextEle:function(obj){
            var a=this;
            var next_ele=obj.nextSibling;
            if(next_ele==undefined){
                return undefined;
            }else if(next_ele.nodeType===1){
                return next_ele
            }else{
                return a.getNextEle(obj.nextSibling);
            }
        }
    }



    var select_ele=method.getEle(".js-select-div");

    // 绑定事件
    for(var i=0,len=select_ele.length;i<len;i++){
        method.addEvent(select_ele[i],"click",function(e){
            var ev=e || window.event;
            var target=ev.target || ev.srcElement;
            var node_name=target.nodeName.toLowerCase();
            if(node_name==="span"){
                var oul=method.getNextEle(target);
                if(!target.is_show){
                    display_str="block";
                    target.is_show=true;
                }else{
                    display_str="none";
                    target.is_show=false;
                }
                oul.style.display=display_str;
            }else if(node_name==="li"){
                var ospn=method.getEle("span",target.parentNode.parentNode)[0];
                var txt=target.innerText,id=target.getAttribute("value") || "";
                ospn.innerText=txt;
                ospn.setAttribute("value",id);
                target.parentNode.style.display="none";
                ospn.is_show=false;
                ospn.className="select-spn now-choice";
                method.getNextEle(target.parentNode.parentNode).style.display="none";
            }

            method.stopBubble(e);
        });
    };
    method.addEvent(document.body,"click",function(e){
        for(var i=0,len=select_ele.length;i<len;i++){
            var oul=method.getEle("ul",select_ele[i])[0];
            oul.style.display="none";
            method.getEle("span",select_ele[i])[0].is_show=false;
        }
    });
}(window);