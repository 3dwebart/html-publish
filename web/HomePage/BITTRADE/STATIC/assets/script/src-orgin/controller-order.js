var timerThread = null;
var count = 21;
var isDebug = true;
var chartData = 0;
var preChartData = 0;

if(typeof(jsonObject.channel)!=='undefined' && jsonObject.channel)
    var get_channel = $.parseJSON($.base64.decode(jsonObject.channel));

// 페이징 처리용 데이터셋
var listPageSet = {
    totalcount:0            // 전체 레코드 수
    ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
    ,current_page:1         // 현재 선택된 페이지 번호
    ,page_block:5           // 블록수 5

    ,total_page:null        // 전체 페이지 갯수
    ,total_block:null       // 전페 블록 갯수
    ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..) 
};

// ------------------------------------------------------------
// ------------------------------------------------------------
// 체결 / 미체결 내역
function initOrderCompleteListData() {
    Account.isLogined(function (islogin) {
        if (islogin) {
            var t = Utils.getTimeStamp();
            $.getJSON("/gettradeorder"+get_channel.currency+"/envliststradecomplete/t-" + t, "", function (data) {
            })
            .done(function (data) {
                var count = (data)?data.length:0;
                for (i = 0; i < count; i++) {
                    if (typeof (data[i].rowlimit) == 'undefined') {

                    } else {
                        listPageSet.totalcount = parseInt(data[0].totalcount); // 전체 레코드수
                        listPageSet.rowlimit = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                        listPageSet.total_page = calfloat('CEIL', listPageSet.totalcount / listPageSet.rowlimit, 0);
                        listPageSet.total_block = calfloat('CEIL', listPageSet.total_page / listPageSet.page_block, 0);
                        listPageSet.current_block = calfloat('CEIL', (listPageSet.current_page) / listPageSet.page_block, 0);
                        var compageTop = (($('#list-complet-page').length > 0) ? ($('#list-complet-page').offset().top) : false);
                        init_pager(getListMyTradeComplete, compageTop);
                    }
                };
                setTimeout("getListMyTradeComplete()", 200);
            });
        }
    });    
}

function initOrderUnCompleteListData() {
    Account.isLogined(function (islogin) {
        if (islogin) {
            // 미체결 주문 페이징 값 가져오기
            var t = Utils.getTimeStamp();
            $.getJSON("/gettradeorder"+get_channel.currency+"/envlistopenorder/t-" + t, "", function (data) {
            })
            .done(function (data) {
                var count = (data)?data.length:0;
                for (i = 0; i < count; i++) {
                    if (typeof (data[i].rowlimit) == 'undefined') {

                    } else {
                        listPageSet.totalcount = parseInt(data[0].totalcount); // 전체 레코드수
                        listPageSet.rowlimit = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                        listPageSet.total_page = calfloat('CEIL', listPageSet.totalcount / listPageSet.rowlimit, 0);
                        listPageSet.total_block = calfloat('CEIL', listPageSet.total_page / listPageSet.page_block, 0);
                        listPageSet.current_block = calfloat('CEIL', (listPageSet.current_page) / listPageSet.page_block, 0);
                        var uncompageTop = (($('#list-uncomplet-page').length > 0) ? ($('#list-uncomplet-page').offset().top) : false);
                        init_pager(getListOpenorder, uncompageTop);
                    }
                };
                setTimeout("getListOpenorder()", 100);
            });
        }
    });
}

/***********************
    최근 체결 내역
***********************/
function getListMyTradeComplete(page) {
    Account.isLogined(function (islogin) {
        if (islogin) {
            if (!page)
                page = 1;
            var t = Utils.getTimeStamp();
            var param = "t-" + t + "/&page=" + page + "/";
            $.getJSON("/gettradeorder"+get_channel.currency+"/liststradecomplete/" + param, "", function (comdata) {

            })
            .done(function (comdata) {
                list_write_com_content(comdata);
            });
        }
    });
}

var list_write_com_content = function (data) {
    // 최근 체결 내역

    var listarray = [];
    var listview = '';
    var val1 = '';
    var val3 = '';

    if (typeof (data) != 'undefined') {
        if (parseInt(data[0].result) > 0) {
            for (var i = 0; i < data.length; i++) {

                var float_total_cost = calfloat('FLOOR', data[i].od_total_cost, 0);
                var float_receipt_fee = parseFloat(data[i].od_receipt_fee);
                var float_receipt_avg = parseFloat(data[i].od_receipt_avg) * parseFloat(data[i].od_receipt_coin);
                    float_receipt_avg = (float_receipt_avg.toFixed(0)).formatNumber();

                if (data[i].od_action == "buy") {
                    val1 = '<td><span class="badge buy">' + langConvert('lang.commonBid', '') + '</span></td>';
                    float_receipt_fee = float_receipt_fee.toFixed(8);
                } else if (data[i].od_action == "sell") {
                    val1 = '<td><span class="badge sell">' + langConvert('lang.commonAsk', '') + '</span></td>';
                    float_receipt_fee = float_receipt_fee.toFixed(0);
                    float_receipt_fee = (float_receipt_fee + '').toString().formatNumber();
                }

                var od_reg_dt = data[i].od_reg_dt;
                od_reg_dt = od_reg_dt.substring(2, od_reg_dt.length);
                var split_od_reg_dt = od_reg_dt.split(" ");
                var reg_ymd = split_od_reg_dt[0];
                var reg_his = split_od_reg_dt[1];
                var od_receipt_dt = data[i].od_receipt_dt;
                od_receipt_dt = od_receipt_dt.substring(2, od_receipt_dt.length);

                listview = '<tr id="'+data[i].od_action+'_'+data[i].od_id+'">';
                listview += val1;
                listview += '<td>' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>'; // 주문시간
                listview += '<td>' + (data[i].od_market_price).formatWon() + '</td>'; // 주문가
                listview += '<td>' + (data[i].od_temp_coin).formatBitcoin() + '</td>'; // 주문량
                listview += '<td></td>'; // 체결시간
                //listview += '<td>' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>'; // 체결시간
                listview += '<td>' + float_receipt_fee + '</td>'; // 수수료
                listview += '<td>' + (data[i].od_receipt_coin).formatBitcoin() + '</td>'; // 체결량
                listview += '<td>' + (data[i].od_receipt_avg).formatWon() + '</td>'; // 체결가(평균)
                listview += '<td>' + float_receipt_avg + '</td>';
                listview += '<td onClick="getComList(\''+data[i].od_id+'\',\''+data[i].od_action+'\')" style="cursor:pointer"><i class=xi-plus-circle></i></td>';
                listview += '</tr>';
                listarray.push(listview);
            }
        } else {
            listview = '<tr><td colspan="10" height="60"><center>' + langConvert('lang.msgThereAreNoRecentTransactionHistory', '') + '</center></td></tr>';
            listarray.push(listview);
        }
    } else {
        listview = '<tr><td colspan="10" height="60"><center>' + langConvert('lang.msgThereAreNoRecentTransactionHistory', '') + '</center></td></tr>';
        listarray.push(listview);
    }
    $('table.list-tradecomplete>tbody').html(listarray.join());
};

/***********************
    미체결 내역
***********************/
function getListOpenorder(page) {
    console.log(123);

     Account.isLogined(function (islogin) {
        if (islogin) {
            if (!page)
                page = 1;
            var t = Utils.getTimeStamp();
            var param = "t-" + t + "/&page=" + page + "/";
            $.getJSON("/gettradeorder"+get_channel.currency+"/listsopenorder/" + param, "", function (uncomdata) {

            })
            .done(function (uncomdata) {
                list_write_uncom_content(uncomdata);
            });
        }
    });
}

var list_write_uncom_content = function (data) {

    var listarray = [];
    var listview = '';

    if (typeof (data) != 'undefined') {
        if (parseInt(data[0].result) > 0) {
            for (var i = 0; i < data.length; i++) {
                var eventBtn = '';
                var od_receipt_coin = 0;
                if(parseFloat(data[i].od_receipt_coin)>0.0){
                    od_receipt_coin = data[i].od_receipt_coin;
                }else{
                    od_receipt_coin = (0).toFixed(8);
                }
                var od_receipt_krw = data[i].od_receipt_avg * data[i].od_receipt_coin;
                    od_receipt_krw = (parseInt(od_receipt_krw)).toString().formatNumber();

                if ((data[i].od_pay_status == 'REQ') && data[i].od_receipt_coin==0.0) {
                    eventBtn  = '<span class="od_id_o_btnc_' + data[i].od_id + '"><button type="button" class="btn-cancle" id="od_id_' + data[i].od_id + '" onClick="userTradeOrderCancel(' + data[i].od_id + ',\'' + data[i].od_action + '\')">' + langConvert('lang.msgTradeOrdersCancel', '') + '</button></span>';
                }else if(parseFloat(data[i].od_receipt_coin)>0.0){
                    eventBtn  = '<span class="od_id_o_btnc_' + data[i].od_id + '"><button type="button" class="btn-cancle" id="od_id_' + data[i].od_id + '" onClick="userTradeOrderCancel(' + data[i].od_id + ',\'' + data[i].od_action + '\')">' + langConvert('lang.msgTradeOrdersCancel', '') + '</button></span>';
                }else{
                    eventBtn = langConvert('lang.commonTradeProgress', '') + ' <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>';
                }

                listview = '<tr>';
                if (data[i].od_action == 'buy') {
                    listview += '<td><span class="badge buy">' + langConvert('lang.commonBid', '') + '</span></td>';
                } else if (data[i].od_action == 'sell') {
                    listview += '<td><span class="badge sell">' + langConvert('lang.commonAsk', '') + '</span></td>';
                }

                var od_reg_dt = data[i].od_reg_dt;
                od_reg_dt = od_reg_dt.substring(2, od_reg_dt.length);
                   
                var split_po_reg_dt = od_reg_dt.split(" ");
                var reg_ymd = split_po_reg_dt[0];
                var reg_his = split_po_reg_dt[1];

                
                listview += '<td>' + reg_ymd + "<br class=only-m> " + reg_his + '</td>'; // 주문시간
                listview += '<td>' + (data[i].od_market_price).formatWon() + '</td>'; // 주문가
                listview += '<td>' + (data[i].od_temp_coin).formatBitcoin() + '</td>'; // 주문량
                listview += '<td>' + od_receipt_coin + '</td>'; // 체결량
                listview += '<td>' + (parseFloat(data[i].od_temp_coin) - parseFloat(data[i].od_receipt_coin)).formatBitcoin() + '</td>'; // 미체결량
                listview += '<td>' + (data[i].od_receipt_avg).formatWon() + '</td>'; // 체결가(평균)
                listview += '<td>' + od_receipt_krw + '</td>'; // 체결총액
                listview += '<td>' + eventBtn + '</td>';
                listview += '</tr>';
                listarray.push(listview);
            }
        } else {
            listview = '<tr><td colspan="9" height="60"><center>' + langConvert('lang.msgNoOrdersHaveAlreadyConcludedHistory', '') + '</center></td></tr>';
            listarray.push(listview);
        }
    } else {
        listview = '<tr><td colspan="9" height="60"><center>' + langConvert('lang.msgNoOrdersHaveAlreadyConcludedHistory', '') + '</center></td></tr>';
        listarray.push(listview);
    }
    $('table.list-tradenocomplete>tbody').html(listarray.join());
};

function getComList(od_id,od_action,list_type){
    
    if(typeof list_type === 'undefined'){
        list_type = "com";
    }else{
        list_type = "uncom";
    }

    if( $('.'+od_action+'_'+od_id+'_detail').length>0 ){
        var detail_display = $('.'+od_action+'_'+od_id+'_detail').css('display');
        if(detail_display == 'table-row'){
            $('.'+od_action+'_'+od_id+'_detail').css('display', 'none');
        }else{
            $('.'+od_action+'_'+od_id+'_detail').css('display', 'table-row');
        }
    }else{
        var ctype = get_channel.currency;
        var get_action = od_action+"_krw_"+ctype;
        var listview = '';
    
        $.getJSON("/gettradelist/select/odid-"+od_id+"/action-"+get_action+"/", "", function (data) {
        })
        .success(function(data) {
            if( typeof(data)!=='undefined'){
                if (parseInt(data[0].result) > 0) {
                    var k = 1;

                    for(var i = 0; i < data.length; i++){
                        var po_reg_dt = data[i].po_reg_dt;
                        po_reg_dt = po_reg_dt.substring(2, po_reg_dt.length);
                        
                        var split_po_reg_dt = po_reg_dt.split(" ");
                        var reg_ymd = split_po_reg_dt[0];
                        var reg_his = split_po_reg_dt[1];

                        var po_point = data[i].po_point;
                        po_point = Math.abs(po_point);
                        
                        var total_cost = parseFloat(po_point) / parseFloat(data[i].od_total_cost);

                        if(list_type == 'uncom'){
                            listview += '<tr class="more '+od_action+'_'+od_id+'_detail" style="display:table-row;">';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '<td>('+k+') ' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>';
                            listview += '<td></td>';
                            listview += '<td>' + data[i].od_total_cost + '</td>';
                            listview += '<td>' + calfloat('FLOOR', total_cost, 0).formatWon() + '</td>';
                            listview += '<td>' + parseInt(po_point).formatWon() + '</td>';
                            listview += '<td></td>';
                            listview += '</tr>';
                        }else{
                            listview += '<tr class="more '+od_action+'_'+od_id+'_detail" style="display:table-row;">';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '<td>('+k+') ' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>';
                            listview += '<td></td>';
                            listview += '<td>' + data[i].od_total_cost + '</td>';
                            listview += '<td>' + calfloat('FLOOR', total_cost, 0).formatWon() + '</td>';
                            listview += '<td>' + parseInt(po_point).formatWon() + '</td>';
                            listview += '<td></td>';
                            listview += '</tr>';
                        }
                        k++;
                    }
                }
            }else{
                //console.log('= undefined');
            }
            $('#'+od_action+'_'+od_id).after(listview);
        });
    }
}

var userTradeOrderCancel = function (od_id, od_action) {

    Account.isLogined(function (islogin) {
        if (islogin) {
            $('#od_id_' + od_id).attr("disabled", true);
            var bean = {
                result: 0,
                currency:get_channel.currency,
                link: {proc: "/gettradeorder"+get_channel.currency+"/cancel/"}
            };
            var order_type = "";

            if (od_action == "buy") {
                order_type = langConvert('lang.commonBid', '');
            } else if (od_action == "sell") {
                order_type = langConvert('lang.commonAsk', '');
            }

            controllerForm.setBeanData(bean);

            controllerForm.userTradeOrderCancel(od_id, function (json) {
                $('#tradeOrder').hide();
                if (json && json != "undefined") {
                    SendEvent.sendTradeRegist();
                    if (json.result > 0) {
                        controllerComm.alertError('<p>' + order_type + langConvert('lang.msgCancellationRequestHasBeenProcessed', '') + '</p><p>' + langConvert('lang.msgUsedThanks','') + '</p>', function () {
//                            initBalanceSum();
                            // getListOpenorder();
                            // getListMyTradeComplet();
                            
                            listPageSet.current_page = 1;
                            initListData();
                            initBalance();
                        });
                    } else {
                        controllerComm.alertError('<p>' + order_type + langConvert('lang.msgCancelTheRequestFailed', '') + '</p><p>' + json.error + '</p>', function () {
                            document.location.reload();
                        });
                    }
                } else {
                    controllerComm.alertError('<p>' + order_type + langConvert('lang.msgCancelTheRequestFailed', '') + '</p><p>' + langConvert('lang.msgUsedThanks','') + '</p>', function () {
                        document.location.reload();
                    });
                }
            });
        } else {
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                $(location).attr('href', "/account/signin");
            });
        }
    });
};

var onTickerMyTradeComEvent = function(data){
//    if(isDebug) console.log('=============onTickerMyTradeComEvent');
    
//    setTimeout("initBalanceSum()", 500);
    setTimeout("getListOpenorder()", 500);
    setTimeout("getListMyTradeComplete()", 1500);
};