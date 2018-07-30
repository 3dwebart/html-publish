// escro
function onPopKBAuthMark()
{
    window.open('','KB_AUTHMARK','height=604, width=648, status=yes, toolbar=no, menubar=no, location=no');
    document.KB_AUTHMARK_FORM.action='http://escrow1.kbstar.com/quics';
    document.KB_AUTHMARK_FORM.target='KB_AUTHMARK';
    document.KB_AUTHMARK_FORM.submit();
}

function onPopCompanyInfo(){
    window.open('http://popcon-ex.com','company');
}

var footerHtml = '\
<div id="footer">\
        <div class="footer-wrap">\
            <div class="footer-left"> <em>Copyright 2018ⓒPOPCON. All rights reserved.</em>\
            서울시 송파구 중대로 109. 대동빌딩 12층<br>\
            골든퓨쳐스 대표자 : 이수식 <span></span> 사업자등록번호 791-81-00992\
            </div>\
            <div class="footer-right">\
                <ul>\
                    <li>\
                        <h5>고객센터 </h5>\
                        고객센터 02-400-5651<br>\
                        운영시간 평일 09:00~18:00<br>\
                        <a href="/customer/questionlist">온라인 1:1 고객문의</a><br>\
                        <a href="mailto:help@popcon-ex.com">help@popcon-ex.com</a>\
                    </li>\
                    <li> \
                        <h5>QUICK LINKS </h5>\
                        <a href="/customer/main">공지사항</a><br>\
                        <a href="/about/terms">이용약관</a><br>\
                        <a href="/customer/faq"> 자주묻는질문</a><br>\
                        <a href="/about/privacy">개인정보취급방침</a>\
                    </li>\
                    <li> <span class="ico-fb"> </span> \
                         <span class="ico-twitter"> </span> \
                    </li>\
                </ul>\
            </div>\
       </div>\
</div>\
';
/*
var footerHtml = '\
<div id="footer">\
	<div class="footer-content">\
<p>Copyright 2018ⓒPOPCON. All rights reserved. </p>\
	<div class="footer-nav">\
		<ul>\
			<li><a href="javascript:onPopCompanyInfo();">'+langConvert('lang.menuEtcCompany','')+'</a></li>\
			<li><a href="/about/terms">'+langConvert('lang.menuAboutTerms','')+'</a></li>\
			<li><a href="/about/privacy">'+langConvert('lang.menuAboutPrivacy','')+'</a></li>\
			<li><a href="/customer/main">'+langConvert('lang.menuNotice','')+'</a></li>\
            <li><a href="/account/signin">'+langConvert('lang.login','')+'</a></li>\
            <li><a href="/account/signup">'+langConvert('lang.join','')+'</a></li>\
		</ul>\
    <div class="box">\
					골든퓨쳐스 대표자 : 이수식<span></span>주소 : 서울시 송파구 중대로 109. 대동빌딩 12층<span></span> 사업자등록번호 791-81-00992<br>\
					고객센터 02-400-5651<span></span>운영시간 평일 09:00~18:00 (본사 방문 상담은 진행하지 않습니다.)<span></span>고객문의 : <a href="mailto:help@popcon-ex.com">ailto:help@popcon-ex.com</a>\
</div>\
	</div>\
</div>\
</div>\
';
*/

document.write(footerHtml);
