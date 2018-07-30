<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/sha256.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>
    <style>
        .btn-google-otp{ background: #e2e2e2;  color: #000; border: 0; border-radius: 0 0 10px 10px;    position: relative;  top: 1px;    -webkit-border-radius: 8px;  -moz-border-radius: 8px;    border-radius: 8px; border: 1px solid #ccc; display: block;  margin: 10px auto 0 auto; width: 95%;  font-size: 15px; vertical-align: middle; padding:10px; text-align: center}
        @media (max-width: 768px){
            #snb{ display: none}  
            #container .body-title{ height:20px; }
            .sub-background{ background: #fff !important; }
            .mobile-main-tab-col4{ border-bottom: 1px solid #ccc}
            
        }
    </style>
</head>

<body class="sub-background">
    <div id="wrap" class="single none">
        <!-- HEADER -->
        <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
        <!-- // HEADER -->
        <!-- GNB -->
        <script src="<?=$view['url']['static']?>/assets/write/snb-account.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
        <!-- // GNB -->
        <script>$('#snb ul li').eq(3).addClass('active');
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
        <div id="container" class="flex-box flex-column flex-center">
            <div class="body-title">
                <div class="inner">
                    <p class="tit">OTP</p>
                </div>
            </div>
            <div id="contents">
                <div class="member w390">
                    <div class="form-box">
                        <div class="form-section">
                            <!-- ★★★★ 인증하기 전 ★★★★ -->
                            <?php if($view['member']['mb_otp_use'] == 'N'){?>
                            <form class="form-signin" id="otp-regist" onsubmit="return false">
                            <input type="hidden" name="encId" value="<?=base64_encode($view['member']['mb_id'])?>" />
                            <input type="hidden" name="g_key" value="<?=$view['otp']['key']?>" />
                            <input type="hidden" name="encPwd" value="" />
                            <input type="hidden" name="mb_nick" value="" />
                            <input type="hidden" name="ac_type" value="regist"/>
                            <input type="hidden" name="token" value="ad1fdb06f59c6b9d2f0034c7ac0ff43d"/>
                            
                            <h4><?php Language::langConvert($view['lang'], 'otpTitle1');?></h4>
                            <div class="form-inner mb40">
                                <div class="article">
                                    <dl>
                                        <dt><?php Language::langConvert($view['lang'], 'email');?></dt>
                                        <dd><input type="text" class="inp" disabled="" value="<?=$view['member']['mb_id']?>"></dd>
                                    </dl>	
                                </div>
                                <div class="article">
                                    <dl>
                                        <dt><?php Language::langConvert($view['lang'], 'password');?></dt>
                                        <dd><input type="password" class="inp" id="pwd_opt" name="mb_pwd" placeholder="<?php Language::langConvert($view['lang'], 'password');?> "></dd>
                                    </dl>	
                                </div>
                                <div class="article">
                                    <dl>
                                        <dt><?php Language::langConvert($view['lang'], 'code');?></dt>
                                        <dd><input type="text" class="inp otp-input" id="code_opt" name="g_otp" maxlength="6" placeholder="<?php Language::langConvert($view['lang'], 'code');?> "></dd>
                                    </dl>	
                                </div>
                                <div class="article">
                                    <div id="login-noty"></div>
                                    <div class="otp-box">
                                        <p class="qrcode"><img src="<?=$view['otp']['qrurl']?>" alt=""></p>
                                        <div class="txt">	
                                            <strong><?=$view['otp']['key']?></strong>
                                            <p><?php Language::langConvert($view['lang'], 'codeDesc');?><br>
                                            <input type="checkbox" name="mb_notify_exchange" value="exchange" class="checkbox2" id="ra1"><label for="ra1"><span></span><?php Language::langConvert($view['lang'], 'check');?></label> </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="article">
                                    <dl>
                                        <dt>OTP 설치 </dt>
                                        <span style="padding:5px; display:block">
                                        2단계 인증을 설정하면 인터넷이나 모바일 서비스에 연결되어 있지 않아도 Google OTP 앱을 통해 코드를 받을 수 있습니다.</span>
                                    </dl>	
                                </div>
                               <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="btn-google-otp">Google OTP 설치</a>
                                
                                <button style="margin: 10px auto 0 auto; width:95%; font-size:15px; background:#007ac7" type="button" id="btn_2fa" class="btn btn-l btn-block btn-danger">OTP <?php echo ($view['member']['mb_otp_use']=='Y')?Language::langConvert($view['lang'], 'disable'):Language::langConvert($view['lang'], 'enable')?></button>
                            </div>
                            </form>
                            <!-- ★★★★ 인증하기 전 ★★★★ -->

                            <?php }else{?>
                            <!-- ★★★★ 인증한 후  ★★★★ -->
                            <form class="form-signin" id="otp-status" onsubmit="return false">
                            <input type="hidden" name="encId" value="<?=base64_encode($view['member']['mb_id'])?>" />
                            <input type="hidden" name="mb_nick" value="" />
                            <input type="hidden" id="status_type" name="status_type" value=""/>
                            <input type="hidden" name="token" value="ad1fdb06f59c6b9d2f0034c7ac0ff43d"/>
                            <input type="hidden" id="otp_login" name="otp_login" value=""/>
                            <input type="hidden" id="otp_withdraw" name="otp_withdraw" value=""/>
                            <h4><?php Language::langConvert($view['lang'], 'code');?></h4>
                            <div class="form-inner mb40">
                                <div class="article">
                                    <div class="chek">
                                        <p>
                                            <input type="checkbox" class="checkbox2" id="login_otp" name="login_otp" <?php echo ($view['member']['otp_login']=='Y')?"checked":"";?>><label for="login_otp"><span></span> <?php Language::langConvert($view['lang'], 'login');?></label> 
                                            <span><?php Language::langConvert($view['lang'], 'loginTxt');?></span>
                                        </p>
                                        <p>
                                            <input type="checkbox" class="checkbox2" id="deal_otp" name="deal_otp" <?php echo ($view['member']['otp_withdraw']=='Y')?"checked":"";?>><label for="deal_otp"><span></span> <?php Language::langConvert($view['lang'], 'withdrawal');?></label> 
                                            <span><?php Language::langConvert($view['lang'], 'withdrawalTxt');?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="article">
                                    <dl>
                                        <dt><?php Language::langConvert($view['lang'], 'number');?></dt>
                                        <dd><input type="text" class="inp otp-input" maxlength="6" name="g_otp1" placeholder="OTP <?php Language::langConvert($view['lang'], 'number');?>"></dd>
                                    </dl>
                                </div>
                                
                                <button type="button" class="btn btn-l btn-block btn-blue btn-bottom" id="btn_update"><?php Language::langConvert($view['lang'], 'save');?></button>
                            </div>

                            <h4><?php Language::langConvert($view['lang'], 'otpTitle3');?></h4>
                            <div class="form-inner">
                                <div class="article">
                                    <dl>
                                        <dt><?php Language::langConvert($view['lang'], 'number');?></dt>
                                        <dd><input type="text" class="inp otp-input" maxlength="6" name="g_otp2" placeholder="OTP <?php Language::langConvert($view['lang'], 'number');?>"></dd>
                                    </dl>
                                </div>
                                <button type="button" class="btn btn-l btn-block btn-danger btn-bottom" id="btn_delete"><?php Language::langConvert($view['lang'], 'otpTitle3');?></button>
                            </div>
                            </form>
                            <!-- ★★★★ 인증한 후  ★★★★ -->
                            <?php }?>
                            
			</div>
                    </div>
		</div>
            </div>
	</div>
	<!-- CONTAINER -->
		
	<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    </div><!-- // wrap -->
	
	
    <script>
 (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/account/verificationcenter" class="round-btn4 ">'+langConvert('lang.menuAccountVerificationCenter','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/account/signedit" class="round-btn4 ">'+langConvert('lang.menuAccountSignEdit','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/account/connectioninfo" class="round-btn4">'+langConvert('lang.menuAccountConnectionInfo','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/account/otp" class="round-btn4 active">'+langConvert('lang.menuAccountOtp','')+'</a>');
        
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
        $('.otp-input').keyup(function(){
            $(this).val($(this).val().replace(/[^0-9]/g,""));
        });
        
        var notyobj = $('div#login-noty');
        var btnid = '#btn_2fa';
        var btnid1 = '#btn_update';
        var btnid2 = '#btn_delete';

        $(btnid).click(function (){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/webmember/login2ch"}
            });
            notyobj.html('');
            if(!$( "input[name=mb_pwd]" ).val()){
                notyobj.html('<p>'+langConvert('lang.msgPleaseInputPassword','')+'</p>');
                notyobj.css("color", "#d00000");
                $(btnid).attr("disabled", false);
            }else if(!$( "input[name=g_otp]" ).val()){
                notyobj.html('<p>'+langConvert('lang.msgPleaseInputOtpCode','')+'</p>');
                notyobj.css("color", "#d00000");
                $(btnid).attr("disabled", false);
            }else if(!$( "#ra1:checked" ).val()){
                $("label[for='ra1']").addClass('red');
            }else{
                notyobj.html('');
                var uid = '<?=$view['member']['mb_id']?>';
                var enpwd = $.base64.encode($('input[name=mb_pwd]').val());
                enpwd = sha256_digest(uid+enpwd);
                $('input[name=encPwd]').val(enpwd);
                $( "input[name=mb_pwd]" ).val('');
                controllerForm.login2ch(btnid,'#otp-regist',interfaceCompletLogin);
            }
        });
        
        $(btnid1).click(function (){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/webmember/otpstatus"}
            });
            if($("#login_otp").is(":checked") == true){
                $("#otp_login").val("Y");
            }else{
                $("#otp_login").val("N");
            }
            if($("#deal_otp").is(":checked") == true){
                $("#otp_withdraw").val("Y");
            }else{
                $("#otp_withdraw").val("N");
            }
            $("#status_type").val('update');
            $(btnid1).attr("disabled", true);
            $(btnid2).attr("disabled", true);
            controllerForm.otpstatus(btnid1,'#otp-status',interfaceCompletLoginStatus);
        });
        
        $(btnid2).click(function (){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/webmember/otpstatus"}
            });
            $("#status_type").val('delete');
            $(btnid1).attr("disabled", true);
            $(btnid2).attr("disabled", true);
            controllerForm.otpstatus(btnid2,'#otp-status',interfaceCompletLoginStatus);
        });

        $("#pwd_opt").on("input keypress keyup change focus", function () {
            var pwdtemp = $(this).val();
            var codetemp = $("#code_opt").val();

            if(pwdtemp.length != 0 && codetemp.length != 0){
                $("#btn_2fa").attr("disabled", false);
                $("#btn_2fa").addClass("btn-blue");
            }
            else {
                $("#btn_2fa").attr("disabled", true);
                $("#btn_2fa").removeClass("btn-blue");
            }
        });

        $("#code_opt").on("input keypress keyup change focus", function () {
            var pwdtemp = $(this).val();
            var codetemp = $("#pwd_opt").val();

            if(pwdtemp.length != 0 && codetemp.length != 0){
                $("#btn_2fa").attr("disabled", false);
                $("#btn_2fa").addClass("btn-blue");
            }
            else {
                $("#btn_2fa").attr("disabled", true);
                $("#btn_2fa").removeClass("btn-blue");
            }
        });

        function interfaceCompletLogin(json){
            if(Number(json.result) > 0){
                document.location.reload();      

            }else if(Number(json.result) == 0){
                $(btnid).attr("disabled", false);
                controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectAuthenticationMember','')+'</p>');

            }else{
                $(btnid).attr("disabled", false);
                controllerComm.alertError(json.error,function(){

                });
            }
        }
        
        function interfaceCompletLoginStatus(json){
            if(Number(json.result) > 0){
                document.location.reload();      

            }else if(Number(json.result) == 0){
                $(btnid1).attr("disabled", false);
                $(btnid2).attr("disabled", false);
                controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectAuthenticationMember','')+'</p>');

            }else{
                $(btnid1).attr("disabled", false);
                $(btnid2).attr("disabled", false);
                controllerComm.alertError(json.error,function(){

                });
            }
        }
    </script>
</body>