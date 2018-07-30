<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/sha256.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-comm.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-form.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script> Config.initHeaderScript();</script>
    <style>
        .mobile-nav-bottom{ display: none}
        #container {min-height: 200px;}
        #wrap {
            display: flex;
            flex-direction: column;
        }
        @media(min-width: 769px) {
            #wrap {
                justify-content: space-between;
            }
        }
    </style>
</head>

<body  class="sub-background">
	<div id="wrap" class="single">
		<!-- HEADER -->
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
		<!-- // HEADER -->
		<script>$('#snb ul li').eq(2).addClass('active');</script>
		<!-- CONTAINER -->
		<div id="container">
			<div id="contents" class="none-shadow">
				<!-- Login -->
				<div id="login" class="member">
					<div>
						<!-- <h4><?php // Language::langConvert($view['lang'], 'login');?></h4> -->
						<!--<div class="mb10 container comment"></div>-->
						<div class="alert" id="login-noty"></div>
						<form class="form-signin" id="flogin" onsubmit="return false">
							<input type="hidden" name="encPwd" value="" />
							<input type="hidden" name="encId" value="" />
							<input type="hidden" name="mb_nick" value="" />
							<input type="hidden" name="client_id" value=""/>
							<input type="hidden" name="token" value="ad1fdb06f59c6b9d2f0034c7ac0ff43d"/>
							<div class="form-box">
								<div class="form-inner form-section">
                                <div class="icon-signup-wrap">
                                    <h2>Log-in</h2>
                                </div>
									<div class="article">
                                        <input type="text" name="mb_id" id="mbId" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'emailPlaceHolder');?>" autofocus>
									</div>
									<div class="article">
                                        <input type="password" name="mb_pwd" id="mbPwd" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'passwordPlaceHolder');?>">
									</div>
                                    <div class="save-check">
                                        <label for="idSaveCheck">
                                            <span></span>
                                            <input type="checkbox" id="idSaveCheck"/> <?php Language::langConvert($view['lang'], 'saveid');?>
                                        </label>
                                        <label for="pwdSaveCheck">
                                            <span></span>
                                            <input type="checkbox" disabled id="pwdSaveCheck" class="no_act"/> <?php Language::langConvert($view['lang'], 'savepw');?>
                                        </label>
                                    </div>
									<!--
									<p><input type="text" name="mb_id" id="mbId" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'emailPlaceHolder');?>" autofocus></p>
									<p><input type="password" name="mb_pwd" id="mbPwd" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'passwordPlaceHolder');?>"></p>
									-->
									<div class="g-recaptcha" data-sitekey="<?=$view['captcha_sitekey']?>" style="margin:10px 0;"></div>
                                    <button style="margin:0 auto; width:95%; font-size:14px; display:block;     background: #007ac7;" class="btn-type1" type="submit" id="btn_login"><?php Language::langConvert($view['lang'], 'btnSignIn');?></button>
                                    <div class="btns"> 
                                        <a href="/account/signup"><strong><?php Language::langConvert($view['lang'], 'signUp');?></strong></a>
                                        <a href="/account/signrequestpwd"><?php Language::langConvert($view['lang'], 'findPwd');?></a>
                                    </div>
								</div>                                    
							</div>
						</form>
					</div>
				</div>
				<!-- // Login -->
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->

    <form id="login2ch" method="POST" action="/account/signin2ch">
        <input type="hidden" name="encPwd" value="" />
        <input type="hidden" name="encId" value="" >
    </form>

    <script>Config.initFooterScript();</script>
    <script>
        var accountPageSet = {
            recaptcha:false
        };

        <?php
            if((int)$view['warncount']>0){
                echo 'accountPageSet.recaptcha=true;';
            }
        ?>

        $( document ).ready(function() {
            if(accountPageSet.recaptcha==false){
                $('div.g-recaptcha').css('display', 'none');
            }
        });

        $('#nav-ticker').hide();
        $('input[name=client_id]').val(get_client.id);

        if(document.URL.indexOf('return-')!==-1){
            $('.container.comment').append('<div class="alert list-group-item-warning" style="display:none;"><span>'+langConvert('lang.msgPleaseLogin', '')+'</span></div>');
            var options = {};

            $('.alert').show( 'slide',function() {
                setTimeout(function() {
                    $('.alert').hide('slow');
                },3000);
            });
        }

        if( Utils.getCookie('login-id') ){
            $('input[name=mb_id]').val(Utils.getCookie('login-id'));
            $("#remember-me").attr('checked', true) ;
        }

        controllerForm.setBeanData({
            result:0,
            link:{proc:"/webmember/login"}
        });

        controllerForm.login('#btn_login','#flogin',interfaceCompletLogin);

        function interfaceCompletLogin(){
            var param = document.URL;
            var cor = 'return-';
            if(param.indexOf(cor)!==-1){
                var tmparr = param.split(cor);
                var reurl = (tmparr.length===2)?'/'+tmparr[1]:'/';
                $(location).attr('href',reurl);
            }else{
                $(location).attr('href',"/");
            }
        }

        var recaptchaOnloadCallback = function() {
            accountPageSet.recaptcha = true;
            $('div.g-recaptcha').css('display', 'block');
            grecaptcha.reset();
        };
    </script>
    <script>
        function setCookie(cookieName, value, exdays){
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var cookieValue = escape(value) + ((exdays==null) ? "" : "; expires=" + exdate.toGMTString());
            document.cookie = cookieName + "=" + cookieValue;
        }
        
        function deleteCookie(cookieName){
            var expireDate = new Date();
            expireDate.setDate(expireDate.getDate() - 1); //어제날짜를 쿠키 소멸날짜로 설정
            document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString();
        }
        
        function getCookie(cookieName) {
            cookieName = cookieName + '=';
            var cookieData = document.cookie;
            var start = cookieData.indexOf(cookieName);
            var cookieValue = '';
            if(start != -1){
                start += cookieName.length;
                var end = cookieData.indexOf(';', start);
                if(end == -1)end = cookieData.length;
                cookieValue = cookieData.substring(start, end);
            }
            return unescape(cookieValue);
        }
    </script>
    <script>
        (function($) {
            if($("input[name='mb_id']").val() != '') {
                $("#idSaveCheck").attr("checked", true);
                $("#idSaveCheck").parent().removeClass('on');
            }
            if($("input[name='mb_pwd']").val() != '') {
                $("#pwdSaveCheck").attr("checked", true);
                $("#pwdSaveCheck").removeAttr("disabled");
                $("#pwdSaveCheck").parent().removeClass('on');
            }
            //Id 쿠키 저장
            var userInputId = getCookie("userInputId");
            $("input[name='mb_id']").val(userInputId);

            if($("input[name='mb_id']").val() != "") {
                $("#idSaveCheck").attr("checked", true);
                $("#pwdSaveCheck").removeAttr("disabled");
            }

            $("#idSaveCheck").change(function() {
                if($("#idSaveCheck").is(":checked"))        {
                    //id 저장 클릭시 pwd 저장 체크박스 활성화
                    $("#pwdSaveCheck").removeAttr("disabled");
                    $("#pwdSaveCheck").removeClass('no_act');
                    var userInputId = $("input[name='mb_id']").val();
                    setCookie("userInputId", userInputId, 365);
                    $(this).parent().addClass('on');
                } else {
                    deleteCookie("userInputId");
                    $("#pwdSaveCheck").attr("checked", false);
                    deleteCookie("userInputPwd");
                    $("#pwdSaveCheck").attr("disabled", true);
                    $("#pwdSaveCheck").addClass('no_act');
                    $(this).parent().removeClass('on');
                    $("#pwdSaveCheck").parent().removeClass('on');
                }
            });


            $("input[name='mb_id']").keyup(function(){ 
                if($("#idSaveCheck").is(":checked")){ 
                    var userInputId = $("input[name='mb_id']").val();
                    setCookie("userInputId", userInputId, 365);
                }
            });

            //Pwd 쿠키 저장 
            var userInputPwd = getCookie("userInputPwd");
            $("input[name='mb_pwd']").val(userInputPwd); 
            
            if($("input[name='mb_pwd']").val() != ""){ 
                $("#pwdSaveCheck").attr("checked", true);
                $("#pwdSaveCheck").removeClass('no_act');
            }

            $("#pwdSaveCheck").change(function(){
                if($("#pwdSaveCheck").is(":checked")){
                    var userInputPwd = $("input[name='mb_pwd']").val();
                    setCookie("userInputPwd", userInputPwd, 365);
                    $(this).parent().addClass('on');
                }else{
                    $(this).parent().removeClass('on');
                    deleteCookie("userInputPwd");
                }
            });

            $("input[name='mb_pwd']").keyup(function() {
                if($("#pwdSaveCheck").is(":checked")) {
                    var userInputPwd = $("input[name='mb_pwd']").val();
                    setCookie("userInputPwd", userInputPwd, 365);
                }
            });
        })(jQuery);
    </script>
</body>
