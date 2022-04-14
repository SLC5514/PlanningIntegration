var oPopStyles = {
	// 初始化
	'init':function(){},
	// 阻止冒泡方法
	'stopPropagation':function(e){
		var e = e || window.event;
		// W3C阻止冒泡方法
		if (e.stopPropagation) {
			e.stopPropagation();
		}
		// IE中阻止冒泡方法
		else {
			e.cancelBubble = true;
		}
	},
	// 阻止默认行为
	'stopPreventDefault':function(e){
		var e = e || window.event;
		// W3C阻止默认浏览器动作
		if ( e.preventDefault ) {
			e.preventDefault(); 
		}
		// IE中阻止默认动作
		else {
			window.event.returnValue = false; 
		}
		return false;
	},
	// 获取数组最大值
	'getMaxFromArray':function(arr){
		var _max = arr[0];
		for (var i=1,j=arr.length; i<j; i++) {
			if (_max < arr[i]) {
				_max = arr[i];
			}
		}
		return _max;
	},
	// 更多推荐
	'moreRecommend':function(){
		var $moreRec = $('.moreRec');	// 更多推荐
		var $toggle = $('.togglebtn');
		var $singleLine = $(".layer_dwid");	// 单行的高度
		var $layerblack = $(".layerblack"); // 推荐列表id
		var recLineCnt = $singleLine.length;
		var singleListH = $singleLine.outerHeight();
		var trueH = recLineCnt*singleListH;
		// 收起|展开
		$toggle.on('click', function(event){
			var $self = $(this);
			var _text = $self.text();
			if (_text == '收起') {
				$self.text('展开');
				$moreRec.removeClass('down');
				$layerblack.animate({height:0},300);
			} else {
				$self.text('收起');
				$layerblack.animate({height:'245px'},300);
			}
		});
		// 更多推荐
		$moreRec.on('click', function(){
			var $self = $(this);
			if($layerblack.height()==245){
				$layerblack.animate({height:trueH},300);
				$self.addClass('down');
				$toggle.text('收起');
			}else{
				$layerblack.animate({height:'245px'},300);
				$self.removeClass('down');
				$toggle.text('收起');
			}
		});
		// 推荐列表Li显示效果	on不支持hover绑定 但可以做模拟
		var $singleLi = $singleLine.find('li');
		$singleLi.hover(function(){
			var $self = $(this);
			var $showdiv = $self.find(".showdiv");
				var $togg1 = $self.find(".togg1");
				var $togg2 = $self.find(".togg2");
				if ($layerblack.height() > 245) {
					$togg2.show();
					$togg1.hide();
				} else {
					$togg1.show();
					$togg2.hide();
				}
				$showdiv.stop().fadeIn(100);
		},function(){
			var $showdiv = $(this).find(".showdiv");
			$showdiv.stop().fadeOut(200);
		});
	},
	// 大小图处理
	'num':0,
	'timer':'',
	'switchImage':function(){
		var orgObj = this;
		var $numList = $('.numlist li');
		var $imgList = $('.bigbox img');
		// 设置默认值
		$numList.eq(orgObj.num).addClass('on');
		$imgList.eq(orgObj.num).css('display', 'block');
		orgObj._setInterval($numList,$imgList);
		$numList.hover(function(){
			var $self = $(this);
			var index = $self.index();
			$self.addClass('on').siblings().removeClass('on');
			$imgList.eq(index).stop(true).fadeIn(400).siblings('img').stop(true).fadeOut(100);
			orgObj.num = index;
			clearInterval(orgObj.timer);
		},function(){
			orgObj._setInterval($numList,$imgList);
		});
		$imgList.hover(function(){
			clearInterval(orgObj.timer);
		},function(){
			orgObj._setInterval($numList,$imgList);
		});
	},
	'_setInterval':function($numList,$imgList){
		var orgObj = this;
		if (orgObj.timer) {
			clearInterval(orgObj.timer);
		}
		orgObj.timer = setInterval(function(){
			orgObj.loopPlay($numList,$imgList);
		}, 3000);
	},
	// 循环显示播放
	'loopPlay':function($numList,$imgList){
		this.num++;
		if(this.num > $numList.length-1){
			this.num=0
		}
		$numList.eq(this.num).addClass('on').siblings().removeClass('on');
		$imgList.eq(this.num).stop(true).fadeIn(400).siblings('img').stop(true).hide();
	},
	// 预加载且设置图片的最大宽高
	'preLoader':function(orgObj, maxW, maxH){
		var oImage = new Image();
		oImage.src = orgObj.src;
		oImage.onload = this.setImgMaxWH(oImage, orgObj, maxW, maxH);
	},
	// 设置图片的最大宽高
	'setImgMaxWH':function(preObj, orgObj, maxW, maxH){
		var preImgW = preObj.width;
		var preImgH = preObj.height;
		var orgImgW = orgObj.width;
		var orgImgh = orgObj.height;
		//计算图片最大宽度
		if ((preImgW > maxW) && (preImgW > preImgH)) {
			orgObj.width = maxW;
			orgObj.height = preImgH * (maxW / preImgW);
			preImgW = orgObj.width;
			preImgH = orgObj.height;
			if (preImgH > maxH) {
				orgObj.height = maxH;
				orgObj.width = preImgW * (maxH / preImgH);
			}
		}
		//计算图片最大高度
		if ((preImgH > maxH) && (preImgH > preImgW)) {
			orgObj.height = maxH;
			orgObj.width = preImgW * (maxH / preImgH);
			preImgW = orgObj.width;
			preImgH = orgObj.height;
			if (preImgW > maxW) {
				orgObj.width = maxW;
				orgObj.height = preImgH * (maxW / preImgW);
			}
		}
		if ((preImgW > maxW) && (preImgW == preImgH)) {
			orgObj.width = maxW;
			orgObj.height = preImgH * (maxW / preImgW);
			preImgW = orgObj.width;
			preImgH = orgObj.height;
			if (preImgH > maxH) {
				orgObj.height = maxH;
				orgObj.width = preImgW * (maxH / preImgH);
			}
		}
		if ((preImgW < maxW || preImgW == maxW) && (preImgH < maxH || preImgH == maxH)) {
			orgObj.width = preImgW;
			orgObj.height = preImgH;
		}
	},
	// 放大镜处理
	'magnifierClick':function(){
		var orgObj = this;
		var $bigImg = $('.bigImg');
		var $magnifier = $('.enlarge');
		var $bgBlack = $('.layer_bgBlack');
		var $image = $("#bigImages");
		var $numList = $('.numlist li');
		var $imgList = $('.bigbox img');
		$magnifier.hover(function(){
			clearInterval(orgObj.timer);
		},function(){
			orgObj._setInterval($numList,$imgList);
		});
		$magnifier.click(function() {
			$('.layer_bgBlack').show();
			$('.bigImg').show().find('.layerBigPic').attr('src', $('.bigbox img:visible').attr('src'));
			orgObj.preLoader($image[0], 658, 1038);
			$image.addClass("szoom");
			orgObj.initBigImg();
		});
		/*关闭大图弹层*/
		var $layerClose = $('.layerClose');
		$layerClose.click(function() {
			$bgBlack.hide();
			$bigImg.hide();
			orgObj._setInterval($numList,$imgList);
		});
		if ($image.length) {
			var oImg = $image[0];
			function wheelScroll(ev) {
				var ev = ev || window.event;
				var zoom = parseInt(oImg.style.zoom, 10) || 100;
				var _image = new Image();
				var imgW = oImg.width;
				_image.src = oImg.src;
				if (ev.wheelDelta) {
					if (ev.wheelDelta > 0) {
						$(oImg).addClass("bzoom").removeClass('szoom');
					} else {
						$(oImg).addClass("szoom").removeClass('bzoom');
					}
				}
				// ff 
				else {
					if (-ev.detail > 0) {
						$(oImg).addClass("bzoom").removeClass('szoom');
					} else {
						$(oImg).addClass("szoom").removeClass('bzoom');
					}
				}
				zoom += ev.wheelDelta ? (ev.wheelDelta / 12) : (-ev.detail);
				imgW = imgW * (zoom / 100);
				if (zoom > 50 && zoom <= 121) {
					oImg.style.cssText = '-moz-transform:scale(' + zoom / 100 + ');-moz-transform-origin: center top 0px;';
					oImg.style.zoom = zoom + "%"; //ff，opera不支持zoom发大缩小
					if (ev.preventDefault) {
						ev.preventDefault();
					}
					ev.returnValue = false;
				}
				return false;
			}
			if (oImg.addEventListener) {
			  /** DOMMouseScroll is for mozilla. */
			  oImg.addEventListener('DOMMouseScroll', wheelScroll, false);
			}
			oImg.onmousewheel = wheelScroll;
			oImg.onmousedown = function() {
			  if ($image.hasClass("bzoom")) {
				$image.addClass("dragIcon").removeClass('bzoom');
			  } else if ($image.hasClass("szoom")) {
				$image.addClass("dragIcon").removeClass('szoom');
			  }
			  oImg.onmouseup = function() {
				$image.addClass("szoom").removeClass('dragIcon');
			  };
			};
			$("#draggable").draggable();
		}
	},
	'initBigImg':function(){
		var $bigImgBox = $("#draggable");
		$bigImgBox.css({
			"left": "50%",
			"margin-left": "-600px",
			"top": 0
		});
		$bigImgBox.find("img").css({
			'-moz-transform': 'scale(1)',
			"zoom": "100%"
		});
	},
	// 弹出层搜索结果左右切换-键盘左右键，细节切换-键盘上下键，关闭弹出层-键盘ESC
	'baseKeyControl':function(event){
		var event = event ? event : window.event;
		switch(event.keyCode){
			case 27:	// esc
				if(($(".collect_layer").is(":hidden") || $(".collect_layer").length <= 0) && $(".bigImg").is(":hidden")){
					$(".pop_layer_page .pop_close").trigger("click");
				} 
				// 关闭大图弹层
				else if(!$(".bigImg").is(":hidden")){
					$(".pop_layer_page .layerClose").trigger("click");
				} 
				else {
					return false;
				}
				break;
			case 37:	// left
				$(".pop_layer_page .leftBtn").trigger("click");
				break;
			case 39:	// right
				$(".pop_layer_page .rightBtn").trigger("click");
				break;
			case 38:	// up
				$(".pop_layer_page .detailBox .prevbtn").trigger("click");
				return false;
			case 40:	// down
				$(".pop_layer_page .detailBox .nextbtn").trigger("click");
				return false;
		}
	},
	'waterfall':function(){
		var orgObj = this;
		var lieshu = 3;
		var J_GetMoreResult = $("#J_GetMoreResult");
		var aGrid = J_GetMoreResult.find(".grid");
		var aMax = [];
		if (aGrid.length <= lieshu) {
			for (var i = 0; i <= aGrid.length - 1; i++) {
				aGrid.eq(i).css({
				"left": i % lieshu * 78,
				"top": 0
				}).addClass('curGrid');
				aMax.push(aGrid.eq(i).outerHeight(true));
			}
			J_GetMoreResult.height(orgObj.getMaxFromArray(aMax));
			return false;
		}
		for (var i = 0; i <= aGrid.length - 1; i++) {
			var shangmianhezigao = 0;
			for (var j = i - lieshu; j >= 0; j = j - lieshu) {
				shangmianhezigao = shangmianhezigao + aGrid.eq(j).outerHeight(true);
				aMax.push(shangmianhezigao + aGrid.eq(i).outerHeight(true));
			}
			aGrid.eq(i).css({
			"left": i % lieshu * 78,
			"top": shangmianhezigao
			}).addClass('curGrid');
		}
		J_GetMoreResult.height(orgObj.getMaxFromArray(aMax));
	},
	'share':function(){
		var $layer = $(".layerLeft_top .fl a");
		var w1 = $layer.eq(0).outerWidth();
		var w2 = $layer.eq(1).outerWidth();
		var w=w1+w2+20;
		$(".share_btn").css('left',w);
		$(".share_btn").hover(function() {
			$(this).stop().animate({width: '260px',backgroundColor:'#fff'}, 300);
		}, function() {
			$(this).stop().animate({width: '40px',backgroundColor:'#ccc'}, 300);
		});
	},
	'run':function(){
		this.init();
		// 文档绑定按键操作
		$(document).on('keydown', this.baseKeyControl);
		this.switchImage();
		this.moreRecommend();
		this.magnifierClick();
		this.share();
		//this.waterfall();
	}
};

$(function(){
	oPopStyles.run();
});