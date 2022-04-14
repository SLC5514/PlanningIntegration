/*
* # date                        2017-09-01
* # author                      lut000
* # content                     color card
* # edition                     development 1.0
*/

(function(g){
    //获取对象
    var series_box=document.querySelector(".js-color-series-div") || null;
    var detail_box=document.querySelector(".js-detail-color-div") || null;
    if(!series_box){return;}
    var detail_par=detail_box.parentNode;
    var detail_canvas=detail_box.querySelector("canvas");
    var series_canvas=series_box.querySelector("canvas");

    var spa1=series_box.querySelector("span");
    var spa2=detail_box.querySelector("span");

    var close_btn=document.body.querySelector(".js-close-color-btn");
    // var spa3=document.querySelector(".js-color-val-div>span");
    var area=document.querySelectorAll(".js-color-val");
    var spnw=parseInt(getComputedStyle(spa1,false)["width"]);
    // 局部对象
    var nw=240;
    // console.log(nw);
    var def={
        is_show_color:false,
        is_drag_start:false,
        ow:nw,
        dw:280,
        tw:400,
        th:40,
        x:0,
        y:0
    };
    def.x=def.dw-1;
    def.k=def.tw/def.ow;
    //初始化
    detail_canvas.width=def.dw;
    detail_canvas.height=def.dw;
    series_canvas.width=def.tw;
    series_canvas.height=def.th;

    var ctx=detail_canvas.getContext("2d");
    var ctx2=series_canvas.getContext("2d");

    //生成色带
    var tw=def.tw;
    while(--tw>-1){
        ctx2.beginPath();
        ctx2.strokeStyle="hsl("+tw+",100%,50%)";
        ctx2.moveTo(tw+1,0);
        ctx2.lineTo(tw+1,def.th);
        ctx2.stroke();
        ctx2.closePath();
    }

    var canvas_data=ctx2.getImageData(0,0,1,1);
    setCanvasData(canvas_data.data);
    //设置色板
    function setCanvasData(data){
        var ow=def.dw,
            oh=def.dw,
            r=data[0],
            gg=data[1],
            b=data[2],
            a=data[3],
            img_data=ctx.getImageData(0,0,ow,oh),
            px_data=img_data.data,
            k=0,
            v=0
        ;
        //循环高度
        for(var i=0;i<oh;i++){

            k=(oh-i)/oh;

            v=255*k;
            for(var j=0;j<ow*4;j+=4){
                px_data[i*ow*4+j]=v-(v-r*k)*j/ow/4|0;
                px_data[i*ow*4+j+1]=v-(v-gg*k)*j/ow/4|0;
                px_data[i*ow*4+j+2]=v-(v-b*k)*j/ow/4|0;
                px_data[i*ow*4+j+3]=255;
            }
        }
        ctx.putImageData(img_data,0,0);
        setColor();
    };


    //点击选择色区
    series_canvas.addEventListener("click",function(e){
        var x=e.offsetX<0?0:e.offsetX;
        var y=e.offsetY<0?0:e.offsetY;
        var ndata=ctx2.getImageData(x*def.k,y,1,1);

        setCanvasData(ndata.data);
        spa1.style.left=x-Math.floor(spnw/2)+"px";

        if(def.is_show_color==false){
            def.is_show_color=true;
            detail_par.style.display="block";
        }

        e.preventDefault;
        e.stopPropagation();
    },false);
    //拖拽
    series_canvas.addEventListener("mousedown",function(e){
        def.is_drag_start=true;
        e.preventDefault;
        e.stopPropagation();
    },false);
    series_canvas.addEventListener("mousemove",function(e){
        if(def.is_drag_start==true){
            var x=e.offsetX<0?0:e.offsetX;
            var y=e.offsetY<0?0:e.offsetY;
            var ndata=ctx2.getImageData(x*def.k,y,1,1);
            setCanvasData(ndata.data);
            spa1.style.left=x-Math.floor(spnw/2)+"px";
        }
        
        e.preventDefault;
        e.stopPropagation();
    },false);
    series_canvas.addEventListener("mouseup",function(e){
        if(def.is_drag_start==true){
            def.is_drag_start=false;
        }
        e.preventDefault;
        e.stopPropagation();
    },false);
    document.body.addEventListener("mouseup",function(e){
        if(def.is_drag_start==true){
            def.is_drag_start=false;
        }
    },false);
    document.body.addEventListener("click",function(e){
        if(def.is_show_color==true){
            def.is_show_color=false;
            detail_par.style.display="none";
        }
    },false);

    detail_par.addEventListener("click",function(e){
        e.preventDefault;
        e.stopPropagation();
    },false);
    close_btn.addEventListener("click",function(e){
        def.is_show_color=false;
        detail_par.style.display="none";
        e.preventDefault;
        e.stopPropagation();
    },false);
    //选择色值
    detail_canvas.addEventListener("click",function(e){
        var x=e.offsetX<0?0:e.offsetX;
        var y=e.offsetY<0?0:e.offsetY;
        if(x>=def.dw){
            x=def.dw-1;
        }
        spa2.style.top=e.offsetY-spnw/2+"px";
        spa2.style.left=e.offsetX-spnw/2+"px";
        def.x=x;
        def.y=y;
        setColor();
        e.preventDefault;
        e.stopPropagation();
    },false);

    function setColor(){
        var nimg_data=ctx.getImageData(def.x,def.y,1,1);
        var r=nimg_data.data[0];
        var g=nimg_data.data[1];
        var b=nimg_data.data[2];
        var a=nimg_data.data[3];
        var ff=toFF(r)+toFF(g)+toFF(b);
        area[0].value=r+","+g+","+b;
        area[1].value=ff;
        if(pattern_model!=null){
            pattern_model.color_layer="#"+ff;
            $(".js-pattern-div").css({"background-color":"#"+ff});
        }
        

        // spa3.style.backgroundColor="#"+ff;
    };

    function toFF(num){
        return num>16?num.toString(16).toUpperCase():"0"+num.toString(16).toUpperCase();
    };


    function getStyle(obj,name){                    // 获取样式----------ie7需要单独处理
        if(obj.currentStyle){
            return obj.currentStyle[name];
        }else{
            return getComputedStyle(obj,false)[name];
        }
    };
})(window);