/*
	#author		fanglinna
	#date 		2018/8/16
    #porpuse    blog
*/


require(["general"], function (general) {
    $(function(){

        // 计算列表框高
        function boxHeight(){
            var framewidth;
            $(".js-blog-li").each(function(){
                framewidth = $(this).width();
                var frameheight = Math.round(framewidth/2.9);
                $(this).find(".img-box").height(frameheight);
            });
        }
        boxHeight();
        window.onresize = function(){
            boxHeight();
        }
        
        
        // 加载数据
        var def={
            "pagesize":$("#link").data("pagesize"),
            "total":$("#link").data("total"),
            "page_num":1,
            "frist_load":true,   //第一次加载
            "load_data":false,
            "list_more":true     //继续加载
        }
        var WEB_URL_IMG_DT='https://imgf2.pop-fashion.com/';
        if (($('html').width() <= 800)) {
          if ($('.js-hot-tags-ul').height() > parseFloat($('html').css('font-size')) * 6.83) {
            $('.js-show-all').parent().show();
            $('.js-blog-hot-tags').css({'margin-bottom': '2.5rem'});
          }
        } else if ($('.js-hot-tags-ul').height() > 125) {
          $('.js-show-all').parent().show();
          $('.js-blog-hot-tags').css({'margin-bottom': '60px'});
        }
        $('.js-show-all').on('click', function() {
          $(this).parent().hide();
          $('.js-hot-tags-ul-box').css({'height': 'auto'});
        })
        $('.js-select-tag').hover(function() {
          $(this).addClass('active');
        }, function() {
          $(this).removeClass('active');
        })
        if(def.total <=def.pagesize){
            $(".js-load-more-btn").parent().hide();
            return;
        }
        $(".js-load-more-btn").on("click", function(){
            def.page_num++;
            def.frist_load = false;
            loadData();            
        });

        // $(window).on("scroll", function(){
        //     if(def.frist_load == true){
        //         return;
        //     }
        //     var srcollTop=($(window).scrollTop())
        //     var foot_h =$(".js-blog-footer").height();
        //     var content_h=($(document).height()-foot_h)
        //     var window_h=$(window).height()
        //     if(srcollTop >= content_h -window_h -900 && def.list_more == true && def.load_data == false){
        //         def.page_num++;
        //         // loadData();
        //         $(".js-load-more-btn").parent().show();
        //     }
            

        // });
        function loadData(){
            def.load_data = true;
            general.fn.subAjax({
                url:window.location.href,
                data:{page:def.page_num},
                ctp:'application/x-www-form-urlencoded; charset=UTF-8',
                success:function(data){                 
                    getData(data);
                }                
            });
        }
        function getData(data){
            var arr=data.data||[];
            if(arr.length>0){
                def.load_data = true;   //加载列表
                var _html="",data_box=$(".js-blog-list-ul");
                for(var i=0,len=arr.length;i<len;i++){
                    var dPublishTime = arr[i]["dPublishTime"] ||"";
                    dPublishTime = dPublishTime.substr(0,10);
                    var sAuthor = arr[i]["sAuthor"] || "";
                    var sTitle = arr[i]["sTitle"] || "";
                    var sCoverImgPath= arr[i]["sCoverImgPath"] || "";
                    sCoverImgPath = sCoverImgPath.indexOf('pop-fashion.com') ==-1?WEB_URL_IMG_DT+sCoverImgPath :sCoverImgPath;
                    var url = arr[i]["url"] || "";
                    _html+='<li class="js-blog-li">';
                    _html+='<a href="'+url+'" title="'+sTitle+'" target="_blank">';
                    _html+='<div class="img-box" style="background:url('+sCoverImgPath+') no-repeat;background-size:100%;"></div>'
                    _html+='<div class="blog-infor">';
                    _html+='<p class="blog-title">'+sTitle+'</p>';
                    _html+='<div class="author-and-data">';
                    _html+='<span class="author-span"><img src="'+WEB_URL_IMG_DT+'/global/images/blog/common/author.png">作者：'+sAuthor+'</span>';
                    _html+='<span class="data-span"><img src="'+WEB_URL_IMG_DT+'/global/images/blog/common/data.png">'+dPublishTime+'</span>';
                    _html+='</div>';
                    _html+='</div>';
                    _html+='</a>';
                    _html+='</li>';
                }
                if(def.page_num==0){
                    data_box.html(_html); 
                }else{
                    data_box.append(_html);
                }
                if(def.page_num*def.pagesize >= data.info.total_count){
                    def.list_more = false;
                }
                def.load_data = false;
                if (def.list_more == false && def.load_data == false) {
                  $(".js-load-more-btn").parent().hide();
                }
                boxHeight();
            }else{
              def.list_more = false;
              $(".js-load-more-btn").parent().hide();
            }
        }
    });


})



