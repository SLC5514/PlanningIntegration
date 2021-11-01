/**
 * Created by Administrator on 2016/11/21.
 */

var mapContainer ,  //读取对象
    fLng,       //经度
    fLat,       //纬度
    marker;     //标记地址
function initMap( ) {
    if( typeof mapContainer != 'object' ) {
        //初始化地图对象，加载地图
        //初始化加载地图时，若center及level属性缺省，地图默认显示用户当前城市范围
        mapContainer = new AMap.Map('container', {
            resizeEnable: true
        });
    }
}
function search_map( _this ) {
    if ($("input[name='activityArea']").val() == 2) {//活动地点是国外
        return false;
    }
    initMap();

    var cityval = $("#city").text() ? $.trim($("#city").text()) : '上海市' ;
    mapContainer.setCity( cityval );

    AMap.service(["AMap.PlaceSearch"], function() {
        var placeSearch = new AMap.PlaceSearch({
            map: mapContainer
        });
        placeSearch.search( _this.value );
    });
    getclickmapMark();
}
function getclickmapMark(){
    initMap();

    //为地图注册click事件获取鼠标点击出的经纬度坐标
    mapContainer.on( 'click', function( e ) {

        fLng = e.lnglat.getLng();
        fLat = e.lnglat.getLat();
        //每次标注前先清除原有标注， 再标注
        if (marker) {
            marker.setMap(null);
            marker = null;
        }
        marker = new AMap.Marker({
            icon:"/global/images/marker_sprite.png",
            position:[fLng, fLat],
            draggable: true,
            cursor: 'move',
            raiseOnDrag: true
        });
        //添加已标记提示
        $("#mark").show();
        marker.setMap( mapContainer );

        $("#fLng").val(fLng);
        $("#fLat").val(fLat);
        regeocoder();
    });
};


//根据经纬度获取地图地址 //逆地理编码
function regeocoder() {
    var geocoder = new AMap.Geocoder({
        radius: 1000,
        extensions: "all"
    });
    lnglatXY = [fLng, fLat];
    geocoder.getAddress( lnglatXY, function( status, result ) {
        if (status === 'complete' && result.info === 'OK') {
            var address = result.regeocode.formattedAddress; //返回地址描述
            $("input[name='activityAddrMap']").val(address);
        }
    });
};

function markAuto( _fLng , _fLat ) {
    initMap();

    fLng = _fLng;
    fLat = _fLat;
    //每次标注前先清除原有标注， 再标注
    if (marker) {
        marker.setMap(null);
        marker = null;
    }
    marker = new AMap.Marker({
        icon:"/global/images/marker_sprite.png",
        position:[_fLng, _fLat],
        draggable: true,
        cursor: 'move',
        raiseOnDrag: true
    });
    marker.setMap( mapContainer );
    $("#fLng").val(fLng);
    $("#fLat").val(fLat);
    regeocoder();
}