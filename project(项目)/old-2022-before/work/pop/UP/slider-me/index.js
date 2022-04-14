/**
 * Created by liqiaoqiao on 2018/4/19.
 */

$(document).ready(function() {
    $.ajaxSetup({
        async : false
    });
    //随机生成拼图块
    function strockArc(ctx,mX,n){//圆心(x,y)，半径r
        var number = Math.floor(Math.random()*9)%9;
        if ( n>=0 ) number = n;
        switch (number) {
            case 0:
                //上凸下凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX+s/2+r,cY+s);
                ctx.arc(mX+s/2,cY+s,r,2*Math.PI,Math.PI,true);
                ctx.lineTo(mX,cY+s);
                return number;
            case 1:
                //上凸右凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s/2-r);
                ctx.arc(mX+s,cY+s/2,r,1.5*Math.PI,0.5*Math.PI,true);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX,cY+s);
                return number;
            case 2 :
                //上凸左凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX,cY+s);
                ctx.lineTo(mX,cY+s/2+r);
                ctx.arc(mX,cY+s/2,r,0.5*Math.PI,1.5*Math.PI,true);
                return number;
            case 3 :
                //上凸下凸
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX+s/2+r,cY+s);
                ctx.arc(mX+s/2,cY+s,r,0,Math.PI);
                ctx.lineTo(mX,cY+s);
                return number;
            case 4 :
                //上凸右凸
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s/2-r);
                ctx.arc(mX+s,cY+s/2,r,1.5*Math.PI,0.5*Math.PI);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX,cY+s);
                return number;
            case 5 :
                //上凸右凸下凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s/2-r);
                ctx.arc(mX+s,cY+s/2,r,1.5*Math.PI,0.5*Math.PI);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX+s/2+r,cY+s);
                ctx.arc(mX+s/2,cY+s,r,2*Math.PI,Math.PI,true);
                ctx.lineTo(mX,cY+s);
                return number;
            case 6 :
                //上凸右凸左凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s/2-r);
                ctx.arc(mX+s,cY+s/2,r,1.5*Math.PI,0.5*Math.PI);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX,cY+s);
                ctx.lineTo(mX,cY+s/2+r);
                ctx.arc(mX,cY+s/2,r,0.5*Math.PI,1.5*Math.PI,true);
                return number;
            case 7 :
                //上凸下凸右凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s/2-r);
                ctx.arc(mX+s,cY+s/2,r,1.5*Math.PI,0.5*Math.PI,true);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX+s/2+r,cY+s);
                ctx.arc(mX+s/2,cY+s,r,0,Math.PI);
                ctx.lineTo(mX,cY+s);
                return number;
            case 8 :
                //上凸左凹右凹
                ctx.arc(mX+s/2,cY,r,Math.PI,2*Math.PI);
                ctx.lineTo(mX+s,cY);
                ctx.lineTo(mX+s,cY+s/2-r);
                ctx.arc(mX+s,cY+s/2,r,1.5*Math.PI,0.5*Math.PI,true);
                ctx.lineTo(mX+s,cY+s);
                ctx.lineTo(mX,cY+s);
                ctx.lineTo(mX,cY+s/2+r);
                ctx.arc(mX,cY+s/2,r,0.5*Math.PI,1.5*Math.PI,true);
                return number;
        }
    }

    //拼图随机生成位置
    var r = 8,s=40;
    var qX= 260, qY = 120;
    var n, resultData;
    var cX, cY;
    var imgData;
    var isMatch = false;

    var canvas_img = document.getElementById('slide-img2');
    var ctx_img = canvas_img.getContext('2d');

    // var img = document.getElementById('yan_img');
    // ctx_img.drawImage(img, 0, 0, 800, 500);
    var img = new Image();

    var canvas_ceng = document.getElementById('slide-img1');
    var ctx_ceng = canvas_ceng.getContext('2d');

    var mX = 0;//移动距离
    var dX = 0, dX1 = 0; //滑动区域
    var isDrag = false;

    //显示拼图块
    ctx_img.clearRect(0,0,qX,qY);
    ctx_ceng.clearRect(0,0,qX,qY);

    $('.slide-img-nopadding').css('display', 'block');

    var btn = $('.yan_drag_btn');
    var mover = $('.yan_drag');

    //生成图
    getData();

    //按钮点击事件
    btn.on('mousedown', function (e) {
        if (isMatch) return;
        dX = e.pageX;
        dX1 = e.pageX + qX - s;
        btn.html('<->');
        isDrag = true;
    });

    //鼠标移动事件
    mover.on('mousemove', function (e) {
        if (isDrag) {
            var x = e.pageX;
            if (x >= dX && x <= dX1) {
                mX = e.pageX - dX;
            }
            if (x>=dX1) {
                isDrag = false;
                mX = 0;
            }
            btn.css('left', mX);
            $('.moved').css('width', mX);
            ctx_ceng.clearRect(0,0,qX,qY);
            ctx_ceng.lineWidth = 2;
            ctx_ceng.strokeStyle = '#ffffff';

            ctx_ceng.putImageData(imgData, mX,cY-r);
            ctx_ceng.globalCompositeOperation="destination-in";

            ctx_ceng.save();
            ctx_ceng.beginPath();
            ctx_ceng.moveTo(mX, cY);
            ctx_ceng.lineTo(mX+s/2-r,cY);
            strockArc(ctx_ceng,mX,n);
            ctx_ceng.closePath();
            ctx_ceng.fillStyle = 'green';
            ctx_ceng.fill();
            ctx_ceng.clip();

            ctx_ceng.restore();
        }
    });

    $(document).on('mouseup', function (e) {
        isDrag = false;
        btn.html('|||');
        if (mX+1 <= cX+3 && mX+1 >= cX-3){
            testData(resultData);
            if (resultData.flag) {
                isMatch = true;
                $('.exm').css({'background-color': 'transparent', color: 'transparent'});
                $('.moved').html('验证通过');
                $('.yan_drag_text').html('');
            } else {
                $('.exm').css({'background-color': 'red', color: '#fff'});
                setTimeout(function () {
                    $('.exm').css({'background-color': 'transparent', color: 'transparent'});
                },3000);
                getData()
            }
        } else {
            $('.exm').css({'background-color': 'red', color: '#fff'});
            setTimeout(function () {
                $('.exm').css({'background-color': 'transparent', color: 'transparent'});
            },3000);
            getData();
        }
    });
    function testData(data) {
        // $.post('test_data',{ test_time: data.time, test_number: data.number }, function (result) {
        //     data.flag = result.result;
        // });
        data.flag = true;
    }
    function getData() {
        //移动距离置为零
        mX = 0;
        btn.css('left',0);
        $('.moved').css('width',0);

        ctx_img.clearRect(0,0,qX,qY);
        //获取x,y轴坐标及背景图src
        // $.get('data',function (result) {
        //     resultData = result;
        //     cX = result.x;
        //     cY =result.y;
        //     img.src = `img/${result.imgSrc}`;
        // });
        var result = product();
        resultData = result;
        cX = result.x;
        cY =result.y;
        img.src = `images/${result.imgSrc}`;

        img.onload = function () {
            ctx_img.drawImage(img, 0,0,qX,qY);
            setTimeout(function () {
                //背景图
                // ctx_img.globalCompositeOperation="destination-over";

                //右方拼图块
                ctx_img.save();
                ctx_img.lineWidth = 2;
                ctx_img.strokeStyle = '#fff';
                ctx_img.beginPath();
                ctx_img.moveTo(cX, cY);
                ctx_img.lineTo(cX+s/2-r,cY);
                n = strockArc(ctx_img, cX);
                ctx_img.closePath();
                ctx_img.stroke();
                ctx_img.clip();
                imgData = ctx_img.getImageData(cX,cY-r,s+r+1,s+2*r+1);
                ctx_img.fillStyle = 'rgba(255,255,255,0.5)';
                ctx_img.fillRect(0,0,qX,qY);

                ctx_img.restore();
                ctx_img.fillStyle = 'rgba(255,255,255,0)';
                ctx_img.fillRect(0,0,qX,qY);

                //左方拼图块
                ctx_ceng.clearRect(0,0,qX,qY);
                ctx_ceng.lineWidth = 2;
                ctx_ceng.strokeStyle = '#fff';

                ctx_ceng.putImageData(imgData, mX+1,cY-r);
                ctx_ceng.globalCompositeOperation="destination-in";
                ctx_ceng.save();
                ctx_ceng.beginPath();
                ctx_ceng.moveTo(mX, cY);
                ctx_ceng.lineTo(mX+s/2-r,cY);
                strockArc(ctx_ceng,mX+1,n);
                ctx_ceng.closePath();
                ctx_ceng.fillStyle = 'green';
                ctx_ceng.fill();
                ctx_ceng.clip();

                ctx_ceng.restore();
            })
        };
    }

    function product() {
      var time = new Date().getTime(); //时间戳
      var number = ("0000000" + 100000000 * Math.random()).match(/(\d{8})(\.|$)/)[1]; //8位随机数
      var imgSrc;
      var n = Math.floor(Math.random()*5)%5;
      imgSrc = `${n+1}.jpg`;
      var qX = 260, qY = 120, s=50;
      var rX = Math.random()*(qX-3*s-s/2) + 2*s;
      var rY = Math.random()*(qY-s-s/2) + s/2;
      var data = {
          time: time,
          number: number,
          imgSrc: imgSrc,
          x: rX,
          y: rY
      };
      // cache.put('test_time', time);
      // cache.put('test_number',number);
      return data;
    };
});