<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
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

<style>
    html {
        height: 100%;
    }
    .message {
        position: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        top: 0;
        background-color: rgba(0, 0, 0, .3);
    }
    .message div.boxing {
        display: inline-block;
        padding: 50px;
        text-align: center;
        border: 1px solid #dedede;
        border-radius: 20px;
        box-shadow: 10px 10px 25px 0.01px #aaaaaa;
        background-color: #ffffff;
    }
    .message div.boxing span.text {
        display: block;
        text-align: left;
    }
    .message div.boxing .btns {
        display: block;
        text-align: center;
        margin-top: 30px;
    }
    .message div.boxing .btns a {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 5px;
        color: #fff;
    }
    .message div.boxing .btns a.ok {
        background-color: #004fa9;
    }
    .message div.boxing .btns a.no {
        background-color: #9c0000;
    }
</style>

<body class="sub-background">
    <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    
    <div id="contents" class="err">
        <div id="container" class="w840">
            <h2><?//=Language::langConvert($view['langcommon'], 'error404');?></h2>
            <h2>페이지를 찾을 수 없습니다.</h2>
            <br />
            <p><?=Language::langConvert($view['langcommon'], 'error404ex');?></p>
            <div  class=" mb150" id="content-main">
            </div>
        </div>
    </div>
    <div class="message">
        <div class="boxing">
            <span>
                세션이 종료 되었거나 현재 URL은 없는 페이지 입니다.<br />
                메인화면으로 돌아가시겠습니까?
            </span>
            <div class="btns">
                <a href="#" class="ok">예</a>
                <a href="#" class="no">아니오</a>
            </div>
        </div>
    </div>
    <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initFooterScript();</script>
    <script>
        (function($) {
            $('.btns a').on('click', function(e) {
                if($(this).hasClass('ok') == true) {
                    location.replace(tmpProtocol + '//' + tmpHost);
                }
                if($(this).hasClass('no') == true) {
                    $('.message').remove();
                }
                e.preventDefault();
            });
        })(jQuery);
    </script>
</body>
