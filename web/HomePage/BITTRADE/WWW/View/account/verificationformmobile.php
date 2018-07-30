<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/nations.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
   <style>
        @media (max-width: 768px){
            #snb{ display: none}
            #container .body-title{ height:0; border-bottom: 1px solid #ccc }
            #wrap.single #container.flex-box #contents{ padding: 0px;     border-radius: 10px;  background: #fff;  margin: 20px 20px;  padding: 0;
            box-shadow: rgba(33, 102, 212, 0.15) 0px 7px 30px 0px;  box-sizing: border-box;
                -webkit-box-shadow: rgba(33, 102, 212, 0.15) 0px 7px 30px 0px;
                -moz-box-shadow: rgba(33, 102, 212, 0.15) 0px 7px 30px 0px;
            }
            
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
		
		<script>$('#snb ul li').eq(0).addClass('active');
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
		<div id="container" class="flex-box flex-column flex-center">
			<div class="body-title">
				<div class="inner">
					<p class="tit"><?=$view['title']?></p>
				</div>
			</div>
			<div id="contents">
				 <div class="member"> 
					<div class="comment"></div>
					<div class="form-box">
						<div class="form-section">
						<form class="form-inner" id="form"  method="POST"> 

								<div class="article">
									<dl>
										<dt><?php Language::langConvert($view['lang'], 'nation');?></dt>
										<dd>
											<div class="select-box">
												<select name="country_code" class="select"></select>
												<i class="xi-angle-down"></i>
											</div>
										</dd>
									</dl>
								</div>

								<div class="article">
									<dl>
										<dt><?php Language::langConvert($view['lang'], 'cellularPhoneNumber');?></dt>
										<dd>
											<div id="divmbcountrydialcode"> 
											<input type="text" class="inp" name="mb_country_dial_code" id="mb_country_dial_code" placeholder="" value="" readonly>
											</div>
											<div id="divmbhp">
												<input type="number" class="inp input-numberric" name="mb_hp" id="mb_hp" style="width:192px" placeholder="<?php Language::langConvert($view['lang'], 'cellularPhoneNumber');?>" maxlength="20" value="" autofocus>
											</div>
											<div class="alert" id="verification-alert-mbhp"></div>
										</dd>
									</dl>
								</div>


								<div class="article">
									<dl>
										<dt><?php Language::langConvert($view['lang'], 'authenticationNumber');?></dt>
										<dd>
											<div>
												<input type="number" name="mb_cert_number" id="mb_cert_number" class="inp input-numberric" placeholder="<?php Language::langConvert($view['lang'], 'authenticationNumber');?>" style="width:100px; border-radius:4px;" maxlength="6">                                   
												<button style="font-size:13px; height:auto " type="button" class="btn btn-l btn-danger"  name="btn_cert_number" id="btn_cert_number"><?php Language::langConvert($view['lang'], 'requestAuthorizationNumber');?></button>
											</div>
											<div class="alert" id="verification-alert-mbcertnumber"></div>
										</dd>
									</dl>
								</div>
								<div class="article">
									<button  style="margin: 10px auto 0 auto; width:95%; font-size:14px; background:#007ac7; height:auto" type="button" id="btn_submit" class="btn btn-block btn-l btn-primary"><?php Language::langConvert($view['lang'], 'btnSubmit');?></button>
								</div>

						</form>
						</div>
					</div><!-- end main -->
				</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
	
    <script>
        (function ($) {$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/account/verificationcenter" class="round-btn4 active ">'+langConvert('lang.menuAccountVerificationCenter','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/account/signedit" class="round-btn4 ">'+langConvert('lang.menuAccountSignEdit','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/account/connectioninfo" class="round-btn4">'+langConvert('lang.menuAccountConnectionInfo','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/account/otp" class="round-btn4">'+langConvert('lang.menuAccountOtp','')+'</a>');
			
	})(jQuery);
        Config.initFooterScript();</script>
    
    <script src="<?=$view['url']['static']?>/assets/script/controller-vertification.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>
        $( document ).ready(function() {
            // 국가코드 값 삽입 및 기본값 선택
            for (var i=0;i<nations.length;i++){
                var country_code = Utils.getLanguage();
                country_code = country_code.toUpperCase();
                if(country_code=='KO' || country_code=='KR'){
                    country_code = 'KR';
                }else if(country_code=='ZH'){
                    country_code = 'CN';
                }

                if( (nations[i].code+"")===country_code){
                    $("<option></option>")
                    .text(nations[i].nation)
                    .attr("value", nations[i].code)
                    .attr("selected", true)
                    .attr("data-dial", nations[i].dial_code)
                    .appendTo("select[name='country_code']");
                    $('#mb_country_dial_code').val(nations[i].dial_code);
                }else{
                    $("<option></option>")
                    .text(nations[i].nation)
                    .attr("value", nations[i].code)
                    .attr("data-dial", nations[i].dial_code)
                    .appendTo("select[name='country_code']");
                }
            }
        });

        // 국가 변경 이벤트
        $('select[name=country_code]').change(function(){
            $("input[name='mb_country_dial_code']").val($("select[name='country_code'] option:selected").data('dial'));
        });

        // 숫자만 입력
        $(".input-numberric").on("input keypress keyup change", $(this), function(){
            var input_value = $(this).val();
            input_value     = input_value.replace(/[^0-9]/g, "");   // numberric
            $(this).val(input_value);
        });

        // 번호 유효 체크 - 경고는 controllerComm 호출
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
                    requestCertNumber();
                }
            }
        );

        $('#btn_submit').click(function(){
            var mb_hp = $('input[name="mb_hp"]').val();
            var cert_number = $('input[name="mb_cert_number"]').val();
            $('#verification-alert-mbhp').html('').hide();
            $('#verification-alert-mbcertnumber').html('').hide();

            if( mb_hp.length<9 ){
                var msg = langConvert('lang.msgIncorrectMobileNumber', '');
                controllerComm.alertError('<p>'+msg+'</p>');
                $('#verification-alert-mbhp').html('<p>'+msg+'</p>').show();
                return false;
            }else if(cert_number.length!=6){
                var msg = langConvert('lang.msgPleaseEnterTheCorrectVerificationNumber', '');
                controllerComm.alertError('<p>'+msg+'</p>');
                $('#verification-alert-mbcertnumber').html('<p>'+msg+'</p>').show();
                return false;
            }

            Account.isLogined(function (islogin) {
                if (islogin) {
                    controllerForm.setBeanData({
                        result:0,
                        link:{proc:"/webmembersmscertification/regist"}
                    });
                    controllerForm.setInitForm("actionverificationformmobile");
                } else {
                    controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>', function () {
                        $(location).attr('href', "/account/signin");
                    });
                }
            });
        });
    </script>
</body>

