var bean = $.parseJSON($.base64.decode(jsonObject));
var emptyFormData = false;


$(document).ready(function(){
    
    $( document ).tooltip();
    
    $('input[placeholder]').inputHints();
      
    $("input[name=token]").val(bean.token);
    $("#btn_list").click(function(){
        $(location).attr('href', bean.link.list);
    });

    
//    $("input[type=text]").change(function(){
//        for (var key in bean.data){
//            var text = $('input[name='+key+']').val();
//            if(!text){
//                emptyFormData = true;
//                break;
//            }else{
//                emptyFormData = false;
//            }
//        }
//        if(!emptyFormData){
//            $("#btn_submit").attr('disabled','enabled');
//            $("#btn_submit").css('color','#000000');
//        }else{
//            $("#btn_submit").attr('disabled','disabled');
//            $("#btn_submit").css('color','#cccccc');
//        }
//    });
    
    //$(":checkbox, :radio, :input, textarea").serializeArray(),
    $("#btn_submit").click(function(){
        var result = -1;
        $('body').showDialogProgress();
        $("form").attr("action",bean.link.update)
        $("#btn_submit").attr("disabled",true);

        $.post(bean.link.update,
            $('form').serialize(),
            function(json){
                
                result = parseInt(json.result);
                if(result>0){
                    if($.getUrlVar('id')) $(location).attr('href',bean.link.done+'&id='+$.getUrlVar('id')); //수정시
                    else $(location).attr('href',bean.link.done+'&id='+result); //등록시
                }else if(result==0){
                    $('body').viewDialogMessage('<p>저장된 값과 입력된 값이 같아 변경된 항목이 없습니다.</p>',function(){
                        document.location.reload();
                    });
                }else{

                    msg = '';
                    errorId = '';
                    for (var key in json){
                        
                        $('input[name='+key+'][required]').removeClass('errorinput');
                        $('select[name='+key+']').removeClass('errorinput');
                        $('textarea[name='+key+'][required]').removeClass('errorinput');

                        if(($('input[name='+key+'][required]').length 
                                || $('select[name='+key+']').length
                                || $('textarea[name='+key+'][required]').length)
                                && (json[key]==null || json[key].length==0 || json[key]=='null'))
                        {

                            if(errorId === '') errorId = key;

                            $('input[name='+key+'][required]').addClass('errorinput');
                            $('select[name='+key+']').addClass('errorinput');
                            $('textarea[name='+key+'][required]').addClass('errorinput');

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
                            $('select[name='+key+']').val(datavalue);
                            $('textarea[name='+key+']').val(datavalue.replace(/\\r\\n/gi,"\r\n"));
                            
                        }
                    }
                    if(typeof (json.resultMsg) !='undefined'){
                        errorId = '';
                        msg = json.resultMsg;
                    }
                    showUpdateError(result,errorId,msg);
                               
                }
            }, "json")
            .done(function() { $("#btn_submit").removeAttr("disabled"); $('body').hideDialogProgress();})
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
            $('select[name='+key+']').val(datavalue);
            $('textarea[name='+key+']').val(datavalue.replace(/\\r\\n/gi,"\r\n"));
            
            
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


function memberCheck(param,value,pre_addid){
    
    if(!value) return false;
    
    if(!pre_addid) pre_addid = '';
    
    var sdata = ""+param+"="+value+"";
    var isSuccess = false;
    $.ajax({type: 'POST', 
        url: "/?mode=WebMember/ajaxIsMember", 
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
                 
                 $("input[name='"+pre_addid+"mbNo']").val(data.mbNo);
                 $("input[name='"+pre_addid+"mbId']").val(data.mbId);
                 $("input[name='"+pre_addid+"mbNick']").val(data.mbNick);
             }

       });
    return isSuccess;
}
function memberCheck2(param,value,pre_addid){
    
    if(!value) return false;
    
    if(!pre_addid) pre_addid = '';
    
    var sdata = ""+param+"="+value+"";
    var isSuccess = false;
    $.ajax({type: 'POST', 
        url: "/?mode=WebMember/ajaxIsMember", 
        contentType : "application/x-www-form-urlencoded",
        datatype : "json",
        data: sdata, 
        async: false,
        success:
             function(data) { 
                 
                 $("label[for='"+pre_addid+"fromMbId']").html(data.msg);
                 
                 if(parseInt(data.result)>0){
                     $("label[for='"+pre_addid+"fromMbId']").addClass('ok');
                     isSuccess = true;
                     
                 }else {
                     $("label[for='"+pre_addid+"fromMbId']").addClass('error');
                     isSuccess = false;
                 }
                 $("input[name='"+pre_addid+"fromMbId']").attr('title',data.msg);
                 
                 $("input[name='"+pre_addid+"fromMbNo']").val(data.mbNo);
                 $("input[name='"+pre_addid+"fromMbId']").val(data.mbId);
                 $("input[name='"+pre_addid+"fromMbNick']").val(data.mbNick);
             }

       });
    return isSuccess;
}


///

function num2won(sid_div,chknum) {
	val = chknum.value;
	strnum = val;
	var won = new Array();
	re = /^[1-9][0-9]*$/;
	num = val.toString().split(',').join('');
	if (!re.test(num)) {
		chknum.value = '';
		document.getElementById(sid_div).innerHTML = '';
	} else {
		wonshow(sid_div);
		var price_unit0 = new Array('','일','이','삼','사','오','육','칠','팔','구');
		var price_unit1 = new Array('','십','백','천');
		var price_unit2 = new Array('','만','억','조','경','해','시','양','구','간','정');
		for(i = num.length-1; i >= 0; i--) {
			won[i] = price_unit0[num.substr(num.length-1-i,1)];
			if(i > 0 && won[i] != '') won[i] += price_unit1[i%4];
			if(i % 4 == 0) won[i] += price_unit2[(i/4)];
		}
		for(i = num.length-1; i >= 0; i--) {
			if(won[i].length == 2) won[i-i%4] += '-';
			if(won[i].length == 1 && i > 0) won[i] = '';
			if(i%4 != 0) won[i] = won[i].replace('일','');
		}
	
		won = won.reverse().join('').replace(/-+/g,'');
		document.getElementById(sid_div).innerHTML = '<b>'+number_format(strnum)+'</b><br />('+won+'원)';
		
	}
}

function wonshow(elementId){
	var element = document.getElementById(elementId);
	if (element){
		element.style.display = 'block';
	}
}

function wonhide(elementId){
	var element = document.getElementById(elementId);
	if (element){
		element.style.display = 'none';
	}
}