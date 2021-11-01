/*
 * 明星达人
 */
function clickStar(obj) {
	var link = $("#link");
	var param = link.data('param');
	var search = link.data('search');
	var url = link.data('url');
	var val = obj.getAttribute('data-sta');
	param = oPopStyles.dealParam(param, 'sta', val);
	param = search ? param.replace(/\/$/,'')+'/'+search : param+'/';
	location.href = url+param.replace(/^\//,'')+'#anchor';
}
var utilLabel={
    letterLoaded:[],//已经渲染过的
    curBrandHtml:[],
	brandLoaded:[],
	brandHtmlNew:[],
    BrandData:[],
    start:'',
    tipName:'明星达人',
    template:'',    //url的模板
    config:function(config){	//载入初始配置
		for(var key in config){
			this[''+key]=config[''+key];
		};
		//设置初始数据
		var self=this;
		var i=1;
    var o = [];
    $(".label_ca").each(function(idx) {
      $(".label_ca:eq(" + idx + ") div[class='items']").each(function(){
        var letter=$(this).find(".letter").html().toUpperCase();
        if(self.checkIsExist(letter) && !o[idx]){
          o[idx] = true;
          self.mouseenter($(this).find(".letter"));
          self.start=letter;
        }else if(letter=='OTHER' && !o[idx]){
          $(".label_ca:eq(" + idx + ")").parents("li").hide();
          $('.showbox').each(function(){
            var $self = $(this);
            var $parent=$self.parent('li');
            var selfWid=$self.outerWidth(true);
            var rightWidth=parseInt(document.body.offsetWidth) -parseInt($parent.offset().left);
            var leftWidth = $parent.offset().left;
            var liWid=$(".paixu li").width();
            if(selfWid > rightWidth){
              $self.css('left', -selfWid+liWid+1);
            }else if(selfWid > leftWidth){
              $self.css('left', '-1px');
            }
          });
        }
      });
    })
		$(".labellist .searInputbox").on('focus', function () {
			var prex=eval('/(搜索.*'+self.tipName+')/');
			var str=$(this).val();
			var isMatch=str.match(prex);
			if(isMatch){
				$(this).val("");
			} 
		})
	},
   //判断当前 字母下是否有数据
   checkIsExist:function(letter){
	  if(this.BrandData==null || typeof(this.BrandData[letter])=='undefined')
		  return false;
	  var a= this.BrandData[letter].length;
	  if(a >= 1){
		 return true; 
	  }else{
		 return false;
	  }
   },
  //当大写字母下没有品牌时
  showNoFilter:function (sel,filter){
  	   var html='<li class="txt" style="height:30px;width:100%"><p >暂无相关的'+this.tipName+'，请尝试用其他字母进行检索...</p></li>';
       sel.html(html);
 	   this.curBrandHtml[filter] = html;
  },
  //当鼠标放上去的时候
  mouseenter:function(obj){
	  var letter = $.trim(obj.html()).toUpperCase();//获取当前letter
	  if(letter=='HOT'){
	  	 var booleanStr="($.inArray(Number(brandList[i].id),this.HotBrands)>-1 )";
	  	 var on='hot_on';
	  }else{
	  	 var booleanStr='true';
	  	 var on='on';
	  }
	  this.inputShow(letter,false);
	  $(".labellist .searInputbox").blur();//失去焦点
	  var thisUL=$('.labellist').find('ul');//将被填充数据的ul
	  var brandList=this.BrandData[letter];
	  var brandHtmlArray = [];
	  obj.closest(".items").addClass(on).siblings().removeClass('on').end().siblings().removeClass('hot_on');
	  if (typeof this.letterLoaded[letter] == 'undefined') {
          // 如果当前字母下的ul里面为空，则通过js绘制品牌的html
          if(typeof(brandList)!='undefined' && brandList.length>0){
              for (var i in brandList) {
              if (brandList.hasOwnProperty(i) && eval(booleanStr)) {
                  brandHtmlArray.push(this.template
                      .replace('#brand_tid', brandList[i].id)
                      .replace('#title', brandList[i].name)
                      .replace(/#brand/g, brandList[i].name));
                  }
              }
              this.curBrandHtml[letter] = brandHtmlArray.join('');
              thisUL.html(brandHtmlArray.join(''));
          }else{
               this.showNoFilter(thisUL,letter);
          }            
          this.letterLoaded[letter] = true;
      } else {
    	  thisUL.html(this.curBrandHtml[letter]);//已渲染过，直接取值
      }
	  this.showBarY(thisUL,letter);
  },
  //搜索框输入时触发
  keyup:function(self){
      var curHoverLetter = self.attr('name');
      var key = $.trim(self.closest(".items").find(".letter").text());
      var sNoTrim=self.val();
      var s = $.trim(self.val()); //输入值
      var letter = s.substring(0, 1);
      var regO = /^[A-Za-z]$/;
      // var regCn = /^[\u4e00-\u9fa5]$/;
      // var letterCn = '';
      if (regO.test(letter)) {
          letter = letter.toUpperCase();
      } else if ( letter =="") {
          return;
      // } else if (regCn.test(letter)) {
      //   for (var i in this.BrandData) {
      //     if (letterCn) {
      //       continue;
      //     }
      //     for (var j = 0, len = this.BrandData[i].length; j < len; j++) {
      //       if (this.BrandData[i][j].name[0] == letter && !letterCn) {
      //         letterCn = letter;
      //         letter = this.BrandData[i][j].alias;
      //       }
      //     }
      //   }
      } else {
          letter = 'OTHER';
      }
      var booleanStr='true';
      this.inputShow(letter,true);
      var obj=$('.label_ca div[name="'+letter+'"]'); 
      obj.closest(".items").addClass('on').siblings('.items').removeClass('on');
      var brandList = this.BrandData[letter];
      var brandHtmlArray = [];
      var curUL =$('.labellist').find('ul');
      if(brandList){
          for (var i in brandList) {
            // if (letterCn) {
            //   if (brandList.hasOwnProperty(i) && brandList[i].name.indexOf(s) > -1 && eval(booleanStr) ) {
            //     brandHtmlArray.push(this.template
            //         .replace('#brand_tid', brandList[i].id)
            //         .replace('#title', brandList[i].name)
            //             .replace(/#brand/g, brandList[i].name));
            //   }
            // } else 
            if (brandList.hasOwnProperty(i) && brandList[i].pinyin.toLowerCase().indexOf(s.toLowerCase()) > -1 && eval(booleanStr) ) {
                brandHtmlArray.push(this.template
                    .replace('#brand_tid', brandList[i].id)
                    .replace('#title', brandList[i].name)
                        .replace(/#brand/g, brandList[i].name));
            }
          }
          curUL.html(brandHtmlArray.join(''));  
      }else{
          this.showNoFilter(curUL,s);
      }
    this.showBarY(obj,s);  
  },
  //处理输入框默认值
  inputShow:function(letter,isInput){
  	$(".labellist .searInputbox").prop('name',letter);
  	if(!isInput){
  		var tip=this.getTipSentence(letter);
  		$(".labellist .searInputbox").val(tip);
  	}	
  },
  //控制显示高度
  showBarY:function (sel,filter){  
      var boxSel = $(".label_ca").find('.contentHolder');
      var boxH = boxSel.height();
      var conH = boxSel.find('.content').height();
      var labellistH=0;
      if(conH<=0){
    	  var liNum = $(".label_ca").find(".labellist li").length;
    	  var x=Math.ceil(liNum/5);
    	  conH=x*30; //30为li的高度
      } 
      if(conH>=320){
    	  	labellistH=320+57;
    	  	var scrollH = boxSel.prop('scrollHeight');
            var barH = parseInt(boxH*boxH/scrollH,10);
            boxSel.scrollTop(0);
            boxSel.perfectScrollbar();
            boxSel.find('.ps-scrollbar-y-rail').show();
            boxSel.find(".ps-scrollbar-y").height(barH);
            boxSel.find(".ps-scrollbar-y").css("top",0);	    	
	  }else{
	  	labellistH=conH+57;
	  	boxSel.find('.ps-scrollbar-y-rail').hide();
	  }
      sel.closest(".showPro").height(labellistH+85);     
  },
  getTipSentence:function (key) {
  	  	return "搜索" + key + "开头的"+this.tipName;
  },
  
  //加载品牌
  brand_new:function(config){	//载入初始配置
	for(var key in config){
		this[''+key]=config[''+key];
	};
	var thisDIV=$('.brand_display');//将被填充数据的DIV
	var brandList=this.BrandData;
	var brandHtmlArray = [];
	var brandLetter;
		// 如果当前字母下的ul里面为空，则通过js绘制品牌的html
		if (typeof this.brandLoaded != true) {
			var count=1;
			for (var letter in brandList) {
				brandLetter = brandList[letter];
				for (var i in brandLetter) {
					if (brandLetter.hasOwnProperty(i)) {
						if( count != 1 ){
							brandHtmlArray.push(this.template
							.replace('#brand_tid', brandLetter[i].id)
							.replace('#title', brandLetter[i].name)
							.replace(/#brand/g, brandLetter[i].name)
							.replace('#borderTop','borderTop'));
						}else{
							brandHtmlArray.push(this.template
							.replace('#brand_tid', brandLetter[i].id)
							.replace('#title', brandLetter[i].name)
							.replace(/#brand/g, brandLetter[i].name));
						}
						count += 1;
					  }
				}
			}
            this.brandHtmlNew= brandHtmlArray.join('');
            thisDIV.html(brandHtmlArray.join(''));         
			this.brandLoaded = true; 
		} else {
			thisDIV.html(this.brandHtmlNew);//已渲染过，直接取值
      }
  }
  
};

