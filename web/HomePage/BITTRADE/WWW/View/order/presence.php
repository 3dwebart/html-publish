<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-comm.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-form.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();setSocChannel('<?=$view['exchannel']['sock_ch']?>');</script>
    <style>
        @media (max-width: 768px) {
            .body-title {height: 0 !important;}
            #snb{ display: none}
            .mobile-main-tab{ border-bottom: 1px solid #ccc}
        }
        .badge.buy{ font-weight: bold}
        .badge.sell{ font-weight: bold}
        .round-div{
            background-color: white;
            padding-top: 5px;
            padding-bottom: 20px;
            box-shadow: rgba(33, 102, 212, 0.15) 0px 7px 30px 0px;
            box-sizing: border-box;
            position: relative;
            height: 100%;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .round-div h4{
            background:#ecf2f8; 
            color: #586481; 
            display: block;
            padding: 10px;
            font-size: 15px;
            margin: 0;
            position: relative;
            min-height: 46px;
        }
        
        .round-div h4 strong{ font-weight: 100; font-size: 11px }
        .round-div table{
            display: inline-table;
            width: 100%;
            border-collapse: collapse; margin: 10px; margin: 0 auto
        }
        
        .round-div table th{
            border-bottom: 1px solid #e7eef5;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            border-collapse: collapse;
            padding: 5px;
            text-align:left;
            color: #6e788f;
            font-size: 12px;
            word-break: keep-all;
        }
        .round-div table td{
            border-bottom: 1px solid #e7eef5;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            border-collapse: collapse;
            padding: 5px;
            text-align: right;
            font-size: 13px;
            color: #6e788f;
        }
        .round-div table td.align-right{ text-align: right}
        .round-div table td.align-left{ text-align: left}
            
        .round-div table th strong{  font-size: 16px; /*float: right;*/  }
        .red-text{ color: #cb1e00 !important;}
            
        .round-content-wrap{ padding: 10px}
        .my-asset th:first-child{padding-left: 25px}
        .my-asset td span {
            color: inherit;
        }
        @media(max-width: 422px) {
            .round-div table td strong,
            .round-div table td span {
                transform: scale(.9, .9);
            }
        }
        div[class^="col-"] {
            padding: 10px 5px;
            min-height: 24px;
            line-height: 100%;
            color: #6e788f;
        }
        .box-wrap {
            display: flex;
            align-item: center;
            border-bottom: 1px solid #e7eef5;
        }
    </style>
</head>
<body class="sub-background">
    <!-- BIGIN :: wrap -->
    <div id="wrap">
        <!-- HEADER -->
        <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
        <!-- // HEADER -->

        <script src="<?=$view['url']['static']?>/assets/write/snb-history.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>

        <script>$('#snb ul li').eq(0).addClass('active');  
            $('#gnb ul li a').eq(0).addClass('active');
        $('.mobile-nav-bottom ul li a').eq(1).addClass('active');
        </script>
        <!--snb-->
         <div class="mobile-main-tab" style="margin-top:46px">
             <ul>
                <li> <a href="/order/presence" class="round-btn active"> 보유코인</a></li>
               <li> <a href="/order/tradecomplete" class="round-btn"> 체결내역</a></li>
                <li> <a href="/order/tradeuncomplete" class="round-btn">미체결내역 </a></li>
             </ul>
        </div>


        <!-- CONTAINER -->
        <div id="container">
             <div class="body-title">
                <div class="inner">
                    <p class="tit"><?=Language::langConvert($view['lang'], 'transaction');?><?=$view['title']?></p>
                </div>
            </div>
            <!-- BIGIN :: contents -->
            <div id="contents" style="margin-top:10px">
                <!-- BIGIN :: 보유코인 --> 
                <div id="mycoin-m">
                    <div class="round-div"> 
                        <div class="round-content-wrap">
                            <h4>보유자산</h4>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>총 보유자산</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_krw_total_asset_held font-15">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>보유 KRW</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_krw_total font-15">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>총 매수금액</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_krw_total_buy_price font-15">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>총 평가손익</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_krw_total_profit font-15">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>총 평가금액</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_krw_total_evaluation_price font-15">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>총 평가수익률</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_krw_total_profit_rate font-15">0</strong> <span class="color-grey">%</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div> <!--round-->
                    <?php
                        foreach ($view['master'] as $key => $value) {
                            $tmp = explode('_', $key);
                            $cursymbol = $tmp[1];
                    ?>
                    <div class="round-div"> 
                        <div class="round-content-wrap">
                            <h4>
                                <span class="ico-coin ico-<?php echo strtolower($cursymbol); ?>"></span>
                                <em><?php Language::langConvert($view['langcommon'], $key); ?></em>
                                <strong><?php echo strtolower($cursymbol); ?>/KRW </strong>
                            </h4>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>수익률</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_<?php echo strtolower($cursymbol); ?>_profit_rate">0</strong> <span class="color-grey">%</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>평가손익</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_<?php echo strtolower($cursymbol); ?>_profit_rate_calc">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>보유</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_<?php echo strtolower($cursymbol) ?>_total">0</strong> <span><?php echo $cursymbol ?></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>평균매수가</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_<?php echo strtolower($cursymbol); ?>_average_buy_price">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>미체결</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_<?php echo strtolower($cursymbol); ?>_uncompletecount">0</strong> <span>건</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="box-wrap">
                                <div class="col-xs-5">
                                    <strong>평가금액</strong>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <strong class="mb_<?php echo strtolower($cursymbol); ?>_evaluation_price">0</strong> <span class="color-grey">KRW</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div> <!--ctype list-->
                    <?php
                        }
                    ?>
                </div>
                <!-- END :: 보유코인-->
                <!-- BIGIN :: PC MY ASSET -->
                <div class="pc-myasset">
                    <div class="my-asset-status"> 
                        <div>
                            <ul style="background:none">
                                <li><em> 총 매수금액</em> <strong><span> KRW</span></strong><strong class="mb_krw_total_buy_price">0 </strong> </li>
                                <li><em> 총 평가금액</em> <strong><span> KRW</span></strong><strong class="mb_krw_total_evaluation_price">0 </strong> </li>
                            </ul>
                        </div>
                        <div>
                            <ul>
                                <li><em> 총 평가손익</em> <strong><span> KRW</span></strong><strong class="mb_krw_total_profit">0 </strong> </li>
                                <li><em> 총 평가수익률</em> <strong><span> %</span></strong><strong class="mb_krw_total_profit_rate">0 </strong> </li>
                            </ul>
                        </div>
                        <div>
                            <ul>
                                <li><em> 보유 KRW</em> <strong><span> KRW</span></strong><strong class="mb_krw_total">0 </strong> </li>
                                <li><em> 총 보유자산</em> <strong><span> KRW</span></strong><strong class="mb_krw_total_asset_held">0 </strong> </li>
                            </ul>
                        </div>
                    </div>
                    <table class=my-asset>
                        <colgroup>
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>보유코인</th>
                                <th>보유수량</th>
                                <th>매수평균가</th>
                                <th>매수금액</th>
                                <th>평가금액</th>
                                <th>평가손익</th>
                                <th>주문</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                foreach ($view['master'] as $key => $value) {
                                    $tmp = explode('_', $key);
                                    $cursymbol = $tmp[1];
                                    $krwsymbol = 'krw-'.strtolower($cursymbol);
							?>
                            <tr>
                                <td><em><?php Language::langConvert($view['langcommon'], $key); ?></em> <span>(<?= $cursymbol ?>)</span></td>
                                <td><strong><span class="mb_<?= strtolower($cursymbol) ?>_total">0</span></strong> <span class="color-grey"><?= $cursymbol ?></span></td>

                                <td><strong><span class="mb_<?= strtolower($cursymbol) ?>_average_buy_price">0</span></strong> <span class="color-grey">KRW</span></td>

                                <td> <strong><span class="mb_<?= strtolower($cursymbol) ?>_po_point">0</span> </strong> <span class="color-grey">KRW</span></td>
                                <td><strong> <span class="mb_<?= strtolower($cursymbol) ?>_evaluation_price">0</span> </strong> <span class="color-grey">KRW</span></td>
                                <td>
                                    <strong> <span class="mb_<?= strtolower($cursymbol) ?>_profit_rate">0</span> </strong><span class="color-grey"> %</span> <br>
                                    <strong class="mb_<?= strtolower($cursymbol) ?>_profit_rate_calc">0</strong> <span class="color-grey">KRW</span>
                                </td>
                                <td><button class="order" onClick="onOrder('<?php echo $krwsymbol; ?>')">주문</button> </td>
                            </tr>
                            <?php
                                }
                            ?>
                            <!--
                            <tr>
                                <td>비트코인 <span>BTC</span></td>
                                <td><strong>6.000000</strong></td>
                                <td>850,683 <span>KRW</span></td>
                                <td>850,683 <span>KRW</span></td>
                                <td><strong>850,683</strong> <span>KRW</span></td>
                                <td>-25374 <span>(%)</span> <br> -1,313,900<span>KRW</span> </td>
                                <td><button class="order">주문</button> </td>
                            </tr>
                            <tr>
                                <td>이더리움 <span>ETH</span></td>
                                <td><strong>6.000000</strong></td>
                                <td>850,683 <span>KRW</span></td>
                                <td>850,683 <span>KRW</span></td>
                                <td><strong>850,683</strong> <span>KRW</span></td>
                                <td>-25374 <span>(%)</span> <br> -1,313,900<span>KRW</span> </td>
                                <td><button class="order">주문</button> </td>
                            </tr>
                            <tr>
                                <td>라이트코인 <span>ETH</span></td>
                                <td><strong>6.000000</strong></td>
                                <td>850,683 <span>KRW</span></td>
                                <td>850,683 <span>KRW</span></td>
                                <td><strong>850,683</strong> <span>KRW</span></td>
                                <td>-25374 <span>(%)</span> <br> -1,313,900<span>KRW</span> </td>
                                <td><button class="order">주문</button> </td>
                            </tr>
                            -->
                        </tbody>
                    </table>
                </div>
                <!-- END :: PC MY ASSET -->
            </div>
            <!-- END :: contents -->
        </div>
        <!-- CONTAINER -->
        <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    </div>
    <!-- END :: wrap -->
    <script>Config.initFooterScript();</script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-order.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>
        function onOrder(v) {
            var link = tmpProtocol + '//' + tmpHost + '/trade/order/' + v;
            document.location.href = link;
        }
        $( document ).ready(function() {
            initOrderCompleteListData();
            $('select[name="ctype"]').val("krw-<?=$view['currency_type']?>").attr("selected", "selected");
            var w = $(window);
            function tableColumnWidth() {
                var selector = $('.table .list-tradecomplete colgroup col');
                if(w.width() > 769) {
                    $('.table .list-tradecomplete').css({
                        width: '100%'
                    });
                    selector.eq(0).attr('width', '6%');
                    selector.eq(1).attr('width', '11%');
                    selector.eq(2).attr('width', '11%');
                    selector.eq(3).attr('width', '11%');
                    selector.eq(4).attr('width', '11%');
                    selector.eq(5).attr('width', '11%');
                    selector.eq(6).attr('width', '11%');
                    selector.eq(7).attr('width', '11%');
                    selector.eq(8).attr('width', '11%');
                    selector.eq(9).attr('width', '6%');
                } else {
                    $('.table .list-tradecomplete').css({
                        width: '750px'
                    });
                    selector.eq(0).attr('width', '40px');
                    selector.eq(1).attr('width', '90px');
                    selector.eq(2).attr('width', '80px');
                    selector.eq(3).attr('width', '80px');
                    selector.eq(4).attr('width', '80px');
                    selector.eq(5).attr('width', '80px');
                    selector.eq(6).attr('width', '80px');
                    selector.eq(7).attr('width', '90px');
                    selector.eq(8).attr('width', '90px');
                    selector.eq(9).attr('width', '60px');
                }
            }
            tableColumnWidth();
            var a = 0;
            $( window ).resize(function() {
                tableColumnWidth();
            });
        });
        
        // 셀렉트 체인지 이벤트
        $('select[name="ctype"]').on('change', function(){
            listPageSet.current_page = 1;
            document.location.href = '/order/tradecomplete/'+$('select[name="ctype"]').val();
        });
    </script>
</body>