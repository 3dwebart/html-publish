<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
         	<link href="<?= $view['url']['static'] ?>/assets/css/common.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/index.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script> Config.initHeaderScript();</script>
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
</head>

<body class="sub-background">
   <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <div id="container comment" style="margin:50px auto; max-width:550px;padding:50px;  min-height: 750px">
   <div id="contents">
       
            <div class="media-body" style="text-align: center;">
                <h3 class="media-heading"><i class="glyphicon glyphicon-ok" style="color:#009e25;margin:0 10px;"></i> <?php Language::langConvert($view['lang'], 'result');?></h3>
              <br />
              <h4 id="result-display"><i class="glyphicon glyphicon-time"></i> <?php Language::langConvert($view['lang'], 'guideProgress');?></h4 >
               <br>
                    <button style="margin:0 auto;width:95%;font-size:14px;display:block;background: #007ac7;" class="btn-type1" onclick="window.location.href='/'">홈으로</button>
            </div>
        </div>
    </div>
   
    <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initFooterScript();</script>
    <script>
        var param   = document.URL;
        var cut     = param.indexOf('/returnemail/confirmwithdraw/');
        var pdata   = param.slice(cut+29);
        var type    = param.indexOf('po_type-krw');
        var ss_token   = '<?=$view['token']?>';

        if(type > 0){
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/webcashwithdraw/requestconfirm/"+pdata}
            });
        }else{
            controllerForm.setBeanData({
                result:0,
                link:{proc:"/getwithdrawals/requestconfirm/"+pdata}
            });
        }
        //로그인 체크부터 시작
        Account.isLogined(function(islogin){
            if(islogin){
                $.getJSON(bean.link.proc,
                    "",
                    function(json){
                        if(typeof(json)==='undefined'){
                            $('#result-display').html('<p>'+langConvert('lang.msgThereWasNoResponseFromTheServerValue', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>');
                            return false;
                        }
                        // 1 true
                        // 6101, 6102, 6203 데이터 없음
                        // 6204 인증키 체크
                        // 6205 유효시간 체크
                        // 6206 request_order 인증 업데이트 오류
                        // 6207 인증키 use_yn 업데이트 오류
                        if(json.result>=1){
                            resulthtml = '<p>'+langConvert('lang.msgWithdrawalRequestHasBeenCompletedAuthenticationThankYou', '')+'</p>';
                            resulthtml = resulthtml + '<br /><p><button type="button" class="btn btn-success btn-lg" onclick="location.href=\'/\'">'+langConvert('lang.msgGoToTheHome', '')+'</button></p>';
                            $('#result-display').html(resulthtml);
                        }else if(json.result==-6101 || json.result==-6102 || json.result==-6203){
                            resulthtml = '<p>'+json.error+'</p>';
                            resulthtml = resulthtml + '<br /><p><button type="button" class="btn btn-success btn-lg" onclick="location.href=\'/\'">'+langConvert('lang.msgGoToTheHome', '')+'</button></p>';
                            $('#result-display').html(resulthtml);
                        }else if(json.result==-6204 || json.result==-6205 || json.result==-6206 || json.result==-6207){
                            resulthtml = '<p>'+json.error+'</p>';
                            resulthtml = resulthtml + '<br /><p><button type="button" class="btn btn-success btn-lg" onclick="location.href=\'/\'">'+langConvert('lang.msgGoToTheHome', '')+'</button></p>';
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
                .fail(function() {
                        controllerComm.alertError('<p>'+langConvert('lang.msgErrorOccurredDuringConnectionToTheServer', '')+'</p><p>'+langConvert('lang.msgTryAgainLater', '')+'</p>');
                })
                .always(function() { controllerComm.nprogressDone();});
            }else{
                controllerComm.alertError('<p>'+langConvert('lang.msgPleaseLogin', '')+'</p>',function(){
                    $(location).attr('href',"/account/signin");
                });
            }
        });

        controllerComm.nprogressDone();

    </script>
</body>
