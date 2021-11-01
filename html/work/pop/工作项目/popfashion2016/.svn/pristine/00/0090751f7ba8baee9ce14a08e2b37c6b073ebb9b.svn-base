$(function(){       
    f_BlocksIt();
    var lazyloadimg = $(".lazyload img");
    lazyloadimg.length && lazyloadimg.lazyload({effect : "show",failure_limit:12,load:f_BlocksIt});        
    function f_BlocksIt(){                 
            if($(".con_width").width() <1500){
        		$('.picbox').masonry({
                    gutterWidth: 47,
                    itemSelector: 'li',
                    isAnimated: false
            	});
        	}
        	else{
        		$('.picbox').masonry({
                    gutterWidth: 57,
                    itemSelector: 'li',
                    isAnimated: false
            	});
        	}               
    }
    $(window).resize(function() {
        f_BlocksIt();
    });
    $(window).scroll(function() {
        f_BlocksIt();
    });

    var P_UserType_action = '4';
    if(P_UserType=='1'||P_UserType=='2'){
        P_UserType_action = '1';
    }else if(P_UserType=='3'){
        P_UserType_action = '2';
    }else if(P_UserType=='4'){
        P_UserType_action = '3';
    }else if(P_UserType=='5'){
        P_UserType_action = '4';
    }

    var statistics_action_token = '';
    if( typeof statistics_token == 'undefined'){
        statistics_action_token = '';
    }else{
        statistics_action_token = statistics_token;
    }
    var action = {		//统计数据
        token: statistics_action_token,
		iUserType: P_UserType_action,		//用户类型
        	
		iUserId: '',  			//用户ID
		sChildUserId: '', 		//子账号id
		sTableName: '', 		//假表名
		iPrid: '',				//当前详情ID
		
		iColumnId: '',			//主栏目ID
		iSubColumnId: '',  		//子栏目ID
		sSelfUrl: location.href, 			//当前URL
		sRefererUrl: location.href,		//上个页面URL
		iSite: 1, 				//站点
		sLang: 'cn',			//语言
		
		iVisit: 1    		//是否能访问 0-无访问权限 1-有访问权限
    }

    setAction();
    function setAction(){  // 配置统计数据
        action.sRefererUrl = document.referrer||'';

        action.iUserId = P_AccountId;	//主账号
        if(P_UserType=='2'){
            action.sChildUserId = P_UserId;	//子账号  
        }

        var _href = location.href;
        if( _href.indexOf('/books/innermaga') != -1 ){  // 流行画册
            var id_arr = ( _href.split('id_')[1]||'' ).split('I')||[];
            var this_id = id_arr[id_arr.length-1];
            action.iPrid = this_id.replace(/^([0-9a-z]+)(.*)/i,'$1')+'';
            action.sTableName = 'imgcol_set';
            action.iSubColumnId = '130';
            action.iColumnId = '6';
        }else if( $('#param').length>0 ){
            var param_el = $('#param');
            action.sTableName = param_el.data('t')||'';
            action.iPrid = param_el.data('id')||'';
            action.iSubColumnId = param_el.data('col')||'';
        }else{
            var _id = parseFloat( _href.split('id_')[1]||'' );
            var _col = parseFloat( _href.split('col_')[1]||'' );
            var _t = ( ( _href.split('t_')[1]||'' ).split('-')[0]||'' ).split('/')[0]||'';
            action.sTableName = param_el.data('t')||'';
            action.iPrid = param_el.data('id')||'';
            action.iSubColumnId = param_el.data('col')||'';
        }
        
    }

    // 浏览量统计
    actionView();
    function actionView(){
        $.ajax({
            "url": "//api.pop136.com/internal/statistics.php?action=view&" + Math.random(),
            "data": action,
            "type": 'get',
            'dataType': "jsonp",
            'jsonp': "callback"
        });
    }

    // 下载统计
    var action_down = true, action_timer = null;
    function actionDown(){
        if( action_down ){     // 在能够下载统计时， 排除书籍的
            action_down = false;
            $.ajax({
                "url": "//api.pop136.com/internal/statistics.php?action=down&" + Math.random(),
                "data": action,
                "type": 'get',
                'dataType': "jsonp",
                'jsonp': "callback"
            });
            
            clearTimeout(action_timer);
            action_timer = setTimeout(function(){
                action_down = true;
            }, 3000);
        }
    }

    // 下载统计
    $('a.download').on('click', function(){
        if( oCommon.downloadPrivilege()){
            actionDown();
        }else{
            return false;
        }
    })

 
});
