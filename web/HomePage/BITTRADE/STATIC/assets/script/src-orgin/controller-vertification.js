var eventPageSet = {
    btnCertNumber:$('button#btn_cert_number')
};

function requestCertNumber(){
    var mb_id = '';
    if($("input[name='mb_id']").length > 0){
        mb_id = $('input[name="mb_id"]').val();
    }else if(typeof(get_member) == 'object'){
        mb_id = get_member.mb_id;
    }


    var mb_hp = $('input[name="mb_hp"]').val();
    var mb_country_dial_code = $('input[name="mb_country_dial_code"]').val();
    var enc_mb_hp = $.base64.encode(mb_hp);
    var enc_mb_id = $.base64.encode(mb_id);
    var enc_mb_country_dial_code = $.base64.encode(mb_country_dial_code);
    var enc_status = $.base64.encode(mb_id+mb_hp);
    var reqdt       = (Utils.getTimeStamp()+'');
    var url_smscertify = "/smscertify/request/type-prove/mbid-"+enc_mb_id+"/mbhp-"+enc_mb_hp+"/reqdt-"+reqdt+"/dialcode-"+enc_mb_country_dial_code+"/";

    if( mb_hp.length>8 ){
        $.getJSON(url_smscertify, "", function(json){
            if( typeof(json)!=="undefined" && json.result>0 ){
                eventPageSet.btnCertNumber.attr("disabled", true);
                controllerComm.alertError(langConvert('lang.msgAuthorizationNumberHasBeenSentToYourMobileNumberEntered', ''));
            }else{
                controllerComm.alertError(json.error);
                eventPageSet.btnCertNumber.attr("disabled", false);

                // 기존 값 있는 경우
                if(json.result===-5035){
                    eventPageSet.btnCertNumber.attr("disabled", true);
                }
            }
        }, "json");
    }else{
        controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectMobileNumber', ''));
        eventPageSet.btnCertNumber.attr("disabled", false);
    }
}