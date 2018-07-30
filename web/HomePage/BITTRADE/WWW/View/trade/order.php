<head>
	<link href="<?= $view['url']['static'] ?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/index.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom_temp.css" rel="stylesheet">
   <link href="<?= $view['url']['static'] ?>/assets/css/jquery.mCustomScrollbar.min.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/jquery-validate.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/utils.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/perpect-scroll.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/decimal.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script><!-- 그래프 십진법 jquery 추가됨 -->
	<script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-comm.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-form.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<script src="<?= $view['url']['static'] ?>/assets/write/common-pagination.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	<!--
	<script src="<?= $view['url']['static'] ?>/assets/js/chart/highstock.js"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/chart/exporting.js"></script>
	-->
	<script src="<?= $view['url']['static'] ?>/assets/js/swiper.min.js"></script>
	<script>Config.initHeaderScript();setSocChannel('<?= $view['exchannel']['sock_ch'] ?>');</script>

	<!-- TradingView Begin -->
	<script src="../../charting_library/charting_library.min.js"></script>
	<script src="../../charting_library/datafeeds/udf/dist/polyfills.js"></script>
	<script src="../../charting_library/datafeeds/udf/dist/bundle.js"></script>
	<!-- TradingView End -->
	<script src="<?= $view['url']['static'] ?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/jquery.splendid.textchange.js"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/hangul.js"></script>
</head>
<style>
html, body {
	min-height: 100%;
}
div {
	box-sizing: border-box;
}
.wrap {
	height: 100%;
}
.flex-box {
	display: flex;
}
.flex-column {
	flex-direction: column;
}
.flex-row {
	flex-direction: row;
}
.mobile-nav-cart{ display: none}
#tv_chart_container {width: 100%; margin-top: 10px;}
#contents {max-width: 1400px;width: 100%;}
.market-list::-webkit-scrollbar { display: none;}
/* 상단 코인타입 */
.coin-status .this strong{ font-size: 24px; color: #005bc2;}
.coin-status .this {
    color: #000;
    line-height: 1.2;
    position: relative;
    height: 40px;  margin: 10px 0 0 0;}
.coin-status {
    position: relative;
    display: inherit;
    left: 0;
    top: 0;
    background: #fff;
   border-bottom: 1px solid #ccc;
    margin: 5px 0 0 0;
    z-index: 1
}
.coin-status .this p {
    font-size: 19px;
    color: #000;
    font-weight: bold;
    display: inline;
    padding: 10px;
    margin: 10px 0;
}
    .coin-status .status{ color: #000; font-size: 12px;
    line-height: 14px;
    margin: 10px 0 0 60px;}
    .coin-status .this span{  color: #000}
.coin-status .status dl dt {
    width: 40px;
    float: left;
 }
.coin-status .status dl {
    white-space: pre;
    float: left;
    display: block;
    margin: 0 10px 0 0;
    min-width: 100px;
}
.btn-ctype-search{
	-webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    height: auto;
    display: inline-block;
    font-size: 13px;
    border-radius: 10px;
    padding: 0 20px;
    line-height: 32px;
    margin: 0 auto;
    color: #fff;
    background-color: #005bc2;
    border: none;
    position: absolute;
    top: 7px;
    right: 10px;
}
.sort-box{ display: none;}
.trade-table table tr.blue{ background: #ecf2f8}
.trade-table table tr.red{ background: #f7ecea}
.trade-table table tbody tr.red td{ border: 1px solid #fff}
.trade-table table tbody tr.blue td{ border: 1px solid #fff}
.status-table {
    position: absolute;
    right: 0;
    top: 32px;
    width: 38%;
    height: 229px;
    box-sizing: border-box;
    padding: 8px;
    background: #fff;
}
    .status-table table th { text-align: left}
.in-table {
    bottom: 0;
    position: absolute;
    left: 0;
    bottom: 6px;
    height: 232px;
	background: #fff;
	padding: 8px;
    /* top: 20px; */
}
.temp_table th:last-child, .temp_table td:last-child {
	padding-right: 10px;
}
    .coin-status .status dl dd{ font-size: 16px}
    .trade-table table thead th:first-child{height: 30px}
    .coin-status .status p { font-size: 16px;  padding-top: 2px; display: block; float: left;   margin: 10px 0 0 0; }
.coin-trade .btn-buy { margin: 0 0 10px 0;  background: #cb1e00;  border-radius: 15px;  border: 1px solid #9c0000;  box-shadow: rgba(170, 179, 187, 0.6) 0px 8px 20px 0px;}
.coin-trade .btn-sell{ border-radius: 15px; background: #005bc2;  border: 2px solid #004fa9;  margin: 0 0 10px 0;  box-shadow: rgba(170, 179, 187, 0.6) 0px 8px 20px 0px;}
.coin-trade .form-section dl.tax dt{ line-height: 45px;}
/*.container{ min-height: 1500px;}*/
</style>
<script language="Javascript">
var tmpHost = window.location.host;
var tmpHostName = window.location.hostname;
var tmpPath = window.location.path;
var tmpProtocol = window.location.protocol;
var fullUrl = window.location.href;
var tmpUrl = fullUrl.replace(tmpProtocol + '//' + tmpHost, '');
	$(document).ready(function() {
        cookiedata = document.cookie;
        if (cookiedata.indexOf("ncookie=done") < 0) {
            document.getElementById('popup').style.display = "block";
        } else {
            document.getElementById('popup').style.display = "none";
        }

    });

    /* popup*/
    function setCookie(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

    function closeWin() {
		var agreeChecked = document.getElementById('AgreePolicy').checked;
		
		if(agreeChecked == true) {
			document.getElementById('popup').style.display = "none";
		} else {
			alert('\"상기 내용에 동의함\"에 체크 하셔야 합니다.');
		}
    }

    function todaycloseWin() {
        setCookie("ncookie", "done", 7);
    }
    
    function agree_policy() {
    var checkBox = document.getElementById("AgreePolicy");
    if (checkBox.checked == true) {
    	document.getElementById('popup-close').style.pointerEvents  = "auto";
		document.getElementById('popup-close').style.cursor = "pointer";
		document.getElementById('popup-close').classList.add( 'active' );
    } else {
		document.getElementById('popup-close').style.display = "block";
		document.getElementById('popup-close').classList.remove( 'active' );
    }
}
    
    
</script>
<body>
<!--modal notice-->  
 
<div  id="popup"  class="main-modal-wrap">
    <div class="modal-dialog-pop">
        <div class="modal-content-pop">
        	<h4>
				최근 암호화폐에 대한 관심과 함께 무리한 투자에 대한 우려도 함께 증가하고 있습니다.
				회원님들께서는 아래 유의사항을 충분히 숙지하신 후 이용 부탁드립니다.
			</h4>
            <div class="modal-body">
				1. 암호화폐는 법정화폐가 아니며, 특정 주체가 가치를 보장하지 않습니다. <br><br>
				2. 암호화폐는 365일 24시간 전 세계에서 거래되며, 투기적 수요 및 국내외 규제환경변화 등에 따라 급격한 시세 변동에 노출될수 있습니다. <br><br>
				3. 암호화폐에 대한 투자 판단의 책임은 본인에게 있으며, 발생 가능한 소실도 투자자 본인에게 귀속됩니다. <br>
				암호화폐의 특성을 충분히 인지하고 신중하게 거래해 주시기를 당부드립니다.
            </div>
			<!--modal content-->
            <div>
				<label>
					<input onclick="todaycloseWin();" type="checkbox" name="chkbox" value="checkbox">
					일주일간 열지 않음
				</label>
                <label style="margin-left:10px; color:#0075c5">
					<input id="AgreePolicy" onclick="agree_policy();" type="checkbox" name="chkbox" value="checkbox">
					상기내용에 동의함
				</label>
         		<a id="popup-close" href="javascript:onclick=closeWin();" class="modal-close btclose" style="cursor: no-drop; ">창닫기 </a>
            </div>
        </div>
    </div>
 </div> <!--// modal notice--> 
    
    
	<div id="wrap" class="trade-wrap">
        <script src="<?= $view['url']['static'] ?>/assets/write/header-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
        <script> $('.mobile-nav-bottom ul li a').eq(0).addClass('active');
          $('#gnb li a').eq(0).addClass('active');
        </script>
		<!-- New Trade -->
		<!-- NEW -->
		<div class="trade-slider-menu list">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<div class="swiper-slide"><a href="javascript:onShowCont('coin-trade')" class="active" ><?php Language::langConvert($view['lang'], 'trade'); ?></a></div>
					<div class="swiper-slide"><a href="javascript:onShowCont('history-end')"><?php Language::langConvert($view['lang'], 'recentTransaction'); ?></a></div>
					<div class="swiper-slide"><a href="javascript:onShowCont('history-ing')"><?php Language::langConvert($view['lang'], 'openOrders'); ?></a></div>
					<div class="swiper-slide"><a href="javascript:onShowCont('current-price')"><?php Language::langConvert($view['lang'], 'tradingWindow'); ?></a></div>
					<div class="swiper-slide"><a href="javascript:onShowCont('current-trade')"><?php Language::langConvert($view['lang'], 'realTimeStatus'); ?></a></div>
					<div class="swiper-slide"><a href="javascript:onShowCont('mine-stats')"><?php Language::langConvert($view['lang'], 'asset'); ?></a></div>
					<div class="swiper-slide"><a href="javascript:onShowCont('graph-box')"><?php Language::langConvert($view['lang'], 'chart'); ?></a></div>
				</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
			</div>
		</div>
		<!-- // NEW -->
		<style>
			@media(min-width: 769px) {
				body {
					background-color: #e9ecf1;
				}
				div {
					-webkit-box-sizing: border-box;
					-moz-box-sizing: border-box;
					box-sizing: border-box;
				}
				.container {
					max-width: 1400px;
					width: 100%;
					display: flex;
					align-content: center;
					margin: 5px auto 10px auto;
				}
				.row {
					margin: 0 -10px;
					width: 100%;
					min-width: 100%;
				}
				.row.price-wrap {
					margin: 0;
				}
				.row > div {
					padding: 6px;
				}
				.row.price-wrap > div {
					flex: 1;
					height: 510px;
					overflow: hidden;
				}
				.row.price-wrap > div:first-child {
					padding: 6px 0 6px 0;
				}
				.row.price-wrap > div:last-child {
					padding: 6px 0 6px 0;
				}
				.row > div.coin-info {
					width: 100%;
					max-width: 30%;
					min-height: 100%;
				}
				.row > div.coin-right {
					width: 100%;
					max-width: 70%;
				}
				.flex-beetween {
					justify-content: space-between;
				}
				.market-list,
				.market-list .market-table,
				.mine-stats,
				.mine-stats .stats-list {
					position: static;
                    
				}
				.current-price {
					position: relative;
				}
				.coin-trade,
				.coin-trade .scroll-area {
					position: static;
					height: calc(100% - 40px);
				}
				.graph-box {
					box-shadow: 2px 2px 4px #dee1e7;
				}
				.coin-trade .head {
					min-height: 40px;
				}
				.trade-history {
					position: static;
					background-color: #ffffff;
					box-shadow: 2px 2px 4px #dee1e7;
				}
				.market-list,
				.mine-stats,
				.current-price,
				.coin-trade {
					width: 100%;
					height: 100%;
					background-color: #ffffff;
					box-shadow: 2px 2px 4px #dee1e7;
				}
				.market-list,
				.mine-stats {
					overflow-y: auto;
				}
				.current-trade {
					background-color: #ffffff;
					box-shadow: 2px 2px 4px #dee1e7;
				}
				.current-trade,
				.current-trade .new-table .scroll-area {
					position: static;
					width: 100%;
				}
				.trade-table.list-trademarketcost {
					background-color: #ffffff;
				}
				.coin-trade .scroll-area form {
					flex: 1;
					display: flex;
					justify-content: space-between;
					flex-direction: column;
					height: 100%;
				}
				.tabs {margin-bottom: 0;}
				.tabs a, .tabs a.active {background-color: #ffffff;}
				#tab1, #tab2, #tab3 {display: none;}
				#tab1.active, #tab2.active, #tab3.active {display: block;height: 350px; overflow-y: auto;}
			}
			.coin-trade .form-section dl{ margin-bottom: 20px;}
			.tabs a{  border: none; }
			.new-table table thead th{ border-top: 1px solid #ddd}
			.trade-table table thead th{ background: #fff}
			#chartdiv {
				display: none;
			}
			@media(min-width: 769px) {
				.temp-table-wrap {
					display: none;
				}
                           			}
			@media(max-width: 768px) {
				.temp-table-wrap {
					margin-top: 120px;
				}
				.pc-view {
					/*display: none;*/
					opacity: 0;
					transform: scale(.001);
					position: absolute;
				}
			}
			@media(max-width: 768px) {
				.coin-status .status  {
					margin: 10px 0 0 0px;
				}
			}
			.currency-type-table #currency-type tr:hover,
			#won .temp_table #currency-type tr:hover {
				cursor: pointer;
			}
		</style>
		<div class="container pc-view">
			<div class="row flex-box flex-row">
				<div class="coin-info flex-box flex-column" id="dash-currency-main">
                   <div class="mobile-search-wrap"> 
                    	<form name="searchForm" id="searchForm" style="background: #fff url(../img/common/ico_m_search.png) 5px 10px;" >
							<input type="text" name="search" id="search" value="" placeholder="코인명 / 심볼 검색">
                    	</form>
					</div>
					<!--
                    <div class="ctype-main-tab">
                        <ul>
                            <li><a href="javascript:void(0);" class="active">원화거래 </a> </li>
                            <li><a href="javascript:void(0);">BTC거래</a> </li>
                            <li><a href="javascript:void(0);">ETH거래 </a></li>
                        </ul>
                    </div>
					-->
                    <table style="background:#fff; color:#686868; height:30px; border-bottom:1px solid #ccc"> 
                        <colgroup> 
                            <col width="100"> 
                            <col width="*"> 
                            <col width="90"> 
                        </colgroup> 
                        <tbody>
                            <tr> 
                                <th>코인명</th> 
                                <th class="right">현재가</th> 
                                <th class="right">전일대비</th> 
                            </tr> 
                        </tbody>
					</table>
					<!-- BIGIN :: 시장현황 -->
					<div class="market-list " style="height: 916px;">
						<div class="market-table scroll-area">
							<table class="currency-type-table">
								<colgroup>
									<col style="width:44%">
									<col style="width:30%">
									<col style="width:26%">
								</colgroup>
								<tbody id="currency-type">
									<?php
										foreach ($view['master'] as $key => $value) {
											$tmp = explode('_', $key);
											$cursymbol = $tmp[1];
									?>
									<tr class="<?= strtolower($key) ?>" onClick="changeCoin('krw-<?= strtolower($cursymbol) ?>')">
										<td>
											<div class="coin-kind">
												<i class="ico-coin ico-<?= strtolower($cursymbol) ?>"></i>
												<span><strong><?php Language::langConvert($view['langcommon'], $key); ?></strong><br><em><?= $cursymbol ?>/KRW</em></span>
											</div>
										</td>
										<td class="price def_price">0</td>
										<td class="def_per_change color_controller">0%</td>
									</tr>
									<?php
										}
									?>
									<?php
										$temp_coin = ['리플코인', '유니키', '피스코인', '비트카르타스', '패비오시스스페셜', '더블유쓰리',
											'애니코인', '이오스', '에이다', '트론', '퀀텀', '스팀', '네오', '스텔라루멘', '온톨로지', '스트라티스',
											'스팀달러', '파워렛저', '지캐시', '오미세고', '어거', '블록틱스', '메탈', '디크레드', '아더', '이그니스'];
										$temp_symb = ['XRP/KRW', 'UNIKEY/KRW', 'PEC/KRW', 'BCS/KRW', 'PCS/KRW', 'W3/KRW',
											'ANY/KRW', 'EOS/KRW', 'ADA/KRW', 'TRX/KRW', 'QTUM/KRW', 'STEEM/KRW', 'NEO/KRW', 'XLM/KRW', 'ONT/KRW', 'STRAT/KRW',
											'SBD/KRW', 'POWR/KRW', 'ZEC/KRW', 'OMG/KRW', 'REP/KRW', 'TIX/KRW', 'MTL/KRW', 'DCR/KRW', 'ARDR/KRW', 'IGNIS/KRW'];
										for ($k = 0, $c = count($temp_coin); $k < $c; $k++) {
											$tmpReadyName = 'ready_'.$temp_coin[$k];
									?>
									<tr class="" onClick="changeCoin('<?php echo $tmpReadyName; ?>')">
										<td>
											<div class="coin-kind">
												<i class="ico-coin"></i>
												<span><strong><?= $temp_coin[$k] ?></strong><br><em><?= $temp_symb[$k] ?></em></span>
											</div>
										</td>
										<td class="price def_price">-</td>
										<td class="def_per_change color_controller">준비중</td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END :: 시장현황 -->
					<!-- BIGIN ::보유자산현황 -->
					<div class="mine-stats" style="height: 245px;  margin-top: 10px;">
						<h4><?php Language::langConvert($view['lang'], 'assets'); ?></h4>
						<!-- <h5><?php // Language::langConvert($view['lang'], 'totalAssets'); ?><span><em class="total-assets">0</em> KRW</span> </h5>-->
						<div class="stats-list scroll-area">
							<dl>
								<dt><span class="ico-coin ico-krw"></span><em><?= Language::langConvert($view['langcommon'], 'KRW_KRW'); ?></em></dt>
								<dd><span class="mb_krw_total">0</span> <?= Language::langConvert($view['langcommon'], 'keyCurrency'); ?></dd>
							</dl>
							<?php
							foreach ($view['master'] as $key => $value) {
								$tmp = explode('_', $key);
								$cursymbol = $tmp[1];
							?>
							<dl>
								<dt><span class="ico-coin ico-<?= strtolower($cursymbol) ?>"></span><em><?php Language::langConvert($view['langcommon'], $key); ?></em></dt>
								<dd><span class="mb_<?= strtolower($cursymbol) ?>_total">0</span> <?= $cursymbol ?></dd>
							</dl>
							<?php
								}
							?>
						</div>
					</div>
					<!-- END :: 보유자산현황 -->
				</div>
				<div class="coin-right" id="dash-currency-main2">
                    <div class="coin-status" id="coinStatus">
						<div class="this <?= $view['exchannel']['sock_ch'] ?>">
							<p>
								<?= Language::langConvert($view['langcommon'], 'KRW_' . $view['exchannel']['currency_type']) ?> (<?= $view['exchannel']['currency_type'] ?>)
							</p>
							<strong class="def_price">0</strong><span>KRW</span>
						</div>
						<div class="status <?= $view['exchannel']['sock_ch'] ?>">
							<dl>
								<dt><?= Language::langConvert($view['langcommon'], 'high'); ?></dt>
								<dd class="def_24hr_high red">0</dd>
							</dl>
							<dl>
								<dt><?= Language::langConvert($view['langcommon'], 'low'); ?></dt>
								<dd class="def_24hr_low blue">0</dd>
							</dl>
							<p class="color_controller white"><strong class="def_price_gap">0</strong><span class="def_per_change">0%</span></p>
						</div>
						<a href="#" class="btn-m-coins"></a>
					</div><!-- // coin-status -->
                    <!-- BIGIN :: Graph -->
					<div class="graph-box">
						<div id="tv_chart_container"></div>
					</div>
					<!-- END :: Graph -->
					<div class="row price-wrap flex-box flex-row" style="margin-top: 5px;margin-bottom: 5px;height: 510px;">
						<div>
							<!-- BIGIN :: 가격 -->
							<div class="current-price">
								<div class="trade-table list-trademarketcost" style="height: 100%;">
									<table style="height: calc(100% - 10px);">
										<colgroup>
											<col style="width:42%">
											<col style="width:20%">
											<col style="width:38%">
										</colgroup>
										<thead>
											<tr>
												<th><?php Language::langConvert($view['lang'], 'remainSell'); ?></th>
												<th><?php Language::langConvert($view['lang'], 'price'); ?></th>
												<th><?php Language::langConvert($view['lang'], 'remainBuy'); ?></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<div class="in-table list-tradevolume">
									<table>
										<colgroup>
											<col style="width:50%">
											<col style="width:50%">
										</colgroup>
										<thead>
											<tr>
												<th><?php Language::langConvert($view['lang'], 'orderPrice'); ?></th>
												<th><?php Language::langConvert($view['lang'], 'orderAmount'); ?></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<div class="status-table">
									<table>
										<tbody class="<?= $view['exchannel']['sock_ch'] ?>">
											<tr>
												<th><?php Language::langConvert($view['langcommon'], 'current'); ?></th>
												<td><strong class="black def_price">0</strong></td>
											</tr>
											<tr>
												<th><?php Language::langConvert($view['langcommon'], 'change'); ?></th>
												<td><span class="def_per_change color_controller">0%</span></td>
											</tr>
											<tr>
												<th><?php Language::langConvert($view['langcommon'], 'high'); ?></th>
												<td><span class="blue def_24hr_high">0</span> </td>
											</tr>
											<tr>
												<th><?php Language::langConvert($view['langcommon'], 'low'); ?></th>
												<td><span class="red def_24hr_low">0</span> </td>
											</tr>
											<tr>
												<th><?php Language::langConvert($view['langcommon'], 'volume'); ?></th>
												<td><span class="def_volume">0</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- END :: 가격 -->
						</div>
						<div class="buy-sell-wrap">
                            <!-- BIGIN :: 구매 / 판매 -->
							<div class="coin-trade" id="order-form-area">
								<div class="head">
									<p class="title"><?php Language::langConvert($view['langcommon'], 'buy'); ?></p>
									<p class="title"><?php Language::langConvert($view['langcommon'], 'sell'); ?></p>
								</div>
								<div class="scroll-area flex-box">
									<!-- 구매 -->
									<form class="form-exchange trade-buy" id="order-form-buy" onsubmit="return false">
										<input type="hidden" name="token" value="<?= $view['token'] ?>">

										<div>
											<div class="mine">
												<div class="available">
													<span><?php Language::langConvert($view['lang'], 'availableKrw'); ?></span>
													<strong><span class="mb_krw_poss">0</span>KRW</strong>
												</div>
											</div>

											<div class="form-section">
												<dl>
													<dt><?php Language::langConvert($view['langcommon'], 'buyAmount'); ?></dt>
													<dd>
														<div class="inp-group">
															<input type="text" class="inp input-calculation only-coin" name="input_amount" maxlength="17" value="0.00000000">
															<div class="inp-button">
																<button class="btn-max" id="btn_max"><?php Language::langConvert($view['lang'], 'btnMax'); ?></button>
															</div>
														</div>
														<div id="input_btc_alert"></div>
													</dd>
												</dl>

												<dl>
													<dt><?php Language::langConvert($view['langcommon'], 'buyAmountPrice'); ?>(1<?= $view['exchannel']['currency_type'] ?>)</dt>
													<dd>
														<div class="inp-group" id="input_price_parent">
															<input type="text" class="inp input-calculation input-rounding-krw" name="input_price" maxlength="17" placeholder="0">
															<div class="inp-button">
																<button class="btn-plus" id="arrow_up_krw"><i class="fa fa-plus"></i></button>
																<button class="btn-minus" id="arrow_down_krw"><i class="fa fa-minus"></i></button>
															</div>
														</div>
														<div id="input_price_alert"></div>
													</dd>
												</dl>

												<dl class="total-price">
													<dt><?php Language::langConvert($view['langcommon'], 'buyPriceTotal'); ?></dt>
													<dd>
														<div class="inp-group">
															<input type="text" class="inp input-calculation" name="input_total" placeholder="0" readonly>
															<div class="inp-button">
																<span><?php Language::langConvert($view['langcommon'], 'currentCurrency'); ?></span>
															</div>
														</div>
														<div id="input_total_alert"></div>
													</dd>
												</dl>
											</div>
											<div class="form-section">
												<dl class="tax">
													<dt><?php Language::langConvert($view['langcommon'], 'fee'); ?> : <?php Language::langConvert($view['lang'], 'marginMakerFee'); ?>(<?= $view['maker_fee_per'] ?>%),<?php Language::langConvert($view['lang'], 'marginTakerFee'); ?>(<?= $view['tracker_fee_per'] ?>%)</dt>
													<dd><span id="trade_fee">0.00000000</span> <?= $view['exchannel']['currency_type'] ?></dd>
												</dl>
												<dl class="total-purchase">
													<dt><?php Language::langConvert($view['langcommon'], 'totalBuyAmount'); ?></dt>
													<dd><span id="trade_receipt">≈ 0.00000000</span> <?= $view['exchannel']['currency_type'] ?></dd>
												</dl>
											</div>
										</div>
										<div class="btn-box">
											<button class="btn-buy" id="btn_buy_submit" ><?php Language::langConvert($view['langcommon'], 'buyButton'); ?></button>
										</div>
									</form>
									<!-- // 구매 -->

									<!-- 판매 -->
									<form class="form-exchange trade-sell" id="order-form-sell" onsubmit="return false">
										<div>
											<div class="mine">
												<div class="available">
													<span><?php Language::langConvert($view['langcommon'], 'available'); ?></span>
													<strong><span class="mb_<?= strtolower($view['exchannel']['currency_type']) ?>_poss">0.00000000</span> <?= $view['exchannel']['currency_type'] ?></strong>
												</div>
											</div>
											<div class="form-section">
												<dl>
													<dt><?php Language::langConvert($view['langcommon'], 'sellAmount'); ?></dt>
													<dd>
														<div class="inp-group">
															<input type="text" class="inp input-calculation only-coin" name="input_amount" maxlength="17" value="0.00000000">
															<div class="inp-button">
																<button class="btn-max" id="btn_max"><?php Language::langConvert($view['lang'], 'btnMax'); ?></button>
															</div>
														</div>
														<div id="input_btc_alert_sell"></div>
													</dd>
												</dl>
												<dl>
													<dt><?php Language::langConvert($view['langcommon'], 'sellAmountPrice'); ?>(1<?= $view['exchannel']['currency_type'] ?>)</dt>
													<dd>
														<div class="inp-group" id="input_price_parent_sell">
															<input type="text" class="inp input-calculation input-rounding-krw" name="input_price" maxlength="17" placeholder="0">
															<div class="inp-button">
																<button class="btn-plus" id="arrow_up_krw_sell"><i class="fa fa-plus"></i></button>
																<button class="btn-minus" id="arrow_down_krw_sell"><i class="fa fa-minus"></i></button>
															</div>
														</div>
														<div id="input_price_alert_sell"></div>
													</dd>
												</dl>

												<dl class="total-price">
													<dt><?php Language::langConvert($view['langcommon'], 'sellPriceTotal'); ?></dt>
													<dd>
														<div class="inp-group">
															<input type="text" class="inp input-calculation" name="input_total" placeholder="0" readonly>
															<div class="inp-button">
																<span><?php Language::langConvert($view['langcommon'], 'currentCurrency'); ?></span>
															</div>
														</div>
														<div id="input_total_alert_sell"></div>
													</dd>
												</dl>
											</div>

											<div class="form-section">
												<dl class="tax">
													<dt><?php Language::langConvert($view['langcommon'], 'fee'); ?> : <?php Language::langConvert($view['lang'], 'marginMakerFee'); ?>(<?= $view['maker_fee_per'] ?>%),<?php Language::langConvert($view['lang'], 'marginTakerFee'); ?>(<?= $view['tracker_fee_per'] ?>%)</dt>
													<dd><span id="trade_fee">0</span><?php Language::langConvert($view['langcommon'], 'currentCurrency'); ?></dd>
												</dl>

												<dl class="total-purchase">
													<dt><?php Language::langConvert($view['langcommon'], 'sellPriceTotal'); ?></dt>
													<dd><span id="trade_receipt">≈ 0</span><?php Language::langConvert($view['langcommon'], 'currentCurrency'); ?></dd>
												</dl>
											</div>
										</div>
										<div class="btn-box">
											<button class="btn-sell" id="btn_sell_submit"><?php Language::langConvert($view['langcommon'], 'sellButton'); ?></button>
										</div>
									</form>
								</div>
							</div>
							<!-- END :: 구매 / 판매 -->
						</div>
					</div>
					<!-- BIGIN :: 최근 체결 내역 / 미체결 주문 -->
					<div class="tabs">
						<a href="#tab1" class="active">최근체결내역</a>
						<a href="#tab2">미체결주문</a>
						<a href="#tab3">실시간 체결 현황</a>
					</div>
					<div class="trade-history">
						<div class="history-end active" id="tab1">
							<div class="new-table">
								<table>
									<colgroup>
										<col style="width:10%">
										<col style="width:">
										<col style="width:17%">
										<col style="width:17%">
										<col style="width:17%">
										<col style="width:17%">
										<col style="width:10%">
									</colgroup>
									<thead>
										<tr>
											<th><?php Language::langConvert($view['lang'], 'ordersHistoryType'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'ordersHistoryOrdersDate'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'ordersHistoryFullfilled'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'ordersHistoryAvg'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'ordersHistoryTotal'); ?></th>
											<th><?php Language::langConvert($view['langcommon'], 'fee'); ?></th>
											<th><!--<i class="xi-plus-circle"></i>--></th>
										</tr>
									</thead>
								</table>
								<div class="scroll-area">
									<table class="list-tradecomplete">
										<colgroup>
											<col style="width:10%">
											<col style="width:">
											<col style="width:17%">
											<col style="width:17%">
											<col style="width:17%">
											<col style="width:17%">
											<col style="width:10%">
										</colgroup>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="history-ing" id="tab2">
							<div class="new-table">
								<table>
									<colgroup>
										<col style="width:10%">
										<col style="width:">
										<col style="width:12%">
										<col style="width:12%">
										<col style="width:12%">
										<col style="width:12%">
										<col style="width:12%">
										<col style="width:10%">
									</colgroup>
									<thead>
										<tr>
											<th><?php Language::langConvert($view['lang'], 'openOrdersType'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'openOrdersDate'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'openOrdersPrice'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'oepnOrdersAmount'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'openOrdersPositions'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'openOrderAvg'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'openOrdersTotal'); ?></th>
											<th><?php Language::langConvert($view['lang'], 'openOrdersStatus'); ?></th>
										</tr>
									</thead>
								</table>
								<div class="scroll-area">
									<table class="list-tradenocomplete">
										<colgroup>
											<col style="width:10%">
											<col style="width:">
											<col style="width:12%">
											<col style="width:12%">
											<col style="width:12%">
											<col style="width:12%">
											<col style="width:12%">
											<col style="width:10%">
										</colgroup>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- END :: 최근 체결 내역 / 미체결 주문 -->
					<!-- BIGIN :: 실시간 거래현황 -->
					<div class="current-trade" id="tab3">
						<!--
						<h4><?php // Language::langConvert($view['lang'], 'realTimeTrade'); ?></h4>
						-->
						<div class="new-table">
							<table>
								<colgroup>
									<col style="width:25%">
									<col style="width:25%">
									<col style="width:25%">
									<col style="width:25%">
								</colgroup>
								<thead>
									<tr>
										<th>거래</th>
										<th><?php Language::langConvert($view['lang'], 'time'); ?></th>
										<th><?php Language::langConvert($view['lang'], 'price'); ?></th>
										<th><?php Language::langConvert($view['lang'], 'amount'); ?></th>
									</tr>
								</thead>
							</table>
							<div class="scroll-area list-realtradevolume">
								<table>
									<colgroup>
										<col style="width:25%">
										<col style="width:25%">
										<col style="width:25%">
										<col style="width:25%">
									</colgroup>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- END :: 실시간 거래현황 -->
				</div>
			</div>
		</div>

		<!-- ################# TEMP TABLE -->
		<div class="temp-table-wrap list">
			<!--//fixed content-->
			<div class="mobile-search-fixed"> 
				<div class="mobile-search-wrap"> 
					<form name="searchForm2" id="searchForm2">
						<input type="text" name="m_search" id="m_search" value="" placeholder="코인명 / 심볼 검색">
					</form>
				</div> <!--search -->
				<!--
				<div class="mobile-main-tab">
					<ul>
						<li> <a href="#won" class="round-btn active"> 원화거래</a></li>
						<li> <a href="#btc" class="round-btn">BTC 거래 </a></li>
						<li><a href="#eth" class="round-btn">ETH 거래 </a></li>
					</ul>
				</div>
				-->
				<table class="temp_table">
					<colgroup>
						<col width="100">
						<col width="*">
						<col width="120">
					</colgroup>
					<tr>
						<th>코인명</th>
						<th class="right">현재가</th>
						<th class="right">전일대비</th>
					</tr>
				</table>
				
			</div> <!--//fixed content-->
			<div class="mobile-search-fixed-margin"> </div>
			
			<div id="won">
				<table class="temp_table">
					<colgroup>
						<col width="10">
						<col width="">
						<col width="">
						<col width="">
					</colgroup>
					<!--<thead>
						<tr>
							<th colspan="2">코인명</th>
							<th>현재가</th>
							<th>전일대비</th>
						</tr>
					</thead>-->
					<tbody id="currency-type">
						<?php 
							foreach ($view['master'] as $key => $value) {
								$tmp = explode('_', $key);
								$cursymbol = $tmp[1];
						?>
						<tr class="<?=strtolower($key)?>" onClick="changeCoin('krw-<?=strtolower($cursymbol)?>')">
							<td>
								<div class="up-down-icon"></div>
							</td>
							<td class="tit">
								<div class="coin-kind">
									<!--<i class="ico-coin ico-<?php // echo(strtolower($cursymbol)); ?>"></i>-->
									<span><strong><?php Language::langConvert($view['langcommon'], $key);?></strong><br><em><?=$cursymbol?>/KRW</em></span>
								</div>
							</td>
							<td class="price def_price color_controller">0</td>
							<td class="def_per_change color_controller">0%</td>
						</tr>
						<?php }?>
						
					<?php 
						$coin_names = ['리플코인', '유니키', '피스코인', '비트카르타스', '패비오시스스페셜', '더블유쓰리', '애니코인', '이오스', '에이다', '트론', '퀀텀', '스팀', '네오',
							'스텔라루멘', '온톨로지', '스트라티스', '스팀달러', '파워렛저', '지캐시', '오미세고', '어거', '블록틱스', '메탈', '디크레드', '아더', '이그니스'];
						$coin_symbols = ['XPR/KRW', 'UNIKEY/KRW', 'PEC/KRW', 'BCS/KRW', 'PCS/KRW', 'W3/KRW', 'ANY/KRW', 'EOS/KRW', 'ADA/KRW', 'TRX/KRW', 'QTUM/KRW', 'STEEM/KRW', 'NEO/KRW',
							'XLMKRW', 'ONT/KRW', 'STRAT/KRW', 'SBD/KRW', 'POWR/KRW', 'ZEC/KRW', 'OMG/KRW', 'REP/KRW', 'TIX/KRW', 'MTL/KRW', 'DCR/KRW', 'ARDR/KRW', 'IGNIS/KRW'];

						for ($i = 0, $c = count($coin_names); $i < $c; $i++)
						{
							$tmpReadyName = 'ready_'.$coin_names[$i];
					?>
						<tr class="" onClick="changeCoin('<?php echo $tmpReadyName; ?>')"> 
							<td> <div class="up-down-icon bar-down"></div> </td> 
							<td class="tit">
								<div class="coin-kind">
									<span>
										<strong><?=$coin_names[$i]?></strong><br>
										<em><?=$coin_symbols[$i]?></em>
									</span>
								</div>
							</td> 
							<td>-</td> 
							<td>준비중</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div id="btc"></div>
			<div id="eth"></div>
		</div>
		<!-- ################# TEMP TABLE -->

		<!-- trade Update Modal -->
		<div class="modal fade" id="tradeUpdateModal" tabindex="-1" role="dialog" aria-labelledby="tradeUpdateModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"> <?php  Language :: langConvert ( $view [ 'lang' ], 'tradeUpdateGuideTitle' ) ;  ?> </h4>
					</div>
					<div class="modal-body">
						<table class="table table-striped table-hover list-tradeUpdate">
							<thead>
								<tr>
									<th class="text-align-right"></th>
									<th class="text-align-right"> <?php  Language :: langConvert ( $view [ 'lang' ], 'tradeUpdateOrdersAmount' ) ;  ?> </th>
									<th class="text-align-right"> <?php  Language :: langConvert ( $view [ 'lang' ], 'tradeUpdateOrdersPrice' ) ;  ?> </th>
									<th class="text-align-right"> <?php  Language :: langConvert ( $view [ 'lang' ], 'tradeUpdateOrdersTotal' ); ?></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
							<br />
							<?php Language::langConvert($view[ 'lang'], 'tradeUpdateDesc'); ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default btn_update-cancel" id="btn_update_cancel" data-dismiss="modal"><?php Language::langConvert($view[ 'lang' ], 'btnCancel' ) ;  ?>   </button>
						<button type="button" class="btn btn-danger" id="btn_update_submit"> <?php  Language :: langConvert( $view [ 'lang' ], 'tradeUpdateBtnUpdateRequest' ) ;  ?></button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- MODAL -->
		<div class="modal fade" id="buyBtcModal" tabindex="-1" role="dialog" aria-labelledby="buyBtcModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><?php Language::langConvert($view['lang'], 'buyGuideTitle'); ?></h4>
					</div>
					<div class="modal-body">
						<?php Language::langConvert($view['lang'], 'buyOrderDesc1'); ?> <span class="show_krw"></span> <?php Language::langConvert($view['lang'], 'buyOrderDesc2'); ?> <span class="show_btc"></span> <?php Language::langConvert($view['lang'], 'buyOrderDesc3'); ?><br /><br />
						<b><?php Language::langConvert($view['lang'], 'buyOrderDesc4'); ?> : <span class="show_krw"></span> <?php Language::langConvert($view['langcommon'], 'currentCurrency'); ?></b> (<?php Language::langConvert($view['lang'], 'buyOrderDesc5'); ?> : <span class="show_price"></span> <?php Language::langConvert($view['langcommon'], 'currentCurrency'); ?>)<br /><br />
						<b><?php Language::langConvert($view['lang'], 'buyOrderDesc6'); ?> : <span class="show_btc"></span> <?= $view['exchannel']['currency_type'] ?></b> (<?php Language::langConvert($view['lang'], 'buyOrderDesc7'); ?> : <span class="show_fee"></span> <?php Language::langConvert($view['lang'], 'buyOrderDesc8'); ?>)<br /><br />
						<?php Language::langConvert($view['lang'], 'buyOrderDesc9'); ?><br />
					</div>
					<div class="modal-footer">
						<span class="pull-left glyphicon glyphicon-time"></span><p class="pull-left">&nbsp;</p>
						<span class="pull-left show_remain_time"><?php Language::langConvert($view['lang'], 'remainTime'); ?> :&nbsp;</span>
						<button type="button" class="btn btn-default btn_submit-cancel" id="btn_submit-cancel" data-dismiss="modal"><?php Language::langConvert($view['lang'], 'btnCancel'); ?></button>
						<button type="button" class="btn btn-danger" id="btn_submit"><?php Language::langConvert($view['lang'], 'btnBuyRequest'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<!-- // MODAL -->
		<script src="<?= $view['url']['static'] ?>/assets/write/footer-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
	</div>
	<script>
		(function($){
			$(window).on("load",function(){
				$(".market-list").mCustomScrollbar({
					scrollButtons:{enable:true,scrollType:"stepped"},
					keyboard:{scrollType:"stepped"},
					mouseWheel:{scrollAmount:188},
					theme:"rounded-dark",
					autoExpandScrollbar:true,
					snapAmount:188,
					snapOffset:65
				});
			});
		})(jQuery);
	</script>
    <script>Config.initFooterScript();</script>
    <!-- <script src="<?= $view['url']['static'] ?>/assets/script/controller-chart.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script> -->
    <!--<script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-chart.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>-->
    <script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-market.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-trade.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script>
		// 수수료 값 삽입
		$(document).ready(function () {
			console.log('cache_expire = ' + '<?php echo $cfg['cache_expire']; ?>');
			$('.trade-slider-menu a').click(function () {
				$(this).addClass('active').parent().siblings().find('a').removeClass('active');
			});
			$('.mobile-main-tab a').on('click', function (e) {
				var tmp_id = $(this).attr('id');
				e.preventDefault();
			});
			$('.tabs').on('click', 'a', function (e) {
				var tabTarget = $(this).attr('href');
				var tmpTarget = "";
				$('.tabs a').each(function () {
					$(this).removeClass('active');
					tmpTarget = $(this).attr('href');
					$(tmpTarget).removeClass('active');
					console.log('t\,삭제' + tmpTarget);
				});
				$(this).addClass('active');
				$(tabTarget).addClass('active');
				console.log(tabTarget);
				e.preventDefault();
			});

			var swiper = new Swiper('.trade-slider-menu .swiper-container', {
				slidesPerView: 'auto',
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
			});

			$('.btn-m-coins').click(function () {
				$(this).toggleClass('active');
				$('.market-list ').stop().slideToggle(250);
			});

			$('.scroll-area').perfectScrollbar();
			$(".krw_<?= strtolower($view['exchannel']['currency_type']) ?>").addClass("active");

			// 거래소 수수료
			tradePageSet.trade_maker_fee = <?= $view['maker_fee'] ? (float) $view['maker_fee_per'] : 0.0 ?>;
			tradePageSet.trade_tracker_fee = <?= $view['tracker_fee'] ? (float) $view['tracker_fee_per'] : 0.0 ?>;

			// 거래 금액
			tradePageSet.trade_krw_min_limit = <?= $view['trade_krw_min_limit'] ? (float) $view['trade_krw_min_limit'] : 10000 ?>;
			tradePageSet.trade_coin_min_limit = <?= $view['trade_coin_min_limit'] ? (float) $view['trade_coin_min_limit'] : 0.001 ?>;
			tradePageSet.trade_call_unit_krw = <?= $view['call_unit_krw'] ? (float) $view['call_unit_krw'] : 10000 ?>;
			tradePageSet.trade_call_unit_coin = <?= $view['call_unit_coin'] ? (float) $view['call_unit_coin'] : 0.001 ?>;

			$(".<?= strtolower($view['exchannel']['currency_type']) ?>").addClass("active");
			changeCurrencyChartItem('<?= $view['exchannel']['currency_type'] ?>');

			initListData();

			var is_reponse_mobile = <?= $view['is_mobile'] ?>;
			if (is_reponse_mobile == 1 && isMobile())
			{
				$('.temp-table-wrap').hide();
				$('.trade-slider-menu').removeClass('list');
				$('.coin-trade').show();
			}


			TradingView.onready(function ()
			{
				var widget = window.tvWidget = new TradingView.widget({
					fullscreen: true,
					//interval: '1S',
					interval: '5',
					symbol: '<?= $view['currency'] ?>',
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
						//"header_indicators",
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
					//locale: getParameterByName('lang') || "ko",
					locale: Utils.getLanguage(),
					allow_symbol_change: false,
					//	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
					drawings_access: {type: 'black', tools: [{name: "Regression Trend"}]},
					//header_layouttoggle: 'off',
					debug: false
					//charts_storage_api_version: "1.1",
					//client_id: 'tradingview.com',
					//user_id: 'public_user_id'
				});


				widget.onChartReady(function () {
					TradingView_BarManager.setTime(Date.now());
					TradingView_BarManager.runTimer();
					widget.chart().createStudy('Moving Average', false, false, [15, "close"], null, {"Plot.color" : "blue"});
					widget.chart().createStudy('Moving Average', false, false, [60, "close"], null, {"Plot.color" : "red"});
					$('#tv_chart_container iframe').contents().find('span.tv-close-panel').trigger('click');
				});
				var tvWidth = $('#tv_chart_container iframe').width();
				if($(window).width() > 768) {
					var tvHeight = tvWidth / 5 * 2;
				} else {
					var tvHeight = tvWidth;
				}
				$('#tv_chart_container iframe').height(tvHeight);

				function marketListHeight() {
					var rightHeight = $('.coin-right').height();
					var coinInfoHeight = $('.coin-info .mine-stats').outerHeight(true);
					var calcContentHeight = rightHeight - coinInfoHeight - 85;
					$('.coin-info .market-list').height(calcContentHeight);
				}
				marketListHeight();

				$(window).resize(function() {
					//marketListHeight();
					tvWidth = $('#tv_chart_container iframe').width();
					if($(window).width() > 768) {
						tvHeight = tvWidth / 5 * 2;
					} else {
						tvHeight = tvWidth;
					}

					$('#tv_chart_container iframe').height(tvHeight);
				});
			});

			var sellBuyTableHeight = $('.trade-table.list-trademarketcost').height() - 40;
			var inTableHeight = (sellBuyTableHeight / 2) - 16;
			$('.status-table table').height(inTableHeight);
			$('.in-table table').height(inTableHeight);
		});
		/* BIGIN :: PC Search */
		var f = document.searchForm;
		var originData = $('.market-list .market-table table #currency-type').html();
		var originDataLen = $('.currency-type-table #currency-type tr').length;
		var trAry = new Array();
		var tmpSearchOrigin = new Array();
		var trHtml = '';
		var trData = '';
		var trClass = '';
		var trEvent = '';
		for(var i = 0; i < originDataLen; i++) {
			trClass = $('.currency-type-table #currency-type tr').eq(i).attr('class');
			trEvent = $('.currency-type-table #currency-type tr').eq(i).attr('onclick');
			trHtml = '<tr class="' + trClass + '" onclick="' + trEvent + '">';
			trHtml += $('.currency-type-table #currency-type tr').eq(i).html();
			trHtml += '</tr>';
			trAry[i] = trHtml;
			trData = $('.currency-type-table #currency-type tr').eq(i).text();
			tmpSearchOrigin[i] = trData.toUpperCase();
		}

		$('#search').on('keydown', function(key) {
			if(key.keyCode == 13) {
				return false;
			}
		});

		$('#search').on('textchange', function() {
			var searchVal = f.search.value;
			searchVal = searchVal.toUpperCase();
			if(searchVal == '') {
				$('.market-list .market-table table #currency-type').html(originData);
			} else {
				$('.market-list .market-table table #currency-type').html('');
				for(var i = 0; i < trAry.length; i++) {
					if(tmpSearchOrigin[i].indexOf(searchVal) != -1) {
						$('#currency-type').append(trAry[i]);
					}
				}
			}
		});
		/* END :: PC Search */
		/* BIGIN :: Mobile Search */
		var f2 = document.searchForm2;
		var originDataM = $('#won .temp_table #currency-type').html();
		var originDataMLen = $('#won .temp_table #currency-type tr').length;
		var trMAry = new Array();
		var tmpMSearchOrigin = new Array();
		var trMHtml = '';
		var trMData = '';
		var trMClass = '';
		var trMEvent = '';
		for(var i = 0; i < originDataMLen; i++) {
			trMClass = $('.currency-type-table #currency-type tr').eq(i).attr('class');
			trMEvent = $('.currency-type-table #currency-type tr').eq(i).attr('onclick');
			trMHtml = '<tr class="' + trMClass + '" onclick="' + trMEvent + '">';
			trMHtml += $('#won .temp_table #currency-type tr').eq(i).html();
			trMHtml += '</tr>';
			trMAry[i] = trMHtml;
			trMData = $('#won .temp_table #currency-type tr').eq(i).text();
			tmpMSearchOrigin[i] = trMData.toUpperCase();
		}

		$('#m_search').on('keydown', function(key) {
			if(key.keyCode == 13) {
				return false;
			}
		});

		$('#m_search').on('textchange', function() {
			var searchMVal = f2.m_search.value;
			searchMVal = searchMVal.toUpperCase();
			if(searchMVal == '') {
				$('#won .temp_table #currency-type').html(originDataM);
			} else {
				$('#won .temp_table #currency-type').html('');
				for(var i = 0; i < trMAry.length; i++) {
					if(tmpMSearchOrigin[i].indexOf(searchMVal) != -1) {
						$('#won .temp_table #currency-type').append(trMAry[i]);
					}
				}
			}
		});
		/* END :: Mobile Search */
		// modal close event
		$('#buyBtcModal').on('hidden.bs.modal',
			function (e) {
				tradePageSet.order_status = false;
				tradePageSet.dom_btn_order.attr('disabled', false);
			}
		);

		function onShowCont(target) {
			$('#dash-currency-main > div').hide();
			if (target == "history-end") {
				$('.history-ing').hide();
				$('.trade-history').show();
			}
			if (target == "history-ing") {
				$('.history-end').hide();
				$('.trade-history').show();
			}
			$('.' + target).show();
		}

		// 시장현황 click event
		function changeCoin(ctype) {
			var readyType = ctype.indexOf('ready_');
			var readyName = ctype.replace('ready_', '');
			if(readyType != -1) {
				//alert('');
				alert('[' + readyName + '] 코인은 현재 준비중 입니다,');
				return false;
			}
			//alert(ctype);
			//var x = window.matchMedia("(max-width: 768px)");
			//var test = "";
			//if (x.matches)
			//	test = ctype + "&" + "mobile";
			//else
			//	test = ctype;
			//alert(test);
			//location.href = '/trade/order/'+ctype;
			//location.href = '/trade/order/'+test;
			if (isMobile())
				location.href = '/trade/order_m/' + ctype;
			else
				location.href = '/trade/order/' + ctype;

			//$('.temp-table-wrap').hide();
			//$('.temp-table-wrap').addClass('hidden');
			//$('.trade-slider-menu').removeClass('list');
			//$('.coin-trade').show();
		}
		// 그래프 호출
		var domchart = $('#main-market-chart');
		var curseltype = '';

		function changeCurrencyChartItem(cointype) {
			if (curseltype == cointype) {
				domchart.toggle();
				$('#' + cointype).toggleClass('active');
			} else {
				//debugger;
				domchart.show();

				$('#' + cointype).addClass('active').siblings().removeClass('active');

				//createChart(cointype, 'TL');
				//generateChartDataMain(cointype);
			}
			curseltype = cointype;
		}

		function isMobile()
		{
			var x = window.matchMedia("(max-width: 768px)");
			return x.matches;
		}

		// console.log("get_linke: " + get_link);

		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
					results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}

    </script>

</body>