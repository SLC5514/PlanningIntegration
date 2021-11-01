/*
	#author		shuzhan
	#date 		2018/08/27
*/
require.config({
    paths:{
        "lazyload":["lib/jquery.lazyload.min"],
        "laypage":["lib/laypage"],
    },
    shim:{
       	"lazyload":{
            deps: ["jquery"],
            exports: "lazyload"
        },
        "laypage":{
        	exports: "laypage"
       	}
    }
});

require(["jquery","general",'laypage','msg',"lazyload"],function(jquery,general,laypage,msg){
    
    $(function(){
        var img_reg = /http(s?):\/\//;
        var def={
            is_set_page:false,
            page_size:30,
            page:1,
            pages:1,
            is_deletion_now:false,                                      //是否删除操作分页
            is_deletion:false                                           //是否批量删除操作
        };

        def.page=location.hash!=''?(location.hash.replace('#!page=','')-0):1;
        def.page=def.page?def.page:1;

        //写入设计列表
        function setDesignList(data){
            var origin_data=data;
            var data = data.data||{}, list = data.list||[], _len = list.length||0,  _html='';
            for (let i = 0; i < _len; i++) {
                var cover = list[i].cover||'';
                if( !img_reg.test(cover) ){
                    cover = STATIC_URL2 + cover;
                }
                var id = list[i].id||'';
                var memo = list[i].memo||'';
                var t = list[i].t||'';
                var dCreateTime = list[i].dCreateTime||'';
                dCreateTime = dCreateTime.split(' ')[0]||'';

                _html += '<li data-id="'+id+'" data-t="'+t+'"><a href="javascript:void(0);" title="'+memo+'">';
                if( i <= 11 ){
                    _html += '<img src="'+cover+'" alt="'+memo+'">';
                }else{
                    _html += '<img data-original="'+cover+'" src="/global/images/common/reference150-150.gif" alt="'+memo+'">';
                }
                
                _html += '<div class="dis-posion"><p><span class="icon"></span>'+dCreateTime+'</p></div>';
                _html += '</a>';
                _html += '<div class="checked-bg js-checked-bg"></div></li>';
            }

            if(_html){
                $('.js-design-list').html(_html);
                $('html,body').animate({'scrollTop':0+'px'},300,'linear');

            }
            if(def.is_set_page==false || def.is_deletion_now==true){
                def.is_deletion_now=false;
                def.is_set_page=true;
                setPage(origin_data.info.total);
            }

            
            $('.js-design-list img').lazyload({effect: "fadeIn"});
            $('.js-loading-div,.js-bg-div').fadeOut();     //加载去除

        }

        // 获取设计列表
        getDesignList();
        function getDesignList(){
            
            general.fn.subAjax({
        		'url': '/patternlibrary/mihuidesignlist/',
        		'data': {
                    params: 'page_'+def.page+'-pageSize_'+def.page_size
                },
        		'ctp':'application/x-www-form-urlencoded',
        		'success':function(data){  
                    setDesignList(data);
        		},
        		'error':function(){
        			$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
        		}
        	})
        };

        // 设置分页
        function setPage(total){
            def.pages=Math.ceil(total/def.page_size);
            new laypage({
                cont:$('.js-laypage-section')
                ,pages:def.pages
                ,curr:def.page
                ,groups:5
                ,hash:'page'
                ,skip:true
                ,first:1
                ,last:def.pages
                ,prev:'&nbsp;'
                ,next:'&nbsp;'
                ,jump:function(obj,is_first){
                    def.page=obj.curr;
                    if(is_first!=true){
                        $('.js-loading-div,.js-bg-div').fadeIn();     //加载去除
                        getDesignList();
                    }
                }
            });
            $('.js-laypage-section').show();
        };



        //-----------------------------------------事件绑定--------------------------------------------      



    	
    	//图案列表点击
		$('.js-design-list').on('click','li',function(e){
            var _page = def.page;
            var _id=$(this).data('id')||'';
            var _t=$(this).data('t')||'';

            if(def.is_deletion==true){
                if(!$(this).hasClass('now-choice')){
                    $(this).addClass('now-choice');
                    $('.js-batch-deletion-btn').css({'display':'none'});
                    $('.js-confirm-deletion-btn').css({'display':'inline-block'});
                }else{
                    $(this).removeClass('now-choice');
                    if($('.js-design-list>li.now-choice').length<1){
                        $('.js-batch-deletion-btn').css({'display':'inline-block'});
                        $('.js-confirm-deletion-btn').css({'display':'none'});
                    }
                }
            }else{
                var frame_url = '/patternlibrary/detail/?id='+_id+'&t='+_t+'&origin_type=3&page='+_page+'&page_size='+def.page_size+'&times='+(new Date()).getTime();
                $('.js-detail-frame').find('iframe').attr('src',frame_url);
                $('.js-detail-frame').fadeIn();
                $('body').addClass('over-hidden');
            }
            
        });

        // 批量删除激活
        $('.js-batch-deletion-btn').on('click',function(){
            if(def.is_deletion==false){
                $(this).css({'background-color':'#dba428'});
                $('.js-cancel-deletion-btn').css({'display':'inline-block'});
                def.is_deletion=true;
            }else{
                def.is_deletion=false;
                $(this).css({'background-color':'#787c81'});
                $('.js-cancel-deletion-btn').css({'display':'none'});
            }
        });
        // 取消批量删除
        $('.js-cancel-deletion-btn').on('click',function(){
            if(def.is_deletion==true){
                $('.js-batch-deletion-btn').css({'background-color':'#787c81','display':'inline-block'});
                $(this).css({'display':'none'});
                $('.js-confirm-deletion-btn').css({'display':'none'});
                def.is_deletion=false;

                $('.js-design-list>li.now-choice').removeClass('now-choice');
            }
        });
        // 确认批量删除
        $('.js-confirm-deletion-btn').on('click',function(){
            if(def.is_deletion==false){return};
            var tags=$('.js-design-list>li.now-choice');
            var data=[],eles=[];
            tags.each(function(){
                var _id=$(this).data('id')||'';
                if(_id!=''){
                    data.push(_id);
                    eles.push($(this));
                }
            });
            if(data.length<1){
                msg.msg({'txt':'请先选择要删除的花型！'},1200);
                return;
            }
            $('.js-loading-div,.js-bg-div').fadeIn();     //加载去除
            general.fn.subAjax({
                'url': '/patternlibrary/batchdelmihuipic/',
                'data': {
                    ids:data
                },
                'ctp':'application/x-www-form-urlencoded',
                'success':function(data){  
                    for(var i=0,len=eles.length;i<len;i++){
                        eles[i].remove();
                    }
                    
                    msg.msg({txt:'删除成功！'},function(){
                        
                        if($('.js-design-list>li').length<1 && def.page==def.pages){
                            def.page-=1;
                            location.hash = '!page='+def.page;
                        }
                        def.is_deletion_now=true;
                        getDesignList();
                    },1200);
                },
                'error':function(){
                    $('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
                }
            });
		});
		
		
		// $('.js-laypage-section').on('keyup','.js-layui-laypage-skip',function(ev){
  //           var val=this.value;
  //           var re=/\D/g;
  //           if(re.test(val)==true){
  //               val=val.replace(re,'');
  //               this.value=val;
  //           }
		// 	if(ev.keyCode==13){
  //               // if(val)
  //           }
		// });


        //设计列表hover
		$('.js-design-list').on('mouseenter mouseleave','li',function(e){
			var e = e || window.event;
			if (e.type == 'mouseenter') {
				$(this).find('.dis-posion').stop(true).slideDown(200);
				$(this).find('.dis-posion').animate({
					'opacity':1
				},150,'linear');
			}else{
				$(this).find('.dis-posion').stop(true).slideUp(200);
				$(this).find('.dis-posion').animate({
					'opacity':0.5
				},150,'linear');
			}
		});







    })

})



