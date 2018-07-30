<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/index.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script>Config.initHeaderScript();</script>
</head>

<body class="sub-background">
	
	
	<div id="wrap">
		<!-- HEADER -->
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
		<!-- // HEADER -->
		
		<!-- GNB -->
		<script src="<?=$view['url']['static']?>/assets/write/snb-cs.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<!-- // GNB -->
		
		<script>$('#snb ul li').eq(1).addClass('active');</script>
		
		<!-- CONTAINER -->
		<div id="container">
			<div class="body-title">
				<div class="inner">
					<p class="tit"><?=$view['title']?></p>
				</div>
			</div>
			<div id="contents">
				<div class="member">
					<div class="form-box">
						<form id="qna" onsubmit="return false">
						<div class="form-section">
							<div class="article">        
								<dl>     
									<dt><?php Language::langConvert($view['lang'], 'subject');?></dt>
									<dd>
										<select name="qna_subject" class="select">
											<option value="<?php Language::langConvert($view['lang'], 'generalQuestion');?>"><?php Language::langConvert($view['lang'], 'generalQuestion');?></option>
											<option value="<?php Language::langConvert($view['lang'], 'bitcoin');?>"><?php Language::langConvert($view['lang'], 'bitcoin');?></option>
											<option value="<?php Language::langConvert($view['lang'], 'bitcoinSellPurchase');?>"><?php Language::langConvert($view['lang'], 'bitcoinSellPurchase');?></option>
										</select>
									</dd>
								</dl> 
							</div>
							<div class="article">        
								<dl>     
									<dt><?php Language::langConvert($view['lang'], 'username');?></dt>
									<dd><input name="qna_nm" type="text" readonly="true" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'usernamePlaceHolder');?>" value="<?=$view['mb_name']?>">
										<input name="qna_email" type="hidden" class="form-control" placeholder="<?php Language::langConvert($view['lang'], 'emailPlaceHolder');?>" value="<?=$view['mb_id']?>">
										<p id="qna_nm_alert"></p>
									</dd>
								</dl> 
							</div>
							<div class="article">        
								<dl>     
									<dt><?php Language::langConvert($view['lang'], 'whatQuestion');?></dt>
									<dd>
										<textarea class="textarea" rows="10" name="qna_contents" placeholder="<?php Language::langConvert($view['lang'], 'pleaseEnterYourContactDetails');?>"></textarea>
										<p id="qna_contents_alert"></p>
									</dd>
								</dl> 
							</div>
							<div class="article">
								<dl>
									<dt>&nbsp;</dt>
									<dd><button type="button" class="btn btn-block btn-l btn-primary" id="btn-qna"><?php Language::langConvert($view['lang'], 'btnQuestionRequest');?></button></dd>
								</dl>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
    <script>Config.initFooterScript();</script>
    <script>

        $("#btn-qna").click(function(){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/webbbsmain/regist"}
            });
            controllerForm.setOnComplet=function(result){
                if(result && result!=="undefined"){
                    controllerComm.alertError('<p>'+langConvert('lang.msgInquiryHasBeenRegisterThankYou', '')+'</p>',function(){
                        $(location).attr('href',"/customer/questionlist");
                    });
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.msgTheInquiryFailedToRegister', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>');
                }
            };
            controllerForm.setInitForm("qnaSend");
        });

        controllerComm.nprogressDone();

    </script>
</body>
