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
    <script src="<?=$view['url']['static']?>/assets/js/sha256.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>
    <style>
        @media (max-width: 768px){
            #snb{ display: none}    
            #container .body-title{ height:20px; }
        }
       .shadow-round{
           max-width: 390px;
}
    </style>
</head>

<body class="sub-background">
	
	<div id="wrap" class="single none">
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		
		
		<!-- GNB  -->
		<script src="<?=$view['url']['static']?>/assets/write/snb-account.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<!--GNB -->
        <script>
            $('#snb ul li').each(function() {
                var tabActive = $(this).find('a').attr('href');
                if(tabActive == tmpUrl) {
                    $(this).addClass('active');
                }
            });
            $('.mobile-nav-bottom ul li a:last').addClass('active');
              $('#gnb li a').eq(3).addClass('active');
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
			<div id="contents" class="sub-background none-shadow">
				
				<div class="member">
                   
					<div class="form-box">
                         <div class="shadow-round"> 
						<div class="form-section">
                            <h4>회원정보 변경</h4>
							<form class="form-horizontal form-inner form-signedit none-shadow" id="form" onsubmit="return false">
								<input type="hidden" name="mb_encPwd" value="" >
								<input type="hidden" name="mb_new_encPwd" value="" >
								<input type="hidden" name="mb_cert_request" value="0" >
								<input type="hidden" name="mb_edit_type" value="1">

								<div class="article">
									<input name="mb_id" id="mb_id" type="text" class="inp" readonly="true" value="<?=$view['member']['mb_id']?>"> 
								</div>
								<div class="article">
								 
											<input name="mb_name" id="mb_name" type="text" class="inp" readonly="true" value="<?=$view['member']['mb_name']?>">
											<p class="alert" id="edit_mb_name_alert"></p>
										 
									 
								</div>
								<div class="article">
									 
											<input name="mb_pwd" id="mb_pwd" type="password" class="inp" maxlength="30" placeholder="<?=Language::langConvert($view['lang'], 'nowpwd')?>" autofocus>
											<p class="alert" id="edit_mb_pwd_alert"></p>
									 
								</div>
								<div class="article">
									 
											<input name="mb_new_pwd" id="mb_new_pwd" type="password" class="inp" maxlength="30" placeholder="<?=Language::langConvert($view['lang'], 'newpwd')?>">
											<p class="alert" id="edit_mb_new_pwd_alert"></p>
 
								</div>
								<div class="article">
									 
											<input name="mb_new_pwd_re" id="mb_new_pwd_re" type="password" class="inp" placeholder="<?=Language::langConvert($view['lang'], 'newpwdconfirm')?>" onchange="PassWord_Check()">
											<p class="alert" id="edit_mb_new_pwd_re_alert"></p>
								 
								</div>
								<button style="max-width:100%; margin:0 auto; font-size:14px; background: #007ac7;"  type="button" id="btn_edit" class="btn btn-l btn-block btn-danger"><?php Language::langConvert($view['lang'], 'btnSignEdit');?></button> 
							</form>
						</div>
				   </div>
                        </div> <!--form box-->
                 
				</div>
				
			</div>
		</div>
		<!-- CONTAINER -->
		

		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		
	</div><!-- // wrap -->
	
	
		
	
	
    <script>
   (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/account/verificationcenter" class="round-btn4 ">'+langConvert('lang.menuAccountVerificationCenter','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/account/signedit" class="round-btn4 active">'+langConvert('lang.menuAccountSignEdit','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/account/connectioninfo" class="round-btn4">'+langConvert('lang.menuAccountConnectionInfo','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/account/otp" class="round-btn4">'+langConvert('lang.menuAccountOtp','')+'</a>');
        
        var infoTextOrigin = $('.mobile-main-tab-col4 ul li a').eq(1).text();
        var infoTextChange = '정보변경';
        function memberInfoTextChange() {
            if($(window).width() > 340) {
                $('.mobile-main-tab-col4 ul li a').eq(1).text(infoTextOrigin);
            } else {
                $('.mobile-main-tab-col4 ul li a').eq(1).text(infoTextChange);
            }
        }
        memberInfoTextChange();
        $(window).resize(function() {
            memberInfoTextChange();
        });
	})(jQuery);
        
        Config.initFooterScript();</script>
    <script>
        function PassWord_Check()
        {
            var Passwd_chk = {
                Password1:$("input[name='mb_new_pwd']"),
                Password2:$("input[name='mb_new_pwd_re']")
            };
            var alert_mbpw2 = $('#edit_mb_new_pwd_re_alert');
            
            if(Passwd_chk.Password1.val() != Passwd_chk.Password2.val()){
                alert_mbpw2.html('<p>'+langConvert('lang.msgPasswordsDoNotMatch', '')+'</p>');
                alert_mbpw2.show();
            }else{
                alert_mbpw2.html('');
                alert_mbpw2.hide();
            }

        }

        $("#btn_edit").click(function(){
            /****************비밀번호체크************************/
            var re1 = /[a-zA-Z]/i; // 영문
            var re2 = /[0-9]/i; // 숫자
            var re3 = /[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?]/; // 특수문자
            var alpaBig= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            var alpaSmall= "abcdefghijklmnopqrstuvwxyz";
            var num = "01234567890";

            var Passwd_chk = {
                Password1:$("input[name='mb_new_pwd']"),
                Password2:$("input[name='mb_new_pwd_re']")
            };

            //영문,숫자,특수문자포함 입력체크
            if((!re1.test(Passwd_chk.Password1.val())) && (!re2.test(Passwd_chk.Password1.val())) && (!re3.test(Passwd_chk.Password1.val()))) {
                controllerComm.alertError(langConvert('lang.msgThePasswordMustContainLettersAndNumbersAndSpecialCharacters', ''));
                return false;
            }else if((!re2.test(Passwd_chk.Password1.val())) && (!re3.test(Passwd_chk.Password1.val()))){
                controllerComm.alertError(langConvert('lang.msgThePasswordShouldIncludeNumbersAndSpecailCharacters', ''));
                return false;
            }else if(!re2.test(Passwd_chk.Password1.val())){
                controllerComm.alertError(langConvert('lang.msgThePasswordMustContainNumber', ''));
                return false;
            }else if(!re3.test(Passwd_chk.Password1.val())){
                controllerComm.alertError(langConvert('lang.msgThePasswordmustContainSpecailCharacters', ''));
                return false;
            }else if(!re1.test(Passwd_chk.Password1.val())){
                controllerComm.alertError(langConvert('lang.msgThePasswordMustContainInEnglish', ''));
                return false;
            }else{
                //8자리 이상입력체크
                if(Passwd_chk.Password1.val().length < 8){
                    controllerComm.alertError(langConvert('lang.msgPleaseEnterLeast8Charaters', ''));
                    return false;
                }
            }
            /****************비밀번호체크 끝************************/
            Account.isLogined(function(islogin){
                if(islogin){
                    controllerForm.setBeanData({
                        result:0,
                        link:{proc:"/webmember/update"}
                    });

                    controllerForm.setInitForm("memberEdit");
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>',function(){
                        $(location).attr('href',"/account/signin");
                    });
                }
            });
        });

    </script>
</body>