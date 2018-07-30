<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();setSocChannel('<?=$view['exchannel']['sock_ch']?>');</script>
</head>
<style>
@media(max-width: 768px) {
    #container .body-title{ height: 0px !important;}
     #snb{ display: none}
}
</style>
<body class="sub-background">
    
    <div id="wrap">
        <!-- HEADER -->
        <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
        <!-- // HEADER -->

        <script src="<?=$view['url']['static']?>/assets/write/snb-history.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>

        <script>$('#snb ul li').eq(2).addClass('active');
             $('.mobile-nav-bottom ul li a').eq(1).addClass('active');
             $('#gnb li a').eq(1).addClass('active');
        </script>
        <div class="mobile-main-tab" style="margin-top:46px">
             <ul>
                <li> <a href="/order/presence" class="round-btn"> 보유코인</a></li>
               <li> <a href="/order/tradecomplete" class="round-btn"> 체결내역</a></li>
                <li> <a href="/order/tradeuncomplete" class="round-btn active">미체결내역 </a></li>
             </ul>
        </div>
        

        <!-- CONTAINER -->
        <div id="container">
            <div class="body-title">
                <div class="inner">
                    <p class="tit"><?=Language::langConvert($view['lang'], 'openOrders');?><?=$view['title']?></p>
                </div>
            </div>
            <div id="contents">
                <div class="trade-history">
                    <div class="title">
                        <strong class="history-title"><?=Language::langConvert($view['langcommon'], 'KRW_'.strtoupper($view['currency_type']));?></strong>
                        <select class="select1" name="ctype">
                            <?php 
                                foreach ($view['master'] as $key => $value) {
                                    $tmp = explode('_', $key);
                                    $cursymbol = $tmp[1];
                            ?>
                            <option value="krw-<?=strtolower($cursymbol)?>"><?=$cursymbol?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="table">
                        <table class="list-tradenocomplete" style="min-width: 100%">
                            <thead>
                                <tr>
                                    <th><?=Language::langConvert($view['lang'], 'order');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'orderTime');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'orderPrice');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'orderVolume');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'contractVolume');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'incomplete');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'contractPrice');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'contractTotalPrice');?></th>
                                    <th><?=Language::langConvert($view['lang'], 'status');?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="pagenate" id="pager"></div>
                </div>
            </div>
        </div>
        <!-- CONTAINER -->

        <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    </div><!-- // wrap -->
    <script>Config.initFooterScript();</script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-order.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>
        $( document ).ready(function() {
            initOrderUnCompleteListData();
            $('select[name="ctype"]').val("krw-<?=$view['currency_type']?>").attr("selected", "selected");
        });
        
        // 셀렉트 체인지 이벤트
        $('select[name="ctype"]').on('change', function(){
            listPageSet.current_page = 1;
            document.location.href = '/order/tradeuncomplete/'+$('select[name="ctype"]').val();
        });
    </script>
</body>