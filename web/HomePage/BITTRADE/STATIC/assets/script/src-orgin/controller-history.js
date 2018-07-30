var listPageSet = {
    totalcount:0            // 전체 레코드 수
    ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
    ,current_page:1         // 현재 선택된 페이지 번호
    ,page_block:5           // 블록수 5
    ,total_page:null        // 전체 페이지 갯수
    ,total_block:null       // 전페 블록 갯수
    ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..)
};

var listPageSet2 = {
    totalcount:0            // 전체 레코드 수
    ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
    ,current_page:1         // 현재 선택된 페이지 번호
    ,page_block:5           // 블록수 5
    ,total_page:null        // 전체 페이지 갯수
    ,total_block:null       // 전페 블록 갯수
    ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..)
};

var historyPageSet = {
    listtype:$('select[name=listtype]'),
    coinlisttype:$('select[name=coinlisttype]')
};

/** cash history start **/
// 원화 입금내역 - 페이징
var deposit_cash_page = function(){
    var t       = Utils.getTimeStamp();
    var param   = "it_id_pay-Y/od_del_yn-N/t-"+t+"/";
    $.getJSON("/webcashdeposit/listsenv/"+param, "", function (data) {
    })
    .success(function(data) {
        for(i=0; i<data.length; i++){
            if(typeof(data[i].rowlimit)==="undefined"){

            }else{
                listPageSet.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                listPageSet.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                listPageSet.total_page      = calfloat('CEIL', listPageSet.totalcount/listPageSet.rowlimit, 0);
                listPageSet.total_block     = calfloat('CEIL', listPageSet.total_page/listPageSet.page_block, 0);
                listPageSet.current_block   = calfloat('CEIL', (listPageSet.current_page)/listPageSet.page_block, 0);
                init_pager();
                deposit_cash_list_content(listPageSet.current_page);
            }
        };
    });
};

// 원화 입금내역 - 테이블 헤더
var deposit_cash_list_header = function(){
    var listheadarray = [];
    var listheadview = '';
    listheadview += '<tr>';
    listheadview += '<th>'+langConvert('lang.msgDepositRequestDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositName', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositAmount', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositBankName', '')+'/'+langConvert('lang.msgDepositAccountNumber', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositStatus', '')+'</th>';
    listheadview += '</tr>';
    listheadarray.push(listheadview);
    $('table.list-cashhistory>thead').html(listheadarray.join());
};

// 원화 입금내역 - 데이터
var deposit_cash_list_content = function(current_page){
    deposit_cash_list_header();
    var t       = Utils.getTimeStamp();
    var param = "it_id_pay-Y/od_del_yn-N/t-"+t+"/&page="+current_page+"/";
    $.getJSON("/webcashdeposit/lists/"+param, "json", function (data) {
    })
    .success(function(data) {
        var listarray   = [];
        var listview    = '';
        if( typeof(data)!=='undefined'){
            if( parseInt(data[0].result) > 0){
                for( var i=0; i<data.length; i++ ){
                    listview = '<tr>';
                    listview += '<td>'+data[i].od_datetime+'</td>';
                    listview += '<td>'+data[i].od_deposit_name+'</td>';
                    listview += '<td>'+(data[i].od_temp_bank+'').toString().formatWon()+'</td>';
                    listview += '<td>'+data[i].od_hope_date+'</td>';
                    listview += '<td>'+data[i].od_bank_account+'</td>';
                    listview += '<td>'+langConvert('lang.msgDepositComplete', '')+'</td>';
                    listview += '</tr>';
                    listarray.push(listview);
                }
            }else{
                listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
                listarray.push(listview);
            }
        }else{
            listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
            listarray.push(listview);
        }
        $('table.list-cashhistory>tbody').html(listarray.join());
    });

};

// 원화 입금대기내역 - 페이징
var depositwait_cash_page = function(){
    var t       = Utils.getTimeStamp();
    var param   = "it_id_pay-N/od_del_yn-N/t-"+t+"/";
    $.getJSON("/webcashdeposit/waitlistsenv/"+param, "", function (data) {
    })
    .success(function(data) {
        for(i=0; i<data.length; i++){
            if(typeof(data[i].rowlimit)==="undefined"){

            }else{
                listPageSet.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                listPageSet.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                listPageSet.total_page      = calfloat('CEIL', listPageSet.totalcount/listPageSet.rowlimit, 0);
                listPageSet.total_block     = calfloat('CEIL', listPageSet.total_page/listPageSet.page_block, 0);
                listPageSet.current_block   = calfloat('CEIL', (listPageSet.current_page)/listPageSet.page_block, 0);
                init_pager();
                depositwait_cash_list_content(listPageSet.current_page);
            }
        };
    });
};

// 원화 입금대기내역 - 테이블 헤더
var depositwait_cash_list_header = function(){
    var listheadarray = [];
    var listheadview = '';
    listheadview += '<tr>';
    listheadview += '<th>'+langConvert('lang.msgDepositRequestDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositName', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositAmount', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositBankName', '')+'/'+langConvert('lang.msgDepositAccountNumber', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.msgDepositStatus', '')+'</th>';
    listheadview += '</tr>';
    listheadarray.push(listheadview);
    $('table.list-cashhistory>thead').html(listheadarray.join());
};

// 원화 입금대기내역 - 테이블 헤더
var depositwait_cash_list_content = function(current_page){
    depositwait_cash_list_header();
    var t       = Utils.getTimeStamp();
    var param   = "it_id_pay-N/od_del_yn-N/t-"+t+"/&page="+current_page+"/";
    $.getJSON("/webcashdeposit/waitlists/"+param, "json", function (data) {
    })
    .success(function(data) {
        var listarray   = [];
        var listview    = '';
        if( typeof(data)!=='undefined'){
            if( parseInt(data[0].result) > 0){
                for( var i=0; i<data.length; i++ ){
                    listview = '<tr>';
                    listview += '<td>'+data[i].od_datetime+'</td>';
                    listview += '<td>'+data[i].od_deposit_name+'</td>';
                    listview += '<td>'+(data[i].od_temp_bank+'').toString().formatWon()+'</td>';
                    listview += '<td>'+data[i].od_bank_account+'</td>';
                    listview += '<td>'+langConvert('lang.msgDepositWaiting', '')+'</td>';
                    listview += '</tr>';
                    listarray.push(listview);
                }
            }else{
                listview = '<tr><th colspan="5" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
                listarray.push(listview);
            }
        }else{
            listview = '<tr><th colspan="5" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
            listarray.push(listview);
        }
        $('table.list-cashhistory>tbody').html(listarray.join());
    });
};

// 원화 출금내역 - 페이징
var withdraw_cash_page = function(){
    var t       = Utils.getTimeStamp();
    var param   = "odaction-withdrawkrw/t-"+t+"/";
    $.getJSON("/webcashwithdraw/listsenv/"+param, "", function (data) {
    })
    .success(function(data) {
        for(i=0; i<data.length; i++){
            if(typeof(data[i].rowlimit)==="undefined"){

            }else{
                listPageSet.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                listPageSet.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                listPageSet.total_page      = calfloat('CEIL', listPageSet.totalcount/listPageSet.rowlimit, 0);
                listPageSet.total_block     = calfloat('CEIL', listPageSet.total_page/listPageSet.page_block, 0);
                listPageSet.current_block   = calfloat('CEIL', (listPageSet.current_page)/listPageSet.page_block, 0);
                init_pager();
                withdraw_cash_list_content(listPageSet.current_page);
            }
        };
    });
};

// 원화 출금내역 - 테이블 헤더
var withdraw_cash_list_header = function(){
    var listheadarray = [];
    var listheadview = '';
    listheadview += '<tr>';
    listheadview += '<th>'+langConvert('lang.viewHistoryKrwWithdrawOrdersDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryKrwWithdrawOrdersKrwAmount', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryKrwWithdrawOrdersFees', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryKrwWithdrawOrdersKrw', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryKrwWithdrawOrdersStatus', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryKrwWithdrawOrdersRmks', '')+'</th>';
    listheadview += '</tr>';
    listheadarray.push(listheadview);
    $('table.list-cashhistory>thead').html(listheadarray.join());
};

// 원화 출금내역 - 리스트 데이터
var withdraw_cash_list_content = function(current_page){
    withdraw_cash_list_header();
    var t       = Utils.getTimeStamp();
    var param   = "odaction-withdrawkrw/t-"+t+"/&page="+current_page+"/";
    $.getJSON("/webcashwithdraw/lists/"+param, "json", function (data) {
    })
    .success(function(data) {
        var listarray   = [];
        var listview    = '';
        var list_od_status = '';
        if( typeof(data)!=='undefined'){
            if( parseInt(data[0].result) > 0){
                for( var i=0; i<data.length; i++ ){
                    listview =  '<tr>';
                    listview += '<td>'+data[i].od_reg_dt+'</td>';
                    listview += '<td>'+(data[i].od_temp_amount+"").formatWon()+'</td>';
                    listview += '<td>'+(data[i].od_fee+"").formatWon()+'</td>';
                    listview += '<td>'+( parseFloat(data[i].od_temp_amount) - parseFloat(data[i].od_fee)+"" ).formatWon()+'</td>';

                    if(data[i].od_status==="WAIT"){
                        list_od_status = langConvert('lang.msgWithdrawAwaitingVerification', '');
                    }else if(data[i].od_status==="REQ"){
                        list_od_status = langConvert('lang.msgWithdrawRequestProgress', '');
                    }else if(data[i].od_status==="REJ"){
                        list_od_status = langConvert('lang.msgWithdrawRequestDenied', '');
                    }else if(data[i].od_status==="OK"){
                        list_od_status = langConvert('lang.msgWithdrawCompletePayment', '');
                    }else if(data[i].od_status==="HOLD"){
                        list_od_status = langConvert('lang.msgWithdrawlHold', '');
                    }else if(data[i].od_status==="CAN" || data[i].od_status==="CAN_D"){
                        list_od_status = langConvert('lang.msgWithdrawCancel', '');
                    }

                    listview += '<td>'+list_od_status+'</td>';
                    if(data[i].od_status_msg){
                        listview += '<td>'+data[i].od_status_msg+'</td>';
                    }else{
                        listview += '<td></td>';
                    }
                    listview += '</tr>';
                    listarray.push(listview);
                }
            }else{
                listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoWithdrawHistory', '')+'</center></th></tr>';
                listarray.push(listview);
            }
        }else{
            listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoWithdrawHistory', '')+'</center></th></tr>';
            listarray.push(listview);
        }
        $('table.list-cashhistory>tbody').html(listarray.join());
    });
};

// 페이징 이벤트
var list_content = function(current_page){
    if(historyPageSet.listtype.val()==="deposit"){
        deposit_cash_list_content(current_page);
    }else if(historyPageSet.listtype.val()==="depositwait"){
        depositwait_cash_list_content(current_page);
    }else if(historyPageSet.listtype.val()==="withdraw"){
        withdraw_cash_list_content(current_page);
    }
};

// 셀렉트 체인지 이벤트
$('select[name="listtype"]').on('change', function(){
    listPageSet.current_page = 1;
    if(historyPageSet.listtype.val()==="deposit"){
        $('.history-title').html(langConvert('lang.deposits', ''));
        deposit_cash_page();
    }else if(historyPageSet.listtype.val()==="depositwait"){
        $('.history-title').html(langConvert('lang.depositsAwaiting', ''));
        depositwait_cash_page();
    }else if(historyPageSet.listtype.val()==="withdraw"){
        $('.history-title').html(langConvert('lang.withdrawals', ''));	
        withdraw_cash_page();
    }
});
/** cash history end **/


/** coin history start **/
// 입금내역 - 페이징
var deposit_page = function(){
    var t       = Utils.getTimeStamp();
    var param   = "?t-"+t;
    $.getJSON("/getwallet/depositlistsenv/po_type-"+walletPageSet.coin_code+"/"+param, "", function (data) {
    })
    .success(function(data) {
        for(i=0; i<data.length; i++){
            if(typeof(data[i].rowlimit)==="undefined"){

            }else{
                listPageSet2.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                listPageSet2.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                listPageSet2.total_page      = calfloat('CEIL', listPageSet2.totalcount/listPageSet2.rowlimit, 0);
                listPageSet2.total_block     = calfloat('CEIL', listPageSet2.total_page/listPageSet2.page_block, 0);
                listPageSet2.current_block   = calfloat('CEIL', (listPageSet2.current_page)/listPageSet2.page_block, 0);
                init_pager_second();
                deposit_list_content(listPageSet2.current_page);
            }
        };
    });
};


// 입금내역 - 테이블 헤더
var deposit_list_header = function(){
    var listheadarray = [];
    var listheadview = '';
    listheadview += '<tr>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersBtc', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersFees', '')+'</th>';
    listheadview += '<th >'+langConvert('lang.viewHistoryBtcOrdersTxid', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersDirectorTransactions', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersStatus', '')+'</th>';
    listheadview += '</tr>';
    listheadarray.push(listheadview);
    $('table.list-history>thead').html(listheadarray.join());
};



// 입금내역 - 데이터
var deposit_list_content = function(current_page){
    deposit_list_header();
    $('#confirm_guide').html('');
    var t       = Utils.getTimeStamp();
    var param   = "?t-"+t+"/&page="+current_page+"/";
    $.getJSON("/getwallet/depositlists/po_type-"+walletPageSet.coin_code+"/"+param, "json", function (data) {
    })
    .success(function(data) {
        var listarray   = [];
        var listview    = '';
        var list_confirm_status = '';
        if( typeof(data)!=='undefined'){
            if( parseInt(data[0].result) > 0){
                for( var i=0; i<data.length; i++ ){
                    var amount = parseFloat(data[i].od_amount+'');
                        amount = amount.toFixed(8);
                    var fee = parseFloat(data[i].od_fee+'');
                        fee = fee.toFixed(8);
                    if(parseInt(data[i].od_confirm)<1){
                        list_confirm_status = langConvert('lang.msgDepositWait', '');
                    }else{
                        list_confirm_status = langConvert('lang.msgDepositComplete', '');
                    }
                    listview =  '<tr>';
                    listview += '<td>'+data[i].od_reg_dt+'</td>';
                    listview += '<td>'+amount+'</td>';
                    listview += '<td>'+fee+'</td>';
                    listview += '<td>'+data[i].od_txid+'</td>';
                    if( (data[i].od_txid).indexOf('member') != -1 ){
                        listview += '<td>'+langConvert('lang.viewHistoryInnerMember', '')+'</td>';
                    }else{
                        listview += '<td><input type="button" class="btn btn-primary" onclick=\"goTransactionInfo(\''+data[i].od_txid +'\')\" value="'+langConvert('lang.viewHistoryBtcOrdersDirectorTransactions', '')+'" /></td>';
                    }
                    listview += '<td>'+list_confirm_status+'</td>';
                    listview += '</tr>';
                    listarray.push(listview);
                }
            }else{
                listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
                listarray.push(listview);
            }
        }else{
            listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
            listarray.push(listview);
        }
        $('table.list-history>tbody').html(listarray.join());
    });
};


// 입금대기내역 - 페이징
var depositwait_page = function(){
    var t       = Utils.getTimeStamp();
    var param   = "t-"+t+"/";
    $.getJSON("/getwallet/depositwaitlistsenv/po_type-"+walletPageSet.coin_code+"/"+param, "", function (data) {
    })
    .success(function(data) {
        for(i=0; i<data.length; i++){
            if(typeof(data[i].rowlimit)==="undefined"){

            }else{
                listPageSet2.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                listPageSet2.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                listPageSet2.total_page      = calfloat('CEIL', listPageSet2.totalcount/listPageSet2.rowlimit, 0);
                listPageSet2.total_block     = calfloat('CEIL', listPageSet2.total_page/listPageSet2.page_block, 0);
                listPageSet2.current_block   = calfloat('CEIL', (listPageSet2.current_page)/listPageSet2.page_block, 0);
                init_pager_second();
                depositwait_list_content(listPageSet2.current_page);
            }
        };
    });
};


// 입금대기내역 - 테이블 헤더
var depositwait_list_header = function(){
    var listheadarray = [];
    var listheadview = '';
    listheadview += '<tr>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersBtc', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersFees', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersConfirm', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersTxid', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersDirectorTransactions', '')+'</th>';
    listheadview += '</tr>';
    listheadarray.push(listheadview);
    $('table.list-history>thead').html(listheadarray.join());
};



// 입금대기내역 - 데이터
var depositwait_list_content = function(current_page){
    depositwait_list_header();
    //$('#confirm_guide').html(langConvert('lang.msgBtc3ConfirmOverYourDepositWillBeInitiatedUponCompletion', ''));
    var t       = Utils.getTimeStamp();
    var param   = "t-"+t+"/&page="+current_page+"/";
    $.getJSON("/getwallet/depositwaitlists/po_type-"+walletPageSet.coin_code+"/"+param, "json", function (data) {
    })
    .success(function(data) {
        var listarray   = [];
        var listview    = '';
        if( typeof(data)!=='undefined'){
            if( parseInt(data[0].result) > 0){
                for( var i=0; i<data.length; i++ ){
                    var amount = parseFloat(data[i].od_amount+'');
                        amount = amount.toFixed(8);
                    var fee = parseFloat(data[i].od_fee+'');
                        fee = fee.toFixed(8);
                    listview =  '<tr>';
                    listview += '<td>'+data[i].od_reg_dt+'</td>';
                    listview += '<td>'+amount+'</td>';
                    listview += '<td>'+fee+'</td>';
                    listview += '<td>'+(data[i].od_confirm).toString().formatNumber();+'</td>';
                    listview += '<td>'+data[i].od_txid+'</td>';
                    listview += '<td><input type="button" class="btn btn-primary" onclick=\"goTransactionInfo(\''+data[i].od_txid +'\')\" value="'+langConvert('lang.viewHistoryBtcOrdersDirectorTransactions', '')+'" /></td>';
                    listview += '</tr>';
                    listarray.push(listview);
                }
            }else{
                listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
                listarray.push(listview);
            }
        }else{
            listview = '<tr><th colspan="6" height="60"><center>'+langConvert('lang.msgThereisNoDepositHistory', '')+'</center></th></tr>';
            listarray.push(listview);
        }
        $('table.list-history>tbody').html(listarray.join());
    });
};



// 출금내역 - 페이징
var withdraw_page = function(){
    var t       = Utils.getTimeStamp();
    var param   = "t-"+t+"/";
    $.getJSON("/getwallet/withdrawallistsenv/po_type-"+walletPageSet.coin_code+"/"+param, "", function (data) {
    })
    .success(function(data) {
        for(i=0; i<data.length; i++){
            if(typeof(data[i].rowlimit)==="undefined"){

            }else{
                listPageSet2.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                listPageSet2.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                listPageSet2.total_page      = calfloat('CEIL', listPageSet2.totalcount/listPageSet2.rowlimit, 0);
                listPageSet2.total_block     = calfloat('CEIL', listPageSet2.total_page/listPageSet2.page_block, 0);
                listPageSet2.current_block   = calfloat('CEIL', (listPageSet2.current_page)/listPageSet2.page_block, 0);
                init_pager_second();
                list_content(listPageSet2.current_page);
            }
        };
    });
};


// 출금내역 - 테이블 헤더
var withdraw_list_header = function(){
    var listheadarray = [];
    var listheadview = '';
    listheadview += '<tr>';
    listheadview += '<th style="max-width:120px">'+langConvert('lang.viewHistoryBtcOrderWithdrawDate', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersBtcAmount', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersBtc1', '')+'</th>';
    listheadview += '<th width="90">'+langConvert('lang.viewHistoryBtcOrdersFees', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersTxid', '')+'</th>';
    listheadview += '<th>'+langConvert('lang.viewHistoryBtcOrdersDirectorTransactions', '')+'</th>';
    listheadview += '<th width="95">'+langConvert('lang.viewHistoryBtcOrdersStatus', '')+'</th>';
    listheadview += '</tr>';
    listheadarray.push(listheadview);
    $('table.list-history>thead').html(listheadarray.join());
};


var withdraw_list_content = function(current_page){
    withdraw_list_header();
    $('#confirm_guide').html('');
    var t       = Utils.getTimeStamp();
    var param = "t-"+t+"/&page="+current_page+"/";
    $.getJSON("/getwallet/withdrawallists/po_type-"+walletPageSet.coin_code+"/"+param, "json", function (data) {
    })
    .success(function(data) {
        var listarray   = [];
        var listview    = '';
        var list_od_status = '';
        if( typeof(data)!=='undefined'){
            if( parseInt(data[0].result) > 0){
                for( var i=0; i<data.length; i++ ){
                    listview = '<tr>';
                    var float_od_request_amount = parseFloat(data[i].od_temp_amount);
                        float_od_request_amount = calfloat('FLOOR', float_od_request_amount ,8);
                        float_od_request_amount = float_od_request_amount.toFixed(8);
                    var float_od_fee  = parseFloat(data[i].od_fee);
                        float_od_fee  = calfloat('FLOOR', float_od_fee ,8);
                        float_od_fee  = float_od_fee.toFixed(8);
                    var float_od_result_amount = float_od_request_amount - float_od_fee;
                        float_od_result_amount = float_od_result_amount.toFixed(8);

                    if(data[i].od_status=="WAIT"){
                        list_od_status = langConvert('lang.msgWithdrawAwaitingVerification', '');
                    }else if(data[i].od_status=="REQ"){
                        list_od_status = langConvert('lang.msgWithdrawRequestProgress', '');
                    }else if(data[i].od_status=="OK"){
                        list_od_status = langConvert('lang.msgWithdrawCompletePayment', '');
                    }else if(data[i].od_status=="HOLD"){
                        list_od_status = langConvert('lang.msgWithdrawlHold', '');
                    }else if(data[i].od_status=="CAN"){
                        list_od_status = langConvert('lang.msgWithdrawCancel', '');
                    }

                    listview += '<td style="max-width:120px">'+data[i].od_reg_dt+'</td>';
                    listview += '<td>'+float_od_request_amount+'</td>';
                    listview += '<td>'+float_od_result_amount+'</td>';
                    listview += '<td>'+float_od_fee+'</td>';
                    if(data[i].od_txid == 'member'){
                        listview += '<td>To address : '+data[i].od_addr+'</td>';
                        listview += '<td>'+langConvert('lang.viewHistoryInnerMember', '')+'</td>';
                    }else{
                        listview += '<td>'+data[i].od_txid+'</td>';
                        listview += '<td><input type="button" class="btn btn-primary" onclick=\"goTransactionInfo(\''+data[i].od_txid+'\')\" value="'+langConvert('lang.viewHistoryBtcOrdersDirectorTransactions', '')+'" /></td>';
                    }
                    listview += '<td class="hidden-xs">'+list_od_status+'</td>';
                    listview += '</tr>';
                    listarray.push(listview);
                }
            }else{
                listview = '<tr><th colspan="7" height="60"><center>'+langConvert('lang.msgThereAreNoRecentWithdrawalHistory', '')+'</center></th></tr>';
                listarray.push(listview);
            }
        }else{
            listview = '<tr><th colspan="7" height="60"><center>'+langConvert('lang.msgThereAreNoRecentWithdrawalHistory', '')+'</center></th></tr>';
            listarray.push(listview);
        }
        $('table.list-history>tbody').html(listarray.join());
    });
};


// 페이징 이벤트
var list_content = function(current_page){
    if(historyPageSet.coinlisttype.val()==="deposit"){
        deposit_list_content(current_page);
    }else if(historyPageSet.coinlisttype.val()==="depositwait"){
        depositwait_list_content(current_page);
    }else if(historyPageSet.coinlisttype.val()==="withdraw"){
        withdraw_list_content(current_page);
    }
};

// 셀렉트 체인지 이벤트
$('select[name="coinlisttype"]').change(function(){
    listPageSet2.current_page = 1;
    if(historyPageSet.coinlisttype.val()==="deposit"){
        $('.history-title').html(langConvert('lang.deposits', ''));
        deposit_page();
    }else if(historyPageSet.coinlisttype.val()==="depositwait"){
        $('.history-title').html(langConvert('lang.depositsAwaiting', ''));
        depositwait_page();
    }else if(historyPageSet.coinlisttype.val()==="withdraw"){
        $('.history-title').html(langConvert('lang.withdrawals', ''));
        withdraw_page();
    }
});
/** coin history end **/