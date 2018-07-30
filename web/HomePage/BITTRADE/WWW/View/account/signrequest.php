<head>
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
<style>
#container {
    min-height: 650px;
}
            @media (max-width: 768px){
            .mobile-nav-bottom{ display: none}
                    
        }
</style>	
	<div id="wrap" class="single">
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<script> $('.mobile-nav-bottom ul li a').eq(3).addClass('active');
            $('#gnb li a').eq(3).addClass('active');
        </script>
		
		<!-- CONTAINER -->
		<div id="container">
			<div id="contents" class="none-shadow">
				<div class="member">
					<!-- Login -->
					<div class="shadow-round">
						<form class="form-signin form-box"  id="flogin" onsubmit="return false">
							<input type="hidden" name="client_id" value=""/>
							<div id="login" class="form-section">
								<h4><?php Language::langConvert($view['lang'], 'signUpEmailRequest');?></h4>
								<div class="form-inner none-shadow">
									<div class="article">
										<input name="mb_id" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'emailAddress');?>">
									</div>
									<button class="btn btn-l btn-block btn-primary" type="submit" id="btn_submit" style="    max-width: 100%; margin: 0 auto; font-size: 14px; background: #007ac7;"><?php Language::langConvert($view['lang'], 'btnSingUpEmailRequest');?></button>
									<div class="btns solo" style="    max-width: 100%; margin-top: 10px; font-size: 14px; background: #e2e2e2;"><a href="/account/signrequestpwd"><?php Language::langConvert($view['lang'], 'pwdRecoveryRequest');?></a></div>
								</div>
							</div>
						</form>
					</div>
					<!-- // Login -->
				</div>
			</div>
		</div>
		<!-- CONTAINER -->
		

		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		
	</div><!-- // wrap -->
	
    <script>Config.initFooterScript();</script>
    <script>
        $('#nav-ticker').hide();

        $("#btn_submit").click(function(){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/email/registconfirm"}
            });
            controllerForm.setInitForm("memberrequestjoinemail");
        });
    </script>
</body>
