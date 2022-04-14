$(function(){
    def={
        page:1,
        count:0,
        dataStatus:true,
        filter_id:'',
        limit:20
    }
    function getUrlParam(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
		var r = window.location.search.substr(1).match(reg);  //匹配目标参数
		if (r != null) return decodeURI(r[2]);
		return ''; //返回参数值
	}
    function init(){
        def.filter_id=getUrlParam('filter_id');
        getList();
    }
    init();
    function getList(){
        if(!def.dataStatus){
            return;
        }
        $('.js-top-load').show();
        def.dataStatus=false;
        $.ajax({
            url: '/top/detail/',
            type: 'GET',
            dataType:'json',
            data:{
                filter_id:def.filter_id,
                page:def.page,
                limit:def.limit
            },
            success: function(data) {
                if(data.data){
                    var html='';
                    var list=data.data;
                    def.count=Math.ceil(data.info.totalCount/def.limit);
                    for(var i=0;i<list.length;i++){
                        html += '<a class="detail-item" href="/details/style/t_'+list[i].t+'-id_'+list[i].id+'-col_'+list[i].col+'/" target="_blank">'+
                            '<img src="'+list[i].cover+'"/>'+
                            '<div class="item-hover"></div>'+
                            '</a>';
                    }
                    $('.js-detail-list').append(html);
                    $('.js-top-load').hide();
                    def.dataStatus=true;
                }else{
                    alert('列表数据为空');
                    location.href='/smarttrends/topstyles/';
                }
            }
        })
    }
    function scrollFun(){
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        var clientHeight = document.documentElement.clientHeight || document.body.clientHeight;
        var scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
       /* 手机缩屏后高度过小 */
        var _height=1000;
        if(clientHeight<1000){
            _height=4000;
        }
        if(scrollHeight > clientHeight && scrollTop + clientHeight >= scrollHeight-_height && def.page < def.count && def.dataStatus) {
            def.page++;
            getList();
        }
    }
    $(window).on("scroll",function(){
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(scrollTop>=500){
            $('.head-layer').show();
        }else{
            $('.head-layer').hide();
        }
        scrollFun();
    });
})
