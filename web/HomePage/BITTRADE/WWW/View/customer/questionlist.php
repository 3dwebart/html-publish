<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
        <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/index.css" rel="stylesheet">
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
		
		<script>$('#snb ul li').eq(1).addClass('active');
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
				<div class="table-basic">
					<table class="list-customer">
						<thead>
						<tr>
							<th class="text-align-center"><?php Language::langConvert($view['lang'], 'ordersNo');?></th>
							<th class="text-align-center"><?php Language::langConvert($view['lang'], 'ordersType');?></th>
							<th class="text-align-center"><?php Language::langConvert($view['lang'], 'ordersDate');?></th>
							<th class="text-align-center"><?php Language::langConvert($view['lang'], 'ordersStatus');?></th>
						</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<div class="pagenate mb30" id="pager"></div>

					<div class="text-center">
						<button type="button" class="btn btn-l btn-primary" style="width:200px; margin-bottom:20px;background: #007ac7;" onclick="location.href='/customer/question'" ><?php Language::langConvert($view['lang'], 'btnQuestionRequest');?></button>
					</div>
				</div>
			</div>
		</div>
		<!-- CONTAINER -->
		
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
		
		
		
		
		
    
    
    <script>
         (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/customer/main" class="round-btn4 ">'+langConvert('lang.menuNotice','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/customer/questionlist" class="round-btn4 active">'+langConvert('lang.menuCsQna','')+'</a>');
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
    // --------------------------------------------------
        // 문의 데이터 - 페이징
        var customer_list_page = function(){
            $.getJSON("/webbbsmain/customerlistsenv/", "", function (data) {
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
                        customer_list_content(listPageSet.current_page);
                        customerSubListArray = [];
                    }
                };
            });
        };

        // 문의 데이터 - 리스트
        var customer_list_content = function(current_page){
            $.getJSON("/webbbsmain/customerlists/&page="+current_page+"/", "json", function (data) {
            })
            .success(function(data) {
                var listarray   = [];
                var listview    = '';
                var val1 = '';
                var list_start_num = 0;
                if(listPageSet.current_page>0){
                    list_start_num = (listPageSet.current_page*20)-20;
                }
                if( typeof(data)!=='undefined'){
                    if( parseInt(data[0].result) > 0){
                        for( var i=0; i<data.length; i++ ){
                                                    var customer_com = "";

                                                    if((data[i].cmt_cnt) > 0){
                                                            customer_com = '<span class="blue">'+langConvert('lang.msgComplete', '')+'</span>';
                                                    }else{
                                                            customer_com = '<span class="red">'+langConvert('lang.msgReceipt', '')+'</span>';
                                                    }
                            val1 =  '<tr id="list'+data[i].bbs_no+'" onclick="onCustomerSubList('+data[i].bbs_no+', $(this))" style="cursor:pointer">';

                            listview = val1;
                            listview += '<td class="text-align-center">'+(list_start_num + (i+1))+'</td>';
                                                    listview += '<td class="text-align-center">'+data[i].subject+'</td>';
                            listview += '<td class="text-align-center">'+data[i].reg_dt+'</td>';
                            listview += '<td class="text-align-center">'+customer_com+'</td>';
                            listview += '</tr>';
                            listarray.push(listview);
                        }
                    }else{
                        listview = '<tr><td colspan="4"><center>'+langConvert('lang.msgThereAreNoRecentContactHistory', '')+'</center></td></tr>';
                        listarray.push(listview);
                    }
                }else{
                    listview = '<tr><td colspan="4"><center>'+langConvert('lang.msgThereAreNoRecentContactHistory', '')+'</center></td></tr>';
                    listarray.push(listview);
                }
                $('table.list-customer>tbody').html(listarray.join());
            });
        };

            var customerSubListArray = [];

        // 문의하기 상세 출력
        function onCustomerSubList(bbsno, targetDom){
            if(!targetDom.hasClass('active')){
                /**************
                문의글가져오기
                *************/
                var location_url    = "/webbbsmain/customerview/bbsno-"+bbsno;

                var my_content = '';
                var rep_content = '';
                $.getJSON(location_url, "", function (data) {
                })
                .done(function(data) {
                    if( typeof(data)!=='undefined' ){
                        for( var j=0; j<data.length; j++ ){
                                my_content = data[j].content;
                                rep_content = data[j].rep_content;
                        }
                    }

                    var myhtml = '<tr><td colspan="4" style="word-break:break-all;text-align:left;padding:10px;">'+my_content+'</td></tr>';

                    if(rep_content){
                        html = '<tr class="customer-a customerdetail' + bbsno + '">';
                        html = html +'<td style="width:20%;text-align:center;">'+langConvert('lang.msgAnswersContent', '')+'</td>';
                        html = html +'<td colspan="3">'+(rep_content).replace(/\\\"/gi,'"')+'</td>';
                        html = html +'</tr>';
                        $('#list'+bbsno).after(html);
                    }

                    $('#list'+bbsno).after(myhtml);
                });
                targetDom.addClass('active');
            }else{
                var location_url    = "/webbbsmain/customerview/bbsno-"+bbsno;

                var my_content = '';
                var rep_content = '';
                $.getJSON(location_url, "", function (data) {
                })
                .done(function(data) {
                    if( typeof(data)!=='undefined' ){
                        for( var j=0; j<data.length; j++ ){
                                rep_content = data[j].rep_content;
                        }
                    }

                    if(rep_content){
                        $('#list'+bbsno).next().remove();
                    }

                    $('#list'+bbsno).next().remove();
                });

                targetDom.removeClass('active');
            }
        }


        var list_content = function(current_page){
            customer_list_page(current_page);
        };

         // 기본 리스트 셋팅
        $(document).ready(function(){
            customer_list_page();
        });
    </script>
</body>
