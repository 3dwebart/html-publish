<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/sha256.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script> Config.initHeaderScript();</script>
        <style>
        @media (max-width: 768px){
            #snb{ display: none}  
            #container .body-title{ height:20px; }
        }
    </style>
</head>

<body class="sub-background">
	
	<div id="wrap" class="single">
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		
		<script>$('#snb ul li').eq(3).addClass('active');
         $('.mobile-nav-bottom ul li a:last').addClass('active');
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
			<div id="contents">
				 <div class="member w390">
					<div class="form-box">
						<div class="form-section">
							<h4 style="margin-top:20px"><?php Language::langConvert($view['lang'], 'otpInput');?></h4>
							<form class="form-signin form-inner " id="otp-regist" onsubmit="return false">
							<input type="hidden" name="encId" value="<?=$_POST['encId']?>" />
							<input type="hidden" name="encPwd" value="<?=$_POST['encPwd']?>" />
							<input type="hidden" name="mb_nick" value="" />
							<input type="hidden" name="client_id" value=""/>
							<input type="hidden" name="ac_type" value="login"/>
							<input type="hidden" name="token" value="ad1fdb06f59c6b9d2f0034c7ac0ff43d"/>
								
								<div class="article">
									<dl>
										<dt>OPT 번호</dt>
										<dd><input type="text" class="inp" name="g_otp" id="g_otp" autofocus placeholder="<?php Language::langConvert($view['lang'], 'otpInput');?>"></dd>
									</dl>
								</div>
								<!--<div class="mb30" id="req-noty"></div>-->
								<button class="btn-type1" type="submit" id="btn_login"><?php Language::langConvert($view['lang'], 'btnSignIn');?></button>
									
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		
	</div>	
    
    
    <script>
        (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/account/signedit" class="round-btn4 ">'+langConvert('lang.menuAccountSignEdit','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/account/verificationcenter" class="round-btn4">'+langConvert('lang.menuAccountVerificationCenter','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/account/connectioninfo" class="round-btn4">'+langConvert('lang.menuAccountConnectionInfo','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/account/otp" class="round-btn4 active">'+langConvert('lang.menuAccountOtp','')+'</a>');
			
	})(jQuery);
        Config.initFooterScript();</script>
    <script>
        if(document.URL.indexOf('return-')!==-1){
            $('.container.comment').append('<div class="alert list-group-item-warning" style="display:none;"><span>'+langConvert('lang.msgPleaseLogin', '')+'</span></div>');
            var options = {};

            $('.alert').show( 'slide',function() {
                setTimeout(function() {
                    $('.alert').hide('slow');
                },3000);
            });
        }

        controllerForm.setBeanData({
            result:0,
            link:{proc:"/webmember/login2ch"}
        });

        var btnid = '#btn_login';

        $(btnid).click(function (){
                controllerForm.login2ch(btnid,'#otp-regist',interfaceCompletLogin);
        });

        function interfaceCompletLogin(json){
            if(Number(json.result) > 0){
                var param = document.URL;
                var cor = 'return-';
                if(param.indexOf(cor)!==-1){
                    var tmparr = param.split(cor);
                    var reurl = (tmparr.length===2)?'/'+tmparr[1]:'/';
                    $(location).attr('href',reurl);
                }else{
                    $(location).attr('href',"/");
                }        

            }else if(Number(json.result) == 0){
                $(btnid).attr("disabled", false);
                controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectAuthenticationMember','')+'</p>');

            }else{
                $(btnid).attr("disabled", false);
                controllerComm.alertError(json.error,function(){
                    location.href='/account/signin/';
                });
            }
        }
    </script>
</body>
