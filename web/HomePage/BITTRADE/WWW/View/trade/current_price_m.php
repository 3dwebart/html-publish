<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <!-- <link href="<?= $view['url']['static']?>/assets/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="<?= $view['url']['static']?>/assets/css/bootstrap-theme.min.css" rel="stylesheet"> -->
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
    <!-- <script src="<?=$view['url']['static']?>/assets/js/chart/highstock.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/chart/exporting.js"></script> -->
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
    .pull-right{padding-right: 0;}
    .btn-m-nav{ right: 0; left: auto;}
    h1{display: none}
    
    .trade-table table tr.red{ background: #fff}
    .trade-table table tr.blue{ background: #fff}
    .trade-table table td{ border: 1px solid #c0c6d5 !important; border-top: 1px solid #c0c6d5 !important;  border-bottom: 1px solid #c0c6d5;}
    .trade-table table td.none-border{ border-bottom: 1px solid #fff !important;}
    .in-table{border-bottom: 1px solid #c0c6d5 } 
    .status-table{border-bottom: 1px solid #c0c6d5; top: 43px; }
    #header .lang{ display: none}
     #tv_chart_container{ height: 100%;}
    #tv_chart_container iframe{ height: 100% !important;}
    .coin-info {
        display: none;
    }
    @media(max-height: 640px) {
        .current-price {
            margin-bottom: 60px;
        }
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
    </style>
    <div class="loading-animate">
        <span class="loading-svg">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <rect x="0" y="13" width="4" height="5" fill="#0070c2">
                    <animate attributeName="height" attributeType="XML"
                    values="5;21;5" 
                    begin="0s" dur="0.6s" repeatCount="indefinite" />
                    <animate attributeName="y" attributeType="XML"
                    values="13; 5; 13"
                    begin="0s" dur="0.6s" repeatCount="indefinite" />
                </rect>
                <rect x="10" y="13" width="4" height="5" fill="#0070c2">
                    <animate attributeName="height" attributeType="XML"
                    values="5;21;5" 
                    begin="0.15s" dur="0.6s" repeatCount="indefinite" />
                    <animate attributeName="y" attributeType="XML"
                    values="13; 5; 13"
                    begin="0.15s" dur="0.6s" repeatCount="indefinite" />
                </rect>
                <rect x="20" y="13" width="4" height="5" fill="#0070c2">
                    <animate attributeName="height" attributeType="XML"
                    values="5;21;5" 
                    begin="0.3s" dur="0.6s" repeatCount="indefinite" />
                    <animate attributeName="y" attributeType="XML"
                    values="13; 5; 13"
                    begin="0.3s" dur="0.6s" repeatCount="indefinite" />
                </rect>
            </svg>
        </span>
    </div>
    <script>
        (function($) {
            setTimeout(function() {
                $('.loading-animate').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 300);
        })(jQuery)
    </script>
    <div id="wrap" class="trade-wrap">
        <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
        <a href="/" class="btn-m-back"><?php Language::langConvert($view['langcommon'], 'KRW_'.$view['currency']);?><em><?=$view['currency']?>/KRW</em></a>
        

        
<div class="header-margin"></div>
<!-- mobile-top coin status-->  
<div id="coinStatus" class="mobile-top-status">
    <table>
     <colgroup>
      <col width="25%">
      <col width="25%">
        <col width="25%">
        <col width="25%">
      </colgroup>   
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
        </div><!-- // coin-status -->
		
        
         
        <!-- cart nav -->
        <div class="trade-slider-menu list" style="display: block; border: none; position: relative;">
            <div class="mobile-nav-cart" >
                <ul>
                    <li><a href="javascript:onShowCont('coin-trade')">주문</a></li>
                    <li><a href="javascript:onShowCont('current-price')" class="active">호가</a></li>
                    <li><a href="javascript:onShowCont('graph-box')">차트</a></li>
                    <li><a href="javascript:onShowCont('history-end')">시세</a></li>
                    <li><a href="javascript:onShowCont('coin-info')">정보</a></li>
                </ul>
            </div>
        </div>
        <!-- // cart nav -->
        <div class="new-trade"  id="dash-currency-main" style="top:40px">
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
            <div class="current-price">
                <div class="trade-table list-trademarketcost">
                    <table>
                        <colgroup>
                            <col style="width:42%">
                            <col style="width:20%">
                            <col style="width:38%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th><?php Language::langConvert($view['lang'], 'remainSell');?></th>
                                <th><?php Language::langConvert($view['lang'], 'price');?></th>
                                <th><?php Language::langConvert($view['lang'], 'remainBuy');?></th>
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
       
            <!-- ################# TEMP TABLE -->
            <div class="temp-table-wrap list">
                <div class="mobile-search-wrap"> 
                    <form>
                        <input type="text" name="search" value="" placeholder="코인명 / 심볼 검색">
                    </form>
                </div> <!--search -->
                <div class="mobile-main-tab">
                    <ul>
                        <li> <a href="#won" class="round-btn active"> 원화거래</a></li>
                        <li> <a href="#btc" class="round-btn">BTC 거래 </a></li>
                        <li><a href="#eth" class="round-btn">ETH 거래 </a></li>
                    </ul>
                </div>
                <div id="won">
                    <table class="temp_table">
                        <colgroup>
                            <col width="10">
                            <col width="">
                            <col width="">
                            <col width="">
                        </colgroup>
                        <thead>
                            <tr>
                                <th colspan="2">코인명</th>
                                <th>현재가</th>
                                <th>전일대비</th>
                            </tr>
                        </thead>
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
                                        <span><strong><?php Language::langConvert($view['langcommon'], $key);?></strong><br><em><?=$cursymbol?></em></span>
                                    </div>
                                </td>
                                <td class="price def_price color_controller">0</td>
                                <td class="def_per_change color_controller">0%</td>
                            </tr>
                            <?php }?>
                           
                            
                            <tr class="krw_btc active down" > 
                                <td> <div class="up-down-icon bar-down"></div> </td> 
                                <td class="tit"> <div class="coin-kind">
                                    <span><strong>리플코인</strong><br>
                                    <em>XRP</em></span> </div> </td> 
                                <td>-</td> 
                                <td>준비중</td> </tr>
                            <tr class="krw_btc active down" > 
                                <td> <div class="up-down-icon bar-down"></div> </td> 
                                <td class="tit"> <div class="coin-kind">
                                    <span><strong>유니키</strong><br>
                                        <em>UNIKEY</em></span> </div> </td> 
                                <td>-</td> 
                                <td>준비중</td>
                            </tr>
                            <tr class="krw_btc active down" > 
                                <td> <div class="up-down-icon bar-down"></div> </td> 
                                <td class="tit"> <div class="coin-kind">
                                    <span><strong>피스코인</strong><br>
                                        <em>PEC</em></span> </div> </td> 
                                <td>-</td> 
                                <td>준비중</td> </tr>
                            <tr class="krw_btc active down" > 
                                <td> <div class="up-down-icon bar-down"></div> </td> 
                                <td class="tit">
                                    <div class="coin-kind">
                                        <span>
                                            <strong>비트카르타스</strong><br>
                                            <em>BCS</em>
                                        </span>
                                    </div>
                                </td> 
                                <td>-</td> 
                                <td>준비중</td>
                            </tr>
                            <tr class="krw_btc active down" > 
                                <td> <div class="up-down-icon bar-down"></div> </td> 
                                <td class="tit">
                                    <div class="coin-kind">
                                        <span>
                                            <strong>패비오시스스페셜</strong><br>
                                            <em>PCS</em>
                                        </span>
                                    </div>
                                </td> 
                                <td>-</td> 
                                <td>준비중</td>
                            </tr>
                            <tr class="krw_btc active down" > 
                                <td> <div class="up-down-icon bar-down"></div> </td> 
                                <td class="tit">
                                    <div class="coin-kind">
                                        <span><strong>더블유쓰리</strong><br>
                                        <em>W3</em></span>
                                    </div>
                                </td> 
                                <td>-</td> 
                                <td>준비중</td>
                            </tr>
                        </tbody>
                    </table>
                    <?PHP /*
                    
                    */ ?>
                </div>
                <div id="btc"></div>
                <div id="eth"></div>
            </div>
            <!-- ################# TEMP TABLE -->

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
                <!--<h5><?php Language::langConvert($view['lang'], 'totalAssets');?><span><em class="total-assets">0</em> KRW</span> </h5>-->
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
        </div><!-- // new-trade -->
            
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
    <!-- <script src="<?=$view['url']['static']?>/assets/script/controller-chart.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script> -->
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-chart.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-market.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-trade.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>
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

    function onShowCont(target){
        loadingAni(1500);
        if(target == 'coin-trade') {
            $(location).attr('href', tmpProtocol + '//' + tmpHost + '/trade/order_m/<?php echo('krw-'.strtolower($view['currency_key'])); ?>');
        } else if(target == 'current-price') {
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
            $('.'+target).removeClass('half-width');
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

    // modal close event
    $('#buyBtcModal').on('hidden.bs.modal',
        function(e){
            tradePageSet.order_status = false;
            tradePageSet.dom_btn_order.attr('disabled', false);
        }
    );
		
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
		
	function changeCurrencyChartItem(cointype) {
        if(curseltype == cointype){
            domchart.toggle();
            $('#'+cointype).toggleClass('active');
        }else{
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
    
    // Document Ready Function
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
        $('.btn-m-coins').click(function(){
                $(this).toggleClass('active');
                $('.market-list ').stop().slideToggle(250);
        });
        $('.current-price').show();
        
        // 수수료 값 삽입
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
        TradingView.onready(function ()
        {
            var widget = window.tvWidget = new TradingView.widget({
                fullscreen: true,
                //interval: '1S',
                interval: '1',
                symbol: '<?= $view['currency'] ?>',
                container_id: "tv_chart_container",
                timezone: "Asia/Seoul",
                datafeed: TradingView_RealtimeDatafeeds,
                //datafeed: new Datafeeds.UDFCompatibleDatafeed("/tradingview", 60000 * 60 * 24),
                library_path: "../../charting_library/",
                disabled_features: [
                    "volume_force_overlay",
                    "left_toolbar",
                    // 심볼 변경 관련 메뉴
                    "header_symbol_search",
                    "symbol_search_hot_key",
                    "compare_symbol",
                    "header_compare",
                    // 컨텍스트 메뉴
                    "pane_context_menu",
                    "show_dom_first_time",
                ],
                overrides: {
                    "volumePaneSize": "small"
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
                //widget.chart().createStudy('funhansoft', false, true);
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
        /*
        var sellBuyTableHeight = $('.trade-table.list-trademarketcost table').height() - 40;
        var inTableHeight = (sellBuyTableHeight / 2) - 16;
        $('.status-table table').height(inTableHeight);
        $('.in-table table').height(inTableHeight);
        */
        $('.status-table table').css({
            'min-height': 193 + 'px'
        });
        $('.in-table table').css({
            'min-height': 193 + 'px'
        });
    });
    </script>
</body>