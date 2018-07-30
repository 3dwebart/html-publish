<?php
session_start();

    $this->cfg = $cfg = Config::getConfig();
    $sitecode   = $this->cfg['niceauth']['sitecode'];       // NICE로부터 부여받은 사이트 코드
    $sitepasswd = $this->cfg['niceauth']['sitepasswd'];     // NICE로부터 부여받은 사이트 패스워드

    $cb_encode_path = $this->cfg['module']['niceauthclient'];

//    $cb_encode_path = "D:\\03.workspace\cointrade\WebApp\Module\CPClient.exe";		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
//    $cb_encode_path = "/opt/HomePage/cointrade/WebApp/Module/CPClient";		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)

    $authtype = "";      	// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드

    $popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
    $customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지

    $reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
                                    // 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
    $reqseq = `$cb_encode_path SEQ $sitecode`;

    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
    $returnurl  = $cfg['url']['site']."/account/authcheck";	// 성공시 이동될 URL
    $errorurl   = $cfg['url']['site']."/account/authfail";		// 실패시 이동될 URL
//    $returnurl  = "http://bit.opencrop.com/account/authcheck";	// 성공시 이동될 URL
//    $errorurl   = "http://bit.opencrop.com/account/authfail";		// 실패시 이동될 URL

    // reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.

    $_SESSION["REQ_SEQ"] = $reqseq;

    // 입력될 plain 데이타를 만든다.
    $plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
                "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
                "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
                "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
                "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
                "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
                "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

    $enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;
    $returnMsg = '';
    if( $enc_data == -1 )
    {
        $returnMsg = "암/복호화 시스템 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -2 )
    {
        $returnMsg = "암호화 처리 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -3 )
    {
        $returnMsg = "암호화 데이터 오류 입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -9 )
    {
        $returnMsg = "입력값 오류 입니다.";
        $enc_data = "";
    }
?>
<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/index.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-form.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>
        <style>
.table3 table thead th { padding: 11px 7px; text-align: center; font-size: 14px;  color: #5f5f5f;  border-left: 1px solid #ffffff;  border-bottom: 1px solid #d4d4d4;  background: #f3f3f3; font-weight: 500; }
.table3 table tbody td { font-size: 13px; padding: 11px 7px; text-align: center; border-left: 1px solid #d4d4d4; border-bottom: 1px solid #d4d4d4;}
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
        <script src="<?=$view['url']['static']?>/assets/write/snb-account.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
        <!-- // GNB -->

        <script>$('#snb ul li').eq(0).addClass('active');
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
            <div id="contents">
                <div class="comment"></div>
                <div class="verifi">
                    <form class="form-horizontal" name="form_chk" method="post">
                        <input type="hidden" name="m" value="checkplusSerivce">  <!-- 필수 데이타로, 누락하시면 안됩니다. -->
                        <input type="hidden" name="EncodeData" value="<?=$enc_data ?>"><!-- 위에서 업체정보를 암호화 한 데이타입니다. -->
                        <input type="hidden" name="param_r1" value="">
                        <input type="hidden" name="param_r2" value="">
                        <input type="hidden" name="param_r3" value="">
                    </form>
                    <div class="level">
                        <p class="lv" id="lv"><strong id="mb_level2">1</strong><span><?php Language::langConvert($view['lang'], 'level');?></span></p>
                        <p class="txt"><strong><span id="mb_name"></span></strong> 님은 현재 <span style="color: #0170c2;"><span id="mb_level"></span> 레벨</span> 입니다.</p>
                        <!--
                        <p class="txt"><span id="mb_name"></span> <?php // Language::langConvert($view['lang'], 'yourLevel');?> <span id="mb_level"></span></p>
    -->
                    </div>
                    <div class="level-step">
                        <div class="step1 active">
                            <i></i>
                            <p class="txt1"><?php Language::langConvert($view['lang'], 'level');?> 1</p>
                            <p class="txt2"><?php Language::langConvert($view['lang'], 'verifyYourEmail');?></p>
                            <p class="txt3"><span id="mb_id"></span></p>
                        </div>
                        <div class="step2">
                            <i></i>
                            <p class="txt1"><?php Language::langConvert($view['lang'], 'level');?> 2-1</p>
                            <p class="txt2"><?php Language::langConvert($view['lang'], 'mobileCheck');?></p>
                            <p class="btn-box">
                                <span id="mb_hp_result_true" class="txt3"><?php Language::langConvert($view['lang'], 'verified');?></span>
                                <span id="mb_hp_result_false"><button class="btn" id="btn_mobile"><?php Language::langConvert($view['lang'], 'authenticate');?></button></span>
                            </p>
                        </div>
                        <div class="step3">
                            <i></i>
                            <p class="txt1"><?php Language::langConvert($view['lang'], 'level');?> 2-2</p>
                            <p class="txt2"><?php Language::langConvert($view['lang'], 'authorization');?></p>
                            <p class="btn-box">
                                <span id="mb_info_result_true"><?php Language::langConvert($view['lang'], 'yourPhoneAuth');?></span>
                                <span id="mb_info_result_false">
                                    <button class="btn" id="btn_namecheck" ><?php Language::langConvert($view['lang'], 'nameCheck');?></button>
                                    <span id="str_or"> or</span>
                                    <button class="btn" id="btn_document"><?php Language::langConvert($view['lang'], 'documentAuthentication');?></button>
                                </span>
                            </p>
                        </div>
                        <div class="step4">
                            <i></i>
                            <p class="txt1"><?php Language::langConvert($view['lang'], 'securitySettings');?></p>
                            <p class="txt2"><?php Language::langConvert($view['lang'], 'otpAuthentication');?></p>
                            <p class="btn-box">
                                <span id=otp-bool><a href="/account/otp" class="btn">활성화</a></span>
                            </p>
                        </div>
                    </div>
                    <p class="desc"><?php Language::langConvert($view['lang'], 'desc1');?><br> <?php Language::langConvert($view['lang'], 'desc2');?></p>
                   <div class="table3">
                        <table>
                            <colgroup>
                                <col style="width:20%">
                                <col style="width:20%">
                                <col style="width:30%">
                                <col style="width:30%">
                            </colgroup>
                            <thead>
                                <th><?php Language::langConvert($view['lang'], 'dailyLimits');?></th>
                                <th><?php Language::langConvert($view['lang'], 'kind');?></th>
                                <th>Lv1</th>
                                <th>Lv2</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php Language::langConvert($view['lang'], 'depositLimit');?></td>
                                    <td><?php Language::langConvert($view['lang'], 'krwCoin');?></td>
                                    <td>0</td>
                                    <td><?php Language::langConvert($view['lang'], 'unlimited');?></td>
                                </tr>
                                <tr>
                                    <td rowspan="9"><?php Language::langConvert($view['lang'], 'withdrawalLimit');?></td>
                                    <td><?php Language::langConvert($view['langcommon'], 'KRW_KRW');?></td>
                                    <td class="lv1-KRW">0</td>
                                    <td class="lv2-KRW">0</td>
                                </tr>
                                <?php 
                                    foreach ($view['master'] as $key => $value) {
                                        $tmp = explode('_', $key);
                                        $cursymbol = $tmp[1];
                                ?>
                                <tr>
                                    <td><?php Language::langConvert($view['langcommon'], 'KRW_'.$cursymbol);?></td>
                                    <td class="lv1-<?=$cursymbol?>">0.00000000 <?=$cursymbol?></td>
                                    <td class="lv2-<?=$cursymbol?>">0.00000000 <?=$cursymbol?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
        <!-- CONTAINER -->

        <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    </div><!-- // wrap -->
	
    <script>
 (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/account/verificationcenter" class="round-btn4 active ">'+langConvert('lang.menuAccountVerificationCenter','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/account/signedit" class="round-btn4 ">'+langConvert('lang.menuAccountSignEdit','')+'</a>');
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
        $( document ).ready(function() {
            $('#mb_id').html(mb_id);
            $('#mb_hp').html(mb_hp);
            $('#mb_name').html(mb_name);
            $('#mb_level').html(mb_level);
            $('#mb_level2').html(mb_level);
			
			// Level2-1 모바일 확인
            if(mb_hp.length==0){
				// 휴대폰 실명인증 전 
                $('#mb_hp_result_false').show();
				$('#mb_hp_result_true').hide();
				$('#mb_info_result_true').show();
				$('#mb_info_result_false').hide();
            }else{
                $('#mb_hp_result_true').show();
                $('#mb_hp_result_false').hide();
				$('.level-step .step2').addClass('active');
				$('#mb_info_result_true').hide();
            }
			
           
			
			if(mb_level>1){
				// 레벨 1이상일경우 
				$('.level-step .step3').addClass('active');
                $('#mb_info_result_true').html(langConvert('lang.viewAccountVerificationcenterVerified', ''));
                $('#mb_info_result_true').show().addClass('txt3');
				$('#mb_info_result_false').remove();
				$('#lv').addClass('lv2');
            }else{
                //$('#mb_info_result_false').hide();
				
            }
			
			
			// Level2-2  NICEID모바일인증 or 서류인증 버튼 비활성화 
            if(mb_hp.length==0){
                $('#btn_document').attr("disabled", true);
                $('#btn_namecheck').attr("disabled", true);
            }
			
			// OTP 사용여부
			if(mb_otp_use == "N"){
				$('#otp-bool a').text(langConvert('lang.enable', ''));
			} else {
				$('.level-step .step4').addClass('active');
				$('#otp-bool a').text(langConvert('lang.disable', ''));
			}
            mbCertificateResult();
            verification_content();
            depositWithdrawContent();
        });
        
        var mb_certificate = get_member.mb_certificate;
        var mb_id       = get_member.mb_id;
        var mb_hp       = get_member.mb_hp;
        var mb_level    = get_member.mb_level;
        var mb_name    = get_member.mb_name;
        var mb_otp_use    = get_member.mb_otp_use;
        
        if(typeof(get_member.mb_hp)==="undefined" || get_member.mb_hp==null || (get_member.mb_hp).length<8){
            mb_hp = '';
        }else{
            var mb_hp_start = ((parseInt(mb_hp.length/2)).toFixed(0))-2;
            mb_hp = mb_hp.replace(mb_hp.substring(mb_hp_start, mb_hp_start+4), '****');
        }

        if(document.URL.indexOf('return')!==-1){
            $('.comment').append('<div class="alert list-group-item-warning" style="display:none;"><span>'+langConvert('lang.msgPleaseUseAfterYouCompletedCertification', '')+'</span></div>');
            var options = {};
            $('.alert').show( 'slide',function() {
                setTimeout(function() {
                    $('.alert').hide('slow');
                },3000);
            });
        }

        // 회원 인증 상단 상태 표기
        var mbCertificateResult = function(){
            var mb_certificate = get_member.mb_certificate;
            if(typeof(get_member.mb_certificate)==="undefined"){
                mb_certificate = "";
            }
            var html = '';
            if(mb_certificate==='N'){
                html = langConvert('lang.msgNamecheckAuthenticationRequired','');
            }else if(mb_certificate==='Y'){
                html = langConvert('lang.msgNamecheckCompleteAuthentication','');
            }
            $('#mbCerificationStatus').html(html);
        };

        $("#btn_document").click(function () {
            onSubmit();
        });
        $('#btn_mobile').click(function(){
            Account.isLogined(function (islogin) {
                if (islogin) {
                    $(location).attr('href', "/account/verificationformmobile");
                } else {
                    controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                        $(location).attr('href', "/account/signin");
                    });
                }
            });
        });

        var onSubmit = function(){
            Account.isLogined(function (islogin) {
                if (islogin) {
                    $(location).attr('href', "/account/verificationform");
                } else {
                    controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                        $(location).attr('href', "/account/signin");
                    });
                }
            });
        };

        var verification_content = function(){
            var t       = Utils.getTimeStamp();
            var param   = "t-"+t+"/";
            var html    = '';
            var htmlBtn = '';
            $.getJSON("/webmemberlevelrequest/select/"+param, "json", function (data) {
            })
            .success(function(data) {
                if( typeof(data)!=='undefined'){
                    if( parseInt(data[0].result) > 0){
                        // 거부
                        if(data[0].admin_confirm=='R'){
                            var admin_memo = data[0].admin_memo;
                            if(admin_memo.length>0){
                                admin_memo = ' ('+data[0].admin_memo+')';
                            }
                            html = langConvert('lang.viewAccountVerificationcenterReject', '') + admin_memo;	// 거부
                        }else if(data[0].admin_confirm=='N'){
							// 서류인증 신청시 - 관리자페이지 확인중 ...  
                            html = langConvert('lang.viewAccountVerificationcenterPending', '');	// 확인중
                            $('button#btn_document').html( langConvert('lang.viewAccountVerificationcenterPending', '') );
                            $('#str_or').hide();
                            $('#btn_namecheck').remove();
                            $('#btn_document').attr("disabled", true);
                        }else if(data[0].admin_confirm=='Y'){
							// 서류인증 완료시
                            $('#str_or').hide();
                            $('#btn_namecheck').remove();
                            $('#btn_document').remove();
                            $('span#mb_info_result_true').css('display', 'block');
                            html = langConvert('lang.viewAccountVerificationcenterVerified', '')+' : '+data[0].admin_confirm_dt;
                        }
                    }else{
                        if(get_member.mb_level==2){
                            html = langConvert('lang.viewAccountVerificationcenterVerified', ''); // 확인
                        }
                    }
                }else{
                }
                $('#mb_info_result').html(html);
            });
        };

        var t       = Utils.getTimeStamp();
        // 입출금 한도 안내
        var depositWithdrawContent = function () {
            var t       = Utils.getTimeStamp();
            var param   = "t-"+t+"/";
            $.getJSON("/webconfigwalletlimit/selectdepositwithdraw/"+param, "", function (data) {
            })
                .success(function (data) {
                    if (typeof (data) !== 'undefined') {
                        if (parseInt(data[0].result) > 0) {
                            for (var i = 0; i < data.length; i++) {
                                console.log(data[i]);
                                if(data[i].cf_wallet_type == 'CASH'){
                                    $('.lv' + data[i].cf_mb_level + '-KRW').html( (data[i].cf_max_withdraw).formatWon() );
                                }else{
                                    var maxwithdraw = 0;
                                    if(parseFloat(data[i].cf_max_withdraw) == 0){
                                        maxwithdraw = (0).toFixed(8);
                                    }else{
                                        maxwithdraw = (data[i].cf_max_withdraw).formatBitcoin();
                                    }
                                    $('.lv' + data[i].cf_mb_level + '-' + data[i].cf_wallet_type).html( maxwithdraw + ' ' + data[i].cf_wallet_type );
                                }
                            }
                        }
                    }
                });
        };

        // 본인인증 관련
        function fnPopup(){
                    window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
                    document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
                    document.form_chk.target = "popupChk";
                    document.form_chk.submit();
            }

        window.name ="Parent_window";

        // 본인 인증하기
        $("#btn_namecheck").click(function(){
            Account.isLogined(function(islogin){
                if(islogin){
                    fnPopup();
                }else{
                    controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>',function(){
                        $(location).attr('href',"/account/signin");
                    });
                }
            });
        });

        var nameChecked = function(result, authdata){

            if(result===false){
                controllerComm.alertError('<p>'+langConvert('lang.msgItFailedToAuthenticate', '')+'</p><p>'+langConvert('lang.msgAnErrorHasOccurredPleaseTryAgain', '')+'</p>');
            }else{
                Account.isLogined(function(islogin){
                    if(islogin){
                        var data = {
                            mbname:authdata.name
                            ,mbbirth:authdata.birthdate
                            ,mbgender:authdata.gender
                            ,mbhp:authdata.mobileno
                        };
                        var pdata = jQuery.param(data);
                        $.post("/webmember/namecheck/"+pdata,
                            pdata,
                            function (json){
                                if( typeof(json.result!=='undefined') && parseInt(json.result.result)>0 ){
                                    controllerComm.alertError('<p>'+langConvert('lang.msgTheIdentityAuthenticationIsComplete', '')+'</p>',
                                        function(){
                                        $(location).attr('href',"/account/verificationcenter");
                                    });
                                }else{
                                    controllerComm.alertError('<p>'+langConvert('lang.msgItFailedToAuthenticate', '')+' '+langConvert('lang.msgAnErrorHasOccurredPleaseTryAgain', '')+'</p>',
                                    function(){
                                        $(location).attr('href',"/account/verificationcenter");
                                    });
                                }
                            }, "json")
                            .error(function (){
                            })
                            .fail(function (){
                            })
                            .always(function (){
                            });
                    }else{
                        controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>',function(){
                            $(location).attr('href',"/account/signin");
                        });
                    }
                });
            }
        };
    </script>
</body>

