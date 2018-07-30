<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    	<link href="<?= $view['url']['static'] ?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/index.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom_temp.css" rel="stylesheet">
<!--    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script> Config.initHeaderScript();</script>
</head>
    <style>
        #contents{ margin-top: 60px; padding: 30px; max-width: 450px; }     
        #container { min-height: 750px;}
        h3 {
    font-size: 16px;
    color: #000;
    /* padding-left: 16px; */
    position: relative;
    margin-bottom: 20px;
    font-weight: 400;
    text-align: left;
    padding: 0;
}
h4 {
    font-size: 16px;
    margin-bottom: 10px;
    font-weight: 500;
    color: #0390f3;
    padding-left: 20px;
    text-align: left;
}
    </style>
<body class="sub-background">
    <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
     <div id="container">
    <div class="contents">
       
            <div class="container comment" style="margin:50px auto; max-width:550px;padding:50px;border: 1px solid #ccc;background: #fff; -webkit-box-shadow: 0px 0px 26px 0px rgba(212,212,212,1);
    -moz-box-shadow: 0px 0px 26px 0px rgba(212,212,212,1);
    box-shadow: 0px 0px 26px 0px rgba(212,212,212,1);">
                  <div class="media-left">
                  </div>
                  <div class="media-body" style="text-align: center;">
                      <h3 class="media-heading"><i class="glyphicon glyphicon-ok" style="color:#009e25;margin:0 10px;"></i> <?php Language::langConvert($view['lang'], 'result');?></h3>
                    <br />
                    <h4 id="result-display"><i class="glyphicon glyphicon-time"></i> <?php Language::langConvert($view['lang'], 'guideProgress');?></h4 >
                    <br>
                    <button style="margin:0 auto;width:95%;font-size:14px;display:block;background: #007ac7;" class="btn-type1" onclick="window.location.href='/'">홈으로</button>
                  </div>
            </div> <!-- /container -->
        </div>
    </div>
    <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initFooterScript();</script>
    <script>
        var param = document.URL;
        var cut = param.indexOf('/returnemail/confirm/');
        var pdata = param.slice(cut+21);

        controllerForm.setBeanData({
            result:0,
            link:{proc:"/email/registconfirm/"+pdata}
        });

        var resulthtml = '';
        $.getJSON(bean.link.proc,
            "",
            function(json){
                if(typeof(json)==='undefined'){
                    $('#result-display').html('<p>'+langConvert('lang.msgThereWasNoResponseFromTheServerValue', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>');
                    return false;
                }
                // 1 true
                // 5101 - 올바른 경로로 접근하세요
                // 5102 - 이미 인증된 회원입니다.
                // 5103 - 유효시간이 지났습니다. 재인증 요청을 진행하세요.
                if(json.result===1){
                    resulthtml = '<p>'+langConvert('lang.msgEmailAuthenticationisNowCompleteThankYou', '')+'</p>';
                    resulthtml = resulthtml + '<br /><p><button type="button" class="btn btn-success btn-lg" onclick="location.href=\'/\'">'+langConvert('lang.msgGoToTheHome', '')+'</button></p>';
                    $('#result-display').html(resulthtml);
                }else if(json.result===-5101 || json.result===-5102){
                    resulthtml = '<p>'+json.error+'</p>';
                    resulthtml = resulthtml + '<br /><p><button type="button" class="btn btn-success btn-lg" onclick="location.href=\'/\'">'+langConvert('lang.msgGoToTheHome', '')+'</button></p>';
                    $('#result-display').html(resulthtml);
                }else if(json.result===-5103){
                    resulthtml = '<p>'+json.error+'</p>';
                    resulthtml = resulthtml + '<br /><p><button type="button" class="btn btn-success btn-lg" onclick="location.href=\'/account/signrequest\'">'+langConvert('lang.msgCertifiedEmailRetransmission', '')+'</button></p>';
                    $('#result-display').html(resulthtml);
                }else{
                    var addmsg = '';
                    if(json.autherror){
                        addmsg = '<br />'+json.autherror;
                    }
                    resulthtml = '<p>'+json.error + addmsg+'</p>';
                    $('#result-display').html(resulthtml);
                }
            }, "json"
        )
        .fail(function() { controllerComm.alertError('<p>'+langConvert('lang.msgErrorOccurredDuringConnectionToTheServer', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>',reload); })
        .always(function() { controllerComm.nprogressDone();});

        controllerComm.nprogressDone();

    </script>
</body>
