var bean = $.parseJSON($.base64.decode(jsonObject));
var emptyFormData = false;


$(document).ready(function(){
    
    
    $("#btn_submit_sms").click(function(){
        var result = -1;
        $('body').showDialogProgress();
        $("form").attr("action","/?mode=WebMemberSms/insert")
        $("#btn_submit_sms").attr("disabled",true);

        $.post("/?mode=WebMemberSms/insert",
            $('form').serialize(),
            function(json){
                
                result = parseInt(json.result);
                if(result>0){
                    if($.getUrlVar('id')) $(location).attr('href',bean.link.done+'&id='+$.getUrlVar('id')); //수정시
                    else $(location).attr('href',bean.link.done+'&id='+result); //등록시
                }else if(result==0){
                    $('body').viewDialogMessage('<p>저장된 값과 입력된 값이 같아 변경된 항목이 없습니다.</p>');
                }else{

                    msg = '';
                    errorId = '';
                    for (var key in json){
                        
                        $('input[name='+key+'][required]').removeClass('errorinput');
                        //$('select[name='+key+']').removeClass('errorinput');
                        //$('textarea[name='+key+'][required]').removeClass('errorinput');

                        if(($('input[name='+key+'][required]').length 
                                /*|| $('select[name='+key+']').length
                                || $('textarea[name='+key+'][required]').length*/)
                                && (json[key]==null || json[key].length==0 || json[key]=='null'))
                        {

                            if(errorId === '') errorId = key;

                            $('input[name='+key+'][required]').addClass('errorinput');
                            //$('select[name='+key+']').addClass('errorinput');
							//$('textarea[name='+key+'][required]').addClass('errorinput');

                        }else{
                            
//                            if($("input[type='hidden'][name='"+key+"'][from]").length
//                            && (json[key]==null || json[key]=='' ||  json[key]=='null'))
//                            {
//                                msg = 'Hidden ['+key+'] 값이 정의되지 않았습니다.';
//                                break;
//
//                            }

                            var datavalue = json[key]+'';
                            datavalue = datavalue.replace(/\\'/gi,"'");
                            datavalue = datavalue.replace(/\\"/gi,"\"");
                            
                            $('input[name='+key+']').val(datavalue);
                            //$('select[name='+key+']').val(datavalue);
                            //$('textarea[name='+key+']').val(datavalue.replace(/\\r\\n/gi,"\r\n"));
                            
                        }
                    }
                    
                    //showUpdateError(result,errorId,msg);
                               
                }
            }, "json")
            .done(function() { $("#btn_submit_sms").removeAttr("disabled"); $('body').hideDialogProgress();})
            .fail(function() { $('body').viewDialogMessage('<p>서버와 연결중 에러가 발생하였습니다.</p><p>잠시 뒤 다시 시도해 보세요.</p>',reload); })
            .always(function() {});
        return false;
    });
    
    //초기데이터 셋팅
    for (var key in bean.data){
        if(bean.data[key]){
            
            var datavalue = bean.data[key]+'';
            datavalue = datavalue.replace(/\\'/gi,"'");
            datavalue = datavalue.replace(/\\"/gi,"\"");
                            
            $('input[type=text][name='+key+'],input[type=hidden][name='+key+']').val(datavalue);
            //$('select[name='+key+']').val(datavalue);
            //$('textarea[name='+key+']').val(datavalue.replace(/\\r\\n/gi,"\r\n"));
            
            
        }
    }
    
    if(bean.result<1){
        var errorMsg = bean.resultMsg;
        if(bean.result==0){
            errorMsg = '수정할 데이터가 존재하지 않습니다.';
        }
        $('body').viewDialogMessage('<p>'+errorMsg+'</p>',historyBack);
    }
    
    $('input[type=password]').val('');
    
});


function memberCheck3(param,value,pre_addid){
    
    if(!value) return false;
    
    if(!pre_addid) pre_addid = '';
    
    var sdata = ""+param+"="+value+"";
    var isSuccess = false;
    $.ajax({type: 'POST', 
        url: "/?mode=WebMember/ajaxIsMemberSms", 
        contentType : "application/x-www-form-urlencoded",
        datatype : "json",
        data: sdata, 
        async: false,
        success:
             function(data) { 
	
                 $("label[for='"+pre_addid+param+"']").html(data.msg);
                 
                 if(parseInt(data.result)>0){
                     $("label[for='"+pre_addid+param+"']").addClass('ok');
                     isSuccess = true;
                     
                 }else {
                     $("label[for='"+pre_addid+param+"']").addClass('error');
                     isSuccess = false;
                 }
                 $("input[name='"+pre_addid+param+"']").attr('title',data.msg);
                 
                 $("input[name='mbNo']").val(data.mbNo);
                 $("input[name='mbId']").val(data.mbId);
                 $("input[name='mbNick']").val(data.mbNick);
				 $("input[name='mbHp']").val(data.mbHp);
             }

       });
    return isSuccess;
}