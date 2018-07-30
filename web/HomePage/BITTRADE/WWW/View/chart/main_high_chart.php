<head>
    <link type="text/css" href="<?= $view['url']['static']?>/assets/css/font-awesome.min.css" rel="stylesheet" >
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script type="text/javascript" src="../../charting_library/charting_library.min.js"></script>
    <script type="text/javascript" src="../../charting_library/datafeeds/udf/dist/polyfills.js"></script>
    <script type="text/javascript" src="../../charting_library/datafeeds/udf/dist/bundle.js"></script>
    <script>Config.initHeaderScript();</script>
</head>

<body>
    <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    
    <div id="contents" style="padding:20px 0;">
        <div id="tv_chart_container"></div>
    </div>
    
    <script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initFooterScript();</script>
</body>