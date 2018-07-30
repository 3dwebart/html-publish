<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
     	<link href="<?= $view['url']['static'] ?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/index.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/sha256.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script> Config.initHeaderScript();</script>
    <style>
        #contents{ margin-top: 60px; padding: 30px; max-width: 450px; }     
        #container { min-height: 750px;}
    </style>
</head>

<body class="sub-background">
    <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <div id="container">
    <div id="contents">
        <div id="login">
            <h2 id="confirm-display"><?php Language::langConvert($view['lang'], 'pleaseChangePwd');?></h2>
            <h4 id="confirm-noty"></h4>
            <form class="form-confirm" id="form" onsubmit="return false">
                <p><input type="password" name="mb_pwd" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'pwd');?>"></p>
                <p><input type="password" name="mb_pwd_re" class="inp" placeholder="<?php Language::langConvert($view['lang'], 'pleaseChangePwd');?>"></p>
                <button class="btn btn-l btn-primary btn-block" type="submit" id="btn_submit"><?php Language::langConvert($view['lang'], 'btnPwdEdit');?></button>
            </form>
        </div>
    </div>
        </div>
    
    
    <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initFooterScript();</script>
    <script>

        $("#btn_submit").click(function(){
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
			}else{

			}

		}
		/****************비밀번호체크 끝************************/
                
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/email/pwdconfirm/"}
            });
            controllerForm.setInitForm("memberrequestpwdprove");
        });

        controllerComm.nprogressDone();

        controllerForm.setOnComplet=function(json){
            if(typeof json!=='undefined'){
                if(json.result==1){
                    controllerComm.alertError('<p>'+langConvert('lang.msgYourPasswordHasBeenChanged', '')+'</p><p>'+langConvert('lang.msgUsedThanks', '')+'</p>', function(){
                        $(location).attr('href',"/account/signin");
                    });
                }else{
                    var msg = (typeof json.error!=='undefined')?json.error:langConvert('lang.msgPasswordChangeFailed','')+' '+langConvert('lang.msgTryAgainLater','');
                    if(json.result==-5104 || json.result==-5105 || json.result==-5106 || json.result==-5107){
                        controllerComm.alertError('<p>'+msg+'</p>', function(){
                            $(location).attr('href',"/account/signrequestpwd");
                        });
                    }else{
                        controllerComm.alertError('<p>'+msg+'</p>', function(){
                            document.location.reload();
                        });
                    }
                }
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgPasswordChangeFailed', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>', function(){
                    document.location.reload();
                });
            }
        };
    </script>
</body>
