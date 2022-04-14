/*
* 轮询请求(用于处理用户在线情况)
*/
define(["jquery","general"],function(jquery,general){
    $(function(){
       	var loop_require={
			timer:null,//定时器标识
			stockTime:30000,   //定时器时间
			time:loopTime,	//参数(token验证时间)
			token:loopToken,//参数(token)
			
			//此函数用于定时器调用（PS：不能用this调用的变量）
			require:function(type){
				var self=this;
				clearTimeout(self.timer);
				var ntimes=(new Date()).getTime();
				$.ajax({
					url:"/looprequire/start/?t="+ntimes,
					type:"get",
					dataType:"json",
					data:{"token":self.token,"time":self.time},
					success:function(e){
						if(type!=1){
							if(e.data.stop!=1){
								self.timer=setTimeout(function(){
									self.require();
								},self.stockTime);
							}else{
								console.log("未登录");
							}
						}
						
					},
					error:function(){
						if(type!=1){
							self.timer=setTimeout(function(){
								self.require();
							},self.stockTime);
						}
					}
				})
			},
			
			//初始化函数
			init:function(){
				var self=this;
				self.require(1);

				self.timer=setTimeout(function(){
					self.require();
				},self.stockTime);
			}
		};
		if(loop_require.token!=""){
			loop_require.init();
		}
    });
});