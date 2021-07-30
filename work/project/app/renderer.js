const cp = require('child_process');
const $ = require('jquery');
$('#open-php54').click(function(){
	cp.exec('start cmd.exe /c docker exec -it php54-fpm /bin/bash');
});
$('#open-php72').click(function(){
	cp.exec('start cmd.exe /c docker exec -it php72-fpm /bin/bash');
});
$('body').on('click','.internet',function(){
	var cmd = $('#internet').find('option:selected').data('cmd');
	var cwd = $('#internet').find('option:selected').data('cwd');
	cp.exec('start '+cmd+' http://www.pop-fashion.com',{cwd});
});

var setInternetApp = function(){
	var cmds = [
		'REG QUERY HKEY_CURRENT_USER\\SOFTWARE\\Clients\\StartMenuInternet',
		'REG QUERY HKEY_LOCAL_MACHINE\\SOFTWARE\\Clients\\StartMenuInternet',
		'REG QUERY HKEY_LOCAL_MACHINE\\SOFTWARE\\WOW6432Node\\Clients\\StartMenuInternet'
	];
	var cmdstr = cmds.join('&&');
	cp.exec(cmdstr, (error, stdout, stderr) => {
		if (error) {
		  console.log('获取应用信息错误'+error);
		  return;
		}
		var apps=[];
		if(stdout){
			arr = stdout.trim().split('\r\n');
			for(var i in arr){
				if(arr[i] && arr[i].indexOf('StartMenuInternet\\')>-1){
					apps.push(arr[i]);
				}
			}
			var temp={};
			for(var i in apps){
				cp.exec('REG QUERY "'+apps[i]+'\\shell\\open\\command"',(error, stdout, stderr) => {
					if (error) {
						console.log('获取应用信息错误');
						return;
					}
					var items = stdout.trim().split('\r\n');
					var cmd = items[1].trim().split('    ');
					var cmd = cmd[2].replace(/\"/g,'');
					var cwd = cmd.replace(/(.*)\\([^\\]+)/g,'$1');
					var cmd = cmd.replace(/(.*)\\([^\\]+)/g,'$2');
					if(!temp[cmd]){
						temp[cmd] = cwd;
						var name = getBrowserName(cmd);
						$('#internet').append(`<option data-cmd="${cmd}" data-cwd="${cwd}">${name}</option>`);
					}
				});
			}
			
		}
	});
} 
var obj = {
	'360se.exe':'360浏览器',
	'360chrome.exe':'360极速浏览器',
	'chrome.exe':'谷歌浏览器',
	'iexplore.exe':'IE浏览器',
	'msedge.exe':'Edge浏览器',
	'SogouExplorer.exe':'搜狗浏览器',
	'firefox.exe':'火狐浏览器',
}
var getBrowserName = function(cmd){
	if(obj[cmd]){
		return  obj[cmd];
	}else{
		return cmd;
	}
}
setInternetApp();