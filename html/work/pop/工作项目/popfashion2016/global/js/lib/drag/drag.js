!function(g){
    function setDrag(options){
        this.box=document.querySelector(options.box || '.js-drag-box');
        this.btn=this.box.querySelector('.js-btn');
        this.bg=this.box.querySelector('.js-bg');

        this.data={
            positions:[],
            hash:'',
        };
        this.success=options.success;
        this.error=options.error;

        this.ekeys={
            pc:['mousedown','mousemove','mouseup'],
            m:['touchstart','touchmove','touchend']
        };
        this.platform_type=isPc()==true?'pc':'m';

        // this.init();
    };
    function isPc(){
        return !/(iPhone|iPad|iPod|iOS|Android)/i.test(navigator.userAgent);
    }

    setDrag.prototype={
        init:function(type){

            this.bindFunc();
        },
        bindFunc:function(){
            var a=this;
            var is_down=false,rx=0,ry=0,nx=0,ny=0,limit_x=0,ow=0,times=0,start_times=0,end_times=0,positions=[];
            getBaseInfo();
            setStyle(0);
            addEvent(a.btn,a.ekeys[a.platform_type][0],downFn);
            addEvent(document,a.ekeys[a.platform_type][1],moveFn);
            addEvent(document,a.ekeys[a.platform_type][2],upFn);


            function downFn(ev){
                var e=a.platform_type=='pc'?ev:ev.targetTouches[0];
                is_down=true;
                start_times=(new Date()).getTime();
                rx=e.clientX-a.btn.offsetLeft;
                ry=e.clientY-a.btn.offsetTop;

                ev.preventDefault();
            };

            function moveFn(ev){
                var e=a.platform_type=='pc'?ev:ev.targetTouches[0];
                if(is_down==true){
                    nx=e.clientX-rx;
                    ny=e.clientY-ry;

                    setStyle(nx);

                    positions.x=nx;
                    positions.y=ny;
                    positions.push({'x':nx,'y':ny});

                }

                ev.preventDefault();
            };
            function upFn(ev){
                var e=a.platform_type=='pc'?ev:ev.targetTouches[0];
                if(is_down==true){
                    is_down=false;
                    times++;
                    end_times=(new Date()).getTime();
                    

                    checkFn(e);
                }
                
            };

            function checkFn(e){
                var is_ok=false;
                var obj={'max_x':a.box.clientWidth,'times':times,'start':start_times,'end':end_times,data:positions,'result':is_ok};
                if(nx>=limit_x-20){
                    obj.result=true;
                    a.data.positions.push(obj);
                    
                    nx=limit_x;
                    removeEvent(a.btn,a.ekeys[a.platform_type][0],downFn);
                    removeEvent(a.box,a.ekeys[a.platform_type][1],moveFn);
                    removeEvent(document,a.ekeys[a.platform_type][2],upFn);
                    setStyle(nx);
                    a.success!=undefined?a.success.call(a,obj):'';
                }else{
                    a.error!=undefined?a.error.call(a,obj):'';
                    nx=0;
                    a.data.positions.push(obj);
                    setStyle(nx);
                }

                
                
            };


            function getBaseInfo(){
                limit_x=a.box.clientWidth-a.btn.clientWidth;
            };


            function setStyle(x){
                if(x<0){
                    x=0;
                }else if(x>limit_x && limit_x!=0){
                    x=limit_x;
                }
                a.btn.style.left=x+'px';
                a.bg.style.width=x+'px';
            };

            function addEvent(obj,type,func){					// 事件绑定
                // obj==目标对象，type==事件,func==绑定的函数
                if(obj.addEventListener){
                    obj.addEventListener(type,func,false);
                }else if(obj.attachEvent){
                    // ie
                    obj.attachEvent("on"+type,func);
                }else{
                    obj.on[type]=func;
                }
            };
            function removeEvent(obj,type,func){					// 取消事件绑定		
                if(obj.removeEventListener){
                    obj.removeEventListener(type,func,false);
                }
                else if(obj.detachEvent){
                    obj.detachEvent("on"+type,func);
                }else{
                    obj.on[type]=null;
                }
            };

        },
    };
    g.setDrag=setDrag;
}(this);
