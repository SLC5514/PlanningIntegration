(function(doc, win) {
	var docEl = doc.documentElement,
		resizeEvt = "orientationchange" in window ? "orientationchange" : "resize",
		recalc = function() {
			var clientWidth = docEl.clientWidth;
			if (!clientWidth) return;
			docEl.style.fontSize = clientWidth / 7.5 + "px";
		};
	if (!doc.addEventListener) return;
	win.addEventListener(resizeEvt, recalc, false);
	doc.addEventListener("DOMContentLoaded", recalc, false);
})(document, window);

$(function() {
	// H5头部导航
	var nav_show = false,
		nav_slide = false;
	function navBoxhide() {
		// 隐藏列表
		if (nav_show) {
			return;
		}
		nav_show = true;

		$(".js-nav-show").animate(
			{
				left: "-70%"
			},
			200,
			function() {
				$(".js-web-downlist1")
					.hide()
					.siblings(".js-web-downlist")
					.show();
				$(".js-nav-show").hide();
				nav_show = false; // 恢复可操作
			}
		);
		$(".js-view-contain, .js-h5-header").animate(
			{
				left: "0%"
			},
			200
		);
	}
	// 按钮显示列表
	$(".js-web-downlist").on("click", function() {
		if (nav_show) {
			return;
		}
		nav_show = true;
		$(".js-nav-show")
			.show()
			.animate(
				{
					left: "0%"
				},
				200,
				function() {
					$(".js-web-downlist")
						.hide()
						.siblings(".js-web-downlist1")
						.show();
					nav_show = false; // 恢复可操作
				}
			);
		$(".js-view-contain, .js-h5-header").animate(
			{
				left: "70%"
			},
			200
		);
	});
	// 按钮隐藏列表
	$(".js-web-downlist1").on("click", function() {
		navBoxhide();
	});

	// 下载切换
	$(".js-download-tab").on("click", function() {
		$(this)
			.addClass("on")
			.siblings()
			.removeClass("on");
		$(".js-download-tabcon>.item")
			.eq($(this).data().index)
			.addClass("on")
			.siblings()
			.removeClass("on");
	});
	// 显示弹窗
	$(".js-show-download-alert").on("click", function() {
		$(".js-download-alert-bg").show();
		$(".js-download-alert").show();
	});
	// 关闭弹窗
	$(".js-download-alert-close").on("click", function() {
		$(".js-download-alert-bg").hide();
		$(".js-download-alert").hide();
	});

	// 滚动
	var winH = $(window).height();
  setTimeout(function() {
		scrollAnitFn($(".js-scrolltop1"), 0);
  }, 500);
	window.onscroll = function() {
		var scrollTop = $(window).scrollTop();
		scrollAnitFn($(".js-scrolltop1"), scrollTop);
		scrollAnitFn($(".js-scrolltop2"), scrollTop);
		scrollAnitFn($(".js-scrolltop3"), scrollTop);
		scrollAnitFn($(".js-scrolltop4"), scrollTop);
	};
	// 封装
	function scrollAnitFn(el, scrollTop) {
		var offsetTop = el.offset().top,
			elH = el.height();
		if (offsetTop < winH && offsetTop < offsetTop + elH - winH) {
			el.addClass("anit-moveup");
		} else {
			if (
				scrollTop > offsetTop - winH * 0.7 &&
				scrollTop < offsetTop + elH - winH * 0.2
			) {
				el.addClass("anit-moveup");
			}
		}
	}

	// H5
	$(".js-download-app").on("click", function() {
    handleFunc();
	});

	function handleFunc() {
		var d1 = new Date().getTime();
		if (isAndroidIos()) {
			if (openApp("trend://poptrend.app")) {
				openApp("trend://poptrend.app");
			} else {
				var timer = setInterval(function() {
					var d2 = new Date().getTime();
					if (d2 - d1 < 3000 && d2 - d1 > 2000) {
						window.location.href =
							"https://android.myapp.com/myapp/detail.htm?apkName=com.pop136.trend";
					}
					if (d2 - d1 >= 3000) {
						clearInterval(timer);
					}
				}, 1000);
			}
		} else {
			if (isWeiBo()) {
				$(".js-browser-visit-box").show();
				return;
			}
			if (isWeiXin()) {
				window.location.href =
					"https://apps.apple.com/cn/app/pop%E8%B6%8B%E5%8A%BF-%E6%97%B6%E5%B0%9A%E8%AE%BE%E8%AE%A1%E5%B8%88%E4%BF%A1%E8%B5%96%E7%9A%84%E6%B5%81%E8%A1%8C%E8%B6%8B%E5%8A%BF%E8%B5%84%E8%AE%AF%E5%B9%B3%E5%8F%B0/id1476036454";
				return;
			}
			// iOS不支持iframe打开APP
			window.location.href = "trend://poptrend.app";
			var timer1 = setTimeout(function() {
				window.location.href =
					"https://apps.apple.com/cn/app/pop%E8%B6%8B%E5%8A%BF-%E6%97%B6%E5%B0%9A%E8%AE%BE%E8%AE%A1%E5%B8%88%E4%BF%A1%E8%B5%96%E7%9A%84%E6%B5%81%E8%A1%8C%E8%B6%8B%E5%8A%BF%E8%B5%84%E8%AE%AF%E5%B9%B3%E5%8F%B0/id1476036454";
			}, 2000);
			$(document).on("visibilitychange webkitvisibilitychange", function() {
				var tag = document.hidden || document.webkitHidden;
				if (tag) {
					clearTimeout(timer1);
				}
			});
		}
	}

	// 是否是安卓
	function isAndroidIos() {
		var u = navigator.userAgent;
		var isAndroid = u.indexOf("Android") > -1 || u.indexOf("Linux") > -1; //android终端或者uc浏览器
		return isAndroid == true ? true : false;
	}

	// 是否是微信
	function isWeiXin() {
		if (/MicroMessenger/gi.test(navigator.userAgent)) {
			return true;
		} else {
			return false;
		}
	}

	// 是否是微博
	function isWeiBo() {
		var ua = navigator.userAgent.toLowerCase();
		if (ua.match(/WeiBo/i) == "weibo") {
			return true;
		} else {
			return false;
		}
	}

	// 打开APP
	function openApp(src) {
		// 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
		// 否则打开a标签的href链接
		var ifr = document.createElement("iframe");
		ifr.src = src;
		ifr.style.display = "none";
		document.body.appendChild(ifr);
		window.setTimeout(function() {
			document.body.removeChild(ifr);
		}, 2000);
	}
});
