<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/index.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>
                    <style>
        @media (max-width: 768px){
            #snb{ display: none}
            #container .body-title{ height:0; border-bottom: 1px solid #ccc }
        }
    </style>
</head>

<body class="sub-background">
	
	<div id="wrap">
		<!-- HEADER -->
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
		<!-- // HEADER -->
		
		<!-- GNB -->
		<script src="<?=$view['url']['static']?>/assets/write/snb-cs.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<!-- // GNB -->
		
		<script>$('#snb ul li').eq(2).addClass('active');
          $('#gnb li a').eq(4).addClass('active');
        </script>
                 <div class="mobile-main-tab-col4 mt-46">
                 <ul>
                    <li>  </li>
                    <li> </li>
                    <li> </li>
                   <li> </li>
                </ul>
                </div>
		
		<!-- CONTAINER -->
		<div id="container">
			<div class="body-title">
				<div class="inner">
					<p class="tit"><?=$view['title']?></p>
				</div>
			</div>
			<div id="contents" style="min-height:300px">
				<div id="content-main" style="padding:20px 0;">데이터가 없습니다.</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	

    <script>
             (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/customer/main" class="round-btn4 ">'+langConvert('lang.menuNotice','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/customer/questionlist" class="round-btn4">'+langConvert('lang.menuCsQna','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/customer/guide" class="round-btn4 active">'+langConvert('lang.menuAbout','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/customer/faq" class="round-btn4">FAQ</a>');
			
	})(jQuery);     
    Config.initFooterScript();</script>
    <script>
        var language = Utils.getLanguage();
        var url = "/webpagemain/view/index-guide/";
        
        $.getJSON(url, "", function (data) {
        })
        .success(function(data) {
            if( data && data.length>0){
                if( data[0].hasOwnProperty('result') && parseInt(data[0].result) > 0){
                    if(language=='zh'){
                        $('#content-main').html(data[0]['pm_content_cn'].replace(/\\\"/gi,'"'));
                    }else if(language=='en'){
                        $('#content-main').html(data[0]['pm_content'].replace(/\\\"/gi,'"'));
                    }else{
                        $('#content-main').html(data[0]['pm_content_kr'].replace(/\\\"/gi,'"'));
                    }
                }else{
                    $('#content-main').html(langConvert('lang.viewEmptyData', ''));
                }
            }else{
                $('#content-main').html(langConvert('lang.viewEmptyData', ''));
            }
        })
        .error(function(){
            $('#content-main').html(langConvert('lang.viewEmptyData', ''));
        });
    </script>
</body>
