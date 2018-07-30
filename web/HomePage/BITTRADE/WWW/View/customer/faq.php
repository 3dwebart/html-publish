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
    <script>
    $(function() {
      $( '.faq-wrap li' ).on( 'click', function() {
            $( this ).parent().find( 'li.active' ).removeClass( 'active' );
            $( this ).addClass( 'active' );
      });
});
    
        Config.initHeaderScript();</script>
    <style>
        #container{ padding-bottom: 0;}
       @media (max-width: 768px){
        #container .body-title{ height: 0; }
           #container .body-title .tit { display: none}
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
		
		<script>$('#snb ul li').eq(3).addClass('active');
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
                  <div class="faq-wrap"> 
                      <h3>자주하는 질문</h3>
                      <p class="subtitle">회원가입</p>
                      <ul> 
                         <li><a href="javascript:void(0);"><strong>  미성년자도 가입 및 거래가 가능한가요? </strong></a>
                          <span class="answer"><p>미성년자, 만19세 미만의 회원님들은 거래가 불가능합니다.<br>
                              (2018년 기준, 태어난 해가 1999년 이하부터 거래가 가능합니다.)</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  외국인이 가입할 수 있나요? </strong></a>
                          <span class="answer"><p>불가능합니다. 팝콘은 국내 내국인만 가입 가능합니다. </p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  이메일 인증 코드를 받지 못했습니다. </strong></a>
                          <span class="answer"><p>이메일 계정에 따라 수신 되기 까지 최대 5분까지 소요 될 수 있습니다.<br>
                              5분이 지나도 이메일을 수신하지 못한 경우 신청하신 계정 정보로 로그인하여 가입 인증 이메일 재요청을 하시기 바랍니다.</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  회원 가입시 지인 혹은 가족의 휴대폰 번호로 등록할 수 있나요? </strong></a>
                          <span class="answer"><p>보안 및 정책상 반드시 본인의 명의로 가입 해주셔야 합니다. </p></span>
                          </li>
                        
                        <li><a href="javascript:void(0);"><strong>  해외에 거주하고 있습니다. 해외 휴대전화로 인증가능 한가요? </strong></a>
                          <span class="answer"><p>정부 정책 준수와 안정적인 서비스 운영을 위하여 본인명의 휴대폰으로 인증해야 합니다. </p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  여러 개의 계정을 이용할 수 있나요? </strong></a>
                          <span class="answer"><p>팝콘 거래소는 1인 1계정 원칙으로 하고 있습니다. 타인의 정보를 도용하여 계정을 생성/이용 하는 경우, <br>
                              이용제재 및 추가 조치를 취할 수 있습니다. </p></span>
                          </li>
                          
                     
                        <li><a href="javascript:void(0);"><strong>  회원탈퇴 방법 </strong></a>
                          <span class="answer"><p>회원 탈퇴는정부 정책 준수와 안정적인 서비스 운영을 위하여 본인명의 휴대폰으로 인증해야 합니다. </p></span>
                          </li>
                        </ul>


                      <p class="subtitle"> 정보변경 </p>
                        <ul> 
                        <li><a href="javascript:void(0);"><strong>  휴대전화 번호를 변경하고 싶어요. </strong></a>
                          <span class="answer"><p>계정 관련 서류는 팝콘 CS센터로 접수 받으며, 반드시 인증한 이메일로 보내주셔야 합니다.<br>
                              # 제출서류 <br>
                                1. "신분증 + 메모" 사진<br>
                                2. "신분증 + 메모"를 들고 있는 본인 사진<br>
                                3. 통신사 이용계약서<br><br>
                              
                              # 주의사항 : 신분증은 반드시 주민번호 뒷자리는 가려주세요. </p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  출금계좌를 변경하고 싶어요. </strong></a>
                          <span class="answer"><p>계정 관련 서류는 팝콘 CS센터로 접수 받으며, 반드시 인증한 이메일로 보내주셔야 합니다.<br>
                              # 제출서류 <br>
                                1. "신분증 + 메모" 사진<br>
                                2. "신분증 + 메모"를 들고 있는 본인 사진<br>
                                3. 기존 등록한 통장사본 또는 표지<br><br>
                              
                              # 주의사항 : 신분증은 반드시 주민번호 뒷자리는 가려주세요. </p></span>
                          </li>
                       </ul>
             
                      <p class="subtitle">입금 및 출금</p>
                      <ul> 
                        <li><a href="javascript:void(0);"><strong>  입출금 수수료는 얼마인가요? </strong></a>
                          <span class="answer"><p>팝콘 입금 수수료는 무료입니다.<br>
                              출금 수수료는 건당 정책으로 부과되며, 출금 화폐에 따라 차이가 있으니, "입출금 수수료 안내"를 참고해주세요.<br>
                              [입출금 수수료안내] 바로가기</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  암호화폐 입금이 지연되는 이유가 무엇인가요? </strong></a>
                          <span class="answer"><p>입금에 소요되는 시간은 각 암호화폐별로 다를 수 있으나, 보통 10분~50분 정도 소요됩니다.<br>
                              다만 블록체인 네트워크에 미승인 거래가 과도하게 쌓인 경우 컨펌이 지연될 수 있으며, 입금이 지연될 수 있습니다.</p></span>
                          </li>
                       
                        <li><a href="javascript:void(0);"><strong>  암호화폐 송금 주소를 잘못 입력했어요. </strong></a>
                          <span class="answer"><p>암호화폐의 특성상 송금 신청이 완료되면 취소할 수 없으니, 송금 전 주소를 꼭 확인해 주세요.<br>
                              주소 오입력 시 <br>송금이 정상적으로 이루어지지 않아 소중한 고객님의 자산을 잃을 수 있습니다.
                              송금신청 완료 이후의 송금 과정은 블록체인 네트워크에서 처리되며, 이 과정에서 발생하는 문제는 팝콘 거래소에서  지원해드릴 수 없습니다.<br>
                              단 'BTC' 또는 'ETH' 계열의 입금 주소를 잘못 입력하신 경우 복구 지원을 도와 드립니다.<br>
                              #주의사항 : 복구 작업을 진행하는 경우에도 기술적 문제로 모든 오입금 금액이 복구되지 않을 수 있으니, 송금시 주소 확인 바랍니다.</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  KRW 입금 확인이 안돼요. </strong></a>
                          <span class="answer"><p>아래와 같은 경우에 KRW 입금 처리가 되지 않을 수 있습니다.<br>
                              1. 입금한 예금주명이 팝콘에서 인증한 실명과 일치하지 않는 경우<br>
                                 (타인명의 계좌에서 입금한 경우, 본인명의의 계좌에서 예금주명을 변경한 후 입금한 경우)<br>
                              2. 계정보안 등의 이유로 일시 입금이 정지된 경우<br>
                              # KRW 입금 처리가 되지 않은 경우, 팝콘 고객센터로 접수를 해주세요</p></span>
                          </li>
                        
                        <li><a href="javascript:void(0);"><strong>  암호화폐 출금이 제한되어 있어요. </strong></a>
                          <span class="answer"><p>팝콘은 보이스피싱, 파밍 등의 금융사고 예방을 위하여 아래의 상황에서 암호화폐 출금을 제한합니다.<br>
                              1. KRW 첫입금 시 : 72시간동안 암호화폐 출금이 제한되며, 72시간 후 자동 해제됩니다.<br>
                              2. 휴대전화 번호 초기화 시 : 휴대전화 번호 초기화 후 고객센터 접수시 암호화폐 출금이 제한되며, 휴대전화 번호 재등록 완료 후 본인이 직접 해제할 수 있습니다.</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  암호화페 출금 한도가 어떻게 되나요? </strong></a>
                          <span class="answer"><p>암호화폐 출금 한도는 보안등급에 따라 차등적용되며, 자세한 내용은 등급별 입출금 안내를 참고하세요.<br>
                              [보안등급별 1일 출금한도] 바로가기 </p></span>
                          </li>
                        
                        <li><a href="javascript:void(0);"><strong> 보유한 KRW 전액 출금이 왜 안되나요?  </strong></a>
                          <span class="answer"><p>미체결 주문이 존재하는 경우 입니다. 미체결 주문을 모두 취소하고 KRW 출금 시도하시기 바랍니다.  </p></span>
                          </li>
                       
                        <li><a href="javascript:void(0);"><strong> 보유한 암호화폐 전부를 출금이 왜 안되나요?  </strong></a>
                          <span class="answer"><p>미체결 주문이 존재하는 경우 입니다. 미체결 주문을 모두 취소하고 암호화폐 출금 시도하시기 바랍니다.  </p></span>
                          </li>  
                    </ul>
             
                    <p class="subtitle">거래</p>
                      <ul> 
                        <li><a href="javascript:void(0);"><strong>  거래 수수료는 얼마인가요? </strong></a>
                          <span class="answer"><p>팝콘의 거래 수수료는 마켓에 따라 다르게 적용되고 있습니다. <br>
                              자세한 수수료는 '거래 수수료 안내'에서 확인하실 수 있습니다.<br>
                              [거래 수수료안내] 바로가기</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  미체결 주문은 어떻게 되나요? </strong></a>
                          <span class="answer"><p>미체결 주문은 마켓에 따라 아래와 같이 처리되고 있습니다.<br>
                              1. KRW 마켓의 미체결 주문은 직접 취소하기 전까지 주문이 유지 됩니다.<br>
                              2. BTC, ETH마켓의 미체결 주문은 주문 시점으로 부터 24시간 후에 자동으로 취소 됩니다.</p></span>
                          </li>
                       
                        <li><a href="javascript:void(0);"><strong>  주문을 수정할 수 있나요? </strong></a>
                          <span class="answer"><p>체결된 주문에 대해서는 수정 및 취소가 불가능 합니다.<br>
                              단, 미체결된 주문은 주문가와 주문 수량은 수정할 수 있습니다.</p></span>
                          </li>
                          
                        <li><a href="javascript:void(0);"><strong>  최소 거래 가능 금액은 얼마인가요? </strong></a>
                          <span class="answer"><p>팝콘의 최소 거래 가능 금액은 '거래 이용 안내'에서 확인하실 수 있습니다.
                               [거래 이용안내] 바로가기</p></span>
                          </li>
                                                
                    </ul>
 <!-- //faq-->
 
			</div>
		</div>
		<!-- CONTAINER -->
		
		
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div><!-- // wrap -->
	
	
   
    
    <script>
      (function ($) {
		$('.mobile-main-tab-col4 ul li').eq(0).html('<a href="/customer/main" class="round-btn4 ">'+langConvert('lang.menuNotice','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(1).html('<a href="/customer/questionlist" class="round-btn4">'+langConvert('lang.menuCsQna','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(2).html('<a href="/customer/guide" class="round-btn4">'+langConvert('lang.menuAbout','')+'</a>');
		$('.mobile-main-tab-col4 ul li').eq(3).html('<a href="/customer/faq" class="round-btn4 active">FAQ</a>');
			
	})(jQuery);      
    Config.initFooterScript();</script>
    
</body>
