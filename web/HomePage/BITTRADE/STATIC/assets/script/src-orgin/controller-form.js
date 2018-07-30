var bean = {};
var emptyFormData = false;

var controllerForm = {
    clickBtnId: '#btn_submit',
    dataId: '#form',
    modeldata: {
        input_amount: '',
        input_total: '',
        po_rel_id: '',
        page_code: ''
    },
    setBeanData: function (v){
        bean = v
    },
    setOnComplet: function (){
//        alert("undefined callback");
    },
    setInitForm: function (action){
//        $(this.clickBtnId).click(function(){
        switch (action){
            case 'memberjoin':
                actionMemberJoin(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                  // 회원 가입
            case 'memberrequestjoinemail':
                actionMemberRequestJoinEmail(this.clickBtnId, this.dataId, this.setOnComplet);
                break;      // 회원 가입 이메일 인증 재요청
            case 'memberrequestpwd':
                actionMemberRequestPwd(this.clickBtnId, this.dataId, this.setOnComplet);
                break;          // 회원 비밀번호 복구
            case 'memberrequestpwdprove':
                actionMemberRequestpwdProve(this.clickBtnId, this.dataId, this.setOnComplet);
                break;          // 회원 비밀번호 복구 완료 액션
            case 'memberEdit':
                actionMemberEdit(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                  // 회원 정보 변경
            case 'memberNotify':
                actionMemberNotify(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                  // 회원 알림설정 정보 변경
            case 'tradebitcoin' :
                actionTradeBitcoin(this.clickBtnId, this.modeldata, this.setOnComplet);
                break;            // 거래소 / 환전소 > 판/구매
            case 'depositkrw':
                actionDepositKrw(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                  // 환전소 > 원화충전요청
            case 'withdrawkrw':
                actionWithdrawKrw(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                  // 환전소 > 원화출금
            case 'withdrawkrwCancel':
                actionWithdrawkrwCancel(this.clickBtnId, this.dataId, this.setOnComplet);
                break;            // 환전소 > 원화출금 취소
            case 'withdrawbtc':
                actionWithdrawBtc(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                  // 환전소 > 비트코인 출금
            case 'withdrawbtcCancel':
                actionWithdrawbtcCancel(this.clickBtnId, this.dataId, this.setOnComplet);
                break;            // 환전소 > 비트코인출금 취소
            case 'qnaSend':
                actionQnaSend(this.clickBtnId, this.dataId, this.setOnComplet);
                break;                      // 고객센터 > 1:1 문의
            case 'requirementSend':
                actionRequirementSend(this.clickBtnId, this.dataId, this.setOnComplet);
                break;						// 고객센터 > 개선사항
            case 'notifymarketcost':
                actionNotifyMarketcost(this.clickBtnId, this.dataId, this.setOnComplet);
                break;            // 거래소 > 시세알림 신청
            case 'notifymarketcostCancel':
                actionNotifyMarketcostCancel(this.clickBtnId, this.dataId, this.setOnComplet);
                break;            // 거래소 > 시세알림 취소
            case 'actionverificationform':
                actionVerificationForm(this.clickBtnId, this.dataId, this.setOnComplet);
                break;          // 인증센터 레벨업 요청
            case 'actionverificationformmobile':
                actionVerificationFormMobile(this.clickBtnId, this.dataId, this.setOnComplet);
                break;          // 인증센터 모바일 인증
            case 'tradecoin' :
                actionTradeCoin(this.clickBtnId, this.modeldata, this.setOnComplet);
                break;            // 거래소 / 환전소 > 판/구매
            case 'actionusertradebitcoinupdate':
                actionUserTradeBitcoinUpdate(this.clickBtnId, this.dataId, this.setOnComplet);
        }
//        });
    },
    login: function (btnid, dataid, callback){
		$(btnid).click(function (){
            loginproc(btnid, dataid, callback);
        });
    },
    login2ch: function (btnid, dataid, callback){
        login2chproc(btnid, dataid, callback);
    },
    otpstatus: function (btnid, dataid, callback){
        actionOtpStatus(btnid, dataid, callback);
    },
    duplication: function (btnid, dataid, callback){
        $(btnid).click(function (){
            actionDuplication(btnid, dataid, callback);
        });
    },
    tradeOrder: function (dataid, callback){   // 환전소 > 비트코인 판/구매 주문서
        actionTradeOrder(dataid, callback);
    },
    userTradeOrder: function (dataid, callback){
        actionTradeBitcoinOrder(dataid, callback);  // 사용하지 않는듯 제대로 체크후 제거
    },
    userTradeBitcoinCancel: function (dataid, callback){
        actionUserTradeBitcoinCancel(dataid, callback);
    },
    userTradeOrderCancel: function (dataid, callback){
        actionUserTradeBitcoinCancel(dataid, callback);
    }

};

/*
 * 로그인 처리
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function loginproc(btnid, dataid, callback){
    // console.log("call loginproc");
    $(btnid).attr("disabled", true);

    var uid         = $('input[name=mb_id]').val();
    var upwd        = $('input[name=mb_pwd]').val();
    var notyobj     = $('#login-noty');
    var strexp      = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;

    if(!uid){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(uid.length < 5){
        notyobj.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!strexp.test(uid)){
        notyobj.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!upwd){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputPassword','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(upwd.length < 5){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputLeast5CharactersForYourPassword','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(accountPageSet.recaptcha==true && !$('#g-recaptcha-response').val()){
        notyobj.html('<p>'+langConvert('lang.msgRobotCheck','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else{
        notyobj.html('');
        var enpwd = $.base64.encode(upwd);
            enpwd = sha256_digest(uid+enpwd);
        $("input[name='encId']").val($.base64.encode(uid));
        $("input[name='encPwd']").val(enpwd);
        $("input[name='mb_pwd']").val('');

        var pdata = $("form").serialize();
        $("input[name='encId']").val(uid);

		// webmember/login
        $.post(bean.link.proc,
                pdata,
                function (json){
                    if(parseInt(json.result) > 0){
                        //아이디 저장
                        if($("input:checkbox[id='remember-me']").is(":checked")){
                            Utils.setCookie('login-id', uid, 3600 * 24 * 100);
                        }else{
                            Utils.setCookie('login-id', uid, 0);
                        }
                        if(json.hasOwnProperty('otp') && json.otp == true){
                            var otpform = $('#login2ch');
                             $("input[name='encId']").val($.base64.encode(uid));
                            otpform.method = 'POST';
                            otpform.submit();
                        }else{
                            if(typeof callback === "function"){
                                callback();
                            }
                        }
                    }else if(parseInt(json.result) == 0){
                        $(btnid).attr("disabled", false);
                        controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectAuthenticationMember','')+'</p>');
                    }else if(parseInt(json.result) == -800){
                        $(btnid).attr("disabled", false);
                        var addmsg = '<p>'+langConvert('lang.msgPleaseClickReSendIfYouHaventReceivedTheConfirmationEmail','')+'</p>';
                        addmsg += '<p><button type="button" class="btn btn-success" onclick="location.href=\'/account/signrequest\'">'+langConvert('lang.viewAccountSignrequestpwdSingUpEmailRequest','')+'</button></p>';
                        controllerComm.alertError(json.error + " <br />" + addmsg);
                    }else{
                        $(btnid).attr("disabled", false);
                        for (var key in json){
                            if(json[key] == null || json[key].length == 0 || json[key] == 'null'){
                                $('input[name=' + key + ']').addClass('errorinput');
                            }else{
                                $('input[name=' + key + ']').removeClass('errorinput');
                                $('input[name=' + key + ']').val(json[key]);
                            }
                        }
                        var addmsg = '';
                        if(json.autherror){
                            addmsg = json.autherror;
                        }
                        controllerComm.alertError(json.error + " <br />" + addmsg, function(){
                            $('input[name="mb_pwd"]').focus();
                        });
                        if(json.recaptcha==true){
                            recaptchaOnloadCallback();
                        }
                    }
                }, "json")
                .done(function (){
                })
                .always(function (){
                });
        return false;
    }
}

/*
 * mb_id 중복 체크
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionDuplication(btnid, dataid, callback){
//    console.log("call actionDuplication");
    $(btnid).attr("disabled", true);

    // alert
    var alert_mbid = $('#signup-alert-mbid');

    // value
    var mbid = $('input[name=mb_id]').val();

    var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;

    var param = {   query: mbid    };
    var pdata = jQuery.param(param);

    // alert 초기화
    alert_mbid.html('');
    alert_mbid.hide();

    if(!mbid){
        alert_mbid.html('<p>'+langConvert('lang.msgPleaseInputEmailAddress','')+'</p>');
        alert_mbid.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(mbid.length < 5){
        alert_mbid.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        alert_mbid.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!strexp.test(mbid)){
        alert_mbid.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        alert_mbid.show();
        $(btnid).attr("disabled", false);
        return false;
    }else{
        $.post("/webmember/duplication/",
            pdata,
            function (json){
                if(typeof callback == 'function')
                    callback(json);
            }, "json")
            .error(function (){
            })
            .fail(function (){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function (){
            });
    }
    return false;
}

/*
 * 로그인 처리
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function login2chproc(btnid, dataid, callback){
    console.log("call loginproc");
    $(btnid).attr("disabled", true);

    var g_otp       = $('input[name=g_otp]').val();
    var notyobj     = $('div#req-noty');

    if(!g_otp){
        notyobj.html('<p>Check form</p>');
        notyobj.css("color", "#d00000");
        $(btnid).attr("disabled", false);
        return false;
    }else{
        notyobj.html('');
        var pdata = $("#otp-regist").serialize();

        $(btnid).button('loading');
        
        $.post(bean.link.proc,
                pdata,
                function (json){
                    if(typeof callback === "function"){
                        callback(json);
                    }
                }, "json")
                .done(function (){
                })
                .always(function (){
                    $(btnid).button('reset');
                });
        return false;
    }
}

function actionOtpStatus(btnid, dataid, callback){
    var pdata = $("#otp-status").serialize();

    $(btnid).button('loading');

    $.post(bean.link.proc,
            pdata,
            function (json){
                if(typeof callback === "function"){
                    callback(json);
                }
            }, "json")
            .done(function (){
            })
            .always(function (){
                $(btnid).button('reset');
            });
    return false;
}

/*
 * trade order - od_id
 * 환전소 > 비트코인 판/구매 - 주문서
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionTradeOrder(dataid, callback){
    var it_name = "";
    var od_temp_btc = 0.0;
    var od_temp_krw = 0;
    var od_total_cost = 0;           // 주문한 토탈 KRW
    var it_market_cost = 0;         // 현재 시가 1btc
    var action   = $("input[name='action']").val();
    var ss_token = $("input[name='token']").val();

    if(action == "buybtc"){
        it_name = langConvert('lang.menuExchangeShort','')+" > "+langConvert('lang.menuExchageBuybtcShort','');
        action = 'buybtc';
        od_temp_btc = ($("input[name='input_amount']").val() + "").toFloat();
        od_total_cost = ($("input[name='input_total']").val() + "").toFloat();
        it_market_cost = ($("input[name='current_market_price']").val() + "").toFloat();
    }else if(action == "sellbtc"){
        it_name = langConvert('lang.menuExchangeShort','')+" > "+langConvert('lang.menuExchageSellbtcShort','');
        action = 'sellbtc';
        od_total_cost = ($("input[name='input_btc']").val() + "").toFloat();
        od_temp_krw = ($("input[name='input_total']").val() + "").toFloat();
        it_market_cost = ($("input[name='current_market_price']").val() + "").toFloat();
    }

    var param = {
        it_name: it_name
        , it_action: action
        , it_id_pay: ""
        , it_market_cost: it_market_cost
        , od_total_cost: od_total_cost
        , od_temp_btc: od_temp_btc
        , od_temp_krw: od_temp_krw
        , od_receipt_btc: 0
        , od_receipt_krw: 0
        , od_del_yn: "N"
        , partner: ""
        , token: ss_token
    };

    var pdata = jQuery.param(param);
    $.post(bean.link.proc,
            pdata,
            function (json){
                if(typeof callback === "function"){
                    callback(json);
                }
            }, "json")
            .error(function (){
                controllerComm.alertError(langConvert('lang.msgServerAccessError','')+" "+langConvert('lang.msgTryAgainLater',''), function(){
                    document.location.reload();
                });
            })
            .fail(function (){
                controllerComm.alertError(langConvert('lang.msgServerAccessError','')+" "+langConvert('lang.msgTryAgainLater',''), function(){
                    document.location.reload();
                });
            })
            .always(function (){
            });
}

// 사용하지 않는듯 제대로 체크후 제거
function actionTradeBitcoinOrder(dataid, callback){
    var it_name = "";
    var od_temp_btc = 0.0;
    var od_temp_krw = 0;
    var od_total_cost = 0;           // 주문한 토탈 KRW
    var it_market_cost = 0;         // 현재 시가 1btc
    var action = $("input[name='action']").val();

    if(action == "buybtc"){
        it_name = "buybtc";
        od_temp_btc = ($("input[name='input_btc']").val() + "").toFloat();
        od_total_cost = ($("input[name='input_total']").val() + "").toFloat();
        it_market_cost = ($("input[name='input_price']").val() + "").toFloat();
    }else if(action == "sellbtc"){
        it_name = "sellbtc";
        od_temp_btc = ($("input[name='input_btc']").val() + "").toFloat();
        od_total_cost = ($("input[name='input_amount']").val() + "").toFloat();
        it_market_cost = ($("input[name='input_price']").val() + "").toFloat();
    }

    var param = {
        it_name: it_name
        , it_id_pay: ""
        , it_market_cost: it_market_cost
        , od_total_cost: od_total_cost
        , od_temp_btc: od_temp_btc
        , od_temp_krw: od_temp_krw
        , od_receipt_btc: 0
        , od_receipt_krw: 0
        , od_del_yn: "N"
        , partner: ""
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
            pdata,
            function (json){
                if(typeof callback === "function"){
                    callback(json);
                }
            }, "json")
            .always(function (){
            });
}



// 거래소 판/구매 취소 이벤트
function actionUserTradeBitcoinCancel(dataid, callback){
//    var od_id_value = $("input[name='od_id']").val();

    var param = {
        od_id: dataid
        , od_pay_status: "CAN"
        , currency: bean.currency
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function (json){
            if(typeof callback === "function"){
                callback(json);
            }
        }, "json")
        .always(function (){
        });
}

/*
 * 회원 가입
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberJoin(btnid, dataid, callback){

    var alert_mbid      = $('#signup-alert-mbid');
    var alert_mbpwd     = $('#signup-alert-mbpwd');
    var alert_mbname    = $('#signup-alert-mbname');
    var alert_policy    = $('#signup-alert-policy');     // 정책
    var alert_mbhp      = $('#signup-alert-mbhp');
    var alert_mbcertnumber = $('#signup-alert-mbcertnumber');
	
	var alert_mbzipcode = $('#signup-alert-mbzipcode');
	var alert_mbaddress = $('#signup-alert-mbaddress');
	var alert_mbdetailaddress = $('#signup-alert-mbdetailaddress');

    var mbid            = $('input[name=mb_id]').val();
    var mbpwd           = $('input[name=mb_pwd]').val();
    var mbpwdre         = $('input[name=mb_pwd_re]').val();
    var mblastname      = $('input[name=mb_last_name]').val();
    var mbfirstname     = $('input[name=mb_first_name]').val();
    var mbcountry       = $('select[name=country_code]').val();
    var mbcountrydialcode = $('input[name=mb_country_dial_code]').val();
    var mbhp            = $('input[name=mb_hp]').val();
    var mbcertnumber    = $('input[name=mb_cert_number]').val();
	
	var mbzipcode       = $('input[name=mb_zip_code]').val();
    var mbaddress       = $('input[name=mb_address]').val();
    var mbdetailaddress = $('input[name=mb_detail_address]').val();

    var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;

    alert_mbid.html('');
    alert_mbpwd.html('');
    alert_mbname.html('');
    alert_mbhp.html('');
    alert_mbcertnumber.html('');
    alert_policy.html('');
	
	alert_mbzipcode.html('');
	alert_mbaddress.html('');
	alert_mbdetailaddress.html('');
    
    alert_mbid.hide();
    alert_mbpwd.hide();
    alert_mbname.hide();
    alert_mbhp.hide();
    alert_mbcertnumber.hide();
    alert_policy.hide();
	
	alert_mbzipcode.hide();
	alert_mbaddress.hide();
	alert_mbdetailaddress.hide();

    if(!mbid){
        alert_mbid.html('<p>'+langConvert('lang.msgPleaseInputEmailAddress','')+'</p>');
        alert_mbid.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(mbid.length < 5){
        alert_mbid.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        alert_mbid.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!strexp.test(mbid)){
        alert_mbid.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        alert_mbid.show();
        $(btnid).attr("disabled", false);
        return false;
    }

    if(!mbpwd && !mbpwdre){
        alert_mbpwd.html('<p>'+langConvert('lang.msgPleaseInputPassword','')+'</p>');
        alert_mbpwd.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(mbpwd.length < 5 && mbpwdre.length < 5){
        alert_mbpwd.html('<p>'+langConvert('lang.msgPleaseInputLeast5CharactersForYourPassword','')+'</p>');
        alert_mbpwd.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(mbpwd != mbpwdre){
        alert_mbpwd.html('<p>'+langConvert('lang.msgPleaseInputIdenticalPassword','')+'</p>');
        alert_mbpwd.show();
        $(btnid).attr("disabled", false);
        return false;
    }

    if(!mblastname){
        alert_mbname.html('<p>'+langConvert('lang.msgPleaseInputYourName','')+'</p>');
        alert_mbname.show();
        $(btnid).attr("disabled", false);
        return false;
    }
    if(!mbfirstname){
        alert_mbname.html('<p>'+langConvert('lang.msgPleaseInputYourName','')+'</p>');
        alert_mbname.show();
        $(btnid).attr("disabled", false);
        return false;
    }
	if(!mbzipcode){
        alert_mbzipcode.html('<p>우편번호를 입력하세요</p>');
        alert_mbzipcode.show();
        $(btnid).attr("disabled", false);
        return false;
    }
	if(!mbaddress){
        alert_mbaddress.html('<p>주소를 입력하세요</p>');
        alert_mbaddress.show();
        $(btnid).attr("disabled", false);
        return false;
    }
	if(!mbdetailaddress){
        alert_mbdetailaddress.html('<p>상세주소를 입력하세요</p>');
        alert_mbdetailaddress.show();
        $(btnid).attr("disabled", false);
        return false;
    }

    if($("input:checkbox[name='mb_agree']").is(":unchecked")){
        console.log(1);
        alert_policy.html('<p>'+langConvert('lang.msgPleaseReadTheTermsOfUsePleaseCheckTheAgreement','')+'</p>');
        alert_policy.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if($("input:checkbox[name='mb_agree2']").is(":unchecked")){
        console.log(2);
        alert_policy.html('<p>'+langConvert('lang.msgPleaseReadCheckTheBoxToAgreeToThePrivacyPolicies','')+'</p>');
        alert_policy.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!$('#g-recaptcha-response').val()){
        console.log(3);
        alert_policy.html('<p>'+langConvert('lang.msgRobotCheck','')+'</p>');
        alert_policy.show();
        $(btnid).attr("disabled", false);
        return false;
    }else{
        alert_mbid.html('');
        alert_mbpwd.html('');
        alert_mbname.html('');
        alert_mbhp.html('');
        alert_mbcertnumber.html('');
        alert_policy.html('');
        
		alert_mbzipcode.html('');
		alert_mbaddress.html('');
		alert_mbdetailaddress.html('');
	
        alert_mbid.hide();
        alert_mbpwd.hide();
        alert_mbname.hide();
        alert_mbhp.hide();
        alert_mbcertnumber.hide();
        alert_policy.hide();
		
		alert_mbzipcode.hide();
		alert_mbaddress.hide();
		alert_mbaddress.hide();

        var en_mbid = $.base64.encode(mbid);
        var en_mbpwd = $.base64.encode(mbpwd);
            en_mbpwd = sha256_digest(mbid + en_mbpwd);
        var en_mbpwdre = $.base64.encode(mbpwdre);
            en_mbpwdre = sha256_digest(mbid + en_mbpwdre);
        var en_mblastname = encodeURI(mblastname);    // 한글이므로 encodeURI 후 base 64
            en_mblastname = $.base64.encode(en_mblastname);
        var en_mbfirstname = encodeURI(mbfirstname);    // 한글이므로 encodeURI 후 base 64
            en_mbfirstname = $.base64.encode(en_mbfirstname);
        var en_mbcountrydialcode = encodeURI(mbcountrydialcode);
            en_mbcountrydialcode = $.base64.encode(en_mbcountrydialcode);
        var en_mb_hp = null;
        var en_mbcertnumber = null;
		
		var en_mbzipcode = $.base64.encode(mbzipcode);
		var en_mbaddress = encodeURI(mbaddress);    // 한글이므로 encodeURI 후 base 64
		    en_mbaddress = $.base64.encode(en_mbaddress);
		var en_mbdetailaddress = encodeURI(mbdetailaddress);    // 한글이므로 encodeURI 후 base 64
		    en_mbdetailaddress = $.base64.encode(en_mbdetailaddress);

        $("input[name='mb_id']").val(en_mbid);
        $("input[name='mb_pwd']").val(en_mbpwd);
        $("input[name='mb_pwd_re']").val(en_mbpwdre);
        $("input[name='mb_last_name']").val(en_mblastname);
        $("input[name='mb_first_name']").val(en_mbfirstname);
        $("input[name='mb_country_dial_code']").val(en_mbcountrydialcode);
		
		$("input[name='mb_zip_code']").val(en_mbzipcode);
		$("input[name='mb_address']").val(en_mbaddress);
		$("input[name='mb_detail_address']").val(en_mbdetailaddress);

        var pdata = $("form").serialize();

        $("input[name='mb_id']").val(mbid);
        $("input[name='mb_pwd']").val(mbpwd);
        $("input[name='mb_pwd_re']").val(mbpwdre);
        $("input[name='mb_last_name']").val(mblastname);
        $("input[name='mb_first_name']").val(mbfirstname);
        $("input[name='mb_country_dial_code']").val(mbcountrydialcode);
		
		$("input[name='mb_zip_code']").val(mbzipcode);
		$("input[name='mb_address']").val(mbaddress);
		$("input[name='mb_detail_address']").val(mbdetailaddress);

        $.post(bean.link.proc,
            pdata,
            function (json){
                if(typeof (json.result) != 'undefined'){
                    if(parseInt(json.result) == 1 && parseInt(json.mailsend)==1){
                        controllerComm.alertError('<p>'+langConvert('lang.msgConfirmationLinkHasBeenSentToYourEmailAddress','')+'</p></p>'+langConvert('lang.msgPleaseVisitTheLinkToActivateYourAccount','')+'</p>',
                            function (){
                                $(location).attr('href', "/account/signin/");
                            });
                    // input error
                    }else if(parseInt(json.result)==-5101){
                        var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgPleaseDefineInputData','')+' '+langConvert('lang.msgTryAgainLater','');
                        controllerComm.alertError(msg, function (){
                            $(location).attr('href', "/account/signup/");
                        });
                    // cert error
                    }else if( parseInt(json.result)==-5102){
                        $(btnid).attr("disabled", false);
                        var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgPleaseEnterTheCorrectVerificationNumber','')+' '+langConvert('lang.msgTryAgainLater','');
                        controllerComm.alertError(msg);
                    // join fail
                    }else if(parseInt(json.result) == -5103 || parseInt(json.result) == -5104 || parseInt(json.result) == -800 || parseInt(json.result) == -801){
                        $(btnid).attr("disabled", false);
                        grecaptcha.reset();
                        controllerComm.alertError('<p>'+json.error+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                            $(location).attr('href', "/account/signup/");
                        });
                    // email error
                    }else if(parseInt(json.result)==-5111 || parseInt(json.result)==-5112 || parseInt(json.result)==-5113){
                        var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgSuccessfulSignupButFailedToDeliveryTheEmail','')+'</p><p>'+langConvert('lang.msgPleaseResendSignupConfirmationEmail','');
                        controllerComm.alertError(msg, function(){
                            $(location).attr('href', "/account/signrequest/");
                        });
                    }else{
                        $(btnid).attr("disabled", false);
                        var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgSignupFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                        controllerComm.alertError(msg, function(){
                            $(location).attr('href', "/account/signup/");
                        });
                    }
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                        $(location).attr('href', "/account/signup/");
                    });
                }
            }, "json")
            .done(function (){
            })
            .fail(function (){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    $(location).attr('href', "/account/signup/");
                });
            })
            .always(function (){
            });
        return false;
    }
}

/*
 * 이메일 인증
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberJoinProve(btnid, dataid, callback){
    $.getJSON("/email/registconfirm/action-sendemail/type-join/mb_id-" + $.base64.encode(uid),
            "",
            function (json){
                if(typeof (json.result) != 'undefined' && json.result == 1){
                    controllerComm.alertError('<p></p>',
                            function (){
                                $(location).attr('href', "/");
                            });
                }else{
                    controllerComm.alertError('<p></p>',
                            function (){
                                $(location).attr('href', "/");
                            });
                }
            }, "json"
            )
            .done(function (){
                $(btnid).attr("disabled", false);
            })
            .fail(function (){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function (){
            });
}

/*
 * 이메일 재인증요청
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberRequestJoinEmail(btnid, dataid, callback){
//    console.log("call actionMemberRequestJoinEmail");
    $(btnid).attr("disabled", true);

    var uid = $('input[name=mb_id]').val();
    var notyobj = $('#req-noty');
    var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;

    if(!uid){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(uid.length < 5){
        notyobj.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!strexp.test(uid)){
        notyobj.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }

    $.getJSON("/email/requestconfirm/action-sendemail/type-rejoin/mb_id-" + $.base64.encode(uid),
            "", function (json){
                if(typeof (json.result) != 'undefined' && json.result == 1){
                    controllerComm.alertError('<p>'+langConvert('lang.msgIfYouAccountExistsPleaseCheckEmailWithConfirmationLink','')+'</p></p>'+langConvert('lang.msgPleaseVisitTheLinkToActivateYourAccount','')+'</p>',
                        function (){
                            $(location).attr('href', "/account/signin");
                        });
                }else if(typeof (json.result) != 'undefined' && json.result == -6105){
                    var msg = (typeof json.error!='undefined')?json.error+langConvert('lang.msgTryAgainLater',''):langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','');
                    controllerComm.alertError('<p>'+msg+'</p>',
                        function (){
                            document.location.reload();
                        });
                }else{
                    // 실패하여도 성공과 동일한 안내를 보낸다. 계정 유무를 감추기 위해..
                    controllerComm.alertError('<p>'+langConvert('lang.msgIfYouAccountExistsPleaseCheckEmailWithPasswordRecoveryLink','')+'</p></p>'+langConvert('lang.msgPleaseVisitTheLinkToActivateYourAccount','')+'</p>',
                        function (){
                            $(location).attr('href', "/account/signin");
                        });
                }
            }, "json")
            .done(function (){
            })
            .fail(function (){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function (){
            });
}

/*
 * 회원 비밀번호 복구
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberRequestPwd(btnid, dataid, callback){
//    console.log("call actionMemberRequestPwd");
    $(btnid).attr("disabled", true);

    var uid = $('input[name=mb_id]').val();
    var notyobj = $('#req-noty');
    var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;

    if(!uid){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(uid.length < 5){
        notyobj.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!strexp.test(uid)){
        notyobj.html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }

    $.getJSON("/email/requestconfirm/action-sendemail/type-pwd/mb_id-" + $.base64.encode(uid),
            "",
            function (json){
                if(typeof (json.result) != 'undefined' && json.result == 1){
                    controllerComm.alertError('<p>'+langConvert('lang.msgIfYouAccountExistsPleaseCheckEmailWithConfirmationLink','')+'</p></p>'+langConvert('lang.msgPleaseVisitLinkRecoveryYourPassword','')+'</p>',
                        function (){
                            $(location).attr('href', "/account/signin");
                        });
                }else if(typeof (json.result) != 'undefined' && json.result == -6105){
                    var msg = (typeof json.error!='undefined')?json.error+langConvert('lang.msgTryAgainLater',''):langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','');
                    controllerComm.alertError('<p>'+msg+'</p>',
                        function (){
                            document.location.reload();
                        });
                }else{
                    // 실패하여도 성공과 동일한 안내를 보낸다. 계정 유무를 감추기 위해..
                    controllerComm.alertError('<p>'+langConvert('lang.msgIfYouAccountExistsPleaseCheckEmailWithPasswordRecoveryLink','')+'</p></p>'+langConvert('lang.msgPleaseVisitLinkRecoveryYourPassword','')+'</p>',
                        function (){
                            $(location).attr('href', "/account/signin");
                        });
                }
            }, "json")
            .done(function (){
            })
            .fail(function (){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function (){
            });
}


/*
 * 회원 비밀번호 복구 액션
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberRequestpwdProve(btnid, dataid, callback){
    $(btnid).attr("disabled", true);
    var pwd = $('input[name=mb_pwd]').val();
    var pwdre = $('input[name=mb_pwd_re]').val();
    var notyobj = $('h4#confirm-noty');

    if(!pwd && !pwdre){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputPassword','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(pwd.length < 5 && pwdre.length < 5){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputLeast5CharactersForYourPassword','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(pwd != pwdre){
        notyobj.html('<p>'+langConvert('lang.msgPleaseInputIdenticalPassword','')+'</p>');
        notyobj.show();
        $(btnid).attr("disabled", false);
        return false;
    }

    var param = document.URL;
    var cut = param.indexOf('/returnemail/confirmpwd/');
    var pdata = param.slice(cut + 24);
    var param_id = param.slice(cut + 56);

    var param = {
        action: "confirmemail"
        , type: "pwd"
        , mb_id: param_id.substring(param_id, param_id.indexOf('/mbkey'))
        , mbkey: param.slice(param.indexOf('mbkey') + 6)
        , mb_pwd: sha256_digest( $.base64.decode(param_id.substring(param_id, param_id.indexOf('/mbkey'))) + $.base64.encode(pwd))
    };
    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
            pdata,
            function (json){
                if(typeof callback === "function"){
                    callback(json);
                }
            }, "json")
            .done(function (){
                $(btnid).attr("disabled", false);
            })
            .fail(function (){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function (){
            });
}

/*
 * 회원 정보 변경
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberEdit(btnid, dataid, callback){
    $(btnid).attr("disabled", true);
    var mid = $('input[name=mb_id]').val();
    var mp = $('input[name=mb_pwd]').val();
    var mnp = $('input[name=mb_new_pwd]').val();
    var mnpr = $('input[name=mb_new_pwd_re]').val();

    $('#edit_mb_pwd_alert').html('');
    $('#edit_mb_new_pwd_alert').html('');
    $('#edit_mb_new_pwd_re_alert').html('');
    
    $('#edit_mb_pwd_alert').hide();
    $('#edit_mb_new_pwd_alert').hide();
    $('#edit_mb_new_pwd_re_alert').hide();

    if(!mp){
        $('#edit_mb_pwd_alert').html('<p class="p_t6_padding">'+langConvert('lang.msgPleaseInputCurrentPassword','')+'</p>');
        $('#edit_mb_pwd_alert').show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!mnp){
        $('#edit_mb_new_pwd_alert').html('<p class="p_t6_padding">'+langConvert('lang.msgPleaseConfirmNewPassword','')+'</p>');
        $('#edit_mb_new_pwd_alert').show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!mnpr){
        $('#edit_mb_new_pwd_re_alert').html('<p class="p_t6_padding">'+langConvert('lang.msgPleaseConfirmNewPassword_confirm','')+'</p>');
        $('#edit_mb_new_pwd_re_alert').show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(mnp != mnpr){
        $('#edit_mb_new_pwd_alert').html('<p class="p_t6_padding">'+langConvert('lang.msgPleaseInputIdenticalPassword','')+'</p>');
        $('#edit_mb_new_pwd_alert').show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(mnp.length < 5 || mnpr.length < 5){
        $('#edit_mb_new_pwd_alert').html('<p class="p_t6_padding">'+langConvert('lang.msgPleaseInputLeast5CharactersForYourPassword','')+'</p>');
        $('#edit_mb_new_pwd_alert').show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!mnp){
        $('#edit_mb_new_pwd_alert').html('<p class="p_t6_padding">'+langConvert('lang.msgPleaseConfirmNewPassword','')+'</p>');
        $('#edit_mb_new_pwd_alert').show();
        $(btnid).attr("disabled", false);
        return false;
    }else{
        $('#edit_mb_new_pwd_alert').html('');
        $('#edit_mb_new_pwd_re_alert').html('');
        $('#edit_mb_pwd_alert').html('');
        
        $('#edit_mb_new_pwd_alert').hide();
        $('#edit_mb_new_pwd_re_alert').hide();
        $('#edit_mb_pwd_alert').hide();

        var mnpr_enc = $.base64.encode(mnp);   // 신 pwd encode
            mnpr_enc = sha256_digest(mid + mnpr_enc);
        var mp_enc = $.base64.encode(mp);   // 구 pwd encode
            mp_enc = sha256_digest(mid + mp_enc);
        var en_mid = $.base64.encode(mid);

        $("input[name='mb_id']").val(en_mid); // 신 pwd
        $("input[name='mb_new_encPwd']").val(mnpr_enc); // 신 pwd
        $("input[name='mb_encPwd']").val(mp_enc); // 구 pwd
        $("input[name='mb_new_pwd']").val(mnpr_enc); // 신 pwd encode
        $("input[name='mb_new_pwd_re']").val(mnpr_enc); // 신 pwd encode re용
        $("input[name='mb_pwd']").val(mp_enc); // 구 pwd encode

        var pdata = $("form").serialize();

        $("input[name='mb_id']").val(mid); // 신 pwd
        $("input[name='mb_pwd']").val(mp);
        $("input[name='mb_new_pwd']").val(mnp);
        $("input[name='mb_new_pwd_re']").val(mnpr);

        $.post(bean.link.proc,
            pdata,
            function (json){
                if(parseInt(json.result) > 0){
                    controllerComm.alertError('<p>'+langConvert('lang.msgProfileChanged','')+'</p>', function(){
                        document.location.reload();
                    });
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.msgFailedToChangeProfile','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                        document.location.reload();
                    });
                }
            });
    }
}


/*
 * 회원 알림 변경
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionMemberNotify(btnid, dataid, callback){
    $('#btn_notify').attr("disabled", true);
    var mb_exchange     = "N";
    var mb_trade        = "N";
    var mb_withdrawals  = "N";
    if($("input:checkbox[name='mb_notify_exchange']").is(":checked")){
        mb_exchange = "Y";
    }

    if($("input:checkbox[name='mb_notify_trade']").is(":checked")){
        mb_trade = "Y";
    }

    if($("input:checkbox[name='mb_notify_withdrawals']").is(":checked")){
        mb_withdrawals = "Y";
    }

    var param = {
        mb_notify_exchange : mb_exchange
        , mb_notify_trade : mb_trade
        , mb_notify_withdrawals : mb_withdrawals
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function(json){
            if(parseInt(json.result) > 0){
                controllerComm.alertError('<p>'+langConvert('lang.msgAlertSettingChange','')+'</p>',function (){
                        $.getJSON("/webmember/notifystatus/", "json", function (j){
                        })
                            .success( function(data){
                                document.location.reload();
                            })
                            .error( function(){
                                controllerComm.alertError('<p>'+langConvert('lang.msgFailedToGetAlertSetting','')+'</p><p>'+langConvert('lang.msgErrorRepeatedPleaseReLogin','')+'</p>', function(){
                            });
                        });
                    });
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgFailedToChangeAlertSetting','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>',
                    function (){
                        document.location.reload();
                    });
            }
        });
}



/*
 * 비트코인 판/구매
 * buybtc, sellbtc
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 * 구매는 그대로 판매는
 * amount <-> total 역순이다 현재는 던지기전에 script에서 처리
 */
function closePreTradeModal(){
    $(".modal").modal('hide');
}

function actionTradeCoin(btnid, modeldata, callback){
    $(btnid).attr("disabled", true);
    
    $.ajaxSetup({
        type: 'POST',
        timeout: 60000,
        error: function(xhr) {
            closePreTradeModal();
            initBalanceSum();
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p><!--<p>' + xhr.statusText + ' ['+xhr.status+']</p>-->', function(){
                document.location.reload();
            });
        }
    });

    var pdata = jQuery.param(modeldata);
    
    $(btnid).button('loading');
    $.post(bean.link.proc,
        pdata,
        function (json){
            if(Number(json.result) == 0){
                if(typeof callback === "function"){
                    callback(json);
                }
            }else if(Number(json.result) > 0){
                SendEvent.sendTradeRegist();
                if(typeof callback === "function"){
                    if(typeof json.complete != 'undefined'){
                        if(json.complete == 'OK' || json.complete == 'PART'){
                            SendEvent.sendTradeComplete();
                        }
                    }
                    callback(json);
//                        $(btnid).attr("disabled", false);
                }
                //initBalance();
            }else if(Number(json.result) < 0){
                if(typeof callback === "function"){
                    callback(json);
                }
            }
//                $(btnid).attr("disabled", false);
        }, "json").always(function (){
            $(btnid).button('reset');
        });
}


/*
 * 원화 충전요청 depositKrw
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function closePreDepositCashModal(){
    $(".depositCashModal_btnnm").html(langConvert('lang.commonKrwChargeRequest',''));  // modal btn nm
    eventDom.btnDepositCashSubmit.attr('disabled', false);   // submit btn
    eventDom.btnSubmitCancel.attr('disabled', false);   // submit btn
    $('#depositCashModal').modal('hide');
}

function actionDepositKrw(btnid, dataid, callback){

    eventDom.error_msg.html('');

    if( !depositCashSet.od_name.val() ){
        closePreDepositCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputDepositorsName','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputDepositorsName','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnDepositCash.attr("disabled", false);
        return false;
    }else if( parseInt(depositCashSet.od_temp_bank.val().unformatWon()) == 0 ){
        closePreDepositCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputDepositAmount','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputDepositAmount','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnDepositCash.attr("disabled", false);
        return false;
    }else{
        eventDom.error_msg.html('');
        eventDom.error_msg.hide();
    }

    var param = {
        it_id:1,
        it_name:langConvert('lang.commonItemKrwPoint',''),
        od_name:depositCashSet.od_name.val(),
        od_temp_bank:parseInt(depositCashSet.od_temp_bank.val().unformatWon()),
        od_bank_name:depositCashSet.od_bank_name.text(),
        od_bank_owner:depositCashSet.od_bank_owner.text(),
        od_bank_account:depositCashSet.od_bank_account.text(),
        od_shop_memo:langConvert('lang.msgKrwDepositRechargeRequest',''),
        od_settle_case:langConvert('lang.commonBankAccountUnavailable','')
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function(json){
            $('#depositCashModal').modal('hide');
            if( typeof(json.result) != 'undefined' && json.result >= 1){
                // 관리자 입금 알림 sms
                /*var ss_token = $('input[name="token"]').val();
                var smsparam = {
                    result: json.result
                    ,action:"depositkrw"
                    ,token: ss_token
                };
                var smsdata = jQuery.param(smsparam);

                $.post("/smsdata/regist/",
                    smsdata,
                    function (json){
                    }, "json")
                    .error(function (){
                    })
                    .fail(function (){
                    })
                    .always(function (){
                    });*/
                controllerComm.alertError('<p>'+langConvert('lang.msgKrwDepositRechargeRequestSucceeded','')+'</p>', function(){
                    document.location.reload();
                });
            }else{
                var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgKrwDepositRechargeRequestFailedPleaseTryAgain','');
                controllerComm.alertError(msg , function(){
                    document.location.reload();
                });
            }
        }, "json")
        .done( function(){
            $(btnid).attr("disabled", false);
        })
        .fail( function(){
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always( function(){
        }
    );
}



/*
 * 원화 출금 withdrawkrw
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function closePreWithdrawCashModal(){
    $(".withdrawCashModal_btnnm").html(langConvert('lang.commonWithdrawalRequest',''));  // modal btn nm
    eventDom.btnWithdrawCashSubmit.attr('disabled', false);  //submit btn
    eventDom.btnSubmitCancel.attr('disabled', false);   // cancel btn
    $('#withdrawCashModal').modal('hide');
}

function actionWithdrawKrw(btnid, dataid, callback){
    if( !withdrawCashSet.request.val() ){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputWithdrawalAmount','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputWithdrawalAmount','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else if( parseInt(withdrawCashSet.request.val().unformatWon()) < parseInt(withdrawCashSet.cash_min_limit) ){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalLimitOfxxKrw', [withdrawCashSet.cash_min_limit.formatWon()])+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalLimitOfxxKrw', [withdrawCashSet.cash_min_limit.formatWon()])+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else if(withdrawCashSet.cash_daily_max_limit_result==true && parseInt(withdrawCashSet.request.val().unformatWon()) > parseInt(withdrawCashSet.cash_daily_max_limit) ){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnsureTheDailyWithdrawalLimit','')+langConvert('lang.msgPleaseEnsureTheMaximumWithdrawalLimitOfxxKrw', [withdrawCashSet.cash_daily_max_limit.formatWon()])+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheDailyWithdrawalLimit','')+langConvert('lang.msgPleaseEnsureTheMaximumWithdrawalLimitOfxxKrw', [withdrawCashSet.cash_daily_max_limit.formatWon()])+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else if(!withdrawCashSet.bank_name.val()){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputTheBankName','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputTheBankName','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else if(!withdrawCashSet.bank_account.val()){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputTheBankAccount','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputTheBankAccount','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else if((withdrawCashSet.bank_account.val()).length<8){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnterMoreThan8LetterForYourAccountNumber','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnterMoreThan8LetterForYourAccountNumber','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else if(!withdrawCashSet.bank_holder.val()){
        closePreWithdrawCashModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputDepositorsName','')+'</p>');
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputDepositorsName','')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCash.attr('disabled', false);
        return false;
    }else{
        eventDom.error_msg.html('');
        eventDom.error_msg.hide();
    }
    
    var param = {
        type: "regist",
        od_bank_name: withdrawCashSet.bank_name.val(),
        od_bank_account: withdrawCashSet.bank_account.val(),
        od_bank_holder: withdrawCashSet.bank_holder.val(),
        od_temp_amount: parseInt(withdrawCashSet.request.val().unformatWon()),
        od_receipt_amount: 0,
        od_fee: parseInt(withdrawCashSet.fee.val().unformatWon())      // view에 있는 수수료 입력
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function(json){
            $('#withdrawCashModal').modal('hide');
            if(typeof (json.result) != 'undefined'){
                if(parseInt(json.result) > 1 && parseInt(json.mailsend)==1){
                    controllerComm.alertError('<p>' + withdrawCashSet.request.val() + langConvert('lang.msgKrwWithdrawalRequestSucceded','')+'</p><p>'+langConvert('lang.msgPleaseConfirmTheWithdrawalRequestEmail','')+'</p>', function(){
                        document.location.reload();
                    });
                }else if(parseInt(json.result) > 1 && parseInt(json.mailsend)==-1){
                    controllerComm.alertError('<p>' + withdrawCashSet.request.val() + langConvert('lang.msgSuccessfulKrwWithdrawalRequestButFailedToDeliverTheEmail','')+'</p><p>'+langConvert('lang.msgPleaseCustomerServiceContactUs','')+'</p>', function (){
                        document.location.reload();
                    });
                }else{
                    var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgKrwWithdrawalRequestFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                    controllerComm.alertError(msg, function(){
                        document.location.reload();
                    });
                }
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            }
        }, "json")
        .done(function (){
            $(btnid).attr("disabled", false);
        })
        .fail(function (){
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always(function (){
        });
}

/*
 * 출금요청 취소 actionWithdrawkrwCancel
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionWithdrawkrwCancel(btnid, dataid, callback){
    var od_id_value = $("input[name='od_id']").val();

    var param = {
        od_id: od_id_value
        , od_pay_status: "CAN"
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function(json){
            $('#withdrawCashModal').modal('hide');
            if( typeof(json[0].result) != 'undefined' && json[0].result == 1){
                controllerComm.alertError('<p>'+langConvert('lang.msgKrwWithdrawalRequestHasBeenCanceled','')+'</p>', function(){
                    document.location.reload();
                });
            }else if( typeof(json[0].result) != 'undefined' && json[0].result == 0){
                controllerComm.alertError('<p>'+langConvert('lang.msgKrwWithdrawalRequestHasBeenAlreadyCanceled','')+'</p>', function(){
                    document.location.reload();
                });
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgFailedToCancelKrwWithdrawalRequest','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            }
        }, "json")
        .done( function(){
            $(btnid).attr("disabled", false);
        })
        .fail( function(){
            $('#withdrawCashModal').modal('hide');
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always( function(){
        });
}

/*
 * 비트코인 출금 withdrawbtc
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function closePreWithdrawBtcModal(){
    $(".withdrawbtcModal_btnnm").html(langConvert('lang.commonWithdrawalRequest',''));
    eventDom.btnWithdrawBtcSubmit.attr('disabled', false);   // submit btn
    eventDom.btnSubmitCancel.attr('disabled', false);   // cancel btn
    $('#withdrawBtcModal').modal('hide');
}
function actionWithdrawBtc(btnid, dataid, callback){

    if(exchangePageSet.is_owner_address==1){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectBtcAddress','')+'</p>');
        eventDom.btc_account_alert.html('<p>'+langConvert('lang.msgIncorrectBtcAddress','')+'</p>');
        eventDom.btc_account_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else if(exchangePageSet.is_outer_address==-1 && exchangePageSet.is_sendto_address <= 0 ){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectBtcAddress','')+'</p>');
        eventDom.btc_account_alert.html('<p>'+langConvert('lang.msgIncorrectBtcAddress','')+'</p>');
        eventDom.btc_account_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else if(exchangePageSet.is_correct_address==1 && exchangePageSet.is_sendto_address ==1 && (exchangePageSet.btc_sendto==null || exchangePageSet.btc_sendto=='') ){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectInnerBtcAddress','')+'</p>');
        eventDom.btc_account_alert.html('<p>'+langConvert('lang.msgIncorrectInnerBtcAddress','')+'</p>');
        eventDom.btc_account_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
//    }else if(exchangePageSet.is_correct_address && exchangePageSet.is_outer_address == 0){
//        closePreWithdrawBtcModal();
//        controllerComm.alertError('<p>'+langConvert('lang.msgNotAuthenticationCompleteMember','')+'</p>');
//        eventDom.btc_account_alert.html('<p>'+langConvert('lang.msgNotAuthenticationCompleteMember','')+'</p>');
//        eventDom.btc_account_alert.show();
//        eventDom.btnWithdrawBtc.attr('disabled', true);
//        return false;
    }else if(!exchangePageSet.btc_account.val()){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputBtcAddress','')+'</p>');
        eventDom.btc_account_alert.html('<p>'+langConvert('lang.msgPleaseInputBtcAddress','')+'</p>');
        eventDom.btc_account_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else if(parseFloat(exchangePageSet.request_btc.val()) > exchangePageSet.float_mb_btc){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnsureYourBtcAccountBalance','')+'</p>');
        eventDom.request_btc_alert.html('<p>'+langConvert('lang.msgPleaseEnsureYourBtcAccountBalance','')+'</p>');
        eventDom.request_btc_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else if(!exchangePageSet.request_btc.val()){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputBtcWithdrawalAmount','')+'</p>');
        eventDom.request_btc_alert.html('<p>'+langConvert('lang.msgPleaseInputBtcWithdrawalAmount','')+'</p>');
        eventDom.request_btc_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else if(parseFloat(exchangePageSet.request_btc.val()) < parseFloat(exchangePageSet.btc_min_limit)){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalLimitOfxxBtc', [(exchangePageSet.btc_min_limit)])+'</p>');
        eventDom.request_btc_alert.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalLimitOfxxBtc', [(exchangePageSet.btc_min_limit)])+'</p>');
        eventDom.request_btc_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else if( exchangePageSet.btc_daily_max_limit_result==true && parseFloat(exchangePageSet.request_btc.val()) > parseFloat(exchangePageSet.btc_daily_max_limit)){
        closePreWithdrawBtcModal();
        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnsureTheDailyWithdrawalLimit','')+'</p><p>'+langConvert('lang.msgPleaseEnsureTheMaximumWithdrawalLimitOfxxBtc', [(exchangePageSet.btc_daily_max_limit+'').formatNumber()])+'</p>');
        eventDom.request_btc_alert.html('<p>'+langConvert('lang.msgPleaseEnsureTheDailyWithdrawalLimit','')+'</p><p>'+langConvert('lang.msgPleaseEnsureTheMaximumWithdrawalLimitOfxxBtc', [(exchangePageSet.btc_daily_max_limit+'').formatNumber()])+'</p>');
        eventDom.request_btc_alert.show();
        eventDom.btnWithdrawBtc.attr('disabled', true);
        return false;
    }else{
        eventDom.btc_account_alert.html('');
        eventDom.request_btc_alert.html('');
        eventDom.btnWithdrawBtc.attr('disabled', false);
        var od_action = 'withdrawbtc';
        var od_btc_sendto = '';
        if( exchangePageSet.is_correct_address==1 && (exchangePageSet.is_sendto_address==1 || exchangePageSet.is_outer_address==0) ){
            od_btc_sendto = exchangePageSet.btc_sendto;
            od_action = 'sendtobtc';
        }
        var param = {
            type: "regist",
            odaction: od_action, // withdrawbtc, sendtobtc
            od_btc_account: exchangePageSet.btc_account.val(),
            od_btc_comment: exchangePageSet.btc_comment.val(),
            od_bank_name: "",
            od_bank_account: "",
            od_bank_holder: "",
            od_btc_sendto:od_btc_sendto,
            od_request_btc: exchangePageSet.float_request_btc,
            od_request_krw: 0,
            od_receipt_btc: 0,
            od_receipt_krw: 0,
            od_total_fee: parseFloat(exchangePageSet.request_fee)      // 수수료 - 후 처리 현재는 0
        };

        var pdata = jQuery.param(param);

        $.post(bean.link.proc,
            pdata,
            function (json){
                    $('#withdrawBtcModal').modal('hide');
                if(parseInt(json.result) > 1 && parseInt(json.mailsend)==1){
                    controllerComm.alertError('<p>' + (exchangePageSet.float_request_btc+"")+ langConvert('lang.msgBtcWithdrawalRequestSucceeded','')+'</p><p>'+langConvert('lang.msgPleaseConfirmTheWithdrawalRequestEmail','')+'</p>', function(){
                        document.location.reload();
                    });
                }else if(parseInt(json.result) > 1 && parseInt(json.mailsend)==-1){
                    controllerComm.alertError('<p>' + (exchangePageSet.float_request_btc+"") + langConvert('lang.msgSuccessfulBtcWithdrawalRequestButFailedToDeliverTheEmail','')+'</p><p>'+langConvert('lang.msgPleaseCustomerServiceContactUs','')+'</p>', function (){
                        document.location.reload();
                    });
                }else{
                    var msg = 'error';
                    if(json.error!='undefined'){ msg = json.error; };
                    controllerComm.alertError(msg, function(){
                        document.location.reload();
                    });
                }
            }, "json")
            .done(function(){
                $(btnid).attr("disabled", false);
            })
            .fail(function(){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function(){
            });
    }
}

/*
 * 출금요청 취소 actionWithdrawbtcCancel
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionWithdrawbtcCancel(btnid, dataid, callback){
    var od_id_value = $("input[name='od_id']").val();

    var param = {
        od_id: od_id_value
        , od_pay_status: "CAN"
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function(json){
            $('#withdrawBtcModal').modal('hide');
            if( typeof(json[0].result) != 'undefined' && json[0].result == 1){
                controllerComm.alertError('<p>'+langConvert('lang.msgBtcWithdrawalRequestHasBeenCanceled','')+'</p>', function(){
                    document.location.reload();
                });
            }else if( typeof(json[0].result) != 'undefined' && json[0].result == 0){
                controllerComm.alertError('<p>'+langConvert('lang.msgBtcWithdrawalRequestHasBeenAlreadyCanceled','')+'</p>', function(){
                    document.location.reload();
                });
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgFailedToCancelBtcWithdrawalRequest','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            }
        }, "json")
        .done( function(){
            $(btnid).attr("disabled", false);
        })
        .fail( function(){
            $('#withdrawBtcModal').modal('hide');
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always( function(){
        });
}

/*
 * 고객센터 > 1:1문의
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionQnaSend(btnid, dataid, callback){
    var qnm = $('input[name=qna_nm]').val();
    var qemail = $('input[name=qna_email]').val();
    var qcontents = $('textarea[name=qna_contents]').val();
    var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;
    $('#qna_nm_alert').html('');
    $('#qna_email_alert').html('');
    $('#qna_contents_alert').html('');

    if(!qnm){
        $('#qna_nm_alert').html('<p>'+langConvert('lang.msgPleaseInputTheUsername','')+'</p>');
        $('#qna_nm_alert').show();
        return false;
    }else if(qemail.length < 5){
        $('#qna_email_alert').html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        $('#qna_email_alert').show();
        return false;
    }else if(!strexp.test(qemail)){
        $('#qna_email_alert').html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        $('#qna_email_alert').show();
        return false;
    }else if(!qcontents){
        $('#qna_contents_alert').html('<p>'+langConvert('lang.msgPleaseInputYourEnquiry','')+'</p>');
        $('#qna_contents_alert').show();
        return false;
    }else{
        var params = {
            ch_no: 110,
            cate_name: langConvert('lang.viewCsQnalistTitle',''),
            mb_nick: qnm,
            subject: $('select[name=qna_subject]').val(),
            contents: qcontents,
            etc1: qemail
        };
        var pdata = jQuery.param(params);

        $.post(bean.link.proc,
                pdata,
                function (json){
                    if(parseInt(json.result) == 0){
                        if(typeof callback === "function"){
                            callback(false);
                        }
                    }else if(parseInt(json.result) > 0){
                        if(typeof callback === "function"){
                            callback(true);
                        }
                    }
                }, "json")
                .done(function (){
                    $(btnid).attr("disabled", false);
                })
                .fail(function (){
                    controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                        document.location.reload();
                    });
                })
                .always(function (){
                });
    }
}

/*
 * 고객센터 > 개선사항
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionRequirementSend(btnid, dataid, callback){
    var qnm = $('input[name=qna_nm]').val();
    var qemail = $('input[name=qna_email]').val();
    var qcontents = $('textarea[name=qna_contents]').val();
    var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;
    $('#qna_nm_alert').html('');
    $('#qna_email_alert').html('');
    $('#qna_contents_alert').html('');

    if(!qnm){
        $('#qna_nm_alert').html('<p>'+langConvert('lang.msgPleaseInputTheUsername','')+'</p>');
        $('#qna_nm_alert').show();
        return false;
    }else if(qemail.length < 5){
        $('#qna_email_alert').html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        $('#qna_email_alert').show();
        return false;
    }else if(!strexp.test(qemail)){
        $('#qna_email_alert').html('<p>'+langConvert('lang.msgIncorrectEmailAddress','')+'</p>');
        $('#qna_email_alert').show();
        return false;
    }else if(!qcontents){
        $('#qna_contents_alert').html('<p>'+langConvert('lang.msgPleaseInputYourEnquiry','')+'</p>');
        $('#qna_contents_alert').show();
        return false;
    }else{
        var params = {
            ch_no: 110,
            cate_name: langConvert('lang.menuCsREquirement',''),
            mb_nick: qnm,
            subject: $('select[name=qna_subject]').val(),
            contents: qcontents,
            etc1: qemail
        };
        var pdata = jQuery.param(params);

        $.post(bean.link.proc,
                pdata,
                function (json){
                    if(parseInt(json.result) == 0){
                        if(typeof callback === "function"){
                            callback(false);
                        }
                    }else if(parseInt(json.result) > 0){
                        if(typeof callback === "function"){
                            callback(true);
                        }
                    }
                }, "json")
                .done(function (){
                    $(btnid).attr("disabled", false);
                })
                .fail(function (){
                    controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                        document.location.reload();
                    });
                })
                .always(function (){
                });
    }
}

/*
 * 거래소 시세알림 actionNotifyMarketcost
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */

function actionNotifyMarketcost(btnid, dataid, callback){

    var market_cost = notifyPageSet.market_cost.val();
    var market_cost_min = parseInt(notifyPageSet.market_cost_min);
    var market_cost_max = parseInt(notifyPageSet.market_cost_max);
    var input_market_cost_min = notifyPageSet.input_market_cost_min;
    var input_market_cost_max = notifyPageSet.input_market_cost_max;

    var market_cost_min_parent = eventDom.market_cost_min_parent;
    var market_cost_min_alert = eventDom.market_cost_min_alert;
    var market_cost_max_parent = eventDom.market_cost_max_parent;
    var market_cost_max_alert = eventDom.market_cost_max_alert;
    var btn_notify = eventDom.btn_notify;
    var input_price_half = '';
    var input_market_cost_min_half = (market_cost_min+'').substring(market_cost_min.length-3);
    var input_market_cost_max_half = (market_cost_max+'').substring(market_cost_max.length-3);
    btn_notify.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Wait..');
    btn_notify.attr("disabled", true);

    market_cost_max_parent.removeClass("has-error");
    market_cost_max_parent.removeClass("btn-default");
    market_cost_max_alert.html('');
    market_cost_min_parent.removeClass("has-error");
    market_cost_min_parent.removeClass("btn-default");
    market_cost_min_alert.html('');

    if( market_cost_min.length > 0 && parseInt(market_cost_min) < 1000 ){
        input_market_cost_min.val('');
        market_cost_min = 0;
        market_cost_min_parent.addClass("has-error");
        market_cost_min_alert.html('<p>'+lang.alert_please_enter_minimum_krw_1000+'</p>');
        market_cost_min_alert.css("color","#d00000");
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if( market_cost_min.length > 0 && (parseInt(market_cost) <= parseInt(market_cost_min)) ){
        input_market_cost_min.val('');
        market_cost_min = 0;
        market_cost_min_parent.addClass("has-error");
        market_cost_min_alert.html('<p>'+lang.alert_please_enter_amount_lower_price_than_current_market_price+'</p>');
        market_cost_min_alert.css("color","#d00000");
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if(market_cost_min.length > 0 && (input_market_cost_min_half=="500" || input_market_cost_min_half=="000") ){
        market_cost_min_parent.addClass("btn-default");
        market_cost_min = 0;
        market_cost_min_alert.html('');
        market_cost_max_parent.addClass("btn-default");
        market_cost_max = 0;
        market_cost_max_alert.html('');
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if( market_cost_max.length > 0 && (parseInt(market_cost) >= parseInt(market_cost_max)) ){
        market_cost_max = 0;
        input_market_cost_max.val('');
        market_cost_max_parent.addClass("has-error");
        market_cost_max_alert.html('<p>'+lang.alert_please_enter_amount_higher_price_than_current_market_price+'</p>');
        market_cost_max_alert.css("color","#d00000");
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if(input_market_cost_max_half=="500" || input_market_cost_max_half=="000"){
        market_cost_max_parent.addClass("btn-default");
        market_cost_max = 0;
        market_cost_max_alert.html('');
        market_cost_min_parent.addClass("btn-default");
        market_cost_min = 0;
        market_cost_min_alert.html('');
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if(input_market_cost_min.val().length==0 && input_market_cost_max.val().length==0){
        market_cost_min_parent.addClass("has-error");
        market_cost_min_alert.html('<p>'+lang.alert_please_enter_amount_higher_price_than_current_market_price+'</p>');
        market_cost_min_alert.css("color","#d00000");
        market_cost_max_parent.addClass("has-error");
        market_cost_max_alert.html('<p>'+lang.alert_please_enter_amount_higher_price_than_current_market_price+'</p>');
        market_cost_max_alert.css("color","#d00000");
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if(input_market_cost_min.val().length!=0 && market_cost <= parseInt(market_cost_min)){
        market_cost_min_parent.addClass("has-error");
        market_cost_min_alert.html('<p>'+lang.alert_please_enter_amount_higher_price_than_current_market_price+'</p>');
        market_cost_min_alert.css("color","#d00000");
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else if(input_market_cost_max.val().length!=0 && market_cost >= parseInt(market_cost_max)){
        market_cost_max_parent.addClass("has-error");
        market_cost_max_alert.html('<p>'+lang.alert_please_enter_amount_higher_price_than_current_market_price+'</p>');
        market_cost_max_alert.css("color","#d00000");
        btn_notify.html(langConvert('lang.alert_market_price_noticy',''));
        btn_notify.attr("disabled", false);
    }else{
        var param = {
            wm_subject : langConvert('lang.alert_market_price_nitification_service',''),     // 제목
            wm_content : "",                // 내용
            wm_type : "ticker",             // type
            wm_market_cost_min: notifyPageSet.market_cost_min,  // 낮은 가격알림
            wm_market_cost_max: notifyPageSet.market_cost_max   // 높은 가격 알림
        };

        var pdata = jQuery.param(param);

        $.post(bean.link.proc,
            pdata,
            function (json){
                if( parseInt(json.result) > 0 ){
                    controllerComm.alertError('<p>'+langConvert('lang.alert_price_notification_request_has_been_completed','')+'</p><p>'+langConvert('lang.msgThanks','')+'.</p>', function (){
                        document.location.reload();
                    });
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.alert_failed_request_price_notification','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function (){
                        document.location.reload();
                    });
                }
            }, "json")
            .done(function(){
            })
            .fail(function(){
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            })
            .always(function(){
            });
    }
}



/*
 * 거래소 시세알림 취소 actionNotifyMarketcostCancel
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */

function actionNotifyMarketcostCancel(btnid, dataid, callback){

    eventDom.btn_notify.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Wait..');
    eventDom.btn_notify.attr("disabled", true);

    var param = {
        wm_status : "CAN"
        ,wm_no: notifyPageSet.wm_no  // wm_no idx
    };

    var pdata = jQuery.param(param);

    $.post(bean.link.proc,
        pdata,
        function (json){
            if( parseInt(json.result) > 0  ){
                controllerComm.alertError('<p>'+langConvert('lang.alert_price_notification_service_cancel_complete','')+'</p><p>'+langConvert('lang.msgThanks','')+'.</p>', function (){
                    document.location.reload();
                });
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.alert_cancel_price_notification_failed','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function (){
                    document.location.reload();
                });
            }
        }, "json")
        .done(function(){
        })
        .fail(function(){
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always(function(){
        });
}



/*
 * 인증센터 레벨업 요청 actionVerificationForm
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionVerificationForm(btnid, dataid, callback){

    var mbcurlevel      = $('input[name="mb_cur_level"]').val();
    var mbreqlevel      = $('input[name="mb_req_level"]').val();
    var mbprovemethod   = $("input[name='mb_prove_method']").val();
    var file1           = $('input[name="mb_prove_file1"]').val();
    var file2           = $('input[name="mb_prove_file2"]').val();
    $(btnid).attr("disabled", true);

    if(!file1){
        $('#verification-alert-mbprovefile1').html('<p>'+langConvert('lang.msgPleaseAttachTheFile', '')+'</p>');
        $('#verification-alert-mbprovefile1').show();
        $(btnid).attr("disabled", false);
        return false;
    }else if(!file2){
        $('#verification-alert-mbprovefile2').html('<p>'+langConvert('lang.msgPleaseAttachTheFile', '')+'</p>');
        $('#verification-alert-mbprovefile2').show();
        $(btnid).attr("disabled", false);
        return false;
    }

    var formData = new FormData();
    formData.append('mb_id', get_member.mb_id);
    formData.append('mb_cur_level', get_member.mb_level);
    formData.append('mb_req_level', mbreqlevel);
    formData.append('mb_prove_method', mbprovemethod);

    formData.append('mb_prove_file1', $('input[name="mb_prove_file1"]')[0].files[0]);
    formData.append('mb_prove_file2', $('input[name="mb_prove_file2"]')[0].files[0]);

    $.ajax({
        url: bean.link.proc,
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(json){
            if(typeof(json)!="undefined"){
                if(parseInt(json.result)>0){
                    controllerComm.alertError("<p>"+langConvert('lang.msgTheInformationRequestIsComplete', '')+"</p>", function (){
                        $(location).attr('href', "/account/verificationcenter/");
                    });
                }else if(parseInt(json.result)==-5402){
                    var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgVerificationRequestFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                    $(btnid).attr("disabled", false);
                    controllerComm.alertError(msg, function (){
                        $(location).attr('href', "/account/verificationcenter/");
                    });
                }else{
                    var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgVerificationRequestFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                    $(btnid).attr("disabled", false);
                    controllerComm.alertError(msg, function (){
//                        $(location).attr('href', "/account/verificationform/");
                    });
                }
            }else{
                var msg = langConvert('lang.msgVerificationRequestFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                $(btnid).attr("disabled", false);
                controllerComm.alertError(msg, function (){
                    $(location).attr('href', "/account/verificationform/");
                });
            }
        },
        always:function(){
            $(btnid).attr("disabled", false);
        }
    });
}



/*
 * 인증센터 모바일 인증 요청 actionVerificationFormMobile
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionVerificationFormMobile(btnid, dataid, callback){

    $(btnid).attr("disabled", true);
    var alert_mbhp          = $('#signup-alert-mbhp');
    var alert_mbcertnumber  = $('#signup-alert-mbcertnumber');

    var mb_hp               = $('input[name="mb_hp"]').val();
    var mb_cert_number      = $('input[name="mb_cert_number"]').val();
    var mb_country          = $('select[name="country_code"]').val();
    var mb_country_dial_code = $('input[name="mb_country_dial_code"]').val();

    if( mb_hp.length<9 ){
        $(btnid).attr("disabled", false);
        var msg = langConvert('lang.msgIncorrectMobileNumber', '');
        controllerComm.alertError('<p>'+msg+'</p>');
        $('#verification-alert-mbhp').html('<p>'+msg+'</p>');
        $('#verification-alert-mbhp').show();
        return false;
    }else if(mb_cert_number.length!=6){
        $(btnid).attr("disabled", false);
        var msg = langConvert('lang.msgPleaseEnterTheCorrectVerificationNumber', '');
        controllerComm.alertError('<p>'+msg+'</p>');
        $('#verification-alert-mbcertnumber').html('<p>'+msg+'</p>');
        $('#verification-alert-mbcertnumber').show();
        return false;
    }

    var en_mb_hp                = $.base64.encode(mb_hp);
    var en_mbcertnumber         = $.base64.encode(mb_cert_number);
    var en_country              = $.base64.encode(mb_country);
    var en_mb_country_dial_code = $.base64.encode(mb_country_dial_code);
    var pdata = {
        mb_hp : en_mb_hp
        ,mb_cert_number : en_mbcertnumber
        ,country : en_country
        ,mb_country_dial_code : en_mb_country_dial_code
    };

    $.post(bean.link.proc,
        pdata,
        function (json){
            if(typeof (json.result) != 'undefined'){
                if(parseInt(json.result) == 1){
                // input error
                    var msg = langConvert('lang.viewAccountVerificationformmobileRequestIsComplete', '');
                    controllerComm.alertError(msg,
                        function (){
                            $(location).attr('href', "/account/verificationcenter")
                        });
                }else{
                    $(btnid).attr("disabled", false);
                    var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.viewAccountVerificationformmobileRequestFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                    controllerComm.alertError(msg);
                }
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.viewAccountVerificationformmobileRequestFailed','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            }
        }, "json")
        .done(function (){
        })
        .fail(function (){
            controllerComm.alertError('<p>'+langConvert('lang.viewAccountVerificationformmobileRequestFailed','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always(function (){
        });
        return false;
}


/*
 * 거래소 주문 수정하기 actionUserTradeBitcoinUpdate
 * @param {type} v
 * @param {type} callback
 * @returns {undefined}
 */
function actionUserTradeBitcoinUpdate(btnid, dataid, callback){
    $('#btn_update_cancel').attr("disabled", true);
    $('#btn_update_submit').attr("disabled", true);
    var pdata = tradeUpdatePageSet;
    var od_total_cost = (tradeUpdatePageSet.od_temp_btc*tradeUpdatePageSet.it_market_cost);
    pdata.od_total_cost = calfloat('FLOOR', od_total_cost, 0);
    pdata.token = $("input[name='token']").val();

    $.post(bean.link.proc,
        pdata,
        function (json){
            $('#tradeUpdateModal').hide();
            $('#btn_update_cancel').attr("disabled", false);
            $('#btn_update_submit').attr("disabled", false);
            if(typeof (json.result) != 'undefined'){
                if(typeof (json.token)!='undeinfed'){
                    if( $("input[name='token']").length > 0){
                        $("input[name='token']").val(json.token);
                    }
                }
                if(parseInt(json.result) > 0){
                    SendEvent.sendTradeRegist();
                    var msg = langConvert('lang.msgTheModifiedOrderIsComplete', '');
                    controllerComm.alertError(msg,
                        function (){
                            setTimeout("getListUncomplet()", 0);
                            setTimeout("initBalanceSum()", 200);
                            setTimeout("getListComplet()", 500);
                        });
                }else{
                    var msg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgFailedToModifyTheOrderRequest','')+' '+langConvert('lang.msgTryAgainLater','');
                    controllerComm.alertError(msg,
                        function (){
                            setTimeout("getListUncomplet()", 0);
                            setTimeout("initBalanceSum()", 200);
                            setTimeout("getListComplet()", 500);
                        });
                }
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgFailedToModifyTheOrderRequest','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            }
        }, "json")
        .done(function (){
        })
        .fail(function (){
            controllerComm.alertError('<p>'+langConvert('lang.msgFailedToModifyTheOrderRequest','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                document.location.reload();
            });
        })
        .always(function (){
        });
        return false;

}
