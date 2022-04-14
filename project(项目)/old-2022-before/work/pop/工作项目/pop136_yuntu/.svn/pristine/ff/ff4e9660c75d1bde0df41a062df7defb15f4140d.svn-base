
$(function(){
    var $js_table = document.getElementById("js-vip-table");
    var len = $js_table.tBodies[0].rows.length;
    for(var i=0;i<len;i++){
        if(i>1){
            //奇数行换色
            if(i%2==0){
                $js_table.tBodies[0].rows[i].setAttribute("class","odd-colour");
            }
            //最后一个td字体换色
            var _td=$js_table.tBodies[0].rows[i].children;
            for(var j=0;j<_td.length;j++){
                if(j==_td.length-1){
                    _td[j].setAttribute("class","font-td");
                }
            }
        }
    }
    //在线qq
    $("body .js-qq-btn").each(function(i){
        var id_txt="js-qq-btn"+(i+1);
        var type=$(this).attr("data-type") || "";
        var qq_number=0;
        if(type!="" && type==1){
            qq_number=800020016;            //售后
        }else{
            qq_number=800030036;            //售前
        }
        $(this).attr("id",id_txt);
        BizQQWPA.addCustom({
            aty:0,
            nameAccount:qq_number,
            selector:id_txt
        });
    });
})
