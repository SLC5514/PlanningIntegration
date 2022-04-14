/*
    #author     lut000
    #date       2017/08/28
*/

$(function(){
	// 局部变量
	var def={
        ww:document.documentElement.clientWidth || document.body.clientWidth,
		wh:document.documentElement.clientHeight || document.body.clientHeight,
        ele1:$(".js-pattern-container"),                                                    //图案包含框
        ele2:$(".js-pattern-div"),                                                          //列表包含框
        ele3:$(".js-controll-div"),                                                         //控制
        ele4:$(".js-pattern-list"),                                                         //图案列表
        ele5:$(".js-check-list"),                                                           //已选中
        ele6:$(".js-check-condition"),                                                      //筛选按钮
        ele7:$(".js-check-box"),                                                            //是否全选
        ele8:$(".js-page-controll"),                                                        //页码控制
        ele9:$(".js-condition-box"),                                                        //筛选条件框
        ele10:$(".js-bg-div2"),                                                             //筛选条件背景
        loading_ele:$(".js-loading-div"),                                                   //加载等待


        img_href:nimg_href+"/global/images/",                                               //图片路径
        is_change_data:false,                                                               //是否更新数据
        is_del_data:false,                                                                  //是否有删除选择
        check_data:[],                                                                      //图案数据

        npage:1,                                                                            //当前页面
        total:1,                                                                            //总页数
        page_size:10,                                                                       //没页条数
        is_show_condition:false,                                                            //是否显示筛选条件
        is_settime:false,                                                                   //是否生成时间

        start_time:null,                                                                    //开始对象
        end_time:null,                                                                      //结束对象

        nlm_id:"",                                                                           //当前select特殊
        select_data:{},                                                                     //筛选条件数据
        get_list_org:{                                                                      //请求列表参数
            page:1,
            page_size:10
        },                                                                    
        check_type:1,                                                                       //筛选类型，1=从新筛选，2=下一页
        parent_ele:parent
	};

    //样式
    setPosition();
    def.ele2.perfectScrollbar();
    //加载数据
    getList();


    /*---------------------事件绑定---------------------*/


    // 切换选中
    // $(".js-nav>li").on("click",function(){
    //     var index=$(this).index();
    //     if(!$(this).hasClass("now-choice")){
    //         $(this).addClass("now-choice").siblings("li").removeClass("now-choice");
    //         if(index==0){
    //             def.ele4.show();
    //             def.ele5.hide();
    //             def.ele6.show();
    //             // def.ele7.hide();
    //             def.ele8.show();
    //             $(".js-cancel-btn").show();
    //             def.is_change_data=false;
    //             if(def.is_del_data==true){
    //                 checkDom();
    //             }
    //         }else if(index==1){
    //             def.ele5.show();
    //             def.ele4.hide();
    //             // def.ele7.show();
    //             def.ele6.hide();
    //             def.ele8.hide();
    //             $(".js-cancel-btn").hide();
    //             if(def.is_change_data==true){
    //                 setCheckedList();
    //             }
                
    //             def.is_del_data=false;
    //         }
    //     }
    // });


    
    // 显示筛选条件
    def.ele6.on("click",function(){
        if(def.is_show_condition==false){
            def.is_show_condition=true;
            def.ele9.fadeIn(200);
            def.ele10.fadeIn(400);
            if(def.is_settime==false){
                def.start_time=laydate.render({
                    elem:".js-start-time"
                });
                def.end_time=laydate.render({
                    elem:".js-end-time"
                });
            }
        }else{
            def.is_show_condition=false;
            def.ele9.fadeOut(200);
            def.ele10.fadeOut(400);
        }
    });
    // 关闭筛选
    $(".js-close-btn").on("click",function(){
        def.is_show_condition=false;
        def.ele9.fadeOut(200);
        def.ele10.fadeOut(400);
    });
    // 确定筛选
    $(".js-confirm-search-btn").on("click",function(){
        var arr=$(".js-condition-box>form").serializeArray();
        def.npage=1;
        var return_obj={
            page:def.npage,
            page_size:def.page_size
        };
        for(var i=0,len=arr.length;i<len;i++){
            if(arr[i]["value"]!=""){
                return_obj[arr[i]["name"]]=arr[i]["value"];
            }
        }
        def.check_type=1;
        def.get_list_org=return_obj;
        getList();
        def.is_show_condition=false;
        def.ele9.fadeOut(200);
        def.ele10.fadeOut(400);
        return false;
    });
    //选中图案
    $(".js-pattern-div").on("click",".js-check-ele",function(){
        var id=$(this).attr("data-id"),nsrc="";
        var src=$(this).children("img").eq(0).attr("src");
        var big_img=$(this).attr("data-bigsrc") || "";
        var table_name=$(this).attr("data-tableName") || "";
        if(id!="" && id!=undefined){
            var data=def.check_data,len=data.length;
            var is_new_data=true,nindex=0;
            if(len>0){
                for(var i=0;i<len;i++){
                    var nid=data[i]["id"];
                    if(nid==id && nid!=""){
                        nindex=i;
                        is_new_data=false;
                        break;
                    }
                }
                if(is_new_data==true){
                    data.push({
                        id:id,
                        big_img:big_img,
                        small_img:src,
                        table_name:table_name
                        // is_check:true,
                    });
                    nsrc="pattern-list/icon3.png";
                }else{
                    nsrc="pattern-list/icon2.png";
                    data.splice(nindex,1);
                }
            }else{
                //建立数据
                data.push({
                    id:id,
                    big_img:big_img,
                    small_img:src,
                    table_name:table_name
                    // is_check:true,
                });
                nsrc="pattern-list/icon3.png";
            }
            $(this).children(".js-check-img").attr("src",def.img_href+nsrc);
            def.is_change_data=true;
        }
        
    });
    //全选or全不选
    def.ele7.on("click",function(){
        var data=def.check_data,len=data.length;
        if(!$(this).hasClass("js-now")){
            def.ele4.find(".js-check-ele").each(function(){
                var id=$(this).attr("data-id");
                var big_img=$(this).attr("data-bigsrc") || "";
                var table_name=$(this).attr("data-tableName") || "";
                var src=$(this).children("img").eq(0).attr("src") || "";
                if(id!="" && id!=undefined){
                    var is_check_in=false;
                    for(var i=0;i<len;i++){
                        var nid=data[i]["id"];
                        if(nid==id && nid!=""){
                            is_check_in=true;
                            break;
                        }
                    }
                    if(is_check_in==false){
                        data.push({
                            id:id,
                            big_img:big_img,
                            small_img:src,
                            table_name:table_name
                            // is_check:true,
                        });
                    }
                    $(this).children(".js-check-img").attr("src",def.img_href+"pattern-list/icon3.png");
                }
            });
            $(this).addClass("js-now");
            $(this).children("img").attr("src",def.img_href+"pattern-list/icon5.png");
        }else{

            var new_data=[];
            def.ele4.find(".js-check-ele").each(function(){
                var id=$(this).attr("data-id"),nindex=0,is_check_in=false;
                if(id!="" && id!=undefined){
                    for(var i=0,len=data.length;i<len;i++){
                        var nid=data[i]["id"];
                        if(nid==id && nid!=""){
                            is_check_in=true;
                            nindex=i;
                            break;
                        }
                    }
                    if(is_check_in==true){
                        data.splice(nindex,1);
                    }
                }
            });
            $(this).removeClass("js-now");
            $(".js-check-ele>img.js-check-img").attr("src",def.img_href+"pattern-list/icon2.png");
            $(this).children("img").attr("src",def.img_href+"pattern-list/icon1.png");
        }
    });
    //删除选中的项
    def.ele2.on("click",".js-del-check",function(){
        var id=$(this).parent().attr("data-id");
        var data=def.check_data;
        if(id!="" && id!=undefined){
            for(var i=0,len=data.length;i<len;i++){
                var nid=data[i]["id"];
                if(nid==id && nid!=""){
                    data.splice(i,1);
                    break;
                }
            }
            def.is_del_data=true;
            $(this).parent().remove();
        }
    });



    // 分页控制
    // 上一页
    $(".js-prev-btn").on("click",function(){
        def.npage--;
        if(def.npage<1){
            def.npage=1;
            return;
        }
        def.check_type=2;
        getList();
    });
    // 下一页
    $(".js-next-btn").on("click",function(){
        def.npage++;
        if(def.npage>def.total){
            def.npage=def.total;
            return;
        }
        def.check_type=2;
        getList();
    });

    // 回车查询
    $(".js-page-area").on("keydown",function(ev){
        var e=ev || window.event;
        if(e.keyCode==13){
            var txt=$(this).val();
            txt=parseInt(txt);
            txt=txt<1?1:txt;
            txt=txt>def.total?def.total:txt;
            def.npage=txt;
            def.check_type=2;
            $(this).val(txt);
            getList();
        }
    }).on("blur",function(){
        var txt=$(this).val();
        var re=/\D/g;
        if(re.test(txt)==true){
            txt=txt.replace(re,"");
            $(this).val(txt);
        }
    });

    // 确定跳转到指定页
    $(".js-go-btn").on("click",function(){
        var txt=$(".js-page-area").val();
        txt=parseInt(txt);
        if(txt<0){
            txt=1;
        }else if(txt>def.total){
            txt=def.total;

        }
        $(".js-page-area").val(txt);
        def.npage=txt;
        def.check_type=2;
        getList();
    });


    //提交数据
    $(".js-return-data-btn").on("click",function(){
        var data=def.check_data;

        if(data.length<1){
            msg.msg({"txt":"请先选择图片！"},1200);
            return;
        }
        def.loading_ele.fadeIn(400);
        pop_fashion_global.fn.subAjax({
            url:"/puzzle/getpuzzlelist/",
            message:"msg",
            ctp:"application/x-www-form-urlencoded",
            data:{data:data},
            successFunc:function(){
                msg.msg({"txt":"提交成功！"},1200);
                def.loading_ele.fadeOut(400);
            },
            errorFunc:function(){
                def.loading_ele.fadeOut(400);
            }
        });
    });


    //获取数据
    function getList(opt){
        def.loading_ele.fadeIn(400);
        var data=def.get_list_org;
        data.page=def.npage;
        pop_fashion_global.fn.subAjax({
            url:"/puzzle/getpuzzlepicslist/",
            message:"msg",
            ctp:"application/x-www-form-urlencoded",
            data:data,
            successFunc:setList,
            errorFunc:function(){
                def.loading_ele.fadeOut(400);
            }
        });
    };

    //生成列表
    function setList(data){
        if(data && data.data && data.data instanceof Array){
            var tag=def.ele4,_html='';
            var arr=data.data;
            var len=arr.length,num=1;
            for(var i=0;i<len;i++){
                var id=arr[i]["id"]!=undefined?arr[i]["id"]:"";
                var src=arr[i]["small_img"]!=undefined?arr[i]["small_img"]:"";
                var big_img=arr[i]["big_img"]!=undefined?arr[i]["big_img"]:"";
                var title=arr[i]["brandName"]!=undefined?arr[i]["brandName"]:"";
                var table_name=arr[i]["table_name"]!=undefined?arr[i]["table_name"]:"";
                var txt1=arr[i]["cat"]!=undefined?arr[i]["cat"]:"";
                var txt2=arr[i]["subCat"]!=undefined?arr[i]["subCat"]:"";

                var is_in=isChecked(id);

                if((i+1)%5==0){
                    _html+='<li style="margin-right:0">';
                }else{
                    _html+='<li>';
                }
                
                // src=def.img_href+"pattern-list/icon5.png";
                _html+='<div class="pic-div js-check-ele" data-id="'+id+'" data-bigsrc="'+big_img+'" data-tableName="'+table_name+'">';
                _html+='<img src="'+src+'" alt="pic">';
                if(is_in==true){
                    num++;
                    _html+='<img class="check-img js-check-img" src="'+def.img_href+'pattern-list/icon3.png" alt="check">';
                }else{
                    _html+='<img class="check-img js-check-img" src="'+def.img_href+'pattern-list/icon2.png" alt="check">';
                }
                
                _html+='<div>';
                _html+='<span>'+title+'</span>';
                _html+='<div></div>';
                _html+='</div>';
                _html+='</div>';
                _html+='<p>单品：'+txt1+'<br>品名：'+txt2+'</p>';
                _html+='<div class="show-big">';
                _html+='<a href="'+big_img+'" title="查看大图" target="_blank"><img src="'+def.img_href+'pattern-list/icon4.png" alt="icon">点击查看大图</a>';
                _html+='</div>';
                _html+='</li>';
            };
            tag.html(_html);
            //判断数据是否为空
            if(_html==""){
                if(def.check_type===1){
                    $(".js-at-p").eq(0).show();
                }
            }else{
                $(".js-at-p").eq(0).hide();
            }
            //计算图片高度
            var oh=$(".js-check-ele").eq(0).height();
            tag.find(".js-check-ele").each(function(){
                var oimg=$(this).children("img").eq(0);
                oimg.on("load",function(){
                    var nh=$(this).height();
                    if(nh<oh){
                        $(this).css({
                            "margin-top":(oh-nh)/2+"px"
                        });
                    }
                    
                });
            });

            def.ele2.perfectScrollbar("update");
            def.loading_ele.fadeOut(400);

            // 判断是否全选
            if(num>=len){
                $(".js-check-box").addClass("js-now");
                $(".js-check-box").children("img").attr("src",def.img_href+"pattern-list/icon5.png");
            }else{
                $(".js-check-box").removeClass("js-now");
                $(".js-check-box").children("img").attr("src",def.img_href+"pattern-list/icon1.png");
            }
            

            var total=data.info.count;
            def.total=total;
            if(def.npage==1){
                $(".js-prev-btn").css({"background-position":"-95px -10px"});
                $(".js-next-btn").css({"background-position":"-127px -10px"});
            }else if(def.npage==def.total){
                $(".js-prev-btn").css({"background-position":"-200px -10px"});
                $(".js-next-btn").css({"background-position":"-234px -10px"});
            }else{
                $(".js-prev-btn").css({"background-position":"-200px -10px"});
                $(".js-next-btn").css({"background-position":"-127px -10px"});
            }
            $(".js-now-page").text(def.npage);
            $(".js-total-page").text("/ "+def.total);
        }else{
            if(def.check_type===1){
                $(".js-at-p").eq(0).show();
            }
        }
    };

    //检测是否选中
    function isChecked(tid){
        var data=def.check_data,is_in=false;
        for(var i=0,len=data.length;i<len;i++){
            var nid=data[i]["id"] || "";
            if(tid==nid && tid!=""){
                is_in=true;
            }
        }
        return is_in;
    };

    //检测被删除的项
    function checkDom(){
        var data=def.check_data;
        def.ele4.find(".js-check-ele").each(function(){
            var id=$(this).attr("data-id");
            if(id!="" && id!=undefined){
                var is_check_in=false;
                for(var i=0,len=data.length;i<len;i++){
                    var nid=data[i]["id"];
                    if(nid==id && nid!=""){
                        is_check_in=true;
                        break;
                    }
                }
                if(is_check_in==false){
                    $(this).children(".js-check-img").attr("src",def.img_href+"pattern-list/icon2.png");
                }
            }
        });
    };
    //生成选中的项
    function setCheckedList(){
        var tag=def.ele5,_html='',data=def.check_data;

        for(var i=0,len=data.length;i<len;i++){
            var id=data[i]["id"];
            var src=data[i]["src"] || "";
            _html+='<li data-id="'+id+'">';
            _html+='<img src="'+src+'" alt="pic">';
            _html+='<button class="icon-ele js-del-check" title="删除"></button>';
            _html+='</li>';
        };

        tag.html(_html);
    };


    // 切换数据

    $(".js-select-ele").on("change",function(){
        var data=def.select_data,key=$(this).attr("name");
        if(data[key]==undefined){
            data[key]={};
        }
        
        var val=$(this).val(),next_select=$(this).next("select");
        var index=$(".js-contact-select").index($(this));
        var _html='<option value="">请选择</option>';
        if(index==0){
            $(".js-contact-select").eq(1).html(_html);
            $(".js-contact-select").eq(2).html(_html);
            $(".js-contact-select").eq(3).html(_html);
        }else if(index==1){
            $(".js-contact-select").eq(2).html(_html);
            $(".js-contact-select").eq(3).html(_html);
        }else if(index==2){
            $(".js-contact-select").eq(3).html(_html);
        }
        if(val==""){
            if(next_select.length>0){
                $(this).next("select").val("");
            }
        }else{
            if(next_select.length>0){
                if(data[key][val]!=undefined){
                    setConditionDom(data[key][val],next_select);
                }else{
                    // 第一次请求数据
                    getConditionDom(key,val,next_select);
                }
            }
        }

        if(key=="cols"){
            if(val==4){
                $(".js-content-select-div").hide();
                $(".js-content-select-div").find("select").val("");
            }else if(val==9){
                $(".js-content-select-div").show();
            }
        }
        def.nlm_id=val;
    });


    //获取筛选条件
    function getConditionDom(key,id,ele){
        var sdata=def.select_data;
        pop_fashion_global.fn.subAjax({
            url:"/puzzle/getsublist/",
            message:"msg",
            ctp:"application/x-www-form-urlencoded",
            data:{
                id:id,
                inputName:key
            },
            successFunc:function(data){

                setConditionDom(data,ele);
                sdata[key][id]=data;
            },
            errorFunc:function(){

            }
        });
    };
    //生成select
    function setConditionDom(data,obj){
        var tag=obj,_html='<option value="">'+tag.attr("data-def")+'</option>',ndata=null;
        var nkey=tag.attr("name");

        switch(nkey){
            case "subCols":
                ndata=data.data?data.data:{};
                for(var key in ndata){
                    var id=key;
                    var txt=ndata[key]["sName"] || "";
                    if(def.nlm_id==9){
                        if(id==82){
                            _html+='<option value="'+id+'">'+txt+'</option>';
                        }
                    }else{
                        _html+='<option value="'+id+'">'+txt+'</option>';
                    }
                };
                break;
            case "subCat":
                ndata=data.data?data.data:{};
                for(var key in ndata){
                    var id=key;
                    var txt=ndata[key] || "";
                    _html+='<option value="'+id+'">'+txt+'</option>';
                };
                break;
            case "reg_2":
            case "reg_3":
            case "reg_4":
                ndata=data.data?data.data:[];
                for(var i=0,len=ndata.length;i<len;i++){
                    var id=ndata[i]["iRegionId"] || "";
                    var txt=ndata[i]["sCnRegionName"] || "";
                    _html+='<option value="'+id+'">'+txt+'</option>';
                };
                break;
            default:
                ndata=data.data?data.data:{};
                var id=ndata["iAttributeId"] || "";
                var txt=ndata["sName"] || "";
                _html+='<option value="'+id+'">'+txt+'</option>';
                break;
        }

        tag.html(_html);
    };


    
    //设置列表高度
    function setPosition(){
        var oh=def.ele1.height();
        var fh=def.ele3.height();
        var hh=def.ele1.find("h3").outerHeight();
        def.ele2.height(oh-fh-hh+"px");
    };

    //监听窗口
    $(window).on("resize",function(){
        throttle(setPosition,this);
    });

    function throttle(method,context,arr){          //函数节流
        clearTimeout(method.tid);
        method.tid=setTimeout(function(){
            method.apply(context,arr);
        },50);
    };




    
});