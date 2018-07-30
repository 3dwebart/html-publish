var bean = $.parseJSON($.base64.decode(jsonObject));

function historyBack(){
    history.go(-1);
}

function deleteAction(){
    var data = {'mbNo':bean.data.mbNo};
    var result = -1;
    $('body').showDialogProgress();
    console.log(data);
    $.post(bean.link.del,
        data,
        function(json){
            result = parseInt(json.result);
            if(result>0){
                $(location).attr('href',bean.link.list);
            }else if(result==0){
                $('body').viewDialogMessage('<p>삭제에 실패하였습니다.</p>');
            }else if(result==-210){
                $('body').viewDialogMessage('<p>권한이 없습니다.</p>', function(){ $(location).attr('href',bean.link.list); });
            }else{
                $('body').viewDialogMessage('<p>삭제에 실패하였습니다.</p><p>Error code : '+result+'</p>');
            }
        }, "json")
        .done(function() { $('body').hideDialogProgress();})
        .fail(function() { $('body').viewDialogMessage('<p>서버와 연결중 에러가 발생하였습니다.</p><p>잠시 뒤 다시 시도해 보세요.</p>',reload); })
        .always(function() {});
    return false;
}

function alertDelete(){
    $('body').viewConfirmDialogMessage('<p>정말 삭제하시겠습니까?</p>',deleteAction);
}
$(document).ready(function(){

    $("#btn_list").click(function(){
        $(location).attr('href', bean.link.list);
    });
    $("#btn_list2").click(function(){
        $(location).attr('href', bean.link.list);
    });

    $("#btn_write").click(function(){
        $(location).attr('href',bean.link.write);
    });
     $("#btn_write2").click(function(){
        $(location).attr('href',bean.link.write);
    });

    if(typeof(bean.auth)!='undefined' && bean.auth==''){
        $('#span_btn_write').css('display', 'none');
        $('#span_btn_delete').css('display', 'none');
    }
    //초기데이터 셋팅
    for (var key in bean.data){


        var datavalue = bean.data[key]+'';
        datavalue = datavalue.replace(/\\'/gi,"'");
        datavalue = datavalue.replace(/\\"/gi,"\"");


        if(datavalue){
            $('span#'+key).html(datavalue);
            $('input[name='+key+']').val(datavalue);
        }
    }

    $("input[name=token]").val(bean.token);

    if(bean.result<1){
        $('body').viewDialogMessage('<p>'+bean.resultMsg+'</p>',historyBack);
    }

});