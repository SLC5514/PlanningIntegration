
// 轮播
!function (g){
	function StabSlide(opt){
    	if(opt && opt instanceof Object){
    		this.opt = opt||{};
    		this.refresh(opt);
    	}
    }
    StabSlide.prototype = {
    	start:function(){
    		var _this=this;
			if(_this.opt.loop==true && _this.opt.type == 1){      //循环添加头尾
				
				var node1=_this.opt.slide_item[0].cloneNode(true);
                var node2=_this.opt.slide_item[_this.opt.len-1].cloneNode(true);
                _this.opt.ul[0].insertBefore(node2,_this.opt.slide_item[0]);
                _this.opt.ul[0].appendChild(node1);
               
               	/*var node1=_this.opt.slide_item.eq(0);
            	var node2=_this.opt.slide_item.eq(_this.opt.len-1);*/
            	_this.opt.ul.append(node1);
            	_this.opt.ul.prepend(node2);
				
			}
			
			if( _this.opt.type == 1 ){
				_this.reStyle();
				$(window).on('resize',function(){
					_this.reStyle();
				});
			}else{
				_this.opt.slide_item.eq(_this.opt.index).show().siblings().hide();
			}
			
			
			if(_this.opt.progress_list && _this.opt.progress_list.length){		//进度
				_this.opt.progress_list.eq(_this.opt.index).addClass('cur').siblings().removeClass('cur');
			}
			_this.bind();		//事件绑定
			_this.opt.is_lun = false;
			_this.slid();		//开始转动
    			
    			
    	},
    	ind_t:function(x, ispress){
    		var _this = this, _index = _this.opt.index, ispress = ispress||false, now_index=_this.opt.index;
    		_this.opt.is_lun = true;
    		if(ispress){
    			now_index = x;
    		}else{
    			now_index = now_index+x;
    		}
    		if( now_index < 0 ){
    			now_index = _this.opt.len-1;
    		}else if( now_index > _this.opt.len-1 ){
    			now_index = 0;
    		}
    		_this.opt.index = now_index;
			clearTimeout(_this.opt.timer);
			
			if(_this.opt.type == 1){	//左右轮播
				if( _this.opt.loop ){
										
					if( _index == 0 && now_index == _this.opt.len-1 ){
						_this.opt.ul.stop(true,true).css({
							'margin-left':-1*(_this.opt.len+1)*_this.opt._w+'px'
						});
					}else if( _index == _this.opt.len-1 && now_index == 0 ){
						_this.opt.ul.stop(true,true).css({
							'margin-left': '0px'
						});
					} 
					
					now_index = now_index+1;
					
				}
				
				if(_this.opt.progress_list && _this.opt.progress_list.length){		//进度
    				_this.opt.progress_list.eq(_this.opt.index).addClass('cur').siblings().removeClass('cur');
    			}
				_this.opt.ul.stop(true,true).animate({
					'margin-left': -1*_this.opt._w*(now_index)+'px'
				},_this.opt.speed,'linear',function(){
					_this.opt.is_lun = false;
					clearTimeout(_this.opt.timer);
					if(!_this.opt.no_auto){		//不自动轮播吗
						_this.opt.timer = setTimeout(function(){
							_this.ind_t(1);
						},_this.opt.interval);
					}
				});
				
			}else{
				if(_this.opt.progress_list && _this.opt.progress_list.length){		//进度
    				_this.opt.progress_list.eq(now_index).addClass('cur').siblings().removeClass('cur');
    			}
				_this.opt.slide_item.stop(true,true).eq(now_index).fadeIn(_this.opt.speed).siblings().fadeOut(_this.opt.speed, function(){
					_this.opt.is_lun = false;
					clearTimeout(_this.opt.timer);
					if(!_this.opt.no_auto){		//不自动轮播吗
						_this.opt.timer = setTimeout(function(){
							_this.ind_t(1);
						},_this.opt.interval);
					}
				});
			}
			
    	},
    	slid:function(i, is_press){		//
    		var _this=this, _i = i==undefined?-9:i;
    		if(_this.opt.is_lun == true){
				return;
			}
    		if(_i == -9){		//自动轮播
    			clearTimeout(_this.opt.timer);
				_this.opt.timer = setTimeout(function(){
					_this.ind_t(1);
				},_this.opt.interval);
    		}else{		//跳转到 _i
				clearTimeout(_this.opt.timer);
				_this.ind_t(_i, is_press);
    		}
    	},
    	bind:function(){		//事件绑定
    		var _this=this;
    		if(_this.opt.pre && _this.opt.pre.length){		//前一张
    			_this.opt.pre.on('click',function(){
    				if(_this.opt.is_lun == false){
    					_this.slid(-1);
    				}
    			});
    		}
    		
    		if(_this.opt.next && _this.opt.next.length){	//后一张
    			_this.opt.next.on('click',function(){
    				if(_this.opt.is_lun == false){
    					_this.slid(1);
    				}
    			});
    		}
    		
    		if(_this.opt.progress_list.length && _this.opt.nav_event != 'none'){		//进度
    			_this.opt.progress_list.on(_this.opt.nav_event,function(){
    				if(_this.opt.is_lun == false){
    					var dex=$(this).index();
	    				_this.slid(dex, true);
    				}
    			});
			}
    		
    		if(_this.opt.hv_stop){			//自动轮播下hover暂停自动轮播
    			_this.opt.ul.hover(function(){
    				clearTimeout(_this.opt.timer);
    				_this.opt.no_auto = true;		// 不自动轮播
//  				_this.slid(_this.opt.index, true, true);	//轮播到最当前的li并暂停自动轮播
    			}, function(){		//恢复轮播
    				_this.opt.no_auto = false;
    				clearTimeout(_this.opt.timer);
    				_this.opt.is_lun=false;
    				_this.opt.timer = setTimeout(function(){
						_this.ind_t(1);
					},_this.opt.interval);
					
    			});
    		}
    		
    		
    	},
    	refresh:function(re_obj){
    		var _this = this;
    		var opt = re_obj||_this.opt||{};
    		if(_this.opt){
    			clearTimeout(_this.opt.timer);
    		}
    		
    		_this.opt = {};
    		
    		_this.opt.no_auto = opt['no_auto']||false;	//不自动轮播吗
    		_this.opt.type = opt['type']||0;		//轮播种类，0--渐出，1--左右
    		_this.opt.box = opt["contain"];         //包含框
    		_this.opt.loop=opt["loop"]||false;                    //是否轮播
            _this.opt.interval=opt["interval"]||5000;                    //自动播放间隔时间
            _this.opt.speed=opt["speed"]||500;                    //移动速度
            _this.opt.index=opt["index"]||0;                    //当前的进度
            _this.opt.nav_event = opt["nav_event"]||'mouseenter';			//导航轮播触动事件
            _this.opt.hv_stop = opt["hv_stop"]||false;			//是否hover暂停轮播
            
            
			_this.opt.ul = _this.opt.box.find('.js-slide-list');        //滚动框
    		
    		_this.opt.slide_item = _this.opt.ul.find('li');              //滚动item
    		_this.opt._w=opt["_w"]||_this.opt.slide_item.eq(0).outerWidth(true)||0;    //item宽度
    		_this.opt.len = _this.opt.slide_item.length||0;
            _this.opt.pre = _this.opt.box.find('.js-slide-pre');        //上一个
            _this.opt.next = _this.opt.box.find('.js-slide-next');        //下一个
            
            _this.opt.progres = _this.opt.box.find('.js-progress-bar');
            
            if(_this.opt.len <= 1){
            	_this.opt.progres.hide();
            	return;
            }
            
            if(_this.opt.progres && _this.opt.progres.length && _this.opt.progres.find('li').length<=0){
            	var progress_html = '';
            	for(var i=0;i<_this.opt.len;i++){
            		if( i == 0 ){
            			progress_html += '<li class="cur"></li>'
            		}else{
            			progress_html += '<li></li>'
            		}
            	}
            	_this.opt.progres.show().html(progress_html);
            }
        	_this.opt.progress_list = _this.opt.progres.find('li');             //进度
			
            _this.opt.timer = null;                //计时器
            _this.opt.is_lun = true;		  //正在轮播中	
            
            _this.start();
    		
    	},
    	reStyle: function(){
    		
    		var _this = this;
    		
    		_this.opt._w = _this.opt.slide_item.eq(0).outerWidth(true)||0;    //item宽度
    		if( _this.opt.type == 1){
    			if(_this.opt.loop){
    				_this.opt.ul.css({			//初始化样式
						'margin-left':-1*_this.opt._w*(_this.opt.index+1)+'px',
						"width":_this.opt._w*(_this.opt.len+2)+'px'
					});
    			}else{
    				_this.opt.ul.css({
						'margin-left':-1*_this.opt._w*_this.opt.index+'px',
						"width":_this.opt._w*_this.opt.len+'px'
					});
    			}
    		}
			
    	}
    };
    
    g.StabSlide = StabSlide;
    
}(window);

	
	

	
