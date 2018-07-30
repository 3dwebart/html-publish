<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="../../charting_library/charting_library.min.js"></script>
    <script src="../../charting_library/datafeeds/udf/dist/polyfills.js"></script>
    <script src="../../charting_library/datafeeds/udf/dist/bundle.js"></script>
    <script type="text/javascript">

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);

        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    
    TradingView.onready(function()
    {
        var widget = window.tvWidget = new TradingView.widget({
            // debug: true, // uncomment this line to see Library errors and warnings in the console
            fullscreen: false,
			//autosize: false,
			container_id: "tv_chart_container",
            symbol: '<?=$view['currency']?>',
            interval: '1', // 조회 간격
            timezone: "Asia/Seoul",
			//debug: true,
			locale: getParameterByName('lang') || "en",
			//allow_symbol_change: true,
            //	BEWARE: no trailing slash is expected in feed URL
            datafeed: new Datafeeds.UDFCompatibleDatafeed("/tradingview"),
            library_path: "../../charting_library/",
            disabled_features: [
                "volume_force_overlay",
				"left_toolbar"
            ],
            
            //	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
            drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
            studies_overrides: {
                "volume.volume.color.0": "#0667D0",
                "volume.volume.color.1": "#D5405D",
                "volume.volume.transparency": 70,
                "volume.volume ma.color": "#D5405D",
                "volume.volume ma.transparency": 30,
                "volume.volume ma.linewidth": 5
            },
            overrides: {
                "mainSeriesProperties.candleStyle.upColor": "#D5405D",
                "mainSeriesProperties.candleStyle.downColor": "#0667D0",
                "mainSeriesProperties.candleStyle.drawBorder": true,
                "mainSeriesProperties.candleStyle.borderUpColor": "#D5405D",
                "mainSeriesProperties.candleStyle.borderDownColor": "#0667D0",
                "mainSeriesProperties.candleStyle.drawWick": true,
                "mainSeriesProperties.candleStyle.wickUpColor": "#D5405D",
                "mainSeriesProperties.candleStyle.wickDownColor": "#0667D0",
                
                "mainSeriesProperties.hollowCandleStyle.upColor": "#D5405D",
                "mainSeriesProperties.hollowCandleStyle.downColor": "#0667D0",
                "mainSeriesProperties.hollowCandleStyle.drawBorder": true,
                "mainSeriesProperties.hollowCandleStyle.borderUpColor": "#D5405D",
                "mainSeriesProperties.hollowCandleStyle.borderDownColor": "#0667D0",
                "mainSeriesProperties.hollowCandleStyle.drawWick": true,
                "mainSeriesProperties.hollowCandleStyle.wickUpColor": "#D5405D",
                "mainSeriesProperties.hollowCandleStyle.wickDownColor": "#0667D0",
                
                "mainSeriesProperties.barStyle.upColor": "#D5405D",
                "mainSeriesProperties.barStyle.downColor": "#0667D0",
                
                "mainSeriesProperties.haStyle.upColor": "#D5405D",
                "mainSeriesProperties.haStyle.downColor": "#0667D0",
                "mainSeriesProperties.haStyle.drawBorder": true,
                "mainSeriesProperties.haStyle.borderUpColor": "#D5405D",
                "mainSeriesProperties.haStyle.borderDownColor": "#0667D0",
                "mainSeriesProperties.haStyle.drawWick": true,
                "mainSeriesProperties.haStyle.wickUpColor": "#D5405D",
                "mainSeriesProperties.haStyle.wickDownColor": "#0667D0"
            }
        });
        
        widget.onChartReady(function() {
            //widget.chart().createStudy('funhansoft', false, true);

        });
    });

    </script>
</head>

<body>
    <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    
    <div id="contents" style="padding:20px 0;">
        <div id="tv_chart_container"></div>
    </div>
    
    <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
</body>