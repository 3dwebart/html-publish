/** cash, coin deposit and withdrawal controller **/
var walletPageSet = {
    code:'krw',
    action:'deposit',
    coin_code:'btc',
    coin_pre_code:'btc',
    coin_action:'deposit',
    addr:{},
    qr_image:{},
    wallet_use:'Y'
};

var depositCashSet = {
    cash_min_limit:0,
    od_name:$("input[name='od_name']"),
    od_temp_bank:$("input[name='od_temp_bank']"),
    od_bank_name:$("#cf_bank_name"),
    od_bank_owner:$("#cf_bank_owner"),
    od_bank_account:$("#cf_bank_account")
};

var withdrawCashSet = {
    cash_min_limit:0,
    cash_max_limit:0,
    cash_limit_daily:0,
    cash_daily_max_limit_result:false,
    fee:$("input[name='cash-withdraw-fee']"),
    request:$("input[name='cash-withdraw-request']"),
    pay:$("input[name='cash-withdraw-pay']"),
    bank_name:$("input[name='input_bank_name']"),
    bank_account:$("input[name='input_bank_account']"),
    bank_holder:$("input[name='input_bank_holder']")
};

var withdrawCoinSet = {
    coin_min_limit:0.0,
    coin_max_limit:0.0,
    coin_limit_daily:0,
    is_owner_address:0,
    is_correct_address:0,
    coin_daily_max_limit_result:false,
    otp_use:true,
    otp_result:false,
    fee:$("input[name='coin-withdraw-fee']"),
    request:$("input[name='coin-withdraw-request']"),
    pay:$("input[name='coin-withdraw-pay']"),
    addr:$("input[name='coin-withdraw-addr']"),
    otp:$("input[name='g_otp']")
}

var eventDom = {
    btnDepositCash:$("button#btnDepositCash"),
    btnDepositCashSubmit:$("button#btnDepositCashSubmit"),
    btnWithdrawCash:$("button#btnWithdrawCash"),
    btnWithdrawCashSubmit:$("button#btnWithdrawCashSubmit"),
    btnWithdrawCoin:$("button#btnWithdrawCoin"),
    btnWithdrawCoinSubmit:$("button#btnWithdrawCoinSubmit"),
    btnSubmitCancel:$("button.btn_submit-cancel"),
    error_msg:$(".error-msg")
};

$('.ass-table tbody tr').on('click', function(){
    eventDom.error_msg.html('');
    eventDom.error_msg.hide();
    eventDom.btnDepositCash.attr("disabled", true);
    eventDom.btnWithdrawCash.attr("disabled", true);
    
    depositCashSet.od_temp_bank.val('');
    
    withdrawCashSet.request.val('');
    withdrawCashSet.pay.val(0);
    withdrawCashSet.bank_name.val('');
    withdrawCashSet.bank_account.val('');
    
    withdrawCoinSet.addr.val('');
    withdrawCoinSet.request.val('');
    withdrawCoinSet.pay.val('0.00000000');
    eventDom.btnWithdrawCoin.attr("disabled", true);
    
    $('.ass-table tbody tr').removeClass('active');
    $(this).addClass('active');
    
    withdrawCoinSet.otp_result = false;
    withdrawCoinSet.otp.val('');

    var child_controller = $(this).attr('id');
    var split_controller = child_controller.split('-');
    var ctype = split_controller[1];
    var pre_ctype = walletPageSet.coin_pre_code;
    walletPageSet.coin_code = ctype;

    if(child_controller == 'parent-cash'){
        walletPageSet.code = 'krw';
        walletPageSet.action = 'deposit';
        
        depositwait_cash_page();
        $('.history-title').html(langConvert('lang.depositsAwaiting', ''));
        $('select[name="listtype"]').val("depositwait").prop("selected", true);
        
        $('#child-coin').hide();
        $('#child-cash').show();

        $('#child-cash-withdraw').hide();
        $('#child-cash-history').hide();
        $('#child-cash-deposit').show();
    }else{
        walletPageSet.code = '';
        walletPageSet.action = '';
        
        getCoinWithdrawalTxFee(ctype);
        getWithdrawCoinLimit(ctype);
        
        depositwait_page();
        $('.history-title').html(langConvert('lang.depositsAwaiting', ''));
        $('select[name="coinlisttype"]').val("depositwait").prop("selected", true);
        
        $('#child-cash').hide();
        $('#child-coin').show();

        $('#child-coin-withdraw').hide();
        $('#child-coin-history').hide();
        $('#child-coin-deposit').show();

        $('#child-coin .total_controller').removeClass('mb_' + pre_ctype + '_total');
        $('#child-coin .exchange_total_controller').removeClass('mb_' + pre_ctype + '_exchange_total');
        $('#child-coin .poss_controller').removeClass('mb_' + pre_ctype + '_poss');

        $('#child-coin .total_controller').addClass('mb_' + ctype + '_total');
        $('#child-coin .exchange_total_controller').addClass('mb_' + ctype + '_exchange_total');
        $('#child-coin .poss_controller').addClass('mb_' + ctype + '_poss');
        
        $('#child-coin .mb_coin_title').html( langConvert('lang.common.KRW_'+ctype.toUpperCase(), '') );
        $('#child-coin .coin_currency').html( ctype.toUpperCase() );

        initBalance(jsondata);
        
        if(coin_wallet_use[ctype] == 'Y'){
            $('.coin-body-controller').show();
            $('.wait-controller').hide();
            //address가 있는지 확인
            getWalletAddress(walletPageSet.coin_code,function(json){
                if(json && json.hasOwnProperty('result')){
                    if(json.result>0){
                        $('.none-qrcode').hide();
                        showWalletAddress(walletPageSet.coin_code);
                    }else{
                        $('.qrcode-box').hide();
                        $('.none-qrcode').show();
                    }
                }
            });
        }else{
            $('.qrcode-box').hide();
            $('.none-qrcode').hide();
            $('.coin-body-controller').hide();
            $('.wait-controller').show();
        }

        walletPageSet.coin_pre_code = ctype;
    }
});

$(".input-calculation").on("keypress keyup focus focusout", $(this), function () {
    var regex = /^(\d*\.?\d{0,8})$|^\$?[\d,]+(\d*)?$/g;

    var input = $(this).val();
    if (regex.test(input)) {
        var matches = input.match(regex);
        for (var match in matches) {
            displayInputAmount();
        }
    } else if (isNaN($(this).val())) {
        $(this).val(input.slice(0, -1));
        return false;
    } else {
        $(this).val(input.slice(0, -1));
        return false;
    }
});

var displayInputAmount = function(){
    if( walletPageSet.action == 'deposit' ){
        var tmp_input_str = parseInt(depositCashSet.od_temp_bank.val().unformatWon());
        if(tmp_input_str > 0){
            tmp_input_str = tmp_input_str.formatWon();
        }else{
            tmp_input_str = '';
        }
        depositCashSet.od_temp_bank.val( tmp_input_str );
        alertTotalAmount();
    }else if(walletPageSet.action == 'withdraw'){
        var tmp_input_str = parseInt(withdrawCashSet.request.val().unformatWon());
        if( tmp_input_str >= parseInt(withdrawCashSet.fee.val().unformatWon()) ){
            withdrawCashSet.pay.val( ( tmp_input_str - parseInt(withdrawCashSet.fee.val().unformatWon()) ).formatWon() );
        }else{
            withdrawCashSet.pay.val( '0' );
        }
        if(tmp_input_str > 0){
            tmp_input_str = tmp_input_str.formatWon();
        }else{
            tmp_input_str = '';
        }
        withdrawCashSet.request.val( tmp_input_str );
        alertTotalAmount();
    }else if(walletPageSet.code == ''){
        if( !withdrawCoinSet.request.val() ){
            withdrawCoinSet.pay.val( '0.00000000' );
        }else if( parseFloat(withdrawCoinSet.request.val()) < parseFloat(withdrawCoinSet.fee.val()) ){
            withdrawCoinSet.pay.val( '0.00000000' );
        }else if( parseFloat(withdrawCoinSet.coin_min_limit) > parseFloat(withdrawCoinSet.request.val()) ){
            withdrawCoinSet.pay.val( '0.00000000' );
        }else{
            withdrawCoinSet.pay.val( (parseFloat(withdrawCoinSet.request.val()) - parseFloat(withdrawCoinSet.fee.val())).formatBitcoin() );
        }
        alertFormValidation();
    }
    
};

/** cash deposit and withdrawal controller start **/
// cash bank-info
function getBankInfo(){
    $.getJSON("/webconfigbankaccount/depositinfo/", "", function (data) {
    })
    .success(function(data) {
        if( typeof(data)!=='undefined' && Array.isArray(data) && data.length>0){
            $('#cf_bank_name').html(data[0].cf_bank_name);
            $('#cf_bank_owner').html(data[0].cf_bank_owner);
            $('#cf_bank_account').html(data[0].cf_bank_account);
        }
    });
}

// cash sub-tab select
function selectWallet(selectType){
    eventDom.error_msg.html('');
    eventDom.error_msg.hide();
    eventDom.btnDepositCash.attr("disabled", true);
    eventDom.btnWithdrawCash.attr("disabled", true);
    
    depositCashSet.od_temp_bank.val('');
    
    withdrawCashSet.request.val('');
    withdrawCashSet.pay.val(0);
    withdrawCashSet.bank_name.val('');
    withdrawCashSet.bank_account.val('');
    
    if( selectType == 'deposit' ){
        $('#child-cash-withdraw').hide();
        $('#child-cash-history').hide();
        $('#child-cash-deposit').show();

        walletPageSet.action = 'deposit';
    }else if( selectType == 'withdraw' ){
        $('#child-cash-deposit').hide();
        $('#child-cash-history').hide();
        $('#child-cash-withdraw').show();

        walletPageSet.action = 'withdraw';
    }else if( selectType == 'history' ){
        $('#child-cash-withdraw').hide();
        $('#child-cash-deposit').hide();
        $('#child-cash-history').show();
    }else{
        $('#child-cash-withdraw').hide();
        $('#child-cash-history').hide();
        $('#child-cash-deposit').show();

        walletPageSet.action = 'deposit';
    }
}

$(".input-alert").on("input keypress keyup change", $(this), function () {
    alertTotalAmount();
});

$("#btnDepositCash").on('click', function(){
    eventDom.error_msg.html('');
    eventDom.error_msg.hide();

    if(checkForm()){
        eventDom.btnDepositCash.attr("disabled", true);
        eventDom.btnSubmitCancel.attr("disabled", false);
        
        var modalbody = langConvert('lang.viewWalletKrwDepositDepositorName', '') + " : " + depositCashSet.od_name.val() + "<br /><br />";
            modalbody += langConvert('lang.viewWalletKrwDepositDepositAmount', '') + " : " + depositCashSet.od_temp_bank.val() + " "+langConvert('lang.common.keyCurrency', '')+"<br /><br />";
            modalbody += langConvert('lang.viewWalletKrwDepositBankName', '') + " : " + depositCashSet.od_bank_name.text() + "<br /><br />";
            modalbody += langConvert('lang.msgCashDepositRequest', '')+"<br />";
            
        $(".depositModalBody").html(modalbody);
        $('#depositCashModal').modal('show');
    }
});

$("#btnDepositCashSubmit").click(function(){

    if(checkForm()){
        $('#depositCashModal').hide();
        Account.isLogined(function(islogin){
            if(islogin){
                // 입금 요청
                controllerForm.setBeanData({
                    result:0,
                    link:{proc:"/webcashdeposit/regist/"}
                });
                controllerForm.setInitForm("depositkrw");
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>',function(){
                    $(location).attr('href',"/account/signin");
                });
            }
        });
    }
});

$("#btnWithdrawCash").on('click', function(){
    eventDom.error_msg.html('');
    eventDom.error_msg.hide();

    if(checkForm()){
        eventDom.btnWithdrawCash.attr("disabled", true);
        eventDom.btnSubmitCancel.attr("disabled", false);
        
        var modalbody = langConvert('lang.viewWalletKrwWithdrawWithdrawalAmount', '')+" : " + withdrawCashSet.request.val() + " "+langConvert('lang.common.keyCurrency', '')+" <br /><br />";
            modalbody += langConvert('lang.viewWalletKrwWithdrawWithdrawalAmountExcludedFees', '') +" : " + withdrawCashSet.pay.val() + " "+langConvert('lang.common.keyCurrency', '')+" <br /><br />";
            modalbody += langConvert('lang.viewWalletKrwWithdrawBankName', '') + " : " + withdrawCashSet.bank_name.val() + "<br /><br />";
            modalbody += langConvert('lang.viewWalletKrwWithdrawAccountNumber', '') + " : " + withdrawCashSet.bank_account.val() + "<br /><br />";
            modalbody += langConvert('lang.viewWalletKrwWithdrawAccountHolder', '') + " : " + withdrawCashSet.bank_holder.val() + "<br /><br />";
            modalbody += langConvert('lang.msgPressTheWithdrawalToCompleteTheWithdrawalWon', '') + "<br />";
            modalbody += langConvert('lang.msgAfterCompletionRequiresEmailVerification', '');
                
        $(".withdrawModalBody").html(modalbody);
        $('#withdrawCashModal').modal('show');
    }
});

$("#btnWithdrawCashSubmit").click(function(){
    Account.isLogined(function(islogin){
        eventDom.btnWithdrawCashSubmit.attr('disabled', true);
        eventDom.btnSubmitCancel.attr('disabled', true);
        $(".withdrawCashModal_btnnm").html('Wait..');
        if(islogin){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/webcashwithdraw/regist/"}
            });
            controllerForm.setInitForm("withdrawkrw");
        }else{
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>',function(){
                $(location).attr('href',"/account/signin");
            });
        }
    });
});

// modal close event
$('#depositCashModal').on('hidden.bs.modal',
    function(e){
        eventDom.btnDepositCash.attr('disabled', false);
    }
);

$('#withdrawCashModal').on('hidden.bs.modal',
    function(e){
        eventDom.btnWithdrawCash.attr('disabled', false);
    }
);

var alertTotalAmount = function(){
    eventDom.error_msg.html('');
    eventDom.error_msg.hide();
    
    if( walletPageSet.action == 'deposit' ){
        if(checkForm()){
            eventDom.btnDepositCash.attr("disabled", false);
        }else{
            eventDom.btnDepositCash.attr("disabled", true);
        }
    }else{
        if(checkForm()){
            eventDom.btnWithdrawCash.attr("disabled", false);
        }else{
            eventDom.btnWithdrawCash.attr("disabled", true);
        }
    }
};

function checkForm(){
    if( walletPageSet.action == 'deposit' ){
        if( !depositCashSet.od_name.val() ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputDepositorsName1', '')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if( parseInt(depositCashSet.od_temp_bank.val().unformatWon()) == 0 ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputDepositAmount','')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if( parseInt(depositCashSet.od_temp_bank.val().unformatWon()) < parseInt(depositCashSet.cash_min_limit) ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumDepositLimitOfxxKrw', [(depositCashSet.cash_min_limit+'').formatNumber()])+'</p>');
            eventDom.error_msg.show();
            return false;
        }
        return true;
    }else{
        if( !withdrawCashSet.request.val() ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnterTheWithdrawalAmount', '')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if( parseInt(withdrawCashSet.fee) >= parseInt(withdrawCashSet.request.val().unformatWon()) ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalFee', '')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if( parseInt(withdrawCashSet.request.val().unformatWon()) < parseInt(withdrawCashSet.cash_min_limit) ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalLimitOfxxKrw', [withdrawCashSet.cash_min_limit.formatWon()])+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if(withdrawCashSet.cash_daily_max_limit_result==true && parseInt(withdrawCashSet.request.val().unformatWon()) > parseInt(withdrawCashSet.cash_daily_max_limit) ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheDailyWithdrawalLimit', '')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if( parseInt(withdrawCashSet.request.val().unformatWon()) > parseInt($('.mb_krw_poss').text().unformatWon()) ){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureYourKrwAccountBalanceToKrw', '')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if(!withdrawCashSet.bank_name.val()){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputTheBankName','')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if(!withdrawCashSet.bank_account.val()){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputTheBankAccount','')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if((withdrawCashSet.bank_account.val()).length<8){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnterMoreThan8LetterForYourAccountNumber','')+'</p>');
            eventDom.error_msg.show();
            return false;
        }else if(!withdrawCashSet.bank_holder.val()){
            eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputDepositorsName','')+'</p>');
            eventDom.error_msg.show();
            return false;
        }
        return true;
    }
}

function getWithdrawCashLimit(){
    $.getJSON("/webconfigwalletlimit/select/wallettype-CASH/", "", function (data) {
    })
    .success(function(data) {
        if( typeof(data)!=='undefined' && Array.isArray(data) && data.length>0 && data[0].cf_max_withdraw>0){
            withdrawCashSet.cash_min_limit = parseInt(data[0].cf_min_withdraw);
            withdrawCashSet.cash_max_limit = parseInt(data[0].cf_max_withdraw);
            $("#cash_max_limit").html( withdrawCashSet.cash_max_limit.formatWon() );

            withdrawCashSet.cash_limit_daily = parseInt(data[0].cf_max_day);
            withdrawCashSet.cash_daily_max_limit_result = true;
            dailyWithdrawSum();
        }else{
            $('#daily_cash_max_limit').html(langConvert('lang.commonUnlimited', ''));
            $('#cash_max_limit').html(langConvert('lang.commonUnlimited', ''));
            return false;
        }
    });
}

function dailyWithdrawSum(){
    if(withdrawCashSet.cash_daily_max_limit_result==false){
        return false;
    }
    var param = (withdrawCashSet.cash_limit_daily-1);
    if(param<0){
        return false;
    }
    $.getJSON("/webcashwithdraw/withdrawsum/day-"+param+"/", "", function (data) {
    })
    .success(function(data) {
        if(!data || typeof(data)=='undefined'){
            return false;
        }
        if(Array.isArray(data) && data.length>0 && typeof data[0].sum_krw!='undefined'){
            /*exchangePageSet.krw_daily_max_limit = exchangePageSet.krw_max_limit - parseInt(data[0].sum_krw);
            if(exchangePageSet.krw_daily_max_limit<0){
                exchangePageSet.krw_daily_max_limit = 0;
            }*/
        }
    });
}
/** cash deposit and withdrawal controller end **/




/** coin deposit and withdrawal controller start **/
// coin sub-tab select
function selectCoinWallet(selectType){
    
    withdrawCoinSet.addr.val('');
    withdrawCoinSet.request.val('');
    withdrawCoinSet.pay.val('0.00000000');
    eventDom.btnWithdrawCoin.attr("disabled", true);
    
    withdrawCoinSet.otp_result = false;
    withdrawCoinSet.otp.val('');
    
    if( selectType == 'deposit' ){
        $('#child-coin-withdraw').hide();
        $('#child-coin-history').hide();
        $('#child-coin-deposit').show();

        walletPageSet.coin_action = 'deposit';
    }else if( selectType == 'withdraw' ){
        $('#child-coin-deposit').hide();
        $('#child-coin-history').hide();
        $('#child-coin-withdraw').show();

        walletPageSet.coin_action = 'withdraw';
    }else if( selectType == 'history' ){
        $('#child-coin-withdraw').hide();
        $('#child-coin-deposit').hide();
        $('#child-coin-history').show();
    }else{
        $('#child-coin-withdraw').hide();
        $('#child-coin-history').hide();
        $('#child-coin-deposit').show();

        walletPageSet.coin_action = 'deposit';
    }
}

// coin wallet
function getWalletAddress(currency,callback){
    var t = Utils.getTimeStamp();
    var addr = '';

    $.getJSON("/getdepositwallet/walletaddress/currency-" + currency + "/?t=" + t, "", function (data){
            var json = {result:0};
            if(Array.isArray(data)){
                json = data[0];
            }        
            if(typeof callback === 'function'){
                callback(json);
            }
        }, "json"
        )
        .done(function (){
        })
        .fail(function (){
            controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
            document.location.reload();
        });
    });
}

// coin wallet
function showWalletAddress(currency,callback){
    var contentDom = $('#deposit-address');
    var qrImageDom = $('.qrcode-box > .code > .qr');
    contentDom.html('<div class="loading-round"></div>');

    var addr = '';
    var qr_image = '';
    
    for(var i = 0; i < coin_arr.length; i++){
        if(currency == coin_arr[i]){
            addr = walletPageSet.addr[i];
            qr_image = walletPageSet.qr_image[i];
        }
    }

    if(typeof addr === 'undefined'){
        $.post("/getdepositwallet/showaddress/",
            "currency="+currency,
            function (json){

                if(typeof callback === "function"){
                    callback(json);
                }else{
                    if(json && json.hasOwnProperty('result')){
                        if(json.result > 0 ){
                            contentDom.val(json.address);
                            var encaddr = $.base64.encode(json.address);
                            qrImageDom.html('<img src="/Image/wallet/q-' + encaddr +'">');
                            $('.qrcode-box').show();
                            for(var i = 0; i < coin_arr.length; i++){
                                if(currency == coin_arr[i]){
                                    walletPageSet.addr[i] = json.address;
                                    walletPageSet.qr_image[i] = encaddr;
                                }
                            }
                        }else{
                            controllerComm.alertError('<p>'+json.error+'</p>', function(){

                            });
                        }
                    }else{
                        controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                            document.location.reload();
                        });
                    }
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
    }else{
        contentDom.val(addr);
        qrImageDom.html('<img src="/Image/wallet/q-' + qr_image +'">');
        $('.qrcode-box').show();
    }
}

// coin wallet
function createWallet(){
    $('.none-qrcode').hide();
    showWalletAddress(walletPageSet.coin_code);
}

// coin wallet address copy
$('.btn-copy').on('click', function(){
    $('#deposit-address').attr('disabled', false);
    $('#deposit-address').select();
    document.execCommand('copy');
    $('#deposit-address').attr('disabled', true);
    controllerComm.alertError('복사되었습니다.');
});

// get coin txfee
function getCoinWithdrawalTxFee(currency){
    $.getJSON("/webconfigwalletserver/select/po_type-"+currency+"/", "", function (data) {
    })
    .success(function(data) {
        if( typeof data !== 'undefined'){
            withdrawCoinSet.fee.val(data[0].wa_tx_fee);
        }
    });
}

var alertFormValidation = function(param){
    var currentCtype =  $('.volume.coin-body-controller dl').eq(0).find('.coin_currency').text();
    if(withdrawCoinSet.is_owner_address==1){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgNotUsedOwnerAddress', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr("disabled", true);
        return false;
    }else if(withdrawCoinSet.is_correct_address!=1){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgIncorrectBtcAddress', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr("disabled", true);
        return false;
    }else if( !withdrawCoinSet.addr.val() ){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputBtcAddress', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else if( parseFloat(withdrawCoinSet.request.val()) > parseFloat($('.mb_' + walletPageSet.coin_code + '_poss').text()) ){
        //eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureYourBtcAccountBalance', '')+'</p>');
        eventDom.error_msg.html('<p>보유한 '+currentCtype+'보다 입력한 '+currentCtype+'가 더 큽니다</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else if( !withdrawCoinSet.request.val() ){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseInputBtcWithdrawalAmount', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else if( parseFloat(withdrawCoinSet.request.val()) < parseFloat(withdrawCoinSet.coin_min_limit) ){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalLimitOfxxBtc', [(parseFloat(withdrawCoinSet.coin_min_limit))] )+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else if( parseFloat(withdrawCoinSet.fee.val()) >= parseFloat(withdrawCoinSet.request.val()) ){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheMinimumWithdrawalFee', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else if( withdrawCoinSet.coin_daily_max_limit_result==true && parseFloat(withdrawCoinSet.request.val()) > parseFloat(withdrawCoinSet.coin_daily_max_limit) ){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseEnsureTheDailyWithdrawalLimit', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else if( withdrawCoinSet.otp_use==true && withdrawCoinSet.otp_result==false ){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgPleaseOtp', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr('disabled',true);
        return false;
    }else{
        eventDom.error_msg.html('');
        eventDom.error_msg.hide();
        eventDom.btnWithdrawCoin.attr('disabled',false);
        return true;
    }
};

function alertValidationAddress(){
    if(withdrawCoinSet.is_owner_address==1){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgNotUsedOwnerAddress', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr("disabled", true);
        return false;
    }else if(withdrawCoinSet.is_correct_address!=1){
        eventDom.error_msg.html('<p>'+langConvert('lang.msgIncorrectBtcAddress', '')+'</p>');
        eventDom.error_msg.show();
        eventDom.btnWithdrawCoin.attr("disabled", true);
        return false;
    }else{
        eventDom.error_msg.html('');
        eventDom.error_msg.hide();
        alertFormValidation();
    }
}

function getWithdrawCoinLimit(currency){
    $.getJSON("/webconfigwalletlimit/select/wallettype-" + currency + "/", "", function (data) {
    })
    .success(function(data) {
        if( typeof(data)!=='undefined' && Array.isArray(data) && data.length>0 && data[0].cf_max_withdraw>0){
            withdrawCoinSet.coin_min_limit = parseFloat(data[0].cf_min_withdraw);
            withdrawCoinSet.coin_max_limit = parseFloat(data[0].cf_max_withdraw);

            withdrawCoinSet.coin_limit_daily = parseInt(data[0].cf_max_day);
            withdrawCoinSet.coin_daily_max_limit_result = true;
            $('.coin_max_limit').html( (data[0].cf_max_withdraw).formatBitcoin() );
            dailyWithdrawCoinSum(currency);
        }else{
            withdrawCoinSet.coin_min_limit = 0.0;
            withdrawCoinSet.coin_max_limit = 0.0;

            withdrawCoinSet.coin_limit_daily = 0;
            withdrawCoinSet.coin_daily_max_limit_result = false;
            $('.coin_max_limit').html( '0.00000000' );
            
            withdrawCoinSet.coin_daily_max_limit = withdrawCoinSet.coin_max_limit;
            return false;
        }
    });
}

function dailyWithdrawCoinSum(currency){
    if(withdrawCoinSet.coin_daily_max_limit_result==false){
        return false;
    }
    var param = (withdrawCoinSet.coin_limit_daily-1);
    if(param<0){
        return false;
    }
    var t       = Utils.getTimeStamp();
    $.getJSON("/getwithdrawals/withdrawsum/po_type-" + currency + "/day-" + param + "/?t=" + t, "", function (data) {
    })
    .success(function(data) {
        if(!data || typeof(data)=='undefined'){
            return false;
        }
        if(Array.isArray(data) && data.length>0 && typeof data[0].sum_coin!='undefined'){
            if(data[0].sum_coin){
                withdrawCoinSet.coin_daily_max_limit = withdrawCoinSet.coin_max_limit - parseFloat(data[0].sum_coin);
                if(withdrawCoinSet.coin_daily_max_limit<0.0){
                    withdrawCoinSet.coin_daily_max_limit = 0.0;
                }
            }else{
                withdrawCoinSet.coin_daily_max_limit = withdrawCoinSet.coin_max_limit;
            }
            //$('.coin_daily_max_limit').html( (withdrawCoinSet.coin_daily_max_limit).formatBitcoin() );
        }
    });
}

$(withdrawCoinSet.addr).on("input keypress keyup change", $(this), function () {
    if( walletPageSet.coin_code == 'etc' || walletPageSet.coin_code == 'eth' ){
        validEthereum();
    }else{
        coinAddressCheck();
    }
});

function validEthereum(){
    withdrawCoinSet.is_correct_address = 0;

    var address = withdrawCoinSet.addr.val();

    if(address.substr(0, 2) != '0x' || address.length < 42){
        withdrawCoinSet.is_correct_address = 0;
        alertValidationAddress();
        return false;
    }else{
        withdrawCoinSet.is_correct_address = 1;
        alertValidationAddress();
    }
}

function coinAddressCheck(){

    withdrawCoinSet.is_correct_address = 0;
    withdrawCoinSet.is_owner_address = 0;
    withdrawCoinSet.coin_sendto = '';

    var address = withdrawCoinSet.addr.val();

    if(address.length>34 || address.length<27){
        withdrawCoinSet.is_correct_address = 0;
        alertValidationAddress();
        return false;
    }
    if(/[0OIl]/.test(address)){
        withdrawCoinSet.is_correct_address = 0;
        alertValidationAddress();
        return false;
    }

    $.getJSON("/address/valid/po_type-<?=$view['currency']?>/od_addr-"+address+"/", "json", function (data) {
    })
    .success(function(data) {
        if(typeof(data)!=='undefined'){
            if(data.result > 0){
                withdrawCoinSet.is_correct_address = 1;
                if(typeof data.isinner && data.isinner == true){
                    if(data.account==get_member.mb_id){
                        withdrawCoinSet.is_owner_address = 1;
                    }else{
                        withdrawCoinSet.coin_sendto = data.account;
                    }
                }
            }
            alertValidationAddress();
        }
    })
    .error(function(){
        alertValidationAddress();
    });
}

$("#otp-btn").click(function (){
    $("#otp-btn").attr("disabled", true);
    var t       = Utils.getTimeStamp();
    var param = "t="+t+"&g_otp="+$("input[name='g_otp']").val();
    $.post("/getotp/check/", param, function (json){
            if(typeof callback === "function"){
                callback(json);
            }else{
                if(json && json.hasOwnProperty('result')){
                    if(json.result > 0 ){
                        controllerComm.alertError('<p>'+langConvert('lang.msgConfirmOtp','')+'</p>', function(){
                            withdrawCoinSet.otp_result = true;
                            eventDom.error_msg.html('');
                            eventDom.error_msg.show();
                            alertFormValidation();
                        });
                    }else{
                        controllerComm.alertError('<p>'+json.error+'</p>', function(){
                            $("#otp-btn").attr("disabled", false);
                        });
                    }
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                        document.location.reload();
                    });
                }
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
});

$("#btnWithdrawCoin").click(function(){
    if( walletPageSet.coin_code == 'etc' || walletPageSet.coin_code == 'eth' ){
        validEthereum();
    }else{
        coinAddressCheck();
    }
    var modalbody = langConvert('lang.viewWalletCoinWithdrawAddress', '') + " : " + withdrawCoinSet.addr.val()+"<br /><br />";
        modalbody += langConvert('lang.viewWalletCoinWithdrawAmount', '') + " : " + withdrawCoinSet.request.val()+" "+langConvert('lang.common.encryptCurrency'+(walletPageSet.coin_code).toUpperCase(), '')+" <br /><br />";
        modalbody += langConvert('lang.viewWalletCoinWithdrawAmountExcludedFees', '') + " : " + withdrawCoinSet.pay.val()+ " "+langConvert('lang.common.encryptCurrency'+(walletPageSet.coin_code).toUpperCase(), '')+" <br /><br />";
        modalbody += langConvert('lang.msgBtcWithdrawalWillBeCompletedByPressingTheWithdrawal', '') + "<br />";
        modalbody += langConvert('lang.msgAfterCompletionRequiresEmailVerification', '');
    $(".withdrawCoinModalBody").html(modalbody);
    $('#withdrawCoinModal').modal('show');
    eventDom.btnWithdrawCoin.attr('disabled', true);
});

// modal close event
$('#withdrawCoinModal').on('hidden.bs.modal',
    function(e){
        eventDom.btnWithdrawCoin.attr('disabled', false);
    }
);

$("#btnWithdrawCoinSubmit").click(function(){
    var addr = withdrawCoinSet.addr.val();
    var amount = withdrawCoinSet.request.val();
    var fee = withdrawCoinSet.fee.val();

    if(!addr || !amount || Number(amount) == 0 || addr.length<10){
        return;
    }

    eventDom.btnWithdrawCoinSubmit.attr('disabled', true);
    eventDom.btnSubmitCancel.attr('disabled', true);
    $(".withdrawCoinModal_btnnm").html('Wait..');

    var param = {
        od_status: 'WAIT'
        , od_temp_amount: amount
        , od_addr: addr
        , od_addr_msg: ''
        , od_sendto: ''
        , od_fee: fee
        , po_type: walletPageSet.coin_code
        , token: ''
    };

    var pdata = jQuery.param(param);    
    $.post("/getwithdrawals/regist/",
    pdata,
    function (json){
        $('#withdrawCoinModal').modal('hide');
        if(typeof callback === "function"){
//                    callback(json);
        }else{
            if(json && json.hasOwnProperty('result')){
                if(Number(json.result) > 0 ){
                    //var coinex = '<b>'+(( Number(param.od_temp_amount) - Number(param.od_fee) ).toFixed(8)) +' BTC</b> ';
                    if( Number(json.mailsend)==1){
                        controllerComm.alertError('<p>' + langConvert('lang.msgBtcWithdrawalRequestSucceeded','')+'</p><p>'+langConvert('lang.msgPleaseConfirmTheWithdrawalRequestEmail','')+'</p>', function(){
                            document.location.reload();
                        });
                    }else if( Number(json.mailsend)==-1){
                        controllerComm.alertError('<p>' + langConvert('lang.msgSuccessfulBtcWithdrawalRequestButFailedToDeliverTheEmail','')+'</p><p>'+langConvert('lang.msgPleaseCustomerServiceContactUs','')+'</p>', function (){
                            document.location.reload();
                        });
                    }
                    //barAnim(Number(json.withdraw_remainbtc_per));
                }else{
                    controllerComm.alertError('<p>'+json.error+'</p>', function(){
                        document.location.reload();
                    });
                }
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
                    document.location.reload();
                });
            }
        }
    }, "json")
    .fail(function (){
        controllerComm.alertError('<p>'+langConvert('lang.msgServerAccessProblem','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>', function(){
            document.location.reload();
        });
    })
    .always(function (){

    });
});
/** coin deposit and withdrawal controller end **/