<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>
</head>

<body class="sub-background">
    <style>
        .verti-box { margin-bottom:50px; }
        .verti-box .col-left { width:47%; }
        .verti-box .col-right { width:47%; }
        .verti-box .img img { max-height:380px; width:auto; }
        .verti-box .img { margin-bottom:20px;  }
        .verti-box .txt { font-size:15px; line-height:1.4em; color:#888; margin-bottom:20px; }
        .verti-box .txt b { font-size:20px; font-weight:600; display:block; margin-bottom:-10px; color:#000;}
        .verti-box label { font-size:15px; color:#006699; font-weight:600; margin-bottom:10px; display:block; }
        .verti-box .alert-msg { padding-top:10px; font-size:15px; }
        @media all and (max-width:768px) {
             .verti-box .col-left { width:100%; }
             .verti-box .col-right { width:100%; }
        }
    </style>
    
    
	
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
		
		<!-- CONTAINER -->
		<div id="container" class="flex-box flex-column flex-center">
			<div class="body-title">
				<div class="inner">
					<p class="tit"><?=$view['title']?></p>
				</div>
			</div>
			<div id="contents" style="padding:30px;">
				 <form class="form-horizontal form-vericationform" id="form" enctype="multipart/form-data" method="POST">
					<input name="mb_req_level" id="mb_req_level" type="hidden" class="form-control" placeholder="" readonly="true" value="">
					<input name="mb_prove_method" id="mb_prove_method" type="hidden" value="Attach">
						<div class="verti-box col-box">
							<div class="col-left">
								<p class="img"><img src="<?=$view['url']['static']?>/assets/img/sample-passport.png" style="width:100%;"></p>
								<div class="txt">
                                                                    <strong>1.본인 신분증 사본</strong> <br>
                                                                    다음과 같이 메모를  기재하여 주세요.<br>
                                                                        1.인증목적: 레벨인증 혹은 출금차단해제<br>
                                                                        2.이메일: 회원가입시 입력한 이메일주소<br>
                                                                        3.요청일자: 메모작성일 (2018년 4월 1일)<br><br>

                                                                        메모지를 부착한 신분증 또는 여권을 사진촬영이나 스캔합니다.<br>
                                                                        메모가 없는 사본은 인정되지 않습니다.<br>
                                                                       * 받은 사진은 실명확인 용도로만 사용됩니다.<br>
                                                                       * 신분증에 부착된 메모는 반드시"자필:로 기재해야 합니다 <br>
								</div>
								<div>
									<label for="mb_prove_file1">신분증(주민등록증,운전면허증,여권)</label>
									<div>
										<input name="mb_prove_file1" id="mb_prove_file1" type="file" placeholder="" value="" onchange="fileUploadCheck()">
									</div>
									<div class="alert-msg" id="verification-alert-mbprovefile1"></div>
								</div>
							</div>
							<div class="col-right">
								<p class="img"><img src="<?=$view['url']['static']?>/assets/img/sample-residency.png" style="width:100%;"></p>
								<div class="txt">
									<strong><?php Language::langConvert($view['lang'], 'prootOfResidencyFileDescTitle');?></strong><br />
									<?php Language::langConvert($view['lang'], 'prootOfResidencyFileDesc1');?><br />
									<?php Language::langConvert($view['lang'], 'prootOfResidencyFileDesc2');?><br />
									<?php Language::langConvert($view['lang'], 'prootOfResidencyFileDesc3');?><br />
									<?php Language::langConvert($view['lang'], 'prootOfResidencyFileDesc4');?><br />
									<?php Language::langConvert($view['lang'], 'prootOfResidencyFileDesc5');?><br />
								</div>
								<div>
									<label for="mb_prove_file2"><?php Language::langConvert($view['lang'], 'prootOfResidencyFile');?></label>
									<div>
										<input name="mb_prove_file2" id="mb_prove_file2" type="file" placeholder="" value="" onchange="fileUploadCheck()">
									</div>
								</div>
								<div class="alert-msg" id="verification-alert-mbprovefile2"></div>
							</div>
						</div>

						<div class="text-center" style="clear:both">
						<button type="button" id="btn_submit" class="btn btn-primary btn-l" style="width:180px"><?php Language::langConvert($view['lang'], 'btnSubmit');?></button>    
						</div>
				  </form>
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
    <script>Config.initFooterScript();</script>
    <script>

    $( document ).ready(function() {
        $('input[name="mb_cur_level"]').val(get_member.mb_level);
        $('input[name="mb_req_level"]').val(parseInt(get_member.mb_level)+1);
    });

    function fileUploadCheck(){
        var filesizelimit = <?=$view['filesizelimit']?>;
        var filesizelimitMb = (filesizelimit/1024)/1024;
        // file size byte
        $('#verification-alert-mbprovefile1').html('');
        $('#verification-alert-mbprovefile2').html('');
        var file1 = $('input[name="mb_prove_file1"]').val();
        var file2 = $('input[name="mb_prove_file2"]').val();

        if(file1==''){
            $('#verification-alert-mbprovefile1').html('<p>'+langConvert('lang.msgPleaseAttachTheFile', '')+'</p><br />');
            $('#verification-alert-mbprovefile1').css("color", "#d00000");
            return false;
        }

        var filename1 = file1.slice(file1.indexOf(".") + 1).toLowerCase();
        var filesize1 = $('input[name="mb_prove_file1"]')[0].files[0].size;
        if(filename1 != "jpg" && filename1 != "jpeg" && filename1 != "png" && filename1 != "bmp" && filename1 != "gif" && filename1 != "bmp"){
            controllerComm.alertError('<p>'+langConvert('lang.msgImageAndWordFileAttachementOnly', '')+'</p>');
            $('#verification-alert-mbprovefile1').html('<p>'+langConvert('lang.msgImageAndWordFileAttachementOnly', '')+'</p><br />');
            $('#verification-alert-mbprovefile1').css("color", "#d00000");
            return false;
        }else if(filesize1 > filesizelimit){
            controllerComm.alertError('<p>'+langConvert('lang.msgAttachmentSizeCanBeLessThanxxMb', [(filesizelimitMb+'')])+'</p>');
            $('#verification-alert-mbprovefile1').html('<p>'+langConvert('lang.msgAttachmentSizeCanBeLessThanxxMb', [(filesizelimitMb+'')])+'</p><br />');
            $('#verification-alert-mbprovefile1').css("color", "#d00000");
            return false;
        }


        if(file2==''){
            $('#verification-alert-mbprovefile2').html('<p>'+langConvert('lang.msgPleaseAttachTheFile', '')+'</p><br />');
            $('#verification-alert-mbprovefile2').css("color", "#d00000");
            return false;
        }

        var filename2 = file2.slice(file2.indexOf(".") + 1).toLowerCase();
        var filesize2 = $('input[name="mb_prove_file2"]')[0].files[0].size;
        if(filename2 != "jpg" && filename2 != "jpeg" && filename2 != "png" && filename2 != "bmp" && filename2 != "gif" && filename2 != "bmp"){
            controllerComm.alertError('<p>'+langConvert('lang.msgImageAndWordFileAttachementOnly', '')+'</p>');
            $('#verification-alert-mbprovefile2').html('<p>'+langConvert('lang.msgImageAndWordFileAttachementOnly', '')+'</p><br />');
            $('#verification-alert-mbprovefile2').css("color", "#d00000");
            return false;
        }else if(filesize2 > filesizelimit){
            controllerComm.alertError('<p>'+langConvert('lang.msgAttachmentSizeCanBeLessThanxxMb', [(filesizelimitMb+'')])+'</p>');
            $('#verification-alert-mbprovefile2').html('<p>'+langConvert('lang.msgAttachmentSizeCanBeLessThanxxMb', [(filesizelimitMb+'')])+'</p><br />');
            $('#verification-alert-mbprovefile2').css("color", "#d00000");
            return false;
        }
        return true;
    }


    $('#btn_submit').click(function(){
        var fileUploadCheckResult = fileUploadCheck();
        if(!fileUploadCheckResult){
            return false;
        }

        controllerForm.setBeanData({
            result:0,
            link:{proc:"/webmemberlevelrequest/regist"}
        });
        controllerForm.setInitForm("actionverificationform");
    });



    </script>
</body>

