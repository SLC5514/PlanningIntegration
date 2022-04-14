/**
 * search function
 * author lichenglong
 * date 2015-11-09 19:37:07
 */

var POPTOOL = POPTOOL || {};
POPTOOL.brand = {};


// 品牌模版通过js动态调取，而不是加载页面时输出大量的html
$(document).ready(function () {
    var letters = $('.nav_band').find('.brands_ca').find('.letter');
    var letterLoaded = [];
    // 替换模版

    var J_Search_Para = $('#J_Search_Para');
    var searchCond = J_Search_Para.val();
    if (!searchCond) {
        searchCond = 'ct-s-0-0-0-0-0-0-0-0-0-0';
    }
    var arr = searchCond.split('-');
    arr[6] = '#brand_tid'; // 第七位是品牌
    var _searchCond = arr.join('-');
    var allBrandLink = '/?c=search&p=' + _searchCond.replace('#brand_tid', '0');

    POPTOOL.brand.template = '<li><a href="/?c=search&p=' + _searchCond + '" title="#title">#brand</a></li>';
    
    var curBrandHtml = [];
    letters.on('mouseenter mouseleave', function (e) {
        var letter = $.trim($(this).html());
        var curUL = $('#popBrand_' + letter);
        if (letterLoaded[letter] === undefined) {
            // 如果当前字母下的ul里面为空，则通过js绘制品牌的html
            var brandList = Brand50[letter];
            var brandHtmlArray = [];
            if(brandList){
                for (var i in brandList) {
                if (brandList.hasOwnProperty(i)) {
                    brandHtmlArray.push(POPTOOL.brand.template
                        .replace('#brand_tid', brandList[i].id)
                        .replace('#title', brandList[i].name)
                        .replace('#brand', brandList[i].name));
                    }
                }
                curBrandHtml[letter] = brandHtmlArray.join('');
                curUL.html(brandHtmlArray.join(''));
            }else{
                  showNoFilter(curUL,letter);
            }            
            letterLoaded[letter] = true;
        } else {
            curUL.html(curBrandHtml[letter]);
        }
    });

     letters.on('mouseenter', function (e) {
            var self = $(this);
            var letter = $.trim(self.html());
            self.closest(".items").addClass('on').siblings().removeClass('on').end().find("input").val(getTipSentence(letter)).blur();           
            showBarY(self,letter);  
     });
    function showNoFilter(sel,filter){
         sel.html('<li class="txt"><p>该栏目中没有以“'+filter+'”开头的品牌名，请尝试用其他字母进行检索...</p></li>');
         var brandslistH = sel.closest(".items").find(".brandslist").height();
         sel.closest(".showPro").height(brandslistH+85);
    }
    function showBarY(sel,filter){  //控制滚动条显示
        var boxSel = sel.closest(".items").find('.contentHolder');
         boxSel.find('.ps-scrollbar-y-rail').hide();
        var boxH = boxSel.height();
        var conH = boxSel.find('.content').height();
        var brandslistH = sel.closest(".items").find(".brandslist").height();
        sel.closest(".showPro").height(brandslistH+85);
        if( conH > 300 && conH>boxH){
            boxSel.scrollTop(1);
            var barYrailH = boxSel.find('.ps-scrollbar-y-rail').height();
            var scrollH = boxSel.prop('scrollHeight');
            var barH = parseInt(boxH*boxH/scrollH,10);
            boxSel.perfectScrollbar();
            boxSel.find('.ps-scrollbar-y-rail').show();
            boxSel.find(".ps-scrollbar-y").height(barH);
            boxSel.find(".ps-scrollbar-y").css("top",0);
        }
        if(conH <= 300){
          boxSel.find('.ps-scrollbar-y-rail').hide();
          if(boxSel.find('.content li p').length > 0 || boxSel.find('.content li').length <= 0 ){
           var ulBox = boxSel.find('.content ul');
            showNoFilter(ulBox,filter);
            //alert(filter);
          }
        }
    }

    //品牌搜索
    //初始化品牌默认是0-9选中显示
    var brand_items = $(".brands_ca .items");
     brand_items.eq(1).addClass('on').siblings().removeClass('on');
     brand_items.eq(1).find(".letter").trigger("mouseenter");
     showBarY($(".lm_shaixuan .items").eq(1),brand_items.eq(1).find(".letter").html());
    function getTipSentence(key) {
        return "搜索" + key + "开头的品牌";
    }


    $(".brandslist .H_searchBrand").on('keyup', function () {
        var self = $(this);
        var curHoverLetter = self.attr('name');
        var key = $.trim(self.closest(".items").find(".letter").text());
        var s = $.trim(self.val());  
        var letter = s.substring(0, 1);
        var regO = /^[A-Za-z]$/;
        if (!isNaN(letter - 0) && letter!="") {
            letter = '0-9';
        } else if (regO.test(letter)) {
            letter = letter.toUpperCase();
        } else if ( letter =="") {
            return;
        } else {
            letter = 'OTHER';
        }
        $(".header .items div[name=" + letter + "]").closest(".items").addClass('on').siblings('.items').removeClass('on');
        if (key.toString() != letter) {
            $(".header div.items input[name=" + letter + "]").val("").focus().val(s);
        }
        if(self.val() != ""  && self.closest(".brandslist").is(":hidden")){
             self.val(getTipSentence(curHoverLetter)).blur();
        }

        var brandList = AZ_KOREA_Brand[letter];
        var brandHtmlArray = [];
        var curUL = $('#popBrand_' + letter);
        if(brandList){
             for (var i in brandList) {
                if (brandList.hasOwnProperty(i) && brandList[i].name.toLowerCase().indexOf(s.toLowerCase()) > -1) {
                    brandHtmlArray.push(POPTOOL.brand.template
                        .replace('#brand_tid', brandList[i].id)
                        .replace('#title', brandList[i].name)
                            .replace('#brand', brandList[i].name));
                }
            }
            curUL.html(brandHtmlArray.join(''));
            showBarY($('#popBrand_' + letter),s);  
         }else{
            showNoFilter(curUL,s);
        }
    });


    $(".brands_ca .items").each(function () {
        var sel = $(this).find("input");
        var selVal = $.trim(sel.val());
        sel.inputText({
            txt: selVal,
            lightColor: "#949393",
            color: "#949393",
            fontSize: "12px"
        });
    });
    
});