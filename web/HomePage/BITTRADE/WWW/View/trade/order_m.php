<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/index.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/js/perpect-scroll.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/js/decimal.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script><!-- 그래프 십진법 jquery 추가됨 -->
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-comm.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-form.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/js/swiper.min.js"></script>
    <script>Config.initHeaderScript();setSocChannel('<?=$view['exchannel']['sock_ch']?>');</script>
	<!-- TradingView Begin -->
	<script src="../../charting_library/charting_library.min.js"></script>
	<script src="../../charting_library/datafeeds/udf/dist/polyfills.js"></script>
	<script src="../../charting_library/datafeeds/udf/dist/bundle.js"></script>
	<!-- TradingView End -->
	<script src="<?= $view['url']['static'] ?>/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="<?= $view['url']['static'] ?>/assets/js/jquery.splendid.textchange.js"></script>
</head>
<style>
	html, body {
		/*height: 100%;*/
		height: calc(100% - 52px);
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
	.mobile-nav-bottom{ display: none;}
	.pull-right{display: none;}
	.btn-m-nav{ right: 0; left: auto;}
	h1{display: none}
	.coin-trade .order-tab {
		display: flex;
		flex-direction: row;
		width: 100%;
		align-content: center;
		align-items: center;
	}
	.coin-trade .order-tab > div {
		flex: 1;
		height: 35px;
		background-color: #919ab0;
		border-right: 1px solid #fff;
	}
	.coin-trade .order-tab > div:first-child {
		border-left: 1px solid #fff;
	}
	.coin-trade .order-tab > div.active {
		background-color: #fff;
	}
	.coin-trade .order-tab > div a {
		display: block;
		width: 100%;
		height: 100%;
		text-align: center;
		line-height: 35px;
		color: #ffffff;
	}
	.coin-trade .order-tab > div.active a {
		color: #cb1e00;
	}
	@media(max-width: 768px) {
		#order-form-buy, #order-form-sell {
			width: 100%;
			display: none;
		}
		#order-form-buy.active, #order-form-sell.active {
			display: block;
		}
	}
	#history-ing {
		display: none;
		flex-direction: row;
		justify-content: space-between
	}
	#history-ing.active {
		display: flex;
	}
	#history-ing a, #history-ing span {
		/*
		display: inline-block;
		padding: 5px 10px;
		font-size: 15px;
		*/
	}
	@media (max-width: 768px) {
		.table {
			min-width: 100%;
		}
		.new-trade .trade-history {
			max-height: 100%;
			overflow-y: scroll;
		}
	}

	#history-ing a {
		/* text-decoration: underline; */
	}
	.current-price > .trade-table.list-trademarketcost > table > tbody tr td:first-child {
		font-weight: bold;
		text-align: right !important;
		padding-right: 10px;
		width: 50%;
	}
	.current-price > .trade-table.list-trademarketcost > table > tbody tr td:last-child {
		font-weight: normal;
		text-align: left !important;
		padding-left: 10px;
		width: 50%;
	}
	.current-price > .trade-table.list-trademarketcost > table > thead {
		display: none;
	}
	.current-price > .in-table {
		display: none;
	}
	.current-price > .status-table {
		display: none;
	}
	#header .lang{  display: none}
	.current-price .trade-table table .red {color: #0068de !important}
	.current-price .trade-table table .blue {color: #e20200 !important}
	.trade-table table tbody tr.red td {
		color: #0238b9;
	}
	.trade-table table tbody tr.blue td {
		color: #cb4140;
	}
    #tv_chart_container{ height: 100%;}
    #tv_chart_container iframe{ height: 100% !important;}
	.test{ }
	.coin-info {
        display: none;
    }
</style>

<body>
	<style>
		.loading-animate {
			position: absolute;
			width: 100%;
			height: 100%;
			display: flex;
			align-content: center;
			align-items: center;
			z-index: 8000;
			background-color: #fff;
		}
		.loading-animate .loading-svg {
			display: block;
			width: 100%;
			text-align: center;
		}
		.status-table { display: none}
                
        </style>
	<div class="loading-animate">
		<span class="loading-svg">
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 accesskey=""width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
				<rect x="0" y="13" width="4" height="5" fill="#0070c2">
					<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0s" dur="0.6s" repeatCount="indefinite" />
					<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0s" dur="0.6s" repeatCount="indefinite" />
				</rect>
				<rect x="10" y="13" width="4" height="5" fill="#0070c2">
					<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
					<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.15s" dur="0.6s" repeatCount="indefinite" />
				</rect>
				<rect x="20" y="13" width="4" height="5" fill="#0070c2">
                    <animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
					<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.3s" dur="0.6s" repeatCount="indefinite" />
				</rect>
			</svg>
		</span>
	</div>
    <div id="wrap" class="trade-wrap">
        <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
        <a href="/" class="btn-m-back"><?php Language::langConvert($view['langcommon'], 'KRW_'.$view['currency']);?><em><?=$view['currency']?>/KRW</em></a>

<div class="header-margin"></div>
	<!-- mobile-top coin status-->
	<div id="coinStatus" class="mobile-top-status">
		<table>
		<!-- <colgroup>
		  <col width="25%">
		  <col width="25%">
			<col width="25%">
			<col width="25%">
		  </colgroup>-->
			<tr>
			<th>현재가</th>
			<td  class="<?=$view['exchannel']['sock_ch']?>"> <strong class="blue-text def_price"> 0</strong></td>
			<th>수익률 </th>
			<td class="<?=$view['exchannel']['sock_ch']?>"><strong class="blue-text def_per_change"> </strong> </td>
			</tr>
		</table>
	</div>
	<!-- //mobile-top coin status-->

	<!-- New Trade -->
	<div class="coin-status" id="coinStatus">
		<div class="this <?=$view['exchannel']['sock_ch']?>">
			<p><?=Language::langConvert($view['langcommon'], 'KRW_'.$view['exchannel']['currency_type'])?> (<?=$view['exchannel']['currency_type']?>)</p>
			<strong class="def_price">0</strong><span>KRW</span>
		</div>
		<div class="status <?=$view['exchannel']['sock_ch']?>">
			<dl>
				<dt><?=Language::langConvert($view['langcommon'], 'high');?></dt>
				<dd class="def_24hr_high ">0</dd>
			</dl>
			<dl>
				<dt><?=Language::langConvert($view['langcommon'], 'low');?></dt>
				<dd class="def_24hr_low">0</dd>
			</dl>
			<p class="color_controller white"><strong class="def_price_gap">0</strong><span class="def_per_change">0%</span></p>
		</div>
		<a href="#" class="btn-m-coins"></a>
	</div>

	<!-- cart nav -->
	<div class="trade-slider-menu list" style="display: block; border: none; position: relative;">
		<div class="mobile-nav-cart" >
			<ul>
				<li><a href="javascript:onShowCont('coin-trade')" class="active">주문</a></li>
				<li><a href="javascript:onShowCont('current-price')">호가</a></li>
				<li><a href="javascript:onShowCont('graph-box')">차트</a></li>
				<li><a href="javascript:onShowCont('history-end')">시세</a></li>
				<li><a href="javascript:onShowCont('coin-info')">정보</a></li>
			</ul>
		</div>
	</div>
	<!-- // cart nav -->
	
	<div class="new-trade"  id="dash-currency-main" style="top:40px;">
		<div class="graph-box">
			<div id="tv_chart_container"></div>
		</div>
		
		<div class="trade-history">
			<div class="history-end">
				<h4><?php Language::langConvert($view['lang'], 'ordersHistory');?></h4>
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
								<th><?php Language::langConvert($view['lang'], 'ordersHistoryType');?></th>
								<th><?php Language::langConvert($view['lang'], 'ordersHistoryOrdersDate');?></th>
								<th><?php Language::langConvert($view['lang'], 'ordersHistoryFullfilled');?></th>
								<th><?php Language::langConvert($view['lang'], 'ordersHistoryAvg');?></th>
								<th><?php Language::langConvert($view['lang'], 'ordersHistoryTotal');?></th>
								<th><?php Language::langConvert($view['langcommon'], 'fee');?></th>
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
			
			<div class="history-ing">
				<h4><?php Language::langConvert($view['lang'], 'openOrders');?></h4>
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
								<th><?php Language::langConvert($view['lang'], 'openOrdersType');?></th>
								<th><?php Language::langConvert($view['lang'], 'openOrdersDate');?></th>
								<th><?php Language::langConvert($view['lang'], 'openOrdersPrice');?></th>
								<th><?php Language::langConvert($view['lang'], 'oepnOrdersAmount');?></th>
								<th><?php Language::langConvert($view['lang'], 'openOrdersPositions');?></th>
								<th><?php Language::langConvert($view['lang'], 'openOrderAvg');?></th>
								<th><?php Language::langConvert($view['lang'], 'openOrdersTotal');?></th>
								<th><?php Language::langConvert($view['lang'], 'openOrdersStatus');?></th>
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
		
		<div class="current-price" style="overflow-y: auto;">
			<div class="trade-table list-trademarketcost" style="height:100%;">
				<table style=" border:1px solid #d0d0d0; background-color:#f5f5f5; height:100%;">
					<colgroup>
						<col style="width:50%">
						<col style="width:50%">
					</colgroup>
					<tbody></tbody>
				</table>
			</div>
			<!--체결량-->
			<div class="in-table list-tradevolume">
				<table>
					<colgroup>
						<col style="width:50%">
						<col style="width:50%">
					</colgroup>
					<thead>
						<tr>
							<th><?php Language::langConvert($view['lang'], 'orderPrice');?></th>
							<th><?php Language::langConvert($view['lang'], 'orderAmount');?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="status-table">
				<table>
					<tbody class="<?=$view['exchannel']['sock_ch']?>">
						<tr>
							<th><?php Language::langConvert($view['langcommon'], 'current');?></th>
							<td><strong class="black def_price">0</strong></td>
						</tr>
						<tr>
							<th><?php Language::langConvert($view['langcommon'], 'change');?></th>
							<td><span class="def_per_change color_controller">0%</span></td>
						</tr>
						<tr>
							<th><?php Language::langConvert($view['langcommon'], 'high');?></th>
							<td><span class="blue def_24hr_high">0</span> </td>
						</tr>
						<tr>
							<th><?php Language::langConvert($view['langcommon'], 'low');?></th>
							<td><span class="red def_24hr_low">0</span> </td>
						</tr>
						<tr>
							<th><?php Language::langConvert($view['langcommon'], 'volume');?></th>
							<td><span class="def_volume">0</span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="coin-trade" id="order-form-area">
			<div class="order-tab">
				<div class="active">
					<a href="#order-form-buy">매수</a>
				</div>
				<div>
					<a href="#order-form-sell">매도</a>
				</div>
				<div>
					<a href="#history-ing">미체결</a>
				</div>
			</div>

			<div class="scroll-area">
				<!-- BIGIN :: 구매 -->
				<form class="form-exchange trade-buy" id="order-form-buy" onsubmit="return false">
					<input type="hidden" name="token" value="<?=$view['token']?>">

					<div>
						<div class="mine">
							<div class="available">
								<span><?php Language::langConvert($view['lang'], 'availableKrw');?></span>
								<strong><span class="mb_krw_poss">0</span>KRW</strong>
							</div>
						</div>

						<div class="form-section">
							<dl>
								<dt><?php Language::langConvert($view['langcommon'], 'buyAmount');?></dt>
								<dd>
									<div class="inp-group">
										<input type="text" class="inp input-calculation only-coin" name="input_amount" maxlength="17" value="0.00000000">
										<div class="inp-button">
											<button class="btn-max" id="btn_max"><?php Language::langConvert($view['lang'], 'btnMax');?></button>
										</div>
									</div>
									<div id="input_btc_alert"></div>
								</dd>
							</dl>

							<dl>
								<dt><?php Language::langConvert($view['langcommon'], 'buyAmountPrice');?>(1<?=$view['exchannel']['currency_type']?>)</dt>
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
								<dt><?php Language::langConvert($view['langcommon'], 'buyPriceTotal');?></dt>
								<dd>
									<div class="inp-group">
										<input type="text" class="inp input-calculation" name="input_total" placeholder="0" readonly>
										<div class="inp-button">
											<span><?php Language::langConvert($view['langcommon'], 'currentCurrency');?></span>
										</div>
									</div>
									<div id="input_total_alert"></div>
								</dd>
							</dl>
						</div>
						<div class="form-section">
							<dl class="tax">
								<dt><?php Language::langConvert($view['langcommon'], 'fee');?> : <?php Language::langConvert($view['lang'], 'marginMakerFee');?>(<?=$view['maker_fee_per']?>%),<?php Language::langConvert($view['lang'], 'marginTakerFee');?>(<?=$view['tracker_fee_per']?>%)</dt>
								<dd><span id="trade_fee">0.00000000</span> <?=$view['exchannel']['currency_type']?></dd>
							</dl>
							<dl class="total-purchase">
								<dt><?php Language::langConvert($view['langcommon'], 'totalBuyAmount');?></dt>
								<dd><span id="trade_receipt">≈ 0.00000000</span> <?=$view['exchannel']['currency_type']?></dd>
							</dl>
						</div>
						<div class="btn-box">
							<button class="btn-buy" id="btn_buy_submit" ><?php Language::langConvert($view['langcommon'], 'buyButton');?></button>
						</div>
					</div>
				</form>
				<!-- END :: 구매 -->
				
				<!-- BIGIN :: 판매 -->
				<form class="form-exchange trade-sell" id="order-form-sell" onsubmit="return false">
					<div>
						<div class="mine">
							<div class="available">
								<span><?php Language::langConvert($view['langcommon'], 'available');?></span>
								<strong><span class="mb_<?=strtolower($view['exchannel']['currency_type'])?>_poss">0.00000000</span> <?=$view['exchannel']['currency_type']?></strong>
							</div>
						</div>
						<div class="form-section">
							<dl>
								<dt><?php Language::langConvert($view['langcommon'], 'sellAmount');?></dt>
								<dd>
									<div class="inp-group">
										<input type="text" class="inp input-calculation only-coin" name="input_amount" maxlength="17" value="0.00000000">
										<div class="inp-button">
											<button class="btn-max" id="btn_max"><?php Language::langConvert($view['lang'], 'btnMax');?></button>
										</div>
									</div>
									<div id="input_btc_alert_sell"></div>
								</dd>
							</dl>
							<dl>
								<dt><?php Language::langConvert($view['langcommon'], 'sellAmountPrice');?>(1<?=$view['exchannel']['currency_type']?>)</dt>
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
								<dt><?php Language::langConvert($view['langcommon'], 'sellPriceTotal');?></dt>
								<dd>
									<div class="inp-group">
										<input type="text" class="inp input-calculation" name="input_total" placeholder="0" readonly>
										<div class="inp-button">
											<span><?php Language::langConvert($view['langcommon'], 'currentCurrency');?></span>
										</div>
									</div>
									<div id="input_total_alert_sell"></div>
								</dd>
							</dl>
						</div>

						<div class="form-section">
							<dl class="tax">
								<dt><?php Language::langConvert($view['langcommon'], 'fee');?> : <?php Language::langConvert($view['lang'], 'marginMakerFee');?>(<?=$view['maker_fee_per']?>%),<?php Language::langConvert($view['lang'], 'marginTakerFee');?>(<?=$view['tracker_fee_per']?>%)</dt>
								<dd><span id="trade_fee">0</span><?php Language::langConvert($view['langcommon'], 'currentCurrency');?></dd>
							</dl>

							<dl class="total-purchase">
								<dt><?php Language::langConvert($view['langcommon'], 'sellPriceTotal');?></dt>
								<dd><span id="trade_receipt">≈ 0</span><?php Language::langConvert($view['langcommon'], 'currentCurrency');?></dd>
							</dl>
						</div>
						<div class="btn-box">
							<button class="btn-sell" id="btn_sell_submit"><?php Language::langConvert($view['langcommon'], 'sellButton');?></button>
						</div>
					</div>
				</form>
				<!-- END :: 판매 -->
				
				<!-- BIGIN :: 미체결 -->
				<div id="history-ing">
					<!-- BIGIN :: 미체결 주문 내용 -->
					<div id="container">
						<div id="contents">
							<div class="trade-history">
								<div class="title">
									<strong class="history-title">
										<?php Language::langConvert($view['langcommon'], 'KRW_'.$view['currency']);?>
									</strong>
								</div>
								<div class="table">
									<table class="list-tradenocomplete" style="min-width: 100%">
										<thead>
											<tr>
												<th>주분</th>
												<th>주문시간</th>
												<th>주문가</th>
												<th>주문량</th>
												<th>체결량</th>
												<th>미체결량</th>
												<th>체결가(평균)</th>
												<th>미체결가(평균)</th>
												<th>비고</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="pagenate" id="pager"></div>
							</div>
						</div>
					</div>
					<!-- END :: 미체결 주문 내용 -->
				</div>
				<!-- END :: 미체결 -->
				
			</div>
		</div>

		<!-- 시장현황 -->
		<div class="market-list ">
			<div class="market-table scroll-area">
				<table>
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
						<tr class="<?=strtolower($key)?>" onClick="changeCoin('krw-<?=strtolower($cursymbol)?>')">
							<td>
								<div class="coin-kind">
									<i class="ico-coin ico-<?=strtolower($cursymbol)?>"></i>
									<span><strong><?php Language::langConvert($view['langcommon'], $key);?></strong><br><em><?=$cursymbol?></em></span>
								</div>
							</td>
							<td class="price def_price">0</td>
							<td class="def_per_change color_controller">0%</td>
						</tr>
						<?php }?>
					<?php
						$temp_coin = ['리플코인', '유니키', '피스코인', '비트카르타스', '패비오시스스페셜', '더블유쓰리'];
						$temp_symb = ['XRP', 'UNIKEY', 'PEC', 'BCS', 'PCS', 'W3'];
						for($k = 0, $c = count($temp_coin); $k < $c; $k++) {
					?>
						<tr class="" onClick="">
							<td>
								<div class="coin-kind">
									<i class="ico-coin"></i>
									<span><strong><?=$temp_coin[$k]?></strong><br><em><?=$temp_symb[$k]?></em></span>
								</div>
							</td>
							<td class="price def_price">-</td>
							<td class="def_per_change color_controller">준비중</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- // 시장현황 -->

		<!-- 실시간 거래현황 -->
		<div class="current-trade">
			<h4><?php Language::langConvert($view['lang'], 'realTimeTrade');?></h4>
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
							<th><?php Language::langConvert($view['lang'], 'type');?></th>
							<th><?php Language::langConvert($view['lang'], 'time');?></th>
							<th><?php Language::langConvert($view['lang'], 'price');?></th>
							<th><?php Language::langConvert($view['lang'], 'amount');?></th>
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
		<!-- // 실시간 거래현황 -->

		<!-- 보유자산현황 -->
		<div class="mine-stats">
			<h4><?php Language::langConvert($view['lang'], 'assets');?></h4>
			<div class="stats-list scroll-area">
				<dl>
					<dt><span class="ico-coin ico-krw"></span><em><?=Language::langConvert($view['langcommon'], 'KRW_KRW');?></em></dt>
					<dd><span class="mb_krw_total">0</span> <?=Language::langConvert($view['langcommon'], 'keyCurrency');?></dd>
				</dl>
				<?php
					foreach ($view['master'] as $key => $value) {
						$tmp = explode('_', $key);
						$cursymbol = $tmp[1];
				?>
				<dl>
					<dt><span class="ico-coin ico-<?=strtolower($cursymbol)?>"></span><em><?php Language::langConvert($view['langcommon'], $key);?></em></dt>
					<dd><span class="mb_<?=strtolower($cursymbol)?>_total">0</span> <?=$cursymbol?></dd>
				</dl>
				<?php } ?>
			</div>
		</div>
		<!-- // 보유자산현황 -->
		<div class="coin-info">
			<div style="padding: 100px 0; text-align: center;font-size: 15px;">
				준비중입니다.
			</div>
		</div>
	</div>
	<!-- // New Trade -->

	<!-- trade Update Modal -->
	<div class="modal fade" id="tradeUpdateModal" tabindex="-1" role="dialog" aria-labelledby="tradeUpdateModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><?php Language::langConvert($view['lang'], 'tradeUpdateGuideTitle');?></h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-hover list-tradeUpdate">
						<thead>
							<tr>
								<th class="text-align-right"></th>
								<th class="text-align-right"><?php Language::langConvert($view['lang'], 'tradeUpdateOrdersAmount');?></th>
								<th class="text-align-right"><?php Language::langConvert($view['lang'], 'tradeUpdateOrdersPrice');?></th>
								<th class="text-align-right"><?php Language::langConvert($view['lang'], 'tradeUpdateOrdersTotal');?></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<br />
					<?php Language::langConvert($view['lang'], 'tradeUpdateDesc');?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn_update-cancel" id="btn_update_cancel" data-dismiss="modal"><?php Language::langConvert($view['lang'], 'btnCancel');?></button>
					<button type="button" class="btn btn-danger" id="btn_update_submit"><?php Language::langConvert($view['lang'], 'tradeUpdateBtnUpdateRequest');?></button>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL -->
	<div class="modal fade" id="buyBtcModal" tabindex="-1" role="dialog" aria-labelledby="buyBtcModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><?php Language::langConvert($view['lang'], 'buyGuideTitle');?></h4>
				</div>
				<div class="modal-body">
					<?php Language::langConvert($view['lang'], 'buyOrderDesc1');?> <span class="show_krw"></span> <?php Language::langConvert($view['lang'], 'buyOrderDesc2');?> <span class="show_btc"></span> <?php Language::langConvert($view['lang'], 'buyOrderDesc3');?><br /><br />
					<b><?php Language::langConvert($view['lang'], 'buyOrderDesc4');?> : <span class="show_krw"></span> <?php Language::langConvert($view['langcommon'], 'currentCurrency');?></b> (<?php Language::langConvert($view['lang'], 'buyOrderDesc5');?> : <span class="show_price"></span> <?php Language::langConvert($view['langcommon'], 'currentCurrency');?>)<br /><br />
					<b><?php Language::langConvert($view['lang'], 'buyOrderDesc6');?> : <span class="show_btc"></span> <?=$view['exchannel']['currency_type']?></b> (<?php Language::langConvert($view['lang'], 'buyOrderDesc7');?> : <span class="show_fee"></span> <?php Language::langConvert($view['lang'], 'buyOrderDesc8');?>)<br /><br />
					<?php Language::langConvert($view['lang'], 'buyOrderDesc9');?><br />
				</div>
				<div class="modal-footer">
					<span class="pull-left glyphicon glyphicon-time"></span><p class="pull-left">&nbsp;</p>
					<span class="pull-left show_remain_time"><?php Language::langConvert($view['lang'], 'remainTime');?> :&nbsp;</span>
					<button type="button" class="btn btn-default btn_submit-cancel" id="btn_submit-cancel" data-dismiss="modal"><?php Language::langConvert($view['lang'], 'btnCancel');?></button>
					<button type="button" class="btn btn-danger" id="btn_submit"><?php Language::langConvert($view['lang'], 'btnBuyRequest');?></button>
				</div>
			</div>
		</div>
	</div>
	<!-- // MODAL -->

	<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    </div>

    <script>Config.initFooterScript();</script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-market-simple.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-trade.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>

		// 수수료 값 삽입
		$( document ).ready(function() {

            $('.trade-slider-menu a').click(function(){
                $(this).addClass('active').parent().siblings().find('a').removeClass('active');

                $('.trade-table > table > tbody > tr.red').each(function() {
                    curNoneBlock = $(this).find('.none-bg.none-border').index(this);
                });
            });
            $('.mobile-main-tab a').on('click', function(e) {
                var tmp_id = $(this).attr('id');
                e.preventDefault();
            });
            var tradeUI = 0;
            var curNoneBlock = 0;
			
			var swiper = new Swiper('.trade-slider-menu .swiper-container', {
					slidesPerView: 'auto',
					navigation: {
							nextEl: '.swiper-button-next',
							prevEl: '.swiper-button-prev',
					  },
			});
			var tableRedPos = $('.list-trademarketcost tbody tr.red').eq(0).find('td.none-border').index();

			console.log('tableRedPos : ' + tableRedPos);

			$('.trade-table.list-trademarketcost > table > tbody > tr.red').each(function() {
				$(this).find('td').eq(0).addClass('price2');
				$(this).find('td').eq(1).addClass('price');
				var priceTd = $(this).find('.price');
				$(this).find('td.none-border').insertBefore(priceTd);
				$(this).find('td.price2').insertAfter(priceTd);
			});
			$('.trade-table.list-trademarketcost > table > tbody > tr').each(function() {
				$(this).parent().parent().find('thead').addClass('hidden-block');
				$(this).find('td.none-border').addClass('hidden-block');
				$(this).parent().parent().parent().parent().find('.in-table').addClass('hidden-block');
				$(this).parent().parent().parent().parent().find('.status-table').addClass('hidden-block');
			});
			$('.current-price').addClass('half-width');
			$('.coin-trade').addClass('half-width');
			$('.btn-m-coins').click(function(){
					$(this).toggleClass('active');
					$('.market-list ').stop().slideToggle(250);
			});
			$('.current-price').show();

			$('#order-form-buy').addClass('active');
			$('.order-tab > div').on('click', 'a', function(e) {
				$(this).parent().siblings().removeClass('active');
				$(this).parent().addClass('active');
				var tabShowID = $(this).attr('href');
				var tabHideID = "";
				$('.order-tab > div').each(function() {
					tabHideID = $(this).find('a').attr('href');
					$(tabHideID).removeClass('active');
				});
				$(tabShowID).addClass('active');

				e.preventDefault();
			});

			// ---------------------------------------------------------------------------------

			$('.scroll-area').perfectScrollbar();
			$(".krw_<?=strtolower($view['exchannel']['currency_type'])?>").addClass("active");

			// 거래소 수수료
			tradePageSet.trade_maker_fee     = <?=$view['maker_fee']?(float)$view['maker_fee_per']:0.0 ?>;
			tradePageSet.trade_tracker_fee     = <?=$view['tracker_fee']?(float)$view['tracker_fee_per']:0.0 ?>;

			// 거래 금액
			tradePageSet.trade_krw_min_limit = <?=$view['trade_krw_min_limit']?(float)$view['trade_krw_min_limit']:10000 ?>;
			tradePageSet.trade_coin_min_limit = <?=$view['trade_coin_min_limit']?(float)$view['trade_coin_min_limit']:0.001 ?>;
			tradePageSet.trade_call_unit_krw = <?=$view['call_unit_krw']?(float)$view['call_unit_krw']:10000 ?>;
			tradePageSet.trade_call_unit_coin = <?=$view['call_unit_coin']?(float)$view['call_unit_coin']:0.001 ?>;

			$(".<?=strtolower($view['exchannel']['currency_type'])?>").addClass("active");
			changeCurrencyChartItem('<?=$view['exchannel']['currency_type']?>');

			initListData();

			{
				$('.temp-table-wrap').hide();
				$('.trade-slider-menu').removeClass('list');
				$('.coin-trade').show();
			}

			removeBlock();

			// BIGIN :: Trading view chart
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
						"volumePaneSize": "small",
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
					header_layouttoggle: 'off',
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
					var tvHeight = tvWidth / 4 * 3;
				}
				$('#tv_chart_container iframe').height(tvHeight);
				var tvWidth = $('#tv_chart_container iframe').width();
				if($(window).width() > 768) {
					var tvHeight = tvWidth / 5 * 2;
				} else {
					var tvHeight = tvWidth / 4 * 3;
				}
				//$('#tv_chart_container iframe').height(tvHeight);
				$('#tv_chart_container iframe').height($(document).width() / 4 * 3);

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
						tvHeight = tvWidth / 4 * 3;
					}

					$('#tv_chart_container iframe').height(tvHeight);
				});
			});
			// END :: Trading view chart
		});

        // modal close event
		$('#buyBtcModal').on('hidden.bs.modal',
			function(e){
				tradePageSet.order_status = false;
				tradePageSet.dom_btn_order.attr('disabled', false);
			}
		);
		function loadingAni(eventTime) {
			var loadingAnimation = '';
			loadingAnimation += '<div class="loading-animate">';
			loadingAnimation += '<span class="loading-svg">';
			loadingAnimation += '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"';
			loadingAnimation += 'accesskey=""width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">';
			loadingAnimation += '<rect x="0" y="13" width="4" height="5" fill="#0070c2">';
			loadingAnimation += '<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0s" dur="0.6s" repeatCount="indefinite" />';
			loadingAnimation += '<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0s" dur="0.6s" repeatCount="indefinite" />';
			loadingAnimation += '</rect>';
			loadingAnimation += '<rect x="10" y="13" width="4" height="5" fill="#0070c2">';
			loadingAnimation += '<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.15s" dur="0.6s" repeatCount="indefinite" />';
			loadingAnimation += '<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.15s" dur="0.6s" repeatCount="indefinite" />';
			loadingAnimation += '</rect>';
			loadingAnimation += '<rect x="20" y="13" width="4" height="5" fill="#0070c2">';
			loadingAnimation += '<animate attributeName="height" attributeType="XML" values="5;21;5" begin="0.3s" dur="0.6s" repeatCount="indefinite" />';
			loadingAnimation += '<animate attributeName="y" attributeType="XML" values="13; 5; 13" begin="0.3s" dur="0.6s" repeatCount="indefinite" />';
			loadingAnimation += '</rect>';
			loadingAnimation += '</svg>';
			loadingAnimation += '</span>';
			loadingAnimation += '</div>';
			$('#wrap').before(loadingAnimation);
			$('.loading-animate').fadeOut(eventTime, function() {$(this).remove();});
		}
        function onShowCont(target) {
			loadingAni(1500);
            if(target == 'coin-trade') {
                $('#dash-currency-main > div').hide();
                if(target == "history-end") {
                        $('.history-ing').hide();
                        $('.trade-history').show();
                }
                if(target == "history-ing") {
                        $('.history-end').hide();
                        $('.trade-history').show();
                }
                $('.'+target).show();
                $('.current-price').show();
                $('.'+target).addClass('half-width');
                $('.current-price').addClass('half-width');
            } else if(target == 'current-price') {
                $(location).attr('href', tmpProtocol + '//' + tmpHost + '/trade/current_price_m/<?php echo('krw-'.strtolower($view['currency'])); ?>');
            } else if(target == 'coin-info') {
                $('#dash-currency-main > div').hide();
                $('.'+target).show();
            } else {
                $('#dash-currency-main > div').hide();
                if(target == "history-end"){
                        $('.history-ing').hide();
                        $('.trade-history').show();
                }
                if(target == "history-ing"){
                        $('.history-end').hide();
                        $('.trade-history').show();
                }
                $('.'+target).show();
                $('.current-price').removeClass('half-width');
            }
        }

		function removeBlock()
		{
			if (g_isTradeMarketCostListComplete != 'undefined' && g_isTradeMarketCostListComplete == 1)
			{
				$('.loading-animate').fadeOut(300, function() { $(this).remove(); });
			}
			else
			{
				setTimeout(function() {
					removeBlock();
				}, 300);
			}
        }

	 // 시장현황 click event
        function changeCoin(ctype){
			if (isMobile())
				location.href = '/trade/order_m/'+ctype;
			else
				location.href = '/trade/order/'+ctype;
        }
	// 그래프 호출
        var domchart = $('#main-market-chart');
        var curseltype = '';

		function changeCurrencyChartItem(cointype){
			if(curseltype == cointype){
				domchart.toggle();
				$('#'+cointype).toggleClass('active');
			}else{
				//debugger;
				domchart.show();

				$('#'+cointype).addClass('active').siblings().removeClass('active');
			}
			curseltype = cointype;
		}

		function isMobile()
		{
			var x = window.matchMedia("(max-width: 768px)");
			return x.matches;
		}
    </script>

</body>