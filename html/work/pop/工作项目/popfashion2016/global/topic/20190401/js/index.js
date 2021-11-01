

  var mySwiper = new Swiper("#title-list", { 
    speed:1400, 
    preventInteractionOnTransition : true,
    mousewheel: {
      releaseOnEdges: false,
      eventsTarged: '.nav-box'      
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    },
    thumbs: {
      swiper: {
        el: '#thumbs',
        spaceBetween: 10,
        slidesPerView: 4,
        watchSlidesVisibility: true,
      },
    },
    on:{
      slideChange: function(){
        mouseHandle(this.activeIndex);
      },
    },



  });
  /* 验证画布类型 */
  var type = "WebGL";
  if (!PIXI.utils.isWebGLSupported()) {
    type = "canvas";
  }
  PIXI.utils.sayHello(type);
  /* 画布色值数组 */
  var canvasColorArr = [0xe5c8a5, 0xe6b133, 0xfad2d9, 0xc397cc];
  /* 遮罩色值数组 */
  var shapeColorArr = [0xdfbb96, 0xcc8f00, 0xfaa0b0, 0xb95acc];
  /* 圆形色值数组 */
  var circleColorArr = [0x525354, 0xcf9b00, 0xbf0033, 0xc397cc];
  /*按钮色值*/
  var btnArr = ['rgba(223,187,150,0.2)','rgba(204,143,0,0.2)','rgba(250,160,176,0.2)','rgba(185,90,204,0.2)']
  /* 图片数组 */
  var imgArr = [
    "/global/topic/20190401/images/1.png",
    "/global/topic/20190401/images/2.png",
    "/global/topic/20190401/images/3.png",
    "/global/topic/20190401/images/4.png"
  ];
  /* 内容块计数 */
  var countNum = 0;
  /* 蒙层控制器对象 */
  var maskConArr = [
    new PIXI.Container(),
    new PIXI.Container(),
    new PIXI.Container()
  ];
  /* 背景图对象 */
  var bgImgArr = [];
  /* 遮罩对象 */
  var shapeArr = [
    new PIXI.Graphics(),
    new PIXI.Graphics(),
    new PIXI.Graphics()
  ];
  /* 蒙层对象 */
  var maskArr = [
    new PIXI.Graphics(),
    new PIXI.Graphics(),
    new PIXI.Graphics()
  ];
  /* 线条对象控制器 */
  var lineCon = new PIXI.Container();
  /* 线条对象 */
  var lineArr = [];
  /* 圆形对象 */
  var circleArr = [
    new PIXI.Graphics(),
    new PIXI.Graphics(),
    new PIXI.Graphics()
  ];
  
  /* 所有比值均指画布宽高与各个精灵的宽高、xy的比值 */
  /* 线条间距比 */
  var lineRatio = 9.09;
  /* 画布高与背景图高的比值 */
  var winBgRatio = 1.39;
  /* 背景图宽高比 */
  var whRatio = 1.78;
  /* 蒙层高度比值 */
  var maskHRatio = 4.62;
  /* 蒙层宽度比值数组 */
  var maskWRatioArr = [2.99, 3.17, 3.7, 2.63, 2.22];
  /* 蒙层y比值数组 */
  var maskYRatioArr = [6.72, 2.57, 1.58];
  /* 蒙层x比值数组 */
  var maskXRatioArr = [2.25, 3.51, 2.5, 4.55, 3.03];
  /* 各块蒙层比值顺序 */
  var maskRatioObj = {
    w: [[0, 1, 2], [2, 1, 2], [3, 4, 2], [2, 3, 3]],
    x: [[0, 1, 2], [1, 0, 0], [2, 3, 2], [4, 2, 3]]
  };
  /* 滚动状态 */
  var handleState = false;
  /* 滚轮延迟调用 */
  var handleTimeOut;
  /* 背景图滚轮延迟调用 */
  var bgImgTimeout;
  /* 动画帧数 */
  var fm = 1400;
  /* 动效方式 */
  var tweenEase = Sine.easeInOut;
  /* 初始化动画库 */
  var TimelineLite = new TimelineLite();
  /* 创建 pixi 应用 */
  var app = new PIXI.Application({
    width: document.documentElement.clientWidth,
    height: $("#canvas-content")[0].offsetHeight,       
    backgroundColor:0xFFFFFF,
    autoResize: true,
    antialias: true,
    resolution: 1
  }); 
  /* 设施画布样式 */
  app.renderer.backgroundColor = canvasColorArr[countNum];
  app.renderer.view.style.position = "absolute";
  app.renderer.view.style.left = 0;
  app.renderer.view.style.top = '116px';
  app.renderer.autoResize = true;
  setTimeout(function(){
    document.body.appendChild(app.view);
  },200)
  
  /* 重置画布尺寸 */
  window.onresize = function() {
    app.renderer.resize(window.innerWidth, $("#canvas-content")[0].offsetHeight);
    bgImgRsCon(bgImgArr);
    maskHandle({ time: 0 });
  };
  
  /* 绘制 */
  init();
  /**
   * @description: 初始化
   */
  function init() {
    drawBgImg(countNum);
    document.addEventListener("mousemove", circleMove);
    document.addEventListener("mouseup", function() {
    });    
    
  }
  /**
   * @description: 鼠标滚轮事件
   */
  function mouseHandle(num) {

    if (handleState) {
      return;
    }
    countNum = num;    
    handleState = true;
    bgColorHandle(countNum);
    shapeHandle();
    bgImgHandle();
    maskHandle();
    circleHandle();    
  }
  /**
   * @description: 蒙层滚轮
   */
  function maskHandle(data) {
    var t = fm / 1000 - 0.5;
    if (data) {
      t = data.time;
    }
    maskArr.map(function(mask, index) {
      TweenMax.to(mask, t, {
        ease: tweenEase,
        pixi: {
          width:
            app.renderer.width /
            maskWRatioArr[maskRatioObj.w[countNum][index]],
          height: app.renderer.height / maskHRatio,
          x:
            app.renderer.width /
            maskXRatioArr[maskRatioObj.x[countNum][index]],
          y: app.renderer.height / maskYRatioArr[index]
        }
      });
    });
  }
  /*
   * @description: 背景图滚轮
   * @param {type}
   * @return:
   */
  function bgImgHandle() {
    clearTimeout(bgImgTimeout);
    bgImgTimeout = setTimeout(function() {
      TimelineLite.to(bgImgArr, 0, {
        ease: tweenEase,
        pixi: { scale: 1 },
        onStart: function() {
          changeBgImg();
        }
      }).to(bgImgArr, fm / 1000 / 2, {
        ease: Sine.easeOut,
        pixi: { scale: 1 }
      });
    }, fm / 2 - 0.3 * 1000);
  }
  /**
   * @description: 更换背景图
   */
  function changeBgImg() {
    bgImgArr.map(function(image, index) {
      var textureButton = PIXI.Texture.fromImage(imgArr[countNum]);
      image.texture = textureButton;
    });
  }
  /**
   * @description: 遮罩滚轮
   */
  function shapeHandle() {
    drawShade();
    TweenMax.to(shapeArr, fm / 1000 - 0.5, {
      ease: tweenEase,
      pixi: {
        x: app.renderer.width - 50
      },
      onComplete: function() {
        TweenMax.to(shapeArr, 0, {
          ease: tweenEase,
          pixi: {
            x: -app.renderer.width + 50
          }
        });
      }
    });
  }
  /**
   * @description: 圆形滚轮
   */
  function circleHandle() {
    TweenMax.to(circleArr, 0, {
      delay: fm / 1000 / 2 - 0.3,
      onComplete: function() {
        drawCircle();
      }
    });
  }
  /**
   * @description: 背景色滚轮
   * @param {滚动方向}
   */
  function bgColorHandle(countNum) {
    $("#shape")
      .removeClass()
      .addClass("count" + countNum);
      drawBgColor();
    clearTimeout(handleTimeOut);
    handleTimeOut = setTimeout(function() {
      handleState = false;
    }, fm);
  }
  /**
   * @description: 绘制背景动画
   */
  function drawBgColor() {
    $(".switch-btn-box>div").css("backgroundColor",btnArr[countNum]);
    app.renderer.backgroundColor = Number(
      colorRGBtoHex($("#shape").css("backgroundColor"))
    );
    if (handleState) {
      requestAnimationFrame(drawBgColor);
    }
  }
  /**
   * @description: RGB转16进制
   * @param {rgb(0,0,0)}
   * @return: 0x000000
   */
  function colorRGBtoHex(color) {
    var rgb = color.split(",");
    var r = parseInt(rgb[0].split("(")[1]);
    var g = parseInt(rgb[1]);
    var b = parseInt(rgb[2].split(")")[0]);
    var hex =
      "0x" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    return hex;
  }
  /**
   * @description: 绘制背景图片
   * @param {内容块计数}
   */
  function drawBgImg(count) {
    drawShade();
    drawCircle();
    maskConArr.map(function(container, index) {
      bgImgArr[index] = PIXI.Sprite.fromImage(imgArr[count], true);
      bgImgArr[index].anchor.set(0.5);
      bgImgRsCon(bgImgArr[index]);
      container.addChild(bgImgArr[index]);
      container.addChild(circleArr[index]);
      container.addChild(shapeArr[index]);
      drawMask(container, bgImgArr[index], index);
      bgImgArr[index].mask = maskArr[index];
      circleArr[index].mask = maskArr[index];
      shapeArr[index].mask = maskArr[index];
      app.stage.addChild(container);
    });
  }
  /**
   * @description: 重置背景图尺寸位置过渡
   * @param {背景图对象/数组}
   */
  function bgImgRsCon(bgImage) {
    if (!Array.isArray(bgImage)) {
      bgImgResize(bgImage);
    } else {
      bgImage.map(function(img, index) {
        bgImgResize(img);
      });
    }
  }
  /**
   * @description: 背景图尺寸位置
   * @param {背景图对象}
   */
  function bgImgResize(bgImage) {
    bgImage.height = app.renderer.height / winBgRatio;
    bgImage.width = bgImage.height * whRatio;
    bgImage.x = app.renderer.width / 2;
    bgImage.y = app.renderer.height / 2;
  }
  /**
   * @description: 绘制遮罩
   */
  function drawShade() {
    shapeArr.map(function(shape, index) {
      shape.clear();
      shape.beginFill(shapeColorArr[countNum]);
      shape.drawRect(0, 0, app.renderer.width, app.renderer.height);
      shape.x = -shape.width + 50;
      shape.y = 0;
      shape.endFill();
    });
  }
  /**
   * @description: 绘制蒙层
   * @param {控制器, 背景图对象, 位置}
   */
  function drawMask(container, bgImage, index) {
    maskRsCon(maskArr[index], index);
    container.addChild(maskArr[index]);
  }
  /**
   * @description: 重置蒙层尺寸位置过渡
   * @param {蒙层对象数组, ?位置}
   */
  function maskRsCon(maskArr, index) {
    if (!Array.isArray(maskArr)) {
      maskResize(maskArr, index);
    } else {
      maskArr.map(function(mask, index) {
        maskResize(mask, index);
      });
    }
  }
  /**
   * @description: 蒙层尺寸位置
   * @param {蒙层对象, 位置}
   */
  function maskResize(mask, index) {
    mask.clear();
    mask.beginFill(0xffffff);
    mask.drawRect(
      0,
      0,
      app.renderer.width / maskWRatioArr[maskRatioObj.w[countNum][index]],
      app.renderer.height / maskHRatio
    );
    mask.x =
      app.renderer.width / maskXRatioArr[maskRatioObj.x[countNum][index]];
    mask.y = app.renderer.height / maskYRatioArr[index];
    mask.endFill();
  }
  /**
   * @description: 绘制圆形
   */
  function drawCircle(container) {
    circleArr.map(function(circle, index) {
      circle.clear();
      circle.beginFill(circleColorArr[countNum], 0.5);
      circle.drawCircle(0, 0, 260);
      circle.endFill();
      circle.x = app.renderer.width / 2;
      circle.y = app.renderer.height / 2;
    });
  }
  /**
   * @description: 重置圆形
   * @param {type}
   * @return:
   */
  function circleMove(e) {
    var ratio = 7.5;
    var x = (e.pageX - app.renderer.width / 2) / ratio;
    var y = (e.pageY - app.renderer.height / 2) / ratio;
    TweenMax.to(circleArr, 0.8, {
      ease: Sine.easeOut,
      pixi: {
        x: app.renderer.width / 2 + x,
        y: app.renderer.height / 2 + y
      }
    });
  }