<head>
   <!-- <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" > -->
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script> Config.initHeaderScript();</script>
</head>
<body class="sub-background">
    
    <div id="wrap">
		<!-- HEADER -->
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
		<!-- // HEADER -->
		
		<!-- GNB -->
		<script src="<?=$view['url']['static']?>/assets/write/snb-about.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<!-- // GNB -->
		
		<script>$('#snb ul li').eq(2).addClass('active');</script>
		
		<!-- CONTAINER -->
		<div id="container">
			<div class="body-title">
				<div class="inner">
					<p class="tit"><?=$view['title']?></p>
				</div>
			</div>
			<div id="contents">
				 <div id="content-main">Loading..</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
    <script>Config.initFooterScript();</script>
    <script>
        var language = Utils.getLanguage();
        var url = "/webpagemain/view/index-mbleave/";
        
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