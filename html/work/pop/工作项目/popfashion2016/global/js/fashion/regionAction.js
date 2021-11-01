/*
 * @todo    地区控件
 * @author  jiangwei
 * @time    2016/03/08
 * @demo ========================================================= 
				 RegionAction.start({
					 regionData:regionFromSolr[50],//地区缓存数据
				 });
		 =========================================================  
 */
var RegionAction={
	template:'<a class="#class" href="javascript:void(0);" data-id="#iRegionId">#sCnRegionName</a>',
	hrefTemplate:'?id=#iRegionId',
	regionData:'',
	myClass:'.region_box',
	//初始化数据
	init:function(obj){
		for(var key in obj){
			this[''+key]=obj[key];
		}
		this.getContinent(2);
	},
	
	//获取所有大洲或区域,type=2大洲，type=1区域。
	getContinent:function(type){
		  var regionData=this.regionData;
		  var $arr=[];
		  var i=1;
		  var className='area on';
		  $(this.myClass).find('.area_qh a').each(function(){
		  	 if((type==1 && $(this).find("span").html()=='按区域') || (type==2 && $(this).find("span").html()=='按大洲')){
		  	 	$(this).addClass('on');
		  	 }else{
		  	 	$(this).removeClass('on');
		  	 }	
		  });

		  $(this.myClass+' .allred').html('');
		  for(var key in regionData){
				 if(regionData[key]['iLevel']==type){
					  if(type==2){
						 if(regionData[key]['iRegionId']==11){
							  continue;
						  }
						 if(i==1){
						   this.getCountries(regionData[key]['iRegionId']);
						   className='continent on';
						   i++;
						 }else{
							className='continent';
						 }
					  }else if(type==1){
						  if(i==1){
						    this.getContinentFromAreaId(regionData[key]['iRegionId']);
					        i++;
						  }else{
							className='area';  
						   }
					   }
					 $arr.push(this.template.replace(/#iRegionId/g,regionData[key]['iRegionId'])
											.replace(/#sCnRegionName/g,regionData[key]['sCnRegionName'])
											.replace(/#class/g,className)
							   );
				  }
		   }
		   if($arr.join('')==''){
			   $(this.myClass+' .content').html('<p style="text-align:center;padding:5px;">无相关数据</p>');
			   this.autoHeight();
		   }
		    var continentHTML=$arr.join('');
		    
		   //要输出的大洲HTML
		   if(type==2){
			   $(this.myClass+" .mingc div[class='fl']").html('<span>大洲名称：</span>'+continentHTML);
			 }else if(type==1){
			    $(this.myClass+" .mingc div[class='fl']").html('<span>区域名称：</span>'+continentHTML);	 
			 }
		},
		
	//获取大洲下的国家和城市
	getCountries:function(ContinentId,flag){
		  var myTemplate='<dl class="category_dl clearfix"><dt>#replaceP</dt><dd>#replaceS</dd></dl>';
		  var regionData=this.regionData;
		  var arr=[];
		  var brochurOther = $('#link').data('other') == 'other' ? true : false;
		  for(var key in regionData){
			   if(regionData[key]['iRegionId']==ContinentId && flag!='ret'){
					var a=this.template.replace('javascript:void(0)',this.hrefTemplate)
								.replace(/#iRegionId/g,regionData[key]['iRegionId'])
								.replace(/#class/g,'getRegion')
								.replace(/#sCnRegionName/g,'全部'+regionData[key]['sCnRegionName']+'国家>>');
				   $(this.myClass+' .allred').html(a);
			   }
			  if(regionData[key]['iRegionPid']==ContinentId){
				   var replaceArr=[];
				   var replaceP=this.template.replace('javascript:void(0)',this.hrefTemplate)
				   							 .replace(/#iRegionId/g,regionData[key]['iRegionId'])
				                             .replace(/#sCnRegionName/g,regionData[key]['sCnRegionName'])
											 .replace(/#class/g,'getRegion country');
				   for(var i in regionData){
					   if(regionData[i]['iRegionPid']==regionData[key]['iRegionId']){
					   	if(brochurOther) {
					   		if ($.inArray(parseInt(regionData[i]['iRegionId']),[272,341,335,323]) > -1) {
						   		continue;
						   	}
					   	}
					     replaceArr.push(this.template.replace('javascript:void(0)',this.hrefTemplate)
						                .replace(/#iRegionId/g,regionData[i]['iRegionId'])
				                        .replace(/#sCnRegionName/g,regionData[i]['sCnRegionName'])
										.replace(/#class/g,'getRegion city')
						   );
					   }
				   }
				   var replaceS=replaceArr.join('');
				   arr.push(myTemplate.replace('#replaceP',replaceP).replace('#replaceS',replaceS));  
			  }
		  }
		   var countyHTML=arr.join('');//要输出的国家HTML
		   if(flag=='ret'){
			   return countyHTML;
			}else{
		      $(this.myClass+' .content').html(countyHTML);
			  this.autoHeight();
			}
	},
	
	//获取已知区域下的大洲和改大洲下的国家和城市
	getContinentFromAreaId:function(areaId){
		var regionData=this.regionData;
		var $arr=[];
		 for(var key in regionData){
			 if(regionData[key]['iRegionId']==areaId){
					var a=this.template.replace('javascript:void(0)',this.hrefTemplate)
								.replace(/#iRegionId/g,regionData[key]['iRegionId'])
								.replace(/#class/g,'getRegion')
								.replace(/#sCnRegionName/g,'全部'+regionData[key]['sCnRegionName']+'国家>>');
				   $(this.myClass+' .allred').html(a);
			   }
			if(regionData[key]['iRegionPid']==areaId){
				$arr.push(this.getCountries(regionData[key]['iRegionId'],'ret'));
			}
		}
		$(this.myClass+' .content').html($arr.join(''));
		this.autoHeight();
	},
	//控制高度根据内容自动适应
	autoHeight:function(){
		 var boxSel=$(".region_box .contentHolder");
		 boxSel.find('.ps-scrollbar-y-rail').hide();
		  var autoHeight=0;
		  obj=$(this.myClass+' .content');
		  autoHeight=obj.height();

		  if(autoHeight>220){
		  	 var scrollH=boxSel.prop('scrollHeight');
		  	 var barH = parseInt(330*330/scrollH,10);
		  	 boxSel.find('.ps-scrollbar-y-rail').show();
             boxSel.find(".ps-scrollbar-y").height(barH);

		  }    
	},
	//搜索框检索功能
	strSearch:function(input){
		  var continentIdArr=[];
		  var countryIds=[];
		  if(typeof($(this.myClass +' .continent.on').attr('data-id'))!='undefined'){
		      var continentId=$(this.myClass +' .continent.on').attr('data-id');
			  var countryIds=this.getIdsFromPid(continentId);
			  continentIdArr.push(parseInt(continentId));
		  }else if(typeof($(this.myClass +' .area.on').attr('data-id'))!='undefined'){
			  var areaId=$(this.myClass +' .area.on').attr('data-id');
			  continentIdArr=this.getIdsFromPid(areaId);
			  for(var key in continentIdArr){
				  countryIds = countryIds.concat(this.getIdsFromPid(continentIdArr[key]));
			  }
		  }
		  $(this.myClass+' .allred').html('<a href="javascript:void(0);" title="搜索结果" class="searchResult">搜索结果</a>');
		  var regionData=this.regionData;
		  var cityArr=[];
		  var countryArr=[];
		  for(var key in regionData){
			  var sCnRegionName=regionData[key]['sCnRegionName'];
			  var iLevel=regionData[key]['iLevel'];
			  var Pid=parseInt(regionData[key]['iRegionPid']);
			  if( sCnRegionName.indexOf(input)>-1 && input!='' && iLevel==4 && $.inArray(Pid,countryIds)>-1 ){
				  cityArr.push(this.template.replace('javascript:void(0)',this.hrefTemplate)
										.replace( /#iRegionId/g,regionData[key]['iRegionId'])
										.replace( /#sCnRegionName/g,regionData[key]['sCnRegionName'])
										.replace(/#class/g,'city')
				           );
			   }else if(iLevel==3 && sCnRegionName.indexOf(input)>-1 &&input!='' && $.inArray(Pid,continentIdArr)>-1){
			      countryArr.push(this.template.replace('javascript:void(0)',this.hrefTemplate)
										.replace( /#iRegionId/g,regionData[key]['iRegionId'])
										.replace( /#sCnRegionName/g,regionData[key]['sCnRegionName'])
										.replace(/#class/g,'country')
				           );
			   }
		  }
		  if(cityArr.join('')=='' && countryArr.join('')==''){
			   $(this.myClass+' .content').html('<p style="text-align:center;padding:5px;">无相关数据</p>');
			}else{
				$(this.myClass+' .content').html('<div class="countryBox">'+countryArr.join('')+'</div><div class="cityBox">'+cityArr.join('')+'</div>');
			} 
		  this.autoHeight();
		},
	//获取地域下的大洲---检功能索专用
	getIdsFromPid:function(areaId){
    var regionData=this.regionData;
		var arr=[];
		for(var key in regionData){
      if(regionData[key]['iRegionPid']==areaId){
        arr.push( parseInt(regionData[key]['iRegionId']) );
      }
		}
		return arr;
	},

	//开始部分
	start:function(obj){
		this.init(obj);
		var self=this;
		$(self.myClass+' .allcc .area_qh').on('click','a',function(){
      var _this = this;
      var idx = $(this).index();
      $(self.myClass).each(function(i, v) {
        var qhEl = $(v).find('.allcc .area_qh a').eq(idx);
        qhEl.addClass('on').siblings('a').removeClass('on');
      })
      var typeName = $(this).find("span").html();
      if(typeName=='按大洲'){
        self.getContinent(2);
      }else if(typeName=='按区域'){
        self.getContinent(1); 
      }
    });
	   //选择中具体大洲
    $(self.myClass).on("click",'.continent',function(){
      var idx = $(this).index() - 1;
      $(self.myClass).each(function(i, v) {
        var contEl = $(v).find('.continent').eq(idx);
        contEl.addClass('on').siblings().removeClass('on');
      })
      var ContinentId = $(this).attr('data-id');
      self.getCountries(ContinentId);
      $(self.myClass+' .searInput').val('请输入国家或城市的关键字');
		});
	   //选中具体地区
    $(self.myClass).on("click",'.area',function(){
      var idx = $(this).index() - 1;
      $(self.myClass).each(function(i, v) {
        var areaEl = $(v).find('.area').eq(idx);
        areaEl.addClass('on').siblings().removeClass('on');
      })
      var areaId = $(this).attr('data-id');
      self.getContinentFromAreaId(areaId);
      $(self.myClass+' .searInput').val('请输入国家或城市的关键字');
		});
		//搜索框清空
		$(self.myClass).on("focus blur",'.searInput',function(){
      $(self.myClass).each(function(i, v) {
        var searEl = $(v).find('.searInput');
        if(searEl.val()=='请输入国家或城市的关键字'){
          searEl.val(''); 
        }else if($.trim(searEl.val())==''){
          searEl.val('请输入国家或城市的关键字');
        }
      })
    });
    //搜索框触发
    $(self.myClass).on("keyup click",'.searInput,.button',function(){
      var str = $(this).parent().find('input[type="text"]').val();
      if(str=='请输入国家或城市的关键字') {
        str='';
      }
      self.strSearch( $.trim(str) );
      if(typeof($(this).parents('.region_box').find('.continent.on').attr('data-id'))!='undefined' && str==''){
        var continentId = $(this).parents('.region_box').find('.continent.on').attr('data-id');
        self.getCountries(continentId);
      }else if(typeof($(this).parents('.region_box').find('.area.on').attr('data-id'))!='undefined' && str==''){
        var areaId = $(this).parents('.region_box').find('.area.on').attr('data-id');
        self.getContinentFromAreaId(areaId);
      }
    });
	  //地区展开时，重新初始化(待定)
    $(self.myClass).parent('li').on('mouseenter',function(){
    var str=$(this).find(".searInput").val();
    if(str!='' || str!="请输入国家或城市的关键字"){
      self.getContinent(1);
      $(this).find(".searInput").val("请输入国家或城市的关键字"); 
    }
    });
    
    $('.region_box').on('click','.getRegion,.city,.country',function(){
      if (!oCommon.clickBeforeFlicker(this)) {
				return false;
			}
			var link = $("#link");
			var param = link.data('param');
			var search = link.data('search');
			var url = link.data('url');
			var _val = $(this).data('id');
			param = oPopStyles.dealParam(param,'reg',_val);
			param = search ? param.replace(/\/$/,'')+'/'+search : param+'/';
			location.href = url+param.replace(/^\//,'')+'#anchor';
		});
	}
};
