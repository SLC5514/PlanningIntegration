!function (g){
  var BannerSlide = function(options) {
    var self = this;
    this.$options = {
        elBox: '.hot-banner-contain',  //父级盒子
        el: '.sp-box', // 绑定元素
        anitEl: '.sp-anit-time', //轮播动画计时
        switchEl:'.sp-switch-box', //前后按钮父级
        prevEl: '.sp-prev', // 前一个按钮
        nextEl: '.sp-next', // 后一个按钮
        prevSd: '.sp-sd-l', // 前一个遮罩
        nextSd: '.sp-sd-r', // 后一个遮罩
        imgList: [], // 数据列表
        time: 300, // 延迟时间ms
        playTime: 5000, // 自动播时间
        lrRemove: 20, // 左右按钮与展示块的距离
        count: 0, // 当前下标
        showSlide: 3, // 展示的块数
        space: 2, // 展示块的间距
        h: 240, // 盒子高度
        sw: 386, // 子块宽度
        sh: 240, // 子块高度
        scale: 1, // 子块的缩放比
        cpSize: 200, // 截取块宽高
        cpT: 20, // 截取块top值
        cpR: 20, // 截取块right值
        spmsgW: 190, // 文字块宽
        spmsgH: 140, // 文字块高
        spmsgL: 20, // 文字块left值
        spmsgT: 50, // 文字块top值
        spmsgFontSize: 20,
        isSvg: false, // 高斯模糊是否为svg
        sf: 1.6, // 多行文字省略参数
        smh: 74,
        slh: 1.2
    }
    if ($(window).width() <= 1500) {
      this.$options = $.extend(this.$options, {
        h: 192, // 盒子高度
        sw: 309, // 子块宽度
        sh: 192, // 子块高度
        cpSize: 160, // 截取块宽高
        cpT: 16, // 截取块top值
        cpR: 16, // 截取块right值
        spmsgW: 153, // 文字块宽
        spmsgH: 112, // 文字块高
        spmsgL: 15, // 文字块left值
        spmsgT: 40, // 文字块top值
        spmsgFontSize: 18,
        sf: 1.6,
        smh: 45,
        slh: 1.3
      });
    }
    this.$options = $.extend(this.$options, options);
    this.$count = this.$options.count;
    this.$elBox = $(this.$options.elBox);
    this.$el = $(this.$options.el);
    this.$anitEl = $(this.$options.anitEl);
    this.$switchEl = $(this.$options.switchEl);
    this.$prevEl = $(this.$options.prevEl);
    this.$nextEl = $(this.$options.nextEl);
    this.$prevSd = $(this.$options.prevSd);
    this.$nextSd = $(this.$options.nextSd);
    this.$len = this.$options.imgList.length;
    if (this.$len < 3) {
        this.$elBox.hide();
        return;
    } else if (this.$len == 3) {
        this.$switchEl.hide();
        this.$prevSd.hide();
        this.$nextSd.hide();
        this.$elBox.show();
    }else {
        this.$elBox.show();
    }
    this.$columeId = $('#link').data().col;
    this.$columeUrl = $('#link').data().url.split('/')[1];
    this.$broswerMsg = this.getBroswer();
    this.$broswerMsg.version = parseInt(this.$broswerMsg.version);
    this.$w = this.$el.width();
    this.$wp = this.$el.find('.sp-wp');
    this.$anit = this.$anitEl.find('span');
    this.$wpL = 0;
    this.$childAll = null;
    this.$clickType = false;
    this.$timeIn = null;
    this.$timeout = null;
    this.$timeoutClick = null;
    this.$timeoutCb = null;
    this.$minW = this.$options.sw + this.$options.space*2;
    this.$maxW = this.$minW*this.$options.showSlide;
    this.$scaleM = (this.$options.sw + this.$options.space*2 - this.$options.sw*this.$options.scale)/2;
    this.$sdWidth = (this.$w - (this.$minW*this.$options.showSlide - this.$options.space*2))/2;
    this.imgOgSize = {};
    this.getImgOgSize(function() {
      switch (self.$columeUrl) {
        case 'books':
        case 'styles':
          self.prevInit(1);
          break;
        case 'runways':
          self.prevInit(2);
          break;
        default:
          self.prevInit();
          break;
      }
      self.$child = self.$el.find('.sp-s');
      self.init();
    });
  }
  BannerSlide.prototype = {
    getImgOgSize: function(callback) {
      var self = this, count = 0;
      $.each(this.$options.imgList, function(i, v) {
        self.imgOgSize[i] = new Image();
        self.imgOgSize[i].src = v.sImagePath;
        self.imgOgSize[i].onload = function() {
          count++;
          self.imgOgSize[i] = {
            w: this.width,
            h: this.height,
            load: true
          }
          if (count == self.$options.imgList.length) {
            callback();
          }
        }
        self.imgOgSize[i].onerror = function() {
          count++;
          self.imgOgSize[i] = {
            w: this.width,
            h: this.height,
            load: false
          }
          if (count == self.$options.imgList.length) {
            callback();
          }
        }
      });
    },
    prevInit: function(type) {
      var childEl = '', cpEl = '', txtEl = '', imgEl = '', gbimgStyle = '', cpimgStyle = '', title = '', bgStyle = '';
      var spCss3 = this.supportCss3('background-size');
      for (var i = 0; i < this.$options.imgList.length; i++) {        
        var img_num = (i+1)% 12 + 1;        
        if (!spCss3) {
          if (this.imgOgSize[i].w < this.imgOgSize[i].h) {
            cpimgStyle = 'style="width:100%;margin-top:' + -(this.imgOgSize[i].h*this.$options.cpSize/this.imgOgSize[i].w - this.$options.cpSize)/2 + 'px;"';
          } else {
            cpimgStyle = 'style="height:100%;margin-left:' + -(this.imgOgSize[i].w*this.$options.cpSize/this.imgOgSize[i].h - this.$options.cpSize)/2 + 'px;"';
          }
          imgEl = '<img src="'+ this.$options.imgList[i].sImagePath +'" ' + cpimgStyle + ' />';
        }
        if (this.imgOgSize[i].w > this.imgOgSize[i].h) {
          gbimgStyle = 'style="height:103%;margin-left:' + -(this.imgOgSize[i].w*this.$options.sh/this.imgOgSize[i].h + this.imgOgSize[i].w*this.$options.sh/this.imgOgSize[i].h*0.03 - this.$options.sw)/2 + 'px;"';
        } else {
          gbimgStyle = 'style="width:100%;margin-top:' + -(this.imgOgSize[i].h*this.$options.sw/this.imgOgSize[i].w - this.$options.sh)/2 + 'px;"';
        }
        if (type == 1 || (type == 2 && this.$options.imgList[i].cover_image_tag != 1)) {
          bgStyle = 'center center/contain';
        } else {
          bgStyle = 'center center/cover';
        }
        title = this.$options.imgList[i].sTitle;
        if (this.$options.imgList[i].manual != 1) {
          if (this.$columeUrl == 'styles') {
            title = general.fn.cutByWidth(this.$options.imgList[i].sTitle + '最新款', this.$options.spmsgW*this.$options.sf, this.$options.spmsgFontSize);
          } else if ((this.$columeId == 82 || this.$columeId == 120)) {
            title = this.$options.imgList[i].sTitle + '<br/>最新花型';
          } else if (this.$columeId == 124) {
            title = '最新<br/>' + this.$options.imgList[i].sTitle + '工艺';
          } else {
            title = general.fn.cutByWidth(this.$options.imgList[i].sTitle, this.$options.spmsgW*this.$options.sf, this.$options.spmsgFontSize);
          }
        } else {
          title = general.fn.cutByWidth(this.$options.imgList[i].sTitle, this.$options.spmsgW*this.$options.sf, this.$options.spmsgFontSize);
        }
        cpEl = '<div class="sp-cp sp-cube" style="width:' + this.$options.cpSize*this.$options.scale + 
          'px;height:' + this.$options.cpSize*this.$options.scale + 
          'px;top:' + this.$options.cpT*this.$options.scale + 
          'px;right:' + this.$options.cpR*this.$options.scale + 
          'px;background: rgba(255,255,255,0.5) url(\''+ this.$options.imgList[i].sImagePath +'\') no-repeat ' + bgStyle + ';">' +
            imgEl +
          '</div>';
        txtEl = '<div class="sp-msg" style="width:' + this.$options.spmsgW*this.$options.scale + 
          'px;height:' + this.$options.spmsgH*this.$options.scale + 
          'px;line-height:' + this.$options.spmsgH*this.$options.scale + 
          'px;left:' + this.$options.spmsgL*this.$options.scale + 
          'px;top:' + this.$options.spmsgT*this.$options.scale + 
          'px;font-size:' + this.$options.spmsgFontSize*this.$options.scale + 'px;">' +
            '<span style="max-height: ' + this.$options.smh + 'px;line-height: ' + this.$options.slh + ';">' + title + '</span>' +
          '</div>';
        childEl += '<a class="sp-s sp-cube" href="'+ this.$options.imgList[i].sLink +'" title="' + this.$options.imgList[i].sTitle + '" target="' + (this.$options.imgList[i].sNewWindow == 1 ? '_blank' : '') + '" style="background:url(https://imgf2.pop-fashion.com/global/images/new_common/n_bg'+img_num+'.jpg)">' + cpEl + txtEl +'</a>';
      }
      this.$wp.html(childEl);
    },
    init: function() {
      var self = this;
      var t = 100;
      this.$prevSd.css({'width': this.$sdWidth + 'px'});
      this.$nextSd.css({'width': this.$sdWidth + 'px'});
    //   this.$prevEl.css({'left': this.$sdWidth - this.$prevEl.width() - this.$options.lrRemove + 'px'});
    //   this.$nextEl.css({'right': this.$sdWidth - this.$nextEl.width() - this.$options.lrRemove + 'px'});
      this.$wp.css('width', (this.$len + this.$options.showSlide*4) * (this.$options.sw + this.$options.space*2) + 'px');

      for (var i = 0; i < this.$child.length; i++) {
        $(this.$child[i]).attr('data-count', i);
        if (i >= this.$count && i < this.$count + this.$options.showSlide) {
          $(this.$child[i]).addClass('on').css({
            'width': this.$options.sw + 'px',
            'height': this.$options.sh + 'px',
            'margin': '0 ' + this.$options.space + 'px'
          }).find('.sp-cp').css({
            'width': this.$options.cpSize + 'px',
            'height': this.$options.cpSize + 'px',
            'top': this.$options.cpT + 'px',
            'right': this.$options.cpR + 'px'
          }).next('.sp-msg').css({
            'width': this.$options.spmsgW + 'px',
            'height': this.$options.spmsgH + 'px',
            'line-height': this.$options.spmsgH + 'px',
            'left': this.$options.spmsgL + 'px',
            'top': this.$options.spmsgT + 'px',
            'font-size': this.$options.spmsgFontSize + 'px'
          });
        } else {
          $(this.$child[i]).css({
            'width': this.$options.sw*this.$options.scale + 'px',
            'height': this.$options.sh*this.$options.scale + 'px',
            'margin': (this.$options.sh - this.$options.sh*this.$options.scale)/2 + 'px ' + this.$scaleM + 'px'
          });
        }
      }

      this.$wpL = -(this.$minW*(this.$count + this.$options.showSlide*2) + this.$options.space);
      this.$wp.css('margin-left', this.$wpL + 'px');

      if (this.$len < this.$options.showSlide*2) {
        this.$wp.prepend(this.$child.slice(-this.$options.showSlide*2).clone(false));
        this.$wp.prepend(this.$child.slice(-(this.$options.showSlide*2 - this.$len)).clone(false));
        this.$wp.append(this.$child.slice(0, this.$options.showSlide*2).clone(false));
        this.$wp.append(this.$child.slice(0, this.$options.showSlide*2 - this.$len).clone(false));
      } else {
        this.$wp.prepend(this.$child.slice(-this.$options.showSlide*2).clone(false));
        this.$wp.append(this.$child.slice(0, this.$options.showSlide*2).clone(false));
      }
      this.$childAll = this.$el.find('.sp-s');
      if (this.$len < 5) {
        var el,idx;
        var count = this.$options.showSlide*2;
        var hasOnList = this.$childAll.filter('.on');
        for (var i = 0,len = hasOnList.length; i < len; i++) {
          el = $(hasOnList[i]);
          idx = el.index();
          if (idx < count || idx >= count + 3) {
            this.changeOn(el);
          }
        }
      }
      if (this.$len == 3) {
        this.$childAll.filter('[class!="sp-s sp-cube on"]').css({'visibility': 'hidden'});
      }

      // 高斯模糊
      this.blurInit();

      this.$timeout = setTimeout(function() {
        // self.$prevSd.fadeIn();
        // self.$nextSd.fadeIn();
        self.$wp.addClass('ts').fadeIn();
        self.$childAll.css('transition-duration', self.$options.time + 'ms')
          .find('.sp-cp').css('transition-duration', self.$options.time + 'ms')
          .next('.sp-msg').css('transition-duration', self.$options.time + 'ms');
        if (location.hash == '#anchor' && $('#anchor').length) {
            self.$elBox.css('height', self.$options.h + 'px');
            if($(window).width >= 1500) {
                var count = 0, top = $('#anchor').offset().top + 40;
            }else{
                var count = 0, top = $('#anchor').offset().top + 20;
            }
            $('#anchor').css('top', top + count);
            window.scrollTo({
                top: $('#anchor').offset().top + self.$options.h,
                behavior: "smooth" // smooth(平滑滚动),instant(瞬间滚动)
            });
            // var timeout = setTimeout(function() {
            //     // if (self.$broswerMsg.broswer == 'IE') {
            //     //   $(window).scrollTop($('#anchor').offset().top + self.$options.h);
            //     // } else {
            //     //   window.scrollTo({
            //     //     top: $('#anchor').offset().top + self.$options.h,
            //     //     behavior: "smooth" // smooth(平滑滚动),instant(瞬间滚动)
            //     //   });
            //     // }
            //     if (self.$broswerMsg.broswer == 'IE' || !window.requestAnimationFrame) {
            //     $(window).scrollTop(top + self.$options.h);
            //     } else {
            //     function tick() {
            //         count += 12;
            //         if (count >= self.$options.h) {
            //         return;
            //         }
            //         $(window).scrollTop(top + count);
            //         window.requestAnimationFrame(tick);
            //     }
            //     window.requestAnimationFrame(tick);
            //     }
            // }, 600);
        } else {
          self.$elBox.animate({'height': self.$options.h + 'px'}, 300);
        }
        self.$wp.addClass('ts');
        type = false;
        // clearTimeout(self.$timeout);
      }, 0);

      if (this.$broswerMsg.broswer == 'IE') {
        t = 450;
      }
      this.$prevEl.on('click', function(e) {
        if (self.$clickType) {
          return false;
        }
        self.$clickType = true;
        self.$anit.stop(true,false).css("height","0px");
        self.$timeoutClick = setTimeout(function() {
          self.$clickType = false;
          clearTimeout(self.$timeoutClick);
        }, self.$options.time + t);
        self.prev();
      });
      this.$nextEl.on('click', function(e) {
        if (self.$clickType) {
          return false;
        }
        self.$clickType = true;
        self.$anit.stop(true,false).css("height","0px");
        self.$timeoutClick = setTimeout(function() {
          self.$clickType = false;
          clearTimeout(self.$timeoutClick);
        }, self.$options.time + t);
        self.next();
      });

      if (this.$len > 3) {
          $(".sp-switch-box,.sp-anit-time").show();
          self.$anit.animate({"height":"100%"},self.$options.playTime,function() {
            self.$anit.css("height","0px");
          })
        this.$timeIn = setInterval(function() {
            self.$anit.animate({"height":"100%"},self.$options.playTime,function() {
                self.$anit.css("height","0px");
              })
            self.next(1);
        }, this.$options.playTime);

        // this.$el.hover(function() {
        //     clearInterval(self.$timeIn);
        // //   self.$prevEl.finish().fadeIn();
        // //   self.$nextEl.finish().fadeIn();
        // }, function(e) {
        // //   self.$prevEl.finish().fadeOut();
        // //   self.$nextEl.finish().fadeOut();
        //     self.$timeIn = setInterval(function() {
        //         self.next(1);
        //     }, self.$options.playTime);
        // })
        this.$elBox.hover(function() {
            clearInterval(self.$timeIn);
            self.$anit.stop(true,false);
        }, function(e) {
            self.$anit.animate({"height":"100%"},self.$options.playTime,function() {
                self.$anit.css("height","0px");
            })
            self.$timeIn = setInterval(function() {
                self.$anit.animate({"height":"100%"},self.$options.playTime,function() {
                    self.$anit.css("height","0px");
                })
                self.next(1);                
            }, self.$options.playTime);
        })
      }
    },
    blurInit: function() {
      //IE10+ blur
      if (typeof document.msHidden != "undefined") {
        this.isSvg = true;
        var blurList = this.$childAll.find(".gb-blur");
        var count = 0;
        var num = this.$options.showSlide*2;
        var imgSrc = '';
        var gbImgStyle = '';
        var hasOn = false;
        var mw,mh,mx,my,sw,sh,sx,sy;
        for (var i = 0, len = blurList.length; i < len; i++) {
          if (i < num) {
            count = i - num + this.$len < 0 ? i - num + this.$len*2 : i - num + this.$len;
          } else if (i > len - num - 1) {
            count = i - num - this.$len >= this.$len ? i - num - this.$len*2 : i - num - this.$len;
          } else {
            count = i - num;
          }
          imgSrc = blurList[i].src;
          hasOn = $(blurList[i]).parents('.sp-s').hasClass('on');
          $(blurList[i]).css('display', 'none');
          if (this.imgOgSize[count].h > 0) {
            mw = this.imgOgSize[count].w*this.$options.sh/this.imgOgSize[count].h;
            mh = this.imgOgSize[count].h*this.$options.sw/this.imgOgSize[count].w;
            mx = -(this.imgOgSize[count].w*this.$options.sh/this.imgOgSize[count].h - this.$options.sw)/2;
            my = -(this.imgOgSize[count].h*this.$options.sw/this.imgOgSize[count].w - this.$options.sh)/2;
            sw = mw*this.$options.scale;
            sh = mh*this.$options.scale;
            sx = mx*this.$options.scale;
            sy = my*this.$options.scale;
          } else {
            mw = '100%';
            mh = '100%';
            mx = 0;
            my = 0;
            sw = '100%';
            sh = '100%';
            sx = 0;
            sy = 0;
          }
          if (!hasOn) {
            if (this.imgOgSize[count].w > this.imgOgSize[count].h) {
              mx -= mw*0.03/2;
              sx -= sw*0.03/2;
              mw += mw*0.03;
              sw += sw*0.03;
              gbImgStyle = 'width="' + sw + '" height="103%" x="' + sx + '" y="0" data-sw="' + sw + '" data-sh="103%" data-sx="' + sx + '" data-sy="0" data-mw="' + mw + '" data-mh="103%" data-mx="' + mx + '" data-my="0"';
            } else {
              gbImgStyle = 'width="100%" height="' + sh + '" x="0" y="' + sy + '" data-sw="100%" data-sh="' + sh + '" data-sx="0" data-sy="' + sy + '" data-mw="100%" data-mh="' + mh + '" data-mx="0" data-my="' + my + '"';
            }
          } else {
            if (this.imgOgSize[count].w > this.imgOgSize[count].h) {
              mx -= mw*0.03/2;
              sx -= sw*0.03/2;
              mw += mw*0.03;
              sw += sw*0.03;
              gbImgStyle = 'width="' + mw + '" height="103%" x="' + mx + '" y="0" data-sw="' + sw + '" data-sh="103%" data-sx="' + sx + '" data-sy="0" data-mw="' + mw + '" data-mh="103%" data-mx="' + mx + '" data-my="0"';
            } else {
              gbImgStyle = 'width="100%" height="' + mh + '" x="0" y="' + my + '" data-sw="100%" data-sh="' + sh + '" data-sx="0" data-sy="' + sy + '" data-mw="100%" data-mh="' + mh + '" data-mx="0" data-my="' + my + '"';
            }
          }
          blurList[i].insertAdjacentHTML("afterend", '<svg class="gb-blur" width="100%" height="100%" style="left:0;top:0;">\
            <image xlink:href="'+ imgSrc + '" src="' + imgSrc + '" ' + gbImgStyle + ' filter="url(#blur)" />\
          </svg>');
        }
      }
    },
    to: function(w, type, callback) {
      var self = this;
      var hasNum = 0;
      var count = 0;
      var countEl = null;
      var hasOn = false;
      var blurImgList = this.$childAll.find("svg.gb-blur>image");
      this.changeOn(this.$childAll);
      var num = type ? - (this.$len - this.$count) : this.$count;
      if (num < -1) {
        hasNum = this.$len + num;
      } else {
        hasNum = type ? - (this.$len - this.$count) : this.$count;
      }
      for (var i = 0; i < this.$options.showSlide; i++) {
        count = this.$count + i;
        countEl = this.$childAll.filter('[data-count=' + (count >= this.$len ? count - this.$len : count) + ']');
        this.changeOn(countEl, true);
      }
      if (this.isSvg) {
        var svgImgEl,svgData;
        for (var i = 0, len = blurImgList.length; i < len; i++) {
          svgImgEl = $(blurImgList[i]);
          svgData = svgImgEl.data();
          hasOn = svgImgEl.parents('.sp-s').hasClass('on');
          if (!hasOn) {
            svgImgEl.attr({
              width: svgData.sw,
              height: svgData.sh,
              x: svgData.sx,
              y: svgData.sy,
            })
          } else {
            svgImgEl.attr({
              width: svgData.mw,
              height: svgData.mh,
              x: svgData.mx,
              y: svgData.my,
            })
          }
        }
      }

      this.$wpL += w;
      this.$wp.css('margin-left', this.$wpL + 'px');

      if (this.$broswerMsg.broswer == 'IE' && this.$broswerMsg.version < 10) {
        callback(self, hasNum);
        if (self.$len < 5) {
          var el,idx;
          var count = self.$options.showSlide*2 + hasNum;
          var hasOnList = self.$childAll.filter('.on');
          for (var i = 0,len = hasOnList.length; i < len; i++) {
            el = $(hasOnList[i]);
            idx = el.index();
            if (idx < count || idx >= count + 3) {
              self.changeOn(el);
            }
          }
        }
      } else {
        this.$timeout = setTimeout(function() {
          callback(self, hasNum);
          if (self.$len < 5) {
            var el,idx;
            var count = self.$options.showSlide*2 + hasNum;
            var hasOnList = self.$childAll.filter('.on');
            for (var i = 0,len = hasOnList.length; i < len; i++) {
              el = $(hasOnList[i]);
              idx = el.index();
              if (idx < count || idx >= count + 3) {
                self.changeOn(el);
              }
            }
          }
          clearTimeout(self.$timeout);
        }, this.$options.time);
      }
    },
    prev: function(type) {
      var self = this;
      var callback = function(){return false;};
      this.$count -= type ? 1 : this.$options.showSlide;
      if (this.$count < 0) {
        callback = this.cback();
        this.$count += this.$len;
      } else if (this.$count == 0 || (this.$count > this.$len - this.$options.showSlide*2 && this.$count <= this.$len - this.$options.showSlide)) {
        callback = this.cback();
      }
      this.to(type ? this.$minW : this.$maxW, 0, callback);
    },
    next: function(type) {
      var self = this;
      var callback = function(){return false;};
      this.$count += type ? 1 : this.$options.showSlide;
      if (this.$count + this.$options.showSlide > this.$len) {
        callback = this.cback();
        if (this.$count > this.$len) {
          this.$count -= this.$len;
        }
      }
      this.to(-(type ? this.$minW : this.$maxW), 1, callback);
    },
    cback: function() {
      var self = this;
      var callback = function(self, hasNum) {
        self.cbcom(self, hasNum);
      }
      return callback;
    },
    cbcom: function(self, hasNum) {
      self.$wpL = -(self.$minW*(hasNum + self.$options.showSlide*2) - self.$sdWidth + self.$options.space);
      self.$wp.removeClass('ts');
      self.$wp.css('margin-left', self.$wpL + 'px');
      self.$timeoutCb = setTimeout(function() {
        self.$wp.addClass('ts');
        clearTimeout(self.$timeoutCb);
      }, 100);
    },
    changeOn: function(el, type) {
      if (type) {
        el.addClass('on').css({
          'width': this.$options.sw + 'px',
          'height': this.$options.sh + 'px',
          'margin': '0 ' + this.$options.space + 'px'
        }).find('.sp-cp').css({
          'width': this.$options.cpSize + 'px',
          'height': this.$options.cpSize + 'px',
          'top': this.$options.cpT + 'px',
          'right': this.$options.cpR + 'px'
        }).next('.sp-msg').css({
          'width': this.$options.spmsgW + 'px',
          'height': this.$options.spmsgH + 'px',
          'line-height': this.$options.spmsgH + 'px',
          'left': this.$options.spmsgL + 'px',
          'top': this.$options.spmsgT + 'px',
          'font-size': this.$options.spmsgFontSize + 'px'
        })
      } else {
        el.removeClass('on').css({
          'width': this.$options.sw*this.$options.scale + 'px',
          'height': this.$options.sh*this.$options.scale + 'px',
          'margin': (this.$options.sh - this.$options.sh*this.$options.scale)/2 + 'px ' + this.$scaleM + 'px'
        }).find('.sp-cp').css({
          'width': this.$options.cpSize*this.$options.scale + 'px',
          'height': this.$options.cpSize*this.$options.scale + 'px',
          'top': this.$options.cpT*this.$options.scale + 'px',
          'right': this.$options.cpR*this.$options.scale + 'px'
        }).next('.sp-msg').css({
          'width': this.$options.spmsgW*this.$options.scale + 'px',
          'height': this.$options.spmsgH*this.$options.scale + 'px',
          'line-height': this.$options.spmsgH*this.$options.scale + 'px',
          'left': this.$options.spmsgL*this.$options.scale + 'px',
          'top': this.$options.spmsgT*this.$options.scale + 'px',
          'font-size': this.$options.spmsgFontSize*this.$options.scale + 'px'
        })
      }
    },
    supportCss3: function(style) {
      var prefix = ['webkit', 'Moz', 'ms', 'o'],
        i,
        humpString = [],
        htmlStyle = document.documentElement.style,
        _toHumb = function (string) {
          return string.replace(/-(\w)/g, function ($0, $1) {
            return $1.toUpperCase();
          });
        };
      for (i in prefix){
        humpString.push(_toHumb(prefix[i] + '-' + style));
      }
      humpString.push(_toHumb(style));
      for (i in humpString){
        if (humpString[i] in htmlStyle) return true;
      }
      return false;
    },
    getBroswer: function(){
      var sys = {};
      var ua = navigator.userAgent.toLowerCase();
      var s;
      (s = ua.match(/edge\/([\d.]+)/)) ? sys.edge = s[1] :
      (s = ua.match(/rv:([\d.]+)\) like gecko/)) ? sys.ie = s[1] :
      (s = ua.match(/msie ([\d.]+)/)) ? sys.ie = s[1] :
      (s = ua.match(/firefox\/([\d.]+)/)) ? sys.firefox = s[1] :
      (s = ua.match(/chrome\/([\d.]+)/)) ? sys.chrome = s[1] :
      (s = ua.match(/opera.([\d.]+)/)) ? sys.opera = s[1] :
      (s = ua.match(/version\/([\d.]+).*safari/)) ? sys.safari = s[1] : 0;

      if (sys.edge) return { broswer : "Edge", version : sys.edge };
      if (sys.ie) return { broswer : "IE", version : sys.ie };
      if (sys.firefox) return { broswer : "Firefox", version : sys.firefox };
      if (sys.chrome) return { broswer : "Chrome", version : sys.chrome };
      if (sys.opera) return { broswer : "Opera", version : sys.opera };
      if (sys.safari) return { broswer : "Safari", version : sys.safari };
      
      return { broswer : "", version : "0" };
    }
  }
  g.BannerSlide = BannerSlide;
}(window);