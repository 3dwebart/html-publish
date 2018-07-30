<head>
    <link rel="stylesheet" type="text/css" href="<?= $view['url']['static']?>/desktop/css/style.css?v=<?=$view['ver']?>">
    <link rel="stylesheet" type="text/css" href="<?= $view['url']['static']?>/desktop/css/font-awesome.min.css">
    <script type="text/javascript" src="<?= $view['url']['static']?>/desktop/js/front.js"></script>

    <script src="<?=$view['url']['static']?>/script/dist/bootstrap.min.js"></script>
    <script src="<?=$view['url']['static']?>/script/dist/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/script/com/sha256.min.js"></script>
    
    <script src="<?=$view['url']['static']?>/script/com/utils.min.js?v=<?=$view['ver']?>"></script>
    <script src="<?=$view['url']['static']?>/script/page/min/controller-comm.js?v=<?=$view['ver']?>"></script>
    <script src="<?=$view['url']['static']?>/script/page/min/controller-form.js?v=<?=$view['ver']?>"></script>
    <script> Config.initHeaderScript();</script>
    <script>
        var pagedata = $.parseJSON($.base64.decode('<?=$view['jsondata']?>'));
    </script>
</head>



<body>
<div id="wrap">
	<!-- header -->
	<script src="<?=$view['url']['static']?>/desktop/write/sub-header.js?v=<?=$view['ver']?>"></script>
	<!-- // header -->
	
	<!-- main -->
    <!-- Container -->
	<div id="container" class="w840">
		<h2>PRIVACY POLICY</h2>
        <br />
        <div  class=" mb150" id="content-main">
            <div >Loading..</div>
        </div>
	</div>
	<!-- // Container -->
    
    <!-- footer -->
    <script src="<?=$view['url']['static']?>/desktop/write/main-footer.js?v=<?=$view['ver']?>"></script>
	<!-- // footer -->
</div>
<script>
    $('#nav-ticker').hide();

    var language = Utils.getLanguage();
    if(typeof pagedata !=='undefined' && typeof pagedata[0]!=='undefined'){
        if(language=='zh'){
            $('#content-main').html(pagedata[0].pm_content_cn.replace(/\\\"/gi,'"'));
        }else if(language=='en'){
            $('#content-main').html(pagedata[0].pm_content.replace(/\\\"/gi,'"'));
        }else{
            $('#content-main').html(pagedata[0].pm_content_kr.replace(/\\\"/gi,'"'));
        }
    }else{
        $('#content-main').html(langConvert('lang.msgThereIsNoInformation', ''));
    }

    Config.initFooterScript();
</script>
</body>