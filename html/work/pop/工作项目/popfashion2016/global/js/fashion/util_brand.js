/**
 * @todo    品牌操作类
 * @time    2016/03/04
 * @author    jiangwei
 *
 * @usage
 *  //初始化参数
 $(function(){
        //配置项
        utilBrand.config({
            BrandData:Brand50,//所有按首字母为key的品牌
            template:'<li><a href="/?c=search&p=#brand_tid " title="#title">#brand</a></li>',
        });
        $('.brands_ca').find('.letter').on('mouseenter', function (e) {
            utilBrand.mouseenter($(this))
        });
        $(".brandslist .searInputbox").on('keyup', function () {
            utilBrand.keyup($(this));
        }); 
    })
 *
 *
 *
 *
 *
 *
 *
 */
//品牌

// 书籍字符转换
var global_brand_obj={
    key_vals: ['pop380', 'pop381', 'pop382', 'pop383', 'pop384', 'pop385', 'pop386', 'pop387', 'pop388', 'pop389', 'pop390', 'pop391', 'pop392', 'pop35'],
    keys: ['-', '_', '~', '!', '.', '*', '(', ')', '&', '<', '>', "'", '+', '#']
};
function clickJump(obj, columnId) {
    if (!oCommon.clickBeforeFlicker(obj)) {
        return false;
    }
    if (columnId && $.inArray(parseInt(columnId), [6, 72, 70, 113]) != -1) {
        var type = 'boo';
    } else {
        var type = 'bra';
    }
    var _bra = obj.getAttribute('data-bra');
    for(var i=0,len=global_brand_obj.keys.length;i<len;i++){
        _bra=_bra.replace(global_brand_obj.keys[i],global_brand_obj.key_vals[i]);
    };
    var link = document.getElementById('link');
    var _url = link.getAttribute('data-url');
    var _param = link.getAttribute('data-param');
    var _key = link.getAttribute('data-search');
    if (_param != '') {
        // 页码如果存在则将页码参数去掉
        if (_param.indexOf('page_') !== -1) {
            var reg = new RegExp('page_([^-]*)', 'gi');
            _param = _param.replace(reg, '');
        }
        // 不存在则增加
        if (_param.indexOf(type + '_') === -1) {
            _param = _param + '-' + type + '_' + _bra;
        }
        // 否则替换
        else {
            var reg = new RegExp(type + '_([^-]*)', 'gi');
            _param = _param.replace(reg, type + '_' + _bra);
        }
    }
    else {
        _param = type + '_' + _bra;
    }
    var jumpUrl = _url + _param.replace(/\/$/, '') + '/' + _key + '#anchor';
    if (obj.getAttribute('data-book') == 1) {
      location.href = jumpUrl;
      return;
    }
    // var searchVal = $.trim($('.searInputbox').val());
    // if (searchVal && !searchVal.match(/[搜索|输入]/ig)) {
      var hisName = $(obj).attr('title');
      if (P_UserType == 5) {
        var stoArr = pop_fashion_global.fn.getSto('brand_history');
        var nowArr = [{
          brand_name: hisName,
          data_bra: _bra
        }];
        if (stoArr) {
          if (JSON.stringify(stoArr).match(hisName)) {
            pop_fashion_global.fn.setSto('brand_history', stoArr);
          } else {
            pop_fashion_global.fn.setSto('brand_history', nowArr.concat(stoArr));
          }
        } else {
          pop_fashion_global.fn.setSto('brand_history', nowArr);
        }
        location.href = jumpUrl;
      } else {
        $.ajax({
          url: '/ajax/brandhistory/?'+Math.random(),
          type: 'POST',
          data: {
            act: 'save',
            brand_id: Number($(obj).attr('data-bra')),
            // col_id: columnId
          },
          complete: function() {
            location.href = jumpUrl;
          }
        })
      }
    // } else {
    //   location.href = jumpUrl;
    // }
}
function backUrl(obj, columnId) {
    if (!oCommon.clickBeforeFlicker(obj)) {
        return false;
    }
    if (columnId && $.inArray(parseInt(columnId), [6, 72, 70, 113]) != -1) {
        var type = 'boo';
    } else {
        var type = 'bra';
    }
    var _bra = obj.getAttribute('data-bra');
    for(var i=0,len=global_brand_obj.keys.length;i<len;i++){
        _bra=_bra.replace(global_brand_obj.keys[i],global_brand_obj.key_vals[i]);
    };
    var link = document.getElementById('link');
    var _url = link.getAttribute('data-url');
    var _param = link.getAttribute('data-param');
    var _key = link.getAttribute('data-search');
    if (_param != '') {
        // 页码如果存在则将页码参数去掉
        if (_param.indexOf('page_') !== -1) {
            var reg = new RegExp('page_([^-]*)', 'gi');
            _param = _param.replace(reg, '');
        }
        // 不存在则增加
        if (_param.indexOf(type + '_') === -1) {
            _param = _param + '-' + type + '_' + _bra;
        }
        // 否则替换
        else {
            var reg = new RegExp(type + '_([^-]*)', 'gi');
            _param = _param.replace(reg, type + '_' + _bra);
        }
    }
    else {
        _param = type + '_' + _bra;
    }
    return _url + _param.replace(/\/$/, '') + '/' + _key + '#anchor';
}

// 清空历史搜索
function clearHistory() {
  if (P_UserType == 5) {
    pop_fashion_global.fn.delSto('brand_history');
    utilBrand.letterLoaded['STAR'] = false;
    utilBrand.starData.history = [];
    $('.brands_ca').each(function(i) {
      utilBrand.mouseenter($('.brands_ca:eq(' + i + ') .letter[name=STAR]'))
    })
  } else {
    $.ajax({
      url: '/ajax/brandhistory/?'+Math.random(),
      type: 'POST',
      data: {
        act: 'delete'
      },
      success: function(res) {
        var resJson = JSON.parse(res);
        utilBrand.letterLoaded['STAR'] = false;
        utilBrand.starData.history = [];
        $('.brands_ca').each(function(i) {
          utilBrand.mouseenter($('.brands_ca:eq(' + i + ') .letter[name=STAR]'))
        })
      }
    })
  }
}


var utilBrand = {
    letterLoaded: [],//已经渲染过的
    curBrandHtml: [],
    brandLoaded: [],
    brandHtmlNew: [],
    BrandData: [],
    HotBrands: [],//热门品牌联动ID
    starData: {
      history: [],
      hot: []
    },
    searchData: {
      key: '',
      keys: '',
      allBrandArr: [],
      regStr: '',
      list: [],
      keyupTime: 0,
      timeOut: null,
      speed: 800
    },
    start: '',
    tipName: '品牌',
    template: '',    //url的模板
    sMarketHotPosition: '',//市场热度
    config: function (config) { //载入初始配置
        for (var key in config) {
            this['' + key] = config['' + key];
        }
        ;
        //品牌根据品牌定位过滤
        if (this.sMarketHotPosition != '' && $.trim(this.tipName) == '品牌') {
            this.sMarketHotPosition = this.sMarketHotPosition.toString();
            var tmpBrandData = [];
            for (var letter in this.BrandData) {
                tmpBrandData[letter] = []
                for (k in this.BrandData[letter]) {
                    if (this.BrandData[letter][k]['search'] == '' || typeof(this.BrandData[letter][k]['search']) == 'undefined') {
                        var seacrh = []
                    } else {
                        var seacrh = this.BrandData[letter][k]['search'].split(",");
                    }
                    if ($.inArray(this.sMarketHotPosition, seacrh) != -1) {
                        tmpBrandData[letter][k] = this.BrandData[letter][k];
                    }
                }
            }
            this.BrandData = tmpBrandData;
        }
        //设置初始数据
        var self = this;
        // 生成数据
        self.setDom();
        // 为搜索合并数据
        for (var i in self.BrandData) {
          if (i == 'HOT') {
            continue;
          }
          self.searchData.allBrandArr = self.searchData.allBrandArr.concat(self.BrandData[i]);
        }
        if (self.tipName == '品牌') {
          var o = {};
          var new_arr = [];
          for (var j = 0; j < self.searchData.allBrandArr.length; j++) {
            var k = self.searchData.allBrandArr[j].id;
            if (!o[k]) {
              o[k]=true;
              new_arr.push(self.searchData.allBrandArr[j]);
            }
          }
          self.searchData.allBrandArr = new_arr;
          $(".brands_ca .items .letter[name='HOT']").parent().hide();
        }
        var o = [];
        if (P_UserType == 5) {
          var stoArr = pop_fashion_global.fn.getSto('brand_history');
          if (stoArr) {
            self.starData.history = stoArr;
          }
          if (!self.starData.history.length && !$('.brands_ca .letter[name=HOT]').length) {
            $(".brands_ca .items .letter[name='STAR']").parent().hide();
          }
          complete();
        } else {
          var link = document.getElementById('link');
          var _param = link.getAttribute('data-param');
          $.ajax({
            url: '/ajax/brandhistory/?'+Math.random(),
            type: 'POST',
            data: {
              col_id: Number($('#link').attr('data-col')),
              params: _param
            },
            success: function(res) {
              var resJson = JSON.parse(res);
              if (resJson.code == 0) {
                self.starData.history = resJson.data;
                if (!self.starData.history.length && !$('.brands_ca .letter[name=HOT]').length) {
                  $(".brands_ca .items .letter[name='STAR']").parent().hide();
                }
              }
            },
            complete: complete
          })
        }
        function complete() {
          $(".brands_ca").each(function(idx) {
            $(".brands_ca:eq(" + idx + ") div[class='items']").each(function () {
                var letter = $(this).find(".letter").attr('name').toUpperCase();
                if (self.checkIsExist(letter) && !o[idx]) {
                    o[idx] = true;
                    self.mouseenter($(this).find(".letter"));
                    self.start = letter;
                } else if (letter == 'OTHER' && !o[idx]) {
                    //self.mouseenter($(this).find(".letter"));
                    $(".brands_ca").parents("li").hide();
                    //品牌隐藏重新计算位置
                    $('.showbox').each(function () {
                        var $self = $(this);
                        var $parent = $self.parent('li');
                        var selfWid = $self.outerWidth(true);
                        var rightWidth = parseInt(document.body.offsetWidth) - parseInt($parent.offset().left);
                        var leftWidth = $parent.offset().left;
                        var liWid = $(".paixu li").width();
                        if (selfWid > rightWidth) {
                            $self.css('left', -selfWid + liWid + 1);
                        } else if (selfWid > leftWidth) {
                            $self.css('left', '-1px');
                        }
                    });
                }
            });
          })
        }
        $(".searInputbox").on('focus', function () {
            var prex = eval('/([搜索|输入].*' + self.tipName + ')/');
            var str = $(this).val();
            var isMatch = str.match(prex);
            if (isMatch) {
                $(this).val("");
            }
        }).on('blur', function() {
          if (!$(this).val().trim()) {
            self.inputShow('', false);
          }
        })

        function rebuildBrandsArr(text, tempBrands) {
          var brands = [];
          var textLen = text.length;
          for (var i in tempBrands) {
            var brand = tempBrands[i];
            var objBrands = {};
            var cn = brand.name.toUpperCase();
            if (cn) {
              var state = false;
              for (var i = 0; i < textLen; i++) {
                if (state) {
                  continue;
                }
                if (cn.indexOf(text[i]) != -1) {
                  state = true;
                  objBrands = brand;
                  brands.push(objBrands);
                }
              }
              continue;
            }
          }
          return brands.sort(function(a, b) {
            var nameA = a.name.toUpperCase();
            var nameB = b.name.toUpperCase();
            var count1 = 0,count2 = 0;
            var str = text;
            var len1 = a.name.length;
            var len2 = b.name.length;
            var idx1 = nameA.indexOf(text);
            var idx2 = nameB.indexOf(text);
            var l1,l2;
            /* 完全匹配 */
            if (nameA == text) {
              count1 += 999;
            }
            if (nameB == text) {
              count2 += 999;
            }
            /* 完整包含 */
            if (nameA.indexOf(text) != -1) {
              count1 += 100;
            }
            if (nameB.indexOf(text) != -1) {
              count2 += 100;
            }
            /* 以输入字符开头 */
            if (nameA.substr(0,textLen) == text) {
              count1 += 100;
            }
            if (nameB.substr(0,textLen) == text) {
              count2 += 100;
            }
            /* 位置 */
            count1 += textLen - (idx1 != -1 ? idx1 : 0);
            count2 += textLen - (idx2 != -1 ? idx2 : 0);
            /* 重复度+ */
            for (var i = textLen; i > 0; i--) {
              str = str.substr(0, i);
              l1 = nameA.split(str).length - 1;
              l2 = nameB.split(str).length - 1;
              count1 += l1/len1+i;
              count2 += l2/len2+i;
            }
            /* 重复度- */
            // for (var i = 1; i <= textLen; i++) {
            //   str = str.substr((textLen - i), textLen);
            //   l1 = nameA.split(str).length - 1;
            //   l2 = nameB.split(str).length - 1;
            //   count1 += l1/len1+i;
            //   count2 += l2/len2+i;
            // }
            a.count = count1;
            b.count = count2;
            return b.count - a.count;
          });
        }
        function getBrandsData(request, resposeCallback) {
          var text = $.trim(request.term);
          var data = [];
          if (text && typeof self.BrandData != 'undefined' ) {
            // text = text.toUpperCase();
            data = rebuildBrandsArr(text.toUpperCase(), self.searchData.allBrandArr);
          }
          if (data.length > 200) {
            data = data.slice(0, 200);
          }
          self.searchData.list = data;
          self.searchIpt(text);
        }
        $(".searInputbox").autocomplete({
          source: getBrandsData,
          minLength: 0,
          delay: 300
        });
    },
    //判断当前 字母下是否有数据
    checkIsExist: function (letter) {
      if (letter == 'STAR' && (this.starData.history.length || $('.brands_ca .letter[name=HOT]').length)) {
        return true;
      } else if (this.BrandData == null || typeof(this.BrandData[letter]) == 'undefined')
            return false;
        var a = this.BrandData[letter].length;
        if (a >= 1) {
            return true;
        } else {
            return false;
        }
    },
    //当大写字母下没有品牌时
    showNoFilter: function (sel, filter) {
        var html = '<li class="txt" style="height:30px;width:100%"><p >暂无相关的' + this.tipName + '，请尝试用其他字母进行检索...</p></li>';
        sel.html(html);
        this.curBrandHtml[filter] = html;
        sel.show();
    },
    //当鼠标放上去的时候
    mouseenter: function (obj) {
        var letter = $.trim(obj.attr('name')).toUpperCase();//获取当前letter
        if (letter == 'HOT') {
            var booleanStr = "($.inArray(Number(brandList[i].id),this.HotBrands)>-1 )";
            var on = 'hot_on';
        } else {
            var booleanStr = 'true';
            var on = 'on';
        }
        this.inputShow(letter, false);
        $(".searInputbox").blur();//失去焦点
        var thisUL = $('.brandslist').find('ul');//将被填充数据的ul
        var brandList = this.BrandData[letter];
        var brandHtmlArray = [];
        obj.closest(".items").addClass(on).siblings().removeClass('on').end().siblings().removeClass('hot_on');
        if (typeof this.letterLoaded[letter] == 'undefined' || !this.letterLoaded[letter]) {
            // 如果当前字母下的ul里面为空，则通过js绘制品牌的html
            if (letter == 'STAR') {
              if (this.starData.history.length) {
                brandHtmlArray.push('<li class="title">搜索历史<span class="clear-history" onclick="clearHistory()"><i></i>清空历史</span></li>');
                this.starData.history.forEach(function(v, i) {
                  if (i < 10) {
                    if (P_UserType != 5) {
                      brandHtmlArray.push('<li><a href="'+v.brand_link+'" title="'+v.brand_name+'">'+v.brand_name+'</a></li>');
                    } else {
                      brandHtmlArray.push('<li><a href="javascript:void(0);" onclick="clickJump(this, '+$('#link').attr('data-col')+')" data-bra="'+v.data_bra+'" title="'+v.brand_name+'">'+v.brand_name+'</a></li>');
                    }
                  }
                });
              }
              if ($('.brands_ca .letter[name=HOT]').length) {
                var brandListHot = this.BrandData['HOT'];
                brandHtmlArray.push('<li class="title">热门推荐</li>');
                for (var i in brandListHot) {
                    if (brandListHot.hasOwnProperty(i) && eval(booleanStr)) {
                        brandHtmlArray.push(this.template
                            .replace('#brand_tid', brandListHot[i].id)
                            .replace('#title', brandListHot[i].name)
                            .replace(/#brand/g, brandListHot[i].name));
                    }
                }
              }
              if (brandHtmlArray.length) {
                this.curBrandHtml[letter] = brandHtmlArray.join('');
                thisUL.html(brandHtmlArray.join(''));
              } else {
                $('.brands_ca .letter[name=STAR]').hide();
                var next = $('.brands_ca .letter[name=STAR]').parent().next();
                next.addClass('on');
                var name = next.children('.letter').attr('name');
                var brandList = this.BrandData[name];
                brandHtmlArray = [];
                for (var i in brandList) {
                    if (brandList.hasOwnProperty(i)) {
                        brandHtmlArray.push(this.template
                            .replace('#brand_tid', brandList[i].id)
                            .replace('#title', brandList[i].name)
                            .replace(/#brand/g, brandList[i].name));
                    }
                }
                this.curBrandHtml[letter] = brandHtmlArray.join('');
                thisUL.html(brandHtmlArray.join(''));
                if (!brandHtmlArray.length) {
                  this.showNoFilter(thisUL, letter);
                }
              }
            } else if (typeof(brandList) != 'undefined' && brandList.length > 0 && (letter == 'HOT' ? this.HotBrands:true)) {
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
            } else {
                this.showNoFilter(thisUL, letter);
            }
            this.letterLoaded[letter] = true;
        } else {
            thisUL.html(this.curBrandHtml[letter]);//已渲染过，直接取值
        }
        this.showBarY(thisUL, letter);
    },
    keyup: function (self, type) {
      if (type == 'book') {
        var curHoverLetter = self.attr('name');
        var key = $.trim(self.closest(".items").find(".letter").text());
        var sNoTrim = self.val();
        var s = $.trim(self.val()); //输入值
        var letter = s.substring(0, 1);
        var regO = /^[A-Za-z]$/;
        if (!isNaN(letter - 0) && letter != "") {
            letter = '0-9';
        } else if (regO.test(letter)) {
            letter = letter.toUpperCase();
        } else if (letter == "") {
            return;
        } else {
            letter = 'OTHER';
        }
        if (curHoverLetter == 'HOT') {
            letter = 'HOT';
            var booleanStr = "($.inArray(Number(brandList[i].id),this.HotBrands)>-1 )";
        } else {
            var booleanStr = 'true';
        }
        this.inputShow(letter, true);
        var obj = $('.brands_ca div[name="' + letter + '"]');
        obj.closest(".items").addClass('on').siblings('.items').removeClass('on');
        var brandList = this.BrandData[letter];
        var brandHtmlArray = [];
        var curUL = self.parents('.brandslist').find('ul');

        if (brandList) {
            for (var i in brandList) {
                if (brandList.hasOwnProperty(i) && brandList[i].name.toLowerCase().indexOf(s.toLowerCase()) > -1 && eval(booleanStr)) {
                    brandHtmlArray.push(this.template
                        .replace('#brand_tid', brandList[i].id)
                        .replace('#title', brandList[i].name)
                        .replace(/#brand/g, brandList[i].name));
                }
            }
            if(brandHtmlArray.length>0){
                curUL.html(brandHtmlArray.join('')).show();
            }else{
                this.showNoFilter(curUL, s);
            }
            

        } else {
            this.showNoFilter(curUL, s);
        }
        // if(curUL.length>1){
        //     curUL.eq(1).hide();
        // }
        this.showBarY(obj, s);
      } else {
        return false;
      }
    },
    searchIpt: function(text) {
        var _this = this;
        var s = text; //输入值
        var curUL = $(".searInputbox").parents('.showPro').find('.brandslist').find('ul');
        var obj = $('.brands_ca>.on>.letter');
        $(".searInputbox").val(text);
        if (s) {
          $('.brands_ca').addClass('brand_hide');
          // curUL.eq(0).html($('<li style="width:100%">检索中，请稍候...</li>')).show();
          var brandHtmlArray = [];
          for (var i in _this.searchData.list) {
            brandHtmlArray.push(_this.template
              .replace('#brand_tid', _this.searchData.list[i].id)
              .replace('#title', _this.searchData.list[i].name)
              .replace(/#brand/g, _this.searchData.list[i].name));
          }
          if(brandHtmlArray.length>0){
              curUL.html(brandHtmlArray.join('')).show();
          }else{
              _this.showNoFilter(curUL, s);
          }
        } else {
          $('.brands_ca').removeClass('brand_hide');
          curUL.html(this.curBrandHtml[$('.brands_ca .items.on .letter').attr('name')]);
        }
        _this.showBarY(obj, s);
    },
    //处理输入框默认值
    inputShow: function (letter, isInput) {
        $(".searInputbox").prop('name', letter);
        // $(".brandslist .searInputbox").prop('name', letter);
        if (!isInput) {
            var tip = this.getTipSentence(letter);
            $(".searInputbox").val(tip);
            // $(".brandslist .searInputbox").val(tip);
        }
    },
    //控制显示高度
    showBarY: function (sel, filter) {
        var boxSel = $(".brands_ca").find('.contentHolder');
        var boxH = boxSel.height();
        var conH = boxSel.find('.content').height();
        var brandslistH = 0;
        if (conH <= 0) {
            var liNum = $(".brands_ca").find(".brandslist li").length;
            var x = Math.ceil(liNum / 5);
            conH = x * 30; //30为li的高度
        }
        if (conH >= 320) {
            brandslistH = 320 + 57;
            
        } else {
            brandslistH = conH + 57;
        }        
        sel.parents(".showPro").height(brandslistH + 65 + "px");

    },
    getTipSentence: function (key) {
      return '输入'+this.tipName+'全称结果更精准';
        if (key == 'HOT') {
            return "搜索热门" + this.tipName;
        } else {
            return "搜索" + key + "开头的" + this.tipName;
        }
    },

    //加载品牌
    brand_new: function (config) {  //载入初始配置
        for (var key in config) {
            this['' + key] = config['' + key];
        }
        ;
        var thisDIV = $('.brand_display');//将被填充数据的DIV
        var brandList = this.BrandData;
        var brandHtmlArray = [];
        var brandLetter;
        // 如果当前字母下的ul里面为空，则通过js绘制品牌的html
        if (typeof this.brandLoaded != true) {
            var count = 1;
            for (var letter in brandList) {
                brandLetter = brandList[letter];
                for (var i in brandLetter) {
                    if (brandLetter.hasOwnProperty(i)) {
                        if (count != 1) {
                            brandHtmlArray.push(this.template
                                .replace('#brand_tid', brandLetter[i].id)
                                .replace('#title', brandLetter[i].name)
                                .replace(/#brand/g, brandLetter[i].name)
                                .replace('#borderTop', 'borderTop'));
                        } else {
                            brandHtmlArray.push(this.template
                                .replace('#brand_tid', brandLetter[i].id)
                                .replace('#title', brandLetter[i].name)
                                .replace(/#brand/g, brandLetter[i].name));
                        }
                        count += 1;
                    }
                }
            }
            this.brandHtmlNew = brandHtmlArray.join('');
            thisDIV.html(brandHtmlArray.join(''));
            this.brandLoaded = true;
        } else {
            thisDIV.html(this.brandHtmlNew);//已渲染过，直接取值
        }
    },
    setDom: function () {
        var a = this, data = a.BrandData;
        var columnId = $('#link').attr('data-col');
        if (data instanceof Object) {
            var _html = '', tar = $(".js-filter-list"), id, tit;
            for (var key in data) {
                var arr = data[key] || [];
                for (var skey in arr) {
                    id = arr[skey]["id"] || "";
                    tit = arr[skey]["name"] || "";
                    _html += '<li><a href="javascript:void(0);" onclick="clickJump(this, '+columnId+')" data-bra="' + id + '" title="' + tit + '">' + tit + '</a></li>';
                }
            }
            ;
            if (_html != "") {

                tar.html(_html);
                a.showBarY(tar);
                // return;
                // if($(".js-filter-list").length>0){
                //     tar=$(".js-filter-list");
                //     tar.html(_html);
                //     a.showBarY(tar);
                // }else{

                // }

                // if($('.brandslist').length>1){
                //     $('.brandslist').each(function(){
                        
                //         var tar=$(this).find('ul').eq(0);
                //         tar.html(_html);
                //         a.showBarY(tar);
                //     });
                // }else{
                //     if($(".js-filter-list").length>0){
                //         tar=$(".js-filter-list");
                //     }else{
                //         tar=$('.brandslist').find('ul').eq(0);
                //     }
                //     tar.html(_html);
                //     a.showBarY(tar);
                // }
                
                
            }
        }
    }

};