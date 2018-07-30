//document.getElementsByClassName('trade-slider-menu').style.display = 'none';
// 세션이 종료 되었거나 세션이 없으면 첫번째 페이지(로그인) 페이지로 이동
//if( !get_member.hasOwnProperty('mb_id') ){
//}
if(typeof(jsonObject)==='object'){
    if(typeof(jsonObject.member)!=='undefined' && jsonObject.member)
        var get_member = $.parseJSON($.base64.decode(jsonObject.member));
    if(typeof(jsonObject.client)!=='undefined' && jsonObject.client)
        var get_client = $.parseJSON($.base64.decode(jsonObject.client));
    if(typeof(jsonObject.link)!=='undefined' && jsonObject.link)
        var get_link = $.parseJSON($.base64.decode(jsonObject.link));
    
}

var socket;
var socket_channel = 'basic';

var domTickerSet = {}; //속도을 위해 DOM객체를 저장
var preTickerData = {}; //티커 이전 정보를 저장
var gCurrentChTicker = {}; //접속 채널(코인)에 해당하는 가격정보


var getSocChannel = function(){
    return socket_channel;
};

var setSocChannel = function(cpage){
    socket_channel = cpage;
};


var Config = {
    debug:true,
    access_token_key : "d86c828583c5c6160e8acfee88ba1590",
    refresh_token_key : "e268443e43d93dab7ebef303bbe9642f",
    account_key : "e268443e43d93dab7ebef303bbe9642f",
    ssid : "c26e8178126688deb863604bef4b0cda",
    connkey : "817eebc5b49c13f8e6a0a7d159a49c09",
    islinkio:false,

    socketOnEventCallback:function(){},
    initHeaderScript:function(){
        is_socket_join = false;
        islinkio = ( typeof(get_link)!='undefined' && typeof(get_link.websocket) !='undefined' )?true:false;

        reqload([
            ((islinkio )?""+get_link.websocket.replace('ws','http')+'/socket.io/socket.io.js':'')
        ]);
        Utils.defaultSetCookieCountry();
        
        

    },
    initFooterScript:function(){
        var chatconf = {
            channel:getSocChannel(),
            linkserver: ''
        };
        
        var ssid = Utils.getCookie(Config.ssid);
        var apikey = Utils.getCookie(this.connkey);
        chatconf.linkserver = ''+get_link.websocket + '/channel?apikey='+apikey+'&ssid='+ssid;
//        if(Config.debug) console.log('apikey:' + apikey + ' ssid:'+ssid );
//		console.log('apikey:' + apikey + ' ssid:'+ssid );
//		console.log('linkserver: ' + chatconf.linkserver );
//		console.log('channel: ' + chatconf.channel );
        if(islinkio){
            var defaulttitle = document.title;
            try {
                socket = io.connect(chatconf.linkserver);

                socket.on('connect', function () {
                    if(Config.debug){
//                        console.log('connect');
                    }
                    socket.emit('join', chatconf.channel, function (err) {
//                        if(err){
//                            if(Config.debug) console.log('err:'+err);
//                        }
//                        else{
//                            if(Config.debug) console.log('join');
//                        }
                        if(!err){
                            is_socket_join = true;
                        }else{
                            controllerComm.alertError('<p>'+langConvert('lang.msgTickerServerAccessFailed','')+'</p><p>'+langConvert('lang.msgServerMaintenanceOrServerErrorPleaseTryAgainLater','')+'</p>');
                            return;
                        }
                    });
                });

                //20row
                function onChannelTradeRegist(data,data2,marketdeps){
//                    if(Config.debug) console.log("socketOn onChannelTradeRegist : " + data,' ,data2:'+data2+' , marketdeps:'+marketdeps);
//                    if(!data || data=='undefined' || data=='{}') return;
                    if(typeof onTickerTradeEvent === 'function' ) {
                           onTickerTradeEvent(data,data2); //buy,sell
                    }
                    if(typeof onTickerMarketDepsEvent === 'function' && marketdeps ) {
                           onTickerMarketDepsEvent(marketdeps); //marketdeps
                    }
                    
                }
                function onChannelTradeComplete(data){
                    if(!data || data=='undefined' || data=='{}') return;
                    //console.log("socketOn onChannelTradeComplete : " + data);
                    if(typeof onTickerTradeComEvent === 'function'){
                        onTickerTradeComEvent(data);
                    }
                }
                function onChannelMyTradeComplete(data){
                    if(!data) return;
                    //console.log("socketOn onChannelMyTradeComplete : " + data);
                    var svmessgage = {type:'',status:''};
                    try{
                        svmessgage = $.parseJSON(data);
                        //console.log("socketOn onChannelMyTradeComplete svmessgage: " + svmessgage);
                    }catch(e){}
                            
                    console.log("socketOn onChannelMyTradeComplete svmessgage.type: " + svmessgage.type);
                    console.log("socketOn onChannelMyTradeComplete svmessgage.status: " + svmessgage.status);
                      if('trade' == svmessgage.type && 'OK' == svmessgage.status){
                           console.log("socketOn onChannelMyTradeComplete _update_buy_sell_price_sum: ");
                          _update_buy_sell_price_sum();
                      }
                      
//                    if(svmessgage && svmessgage.hasOwnProperty('type') && svmessgage.type == 'trade'){
                        SendEvent.sendTradeComplete();
                        tradeSignMessage(svmessgage.status); //메시지
//                        initBalanceSum(); 
//                    }
 
                    
                    if(typeof onTickerMyTradeComEvent === 'function' && data){
                        onTickerMyTradeComEvent(data);
                    }
                }

                function onMyBalanceChange(data){
                    var svmessgage = {type:'',status:'',msg:''};
                    try{
                        svmessgage = $.parseJSON(data);
                    }catch(e){}
                    if(svmessgage && svmessgage.hasOwnProperty('type') && svmessgage.type == 'balance'){
                        initBalanceSum();
                    }
                }
                
                var domMainSet = {
                    ch_currency_status : $('#dash-currency-status')
                    //, ch_24hour_status : $('#dash-24hour-status')
                }; //속도을 위해 DOM객체를 저장
                
                var isMainPage = $('#dash-currency-main').length;
                var isWalletPage = $('.wallet-page').length;
				var isIntroMainPage = $('.pc-main-wrap').length;

                function onChannelTicker(jstring){
                    if(!jstring ){
                        return;
                    }
                    var jsondata = {};
                    try{
                        jsondata = $.parseJSON(jstring);
                    }catch(e){console.log(e)}
                                               
                    if(typeof jsondata === 'string'){
                        try{
                            jsondata = $.parseJSON(jsondata);
                        }catch(e){console.log(e)}
                    }
                    
                    var dataCur = jsondata.master;
                    var data24Hour = jsondata.ch24hour;

                    if(isMainPage > 0){
                        
                        for (key in dataCur) {
                            var perChange = 0.0;
                            var per_str = dataCur[key].percentChange+'%';
                            var priceChange = 0;
                            
                            perChange = calfloat('FLOOR',Number(dataCur[key].percentChange),2);
                            priceChange = priceGap(dataCur[key].last, perChange);
                            
                            $('#dash-currency-main').find('.'+key+' .def_price').html((dataCur[key].last).formatWon());
                            $('#dash-currency-main2').find('.'+key+' .def_price').html((dataCur[key].last).formatWon());
                            $('#won').find('.'+key+' .def_price').html((dataCur[key].last).formatWon());
							$('#coinStatus').find('.'+key+' .def_price').html((dataCur[key].last).formatWon());
                            if(dataCur[key].volume24h != null){
                                $('#dash-currency-main2').find('.'+key+' .def_volume').html(dataCur[key].volume24h);
								//console.log("key : " + key + " data : " + dataCur[key].volume24h);
                            }else{
                                $('#dash-currency-main2').find('.'+key+' .def_volume').html('0.00000000');
                            }
                            
                            if(!isNaN(Number(dataCur[key].percentChange))){
                                if( perChange > 0 ){
                                    $('#dash-currency-main').find('.'+key+' .def_per_change').html(per_str);
                                    $('#wrap').find('.'+key+' .def_per_change').html(per_str);
                                    $('#wrap').find('.'+key+' .color_controller').removeClass('blue');
                                    $('#wrap').find('.'+key+' .color_controller').removeClass('black');
                                    $('#wrap').find('.'+key+' .color_controller').addClass('red');
                                    $('#wrap').find('.'+key+' .color_controller').parent().addClass('up');
                                    $('#wrap').find('.'+key+' .color_controller').parent().find('.up-down-icon').addClass('bar-up');
                                }else if( perChange < 0 ){
                                    $('#dash-currency-main').find('.'+key+' .def_per_change').html(per_str);
                                    $('#wrap').find('.'+key+' .def_per_change').html(per_str);
                                    $('#wrap').find('.'+key+' .color_controller').removeClass('red');
                                    $('#wrap').find('.'+key+' .color_controller').removeClass('black');
                                    $('#wrap').find('.'+key+' .color_controller').addClass('blue');
                                    $('#wrap').find('.'+key+' .color_controller').parent().addClass('down');
                                    $('#wrap').find('.'+key+' .color_controller').parent().find('.up-down-icon').addClass('bar-down');
                                }else{
                                    $('#dash-currency-main').find('.'+key+' .def_per_change').html(per_str);
                                    $('#wrap').find('.'+key+' .def_per_change').html(per_str);
                                    $('#wrap').find('.'+key+' .color_controller').removeClass('red');
                                    $('#wrap').find('.'+key+' .color_controller').removeClass('blue');
                                    $('#wrap').find('.'+key+' .color_controller').addClass('black');
                                    $('#wrap').find('.'+key+' .color_controller').parent().addClass('down');
                                    $('#wrap').find('.'+key+' .color_controller').parent().find('.up-down-icon').addClass('bar-down');
                                }
                                $('#wrap').find('.'+key+' .def_price_gap').html(priceChange.formatWon());
                                
                            }

                            if(typeof(data24Hour[key]) !== 'undefined'){
                                if(data24Hour[key].maxprice != null){
                                    $('#wrap').find('.'+key+' .def_24hr_high').html((data24Hour[key].maxprice).formatWon());
                                    $('.def_24hr_high').removeClass('blue');
                                    $('.def_24hr_high').addClass('red');
                                }else{
                                    $('#wrap').find('.'+key+' .def_24hr_high').html(0);
                                    $('.def_24hr_high').removeClass('blue');
                                    $('.def_24hr_high').addClass('red');
                                }
                                if(data24Hour[key].maxprice != null){
                                    $('#wrap').find('.'+key+' .def_24hr_low').html((data24Hour[key].minprice).formatWon());
                                    $('.def_24hr_low').removeClass('red');
                                    $('.def_24hr_low').addClass('blue');
                                }else{
                                    $('#wrap').find('.'+key+' .def_24hr_low').html(0);
                                    $('.def_24hr_low').removeClass('red');
                                    $('.def_24hr_low').addClass('blue');
                                }
                            }
                        }
                    }
					else if(isWalletPage > 0){
                        initBalance(jsondata);
                    }
					// 최초 시작 페이지
					else if(isIntroMainPage > 0) {
						//console.log(jsondata);
						var find_obj = $("#current-coin-price");
                        for (key in dataCur) {
                            var per_str = dataCur[key].percentChange+'%'; // 전일대비(%)
							var perChange = calfloat('FLOOR',Number(dataCur[key].percentChange),2);
                            var priceChange = priceGap(dataCur[key].last, perChange); // 전일대비가격
							var total_price = dataCur[key].last * dataCur[key].volume24h;
							
                            find_obj.find('#total-price-'+key).html((total_price).formatWon()); // 시가총액
                            find_obj.find('#last-'+key).html((dataCur[key].last).formatWon()); // 실시간시세
							find_obj.find('#percentChange-'+key).html(per_str); // 전일대비
							find_obj.find('#bid-'+key).html((dataCur[key].bid).formatWon()); // 24h 고가
							find_obj.find('#ask-'+key).html((dataCur[key].ask).formatWon()); // 24h 저가
							find_obj.find('#volume24h-'+key).html((dataCur[key].volume24h)); // 24h 거래량
                        } 
					}
                    
                    var thtml = '';
                    var cointype = '';
                    for(key in dataCur){
                        cointype = key.replace("krw_","");
                        thtml += '<li><a href="/trade/order/krw-'+cointype+'">'+cointype.toUpperCase()+' ' +(dataCur[key].last).formatWon()+ ' '+langConvert('lang.currentCurrencyOrignLang', '')+ '</a></li>'
                        
                    }
                    $('#header .prices').html( thtml );

                    try{
                        if(typeof onTickerEvent === 'function'){
                            onTickerEvent(jsondata);
                        }
                    }catch(e){
                        console.log(e);
                    }
                    preTickerData = jsondata; //이전 데이터를 저장
                }

                function onChannelTickerIni(jticker,buy,sell,complete, marketdeps){
                    
                    
                    if(socket_channel && socket_channel!=''){
//                        console.log('==================onChannelTickerIni==================== : ' + jticker);
                        onChannelTicker(jticker);
                        onChannelTradeRegist(buy,sell,marketdeps);
                        onChannelTradeComplete(complete);
                        
                    }
                    
                }
                

                socket.on('ticker',onChannelTicker);
                socket.on('tickerini',onChannelTickerIni);
                // 거래소 > 트레이드 발생시
                socket.on('traderegist',onChannelTradeRegist);
                // 거래소 > 체결 완료시
                socket.on('tradecomplete',onChannelTradeComplete);
                // 거래소 > 체결 완료시(내주문)
                socket.on('mytradecomplete',onChannelMyTradeComplete);
                socket.on('mybalance',onMyBalanceChange);
                

                socket.on('disconnect',function(){console.log('disconnect');});
                window.onbeforeunload = function(event){
                    if(typeof event == 'undefined'){
                       event = window.event;
                    }
                    if(event){
                       SendEvent.sendLeave();
                    }
                };
                
                

                var tradeSignMessage = function(msg){
                    var message = langConvert('lang.msgYourOrderCompleted','');
                    if(msg=='OK') message = langConvert('lang.msgYourOrderHasBeenSigned','');
                    else if(msg=='PART') message = langConvert('lang.msgYourOrderHasBeenSignedPart','');

                    $.notify({message: '  '+message+' ' },{
                        type: 'success',
                        z_index:7501,
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        offset: {
                            x: 12,
                            y: 80
                        }
                    });
                    initBalance();
                };


            }catch(e){
                controllerComm.alertError('<p>'+langConvert('lang.msgTickerServerAccessFailed','')+'</p><p>'+langConvert('lang.msgServerMaintenanceOrServerErrorPleaseTryAgainLater','')+'</p>');
            }
        }
    }
};

var SendEvent = {
    sendTradeRegist:function(){
        socket.emit('trade', 'traderegist');
    },
    sendTradeComplete:function(){
        socket.emit('trade', 'tradecomplete');
    },
    sendLeave:function(){
        socket.emit('leave');
    }
};



var Utils = {
    getCookie:function(key)
    {
        var i,x,y,ARRcookies=document.cookie.split(";");
        for (i=0;i<ARRcookies.length;i++)
        {
          x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
          y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
          x=x.replace(/^\s+|\s+$/g,"");
          if (x == key)
            {
                var someValue = unescape(y);
                var base64Matcher = new RegExp("^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{4})$");
                if (base64Matcher.test(someValue)) {
                    return $.base64.decode(unescape(y));
                } else {
                    return unescape(y);
                }

            }
        }
        return null;
    },
    setCookie:function(key,value,exdays)
    {
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value=escape($.base64.encode(value)) + ((exdays==null) ? "" : ";expires="+exdate.toUTCString());
        document.cookie=key + "=" + c_value +';path=/;';
    },
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name) {
        var resStr = $.getUrlVars()[name];
        if(resStr){
            resStr = decodeURI(resStr);
            resStr = resStr.replace(/\+/g," ");
        }
        return resStr;
    },
    getTimeStamp: function(){
        var date = Math.floor(new Date().getTime() / 1000);
        return date;
    },
    getLanguage: function(){
        var lang = Utils.getCookie('Language');
        return lang;
    },
    setLanguage: function(nationValue){
        var language = Utils.getCookie('Language');
        if(!lang || language=='kr') language = 'ko';
        if(language != nationValue){
            Utils.setCookie('Language', nationValue, 30);
            document.location.reload();
        }
    },
    defaultSetCookieCountry: function(){
        var lang = Utils.getCookie('Language');
        if(!lang || lang =='kr'){
            Utils.setCookie('Language','ko',30);
        }
    }
}

var Account = {
    isLogined:function(callback){
        if(Utils.getCookie(Config.ssid)){
            if (typeof callback === "function") callback(true);
        }else{
            if (typeof callback === "function") callback(false);
        }
    },
    getInfo:function(callback){
        if (typeof callback === "function") callback(Utils.getCookie(Config.account_key));
    }
};

function convertUpDownTag(arrayValue, price){
    var flagText = '';
    if(typeof arrayValue=='undeinfed' || typeof price=='undeinfed'){
        flagText = '';
    }else if(parseInt(arrayValue) == parseInt(price) ){
        flagText = '';
    }else if( parseInt(arrayValue) > parseInt(price) ){
        flagText = '&#9660';
    }else if( parseInt(arrayValue) < parseInt(price) ){
        flagText = '&#9650';
    }
    return flagText;
}

var emptyFormData = false;
var errorMsg = '';
var controllerComm = {
    /*
     *
     * @param {type} v
     * @param {type} callback
     * @returns {undefined}
     */
    alertError:function(v,fncallback){
        var box = bootbox.dialog({
            closeButton: false,
            message: v,
            title: langConvert('lang.modalNotify',''),
            buttons: {
              main: {
                label: langConvert('lang.modalConfirm',''),
                className: "btn-primary",
                callback: function(result) {
                    $(document).keypress(function(e){
                        if(e.keyCode==27 || e.keyCode==32 || e.keyCode==13 ){
                            $(".modal").modal('hide');
                        }
                    });
                    if(typeof fncallback =='function'){
                        $(".modal").modal('hide');
                        fncallback();
                    }
                }

              }
            }
          });
    },
    alerConfirmDialog:function(v,callfunc){

    },
    nprogressStart:function(){
        NProgress.start();
        setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 1000);

    },
    nprogressSet:function(per){
        //0.0 ~ 1
        NProgress.set(per);
    },
    nprogressDone:function(){
        NProgress.done(); $('.fade').removeClass('out');
    },
    loading:function(msg){

        var divid = '#loading-progress';
        var html = '';
        html += '<div id="loading-progress" class="ui-loading"></div>';
        if($(divid).length<1){
            $('body').append(html);
        }
        if(msg) $(divid).append('<p>'+msg+'</p>');

        var windowWidth = document.documentElement.clientWidth;
        var windowHeight = document.documentElement.clientHeight;
        var popupHeight = $(divid).height();
        var popupWidth = $(divid).width();

        $(divid).css({
                "position": "absolute",
                "top": ($(window).scrollTop()+(windowHeight/2))-(popupHeight/2),
                "left": windowWidth/2-popupWidth/2
        });
        $(divid).show();

    },
    loadingClose:function(){
        $('#loading-progress').hide();
    },

    showUpdateError:function (code,errorElementId,msg){

    var addMsg = '';
	if(msg) addMsg = '<p>'+msg+'</p>';
    switch(code){
        case -90:
            controllerComm.alertError('<p>'+langConvert('lang.msgDataDoesNotExist','')+'</p>'+addMsg);
        break;
        case -101:
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseDefineInputData','')+'</p>'+addMsg);
        break;
        case -180:
            controllerComm.alertError('<p>'+langConvert('lang.msgRequestedInformationMustBeFilledOut','')+'</p>'+addMsg);
        break;
        case -210:
            controllerComm.alertError('<p>'+langConvert('lang.msgAccessDenied','')+'</p>'+addMsg,reload);
        break;
        case -290:
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>'+addMsg,reload);
        break;
        case -900:
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>'+addMsg,reload);
        break;
        case -910:
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseDoNotInputDataTooFrequently','')+'</p><p>'+langConvert('lang.msgTryAgainLater','')+'</p>'+addMsg,reload);
        break;
        case -930:
            controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectAccessPath','')+'</p>'+addMsg,historyBack);
        break;
        case -940:
            controllerComm.alertError('<p>'+langConvert('lang.msgPleaseEnsureTheAvailability','')+'</p>'+addMsg);
        break;
        case -950:
            controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectAntiAutoRegistrionMessage','')+'</p>'+addMsg);
        break;
        case -951:
            controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectPassword','')+'</p>'+addMsg);
        break;
        case -990:
            controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectInputDataFormat','')+'</p>'+addMsg);
        break;
        default:
            controllerComm.alertError('<p>'+langConvert('lang.msgExceptionsoccurred','')+'</p><p>Error . '+code+' / '+errorElementId+'</p>'+addMsg,reload);
        break;
    }
}
};

/*
 * 공통함수
 */
function reload(){
    document.location.reload();
}

function initBalanceSum(){
    _initBalanceSum();
}

function initBalance(data){
    if(typeof data !== 'undefined'){
        _initBalanceSum('y', data);
    }else{
        _initBalanceSum('y');
    }
}

function _update_buy_sell_price_sum(){
    var t = Utils.getTimeStamp();
    var updateBuySellUrl = "/webmember/update_buy_sell/?t="+t;
    
    $.getJSON(updateBuySellUrl, "json", function (j){
       })
       .done( function(data){
            console.log('_update_buy_sell_price_sum result :' + data.success);
       })
       .fail( function(){
         console.log('lang._update_buy_sell_price_sum');
    });
}

function _initBalanceSum(isMem, data){

    if(typeof data === 'undefined'){
        var dataCur = preTickerData.master;
    }else{
        var dataCur = data.master;
    }
    
    var t = Utils.getTimeStamp();
    var balanceUrl = '';

    if(typeof isMem === 'undefined'){
        balanceUrl = "/getbalance/trade/cmd-sum/?t="+t;
    }else{
        balanceUrl = "/getbalance/trade/ismem-y/?t="+t;
    }
    //left balance alt
    $.getJSON(balanceUrl, "json", function (j){
        })
        .done( function(data){
            if(data && data.hasOwnProperty('balance')){
                //if(data.hasOwnProperty('asset_info'))
                var asset_info = $.parseJSON($.base64.decode(data.asset_info));
                //console.log("asset_info :"+ asset_info);
                var balance = $.parseJSON($.base64.decode(data.balance));
                //console.log("balance :"+ balance);
                var balanceBarData = new Array();
                var _total, _ontrade, _onetc, _onlend, _assets =0;
                
                
                //var asset_info = $.parseJSON($.base64.decode(data.asset_info));
               
                for(var i= 0, length = balance.length; i < length; i++)
                {
                    _total   =  Number(balance[i]["total"]);
                    _ontrade =  Number(balance[i]["on_trade"]);
                    _onetc   =  Number(balance[i]["on_etc"]);
                    _onlend  =  Number(balance[i]["on_lend"]);

                    if(_total < 0)
                        _total = 0;

                    if(_ontrade < 0)
                        _ontrade = 0;

                    if(_onetc < 0)
                        _onetc = 0;

                    if(_onlend < 0)
                        _onlend = 0;

                    balance[i]["total"]      = String(_total.toFixed(8));
                    balance[i]["on_trade"]   = String(_ontrade.toFixed(8));
                    balance[i]["on_etc"]     = String(_onetc.toFixed(8));
                    balance[i]["on_lend"]    = String(_onlend.toFixed(8));

                    /*
                    console.log("=======================================");
                    console.log(asset_info);
                    console.log("=======================================");
                    */

                    if(balance[i]["po_type"] == "krw"){
                        var kwr_info  = asset_info["krw"];
                        // 총 매수금액
                        $(".mb_krw_total_buy_price").html((kwr_info["total_buy_price"]).formatWon());
                        // 총 평가금액
                        $(".mb_krw_total_evaluation_price").html((kwr_info["total_evaluation_price"]).formatWon());
                        // 총평가손익
                        if(kwr_info["total_profit"] != null) {
                            $(".mb_krw_total_profit").html((kwr_info["total_profit"]).formatWon());
                        }
                        // 총평가수익률
                        $(".mb_krw_total_profit_rate").html((kwr_info["total_profit_rate"]).formatWon());
                        // 보유 KRW
                        $(".mb_krw_total").html((kwr_info["total"]).formatWon());
                        // 총 보유자산
                        $(".mb_krw_total_asset_held").html((kwr_info["total_asset_held"]).formatWon());

                       // console.log("mb_krw_total_buy_price : " + kwr_info["total_buy_price"]);
                                              
                        $(".mb_" + balance[i]["po_type"] + "_total").html((balance[i]["total"]).formatWon());
                        $(".mb_" + balance[i]["po_type"] + "_poss").html((balance[i]["total"] - balance[i]["on_trade"] - balance[i]["on_etc"]).formatWon());
                       
                        
                        _assets += parseInt(balance[i]["total"]);
                    }else{
                        var coin_info  = asset_info[balance[i]["po_type"]];
                        $(".mb_" + balance[i]["po_type"] + "_total").html((balance[i]["total"]).formatBitcoin());
                        $(".mb_" + balance[i]["po_type"] + "_poss").html((balance[i]["total"] - balance[i]["on_trade"] - balance[i]["on_etc"]).formatBitcoin());
                        if(typeof coin_info !== "undefined") {
                            // 매수평균가 average_buy_price
                            $(".mb_" + balance[i]["po_type"] + "_average_buy_price").html((Math.floor(coin_info["average_buy_price"])).formatWon());
                            // 매수금액 po_point
                            $(".mb_" + balance[i]["po_type"] + "_po_point").html((Math.floor(coin_info["po_point"])).formatWon());
                            // 평가금액 evaluation_price
                            $(".mb_" + balance[i]["po_type"] + "_evaluation_price").html((Math.floor(coin_info["evaluation_price"])).formatWon());
                            // 평가손익(%) profit_rate
                            var profit_rate = (coin_info["profit_rate"]).formatBitcoin();
                            profit_rate = Number(profit_rate).toFixed(1);
                            // 미체결 수 uncompletecount
                            $(".mb_" + balance[i]["po_type"] + "_uncompletecount").html((coin_info["uncomplete_count"]).formatWon());
                            console.log('미체결 카운트 : ' + coin_info["uncomplete_count"]);
                            console.log('coin_info : ' + coin_info["evaluation_price"]);
                            var _profit_rate_calc = Math.floor(coin_info["evaluation_price"]) - Math.floor(coin_info["po_point"]);
                            $(".mb_" + balance[i]["po_type"] + "_profit_rate").html(profit_rate);
                            $(".mb_" + balance[i]["po_type"] + "_profit_rate_calc").html(_profit_rate_calc.formatWon());
                            if(profit_rate > 0) {
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate").addClass('red');
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate_calc").parent().addClass('red');
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate_calc").parent().find(".krw").addClass('red');
                            } else if(profit_rate < 0) {
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate").addClass('blue');
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate_calc").parent().addClass('blue');
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate_calc").parent().find(".krw").addClass('blue');
                            } else {
                                $(".mb_" + balance[i]["po_type"] + "_profit_rate_calc").parent().find(".krw").addClass('color-grey');
                            }
                        } else {
                            //console.log("average_buy_price : " + coin_info["average_buy_price"]);
                        }
                        //$(".mb_" + balance[i]["po_type"] + "_average_buy_price").html((coin_info["average_buy_price"]).formatBitcoin());
                         
                    }
                    
                    //console.log(balance[i]);
                    balanceBarData[i] = new Array();
                    if(balance[i]["po_type"] == "krw"){
                        balanceBarData[i]["po_type"] = "krw";
                        balanceBarData[i]["price"] = balance[i]["total"];
                    }
                    for (key in dataCur) {
                        var cprice = parseInt(dataCur[key]['last']) * parseFloat(balance[i]["total"]);
                        
                        if('krw_'+balance[i]["po_type"] == key){
                            // console.log(balance[i]["po_type"]);
                            _assets += cprice;
                            $(".mb_" + balance[i]["po_type"] + "_exchange_total").html(cprice.formatWon());
                            
                            balanceBarData[i]["po_type"] = balance[i]["po_type"];
                            balanceBarData[i]["price"] = cprice;
                        }
                    }
                }
                $(".total-assets").html( _assets.formatWon() );
                //=============================================================================================//
                            
                get_member.balance = balance;

                if(typeof onWriteChangeMyBalance === 'function'){
                    onWriteChangeMyBalance(balance);
                }
                if(typeof onChangeBalanceBar === 'function'){
                    onChangeBalanceBar(_assets, balanceBarData);
                }
            }
         
        })
        .fail( function(){
            console.log('lang.msgFailedToGetAccountBalanceInformation');
//            controllerComm.alertError('<p>'+langConvert('lang.msgFailedToGetAccountBalanceInformation','')+'</p><p>'+langConvert('lang.msgErrorRepeatedPleaseReLogin','')+'</p>', function(){
//            });
    });
}
/*function initBalance(){
    var t = Utils.getTimeStamp();
    //left balance alt
    $.getJSON("/getbalance/trade/?t="+t, "json", function (j){
        })
        .done( function(data){
            if(data && data.hasOwnProperty('balance')){
                var balance = $.parseJSON($.base64.decode(data.balance));
                get_member.balance = balance;
                if(typeof onWriteChangeMyBalance === 'function'){
                    onWriteChangeMyBalance(balance);
                }
            }
        })
        .fail( function(){
//            controllerComm.alertError('<p>'+langConvert('lang.msgFailedToGetAccountBalanceInformation','')+'</p><p>'+langConvert('lang.msgErrorRepeatedPleaseReLogin','')+'</p>', function(){
//            });
    });
}*/


var controllerNoty = {
    setCallBack:function(callback){
        if (typeof callback === "function")
            callback();
    },
    top:function(html,type,timeout){
        if(!type) type = 'warning';
        if(!timeout) timeout = 4000;
        var n = noty({
                text: html,
                type: type,
                dismissQueue: false,
                layout: 'top',
                theme: 'defaultTheme',
                timeout:timeout
        });
    },
    topCenter:function(html,type,timeout){
        if(!type) type = 'warning';
        if(!timeout) timeout = 5000;
        var n = noty({
                text: html,
                type: type,
                dismissQueue: false,
                layout: 'topCenter',
                theme: 'defaultTheme',
                timeout:timeout
        });
    },
    topRight:function(html,type,timeout){
        if(!type) type = 'warning';
        if(!timeout) timeout = 5000;
        var n = noty({
                text: html,
                type: type,
                dismissQueue: false,
                layout: 'topRight',
                theme: 'defaultTheme',
                timeout:timeout
        });
    }
};

$(document).ready(function(){

//    $(".dialog-close").click(function(){
//        closeDialog();
//    });
    //Click out event!
//    $(".dialog-background").click(function(){
//        closeDialog();
//    });


    //브라우저체크
    if(navigator.appName.indexOf("Internet Explorer")!=-1){     //yeah, he's using IE
        var badBrowser=(
            navigator.appVersion.indexOf("MSIE 1")==-1  //v10, 11, 12, etc. is fine too
        );

        if(badBrowser){
            alert(langConvert('lang.msgServiceNotSupportBrowser','')+"\n\n"+langConvert('lang.msgPleaseTryAnotherBrowserOrUpdateToLastestVersion',''));
            NProgress.set(per);
        }else{

        }
    }


});


/*
 *
 * noty
 */
!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t("object"==typeof exports?require("jquery"):jQuery)}(function(t){function s(s){var e=!1;return t('[data-notify="container"]').each(function(i,n){var a=t(n),o=a.find('[data-notify="title"]').text().trim(),r=a.find('[data-notify="message"]').html().trim(),l=o===t("<div>"+s.settings.content.title+"</div>").html().trim(),d=r===t("<div>"+s.settings.content.message+"</div>").html().trim(),g=a.hasClass("alert-"+s.settings.type);return l&&d&&g&&(e=!0),!e}),e}function e(e,n,a){var o={content:{message:"object"==typeof n?n.message:n,title:n.title?n.title:"",icon:n.icon?n.icon:"",url:n.url?n.url:"#",target:n.target?n.target:"-"}};a=t.extend(!0,{},o,a),this.settings=t.extend(!0,{},i,a),this._defaults=i,"-"===this.settings.content.target&&(this.settings.content.target=this.settings.url_target),this.animations={start:"webkitAnimationStart oanimationstart MSAnimationStart animationstart",end:"webkitAnimationEnd oanimationend MSAnimationEnd animationend"},"number"==typeof this.settings.offset&&(this.settings.offset={x:this.settings.offset,y:this.settings.offset}),(this.settings.allow_duplicates||!this.settings.allow_duplicates&&!s(this))&&this.init()}var i={element:"body",position:null,type:"info",allow_dismiss:!0,allow_duplicates:!0,newest_on_top:!1,showProgressbar:!1,placement:{from:"top",align:"right"},offset:20,spacing:10,z_index:1031,delay:5e3,timer:1e3,url_target:"_blank",mouse_over:null,animate:{enter:"animated fadeInDown",exit:"animated fadeOutUp"},onShow:null,onShown:null,onClose:null,onClosed:null,icon_type:"class",template:'<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'};String.format=function(){for(var t=arguments[0],s=1;s<arguments.length;s++)t=t.replace(RegExp("\\{"+(s-1)+"\\}","gm"),arguments[s]);return t},t.extend(e.prototype,{init:function(){var t=this;this.buildNotify(),this.settings.content.icon&&this.setIcon(),"#"!=this.settings.content.url&&this.styleURL(),this.styleDismiss(),this.placement(),this.bind(),this.notify={$ele:this.$ele,update:function(s,e){var i={};"string"==typeof s?i[s]=e:i=s;for(var n in i)switch(n){case"type":this.$ele.removeClass("alert-"+t.settings.type),this.$ele.find('[data-notify="progressbar"] > .progress-bar').removeClass("progress-bar-"+t.settings.type),t.settings.type=i[n],this.$ele.addClass("alert-"+i[n]).find('[data-notify="progressbar"] > .progress-bar').addClass("progress-bar-"+i[n]);break;case"icon":var a=this.$ele.find('[data-notify="icon"]');"class"===t.settings.icon_type.toLowerCase()?a.removeClass(t.settings.content.icon).addClass(i[n]):(a.is("img")||a.find("img"),a.attr("src",i[n]));break;case"progress":var o=t.settings.delay-t.settings.delay*(i[n]/100);this.$ele.data("notify-delay",o),this.$ele.find('[data-notify="progressbar"] > div').attr("aria-valuenow",i[n]).css("width",i[n]+"%");break;case"url":this.$ele.find('[data-notify="url"]').attr("href",i[n]);break;case"target":this.$ele.find('[data-notify="url"]').attr("target",i[n]);break;default:this.$ele.find('[data-notify="'+n+'"]').html(i[n])}var r=this.$ele.outerHeight()+parseInt(t.settings.spacing)+parseInt(t.settings.offset.y);t.reposition(r)},close:function(){t.close()}}},buildNotify:function(){var s=this.settings.content;this.$ele=t(String.format(this.settings.template,this.settings.type,s.title,s.message,s.url,s.target)),this.$ele.attr("data-notify-position",this.settings.placement.from+"-"+this.settings.placement.align),this.settings.allow_dismiss||this.$ele.find('[data-notify="dismiss"]').css("display","none"),(this.settings.delay>0||this.settings.showProgressbar)&&this.settings.showProgressbar||this.$ele.find('[data-notify="progressbar"]').remove()},setIcon:function(){"class"===this.settings.icon_type.toLowerCase()?this.$ele.find('[data-notify="icon"]').addClass(this.settings.content.icon):this.$ele.find('[data-notify="icon"]').is("img")?this.$ele.find('[data-notify="icon"]').attr("src",this.settings.content.icon):this.$ele.find('[data-notify="icon"]').append('<img src="'+this.settings.content.icon+'" alt="Notify Icon" />')},styleDismiss:function(){this.$ele.find('[data-notify="dismiss"]').css({position:"absolute",right:"10px",top:"5px",zIndex:this.settings.z_index+2})},styleURL:function(){this.$ele.find('[data-notify="url"]').css({backgroundImage:"url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)",height:"100%",left:0,position:"absolute",top:0,width:"100%",zIndex:this.settings.z_index+1})},placement:function(){var s=this,e=this.settings.offset.y,i={display:"inline-block",margin:"0px auto",position:this.settings.position?this.settings.position:"body"===this.settings.element?"fixed":"absolute",transition:"all .5s ease-in-out",zIndex:this.settings.z_index},n=!1,a=this.settings;switch(t('[data-notify-position="'+this.settings.placement.from+"-"+this.settings.placement.align+'"]:not([data-closing="true"])').each(function(){e=Math.max(e,parseInt(t(this).css(a.placement.from))+parseInt(t(this).outerHeight())+parseInt(a.spacing))}),this.settings.newest_on_top===!0&&(e=this.settings.offset.y),i[this.settings.placement.from]=e+"px",this.settings.placement.align){case"left":case"right":i[this.settings.placement.align]=this.settings.offset.x+"px";break;case"center":i.left=0,i.right=0}this.$ele.css(i).addClass(this.settings.animate.enter),t.each(["webkit-","moz-","o-","ms-",""],function(t,e){s.$ele[0].style[e+"AnimationIterationCount"]=1}),t(this.settings.element).append(this.$ele),this.settings.newest_on_top===!0&&(e=parseInt(e)+parseInt(this.settings.spacing)+this.$ele.outerHeight(),this.reposition(e)),t.isFunction(s.settings.onShow)&&s.settings.onShow.call(this.$ele),this.$ele.one(this.animations.start,function(){n=!0}).one(this.animations.end,function(){t.isFunction(s.settings.onShown)&&s.settings.onShown.call(this)}),setTimeout(function(){n||t.isFunction(s.settings.onShown)&&s.settings.onShown.call(this)},600)},bind:function(){var s=this;if(this.$ele.find('[data-notify="dismiss"]').on("click",function(){s.close()}),this.$ele.mouseover(function(){t(this).data("data-hover","true")}).mouseout(function(){t(this).data("data-hover","false")}),this.$ele.data("data-hover","false"),this.settings.delay>0){s.$ele.data("notify-delay",s.settings.delay);var e=setInterval(function(){var t=parseInt(s.$ele.data("notify-delay"))-s.settings.timer;if("false"===s.$ele.data("data-hover")&&"pause"===s.settings.mouse_over||"pause"!=s.settings.mouse_over){var i=(s.settings.delay-t)/s.settings.delay*100;s.$ele.data("notify-delay",t),s.$ele.find('[data-notify="progressbar"] > div').attr("aria-valuenow",i).css("width",i+"%")}t>-s.settings.timer||(clearInterval(e),s.close())},s.settings.timer)}},close:function(){var s=this,e=parseInt(this.$ele.css(this.settings.placement.from)),i=!1;this.$ele.data("closing","true").addClass(this.settings.animate.exit),s.reposition(e),t.isFunction(s.settings.onClose)&&s.settings.onClose.call(this.$ele),this.$ele.one(this.animations.start,function(){i=!0}).one(this.animations.end,function(){t(this).remove(),t.isFunction(s.settings.onClosed)&&s.settings.onClosed.call(this)}),setTimeout(function(){i||(s.$ele.remove(),s.settings.onClosed&&s.settings.onClosed(s.$ele))},600)},reposition:function(s){var e=this,i='[data-notify-position="'+this.settings.placement.from+"-"+this.settings.placement.align+'"]:not([data-closing="true"])',n=this.$ele.nextAll(i);this.settings.newest_on_top===!0&&(n=this.$ele.prevAll(i)),n.each(function(){t(this).css(e.settings.placement.from,s),s=parseInt(s)+parseInt(e.settings.spacing)+t(this).outerHeight()})}}),t.notify=function(t,s){var i=new e(this,t,s);return i.notify},t.notifyDefaults=function(s){return i=t.extend(!0,{},i,s)},t.notifyClose=function(s){void 0===s||"all"===s?t("[data-notify]").find('[data-notify="dismiss"]').trigger("click"):t('[data-notify-position="'+s+'"]').find('[data-notify="dismiss"]').trigger("click")}});

/* NProgress, (c) 2013, 2014 Rico Sta. Cruz - http://ricostacruz.com/nprogress
 * @license MIT */
(function(a,b){if(typeof define==="function"&&define.amd){define(b)}else{if(typeof exports==="object"){module.exports=b()}else{a.NProgress=b()}}})(this,function(){var e={};e.version="0.2.0";var b=e.settings={minimum:0.08,easing:"ease",positionUsing:"",speed:200,trickle:true,trickleRate:0.02,trickleSpeed:800,showSpinner:true,barSelector:'[role="bar"]',spinnerSelector:'[role="spinner"]',parent:"body",template:'<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'};e.configure=function(m){var n,o;for(n in m){o=m[n];if(o!==undefined&&m.hasOwnProperty(n)){b[n]=o}}return this};e.status=null;e.set=function(s){var m=e.isStarted();s=g(s,b.minimum,1);e.status=(s===1?null:s);var o=e.render(!m),p=o.querySelector(b.barSelector),q=b.speed,r=b.easing;o.offsetWidth;i(function(n){if(b.positionUsing===""){b.positionUsing=e.getPositioningCSS()}h(p,a(s,q,r));if(s===1){h(o,{transition:"none",opacity:1});o.offsetWidth;setTimeout(function(){h(o,{transition:"all "+q+"ms linear",opacity:0});setTimeout(function(){e.remove();n()},q)},q)}else{setTimeout(n,q)}});return this};e.isStarted=function(){return typeof e.status==="number"};e.start=function(){if(!e.status){e.set(0)}var m=function(){setTimeout(function(){if(!e.status){return}e.trickle();m()},b.trickleSpeed)};if(b.trickle){m()}return this};e.done=function(m){if(!m&&!e.status){return this}return e.inc(0.3+0.5*Math.random()).set(1)};e.inc=function(m){var o=e.status;if(!o){return e.start()}else{if(typeof m!=="number"){m=(1-o)*g(Math.random()*o,0.1,0.95)}o=g(o+m,0,0.994);return e.set(o)}};e.trickle=function(){return e.inc(Math.random()*b.trickleRate)};(function(){var m=0,n=0;e.promise=function(o){if(!o||o.state()==="resolved"){return this}if(n===0){e.start()}m++;n++;o.always(function(){n--;if(n===0){m=0;e.done()}else{e.set((m-n)/m)}});return this}})();e.render=function(m){if(e.isRendered()){return document.getElementById("nprogress")}j(document.documentElement,"nprogress-busy");var n=document.createElement("div");n.id="nprogress";n.innerHTML=b.template;var q=n.querySelector(b.barSelector),o=m?"-100":c(e.status||0),p=document.querySelector(b.parent),r;h(q,{transition:"all 0 linear",transform:"translate3d("+o+"%,0,0)"});if(!b.showSpinner){r=n.querySelector(b.spinnerSelector);r&&k(r)}if(p!=document.body){j(p,"nprogress-custom-parent")}p.appendChild(n);return n};e.remove=function(){l(document.documentElement,"nprogress-busy");l(document.querySelector(b.parent),"nprogress-custom-parent");var m=document.getElementById("nprogress");m&&k(m)};e.isRendered=function(){return !!document.getElementById("nprogress")};e.getPositioningCSS=function(){var m=document.body.style;var n=("WebkitTransform" in m)?"Webkit":("MozTransform" in m)?"Moz":("msTransform" in m)?"ms":("OTransform" in m)?"O":"";if(n+"Perspective" in m){return"translate3d"}else{if(n+"Transform" in m){return"translate"}else{return"margin"}}};function g(p,o,m){if(p<o){return o}if(p>m){return m}return p}function c(m){return(-1+m)*100}function a(q,o,p){var m;if(b.positionUsing==="translate3d"){m={transform:"translate3d("+c(q)+"%,0,0)"}}else{if(b.positionUsing==="translate"){m={transform:"translate("+c(q)+"%,0)"}}else{m={"margin-left":c(q)+"%"}}}m.transition="all "+o+"ms "+p;return m}var i=(function(){var n=[];function m(){var o=n.shift();if(o){o(m)}}return function(o){n.push(o);if(n.length==1){m()}}})();var h=(function(){var m=["Webkit","O","Moz","ms"],r={};function o(s){return s.replace(/^-ms-/,"ms-").replace(/-([\da-z])/gi,function(t,u){return u.toUpperCase()})}function q(s){var u=document.body.style;if(s in u){return s}var t=m.length,w=s.charAt(0).toUpperCase()+s.slice(1),v;while(t--){v=m[t]+w;if(v in u){return v}}return s}function p(s){s=o(s);return r[s]||(r[s]=q(s))}function n(s,u,t){u=p(u);s.style[u]=t}return function(u,t){var s=arguments,w,v;if(s.length==2){for(w in t){v=t[w];if(v!==undefined&&t.hasOwnProperty(w)){n(u,w,v)}}}else{n(u,s[1],s[2])}}})();function f(n,m){var o=typeof n=="string"?n:d(n);return o.indexOf(" "+m+" ")>=0}function j(n,m){var p=d(n),o=p+m;if(f(p,m)){return}n.className=o.substring(1)}function l(n,m){var p=d(n),o;if(!f(n,m)){return}o=p.replace(" "+m+" "," ");n.className=o.substring(1,o.length-1)}function d(m){return(" "+(m.className||"")+" ").replace(/\s+/gi," ")}function k(m){m&&m.parentNode&&m.parentNode.removeChild(m)}return e});


$(function(){
    

/* Mobile Navigation */
	$('.btn-m-nav').click(function(){
		$('body').toggleClass('fixed');
		return false;	
	});
	
	$('#m-nav .nav .dep1').click(function(){

		if($(this).closest('div').hasClass('active')){
			$(this).closest('div').removeClass('active').find('ul').slideUp(300);
		}else{
			$(this).closest('div').addClass('active').find('ul').slideDown(300);
			$(this).closest('div').siblings().removeClass('active').find('ul').slideUp(300);
		}
	});
	$('#m-nav .btn-close, .bg-gnb').click(function(){
		$('body').removeClass('fixed');
		return false;
	});
        
        $('.user-mine .btn-flip').click(function(){
           $('.user-mine').toggleClass('active');
           if($('.user-mine').hasClass('active')){
               $('.user-mine .list').stop().slideDown(300);
           }else{
               $('.user-mine .list').stop().slideUp(300);
           }
        });
});