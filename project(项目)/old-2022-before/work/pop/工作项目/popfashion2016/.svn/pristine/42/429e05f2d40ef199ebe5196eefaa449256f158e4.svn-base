$(function(){
	// 品牌
	var bpos = $('input[name="hidd"]').data('bpos');
	var col = $('input[name="hidd"]').data('col');
  var columnId = $('#link').attr('data-col');
	if(!bpos) {
		bpos = '';
	}
	if(typeof(Brand)!="undefined"){
	    utilBrand.config({
	        	start:'STAR',//初始值
	        	BrandData:Brand,//所有按首字母为key的品牌
	        	sMarketHotPosition:bpos,
            tipName:'品牌',
	        	HotBrands:eval($("#HotBrandIds").html()),
	        	template:'<li><a href="javascript:void(0);" onclick="clickJump(this, '+columnId+')" data-bra="#brand_tid" title="#title">#brand</a></li>'
	    });
      
      $('.brands_ca').find('.letter').on('mouseenter', function (e) {
        var idx = $(this).parent().index();
        $('.brands_ca').each(function(i, v) {
          utilBrand.mouseenter($(v).find('.items:eq(' + idx + ')').find('.letter'));
        })
      });
      function OSnow(){
        var agent = navigator.userAgent.toLowerCase();
        var isMac = /macintosh|mac os x/i.test(navigator.userAgent);
        if (agent.indexOf("win32") >= 0 || agent.indexOf("wow32") >= 0 || agent.indexOf("win64") >= 0 || agent.indexOf("wow64") >= 0) {
          $('.showPro div.inputBox').css({width: '670px'});
        }
      }
      OSnow();
	}
	// 书名
	if(typeof(BookName) != 'undefined') {
		var _BookName = BookName[col];
		var share = $('input[name="hidd"]').data('share');
	    if(typeof(_BookName)!="undefined"){
	    	if($.inArray(parseInt(col), [71, 72, 70, 113, 131]) != -1 && share) {
	    		utilBrand.config({
		        	start:'0-9',//初始值
		        	BrandData:_BookName,//所有按首字母为key的书名
					tipName:'书名',
		        	template:'<li><a href="javascript:void(0);" onclick="clickJump(this, '+col+')" data-bra="#brand" data-book="1" title="#title">#brand</a></li>'
		    	});	
	    	}else {
	    		utilBrand.brand_new({
		        	start:'0-9',//初始值
		        	BrandData:_BookName,//所有按首字母为key的书名
					tipName:'书名',
		        	template:'<a href="javascript:void(0);" onclick="clickJump(this, '+col+')" data-bra="#brand" title="#title" data-book="1" class="clickme #borderTop" style="font-size:12px;">#brand</a>'
		    	});
	    	}
		       
		    $('.brands_ca').find('.letter').on('mouseenter', function (e) {
		    	utilBrand.mouseenter($(this));
		    });
		    
		    $(".searInputbox").on('keyup', function () {
		        utilBrand.keyup($(this), 'book');
		    });
		}
	}
	// 明星/达人
	var sStarLabel = $('input[name="hidd"]').data('star');
	if(typeof(sStarLabel) != "undefined" && sStarLabel != ''){
    utilLabel.config({
      BrandData:sStarLabel,
      template:'<li><a href="javascript:void(0);" data-sta="#brand_tid" title="#title">#brand</a></li>'
    });
    
    $('.label_ca').find('.letter').on('mouseenter', function (e) {
      var idx = $(this).parent().index();
      $('.label_ca').each(function(i, v) {
        utilLabel.mouseenter($(v).find('.items:eq(' + idx + ') .letter'));
      })
    });
    
    $(".labellist .searInputbox").on('keyup', function () {
      utilLabel.keyup($(this));
    });
	}
	// 地区
	var link = $("#link");
	var ds = link.data('ds');
	if(ds!=undefined && ds!=""){
		var _col=link.data("col")+"_"+ds;
	}else{
		var _col=link.data("col");
	}
	if(typeof(RegionAction) != 'undefined') {
		RegionAction.start({
			regionData:regionFromSolr[_col],
			hrefTemplate:'javascript:void(0)'//地区缓存数据
		});
	}	

	
	
	/*右侧排序*/
	$(".js-screen-category").on('mouseenter mouseleave', 'li' ,function(e){
		if(e.type == 'mouseenter'){
			$(this).addClass('hover').find('.showbox').stop().css("visibility","visible");
		} else{
			$(this).removeClass('hover').find('.showbox').stop().css("visibility","hidden");
			$(this).find(".js-filter-list").show().siblings("ul").hide();
			$(this).find(".js-search-brand-area").val("搜索热门品牌名").blur();
		}
	});
	 var curl = window.location.href;
	$(".showbox").on('click','.js-page-select a',function(){
        var link = $("#link");
        var param = link.data('param');
        var search = link.data('search');
        var url = link.data('url');
        var val = $(this).attr('data-sta');
        param = oPopStyles.dealParam(param, 'sta', val);
        var disNum = curl.split('dis_')[1];
    	disNum = parseFloat(disNum) || 1;
    	var re = /dis\_[0-9]{1,}/;
        if(curl.indexOf('dis_')==-1){        	
    		param = search ? param.replace(/\/$/,'')+'-dis_'+disNum+'/'+search : param+'-dis_'+disNum+'/';        	
        }else{
        	param = search ? param.replace(/\/$/,'')+'/'+search : param+'/';
	        param = param.replace(re, 'dis_'+disNum);
        }
        location.href = url+param.replace(/^\//,'')+'#anchor';
    });    
});