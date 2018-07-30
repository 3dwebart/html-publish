<head>
    <link href="<?= $view['url']['static'] ?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static'] ?>/assets/css/custom_temp.css" rel="stylesheet">
    <link href="<?= $view['url']['static'] ?>/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= $view['url']['static'] ?>/assets/css/magnific-popup.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/utils.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/nations.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/sha256.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/controller-comm.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-form.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script> Config.initHeaderScript();</script>
    <style>
        #wrap.single #contents{ top: 45%;} 
        #container {
    min-height: 850px;
}
    #wrap.single .form-inner { border-radius: 10px; background: #fff; max-width: 390px; margin: 0 auto;  -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;}
    .inp {
        width: 100%;
        border: solid 1px #dedede;
        font-size: 13px;
        line-height: 1.2;
        padding: .8em 1em;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        -o-border-radius: 3px;
        border-radius: 3px;
        background-color: #f7f8f9;
        margin-bottom: 15px;
        color: #333;
        height: 33px;
    }
    .article{ display: block; }
    .addr-wrap1{  width: 66.66667%;position: relative;
    min-height: 1px;
      float: left;-webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box; }
    .addr-wrap2{  width: 33.33333%; float: left; position: relative;
    min-height: 1px;
   -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;}
    .btn-addr-find{
        background: #0082cd;
        color: #fff;
        border: 0;
        border-radius: 0 0 10px 10px;
        position: relative;
        top: 1px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        display: block;
        padding: 9px;
        margin: 0 0 0 5px;
        min-width: 98%;
    }
    h4{ font-size: 24px; cursor: pointer}
        .member .form-signup, #login { margin: 0}
 @media screen and (max-height: 575px){
     .g-recaptcha {transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;} 
    } 
 @media screen and (max-width: 411px){
     .g-recaptcha {transform:scale(1.02);-webkit-transform:scale(1.02);transform-origin:0 0;-webkit-transform-origin:0 0;} 
    } 

 @media screen and (max-width: 375px){
     .g-recaptcha {transform:scale(0.94);-webkit-transform:scale(0.94);transform-origin:0 0;-webkit-transform-origin:0 0;} 
    } 
@media screen and (max-width: 360px){
    .g-recaptcha {transform:scale(0.89);-webkit-transform:scale(0.89);transform-origin:0 0;-webkit-transform-origin:0 0;} 
    } 
        
@media screen and (max-width: 320px){
    .g-recaptcha {transform:scale(0.76);-webkit-transform:scale(0.76);transform-origin:0 0;-webkit-transform-origin:0 0;} 
    } 
    .hidden {
        overflow: hidden;
        display: none;
        visibility: hidden;
    }
    .agree-text {
        max-width: 600px;
        min-height: 100px;
        max-height: 800px;
        padding: 15px;
        overflow-y: auto;
        background-color: #ffffff;
        margin: 0 auto;
        position: relative;
    }
    </style>
</head>
<body class="sub-background">
    <div id="wrap" class="single">
        <!-- HEADER -->
        <script src="<?= $view['url']['static'] ?>/assets/write/header-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>		
        <!-- // HEADER -->
        <script>$('#snb ul li').eq(2).addClass('active');</script>

        <!-- CONTAINER -->	
        <div id="container">
            <div id="contents">
                <div class="member">
                    <div class="form-box">
                        <div class="form-section">
							<form class="form-horizontal form-signup" id="form" onsubmit="return false">
								<div style="padding:30px"> 
									<h4>회원가입</h4>  
									<div class="form-inner" style="position:relative;">
										<div class="article">
											<input type="text" class="inp" name="mb_id" id="mb_id" placeholder="<?php Language::langConvert($view['lang'], 'email'); ?>" maxlength="50" value="" autofocus />
											<p class="alert" id="signup-alert-mbid"></p>
										</div>
										<div class="article">
											<input type="password" class="inp" name="mb_pwd" id="mb_pwd" placeholder="<?php Language::langConvert($view['lang'], 'pwd'); ?>" maxlength="30" value="" onchange="PassWord_Check()">
											<p class="alert" id="signup-alert-mbpwd"></p>
										</div>
										<div class="article">
											<input type="password" class="inp" name="mb_pwd_re" id="mb_pwd_re" placeholder="<?php Language::langConvert($view['lang'], 'confirmPwd'); ?>" maxlength="30" value="" onchange="PassWord_Check2()">
											<p class="alert" id="signup-alert-mbpwd2"></p>
										</div>
										<div class="article">
											<input type="text" class="inp" name="mb_last_name" id="mb_last_name" placeholder="<?php Language::langConvert($view['lang'], 'lastName'); ?>" maxlength="10" value="">
											<p class="alert" id="signup-alert-mblastname"></p>
										</div>
										<div class="article">
											<input type="text" class="inp" name="mb_first_name" id="mb_first_name" placeholder="<?php Language::langConvert($view['lang'], 'firstName'); ?>" maxlength="10" value=""> 
											<p class="alert" id="signup-alert-mbname"></p>
										</div>
										<div class="article" style="display: -webkit-box;">
											<div class="addr-wrap1">
												<input type="text" class="inp" name="mb_zip_code" id="mb_zip_code" placeholder="우편번호" maxlength="10" value="" />
												<p class="alert" id="signup-alert-mbzipcode"></p>
											</div>
											<div class="addr-wrap2"><input type="button" class="btn-addr-find" value="주소찾기" onclick="execDaumPostcode();"></div>
										</div>
										<div class="article">
											<input type="text" class="inp" name="mb_address" id="mb_address" placeholder="주소" maxlength="10" value=""> 
											<p class="alert" id="signup-alert-mbaddress"></p>
										</div>
										<div class="article">
											<input type="text" class="inp" name="mb_detail_address" id="mb_detail_address" placeholder="상세주소 입력" maxlength="10" value=""> 
											<p class="alert" id="signup-alert-mbdetailaddress"></p>
										</div>
										
										<!--
										<div class="article">
											<dl>
												<dt><?php Language::langConvert($view['lang'], 'nation'); ?></dt>
												<dd>
													<div class="select-box"><select name="country_code" class="select"></select><i class="xi-angle-down"></i></div>
												</dd>
											</dl>
										</div>
										-->
										<div class="article">
											<input type="hidden" class="inp" name="mb_country_dial_code" id="mb_country_dial_code" placeholder="" value="" readonly />
											<div class="g-recaptcha mb10" data-sitekey="<?= $view['captcha_sitekey'] ?>"></div>
										</div>
										<div class="agree-box article" style="height:auto; padding:10px 0;">
											<dl style="height:auto; margin-bottom:10px; padding-bottom:10px;"> 
												<input type="checkbox" id="chk1" name="mb_agree" value="agree">
												<label for="chk1" class="check-box"></label>
												<label for="chk1">이용약관</label>
												<span class="agree-txt" style="margin-right:12px">
													<a href="#terms" class="under btn-agree"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
												</span> 
												<input type="checkbox" id="chk2" name="mb_agree2" value="agree">
												<label for="chk2" class="check-box"></label>
												<label for="chk2">개인정보취급방침</label>
												<span class="agree-txt">
													<a href="#privacy" class="under btn-agree"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
												</span>
												<div id="signup-alert-policy" ></div>
											</dl>
										</div>
										<button style="max-width:95%; margin:0 auto; font-size:14px; background: #007ac7;" class="btn btn-block" type="submit" id="btn_submit" ><?php Language::langConvert($view['lang'], 'btnSignUp'); ?></button>
									</div><!-- // form-inner -->
								</div>
							</form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER -->
        <script src="<?= $view['url']['static'] ?>/assets/write/footer-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    </div><!-- // wrap -->
    <div id="terms" class="agree-text mfp-hide">
    이용약관
    </div>
    <div id="privacy" class="agree-text mfp-hide">
    개인정보취급방침
    </div>
    <script>Config.initFooterScript();</script>

<!--	<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>-->
<!--	<script charset="UTF-8" type="text/javascript" src="http://t1.daumcdn.net/cssjs/postcode/1522037570977/180326.js"></script>-->
	<script src='https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js'></script>
	<script>
		//본 예제에서는 도로명 주소 표기 방식에 대한 법령에 따라, 내려오는 데이터를 조합하여 올바른 주소를 구성하는 방법을 설명합니다.
		function execDaumPostcode() {
			var width = 300;
			var height = 500;
			new daum.Postcode({
				width: width,
				height: height,
				oncomplete: function(data) {
					// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

					// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
					// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
					var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
					var extraRoadAddr = ''; // 도로명 조합형 주소 변수

					// 법정동명이 있을 경우 추가한다. (법정리는 제외)
					// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
					if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
						extraRoadAddr += data.bname;
					}
					// 건물명이 있고, 공동주택일 경우 추가한다.
					if(data.buildingName !== '' && data.apartment === 'Y'){
					   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
					if(extraRoadAddr !== ''){
						extraRoadAddr = ' (' + extraRoadAddr + ')';
					}
					// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
					if(fullRoadAddr !== ''){
						fullRoadAddr += extraRoadAddr;
					}

					// 우편번호와 주소 정보를 해당 필드에 넣는다.
					document.getElementById('mb_zip_code').value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById('mb_address').value = fullRoadAddr;
					document.getElementById('mb_detail_address').value = data.jibunAddress;

					// 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
					/*
					if(data.autoRoadAddress) {
						//예상되는 도로명 주소에 조합형 주소를 추가한다.
						var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
						document.getElementById('guide').innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';

					} else if(data.autoJibunAddress) {
						var expJibunAddr = data.autoJibunAddress;
						document.getElementById('guide').innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';

					} else {
						document.getElementById('guide').innerHTML = '';
					} */
				}
			}).open({left: (window.screen.width / 2) - (width / 2), top: (window.screen.height / 2) - (height / 2), popupName: '회원주소입력'});
		}
	</script>
    <script src="<?= $view['url']['static'] ?>/assets/js/jquery.magnific-popup.js"></script>
    <script>
    function PassWord_Check(){
        var re1 = /[a-zA-Z]/i; // 영문
        var re2 = /[0-9]/i; // 숫자
        var re3 = /[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?]/i; // 특수문자
        var alpaBig= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var alpaSmall= "abcdefghijklmnopqrstuvwxyz";
        var num = "01234567890";
        var Passwd_chk = {
            Password1:$("input[name='mb_pwd']"),
            Password2:$("input[name='mb_pwd_re']")
        };

        //영문,숫자,특수문자포함 입력체크
        var alert_mbpw = $('#signup-alert-mbpwd');
        
        if((!re1.test(Passwd_chk.Password1.val())) && (!re2.test(Passwd_chk.Password1.val())) && (!re3.test(Passwd_chk.Password1.val()))) {
            alert_mbpw.html('<p>'+langConvert('lang.msgThePasswordMustContainLettersAndNumbersAndSpecialCharacters', '')+'</p>');
            alert_mbpw.css("color","#d00000");
        }else if((!re2.test(Passwd_chk.Password1.val())) && (!re3.test(Passwd_chk.Password1.val()))){
            alert_mbpw.html('<p>'+langConvert('lang.msgThePasswordShouldIncludeNumbersAndSpecailCharacters', '')+'</p>');
            alert_mbpw.css("color","#d00000");
        }else if(!re2.test(Passwd_chk.Password1.val())){
            alert_mbpw.html('<p>'+langConvert('lang.msgThePasswordMustContainNumber', '')+'</p>');
            alert_mbpw.css("color","#d00000");
        }else if(!re3.test(Passwd_chk.Password1.val())){
            alert_mbpw.html('<p>'+langConvert('lang.msgThePasswordmustContainSpecailCharacters', '')+'</p>');
            alert_mbpw.css("color","#d00000");
        }else if(!re1.test(Passwd_chk.Password1.val())){
            alert_mbpw.html('<p>'+langConvert('lang.msgThePasswordMustContainInEnglish', '')+'</p>');
            alert_mbpw.css("color","#d00000");
        }else{
            //8자리 이상입력체크
            if(Passwd_chk.Password1.val().length < 8){
                alert_mbpw.html('<p>'+langConvert('lang.msgPleaseEnterLeast8Charaters', '')+'</p>');
                alert_mbpw.css("color","#d00000");
            }else{
                alert_mbpw.html('');
                alert_mbpw.hide();
            }
        }
    }
    
    function PassWord_Check2(){
        var Passwd_chk = {
            Password1:$("input[name='mb_pwd']"),
            Password2:$("input[name='mb_pwd_re']")
        };

        var alert_mbpw2 = $('#signup-alert-mbpwd2');
        
        if(Passwd_chk.Password1.val() != Passwd_chk.Password2.val()){
            alert_mbpw2.html('<p>'+langConvert('lang.msgPasswordsDoNotMatch', '')+'</p>');
            alert_mbpw2.show();
        }else{
            alert_mbpw2.html('');
            alert_mbpw2.hide();
        }
    }

    var accountPageSet = {
        pre_country_code:null,
        country_code:$("select[name=country_code]")
    };

    $('#nav-ticker').hide();

    $( document ).ready(function() {
        $('a.btn-agree').on('click', function(event) {
            event.preventDefault();

            var gallery = $(this).attr('href');
            console.log('ID = ' + gallery);

            $.magnificPopup.open({
                items: {
                    src: gallery,
                    type: 'inline'
                }
            });
            /*
            $(gallery).magnificPopup({
                delegate: 'a',
                type:'image',
                gallery: {
                    enabled: true
                }
            }).magnificPopup('open');
            */
        });
		// $('.view-terms').magnificPopup({
		// 	type: 'ajax'
		// });
		// $('.view-privacy').magnificPopup({
		// 	type: 'ajax'
		// });
		// $('.test').magnificPopup({
		// 	type: 'ajax'
		// }).magnificPopup('open');
		var language_code = Utils.getLanguage();
		var country_code = '';
		if(!language_code || language_code=='ko' || language_code=='kr') {
			country_code = 'KR';
		}else if(language_code=='zh') {
			country_code = 'CN';
		}
		country_code = country_code.toUpperCase();
        // 국가코드 값 삽입 및 기본값 선택
        for (var i=0;i<nations.length;i++){
            if( (nations[i].code+"")==country_code){
                $("<option></option>")
                .text(nations[i].nation)
                .attr("value", nations[i].code)
                .attr("selected", true)
                .attr("data-dial", nations[i].dial_code)
                .appendTo("select[name='country_code']");
                $('#mb_country_dial_code').val(nations[i].dial_code);
                accountPageSet.pre_country_code = nations[i].code;
            }else{
                $("<option></option>")
                .text(nations[i].nation)
                .attr("value", nations[i].code)
                .attr("data-dial", nations[i].dial_code)
                .appendTo("select[name='country_code']");
            }
        }
        changeNameInputLocation();
    });

    $('select[name=country_code]').change(function(){
        changeNameInputLocation();
    });

    // KR, CN, JP, KP 에만 성, 이름 위치 변경
    function changeNameInputLocation(){
        if((accountPageSet.pre_country_code=='KR' || accountPageSet.pre_country_code=='CN' || accountPageSet.pre_country_code=='JP' || accountPageSet.pre_country_code=='KP') &&
            accountPageSet.country_code.val()!='KR' && accountPageSet.country_code.val()!='CN' && accountPageSet.country_code.val()!='JP' && accountPageSet.country_code.val()!='KP'){
            var lastnamevalue = $("#mb_last_name").val();
            var firstnamevalue = $("#mb_first_name").val();
            var lastnamehtml = $("#divmblastname").html();
            var firstnamehtml = $("#divmbfirstname").html();
            $("#divmblastname").html(firstnamehtml);
            $("#divmbfirstname").html(lastnamehtml);
            $("#mb_last_name").val(lastnamevalue);
            $("#mb_first_name").val(firstnamevalue);
            accountPageSet.pre_country_code = accountPageSet.country_code.val();
        }
        if(accountPageSet.country_code.val()=='KR' || accountPageSet.country_code.val()=='CN' || accountPageSet.country_code.val()=='JP' || accountPageSet.country_code.val()=='KP'){
            var lastnamevalue = $("#mb_last_name").val();
            var firstnamevalue = $("#mb_first_name").val();
            var lastnamehtml = $("#divmblastname").html();
            var firstnamehtml = $("#divmbfirstname").html();
            $("#divmblastname").html(firstnamehtml);
            $("#divmbfirstname").html(lastnamehtml);
            $("#mb_last_name").val(lastnamevalue);
            $("#mb_first_name").val(firstnamevalue);
            accountPageSet.pre_country_code = accountPageSet.country_code.val();
        }
        $("input[name='mb_country_dial_code']").val($("select[name='country_code'] option:selected").data('dial'));
    }

    // 숫자만 입력
    $(".input-numberric").on("input keypress keyup change", $(this), function(){
        var input_value = $(this).val();
        input_value     = input_value.replace(/[^0-9]/g, "");   // numberric
        $(this).val(input_value);
    });

    function mbidCheck(){
        var result = false;
        var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;
        var mb_id = $('input[name="mb_id"]').val();

        if(!mb_id){
            alert_mbid.html('<p>'+langConvert('lang.msgPleaseInputEmailAddress', '')+'</p><br />');
            alert_mbid.show();
            result = false;
        }else if(mb_id.length < 5){
            alert_mbid.html('<p>'+langConvert('lang.msgIncorrectEmailAddress', '')+'</p><br />');
            alert_mbid.show();
            result = false;
        }else if(!strexp.test(mb_id)){
            alert_mbid.html('<p>'+langConvert('lang.msgIncorrectEmailAddress', '')+'</p><br />');
            alert_mbid.show();
            return false;
        }else{
            return true;
        }
    }

    //  이메일 포커스 아웃 - 중복 체크
    var alert_mbid = $('#signup-alert-mbid');
    $('input[name="mb_id"]').focusout(function() {
        var checked = mbidCheck();
        if(checked){
            var param = {   query: $.base64.encode($(this).val())    };
            var pdata = jQuery.param(param);
            $.post("/webmember/duplication/",
                pdata,
                function (json){
                    if( typeof(json.result!=='undefined') && parseInt(json.result[0].mb_count)>0 ){
                        alert_mbid.html('<p>'+langConvert('lang.msgEmailIsAlreadyInOutSystem', '')+'</p><p style="display:none">'+langConvert('lang.msgPleaseEnterDifferentEmail', '')+'</p>');
                        alert_mbid.show();
                        $('#btn_submit').attr("disabled", false);
                    }else{
                        alert_mbid.html('');
                        alert_mbid.hide();
                    }
                }, "json")
                .error(function (){
                })
                .fail(function (){
                })
                .always(function (){
                });
        }
    });

    function requestDuplicationMbid(){
        var mb_id = $('input[name="mb_id"]').val();
        var checked = mbidCheck();
        if(checked){
            var param = { query: $.base64.encode(mb_id) };
            var pdata = jQuery.param(param);

            $.post("/webmember/duplication/",
                pdata,
                function (json){
                    if( typeof(json.result!=='undefined') && parseInt(json.result[0].mb_count)>0 ){
                        controllerComm.alertError('<p>'+langConvert('lang.msgEmailIsAlreadyInOutSystem', '')+'</p><p style="display:none">'+langConvert('lang.msgPleaseEnterDifferentEmail', '')+'</p>', function(){   });
                    }else{
                        requestCertNumber();
                    }
                }, "json")
                .error(function (){
                })
                .fail(function (){
                })
                .always(function (){
                });
        }
    }

    if(document.URL.indexOf('return-')!==-1){
        $('.container.comment').append('<div class="alert list-group-item-warning" style="display:none;min-width:400px;line-height:10px;margin:0 auto;position:absolute;">'+langConvert('lang.msgPleaseLogin', '')+'</div>');

        var options = {};
        $('.alert').show( 'slide',function() {
            setTimeout(function() {
                $('.alert').hide('slow');
            },2000);
        });
    }

    controllerForm.setBeanData({
        result:0,
        link:{proc:"/webmember/duplication"}
    });

    controllerForm.duplication('#btn_submit','#form',function(json){
        /****************비밀번호체크************************/
        var re1 = /[a-zA-Z]/i; // 영문
        var re2 = /[0-9]/i; // 숫자
        var re3 = /[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?]/; // 특수문자
        var alpaBig= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var alpaSmall= "abcdefghijklmnopqrstuvwxyz";
        var num = "01234567890";

        var Passwd_chk = {
            Password1:$("input[name='mb_pwd']"),
            Password2:$("input[name='mb_pwd_re']")
        };
        //영문,숫자,특수문자포함 입력체크
        if((!re1.test(Passwd_chk.Password1.val())) && (!re2.test(Passwd_chk.Password1.val())) && (!re3.test(Passwd_chk.Password1.val()))) {
            controllerComm.alertError(langConvert('lang.msgThePasswordMustContainLettersAndNumbersAndSpecialCharacters', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }else if((!re2.test(Passwd_chk.Password1.val())) && (!re3.test(Passwd_chk.Password1.val()))){
            controllerComm.alertError(langConvert('lang.msgThePasswordShouldIncludeNumbersAndSpecailCharacters', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }else if(!re2.test(Passwd_chk.Password1.val())){
            controllerComm.alertError(langConvert('lang.msgThePasswordMustContainNumber', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }else if(!re3.test(Passwd_chk.Password1.val())){
            controllerComm.alertError(langConvert('lang.msgThePasswordmustContainSpecailCharacters', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }else if(!re1.test(Passwd_chk.Password1.val())){
            controllerComm.alertError(langConvert('lang.msgThePasswordMustContainInEnglish', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }else{
            //8자리 이상입력체크
            if(Passwd_chk.Password1.val().length < 8){
                controllerComm.alertError(langConvert('lang.msgPleaseEnterLeast8Charaters', ''));
                $('#btn_submit').attr("disabled", false);
                return false;
            }
        }
        /****************비밀번호체크 끝************************/
        
        var mblastname      = $('input[name=mb_last_name]').val();
        var mbfirstname     = $('input[name=mb_first_name]').val();
        
        if(!mblastname){
            controllerComm.alertError(langConvert('lang.msgPleaseInputYourName', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }else if(!mbfirstname){
            controllerComm.alertError(langConvert('lang.msgPleaseInputYourName', ''));
            $('#btn_submit').attr("disabled", false);
            return false;
        }
    
        if(typeof(json)=="object"){
            if(typeof(json.result!=='undefined') && parseInt(json.result[0].mb_count)>0){
                alert_mbid.html('<p>'+langConvert('lang.msgEmailIsAlreadyInOutSystem', '')+'</p><p>'+langConvert('lang.msgPleaseEnterDifferentEmail', '')+'</p>');
                alert_mbid.show();
                $('#btn_submit').attr("disabled", false);
                return false;
            }else{
                console.log(333);
                alert_mbid.html("");
                alert_mbid.hide();
                controllerForm.setBeanData({
                    result:0,
                    link:{proc:"/webmember/regist"}
                });
                controllerForm.setInitForm("memberjoin");
            }
        }else{
            controllerComm.alertError('<p>'+langConvert('lang.msgErrorOccurredDuringConnectionToTheServer', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>');
            return false;
        }
    });

    // 인증번호 요청 이메일, 휴대폰 번호 체크
    // 인증번호 요청 전 이메일 중복 다시 체크, 번호 유효 체크 - 경고는 controllerComm 호출
    $("#btn_cert_number").click(
        function(){
            var mb_id = '';
            if($("input[name='mb_id']").length > 0){
                mb_id = $('input[name="mb_id"]').val();
            }else if(typeof(get_member) == 'object'){
                mb_id = get_member.mb_id;
            }
            var strexp = /^[a-zA-z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9]{2,4}$/i;

            if( !mb_id ){
                controllerComm.alertError('<p>'+langConvert('lang.msgPleaseInputEmailAddress', '')+'</p>');
                return false;
            }else if( mb_id.length < 5 ){
                controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectEmailAddress', '')+'</p>');
                return false;
            }else if ( !strexp.test(mb_id) ){
                controllerComm.alertError('<p>'+langConvert('lang.msgIncorrectEmailAddress', '')+'</p>');
                return false;
            }else{
                requestDuplicationMbid();
            }
        }
    );
    </script>
</body>