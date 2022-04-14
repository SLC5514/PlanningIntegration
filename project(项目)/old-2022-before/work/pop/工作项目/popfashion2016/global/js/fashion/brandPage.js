var brandPage={
	brandAll:brandAll,//所有数据
	template:'<a href="javascript:void(0);" data-id="#id">#name#cnName</a>',
	showClass:'.brand_result_list',
	searchInputClass:'.brand_search .s_box',
	searchAliasClass:'.brand_search .zm',
	conditionClass:'.all_con .s_leibie',
	conditionCheckClass:'cur',//查询条件被选中时class
	aliasCheckClass:'check',//字母栏选中时的class
	unableClass:'isUnable',//字母栏不能被选中的class
	listArrAll:{},
	listArrAllOffset:65,
	//输出当前数据到页面
	printHTML:function(listArr,alias){
		this.isAbleCheck();
		if(alias=='all'){
			if(this.listArrAllOffset==65){
				$('.all_con .brand_result ul').html('');
			}
			var arrHTMLs='';
			var cnName;
			for(var aliasTmp in listArr){
				var arrHTML=[];
				for (var key in listArr[aliasTmp] ){
					if(listArr[aliasTmp][key]['cnName'] != '' && listArr[aliasTmp][key]['name']!=''){
						 cnName='/'+listArr[aliasTmp][key]['cnName'];
					}else{
						 cnName=listArr[aliasTmp][key]['cnName'];
					}
					arrHTML.push(this.template.replace('#id',key)
							.replace('#name',listArr[aliasTmp][key]['name'])
							.replace('#cnName',cnName)
					);
				}
				if (aliasTmp=='OTHER'){
					aliasTmp='其他';
				}else{
					aliasTmp=aliasTmp;
				}
				if(arrHTML.join('')==''){
					
				}else{
					arrHTMLs +='<li class="clearfix"><h3 class="fl">'+aliasTmp+'</h3><div class="brand_result_list">'+
					arrHTML.join('')+'</div></li>'
				}
			}
			if(arrHTMLs==''){
				var arrHTMLs='<li class="clearfix"><h3 class="fl">全部</h3><div class="brand_result_list"><p style="color:red;text-align:center;">该条件下没有品牌名，请尝试用其他字母进行检索...</p></div></li>';
				$('.all_con .brand_result ul').html(arrHTMLs);
			}else{
				if(aliasTmp=='A'){
					$('.all_con .brand_result ul').html(arrHTMLs);
				}else{
					$(".all_con .brand_result ul").append(arrHTMLs);
				}
			}
		}else{
			var arrHTML=[];
			if (alias=='OTHER'){
				var alias='其他';	
			}else{
				var alias=alias;
			}
			for (var key in listArr ){
				if(listArr[key]['cnName'] != '' && listArr[key]['name']!=''){
					var cnName='/'+listArr[key]['cnName'];
				}else{
					var cnName=listArr[key]['cnName'];
				}
				arrHTML.push(this.template.replace('#id',key)
						.replace('#name',listArr[key]['name'])
						.replace('#cnName',cnName)
				);
			}
			if(arrHTML.join('')==''){
				var HTML='<li class="clearfix"><h3 class="fl">'+alias+'</h3><div class="brand_result_list"><p style="color:red;text-align:center;">该条件下没有以“'+alias+'”开头的品牌名，请尝试用其他字母进行检索...</p></div></li>';
				$('.all_con .brand_result ul').html(HTML);
			}else{
				var HTML='<li class="clearfix"><h3 class="fl">'+alias+'</h3><div class="brand_result_list">'+
				arrHTML.join('')+'</div></li>';
			   $(".all_con .brand_result ul").html(HTML);
			}
		}
		//======样式控制======
    	$(".all_con .brand_result ul li:first").css({'border-top':'0'});
    	var self=this;
    	var boxSel=$(".all_con");
    	var boxH=boxSel.find('.contentHolder').height(); 
    	var scrollH = boxSel.find('.brand_result  ul').height();
        var barH = parseInt(boxH*boxH/scrollH,10);
    	if(scrollH>500){
    		if(alias!='all' || typeof(listArr['A']) != 'undefined'){
    			$('.contentHolder').scrollTop(0);	
    		}
    		$('.contentHolder').perfectScrollbar('update');
	        boxSel.find('.ps-scrollbar-y-rail').show();
	        boxSel.find(".ps-scrollbar-y").height(barH);
	        boxSel.find(".ps-scrollbar-y").css("top",0);	
    	}else{
    		boxSel.find('.ps-scrollbar-y-rail').hide();
    	}
    	//触发跳转
    	$('.brand_result li').on('click','a',function(){
    		var id=$(this).attr('data-id');
    		var contidion=self.getCondition().join('#');
			contidion=contidion.replace('-','0000');
    		var alias=self.getAlias();
    		var url='/brands/detail/id_'+id+encodeURIComponent(contidion ? '-c_'+contidion : '')+'/';
    		location.href=url;
    	});
	},
	//取出当前字母下符合条件的所有数据
	getDataList:function(condition,alias){
		var brandList=this.brandAll[alias];
		var listArr=[];
		if(condition.length > 0){ //有查询条件时
			var ret=false;
    		for(var key in brandList){
    			var arr = brandList[key]['search'].split(',');
    		    for(var i in condition){
        			if($.inArray(condition[i],arr)>-1){
        				ret=true;
        			}else{
        				ret=false;
        				break;
        			}
        		}
    		   if(ret){
    			   listArr[key]=brandList[key];
    		   }
        	}
        	return listArr;
    	}else{ //无查询条件时
    		for(var key in brandList){
    			if( typeof(brandList[key]['name']) !='undefined'){
    				return brandList;
    			}
    		}
    		return brandList;
    	}	
	},
	//检查该字母下是否存在数据
    checkIsExist:function(condition,alias){
    	var brandList=this.brandAll[alias];
    	var ret=false;
    	if(condition.length > 0){ //有查询条件时
    		for(var key in brandList){
    			var arr = brandList[key]['search'].split(',');
    		    for(var i in condition){
        			if($.inArray(condition[i],arr)>-1){
        				ret=true;
        			}else{
        				ret=false;
        				break;
        			}
        		}
    		   if(ret) break;
        	}
        	return ret;
    	}else{ //无查询条件时
    		for(var key in brandList){
    			if( typeof(brandList[key]['name']) !='undefined'){
    				return true;
    			}
    		}
    		return false;
    	}	
    },
    //输入框检索
    inputSearch:function(){
		var self=this;
    	var inputStr=$.trim($(this.searchInputClass).find('#searchInput').val());
    	if(inputStr=='请输入品牌名'){
    		inputStr='';
    	};
    	var condition = this.getCondition();
    	var letter = inputStr.substring(0, 1);
	    var regO = /^[A-Za-z]$/;
	     if (!isNaN(letter - 0) && letter!="") {
	         letter = '0-9';
	     } else if (regO.test(letter)) {
	         letter = letter.toUpperCase();
	     } else if ( letter =="") {
			 if(inputStr==''){
				var obj=$(self.searchAliasClass).find('a[data="all"]');
				$(self.searchAliasClass).find('a').removeClass(self.aliasCheckClass);
				obj.addClass(self.aliasCheckClass);
				self.showResult();
				return false;
			}else{
				letter=this.getAlias();
			}	
	     } else {
	         letter = 'OTHER';
	     }
    	var alias =letter;
    	var data=this.getDataList(condition,alias);
    	var listArr=[];
    	for(var key in data){
    		var str = (data[key]['name']+data[key]['cnName']).toUpperCase();
    		if(str.indexOf(inputStr.toUpperCase())>-1){
    			listArr[key]=data[key];
    		}
    	}
    	$(this.searchAliasClass+' a').removeClass(this.aliasCheckClass);
    	$(this.searchAliasClass).find('a[data="'+alias+'"]').addClass(this.aliasCheckClass);
    	this.printHTML(listArr, alias);
    },
    //获取查询条件
    getCondition:function(){
    	var ret=[];
    	$(this.conditionClass+' .'+this.conditionCheckClass).each(function(){
    		if( $(this).attr('data-id')!= '0'){
    			ret.push($(this).attr('data-id'));		
    		}
    	});
    	return ret;
    },
	 getType:function(){
		 var $ret;
    	$(this.conditionClass+' .'+this.conditionCheckClass).each(function(){
			//alert($(this).attr('data-type'));
    		if( $(this).attr('data-type') == 'gender'){
    			$ret='gender';
				return false;	
    		}
			$ret='';
    	});
    	return $ret;
    },
    //获取品牌首字母
    getAlias:function(){
    	var alias =$(this.searchAliasClass).find('.'+this.aliasCheckClass).attr('data');
        return alias;
    },
    //点击操作显示结果
    showResult:function(){
    	var self=this;
    	var condition = self.getCondition();
    	var alias = self.getAlias();
    	var listArr={};
    	if(alias=='all'){
			self.listArrAllOffset=65;
			$(self.searchAliasClass +' a').each(function(){
				var aliasTmp=$(this).attr("data");
				if(aliasTmp=='all')
						return ;
				listArr[aliasTmp]=self.getDataList(condition,aliasTmp);
				self.listArrAll=listArr;
			})
			//计算数量
			var count=0;
			for(var key in listArr){
				for(var i in listArr[key]){
					count++
				}
			}
			$(".all_searchContent span").html(count);
			
			var list={};
			if(condition==''){
				//无条件时，分段显示
				var tmp=String.fromCharCode(self.listArrAllOffset)
				list[tmp]=listArr[tmp];
			}else{
				//有条件时不分段显示
				list=listArr;
			}
			self.printHTML(list,alias);
    	}else{
    		listArr=self.getDataList(condition,alias);
    		var count=0;
    		for(var key in listArr ){
    			count++;
    		}
    		$(".all_searchContent span").html(count);
			self.printHTML(listArr,alias);			
    	}
    },
    //控制字母栏能否被选中
    isAbleCheck:function(){
    	var self=this;
    	var condition = this.getCondition();
    	var allIsExist=false;
    	$(self.searchAliasClass +' a').each(function(){
    		var alias=$(this).attr('data');
    		if(alias !='all'){
	    		if(!self.checkIsExist(condition,alias)){
	    			$(this).addClass(self.unableClass);
	    			$(this).attr(self.unableClass,'true');
	    		}else{
	    			$(this).removeClass(self.unableClass);
	    			$(this).removeAttr(self.unableClass);
	    			allIsExist=true;
	    		}
    		}
    	});
    	var obj=$(self.searchAliasClass +' a[data="all"]');
    	if(allIsExist){
    		obj.removeClass(self.unableClass);
    		obj.removeAttr(self.unableClass);
    	}else{
    		obj.addClass(self.unableClass);
    		obj.attr(self.unableClass,'true');
    	}
    },
    start:function(){
    	//============初始化相关===================
    	var self=this;
    	self.isAbleCheck();
    	//页面带条件时,初始化条件
    	if(typeof($(self.conditionClass).attr('data'))!='undefined'){
    		var arr=[];
    		var c=$(self.conditionClass).attr('data');
    		arr=c.split('#');
    		for(var key in arr){
    			var obj=$(self.conditionClass+' a[data-id="'+arr[key]+'"]');
    			obj.addClass(self.conditionCheckClass);
    			obj.siblings('a').removeClass(self.conditionCheckClass);
    		}
    		$(self.conditionClass).removeAttr('data');
    	}
    	
    	/*//初始选中的字母
    	$(self.searchAliasClass +' a').each(function(){
    		if(typeof($(self.searchAliasClass+' .check').html())!="undefined"){//假如初始有选中值
    			return false;
    		}
    		var alias=$(this).attr('data');
    		var condition =self.getCondition();
    		if(self.checkIsExist(condition,alias)){
    			$(self.searchAliasClass).find('a[data="'+alias+'"]').addClass(self.aliasCheckClass);
    			return false;
    		}		
    	});*/
    	if(self.getAlias() ==undefined){
    		$(self.searchAliasClass).find('a[data="all"]').addClass(self.aliasCheckClass);//默认选中全部
    	}
        self.showResult();
    	//==========条件点击触发=============
    	//条件部分点击
    	$(self.conditionClass).on('click','a',function(){
    		if(typeof($(this).attr('data-id'))!='undefined'){
    			$(this).addClass(self.conditionCheckClass);
    			$(this).siblings('a').removeClass(self.conditionCheckClass);
				
				var contidion=self.getCondition().join('#');
				var type=self.getType();
				contidion=contidion.replace('-','0000');
				var alias=self.getAlias();
				var url='/brands/';
				    url += alias ? 'alias_'+alias :'all';
					url += type ? '-type_'+type:'';
					url += encodeURIComponent(contidion ? '-c_'+contidion : '')+'/';    
				location.href=url;
    		}
    	});
    	//字母部分点击
    	$(self.searchAliasClass).on('click','a',function(){
    		if($(this).attr(self.unableClass)=='true'){
    			return false;
    		}
    		$(self.searchInputClass).find('#searchInput').val('请输入品牌名');
    		$(this).addClass(self.aliasCheckClass);
    		$(this).siblings('a').removeClass(self.aliasCheckClass);
    		self.showResult();
    	});
    	//=========输入框相关=========
		//输入框keyup触发
		$(self.searchInputClass).find('#searchInput').on('keyup',function(){
			self.inputSearch();
		});
		//输入框获取焦点
		$(self.searchInputClass).find('#searchInput').on('focus',function(){
			if($(this).val()=='请输入品牌名'){
				$(this).val("");
			}
		});
		$(self.searchInputClass).find('#searchInput').on('blur',function(){
			if($(this).val()==''){
				$(this).val("请输入品牌名");
			}
		});
    	//输入框点击搜索触发
    	$(self.searchInputClass).find('#searchButton').on('click',function(){
    		self.inputSearch();
    	});
		$(".all_con  div").scroll(function() {
			var alias = self.getAlias();
			var condition = self.getCondition();
			if(alias!='all' || condition !='')
				 return;
			self.listArrAllOffset++;
			if(self.listArrAllOffset<=90){
				var tmp=String.fromCharCode(self.listArrAllOffset);
			}else if(self.listArrAllOffset==91){
				var tmp='0-9';
			}else if(self.listArrAllOffset==92){
				var tmp='OTHER';
			}else{
				return ;
			}
			var list={};
			list[tmp]=self.listArrAll[tmp];
			self.printHTML(list,alias);
		});
    }    
};
$(function(){
	brandPage.start();
})