<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
	<link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-comm.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/controller-form.min.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();</script>
        <style>
        @media (max-width: 768px){
            #snb{ display: none} 
            #container .body-title{ height:0; border-bottom: 1px solid #ccc }
            .table-basic table tbody td { font-size: 11px;}
            .table-basic table thead th{ border-top:none}
            
        }

    </style>
</head>

<body class="sub-background">
    
	
	<div id="wrap">
		<!-- HEADER -->
		<script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>		
		<!-- // HEADER -->
		
		<!-- GNB -->
		<script src="<?=$view['url']['static']?>/assets/write/snb-account.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
		<!-- // GNB -->
		
		<script>$('#snb ul li').eq(2).addClass('active');
         $('.mobile-nav-bottom ul li a:last').addClass('active');
              $('#gnb li a').eq(3).addClass('active');
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
				<div class="table-basic">
					<table class="list-connectioninfo">
						<thead>
						<tr>
							<th><?php Language::langConvert($view['lang'], 'date');?></th>
							<th><?php Language::langConvert($view['lang'], 'status');?></th>
							<th><?php Language::langConvert($view['lang'], 'ipAddress');?></th>
						</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<div class="pagenate" id="pager">

					</div>
				</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
    <script>
   (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/account/verificationcenter" class="round-btn4 ">'+langConvert('lang.menuAccountVerificationCenter','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/account/signedit" class="round-btn4 ">'+langConvert('lang.menuAccountSignEdit','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/account/connectioninfo" class="round-btn4 active">'+langConvert('lang.menuAccountConnectionInfo','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/account/otp" class="round-btn4">'+langConvert('lang.menuAccountOtp','')+'</a>');
        
        var infoTextOrigin = $('.mobile-main-tab-col4 ul li a').eq(1).text();
        var infoTextChange = '정보변경';
        function memberInfoTextChange() {
            if($(window).width() > 340) {
                $('.mobile-main-tab-col4 ul li a').eq(1).text(infoTextOrigin);
            } else {
                $('.mobile-main-tab-col4 ul li a').eq(1).text(infoTextChange);
            }
        }
        memberInfoTextChange();
        $(window).resize(function() {
            memberInfoTextChange();
        });
	})(jQuery);
        Config.initFooterScript();</script>
    <script>
        var listPageSet = {
            totalcount:0            // 전체 레코드 수
            ,rowlimit:0             // 화면에 표시할 레코드 번호 갯수
            ,current_page:1         // 현재 선택된 페이지 번호
            ,page_block:5           // 블록수 5

            ,total_page:null        // 전체 페이지 갯수
            ,total_block:null       // 전페 블록 갯수
            ,current_block:null     // 현재 페이지가 속해 있는 블록 번호(1:1-5, 2:6-10, 3:11-15..)
        };

        var t       = Utils.getTimeStamp();
        var param   = "t-"+t+"/";
        $.getJSON("/getmemberloginhis/listsenv/"+param, "", function (data) {
        })
            .success(function (data) {
                for (i = 0; i < data.length; i++) {
                    if (typeof (data[i].rowlimit) === "undefined") {

                    } else {
                        listPageSet.totalcount = parseInt(data[0].totalcount); // 전체 레코드수
                        listPageSet.rowlimit = parseInt(data[0].rowlimit);   // 한페이지 표현 개수
                        listPageSet.total_page = calfloat('CEIL', listPageSet.totalcount / listPageSet.rowlimit, 0);
                        listPageSet.total_block = calfloat('CEIL', listPageSet.total_page / listPageSet.page_block, 0);
                        listPageSet.current_block = calfloat('CEIL', (listPageSet.current_page) / listPageSet.page_block, 0);
                        init_pager();
                        list_content(listPageSet.current_page);
                    }
                };
            });

         var list_content = function (current_page) {
            var t       = Utils.getTimeStamp();
            var param   = "t-"+t+"/&page="+current_page+"/";
            $.getJSON("/getmemberloginhis/lists/"+param, "", function (data) {
            })
                .success(function (data) {
                    var listarray = [];
                    var listview = '';
                    var lh_reg_ip = '';
                    if (typeof (data) !== 'undefined') {
                        if (parseInt(data[0].result) > 0) {
                            for (var i = 0; i < data.length; i++) {
                                listview = '<tr>';
                                listview += '<td class="text-align-center">' + data[i].lh_reg_dt + '</td>';

                                var result_msg = '';
                                if(data[i].lh_result_msg == 'SignOut'){
                                    result_msg = '로그아웃';
                                }else{
                                    result_msg = data[i].lh_result_msg;
                                }
                                listview += '<td class="text-align-center">' + result_msg + '</td>';
                                listview += '<td class="text-align-center">' + data[i].lh_reg_ip + '</td>';
                                listview += '</tr>';
                                listarray.push(listview);
                            }
                        } else {
                            listview = '<tr><th colspan="4"><center>'+langConvert('lang.msgAccessInformationDoesNotExist', '')+'</center></th></tr>';
                            listarray.push(listview);
                        }
                    } else {
                        listview = '<tr><th colspan="4"><center>'+langConvert('lang.msgAnErrorHasOccurredPleaseTryAgain', '')+'</center></th></tr>';
                        listarray.push(listview);
                    }
                    $('table.list-connectioninfo>tbody').html(listarray.join());
                });
            };
    </script>
</body>