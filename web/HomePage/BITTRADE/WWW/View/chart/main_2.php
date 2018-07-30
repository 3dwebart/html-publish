<head>
    <link href="<?= $view['url']['static'] ?>/assets/css/style.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/utils.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/controller-comm.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/controller-form.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<script>Config.initHeaderScript();setSocChannel('<?=$view['exchannel']['sock_ch']?>');</script>
	
	<!-- TradingView Begin -->
    <script src="../../charting_library/charting_library.min.js"></script>
    <script src="../../charting_library/datafeeds/udf/dist/polyfills.js"></script>
    <script src="../../charting_library/datafeeds/udf/dist/bundle.js"></script>
    <script type="text/javascript">
	
	// console.log("get_linke: " + get_link);

	// 웹 소켓
	var TradingView_SocketManager = {
		//ssid: "c26e8178126688deb863604bef4b0cda",
		//connkey: "817eebc5b49c13f8e6a0a7d159a49c09",
		channel: '<?=$view['exchannel']['sock_ch']?>',
		// url: "//chbtc.devpopcon.com:9090",
		is_join: 0,

		connectServer: function(onTradeCompleteCallback) {
			console.log("channel: " + this.channel);
			var _self = this;
			var param = '/channel?apikey='+ this.getCookie(Config.connkey) + '&ssid=' + this.getCookie(Config.ssid);
			//var websocket_url  = "//chbtc.devpopcon.com:9090/channel?apikey=aac51d760070a27c5db8824e5c8229f7&ssid=MB1-5f5c4c83a05aa32cbdfba45da7664262";
			//var websocket_url = this.url + param;
			var websocket_url = get_link.websocket + param;
			console.log(websocket_url);
			if (this.is_join == 1)
				return;
			
			this.socket_object = io.connect(websocket_url);
			this.socket_object.on('connect', function () {
				_self.socket_object.emit('join', _self.channel, function (err) {
					if (err) {
						console.log('connect failed: ' + err);
					} else {
						_self.is_join = 1;
						console.log('connect success');
						
						_self.socket_object.on('tradecomplete', function(data) {
							// console.log("tradecomplete: " + data);
							onTradeCompleteCallback(data);
						});
						_self.socket_object.on('ticker', function(data) {
							// console.log("ticker: " + data);
						});
						_self.socket_object.on('tickerini', function(jticker,buy,sell,complete, marketdeps) {
							console.log("tickerini");
						});
						_self.socket_object.on('traderegist', function(data,data2,marketdeps) {
							//console.log("traderegist");
						});
						_self.socket_object.on('mytradecomplete',function(data) {
							console.log("mytradecomplete: " + data);
						});
						_self.socket_object.on('mybalance',function(data) {
							console.log("mybalance: " + data);
						});
						_self.socket_object.on('disconnect',function(){
							console.log('disconnect');
						});
					}
				});
			});
		},
		
		getCookie: function(key) {
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
		}
	};
	
	var TradingView_BarManager = {
		open: 0,
		close: 0,
		high: 0,
		low: 0,
		volume: 0,
		
		initBar: function(v) {
			this.low = this.high = this.close = v.close;
		},
		
		updateBar: function(v) {
			this.open = this.close;
			if (this.high < v[2])
				this.high = v[2];
			if (this.low > v[2])
				this.low = v[2];
			this.close = v[2];
			this.volume = this.volume + v[3];
		},
		
		getCurrentBar: function() {
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
		
		getEmptyBar: function() {
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
		
		setTime: function(time) {
			unixTime = time;
		},

		runTimer: function() {
			var _self = this;
			// 이전 타이머 취소
			if(this.intervalId !== 0) {
				clearInterval(this.intervalId);
				this.intervalId = 0;
			}
			
			// 타이머 시작
			this.intervalId = setInterval(function() {	
				var time = Date.now();
				if (_self.isChangeMinute(time))
				{
					console.log("change minute....");
					_self.unixTime = time;
					var barValue = _self.getEmptyBar();
					TradingView_RealtimeDatafeeds.history_data.push(barValue);
					TradingView_RealtimeDatafeeds._realtimeListeners.callback(barValue);
				}
			}, _self.interval);
		},
		
		isChangeMinute: function(time) {
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
		
		onReady: function(callback) {
			this.super_datafeeds = new Datafeeds.UDFCompatibleDatafeed("/tradingview", 60000 * 60 * 24);
			this.super_datafeeds.onReady(callback);
			this.history_data = [];
		},
		
		// 심볼(화폐이름)검색창에 값을 입력할때 호출(좌측상단)
		searchSymbols: function(searchString, exchange, symbolType, onResultReadyCallback) {
			this.super_datafeeds.searchSymbols(searchString, exchange, symbolType, onResultReadyCallback);
		},
		
		// step 2: 심볼을 검색할때 호출(차트가 처음 만들어질 때도 호출된다)
		resolveSymbol: function (symbolName, onSymbolResolvedCallback, onResolveErrorCallback) {
			console.log("resolveSymbol");
			this.super_datafeeds.resolveSymbol(symbolName, onSymbolResolvedCallback, onResolveErrorCallback);
		},
		
		// step 3: 거래내역을 가져올때 호출
		getBars: function (symbolInfo, _resolution, rangeStartDate, rangeEndDate, onHistoryCallback, onErrorCallback) {
			console.log("getBars");
			var _self = this;
			//this.super_datafeeds.getBars(symbolInfo, _resolution, rangeStartDate, rangeEndDate, onHistoryCallback, onErrorCallback);
			
			var form_data = {symbol:symbolInfo.name, resolution: _resolution, from:rangeStartDate, to: rangeEndDate};
			$.post('/tradingview/history', form_data, function(json) {
				//console.log(json);
				var c = 0;
				if (json != null && json.s == 'ok')	{
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
					}
					
					if (bars.length > 0)
					{
						c = _self.history_data.length;
						for (var h = 0; h < c; h++)	{
							bars.push(_self.history_data[h]);
						}

						TradingView_BarManager.initBar(bars[bars.length-1]);
						onHistoryCallback(bars);
						return;
					}
				}

				onHistoryCallback([], {noData:true});
			}, 'json')
			.fail(function() { });
		},
		
		// step 4 : 
		subscribeBars: function (symbolInfo, resolution, onRealtimeCallback, listenerGUID, onResetCacheNeededCallback) {
			console.log("subscribeBars resolution " + resolution);
			onResetCacheNeededCallback();
			this.super_datafeeds.subscribeBars(symbolInfo, resolution, onRealtimeCallback, listenerGUID, onResetCacheNeededCallback);
			
			var _self = this;
			this._realtimeListeners = { guid: listenerGUID, callback: onRealtimeCallback };

			// 여기서 웹소켓에서 들어온 이벤트를 처리하자
			TradingView_SocketManager.connectServer(function(json) {
				console.log(json);
				//[20556,"sell",8314000,0.0062,"2018-05-30 12:38:43",51546.8],[20555,"buy",8364000,0.0009,"2018-05-30 12:38:24",7527.6]
				var data = $.parseJSON(json);
				console.log("data count : " + data.length);
				var t = data[0];

				TradingView_BarManager.updateBar(t);

				var barValue = TradingView_BarManager.getCurrentBar();
				onRealtimeCallback(barValue);
				_self.history_data.push(barValue);
			});
		},
			
		unsubscribeBars: function(subscriberUID) {
			this.super_datafeeds.unsubscribeBars(subscriberUID);
		},

		//getServerTime: function(unixTime) {
			//this.super_datafeeds.getServerTime(function(time) {
			//	console.log("time : " + time);
			//	TradingView_BarManager.setTime(time);
			//	TradingView_BarManager.runTimer();
			//});
		//}
	};
		
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
   
	TradingView.onready(function()
	{
		var widget = window.tvWidget = new TradingView.widget({
			fullscreen: false,
			//interval: '1S',
			interval: '5',
			symbol: '<?=$view['currency']?>',
			container_id: "tv_chart_container",
			timezone: "Asia/Seoul",
			datafeed: TradingView_RealtimeDatafeeds,
			//datafeed: new Datafeeds.UDFCompatibleDatafeed("/tradingview", 60000 * 60 * 24),
			library_path: "../../charting_library/",
			disabled_features: [
				// 심볼 변경 관련 메뉴
				"header_symbol_search",
				"symbol_search_hot_key",
				"compare_symbol",
				"header_compare",
				"header_indicators",
				// 로컬저장을 못하도록 하여 항상 리셋
				"save_chart_properties_to_local_storage",
				// 컨텍스트 메뉴
				"pane_context_menu"
				
			],
			enabled_features: [
				"move_logo_to_main_pane",
				"adaptive_logo",
				"side_toolbar_in_fullscreen_mode",
				"keep_left_toolbar_visible_on_small_screens",
				"hide_left_toolbar_by_default"
			],
			overrides: {
				"mainSeriesProperties.style": 1,
				"mainSeriesProperties.candleStyle.upColor": "#D5405D", //"#D5405D", // 붉은색
                "mainSeriesProperties.candleStyle.downColor": "#0667D0", // 파란색
                "mainSeriesProperties.candleStyle.drawBorder": true,
                "mainSeriesProperties.candleStyle.borderUpColor": "#D5405D",
                "mainSeriesProperties.candleStyle.borderDownColor": "#0667D0",
                "mainSeriesProperties.candleStyle.drawWick": true,
                "mainSeriesProperties.candleStyle.wickUpColor": "#D5405D",
                "mainSeriesProperties.candleStyle.wickDownColor": "#0667D0",
				"paneProperties.legendProperties.showLegend" : false
			},
			locale: "ko",
			allow_symbol_change: false,
			//	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
			drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
			debug: false,
			//charts_storage_api_version: "1.1",
			//client_id: 'tradingview.com',
			//user_id: 'public_user_id'
		});

		widget.onChartReady(function() {
			//TradingView_BarManager.setTime(Date.now());
			//TradingView_BarManager.runTimer();
			widget.chart().createStudy('Moving Average', false, false, [15, "close"], null, {"Plot.color" : "DarkGreen"});
			widget.chart().createStudy('Moving Average', false, false, [60, "close"], null, {"Plot.color" : "Brown"});
			//widget.subscribe('header_toggle', function() {alert("toggle");});
			hideClosePanel(0); 
		});
	});
	
	function hideClosePanel(interval)	{
		setTimeout(function() {
			if ($('iframe').contents().find('span.tv-close-panel'))
				$('iframe').contents().find('span.tv-close-panel').trigger('click');
			else
				hideClosePanel(100);
		}, interval);
	}
    </script>
	<!-- TradingView End -->
	
</head>

<body>
    <script src="<?= $view['url']['static'] ?>/assets/write/header-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>

    <div id="contents" style="padding:20px 0;">
        <div id="tv_chart_container"></div>
    </div>

    <script src="<?= $view['url']['static'] ?>/assets/write/footer-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
</body>