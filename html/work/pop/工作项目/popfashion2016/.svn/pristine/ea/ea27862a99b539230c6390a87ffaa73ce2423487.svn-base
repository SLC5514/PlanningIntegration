$(function() {
    var isH5 = screen.width <= 750;
    var isBV = $('.js-report-book-list-box').width() > 1500;
    var def = {
        txtW: isH5 ? ((screen.width / 375) * 20).toFixed(4) * 13.75 : (isBV ? 290 : 240),
        book_id: window.location.pathname.match(/\/details\/reportbook\/(\d+)\//)[1],
        report_id: general.fn.getLocationParameter().report_id
    };
    var txtSize = def.txtW * (isH5 ? 5 : 6);
    $('.js-cut-title').text(cutByWidth($('.js-cut-title').text(), def.txtW - 5, 26));
    $('.js-cut-info').text(cutByWidth($('.js-cut-info').text(), txtSize, 12));
    if (isH5 && $('.js-cut-info').text().indexOf('...') != -1) {
        $('.js-more').addClass('in');
        $('.js-more').on('click', function() {
            if ($(this).hasClass('on')) {
                $(this).removeClass('on').html('展开全文<i></i>');
                $('.js-cut-info').show().next().hide();
            } else {
                $(this).addClass('on').html('收起全文<i></i>');
                $('.js-cut-info').hide().next().show();
            }
        });
    }
    var bookListBox = $('.js-book-list');
    var bookListItem = bookListBox.children();
    var litop = isBV ? 40 : 20;
    $('.js-book-list').scrollTop(0);
    for (var i = 0; i < bookListItem.length; i++) {
        var itemEl = $(bookListItem[i]);
        var listBox = itemEl.find('ul');
        var listItems = listBox.children();
        for (var j = 0; j < listItems.length; j++) {
            var item = $(listItems[j]);
            if (item.data().id == def.report_id) {
                item.addClass('on');
                if (isH5) {
                    var t = item.parents('li').offset().top + ((screen.width / 375) * 20).toFixed(4) * 1;
                    window.onload = function() {
                        setTimeout(function(){
                            window.scrollTo(0, t);
                        }, 0);
                    }
                } else {
                    $('.js-book-list').scrollTop(item.parents('li').offset().top - litop - $('.js-book-list').offset().top);
                }
                break;
            }
        }
    }

    if (!isH5) {
        $('.js-book-list').perfectScrollbar('destroy');
        $('.js-book-list').perfectScrollbar();
    }

    function cutByWidth(str, wid, fontSize) {
        //通过宽度截取字符串
        var nstr = '';
        if (typeof str === 'string' && wid > 0) {
            nstr = str;
            var nfs = fontSize !== undefined ? fontSize : 14,
                limit_val = wid,
                is_length = false;
            recursionFunc(nstr);
            function recursionFunc(keys) {
                var nw = textSize(
                    {
                        fontSize: nfs,
                    },
                    keys
                );
                if (nw > limit_val) {
                    is_length = true;
                    var nkey = keys.substr(0, keys.length - 1);
                    arguments.callee(nkey);
                } else {
                    if (is_length === true) {
                        nstr = keys + '...';
                    } else {
                        nstr = keys;
                    }
                    return keys;
                }
            }
        }
        return nstr;
    }
    function textSize(cssList, text) {
        // 通过元素获取文字宽高
        var span = document.createElement('span');
        var result = {};
        result.width = span.offsetWidth;
        result.height = span.offsetWidth;
        span.style.visibility = 'hidden';
        span.style.cssText = 'font-size:14px;line-height:1em;display:inline;padding:0;margin:0;border:none;letter-spacing:0px;white-space:nowrap;';
        span.style.fontSize = cssList['fontsize'] !== undefined ? cssList['fontsize'] + 'px' : '14px';
        span.style.lineHeight = cssList['lineheight'] !== undefined ? cssList['lineheight'] : '1em';
        document.body.appendChild(span);
        if (typeof span.textContent != 'undefined') {
            span.textContent = text;
        } else {
            span.innerText = text;
        }
        result.width = span.offsetWidth - result.width;
        result.height = span.offsetHeight - result.height;
        span.parentNode.removeChild(span);
        return result.width;
    }
});
