var bean = $.parseJSON($.base64.decode(jsonObject));
var selectedPage = 1;
var nbCmtPages = (bean.common.totalPage)?bean.common.totalPage:0;
var nbPageLength = 5;
var pageObj;

//리스트 번호 마지막 번호
function getLastestNum(page){
    return bean.common.totalCount - (bean.common.limitRow*(page-1));
}
function getFirstNum(page){
    return bean.common.limitRow*(page-1) + 1;
}

function getSearchUrl(page){
    var url = bean.link.lists +'&page='+page;
    var sf = $.getUrlVar('sf');
    var sv = $.getUrlVar('sv');
    if(sf && sv){
        url = url + '&sf='+sf + '&sv='+sv;
    }
    return url;
}

function viewLink(id){
    $(location).attr('href',bean.link.view+'&id='+id+'#page:'+selectedPage);
}

function getColCount(){
    var count=0;
    var titleJson = $.parseJSON(listHeader);
    for(var prop in titleJson[0]) {
       if (titleJson[0].hasOwnProperty(prop)) {
          ++count;
       }
    }
    return count;
}

var getPageParam = function(param) {
    var found;
    document.URL.split("#").forEach(function(item) {
        if (param ==  item.split(":")[0]) {
            found = item.split(":")[1];
        }
    });
    return found;
};

function getLists(parampage){
    
//    $('#pagination').viewLoading('pageloading');
    $.getJSON(getSearchUrl(parampage), 'json', function (data) {
        backdata = data;
        selectedPage = parampage;
    })
    .done(function() {
        $('.lists').html($('<table />', {
            'class' : 'tlists_basic',
            'html':  writeListHead($.parseJSON(listHeader)) + writeListBody(backdata)
        }));
        $('#pageloading').hide();

    })
    .fail(function() {
        $('#pageloading').hide();
        $('body').viewDialogMessage('<p>서버와 연결중 에러가 발생하였습니다.</p><p>잠시 뒤 다시 시도해 보세요.</p>');
    })
    .always(function() {
        selectedPage = parampage;
        
//        $('html, body').animate( {scrollTop:0} );
        
        $('#pageloading').hide();
        pageloadset.selectedPage = selectedPage;
        $("#pagination").trigger("selectpage",pageloadset,parampage);
    });
}



var pageloadset ={ 
    nbPages:nbCmtPages,
    selectedPage:selectedPage,
    marginPx:6,
    length:nbPageLength, 
    overBtnLeft:'#over_backward',
    overBtnRight:'#over_forward', 
    maxBtnLeft:'#max_backward', 
    maxBtnRight:'#max_forward',
    onPageClicked: function(a,num) {
        if(selectedPage!=num && num>0){
            $(location).attr('href','#page:'+num);
        }
    }
}

function moveTopageSet(){
    var parampage = getPageParam('page');
    if(document.URL.indexOf('#page:')){
        if(typeof parampage!=='undefined'){
            if(parampage>0){
                getLists(parampage);
            }
        }
    }
}


function weiteExcelDownlod(rowlimit){
    if(rowlimit>1000) rowlimit = 1000;
    var data = '';
    var excelTotalPage = Math.ceil(bean.common.totalCount / rowlimit);
    var sf = $('select[name="sf"] option:selected').val();
    var sv = $('input[name="sv"]').val();
    var svdf = $('input[name="svdf"]').val();
    var svdt = $('input[name="svdt"]').val();
    
    for(var i=0;i<excelTotalPage;i++){
        var expage = i+1;
        var downurl = bean.link.lists.replace("%2Flists","")+'/downloadExcel&page='+expage+'&limit='+rowlimit+'&sf='+sf+'&sv='+sv+'&svdf='+svdf+'&svdt='+svdt;
        data += '<button class="btn-default" onclick="document.location.href=\''+downurl+'\'">#'+expage+'</button>';
    }
    $("#list_download_excel").html(data);
}

window.onhashchange = function() {
    moveTopageSet();
}



function initCalendarSearchFrom(){

    var dateFormat = "yy-mm-dd",
      from = $( "input[name='svdf']" )
        .datepicker({
         defaultDate: "+1w",
         changeMonth: true,
         changeYear: true,
         dateFormat: "yy-mm-dd",
         yearRange: "2015:2050",
         dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], 
         monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
          numberOfMonths: 3
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "input[name='svdt']" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        yearRange: "2015:2050",
        dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], 
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        numberOfMonths: 3
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
}

/*
 * 여긴 un min
 */
$(document).ready(function(){

    $('.lists').html($('<table />', {
        'class' : 'tlists_basic',
        'html':  writeListHead($.parseJSON(listHeader)) + writeListBody(bean.data)
    }));

    pageObj = $("#pagination").jPaginator(pageloadset);

    $('.paginator_p').addClass('ui-corner-all');

    if(nbCmtPages<=nbPageLength){
        $("#over_backward").addClass('disabled');
        $("#over_forward").addClass('disabled');
        $("#max_backward").addClass('disabled');
        $("#max_forward").addClass('disabled');
    }

    if($.getUrlVar('sf') && $.getUrlVar('sv')){
        $('select[name=sf]').val($.getUrlVar('sf'));
        $('input[name=sv]').val($.getUrlVar('sv'));
        $('input[name=btn_list_all]').removeClass("hidden");
    }
    
    if($.getUrlVar('svdf') && $.getUrlVar('svdt')){
        $('input[name=svdf]').val($.getUrlVar('svdf'));
        $('input[name=svdt]').val($.getUrlVar('svdt'));
        $('input[name=btn_list_all]').removeClass("hidden");
    }

    moveTopageSet();
    
    initCalendarSearchFrom();
    
    $("form input[name='mode']").val($.getUrlVar('mode'));
});