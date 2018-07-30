var defaultSet = 0;                 // 데이터 기본 로딩 - 여부에 따라서 이펙트 효과
var tradeMarketCostListArray  = []; // 시장현황 데이터 - 이펙트를 위해서 값을 가지고 있는다. 리프레시 될때마다 비교

// 기본 로딩 바
var loading = '<tr><td colspan="3" style="font-size:15px;background-color:#FFFFFF;" align="center"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</td></tr>';
$('.list-trademarketcost tbody').html(loading);

// 페이지 로딩용 list call
//tradeMarketCostList(); => 이벤트 발생시 호출로 변경됨 150814
//tradeCompleteList();


// 시장 시세 호출
var tradeMarketCostRefresh = function(){
    // 인터벌로 리프레시 2번째부터 부르는거니 데이터 기본 로딩 체크값 1로 변경
    defaultSet = 1;
//    clearAnimationClass();
}

var getMarketCost = function(code_type,od_market_price,qty){
    //if(code_type == 'buy'){
        tradePageSet.code = 'sell';
        domObjSet.sell_price.val((od_market_price).formatWon());
        domObjSet.sell_amount.val(qty.formatBitcoin());
    //}else if(code_type == 'sell'){
        tradePageSet.code = 'buy';
        domObjSet.buy_price.val((od_market_price).formatWon());
        domObjSet.buy_amount.val(qty.formatBitcoin());
    //}
    displayInputAmount();
}
    
// 시장 시세 - 거래소
// var tradeMarketCostList = function(){
var tmpX = 0;
var g_isTradeMarketCostListComplete = 0;
function tradeMarketCostList(data){
	g_isTradeMarketCostListComplete = 0;
	if (tmpX == 0)
	{
		// <div> .....
		tmpX = 1;
	}
	
//    var tmpHtml = '<div style="background-color: #f00;width: 100%;height: 100%;position: absolute;top:0;left:0;z-index:7999;" id="tmpBlock"></div>';
//    if(tmpX == 0) {
//        if(tmpUrl.substr(0,14) == '/trade/order_m' || tmpUrl.substr(0,22) == '/trade/current_price_m') {
//            $('body').append(tmpHtml);
//            //document.getElementById('tmpBox').write(tmpHtml);
//            setTimeout(function() {
//                if(tmpUrl.substr(0,14) == '/trade/order_m' || tmpUrl.substr(0,22) == '/trade/current_price_m') {
//                    document.getElementById('tmpBlock').style.display = 'none';
//                }
//            }, 1000);
//        }
//    }
    //tmpX = 1;
    if(tradeMarketCostListArray && typeof tradeMarketCostListArray[0] !=="undefined"){
        defaultSet = 1;
    }
    if(typeof chartPageSet!=='undefined'){
        drawTradeChart(data);
    }
    
    try{
        data = $.parseJSON(data);
    }catch(e){
        console.log('e:'+e + ' type:' + (typeof data) );
        return;
    }

    if(!Array.isArray(data)) return;
    var sellbtc_cnt         = 0;    // 판매 데이터 수 체크
    var buybtc_cnt          = 0;    // 구매 데이터 수 체크
    var listarray           = [];
    var sellbtc_idx         = 7;
    var buybtc_idx          = 1;
    var listview            = '';
    var desc                = '';
    var data_array          = [];
    var compare_result      = false;
    var linked              = "";
    var od_temp_btc         = 0.0;
    var sellbtc_empty_view  = '';   // 판매 기본 물량 view
    var buybtc_empty_view   = '';   // 구매 기본 물량 view

    // 기본 물량 판/구매 각 7개를 위한 추가 20160314
    
    // 판매 개수 체크 sellbtc_cnt
    for(var i=0; i<data.length;i++) {
        if(data[i][3]=="sell") {
            sellbtc_cnt++;
        }
        if(data[i][3]=="buy") {
            buybtc_cnt++;
        }
    }

    // 판매 물량 != 기본 셋팅 물량
    if(sellbtc_cnt!=7){
        for(var i=sellbtc_idx; i>sellbtc_cnt; i--){
            desc = langConvert('lang.commonAsk','')+"("+ i + ")";
            sellbtc_empty_view +='<tr class="blue">';
            sellbtc_empty_view +='<td>' + (parseFloat(0.0)).toFixed(8) + '</td>';
            sellbtc_empty_view +='<td>0</td>';
            sellbtc_empty_view +='<td class="none-bg none-border"></td>';
            sellbtc_empty_view +='</tr>';
        }
    }
    // 구매 물량 != 기본 셋팅 물량
    if(buybtc_cnt!=7){
        for(var i=(buybtc_cnt+1); i<8; i++){
            desc = langConvert('lang.commonBid','')+"("+ i + ")";
            buybtc_empty_view +='<tr class="red">';
            buybtc_empty_view +='<td class="none-bg none-border"></td>';
            buybtc_empty_view +='<td>0</td>';
            buybtc_empty_view +='<td>' + (parseFloat(0.0)).toFixed(8) + '</td>';
            buybtc_empty_view +='</tr>';
        }
    }
    // BIGIN :: 최대.최소 그래프를 그리기 위해 최소값과 최대값 구하는 for 문 추가 / 전체 값 : tmpDataArr 배열에 담음 2018-06-29
    var tmpDataArr = new Array();
    var tmpDataTypeArr = new Array();
    console.log('====================================================');
    var sellSave = 0;
    var buySave = 7;
    for(var i=0; i<data.length; i++){
        if(typeof data[i][3] !== "undefined") {
            tmpDataTypeArr[i] = data[i][3];
            tmpDataArr[i] = data[i][1];
            /*
            if(data[i][3] == 'sell') {
                if(sellSave < 7) {
                    tmpDataTypeArr[sellSave] = data[i][3];
                    tmpDataArr[sellSave] = data[i][1];
                    sellSave++;
                }
            }
            if(data[i][3] == 'buy') {
                if(buySave < 14) {
                    tmpDataTypeArr[buySave] = data[i][3];
                    tmpDataArr[buySave] = data[i][1];
                    buySave++;
                }
            }
            */
            //console.log('값 타입 :: ' + tmpDataTypeArr[i] + ' / 값 :: ' + tmpDataArr[i]);
            //console.log('값 :: ' + tmpDataArr[i]);
            /*
            sell 7개 까지 저장, 
            밑으로 무시 하다가..
            buy 시작부터 
            7개 까지 저장
            배열 하나에 총 14개 값을 저장( 0 ... 13 )
            Math.max로 값 재 배열
            Math.min로 값 재 배열
            백분율로 계산후 ... : 100 / (max - min)
            비율에 따른 막대 그래프 생성
            상황 반복 ...
            */
        }
    }
    var maxValue = 0;
    var minValue = 0;
    maxValue = Math.max.apply(null, tmpDataArr);
    minValue = Math.min.apply(null, tmpDataArr);
    var onePrecentage = (1000000 / (maxValue - minValue)).toFixed(8);

    var calcPercentage = new Array();
    console.log('TOTAL Array :: ' + tmpDataArr.length);
    for(var i = 0; i < tmpDataArr.length; i++) {
        calcPercentage[i] = (Math.round((tmpDataArr[i] - minValue) * onePrecentage).toFixed(8)) / 10000;

        console.log('값 % :: ' + calcPercentage[i]);
        console.log('값 타입 :: ' + tmpDataTypeArr[i] + ' / 값 :: ' + tmpDataArr[i]);
    }
    
    console.log('MIN Value :: ' + minValue);
    console.log('MAX Value :: ' + maxValue);
    console.log('1% :: ' + onePrecentage);
    console.log('minus value :: ' + (maxValue - minValue));


    console.log('====================================================');
    // END :: 최대.최소 그래프를 그리기 위해 최소값과 최대값 구하는 for 문 추가 / 전체 값 : tmpDataArr 배열에 담음 2018-06-29
    for(var i=0; i<data.length; i++){
        // false면 같지 않다.. 이펙트 효과 on
        // true면 동일하다 이펙트효과 off
        var tmpFormatBitCoin = fixedFloat(data[i][1].formatBitcoin());
        var tmpFormatBitCoinArr = tmpFormatBitCoin.split('.');
        var finalPercentage = 0;
        
        
        //console.log('data ' + i + ' = ' + tmpFormatBitCoin);
        if(typeof data[i][3] === "undefined") {
            listview = '<tr><th colspan="3"><center>'+langConvert('lang.msgExceptionsoccurred','')+'</center></th></tr>';
            listarray.push(listview);
            $('.list-trademarketcost tbody').html(listarray.join());

            return false;
        } else if(data[i][3]=="sell") {
            if(sellbtc_cnt < 8){
                console.log('========================= Sell ===========================');
                for(x = 0; x < tmpDataArr.length; x++) {
                    if(tmpFormatBitCoin == tmpDataArr[x]) {
                        console.log('data type : ' + tmpDataTypeArr[x] + ' / ' + tmpFormatBitCoin + ' / true_' + x + ' / ' + tmpDataArr[x]);
                        finalPercentage = calcPercentage[x];
                        console.log('final value : ' + finalPercentage + ' %');
                        break;
                    }
                }
                console.log('========================= Sell ===========================');
                listview = '';
                //console.log(sellbtc_idx);
                //console.log(sellbtc_cnt);
                if(i==0 && sellbtc_idx!=sellbtc_cnt) {
                    listview += sellbtc_empty_view;
                }
                // 0부터 시작 총 개수는 sellbtc_cnt
                data_array = data[i][3] + "," + data[i][0] + "," + data[i][1];
                if(defaultSet!=0) {
                    compare_result = compare(tradeMarketCostListArray[i], data_array);
                }
                // desc = langConvert('lang.pay_sale','')+"("+ sellbtc_idx + ")";
                desc = langConvert('lang.commonAsk','')+"("+ sellbtc_cnt + ")";
                // 링크
                if(typeof tradePageSet!=='undefined') {
                    od_temp_btc = (parseFloat(data[i][1])).toFixed(8);
                    linked = ' style="cursor:pointer" onClick="getMarketCost(\''+data[i][3]+'\','+data[i][0]+','+od_temp_btc+')" ';
                }

                // 데이터 effect 효과 유무
                if(compare_result) {
                    // true
                    listview += '<tr class="blue" '+linked+'> ';
                } else {
                    // false (new data)
                    if(defaultSet==1) {
                        // ok
                        listview += "<tr class='blue animated ' "+linked+">";
                    } else {
                        listview += "<tr class='blue' "+linked+">";
                    }
                }
                //listview += '<td>' + data[i][1].formatBitcoin() + '</td>';
                //listview += '<td><div class="float-num pull-right" style="background-color: rgba(0, 0, 128, 0.5); width: 100%; text-align: right;"><span class="int">' + tmpFormatBitCoinArr[0] + '</span><span class="double">' + tmpFormatBitCoinArr[1] + '</span></div></td>';
                listview += '<td><div class="float-sell-wrap"><div class="sell-percent-graph" style="width: calc(' + finalPercentage + '% + 10px);"></div><div class="float-num pull-right"><span class="int">' + tmpFormatBitCoinArr[0] + '</span><span class="double">' + tmpFormatBitCoinArr[1] + '</span></div></div></td>';
                listview += '<td>' + (data[i][0] + '').toString().formatNumber() + '</td>';
                listview += '<td class="none-bg none-border sell-info-area"></td>';
                /*
                if(sellbtc_cnt == 7) {
                    listview += '<td rowspan="7" class="none-bg none-border sell-info-area"></td>';
                }
                */
            } else {
                listview = '';
            }

            sellbtc_idx --;
            sellbtc_cnt --;
        } else if(data[i][3]=="buy") {
            if(buybtc_idx < 8){
                console.log('========================= Buy ===========================');
                for(x = 0; x < tmpDataArr.length; x++) {
                    if(tmpFormatBitCoin == tmpDataArr[x]) {
                        console.log('data type : ' + tmpDataTypeArr[x] + ' / ' + tmpFormatBitCoin + ' / true_' + x + ' / ' + tmpDataArr[x]);
                        finalPercentage = calcPercentage[x];
                        console.log('final value : ' + finalPercentage + ' %');
                        break;
                    }
                }
                console.log('========================= Buy ===========================');
                listview = '';
                if(i==0 && sellbtc_idx!=sellbtc_cnt) {
                    listview += sellbtc_empty_view;
                }
                data_array = data[i][3] + "," + data[i][0] + "," + data[i][1];
                if(defaultSet!=0) {
                    compare_result = compare(tradeMarketCostListArray[i], data_array);
                }
                desc = langConvert('lang.commonBid','')+"("+ buybtc_idx + ")";
                // 링크
                if(typeof tradePageSet!=='undefined') {
                    od_temp_btc = (parseFloat(data[i][1])).toFixed(8);
                    linked = ' style="cursor:pointer" onClick="getMarketCost(\''+data[i][3]+'\','+data[i][0]+','+od_temp_btc+')" ';
                }

                // 데이터 effect 효과 유무
                if(compare_result) {
                    // true
                    // 테스트후 제거
                    listview += '<tr class="red" '+linked+'>';
                } else {
                    // false (new data)
                    if(defaultSet==1) {
                        // ok
                        listview += '<tr class="red animated" '+linked+'>';
                    } else {
                        listview += '<tr class="red" '+linked+'>';
                    }
                }

                listview += '<td class="none-bg none-border"></td>';
                listview += '<td>' + (data[i][0] + '').toString().formatNumber() + '</td>';
                //listview += '<td>' + data[i][1].formatBitcoin() + '</td>';
                listview += '<td><div class="float-buy-wrap"><div class="buy-percent-graph" style="width: calc(' + finalPercentage + '% + 10px);"></div><div class="float-num"><span class="int">' + tmpFormatBitCoinArr[0] + '</span><span class="double">' + tmpFormatBitCoinArr[1] + '</span></div></div></td>';
            } else {
                listview = '';
            }
    
            buybtc_idx ++;
        }
        // 구매 기본 물량 깔아주기
        if(i==(data.length-1) && buybtc_cnt!=7) {
            listview += buybtc_empty_view;
        }
        listarray.push(listview);
        tradeMarketCostListArray.push(data_array);
        tradeMarketCostListArray[i] = data_array;   // 값 비교를 위한 데이터
    }
	
    // 없을시 기본 테이블 구조를 위한 판구매 7/7 셋팅
    if(data.length==0) {
        listview = sellbtc_empty_view + buybtc_empty_view;
        listarray.push(listview);
    }

    $('.list-trademarketcost tbody').html(listarray.join());

    if( $(".animated2").length ){
        clearTradeAnimationClass();
    }
	
	g_isTradeMarketCostListComplete = 1;
    // console.log("거래정보 설정.....");
}

// 거래 판/구매 요청 데이터 변경시 effect
function clearAnimationClass() {
    var objsell = $(".sell");
    var objbuy = $(".buy");
    var objtrade = $(".tradecomlist");

    setTimeout(function() {
        objsell.removeClass("sell_effect");
        objbuy.removeClass("buy_effect");
        objtrade.removeClass("trade_effect");
    }, 5000);
}
var clearTradeAnimationClass = function (domid){
    setTimeout(function() {
       $('#market-trade-his #'+domid).removeClass("animated2");
//        domObjSet.list_table_tradehis_animated.removeClass("animated");
    }, 3000);
};

/*******************************
 * 판 / 구매 시장현황
 ******************************/
var preTradeSellMarketCostListArray    = []; // 이전 데이터비교 용
var preTradeBuyMarketCostListArray     = []; // 이전 데이터비교 용

function tradeMarketBuyCostList(data){
    var listarray           = [];
    var listview            = '';
    
    if(!data) {
        listview = '<tr><td colspan="3" style="max-height:30px;"><center>'+langConvert('lang.viewEmptyData','')+'</center></td></tr>';
        listarray.push(listview);
        $('table.short-list-tradebuymarketcost>tbody').html(listarray.join());
        return;
    }

    try{
        if(typeof data!=='object') data = JSON.parse(data);
    }catch(e){
        console.log('tradecomlist e:'+e + ' type:' + (typeof data) );
        return;
    }

    var data_limit = 10; // 10개 제한
    var compare_result = 0;
    var tmpTradeBuyArray = [];
    
    for(i=0; i<data_limit; i++){

        if(typeof data[i] !== 'undefined'){

            compare_data = data[i][0] + "-" + data[i][1];
            compare_result = preTradeBuyMarketCostListArray.indexOf(compare_data);
            if(preTradeBuyMarketCostListArray.length == 0) compare_result = 0;
            
            if(compare_result === -1){
                listview = '<tr class="animated2">';
            }else{
                listview = '<tr>';
            }

            listview += '<td style="max-height:30px;" class="text-align-center">' + (data[i][0]+'').toString().formatNumber() + '</td>';
            listview += '<td class="text-align-center">' + (data[i][1]).formatBitcoin() + '</td>';
            listview += '<td class="text-align-right">' + (data[i][2]+'').toString().formatNumber() + '</td>';
            listview += '</tr>';
            listarray.push(listview);
            tmpTradeBuyArray.push(compare_data);
        }
    }
    
    preTradeBuyMarketCostListArray = tmpTradeBuyArray;

    if(data.length==0){
        listview = '<tr><td colspan="3" style="max-height:30px;"><center>'+langConvert('lang.msgThereAreNoRecentTransactionHistory','')+'</center></td></tr>';
        listarray.push(listview);
    }

    $('table.short-list-tradebuymarketcost>tbody').html(listarray.join());
}

function tradeMarketSellCostList(data){
    var listarray           = [];
    var listview            = '';
    
    if(!data){
        listview = '<tr><td colspan="3" style="max-height:30px;"><center>'+langConvert('lang.viewEmptyData','')+'</center></td></tr>';
        listarray.push(listview);
        $('table.short-list-tradesellmarketcost>tbody').html(listarray.join());
        return;
    }

    try{
        if(typeof data!=='object') data = JSON.parse(data);
    }catch(e){
        console.log('tradecomlist e:'+e + ' type:' + (typeof data) );
        return;
    }

    var data_limit = 10; // 10개 제한
    var compare_result = 0;
    var tmpTradeSellArray = [];
    
    for(i=0; i<data_limit; i++){

        if(typeof data[i] !== 'undefined'){
            
            compare_data = data[i][0] + "-" + data[i][1];
            compare_result = preTradeSellMarketCostListArray.indexOf(compare_data);
            if(preTradeSellMarketCostListArray.length == 0) compare_result = 0;

            if(compare_result === -1){
                listview = '<tr class="animated2">';
            }else{
                listview = '<tr>';
            }

            listview += '<td style="max-height:30px;" class="text-align-center">' + (data[i][0]+'').toString().formatNumber() + '</td>';
            listview += '<td class="text-align-center">' + (data[i][1]).formatBitcoin() + '</td>';
            listview += '<td class="text-align-right">' + (data[i][2]+'').toString().formatNumber() + '</td>';
            listview += '</tr>';
            listarray.push(listview);
            tmpTradeSellArray.push(compare_data);
        }
    }
    
    preTradeSellMarketCostListArray = tmpTradeSellArray;

    if(data.length==0){
        listview = '<tr><td colspan="3" style="max-height:30px;"><center>'+langConvert('lang.msgThereAreNoRecentTransactionHistory','')+'</center></td></tr>';
        listarray.push(listview);
    }

    $('table.short-list-tradesellmarketcost>tbody').html(listarray.join());
}

// 거래 완료 리스트
var tradeCompleteLastestDT=null;
function tradeCompleteList(data){
    var listarray           = [];
    var listview            = '';

    if(!data){
        listview = '<tr><td colspan="5" style="max-height:30px;"><center>'+langConvert('lang.viewEmptyData','')+'</center></td></tr>';
        listarray.push(listview);
        $('table.short-list-tradecomplete>tbody').html(listarray.join());
        return;
    }

    try{
        if(typeof data!=='object') data = JSON.parse(data);
    }catch(e){
        console.log('tradecomlist e:'+e + ' type:' + (typeof data) );
        return;
    }
    
    var str_date = "";
    var str_type = "";
    var effect_skip = false; //신규 리스트 효과주기
    var data_limit = 10; // 10개 제한

    for(i=0; i<data_limit; i++){

        if(typeof data[i] !== 'undefined'){
            str_date = (data[i][4]+"").toString();
            str_date = str_date.substring(5, str_date.length);  // 연도 제거
            str_date = str_date.substring(0, str_date.length-3);    // 초 제거

            listview = '<tr>';
            //처음은 실행 안함
            if(tradeCompleteLastestDT!=null && !effect_skip){
                if(tradeCompleteLastestDT!=null && typeof tradeCompleteLastestDT !=='undefined' &&
                    data[i][4] == tradeCompleteLastestDT){
                        effect_skip = true;
                }else{
                    listview = '<tr class="tradecomlist animated2">';
                }
            }
            if(data[i][1] == 'buy'){
                str_type = '구매';

            }else if(data[i][1] == 'sell'){
                str_type = '판매';

            }

            listview += '<td style="max-height:30px;" class="text-align-left">' + str_date + '</td>';
            listview += '<td class="text-align-center">' + str_type + '</td>';
            listview += '<td class="text-align-center">' + (data[i][2]+'').toString().formatNumber() + '</td>';
            listview += '<td class="text-align-right">' + (data[i][5]+'').toString().formatNumber() + '</td>';
            listview += '<td class="text-align-right">' + (data[i][3]).formatBitcoin() + '</td>';
            listview += '</tr>';
            listarray.push(listview);
        }
    }

    if(data.length==0){
        listview = '<tr><td colspan="5" style="max-height:30px;"><center>'+langConvert('lang.msgThereAreNoRecentTransactionHistory','')+'</center></td></tr>';
        listarray.push(listview);
    }else{
        //마지막 시간값을 계산하여 애니메이션 처리에 사용
        tradeCompleteLastestDT = data[0][4];
    }

    $('table.short-list-tradecomplete>tbody').html(listarray.join());
}

var compare = function(a, b){
    var i = 0, j;
    if(typeof a == "object" && a){
        if(Array.isArray(a)){
            if(!Array.isArray(b) || a.length != b.length) return false;
            for(j = a.length ; i < j ; i++) if(!compare(a[i], b[i])) return false;
            return true;
        }else{
            for(j in b) if(b.hasOwnProperty(j)) i++;
            for(j in a) if(a.hasOwnProperty(j)){
                if(!compare(a[j], b[j])) return false;
                i--;
            }
            return !i;
        }
    }
    return a === b;
};
