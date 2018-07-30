var timerThread = null;
var count = 21;
var isDebug = true;
var chartData = 0;
var preChartData = 0;

if(typeof(jsonObject.channel)!=='undefined' && jsonObject.channel)
    var get_channel = $.parseJSON($.base64.decode(jsonObject.channel));

// 미체결내역 페이징 처리용 데이터셋
if(tmpUrl.substr(0,14) == '/trade/order_m') {
    var tmpPageBlock = 3;
} else {
    var tmpPageBlock = 5;
}
var listPageSet = {
    totalcount:0            // 전체 레코드 수
    ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
    ,current_page:1         // 현재 선택된 페이지 번호
    ,page_block:tmpPageBlock           // 블록수 5

    ,total_page:null        // 전체 페이지 갯수
    ,total_block:null       // 전페 블록 갯수
    ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..) 
};

// 최근체결내역 페이징 처리용 데이터셋
var listPageSet2 = {
    totalcount:0            // 전체 레코드 수
    ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
    ,current_page:1         // 현재 선택된 페이지 번호
    ,page_block:5           // 블록수 5

    ,total_page:null        // 전체 페이지 갯수
    ,total_block:null       // 전페 블록 갯수
    ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..) 
};

// 거래소 거래관련 데이터셋
var tradePageSet = {
    code:'buy',
    defaultSet:0,   // 시장가 삽입을 위한 변수
    float_market_price:0.0,
    float_amount:0.0,
    float_total:0,
    int_total:0,
    order_status:false,
    arrow_krw_status:false,
    arrow_coin_status:false,
    arrow_krw_sell_status:false,
    arrow_coin_sell_status:false,
    trade_info_fee:0.0,     // 수수료 안내
    trade_fee:0.0,          // 수수료
    trade_maker_fee:0.0,
    trade_tracker_fee:0.0,
    trade_receipt_btc:0.0,   // 총 구매 코인
    trade_krw_min_limit:0.0,
    trade_coin_min_limit:0,
    trade_call_unit_krw:0.0,
    trade_call_unit_coin:0,
};

// domset
var domObjSet = {
    defaultSet:0,   // 시장가 삽입을 위한 변수
    fromarea:$("#order-form-area"),
    only_coin:$("#order-form-area .only-coin"),

    buy_amount:$("#order-form-buy input[name='input_amount']"),
    buy_price:$("#order-form-buy input[name='input_price']"),
    buy_total:$("#order-form-buy input[name='input_total']"),
    buy_fee:$("#order-form-buy #trade_fee"),
    buy_receipt:$("#order-form-buy #trade_receipt"),

    sell_amount:$("#order-form-sell input[name='input_amount']"),
    sell_price:$("#order-form-sell input[name='input_price']"),
    sell_total:$("#order-form-sell input[name='input_total']"),
    sell_fee:$("#order-form-sell #trade_fee"),
    sell_receipt:$("#order-form-sell #trade_receipt"),

    btn_buy_submit:$("#btn_buy_submit"),
    btn_sell_submit:$("#btn_sell_submit"),

    token:$('#order-form-area input[name="token"]'),
};
  
var statusListVisible = {
    list_my_trade:false, // 최근 체결내역 hidden여부
    list_open_order:true,
};

/**********************
 * 판/구매 폼 영역 
 **********************/
var setInputCalculationBuySell = function(type) {
    var dom_price = domObjSet.buy_price;
    var dom_amount = domObjSet.buy_amount;
    if(type == 'buy'){
    }else if(type == 'sell'){
        dom_price = domObjSet.sell_price;
        dom_amount = domObjSet.sell_amount;
    }
    
    //dom_price.val(dom_price.val().replace(/[^0-9]/g, ""));
    if(dom_price.val()) {
        tradePageSet.float_market_price = calfloat('ROUND', dom_price.val().replace(/[^0-9]/g, ""), 8);
    } else {
        tradePageSet.float_market_price = 0;
    }

    if(dom_amount.val()) {
        tradePageSet.float_amount = calfloat('FLOOR', dom_amount.val(), 8);
    } else {
        tradePageSet.float_amount = 0;
    }
    // console.log(tradePageSet.float_market_price + ', a:' +tradePageSet.float_amount + ' ina:'+dom_amount.val());
};
var setTotalCalculationBuySell = function(type,focus){
    var dom_amount = domObjSet.buy_amount;
    var dom_total = domObjSet.buy_total;
    var dom_fee = domObjSet.buy_fee;
    var dom_receipt = domObjSet.buy_receipt;
    if(type == 'buy') {
    } else if(type == 'sell') {
        dom_amount = domObjSet.sell_amount;
        dom_total = domObjSet.sell_total;
        dom_fee = domObjSet.sell_fee;
        dom_receipt = domObjSet.sell_receipt;
    }
    //총 가격에 포커스 시
    if(focus == 'total') {
        dom_total.val(dom_total.val().replace(/[^0-9]/g, ""));
        if(dom_total.val()) tradePageSet.float_total = calfloat('FLOOR', dom_total.val(), 8);
        else tradePageSet.float_total = 0;
        if(Number(tradePageSet.float_total) > 0.00000001 && tradePageSet.float_market_price > 0 ) {
             //amount를 다시 계산
            tradePageSet.float_amount =  calfloat('ROUND', tradePageSet.float_total / tradePageSet.float_market_price, 8);
            dom_amount.val((tradePageSet.float_amount).toString().formatBitcoin());
        } else {
            tradePageSet.float_amount = 0;
            dom_amount.val("0.00000000");
        }
    //수량 혹은 가격에 포커스 시
    } else {
        if(Number(tradePageSet.float_market_price) > 0 && Number(tradePageSet.float_amount) > 0) {
            tradePageSet.float_total = calfloat('ROUND', tradePageSet.float_market_price * tradePageSet.float_amount);
            if(tradePageSet.float_total < 0.00000001) {
                tradePageSet.float_total = 0;
            }
            dom_total.val( tradePageSet.float_total.formatWon() );
            if(type == 'buy') {
                dom_fee.html( (domObjSet.buy_amount.val() * tradePageSet.trade_maker_fee / 100).formatBitcoin() );
                dom_receipt.html( '≈ ' + (domObjSet.buy_amount.val() - domObjSet.buy_amount.val() * tradePageSet.trade_maker_fee / 100).formatBitcoin() );
            } else if(type == 'sell') {
                dom_fee.html( (dom_total.val().replace(/[^0-9]/g, "") * tradePageSet.trade_maker_fee / 100).formatWon() );
                dom_receipt.html( '≈ ' + (tradePageSet.float_total - tradePageSet.float_total * tradePageSet.trade_maker_fee / 100).formatWon() );
            }
        } else {
            dom_total.val("");
            dom_fee.val("");
            dom_receipt.val("");
        }
    }
};

var fixedFloat = function (v) {
    var tmpTofix = Number(v);
    var tofix = Number(tmpTofix).toFixed(8);
    var ftofix = tofix;
    if(tofix.substr(tofix.length - 1) == 0) {
        ftofix = Number(tmpTofix).toFixed(7);
        if(tofix.substr(tofix.length - 2) == 0) {
            ftofix = Number(tmpTofix).toFixed(6);
            if(tofix.substr(tofix.length - 3) == 0) {
                ftofix = Number(tmpTofix).toFixed(5);
                if(tofix.substr(tofix.length - 4) == 0) {
                    ftofix = Number(tmpTofix).toFixed(4);
                    if(tofix.substr(tofix.length - 5) == 0) {
                        ftofix = Number(tmpTofix).toFixed(3);
                        if(tofix.substr(tofix.length - 6) == 0) {
                            ftofix = Number(tmpTofix).toFixed(2);
                            if(tofix.substr(tofix.length - 7) == 0) {
                                ftofix = Number(tmpTofix).toFixed(1);
                                //소수점 1자리
                                return ftofix;
                            }
                            //소수점 2자리
                            return ftofix;
                        }
                        //소수점 3자리
                        return ftofix;
                    }
                    //소수점 4자리
                    return ftofix;
                }
                //소수점 5자리
                return ftofix;
            }
            //소수점 6자리
            return ftofix;
        }
        //소수점 7자리
        return ftofix;
    }
    //소수점 8자리
    return ftofix;
};

var displayInputAmount = function() {
	
    // buy
    if(domObjSet.buy_price.is( ":focus" ) || domObjSet.buy_amount.is( ":focus" )) {
        tradePageSet.code = 'buy';
        setInputCalculationBuySell('buy');
        setTotalCalculationBuySell('buy');
    } else if(domObjSet.buy_total.is( ":focus" )) {
        tradePageSet.code = 'buy';
        setInputCalculationBuySell('buy');
        setTotalCalculationBuySell('buy','total');
    // sell
    } else if(domObjSet.sell_price.is( ":focus" ) || domObjSet.sell_amount.is( ":focus" )) {
        tradePageSet.code = 'sell';
        setInputCalculationBuySell('sell');
        setTotalCalculationBuySell('sell');
    }
	else if(domObjSet.sell_total.is( ":focus" )) {
        tradePageSet.code = 'sell';
        setInputCalculationBuySell('sell');
        setTotalCalculationBuySell('sell','total');
    // focusout
    } else {
	    if(tradePageSet.code == 'buy') {
            setInputCalculationBuySell('buy');
            //setTotalCalculationBuySell('buy');
        } else if(tradePageSet.code == 'sell') {
            setInputCalculationBuySell('sell');
            //setTotalCalculationBuySell('sell');
        }
		setTotalCalculationBuySell('buy');
		setTotalCalculationBuySell('sell');
	}
};
/***********************
    주문 
***********************/
var orderSubmit = function(thisobj) {
    Account.isLogined(function (islogin) {
        if (islogin) {
            thisobj.button('loading');

            displayInputAmount();
            
            var bean = {
                result: 0,
                link: {proc: "/gettradeorder"+get_channel.currency+"/regist/"}
            };
            controllerForm.setBeanData(bean);

            var ss_token = domObjSet.token.val();

            console.log("주문: " + tradePageSet.code);

            controllerForm.modeldata = {
                od_action: tradePageSet.code,
                od_pay_status: "REQ",
                od_market_price: tradePageSet.float_market_price,
                od_total_cost: tradePageSet.float_total, // 주문한 총 금액(BTC)
                od_temp_coin: tradePageSet.float_amount, //슈량 (코인별 amount)
                channel: get_channel.ch,
                currency: get_channel.currency,
                token: ss_token
            };
            /***********************
                주문 완료
            ***********************/
            controllerForm.setOnComplet=function(json) {
                if(json && json!="undefined" && json.result!="undefined") {
                    if(typeof (json.token)!='undeinfed'){
                        if( domObjSet.token.length > 0){
                            domObjSet.token.val(json.token);
                        }
                    }
                    
                    if(Number(json.result)>0) {
                        var currencystr = (get_channel.currency+'').toUpperCase();
                        var successmsg = '<p>'+langConvert('lang.msgxxBtcHasBeenCompletedPurchaseOrderRequest', [(tradePageSet.float_amount)+''+currencystr])+'</p><p>'+langConvert('lang.msgUsedThanks', '')+'</p>';
                        if(typeof json.complete !='undefined') {
                            if(json.complete=='OK') {
                                successmsg = '<p>'+langConvert('lang.msgxxBtcPurchaseRequestOrderHasBeenSigned', [(tradePageSet.float_amount)+''+currencystr])+'</p><p>'+langConvert('lang.msgUsedThanks', '')+'</p>';
                            } else if(json.complete=='PART') {
                                successmsg = '<p>'+langConvert('lang.msgxxBtcWasSignedThisPurchaseOrderRequestSection', [(tradePageSet.float_amount)+''+currencystr])+'</p><p>'+langConvert('lang.msgUsedThanks', '')+'</p>';
                            } else if(json.complete=='WAIT') {
                                successmsg = '<p>'+langConvert('lang.msgxxBtcIsOneOfOrdersSignedPurchaseRequestProcess', [(tradePageSet.float_amount)+''+currencystr])+'</p><p>'+langConvert('lang.msgTradeCompleteNotityBottom', '')+'<p/><p>'+langConvert('lang.msgUsedThanks', '')+'</p>';
                                //$('html ,body').animate( {scrollTop:$('#list-uncomplet-page').offset().top}, 300 );
                            }
                        }
                        listPageSet.current_page = 1;
                        initListData();
                        //initBalanceSum();
                        controllerComm.alertError(successmsg,
                            function() {
                                if(typeof json.complete =='undefined' || json.complete=='') {

                                }
                            }
                        );
                    } else {
                        var errormsg = (typeof json.error!='undefined')?json.error:langConvert('lang.msgExceptionsoccurred', '');
                        if(Number(json.result)==0) {
                            errormsg = langConvert('lang.msgTryAgainLater', '');
                        }

                        controllerComm.alertError('<p>'+errormsg+'</p>',
                            function() {
                                if(json.result==-900) {
                                    document.location.reload();
                                }
                            }
                        );
                    }
                } else {
                    controllerComm.alertError('<p>'+langConvert('lang.msgTradeFailedRequestOrders', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>',
                        function() {
                            document.location.reload();
                        }
                    );
                }
                // setTimeout(function(){  }, 3000);
                thisobj.button('reset');
            };
            
            //run form
            controllerForm.setInitForm("tradecoin");
        } else {
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                $(location).attr('href', "/account/signin");
            });
        }
    });
};

$(domObjSet.btn_buy_submit).click(function () {
	console.log("click 구매버튼");
    tradePageSet.code = 'buy';
    var thisobj = $(this);
    orderSubmit(thisobj);
});
$(domObjSet.btn_sell_submit).click(function () {
	console.log("click 판매버튼");
    tradePageSet.code = 'sell';
    var thisobj = $(this);
    orderSubmit(thisobj);
});
// ------------------------------------------------------------
// ------------------------------------------------------------
// 비트코인 판/구매 리스트
function initListData() {
    Account.isLogined(function (islogin) {
        if (islogin) {
            // 미체결 주문 페이징 값 가져오기
            var t = Utils.getTimeStamp();
            $.getJSON("/gettradeorder"+get_channel.currency+"/envlistopenordershort/t-" + t, "", function (data) {
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
            $.getJSON("/gettradeorder"+get_channel.currency+"/envliststradecompleteshort/t-" + t, "", function (data) {
            })
            .done(function (data) {
                var count = (data)?data.length:0;
                for (i = 0; i < count; i++) {
                    if (typeof (data[i].rowlimit) == 'undefined') {

                    } else {
                        listPageSet2.totalcount = parseInt(data[0].totalcount); // 전체 레코드수
                        listPageSet2.rowlimit = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                        listPageSet2.total_page = calfloat('CEIL', listPageSet2.totalcount / listPageSet2.rowlimit, 0);
                        listPageSet2.total_block = calfloat('CEIL', listPageSet2.total_page / listPageSet2.page_block, 0);
                        listPageSet2.current_block = calfloat('CEIL', (listPageSet2.current_page) / listPageSet2.page_block, 0);
                        var compageTop = (($('#list-complet-page').length > 0) ? ($('#list-complet-page').offset().top) : false);
                        init_pager_second(getListMyTradeComplet, compageTop);
                    }
                };
                setTimeout("getListMyTradeComplet()", 200);
            });
        }
    });    
}

//미체결 데이터
function getListOpenorder(page) {

     Account.isLogined(function (islogin) {
        if (islogin) {
            if (!page)
                page = 1;
            var t = Utils.getTimeStamp();
            var param = "t-" + t + "/&page=" + page + "/";
            $.getJSON("/gettradeorder"+get_channel.currency+"/listsopenordershort/" + param, "", function (uncomdata) {

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
                    od_receipt_coin = 0;
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

                
                listview += '<td>' + reg_ymd + "<br class=only-m> " + reg_his + '</td>';
                listview += '<td><input type="hidden" maxlength="17" readonly="true" id="od_id_m_price_' + data[i].od_id+'" name="order_price" value="'+(data[i].od_market_price).toString()+'" data-price="'+(data[i].od_market_price).toString().formatNumber()+'"><span>' + (data[i].od_market_price).toString().formatNumber() + '</span></td>';
                listview += '<td><input type="text" style="display:none;width:120px;" id="od_id_m_btc_' + data[i].od_id+'" class="inp input_modify input-modify-btc od_id_m_btc_' + data[i].od_id+'" name="order_btc" maxlength="17" value="'+parseFloat(data[i].od_temp_coin)+'" data-amount="'+parseFloat(data[i].od_temp_coin)+'"><span class="od_id_btc_' + data[i].od_id+'">' + data[i].od_temp_coin + '</span></td>';
                listview += '<td>' + od_receipt_coin + '</td>';
                listview += '<td>'+(parseInt(data[i].od_receipt_avg)+'').toString().formatNumber()+''+'</td>';
                listview += '<td>'+od_receipt_krw+'</td>';
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

/***********************
    최근 체결 내역
***********************/
function getListMyTradeComplet(page) {
    Account.isLogined(function (islogin) {
        if (islogin) {
            if (!page)
                page = 1;
            var t = Utils.getTimeStamp();
            var param = "t-" + t + "/&page=" + page + "/";
            $.getJSON("/gettradeorder"+get_channel.currency+"/liststradecompleteshort/" + param, "", function (comdata) {

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
                var odTempCoinFixedFloat = fixedFloat(data[i].od_temp_coin);
                var odTempCoinFixedFloatArr = odTempCoinFixedFloat.split('.');
                float_receipt_fee = fixedFloat(float_receipt_fee);
                float_receipt_feeArr = float_receipt_fee.split('.');

                listview = '<tr id="'+data[i].od_action+'_'+data[i].od_id+'">';
                listview += val1;
                listview += '<td>' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>'; // 주문시간
                //listview += '<td>' + (parseInt(data[i].od_market_price)+'').toString().formatNumber()+ '</td>';
                //listview += '<td>' + (data[i].od_temp_coin) + '</td>'; //체결량
                listview += '<td><div class="float-num full"><span class="int">' + odTempCoinFixedFloatArr[0] + '</span><span class="double">' + odTempCoinFixedFloatArr[1] + '</span></div></td>'; //체결량
                //listview += '<td>' + od_receipt_dt + '</td>';
                //listview += '<td>' + float_receipt_fee + '</td>';
                listview += '<td>' + (parseInt(data[i].od_receipt_avg)+'').toString().formatNumber() + '</td>';
                listview += '<td>' + float_receipt_avg + '</td>';
                listview += '<td><div class="float-num full"><span class="int">' + float_receipt_feeArr[0] + '</span><span class="double">' + float_receipt_feeArr[1] + '</span></div></td>'; // 수수료
                listview += '<td onClick="getComList(\''+data[i].od_id+'\',\''+data[i].od_action+'\')" style="cursor:pointer"><i class=xi-plus-circle></i></td>';
                listview += '</tr>';
                listarray.push(listview);
            }
        } else {
            listview = '<tr><td colspan="9" height="60"><center>' + langConvert('lang.msgThereAreNoRecentTransactionHistory', '') + '</center></td></tr>';
            listarray.push(listview);
        }
    } else {
        listview = '<tr><td colspan="9" height="60"><center>' + langConvert('lang.msgThereAreNoRecentTransactionHistory', '') + '</center></td></tr>';
        listarray.push(listview);
    }
    $('table.list-tradecomplete>tbody').html(listarray.join());

};

function getComList(od_id,od_action,list_type) {
    
    if(typeof list_type === 'undefined') {
        list_type = "com";
    } else {
        list_type = "uncom";
    }

    if( $('.'+od_action+'_'+od_id+'_detail').length>0 ) {
        var detail_display = $('.'+od_action+'_'+od_id+'_detail').css('display');
        if(detail_display == 'table-row') {
            $('.'+od_action+'_'+od_id+'_detail').css('display', 'none');
        } else {
            $('.'+od_action+'_'+od_id+'_detail').css('display', 'table-row');
        }
    } else {
        var ctype = get_channel.currency;
        var get_action = od_action+"_krw_"+ctype;
        var listview = '';
    
        $.getJSON("/gettradelist/select/odid-"+od_id+"/action-"+get_action+"/", "", function (data) {
        })
        .success(function(data) {
            if( typeof(data)!=='undefined') {
                if (parseInt(data[0].result) > 0) {
                    var k = 1;

                    for(var i = 0; i < data.length; i++) {
                        var po_reg_dt = data[i].po_reg_dt;
                        po_reg_dt = po_reg_dt.substring(2, po_reg_dt.length);
                        
                        var split_po_reg_dt = po_reg_dt.split(" ");
                        var reg_ymd = split_po_reg_dt[0];
                        var reg_his = split_po_reg_dt[1];

                        var po_point = data[i].po_point;
                        po_point = Math.abs(po_point);
                        
                        var total_cost = parseFloat(po_point) / parseFloat(data[i].od_total_cost);
                        var odTotalCostFixedFloat = fixedFloat(data[i].od_total_cost);
                        var odTotalCostFixedFloatArr = odTotalCostFixedFloat.split('.');

                        if(list_type == 'uncom') {
                            listview += '<tr class="more '+od_action+'_'+od_id+'_detail" style="display:table-row;">';
                            listview += '<td></td>'; 
                            listview += '<td>('+k+')' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>';
                            //listview += '<td>' + data[i].od_total_cost + '</td>';
                            listview += '<td><div class="float-num full"><span class="int">' + odTotalCostFixedFloatArr[0] + '</span><span class="double">' + odTotalCostFixedFloatArr[1] + '</span></div></td>';
                            listview += '<td>' + calfloat('FLOOR', total_cost, 0).formatWon() + '</td>';
                            listview += '<td>' + parseInt(po_point).formatWon() + '</td>';
                            listview += '<td></td>';
                            listview += '</tr>';
                        } else {
                            listview += '<tr class="more '+od_action+'_'+od_id+'_detail" style="display:table-row;">';
                            listview += '<td></td>';
                            listview += '<td>('+k+')' + reg_ymd + "<br class=only-m> "+ reg_his + '</td>';
                            listview += '<td><div class="float-num full"><span class="int">' + odTotalCostFixedFloatArr[0] + '</span><span class="double">' + odTotalCostFixedFloatArr[1] + '</span></div></td>';
                            listview += '<td>' + calfloat('FLOOR', total_cost, 0).formatWon() + '</td>';
                            listview += '<td>' + parseInt(po_point).formatWon() + '</td>';
                            listview += '<td></td>';
                            listview += '<td></td>';
                            listview += '</tr>';
                        }
                        k++;
                    }
                }
            } else {
                //console.log('= undefined');
            }
            $('#'+od_action+'_'+od_id).after(listview);
        });
    }
}
// 비트코인 판/구매 리스트
// ------------------------------------------------------------
// ------------------------------------------------------------
// 비트코인 판/구매 submit action
if (typeof(tradePageSet) != 'undefined' && typeof(tradePageSet.dom_btn_order) != 'undefined' && tradePageSet.dom_btn_order.length > 0) {
    tradePageSet.dom_btn_order.click(function () {
        // 로그인 세션 주문서부터 return
        Account.isLogined(function (islogin) {
            if (islogin) {
                tradePageSet.order_status = true;
                clearInterval(timerThread);

                // 로그인 세션 체크 삽입시
                // tradeOrderCheck -> hide 이벤트가 먹힌다.
                if (tradePageSet.code == 'buy') {
                    $('#buyBtcModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else if (tradePageSet.code == 'sell') {
                    $('#sellBtcModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                count = 21;
                $('.show_remain_time').html(langConvert('lang.msgOrderPlacing', '') + ' ' + langConvert('lang.msgPleaseWait', ''));
                $('#btn_submit').attr('disabled', true);

                $('.show_krw').html(calfloat('FLOOR', tradePageSet.float_total, 0).toString().formatWon());
                $('.show_btc').html(calfloat('FLOOR', tradePageSet.float_btc, 8).toString().formatBitcoin());
                $('.show_price').html(calfloat('FLOOR', tradePageSet.float_price, 0).toString().formatWon());
                if (tradePageSet.code == 'buy') {
                    $('.show_fee').html((tradePageSet.trade_fee).toFixed(8));
                } else if (tradePageSet.code == 'sell') {
                    $('.show_fee').html((calfloat('FLOOR', tradePageSet.trade_fee, 0) + '').toString().formatWon());
                }

                $('.show_timer').html();

                timerThread = setInterval(function () {
                    $('#btn_submit').attr('disabled', false);
                    if (tradePageSet.order_status == true) {
                        tradePageSet.dom_btn_order.attr("disabled", true);
                    }
                    count--;
                    $('.show_remain_time').html(langConvert('lang.commonRemainTime', '') + " : " + count + langConvert('lang.commonDateSecond', ''));
                    if (count <= 0) {
                        clearInterval(timerThread);
                        $('.show_remain_time').html(langConvert('lang.msgOrderExpiredPleaseOrderAgain', ''));
                        $("#btn_submit").attr("disabled", true);
                    }
                }, 1000);
            } else {
                controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                    $(location).attr('href', "/account/signin");
                });
            }
        });
    });
};

if (typeof(tradePageSet) != 'undefined' && typeof(tradePageSet.dom_btn_order_sell) != 'undefined' && tradePageSet.dom_btn_order_sell.length > 0) {
    tradePageSet.dom_btn_order_sell.click(function () {
        // 로그인 세션 주문서부터 return
        Account.isLogined(function (islogin) {
            if (islogin) {
                tradePageSet.order_status = true;
                tradePageSet.code = 'sell';
                clearInterval(timerThread);

                // 로그인 세션 체크 삽입시
                // tradeOrderCheck -> hide 이벤트가 먹힌다.
                if (tradePageSet.code == 'buy') {
                    $('#buyBtcModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else if (tradePageSet.code == 'sell') {
                    $('#sellBtcModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                count = 21;
                $('.show_remain_time').html(langConvert('lang.msgOrderPlacing', '') + ' ' + langConvert('lang.msgPleaseWait', ''));
                $('#btn_submit').attr('disabled', true);

                $('.show_krw').html(calfloat('FLOOR', tradePageSet.float_total, 0).toString().formatWon());
                $('.show_btc').html(calfloat('FLOOR', tradePageSet.float_btc, 8).toString().formatBitcoin());
                $('.show_price').html(calfloat('FLOOR', tradePageSet.float_price, 0).toString().formatWon());
                if (tradePageSet.code == 'buy') {
                    $('.show_fee').html((tradePageSet.trade_fee).toFixed(8));
                } else if (tradePageSet.code == 'sell') {
                    $('.show_fee').html((calfloat('FLOOR', tradePageSet.trade_fee, 0) + '').toString().formatWon());
                }

                $('.show_timer').html();

                timerThread = setInterval(function () {
                    $('#btn_submit').attr('disabled', false);
                    if (tradePageSet.order_status == true) {
                        tradePageSet.dom_btn_order.attr("disabled", true);
                    }
                    count--;
                    $('.show_remain_time').html(langConvert('lang.commonRemainTime', '') + " : " + count + langConvert('lang.commonDateSecond', ''));
                    if (count <= 0) {
                        clearInterval(timerThread);
                        $('.show_remain_time').html(langConvert('lang.msgOrderExpiredPleaseOrderAgain', ''));
                        $("#btn_submit").attr("disabled", true);
                    }
                }, 1000);
            } else {
                controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                    $(location).attr('href', "/account/signin");
                });
            }
        });
    });
};
// 비트코인, 달러 판/구매 주문서 취소 btn action
$("#btn_submit-cancel").click(function () {
    setTimeout(function () {
        clearInterval(timerThread);
    }, 0);
}
);

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
//-------------------------------------event-----------------------------------------//
// 비트코인, 달러 판구매, 비트코인 원화 출금 - 사용자 입력시 제한소수점
$(".input-calculation").on("input keypress keyup change", $(this), function () {
    var regex = /^(\d*\.?\d{0,8})$|^\$?[\d,]+(\d*)?$/g;

    // 비트코인 판매, 구매, 비트코인 출금
    var input = $(this).val();
    if (regex.test(input)) {
        var matches = input.match(regex);
        for (var match in matches) {
//            console.log(matches[match]);
            displayInputAmount();
        }
    } else if (isNaN($(this).val())) {
        $(this).val(input.slice(0, -1));
//        console.log("isNaN!");
        return false;
    } else {
        $(this).val(input.slice(0, -1));
//        console.log("No matches found!");
        return false;
    }
});

// 구매 최대버튼
$("#order-form-buy #btn_max").click(function () {
    var krw_poss = (domObjSet.fromarea.find('.mb_krw_poss').text()).toInteger();
    if(krw_poss > 0){
        if(domObjSet.buy_price.val().replace(/[^0-9]/g, "") > 0){
            var buyAmount = (krw_poss / domObjSet.buy_price.val().replace(/[^0-9]/g, "")).formatBitcoin();
            domObjSet.buy_amount.val(buyAmount);
            domObjSet.buy_total.val(krw_poss);
            domObjSet.buy_amount.focus();
            displayInputAmount();
        }
    }
});

// 판매 최대버튼
$("#order-form-sell #btn_max").click(function () {
    var coin_poss = (domObjSet.fromarea.find('.mb_'+get_channel.currency+'_poss').text()).formatBitcoin();
    if(coin_poss > 0.00000000){
        domObjSet.sell_amount.val(coin_poss);
        domObjSet.sell_amount.focus();
    }
    displayInputAmount();
});

$(document).on('click', '.bs-dropdown-to-select-group #list_krw_price li',
    function (event) {
        var $target = $(event.currentTarget);
        $target.closest('.bs-dropdown-to-select-group')
                .find('[data-bind="bs-sel-value"]').val($target.attr('data-value'))
                .end()
                .children('.dropdown-toggle').dropdown('toggle');
        tradePageSet.float_price = parseFloat($target.attr('data-value'));
        displayInputAmount();
        return false;
    }
);

$(document).on('click', '.bs-dropdown-to-select-group #list_btc_price li',
    function (event) {
        var $target = $(event.currentTarget);
        $target.closest('.bs-dropdown-to-select-group')
                .find('[data-bind="bs-sel-value"]').val($target.attr('data-value'))
                .end()
                .children('.dropdown-toggle').dropdown('toggle');
        tradePageSet.float_btc = parseFloat($target.attr('data-value'));
        displayInputAmount();
        return false;
    }
);

$('#toggle_krw').click(function () {
    setTimeout(function () {
        $('ul.list-trademarketcostvalue').scrollTop(250);
    }, 1);
});

$('#arrow_up_coin').click(function () {
    tradePageSet.code = 'buy';
    tradePageSet.arrow_coin_status = true;
    domObjSet.buy_amount.val((parseFloat(domObjSet.buy_amount.val()) + parseFloat(tradePageSet.trade_call_unit_coin)).toFixed(8));
    displayInputAmount();
    tradePageSet.arrow_coin_status = false;
});

$('#arrow_down_coin').click(function () {
    tradePageSet.code = 'buy';
    tradePageSet.arrow_coin_status = true;
    if (parseFloat(domObjSet.buy_amount.val()) - parseFloat(tradePageSet.trade_call_unit_coin) <= 0.0) {
        domObjSet.buy_amount.val((0).toFixed(8));
    } else {
        domObjSet.buy_amount.val((parseFloat(domObjSet.buy_amount.val()) - parseFloat(tradePageSet.trade_call_unit_coin)).toFixed(8));
    }
    displayInputAmount();
    tradePageSet.arrow_coin_status = false;
});

$('#arrow_up_krw').click(function () {
    tradePageSet.code = 'buy';
    tradePageSet.arrow_krw_status = true;
    domObjSet.buy_price.val( (parseInt(domObjSet.buy_price.val().replace(/[^0-9]/g, "")) + parseInt(tradePageSet.trade_call_unit_krw)).formatWon() );
    displayInputAmount();
    tradePageSet.arrow_krw_status = false;
});

$('#arrow_down_krw').click(function () {
    tradePageSet.code = 'buy';
    tradePageSet.arrow_krw_status = true;
    if (parseInt(domObjSet.buy_price.val().replace(/[^0-9]/g, "")) - parseInt(tradePageSet.trade_call_unit_krw) <= 0) {
        domObjSet.buy_price.val(0);
    } else {
        domObjSet.buy_price.val( (parseInt(domObjSet.buy_price.val().replace(/[^0-9]/g, "")) - parseInt(tradePageSet.trade_call_unit_krw)).formatWon() );
    }
    displayInputAmount();
    tradePageSet.arrow_krw_status = false;
});
    
$('#arrow_up_coin_sell').click(function () {
    tradePageSet.code = 'sell';
    tradePageSet.arrow_coin_sell_status = true;
    domObjSet.sell_amount.val((parseFloat(domObjSet.sell_amount.val()) + parseFloat(tradePageSet.trade_call_unit_coin)).toFixed(8));
    displayInputAmount();
    tradePageSet.arrow_coin_sell_status = false;
});

$('#arrow_down_coin_sell').click(function () {
    tradePageSet.code = 'sell';
    tradePageSet.arrow_coin_sell_status = true;
    if (parseFloat(domObjSet.sell_amount.val()) - parseFloat(tradePageSet.trade_call_unit_coin) <= 0.0) {
        domObjSet.sell_amount.val((0).toFixed(8));
    } else {
        domObjSet.sell_amount.val((parseFloat(domObjSet.sell_amount.val()) - parseFloat(tradePageSet.trade_call_unit_coin)).toFixed(8));
    }
    displayInputAmount();
    tradePageSet.arrow_coin_sell_status = false;
});

$('#arrow_up_krw_sell').click(function () {
    tradePageSet.code = 'sell';
    tradePageSet.arrow_krw_sell_status = true;
    domObjSet.sell_price.val( (parseInt(domObjSet.sell_price.val().replace(/[^0-9]/g, "")) + parseInt(tradePageSet.trade_call_unit_krw)).formatWon() );
    displayInputAmount();
    tradePageSet.arrow_krw_sell_status = false;
});

$('#arrow_down_krw_sell').click(function () {
    tradePageSet.code = 'sell';
    tradePageSet.arrow_krw_sell_status = true;
    if (parseInt(domObjSet.sell_price.val().replace(/[^0-9]/g, "")) - parseInt(tradePageSet.trade_call_unit_krw) <= 0) {
        domObjSet.sell_price.val(0);
    } else {
        domObjSet.sell_price.val( (parseInt(domObjSet.sell_price.val().replace(/[^0-9]/g, "")) - parseInt(tradePageSet.trade_call_unit_krw)).formatWon() );
    }
    displayInputAmount();
    tradePageSet.arrow_krw_sell_status = false;
});

$('.input-rounding-krw').focusout(
    function () {
        if(tradePageSet.code == 'buy') {
            domObjSet.buy_price.val( (domObjSet.buy_price.val()).formatWon() );
        } else if(tradePageSet.code == 'sell') {
            domObjSet.sell_price.val( (domObjSet.sell_price.val()).formatWon() );
        }
    }
);

$('.only-coin').focusout(
    function () {
        if(tradePageSet.code == 'buy'){
            if(domObjSet.buy_amount.val() > 0.00000000) {
                domObjSet.buy_amount.val( (domObjSet.buy_amount.val()).formatBitcoin() );
            } else {
                domObjSet.buy_amount.val( '0.00000000' );
            }
        } else if(tradePageSet.code == 'sell') {
            if(domObjSet.sell_amount.val() > 0.00000000) {
                domObjSet.sell_amount.val( (domObjSet.sell_amount.val()).formatBitcoin() );
            } else {
                domObjSet.sell_amount.val( '0.00000000' );
            }
        }
    }
);
/**************************************
 * market
 *************************************/
var preTradeSellMarketCostListArray    = []; // 이전 데이터비교 용
var preTradeBuyMarketCostListArray     = []; // 이전 데이터비교 용
var cfg_chart_vmin_cost,cfg_chart_vmax_cost;
// 거래 판/구매 요청 데이터 변경시 effect
var clearBuySellAnimationClass = function (domid) {
    setTimeout(function() {
        $('#sellbuy-orderbooks #'+domid).removeClass("animated");
//        sellbuy_orderbooks_animated.removeClass("animated");
    }, 3000);
};
var clearBuySellAnimationClassDomObj = function (dom) {
    setTimeout(function() {
        dom.removeClass("animated");
//        sellbuy_orderbooks_animated.removeClass("animated");
    }, 3000);
};

var clearTradeAnimationClass = function (domid) {
    setTimeout(function() {
       $('#market-trade-his #'+domid).removeClass("animated");
//        domObjSet.list_table_tradehis_animated.removeClass("animated");
    }, 3000);
};
/*******************************
 * 판 / 구매 시장현황
 ******************************/
//html을 미리 생성한다.
var domTradeBuyMarket = []; //속도를 위해 저장
var domTradeSellMarket = []; //속도를 위해 저장
var initTradeBuySellMarketDom = function(type) {
    var loopcnt = 10;
    var html = '';
    for( var i=0; i<loopcnt; i++ ) {
        var domname = 'order_list_'+type + i;
        if(type == 'buy') {
            domTradeBuyMarket[i] = $('#'+domname);
        } else if(type == 'sell') {
            domTradeSellMarket[i] = $('#'+domname);
        }
    }
    
};
initTradeBuySellMarketDom('buy');
initTradeBuySellMarketDom('sell');

// 판/구매 시장현황 ----- 동적
var tradeBuySellMarketCostList = function(type,json) {
    
    //buy/sell , 1:od_market_price, 2:amount, sum
    //1:od_market_price, 2:amount, sum
    
    var domobj = domTradeBuyMarket;
    if(type == 'sell') {
        domobj = domTradeSellMarket;
    }
    var compare_result      = 0;
    var data;
    try {
       data = $.parseJSON(json);
    } catch(e){}

    var loopcnt = 10;
    var tmpTradeBuySellArray = [];

    for( var i=0; i<loopcnt; i++ ) {
        if(!data || !data.hasOwnProperty(i)) {
            domobj[i].find('.p').html('');
            domobj[i].find('.a').html('');
            domobj[i].find('.s').html('');
            continue;
        }
        
        compare_data = data[i][0] + "-" + data[i][1];
        if(type == 'buy') {
            compare_result = preTradeBuyMarketCostListArray.indexOf(compare_data);
            if(preTradeBuyMarketCostListArray.length == 0) compare_result = 0;
        } else if(type == 'sell') {
            compare_result = preTradeSellMarketCostListArray.indexOf(compare_data);
            if(preTradeSellMarketCostListArray.length == 0) compare_result = 0;
        }
        
        if(compare_result === -1) {
            domobj[i].removeClass('animated').addClass('animated');
            clearBuySellAnimationClassDomObj(domobj[i]);
        }
        domobj[i].find('.p').html((data[i][0]+"").formatBitcoin());
        if(type == 'sell') {
            domobj[i].find('.p').removeClass('buy');
            domobj[i].find('.p').addClass('sell');
        } else {
            domobj[i].find('.p').removeClass('sell');
            domobj[i].find('.p').addClass('buy');
        }
        var totalbtc = Number(data[i][0]) * Number(data[i][1]);
        domobj[i].find('.a').html((data[i][1]+"").formatBitcoin());
        domobj[i].find('.s').html((totalbtc+"").formatBitcoin());
        tmpTradeBuySellArray.push(compare_data);
    }

    if(type == 'buy') {
        preTradeBuyMarketCostListArray = tmpTradeBuySellArray;
    } else if(type == 'sell') {
        preTradeSellMarketCostListArray = tmpTradeBuySellArray;
    }
};

function onWriteChangeMyBalance(balance) {
    if(typeof tradePageSet === 'undefined' ||
            !balance ||
            !Array.isArray(balance)) {
        return;
    }
}

var isFirstLoad = true;

function onTickerEvent(data) {
//    if(isDebug) console.log('=============onTickerEvent :' + JSON.stringify(data));
}

var onTickerMyTradeComEvent = function(data) {
//    if(isDebug) console.log('=============onTickerMyTradeComEvent');
    
//    setTimeout("initBalanceSum()", 500);
    setTimeout("getListOpenorder()", 500);
    setTimeout("getListMyTradeComplet()", 1500);
};

var onTickerTradeEvent = function(buy,sell) {
    
//    if(isDebug) console.log('=============onTickerTradeEvent');
    //tradeBuySellMarketCostList('buy',buy);
    var buyData;
    var sellData;
    
    if(buy=='{}') {
        buyData = [[0,0.0,0,'buy']];
    } else {
        buyData = $.parseJSON(buy);
        if(buyData != null) {
            for(var i=0; i<buyData.length; i++){
                buyData[i].push("buy");
            }
        } else {
            buyData = [[0,0.0,0,'buy']];
        }
    }
    
    if(sell=='{}') {
        sellData = [[0,0.0,0,'sell']];
    } else {
        sellData = $.parseJSON(sell);
        if(sellData != null) {
            for(var i=0; i<sellData.length; i++) {
                sellData[i].push("sell");
            }
        } else {
            sellData = [[0,0.0,0,'sell']];
        }
    }
    
    var tradeData = sellData.reverse().concat(buyData);
    var jsonData = JSON.stringify(tradeData);

    tradeMarketCostList(jsonData);
    
    if(isFirstLoad){
        setOnceInitForm(buy,sell);
        isFirstLoad = false;
        setOnceInitForm = null;
    }
};
//시장현황 market deps
var onTickerMarketDepsEvent = function(data){
//    if(isDebug) console.log('=============onTickerMarketDepsEvent:'+data);
    /*var json;
    try{
        json = $.parseJSON(data);
    }catch(e){}
    if(json && Array.isArray(json) && json[0] && json[0].length==3){
        if(json[0][0] == 'buy'){
            domObjSet.buy_order_sum.html((json[0][2]+'').formatBitcoin() );
        }
        var lastcnt = json.length - 1;
        
        if(json[lastcnt][0] == 'sell'){
            domObjSet.sell_order_sum.html( (json[lastcnt][2]+'').formatBitcoin()  ) ;
        }
    }*/
    // kim
    if(typeof onChartDrawMarketDeps === 'function') {
        onChartDrawMarketDeps(data);
    }
};

//체결완료
var onTickerTradeComEvent = function(data) {
    if(typeof onTradeList === 'function') {
        onTradeList(data);
    }
    if(typeof onRealTradeList === 'function') {
        onRealTradeList(data);
    }
//    tradeCompleteList(data);
    
//    // kim
//    if(typeof onChartDataChange === 'function'){
//        onChartDataChange(data);
//    }

};

var setOnceInitForm = function(strbuy,strsell) {
    var buy = [], sell = [];
    try {
        buy = $.parseJSON(strbuy);
        sell = $.parseJSON(strsell);
    } catch(e) { }
    
    if(buy == true) {
        buy.reverse();
    }

    domObjSet.buy_price.removeAttr('readonly', false); // input_btc
    domObjSet.buy_amount.removeAttr('readonly', false); // input_price

    domObjSet.sell_price.removeAttr('readonly', false); // input_btc
    domObjSet.sell_amount.removeAttr('readonly', false); // input_price

    if(buy && Array.isArray(buy) && buy.hasOwnProperty(0) && buy[0].length>1) {
        domObjSet.sell_price.val((parseInt(buy[0][0])).formatWon());
    } else {
        domObjSet.sell_price.val(0);
    }

    if(sell && Array.isArray(sell) && sell.hasOwnProperty(0) && sell[0].length>1) {
        domObjSet.buy_price.val((parseInt(sell[0][0])).formatWon());
    } else {
        domObjSet.buy_price.val(0);
    }
};

var TradingView_BarManager = {
	open: 0,
	close: 0,
	high: 0,
	low: 0,
	volume: 0,

	initBar: function (v) {
		this.low = this.high = this.close = v.close;
	},

	updateBar: function (v) {
		this.open = this.close;
		if (this.high < v[2])
			this.high = v[2];
		if (this.low > v[2])
			this.low = v[2];
		this.close = v[2];
		this.volume = this.volume + v[3];
	},

	getCurrentBar: function () {
		var _self = this;
		return {
			time: (new Date()).getTime(),
			open: _self.open,
			close: _self.close,
			high: _self.high,
			low: _self.low,
			volume: _self.volume
		};
	},

	getEmptyBar: function () {
		var _self = this;
		this.high = this.low = this.open = this.close;
		this.volume = 0;
		return {
			time: (new Date()).getTime(),
			open: _self.open,
			close: _self.close,
			high: _self.high,
			low: _self.low,
			volume: _self.volume
		};
	},

	interval: 1500,
	intervalId: 0,
	unixTime: 0,

	setTime: function (time) {
		unixTime = time;
	},

	runTimer: function () {
		var _self = this;
		// 이전 타이머 취소
		if (this.intervalId !== 0) {
			clearInterval(this.intervalId);
			this.intervalId = 0;
		}

		// 타이머 시작
		this.intervalId = setInterval(function () {
			//console.log("check change minute....");
			var time = Date.now();
			if (_self.isChangeMinute(time))
			{
				_self.unixTime = time;
				var barValue = _self.getEmptyBar();
				TradingView_RealtimeDatafeeds.history_data.push(barValue);
				TradingView_RealtimeDatafeeds._realtimeListeners.callback(barValue);
			}
		}, _self.interval);
	},

	isChangeMinute: function (time) {
		var a = new Date(this.unixTime);
		var b = new Date(time);
		//console.log("prevTime: " + a.getMinutes());
		//console.log("currTime: " + b.getMinutes());
		if (a.getMinutes() != b.getMinutes())
			return true;
		return false;
	}
};
		
var TradingView_BarManager = {
	open: 0,
	close: 0,
	high: 0,
	low: 0,
	volume: 0,

	initBar: function (v) {
		this.low = this.high = this.close = v.close;
	},

	updateBar: function (v) {
		this.open = this.close;
		if (this.high < v[2])
			this.high = v[2];
		if (this.low > v[2])
			this.low = v[2];
		this.close = v[2];
		this.volume = this.volume + v[3];
	},

	getCurrentBar: function () {
		var _self = this;
		return {
			time: (new Date()).getTime(),
			open: _self.open,
			close: _self.close,
			high: _self.high,
			low: _self.low,
			volume: _self.volume
		};
	},

	getEmptyBar: function () {
		var _self = this;
		this.high = this.low = this.open = this.close;
		this.volume = 0;
		return {
			time: (new Date()).getTime(),
			open: _self.open,
			close: _self.close,
			high: _self.high,
			low: _self.low,
			volume: _self.volume
		};
	},

	interval: 1500,
	intervalId: 0,
	unixTime: 0,

	setTime: function (time) {
		unixTime = time;
	},

	runTimer: function () {
		var _self = this;
		// 이전 타이머 취소
		if (this.intervalId !== 0) {
			clearInterval(this.intervalId);
			this.intervalId = 0;
		}

		// 타이머 시작
		this.intervalId = setInterval(function () {
			//console.log("check change minute....");
			var time = Date.now();
			if (_self.isChangeMinute(time))
			{
				_self.unixTime = time;
				var barValue = _self.getEmptyBar();
				TradingView_RealtimeDatafeeds.history_data.push(barValue);
				TradingView_RealtimeDatafeeds._realtimeListeners.callback(barValue);
			}
		}, _self.interval);
	},

	isChangeMinute: function (time) {
		var a = new Date(this.unixTime);
		var b = new Date(time);
		//console.log("prevTime: " + a.getMinutes());
		//console.log("currTime: " + b.getMinutes());
		if (a.getMinutes() != b.getMinutes())
			return true;
		return false;
	}
};

var TradingView_RealtimeDatafeeds = {

	onReady: function (callback) {
		this.super_datafeeds = new Datafeeds.UDFCompatibleDatafeed("/tradingview", 60000 * 60 * 24);
		this.super_datafeeds.onReady(callback);
		this.history_data = [];
	},

	// 심볼(화폐이름)검색창에 값을 입력할때 호출(좌측상단)
	searchSymbols: function (searchString, exchange, symbolType, onResultReadyCallback) {
		this.super_datafeeds.searchSymbols(searchString, exchange, symbolType, onResultReadyCallback);
	},

	// step 2: 심볼을 검색할때 호출(차트가 처음 만들어질 때도 호출된다)
	resolveSymbol: function (symbolName, onSymbolResolvedCallback, onResolveErrorCallback) {
		// console.log("resolveSymbol");
		this.super_datafeeds.resolveSymbol(symbolName, onSymbolResolvedCallback, onResolveErrorCallback);
	},

	// step 3: 거래내역을 가져올때 호출
	getBars: function (symbolInfo, _resolution, rangeStartDate, rangeEndDate, onHistoryCallback, onErrorCallback) {
		// console.log("getBars");
		var _self = this;
		// this.super_datafeeds.getBars(symbolInfo, _resolution, rangeStartDate, rangeEndDate, onHistoryCallback, onErrorCallback);
		
		var form_data = {symbol: symbolInfo.name, resolution: _resolution, from: rangeStartDate, to: rangeEndDate};
		$.post('/tradingview/history', form_data, function (json) {
			//console.log(json);
			var c = 0;
			if (json != null && json.s == 'ok') {
				c = json.t.length;
				var bars = [];
				for (var i = 0; i < c; i++) {
					bars.push({
						time: json.t[i] * 1000,
						open: json.o[i],
						close: json.c[i],
						high: json.h[i],
						low: json.l[i],
						volume: json.v[i],
					});
				}

				if (bars.length > 0)
				{
					c = _self.history_data.length;
					for (var h = 0; h < c; h++) {
						bars.push(_self.history_data[h]);
					}

					TradingView_BarManager.initBar(bars[bars.length - 1]);
					onHistoryCallback(bars);
					return;
				}
			}

			onHistoryCallback([], {noData: true});
		}, 'json')
				.fail(function () { });
		
	},

	// step 4 : 
	subscribeBars: function (symbolInfo, resolution, onRealtimeCallback, listenerGUID, onResetCacheNeededCallback) {
		// console.log("subscribeBars resolution " + resolution);
		onResetCacheNeededCallback();
		this.super_datafeeds.subscribeBars(symbolInfo, resolution, onRealtimeCallback, listenerGUID, onResetCacheNeededCallback);

		this._realtimeListeners = {guid: listenerGUID, callback: onRealtimeCallback};
	},

	unsubscribeBars: function (subscriberUID) {
		this.super_datafeeds.unsubscribeBars(subscriberUID);
	},
/*
	getServerTime: function(unixTime) {
		this.super_datafeeds.getServerTime(function(time) {
	//		console.log("time : " + time);
			TradingView_BarManager.setTime(time);
			TradingView_BarManager.runTimer();
		});
	},
*/

	updateChartData: function(data)
	{
		//console.log("updateChartData");
		//console.log(data);
		if (!this.hasOwnProperty('history_data')) {
			this.history_data = [];
		}

		if (this.hasOwnProperty('_realtimeListeners'))	{
			
			var t = data[0];
			TradingView_BarManager.updateBar(t);
			var barValue = TradingView_BarManager.getCurrentBar();
			this.history_data.push(barValue);
			this._realtimeListeners.callback(barValue);
		}
	}
};


var onTradeList = function(json) {
    var data = $.parseJSON(json);
    var listarray = [];
    var listview = '';
    // 리스트갯수
    var tradeListCount = 8;
    
    var isChartChangeCallback = false;
    if(typeof onChartDataChange === 'function') {
        isChartChangeCallback = true;
    }
    
	if( typeof(TradingView_RealtimeDatafeeds) != 'undefined' )
	{
		TradingView_RealtimeDatafeeds.updateChartData(data);
	}

    if ( data != null ) {
        for(var i=0; i < tradeListCount; i++){
            if(isChartChangeCallback && preChartData < data[i][0]) {
                onChartDataChange(data[i]);
            }
                
            var listclass = '';
            if(typeof data[i] !== 'undefined') {
                if(data[i][1] == 'buy') {
                    listclass = 'red';
                } else if(data[i][1] == 'sell') {
                    listclass = 'blue';
                }
                var tmpDataBitCoin = fixedFloat((data[i][3]).formatBitcoin());
                var tmpDataBitCoinArr = tmpDataBitCoin.split('.');
                listview = '<tr class="' + listclass + '">';
                listview += '<td>' + (data[i][2]).formatWon() + '</td>';
                //listview += '<td>' + (data[i][3]).formatBitcoin() + '</td>';
                listview += '<td><div class="float-num full"><span class="int">' + tmpDataBitCoinArr[0] + '</span><span class="double">' + tmpDataBitCoinArr[1] + '</span></div></td>';
                listview += '</tr>';

                listarray.push(listview);
            } else {
                listview = '<tr>';
                listview += '<td></td>';
                listview += '<td></td>';
                //listview += '<td></td>';
                listview += '</tr>';
            }
            if(i == 0) {
                chartData = data[i][0];
            }
        }
        preChartData = chartData;
    } else {
        listview = '<tr><td>' + langConvert('lang.viewEmptyData', '') + '</td></tr>';
        listarray.push(listview);
    }
    $('.list-tradevolume tbody').html(listarray.join());
};

var onRealTradeList = function(json) {
    var data = $.parseJSON(json);
	//console.log(data);
    var listarray = [];
    var listview = '';
    // 리스트갯수
    var tradeListCount = 50;
    var tradeType = '';
	
    if ( data != null ) {
        for(var i=0; i < tradeListCount; i++) {
            var listclass = '';
            if(typeof data[i] !== 'undefined') {
                if(data[i][1] == 'buy') {
                    listclass = 'red';
                    tradeType = langConvert('lang.commonBid', '');
                } else if(data[i][1] == 'sell') {
                    listclass = 'blue';
                    tradeType = langConvert('lang.commonAsk', '');
                }
                var realBitCoin = fixedFloat((data[i][3]).formatBitcoin());
                var realBitCoinArr = realBitCoin.split('.');
                listview = '<tr class="' + listclass + '">';
                listview += '<td>' + tradeType+ '</td>';
                listview += '<td>' + data[i][4].substr(10,9) + '</td>';
                listview += '<td>' + (data[i][2]).formatWon() + '</td>';
                listview += '<td><div class="float-num full"><span class="int">' + realBitCoinArr[0] + '</span><span class="double">' + realBitCoinArr[1] + '</span></div></td>';
                listview += '</tr>';

                listarray.push(listview);
            } else {
                listview = '<tr>';
                listview += '<td></td>';
                listview += '<td></td>';
                listview += '<td></td>';
                listview += '<td></td>';
                listview += '</tr>';
            }
        }
    } else {
        listview = '<tr><td>' + langConvert('lang.viewEmptyData', '') + '</td></tr>';
        listarray.push(listview);
    }
    $('.list-realtradevolume tbody').html(listarray.join());
};
