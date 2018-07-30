/*
****************************************************************************************************************
 * chart - highcharts 사용 (https://www.highcharts.com/) 
 * timeline, marketdepth chart 생성 및 사용
****************************************************************************************************************
*/
var isChartDebug = false;

// Timeline, Marketdepth chart 구분 전역 변수
// 'TL' :  Timeline, 	'MD' : Marketdepth
var g_cType = 'TL';

// Timeline chart에서 사용되는 데이타를 가지고 있는 전역 변수
var chartDataArrayList = [];

// Timeline chart의 series[0]에서 사용하는 값.  date, open, high, low, close 값을 가지고 있음
// 이 값은 마지막 값은 실시간으로 변경된다.
var g_ohlcArray = [];

// Timeline chart의 series[1]에서 사용하는 값.  date, volume 값을 가지고 있음
// 이 값의 마지막 값은 실시간으로 변경된다.
var g_volumeArray = [];

// Timeline chart의 마지막 값을 가지고 있는 변수
var g_date = 0, g_open = 0, g_high = 0, g_low = 0, g_close = 0, g_volume = 0;

// Timeline chart에서 Zoom에 정의된값을 가지고 있는 Enum 변수
var ZoomEnum = Object.freeze({HOUR: 0, DAY: 1, WEEK: 2, WEEK2: 3, MONTH: 4, MONTH3: 5});

// Timeline chart에서 CandleStick의 정의된값을 가지고 있는 Enum 변수
// candlestick값에 따라서 db에서 불러오는 데이타의 단위가 달라진다.
// 예)	MIN1 ( 데이타를 1분단위로 db에 요청하여 받아온다), 
//		MIN30( 데이타를 30분 단위로 db에 요청하여 받아온다)
var CandleStickEnum = Object.freeze({MIN1: 0, MIN5: 1, MIN15: 2, MIN30: 3, HOUR: 4, DAY: 5}); 

// Timeline chart의 현재 Zoom 값과  CandleStick 값을 가지고 있다.
var g_ZoomType = ZoomEnum.DAY;
var g_CandleStickType = CandleStickEnum.MIN5;

// Timeline chart에서 마지막 값을 처리하기 위한  timer함수에서(setInterval) 사용하는 
// interval 값으로 단위는 '분'단위를 사용.
// 기본값은 5분. [ g_CandleStickType의 값과 동일하게 이루어져야 함)
var g_interval = 5;

// Timeline chart에서 마지막 데이타를 실시간 연동하기 위한 setInterval함수의 ID
// g_CandleStickType 단위가 변경되어 다시 setInterval 함수를 변경된 값에 따라 재실행할때
// 이전 setInterval 함수를 중지하기 위하여 사용
var g_intervalId = null;

var g_currencytype = 'ETC';
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++/
var tmpdata;
var plotLineConfig = {
           color: 'red',
           dashStyle: 'dash',
           width: 1,
           value: 0.0000,
           id: 'current',
           zIndex: 3,       
           label: {
               text: '0.00',
               align: 'left',
               x: 15,
               style: { 
                    color: "#111111",  
                    fontSize: "9px" 
                },
           }
       };

// timeline chart zoom Type  설정
function setChartZoomType(type)
{
	g_ZoomType = type;
}

function getChartZoomType()
{
	return g_ZoomType;
}

// CandleStick 설정
 function setCandleStick(type)
 {
	 g_CandleStickType = type;
 }
 
 function getCandleStick()
 {
	 return g_CandleStickType;
 }

//-----------------------------------------------------------------------------------------------------------------------------------/
// Zoom 버튼  action
//-----------------------------------------------------------------------------------------------------------------------------------/
// Zoom 시간별
$("#btn_graph_zoom_hour").click(function(){    
	setChartZoomType(ZoomEnum.HOUR);
	zoomActiveRemove();
	$('#btn_graph_zoom_hour').addClass("active");
	if(isChartDebug)
		console.log("시간");  	
	var chart = $('#chartdiv').highcharts();	
	chart.rangeSelector.clickButton(0);
	chart.redraw();	
});

// Zoom 일별  - 기본
$("#btn_graph_zoom_day").click(function(){  
	setChartZoomType(ZoomEnum.DAY);
	zoomActiveRemove();
    $('#btn_graph_zoom_day').addClass("active");
	if(isChartDebug)
		console.log("일별");	
	var chart = $('#chartdiv').highcharts();	
	chart.rangeSelector.clickButton(1);
	chart.redraw();   
});
// Zoom 주별
$("#btn_graph_zoom_week").click(function(){
	setChartZoomType(ZoomEnum.WEEK);
	zoomActiveRemove();
    $('#btn_graph_zoom_week').addClass("active");
	if(isChartDebug)
		console.log("주별");   	
	var chart = $('#chartdiv').highcharts();	
	chart.rangeSelector.clickButton(2);
	chart.redraw();
});

// Zoom 2주별
$("#btn_graph_zoom_2week").click(function(){
	setChartZoomType(ZoomEnum.WEEK2);
	zoomActiveRemove();
    $('#btn_graph_zoom_2week').addClass("active");
	if(isChartDebug)
		console.log("2주별");   	
	var chart = $('#chartdiv').highcharts();	
	chart.rangeSelector.clickButton(3);
	chart.redraw();
});

// Zoom 월별
$("#btn_graph_zoom_month").click(function(){    
setChartZoomType(ZoomEnum.MONTH);
	zoomActiveRemove();
    $('#btn_graph_zoom_month').addClass("active");
	if(isChartDebug)
		console.log("월별");   	
	var chart = $('#chartdiv').highcharts();	
	chart.rangeSelector.clickButton(4);
	chart.redraw();
});

// Zoom 3개월별
$("#btn_graph_zoom_3month").click(function(){    
setChartZoomType(ZoomEnum.MONTH);
	zoomActiveRemove();
    $('#btn_graph_zoom_3month').addClass("active");
	if(isChartDebug)
		console.log("3개월별");   	
	var chart = $('#chartdiv').highcharts();	
	chart.rangeSelector.clickButton(5);
	chart.redraw();
});
//-----------------------------------------------------------------------------------------------------------------------------------/

//-----------------------------------------------------------------------------------------------------------------------------------/
// CandleStick 버튼 action
//-----------------------------------------------------------------------------------------------------------------------------------/
// CandleStick 1min
$("#btn_graph_candlestick_1m").click(function(){
	setCandleStick(CandleStickEnum.MIN1);
	CandleStickActiveRemove();
    $('#btn_graph_candlestick_1m').addClass("active");
	if(isChartDebug)
		console.log("candlestick 1m");   	
	generateChartDataMain(g_currencytype);
});

// CandleStick 5min
$("#btn_graph_candlestick_5m").click(function(){
	setCandleStick(CandleStickEnum.MIN5);
	CandleStickActiveRemove();
    $('#btn_graph_candlestick_5m').addClass("active");
	if(isChartDebug)
		console.log("candlestick 5m");   	
	generateChartDataMain(g_currencytype);
});

// CandleStick 15min
$("#btn_graph_candlestick_15m").click(function(){
    setCandleStick(CandleStickEnum.MIN15);
	CandleStickActiveRemove();
	$('#btn_graph_candlestick_15m').addClass("active");
	if(isChartDebug)
		console.log("candlestick 15m");   
	generateChartDataMain(g_currencytype);
});

// CandleStick 30min
$("#btn_graph_candlestick_30m").click(function(){
	setCandleStick(CandleStickEnum.MIN30);
	CandleStickActiveRemove();
    $('#btn_graph_candlestick_30m').addClass("active");
	if(isChartDebug)
		console.log("candlestick 30m");
	generateChartDataMain(g_currencytype);	
});

// CandleStick 1hour
$("#btn_graph_candlestick_1h").click(function(){
	setCandleStick(CandleStickEnum.HOUR);
	CandleStickActiveRemove();
    $('#btn_graph_candlestick_1h').addClass("active");
	if(isChartDebug)
		console.log("candlestick 1h");
	generateChartDataMain(g_currencytype);
});

// CandleStick 1day
$("#btn_graph_candlestick_1d").click(function(){
	setCandleStick(CandleStickEnum.DAY);
	CandleStickActiveRemove();
    $('#btn_graph_candlestick_1d').addClass("active");
	if(isChartDebug)
		console.log("candlestick 1d");   
	generateChartDataMain(g_currencytype);	
});
//-----------------------------------------------------------------------------------------------------------------------------------/

// Zoom 버튼 remove active
function zoomActiveRemove(){
    $('#btn_graph_zoom_hour').removeClass("active");
    $('#btn_graph_zoom_day').removeClass("active");
    $('#btn_graph_zoom_week').removeClass("active");
    $('#btn_graph_zoom_2week').removeClass("active");
    $('#btn_graph_zoom_month').removeClass("active");
    $('#btn_graph_zoom_3month').removeClass("active");
}

// CandleStick 버튼 remove active
function CandleStickActiveRemove(){
    $('#btn_graph_candlestick_1m').removeClass("active");
    $('#btn_graph_candlestick_5m').removeClass("active");
    $('#btn_graph_candlestick_15m').removeClass("active");
    $('#btn_graph_candlestick_30m').removeClass("active");
    $('#btn_graph_candlestick_1h').removeClass("active");
    $('#btn_graph_candlestick_1d').removeClass("active");
}

// chart tooltip - candlestick
function getHTMLCreateCustomHtmlTooltip(ch_date, ch_begin_cost, ch_min_cost, ch_max_cost, ch_close_cost){
    var html =  '<div class="main-chart-tooltip">';
        html += '<table style="width:130px;">';
        html += '<tr><td colspan="2"><b>'+ch_date+'</b><br></td></tr>';
        html += '<tr><td width="40px;">'+langConvert('lang.viewChartBeginPrice','')+' : </td><td class="pull-right">'+calfloat('FLOOR', ch_begin_cost, 0).toString().formatBitcoin()+' '+langConvert('lang.common.currentCurrency', '')+'<td/></tr>';
        html += '<tr><td>'+langConvert('lang.viewChartUpperLimitPrice','')+' : </td><td class="pull-right">'+calfloat('FLOOR', ch_max_cost, 0).toString().formatBitcoin()+' '+langConvert('lang.common.currentCurrency', '')+'<td/></tr>';
        html += '<tr><td>'+langConvert('lang.viewChartLowerLimitPrice','')+' : </td><td class="pull-right">'+calfloat('FLOOR', ch_min_cost, 0).toString().formatBitcoin()+' '+langConvert('lang.common.currentCurrency', '')+'<td/></tr>';
        html += '<tr><td>'+langConvert('lang.viewChartClosePrice','')+' : </td><td class="pull-right">'+calfloat('FLOOR', ch_close_cost, 0).toString().formatBitcoin()+' '+langConvert('lang.common.currentCurrency', '')+'<td/></tr>';
        html += '</table></div>';
    return html;
}

// chart tooltip - area
function getHTMLCreateCustomHtmlTooltipArea(od_market_price, od_temp_coin){
    var html =  '<div class="main-areachart-tooltip">';
        html += '<table style="width:150px">';
        html += '<tr><td>'+langConvert('lang.viewChartStockPrice','')+' : </td><td class="pull-right">'+calfloat('FLOOR', Number(od_market_price), 0).toString().formatBitcoin()+langConvert('lang.common.currentCurrency', '')+'<td/></tr>';
        html += '<tr><td>'+langConvert('lang.viewChartRemainAmount','')+' : </td><td class="pull-right">'+od_temp_coin+langConvert('lang.common.encryptCurrency', '')+'<td/></tr>';
        html += '</table></div>';
    return html;
}

//-----------------------------------------------------------------------------------------------------------------------------------/
// timeline chart의 마지막 값 실시간 업데이트 처리
//-----------------------------------------------------------------------------------------------------------------------------------/
function updateTLChart(newDate, newOpen, newHigh, newLow, newClose, newVolume)
{
    
	if(g_cType != 'TL') 
		return;
	
	var chart = $('#chartdiv').highcharts();
	g_ohlcArray.push([newDate , newOpen, newHigh, newLow , newClose]);
	g_volumeArray.push([newDate, newVolume]);
	
	chart.series[0].setData(g_ohlcArray);
	chart.series[1].setData(g_volumeArray);	
	
	g_volume = newVolume;
        g_high = newHigh;
        g_low = newLow;
        
        updateCurrentIndicator(newClose);
	
}

function updateRealTLChart( newHigh, newLow, newClose, newVolume) {
        
	if(g_cType != 'TL') 
		return;
                 
	
	if(isChartDebug)
	{
		console.log("updateRealTLChart event -> begin");
		console.log("date : " + g_date +" h : " + newHigh + " low : " + newLow + " close : " + newClose + " volume : " + newVolume);	
		console.log(g_ohlcArray);
		console.log(g_ohlcArray.length);
	}	
	
	var len = g_ohlcArray.length - 1;
        
        if(len < 0)
            return;
	
	// 0:data, 1:open, 2:high, 3:low, 4:close
	g_ohlcArray[len][2] = newHigh;
	g_ohlcArray[len][3] = newLow;
	g_ohlcArray[len][4] = newClose;
	
	g_volumeArray[len][1] = newVolume;		
	
	var chart = $('#chartdiv').highcharts();	
	chart.series[0].setData(g_ohlcArray);
	chart.series[1].setData(g_volumeArray);
        
        updateCurrentIndicator(newClose);

}

// 체결이 이루어졌을때 controller-trade.js 에서호출하는 이벤트를 받음
function onChartDataChange(data)
{
    if(g_cType != 'TL') 
            return;

    if(!data) 
            return;

     // {"tr_no":1515,"od_action":"buy","tr_market_cost":0.039,"tr_total_coin":0.02,"tr_reg_dt":"2017-08-11 15:42:05","tr_total_cost":0.00078}
    g_volume = g_volume + data[3];

    if( g_high < data[2])
            g_high = data[2];

    if(g_low > data[2])
            g_low = data[2];

    g_close = data[2];

    // 실시간 차트 업데이트 
    updateRealTLChart(g_high, g_low, g_close, g_volume);

}

function setTimer() {
	
    g_intervalId = setInterval(function() {	
        g_date = g_date + (60000 * g_interval);
        updateTLChart(g_date, g_close, g_close, g_close, g_close, 0);
    }, 60000 * g_interval);
}

// cType에 따라서 Timeline, MarketDepth 차트를 생성한다.
// cType : 'TL', 'MD'
function createChart(cCurrency, cType){
	
	if(isChartDebug)
	{
		console.log('createChart');
		console.log(cType);
	}
	
	if(cType == 'TL') {
		if(isChartDebug) console.log('Timeline createChart()');
		g_cType = 'TL';
		createTimelineChart(cCurrency);
	}
	else if (cType == 'MD') {
		if(isChartDebug) console.log('Timeline createChart()');
		g_cType = 'MD';
		createMarketdepthChart();
	}
	else {
		if(isChartDebug) console.log("Failed createChart !");
	}
}

// 현재 차트의  currency를 비교하여 맞는 소수점 이하 자리수를 가져온다
function getFixedCountByCurrency(cCurrency)
{
    var nFixed = 8;
    
    if(cCurrency.toLowerCase() == 'etc')
        nFixed = 6;
    else if(cCurrency.toLowerCase() == 'eth')
        nFixed = 4;
    else if(cCurrency.toLowerCase() == 'ltc')
        nFixed = 5;
    else if(cCurrency.toLowerCase() == 'sc')
        nFixed = 8;
    else if(cCurrency.toLowerCase() == 'bch')
        nFixed = 4;
       
    return nFixed;
}


function updateCurrentIndicator(value) {
    /*var chart = $('#chartdiv').highcharts();    
    chart.yAxis[0].removePlotLine('current');
    plotLineConfig.value = value;
    plotLineConfig.label.text = value.toFixed(8);
    chart.yAxis[0].addPlotLine(plotLineConfig);*/
}

function createTimelineChart(cCurrency) {
    
    var ohlc = [], volume = [];
 
    // set the allowed units for data grouping
    groupingUnits = [[
            'minute',   [1, 2, 5, 10, 15, 30]
            ], [
            'hour',   [1, 2, 3, 4, 6, 8, 12]
            ], [
            'day',   [1]
            ], [
            'week',   [1,2]
            ], [
            'month',   [1, 3, 6]
            ], [
            'year',   null
    ]];

Highcharts.theme = {
   colors: ['#cb4240', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066',
      '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
   chart: {
      backgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
         stops: [
            [0, '#ffffff'],	// 배경색 그라데이션 151d23
            [1, '#ffffff'] // 151d23
         ]
      },
      style: {
         fontFamily: '\'Unica One\', sans-serif'
      },
      plotBorderColor: '#606063'
   },
   title: {
      style: {
         color: '#333333', //E0E0E3
         textTransform: 'uppercase',
         fontSize: '20px'
      }
   },
   subtitle: {
      style: {
         color: '#333333', //E0E0E3
         textTransform: 'uppercase'
      }
   },
   xAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#333333'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      title: {
         style: {
            color: '#A0A0A3'

         }
      }
   },
   yAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#333333'
         }
      },
      lineColor: '#707073',
      minorGridLineColor: '#505053',
      tickColor: '#707073',
      tickWidth: 1,
      title: {
         style: {
            color: '#A0A0A3'
         }
      }
   },
   tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.85)',
      style: {
         color: '#0667d0' //color: '#F0F0F0'
      }
   },
   plotOptions: {
      series: {
         dataLabels: {
            color: '#B0B0B3'
         },
         marker: {
            lineColor: '#333'
         }
      },
    candlestick: {
        color: 'rgba(6,103,208,1)',
        lineColor: 'rgba(6,103,208,1)',
        upColor: 'rgba(213,64,93,1)',
        upLineColor: 'rgba(213,64,93,1)'
    },
      boxplot: {
         fillColor: '#505053'
      },
      errorbar: {
         color: '#000'
      }
   },
   legend: {
      itemStyle: {
         color: '#333333'
      },
      itemHoverStyle: {
         color: '#FFF'
      },
      itemHiddenStyle: {
         color: '#606063'
      }
   },
   credits: {
      style: {
         color: '#666'
      }
   },
   labels: {
      style: {
         color: '#707073'
      }
   },

   drilldown: {
      activeAxisLabelStyle: {
         color: '#F0F0F3'
      },
      activeDataLabelStyle: {
         color: '#F0F0F3'
      }
   },

   navigation: {
      buttonOptions: {
         symbolStroke: '#DDDDDD',
         theme: {
            fill: '#505053'
         }
      }
   },

   // scroll charts
   rangeSelector: {
      buttonTheme: {
         fill: '#505053',
         stroke: '#000000',
         style: {
            color: '#CCC'
         },
         states: {
            hover: {
               fill: '#707073',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            },
            select: {
               fill: '#000003',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            }
         }
      },
      inputBoxBorderColor: '#505053',
      inputStyle: {
         backgroundColor: '#333',
         color: 'silver'
      },
      labelStyle: {
         color: 'silver'
      }
   },

   navigator: {
      handles: {
         backgroundColor: '#666',
         borderColor: '#AAA'
      },
      outlineColor: '#CCC',
      maskFill: 'rgba(255,255,255,0.1)',
      series: {
         color: '#7798BF',
         lineColor: '#A6C7ED'
      },
      xAxis: {
         gridLineColor: '#505053'
      }
   },

   scrollbar: {
      barBackgroundColor: '#808083',
      barBorderColor: '#808083',
      buttonArrowColor: '#CCC',
      buttonBackgroundColor: '#606063',
      buttonBorderColor: '#606063',
      rifleColor: '#FFF',
      trackBackgroundColor: '#404043',
      trackBorderColor: '#404043'
   },

   // special colors for some of the
   legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
   background2: '#505053',
   dataLabelsColor: '#B0B0B3',
   textColor: '#C0C0C0',
   contrastTextColor: '#F0F0F3',
   maskColor: 'rgba(255,255,255,0.3)'
};

	
	
	
// Apply the theme
Highcharts.setOptions(Highcharts.theme);
    Highcharts.setOptions({
      global: {
         useUTC: false
      }
   });
    
    // create the chart
    Highcharts.stockChart('chartdiv', {
        navigation: {
            buttonOptions: {
                enabled: false
            }
        }, 
        rangeSelector: {
            inputEnabled:false,
            buttonTheme: {
                visibility: 'hidden'
            },
            labelStyle: {
                visibility: 'hidden'
            },
            buttons: [{
                type: 'hour',
                count: 1,
                text: '1H',               
            }, {
                type: 'day',
                count: 1,
                text: '1D',               
            }, {
                type: 'week',
                count: 1,
                text: '1W',
            }, {
                type: 'week',
                count: 2,
                text: '2W',               
            }, {
                type: 'month',
                count: 1,
                text: '1M',   
            }, {
                type: 'month',
                count: 3,
                text: '3M',
            }],
            selected: g_ZoomType,
            display: false

        },

        title: {
            text: ''
        },

        yAxis: [{
            labels: {
                formatter:function() {
                  return this.value.formatWon();
                },
                align: 'right',
                x: -3
            },
            title: {
                text: ''
            },
            height: '60%',
            lineWidth: 2,
            startOnTick: false,
            resize: {
                enabled: true
            }
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: ''
            },
            top: '65%',
            height: '35%',
            offset: 0,
            lineWidth: 2
        }],

        tooltip: {
            formatter: function () {
                var s = '<div style="text-align:right"><strong style="font-size:11px;font-weight:600">' + Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '</strong></span>';

                $.each(this.points, function () {
                    if(this.point.series.name == 'Price') {
                        s += ' | <span style=""><strong style="font-size:11px;font-weight:600">Open:</strong></span> <span style="font-size:11px"> ' + Highcharts.numberFormat(this.point.options.open, 0, '.',',') + '</span>';
                        s += ' | <span style="color:gray"><strong style="font-size:11px;font-weight:600">High:</strong></span> <span style="font-size:11px"> ' + Highcharts.numberFormat(this.point.options.high, 0, '.',',') + '</span>';
                        s += ' | <span style="color:gray"><strong style="font-size:11px;font-weight:600">Low:</strong></span> <span style="font-size:11px"> ' + Highcharts.numberFormat(this.point.options.low, 0, '.',',') + '</span>';
                        s += ' | <span style="color:gray"><strong style="font-size:11px;font-weight:600">Close:</strong></span> <span style="font-size:11px"> ' + Highcharts.numberFormat(this.point.options.close, 0, '.',',') + '</span>';
                    }
                    else if(this.point.series.name == 'Volume') {
                        s += ' | <span style="color:gray"><strong style="font-size:11px;font-weight:600">Volume:</strong></span> <span style="font-size:11px"> ' + Number(this.point.options.y).toFixed(4) + '</span></div>';
                    }                                
                });
                return s;
            },
             positioner: function () {
                return { x: 5, y: 18 };
            },
            split: false,
            borderWidth: 0,
            backgroundColor: "rgba(255,255,255,0)",
            shadow: false
        },

        series: [{
            type: 'candlestick',
            name: 'Price',
            data: ohlc,
            dataGrouping: {
                units: groupingUnits
            }
        }, {
            type: 'column',
            name: 'Volume',
            data: volume,
            yAxis: 1,
            dataGrouping: {
                units: groupingUnits
            }
        }]
    });
	
	
}

function createTimelineChart_old(cCurrency) {
   
        //var nFixed = getFixedCountByCurrency(cCurrency);
        var nFixed = 0;
        
   var ohlc = [], volume = [];
   
   // set the allowed units for data grouping
   groupingUnits = [[
      'minute',   [1, 2, 5, 10, 15, 30]
      ], [
      'hour',   [1, 2, 3, 4, 6, 8, 12]
      ], [
      'day',      [1]
      ], [
      'week',   [1,2]
      ], [
      'month',   [1, 3, 6]
      ], [
      'year',   null
   ]];
      
   Highcharts.setOptions({
      global: {
                        // 싱가폴 기준으로 시간을 설정함
         useUTC: true,
                        timezoneOffset: -8*60 
      },
                lang: {
         thousandsSep: ''
      },
                
   });
        

   // TimeLine Chart Js 
    charts= Highcharts.stockChart('chartdiv', {
        navigation: {
            buttonOptions: {
                enabled: false
            }
        },   
        rangeSelector: {
            inputEnabled:false,
            buttonTheme: {
                visibility: 'hidden'
            },
            labelStyle: {
                visibility: 'hidden'
            },
            buttons: [{
                type: 'hour',
                count: 1,
                text: '1H',               
            }, {
                type: 'day',
                count: 1,
                text: '1D',               
            }, {
                type: 'week',
                count: 1,
                text: '1W',
            }, {
                type: 'week',
                count: 2,
                text: '2W',               
            }, {
                type: 'month',
                count: 1,
                text: '1M',   
            }, {
                type: 'month',
                count: 3,
                text: '3M',
            }],
            selected: g_ZoomType,
            display: false

        },

        title: {
                text: ''
        },

        plotOptions: {
            candlestick: {
                color: 'rgba(6,103,208,1)',
                lineColor: 'rgba(6,103,208,1)',
                upColor: 'rgba(213,64,93,1)',
                upLineColor: 'rgba(213,64,93,1)'
            }
        },

        xAxis: [{
            labels: {
                style: { color: "#999",  fontSize: "9px" },
            },
                lineWidth: 1,
                lineColor: '#d2d2d2',
            }, {
                lineWidth: 0,
        }],

        yAxis: [{
            labels: {
                formatter: function () {
                    return this.value.toFixed(nFixed);
                },
                align: 'right',
                x: -3,
                style: { 
                    color: "#0000ff",  
                    fontSize: "10px" 
                },
            },
            title: {
                text: ''
            },
            height: '70%',
            lineWidth: 0,                        
            offset: 65,
            startOnTick: false,
            //min: 0.00000001,
            //minRange: 0.00000001,
            gridLineColor:'#f1f1f1',
            tickPixelInterval: 40
           // plotLines: [plotLineConfig]

        }, {
            labels: {
                formatter: function () {
                    return this.value.toFixed(nFixed);
                },
                align: 'right',
                x: -3,
                style: { 
                    color: "#ff00ff",  
                    fontSize: "10px" 
                },
            },
            title: { text: '' },  
            top:'70%',
            height: '30%',
            offset: 65,
            //min: 0.00000001,
            lineWidth: 0,
            gridLineWidth:1,
            tickPixelInterval: 40
        }],

      
        tooltip: {
            
            shared: true,
            split: false,
            useHTML: true,
            borderWidth: 0,
            backgroundColor: "rgba(255,255,255,0)",
            shadow: false,
            borderRadius: 0,
            valueDecimals: 8                      
        },
      
        series: [
            {
                type: 'candlestick',
                name: 'Price',
                data: ohlc,
                dataGrouping: {
                        units: groupingUnits
                },         

            }, {
                    type: 'column',
                    color: 'rgba(255,100,255,255)',
                    name: 'Volume',
                    data: volume,
                    width:10,
                    yAxis: 1,
            }
        ],
        navigator: {
            maskFill: 'rgba(200, 200, 200, 0.1)',
            xAxis: {
                lineWidth: 1,
                lineColor: '#d2d2d2',
                labels: {
                style: { color: "#999",  fontSize: "9px" },
                },
            },
            yAxis: {
                lineWidth: 1,
                lineColor: '#d2d2d2',
            },
            series: {
                lineWidth: 0.6,
                lineColor: '#d0ea00',
                fillColor : {
                    linearGradient : {  
                        x1 : 0, 
                        y1 : 0, 
                        x2 : 0, 
                        y2 : 1 
                    }, 
                    stops : [[0, '#fff'], [1, '#fefff7']] 
                },
                marker: {
                    enabled: false
                }
            }
        },
      
        scrollbar: {
            enabled: false,
            barBackgroundColor: '#f9f9f9',
            barBorderColor: '#d1d1d1',
            buttonArrowColor: '#999',
            buttonBackgroundColor: '#fff',
            trackBackgroundColor:'#fff'
        }
    });

}

// 차트에 데이타 설정
function setChartData(ohlc, volume) {
		
	if(g_cType == 'MD') 
		return;
	
	var chart = $('#chartdiv').highcharts();
	
  
        
	g_ohlcArray = ohlc;
	g_volumeArray = volume;
	
	if(isChartDebug) console.log("setChartData - begin");
	
	
	chart.series[0].setData(ohlc);
	chart.series[1].setData(volume);		
	chart.redraw();	
	
	if(g_intervalId != null) {
		clearInterval(g_intervalId);
		g_intervalId = null;
	}	
	setTimer();	
}

// 차트 데이타를 가져와서 차트의  series에 넣을 데이타를 가공하여 
// setChartData를 호출하여 차트에 데이타를 설정한다.
/*function generateChartDataMain(currencytype) {
	
	if(g_cType == 'MD') 
		return;

	g_currencytype = currencytype;
	
	var result = getChartData(g_CandleStickType, currencytype.toUpperCase(), function(data){
		
		var ohlc = [], volume = [];
		for(var i=0; i< data.length; i++){				

			ohlc.push([
				data[i].date, 	// the date
				data[i].open, 	// open
				data[i].high,	// high
				data[i].low, 	// low
				data[i].close	// close
			]);
						
			volume.push([
				data[i].date, 	// the date
				data[i].volume 	// the volume
			]);			
			
			// 데이타의 마지막 값은 실시간 연동시 비교하기 위해 별도 변수에 담아 놓는다.			
			if(i == (data.length - 1)) {
				g_date 	= Number(data[i].date);
				g_open 	= Number(data[i].open);
				g_high 	= Number(data[i].high);
				g_low 	= Number(data[i].low);
				g_close	= Number(data[i].close);
				g_volume = Number(data[i].volume);   
                                
                                if(!isNaN(g_close))
                                    updateCurrentIndicator(g_close);
                                else {                                    
                                    updateCurrentIndicator(Number(data[i-1].close));
                                }
			}		
                        
                        
		};
                setChartData(ohlc, volume);
	});
}*/

function generateChartDataMain(currencytype) {
	
	if(g_cType == 'MD') 
		return;

	g_currencytype = currencytype;
	
	var result = getChartData(g_CandleStickType, currencytype.toUpperCase(), function(data){
		
        var ohlc = [], volume = [];
        var checka = 0;

            data[0].open = data[0].close;
        data[0].low = data[0].close;
        tmpdata = data[1];
        for (var i = 0; i < data.length; i++){
            if (checka == 0) {
                if (data[i].close > 0) {
                    checka = 1;
                    data[i].open = data[i].close;
                    data[i].low = data[i].close;
                }

            }
            else {
                if (data[i].low == 0) {
                    if (data[i].open > 0) {
                        data[i].low = data[i].open;
                    }
                    else {
                        if (tmpdata) { data[i] = tmpdata; }
                    }
                }
                else {
                    tmpdata = data[i];
                }
                if (data[i].close == 0) {
                    data[i].close = data[i].open;
                }else if(data[i].high == 0){
                    data[i].high = data[i].open;
                }else if(data[i].close < data[i].low){
                    data[i].low = data[i].close;
                }else if(data[i].open > data[i].high){
                    data[i].high = data[i].open;
                }
                ohlc.push([
                    data[i].date, 	// the date
                    data[i].open, 	// open
                    data[i].high,	// high
                    data[i].low, 	// low
                    data[i].close	// close
                ]);

                volume.push([
                    data[i].date, 	// the date
                    data[i].volume 	// the volume
                ]);

                // 데이타의 마지막 값은 실시간 연동시 비교하기 위해 별도 변수에 담아 놓는다.			
                if (i == (data.length - 1)) {
                    g_date = Number(data[i].date);
                    g_open = Number(data[i].open);
                    g_high = Number(data[i].high);
                    g_low = Number(data[i].low);
                    g_close = Number(data[i].close);
                    g_volume = Number(data[i].volume);

                    if (!isNaN(g_close))
                        updateCurrentIndicator(g_close);
                    else {
                        updateCurrentIndicator(Number(data[i - 1].close));
                    }
                }		
            }
            
                        
                        
		};
                setChartData(ohlc, volume);
	});
}

function changeChartData(edata){
	if(isChartDebug) console.log("changeChartData");    
}

// get chart hour data
function getChartData(CandleStickType, currencytype, callback){

	if(g_cType == 'MD') 
		return;
	
	if(isChartDebug)
    {
		console.log("getChartData");
		console.log(currencytype);
	}
	
	var currencyurl = '/getchartdata/chartlist/currency-btc';
    if(currencytype && typeof currencytype === 'string'){
        currencyurl = '/getchartdata/chartlist/currency-' + currencytype.toLowerCase();
    }
	
    var url = '';   
	switch(g_CandleStickType)
	{
		case CandleStickEnum.MIN1:
			url = currencyurl + '/timetype-min/timevalue-1/';	
			g_interval = 1;
			break;
		case CandleStickEnum.MIN5:
			url = currencyurl + '/timetype-min/timevalue-5/';	
			g_interval = 5;
			break;
		case CandleStickEnum.MIN15:
			url = currencyurl + '/timetype-min/timevalue-15/';	
			g_interval = 15;
			break;
		case CandleStickEnum.MIN30:
			url = currencyurl + '/timetype-min/timevalue-30/';	
			g_interval = 30;
			break;
		case CandleStickEnum.HOUR:
			url = currencyurl + '/timetype-hour/timevalue-1/';	
			g_interval = 60;
			break;
		case CandleStickEnum.DAY:
			url = currencyurl + '/timetype-day/timevalue-1/';	
			g_interval = 60*24;
			break;
		default:
			console.log("Mismatch CandleStickEnumType!");
			break;
	}
			
	

    $.getJSON(url, "", function (data) {
    })
    .done(function(data) {		
		chartDataArrayList=[];

        if( data && Array.isArray(data) ){
            if( data[0].hasOwnProperty('result') && Number(data[0].result) > 0){

                var maxcount = data.length;
                if(isChartDebug) console.log(maxcount);              
                for(var k=0;k<data.length;k++){
                   
                    var newDate = data[k].j * 1000;
		                    
                    chartDataArrayList[k] = ({
                            date: newDate,
                            open: Number(data[k].x),
                            close: Number(data[k].q),
                            high: Number(data[k].y),
                            low: Number(data[k].z),
                            volume: Number(data[k].k)
                    });
                }
				
                //최근 최종가 셋팅
                var last = gCurrentChTicker.last;
                chartDataArrayList[maxcount-1].close = Number(last);
                updateCurrentIndicator(Number(last));
                if(typeof callback === 'function' ){
                    callback(chartDataArrayList);
                }
            }
        }
    })
    .fail(function(){ });
   
}


function addPanel() {
    
}

// 데이터 삽입, 갱신 여부
function isNewChartNewData(date_type, lastchartdate){

    var curMinute   = (new Date().format('mm'));
    var curDay      = (new Date().format('dd'));
    var curMonth    = (new Date().format('MM'));

    if(date_type=='h'){
        lastchartdate = (new Date(lastchartdate)).format('mm');
        // 차트 분 비교 후 같으면 차트 데이터에 삽입하지 않음
//            console.log(date_type +', ' + lastchartdate + ', ' + ((lastchartdate).toString()).charAt(0) + ', ' + ((curMinute).toString()).charAt(0) );
        if( ((lastchartdate).toString()).charAt(0)==((curMinute).toString()).charAt(0) ){
            return false;
        }else{
            return true;
        }
    }else if(date_type=='d'){
        lastchartdate = (new Date(lastchartdate)).format('dd');
        if( lastchartdate==curDay ){
            return false;
        }else{
            return true;
        }
    }else if(date_type=='m'){
        lastchartdate = (new Date(lastchartdate)).format('MM');
        if( lastchartdate==curMonth ){
            return false;
        }else{
            return true;
        }
    }
}

// 최근 데이터 업데이트
function procLastestChartDataUpdate(newdata){
    if(!newdata && newdata.length == 0){ return; }
    if(typeof newdata == 'string'){
        try{
           newdata = $.parseJSON(newdata);
        }catch(e){
            return;
        }
    }else if(typeof newdata == 'object'){
    }else{ return;}
    if(newdata.length==0){
        return;
    }
    
    var maxidx = chartDataArrayList.length -1;

    //var charttype = getChartZoomType();

    if(chartDataArrayList.length==0){ return; }
    
    var lastdate = new Date();
    if(chartDataArrayList[maxidx] && chartDataArrayList[maxidx].hasOwnProperty('ch_date')){
        lastdate = chartDataArrayList[maxidx].ch_date;
    }
    
    if(isNewChartNewData(g_ZoomType, lastdate)){
        // 신규 row 추가
        var defaultdata = getDefaultNewChartDateInsert();
        if( defaultdata.ch_date != 0 ){
            chartDataArrayList.unshift(defaultdata);
        }
//            console.log('신규 row 추가');
    }

    // 기존 데이터 업데이트
    var tradelastcost   = Number(newdata[0].tr_market_cost);

    if(chartDataArrayList.length==0){
        return;
    }

}

// 신규 데이터 삽입
function getDefaultNewChartDateInsert(){
    var datevalue = getLastestChartDateFormat();
    var chartNewData = {
        date: 0,
        open: 0,
        close: 0,
        high: 0,
        low: 0,
        volume: 0,
        value: 0
    };
    if(chartDataArrayList && chartDataArrayList.length>0 && (datevalue).toString()!=(chartDataArrayList[0].ch_date).toString()){
        var ch_close_cost = chartDataArrayList[0].ch_close_cost;
        chartDataLastDate   = (datevalue).toString();
        chartNewData.date   = (datevalue).toString();
        chartNewData.open   = ch_close_cost;
        chartNewData.low    = ch_close_cost;
        chartNewData.high   = ch_close_cost;
        chartNewData.close  = ch_close_cost;
    }

    return chartNewData;
}

// 신규 데이터 삽입용 날짜
function getLastestChartDateFormat(){
    //var charttype = getChartZoomType();
    var datevalue = '';

    if(g_ZoomType==ZoomEnum.HOUR){
        var datevalue = (new Date().format('mm'));
        datevalue = (datevalue.toString()).charAt(0)+'0';
        datevalue = (new Date().format('yyyy-MM-dd HH:'+datevalue+':00'));
    }else if(g_ZoomType==ZoomEnum.DAY){
        datevalue = (new Date().format('yyyy-MM-dd'));
    }else if(g_ZoomType==ZoomEnum.MONTH){
        datevalue = (new Date().format('yyyy-MM'));
    }
    return datevalue;
}

//--------------------------------------------------------------------------------------------------------------------------------------------------//
// MARKET DEPTH CHART
//--------------------------------------------------------------------------------------------------------------------------------------------------//

// MARKET DEPTH CHART 생성
function createMarketdepthChart() {
		
	if(isChartDebug) console.log('createMarketdepthChart');
		
        Highcharts.setOptions({
		lang: {
			thousandsSep: ''
		}
            });
                
	Highcharts.chart('chartdiv', {
                navigation: {
                    buttonOptions: {
                        enabled: false
                    }
                },
                
                credits: {
                    enabled: false
                },
                
		chart: {
			type: 'areaspline',			
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			reversed: false,
			title: {
				enabled: true,
				text: ''
			},
			labels: {
				formatter: function () {
					return this.value;
				}
			},
			maxPadding: 0.05,
			showLastLabel: true
		},
		yAxis: {
			title: {
				text: ''
			},
			labels: {
				formatter: function () {
					return this.value;
				}
			},
			lineWidth: 2
		},
		legend: {
			enabled: false
		},
		tooltip: {
			headerFormat: '<span style="color:{series.color}">{series.name}</span><br/>',
			pointFormat: 'PRICE(KRW) : {point.x} <br>VOLUME(' + g_currencytype.toUpperCase() +') : {point.y}',
                        valueDecimals: 8          
		},
		plotOptions: {
			spline: {
				marker: {
					enable: false
				}
			}
		},
		series: [{
			name: 'BUY',
			data: [],
			//color : Highcharts.getOptions().colors[0],
			color : "#ff7e97",
		}, {
			name: 'SELL',
			data: [],
			//color : Highcharts.getOptions().colors[5],
			color : "#74b7ff",
		}]
	});
}

// 시장 현황(판매/구매)관련 변화가 생겼을때 (MARKET DEPTH)controller-trade.js 에서호출하는 이벤트를 받음
var g_preDataMarketDeps = null;
function onChartDrawMarketDeps(data) {
        
        
	if(g_cType != 'MD'){
            g_preDataMarketDeps = data; //차트변경시 초기화 데이터 필요
            return ;
                
        }
		
        if(!data) data = g_preDataMarketDeps;
        
     
	if(isChartDebug)
	{
		console.log("onChartDrawMarketDeps :" + g_preDataMarketDeps);
		console.log(data);
	}
	var mddata = JSON.parse(data);	
	var len = mddata.length;	
	var bsddata = [], askdata = [];		
	
	
	
	for(var i = 0; i < len ; i++)
	{
		if(mddata[i][0] == 'buy') {
			bsddata.push([mddata[i][1], mddata[i][2]]);
		}
		else if(mddata[i][0] == 'sell') {
			askdata.push([mddata[i][1], mddata[i][2]]);
		}
	}
	
	
	
	var chart = $('#chartdiv').highcharts();
	chart.series[0].setData(bsddata);
	chart.series[1].setData(askdata);
}
