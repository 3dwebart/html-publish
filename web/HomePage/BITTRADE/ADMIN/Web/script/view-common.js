function historyBack(){
    history.go(-1);
}
function reload(){location.reload();}

function showUpdateError(code,errorElementId,msg){

    var addMsg = '';
	if(msg) addMsg = '<p>'+msg+'</p>';
    switch(code){
        case -90:
            $('body').viewDialogMessage('<p>기존의 데이터와 전송된 데이터가 동일합니다.</p>'+addMsg,reload);
        break;
        case -101:
            $('body').viewDialogMessage('<p>입력데이터 값을 정의해 주세요.</p><p>Debug폴더에 있는 에러내용을 확인해 보세요.</p>'+addMsg,reload);
        break;
        case -180:
            $('body').viewDialogMessage('<p>강조된 입력란에 빈 항목을 작성해 주세요.</p>'+addMsg,reload);
        break;
        case -210:
            $('body').viewDialogMessage('<p>접근권한이 없습니다.</p>'+addMsg,reload);
        break;
        case -900:
            $('body').viewDialogMessage('<p>토큰 인증에러가 발생하였습니다.</p><p>잠시 후 다시 시도해 주세요.</p>'+addMsg,reload);
        break;
        case -910:
            $('body').viewDialogMessage('<p>연속적인 데이터를 입력할 수 없습니다.</p><p>잠시 후 다시 시도해 주세요.</p>'+addMsg,reload);
        break;
        case -930:
            $('body').viewDialogMessage('<p>올바른 경로로 접근하세요.</p>'+addMsg,historyBack);
        break;
        default:
            $('body').viewDialogMessage('<p>예외가 발생되었습니다.</p><p>Error . '+code+' / '+errorElementId+'</p>'+addMsg,reload);
        break;
    }
}

$(document).ready(function(){
    var menuTopHTML=[];
    var naviHTML=[];
    var menuSubHTML=[];
    var menulink;
    var menuName ='';
    var menuSubName ='';

    $( document ).tooltip();

    //상단메뉴
    menuTopHTML.push('<h1 class="site_title"><a href="/">Admin Console</a></h1>');
    $.getJSON('/?mode=IndexMain/getMenu', 'json', function (data) {
        naviHTML.push('<div class="user"><p>'+data.user.name+'(<a href="#">0</a>)</p></div>');
        naviHTML.push('<div class="breadcrumbs_container">');
        naviHTML.push('<article class="breadcrumbs"><a href="/">관리자</a> ');

        //top메뉴
        for (var key in data.category){

            menulink = '/?mode=IndexMain/menu&cate='+key;
            menuName = data.category[key];
            if(selectedMenu == key){

                //네비게이션
                naviHTML.push('<div class="breadcrumb_divider"></div> ');
                if(key){
                    naviHTML.push('<a href="'+menulink+'">'+menuName+'</a> ');
                    //사이드메뉴
                    menuSubHTML.push('<h3>'+menuName+'</h3>');
                }
            }
        }

        //사이드메뉴
        menuSubHTML.push('<ul class="toggle" >');
        for (var key in data.categorySub){

            menulink = '/?mode=IndexMain/menu&cate='+key;
            menuName = data.category[key];

            menuTopHTML.push('<div class="btn_menu '+((selectedMenu == key)?'selected':'')+'"><a href="'+menulink+'" onmouseover="$(\'ul.topdropsub\').hide().removeAttr(\'id\');$(this).next(\'ul\').show().attr(\'id\',\'menu-top-selected\');">'+menuName+'</a>');
            menuTopHTML.push('<ul class="topdropsub" onmouseleave="$(\'ul.topdropsub\').hide();" >');

            var subdata = data.categorySub[key];
            for (var subkey in subdata){
                menulink = '/?mode='+subkey;
                menuSubName = subdata[subkey];

                if(subkey=="" || subkey.indexOf('-line')!=-1){
                    menuTopHTML.push('<li class="icn_line"> </li>');
                }else{
                    menuTopHTML.push('<li class="icn_basic"><a href="'+menulink+'" class="'+((selectedSubMenu == subkey)?'selected':'')+'">'+menuSubName+'</a></li>');
                }

                if(selectedMenu == key){
                    if(selectedSubMenu == subkey){
                        menuSubHTML.push('<li class="icn_basic"><a href="'+menulink+'" class="selected">'+menuSubName+'</a></li>');
                        naviHTML.push('<div class="breadcrumb_divider"></div> ');
                        naviHTML.push('<a class="current">'+menuSubName+'</a></article>');
                    }else{
                        if(subkey=="" || subkey.indexOf('-line')!=-1){
                            menuSubHTML.push('<li class="icn_line"> </li>');
                        }else{
                            menuSubHTML.push('<li class="icn_basic"><a href="'+menulink+'">'+menuSubName+'</a></li>');
                        }
                    }
                }
            }
            menuTopHTML.push('</ul></div>');
        }
        menuSubHTML.push('</ul>');
        menuSubHTML.push('<h3>Setting</h3><ul class="toggle">');
//        menuSubHTML.push('<li class="icn_settings"><a href="#">기타설정</a></li>');
        menuSubHTML.push('<li class="icn_security"><a href="?mode=WebAdminMember%2Fform&id='+data.user.mbno+'">비번변경</a></li>');
        menuSubHTML.push('<li class="icn_jump_back"><a href="/?mode=IndexMain/logout">로그아웃</a></li>');
        menuSubHTML.push('</ul>');
        menuSubHTML.push('<footer><hr /><p><b>&copy;2018 POPCON Co.,Ltd</b></p></footer>');
        naviHTML.push('</div>');
    })
    .fail(function() {
        $('body').viewDialogMessage('<p>서버에서 메뉴데이터를 가져오는데 실패하였습니다.</p><p>잠시 뒤 다시 시도해 보세요.</p>');
    })
    .always(function() {
        $('#header hgroup').html(menuTopHTML.join(''));
        $('#header section.secondary_bar').html(naviHTML.join(''));
        $('aside.sidebar').html(menuSubHTML.join(''));
    });

    //top scrolling
    $('#footer').html('<div id="toTop" class="tooltip-left" data-title="Top" data-original-title="" title="" style="display: block;"><i class="icn_jump_back">TOP</i></div>');
    $('#toTop').click(function($e){
		$('html, body').animate( {scrollTop:0} );
    });

    if(blockMenu){
        $('div#content').html('<h4 class="alert_error">해당 메뉴에 접근권한이 없습니다. 슈퍼관리자에게 문의하세요.</h4>');
        //$('body').viewDialogMessage('<p>해당 메뉴에 접근권한이 없습니다.</p>');
    }
});

Date.prototype.format = function(f) {
    if (!this.valueOf()) return " ";

    var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
    var d = this;

    return f.replace(/(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p)/gi, function($1) {
        switch ($1) {
            case "yyyy": return d.getFullYear();
            case "yy": return (d.getFullYear() % 1000).zf(2);
            case "MM": return (d.getMonth() + 1).zf(2);
            case "dd": return d.getDate().zf(2);
            case "E": return weekName[d.getDay()];
            case "HH": return d.getHours().zf(2);
            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "mm": return d.getMinutes().zf(2);
            case "ss": return d.getSeconds().zf(2);
            case "a/p": return d.getHours() < 12 ? "오전" : "오후";
            default: return $1;
        }
    });
};

String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};

$(function(){
    var sideBarHeight = $(window).height();
    $('.sidebar').height(sideBarHeight - 41);
});