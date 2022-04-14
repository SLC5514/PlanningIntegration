/*
    #author lut000
    #date   2018/09/27
*/

$(function(){
    var def={
        is_show_nav:false,
        chart_data:[
            {
                "id":1,
                "name":"已登录",
                "sColorNumber":"12-0752TPX",
                "value":2,
                "itemStyle":{
                    "normal":{
                        "color":"#67a5fe"
                    }
                },
                "label":{
                    "normal":{
                        "textStyle":{
                            "color":"#000"
                        }
                    }
                },
                "labelLine":{
                    "normal":{
                        "lineStyle":{
                            "color":"#000"
                        }
                    }
                },
                "iSort":"4999"
            },
            {
                "id":2,
                "name":"未登录",
                "sColorNumber":"12-0752TPX",
                "value":31,
                "itemStyle":{
                    "normal":{
                        "color":"#f5cc61"
                    }
                },
                "label":{
                    "normal":{
                        "textStyle":{
                            "color":"#000"
                        }
                    }
                },
                "labelLine":{
                    "normal":{
                        "lineStyle":{
                            "color":"#000"
                        }
                    }
                },
                "iSort":"4999"
            }
        ]
    };

    getData();
    function getData(){
        pop_fashion_global.fn.subAjax({
            url:'/member/dataReport/?user_id='+user_id+'&hash='+hash,
            data:{},
            successFunc:setDom,
            errorFunc:function(data){
                if(data.code==1){
                    msg.msg({'txt':data.message},1200);
                }
            }           
        })
    };

    function setDom(data){                                                                  //生成dom
        var tag1=$('.js-site-data-table');
        var tag2=$('.js-msite-data-table');
        var _html1='',_html2='';
        var ndata=data.data || {};

        var main_login_days = ndata.main_login_days || {};
        var main_day = $(".js-main-day span");
        main_day.eq(0).text(main_login_days.value?main_login_days.value:0);
        main_day.eq(1).text(main_login_days.value?main_login_days.value:0);

        main_day.eq(2).text(main_login_days.rank?main_login_days.rank:0);
        main_day.eq(3).text(main_login_days.rank?main_login_days.rank:0);

        // main_day.eq(4).text(main_login_days.morethan?(main_login_days.morethan*100).toFixed(0):0);
        // main_day.eq(5).text(main_login_days.morethan?(main_login_days.morethan*100).toFixed(0):0);

        var main_login_times = ndata.main_login_times || {};
        var main_times = $(".js-main-times span");
        main_times.eq(0).text(main_login_times.value?main_login_times.value:0);
        main_times.eq(1).text(main_login_times.value?main_login_times.value:0);
        $(".js-main-times p>label").text(ndata.mainInfo.account || '--');

        main_times.eq(2).text(main_login_times.rank?main_login_times.rank:0);
        main_times.eq(3).text(main_login_times.rank?main_login_times.rank:0);

        // main_times.eq(4).text(main_login_times.morethan?(main_login_times.morethan*100).toFixed(0):0);
        // main_times.eq(5).text(main_login_times.morethan?(main_login_times.morethan*100).toFixed(0):0);
 
        var login_rate=ndata.loginRate || {};
        var rate_ele=$('.js-rate-list span');
        rate_ele.eq(0).text(login_rate.value?(login_rate.value*100).toFixed(0):0);
        rate_ele.eq(1).text(login_rate.value?(login_rate.value*100).toFixed(0):0);

        rate_ele.eq(2).text(login_rate.rank?login_rate.rank:'--');
        rate_ele.eq(3).text(login_rate.rank?login_rate.rank:'--');

        // rate_ele.eq(4).text(login_rate.morethan?(login_rate.morethan*100).toFixed(0):0);
        // rate_ele.eq(5).text(login_rate.morethan?(login_rate.morethan*100).toFixed(0):0);

        var login_days=ndata.loginDays || {};
        var day_ele=$('.js-day-list span');
        day_ele.eq(0).text(login_days.value?login_days.value:0);
        day_ele.eq(1).text(login_days.value?login_days.value:0);

        day_ele.eq(2).text(login_days.rank?login_days.rank:'--');
        day_ele.eq(3).text(login_days.rank?login_days.rank:'--');

        // day_ele.eq(4).text(login_days.morethan?(login_days.morethan*100).toFixed(0):0);
        // day_ele.eq(5).text(login_days.morethan?(login_days.morethan*100).toFixed(0):0);



        var login_times=ndata.loginTimes || {};
        var time_ele=$('.js-time-list span');
        time_ele.eq(0).text(login_times.value?login_times.value:0);
        time_ele.eq(1).text(login_times.value?login_times.value:0);

        time_ele.eq(2).text(login_times.rank?login_times.rank:'--');
        time_ele.eq(3).text(login_times.rank?login_times.rank:'--');

        // time_ele.eq(4).text(login_times.morethan?(login_times.morethan*100).toFixed(0):0);
        // time_ele.eq(5).text(login_times.morethan?(login_times.morethan*100).toFixed(0):0);


        var login_days_top=ndata.loginDaysTop5 || [];
        $('.js-day-list label').eq(0).text(login_days_top[0]?login_days_top[0].account:'--');
        setTopDom(login_days_top,$('.js-day-top-list'),1);

        var login_times_top=ndata.loginTimesTop5 || [];
        $('.js-time-list label').eq(0).text(login_times_top[0]?login_times_top[0].account:'--');
        setTopDom(login_times_top,$('.js-time-top-list'),2);

        var table_data=ndata.childInfo || {};

        var main_info=ndata.mainInfo || {};
        main_info.sChildAccount=ndata.mainInfo.account;

        setTableDom({'1':main_info});
        setTableDom(table_data);



        var login_rate_chart=ndata.loginRateChart || {};
        def.chart_data[0].value=login_rate_chart.login_cnt;
        def.chart_data[1].value=login_rate_chart.total_cnt-login_rate_chart.login_cnt;
        if(!$.isEmptyObject(login_rate_chart)){
            setChart($('#chart-div')[0],def.chart_data);
        }

    };

    function setTableDom(data){                                                                  //生成表格
        var tag1=$('.js-site-data-table');
        var tag2=$('.js-msite-data-table');
        var _html1='',_html2='';
        for(var key in data){
            var download=data[key]['download'] || '--';
            var ip = data[key]['ip'] ? '<a href="http://www.ip138.com/ips138.asp?ip=' + data[key]['ip'] + '" title="点击查看IP归属地" target="ip">' + data[key]['ip'] + '</a>' : '--';
            var last_login=data[key]['last_login'] || '--';
            var login_days=data[key]['login_days']?data[key]['login_days']+'天':'<span style="color:red">未登录</span>';

            var login_times=data[key]['login_times']?data[key]['login_times']+'次':'--';
            var region=data[key]['region'] || '';
            var sChildAccount=data[key]['sChildAccount'] || '--';
            // var account = sChildAccount.substr(0,3)+'****'+sChildAccount.substr(7);
            var report_view=data[key]['report_view'] || '--';
            var style_view=data[key]['style_view'] || '--';

            _html1+='<tr>'+
            '<td>'+sChildAccount+'</td>'+
            '<td>'+login_days+'</td>'+
            '<td>'+login_times+'</td>'+
            '<td>'+last_login+'</td>'+
            '<td>'+ip + '</td>'+
            '<td>'+report_view+'</td>'+
            '<td>'+style_view+'</td>'+
            '<td>'+download+'</td>'+
            (key == 1 ? '<td>--</td>':'<td><a class="unbind-btn" href="/member/associate/?key='+sChildAccount+'#anchor" title="解除绑定" target="unbind">解除绑定</a></td>')+
            '</tr>';

            _html2+='<tr>'+
            '<td>'+sChildAccount+'</td>'+
            '<td>'+login_days+'</td>'+
            '<td>'+report_view+'</td>'+
            '<td>'+style_view+'</td>'+
            '</tr>';
        }


        if(_html1!=''){
            tag1.append(_html1);
            tag2.append(_html2);
        }
    };


    function setTopDom(data,tag,type){                                                                  //生成排行榜
        var _html='',str=type==1?'天':'次';
        var max_val=0;
        for(var i=0,len=data.length;i<len;i++){
            var account=data[i]['account'] || '--';
            var value=data[i]['value'] || 0;
            if(i==0){
                max_val=value;
            }
            var percent=value/max_val*100;
            _html+='<li>'+
            '<span>'+account+'</span>'+
            '<div><div style="width:'+percent+'%"></div></div>'+
            '<label>'+value+str+'</label>'+
            '</li>';
        }


        if(_html!=''){
            tag.html(_html);
        }
    };

    





    
    function setChart(ele,data){                                                                    //生成图表
        // 基于准备好的ele，初始化echarts实例
        var myChart = echarts.init(ele);
        // if( aco!="" && type=="aco"){
        //     var formatter = function(e){
        //         return e.data.sColorNumber;
        //     }
        // }else if(con !="" && type=="con"){
        //     var formatter = "{c}";
        // }else{
        //     var formatter=function(){
        //         return "";
        //     }
        // }
        var formatter = "{c}";
        var _data = data;
        // 指定图表的配置项和数据
        var option = {
            tooltip: {
                trigger: 'item',
                formatter: formatter
            },
            series: [
                {
                    name: '登录率',
                    type: 'pie',
                    radius: ['40%', '60%'],
                    data: _data,
                    label: {
                        normal: {
                            // formatter: '{b}{c}人\n\n{d}%'
                            formatter: function (params, ticket) {
                                params.percent = parseInt(params.percent.toFixed(0));
                                // console.log(params);
                                return params.name + params.value + '人\n\n' + params.percent + '%';
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            length: 15,
                            length2: 20
                        }
                    },
                    itemStyle: {
                        normal: {
                            shadowBlur: 5,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.2)'
                        }
                    }
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);


        $(window).on('resize',function(){
            pop_fashion_global.fn.throttle(function(){

                myChart.resize();
            },myChart,[],200);
        });
    }
});