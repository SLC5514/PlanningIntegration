/*
	#author		shuzhan
	#date 		2017/11/09
*/
require.config({
	paths: {
		"lazyload": ["lib/jquery.lazyload.min"],
		"jquery_mousewheel": ["lib/jquery.mousewheel"],
		'perfectScrollbar': ["lib/perfect-scrollbar"]
	},
	shim: {
		"lazyload": {
			deps: ["jquery"],
			exports: "lazyload"
		},
		"jquery_mousewheel": {
			deps: ["jquery"],
			exports: "jquery_mousewheel"
		},
		'perfectScrollbar': {
			deps: ["jquery", "jquery_mousewheel"],
			exports: "perfectScrollbar"
		}
	}
});

require(["jquery", "general", "perfectScrollbar", "jquery_mousewheel", "lazyload"], function (jquery, general, perfectScrollbar) {
	$(function () {
		var def = {
            'loadingEl': $('.js-loading-div'),
            'loadingBgEl': $('.js-bg-div'),
            'tipEl': $('.tip-box'),
            'timeout': null,
			'params': general.fn.getLocationParameter(),
			"cond": $('#cond'),	//数据
			'is_first_list': true,		//是不是新的一页
			'aco': '',
			'con': '',
			'list_num': 1,
			'list_data': [],		//列表数据
			'is_add_dom': true,	//一页还有数据
			'pie_exsist': false,	//饼图是否存在
			'list_more': true,
			'cond_data': {
				'params': '',
				'key': ''
			},
			'tabchange': ''
		};
		def.cond_data.params = def.cond.data('params') || '';
		def.cond_data.key = def.cond.data('key') || '';

		if (def.params.tabchange === 'pattern' || !def.params.tabchange) {
			def.tabchange = 'pattern';
			$('.js-tab-section>a[data-tabchange=pattern]').addClass('on');
			$('.js-collect-list').show();
			//获取图案列表
			getPatternList();
		} else if (def.params.tabchange === 'trend') {
			def.tabchange = 'trends';
			$('.js-tab-section>a[data-tabchange=trend]').addClass('on');
			$('.js-trends-list').show();
			//获取趋势列表
			getPatternList();
		}

		//获取列表
		function getPatternList() {
			def.is_add_dom = true;
			general.fn.subAjax({
				'url': '/collect/getlist/',
				data: {
					change: def.tabchange,
					page: def.list_num
				},
				'ctp': 'application/x-www-form-urlencoded',
				'success': function (data) {
					$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
					data = data || {};
					var list = data.data.list || [];
					var total = data.info.total || 0;
					var pagesize = data.info.pagesize || 0;
					var totalpage = Math.ceil(total / pagesize);
					if (def.list_num >= totalpage) {
						$('.js-list-more').hide();
						def.list_more = false;
					}
					def.is_add_dom = false;
					if (def.tabchange === 'pattern') {
						setPatternList(list);
					} else if (def.tabchange === 'trends') {
						setTrendsList(list);
					}
				},
				'error': function (data) {
					if (data.code == 1001) {
						$('.js-list-more').hide();
					}
					$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
				}
			})
		}

		//写入图案列表
		function setPatternList(list) {
			list = list || [];
			if (list.length > 0) {
				var _html = '';
				for (var i = 0; len = list.length, i < len; i++) {
					var cover = list[i].cover || '';		//图片
					var id = list[i].id || '';
					var index = list[i].index || 0;
					var t = list[i].t || '';
					var s_p = list[i].sPatternContent || [];		//字段
					var dCreateTime = list[i].dCreateTime || '';	//时间
					var colorProportion = list[i].colorProportion || [];	//色系
					var iBrand = list[i].brand || {};	//品牌名
					var collect_status = list[i].collect_status || false;

					_html += '<li data-id="' + id + '" data-t="' + t + '" data-index="' + index + '"><a href="javascript:void(0);" title="">';
					if (i < 12) {
						_html += '<img src="' + cover + '" alt="" />';
					} else {
						_html += '<img src="' + '/global/images/common/reference150-150.gif' + '" data-original="' + cover + '" alt="" />';
					}

					if (colorProportion && colorProportion.length > 0) {
						_html += '<div class="dis-posion">';
					} else {
						_html += '<div class="dis-posion" style="bottom:0px;">';
					}

					if (iBrand.name) {
						_html += '<h3 class="js-h-link" data-url="' + iBrand.link + '">' + iBrand.name + '</h3>';
					}

					if (s_p && s_p.length > 0) {
						_html += '<div class="list-words js-list-words">';
						for (var j = 0; j < s_p.length; j++) {
							_html += '<p data-url="' + s_p[j]['link'] + '">' + s_p[j]['name'] + '</p>';
						}
						_html += '</div>';
					}

					_html += '<p><span class="icon"></span>' + dCreateTime + '</p></div>';
					if (collect_status) {
						_html += '<div class="collect-data js-collect-data on"></div>';
					} else {
						_html += '<div class="collect-data js-collect-data"></div>'
					}
					_html += '</a>'

					if (colorProportion && colorProportion.length > 0) {
						_html += '<div class="color-bott clear">';
						//色条
						for (var k = 0; k < colorProportion.length; k++) {
							_html += '<a href="javascript:void(0);" title="潘通参考值：' + colorProportion[k].pantonColorNumber + '" style="background: ' + (colorProportion[k].Color || '') + '; width:' + colorProportion[k].Percent + '%;"></a>';
						}
						_html += '</div>';
					}
					_html += '</li>';
				}

				$('.js-collect-list').append(_html);
				$('.js-collect-list img').lazyload({ effect: "fadeIn" });
			}
		}

        // 渲染趋势列表
        function setTrendsList(list) {
            list = list || [];
            if (list.length > 0) {
                var _html = '', len = list.length;
                for (var i = 0; i < len; i++) {
                    var labels = list[i].labels || '';
                    _html += '<li data-id="' + list[i].id + '" data-t="' + list[i].t + '" data-col="' + list[i].col + '" data-handle="' + (list[i].collect_status ? 'cancel' : 'join') + '">' +
                        '<div class="collect-data js-collect-data ' + (list[i].collect_status ? 'on' : '') + '"></div>' +
                        '<a data-href="' + list[i].detail_url + '" title="' + list[i].title + '">';
						if (i < 12) {
							_html += '<img src="' + list[i].cover + '" alt="' + list[i].title + '">';
						} else {
							_html += '<img src="' + general.def.img_path + '/global/images/common/reference150-150.gif" data-original="' + list[i].cover + '" alt="' + list[i].title + '">';
						}
                    _html += '</a>' +
                        '<div class="text-down">' +
                        '<a data-href="' + list[i].detail_url + '">' +
                        '<p class="time clear">' +
                        '<span class="fl">浏览（' + list[i].view + '）</span>' +
                        '<span class="fr">' + list[i].publish_time + '</span>' +
                        '</p>' +
                        '<p class="title" title="' + list[i].title + '">' + list[i].title + '</p>' +
                        '</a>' +
                        '<div class="label">';
                    for (var j = 0; j < labels.length; j++) {
                        _html += '<a href="' + labels[j].lLink + '" title="' + labels[j].name + '" target="_blank">' + labels[j].name + '</a>'
                    }
                    _html += '</div></div></li>';
                }
				$('.js-trends-list').append(_html);
                $('.js-trends-list img').lazyload({ effect: "fadeIn" });
                $(window).scrollTop($(window).scrollTop() + 1);
            }
        }

        // 页面加载控制
        function loadingToggle(type) {
            if (type === true) {
                def.loadingEl.fadeIn(200);
                def.loadingBgEl.fadeIn(400);
            } else if (type === false) {
                def.loadingEl.fadeOut(200);
                def.loadingBgEl.fadeOut(400);
            } else {
                def.loadingEl.fadeToggle(200);
                def.loadingBgEl.fadeToggle(400);
            }
        }

        // tip提示控制
        function tipToggle(msg, type) {
            if (type) {
                def.tipEl.addClass('on');
            } else {
                def.tipEl.removeClass('on');
            }
            def.tipEl.text(msg).fadeIn(200);
            clearTimeout(def.timeout);
            def.timeout = setTimeout(function () {
                def.tipEl.fadeOut(200);
                clearTimeout(def.timeout);
            }, 2000);
        }

		//页面滚动加载
		$(window).on('scroll', function () {
			if (def.is_add_dom === false && def.list_more) {
				var content_h = $(".js-list-more").offset().top;
				var scroll_top = $(window).scrollTop();
				var w_h = document.documentElement.clientHeight || document.body.clientHeight;
				if (scroll_top + w_h > content_h) {
					def.list_num++;
					getPatternList();		//加载下一屏
				}
			}
		});

		//-----------------------------------------事件绑定--------------------------------------------      
		//收藏列表点击
		$('.js-design-div').on('click', 'li', function (e) {
			var _id = $(this).data('id') || '';
			var _t = $(this).data('t') || '';
			var frame_url = '/patternlibrary/detail/?id=' + _id + '&t=' + _t + '&origin_type=3';
			$('.js-detail-frame').find('iframe').attr('src', frame_url);
			$('.js-detail-frame').fadeIn();
			$('body').addClass('over-hidden');
		});

		//收藏列表点击
		$('.js-collect-list').on('click', 'li', function (e) {
			if (userType == 'GENERAL') {
				$('.js-general-user-info-fixbox').slideDown();
				return false;
			}

			var _id = $(this).data('id') || '';
			var _t = $(this).data('t') || '';
			var frame_url = '/patternlibrary/detail/?params=' + def.cond_data.params + '&key=' + def.cond_data.key + '&id=' + _id + '&t=' + _t + '&origin_type=4';
			$('.js-detail-frame').find('iframe').attr('src', frame_url);
			$('.js-detail-frame').fadeIn();
			$('body').addClass('over-hidden');
		});

		$('.js-collect-list').on('click', '.js-list-words>p, .js-h-link', function (e) {
			general.fn.stopBubble(e);
			var _url = $(this).data('url') || '';
			if (_url != '') {
				window.location.href = _url;
			}
		});

		//图案列表hover
		$('.js-collect-list').on('mouseenter mouseleave', 'li', function (e) {
			var e = e || window.event;
			if (e.type == 'mouseenter') {
				$(this).find('.dis-posion').stop(true).slideDown(200);
				$(this).find(".collect-data").show();
				$(this).find('.dis-posion').animate({
					'opacity': 1
				}, 150, 'linear');
			} else {
				$(this).find('.dis-posion').stop(true).slideUp(200);
				$(this).find(".collect-data").hide();
				$(this).find('.dis-posion').animate({
					'opacity': 0.5
				}, 150, 'linear');
			}
		});

		//无权限提示关闭
		$('.js-general-user-info-fixbox button').on('click', function (e) {
			$(this).parents('.js-general-user-info-fixbox').slideUp(200);
		});

        // 跳转详情
        $('.js-trends-list').on('click', 'a[data-href]', function () {
            if (userType === 'GENERAL') {
                $('.js-trend-prompt').slideDown();
            } else {
                var data = $(this).parents('li').data();
                var frame_url = '/trendspattern/detail/?id=' + data.id + '&t=' + data.t + '&col=' + (data.col || '')/*  + '&refresh=1' */;
                window.open(frame_url);
            }
        })

        // 最新趋势 普通用户提示
        $('.js-trend-prompt').on('click', '.close', function () {
            $('.js-trend-prompt').slideUp(200);
        })

	})
})