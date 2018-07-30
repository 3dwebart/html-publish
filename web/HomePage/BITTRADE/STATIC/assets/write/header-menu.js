var tmpHost = window.location.host;
var staticHost = tmpHost.replace('www', 'static');
var tmpHostName = window.location.hostname;
var tmpPath = window.location.path;
var tmpProtocol = window.location.protocol;
var fullUrl = window.location.href;
var staticFullUrl = tmpProtocol + '//' + staticHost;
var tmpUrl = fullUrl.replace(tmpProtocol + '//' + tmpHost, '');

$(function(){
    var current = location.pathname;
    $('.mobile-nav-bottom li a').each(function(){
        var $this = $(this);
		// if the current path is like this link, make it active
		var Url = $(this).attr('href');
		if(tmpUrl == '/order/tradeuncomplete') {
			if(Url == '/order/presence') {
				$(this).addClass('active');
			}
		}
		if(Url == tmpUrl) {
			$(this).addClass('active');
		}
    });
});


if( typeof get_member === 'undefined' || !get_member.hasOwnProperty('mb_name') || typeof get_member.mb_name === 'undefined'){
    var get_member={
        mb_name:'guest',
        mb_level:0
    };
}

// 좌측 상단 슬라이딩 메뉴
var submenulink = [
    {link:"/history", text:langConvert('lang.menuHistory','')},
    {link:"/trade",text:langConvert('lang.menuTrade','')},
    {link:"/chart",text:langConvert('lang.menuTradeMarketCondition','')},
    {link:"/exchange",text:langConvert('lang.menuWallet','')},
    {link:"/account",text:langConvert('lang.menuAccountSignEdit','')},
    {link:"/customer",text:langConvert('lang.menuCsNotice','')}
];

var mNavLoginedEtc = '';
if(typeof  Account!=undefined) {
    Account.isLogined(function(logined) {
		if(logined) {
			mNavLoginedEtc = '\
				<span>'+get_member.mb_name+'</span>\
				<a href="/account/signout">'+langConvert('lang.gnbLogout','')+'</a>\
			';
		} else {
			mNavLoginedEtc = '\
				<a href="/account/signin">'+langConvert('lang.gnbLogin','')+'</a>\
				<a href="/account/signup">'+langConvert('lang.gnbSignup','')+'</a>\
			';
		}
    });
}

/*
<div class="lang">\
	<a href="#" class="'+Utils.getLanguage()+'"><span></span></a>\
	<ul>\
		<li class="kr"><a href="javascript:Utils.setLanguage(\'ko\')"><span>KOR</span></a></li>\
		<li class="en"><a href="javascript:Utils.setLanguage(\'en\')"><span>ENG</span></a></li>\
		<li class="zh"><a href="javascript:Utils.setLanguage(\'zh\')"><span>CHN</span></a></li>\
	</ul>\
</div>\
*/

// 모바일 해상도에서만 보이는 하단 네비 메뉴
//if(tmpUrl == '/account/signrequestpwd' || tmpUrl == '/account/signup') {
//	var menuhtml = '';
//} else {
if( get_member.hasOwnProperty('mb_id') ){
var menuhtml = '\
<div class="mobile-nav-bottom" >\
    <ul>\
        <li><a class="mobile-nav-market" href="/trade/order/" id="footNav1">'+langConvert('lang.menuTradeNew','')+'</a></li>\
        <li><a class="mobile-nav-invest " href="/order/presence" id="footNav2">'+langConvert('lang.menuHistory','')+'</a></li>\
        <li><a class="mobile-nav-cashout" href="/wallet/balances" id="footNav3">'+langConvert('lang.menuWalletNew','')+'</a></li>\
        <li><a class="mobile-nav-mypage" href="/account/signedit" id="footNav4">'+langConvert('lang.menuAccountNew','')+'</a></li>\
    </ul>\
</div>\
';
}
menuhtml += '\
<div class="header-margin"></div>\
	<div id="header">\
<div class="header-container">\
		<a href="#" class="btn-m-nav"><i class="xi-bars"></i></a>\
		<h1><a href="/">거래소</a></h1>\
		<div class="pull-left">\
			<ul id="gnb">\
				<li '+((document.URL.indexOf(submenulink[1].link) !== -1) ? 'class="active"':'')+'><a href="/trade/order">'+langConvert('lang.menuTradeNew','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[1].link) !== -1) ? 'class="active"':'')+'><a href="/order/presence">'+langConvert('lang.menuHistory','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[3].link) !== -1) || (document.URL.indexOf(submenulink[0].link) !== -1) ? 'class="active"':'')+'><a href="/wallet/balances">'+langConvert('lang.menuWalletNew','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[4].link) !== -1) ? 'class="active"':'')+'><a href="/account/signedit">'+langConvert('lang.menuAccountNew','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[5].link) !== -1) ? 'class="active"':'')+'><a href="/customer/main">'+langConvert('lang.menuCsNew','')+'</a></li>\
			</ul>\
';


// 우측 상단 메뉴
var rightMenu = '\
		<ul id="welcome">\
			<li><a href="/account/signin"><i class="fa fa-cog"></i>'+langConvert('lang.login','')+'</a></li>\
			<li><a href="/account/signup"><i class="fa fa-user-circle-o"></i>'+langConvert('lang.join','')+'</a></li>\
		</ul>\
</div>\
';

if(typeof  Account!=undefined){
    Account.isLogined(function(logined){

    if(logined){
    rightMenu = '\
            <ul id="welcome">\
				<li class="text"><strong>'+get_member.mb_name+'</strong></li>\
				<li><a href="/account/signout">'+langConvert('lang.logout','')+'</a></li>\
			</ul>\
</div>\
        ';
        }
        document.write(menuhtml + rightMenu + '</div></div>');
    });
}

var menuHtml2 = '\
		<div class="bg-gnb"></div>\
		<div id="m-nav">\
			<p class="logo"><a href="' + tmpProtocol + '//' + tmpHost + '"><span></span></a></p>\
			<div class="log">\
				'+mNavLoginedEtc+'\
			</div>\
			<ul>\
				<li '+((document.URL.indexOf(submenulink[1].link) !== -1) ? 'class="active"':'')+'><a href="/trade/order">'+langConvert('lang.menuTradeNew','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[2].link) !== -1) ? 'class="active"':'')+'><a href="/order/presence">'+langConvert('lang.menuHistory','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[3].link) !== -1) || (document.URL.indexOf(submenulink[0].link) !== -1) ? 'class="active"':'')+'><a href="/wallet/balances">'+langConvert('lang.menuWalletNew','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[4].link) !== -1) ? 'class="active"':'')+'><a href="/account/verificationcenter">'+langConvert('lang.menuAccountNew','')+'</a></li>\
				<li '+((document.URL.indexOf(submenulink[5].link) !== -1) ? 'class="active"':'')+'><a href="/customer/main">'+langConvert('lang.menuCsNew','')+'</a></li>\
			</ul>\
			<button class="btn-close"><i class="xi-close-thin"></i></button>\
		</div>\
';

document.write(menuHtml2);

if( get_member.hasOwnProperty('mb_id') && $('.wallet-page').length == 0){
    initBalance();
}
$(document).ready(function(){
    var current = location.pathname;
    $('#gnb li a').each(function(){
        var $this = $(this);
        // if the current path is like this link, make it active
        if($this.attr('href').indexOf(current) !== -1){
            $this.addClass('active').parent().siblings().find('a').removeClass('active');
        }
	});
	$(window).scroll(function() {
		if($(this).scrollTop() == 0 || $(this).scrollTop() < 69) {
			$('#header').removeClass('active');
		} else {
			$('#header').addClass('active');
		}
	});

	$('#header, #gnb li a, #welcome li a').on('mouseenter', function() {
		if($(document).scrollTop() != 0 || $(document).scrollTop() > 70) {
			$('#header').removeClass('active');
		}
	});
	$('#header').on('mouseleave', function() {
		if($(document).scrollTop() != 0 || $(document).scrollTop() > 70) {
			$('#header').addClass('active');
		}
	});

	$('#gnb li a, #welcome li a').on('mouseleave', function() {
		if($(document).scrollTop() != 0 || $(document).scrollTop() > 70) {
			//$('#header').addClass('active');
			$('#header').on('mouseenter', function() {
				$('#header').removeClass('active');
			});
			$('#header').on('mouseleave', function() {
				$('#header').addClass('active');
			});
		}
	});
})