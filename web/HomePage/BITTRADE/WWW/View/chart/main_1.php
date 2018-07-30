<head>
    <link href="<?= $view['url']['static'] ?>/assets/css/style.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/utils.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/controller-comm.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/controller-form.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="../../charting_library/charting_library.min.js"></script>
    <script src="../../charting_library/datafeeds/udf/dist/polyfills.js"></script>
    <script src="../../charting_library/datafeeds/udf/dist/bundle.js"></script>
	<script src="//chbtc.devpopcon.com:9090/socket.io/socket.io.js"></script>
    <script type="text/javascript">

	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	function Unix2Date(timestamp)
	{
		var d = new Date(timestamp);
		timeStampCon = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + " " + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
		return timeStampCon;
	}
	
	var tradingview_resolutions = [
		// "1S","5S","15S","30S","1","5","15","30","60","120","240", "360", "720", "D", "3D", "W", "M"
		'1','5','15','30','60','240','1D'
	];
	
	var tradingview_config = {
		exchanges: [],
		symbolsTypes: [],
		supported_resolutions: tradingview_resolutions,
		supports_time: true,
		supports_marks: true, // TODO: find out what this is
		supports_timescale_marks: true, // TODO: find out what this is
		supports_search: true,
		supports_group_request: false
	};

	// 트레이딩 뷰의 설정
	// 트레이딩뷰를 구성하는 컴포넌트들을 그림으로 보여줌(http://tradingview.github.io/featuresets.html)
	// symbolInfo 구조체 정보(https://github.com/tradingview/charting_library/wiki/Symbology#symbolinfo-structure)
	var tradingview_resolve_symbolinfo = {
		name: "BTC", // 심볼이름
		type: "bitcoin", // 차트타입
		session: "24x7", // 세션유효기간
		exchange: "",
		//listed_exchange: "",
		timezone: "Asia/Seoul",
		minmov: 1,
		pricescale: 1, // 데이타값을 나누는 값
		minmov2: 4,
		pointvalue: 1,
		supported_resolutions: tradingview_resolutions, // 해상도(header_resolutions)
		has_daily: true, // 1분 단위 해상도만 만듬
		has_intraday: true, // 분 단위 해상도 지원여부(좌측상단 header_resolutions)
		intraday_multipliers: ["1"], // ? 뭔지 모르겠다. 1로 하면 되는 것 같음.
		has_seconds: false, // 초단위 해상도를 선택할 수 있는지 여부
		//seconds_multipliers: [1], // ? 뭔지 모르겠다. 1로 하면 되는 것 같음.
		has_empty_bars: true, // 데이타피드가 없어도 시간에 따른 공백 보여줌
		has_no_volume: false, // 거래량 차트 보여줌
		description: "",
		ticker: "BTC-Ticker",
		currency_code: "KRW",
		data_status: "streaming"
	};
	
	var symbol_database = {};
	symbol_database['BTC'] = {
		symbol: "BTC",
		full_name: "BTC/KRW", // e.g., BTCE:BTCUSD
		description: "",
		exchange: "popcon-exchange",
		ticker: "BTC-Ticker",
		type: "bitcoin"
	};
	
	var socket_object;
	var is_join = 0;
	
	// 웹 소켓
	var realtime_socket = {
		access_token_key: "d86c828583c5c6160e8acfee88ba1590",
		refresh_token_key: "e268443e43d93dab7ebef303bbe9642f",
		account_key: "e268443e43d93dab7ebef303bbe9642f",
		ssid: "c26e8178126688deb863604bef4b0cda",
		connkey: "817eebc5b49c13f8e6a0a7d159a49c09",
		url: "",
		channel: "",
		websocket_url: "",

		connectServer: function(onTradeCompleteCallback) {
			// var ssid = Utils.getCookie(this.ssid);
			// var apikey = Utils.getCookie(this.connkey);
			//this.websocket_url = "//chbtc.devpopcon.com:9090/channel?apikey=aac51d760070a27c5db8824e5c8229f7&ssid=MB1-5f5c4c83a05aa32cbdfba45da7664262";
			websocket_url = "//chbtc.devpopcon.com:9090/channel?apikey=aac51d760070a27c5db8824e5c8229f7&ssid=MB1-5f5c4c83a05aa32cbdfba45da7664262";
			                 //chbtc.devpopcon.com:9090/channel?apikey=aac51d760070a27c5db8824e5c8229f7&ssid=MB1-5f5c4c83a05aa32cbdfba45da7664262
			channel = "krw_btc";
			if (is_join == 1)
				return;
			
			socket_object = io.connect(websocket_url);
			socket_object.on('connect', function () {
				socket_object.emit('join', 'krw_btc', function (err) {
					if (err) {
						console.log('connect failed: ' + err);
					} else {
						is_join = 1;
						console.log('connect success');
						
						socket_object.on('tradecomplete', function(data) {
							// console.log("tradecomplete: " + data);
							onTradeCompleteCallback(data);
						});
						socket_object.on('ticker', function(data) {
							// console.log("ticker: " + data);
						});
						socket_object.on('tickerini', function(jticker,buy,sell,complete, marketdeps) {
							console.log("tickerini");
						});
						socket_object.on('traderegist', function(data,data2,marketdeps) {
							//console.log("traderegist");
						});
						socket_object.on('mytradecomplete',function(data) {
							console.log("mytradecomplete: " + data);
						});
						socket_object.on('mybalance',function(data) {
							console.log("mybalance: " + data);
						});
						socket_object.on('disconnect',function(){
							console.log('disconnect');
						});
					}
				});
			});
		},
	};
	
	var g_prev_close = 0;

	var realtime_datafeeds = {
		onReady: function (callback) {
			setTimeout(function () {
				callback(tradingview_config);
			}, 0);
		},
		
		// 심볼(화폐이름)검색창에 값을 입력할때 호출(좌측상단)
		searchSymbols: function(searchString, exchange, symbolType, onResultReadyCallback) {
			//alert("searchSymbols : " + searchString);
			if (symbol_data.hasOwnProperty(searchString))
				onResultReadyCallback(symbol_data[searchString]);
		},
		
		// step 2: 심볼을 검색할때 호출(차트가 처음 만들어질 때도 호출된다)
		resolveSymbol: function (symbolName, onSymbolResolvedCallback, onResolveErrorCallback) {
			//alert("resolveSymbol : " + symbolName);
			setTimeout(function () {
				onSymbolResolvedCallback(tradingview_resolve_symbolinfo);
			}, 0);
		},
		
		// step 3: 거래내역을 가져올때 호출
		getBars: function (symbolInfo, resolution, rangeStartDate, rangeEndDate, onHistoryCallback, onErrorCallback) {
			/*
			var bars = [];
			alert("start date " +  Unix2Date(rangeStartDate * 1000) + " end date " + Unix2Date(rangeEndDate * 1000));
			for (var i = (rangeStartDate * 1000); i < (rangeEndDate * 1000); i = i + (60000 * resolution)) {
				var barValue = {
					time: i,
					open: 5000 + (Math.random() * 100),
					close: 5000 + (Math.random() * 100),
					volume: 5000 + (Math.random() * 100)
				};
				barValue.high = Math.max(barValue.open, barValue.close) + (Math.random() * 100);
				barValue.low = Math.min(barValue.open, barValue.close) - (Math.random() * 100);
				bars.push(barValue);
			}

			Datafeeds.lasttime = bars[bars.length - 1].time;
			onHistoryCallback(bars);
			*/
		    var form_data = {symbol:'BTC', resolution: '1', from:rangeStartDate, to: rangeEndDate};
			$.post('/tradingview/history', form_data, function(json) {
				console.log(json);
				var c = 0;
				if (json != null && json.s == 'ok')
				{
					c = json.t.length;
					var bars = [];
					for (var i = 0; i < c; i++)	{
						bars.push({
							time: json.t[i] * 1000,
							open: json.o[i],
							close: json.c[i],
							high: json.h[i],
							low: json.l[i],
							volume: json.v[i],
						});

						//alert(Unix2Date(json.t[i] * 1000));
					}
					
					g_prev_close = bars[bars.length-1].close;

					Datafeeds.lasttime = bars[bars.length - 1].time;
					onHistoryCallback(bars);
				}
			
			}, 'json')
			.fail(function() { });
		},
		
		// step 4 : 
		subscribeBars: function (symbolInfo, resolution, onRealtimeCallback, listenerGUID, onResetCacheNeededCallback) {
			//alert("subscribeBars : " + symbolInfo);
			//alert("subscribeBars : " + listenerGUID);
			
			if (typeof this._realtimeListeners == "undefined") {
				this._realtimeListeners = {};
				this._realtimeListeners[symbolInfo.ticker + resolution] = [];
			}

			this._realtimeListeners[symbolInfo.ticker + resolution].push({ guid: listenerGUID, callback: onRealtimeCallback });
			/*
			if (this._subscribers === 'undefined') {
				this._subscribers = {};
			}

			if (!this._subscribers.hasOwnProperty(listenerGUID)) {
				this._subscribers[listenerGUID] = {
					symbolInfo: symbolInfo,
					resolution: resolution,
					lastBarTime: NaN,
					listeners: []
				};
			}

			this._subscribers[listenerGUID].listeners.push(onRealtimeCallback);
			*/
		   
			//
			// 여기서 웹소켓에서 들어온 이벤트를 처리하자
			realtime_socket.connectServer(function(json) {
				console.log(json);
				//[20556,"sell",8314000,0.0062,"2018-05-30 12:38:43",51546.8],[20555,"buy",8364000,0.0009,"2018-05-30 12:38:24",7527.6]
				var data = $.parseJSON(json);
				console.log("data count : " + data.length);
				var current_time = (new Date()).getTime();
				var barValue = {
					time: current_time,
					open: g_prev_close, // 시작가격
					close: data[0][2], // 종료가격(현재가격)
					high: data[0][2], // 최고가
					low: data[0][2], // 최저가
					volume: data[0][3], // 거래량
				};
				for(var i = 1; i < data.length; i++)
				{
					var t = data[i];
					if (barValue.high < t[2])
						barValue.high = t[2];
					if (barValue.low > t[2])
						barValue.low = t[2];
					// barValue.close = t[2];
					barValue.volume += t[3];
				}
				
				console.log(Unix2Date(barValue.time) + " : " +  barValue);
				g_prev_close = barValue.close;
				//this._realtimeListeners[symbolInfo.ticker + resolution].callback(barValue);
				onRealtimeCallback(barValue);
			});
			/*		this._cable_websocket.subscriptions.create({
				 channel: 'TradingViewPriceUpdatesChannel',
				 ex: symbolInfo.exchange,
				 pa: symbolInfo.ticker,
				 res: resolution
				 },
				 {
				 connected: () => {},
				 received: (data) => {
				 // data goes where?
				 this._realtimeListeners[symbolInfo.ticker + resolution].forEach(el => el.callback(data));
				 }
				 }); */
			}
			
			//unsubscribeBars: function(subscriberUID) {
			//}
		};
		
		TradingView.onready(function()
		{
			var widget = window.tvWidget = new TradingView.widget({
				fullscreen: false,
				//interval: '1S',
				interval: '1',
				container_id: "tv_chart_container",
				timezone: "Asia/Seoul",
				datafeed: realtime_datafeeds,
				library_path: "../../charting_library/",
				disabled_features: [
					"volume_force_overlay",
					"left_toolbar",
				],
				locale: getParameterByName('lang') || "en",
				allow_symbol_change: false,
				//	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
				drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
				debug: false,
				//charts_storage_api_version: "1.1",
				//client_id: 'tradingview.com',
				//user_id: 'public_user_id'
			});

			widget.onChartReady(function() {
				//widget.chart().createStudy('funhansoft', false, true);
			});
		});
    </script>
</head>

<body>
    <script src="<?= $view['url']['static'] ?>/assets/write/header-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>

    <div id="contents" style="padding:20px 0;">
        <div id="tv_chart_container"></div>
    </div>

    <script src="<?= $view['url']['static'] ?>/assets/write/footer-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
</body>