var bean = $.parseJSON($.base64.decode(jsonObject));

var selectedPage = 1;
var nbCmtPages = (bean.common.totalPage)?bean.common.totalPage:0;
var nbCmtPageLength = 5;

//리스트 번호 마지막 번호
function getLastestNum(page){
    return bean.common.totalCount - (bean.common.limitRow*(page-1));
}
function getFirstNum(page){
    return bean.common.limitRow*(page-1) + 1;
}

function getUrl(page){
    var url = bean.link.cmtlist+'&page='+page;
    return url;
}

var isUseEdit = false;
var cmtWriteLink = '/?mode=WebBbsMainCmt/insert';
var oEditors = []; //에디터
var elholder = ''

function setEditor(elholder){
    try{
        elholder = elholder;
        nhn.husky.EZCreator.createInIFrame({
                oAppRef: oEditors,
                elPlaceHolder: elholder,
                sSkinURI: "/Plugin/editor/SmartEditor2Skin.html",	
                htParams : {
                        bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                        bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                        bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                        //aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
                        fOnBeforeUnload : function(){
                            //alert("완료!");
                        }
                }, //boolean
                fOnAppLoad : function(){
                        //예제 코드
                        //oEditors.getById["content"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
                },
                fCreator: "createSEditor2"
        });
        isUseEdit = true;
    }catch(e){}

}

/*
 * 여긴 un min
 */
$(document).ready(function(){

    //코멘트
    $("#btn_submit_cmt").click(function(){
        var result = -1;
        $('body').showDialogProgress();
        $("form").attr("action",cmtWriteLink);
        $("#btn_submit_cmt").attr("disabled",true);

        if(isUseEdit) oEditors.getById['textarea-cmtcontent'].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
        $.post(cmtWriteLink,
            $('form').serialize(),
            function(json){
                result = parseInt(json.result);
                if(result>0){
                    location.reload();
                    console.log(bean.link.view+'&id='+$.getUrlVar('id'));
                    $(location).attr('href',bean.link.view+'&id='+$.getUrlVar('id'));
                    if($.getUrlVar('id')) $(location).attr('href',bean.link.view+'&id='+$.getUrlVar('id'));
                    else $(location).attr('href',bean.link.view+'&id='+$.getUrlVar('id'));
//                    if($.getUrlVar('id')) $(location).attr('href',bean.link.view+'&id='+$.getUrlVar('id')); //수정시
//                    else $(location).attr('href',bean.link.done+'&id='+result); //등록시
                }else if(result==0){
                    $('body').viewDialogMessage('<p>저장된 값과 입력된 값이 같아 변경된 항목이 없습니다.</p>');
                }else{
                    msg = '';
                    errorId = '';
                    showUpdateError(result,errorId,msg);
                }
            }, "json")
            .done(function() { $("#btn_submit_cmt").removeAttr("disabled"); $('body').hideDialogProgress();})
            .fail(function() { $('body').viewDialogMessage('<p>서버와 연결중 에러가 발생하였습니다.</p><p>잠시 뒤 다시 시도해 보세요.</p>',reload); })
            .always(function() {});
        return false;
    });


    if(bean.common.totalCount>0){
        $('.cmtlists').html($('<table />', {
            'class' : 'cmtlists_basic',
            'html':  writeCmtListBody(bean.cmtdata)
        }));



        $("#pagination").jPaginator({ 
                nbPages:nbCmtPages,
                selectedPage:selectedPage,
                marginPx:5,
                length:nbCmtPageLength, 
                overBtnLeft:'#over_backward',
                overBtnRight:'#over_forward', 
                maxBtnLeft:'#max_backward', 
                maxBtnRight:'#max_forward',
                onPageClicked: function(a,num) {
                    if(selectedPage!=num && num>0){                 
                        $('#pagination').viewLoading('pageloading');
                        var backdata;
                        $.getJSON(getUrl(num), 'json', function (data) {
                            backdata = data;
                        })
                        .done(function() {
                            $('.cmtlists').html($('<table />', {
                                'class' : 'cmtlists_basic',
                                'html':   writeCmtListBody(backdata)
                            }));
                            selectedPage = num;
                        })
                        .fail(function() {
                            $('body').viewDialogMessage('<p>서버와 연결중 에러가 발생하였습니다.</p><p>잠시 뒤 다시 시도해 보세요.</p>');
                        })
                        .always(function() {$('#pageloading').hide();});
                    }
                }
        });

        if(nbCmtPages<=nbCmtPageLength){
            $("#over_backward").addClass('disabled');
            $("#over_forward").addClass('disabled');
            $("#max_backward").addClass('disabled');
            $("#max_forward").addClass('disabled');
        }
        
        
    }
    
   
    
    if(!nbCmtPages || nbCmtPages<2){
        $("#pagination").hide();
    }
});

