<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/index.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
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
		<script src="<?=$view['url']['static']?>/assets/write/snb-cs.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<!-- // GNB -->
		
	
        <script>$('#snb ul li').eq(0).addClass('active');
                $('#gnb li a').eq(4).addClass('active');
            
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
				<div class="notice-head">
					<ul>
						<li>번호</li>
						<li>제목</li>
						<li>날짜</li>
					</ul>
				</div>
				<!--
				<div class="notice-list">
					<div class="section">
						<p class="num">12</p>
						<p class="subj">안녕하세요 현재 베타 테스트중입니다.  곧 오픈예정입니다. 회원가입및 결제는 진행되지 않는점 유의 바랍니다.</p>
						<p class="date">2018.03.01</p>
					</div>
					<div class="article">
						안녕하세요. 뻔한소프트 입니다. <br><br>

						거래소 솔루션이 V3.0.0으로 업그레이드 되었습니다.<br>
						기존에 솔루션을 사용하고 계신 고객분들은 저희에게 연락을 취하셔서 업그레이드를 받으시길 바랍니다.<br><br>

						업그레이드 및 추가된 기능 <br><br>

						- 언어관리<br>
						- 보안패치<br>
						- 데몬서버 관리<br>
						- 지갑관리 서버<br>
						- PHP7.1이상 지원<br>
						- 체결서버<br><br>

						감사합니다.
					</div>
				</div>
				-->
				<div id="notice-list" class="notice-list"></div>
				<div class="pagenate" id="pager"></div>
			</div>
		</div>
		<!-- CONTAINER -->
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
   
    
    <script>
     (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/customer/main" class="round-btn4 active ">'+langConvert('lang.menuNotice','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/customer/questionlist" class="round-btn4">'+langConvert('lang.menuCsQna','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/customer/guide" class="round-btn4">'+langConvert('lang.menuAbout','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/customer/faq" class="round-btn4">FAQ</a>');
			
	})(jQuery);    
    Config.initFooterScript();</script>
    <script>
        // 페이징 처리용 데이터셋
        var listPageSet = {
            totalcount:0            // 전체 레코드 수
            ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
            ,current_page:1         // 현재 선택된 페이지 번호
            ,page_block:5           // 블록수 5

            ,total_page:null        // 전체 페이지 갯수
            ,total_block:null       // 전페 블록 갯수
            ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..)
        };

        // 페이징 처리용 데이터값 가져오기
        $.getJSON("/webbbsmain/noticelistsenv/chno-100/", "", function (data) {
        })
        .success(function(data) {
            for(i=0; i<data.length; i++){
                if(typeof(data[i].rowlimit)==="undefined"){

                }else{
                    listPageSet.totalcount      = parseInt(data[0].totalcount); // 전체 레코드수
                    listPageSet.rowlimit        = parseInt(data[0].rowlimit);   // 한페이지 표현 개수

                    listPageSet.total_page      = calfloat('CEIL', listPageSet.totalcount/listPageSet.rowlimit, 0);
                    listPageSet.total_block     = calfloat('CEIL', listPageSet.total_page/listPageSet.page_block, 0);
                    listPageSet.current_block   = calfloat('CEIL', (listPageSet.current_page)/listPageSet.page_block, 0);
                    init_pager();
                    list_content(listPageSet.current_page);
                }
            };
        });

        // 공지사항 리스트 데이터
        var list_content = function(current_page){
            $.getJSON("/webbbsmain/noticelists/chno-100/&page="+current_page+"/", "", function (data) {
            })
            .success(function(data) {
                var listarray   = [];
                var listview    = '';
                var language = Utils.getLanguage();
                if( typeof(data)!=='undefined'){
                    if( parseInt(data[0].result) > 0){
                        for( var i=0; i<data.length; i++ ){
                            listview =  '';
                            if(language=='en'){
                                listview += '<div class="section"><p class="num">'+(i+1)+'</p><div class="subj">'+data[i].subject+'</div><p class="date">'+data[i].reg_dt+'</p></div>';
                                listview += '<div class="article">'+(data[i].content).replace(/\\\"/gi,'"')+'</div></div>';
                            }else if(language=='zh'){
                                listview += '<div class="section"><p class="num">'+(i+1)+'</p><div class="subj">'+data[i].subject_cn+'</div><p class="date">'+data[i].reg_dt+'</p></div>';
                                listview += '<div class="article">'+(data[i].content_cn).replace(/\\\"/gi,'"')+'</div></div>';
                            }else{
                                listview += '<div class="section"><p class="num">'+(i+1)+'</p><div class="subj">'+data[i].subject_kr+'</div><p class="date">'+data[i].reg_dt+'</p></div>';
                                listview += '<div class="article">'+(data[i].content_kr).replace(/\\\"/gi,'"')+'</div></div>';
                            }
                            listarray.push(listview);
                        }
                    }else{
                        listview = '<div class="section"><div style="height:60px; line-height:59px;"><center>'+langConvert('lang.msgNoticeUnavailable', '')+'</center></div></div>';
                        listarray.push(listview);
                    }
                }else{
                    listview = '<div class="section"><div style="height:60px; line-height:59px;"><center>'+langConvert('lang.msgNoticeUnavailable', '')+'</center></div></div>';
                    listarray.push(listview);
                }
                $('#notice-list').html(listarray);
                $('#notice-list .section').mousedown(function(){
                    if($(this).hasClass('active')){
                            $(this).removeClass('active');
                            $(this).next().slideUp(300);
                    }else{
                            $(this).addClass('active');
                            $(this).next().slideDown(300);
                    }
                });
            });
        };

        controllerComm.nprogressDone();
    </script>
</body>
