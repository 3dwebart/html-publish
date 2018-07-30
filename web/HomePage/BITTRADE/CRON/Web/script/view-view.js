var bean = $.parseJSON($.base64.decode(jsonObject));

function historyBack(){
    history.go(-1);
}


var getPageParam = function(param) {
    var found;
    document.URL.split("#").forEach(function(item) {
        if (param ==  item.split(":")[0]) {
            found = item.split(":")[1];
        }
    });
    return found; 
};

$(document).ready(function(){
    var listurl = bean.link.list;
    var parampage = getPageParam('page');
    if(document.URL.indexOf('#page:')){
        if(typeof parampage!=='undefined'){
            listurl = bean.link.list+'#page:'+parampage;
        }
    }

    $("#btn_list").click(function(){
        $(location).attr('href', listurl);
    });
    
    $("#btn_write").click(function(){
        $(location).attr('href',bean.link.write);
    });
    
    //초기데이터 셋팅
    for (var key in bean.data){
        
        var datavalue = bean.data[key]+'';
        datavalue = datavalue.replace(/\\'/gi,"'");
        datavalue = datavalue.replace(/\\"/gi,"\"");
        datavalue = datavalue.replace(/\\r\\n/gi,"<br />");
        
        if(bean.data[key]){$('span#'+key).html(datavalue)}
    }
    
    if(bean.result<1){
        $('body').viewDialogMessage('<p>'+bean.resultMsg+'</p>',historyBack);
    }
    
});