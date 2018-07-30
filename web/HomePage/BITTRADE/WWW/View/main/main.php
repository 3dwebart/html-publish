<head>

	<link href="<?= $view['url']['static'] ?>/assets/css/main.css" rel="stylesheet">
    <link href="<?= $view['url']['static'] ?>/assets/css/swiper.min.css" rel="stylesheet">
	<link href="<?= $view['url']['static'] ?>/assets/css/xeicon.css" rel="stylesheet">
  <!--<link href="<?= $view['url']['static'] ?>/assets/css/style.css?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>" rel="stylesheet"> -->
	<script>
		var tmpHost = window.location.host;
		var staticHost = tmpHost.replace('www', 'static');
		var tmpHostName = window.location.hostname;
		var tmpPath = window.location.path;
		var tmpProtocol = window.location.protocol;
		var fullUrl = window.location.href;
		var staticFullUrl = tmpProtocol + '//' + staticHost;
		var tmpUrl = fullUrl.replace(tmpProtocol + '//' + tmpHost, '');
		console.log('url = ' + tmpUrl);
	</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/jquery-validate.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/utils.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/perpect-scroll.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/decimal.min.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script><!-- 그래프 십진법 jquery 추가됨 -->
    <script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-comm.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/script/src-orgin/controller-form.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/write/common-pagination.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/chart/highstock.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/chart/exporting.js"></script>
    <script src="<?= $view['url']['static'] ?>/assets/js/swiper.min.js"></script>
    <script>Config.initHeaderScript();setSocChannel('<?= $view['exchannel']['sock_ch'] ?>');</script>

	<style>
		.mobile-nav-bottom{ display: none;}
	</style>
    
 <script language="Javascript">
var tmpHost = window.location.host;
var tmpHostName = window.location.hostname;
var tmpPath = window.location.path;
var tmpProtocol = window.location.protocol;
var fullUrl = window.location.href;
var tmpUrl = fullUrl.replace(tmpProtocol + '//' + tmpHost, '');
	$(document).ready(function() {
        cookiedata = document.cookie;
        if (cookiedata.indexOf("mcookie=done") < 0) {
            document.getElementById('popup').style.display = "block";
        } else {
            document.getElementById('popup').style.display = "none";
        }

    });

    /* popup*/
    function setCookie(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

    function closeWin() {
        document.getElementById('popup').style.display = "none";
    }

    function todaycloseWin() {
        setCookie("mcookie", "done", 7);
    }
    
 
    
    
</script> 
    
</head>

<body>

<!-- <script src="<?= $view['url']['static'] ?>/assets/write/header-menu.js?v=<?= Language::langConvert($view['langcommon'], 'version'); ?>"></script> -->


    <!-- 메인 영역 -->
	<style>
		#header { border-bottom: 0;}
	</style>
    
 <!--modal notice-->  
 
<div  id="popup"  class="main-modal-wrap" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
           <h4>팝콘 거래소에서 알려드립니다. </h4>
            <div class="modal-body">팝콘은 정식 서비스 출시에 앞서, 베타 서비스를 시행합니다.<br>
베타 서비스 기간 중에는 금융사기범죄(피싱 및 대포통장) 등 각종 금융 범죄로부터 고객님들의 자산을 안전하게 보호하고자, 정식 서비스 이전까지 베타 테스터를 제외한 거래소 가입, 로그인, 입출금 서비스, 암호화폐 입출금 서비스가 제한됩니다.<br>
또한 베타 서비스 기간에는 웹/APP 서비스 등 일부 기능에 제한이 있을 수 있습니다.<br>
안정적인 서비스 제공과 소중한 회원님의 정보보호를 위한 조치이니 양해 부탁드립니다.<br>
빠른 시일내에 정식 서비스를 오픈하여 고객님의 성원에 보답 드리겠습니다.
            </div> <!--modal content-->
            <div> <label>  <input onclick="todaycloseWin();" type="checkbox" name="chkbox" value="checkbox">
 일주일간 열지 않음 </label>
                 
         <a style="display:block" id="popup-close" href="javascript:onclick=closeWin();" class="modal-close btclose">창닫기 </a>
            </div>
        </div>
    </div>
 </div> <!--// modal notice-->  
    

    <div class="m-main-wrap">
		<div class="mobile-header"> 

			<div class="pc-gnb" >
				<ul>
					<li><a href="/trade/order/">거래소</a></li>
					<li><a href="/wallet/balances">입출금</a></li>
					<li><a href="/order/tradecomplete">yhgygy</a></li>
					<li><a href="/account/signedit">마이페이지</a></li>
					<li><a href="/customer/main">고객센터</a></li>
				</ul>
			</div>
			<h1>
				<a href="#"> </a>
			</h1> 
			<a href="" class="btn-mobile-tour">둘러보기 </a>
			<div class="gnb-right">

				<ul>
					<li><a href="/account/signin">로그인</a></li>
					<li><a href="/account/signup" class="btn-register">회원가입</a></li>
					<!--
					<li>
						<div class="lang">
							<a href="#"><span class="xi-globus"></span></a>
							<ul>
								<li class="kr"><a href="javascript:Utils.setLanguage('ko')"><span>KOR</span></a></li>
								<li class="en"><a href="javascript:Utils.setLanguage('en')"><span>ENG</span></a></li>
								<li class="zh"><a href="javascript:Utils.setLanguage('zh')"><span>CHN</span></a></li>
							</ul>
						</div> 
					</li>
					-->
				</ul>

			</div>

		</div>
		<!--mobile-header-->

		<div class="mobile-main-slide-wrap">
			<h2 class="pc-main-title">새로운 알트코인으로 누구보다 먼저! <em>믿고 안전하게 거래할 수 있는 보안체계  </em></h2>
			<div class="swiper-wrapper">
				<div id="pc-slider">
					<div class="pc-slider-content swiper-slide">
						<ul>
							<li class="ico-bitcoin-pc">
								새로운 알트코인으로<br>누구보다 먼저!
								<em> 믿고 안전하게 거래할 수 있는 보안체계</em> 
							</li>   
							<li class="ico-blockchain-pc">대한민국 최다<br>암호화폐 거래소
								<em> 신규알트코인의 첫번째 거래소</em>
							</li>
							<li class="ico-wallet-pc">
								빠르고 쉬운<br>구매시스템
								<em>간단하고 즉각적이고 안전합니다.</em> 
							</li>
						</ul>

					</div>    
				</div> <!-- pc slide content-->


			</div><!--PC slide-->


			<!--mobile slide-->
			<div id="mobile" class="swiper-container">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						<div class="mobile-main-slide-content ico-bitcoin-mobile  ">
							새로운 알트코인으로<br>누구보다 먼저!
							<em> 믿고 안전하게 거래할 수 있는 보안체계</em>

						</div>
					</div>

					<div class="swiper-slide">
						<div class="mobile-main-slide-content ico-blockchain-mobile  ">
							대한민국 최다<br>암호화폐 거래소
							<em> 신규알트코인의 첫번째 거래소</em>

						</div>
					</div>

					<div class="swiper-slide">
						<div class="mobile-main-slide-content ico-wallet-mobile  ">
							빠르고 쉬운<br>구매시스템
							<em>간단하고 즉각적이고 안전합니다.</em>

						</div>
					</div>




				</div> <!--swiper-wrapper-->
				<div class="slide-dot">
					<div class="swiper-pagination"></div>
				</div>

			</div>
			<!--//mobile slide-->
		</div><!--slide-->



		<div class="btn-mobile-join-wrap">
			<a href="/account/signup" class="btn-mobile-join">회원가입</a><br>
			<em> 이미 팝콘 계정을 가지고 계신가요? </em>

			<a href="/account/signin" class="btn-mobile-login">로그인</a>
		</div>


		<div class="pc-main-wrap"> 
			<div class="pc-main-ctype-list">
				<!--
				<div class="round-tab-wrap"> 
					<ul> 
						<li> <a href="#won" class="round-btn active"> 원화거래</a></li> 
						<li> <a href="#btc" class="round-btn">BTC 거래 </a></li> 
						<li><a href="#eth" class="round-btn">ETH 거래 </a></li> 
					</ul> 
				</div>   
				--> 


				<table id="current-coin-price">
					<caption> </caption>
					<colgroup>
						<col width="">
						<col width="">
						<col width="">
						<col width="">
						<col width="">
						<col width="">
						<col width="">
					</colgroup>
					<thead>
						<tr>
							<th>코인</th>
							<th>시가총액</th>
							<th>실시간 시세 </th>
							<th>전일대비</th>
							<th>24h 고가</th>
							<th>24h 저가</th>
							<th>24h 거래량</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($view['markets'] as $key => $value) { $key_lower = strtolower($key);?>
							<tr>
								<td id="coin-type-<?=$key_lower?>"><?php Language::langConvert($view['langcommon'], $key);?></td> <!--코인명-->
								<td id="total-price-<?=$key_lower?>"><?=ceil($value['volume24h']*$value['last']);?></td> <!--시가총액 -->
								<td id="last-<?=$key_lower?>"><?= ceil($value['last']);?></td><!--실시간시세 -->
								<td id="percentChange-<?=$key_lower?>"><?= $value['percentChange'].'%' ?></td><!--전일대비 -->
								<td id="bid-<?=$key_lower?>"><?= ceil($value['bid']);?></td><!--24h 고가 -->
								<td id="ask-<?=$key_lower?>"><?= ceil($value['ask']);?></td><!--24h 저가 -->
								<td id="volume24h-<?=$key_lower?>"><?= $value['volume24h'] ?></td><!--거래량 -->
							</tr>
						<?php } ?>
					</tbody>
				</table>


			</div> <!--// coin list-->

			<div class="service-info-wrap">
				<ul>
					<li>
						<div class="content">
							<span class="service-info-ico-wallet"> 
								<h3>안전한 월렛보안</h3>
								중요한 개인 키는 하드웨어 지갑의 안전한 오프라인 환경에서 완벽하게 보호됩니다.
							</span>
						</div> 
					</li>
					<li>
						<div class="content">
							<span class="service-info-ico-commission"> 
								<h3>경쟁적 커미션</h3>
								수취인 및 제조사에 대한 합리적인 거래 수수료, 대량 거래자에 대한 특별 조건, 시장 제조사에 대한 강력한 제공.
							</span>
						</div> 
					</li>
					<li>
						<div class="content service-info-ico-security">
							<span> 
								<h3>강력한 보안성</h3>
								DDoS 공격으로부터 보호, PCI DSS 표준을 준수하는 완전한 데이터 암호화. 
							</span>
						</div> 
					</li>

				</ul>    

			</div>

		</div>  <!--// PC CONTENT--> 
		<div class="app-info-wrap"> 
			<div class="app-info">
				<span> <h4>Trade. Anywhere.<br>간편하고 빠른 팝콘 모바일 앱 </h4> 
					당사는 암호화 시장에 대한 투자를 쉽고 접근이 용이하게 만듭니다.  팝콘에서는 비트 코인, 라이트 코인, 대시 코인, 리플을 매수하고 매도하십시오.<br>
					여러분은 저희 플랫폼에서 편안하게 비트코인과 이더리움, 기타 암호화폐를  거래할 수 있으며, 여러분에게 세계에서 가장 많이 거래되는 암호화와 디지털 지불 시스템의 일부가 될 수 있는 기회를 제공합니다.
				</span>
			</div>
		</div> <!--app info-->





		<div class="main-visual">  </div>

		<div class="main-bottom-shape"> </div> 
		<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
	</div> <!--mobile content-->


	<script>
		var swiper = new Swiper('.swiper-container', {
			loop: true,
			pagination: {
				el: '.swiper-pagination',
			},
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
		});
	</script>
</body>

<script>
	(function ($) {
		$('.pc-gnb ul li').eq(0).html('<a href="/trade/order">' + langConvert('lang.menuTradeNew', '') + '</a>');
		$('.pc-gnb ul li').eq(1).html('<a href="/order/presence">' + langConvert('lang.menuHistory', '') + '</a>');
		$('.pc-gnb ul li').eq(2).html('<a href="/wallet/balances">' + langConvert('lang.menuWalletNew', '') + '</a>');
		$('.pc-gnb ul li').eq(3).html('<a href="/account/signedit">' + langConvert('lang.menuAccountNew', '') + '</a>');
		$('.pc-gnb ul li').eq(4).html('<a href="/customer/main">' + langConvert('lang.menuCsNew', '') + '</a>');
		$('.gnb-right > ul > li').eq(0).html('<a href="/account/signin">' + langConvert('lang.login', '') + '</a>');
		$('.gnb-right > ul > li').eq(1).html('<a href="/account/signup" class="btn-register">' + langConvert('lang.join', '') + '</a>');
		$('.gnb-right > ul > li').find('.lang > a').addClass(Utils.getLanguage());
	})(jQuery);
	
	Config.initFooterScript();
</script>